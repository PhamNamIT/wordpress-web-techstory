<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Product_Countdown
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Product_Countdown {
	protected $settings;
	protected $id;
	protected $index;
	protected $position;

	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
			add_action( 'init', array( $this, 'shortcode_init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'sctv_reset_schedule' ));
			add_filter( 'woocommerce_post_class', array( $this, 'sctv_woocommerce_post_class' ), PHP_INT_MAX, 2);
			add_filter( 'woocommerce_sale_flash', array( $this, 'sctv_woocommerce_sale_flash' ), PHP_INT_MAX, 3);
			add_filter( 'woocommerce_get_price_html', array( $this, 'sctv_woocommerce_get_price_html' ), PHP_INT_MAX, 2 );
			add_action( 'woocommerce_new_order_item', array( $this, 'woocommerce_new_order_item' ), 10, 3 );
		}
	}

	public function shortcode_init() {
		add_shortcode( 'sctv_product_countdown_timer', array( $this, 'register_shortcode' ) );
	}

	public function sctv_reset_schedule() {
		if ( get_transient( '_sales_countdown_timer_set_schedule' ) ) {
			$product_ids_on_sale = wc_get_product_ids_on_sale();
			foreach ( $product_ids_on_sale as $product_id ) {
				$product = wc_get_product( $product_id );
				if ( ! $product ) {
					continue;
				}
				if ( ! $product->get_sale_price( 'edit' ) ) {
					continue;
				}
				if ( get_post_meta( $product_id, '_sale_price_times_to', true ) ) {
					if ( $product->get_date_on_sale_from( 'edit' ) && $product->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
						if ( current_time( 'timestamp',
								true ) < $product->get_date_on_sale_from( 'edit' )->getTimestamp() ) {
							update_post_meta( $product_id, '_price', $product->get_regular_price( 'edit' ) );
							$product->set_price( $product->get_regular_price( 'edit' ) );
							wp_schedule_single_event( $product->get_date_on_sale_from( 'edit' )->getTimestamp(),
								'sctv_schedule_sale_product',
								array( $product_id ) );
						} else {
							update_post_meta( $product_id, '_price', $product->get_sale_price( 'edit' ) );
							$product->set_price( $product->get_sale_price( 'edit' ) );
						}
					}
					if ( $product->get_date_on_sale_to( 'edit' ) && $product->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
						if ( current_time( 'timestamp',
								true ) < $product->get_date_on_sale_to( 'edit' )->getTimestamp() ) {
							wp_schedule_single_event( $product->get_date_on_sale_to( 'edit' )->getTimestamp(),
								'sctv_schedule_remove_sale_product',
								array( $product_id ) );
						} else {
							$regular_price = $product->get_regular_price();
							$product->set_price( $regular_price );
							$product->set_sale_price( '' );
							$product->set_date_on_sale_to( '' );
							$product->set_date_on_sale_from( '' );
							$product->save();
							delete_post_meta( $product_id, '_sale_price_times_from' );
							delete_post_meta( $product_id, '_sale_price_times_to' );
							delete_post_meta( $product_id, '_woo_ctr_product_sold_quantity' );
						}
					}

				}

			}
			delete_transient( '_sales_countdown_timer_set_schedule' );
		}
	}

	public function woocommerce_new_order_item( $item_id, $item, $order_id ) {
		if ( ! WC()->session ) {
			return;
		}
		if ( WC()->session->get( 'sctv_checkout_countdown_info' ) ) {
			$countdown_info = WC()->session->get( 'sctv_checkout_countdown_info' );
			update_post_meta( $order_id, 'sctv_checkout_countdown_info', $countdown_info );
			update_post_meta( $order_id, 'sctv_checkout_countdown_info', $countdown_info );
			WC()->session->__unset( 'sctv_checkout_countdown_info' );
		}
		$product_id   = wc_get_order_item_meta( $item_id, '_product_id', true );
		$variation_id = wc_get_order_item_meta( $item_id, '_variation_id', true );
		$product_id   = $variation_id ? $variation_id : $product_id;
		$product      = wc_get_product( $product_id );
		$pg_enable    = get_post_meta( $product_id, '_woo_ctr_enable_progress_bar', true );
		$countdown_id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		if ( $product && $product->is_on_sale() && $pg_enable && $countdown_id ) {
			$index = array_search( $countdown_id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index >= 0 ) {
				$data   = get_post_meta( $product_id,
					'_woo_ctr_product_sold_quantity',
					true ) ? ( get_post_meta( $product_id, '_woo_ctr_product_sold_quantity', true ) ) : array();
				$data[] = array( 'id' => $order_id, 'quantity' => wc_get_order_item_meta( $item_id, '_qty', true ) );
				update_post_meta( $product_id, '_woo_ctr_product_sold_quantity', $data );
				$this->check_over_goal( $product_id, $data, $index );
			}
		}
	}

	public function check_over_goal( $product_id, $data, $index ) {
		$goal                       = get_post_meta( $product_id, '_woo_ctr_progress_bar_goal', true );
		$initial                    = get_post_meta( $product_id, '_woo_ctr_progress_bar_initial', true );
		$order_status               = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_order_status',
			$index ) ? explode( ',',
			$this->settings->get_current_countdown( 'sale_countdown_progress_bar_order_status', $index ) ) : array();
		$progress_bar_real_quantity = 0;
		if ( is_array( $order_status ) && empty( $order_status ) ) {
			$order_status = array_keys( wc_get_order_statuses() );
		}
		if ( is_array( $data ) && count( $data ) && is_array( $order_status ) && count( $order_status ) ) {
			foreach ( $data as $key => $value ) {
				$order = get_post( $value['id'] );
				if ( $order && in_array( $order->post_status, $order_status ) ) {
					$progress_bar_real_quantity += $value['quantity'];
				}
			}
		}
		$progress_bar_real_quantity += $initial;
		if ( $progress_bar_real_quantity >= $goal ) {
			$action = get_post_meta( $product_id, '_woo_ctr_action_for_over_goal', true );
			if ( $action === 'none' ) {
				return;
			}
			$product = wc_get_product( $product_id );
			switch ( $action ) {
				case 'set_private':
					$product->set_status( 'private' );
					$product->save();
					break;
				case 'set_out_of_stock':
					$product->set_stock_status( 'outofstock' );
					$product->save();
					break;
			}
			wp_clear_scheduled_hook( 'sctv_schedule_sale_product', array( $product_id ) );
			wp_clear_scheduled_hook( 'sctv_schedule_remove_sale_product', array( $product_id ) );
			$regular_price = $product->get_regular_price( 'edit' );
			$product->set_price( $regular_price );
			delete_post_meta( $product_id, '_sale_price' );
			delete_post_meta( $product_id, '_sale_price_dates_to' );
			delete_post_meta( $product_id, '_sale_price_dates_from' );
			delete_post_meta( $product_id, '_sale_price_times_from' );
			delete_post_meta( $product_id, '_sale_price_times_to' );
			delete_post_meta( $product_id, '_woo_ctr_product_sold_quantity' );
			update_post_meta( $product_id, '_price', $regular_price );
		}
	}
	public function sctv_woocommerce_post_class($class,$product){
		$class[] = 'vi-sctv-product';
	    return $class;
    }
	public function sctv_woocommerce_get_price_html($html,$product){
		$html = '<span class="vi-sctv-price">'.$html.'</span>';
	    return $html;
    }
	public function sctv_woocommerce_sale_flash($html, $post, $product){
		$html = str_replace('class="', 'class="vi-sctv-sale-badge ', $html);
	    return $html;
    }

	public function register_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'product_id'                 => 0,
			'countdown_enable'           => '1',
			'countdown_id'               => 'salescountdowntimer',
			'progress_bar_enable'        => '1',
			'progress_bar_position'      => 'above_countdown',
			'resize_archive_page_enable' => '',
			'sale_countdown_timer_id_t'  => '',
			'is_single'                  => '',
		),
			$atts ) );
		if ( ! $product_id ) {
			return false;
		}
		$product = wc_get_product( $product_id );
		if ( ! $product ) {
			return false;
		}
		if ( ! $countdown_id ) {
			$countdown_id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		}
		if ( $countdown_id === '' ) {
			return false;
		}
		$index = array_search( $countdown_id, $this->settings->get_params( 'sale_countdown_id' ) );
		if ( $index === false ) {
			return false;
		}
		if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
			return false;
		}
		$countdown_html = '';
		$offset         = get_option( 'gmt_offset' );
		$sale_from      = ( $product->get_date_on_sale_from( 'edit' ) ) ? ( $product->get_date_on_sale_from( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
		$sale_to        = ( $product->get_date_on_sale_to( 'edit' ) ) ? ( $product->get_date_on_sale_to( 'edit' )->getTimestamp() + $offset * 3600 ) : 0;
		if (get_post_meta($product_id,'_woo_ctr_enable_loop_countdown',true)){
		    $countdown_time_reset = get_post_meta($product_id,'_woo_ctr_loop_countdown_val',true) ?: 0;
		    if ($countdown_time_reset){
			    $countdown_time_reset  = $this->get_loop_time_val( $countdown_time_reset, get_post_meta($product_id,'_woo_ctr_loop_countdown_type',true) );
            }
        }
		if ( $countdown_enable ) {
			$shortcode_atts = array(
				'type'                       => 'product',
				'product_id'                 => $product_id,
				'id'                         => $countdown_id,
				'sale_from'             => $sale_from,
				'sale_to'               => $sale_to,
				'resize_archive_page_enable' => $resize_archive_page_enable,
				'sale_countdown_timer_id_t'  => $sale_countdown_timer_id_t,
				'countdown_time_reset'  => $countdown_time_reset ?? '',
			);
			$shortcode      = '[sales_countdown_timer ';
			foreach ( $shortcode_atts as $key => $value ) {
				$shortcode .= $key . '="' . $value . '" ';
			}
			$shortcode      .= ']';
			$countdown_html = do_shortcode( $shortcode );
		}
		$progress_bar_html = '';
		$now               = current_time( 'timestamp' );
		if ( $progress_bar_enable && $now < $sale_to ) {
			$pg_upcoming_enable = $this->settings->get_current_countdown( 'sale_countdown_upcoming_progress_bar_enable',
				$index );
			if ( ! $pg_upcoming_enable && $sale_from > $now ) {
				$progress_bar_html = '';
			} else {
				$data                       = get_post_meta( $product_id,
					'_woo_ctr_product_sold_quantity',
					true ) ? ( get_post_meta( $product_id, '_woo_ctr_product_sold_quantity', true ) ) : array();
				$order_status               = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_order_status',
					$index ) ? explode( ',',
					$this->settings->get_current_countdown( 'sale_countdown_progress_bar_order_status',
						$index ) ) : array();
				$progress_bar_message       = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_message',
					$index );
				$progress_bar_real_quantity = 0;
				if ( is_array( $order_status ) && empty( $order_status ) ) {
					$order_status = array_keys( wc_get_order_statuses() );
				}
				if ( is_array( $data ) && count( $data ) && is_array( $order_status ) && count( $order_status ) ) {
					foreach ( $data as $key => $value ) {
						$order = get_post( $value['id'] );
						if ( $order && in_array( $order->post_status, $order_status ) ) {
							$progress_bar_real_quantity += $value['quantity'];
						}
					}
				}
				$progress_bar_html = self::get_progress_bar_html( $product_id,
					$countdown_id,
					$index,
					$progress_bar_real_quantity,
					$progress_bar_message,
					$this->settings,
					$is_single );
			}
		}
		ob_start();
		if ( in_array( $progress_bar_position, array( 'above_countdown', 'left_countdown' ) ) ) {
			echo $progress_bar_html . $countdown_html;
		} else {
			echo $countdown_html . $progress_bar_html;
		}
		$html = ob_get_clean();

		return $html;
	}
	private function get_loop_time_val( $loop, $type ) {
		switch ( $type ) {
			case 'day':
				$result = $loop * 86400;
				break;
			case 'hour':
				$result = $loop * 3600;
				break;
			default:
				$result = $loop * 60;
		}
		return $result;
	}

	public static function get_progress_bar_html(
		$product_id,
		$countdown_id,
		$index,
		$progress_bar_real_quantity,
		$progress_bar_message,
		$settings,
		$is_single = false
	) {
		$progress_bar         = get_post_meta( $product_id, '_woo_ctr_enable_progress_bar', true );
		$progress_bar_goal    = get_post_meta( $product_id, '_woo_ctr_progress_bar_goal', true );
		$progress_bar_initial = get_post_meta( $product_id,
			'_woo_ctr_progress_bar_initial',
			true ) ? get_post_meta( $product_id, '_woo_ctr_progress_bar_initial', true ) : 0;

		$progress_bar_type             = $settings->get_current_countdown( 'sale_countdown_progress_bar_type', $index );
		$progress_bar_message_position = $is_single ? $settings->get_current_countdown( 'sale_countdown_progress_bar_message_position_in_single', $index ) : $settings->get_current_countdown( 'sale_countdown_progress_bar_message_position',
			$index );
		$progress_bar_html             = '';
		if ( $progress_bar_real_quantity >= 0 && $progress_bar && $progress_bar_goal ) {
			$progress_bar_real_quantity += $progress_bar_initial;
			$quantity_sold              = $progress_bar_real_quantity;
			$quantity_left              = (int) ( $progress_bar_goal - $progress_bar_real_quantity );
			$percentage_sold            = (int) ( 100 * ( $progress_bar_real_quantity / $progress_bar_goal ) );
			$percentage_left            = 100 - $percentage_sold;
			if ( $progress_bar_real_quantity >= $progress_bar_goal ) {
				$progress_bar_real_quantity = $progress_bar_goal;
			}
			$progress_bar_fill = 100 * ( $progress_bar_real_quantity / $progress_bar_goal );
			if ( $progress_bar_type == 'decrease' ) {
				$progress_bar_fill = 100 - $progress_bar_fill;
			}
			if ( $progress_bar_fill < 0 ) {
				$progress_bar_fill = 0;
			} elseif ( $progress_bar_fill > 100 ) {
				$progress_bar_fill = 100;
			}

			$progress_bar_message = str_replace( '{quantity_left}', $quantity_left, $progress_bar_message );
			$progress_bar_message = str_replace( '{quantity_sold}', $quantity_sold, $progress_bar_message );
			$progress_bar_message = str_replace( '{percentage_sold}', $percentage_sold, $progress_bar_message );
			$progress_bar_message = str_replace( '{percentage_left}', $percentage_left, $progress_bar_message );
			$progress_bar_message = str_replace( '{goal}', $progress_bar_goal, $progress_bar_message );

			$progress_bar_class = array(
				'woo-sctr-progress-bar-wrap-container',
				'woo-sctr-progress-bar-wrap-container-shortcode-' . $countdown_id
			);
			if ( in_array( $progress_bar_message_position, array( 'left_progressbar', 'right_progressbar' ) ) ) {
				$progress_bar_class[] = 'woo-sctr-progress-bar-wrap-inline';
			}
			$progress_bar_class = trim( implode( ' ', $progress_bar_class ) );
			ob_start();
			?>
            <div class="<?php esc_attr_e( $progress_bar_class ); ?>">
				<?php
				if ( in_array( $progress_bar_message_position, array( 'above_progressbar', 'left_progressbar' ) ) ) {
					?>
                    <div class="woo-sctr-progress-bar-message"><?php echo $progress_bar_message; ?></div>
					<?php
				}
				?>
                <div class="woo-sctr-progress-bar-wrap">
                    <div class="woo-sctr-progress-bar-fill" data-width="<?php esc_attr_e( $progress_bar_fill ); ?>">
                    </div>
					<?php
					if ( $progress_bar_message_position === 'in_progressbar' ) {
						?>
                        <div class="woo-sctr-progress-bar-message"><?php echo $progress_bar_message; ?></div>
						<?php
					}
					?>
                </div>
				<?php
				if ( in_array( $progress_bar_message_position, array( 'below_progressbar', 'right_progressbar' ) ) ) {
					?>
                    <div class="woo-sctr-progress-bar-message"><?php echo $progress_bar_message; ?></div>
					<?php
				}
				?>
            </div>
			<?php
			$progress_bar_html = ob_get_clean();
		}

		return $progress_bar_html;
	}

}
