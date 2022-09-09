<?php 
/**
  * Plugin Name: Change Quantity on Checkout for WooCommerce
  * Description: This plugin allow you to change the quantity & delete the product on the checkout page for WooCommerce.
  * Version: 2.0
  * Author: Bhavik Kiri
  * Requires PHP: 5.6
  * WC requires at least: 3.0.0
  * WC tested up to: 5.2
  * License: GNU General Public License v3.0
  * License URI: http://www.gnu.org/licenses/gpl-3.0.html
  */
/**
 * Add_Quantity_On_Checkout
 **/
if (!class_exists('Change_Quantity_On_Checkout')) {

	class Change_Quantity_On_Checkout {

		public $plugin_version = '1.0';
		public function __construct() {
            add_filter ('woocommerce_cart_item_name',              array( &$this, 'cqoc_add_items'), 10, 3 );
    	    add_filter ('woocommerce_checkout_cart_item_quantity', array( &$this, 'cqoc_add_quantity'), 10, 2 );
    	    add_action( 'wp_footer',                               array( &$this, 'cqoc_add_js' ), 10 );
     	    add_action( 'init',                                    array( &$this, 'cqoc_load_ajax' ) );
            
        }

        public static function cqoc_add_items( $product_title, $cart_item, $cart_item_key ) {

		    /*
		     * It will add Delete button, Quanitity field of the checkout page Table.
		     */
		    if (  is_checkout() ) {
		        $cart     = WC()->cart->get_cart();
                foreach ( $cart as $cart_key => $cart_value ){
                   if ( $cart_key == $cart_item_key ){
                        $product_id = $cart_item['product_id'];
                        $_product   = $cart_item['data'] ;
                        $return_value = sprintf(
                          '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
                          esc_url( wc_get_cart_remove_url( $cart_key ) ),
                          __( 'Remove this item', 'woocommerce' ),
                          esc_attr( $product_id ),
                          esc_attr( $_product->get_sku() )
                        );
                        $return_value_test = '';
                        $return_value .= '&nbsp; <span class = "cqoc_product_name" >' . $product_title . '</span>' ;
                        if ( $_product->is_sold_individually() ) {
                          $return_value .= sprintf( ' 1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_key );
                        } else {
                          $return_value .= woocommerce_quantity_input( array(
                              'input_name'  => "cart[{$cart_key}][qty]",
                              'input_value' => $cart_item['quantity'],
                              'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(), $product ),
                              'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
                              'pattern'     => '[0-9]*'
                              ), $_product );
                        }
                        return $return_value;
                    }
                }
		    }else{
		        /*
		         * It will return the product name on the cart page.
		         * As the filter used on checkout and cart are same.
		         */
		        $_product   = $cart_item['data'] ;
		        $product_permalink = $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '';
		        if ( ! $product_permalink ) {
		            $return_value = $_product->get_title() . '&nbsp;';
		        } else {
		            $return_value = sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_title());
		        }
		        return $return_value;
		    }
		}

		/*
		 * It will remove the selected quantity count from checkout page table.
		 */
    	public static function cqoc_add_quantity( $cart_item, $cart_item_key ) {
    	   $product_quantity= '';
    	   return $product_quantity;
    	}
    	
    	function cqoc_add_js(){
    	     
            if (  is_checkout() ) {
			
    		$plugin_version = $this->plugin_version;
			wp_enqueue_style( 'cqoc_checkout', plugins_url( '/assets/css/change-quantity-on-checkout.css', __FILE__ ), '', $plugin_version, false );
            ?>  
                <script type="text/javascript">
					<?php  $admin_url = get_admin_url(); ?>
                    jQuery("form.checkout").on("keypress", "input.qty", function( event ){
                        return event.charCode >= 48 && event.charCode <= 57;
                    });
                    
					jQuery("form.checkout").on("change", "input.qty", function( event ){
                        
                        $form = jQuery( 'form.checkout' );
                        if ( $form[0].checkValidity() ){
                            var data = {
                        		action: 'cqoc_update_order_review',
                        		security: wc_checkout_params.update_order_review_nonce,
                        		post_data: jQuery( 'form.checkout' ).serialize()
                        	};
    						
                        	jQuery.post( '<?php echo $admin_url; ?>' + 'admin-ajax.php', data, function( response )
                    		{
                                jQuery( 'body' ).trigger( 'update_checkout' );   
                            });
                        }
                    });
                </script>
             <?php  
             }
        }
        
        function cqoc_load_ajax() {
        
            if ( !is_user_logged_in() ){
                add_action( 'wp_ajax_nopriv_cqoc_update_order_review', array( &$this, 'cqoc_update_order_review' ) );
            } else{
                add_action( 'wp_ajax_cqoc_update_order_review',        array( &$this, 'cqoc_update_order_review' ) );
            }
        
        }
        
        function cqoc_update_order_review() {
             
            $values = array();
            parse_str($_POST['post_data'], $values);
            $cart = $values['cart'];
            foreach ( $cart as $cart_key => $cart_value ){
                WC()->cart->set_quantity( $cart_key, $cart_value['qty'], false );
                WC()->cart->calculate_totals();
            }
            
            wp_die();
        }
	}
}
$change_quantity_on_checkout = new Change_Quantity_On_Checkout();