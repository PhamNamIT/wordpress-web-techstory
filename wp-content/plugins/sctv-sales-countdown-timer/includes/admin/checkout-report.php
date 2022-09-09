<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Checkout_Report {
	protected $start_date;
	protected $end_date;

	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$current_time     = current_time( 'timestamp' );
			$this->start_date = date( 'Y-m-d', ( $current_time - 30 * 86400 ) );
			$this->end_date   = date( 'Y-m-d', $current_time );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 30 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 999999 );
		}
	}

	public function admin_menu() {
		add_submenu_page(
			'sales-countdown-timer',
			esc_html__( 'Checkout Report', 'sales-countdown-timer' ),
			esc_html__( 'Checkout Report', 'sales-countdown-timer' ),
			'manage_options',
			'sales-countdown-timer-checkout-report',
			array( $this, 'settings_callback' )
		);
	}


	public function settings_callback() {
		$start_date = isset( $_REQUEST['start_date'] ) ? urldecode( $_REQUEST['start_date'] ) : $this->start_date;
		$end_date   = isset( $_REQUEST['end_date'] ) ? urldecode( $_REQUEST['end_date'] ) : $this->end_date;
		$active     = isset( $_GET['subpage'] ) ? 1 : '';
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Checkout Reporting', 'sales-countdown-timer' ) ?></h2>
            <div class="vi-ui secondary pointing menu">
                <a class="item <?php echo ! $active ? 'active' : ''; ?>"
                   href="<?php echo admin_url( 'admin.php?page=sales-countdown-timer-checkout-report' ) ?>"><?php esc_html_e( 'General', 'sales-countdown-timer' ) ?></a>
                <a class="item <?php echo $active ? 'active' : ''; ?>"
                   href="<?php echo admin_url( 'admin.php?page=sales-countdown-timer-checkout-report&subpage=orders' ) ?>"><?php esc_html_e( 'Orders', 'sales-countdown-timer' ) ?></a>
            </div>
            <form class="vi-ui form" method="post">
				<?php wp_nonce_field( 'vi_sct_checkout_report_nonce', '_vi_sct_checkout_report' ) ?>
                <div class="inline fields">
                    <div class="two field vi_sct_edit_date_field">
                        <label><?php esc_html_e( 'From', 'sales-countdown-timer' ) ?></label>
                        <input type="date" name="start_date" class="start_date"
                               value="<?php echo esc_attr( $start_date ) ?>"/>
                    </div>
                    <div class="two field vi_sct_edit_date_field">
                        <label><?php esc_html_e( 'To', 'sales-countdown-timer' ) ?></label>
                        <input type="date" name="end_date" class="end_date"
                               value="<?php echo esc_attr( $end_date ) ?>"/>
                    </div>
					<?php
					if ( $active ) {
						$choose_time = isset( $_REQUEST['display_time'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['display_time'] ) ) : '';
						?>
                        <div class="two field vi_sct_display_time_field">
                            <select name="display_time" id="display_time" class="vi-ui fluid dropdown display_time">
                                <option value="">
									<?php esc_html_e( 'Choose display time', 'sales-countdown-timer' ); ?>
                                </option>
                                <option value="minute" <?php selected( $choose_time, 'minute' ) ?>>
									<?php esc_html_e( 'Minute', 'sales-countdown-timer' ); ?>
                                </option>
                                <option value="second" <?php selected( $choose_time, 'second' ) ?>>
									<?php esc_html_e( 'Second', 'sales-countdown-timer' ); ?>
                                </option>
                            </select>
                        </div>
						<?php
					}
					?>
                    <div class="two field">
                        <input type="submit" value="<?php esc_html_e( 'VIEW', 'sales-countdown-timer' ) ?>"
                               class="button button-primary"/>
                    </div>
                </div>
                <div class="fields">
                    <div class="eleven wide field">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </form>
        </div>
		<?php
	}

	protected function time_revert( $time, $choose_time ) {
		if ( $choose_time === 'second' ) {
			return $time;
		}

		$day  = floor( $time / 86400 );
		$hour = floor( ( $time - $day * 86400 ) / 3600 );
		$min  = floor( ( $time - $day * 86400 - 3600 * $hour ) / 60 );

		return $min;
	}

	public function admin_enqueue_scripts() {
		$page        = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page === 'sales-countdown-timer-checkout-report' ) {
			$admin = 'VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Settings';
			$subpage     = isset( $_GET['subpage'] ) ? sanitize_text_field( wp_unslash( $_GET['subpage'] ) ) : '';
			$choose_time = isset( $_REQUEST['display_time'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['display_time'] ) ) : '';
			$choose_time = $choose_time ?: 'minute';
			$admin::remove_other_script();
			$admin::enqueue_style(
				array(  'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-menu', 'transition', 'vi-sct-checkout-report-css' ),
				array( 'dropdown.min.css', 'form.min.css', 'menu.min.css', 'transition.min.css', 'admin-checkout-report.css' )
			);
			$admin::enqueue_script(
				array(  'semantic-ui-dropdown', 'transition', 'vi-sct-checkout-report-chart', 'vi-sct-checkout-report-js' ),
				array( 'dropdown.min.js', 'transition.min.js', 'chart.min.js', 'admin-checkout-report.js' )
			);
			wp_enqueue_script( 'jquery-ui-datepicker' );
			$args = array(
				'subpage'              => $subpage,
				'time'                 => ucfirst( $choose_time ),
				'chart_labels'         => array(),
				'click_label'          => __( 'Click checkout button', 'sales-countdown-timer' ),
				'click_data'           => array(),
				'sct_order_label'      => __( 'Orders on sale countdown', 'sales-countdown-timer' ),
				'sct_order_data'       => array(),
				'order_label'          => __( 'Orders', 'sales-countdown-timer' ),
				'order_data'           => array(),
				'sct_time_order_label' => __( 'Orders', 'sales-countdown-timer' ),
				'sct_time_order_data'  => array(),
			);
			if ( ! $subpage ) {
				$data = $this->get_data_by_date();
				if ( $data ) {
					$labels = $count_order = $count_sct_order = array();
					if ( count( $data->label ) ) {
						global $wpdb;
						foreach ( $data->label as $label ) {
							$labels[]          = date( "M d", $label );
							$query_date        = date( 'Y-m-d', $label );
							$query_sct_orders  = "SELECT  count(wp_posts.ID) FROM  {$wpdb->posts} AS wp_posts LEFT JOIN {$wpdb->postmeta} AS wp_postmeta ON wp_posts.ID=wp_postmeta.post_id ";
							$query_sct_orders  .= " WHERE wp_posts.post_type= 'shop_order' AND wp_postmeta.meta_key='sctv_checkout_countdown_info' AND wp_posts.post_date  BETWEEN '{$query_date} 00:00:00' AND '{$query_date} 23:59:59'";
							$count_sct_order[] = $wpdb->get_var( $query_sct_orders );
							$query_orders      = "SELECT  count(wp_posts.ID) FROM  {$wpdb->posts} AS wp_posts WHERE wp_posts.post_type= 'shop_order' AND wp_posts.post_date  BETWEEN '{$query_date} 00:00:00' AND '{$query_date} 23:59:59'";
							$count_order[]     = $wpdb->get_var( $query_orders );
						}
					}
					$counts_clicked = array();

					if ( count( $data->data ) && is_array( $data->data ) ) {
						foreach ( $data->data as $count ) {
							$counts_clicked[] = count( $count );
						}
					}
					$args['chart_labels']   = $labels;
					$args['click_data']     = $counts_clicked;
					$args['sct_order_data'] = $count_sct_order;
					$args['order_data']     = $count_order;
				}
			} elseif ( $subpage === 'orders' ) {
				$data = $this->get_data_by_countdown_time( $choose_time );
				if ( $data ) {
					if ( ! empty( $data['labels'] ) ) {
						$labels = array();
						switch ( $choose_time ) {
							case 'second':
								foreach ( $data['labels'] as $label ) {
									$labels[] = $label . ' ' . $choose_time;
								}
								break;
							default:
								foreach ( $data['labels'] as $label ) {
									$labels[] = $label . ' - ' . ( $label + 1 ) . ' ' . $choose_time;
								}
						}
						$args['chart_labels'] = $labels;
					}
					if ( ! empty( $data['data'] ) ) {
						$args['sct_time_order_data'] = $data['data'];
					}
				}
			}
			wp_localize_script( 'vi-sct-checkout-report-js', 'vi_sct_chart_params', $args );
		}
	}

	private function get_data_by_countdown_time1( $choose_time ) {
		$settings   = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$time_clear = intval( $settings->get_params( 'checkout_countdown_history_time' ) );
		$start_date = $clear_end = current_time( "Y-m-d" ) . " - {$time_clear} days";
		$end_date   = $this->end_date;
		if ( isset( $_POST['_vi_sct_checkout_report'] ) ) {
			if ( wp_verify_nonce( $_POST['_vi_sct_checkout_report'], 'vi_sct_checkout_report_nonce' ) ) {
				$start_date = isset( $_POST['start_date'] ) ? urldecode( $_POST['start_date'] ) : $this->start_date;
				$end_date   = isset( $_POST['end_date'] ) ? urldecode( $_POST['end_date'] ) : $this->end_date;
				$start_date = strtotime( $start_date ) < strtotime( $clear_end ) ? $clear_end : $start_date;
			}
		}
		$args               = array(
			'post_type'      => 'shop_order',
			'post_status'    => array(
				'wc-completed',
				'wc-on-hold',
				'wc-pending',
				'wc-processing',
				'wc-failed',
				'wc-refunded',
				'wc-cancelled'
			),
			'posts_per_page' => - 1,
			'meta_key'       => 'sctv_checkout_countdown_info',
		);
		$args['date_query'] = array(
			array(
				'after'     => $start_date . ' 00:00:00',
				'inclusive' => true,
				//(boolean) - For after/before, whether exact value should be matched or not'.
				'compare'   => '<=',
				//(string) - Possible values are '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS' (only in WP >= 3.5), and 'NOT EXISTS' (also only in WP >= 3.5). Default value is '='
				'column'    => 'post_date',
				//(string) - Column to query against. Default: 'post_date'.
				'relation'  => 'AND',
				//(string) - OR or AND, how the sub-arrays should be compared. Default: AND.
			),
			array(
				'before'    => $end_date . ' 23:59:59',
				'inclusive' => true,
				//(boolean) - For after/before, whether exact value should be matched or not'.
				'compare'   => '<=',
				//(string) - Possible values are '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS' (only in WP >= 3.5), and 'NOT EXISTS' (also only in WP >= 3.5). Default value is '='
				'column'    => 'post_date',
				//(string) - Column to query against. Default: 'post_date'.
				'relation'  => 'AND',
				//(string) - OR or AND, how the sub-arrays should be compared. Default: AND.
			),
		);
		$result             = $count_orders = $labels = array();
		$the_query          = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$order_id        = get_the_ID();
				$countdown_info  = get_post_meta( $order_id, 'sctv_checkout_countdown_info', true );
				$countdown_timer = $this->time_revert( $countdown_info['time'], $choose_time ) ?: 0;
				if ( in_array( $countdown_timer, $labels ) ) {
					$count_orders[ $countdown_timer ] ++;
				} else {
					$labels[]                         = $countdown_timer;
					$count_orders[ $countdown_timer ] = 1;
				}
			}
		}
		wp_reset_postdata();
		ksort( $count_orders );
		$result['labels'] = array_keys( $count_orders );
		$result['data']   = array_values( $count_orders );

		return $result;
	}

	private function get_data_by_countdown_time( $choose_time ) {
		$settings   = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$time_clear = intval( $settings->get_params( 'checkout_countdown_history_time' ) );
		$start_date = $clear_end = current_time( "Y-m-d" ) . " - {$time_clear} days";
		$end_date   = $this->end_date;
		if ( isset( $_POST['_vi_sct_checkout_report'] ) ) {
			if ( wp_verify_nonce( $_POST['_vi_sct_checkout_report'], 'vi_sct_checkout_report_nonce' ) ) {
				$start_date = isset( $_POST['start_date'] ) ? urldecode( $_POST['start_date'] ) : $this->start_date;
				$end_date   = isset( $_POST['end_date'] ) ? urldecode( $_POST['end_date'] ) : $this->end_date;
				$start_date = strtotime( $start_date ) < strtotime( $clear_end ) ? $clear_end : $start_date;
			}
		}
		$args               = array(
			'post_type'      => 'shop_order',
			'post_status'    => array(
				'wc-completed',
				'wc-on-hold',
				'wc-pending',
				'wc-processing',
				'wc-failed',
				'wc-refunded',
				'wc-cancelled'
			),
			'posts_per_page' => - 1,
			'meta_key'       => 'sctv_checkout_countdown_info',
		);
		$args['date_query'] = array(
			array(
				'after'     => $start_date . ' 00:00:00',
				'inclusive' => true,
				//(boolean) - For after/before, whether exact value should be matched or not'.
				'compare'   => '<=',
				//(string) - Possible values are '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS' (only in WP >= 3.5), and 'NOT EXISTS' (also only in WP >= 3.5). Default value is '='
				'column'    => 'post_date',
				//(string) - Column to query against. Default: 'post_date'.
				'relation'  => 'AND',
				//(string) - OR or AND, how the sub-arrays should be compared. Default: AND.
			),
			array(
				'before'    => $end_date . ' 23:59:59',
				'inclusive' => true,
				//(boolean) - For after/before, whether exact value should be matched or not'.
				'compare'   => '<=',
				//(string) - Possible values are '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'BETWEEN', 'NOT BETWEEN', 'EXISTS' (only in WP >= 3.5), and 'NOT EXISTS' (also only in WP >= 3.5). Default value is '='
				'column'    => 'post_date',
				//(string) - Column to query against. Default: 'post_date'.
				'relation'  => 'AND',
				//(string) - OR or AND, how the sub-arrays should be compared. Default: AND.
			),
		);
		$result             = $count_orders = $labels = array();
		$the_query          = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$order_id        = get_the_ID();
				$countdown_info  = get_post_meta( $order_id, 'sctv_checkout_countdown_info', true );
				$countdown_timer = $this->time_revert( $countdown_info['time'], $choose_time ) ?: 0;
				if ( in_array( $countdown_timer, $labels ) ) {
					$count_orders[ $countdown_timer ] ++;
				} else {
					$labels[]                         = $countdown_timer;
					$count_orders[ $countdown_timer ] = 1;
				}
			}
		}
		wp_reset_postdata();
		ksort( $count_orders );

		$result['labels'] = array_keys( $count_orders );
		$result['data']   = array_values( $count_orders );

		return $result;
	}

	private function get_data_by_date() {
		$start_date = '';
		$end_date   = '';
		if ( isset( $_POST['_vi_sct_checkout_report'] ) ) {
			if ( wp_verify_nonce( $_POST['_vi_sct_checkout_report'], 'vi_sct_checkout_report_nonce' ) ) {
				$start_date = isset( $_POST['start_date'] ) ? urldecode( $_POST['start_date'] ) : $this->start_date;
				$end_date   = isset( $_POST['end_date'] ) ? urldecode( $_POST['end_date'] ) : $this->end_date;
				/*Convert to int*/
				$start_date = strtotime( $start_date );
				$end_date   = strtotime( $end_date );
			}
		}
		$files = $this->scan_dir( VI_SCT_SALES_COUNTDOWN_TIMER_CACHE );
		if ( ! is_array( $files ) ) {
			return false;
		}
		$data  = new stdClass();
		$files = array_map( 'intval', $files );
		asort( $files );
		$files      = array_values( $files );
		$settings   = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$time_clear = intval( $settings->get_params( 'checkout_countdown_history_time' ) );
		if ( $time_clear > 0 && count( $files ) ) {
			$clear_end = strtotime( current_time( "Y-m-d" ) . " - {$time_clear} days" );
			foreach ( $files as $k => $file ) {
				if ( $file <= $clear_end ) {
					wp_delete_file( VI_SCT_SALES_COUNTDOWN_TIMER_CACHE . $file . '.txt' );
					unset( $files[ $k ] );
				}
			}
			$files = array_values( $files );
		}
		/*Filter files*/
		if ( $start_date || $end_date ) {
			$new_arg = array();
			if ( $start_date && $end_date ) {
				foreach ( $files as $file ) {
					if ( $file >= $start_date && $file <= $end_date ) {
						$new_arg[] = $file;
					}
				}
			} elseif ( $start_date ) {
				foreach ( $files as $file ) {
					if ( $file >= $start_date ) {
						$new_arg[] = $file;
					}
				}
			} else {
				foreach ( $files as $file ) {
					if ( $file <= $end_date ) {
						$new_arg[] = $file;
					}
				}
			}

			$files = $new_arg;

			if ( count( $files ) < 1 ) {
				return false;
			}
		}

		$data->label = $files;
		$temp        = array();
		if ( count( $files ) ) {
			foreach ( $files as $file ) {
				@$content = file_get_contents( VI_SCT_SALES_COUNTDOWN_TIMER_CACHE . $file . '.txt' );
				if ( $content ) {
					$array  = explode( ',', $content );
					$temp[] = $array;
				}
			}
		}
		if ( count( $temp ) ) {
			$data->data = $temp;
		} else {
			$data->data = false;
		}

		return $data;
	}

	/**
	 * Get files in directory
	 *
	 * @param $dir
	 *
	 * @return array|bool
	 */
	private function scan_dir( $dir ) {
		$ignored = array( '.', '..', '.svn', '.htaccess', 'test-log.log' );

		$files = array();
		if ( is_dir( $dir ) ) {
			$scan_dir = scandir( $dir );
			if ( is_array( $scan_dir ) && count( $scan_dir ) ) {
				foreach ( $scan_dir as $file ) {
					if ( in_array( $file, $ignored ) ) {
						continue;
					}
					$files[ $file ] = filemtime( $dir . '/' . $file );
				}
			}

		}
		arsort( $files );
		$files = array_keys( $files );

		return ( $files ) ? $files : false;
	}
}