<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Shortcode_Checkout
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Shortcode_Checkout_Countdown {
	protected $settings;

	public function __construct() {
		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) && $this->settings->get_params( 'checkout_countdown_enable' ) ) {
			add_action( 'init', array( $this, 'shortcode_init' ) );
		}
		add_action( 'wp_ajax_woo_sctr_set_checkout_discount', array( $this, 'woo_sctr_set_checkout_discount' ) );
		add_action( 'wp_ajax_nopriv_woo_sctr_set_checkout_discount', array( $this, 'woo_sctr_set_checkout_discount' ) );
		add_action( 'wp_ajax_woo_sctr_set_session', array( $this, 'woo_sctr_set_session' ) );
		add_action( 'wp_ajax_nopriv_woo_sctr_set_session', array( $this, 'woo_sctr_set_session' ) );
		add_action( 'wp_ajax_woo_sctr_set_session_on_cart_page', array( $this, 'woo_sctr_set_session_on_cart_page' ) );
		add_action( 'wp_ajax_nopriv_woo_sctr_set_session_on_cart_page',
			array(
				$this,
				'woo_sctr_set_session_on_cart_page',
			) );
		add_filter( 'sctv_set_checkout_message', array( $this, 'set_message' ) );
	}

	public function shortcode_init() {
		if ( is_admin() ) {
			return;
		}
		add_shortcode( 'sctv_checkout_countdown_timer', array( $this, 'register_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'shortcode_enqueue_script' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( __CLASS__, 'wc_mini_cart_ajax_refresh' ) );
	}

	public static function wc_mini_cart_ajax_refresh( $fragments ) {
		$settings                          = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$checkout_countdown_details        = WC()->session->get( 'sctv_checkout_countdown_details' );
		$checkout_countdown_details_before = WC()->session->get( 'sctv_checkout_countdown_details_before' );
		$fragments_enable                  = true;
		if ( $checkout_countdown_details ) {
			$checkout_message = isset( $checkout_countdown_details['message_pg'] ) && $checkout_countdown_details['message_pg'] ? $checkout_countdown_details['message_pg'] : $settings->get_params( 'checkout_countdown_message_checkout_page' );
			$op_message       = $checkout_countdown_details && isset( $checkout_countdown_details['message_og'] ) && $checkout_countdown_details['message_og'] ? $checkout_countdown_details['message_og'] : $settings->get_params( 'checkout_countdown_message_other_page' );
		} elseif ( $checkout_countdown_details_before ) {
			$checkout_message = $settings->get_params( 'checkout_countdown_message_checkout_page_missing' );
			$op_message       = $settings->get_params( 'checkout_countdown_message_other_page_missing' );
		} else {
			$fragments_enable = false;
		}
		if ( $fragments_enable ) {
			$checkout_message = explode( '{countdown_timer}', $checkout_message );
			$op_message       = explode( '{countdown_timer}', $op_message );
			$text_before_cp   = $text_after_cp = $text_before_op = $text_after_op = '';
			if ( count( $checkout_message ) >= 2 ) {
				ob_start();
				echo apply_filters( 'sctv_set_checkout_message', $checkout_message[0] );
				$text_before_cp = ob_get_clean();
				ob_start();
				echo apply_filters( 'sctv_set_checkout_message', $checkout_message[1] );
				$text_after_cp = ob_get_clean();
			}
			if ( count( $op_message ) >= 2 ) {
				ob_start();
				echo apply_filters( 'sctv_set_checkout_message', $op_message[0] );
				$text_before_op = ob_get_clean();
				ob_start();
				echo apply_filters( 'sctv_set_checkout_message', $op_message[1] );
				$text_after_op = ob_get_clean();
			}
			$fragments['.woo-sctr-checkout-countdown-checkout-page-wrap .woo-sctr-countdown-timer-text-before'] = '<span class="woo-sctr-countdown-timer-text-before">' . $text_before_cp . '</span>';
			$fragments['.woo-sctr-checkout-countdown-checkout-page-wrap .woo-sctr-countdown-timer-text-after']  = '<span class="woo-sctr-countdown-timer-text-after">' . $text_after_cp . '</span>';
			$fragments['.woo-sctr-checkout-countdown-other-page-wrap .woo-sctr-countdown-timer-text-before']    = '<span class="woo-sctr-countdown-timer-text-before">' . $text_before_op . '</span>';
			$fragments['.woo-sctr-checkout-countdown-other-page-wrap .woo-sctr-countdown-timer-text-after']     = '<span class="woo-sctr-countdown-timer-text-after">' . $text_after_op . '</span>';
		}

		return $fragments;
	}

	public function woo_sctr_set_session_on_cart_page() {
		$result                             = array(
			'status'  => '',
			'message' => '',
			'detail'  => $_REQUEST,
		);
		$result['shortcode_js_url']         = WP_DEBUG ? VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-countdown.js' : VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-countdown.min.js';
		$result['shortcode_css_url']        = WP_DEBUG ? VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-countdown.css' : VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-countdown.min.css';
		$result['shortcode_version']        = VI_SCT_SALES_COUNTDOWN_TIMER_VERSION;
		$has_time_end                       = isset( $_REQUEST['time_end'] ) ? sanitize_text_field( $_REQUEST['time_end'] ) : '';
		$session_countdown_details_default  = WC()->session->get( 'sctv_checkout_countdown_details_default' );
		$checkout_countdown_details_current = WC()->session->get( 'sctv_checkout_countdown_details' );
		$session_countdown_time             = WC()->session->get( 'sctv_checkout_countdown_time' );
		$checkout_countdown_details_before  = WC()->session->get( 'sctv_checkout_countdown_details_before' );
		$cart_subtotal_start                = isset( $session_countdown_details_default['cart_subtotal_start'] ) ? (float) $session_countdown_details_default['cart_subtotal_start'] : 0;
		$cart_subtotal_start                = apply_filters( 'wmc_change_3rd_plugin_price', $cart_subtotal_start );
		$wc_cart                            = WC()->cart;
		$wc_cart_total                      = (float) WC()->session->cart_totals['cart_contents_total'];
		if ( $checkout_countdown_details_before ) {
			if ( $wc_cart_total < $cart_subtotal_start ) {
				$result['status']  = 'error';
				$result['message'] = '$wc_cart_total < $cart_subtotal_start 4';
				wp_send_json( $result );
			}
			$now                     = current_time( 'timestamp' );
			$session_countdown_start = isset( $session_countdown_time['start'] ) ? (int) $session_countdown_time['start'] : 0;
			$session_countdown_end   = isset( $session_countdown_time['end'] ) ? (int) $session_countdown_time['end'] : 0;
			if ( $session_countdown_start > 0 && $session_countdown_end > 0 && $session_countdown_end > $now ) {
				$checkout_countdown_details = $checkout_countdown_details_before;
				$check_continue             = false;
				switch ( $session_countdown_details_default['change_type'] ) {
					case 'auto_change':
						$time_done = $now - $session_countdown_start;
						$time_loop = $session_countdown_details_default['auto_change_time_type'] === 'minute' ? (int) $session_countdown_details_default['auto_change_time'] * 60 : (int) $session_countdown_details_default['auto_change_time'];
						$loop      = $time_loop > 0 ? floor( $time_done / $time_loop ) : 0;
						if ( $loop > 0 ) {
							$loop_stop       = false;
							$t               = wc_format_decimal( 0, '' );
							$discount_amount = $session_countdown_details_default['discount_amount'];
							for ( $i = 1; $i <= $loop; $i ++ ) {
								$discount_amount = $discount_amount * ( 100 - $session_countdown_details_default['auto_change_discount_amount'] ) / 100;
								$discount_amount = wc_format_decimal( $discount_amount, '' );
								if ( $discount_amount === $t ) {
									$loop_stop = true;
									break;
								}
							}
							$checkout_countdown_details['discount_amount'] = $discount_amount;
							$check_continue                                = true;
							if ( $loop_stop ) {
								$session_countdown_details_default['change_type'] = 'none';
								WC()->session->set( 'sctv_checkout_countdown_details_default',
									$session_countdown_details_default );
								WC()->session->save_data();
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
							$checkout_countdown_details['message_pg']      = isset( $session_countdown_details_default['custom_message_pg'][ $index_current ] ) ? $session_countdown_details_default['custom_message_pg'][ $index_current ] : '';
							$checkout_countdown_details['message_og']      = isset( $session_countdown_details_default['custom_message_og'][ $index_current ] ) ? $session_countdown_details_default['custom_message_og'][ $index_current ] : '';
							$checkout_countdown_details['free_shipping']   = isset( $session_countdown_details_default['custom_free_ship'][ $index_current ] ) ? $session_countdown_details_default['custom_free_ship'][ $index_current ] : '';
							$checkout_countdown_details['free_product']    = isset( $session_countdown_details_default['custom_free_product'][ $index_current ] ) ? $session_countdown_details_default['custom_free_product'][ $index_current ] : '';
							$checkout_countdown_details['discount_amount'] = isset( $session_countdown_details_default['custom_discount_amount'][ $index_current ] ) ? $session_countdown_details_default['custom_discount_amount'][ $index_current ] : 0;
							$check_continue                                = true;
						}

						break;
				}
				$result['$check_continue']             = $check_continue;
				$result['$checkout_countdown_details'] = $checkout_countdown_details;
				if ( $check_continue ) {
					$result['$checkout_countdown_details_check'] = WC()->session->get( 'sctv_checkout_countdown_details' );
					WC()->session->__unset( 'sctv_checkout_countdown_details' );
					WC()->session->__unset( 'sctv_checkout_countdown_details_before' );
					WC()->session->set( 'sctv_checkout_countdown_details', $checkout_countdown_details );
					WC()->session->save_data();
					WC()->cart->calculate_fees();
					WC()->cart->calculate_totals();
					$result['status']     = 'success';
					$result['has_detail'] = 1;
					wp_send_json( $result );
				} else {
					$result['status']  = 'error';
					$result['message'] = '$checkout_countdown_details not found';
					wp_send_json( $result );
				}
			} else {
				$result['status']  = 'error';
				$result['message'] = 'no time';
				wp_send_json( $result );
			}
		} elseif ( $checkout_countdown_details_current ) {
			$has_detail          = 1;
			$discount_amount     = isset( $checkout_countdown_details_current['discount_amount'] ) ? (float) $checkout_countdown_details_current['discount_amount'] : '';
			$cart_subtotal_start = $discount_amount ? $cart_subtotal_start * ( 100 - $discount_amount ) / 100 : $cart_subtotal_start;
			if ( $wc_cart->is_empty() || $wc_cart_total < $cart_subtotal_start ) {
				WC()->session->set( 'sctv_checkout_countdown_details_before', $checkout_countdown_details_current );
				WC()->session->__unset( 'sctv_checkout_countdown_details' );
				WC()->session->save_data();
				WC()->cart->calculate_fees();
				WC()->cart->calculate_totals();
				$has_detail       = '';
				$result['status'] = 'success';
			} elseif ( ! $has_time_end ) {
				$time_expire        = isset( $session_countdown_details_default['time_expire'] ) ? (int) $session_countdown_details_default['time_expire'] : 0;
				$result['time_end'] = $time_expire - 1;
				$result['status']   = 'success';
			} else {
				$result['status']  = 'error';
				$result['message'] = 'enough term to run countdown';
			}
			$result['has_detail'] = $has_detail;

			wp_send_json( $result );
		}
	}

	public function woo_sctr_set_session() {
		$result = array(
			'status'  => '',
			'message' => '',
			'detail'  => $_REQUEST,
		);
		$type   = isset( $_REQUEST['type'] ) ? sanitize_text_field( $_REQUEST['type'] ) : '';
		if ( ! $type ) {
			$result['status']  = 'error';
			$result['message'] = 'no type to set session';
			wp_send_json( $result );
		}
		$session_countdown_details_default = WC()->session->get( 'sctv_checkout_countdown_details_default' );
		if ( ! $session_countdown_details_default ) {
			$result['status']  = 'error';
			$result['message'] = 'no $session_countdown_details_default';
			wp_send_json( $result );
		}
		$cart_subtotal_start = isset( $session_countdown_details_default['cart_subtotal_start'] ) ? (float) $session_countdown_details_default['cart_subtotal_start'] : 0;
		$cart_subtotal_start = apply_filters( 'wmc_change_3rd_plugin_price', $cart_subtotal_start );
		switch ( $type ) {
			case 'removed_from_cart':
				$checkout_countdown_details_current = WC()->session->get( 'sctv_checkout_countdown_details' );
				$wc_cart                            = WC()->cart;
				$wc_cart_total                      = $wc_cart->get_cart_contents_total();
				$has_detail                         = '';
				if ( $checkout_countdown_details_current ) {
					$has_detail = 1;
					if ( $wc_cart->is_empty() || $wc_cart_total < $cart_subtotal_start ) {
						WC()->session->set( 'sctv_checkout_countdown_details_before',
							$checkout_countdown_details_current );
						WC()->session->__unset( 'sctv_checkout_countdown_details' );
						WC()->cart->calculate_fees();
						WC()->cart->calculate_totals();
						$has_detail = '';
					}
				}
				$result['status']     = 'success';
				$result['has_detail'] = $has_detail;
				wp_send_json( $result );
				break;
			case 'added_to_cart':
				$session_countdown_enable = WC()->session->get( 'sctv_checkout_countdown_enable' );
				$session_countdown_check  = WC()->session->get( 'sctv_checkout_countdown_check' );
				if ( $session_countdown_enable === 'no' ) {
					$result['status']  = 'error';
					$result['message'] = 'checkout countdown disable';
					wp_send_json( $result );
				}
				$now           = current_time( 'timestamp' );
				$wc_cart_total = (float) WC()->cart->get_cart_contents_total();
				if ( ! $session_countdown_check ) {
					if ( $session_countdown_details_default['action_start'] === 'add_to_cart' ) {
						if ( $wc_cart_total < $cart_subtotal_start ) {
							$result['status']  = 'error';
							$result['message'] = '$wc_cart_total < $cart_subtotal_start 3';
							wp_send_json( $result );
						}
						$time_expire = isset( $session_countdown_details_default['time_expire'] ) ? (int) $session_countdown_details_default['time_expire'] : 0;
						if ( $time_expire === 0 ) {
							$result['status']  = 'error';
							$result['message'] = '$time_expire=0';
							wp_send_json( $result );
						}
						$session_countdown_time     = array( 'start' => $now, 'end' => $now + $time_expire );
						$checkout_countdown_details = array(
							'free_shipping'   => $session_countdown_details_default['free_ship'],
							'free_product'    => $session_countdown_details_default['free_product'],
							'free_product_id' => isset( $session_countdown_details_default['free_product_id'] ) ? $session_countdown_details_default['free_product_id'] : '',
							'free_product_qt' => isset( $session_countdown_details_default['free_product_qt'] ) ? $session_countdown_details_default['free_product_qt'] : '',
							'discount_type'   => $session_countdown_details_default['discount_type'],
							'discount_amount' => $session_countdown_details_default['discount_amount'],
						);
						WC()->session->set( 'sctv_checkout_countdown_details', $checkout_countdown_details );
						WC()->session->set( 'sctv_checkout_countdown_time', $session_countdown_time );
						WC()->session->set( 'sctv_checkout_countdown_check', 'yes' );
						WC()->cart->calculate_fees();
						WC()->cart->calculate_totals();
						$result['status']            = 'success';
						$result['has_detail']        = 1;
						$result['time_end']          = $time_expire - 1;
						$result['html']              = 1;
						$result['shortcode_js_url']  = VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-countdown.js';
						$result['shortcode_css_url'] = VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-countdown.css';
						$result['shortcode_version'] = VI_SCT_SALES_COUNTDOWN_TIMER_VERSION;
						wp_send_json( $result );
					} else {
						$result['status']  = 'error';
						$result['message'] = '$session_countdown_check=null && $session_countdown_details_default[\'action_start\'] !=add_to_cart';
						wp_send_json( $result );
					}
				} elseif ( $session_countdown_check === 'no' ) {
					$result['status']  = 'error';
					$result['message'] = 'checkout countdown was run';
					wp_send_json( $result );
				} else {
					$checkout_countdown_details_before = WC()->session->get( 'sctv_checkout_countdown_details_before' );
					$session_countdown_time            = WC()->session->get( 'sctv_checkout_countdown_time' );
					if ( ! $checkout_countdown_details_before || $cart_subtotal_start === 0 || ! is_array( $session_countdown_time ) || count( $session_countdown_time ) < 2 ) {
						$result['status']  = 'error';
						$result['message'] = 'not enough condition';
						$result['detail']  = array(
							'$checkout_countdown_details_before' => $checkout_countdown_details_before,
							'$cart_subtotal_start'               => $cart_subtotal_start,
							'$session_countdown_time'            => $session_countdown_time,
						);
						wp_send_json( $result );
					}
					if ( $wc_cart_total < $cart_subtotal_start ) {
						$result['status']  = 'error';
						$result['message'] = '$wc_cart_total < $cart_subtotal_start 2';
						wp_send_json( $result );
					}
					$session_countdown_start = isset( $session_countdown_time['start'] ) ? (int) $session_countdown_time['start'] : 0;
					$session_countdown_end   = isset( $session_countdown_time['end'] ) ? (int) $session_countdown_time['end'] : 0;
					$check_continue          = false;
					if ( $session_countdown_start > 0 && $session_countdown_end > 0 && $session_countdown_end > $now ) {
						$checkout_countdown_details = $checkout_countdown_details_before;
						$check_continue             = true;
						switch ( $session_countdown_details_default['change_type'] ) {
							case 'auto_change':
								$time_done = $now - $session_countdown_start;
								$time_loop = $session_countdown_details_default['auto_change_time_type'] === 'minute' ? (int) $session_countdown_details_default['auto_change_time'] * 60 : (int) $session_countdown_details_default['auto_change_time'];
								$loop      = $time_loop > 0 ? floor( $time_done / $time_loop ) : 0;
								if ( $loop > 0 ) {
									$loop_stop       = false;
									$t               = wc_format_decimal( 0, '' );
									$discount_amount = $session_countdown_details_default['discount_amount'];
									for ( $i = 1; $i <= $loop; $i ++ ) {
										$discount_amount = $discount_amount - $discount_amount * $session_countdown_details_default['auto_change_discount_amount'] / 100;
										if ( wc_format_decimal( $discount_amount, '' ) === $t ) {
											$loop_stop = true;
											break;
										}
									}
									$discount_amount = wc_format_decimal( $discount_amount, '' );

									$checkout_countdown_details['discount_amount'] = $discount_amount;
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
									$checkout_countdown_details['message_pg']      = isset( $session_countdown_details_default['custom_message_pg'][ $index_current ] ) ? $session_countdown_details_default['custom_message_pg'][ $index_current ] : '';
									$checkout_countdown_details['message_og']      = isset( $session_countdown_details_default['custom_message_og'][ $index_current ] ) ? $session_countdown_details_default['custom_message_og'][ $index_current ] : '';
									$checkout_countdown_details['free_shipping']   = isset( $session_countdown_details_default['custom_free_ship'][ $index_current ] ) ? $session_countdown_details_default['custom_free_ship'][ $index_current ] : '';
									$checkout_countdown_details['free_product']    = isset( $session_countdown_details_default['custom_free_product'][ $index_current ] ) ? $session_countdown_details_default['custom_free_product'][ $index_current ] : '';
									$checkout_countdown_details['discount_amount'] = isset( $session_countdown_details_default['custom_discount_amount'][ $index_current ] ) ? $session_countdown_details_default['custom_discount_amount'][ $index_current ] : 0;

								}

								break;
							default:
						}
						if ( $check_continue ) {
							WC()->session->set( 'sctv_checkout_countdown_details', $checkout_countdown_details );
							WC()->session->__unset( 'sctv_checkout_countdown_details_before' );
							WC()->session->set( 'sctv_checkout_countdown_check', 'yes' );
							WC()->cart->calculate_fees();
							WC()->cart->calculate_totals();

							WC()->session->save_data();
							$result['status']     = 'success';
							$result['has_detail'] = 1;
							wp_send_json( $result );
						} else {
							$result['status']  = 'error';
							$result['message'] = '$checkout_countdown_details not found';
							wp_send_json( $result );
						}
					} else {
						$result['status']  = 'error';
						$result['message'] = 'no time';
						wp_send_json( $result );
					}
				}
				break;
		}
	}

	public function woo_sctr_set_checkout_discount() {
		$result = array(
			'status'  => '',
			'message' => '',
			'detail'  => $_REQUEST,
		);
		$type   = isset( $_REQUEST['type'] ) ? sanitize_text_field( $_REQUEST['type'] ) : '';
		if ( ! $type ) {
			$result['status']  = 'error';
			$result['message'] = 'no type checkout discount';
			wp_send_json( $result );
		}
		switch ( $type ) {
			case 'auto_change':
				$discount_amount = isset( $_REQUEST['discount_amount'] ) ? (float) sanitize_text_field( $_REQUEST['discount_amount'] ) : '';
				if ( ! $discount_amount ) {
					$result['status']  = 'error';
					$result['message'] = 'no value to change discount checkout';
					wp_send_json( $result );
				}
				$checkout_countdown_details = WC()->session->get( 'sctv_checkout_countdown_details' );
				if ( $checkout_countdown_details ) {
					WC()->session->__unset( 'sctv_checkout_countdown_details' );
					$discount_amount              = $checkout_countdown_details['discount_amount'] * ( 100 - $discount_amount ) / 100;
					$discount_amount              = wc_format_decimal( $discount_amount, '' );
					$checkout_countdown_details_t = array(
						'discount_type'   => $checkout_countdown_details['discount_type'],
						'free_product'    => $checkout_countdown_details['free_product'],
						'free_product_id' => $checkout_countdown_details['free_product_id'],
						'free_product_qt' => $checkout_countdown_details['free_product_qt'],
						'free_shipping'   => $checkout_countdown_details['free_shipping'],
						'message_og'      => isset( $checkout_countdown_details['message_og'] ) ? $checkout_countdown_details['message_og'] : '',
						'message_pg'      => isset( $checkout_countdown_details['message_pg'] ) ? $checkout_countdown_details['message_pg'] : '',
						'discount_amount' => $discount_amount,
					);
					WC()->session->set( 'sctv_checkout_countdown_details', $checkout_countdown_details_t );
					$result['status'] = 'success';
					if ( $discount_amount === wc_format_decimal( 0,
							'' ) || $discount_amount === $checkout_countdown_details['discount_amount'] ) {

						$session_countdown_details_default = WC()->session->get( 'sctv_checkout_countdown_details_default' );
						WC()->session->__unset( 'sctv_checkout_countdown_details_default' );
						$session_countdown_details_default['change_type'] = 'none';
						WC()->session->set( 'sctv_checkout_countdown_details_default',
							$session_countdown_details_default );

						$result['status'] = 'stop';
					}
					WC()->session->save_data();
					WC()->cart->calculate_fees();
					WC()->cart->calculate_totals();
					wp_send_json( $result );
				} else {
					$result['status']  = 'error';
					$result['message'] = 'no value to change discount checkout';
					wp_send_json( $result );
				}
				break;
			case 'custom':
				$is_checkout                = isset( $_POST['is_checkout'] ) ? sanitize_text_field( $_POST['is_checkout'] ) : '';
				$message_pg                 = isset( $_POST['message_pg'] ) ? sanitize_text_field( $_POST['message_pg'] ) : '';
				$message_og                 = isset( $_POST['message_og'] ) ? sanitize_text_field( $_POST['message_og'] ) : '';
				$free_ship                  = isset( $_POST['free_ship'] ) ? sanitize_text_field( $_POST['free_ship'] ) : '';
				$free_product               = isset( $_POST['free_product'] ) ? sanitize_text_field( $_POST['free_product'] ) : '';
				$discount_amount            = isset( $_POST['discount_amount'] ) ? (float) sanitize_text_field( $_POST['discount_amount'] ) : 0;
				$checkout_countdown_details = WC()->session->get( 'sctv_checkout_countdown_details' );
				if ( $checkout_countdown_details ) {
					WC()->session->__unset( 'sctv_checkout_countdown_details' );
					$checkout_countdown_details['message_pg']      = $message_pg;
					$checkout_countdown_details['message_og']      = $message_og;
					$checkout_countdown_details['free_shipping']   = $free_ship;
					$checkout_countdown_details['free_product']    = $free_product;
					$checkout_countdown_details['discount_amount'] = $discount_amount;
					WC()->session->set( 'sctv_checkout_countdown_details', $checkout_countdown_details );
					WC()->cart->calculate_fees();
					WC()->cart->calculate_totals();
					WC()->session->save_data();
					$message   = $is_checkout === 'yes' ? $message_pg : $message_og;
					$message   = explode( '{countdown_timer}', $message );
					$countdown = $text_before = $text_after = '';
					if ( count( $message ) >= 2 ) {
						$countdown   = 1;
						$text_before = $message[0];
						$text_after  = $message[1];
					}
					$result['status']      = 'success';
					$result['countdown']   = $countdown;
					$result['text_before'] = $text_before;
					$result['text_after']  = $text_after;
					wp_send_json( $result );
				} else {
					$result['status']  = 'error';
					$result['message'] = 'no value to change discount checkout';
					wp_send_json( $result );
				}
				break;
		}

	}

	public function register_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'message'           => '{countdown_timer}',
			'sale_countdown_id' => 'salescountdowntimer',
			'checkout_inline'   => '',
			'position'          => '',
			'time_end'          => 0,
		),
			$atts ) );
		if ( ! wp_script_is( 'woo-sctr-shortcode-checkout-countdown-script', 'enqueued' ) ) {
			wp_enqueue_script( 'woo-sctr-shortcode-checkout-countdown-script' );
		}
		if ( ! wp_script_is( 'woo-sctr-shortcode-checkout-countdown-style', 'enqueued' ) ) {
			wp_enqueue_style( 'woo-sctr-shortcode-checkout-countdown-style' );
		}
		$shortcode = '[sales_countdown_timer id="' . $sale_countdown_id . '" type="checkout" checkout_to_time ="' . $time_end . '" checkout_inline ="' . $checkout_inline . '" message="' . $message . '" ]';
		ob_start();
		?>
        <div class="woo-sctr-checkout-countdown-shortcode">
			<?php
			echo do_shortcode( $shortcode );
			?>
        </div>
		<?php
		$html = ob_get_clean();
		$html = $this->set_message( $html );

		return ent2ncr( $html );
	}

	public function set_message( $html ) {
		$checkout_button_target_blank = $this->settings->get_params( 'checkout_button_checkout_link_target' ) ? 'target="_blank"' : '';
		$checkout_button_link         = wc_get_checkout_url();
		if ( $this->settings->get_params( 'checkout_countdown_save_log' ) ) {
			$checkout_button_link = wp_nonce_url( $checkout_button_link, 'sctv_checkout_countdown_link', 'link' );
		}
		$checkout_button = '<a class="woo-sctr-checkout-button woo-sctr-checkout-checkout-button" href="' . $checkout_button_link . '" ' . $checkout_button_target_blank . '>' . $this->settings->get_params( 'checkout_button_checkout_title' ) . '</a>';

		$shop_button_target_blank = $this->settings->get_params( 'checkout_button_shop_link_target' ) ? 'target="_blank"' : '';
		$shop_button              = '<a class="woo-sctr-checkout-button woo-sctr-go-shop-button" href="' . get_permalink( wc_get_page_id( 'shop' ) ) . '" ' . $shop_button_target_blank . '>' . $this->settings->get_params( 'checkout_button_shop_title' ) . '</a>';

		$checkout_countdown_details = WC()->session->get( 'sctv_checkout_countdown_details' );
		$checkout_countdown_default = WC()->session->get( 'sctv_checkout_countdown_details_default' );
		$discount_type              = isset( $checkout_countdown_details['discount_type'] ) ? $checkout_countdown_details['discount_type'] : '';
		$discount_amount            = isset( $checkout_countdown_details['discount_amount'] ) ? (float) $checkout_countdown_details['discount_amount'] : 0;
		$minimum_cart_total         = isset( $checkout_countdown_default['cart_subtotal_start'] ) ? (float) $checkout_countdown_default['cart_subtotal_start'] : 0;
		$content_cart_total         = (float) WC()->cart->cart_contents_total;
		$original_cart_total        = (float) WC()->session->cart_totals['total'];
		$discount_fixed             = 0;
		$discount_percentage        = 0;
		$missing_amount             = 0;
		if ( in_array( $discount_type, array( 'fixed', 'percent' ) ) && $discount_amount && $discount_amount > 0 ) {
			if ( $discount_type === 'percent' ) {
				$discount_fixed      = $discount_amount === 100 ? $content_cart_total : $discount_amount * $content_cart_total / 100;
				$discount_percentage = $discount_amount;
			} else {
				$discount_fixed      = $discount_amount > $content_cart_total ? $content_cart_total : $discount_amount;
				$discount_percentage = $discount_amount < $content_cart_total ? floor( $discount_amount * 100 / $content_cart_total ) : 100;
			}
		}
		if ( $content_cart_total < $minimum_cart_total ) {
			$missing_amount = $minimum_cart_total - $content_cart_total;
		}
		$original_cart_total += $discount_fixed;

//        $discount_fixed=apply_filters('wmc_change_3rd_plugin_price',$discount_fixed);
//        $original_cart_total = apply_filters('wmc_change_3rd_plugin_price',$original_cart_total);
		$minimum_cart_total  = apply_filters( 'wmc_change_3rd_plugin_price', $minimum_cart_total );
		$discount_fixed      = '<span class="woo-sctr-discount-amount woo-sctr-discount-fixed">' . wc_price( $discount_fixed ) . '</span>';
		$original_cart_total = '<span class="woo-sctr-discount-amount  woo-sctr-discount-original_cart_total">' . wc_price( $original_cart_total ) . '</span>';
		$minimum_cart_total  = '<span class="woo-sctr-discount-amount  woo-sctr-discount-minimum_cart_total">' . wc_price( $minimum_cart_total ) . '</span>';
		$missing_amount      = '<span class="woo-sctr-discount-amount  woo-sctr-discount-missing_amount">' . wc_price( $missing_amount ) . '</span>';
		$discount_percentage = $discount_percentage > 100 ? 100 : $discount_percentage;
		$discount_percentage = '<span class="woo-sctr-discount-amount  woo-sctr-discount-discount_percentage">' . $discount_percentage . '%' . '</span>';
		$html                = str_replace( "{checkout_button}", $checkout_button, $html );
		$html                = str_replace( "{shop_button}", $shop_button, $html );
		$html                = str_replace( "{discount_fixed}", $discount_fixed, $html );
		$html                = str_replace( "{discount_percentage}", $discount_percentage, $html );
		$html                = str_replace( "{missing_amount}", $missing_amount, $html );
		$html                = str_replace( "{minimum_cart_total}", $minimum_cart_total, $html );
		$html                = str_replace( "{original_cart_total}", $original_cart_total, $html );

		return ent2ncr( $html );
	}

	public function shortcode_enqueue_script() {
		if ( WP_DEBUG ) {
			wp_enqueue_style( 'woo-sctr-shortcode-checkout-countdown-style',
				VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-checkout-countdown.css',
				array(),
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			wp_enqueue_script( 'woo-sctr-shortcode-checkout-countdown-script',
				VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-checkout-countdown.js',
				array( 'jquery' ),
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION,
				true );
		} else {
			wp_enqueue_style( 'woo-sctr-shortcode-checkout-countdown-style',
				VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-checkout-countdown.min.css',
				array(),
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			wp_enqueue_script( 'woo-sctr-shortcode-checkout-countdown-script',
				VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-checkout-countdown.min.js',
				array( 'jquery' ),
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION,
				true );
		}
		$css = '';
		$css .= '.woo-sctr-checkout-button.woo-sctr-checkout-checkout-button{';
		if ( $checkout_button_checkout_fontsize = $this->settings->get_params( 'checkout_button_checkout_fontsize' ) ) {
			$css .= 'font-size: ' . $checkout_button_checkout_fontsize . 'px;';
		}
		if ( $checkout_button_checkout_color = $this->settings->get_params( 'checkout_button_checkout_color' ) ) {
			$css .= 'color: ' . $checkout_button_checkout_color . ';';
		}
		if ( $checkout_button_checkout_color = $this->settings->get_params( 'checkout_button_checkout_background' ) ) {
			$css .= 'background: ' . $checkout_button_checkout_color . ';';
		}
		$css .= '}';
		$css .= '.woo-sctr-checkout-button.woo-sctr-go-shop-button{';
		if ( $checkout_button_shop_fontsize = $this->settings->get_params( 'checkout_button_shop_fontsize' ) ) {
			$css .= 'font-size: ' . $checkout_button_shop_fontsize . 'px;';
		}
		if ( $checkout_button_shop_color = $this->settings->get_params( 'checkout_button_shop_color' ) ) {
			$css .= 'color: ' . $checkout_button_shop_color . ';';
		}
		if ( $checkout_button_shop_background = $this->settings->get_params( 'checkout_button_shop_background' ) ) {
			$css .= 'background: ' . $checkout_button_shop_background . ';';
		}
		$css .= '}';
		wp_add_inline_style( 'woo-sctr-shortcode-checkout-countdown-style', $css );


		$checkout_countdown_default           = WC()->session->get( 'sctv_checkout_countdown_details_default' );
		$session_countdown_time               = WC()->session->get( 'sctv_checkout_countdown_time' );
		$t                                    = array(
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'is_checkout' => is_checkout() ? 'yes' : 'no',
		);
		$t['checkout_countdown_default']      = $checkout_countdown_default;
		$t['sctv_checkout_countdown_details'] = WC()->session->get( 'sctv_checkout_countdown_details' );
		$t['sctv_checkout_countdown_check']   = WC()->session->get( 'sctv_checkout_countdown_check' );
		if ( $session_countdown_time ) {
			$now              = current_time( 'timestamp' );
			$checkout_to_time = (int) $session_countdown_time['end'];
			if ( $now < $checkout_to_time ) {
				$t['time_end'] = $checkout_to_time - $now - 1;
			} else {
				$t['time_end'] = '';
			}
		}
		wp_localize_script(
			'woo-sctr-shortcode-checkout-countdown-script',
			'woo_sctr_checkout_countdown_shortcode',
			$t
		);
		if ( isset( $checkout_countdown_default ) && in_array( $checkout_countdown_default['action_start'],
				array(
					'add_to_cart',
					'to_cart_page',
				) ) && ! $session_countdown_time ) {
			if ( WP_DEBUG ) {
				if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-style', 'registered' ) ) {
					wp_register_style( 'woo-sctr-shortcode-countdown-style',
						VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-countdown.css',
						array(),
						VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
				}
				if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-script', 'registered' ) ) {
					wp_register_script( 'woo-sctr-shortcode-countdown-script',
						VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-countdown.js',
						array( 'jquery' ),
						VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
					wp_enqueue_script( 'woo-sctr-shortcode-countdown-script' );
				}
			} else {
				if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-style', 'registered' ) ) {
					wp_register_style( 'woo-sctr-shortcode-countdown-style',
						VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'shortcode-countdown.min.css',
						array(),
						VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
				}
				if ( ! wp_script_is( 'woo-sctr-shortcode-countdown-script', 'registered' ) ) {
					wp_register_script( 'woo-sctr-shortcode-countdown-script',
						VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'shortcode-countdown.min.js',
						array( 'jquery' ),
						VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
					wp_enqueue_script( 'woo-sctr-shortcode-countdown-script' );
				}
			}
		}
	}
}