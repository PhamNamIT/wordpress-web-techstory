<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Single_Product_Countdown
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Single_Product_Countdown {
	protected $settings;
	protected $id;
	protected $index;
	protected $position;
	protected $pg_position;
	protected $product_id;
	protected $sticky_countdown;
	protected $atc_button;

	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
			add_action( 'wp', array( $this, 'init' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ),999999999 );
            /*countdown timer position*/
			add_action( 'woocommerce_before_template_part', array( $this, 'countdown_before_template' ) );
			add_action( 'woocommerce_after_template_part', array( $this, 'countdown_after_template' ) );
			add_action( 'woocommerce_after_add_to_cart_form', array( $this, 'countdown_cart_after' ) );
			add_action( 'woocommerce_product_thumbnails', array( $this, 'woocommerce_product_thumbnails' ), 99, 1 );
			add_action( 'woocommerce_single_product_summary', array( $this, 'woocommerce_single_product_summary', ), 99, 1 );
			add_filter( 'woocommerce_available_variation', array( $this, 'woocommerce_available_variation' ), 10, 3 );
		}
	}

	public function init() {
		if ( is_admin() ) {
			return;
		}
		if ( is_product() && is_single() ) {
			/*single product page*/
			global $post;
			$product_id = $post->ID;
			$product    = wc_get_product( $product_id );
			if ( $product ) {
				if ( ! $product->is_type( 'variable' ) ) {
					add_action( 'woocommerce_before_add_to_cart_form', array( $this, 'countdown_cart_before' ) );
					if ( ! $product->is_in_stock() ) {
						return;
					}
					if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' ) ) {
						return;
					}
					$this->init_countdown( $product_id );
				} else {
					add_action( 'woocommerce_before_single_variation', array( $this, 'countdown_cart_before' ) );
					if ( ! $product->is_in_stock() ) {
						return;
					}
					$variation_id      = get_post_meta( $product_id, '_woo_ctr_display_enable', true );
					$default_attribute = method_exists( $product, 'get_default_attributes' ) ? $product->get_default_attributes() : $product->get_variation_default_attributes();
					if ( ! $variation_id || count( $default_attribute ) ) {
						return;
					}

					$variation = wc_get_product( $variation_id );
					if ( ! $variation || ! $variation->is_in_stock()) {
						return;
					}
					if ( ! $variation->get_date_on_sale_from( 'edit' ) && ! $variation->get_date_on_sale_to( 'edit' ) ) {
						return;
					}
					$this->init_countdown( $variation_id );
				}
			}
		}
	}

	public function init_countdown( $product_id ) {
		$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return;
			}

			$this->product_id       = $product_id;
			$this->id               = $id;
			$this->pg_position      = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position_in_single', $index );
			$this->position         = $this->settings->get_current_countdown( 'sale_countdown_single_product_position', $index );
			$this->sticky_countdown = $this->settings->get_current_countdown( 'sale_countdown_single_product_sticky', $index );
			$this->atc_button       = $this->settings->get_current_countdown( 'sale_countdown_add_to_cart_button', $index );
		}
	}

	public function wp_enqueue_scripts() {
		if ( $this->id && $this->sticky_countdown !== 'none' ) {
			wp_enqueue_style( 'sales-countdown-timer-single-product',
				VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'sales-countdown-timer-single-product.css',
				array(),
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			wp_enqueue_script( 'sales-countdown-timer-single-product',
				VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'sales-countdown-timer-single-product.js',
				array( 'jquery' ),
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );

			$t = array(
				'ajax_url'    => admin_url( 'admin-ajax.php' ),
				'sticky_type' => $this->sticky_countdown,
			);
			if ( $this->atc_button ) {
				ob_start();
				echo do_shortcode( '[add_to_cart id="' . $this->product_id . '" show_price="false" style="transform: scale(0.75)" class="woo-sctr-add-to-cart-button"] ' );
				$atc_button_html = ob_get_clean();
				$t['atc_button'] = $atc_button_html;
			}
			wp_localize_script(
				'sales-countdown-timer-single-product',
				'woo_sctr_single_product_countdown',
				$t
			);
		}
	}

	public function countdown_before_template( $template_name ) {
		if ( ! $this->id ) {
			return;
		}
		switch ( $template_name ) {
			case 'single-product/sale-flash.php':
				if ( $this->position === 'before_saleflash' ) {
					$shortcode = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $this->product_id . '" progress_bar_enable="1" progress_bar_position ="' . $this->pg_position . '" countdown_enable="1" countdown_id = "' . $this->id . '"]' );
					echo '<div class="woo-sctr-single-product-container woo-sctr-single-product-shortcode-' . $this->id . ( in_array( $this->pg_position, array(
							'left_countdown',
							'right_countdown'
						) ) ? ' woo-sctr-single-product-inline-container ' : '' ) . '">' . $shortcode . '</div>';
				}
				break;
			case 'single-product/price.php':
				if ( $this->position === 'before_price' ) {
					$shortcode = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $this->product_id . '" progress_bar_enable="1" progress_bar_position ="' . $this->pg_position . '" countdown_enable="1"  countdown_id = "' . $this->id . '"]' );
					echo '<div class="woo-sctr-single-product-container woo-sctr-single-product-shortcode-' . $this->id . ( in_array( $this->pg_position, array(
							'left_countdown',
							'right_countdown'
						) ) ? ' woo-sctr-single-product-inline-container' : '' ) . '">' . $shortcode . '</div>';

				}
				break;
			default:
				return;
		}
	}

	public function countdown_after_template( $template_name ) {
		if ( ! $this->id ) {
			return;
		}
		switch ( $template_name ) {
			case 'single-product/sale-flash.php':
				if ( $this->position == 'after_saleflash' ) {
					$shortcode = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $this->product_id . '" progress_bar_enable="1" progress_bar_position ="' . $this->pg_position . '" countdown_enable="1" countdown_id = "' . $this->id . '"]' );
					echo '<div class="woo-sctr-single-product-container woo-sctr-single-product-shortcode-' . $this->id . ( in_array( $this->pg_position, array(
							'left_countdown',
							'right_countdown'
						) ) ? ' woo-sctr-single-product-inline-container' : '' ) . '">' . $shortcode . '</div>';
				}
				break;
			case 'single-product/price.php':
				if ( $this->position == 'after_price' ) {
					$shortcode = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $this->product_id . '" progress_bar_enable="1" progress_bar_position ="' . $this->pg_position . '" countdown_enable="1" countdown_id = "' . $this->id . '"]' );
					echo '<div class="woo-sctr-single-product-container woo-sctr-single-product-shortcode-' . $this->id . ( in_array( $this->pg_position, array(
							'left_countdown',
							'right_countdown'
						) ) ? ' woo-sctr-single-product-inline-container' : '' ) . '">' . $shortcode . '</div>';
				}
				break;
			default:
				return;
		}
	}

	public function countdown_cart_before() {
		if ( ! $this->id ) {
			return;
		}
		if ( $this->position == 'before_cart' ) {
			$shortcode = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $this->product_id . '" progress_bar_enable="1" progress_bar_position ="' . $this->pg_position . '" countdown_enable="1" countdown_id = "' . $this->id . '"]' );
			echo '<div class="woo-sctr-single-product-container woo-sctr-single-product-shortcode-' . $this->id . ( in_array( $this->pg_position, array(
					'left_countdown',
					'right_countdown'
				) ) ? ' woo-sctr-single-product-inline-container' : '' ) . '">' . $shortcode . '</div>';
		}
	}

	public function countdown_cart_after() {
		if ( ! $this->id ) {
			return;
		}
		if ( $this->position == 'after_cart' ) {
			$shortcode = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $this->product_id . '" progress_bar_enable="1" progress_bar_position ="' . $this->pg_position . '" countdown_enable="1"   countdown_id = "' . $this->id . '"]' );
			echo '<div class="woo-sctr-single-product-container woo-sctr-single-product-shortcode-' . $this->id . ( in_array( $this->pg_position, array(
					'left_countdown',
					'right_countdown'
				) ) ? ' woo-sctr-single-product-inline-container' : '' ) . '">' . $shortcode . '</div>';
		}
	}


	public function woocommerce_product_thumbnails() {
		if ( ! is_product() || ! is_single() ) {
			return;
		}

		if ( $this->id && $this->position == 'product_image' ) {
			ob_start();
		}
	}

	public function woocommerce_single_product_summary() {
		if ( is_admin() ) {
			return;
		}
		if ( ! is_product() || ! is_single() ) {
			return;
		}
		if ( $this->id && $this->position == 'product_image' ) {
			$html = ob_get_clean();
			$html = str_replace( "\n", '', $html );
			$html = str_replace( "\r", '', $html );
			$html = str_replace( "\t", '', $html );
			$html = str_replace( "\l", '', $html );
			$html = str_replace( "\0", '', $html );

			$shortcode = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $this->product_id . '" progress_bar_enable="1" progress_bar_position ="' . $this->pg_position . '" countdown_enable="1"  countdown_id = "' . $this->id . '"]' );

			$html = str_replace( '</figure>',
				'</figure><div class="woo-sctr-single-product-container"><div class="woo-sctr-countdown-timer-product-image-wrap woo-sctr-single-product-shortcode-' . $this->id . ( in_array( $this->pg_position, array(
					'left_countdown',
					'right_countdown'
				) ) ? ' woo-sctr-single-product-inline-container' : '' ) . '">' . $shortcode . '</div></div>',
				$html );
			echo $html;
		}

	}

	public function woocommerce_available_variation( $variation_data, $parent, $variation ) {
		$wc_ajax = $_REQUEST['wc-ajax'] ??'';
		if (in_array($wc_ajax,['viwcaio_show_variation'])){
			return $variation_data;
		}
		if (!apply_filters('sctv_get_countdown_on_available_variation', true)){
			return $variation_data;
		}
		$variation_id = $variation->get_id();
		if ( ! $variation->is_in_stock() || ! $variation->get_sale_price( 'edit' ) ) {
			return $variation_data;
		}
		if ( ! $variation->get_date_on_sale_from( 'edit' ) && ! $variation->get_date_on_sale_to( 'edit' ) ) {
			return $variation_data;
		}
		$id = get_post_meta( $variation_id, '_woo_ctr_select_countdown_timer', true );
		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return $variation_data;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return $variation_data;
			}
			$pg_position                             = $this->settings->get_params( 'sale_countdown_progress_bar_position_in_single' )[ $index ];
			$shortcode                               = do_shortcode( '[sctv_product_countdown_timer is_single ="1" product_id="' . $variation_id . '" progress_bar_enable="1" progress_bar_position ="' . $pg_position . '" countdown_enable="1" countdown_id = "' . $id . '" sale_countdown_timer_id_t="' . current_time( 'timestamp' ) . '"]' );
			$variation_data['variation_description'] .= '<div class="woo-sctr-variation-product-container woo-sctr-single-product-shortcode-' . $id . ( in_array( $pg_position, array(
					'left_countdown',
					'right_countdown'
				) ) ? ' woo-sctr-single-product-inline-container' : '' ) . '">' . $shortcode . '</div>';
		}

		return $variation_data;
	}
}