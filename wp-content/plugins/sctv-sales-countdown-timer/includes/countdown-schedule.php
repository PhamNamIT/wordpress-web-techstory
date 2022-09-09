<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Cron {
	public function __construct() {
		add_action( 'admin_init', array( $this, 'init_schedule' ), 100 );
		add_action( 'sctv_schedule_time_shortcode', array( $this, 'sctv_schedule_time_shortcodes' ), 10, 1 );
		add_action( 'sctv_schedule_sale_product', array( $this, 'sctv_schedule_sale_products' ), 10, 1 );
		add_action( 'sctv_schedule_remove_sale_product', array( $this, 'sctv_schedule_remove_sale_products' ), 10, 1 );
	}


	public function init_schedule() {
		if ( get_option( 'sctv_schedule_sales' ,'') ) {
			if ( !get_transient( '_sales_countdown_timer_set_schedule' ) ) {
				return;
			}else{
				delete_transient( '_sales_countdown_timer_set_schedule' );
			}
		}
		$crons = _get_cron_array();
		if ( ! $crons ) {
			return;
		}
		$events = array();
		foreach ( $crons as $time => $cron ) {
			foreach ( $cron as $hook => $dings ) {
				if ( ! in_array( $hook, array( 'sctv_schedule_remove_sale_product', 'sctv_schedule_sale_product' ) ) ) {
					continue;
				}
				$schedule = $events[ $time ] ?? array();
				foreach ( $dings as $sig => $data ) {
					$term = array_values( $data['args'] );
					$ids  = is_array( $term ) && is_array( $term[0] ) ? $term[0] : $term ;
					if ( array_key_exists( $hook, $schedule ) ) {
						$schedule[ $hook ]= array(wp_parse_args($ids,$schedule[$hook][0] ?? array()));
					} else {
						$schedule[ $hook ] = array($ids);
					}
				}
				$events[ $time ] = $schedule;
			}
		}
		if ( empty( $events ) ) {
			add_option( 'sctv_schedule_sales', array() );
		} else {
			wp_unschedule_hook( 'sctv_schedule_sale_product' );
			wp_unschedule_hook( 'sctv_schedule_remove_sale_product' );
			$schedule_time = array();
			foreach ( $events as $time => $time_data ) {
				foreach ( $time_data as $hook => $hook_data ) {
					wp_schedule_single_event( $time, $hook, $hook_data );
					$schedule_time[] = array(
						'time' => $time,
						'hook' => $hook,
						'arg'  => $hook_data,
					);
				}
			}
			add_option( 'sctv_schedule_sales', $schedule_time );
		}
	}

	public function sctv_schedule_time_shortcodes( $ids = array() ) {
		if ( empty( $ids ) ) {
			return;
		}
		foreach ( $ids as $id ) {
			$this->sctv_schedule_time_shortcode( $id );
		}
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
		if ( is_plugin_active( 'wp-fastest-cache/wpFastestCache.php' ) ) {
			$cache = new WpFastestCache();
			$cache->deleteCache( true );
		}
		do_action( 'litespeed_purge_all' );
	}

	private function sctv_schedule_time_shortcode( $id ) {
		if ( ! $id ) {
			return;
		}
		global $woo_ctr_settings;
		$settings      = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$countdown_ids = $settings->get_params( 'sale_countdown_id' );
		if ( empty( $countdown_ids ) ) {
			return;
		}
		$index = array_search( $id, $countdown_ids );
		if ( $index >= 0 ) {
			$loop_enable = $settings->get_current_countdown( 'sale_countdown_loop_enable', $index );
			if ( ! $loop_enable ) {
				return;
			}
			$args               = array();
			$now                = current_time( 'timestamp' );
			$countdown_fom_date = $settings->get_params( 'sale_countdown_fom_date' );
			$countdown_fom_time = $settings->get_params( 'sale_countdown_fom_time' );
			$countdown_to_date  = $settings->get_params( 'sale_countdown_to_date' );
			$countdown_to_time  = $settings->get_params( 'sale_countdown_to_time' );
			$loop_time_val      = (int) $settings->get_current_countdown( 'sale_countdown_loop_time_val', $index );
			if ( $loop_time_val ) {
				$loop_time_type = $settings->get_current_countdown( 'sale_countdown_loop_time_type', $index );
				$loop_time_val  = $this->get_loop_time_val( $loop_time_val, $loop_time_type );
			}
			$countdown_time_start = strtotime( $countdown_fom_date[ $index ] ) + woo_ctr_time( $countdown_fom_time[ $index ] );
			$countdown_time_end   = strtotime( $countdown_to_date[ $index ] ) + woo_ctr_time( $countdown_to_time[ $index ] );
			$countdown_time       = $countdown_time_end - $countdown_time_start;
			if ( $countdown_time_end < $now ) {
				$missed                 = floor( ( $now - $countdown_time_end ) / ( $countdown_time + $loop_time_val ) );
				$countdown_time_start_t = $countdown_time_end + $missed * ( $countdown_time + $loop_time_val ) + $loop_time_val;
			} else {
				$countdown_time_start_t = $countdown_time_end + $loop_time_val;
			}
			$countdown_time_end_t = $countdown_time_start_t + $countdown_time;
			set_transient( 'sales_countdown_timer_params_' . $id, array( 'sale_countdown_fom' => $countdown_time_start_t, 'sale_countdown_to' => $countdown_time_end_t ), $countdown_time_end_t );
			$form_date                       = date( 'Y-m-d', $countdown_time_start_t );
			$form_time                       = date( 'H:i', $countdown_time_start_t );
			$to_date                         = date( 'Y-m-d', $countdown_time_end_t );
			$to_time                         = date( 'H:i', $countdown_time_end_t );
			$countdown_fom_date[ $index ]    = $form_date;
			$countdown_fom_time[ $index ]    = $form_time;
			$countdown_to_date[ $index ]     = $to_date;
			$countdown_to_time[ $index ]     = $to_time;
			$args['sale_countdown_fom_date'] = $countdown_fom_date;
			$args['sale_countdown_fom_time'] = $countdown_fom_time;
			$args['sale_countdown_to_date']  = $countdown_to_date;
			$args['sale_countdown_to_time']  = $countdown_to_time;
			$args                            = wp_parse_args( $args, get_option( 'sales_countdown_timer_params', $woo_ctr_settings ) );
			$woo_ctr_settings                = $args;
			update_option( 'sales_countdown_timer_params', $args );
			$gmt_offset       = get_option( 'gmt_offset' );
			$date_on_sale_to1 = ( $countdown_time_end_t - $gmt_offset * 3600 ) > 0 ? intval( $countdown_time_end_t - $gmt_offset * 3600 ) : 0;

			$schedule = get_option( 'sctv_schedule_time_shortcode', array() );
			if ( ! empty( $schedule ) && array_key_exists( $date_on_sale_to1, $schedule ) ) {
				$schedule_arg = $schedule[ $date_on_sale_to1 ];
				wp_unschedule_event( $date_on_sale_to1, 'sctv_schedule_time_shortcode', array( $schedule_arg ) );
				$schedule_arg[]                = $id;
				$schedule[ $date_on_sale_to1 ] = array_unique( $schedule_arg );
				wp_schedule_single_event( $date_on_sale_to1, 'sctv_schedule_time_shortcode', array( array_unique( $schedule_arg ) ) );
			} else {
				$schedule[ $date_on_sale_to1 ] = array( $id );
				wp_schedule_single_event( $date_on_sale_to1, 'sctv_schedule_time_shortcode', array( array( $id ) ) );
			}
			update_option( 'sctv_schedule_time_shortcode', $schedule );
		}
	}

	public function sctv_schedule_sale_products( $ids = array() ) {
		if ( empty( $ids ) ) {
			return;
		}
		foreach ( $ids as $id ) {
			$this->sctv_schedule_sale_product( $id );
		}
	}

	public function sctv_schedule_remove_sale_products( $ids = array() ) {
		if ( empty( $ids ) ) {
			return;
		}
		foreach ( $ids as $id ) {
			$this->sctv_schedule_remove_sale_product( $id );
		}
	}

	private function sctv_schedule_sale_product( $id ) {
		if ( ! $id || ! wc_get_product( $id ) ) {
			return;
		}
		$product    = wc_get_product( $id );
		$sale_price =get_post_meta($id,'_sale_price',true);
		if ( ! $sale_price ) {
			return;
		}
		$product->set_price( $sale_price );
		$product->save();
		update_post_meta( $id, '_price', $sale_price );

	}

	private function sctv_schedule_remove_sale_product( $id ) {
		if ( ! $id || ! wc_get_product( $id ) ) {
			return;
		}
		$product       = wc_get_product( $id );
		$regular_price = $product->get_regular_price( 'edit' );
		$loop_enable = get_post_meta( $id, '_woo_ctr_enable_loop_countdown', true );
		if ( $loop_enable ) {
			$now               = current_time( 'timestamp' );
			$gmt_offset        = get_option( 'gmt_offset' );
			$time_offset = $now - 3600*$gmt_offset;
			$date_on_sale_from = get_post_meta($id,'_sale_price_dates_from',true);
			$date_on_sale_to = get_post_meta($id,'_sale_price_dates_to',true);
			$countdown_time    = $date_on_sale_to - $date_on_sale_from;
			$loop_time_val     = (int) get_post_meta( $id, '_woo_ctr_loop_countdown_val', true );
			if ( $loop_time_val ) {
				$loop_time_type = get_post_meta( $id, '_woo_ctr_loop_countdown_type', true );
				$loop_time_val  = $this->get_loop_time_val( $loop_time_val, $loop_time_type );
			}
			if ( $date_on_sale_to < $time_offset ) {
				$missed              = floor( ( $time_offset- $date_on_sale_to ) / ( $countdown_time + $loop_time_val ) );
				$date_on_sale_from_t = $date_on_sale_to + $missed * ( $countdown_time + $loop_time_val ) + $loop_time_val;
			} else {
				$date_on_sale_from_t = $date_on_sale_to + $loop_time_val;
			}
			$date_on_sale_to_t = $date_on_sale_from_t + $countdown_time;
			$date_on_sale_from_t1 = $date_on_sale_from_t + $gmt_offset * 3600;
			$date_on_sale_to_t1   = $date_on_sale_to_t + $gmt_offset * 3600;
			set_transient( 'sales_countdown_timer_params_product_' . $id, array( 'sale_countdown_fom' => $date_on_sale_from_t1, 'sale_countdown_to' => $date_on_sale_to_t1 ), $date_on_sale_to_t );
			$form_time            = date( 'H:i', $date_on_sale_from_t );
			$to_time              = date( 'H:i', $date_on_sale_to_t );

			$schedule             = get_option( 'sctv_schedule_sales', array() );
			$schedule_remove_sale = $schedule_sale = array();
			if ( count( $schedule ) ) {
				foreach ( $schedule as $key => $schedule_data ) {
					$schedule_time = $schedule_data['time'] ?? 0;
					$schedule_hook = $schedule_data['hook'] ?? '';
					$schedule_arg  = $schedule_data['arg'] ?? array();
					if ( $schedule_time < $time_offset|| empty( $schedule_arg ) ) {
						unset( $schedule[ $key ] );
						continue;
					}
					if ( $schedule_hook === 'sctv_schedule_sale_product' && $schedule_time == $date_on_sale_from_t ) {
						$schedule_sale = array(
							'key' => $key,
							'arg' => $schedule_arg,
						);
						continue;
					}
					if ( $schedule_hook === 'sctv_schedule_remove_sale_product' && $schedule_time == $date_on_sale_to_t ) {
						$schedule_remove_sale = array(
							'key' => $key,
							'arg' => $schedule_arg,
						);
						continue;
					}
				}
			}
			if ( $time_offset < $date_on_sale_from_t ) {
				$product->set_price( $regular_price );
				$product->save();
				update_post_meta( $id, '_price', $regular_price );
				if ( ! empty( $schedule_sale ) ) {
					wp_unschedule_event( $date_on_sale_from_t, 'sctv_schedule_sale_product', $schedule_sale['arg'] );
					$schedule_sale_arg                        = $schedule_sale['arg'][0];
					$schedule_sale_arg[]                      = $id;
					$schedule[ $schedule_sale['key'] ]['arg'] = array( array_unique( $schedule_sale_arg ) );
					wp_schedule_single_event( $date_on_sale_from_t, 'sctv_schedule_sale_product', array( array_unique( $schedule_sale_arg ) ) );
				} else {
					wp_schedule_single_event( $date_on_sale_from_t, 'sctv_schedule_sale_product', array( array( $id ) ) );
					$schedule[] = array(
						'time' => $date_on_sale_from_t,
						'hook' => 'sctv_schedule_sale_product',
						'arg'  => array( array( $id ) ),
					);
				}
			}
			$product->set_date_on_sale_from( $date_on_sale_from_t );
			$product->set_date_on_sale_to( $date_on_sale_to_t );
			$product->save();
			update_post_meta( $id, '_sale_price_dates_from', $date_on_sale_from_t );
			update_post_meta( $id, '_sale_price_dates_to', $date_on_sale_to_t );
			update_post_meta( $id, '_sale_price_times_from', $form_time );
			update_post_meta( $id, '_sale_price_times_to', $to_time );
			delete_post_meta( $id, '_woo_ctr_product_sold_quantity' );
			if ( ! empty( $schedule_remove_sale ) ) {
				wp_unschedule_event( $date_on_sale_to_t, 'sctv_schedule_remove_sale_product', $schedule_remove_sale['arg'] );
				$schedule_remove_sale_arg                        = $schedule_remove_sale['arg'][0];
				$schedule_remove_sale_arg[]                      = $id;
				$schedule[ $schedule_remove_sale['key'] ]['arg'] = array( array_unique( $schedule_remove_sale_arg ) );
				wp_schedule_single_event( $date_on_sale_to_t, 'sctv_schedule_remove_sale_product', array( array_unique( $schedule_remove_sale_arg ) ) );
			} else {
				$schedule[] = array(
					'time' => $date_on_sale_to_t,
					'hook' => 'sctv_schedule_remove_sale_product',
					'arg'  => array( array( $id ) ),
				);
				wp_schedule_single_event( $date_on_sale_to_t, 'sctv_schedule_remove_sale_product', array( array( $id ) ) );
			}
			update_option( 'sctv_schedule_sales', $schedule );
		} else {
			$product->set_price( $regular_price );
			$product->set_sale_price( '' );
			$product->set_date_on_sale_to( '' );
			$product->set_date_on_sale_from( '' );
			$product->save();
			delete_post_meta( $id, '_sale_price_times_from' );
			delete_post_meta( $id, '_sale_price_times_to' );
			delete_post_meta( $id, '_woo_ctr_product_sold_quantity' );
		}
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
}

new VI_SCT_SALES_COUNTDOWN_TIMER_Cron();