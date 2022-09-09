<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Checkout_Countdown
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Checkout_Countdown {
	protected $settings;

	public function __construct() {
		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $this->settings->get_params( 'checkout_countdown_enable' ) ) {
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'woocommerce_after_checkout_validation', array( __CLASS__, 'checkout_validation' ), 10, 2 );

			add_action( 'woocommerce_cart_calculate_fees', array( __CLASS__, 'set_discount_checkout' ), 99, 1 );

//            add_filter('woocommerce_cart_item_name', array(__CLASS__, 'append_percentage_to_item_name'), 20, 3);
//
//            add_filter('woocommerce_add_cart_item_data', array(__CLASS__, 'set_cart_item_price'), 10, 3);
//            add_action('woocommerce_before_calculate_totals', array(__CLASS__, 'discounted_cart_item_price'), 10, 1);

//            add_action('woocommerce_cart_updated', array(__CLASS__, 'woocommerce_cart_updated'), 10, 0);

			add_action( 'woocommerce_cart_loaded_from_session',
				array(
					__CLASS__,
					'woocommerce_before_mini_cart',
				),
				99 );

		}

	}

	public function init() {
		if ( is_admin() || ! isset( WC()->session ) ) {
			return;
		}
		if ( $this->settings->get_params( 'checkout_test_mode_enable' ) ) {
			if ( ! is_user_logged_in() ) {
				return;
			}
			$current_user = wp_get_current_user();
			if ( ! in_array( 'administrator', $current_user->roles ) ) {
				return;
			}
		}
		$session_countdown_enable          = WC()->session->get( 'sctv_checkout_countdown_enable' );
		$session_countdown_check           = WC()->session->get( 'sctv_checkout_countdown_check' );
		$session_countdown_time            = WC()->session->get( 'sctv_checkout_countdown_time' );
		$session_countdown_details_default = WC()->session->get( 'sctv_checkout_countdown_details_default' );
		$session_countdown_details         = WC()->session->get( 'sctv_checkout_countdown_details' );
		$time_reset                        = (int) $this->settings->get_params( 'checkout_countdown_reset' ) * 86400;
		$now                               = (int) current_time( 'timestamp' );
		if ( ! $session_countdown_enable ) {
			WC()->session->set( 'sctv_checkout_countdown_enable', 'yes' );
			$session_countdown_enable = 'yes';
		} else {
			if ( $session_countdown_enable === 'no' ) {
				return;
			}
		}
		if ( $session_countdown_check === 'no' ) {
			if ( $session_countdown_time && is_array( $session_countdown_time ) && count( $session_countdown_time ) >= 2 ) {
				$session_countdown_start = isset( $session_countdown_time['start'] ) ? (int) $session_countdown_time['start'] : 0;
				$session_countdown_end   = isset( $session_countdown_time['end'] ) ? (int) $session_countdown_time['end'] : 0;
				if ( $session_countdown_start && $session_countdown_end && ( $session_countdown_end + $time_reset ) < $now ) {
					WC()->session->__unset( 'sctv_checkout_countdown_time' );
					WC()->session->__unset( 'sctv_checkout_countdown_details' );
					WC()->session->__unset( 'sctv_checkout_countdown_details_before' );
					WC()->session->__unset( 'sctv_checkout_countdown_details_default' );
					WC()->session->__unset( 'sctv_checkout_countdown_check' );
					$session_countdown_time = $session_countdown_details_default = $session_countdown_details = $session_countdown_check = '';
				} else {
					return;
				}
			} else {
				return;
			}
		}
		self::enqueue_action();
		if ( ! $session_countdown_details_default ) {
			$countdown_details_default = self::set_countdown_details_default();
			if ( ! $countdown_details_default ) {
				return;
			}
			WC()->session->set( 'sctv_checkout_countdown_details_default', $countdown_details_default );
		}
		if ( $session_countdown_time && is_array( $session_countdown_time ) && count( $session_countdown_time ) >= 2 ) {
			$session_countdown_start = isset( $session_countdown_time['start'] ) ? (int) $session_countdown_time['start'] : 0;
			$session_countdown_end   = isset( $session_countdown_time['end'] ) ? (int) $session_countdown_time['end'] : 0;
			if ( $session_countdown_start && $session_countdown_end && $session_countdown_end > $now ) {
				WC()->session->set( 'sctv_checkout_countdown_check', 'yes' );
				$session_countdown_check = 'yes';
				if ( $session_countdown_details ) {
					switch ( $session_countdown_details_default['change_type'] ) {
						case 'auto_change':
							$time_done = $now - $session_countdown_start;
							$time_loop = $session_countdown_details_default['auto_change_time_type'] === 'minute' ? (int) $session_countdown_details_default['auto_change_time'] * 60 : (int) $session_countdown_details_default['auto_change_time'];
							$loop      = $time_loop > 0 ? (int) floor( $time_done / $time_loop ) : 0;
							if ( $loop > 0 ) {
								$loop_stop       = false;
								$t               = wc_format_decimal( 0, '' );
								$discount_amount = $session_countdown_details_default['discount_amount'];
								for ( $i = 1; $i <= $loop; $i ++ ) {
									$discount_amount = $discount_amount - $discount_amount * $session_countdown_details_default['auto_change_discount_amount'] / 100;
									$discount_amount = wc_format_decimal( $discount_amount, '' );
									if ( $discount_amount === $t ) {
										$loop_stop = true;
										break;
									}
								}
								$session_countdown_details['discount_amount'] = $discount_amount;
								WC()->session->set( 'sctv_checkout_countdown_details', $session_countdown_details );
								if ( $loop_stop ) {
									$session_countdown_details_default['change_type'] = 'none';
									WC()->session->set( 'sctv_checkout_countdown_details_default',
										$session_countdown_details_default );
								}
							}
							break;
						case 'custom':
							$time_expire = $session_countdown_end - $now;
							if ( $custom_length = count( $session_countdown_details_default['custom_minutes'] ) ) {
								if ( $custom_length > 1 ) {
									for ( $i = $custom_length - 1; $i >= 0; $i -- ) {
										$time_expire_next = (int) $session_countdown_details_default['custom_minutes'][ $i ] * 60 + (int) $session_countdown_details_default['custom_seconds'][ $i ];

										if ( $time_expire <= $time_expire_next ) {
											$index_current = $i;
											break;
										}
									}
								} else {
									$time_expire_next = (int) $session_countdown_details_default['custom_minutes'][0] * 60 + (int) $session_countdown_details_default['custom_seconds'][0];
									if ( $time_expire <= $time_expire_next ) {
										$index_current = 0;
									}
								}
							}

							if ( isset( $index_current ) && $index_current >= 0 ) {
								$session_countdown_details['message_pg']      = isset( $session_countdown_details_default['custom_message_pg'][ $index_current ] ) ? $session_countdown_details_default['custom_message_pg'][ $index_current ] : '';
								$session_countdown_details['message_og']      = isset( $session_countdown_details_default['custom_message_og'][ $index_current ] ) ? $session_countdown_details_default['custom_message_og'][ $index_current ] : '';
								$session_countdown_details['free_shipping']   = isset( $session_countdown_details_default['custom_free_ship'][ $index_current ] ) ? $session_countdown_details_default['custom_free_ship'][ $index_current ] : '';
								$session_countdown_details['free_product']    = isset( $session_countdown_details_default['custom_free_product'][ $index_current ] ) ? $session_countdown_details_default['custom_free_product'][ $index_current ] : '';
								$session_countdown_details['discount_amount'] = isset( $session_countdown_details_default['custom_discount_amount'][ $index_current ] ) ? $session_countdown_details_default['custom_discount_amount'][ $index_current ] : 0;
								WC()->session->set( 'sctv_checkout_countdown_details', $session_countdown_details );
							}
							break;
						default:
					}
				}
			} else {
				$session_countdown_check = $this->stop_session_countdown_check();
			}
		}
		$cart_subtotal_start = isset( $session_countdown_details_default['cart_subtotal_start'] ) ? (float) $session_countdown_details_default['cart_subtotal_start'] : 0;
//        if ($session_countdown_check === 'yes' && $cart_subtotal_start > 0) {
//            if ($session_countdown_details) {
//                $t = (float)$session_countdown_details['discount_amount'];
//                $discount_type_t = $session_countdown_details['discount_type'];
//            } else {
//                $t = (float)$session_countdown_details_default['discount_amount'];
//                $discount_type_t = $session_countdown_details_default['discount_type'];
//            }
//            if ($discount_type_t === 'percent') {
//                $cart_subtotal_start = $t< 100? $cart_subtotal_start * (100 - $t) / 100: $cart_subtotal_start;
//            } elseif($discount_type_t === 'fixed') {
//                $cart_subtotal_start =$t < $cart_subtotal_start ? $cart_subtotal_start - $t: $cart_subtotal_start;
//            }
//        }
        $cart_subtotal_start=apply_filters('wmc_change_3rd_plugin_price',$cart_subtotal_start);
		if ( $session_countdown_details_default && (float) WC()->session->cart_totals['cart_contents_total'] < $cart_subtotal_start ) {
			if ( $session_countdown_details ) {
				WC()->session->set( 'sctv_checkout_countdown_details_before', $session_countdown_details );
				WC()->session->__unset( 'sctv_checkout_countdown_details' );

				return;
			} elseif ( $session_countdown_details_default['action_start'] !== 'add_to_cart' ) {
				return;
			}
		}
		if ( ( $session_countdown_check !== 'no' ) && $session_countdown_enable === 'yes' ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'checkout_countdown_enqueue_script' ) );
		} else {
			WC()->session->__unset( 'sctv_checkout_countdown_details' );
		}
	}

	public function checkout_countdown_enqueue_script() {
		if ( ! wp_script_is( 'woo-sctr-shortcode-checkout-countdown-style', 'registered' ) ) {
			if ( WP_DEBUG ) {
				wp_enqueue_style( 'woo-sctr-shortcode-checkout-countdown-style',
					VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-checkout-countdown.css',
					array(),
					VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			} else {
				wp_enqueue_style( 'woo-sctr-shortcode-checkout-countdown-style',
					VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-checkout-countdown.min.css',
					array(),
					VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			}
		}

		$session_countdown_check           = WC()->session->get( 'sctv_checkout_countdown_check' );
		$session_countdown_time            = WC()->session->get( 'sctv_checkout_countdown_time' );
		$session_countdown_details_default = WC()->session->get( 'sctv_checkout_countdown_details_default' );
		$checkout_countdown_details        = WC()->session->get( 'sctv_checkout_countdown_details' );

		$cart_subtotal_start = isset( $session_countdown_details_default['cart_subtotal_start'] ) ? (float) $session_countdown_details_default['cart_subtotal_start'] : 0;
		$action_start        = isset( $session_countdown_details_default['action_start'] ) ? $session_countdown_details_default['action_start'] : 'to_checkout_page';
		$check_continue      = true;
		switch ( $action_start ) {
			case 'to_checkout_page':
				if ( ! is_checkout() && ! $session_countdown_check ) {
					$check_continue = false;
				}
				break;
			case 'to_cart_page':
				if ( ! is_cart() && ! $session_countdown_check ) {
					$check_continue = false;
				}
				break;
			case 'add_to_cart':
				if ( $session_countdown_check === 'yes' && $cart_subtotal_start > 0 ) {
					$t               = (float) $session_countdown_details_default['discount_amount'];
					$discount_type_t = $session_countdown_details_default['discount_type'];
					if ( $discount_type_t === 'percent' ) {
						$cart_subtotal_start = $t < 100 ? $cart_subtotal_start * ( 100 - $t ) / 100 : $cart_subtotal_start;
					} elseif ( $discount_type_t === 'fixed' ) {
						$cart_subtotal_start = $t < $cart_subtotal_start ? $cart_subtotal_start - $t : $cart_subtotal_start;
					}
				}
				if ( $session_countdown_details_default && (float) WC()->cart->get_cart_contents_total() < $cart_subtotal_start ) {
					$check_continue = false;
				}
				break;
		}

		if ( ! $check_continue || (float) WC()->cart->get_cart_contents_total() < $cart_subtotal_start ) {
			return;
		}
		if ( ! $session_countdown_check ) {
			WC()->session->set( 'sctv_checkout_countdown_check', 'yes' );
			$session_countdown_check = 'yes';
		}
		if ( ! $session_countdown_time ) {
			$now         = current_time( 'timestamp' );
			$time_expire = isset( $session_countdown_details_default['time_expire'] ) ? (int) $session_countdown_details_default['time_expire'] : 0;
			if ( $time_expire === 0 ) {
				return;
			}
			$session_countdown_time = array( 'start' => $now, 'end' => $now + $time_expire );
			WC()->session->set( 'sctv_checkout_countdown_time', $session_countdown_time );
		}

		if ( $session_countdown_check === 'yes' ) {
			if ( ! $checkout_countdown_details ) {
				$checkout_countdown_details = array(
					'free_shipping'   => $session_countdown_details_default['free_ship'],
					'free_product'    => $session_countdown_details_default['free_product'],
					'free_product_id' => isset( $session_countdown_details_default['free_product_id'] ) ? $session_countdown_details_default['free_product_id'] : '',
					'free_product_qt' => isset( $session_countdown_details_default['free_product_qt'] ) ? $session_countdown_details_default['free_product_qt'] : '',
					'discount_type'   => $session_countdown_details_default['discount_type'],
					'discount_amount' => $session_countdown_details_default['discount_amount'],
				);
				WC()->session->set( 'sctv_checkout_countdown_details', $checkout_countdown_details );
			}
		}
	}

	public static function enqueue_action() {
		$settings                  = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$position_on_checkout_page = $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) ? $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) : 'before_checkout_form';
		$position_on_archive_page  = $settings->get_params( 'checkout_countdown_position_on_archive_page' ) ? $settings->get_params( 'checkout_countdown_position_on_archive_page' ) : 'sticky_top';
		$display_on_page_arg       = array(
			'before_checkout_form'   => 'woocommerce_before_checkout_form',
			'before_customer_detail' => 'woocommerce_checkout_before_customer_details',
			'before_payment'         => 'woocommerce_review_order_before_payment',
			'sticky_top'             => 'wp_footer',
			'sticky_bottom'          => 'wp_footer',
		);
		if ( array_key_exists( $position_on_checkout_page, $display_on_page_arg ) ) {
			add_action( $display_on_page_arg[ $position_on_checkout_page ],
				array(
					__CLASS__,
					$display_on_page_arg[ $position_on_checkout_page ],
				) );
		}
		if ( array_key_exists( $position_on_archive_page,
				$display_on_page_arg ) && $position_on_checkout_page !== $position_on_archive_page ) {
			add_action( $display_on_page_arg[ $position_on_archive_page ],
				array(
					__CLASS__,
					$display_on_page_arg[ $position_on_archive_page ],
				) );
		}
	}

	public static function woocommerce_before_checkout_form() {
		$settings  = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$page_show = $settings->get_params( 'checkout_countdown_display_on_page' ) ? $settings->get_params( 'checkout_countdown_display_on_page' ) : array();
		if ( ! in_array( 'checkout', $page_show ) && count( $page_show ) ) {
			return;
		}

		$position = $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) ? $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) : 'sticky_top';
		if ( $position === 'before_checkout_form' ) {
			echo self::checkout_countdown();
		}
	}

	public static function woocommerce_checkout_before_customer_details() {
		$settings  = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$page_show = $settings->get_params( 'checkout_countdown_display_on_page' ) ? $settings->get_params( 'checkout_countdown_display_on_page' ) : array();
		if ( ! in_array( 'checkout', $page_show ) && count( $page_show ) ) {
			return;
		}
		$position = $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) ? $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) : 'sticky_top';
		if ( $position === 'before_customer_detail' ) {
			echo self::checkout_countdown();
		}
	}

	public static function woocommerce_review_order_before_payment() {
		$settings  = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$page_show = $settings->get_params( 'checkout_countdown_display_on_page' ) ? $settings->get_params( 'checkout_countdown_display_on_page' ) : array();
		if ( ! in_array( 'checkout', $page_show ) && count( $page_show ) ) {
			return;
		}
		$position = $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) ? $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) : 'sticky_top';
		if ( $position === 'before_payment' ) {
			echo self::checkout_countdown();
		}
	}

	public static function wp_footer() {

		$settings                  = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$page_show                 = $settings->get_params( 'checkout_countdown_display_on_page' ) ? $settings->get_params( 'checkout_countdown_display_on_page' ) : array();
		$position_on_checkout_page = $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) ? $settings->get_params( 'checkout_countdown_position_on_checkout_page' ) : 'sticky_top';
		$position_on_archive_page  = $settings->get_params( 'checkout_countdown_position_on_archive_page' ) ? $settings->get_params( 'checkout_countdown_position_on_archive_page' ) : 'sticky_top';
		$position_arg              = array(
			'sticky_top',
			'sticky_bottom',
		);
		if ( count( $page_show ) ) {
			$page_show_t = '';
			foreach ( $page_show as $item ) {
				switch ( $item ) {
					case 'shop':
						$page_show_t .= '||is_shop()';
						break;
					case 'cart':
						$page_show_t .= '||is_cart()';
						break;
					case 'checkout':
						$page_show_t .= '||is_checkout()';
						break;
					case 'myaccount':
						$page_show_t .= '||is_account_page()';
						break;
					case 'home':
						$page_show_t .= '||is_front_page()';
						break;
					case 'product':
						$page_show_t .= '||is_product()';
						break;
					case 'category':
						$page_show_t .= '||is_category()';
						break;
					case 'assign':
						$assign_page = $settings->get_params( 'checkout_countdown_display_on_assign_page' );
						$page_show_t .= $assign_page ? '||' . $assign_page : '';
						break;
				}
			}
			if ( $page_show_t ) {
				$page_show_t = trim( $page_show_t, '||' );
				if ( stristr( $page_show_t, "return" ) === false ) {
					$page_show_t = "return (" . $page_show_t . ");";
				}
				if ( ! eval( $page_show_t ) ) {
					$page = is_checkout() ? '' : 'other';
					echo self::checkout_countdown( 'popup', $page );

					return;
				}
			}
			if ( is_checkout() && in_array( $position_on_checkout_page, $position_arg ) ) {
				echo self::checkout_countdown( $position_on_checkout_page );
			}
			if ( ! is_checkout() && in_array( $position_on_archive_page, $position_arg ) ) {
				echo self::checkout_countdown( $position_on_archive_page, 'other' );
			}

		} else {
			if ( is_checkout() && in_array( $position_on_checkout_page, $position_arg ) ) {
				echo self::checkout_countdown( $position_on_checkout_page );
			}
			if ( ! is_checkout() && in_array( $position_on_archive_page, $position_arg ) ) {
				echo self::checkout_countdown( $position_on_archive_page, 'other' );
			}
		}

	}

	public static function checkout_countdown( $position = '', $page = '' ) {
		$settings                         = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$session_countdown_check          = WC()->session->get( 'sctv_checkout_countdown_check' );
		$session_countdown_time           = WC()->session->get( 'sctv_checkout_countdown_time' );
		$session_countdown_details        = WC()->session->get( 'sctv_checkout_countdown_details' );
		$session_countdown_details_before = WC()->session->get( 'sctv_checkout_countdown_details_before' );
		$session_countdown_end            = is_array( $session_countdown_time ) && isset( $session_countdown_time['end'] ) ? (int) $session_countdown_time['end'] : '';
		if ( $session_countdown_check === 'yes' && $session_countdown_time ) {
			if ( ! $page ) {
				if ( $session_countdown_details ) {
					$checkout_message = isset( $session_countdown_details['message_pg'] ) && $session_countdown_details['message_pg'] ? $session_countdown_details['message_pg'] : $settings->get_params( 'checkout_countdown_message_checkout_page' );
				} elseif ( $session_countdown_details_before ) {
					$checkout_message = $settings->get_params( 'checkout_countdown_message_checkout_page_missing' );
				} else {
					$checkout_message = $settings->get_params( 'checkout_countdown_message_checkout_page' );
				}
				$sale_countdown_id = $settings->get_params( 'checkout_countdown_id_on_checkout_page' );

			} else {
				if ( $session_countdown_details ) {
					$checkout_message = isset( $session_countdown_details['message_og'] ) && $session_countdown_details['message_og'] ? $session_countdown_details['message_og'] : $settings->get_params( 'checkout_countdown_message_other_page' );
				} elseif ( $session_countdown_details_before ) {
					$checkout_message = $settings->get_params( 'checkout_countdown_message_other_page_missing' );
				} else {
					$checkout_message = $settings->get_params( 'checkout_countdown_message_other_page' );
				}
				$sale_countdown_id = $settings->get_params( 'checkout_countdown_id_on_other_page' );
			}
			$checkout_inline = '';
			if ( $position ) {
				$checkout_inline = '1';
			}
			$checkout_shortcode = '[sctv_checkout_countdown_timer sale_countdown_id="' . $sale_countdown_id . '"  time_end ="' . $session_countdown_end . '" checkout_inline ="' . $checkout_inline . '" message="' . htmlentities($checkout_message) . '" ]';

		}
		$checkout_countdown_class = $page ? 'woo-sctr-checkout-countdown-other-page-wrap' : 'woo-sctr-checkout-countdown-checkout-page-wrap';
		switch ( $position ) {
			case 'popup':
				$checkout_countdown_class .= ' woo-sctr-checkout-countdown-popup-wrap woo-sctr-checkout-countdown-sticky-top';
				break;
			case 'sticky_top':
				$checkout_countdown_class .= ' woo-sctr-checkout-countdown-sticky-top';
				break;
			case 'sticky_bottom':
				$checkout_countdown_class .= ' woo-sctr-checkout-countdown-sticky-bottom';
				break;
		}
		ob_start();
		?>
        <div class="woo-sctr-checkout-countdown-wrap-wrap <?php esc_attr_e( trim( $checkout_countdown_class ) ); ?>">
			<?php
			if ( isset( $checkout_shortcode ) ) {
				echo do_shortcode( $checkout_shortcode );
			}
			?>
        </div>
		<?php
		$html = ob_get_clean();

		return ent2ncr( $html );
	}

	public static function set_countdown_details_default() {
		$settings = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$minutes  = $settings->get_params( 'checkout_countdown_time_minute' ) ? (int) $settings->get_params( 'checkout_countdown_time_minute' ) : 0;
		$seconds  = $settings->get_params( 'checkout_countdown_time_second' ) ? (int) $settings->get_params( 'checkout_countdown_time_second' ) : 0;

		if ( $minutes === 0 && $seconds === 0 ) {
			return '';
		}
		$countdown_details_default = array(
			'action_start'        => $settings->get_params( 'checkout_countdown_start' ),
			'cart_subtotal_start' => $settings->get_params( 'checkout_countdown_cart_total_start' ),
			'time_expire'         => $seconds + $minutes * 60,
			'free_ship'           => $settings->get_params( 'checkout_countdown_free_ship' ),
			'discount_type'       => $settings->get_params( 'checkout_countdown_discount_type' ),
			'discount_amount'     => $settings->get_params( 'checkout_countdown_discount_amount' ),
			'free_product'        => $settings->get_params( 'checkout_countdown_free_product_enable' ),
		);
		if ( $settings->get_params( 'checkout_countdown_free_product_enable' ) ) {
			$free_product_arg                             = $settings->get_params( 'checkout_countdown_free_products' );
			$free_product_qt                              = $settings->get_params( 'checkout_countdown_free_product_quantity' );
			$countdown_details_default['free_product_id'] = self::get_free_product( $free_product_arg,
				$free_product_qt );
			$countdown_details_default['free_product_qt'] = $free_product_qt;
		}
		$change_type                               = $settings->get_params( 'checkout_countdown_change' ) ? $settings->get_params( 'checkout_countdown_change' ) : 'none';
		$countdown_details_default ['change_type'] = $change_type;
		$countdown_details_change                  = array();
		switch ( $change_type ) {
			case 'auto_change':
				$auto_change_time_t  = (int) $settings->get_params( 'checkout_countdown_auto_change_time' );
				$auto_change_value_t = (int) $settings->get_params( 'checkout_countdown_auto_change_details_value' );
				if ( $auto_change_time_t > 0 && $auto_change_value_t > 0 ) {
					$countdown_details_change = array(
						'auto_change_time_type'       => $settings->get_params( 'checkout_countdown_auto_change_time_type' ),
						'auto_change_time'            => $settings->get_params( 'checkout_countdown_auto_change_time' ),
						'auto_change_discount_type'   => $settings->get_params( 'checkout_countdown_auto_change_details_type' ),
						'auto_change_discount_amount' => $settings->get_params( 'checkout_countdown_auto_change_details_value' ),
					);
				} else {
					$countdown_details_default ['change_type'] = 'none';
				}
				break;
			case 'custom':
				$countdown_details_change = array(
					'custom_minutes'         => $settings->get_params( 'checkout_countdown_custom_minutes' ),
					'custom_seconds'         => $settings->get_params( 'checkout_countdown_custom_seconds' ),
					'custom_message_pg'      => $settings->get_params( 'checkout_countdown_custom_messages_checkout_page' ),
					'custom_message_og'      => $settings->get_params( 'checkout_countdown_custom_messages_other_page' ),
					'custom_free_ship'       => $settings->get_params( 'checkout_countdown_custom_free_ships' ),
					'custom_free_product'    => $settings->get_params( 'checkout_countdown_custom_free_products' ),
					'custom_discount_amount' => $settings->get_params( 'checkout_countdown_custom_discount_value' ),
				);
				break;
		}
		$countdown_details_default = array_merge( $countdown_details_default, $countdown_details_change );

		return $countdown_details_default;
	}

	public static function get_free_product( $arg = array(), $qt = 1 ) {
		if ( empty( $arg ) ) {
			return false;
		}
		if ( count( $arg ) === 1 ) {
			$product = wc_get_product( $arg[0] );
			if ( $product && $product->is_in_stock() && ( $product->get_stock_quantity() > $qt || ! $product->get_stock_quantity() ) ) {
				return $arg[0];
			} else {
				return false;
			}
		} else {
			$id      = array_rand( $arg, 1 );
			$product = wc_get_product( $arg[ $id ] );
			if ( $product && $product->is_in_stock() && ( $product->get_stock_quantity() > $qt || ! $product->get_stock_quantity() ) ) {
				return $arg[ $id ];
			} else {
				unset( $arg[ $id ] );

				return self::get_free_product( $arg, $qt );
			}
		}
	}

	public function stop_session_countdown_check() {
		WC()->session->set( 'sctv_checkout_countdown_check', 'no' );

		return 'no';
	}

	public static function woocommerce_before_mini_cart() {
		WC()->cart->calculate_totals();
	}

	public static function checkout_validation( $data, $errors ) {
		$count_error = $errors->get_error_messages();
		if ( empty( $count_error ) ) {
			$session_countdown_check   = WC()->session->get( 'sctv_checkout_countdown_check' );
			$session_countdown_time    = WC()->session->get( 'sctv_checkout_countdown_time' );
			$session_countdown_details = WC()->session->get( 'sctv_checkout_countdown_details' );
			if ( $session_countdown_check === 'yes' && $session_countdown_details && $session_countdown_time && is_array( $session_countdown_time ) && count( $session_countdown_time ) >= 2 ) {
				$session_countdown_start = isset( $session_countdown_time['start'] ) ? (int) $session_countdown_time['start'] : 0;
				$session_countdown_end   = isset( $session_countdown_time['end'] ) ? (int) $session_countdown_time['end'] : 0;
				$now                     = (int) current_time( 'timestamp' );
				$countdown_info          = array();
				if ( $session_countdown_start && $session_countdown_end && $session_countdown_end > $now ) {
					$countdown_info['time'] = $now - $session_countdown_start;
				}
				$discount_type   = isset( $session_countdown_details['discount_type'] ) ? $session_countdown_details['discount_type'] : '';
				$discount_amount = isset( $session_countdown_details['discount_amount'] ) ? (float) $session_countdown_details['discount_amount'] : '';
				if ( in_array( $discount_type,
						array(
							'fixed',
							'percent',
						) ) && $discount_amount && $discount_amount > 0 ) {
					$cart_content_total = WC()->cart->get_cart_contents_total();
					if ( $discount_type === 'percent' ) {
						$discount_amount1 = $discount_amount === 100 ? $cart_content_total : $discount_amount * $cart_content_total / 100;
					} else {
						$discount_amount1 = $discount_amount > $cart_content_total ? $cart_content_total : $discount_amount;
					}
					$countdown_info['discount_amount'] = $discount_amount1;
				}
				$countdown_info['free_ship'] = $session_countdown_details['free_shipping'] ?: '';
				if ( count( $countdown_info ) ) {
					WC()->session->set( 'sctv_checkout_countdown_info', $countdown_info );
				}
			}
			WC()->session->__unset( 'sctv_checkout_countdown_enable' );
			WC()->session->__unset( 'sctv_checkout_countdown_check' );
			WC()->session->__unset( 'sctv_checkout_countdown_time' );
			WC()->session->__unset( 'sctv_checkout_countdown_details' );
			WC()->session->__unset( 'sctv_checkout_countdown_details_before' );
			WC()->session->__unset( 'sctv_checkout_countdown_details_default' );
		}
	}

	public static function set_discount_checkout( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}
		$checkout_countdown_details = WC()->session->get( 'sctv_checkout_countdown_details' );
		$free_shipping              = isset( $checkout_countdown_details['free_shipping'] ) ? $checkout_countdown_details['free_shipping'] : '';
		$discount_type              = isset( $checkout_countdown_details['discount_type'] ) ? $checkout_countdown_details['discount_type'] : '';
		$discount_amount            = isset( $checkout_countdown_details['discount_amount'] ) ? (float) $checkout_countdown_details['discount_amount'] : '';
		if ( in_array( $discount_type, array( 'fixed', 'percent' ) ) && $discount_amount && $discount_amount > 0 ) {
			$cart_content_total = (float) $cart->cart_contents_total;
			if ( $discount_type === 'percent' ) {
				$discount_amount1 = $discount_amount === 100 ? $cart_content_total : $discount_amount * $cart_content_total / 100;
			} else {
				$discount_amount1 = $discount_amount > $cart_content_total ? $cart_content_total : $discount_amount;
			}
			$cart->add_fee( __( 'Discount checkout', 'sales-countdown-timer' ), - $discount_amount1 );
		}
		if ( $free_shipping ) {
			$free_shipping = WC()->session->cart_totals['shipping_total'];
			if ( $free_shipping > 0 ) {
				$cart->add_fee( __( 'Free Shipping', 'sales-countdown-timer' ), - $free_shipping );
			}
		}
	}

	public static function append_percentage_to_item_name( $product_name, $cart_item, $cart_item_key ) {
		$checkout_countdown_details = WC()->session->get( 'sctv_checkout_countdown_details' );
		$discount_type              = isset( $checkout_countdown_details['discount_type'] ) ? $checkout_countdown_details['discount_type'] : '';
		$discount_amount            = isset( $checkout_countdown_details['discount_amount'] ) ? (float) $checkout_countdown_details['discount_amount'] : '';
		if ( isset( $cart_item['sctv_free_product'] ) ) {
			$product_name .= __( ' <em>(Free)</em>', 'sales-countdown-timer' );
		} elseif ( $discount_type && $discount_type === 'percent' && $discount_amount && $discount_amount > 0 ) {
			$product_name .= sprintf( __( ' <em>(%s OFF)</em>', 'sales-countdown-timer' ), $discount_amount . '%' );
		}

		return $product_name;
	}

	public static function set_cart_item_price( $cart_item_data, $product_id, $variation_id ) {
		if ( ! isset( $cart_item_data['sctv_free_product'] ) && ! isset( $cart_item_data['sctv_base_price'] ) ) {
			$product_id = $variation_id > 0 ? $variation_id : $product_id;
			$product    = wc_get_product( $product_id );
			$price      = (float) $product->get_price();

			$cart_item_data['sctv_base_price'] = $price;
		}

		return $cart_item_data;
	}

	public static function discounted_cart_item_price( $cart ) {
		if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
			return;
		}

		if ( did_action( 'woocommerce_before_calculate_totals' ) >= 2 ) {
			return;
		}
		$checkout_countdown_details = WC()->session->get( 'sctv_checkout_countdown_details' );
		if ( $checkout_countdown_details ) {
			$discount_type   = isset( $checkout_countdown_details['discount_type'] ) ? $checkout_countdown_details['discount_type'] : '';
			$discount_amount = ( isset( $checkout_countdown_details['discount_amount'] ) && $discount_type === 'percent' && $checkout_countdown_details['discount_amount'] ) ? (float) $checkout_countdown_details['discount_amount'] : 0;
			foreach ( $cart->get_cart() as $cart_item ) {
				if ( isset( $cart_item['sctv_base_price'] ) && $cart_item['sctv_base_price'] ) {
					$cart_item['sctv_discount']  = $discount_amount;
					$cart_item['sctv_new_price'] = $cart_item['sctv_base_price'] * ( 100 - $discount_amount ) / 100;
					method_exists( $cart_item['data'],
						'set_price' ) ? $cart_item['data']->set_price( $cart_item['sctv_new_price'] ) : $cart_item['data']->price = $cart_item['sctv_new_price'];
				}
			}
		} elseif ( ! $cart->is_empty() ) {
			foreach ( $cart->get_cart() as $cart_item ) {
				if ( isset( $cart_item['sctv_base_price'] ) && $cart_item['sctv_base_price'] && $cart_item['sctv_base_price'] !== $cart_item['data']->get_price() ) {
					method_exists( $cart_item['data'],
						'set_price' ) ? $cart_item['data']->set_price( $cart_item['sctv_base_price'] ) : $cart_item['data']->price = $cart_item['sctv_base_price'];
				}
			}
		}
	}

	public static function woocommerce_cart_updated() {
	}
}