<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Countdown {
	protected $settings;

	function __construct() {
		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		add_action( 'admin_init', array( $this, 'check_update' ), 100 );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 10 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), PHP_INT_MAX );
		add_action( 'wp_ajax_woo_sctr_save_settings', array( $this, 'save_settings' ) );
	}
	public function save_settings(){
		$response = array(
			'status'  => 'failed'
		);
		if (isset( $_POST['woo_ctr_nonce_field'] ) && wp_verify_nonce( sanitize_text_field($_POST['woo_ctr_nonce_field']), 'woo_ctr_settings_page_save' ) ){
			global $woo_ctr_settings;
			$args = array();
			if ( isset( $_POST['save_all_changes'] ) && sanitize_text_field($_POST['save_all_changes']) ) {
				$map_arg_1 = array(
					'sale_countdown_id',
					'sale_countdown_name',
					'sale_countdown_active',
					'sale_countdown_loop_enable',
					'sale_countdown_loop_time_val',
					'sale_countdown_loop_time_type',
					'sale_countdown_fom_date',
					'sale_countdown_fom_time',
					'sale_countdown_to_date',
					'sale_countdown_to_time',
					'sale_countdown_layout',
					'sale_countdown_display_type',
					'sale_countdown_message_position',
					'sale_countdown_time_units',
					'sale_countdown_time_separator',
					'sale_countdown_datetime_format',
					'sale_countdown_datetime_format_custom_date',
					'sale_countdown_datetime_format_custom_hour',
					'sale_countdown_datetime_format_custom_minute',
					'sale_countdown_datetime_format_custom_second',
					'sale_countdown_animation_style',

					'sale_countdown_layout_fontsize',
					'sale_countdown_layout_1_background',
					'sale_countdown_layout_1_color',
					'sale_countdown_layout_1_border_color',
					'sale_countdown_layout_1_border_radius',
					'sale_countdown_layout_1_padding',
					'sale_countdown_layout_1_sticky_background',
					'sale_countdown_layout_1_sticky_color',
					'sale_countdown_layout_1_sticky_border_color',

					'sale_countdown_template_1_time_unit_position',
					'sale_countdown_template_1_time_unit_color',
					'sale_countdown_template_1_time_unit_background',
					'sale_countdown_template_1_time_unit_fontsize',
					'sale_countdown_template_1_value_color',
					'sale_countdown_template_1_value_background',
					'sale_countdown_template_1_value_border_color',
					'sale_countdown_template_1_value_border_radius',
					'sale_countdown_template_1_value_width',
					'sale_countdown_template_1_value_height',
					'sale_countdown_template_1_value_font_size',

					'sale_countdown_template_2_item_border_color',
					'sale_countdown_template_2_item_border_radius',
					'sale_countdown_template_2_item_height',
					'sale_countdown_template_2_item_width',
					'sale_countdown_template_2_value_color',
					'sale_countdown_template_2_value_background',
					'sale_countdown_template_2_value_fontsize',
					'sale_countdown_template_2_time_unit_color',
					'sale_countdown_template_2_time_unit_background',
					'sale_countdown_template_2_time_unit_fontsize',
					'sale_countdown_template_2_time_unit_position',

					'sale_countdown_template_3_value_color',
					'sale_countdown_template_3_value_background',
					'sale_countdown_template_3_value_fontsize',
					'sale_countdown_template_3_time_unit_color',
					'sale_countdown_template_3_time_unit_background',
					'sale_countdown_template_3_time_unit_fontsize',

					'sale_countdown_template_4_value_border_color1',
					'sale_countdown_template_4_value_border_color2',
					'sale_countdown_template_4_value_color',
					'sale_countdown_template_4_value_background',
					'sale_countdown_template_4_value_fontsize',
					'sale_countdown_template_4_value_border_width',
					'sale_countdown_template_4_value_diameter',
					'sale_countdown_template_4_time_unit_color',
					'sale_countdown_template_4_time_unit_background',
					'sale_countdown_template_4_time_unit_fontsize',
					'sale_countdown_template_4_time_unit_position',
					'sale_countdown_circle_smooth_animation',

					'sale_countdown_template_5_item_border_width',
					'sale_countdown_template_5_item_diameter',
					'sale_countdown_template_5_value_color',
					'sale_countdown_template_5_value_fontsize',
					'sale_countdown_template_5_time_unit_color',
					'sale_countdown_template_5_time_unit_fontsize',
					'sale_countdown_template_5_date_border_color1',
					'sale_countdown_template_5_date_border_color2',
					'sale_countdown_template_5_date_background',
					'sale_countdown_template_5_hour_border_color1',
					'sale_countdown_template_5_hour_border_color2',
					'sale_countdown_template_5_hour_background',
					'sale_countdown_template_5_minute_border_color1',
					'sale_countdown_template_5_minute_border_color2',
					'sale_countdown_template_5_minute_background',
					'sale_countdown_template_5_second_border_color1',
					'sale_countdown_template_5_second_border_color2',
					'sale_countdown_template_5_second_background',


					'sale_countdown_template_6_time_unit_position',
					'sale_countdown_template_6_value_width',
					'sale_countdown_template_6_value_height',
					'sale_countdown_template_6_value_border_radius',
					'sale_countdown_template_6_value_fontsize',
					'sale_countdown_template_6_value_color1',
					'sale_countdown_template_6_value_color2',
					'sale_countdown_template_6_value_background1',
					'sale_countdown_template_6_value_background2',
					'sale_countdown_template_6_value_box_shadow',
					'sale_countdown_template_6_value_cut_color',
					'sale_countdown_template_6_value_cut_behind',
					'sale_countdown_template_6_time_unit_color',
					'sale_countdown_template_6_time_unit_fontsize',
					'sale_countdown_template_6_time_unit_grid_gap',

					'sale_countdown_template_7_time_unit_position',
					'sale_countdown_template_7_value_width',
					'sale_countdown_template_7_value_height',
					'sale_countdown_template_7_value_border_radius',
					'sale_countdown_template_7_value_fontsize',
					'sale_countdown_template_7_value_color1',
					'sale_countdown_template_7_value_color2',
					'sale_countdown_template_7_value_background1',
					'sale_countdown_template_7_value_background2',
					'sale_countdown_template_7_value_box_shadow',
					'sale_countdown_template_7_value_cut_color',
					'sale_countdown_template_7_value_cut_behind',
					'sale_countdown_template_7_time_unit_color',
					'sale_countdown_template_7_time_unit_fontsize',
					'sale_countdown_template_7_time_unit_grid_gap',

					'sale_countdown_single_product_sticky',
					'sale_countdown_single_product_position',
					'sale_countdown_mobile_resize',
					'sale_countdown_loop_resize',
					'sale_countdown_sticky_resize',

					'sale_countdown_archive_page_enable',
					'sale_countdown_archive_page_assign',
					'sale_countdown_archive_page_position',

					'sale_countdown_progress_bar_type',
					'sale_countdown_progress_bar_order_status',
					'sale_countdown_progress_bar_position',
					'sale_countdown_progress_bar_template',
					'sale_countdown_progress_bar_message_position',
					'sale_countdown_progress_bar_template_1_background',
					'sale_countdown_progress_bar_template_1_color',
					'sale_countdown_progress_bar_template_1_message_color',
					'sale_countdown_progress_bar_template_1_border_radius',
					'sale_countdown_progress_bar_template_1_font_size',
					'sale_countdown_progress_bar_template_1_height',
					'sale_countdown_progress_bar_template_1_width',
					'sale_countdown_progress_bar_template_1_width_type',

					'sale_countdown_wrap_border_radius_in_single',
					'sale_countdown_wrap_border_color_in_single',
					'sale_countdown_progress_bar_position_in_single',
					'sale_countdown_progress_bar_message_position_in_single',

					'sale_countdown_upcoming_enable',
					'sale_countdown_upcoming_progress_bar_enable',

					'sale_countdown_sticky_width',
					'sale_countdown_sticky_time_unit_disable',
					'sale_countdown_add_to_cart_button',
				);
				$map_arg_2 = array(
					'sale_countdown_message',
					'sale_countdown_progress_bar_message',
					'sale_countdown_upcoming_message',
				);
				foreach ( $map_arg_1 as $item ) {
					$args[ $item ] = isset( $_POST[ $item ] ) ? villatheme_sanitize_fields( $_POST[ $item ] ) : array();
				}
				foreach ( $map_arg_2 as $item ) {
					$args[ $item ] = isset( $_POST[ $item ] ) ? array_map( 'wp_kses_post', $_POST[ $item ] ) : array();
				}

				if ( ! count( $args['sale_countdown_name'] ) ) {
					$response['message'] = __( 'Can not remove all Countdown timer settings.',
						'sales-countdown-timer' );
					wp_send_json( $response );
					die;
				} else {
					if ( count( $args['sale_countdown_name'] ) != count( array_unique( $args['sale_countdown_name'] ) ) ) {
						$response['message'] = __( 'Names are unique.', 'sales-countdown-timer' );
						wp_send_json( $response );
						die;
					}
					foreach ( $args['sale_countdown_name'] as $key => $name ) {
						if ( ! $name ) {
							$response['message'] = __( 'Names can not be empty.', 'sales-countdown-timer' );
							wp_send_json( $response );
							die;
						}
					}
				}

				$countdown_ids_old = $woo_ctr_settings['sale_countdown_id'] ?? array();
				$countdown_ids     = $args['sale_countdown_id'] ?: array();
				wp_unschedule_hook( 'sctv_schedule_time_shortcode' );
				delete_transient( 'sctv_schedule_time_shortcode' );
				$countdown_diff = array_diff( $countdown_ids_old, $countdown_ids );
				if ( count( $countdown_diff ) ) {
					foreach ( $countdown_diff as $item ) {
						delete_transient( 'sales_countdown_timer_params_' . $item );
					}
				}
				$schedule_shortcode = array();
				if ( $count_ids = count( $countdown_ids ) ) {
					$gmt_offset = get_option( 'gmt_offset' );
					$schedule   = array();
					for ( $i = 0; $i < $count_ids; $i ++ ) {
						delete_transient( 'sales_countdown_timer_params_' . $countdown_ids[ $i ] );
						if ( ! empty( $args['sale_countdown_loop_enable'][ $i ] ) ) {
							$date_on_sale_to                 = isset( $args['sale_countdown_to_date'][ $i ] ) ? strtotime( $args['sale_countdown_to_date'][ $i ] ) : 0;
							$sale_price_times_to             = isset( $args['sale_countdown_to_time'][ $i ] ) ? sanitize_text_field( $args['sale_countdown_to_time'][ $i ] ) : '00:00';
							$time_to                         = woo_ctr_time( $sale_price_times_to );
							$date_on_sale_to                 += $time_to;
							$date_on_sale_to1                = ( $date_on_sale_to - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_to - $gmt_offset * 3600 ) : 0;
							$schedule[ $date_on_sale_to1 ][] = $countdown_ids[ $i ];
						}
					}
					if ( count( $schedule ) ) {
						$schedule_shortcode = $schedule;
						foreach ( $schedule as $time => $value ) {
							wp_schedule_single_event( $time, 'sctv_schedule_time_shortcode', array( $value ) );
						}
					}
				}
				update_option( 'sctv_schedule_time_shortcode', $schedule_shortcode );
			}
			if ( isset( $_POST['save_update_key'] ) && $_POST['save_update_key'] ) {
				delete_transient( '_site_transient_update_plugins' );
				delete_transient( 'villatheme_item_15899' );
				delete_option( 'sales-countdown-timer_messages' );
				$args['update_key'] = isset( $_POST['update_key'] ) ? sanitize_text_field( $_POST['update_key'] ) : '';
			}
			$args             = wp_parse_args( $args, get_option( 'sales_countdown_timer_params', $woo_ctr_settings ) );
			$woo_ctr_settings = $args;
			update_option( 'sales_countdown_timer_params', $args );
			$response['status']  = 'successful';
			$response['data']    = $args;
			$response['message'] = __( 'Data saved.', 'sales-countdown-timer' );
			wp_send_json( $response );
		}
    }
	public function check_update() {
		/**
		 * Check update
		 */
		$key = $this->settings->get_params( 'update_key' );
		/*Check update*/
		if ( class_exists( 'VillaTheme_Plugin_Check_Update' ) ) {
			$setting_url = admin_url( 'admin.php?page=sales-countdown-timer' );
			new VillaTheme_Plugin_Check_Update (
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION,                    // current version
				'https://villatheme.com/wp-json/downloads/v3',  // update path
				'sctv-sales-countdown-timer/sctv-sales-countdown-timer.php',                  // plugin file slug
				'sales-countdown-timer', '15899', $key, $setting_url
			);
			new VillaTheme_Plugin_Updater( 'sctv-sales-countdown-timer/sctv-sales-countdown-timer.php', 'sales-countdown-timer', $setting_url );
		}
	}
	public function admin_menu() {
		add_menu_page(
			__( 'Sales Countdown Timer', 'sales-countdown-timer' ),
			__( 'Countdown Timer', 'sales-countdown-timer' ),
			'manage_options',
			'sales-countdown-timer',
			array( $this, 'settings_callback' ),
			'dashicons-clock',
			2 );
	}
	public function settings_callback() {
		$this->settings = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$div_class      = is_rtl() ? 'wrap woo-sctr-wrap woo-sctr-wrap-rtl' : 'wrap woo-sctr-wrap';
		?>
        <div class="<?php echo esc_attr( $div_class ); ?>">
            <h2><?php esc_html_e( 'Sales Countdown Timer', 'sales-countdown-timer' ) ?></h2>
            <div id="woo-scrt-message-error" class="error woo-sctr-countdown-hidden"></div>
            <form class="vi-ui form" method="post">
				<?php
				wp_nonce_field( 'woo_ctr_settings_page_save', '_woo_ctr_settings_page_nonce_field' );
				?>
                <div class="vi-ui vi-ui-main top tabular attached menu">
                    <a class="item" data-tab="general">
						<?php esc_html_e( 'General Settings', 'sales-countdown-timer' ); ?>
                    </a>
                    <a class="item active" data-tab="countdown">
						<?php esc_html_e( 'Countdown Timer', 'sales-countdown-timer' ); ?>
                    </a>
                    <a class="item" data-tab="update">
						<?php esc_html_e( 'Update', 'sales-countdown-timer' ); ?>
                    </a>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="general">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
							<?php
							$custom_css = $this->settings->get_params( 'custom_css' );
							?>
                            <th>
                                <label for="woo-stcr-custom-css">
									<?php esc_html_e( 'Custom css', 'sales-countdown-timer' ) ?>
                                </label>
                            </th>
                            <td>
                                    <textarea name="custom_css" id="woo-stcr-custom-css" class="woo-stcr-custom-css"
                                              rows="10"><?php echo esc_textarea( $custom_css ) ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment active" data-tab="countdown">
					<?php
					$countdown_timers_id = $this->settings->get_params( 'sale_countdown_id' );
					if ( ! $countdown_timers_id || ! is_array( $countdown_timers_id ) || empty( $countdown_timers_id ) ) {
						$countdown_timers_id = array( 'salescountdowntimer' );
					}
					$check_woo_active = false;
					if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
						$check_woo_active = true;
					}
					if ( $check_woo_active && get_transient( '_sales_countdown_timer_demo_product_init' ) ) {
						$sale_products     = get_transient( 'wc_products_onsale' );
						$default_countdown = count( $countdown_timers_id ) ? $countdown_timers_id[0] : 'salescountdowntimer';
						$now               = current_time( 'timestamp', true );
						$product_url       = '';
						if ( false === $sale_products ) {
							$products_args = array(
								'post_type'      => 'product',
								'status'         => 'publish',
								'posts_per_page' => - 1,
								'meta_query'     => array(
									'relation' => 'AND',
									array(
										'key'     => '_sale_price',
										'value'   => '',
										'compare' => '!=',
									),
									array(
										'key'     => '_sale_price_dates_to',
										'value'   => $now,
										'compare' => '>',
									),
								),
							);
							$the_query     = new WP_Query( $products_args );
							if ( $the_query->have_posts() ) {
								while ( $the_query->have_posts() ) {
									$the_query->the_post();
									$product_id = get_the_ID();
									if ( ! $product_url ) {
										$product_url = get_permalink( $product_id );
									}
								}
							}
							wp_reset_postdata();
						} elseif ( is_array( $sale_products ) && count( $sale_products ) ) {
							foreach ( $sale_products as $product_id ) {
								if ( ! $product_url ) {
									$_woo_ctr_select_countdown_timer = get_post_meta($product_id,'_woo_ctr_select_countdown_timer', true);
									if (empty($_woo_ctr_select_countdown_timer)){
										update_post_meta( $product_id, '_woo_ctr_select_countdown_timer', $default_countdown );
									}
									$product_url = get_permalink( $product_id );
								}
							}
						}
						if ( $product_url ) {
							echo esc_html__( 'See your very first sales countdown timer ',
									'sales-countdown-timer' ) . '<a href="' . $product_url . '" target="_blank">' .
                                 esc_html__( 'here.', 'sales-countdown-timer' ) . '</a>';
							delete_transient( '_sales_countdown_timer_demo_product_init' );
						}
					}
					foreach ( $countdown_timers_id as $i => $id ) {
						$countdown_active              = $this->settings->get_current_countdown( 'sale_countdown_active', $i );
						$countdown_name                = $this->settings->get_current_countdown( 'sale_countdown_name', $i );
						$countdown_form_date           = $this->settings->get_current_countdown( 'sale_countdown_fom_date', $i );
						$countdown_form_time           = $this->settings->get_current_countdown( 'sale_countdown_fom_time', $i );
						$countdown_to_date             = $this->settings->get_current_countdown( 'sale_countdown_to_date', $i );
						$countdown_to_time             = $this->settings->get_current_countdown( 'sale_countdown_to_time', $i );
						$countdown_time_mobile_resize  = $this->settings->get_current_countdown( 'sale_countdown_mobile_resize', $i );
						$countdown_time_loop_resize    = $this->settings->get_current_countdown( 'sale_countdown_loop_resize', $i );
						$countdown_time_sticky_resize  = $this->settings->get_current_countdown( 'sale_countdown_sticky_resize', $i );
						$sale_countdown_loop_enable    = $this->settings->get_current_countdown( 'sale_countdown_loop_enable', $i );
						$sale_countdown_loop_time_val  = $this->settings->get_current_countdown( 'sale_countdown_loop_time_val', $i );
						$sale_countdown_loop_time_type = $this->settings->get_current_countdown( 'sale_countdown_loop_time_type', $i );
						?>
                        <div class="woo-sctr-accordion-wrap woo-sctr-accordion-wrap-<?php echo esc_attr( $id ); ?> vi-ui segment" data-accordion_id="<?php echo esc_attr( $id ); ?>">
                            <div class="woo-sctr-accordion">
                                <div class="vi-ui toggle checkbox">
                                    <input type="hidden" name="sale_countdown_active[]" class="woo-sctr-active" value="<?php esc_attr_e( $countdown_active ); ?>">
                                    <input type="checkbox" class="woo-sctr-active" <?php checked( $countdown_active, 1 ); ?>><label>
                                </div>
                                <span class="woo-sctr-accordion-name"><?php echo esc_html( $countdown_name ); ?></span>
                                <span class="woo-sctr-short-description">
                                    <span class="woo-sctr-short-description-from"><?php echo esc_html__( 'From: ', 'sales-countdown-timer' ) ?>
										<span class="woo-sctr-short-description-from-date"><?php echo esc_html( $countdown_form_date ); ?></span>&nbsp;
                                        <span class="woo-sctr-short-description-from-time"><?php echo esc_html( $countdown_form_time ); ?></span>
                                    </span>
                                    <span class="woo-sctr-short-description-to"><?php echo esc_html__( 'To: ', 'sales-countdown-timer' ) ?>
										<span class="woo-sctr-short-description-to-date"><?php echo esc_html( $countdown_to_date ); ?></span>&nbsp;
                                        <span class="woo-sctr-short-description-to-time"><?php echo esc_html( $countdown_to_time ); ?></span>
                                    </span>
                                </span>
                                <div class="woo-sctr-shortcode-text">
                                    <span><?php echo esc_html__( 'Shortcode: ', 'sales-countdown-timer' ) ?></span>
                                    <span class="woo-sctr-shortcode-show"><?php echo sprintf( '[sales_countdown_timer id="%s"]', esc_html( $id ) ); ?></span>
                                    <span class="woo-sctr-shortcode-copied woo-sctr-countdown-hidden description">
                                        <?php esc_html_e( 'Shortcode copied to clipboard!', 'sales-countdown-timer' ); ?>
                                    </span>
                                </div>
                                <span class="woo-sctr-button-edit">
                                    <span class="woo-sctr-short-description-copy-shortcode vi-ui button"><?php esc_html_e( 'Copy shortcode', 'sales-countdown-timer' ); ?></span>
                                    <span class="woo-sctr-button-edit-duplicate vi-ui positive button"><?php esc_html_e( 'Duplicate', 'sales-countdown-timer' ) ?></span>
                                    <span class="woo-sctr-button-edit-remove vi-ui negative button"><?php esc_html_e( 'Remove', 'sales-countdown-timer' ) ?></span>
                                </span>
                            </div>
                            <div class="woo-sctr-panel vi-ui styled fluid accordion">
                                <div class="title<?php echo esc_attr( $countdown_active ? ' active' : '' ); ?>">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'General settings', 'sales-countdown-timer' ) ?>
                                </div>
                                <div class="content<?php echo esc_attr( $countdown_active ? ' active' : '' ); ?>">
                                    <div class="field">
                                        <label><?php esc_html_e( 'Name', 'sales-countdown-timer' ) ?></label>
                                        <input type="hidden" name="sale_countdown_id[]" class="woo-sctr-id" value="<?php echo esc_attr( $id ); ?>">
                                        <input type="text" name="sale_countdown_name[]" class="woo-sctr-name" value="<?php echo $countdown_name; ?>">
                                    </div>
                                    <h4 class="vi-ui dividing header">
                                        <label><?php esc_html_e( 'Schedule the time for shortcode usage', 'sales-countdown-timer' ) ?></label>
                                    </h4>
                                    <div class="field" data-tooltip="<?php esc_attr_e( 'These values are used for shortcode only. To schedule sale for product please go to admin product.', 'sales-countdown-timer' ) ?>">
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'From', 'sales-countdown-timer' ) ?></label>
                                                <div class="two fields">
                                                    <div class="field">
                                                        <input type="date"
                                                               name="sale_countdown_fom_date[]"
                                                               class="woo-sctr-sale-from-date woo-sctr-sale-date "
                                                               value="<?php echo esc_attr( $countdown_form_date ); ?>">
                                                    </div>
                                                    <div class="field">
                                                        <input type="time"
                                                               name="sale_countdown_fom_time[]"
                                                               class="woo-sctr-sale-from-time"
                                                               value="<?php echo esc_attr( $countdown_form_time ?: '00:00' ) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'To', 'sales-countdown-timer' ) ?></label>
                                                <div class="two fields">
                                                    <div class="field">
                                                        <input type="date" name="sale_countdown_to_date[]" class="woo-sctr-sale-to-date woo-sctr-sale-date"
                                                               value="<?php echo esc_attr( $countdown_to_date ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <input type="time" name="sale_countdown_to_time[]"
                                                               class="woo-sctr-sale-to-time"
                                                               value="<?php echo esc_attr( $countdown_to_time ?: '00:00' ); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Countdown evergreen', 'sales-countdown-timer' ); ?></label>
                                                <div class="vi-ui toggle checkbox">
                                                    <input type="hidden" name="sale_countdown_loop_enable[]"
                                                           class="woo-sctr-sale_countdown_loop_enable"
                                                           value="<?php echo esc_attr( $sale_countdown_loop_enable ); ?>">
                                                    <input type="checkbox" <?php checked( $sale_countdown_loop_enable, '1' ) ?>>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Restart countdown after', 'sales-countdown-timer' ); ?></label>
                                                <div class="vi-ui right labeled action input">
                                                    <input type="number" name="sale_countdown_loop_time_val[]"
                                                           step="1" min="0"
                                                           class="woo-sctr-sale_countdown_loop_time_val"
                                                           value="<?php echo esc_attr( $sale_countdown_loop_time_val ); ?>">
                                                    <select name="sale_countdown_loop_time_type[]"
                                                            class="vi-ui dropdown woo-sctr-sale_countdown_loop_time_type">
                                                        <option value="day" <?php selected( $sale_countdown_loop_time_type, 'day' ) ?>>
															<?php esc_html_e( 'Days', 'sales-countdown-timer' ); ?>
                                                        </option>
                                                        <option value="hour" <?php selected( $sale_countdown_loop_time_type, 'hour' ) ?>>
															<?php esc_html_e( 'Hours', 'sales-countdown-timer' ); ?>
                                                        </option>
                                                        <option value="min" <?php selected( $sale_countdown_loop_time_type, 'min' ) ?>>
															<?php esc_html_e( 'Minutes', 'sales-countdown-timer' ); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="title">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'Design', 'sales-countdown-timer' ) ?>
                                </div>
                                <div class=" content">
									<?php
									$countdown_message                       = $this->settings->get_current_countdown( 'sale_countdown_message', $i );
									$text                                    = explode( '{countdown_timer}', $countdown_message );
									$text_before                             = $text[0] ?? '';
									$text_after                              = $text[1] ?? '';
									$countdown_message_position              = $this->settings->get_current_countdown( 'sale_countdown_message_position', $i );
									$countdown_time_units                    = $this->settings->get_current_countdown( 'sale_countdown_time_units', $i );
									$countdown_time_units_arg                = $countdown_time_units ? explode( ',', $countdown_time_units ) : array();
									$countdown_time_separator                = $this->settings->get_current_countdown( 'sale_countdown_time_separator', $i );
									$countdown_datetime_format               = $this->settings->get_current_countdown( 'sale_countdown_datetime_format', $i );
									$countdown_datetime_format_custom_date   = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_date', $i );
									$countdown_datetime_format_custom_hour   = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_hour', $i );
									$countdown_datetime_format_custom_minute = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_minute', $i );
									$countdown_datetime_format_custom_second = $this->settings->get_current_countdown( 'sale_countdown_datetime_format_custom_second', $i );
									$countdown_animation_style               = $this->settings->get_current_countdown( 'sale_countdown_animation_style', $i );
									$sale_countdown_circle_smooth_animation  = $this->settings->get_current_countdown( 'sale_countdown_circle_smooth_animation', $i );
									$sale_countdown_sticky_width             = $this->settings->get_current_countdown( 'sale_countdown_sticky_width', $i );
									$sale_countdown_sticky_time_unit_disable = $this->settings->get_current_countdown( 'sale_countdown_sticky_time_unit_disable', $i );
									$countdown_layout                        = $this->settings->get_current_countdown( 'sale_countdown_layout', $i );
									$countdown_display_type                  = (int) $this->settings->get_current_countdown( 'sale_countdown_display_type', $i );
									$countdown_display_type_args             = array(
										'1' => __( 'Square Countdown Timer', 'sales-countdown-timer' ),
										'2' => __( 'Square Countdown Timer 1', 'sales-countdown-timer' ),
										'3' => __( 'Basic Countdown Timer', 'sales-countdown-timer' ),
										'4' => __( 'Circular Countdown Timer', 'sales-countdown-timer' ),
										'5' => __( 'Circular Countdown Timer 1', 'sales-countdown-timer' ),
										'6' => __( 'Sleek Countdown Timer', 'sales-countdown-timer' ),
										'7' => __( 'Sleek Countdown Timer 1', 'sales-countdown-timer' ),
									);
									?>
                                    <div class="field">
                                        <label><?php esc_html_e( 'Message', 'sales-countdown-timer' ) ?></label>
                                        <textarea rows="3" name="sale_countdown_message[]" class="woo-sctr-message"><?php echo wp_unslash( $countdown_message ); ?></textarea>
                                    </div>
                                    <div class="field">
                                        <p>{countdown_timer} - <?php esc_html_e( 'The countdown timer that you set on the design tab', 'sales-countdown-timer' ) ?></p>
                                        <p class="woo-sctr-warning-message-countdown-timer<?php echo esc_attr( count( $text ) >= 2 ? ' woo-sctr-hidden' : '' ); ?>">
											<?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}', 'sales-countdown-timer' ) ?>
                                        </p>
                                    </div>
                                    <div class="field">
                                        <label><?php esc_html_e( 'Reduce the size of countdown timer on', 'sales-countdown-timer' ) ?></label>
                                        <div class="equal width fields">
                                            <div class="inline field">
                                                <div class="vi-ui right labeled  input">
                                                    <div class="vi-ui basic label">
														<?php esc_html_e( 'Products list', 'sales-countdown-timer' ) ?>
                                                    </div>
                                                    <input type="number" name="sale_countdown_loop_resize[]" min="30" max="100" class="woo-sctr-single-loop-resize"
                                                           value="<?php echo esc_attr( $countdown_time_loop_resize ); ?>">
                                                    <div class="vi-ui basic label">%</div>
                                                </div>
                                            </div>
                                            <div class="inline field">
                                                <div class="vi-ui right labeled  input">
                                                    <div class="vi-ui basic label">
														<?php esc_html_e( 'Mobile', 'sales-countdown-timer' ) ?>
                                                    </div>
                                                    <input type="number" name="sale_countdown_mobile_resize[]" min="30" max="100" class="woo-sctr-single-product-resize"
                                                           value="<?php echo esc_attr( $countdown_time_mobile_resize ); ?>">
                                                    <div class="vi-ui basic label">%</div>
                                                </div>
                                            </div>
                                            <div class="inline field">
                                                <div class="vi-ui right labeled  input">
                                                    <div class="vi-ui basic label">
														<?php esc_html_e( 'Sticky', 'sales-countdown-timer' ) ?>
                                                    </div>
                                                    <input type="number" name="sale_countdown_sticky_resize[]" min="30" max="100" class="woo-sctr-single-sticky-resize"
                                                           value="<?php echo esc_attr( $countdown_time_sticky_resize ); ?>">
                                                    <div class="vi-ui basic label">%</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Display type', 'sales-countdown-timer' ) ?></label>
                                            <input type="hidden" value="1" name="sale_countdown_layout[]">
                                            <select name="sale_countdown_display_type[]" class="woo-sctr-sal-countdown-display-type vi-ui dropdown">
												<?php
												foreach ( $countdown_display_type_args as $item_id => $item_title ) {
													?>
                                                    <option value="<?php echo esc_attr( $item_id ) ?>" <?php selected( $item_id, $countdown_display_type ) ?> ><?php echo esc_html( $item_title ); ?></option>
													<?php
												}
												?>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Message position', 'sales-countdown-timer' ) ?></label>
                                            <select name="sale_countdown_message_position[]" class="woo-sctr-message-position vi-ui dropdown">
                                                <option value="default" <?php selected( $countdown_message_position, 'default' ); ?>>
													<?php esc_html_e( 'Default', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="inline_countdown" <?php selected( $countdown_message_position, 'inline_countdown' ); ?>>
													<?php esc_html_e( 'The same line as the countdown timer', 'sales-countdown-timer' ) ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field"
                                             data-tooltip="<?php esc_attr_e( 'The option is used for shortcode only.Leave blank to show all time units on countdown timers. Don\'t apply for \'Sleek Countdown Timer 1\'', 'sales-countdown-timer' ) ?>">
                                            <input type="hidden" name="sale_countdown_time_units[]" value="<?php echo esc_attr( $countdown_time_units ) ?>" class="woo-sctr-time-units-display">
                                            <label><?php esc_html_e( 'Time units', 'sales-countdown-timer' ) ?></label>
                                            <select name="woo_ctr_datetime_unit_display_select" id="woo-sctr-time-units-display-<?php echo esc_attr( $i ); ?>"
                                                    class="vi-ui fluid dropdown woo-sctr-time-units-display-select" multiple>
                                                <option value="day" <?php selected( in_array( 'day', $countdown_time_units_arg ), true ); ?>>
													<?php esc_html_e( 'Day', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="hour" <?php selected( in_array( 'hour', $countdown_time_units_arg ), true ); ?>>
													<?php esc_html_e( 'Hour', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="minute" <?php selected( in_array( 'minute', $countdown_time_units_arg ), true ); ?>>
													<?php esc_html_e( 'Minute', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="second" <?php selected( in_array( 'second', $countdown_time_units_arg ), true ); ?>>
													<?php esc_html_e( 'Second', 'sales-countdown-timer' ) ?></option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Time separator', 'sales-countdown-timer' ) ?></label>
                                            <select name="sale_countdown_time_separator[]" class="woo-sctr-time-separator vi-ui dropdown">
                                                <option value="blank" <?php selected( $countdown_time_separator, 'blank' ); ?>>
													<?php esc_html_e( 'Blank', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="colon" <?php selected( $countdown_time_separator, 'colon' ); ?>>
													<?php esc_html_e( 'Colon(:)', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="comma" <?php selected( $countdown_time_separator, 'comma' ); ?>>
													<?php esc_html_e( 'Comma(,)', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="dot" <?php selected( $countdown_time_separator, 'dot' ); ?>>
													<?php esc_html_e( 'Dot(.)', 'sales-countdown-timer' ) ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Datetime format style', 'sales-countdown-timer' ) ?></label>
                                            <select name="sale_countdown_datetime_format[]" class="woo-sctr-count-style vi-ui dropdown">
                                                <option value="1" <?php selected( $countdown_datetime_format, 1 ); ?>>
													<?php esc_html_e( '01 days 02 hrs 03 mins 04 secs', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="2" <?php selected( $countdown_datetime_format, 2 ); ?>>
													<?php esc_html_e( '01 days 02 hours 03 minutes 04 seconds', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="3" <?php selected( $countdown_datetime_format, 3 ); ?>>
													<?php esc_html_e( '01:02:03:04', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="4" <?php selected( $countdown_datetime_format, 4 ); ?>>
													<?php esc_html_e( '01d:02h:03m:04s', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="#other" <?php selected( $countdown_datetime_format, '#other' ); ?>>
													<?php esc_html_e( 'Custom', 'sales-countdown-timer' ) ?>
                                                </option>
                                            </select>
                                            <div class="equal width fields woo-sctr-hidden woo-sctr-datetime-format-style-custom" style="margin-top: 10px;">
                                                <div class="field">
                                                    <input type="text"
                                                           name="sale_countdown_datetime_format_custom_date[]"
                                                           class="woo-sctr-datetime-format-custom woo-sctr-datetime-format-custom-date"
                                                           placeholder="<?php echo esc_attr( 'days', 'sales-countdown-timer' ) ?>"
                                                           value="<?php echo esc_attr( $countdown_datetime_format_custom_date ) ?>">
                                                </div>
                                                <div class="field">
                                                    <input type="text"
                                                           name="sale_countdown_datetime_format_custom_hour[]"
                                                           class="woo-sctr-datetime-format-custom woo-sctr-datetime-format-custom-hour"
                                                           placeholder="<?php esc_attr_e( 'hours', 'sales-countdown-timer' ) ?>"
                                                           value="<?php echo esc_attr( $countdown_datetime_format_custom_hour ) ?>">
                                                </div>
                                                <div class="field">
                                                    <input type="text"
                                                           name="sale_countdown_datetime_format_custom_minute[]"
                                                           class="woo-sctr-datetime-format-custom woo-sctr-datetime-format-custom-minute"
                                                           placeholder="<?php esc_attr_e( 'minutes', 'sales-countdown-timer' ) ?>"
                                                           value="<?php echo esc_attr( $countdown_datetime_format_custom_minute ) ?>">
                                                </div>
                                                <div class="field">
                                                    <input type="text"
                                                           name="sale_countdown_datetime_format_custom_second[]"
                                                           class="woo-sctr-datetime-format-custom woo-sctr-datetime-format-custom-second"
                                                           placeholder="<?php esc_attr_e( 'seconds', 'sales-countdown-timer' ) ?>"
                                                           value="<?php echo esc_attr( $countdown_datetime_format_custom_second ) ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Animation style', 'sales-countdown-timer' ) ?></label>
                                            <select name="sale_countdown_animation_style[]" class="woo-sctr-animation-style vi-ui dropdown">
                                                <option value="default" <?php selected( $countdown_animation_style, 'default' ); ?>>
													<?php esc_html_e( 'Default', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="slide" <?php selected( $countdown_animation_style, 'slide' ); ?>>
													<?php esc_html_e( 'Slide', 'sales-countdown-timer' ) ?>
                                                </option>
                                                <option value="flip" <?php selected( $countdown_animation_style, 'flip' );
												disabled( in_array( $countdown_display_type, array( 6, 7 ) ), false ); ?>>
													<?php esc_html_e( ' 3D Flip', 'sales-countdown-timer' ) ?>
                                                </option>
                                            </select>
                                        </div>
                                        <div class="field woo-sctr-countdown-circle-smooth-animation-wrap<?php echo in_array( $countdown_display_type, array( 4, 5, ) ) ? '' : esc_attr( ' woo-sctr-hidden' ); ?>">
                                            <label><?php esc_html_e( 'Use smooth animation for circle', 'sales-countdown-timer' ) ?></label>
                                            <div class="vi-ui checkbox toggle">
                                                <input type="hidden" value="<?php echo esc_attr( $sale_countdown_circle_smooth_animation ) ?>"
                                                       name="sale_countdown_circle_smooth_animation[]" class="woo-sctr-countdown-template-4-circle-smooth-animation"
													<?php checked( $sale_countdown_circle_smooth_animation, '1' ) ?>>
                                                <input type="checkbox" class="woo-sctr-countdown-circle-smooth-animation-check" <?php checked( $sale_countdown_circle_smooth_animation, '1' ); ?>>
                                            </div>
                                        </div>
                                    </div>
									<?php
									switch ( $countdown_datetime_format ) {
										case '#other':
											$date   = $countdown_datetime_format_custom_date;
											$hour   = $countdown_datetime_format_custom_hour;
											$minute = $countdown_datetime_format_custom_minute;
											$second = $countdown_datetime_format_custom_second;
											break;
										case '1':
											$date   = esc_html__( 'days', 'sales-countdown-timer' );
											$hour   = esc_html__( 'hrs', 'sales-countdown-timer' );
											$minute = esc_html__( 'mins', 'sales-countdown-timer' );
											$second = esc_html__( 'secs', 'sales-countdown-timer' );
											break;
										case '2':
											$date   = esc_html__( 'days', 'sales-countdown-timer' );
											$hour   = esc_html__( 'hours', 'sales-countdown-timer' );
											$minute = esc_html__( 'minutes', 'sales-countdown-timer' );
											$second = esc_html__( 'seconds', 'sales-countdown-timer' );
											break;
										case '3':
											$date   = esc_html__( '', 'sales-countdown-timer' );
											$hour   = esc_html__( '', 'sales-countdown-timer' );
											$minute = esc_html__( '', 'sales-countdown-timer' );
											$second = esc_html__( '', 'sales-countdown-timer' );
											break;
										default:
											$date   = esc_html__( 'd', 'sales-countdown-timer' );
											$hour   = esc_html__( 'h', 'sales-countdown-timer' );
											$minute = esc_html__( 'm', 'sales-countdown-timer' );
											$second = esc_html__( 's', 'sales-countdown-timer' );
									}
									switch ( $countdown_time_separator ) {
										case 'dot':
											$time_separator = '.';
											break;
										case 'comma':
											$time_separator = ',';
											break;
										case 'colon':
											$time_separator = ':';
											break;
										default:
											$time_separator = '';
									}
									switch ( count( $countdown_time_units_arg ) ) {
										case 1:
											$countdown_template_class = 'woo-sctr-countdown-timer woo-sctr-shortcode-countdown-count-unit-grid-one';
											break;
										case 2:
											$countdown_template_class = 'woo-sctr-countdown-timer woo-sctr-shortcode-countdown-count-unit-grid-two';
											break;
										case 3:
											$countdown_template_class = 'woo-sctr-countdown-timer woo-sctr-shortcode-countdown-count-unit-grid-three';
											break;
										default:
											$countdown_template_class = 'woo-sctr-countdown-timer woo-sctr-shortcode-countdown-count-unit-grid-four';
									}
									$sale_countdown_template_1_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_1_time_unit_position', $i );
									$sale_countdown_template_2_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_2_time_unit_position', $i );
									$sale_countdown_template_4_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_4_time_unit_position', $i );
									$sale_countdown_template_6_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_6_time_unit_position', $i );
									$sale_countdown_template_6_value_cut_behind   = $this->settings->get_current_countdown( 'sale_countdown_template_6_value_cut_behind', $i );
									$sale_countdown_template_7_time_unit_position = $this->settings->get_current_countdown( 'sale_countdown_template_7_time_unit_position', $i );
									$sale_countdown_template_7_value_cut_behind   = $this->settings->get_current_countdown( 'sale_countdown_template_7_value_cut_behind', $i );
									$div_container_class                          = 'woo-sctr-shortcode-countdown-timer-wrap  woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id;
									$countdown_layout_class                       = 'woo-sctr-countdown-timer-layout woo-sctr-layout-' . $countdown_layout;
									$countdown_layout_class                       .= $countdown_message_position === ' inline_countdown' ? ' woo-sctr-countdown-timer-layout-same-line ' : '';
									$unit_day_class = 'woo-sctr-countdown-unit-wrap woo-sctr-countdown-date-wrap woo-sctr-countdown-unit-wrap-two';
									$unit_hour_class = 'woo-sctr-countdown-unit-wrap woo-sctr-countdown-hour-wrap woo-sctr-countdown-unit-wrap-two';
									$unit_minute_class = 'woo-sctr-countdown-unit-wrap woo-sctr-countdown-minute-wrap woo-sctr-countdown-unit-wrap-two';
									$unit_second_class = 'woo-sctr-countdown-unit-wrap woo-sctr-countdown-second-wrap';
									?>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header">
                                            <label><?php esc_html_e( 'Countdown timer preview', 'sales-countdown-timer' ); ?></label>
                                        </h4>
                                        <div class="field woo-sctr-countdown-preview-wrap">
                                            <div class="woo-sctr-countdown-preview">
                                                <div class="<?php echo esc_attr( $div_container_class ); ?>">
                                                    <div class="<?php esc_attr_e( $countdown_layout_class ); ?>">
                                                        <div class="woo-sctr-countdown-timer-text-wrap woo-sctr-countdown-timer-text-before-wrap">
                                                            <span class="woo-sctr-countdown-timer-text-before"><?php echo wp_kses_post( $text_before ); ?></span>
                                                        </div>
                                                        <?php
                                                        for ($k =7; $k > 0; $k--){
	                                                        $wrap_class = 'woo-sctr-countdown-timer-wrap woo-sctr-countdown-timer-wrap-'.$k;
	                                                        $wrap_class .=$countdown_display_type ===$k ?'':' woo-sctr-countdown-hidden';
                                                            echo sprintf('<div class="%s" data-countdown_template="%s">', $wrap_class, $k);
	                                                        $countdown_template_class_t  = $k===7?'woo-sctr-countdown-timer woo-sctr-shortcode-countdown-count-unit-grid-four': $countdown_template_class;
	                                                        $countdown_template_class_t  .= ' woo-sctr-countdown-timer-'.$k;
	                                                        if ($k !==7 && $countdown_time_units){
		                                                        $unit_day_class .= ! in_array( 'day', $countdown_time_units_arg ) ? ' woo-sctr-countdown-hidden' : '';
		                                                        $unit_hour_class .=! in_array( 'hour', $countdown_time_units_arg ) ? ' woo-sctr-countdown-hidden' : '';
		                                                        $unit_minute_class .=  ! in_array( 'minute', $countdown_time_units_arg ) ? ' woo-sctr-countdown-hidden' : '';
		                                                        $unit_second_class .= ! in_array( 'second', $countdown_time_units_arg ) ? ' woo-sctr-countdown-hidden' : '';
                                                            }
	                                                        wc_get_template( 'countdown-'.$k.'.php',
		                                                        array(
			                                                        'index'          => $i,
			                                                        'settings'          => $this->settings,
			                                                        'animation_style'          => $countdown_animation_style,
			                                                        'time_separator'          => $time_separator,
			                                                        'countdown_template_class'           => $countdown_template_class_t,
			                                                        'unit_day_class'          => $unit_day_class,
			                                                        'day'          => $date,
			                                                        'day_deg'          => 10,
			                                                        'day_left'          => $day_left = 1,
			                                                        'day_left_t'          => zeroise( $day_left, 2 ),
			                                                        'unit_hour_class'          => $unit_hour_class,
			                                                        'hour'          => $hour,
			                                                        'hour_deg'          => 30,
			                                                        'hour_left'          => $hour_left=2,
			                                                        'hour_left_t'          => zeroise( $hour_left, 2 ),
			                                                        'unit_minute_class'          => $unit_minute_class,
			                                                        'minute'          => $minute,
			                                                        'min_deg'          => 180,
			                                                        'min_left'          => $min_left= 30,
			                                                        'min_left_t'          => zeroise( $min_left, 2 ),
			                                                        'unit_second_class'          => $unit_second_class,
			                                                        'second'          => $second,
			                                                        'sec_deg'          => 240,
			                                                        'sec_left'          => 40,
			                                                        'sec_left_t'          => 40,
			                                                        'is_preview'          => true,
		                                                        ),
		                                                        'sctv-sales-countdown-timer' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR,
		                                                        VI_SCT_SALES_COUNTDOWN_TIMER_TEMPLATES );
                                                            echo sprintf('</div>');
                                                        }
                                                        ?>
                                                        <div class="woo-sctr-countdown-timer-text-wrap woo-sctr-countdown-timer-text-after-wrap">
                                                            <span class="woo-sctr-countdown-timer-text-after"><?php echo wp_kses_post( $text_after ); ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header">
                                            <label for=""><?php esc_html_e( 'Layout', 'sales-countdown-timer' ) ?></label>
                                        </h4>
                                        <div class="field woo-sctr-design-layout woo-sctr-design-layout-1<?php echo esc_attr( $countdown_layout === '1' ? '' : ' woo-sctr-countdown-hidden' ); ?>">
											<?php
											$sale_countdown_layout_fontsize        = $this->settings->get_current_countdown( 'sale_countdown_layout_fontsize', $i );
											$sale_countdown_layout_1_color         = $this->settings->get_current_countdown( 'sale_countdown_layout_1_color', $i );
											$sale_countdown_layout_1_background    = $this->settings->get_current_countdown( 'sale_countdown_layout_1_background', $i );
											$sale_countdown_layout_1_border_color  = $this->settings->get_current_countdown( 'sale_countdown_layout_1_border_color', $i );
											$sale_countdown_layout_1_border_radius = $this->settings->get_current_countdown( 'sale_countdown_layout_1_border_radius', $i );
											$sale_countdown_layout_1_padding       = $this->settings->get_current_countdown( 'sale_countdown_layout_1_padding', $i );
											?>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label for=""><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                    <input type="text" class="color-picker woo-sctr-countdown-layout-1-color" name="sale_countdown_layout_1_color[]"
                                                           value="<?php echo esc_attr( $sale_countdown_layout_1_color ) ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                    <input type="text" class="color-picker woo-sctr-countdown-layout-1-background"
                                                           name="sale_countdown_layout_1_background[]" value="<?php echo esc_attr( $sale_countdown_layout_1_background ) ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border color', 'sales-countdown-timer' ) ?></label>
                                                    <input type="text" class="color-picker woo-sctr-countdown-layout-1-border-color"
                                                           name="sale_countdown_layout_1_border_color[]" value="<?php echo esc_attr( $sale_countdown_layout_1_border_color ) ?>">
                                                </div>
                                            </div>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Fontsize', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui right labeled input">
                                                        <input type="number" class="woo-sctr-countdown-layout-fontsize"
                                                               name="sale_countdown_layout_fontsize[]" min="0"
                                                               value="<?php echo esc_attr( $sale_countdown_layout_fontsize ) ?>">
                                                        <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui right labeled input">
                                                        <input type="number" class="woo-sctr-countdown-layout-1-border-radius"
                                                               name="sale_countdown_layout_1_border_radius[]" min="0"
                                                               value="<?php echo esc_attr( $sale_countdown_layout_1_border_radius ) ?>">
                                                        <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Padding',
															'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui right labeled input">
                                                        <input type="number" class="woo-sctr-countdown-layout-1-padding"
                                                               name="sale_countdown_layout_1_padding[]" min="0"
                                                               value="<?php echo esc_attr( $sale_countdown_layout_1_padding ) ?>">
                                                        <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h4 class="vi-ui dividing header">
                                            <label for=""><?php esc_html_e( 'Layout on sticky', 'sales-countdown-timer' ); ?></label>
                                        </h4>
                                        <div class="field woo-sctr-design-layout woo-sctr-design-layout-1<?php echo esc_attr( $countdown_layout === '1' ? '' : ' woo-sctr-countdown-hidden' ); ?>">
											<?php
											$sale_countdown_layout_1_sticky_color        = $this->settings->get_current_countdown( 'sale_countdown_layout_1_sticky_color', $i );
											$sale_countdown_layout_1_sticky_background   = $this->settings->get_current_countdown( 'sale_countdown_layout_1_sticky_background', $i );
											$sale_countdown_layout_1_sticky_border_color = $this->settings->get_current_countdown( 'sale_countdown_layout_1_sticky_border_color', $i );
											?>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Set width 100% on sticky', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui checkbox toggle">
                                                        <input type="hidden" value="<?php echo esc_attr( $sale_countdown_sticky_width ) ?>" name="sale_countdown_sticky_width[]" class="woo-sctr-sticky-with">
                                                        <input type="checkbox" class="woo-sctr-sticky-with-check" <?php checked( $sale_countdown_sticky_width, '1' ) ?>>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Hide datetime unit on sticky', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui checkbox toggle">
                                                        <input type="hidden" value="<?php esc_attr_e( $sale_countdown_sticky_time_unit_disable ) ?>"
                                                               name="sale_countdown_sticky_time_unit_disable[]" class="woo-sctr-sticky-time-unit-disable">
                                                        <input type="checkbox" class="woo-sctr-sticky-time-unit-disable-check"<?php checked( $sale_countdown_sticky_time_unit_disable, '1' ) ?>>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                    <input type="text" class="color-picker woo-sctr-countdown-layout-1-sticky-color"
                                                           name="sale_countdown_layout_1_sticky_color[]" value="<?php echo esc_attr( $sale_countdown_layout_1_sticky_color ) ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                    <input type="text" class="color-picker woo-sctr-countdown-layout-1-sticky-background"
                                                           name="sale_countdown_layout_1_sticky_background[]" value="<?php echo esc_attr( $sale_countdown_layout_1_sticky_background ) ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border color', 'sales-countdown-timer' ) ?></label>
                                                    <input type="text" class="color-picker woo-sctr-countdown-layout-1-sticky-border-color"
                                                           name="sale_countdown_layout_1_sticky_border_color[]" value="<?php echo esc_attr( $sale_countdown_layout_1_sticky_border_color ) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="field woo-sctr-design-countdown-timer woo-sctr-design-countdown-timer-1<?php echo esc_attr( $countdown_display_type === 1 ? '' : ' woo-sctr-countdown-hidden' ); ?>">
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label><?php esc_html_e( 'Datetime value', 'sales-countdown-timer' ) ?></label>
                                                </h4>
												<?php
												$sale_countdown_template_1_value_color         = $this->settings->get_current_countdown( 'sale_countdown_template_1_value_color', $i );
												$sale_countdown_template_1_value_background    = $this->settings->get_current_countdown( 'sale_countdown_template_1_value_background', $i );
												$sale_countdown_template_1_value_border_color  = $this->settings->get_current_countdown( 'sale_countdown_template_1_value_border_color', $i );
												$sale_countdown_template_1_value_border_radius = $this->settings->get_current_countdown( 'sale_countdown_template_1_value_border_radius', $i );
												$sale_countdown_template_1_value_height        = $this->settings->get_current_countdown( 'sale_countdown_template_1_value_height', $i );
												$sale_countdown_template_1_value_width         = $this->settings->get_current_countdown( 'sale_countdown_template_1_value_width', $i );
												$sale_countdown_template_1_value_font_size     = $this->settings->get_current_countdown( 'sale_countdown_template_1_value_font_size', $i );
												?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-countdown-template-1-value-color"
                                                               name="sale_countdown_template_1_value_color[]" value="<?php echo esc_attr( $sale_countdown_template_1_value_color ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-countdown-template-1-value-background"
                                                               name="sale_countdown_template_1_value_background[]" value="<?php echo esc_attr( $sale_countdown_template_1_value_background ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border color', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-countdown-template-1-value-border-color"
                                                               name="sale_countdown_template_1_value_border_color[]" value="<?php echo esc_attr( $sale_countdown_template_1_value_border_color ) ?>">
                                                    </div>
                                                </div>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" class="woo-sctr-countdown-template-1-value-font-size"
                                                                   name="sale_countdown_template_1_value_font_size[]"
                                                                   min="0" value="<?php echo esc_attr( $sale_countdown_template_1_value_font_size ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" class="woo-sctr-countdown-template-1-value-border-radius"
                                                                   name="sale_countdown_template_1_value_border_radius[]" min="0"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_1_value_border_radius ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Height', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" class="woo-sctr-countdown-template-1-value-height"
                                                                   name="sale_countdown_template_1_value_height[]" min="0" value="<?php echo esc_attr( $sale_countdown_template_1_value_height ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Width', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" class="woo-sctr-countdown-template-1-value-width"
                                                                   name="sale_countdown_template_1_value_width[]" min="0" value="<?php echo esc_attr( $sale_countdown_template_1_value_width ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label for=""><?php esc_html_e( 'Datetime unit', 'sales-countdown-timer' ) ?></label>
                                                </h4>
												<?php
												$sale_countdown_template_1_time_unit_color      = $this->settings->get_current_countdown( 'sale_countdown_template_1_time_unit_color', $i );
												$sale_countdown_template_1_time_unit_background = $this->settings->get_current_countdown( 'sale_countdown_template_1_time_unit_background', $i );
												$sale_countdown_template_1_time_unit_fontsize   = $this->settings->get_current_countdown( 'sale_countdown_template_1_time_unit_fontsize', $i );
												?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                        <select name="sale_countdown_template_1_time_unit_position[]" class="vi-ui fluid dropdown woo-sctr-countdown-template-1-time-unit-position">
                                                            <option value="top" <?php selected( $sale_countdown_template_1_time_unit_position, 'top' ) ?> >
																<?php esc_html_e( 'Top', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="bottom" <?php selected( $sale_countdown_template_1_time_unit_position, 'bottom' ) ?> >
																<?php esc_html_e( 'Bottom', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <div class="field">
                                                        <label for=""><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-countdown-template-1-text-color"
                                                               name="sale_countdown_template_1_time_unit_color[]" value="<?php echo esc_attr( $sale_countdown_template_1_time_unit_color ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-1-text-background"
                                                               name="sale_countdown_template_1_time_unit_background[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_1_time_unit_background ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" min="0" class="woo-sctr-countdown-template-1-text-fontsize" name="sale_countdown_template_1_time_unit_fontsize[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_1_time_unit_fontsize ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field woo-sctr-design-countdown-timer woo-sctr-design-countdown-timer-2<?php echo esc_attr( $countdown_display_type === 2 ? '' : ' woo-sctr-countdown-hidden' ); ?>">
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label> <?php esc_html_e( 'Countdown timer items', 'sales-countdown-timer' ) ?></label>
                                                </h4>
												<?php
												$sale_countdown_template_2_item_border_color    = $this->settings->get_current_countdown( 'sale_countdown_template_2_item_border_color', $i );
												$sale_countdown_template_2_item_border_radius   = $this->settings->get_current_countdown( 'sale_countdown_template_2_item_border_radius', $i );
												$sale_countdown_template_2_item_height          = $this->settings->get_current_countdown( 'sale_countdown_template_2_item_height', $i );
												$sale_countdown_template_2_item_width           = $this->settings->get_current_countdown( 'sale_countdown_template_2_item_width', $i );
												$sale_countdown_template_2_value_color          = $this->settings->get_current_countdown( 'sale_countdown_template_2_value_color', $i );
												$sale_countdown_template_2_value_background     = $this->settings->get_current_countdown( 'sale_countdown_template_2_value_background', $i );
												$sale_countdown_template_2_value_fontsize       = $this->settings->get_current_countdown( 'sale_countdown_template_2_value_fontsize', $i );
												$sale_countdown_template_2_time_unit_color      = $this->settings->get_current_countdown( 'sale_countdown_template_2_time_unit_color', $i );
												$sale_countdown_template_2_time_unit_background = $this->settings->get_current_countdown( 'sale_countdown_template_2_time_unit_background', $i );
												$sale_countdown_template_2_time_unit_fontsize   = $this->settings->get_current_countdown( 'sale_countdown_template_2_time_unit_fontsize', $i );
												?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border color', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-countdown-template-2-item-border-color"
                                                               name="sale_countdown_template_2_item_border_color[]" value="<?php echo esc_attr( $sale_countdown_template_2_item_border_color ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" min="0"
                                                                   class="woo-sctr-countdown-template-2-item-border-radius" name="sale_countdown_template_2_item_border_radius[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_2_item_border_radius ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Height', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" min="0" class="woo-sctr-countdown-template-2-item-height"
                                                                   name="sale_countdown_template_2_item_height[]" value="<?php echo esc_attr( $sale_countdown_template_2_item_height ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Width', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number" min="0" class="woo-sctr-countdown-template-2-item-width"
                                                                   name="sale_countdown_template_2_item_width[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_2_item_width ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label> <?php esc_html_e( 'Datetime value', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-2-item-value-color"
                                                                   name="sale_countdown_template_2_value_color[]" value="<?php echo esc_attr( $sale_countdown_template_2_value_color ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-2-item-value-background"
                                                                   name="sale_countdown_template_2_value_background[]" value="<?php echo esc_attr( $sale_countdown_template_2_value_background ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-2-item-value-fontsize"
                                                                       name="sale_countdown_template_2_value_fontsize[]" value="<?php echo esc_attr( $sale_countdown_template_2_value_fontsize ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label> <?php esc_html_e( 'Datetime unit', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                            <select name="sale_countdown_template_2_time_unit_position[]" class="vi-ui fluid dropdown woo-sctr-countdown-template-2-time-unit-position">
                                                                <option value="top" <?php selected( $sale_countdown_template_2_time_unit_position, 'top' ) ?> >
																	<?php esc_html_e( 'Top', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                                <option value="bottom" <?php selected( $sale_countdown_template_2_time_unit_position, 'bottom' ) ?> >
																	<?php esc_html_e( 'Bottom', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-2-item-time-unit-color"
                                                                   name="sale_countdown_template_2_time_unit_color[]" value="<?php echo esc_attr( $sale_countdown_template_2_time_unit_color ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-2-item-time-unit-background"
                                                                   name="sale_countdown_template_2_time_unit_background[]" value="<?php echo esc_attr( $sale_countdown_template_2_time_unit_background ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-2-item-time-unit-fontsize"
                                                                       name="sale_countdown_template_2_time_unit_fontsize[]" value="<?php echo esc_attr( $sale_countdown_template_2_time_unit_fontsize ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field woo-sctr-design-countdown-timer woo-sctr-design-countdown-timer-3<?php echo esc_attr( $countdown_display_type === 3 ? '' : ' woo-sctr-countdown-hidden' ); ?>">
                                            <div class="field">
												<?php
												$sale_countdown_template_3_value_color          = $this->settings->get_current_countdown( 'sale_countdown_template_3_value_color', $i );
												$sale_countdown_template_3_value_background     = $this->settings->get_current_countdown( 'sale_countdown_template_3_value_background', $i );
												$sale_countdown_template_3_value_fontsize       = $this->settings->get_current_countdown( 'sale_countdown_template_3_value_fontsize', $i );
												$sale_countdown_template_3_time_unit_color      = $this->settings->get_current_countdown( 'sale_countdown_template_3_time_unit_color', $i );
												$sale_countdown_template_3_time_unit_background = $this->settings->get_current_countdown( 'sale_countdown_template_3_time_unit_background', $i );
												$sale_countdown_template_3_time_unit_fontsize   = $this->settings->get_current_countdown( 'sale_countdown_template_3_time_unit_fontsize', $i );
												?>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label> <?php esc_html_e( 'Datetime value', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-3-value-color"
                                                                   name="sale_countdown_template_3_value_color[]" value="<?php echo esc_attr( $sale_countdown_template_3_value_color ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-3-value-background"
                                                                   name="sale_countdown_template_3_value_background[]" value="<?php echo esc_attr( $sale_countdown_template_3_value_background ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-3-value-fontsize"
                                                                       name="sale_countdown_template_3_value_fontsize[]" value="<?php echo esc_attr( $sale_countdown_template_3_value_fontsize ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label> <?php esc_html_e( 'Datetime unit', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-3-time-unit-color"
                                                                   name="sale_countdown_template_3_time_unit_color[]" value="<?php echo esc_attr( $sale_countdown_template_3_time_unit_color ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-3-time-unit-background"
                                                                   name="sale_countdown_template_3_time_unit_background[]" value="<?php echo esc_attr( $sale_countdown_template_3_time_unit_background ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-3-time-unit-fontsize" name="sale_countdown_template_3_time_unit_fontsize[]"
                                                                       value="<?php echo esc_attr( $sale_countdown_template_3_time_unit_fontsize ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field woo-sctr-design-countdown-timer woo-sctr-design-countdown-timer-4<?php echo esc_attr( $countdown_display_type === 4 ? '' : ' woo-sctr-countdown-hidden' ); ?>">
                                            <div class="field">
												<?php
												$sale_countdown_template_4_value_border_color1  = $this->settings->get_current_countdown( 'sale_countdown_template_4_value_border_color1', $i );
												$sale_countdown_template_4_value_border_color2  = $this->settings->get_current_countdown( 'sale_countdown_template_4_value_border_color2', $i );
												$sale_countdown_template_4_value_color          = $this->settings->get_current_countdown( 'sale_countdown_template_4_value_color', $i );
												$sale_countdown_template_4_value_background     = $this->settings->get_current_countdown( 'sale_countdown_template_4_value_background', $i );
												$sale_countdown_template_4_value_fontsize       = $this->settings->get_current_countdown( 'sale_countdown_template_4_value_fontsize', $i );
												$sale_countdown_template_4_value_border_width   = $this->settings->get_current_countdown( 'sale_countdown_template_4_value_border_width', $i );
												$sale_countdown_template_4_value_diameter       = $this->settings->get_current_countdown( 'sale_countdown_template_4_value_diameter', $i );
												$sale_countdown_template_4_time_unit_color      = $this->settings->get_current_countdown( 'sale_countdown_template_4_time_unit_color', $i );
												$sale_countdown_template_4_time_unit_background = $this->settings->get_current_countdown( 'sale_countdown_template_4_time_unit_background', $i );
												$sale_countdown_template_4_time_unit_fontsize   = $this->settings->get_current_countdown( 'sale_countdown_template_4_time_unit_fontsize', $i );
												?>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label> <?php esc_html_e( 'Datetime value', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-4-value-color"
                                                                   name="sale_countdown_template_4_value_color[]" value="<?php echo esc_attr( $sale_countdown_template_4_value_color ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Border color 1', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-4-value-border-color-1"
                                                                   name="sale_countdown_template_4_value_border_color1[]" value="<?php echo esc_attr( $sale_countdown_template_4_value_border_color1 ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Border color 2', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-4-value-border-color-2"
                                                                   name="sale_countdown_template_4_value_border_color2[]" value="<?php echo esc_attr( $sale_countdown_template_4_value_border_color2 ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-4-value-background"
                                                                   name="sale_countdown_template_4_value_background[]" value="<?php echo esc_attr( $sale_countdown_template_4_value_background ) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-4-value-fontsize"
                                                                       name="sale_countdown_template_4_value_fontsize[]" value="<?php echo esc_attr( $sale_countdown_template_4_value_fontsize ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Border width', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-4-value-border-width"
                                                                       name="sale_countdown_template_4_value_border_width[]" value="<?php echo esc_attr( $sale_countdown_template_4_value_border_width ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Diameter', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-4-value-diameter"
                                                                       name="sale_countdown_template_4_value_diameter[]" value="<?php echo esc_attr( $sale_countdown_template_4_value_diameter ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label> <?php esc_html_e( 'Datetime unit', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                            <select name="sale_countdown_template_4_time_unit_position[]"
                                                                    class="vi-ui fluid dropdown woo-sctr-countdown-template-4-time-unit-position">
                                                                <option value="top" <?php selected( $sale_countdown_template_4_time_unit_position, 'top' ) ?> >
                                                                    <?php esc_html_e( 'Top', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                                <option value="bottom" <?php selected( $sale_countdown_template_4_time_unit_position, 'bottom' ) ?> >
                                                                    <?php esc_html_e( 'Bottom', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-4-time-unit-color"
                                                                   name="sale_countdown_template_4_time_unit_color[]" value="<?php echo esc_attr( $sale_countdown_template_4_time_unit_color ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text" class="color-picker woo-sctr-countdown-template-4-time-unit-background"
                                                                   name="sale_countdown_template_4_time_unit_background[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_4_time_unit_background ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number" min="0" class="woo-sctr-countdown-template-4-time-unit-fontsize"
                                                                       name="sale_countdown_template_4_time_unit_fontsize[]"
                                                                       value="<?php echo esc_attr( $sale_countdown_template_4_time_unit_fontsize ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field woo-sctr-design-countdown-timer woo-sctr-design-countdown-timer-5<?php echo esc_attr($countdown_display_type === 5 ? '' : ' woo-sctr-countdown-hidden'); ?>">
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label><?php esc_html_e( 'Countdown timer items', 'sales-countdown-timer' ) ?></label>
                                                </h4>
	                                            <?php
	                                            $sale_countdown_template_5_item_border_width = $this->settings->get_current_countdown( 'sale_countdown_template_5_item_border_width', $i );
	                                            $sale_countdown_template_5_item_diameter     = $this->settings->get_current_countdown( 'sale_countdown_template_5_item_diameter', $i );
	                                            $sale_countdown_template_5_value_color       = $this->settings->get_current_countdown( 'sale_countdown_template_5_value_color', $i );
	                                            $sale_countdown_template_5_time_unit_color   = $this->settings->get_current_countdown( 'sale_countdown_template_5_time_unit_color', $i );
	                                            ?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label for=""><?php esc_html_e( 'Datetime value color',
				                                                'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-value-color"
                                                               name="sale_countdown_template_5_value_color[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_value_color ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Datetime unit color', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-time-unit-color"
                                                               name="sale_countdown_template_5_time_unit_color[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_time_unit_color ) ?>">
                                                    </div>
                                                </div>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border width', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number"
                                                                   min="0"
                                                                   class="woo-sctr-countdown-template-5-item-border-width"
                                                                   name="sale_countdown_template_5_item_border_width[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_5_item_border_width ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Diameter', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number"
                                                                   min="0"
                                                                   class="woo-sctr-countdown-template-5-item-diameter"
                                                                   name="sale_countdown_template_5_item_diameter[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_5_item_diameter ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
	                                                <?php
	                                                $sale_countdown_template_5_value_fontsize     = $this->settings->get_current_countdown( 'sale_countdown_template_5_value_fontsize', $i );
	                                                $sale_countdown_template_5_time_unit_fontsize = $this->settings->get_current_countdown( 'sale_countdown_template_5_time_unit_fontsize', $i );
	                                                ?>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Datetime value font size', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number"
                                                                   min="0"
                                                                   class="woo-sctr-countdown-template-5-value-fontsize"
                                                                   name="sale_countdown_template_5_value_fontsize[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_5_value_fontsize ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Datetime unit font size', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right labeled input">
                                                            <input type="number"
                                                                   min="0"
                                                                   class="woo-sctr-countdown-template-5-time-unit-fontsize"
                                                                   name="sale_countdown_template_5_time_unit_fontsize[]"
                                                                   value="<?php echo esc_attr( $sale_countdown_template_5_time_unit_fontsize ) ?>">
                                                            <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label ><?php esc_html_e( 'Countdown timer date', 'sales-countdown-timer' ) ?></label>
                                                </h4>
	                                            <?php
	                                            $sale_countdown_template_5_date_border_color1 = $this->settings->get_current_countdown( 'sale_countdown_template_5_date_border_color1', $i );
	                                            $sale_countdown_template_5_date_border_color2 = $this->settings->get_current_countdown( 'sale_countdown_template_5_date_border_color2', $i );
	                                            $sale_countdown_template_5_date_background    = $this->settings->get_current_countdown( 'sale_countdown_template_5_date_background', $i );
	                                            ?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border color 1', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-date-border-color1"
                                                               name="sale_countdown_template_5_date_border_color1[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_date_border_color1 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border color 2', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-date-border-color2"
                                                               name="sale_countdown_template_5_date_border_color2[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_date_border_color2 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-date-background"
                                                               name="sale_countdown_template_5_date_background[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_date_background ) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label ><?php esc_html_e( 'Countdown timer hour', 'sales-countdown-timer' ) ?></label>
                                                </h4>
	                                            <?php
	                                            $sale_countdown_template_5_hour_border_color1 = $this->settings->get_current_countdown( 'sale_countdown_template_5_hour_border_color1', $i );
	                                            $sale_countdown_template_5_hour_border_color2 = $this->settings->get_current_countdown( 'sale_countdown_template_5_hour_border_color2', $i );
	                                            $sale_countdown_template_5_hour_background    = $this->settings->get_current_countdown( 'sale_countdown_template_5_hour_background', $i );
	                                            ?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border color 1', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-hour-border-color1"
                                                               name="sale_countdown_template_5_hour_border_color1[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_hour_border_color1 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border color 2', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-hour-border-color2"
                                                               name="sale_countdown_template_5_hour_border_color2[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_hour_border_color2 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-hour-background"
                                                               name="sale_countdown_template_5_hour_background[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_hour_background ) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label ><?php esc_html_e( 'Countdown timer minute', 'sales-countdown-timer' ) ?></label>
                                                </h4>
	                                            <?php
	                                            $sale_countdown_template_5_minute_border_color1 = $this->settings->get_current_countdown( 'sale_countdown_template_5_minute_border_color1', $i );
	                                            $sale_countdown_template_5_minute_border_color2 = $this->settings->get_current_countdown( 'sale_countdown_template_5_minute_border_color2', $i );
	                                            $sale_countdown_template_5_minute_background    = $this->settings->get_current_countdown( 'sale_countdown_template_5_minute_background', $i );
	                                            ?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border color 1', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-minute-border-color1"
                                                               name="sale_countdown_template_5_minute_border_color1[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_minute_border_color1 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Border color 2', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-minute-border-color2"
                                                               name="sale_countdown_template_5_minute_border_color2[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_minute_border_color2 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-minute-background"
                                                               name="sale_countdown_template_5_minute_background[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_minute_background ) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <h4 class="vi-ui dividing header">
                                                    <label ><?php esc_html_e( 'Countdown timer second', 'sales-countdown-timer' ) ?></label>
                                                </h4>
	                                            <?php
	                                            $sale_countdown_template_5_second_border_color1 = $this->settings->get_current_countdown( 'sale_countdown_template_5_second_border_color1', $i );
	                                            $sale_countdown_template_5_second_border_color2 = $this->settings->get_current_countdown( 'sale_countdown_template_5_second_border_color2', $i );
	                                            $sale_countdown_template_5_second_background    = $this->settings->get_current_countdown( 'sale_countdown_template_5_second_background', $i );
	                                            ?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Border color 1', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-second-border-color1"
                                                               name="sale_countdown_template_5_second_border_color1[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_second_border_color1 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Border color 2', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-second-border-color2"
                                                               name="sale_countdown_template_5_second_border_color2[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_second_border_color2 ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text"
                                                               class="color-picker woo-sctr-countdown-template-5-second-background"
                                                               name="sale_countdown_template_5_second_background[]"
                                                               value="<?php echo esc_attr( $sale_countdown_template_5_second_background ) ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field woo-sctr-design-countdown-timer woo-sctr-design-countdown-timer-6<?php echo esc_attr($countdown_display_type === 6 ? '' : ' woo-sctr-countdown-hidden'); ?>">
                                            <div class="field">
	                                            <?php
	                                            $template_6_args_t = array(
		                                            'sale_countdown_template_6_value_width'         => '70',
		                                            'sale_countdown_template_6_value_height'        => '75',
		                                            'sale_countdown_template_6_value_border_radius' => '10',
		                                            'sale_countdown_template_6_value_fontsize'      => '50',
		                                            'sale_countdown_template_6_value_color1'        => '#ccc',
		                                            'sale_countdown_template_6_value_color2'        => '#fff',
		                                            'sale_countdown_template_6_value_background1'   => '#1f1e1e',
		                                            'sale_countdown_template_6_value_background2'   => '#383636',
		                                            'sale_countdown_template_6_value_box_shadow'    => '1',
		                                            'sale_countdown_template_6_value_cut_color'     => '#000',
		                                            'sale_countdown_template_6_time_unit_color'     => '',
		                                            'sale_countdown_template_6_time_unit_fontsize'  => '12',
		                                            'sale_countdown_template_6_time_unit_grid_gap'  => '10',
	                                            );
	                                            $template_6_args   = array();
	                                            foreach ( $template_6_args_t as $item_id => $item_value ) {
		                                            $template_6_args[ $item_id ] = $this->settings->get_current_countdown( $item_id, $i, $item_value );
	                                            }
	                                            ?>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label ><?php esc_html_e( 'Datetime value', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Color 1', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-6-value-color1"
                                                                   name="sale_countdown_template_6_value_color1[]"
                                                                   value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_color1'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Color 2', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-6-value-color2"
                                                                   name="sale_countdown_template_6_value_color2[]"
                                                                   value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_color2'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Background 1', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-6-value-background1"
                                                                   name="sale_countdown_template_6_value_background1[]"
                                                                   value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_background1'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Background 2', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-6-value-background2"
                                                                   name="sale_countdown_template_6_value_background2[]"
                                                                   value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_background2'] ) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Width', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-6-value-width"
                                                                       name="sale_countdown_template_6_value_width[]"
                                                                       value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_width'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Height', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-6-value-height"
                                                                       name="sale_countdown_template_6_value_height[]"
                                                                       value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_height'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-6-value-border-radius"
                                                                       name="sale_countdown_template_6_value_border_radius[]"
                                                                       value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_border_radius'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Fontsize', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-6-value-fontsize"
                                                                       name="sale_countdown_template_6_value_fontsize[]"
                                                                       value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_fontsize'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Enable box shadow', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui checkbox toggle">
                                                                <input type="hidden"
                                                                       value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_box_shadow'] ) ?>"
                                                                       name="sale_countdown_template_6_value_box_shadow[]"
                                                                       class="woo-sctr-countdown-template-6-value-box-shadow">
                                                                <input type="checkbox" class="woo-sctr-countdown-template-6-value-box-shadow-check"
				                                                    <?php checked( $template_6_args['sale_countdown_template_6_value_box_shadow'], '1' ) ?>>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Show dividing line behind datetime value', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui checkbox toggle">
                                                                <input type="hidden"
                                                                       value="<?php echo esc_attr( $sale_countdown_template_6_value_cut_behind ) ?>"
                                                                       name="sale_countdown_template_6_value_cut_behind[]"
                                                                       class="woo-sctr-countdown-template-6-value-cut-behind">
                                                                <input type="checkbox" class="woo-sctr-countdown-template-6-value-cut-behind-check"
				                                                    <?php checked( $sale_countdown_template_6_value_cut_behind, '1' ) ?>>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Dividing line color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-6-value-cut-color"
                                                                   name="sale_countdown_template_6_value_cut_color[]"
                                                                   value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_value_cut_color'] ) ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label ><?php esc_html_e( 'Datetime unit', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                            <select name="sale_countdown_template_6_time_unit_position[]" class="vi-ui fluid dropdown woo-sctr-countdown-template-6-time-unit-position">
                                                                <option value="top" <?php selected( $sale_countdown_template_6_time_unit_position, 'top' ) ?> >
                                                                    <?php esc_html_e( 'Top', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                                <option value="bottom" <?php selected( $sale_countdown_template_6_time_unit_position, 'bottom' ) ?> >
                                                                    <?php esc_html_e( 'Bottom', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Color ', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-6-time-unit-color"
                                                                   name="sale_countdown_template_6_time_unit_color[]"
                                                                   value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_time_unit_color'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Fontsize', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-6-time-unit-fontsize"
                                                                       name="sale_countdown_template_6_time_unit_fontsize[]"
                                                                       value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_time_unit_fontsize'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Distance to time value', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-6-time-unit-grid-gap"
                                                                       name="sale_countdown_template_6_time_unit_grid_gap[]"
                                                                       value="<?php echo esc_attr( $template_6_args['sale_countdown_template_6_time_unit_grid_gap'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field woo-sctr-design-countdown-timer woo-sctr-design-countdown-timer-7<?php echo esc_attr($countdown_display_type === 7 ? '' : ' woo-sctr-countdown-hidden'); ?>">
                                            <div class="field">
	                                            <?php
	                                            $template_7_args_t = array(
		                                            'sale_countdown_template_7_value_width',
		                                            'sale_countdown_template_7_value_height',
		                                            'sale_countdown_template_7_value_border_radius',
		                                            'sale_countdown_template_7_value_fontsize',
		                                            'sale_countdown_template_7_value_color1',
		                                            'sale_countdown_template_7_value_color2',
		                                            'sale_countdown_template_7_value_background1',
		                                            'sale_countdown_template_7_value_background2',
		                                            'sale_countdown_template_7_value_box_shadow',
		                                            'sale_countdown_template_7_value_cut_color',
		                                            'sale_countdown_template_7_time_unit_color',
		                                            'sale_countdown_template_7_time_unit_fontsize',
		                                            'sale_countdown_template_7_time_unit_grid_gap',
	                                            );
	                                            $template_7_args   = array();
	                                            foreach ( $template_7_args_t as $item ) {
		                                            $template_7_args[ $item ] = $this->settings->get_current_countdown( $item, $i );
	                                            }
	                                            ?>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label ><?php esc_html_e( 'Datetime value', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Color 1', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-7-value-color1"
                                                                   name="sale_countdown_template_7_value_color1[]"
                                                                   value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_color1'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Color 2', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-7-value-color2"
                                                                   name="sale_countdown_template_7_value_color2[]"
                                                                   value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_color2'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Background 1', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-7-value-background1"
                                                                   name="sale_countdown_template_7_value_background1[]"
                                                                   value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_background1'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Background 2', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-7-value-background2"
                                                                   name="sale_countdown_template_7_value_background2[]"
                                                                   value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_background2'] ) ?>">
                                                        </div>
                                                    </div>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Width', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-7-value-width"
                                                                       name="sale_countdown_template_7_value_width[]"
                                                                       value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_width'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Height', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-7-value-height"
                                                                       name="sale_countdown_template_7_value_height[]"
                                                                       value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_height'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-7-value-border-radius"
                                                                       name="sale_countdown_template_7_value_border_radius[]"
                                                                       value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_border_radius'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Fontsize', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-7-value-fontsize"
                                                                       name="sale_countdown_template_7_value_fontsize[]"
                                                                       value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_fontsize'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Enable box shadow', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui checkbox toggle">
                                                                <input type="hidden"
                                                                       value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_box_shadow'] ) ?>"
                                                                       name="sale_countdown_template_7_value_box_shadow[]"
                                                                       class="woo-sctr-countdown-template-7-value-box-shadow">
                                                                <input type="checkbox" class="woo-sctr-countdown-template-7-value-box-shadow-check"
				                                                    <?php checked( $template_7_args['sale_countdown_template_7_value_box_shadow'], '1' ) ?>>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Show dividing line behind value text', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui checkbox toggle">
                                                                <input type="hidden"
                                                                       value="<?php echo esc_attr( $sale_countdown_template_7_value_cut_behind ) ?>"
                                                                       name="sale_countdown_template_7_value_cut_behind[]"
                                                                       class="woo-sctr-countdown-template-7-value-cut-behind">
                                                                <input type="checkbox" class="woo-sctr-countdown-template-7-value-cut-behind-check"
				                                                    <?php checked( $sale_countdown_template_7_value_cut_behind, '1' ) ?>>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Dividing line color', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-7-value-cut-color"
                                                                   name="sale_countdown_template_7_value_cut_color[]"
                                                                   value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_value_cut_color'] ) ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <h4 class="vi-ui dividing header">
                                                        <label><?php esc_html_e( 'Datetime unit', 'sales-countdown-timer' ) ?></label>
                                                    </h4>
                                                    <div class="equal width fields">
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                            <select name="sale_countdown_template_7_time_unit_position[]"class="vi-ui fluid dropdown woo-sctr-countdown-template-7-time-unit-position">
                                                                <option value="top" <?php selected( $sale_countdown_template_7_time_unit_position, 'top' ) ?> >
                                                                    <?php esc_html_e( 'Top', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                                <option value="bottom" <?php selected( $sale_countdown_template_7_time_unit_position, 'bottom' ) ?> >
                                                                    <?php esc_html_e( 'Bottom', 'sales-countdown-timer' ) ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                        <div class="field">
                                                            <label ><?php esc_html_e( 'Color ', 'sales-countdown-timer' ) ?></label>
                                                            <input type="text"
                                                                   class="color-picker woo-sctr-countdown-template-7-time-unit-color"
                                                                   name="sale_countdown_template_7_time_unit_color[]"
                                                                   value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_time_unit_color'] ) ?>">
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Fontsize', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-7-time-unit-fontsize"
                                                                       name="sale_countdown_template_7_time_unit_fontsize[]"
                                                                       value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_time_unit_fontsize'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                        <div class="field">
                                                            <label><?php esc_html_e( 'Distance to time value', 'sales-countdown-timer' ) ?></label>
                                                            <div class="vi-ui right labeled input">
                                                                <input type="number"
                                                                       min="0"
                                                                       class="woo-sctr-countdown-template-7-time-unit-grid-gap"
                                                                       name="sale_countdown_template_7_time_unit_grid_gap[]"
                                                                       value="<?php echo esc_attr( $template_7_args['sale_countdown_template_7_time_unit_grid_gap'] ) ?>">
                                                                <div class="vi-ui basic label woo-sctr-countdown-design-input-label">Px</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                if ($check_woo_active){
                                    ?>
                                    <div class="title">
                                        <i class="dropdown icon"></i>
		                                <?php esc_html_e( 'WooCommerce Product', 'sales-countdown-timer' ) ?>
                                    </div>
                                    <div class="content">
                                        <div class="field">
                                            <h4 class="vi-ui dividing header">
                                                <label><?php esc_html_e( 'Countdown timer on the single product page', 'sales-countdown-timer' ) ?></label>
                                            </h4>
	                                        <?php
	                                        $sale_countdown_single_product_sticky   = $this->settings->get_current_countdown( 'sale_countdown_single_product_sticky', $i );
	                                        $sale_countdown_single_product_position = $this->settings->get_current_countdown( 'sale_countdown_single_product_position', $i );
	                                        ?>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui input"
                                                         data-tooltip="<?php esc_attr_e( 'The position of countdown timer on the single product page (can not set position for variable products)', 'sales-countdown-timer' ) ?>">
                                                        <select name="sale_countdown_single_product_position[]" class="woo-sctr-single-product-position vi-ui fluid dropdown">
                                                            <option value="before_price" <?php selected( $sale_countdown_single_product_position, 'before_price' ); ?>>
                                                                <?php esc_html_e( 'Before price', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="after_price" <?php selected( $sale_countdown_single_product_position, 'after_price' ); ?>>
                                                                <?php esc_html_e( 'After price', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="before_saleflash" <?php selected( $sale_countdown_single_product_position, 'before_saleflash' ); ?>>
                                                                <?php esc_html_e( 'Before sale flash', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="after_saleflash" <?php selected( $sale_countdown_single_product_position, 'after_saleflash' ); ?>>
                                                                <?php esc_html_e( 'After sale flash', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="before_cart" <?php selected( $sale_countdown_single_product_position, 'before_cart' ); ?>>
                                                                <?php esc_html_e( 'Before cart', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="after_cart" <?php selected( $sale_countdown_single_product_position, 'after_cart' ); ?>>
                                                                <?php esc_html_e( 'After cart', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="product_image" <?php selected( $sale_countdown_single_product_position, 'product_image' ); ?>>
                                                                <?php esc_html_e( 'Product image', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Sticky when scrolling', 'sales-countdown-timer' ) ?></label>
                                                    <select name="sale_countdown_single_product_sticky[]"
                                                            class="vi-ui fluid dropdown woo-sctr-single-product-sticky">
                                                        <option value="none" <?php selected( $sale_countdown_single_product_sticky, 'none' ); ?>>
                                                            <?php esc_html_e( 'None', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="sticky_top" <?php selected( $sale_countdown_single_product_sticky, 'sticky_top' ); ?>>
                                                            <?php esc_html_e( 'Sticky top', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="sticky_bottom" <?php selected( $sale_countdown_single_product_sticky, 'sticky_bottom' ); ?>>
                                                            <?php esc_html_e( 'Sticky bottom', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Show "Add to cart" button on sticky countdown', 'sales-countdown-timer' ) ?></label>
		                                            <?php
		                                            $sale_countdown_add_to_cart_button = $this->settings->get_current_countdown( 'sale_countdown_add_to_cart_button', $i );
		                                            ?>
                                                    <div class="vi-ui toggle checkbox">
                                                        <input type="hidden"
                                                               name="sale_countdown_add_to_cart_button[]"
                                                               class="woo-sctr-add-to-cart-button"
                                                               value="<?php echo esc_attr( $sale_countdown_add_to_cart_button ); ?>">
                                                        <input type="checkbox"
                                                               class="woo-sctr-add-to-cart-button-check" <?php echo $sale_countdown_add_to_cart_button ? 'checked' : ''; ?>><label></label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <h4 class="vi-ui dividing header">
                                                <label><?php esc_html_e( 'Countdown timer on Shop page', 'sales-countdown-timer' ) ?></label>
                                            </h4>
	                                        <?php
	                                        $sale_countdown_archive_page_enable = $this->settings->get_current_countdown( 'sale_countdown_archive_page_enable', $i );
	                                        $sale_countdown_archive_page_assign = $this->settings->get_current_countdown( 'sale_countdown_archive_page_assign', $i );
	                                        $sale_countdown_archive_page_position = $this->settings->get_current_countdown( 'sale_countdown_archive_page_position', $i );
	                                        ?>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Enable', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui toggle checkbox">
                                                        <input type="hidden"
                                                               name="sale_countdown_archive_page_enable[]"
                                                               class="woo-sctr-archive-page-enable"
                                                               value="<?php echo esc_attr( $sale_countdown_archive_page_enable ); ?>">
                                                        <input type="checkbox"
                                                               class="woo-sctr-archive-page-enable-check" <?php checked($sale_countdown_archive_page_enable, 1); ?>><label></label>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label for=""><?php esc_html_e( 'Show on', 'sales-countdown-timer' ); ?></label>
                                                    <div class="vi-ui input"
                                                         data-tooltip="<?php esc_attr_e( 'Leave blank to display on all pages with products list', 'sales-countdown-timer' ); ?>">
                                                        <input type="text" placeholder="<?php esc_html_e( 'eg: !is_page(array(34,98,73))', 'sales-countdown-timer' ) ?>"
                                                               name="sale_countdown_archive_page_assign[]"
                                                               class="woo-sctr-sale_countdown_archive_page_assign"
                                                               value="<?php echo esc_attr(wp_unslash( $sale_countdown_archive_page_assign) ); ?>">
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui input"
                                                         data-tooltip="<?php esc_attr_e( 'The position of countdown timer on shop page, category page and other products list pages', 'sales-countdown-timer' ) ?>">
                                                        <select name="sale_countdown_archive_page_position[]" class="woo-sctr-archive-page-position vi-ui fluid dropdown">
                                                            <option value="before_price" <?php selected( $sale_countdown_archive_page_position, 'before_price' ); ?>>
                                                                <?php esc_html_e( 'Before price', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="after_price" <?php selected( $sale_countdown_archive_page_position, 'after_price' ); ?>>
                                                                <?php esc_html_e( 'After price', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="before_saleflash" <?php selected( $sale_countdown_archive_page_position, 'before_saleflash' ); ?>>
                                                                <?php esc_html_e( 'Before sale flash', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="after_saleflash" <?php selected( $sale_countdown_archive_page_position, 'after_saleflash' ); ?>>
                                                                <?php esc_html_e( 'After sale flash', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="before_cart" <?php selected( $sale_countdown_archive_page_position, 'before_cart' ); ?>>
                                                                <?php esc_html_e( 'Before cart', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="after_cart" <?php selected( $sale_countdown_archive_page_position, 'after_cart' ); ?>>
                                                                <?php esc_html_e( 'After cart', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="product_image" <?php selected( $sale_countdown_archive_page_position, 'product_image' ); ?>>
                                                                <?php esc_html_e( 'Product image', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <h4 class="vi-ui dividing header"><?php esc_html_e( 'Progress bar', 'sales-countdown-timer' ) ?></h4>
	                                        <?php
	                                        $sale_countdown_progress_bar_message          = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_message', $i );
	                                        $sale_countdown_progress_bar_type             = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_type', $i );
	                                        $sale_countdown_progress_bar_order_status     = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_order_status', $i );
	                                        $sale_countdown_progress_bar_order_status_arg = $sale_countdown_progress_bar_order_status ? explode( ',', $sale_countdown_progress_bar_order_status ) : array();
	                                        $sale_countdown_progress_bar_message_position = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_message_position', $i );
	                                        $sale_countdown_progress_bar_position         = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position', $i );
	                                        $sale_countdown_progress_bar_template         = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template', $i );
	                                        ?>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Progress bar message', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui input">
                                                    <input type="text" name="sale_countdown_progress_bar_message[]"
                                                           class="woo-sctr-progress-bar-message-input"
                                                           value="<?php echo esc_attr( $sale_countdown_progress_bar_message ); ?>">
                                                </div>
                                            </div>
                                            <div class="field">
                                                <p>{quantity_left} - <?php esc_html_e( 'Number of products left', 'sales-countdown-timer' ) ?></p>
                                                <p>{quantity_sold} - <?php esc_html_e( 'Number of products sold', 'sales-countdown-timer' ) ?></p>
                                                <p>{percentage_left} - <?php esc_html_e( 'Percentage of products left', 'sales-countdown-timer' ) ?></p>
                                                <p>{percentage_sold} - <?php esc_html_e( 'Percentage of products sold' , 'sales-countdown-timer') ?></p>
                                                <p>{goal} - <?php esc_html_e( 'The goal that you set on single product' , 'sales-countdown-timer') ?></p>
                                            </div>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Progress bar type', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui input"
                                                         data-tooltip="<?php esc_attr_e( 'If select increase, the progress bar fill will increase each time the product is bought and vice versa', 'sales-countdown-timer' ) ?>">
                                                        <select name="sale_countdown_progress_bar_type[]" class="woo-sctr-progress-bar-type vi-ui fluid dropdown">
                                                            <option value="increase" <?php selected( $sale_countdown_progress_bar_type, 'increase' ); ?>>
                                                                <?php esc_html_e( 'Increase', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="decrease" <?php selected( $sale_countdown_progress_bar_type, 'decrease' ); ?>>
                                                                <?php esc_html_e( 'Decrease', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Order status', 'sales-countdown-timer' ) ?></label>
                                                    <input type="hidden"
                                                           name="sale_countdown_progress_bar_order_status[]"
                                                           value="<?php echo esc_attr( $sale_countdown_progress_bar_order_status ); ?>"
                                                           class="woo-sctr-progress-bar-order-status">
                                                    <div class="vi-ui input"
                                                         data-tooltip="<?php esc_attr_e( 'When new order created, update the progress bar when order status are(leave blank to apply for all order status):', 'sales-countdown-timer' ) ?>">
                                                        <select multiple class="woo-sctr-progress-bar-order-status-select vi-ui fluid dropdown">
                                                            <option value="wc-completed" <?php selected( in_array( 'wc-completed', $sale_countdown_progress_bar_order_status_arg ), true )?>>
                                                                <?php esc_html_e( 'Completed', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="wc-on-hold" <?php selected( in_array( 'wc-on-hold', $sale_countdown_progress_bar_order_status_arg ), true ) ?>>
                                                                <?php esc_html_e( 'On-hold', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="wc-pending" <?php selected( in_array( 'wc-pending', $sale_countdown_progress_bar_order_status_arg ), true ) ?>>
                                                                <?php esc_html_e( 'Pending', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="wc-processing" <?php  selected( in_array( 'wc-processing', $sale_countdown_progress_bar_order_status_arg ), true )?>>
                                                                <?php esc_html_e( 'Processing', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="wc-failed" <?php selected( in_array( 'wc-failed', $sale_countdown_progress_bar_order_status_arg ), true )?>>
                                                                <?php esc_html_e( 'Failed', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="wc-refunded" <?php selected( in_array( 'wc-refunded', $sale_countdown_progress_bar_order_status_arg ), true ) ?>>
                                                                <?php esc_html_e( 'Refunded', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                            <option value="wc-cancelled" <?php selected( in_array( 'wc-cancelled', $sale_countdown_progress_bar_order_status_arg ), true )?>>
                                                                <?php esc_html_e( 'Cancelled', 'sales-countdown-timer' ) ?>
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Progress bar position', 'sales-countdown-timer' ) ?></label>
                                                    <select name="sale_countdown_progress_bar_position[]" class="woo-sctr-progress-bar-position vi-ui dropdown">
                                                        <option value="above_countdown" <?php selected( $sale_countdown_progress_bar_position, 'above_countdown' ); ?>>
                                                            <?php esc_html_e( 'Above Countdown', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="below_countdown" <?php selected( $sale_countdown_progress_bar_position, 'below_countdown' ); ?>>
                                                            <?php esc_html_e( 'Below Countdown', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Progress bar message position', 'sales-countdown-timer' ) ?></label>
                                                    <select name="sale_countdown_progress_bar_message_position[]" class="woo-sctr-progress-bar-message-position vi-ui dropdown">
                                                        <option value="above_progressbar" <?php selected( $sale_countdown_progress_bar_message_position, 'above_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Above Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="below_progressbar" <?php selected( $sale_countdown_progress_bar_message_position, 'below_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Below Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="left_progressbar" <?php selected( $sale_countdown_progress_bar_message_position, 'left_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Left Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="in_progressbar" <?php selected( $sale_countdown_progress_bar_message_position, 'in_progressbar' ); ?>>
                                                            <?php esc_html_e( 'In Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="right_progressbar" <?php selected( $sale_countdown_progress_bar_message_position, 'right_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Right Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                    </select>
                                                    <input type="hidden" name="sale_countdown_progress_bar_template[]" value="1">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <h4 class="vi-ui dividing header">
                                                <label ><?php esc_html_e( 'Progress bar preview', 'sales-countdown-timer' ) ?></label>
                                            </h4>
                                            <div class="field woo-sctr-progress-bar-preview-wrap">
                                                <div class="woo-sctr-progress-bar-preview">
                                                    <!-- progress bar template 1 -->
	                                                <?php
	                                                $sale_countdown_progress_bar_message = str_replace( '{quantity_left}', 80, $sale_countdown_progress_bar_message );
	                                                $sale_countdown_progress_bar_message = str_replace( '{quantity_sold}', 20, $sale_countdown_progress_bar_message );
	                                                $sale_countdown_progress_bar_message = str_replace( '{percentage_sold}', 20, $sale_countdown_progress_bar_message );
	                                                $sale_countdown_progress_bar_message = str_replace( '{percentage_left}', 80, $sale_countdown_progress_bar_message );
	                                                $sale_countdown_progress_bar_message = str_replace( '{goal}', 100, $sale_countdown_progress_bar_message );
	                                                $progress_bar_class = array(
	                                                        'woo-sctr-progress-bar-wrap-container',
		                                                'woo-sctr-progress-bar-wrap-container-shortcode-' . $id
                                                    );
	                                                $progress_bar_class[]=$sale_countdown_progress_bar_template === '1' ? '' : 'woo-sctr-progress-bar-hidden';
	                                                $progress_bar_class[]= in_array( $sale_countdown_progress_bar_message_position, array( 'left_progressbar', 'right_progressbar' ) )  ? 'woo-sctr-progress-bar-wrap-inline' : '';
	                                                $progress_bar_class = trim(implode(' ', $progress_bar_class));
	                                                $progress_bar_mess_above =in_array( $sale_countdown_progress_bar_message_position, array( 'above_progressbar', 'left_progressbar' ) );
	                                                ?>
                                                    <div class="<?php echo  esc_attr($progress_bar_class); ?>">
                                                        <div class="woo-sctr-progress-bar-message woo-sctr-progress-bar-message-above<?php echo esc_attr($progress_bar_mess_above ?'':' woo-sctr-progress-bar-hidden'); ?>">
                                                            <?php echo wp_kses_post($sale_countdown_progress_bar_message) ?>
                                                        </div>
                                                        <div class="woo-sctr-progress-bar-wrap">
                                                            <div class="woo-sctr-progress-bar-fill"></div>
                                                            <div class="woo-sctr-progress-bar-message woo-sctr-progress-bar-message-in<?php echo esc_attr($sale_countdown_progress_bar_message_position === 'in_progressbar' ? '' : ' woo-sctr-progress-bar-hidden'); ?>">
		                                                        <?php echo wp_kses_post($sale_countdown_progress_bar_message); ?>
                                                            </div>
                                                        </div>
                                                        <div class="woo-sctr-progress-bar-message woo-sctr-progress-bar-message-below<?php echo esc_attr(!$progress_bar_mess_above ?'':' woo-sctr-progress-bar-hidden'); ?>">
	                                                        <?php echo wp_kses_post($sale_countdown_progress_bar_message); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field woo-sctr-design-progress-bar woo-sctr-design-progress-bar-1<?php echo esc_attr($sale_countdown_progress_bar_template === '1' ? '' : ' woo-sctr-progress-bar-hidden'); ?>">
	                                            <?php
	                                            $sale_countdown_progress_bar_template_1_background    = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_background', $i );
	                                            $sale_countdown_progress_bar_template_1_color         = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_color', $i );
	                                            $sale_countdown_progress_bar_template_1_message_color = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_message_color', $i );
	                                            $sale_countdown_progress_bar_template_1_border_radius = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_border_radius', $i );
	                                            $sale_countdown_progress_bar_template_1_width         = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_width', $i );
	                                            $sale_countdown_progress_bar_template_1_width_type    = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_width_type', $i );
	                                            $sale_countdown_progress_bar_template_1_height        = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_height', $i );
	                                            $sale_countdown_progress_bar_template_1_font_size     = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_font_size', $i );
	                                            ?>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-progress-bar-template-1-background"
                                                               name="sale_countdown_progress_bar_template_1_background[]" value="<?php echo esc_attr( $sale_countdown_progress_bar_template_1_background ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label ><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-progress-bar-template-1-color"
                                                               name="sale_countdown_progress_bar_template_1_color[]" value="<?php echo esc_attr( $sale_countdown_progress_bar_template_1_color ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Message color', 'sales-countdown-timer' ) ?></label>
                                                        <input type="text" class="color-picker woo-sctr-progress-bar-template-1-message-color"
                                                               name="sale_countdown_progress_bar_template_1_message_color[]" value="<?php echo esc_attr( $sale_countdown_progress_bar_template_1_message_color ) ?>">
                                                    </div>
                                                </div>
                                                <div class="equal width fields">
                                                    <div class="field">
                                                        <label>
			                                                <?php esc_html_e( 'Width', 'sales-countdown-timer' ) ?>
                                                        </label>
                                                        <div class="vi-ui right action labeled input">
                                                            <input type="number"
                                                                   class="woo-sctr-progress-bar-template-1-width"
                                                                   name="sale_countdown_progress_bar_template_1_width[]"
                                                                   min="0"
                                                                   value="<?php echo esc_attr( $sale_countdown_progress_bar_template_1_width ) ?>">
                                                            <select name="sale_countdown_progress_bar_template_1_width_type[]"
                                                                    class="vi-ui fluid dropdown woo-sctr-progress-bar-template-1-width-type">
                                                                <option value="%" <?php selected( $sale_countdown_progress_bar_template_1_width_type, '%' ) ?>>
					                                                <?php esc_html_e( '%', 'sales-countdown-timer' ); ?>
                                                                </option>
                                                                <option value="px" <?php selected( $sale_countdown_progress_bar_template_1_width_type, 'px' ) ?>>
					                                                <?php esc_html_e( 'px', 'sales-countdown-timer' ); ?>
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Height', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right action labeled input">
                                                        <input type="number"
                                                               class="woo-sctr-progress-bar-template-1-height"
                                                               name="sale_countdown_progress_bar_template_1_height[]"
                                                               min="0"
                                                               value="<?php echo esc_attr( $sale_countdown_progress_bar_template_1_height ) ?>">
                                                            <div class="vi-ui basic label">px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right action labeled input">
                                                            <input type="number"
                                                                   class="woo-sctr-progress-bar-template-1-border-radius"
                                                                   name="sale_countdown_progress_bar_template_1_border_radius[]"
                                                                   min="0"
                                                                   value="<?php echo esc_attr( $sale_countdown_progress_bar_template_1_border_radius ) ?>">
                                                            <div class="vi-ui basic label">px</div>
                                                        </div>
                                                    </div>
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Font size', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui right action labeled input">
                                                            <input type="number"
                                                                   class="woo-sctr-progress-bar-template-1-font-size"
                                                                   name="sale_countdown_progress_bar_template_1_font_size[]"
                                                                   min="0"
                                                                   value="<?php echo esc_attr( $sale_countdown_progress_bar_template_1_font_size ) ?>">
                                                            <div class="vi-ui basic label">px</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <h4 class="vi-ui dividing header">
		                                        <?php esc_html_e( 'Sale Countdown on the single product page', 'sales-countdown-timer' ) ?>
                                            </h4>
	                                        <?php
	                                        $sale_countdown_wrap_border_radius_in_single            = $this->settings->get_current_countdown( 'sale_countdown_wrap_border_radius_in_single', $i );
	                                        $sale_countdown_wrap_border_color_in_single             = $this->settings->get_current_countdown( 'sale_countdown_wrap_border_color_in_single', $i );
	                                        $sale_countdown_progress_bar_message_position_in_single = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_message_position_in_single', $i );
	                                        $sale_countdown_progress_bar_position_in_single         = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position_in_single', $i );
	                                        ?>
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                    <div class="vi-ui right labeled  input">
                                                        <input type="number"
                                                               class="woo-sctr-sale_countdown_wrap_border_radius_in_single"
                                                               name="sale_countdown_wrap_border_radius_in_single[]"
                                                               min="0"
                                                               value="<?php echo esc_attr( $sale_countdown_wrap_border_radius_in_single ) ?>">
                                                        <div class="vi-ui basic label">px</div>
                                                    </div>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border color', 'sales-countdown-timer' ) ?></label>
                                                    <input type="text"
                                                           class="color-picker woo-sctr-sale_countdown_wrap_border_color_in_single"
                                                           name="sale_countdown_wrap_border_color_in_single[]"
                                                           value="<?php echo esc_attr( $sale_countdown_wrap_border_color_in_single ) ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Progress bar position', 'sales-countdown-timer' ) ?></label>
                                                    <select name="sale_countdown_progress_bar_position_in_single[]" class="woo-sctr-progress-bar-position vi-ui dropdown">
                                                        <option value="above_countdown" <?php selected( $sale_countdown_progress_bar_position_in_single, 'above_countdown' ); ?>>
                                                            <?php esc_html_e( 'Above Countdown', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="below_countdown" <?php selected( $sale_countdown_progress_bar_position_in_single, 'below_countdown' ); ?>>
                                                            <?php esc_html_e( 'Below Countdown', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="left_countdown" <?php selected( $sale_countdown_progress_bar_position_in_single, 'left_countdown' ); ?>>
                                                            <?php esc_html_e( 'Left Countdown', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="right_countdown" <?php selected( $sale_countdown_progress_bar_position_in_single, 'right_countdown' ); ?>>
                                                            <?php esc_html_e( 'Right Countdown', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Progress bar message position', 'sales-countdown-timer' ) ?></label>
                                                    <select name="sale_countdown_progress_bar_message_position_in_single[]" class="woo-sctr-progress-bar-message-position vi-ui dropdown">
                                                        <option value="above_progressbar" <?php selected( $sale_countdown_progress_bar_message_position_in_single, 'above_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Above Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="below_progressbar" <?php selected( $sale_countdown_progress_bar_message_position_in_single, 'below_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Below Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="left_progressbar" <?php selected( $sale_countdown_progress_bar_message_position_in_single, 'left_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Left Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="in_progressbar" <?php selected( $sale_countdown_progress_bar_message_position_in_single, 'in_progressbar' ); ?>>
                                                            <?php esc_html_e( 'In Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                        <option value="right_progressbar" <?php selected( $sale_countdown_progress_bar_message_position_in_single, 'right_progressbar' ); ?>>
                                                            <?php esc_html_e( 'Right Progress Bar', 'sales-countdown-timer' ) ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <h4 class="vi-ui dividing header"><?php esc_html_e( 'Upcoming sale', 'sales-countdown-timer' ) ?></h4>
	                                        <?php
	                                        $sale_countdown_upcoming_enable              = $this->settings->get_current_countdown( 'sale_countdown_upcoming_enable', $i );
	                                        $sale_countdown_upcoming_progress_bar_enable = $this->settings->get_current_countdown( 'sale_countdown_upcoming_progress_bar_enable', $i );
	                                        $sale_countdown_upcoming_message             = $this->settings->get_current_countdown( 'sale_countdown_upcoming_message', $i );
	                                        ?>
                                            <div class="fields">
                                                <div class="three wide field">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Enable', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui toggle checkbox">
                                                            <input type="hidden" name="sale_countdown_upcoming_enable[]"
                                                                   class="woo-sctr-upcoming"
                                                                   value="<?php echo esc_attr( $sale_countdown_upcoming_enable ); ?>">
                                                            <input type="checkbox"
                                                                   class="woo-sctr-upcoming-check" <?php checked( $sale_countdown_upcoming_enable ,1); ?>><label></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="three wide field">
                                                    <div class="field">
                                                        <label><?php esc_html_e( 'Progress bar', 'sales-countdown-timer' ) ?></label>
                                                        <div class="vi-ui toggle checkbox">
                                                            <input type="hidden"
                                                                   name="sale_countdown_upcoming_progress_bar_enable[]"
                                                                   class="woo-sctr-upcoming-progress-bar"
                                                                   value="<?php echo esc_attr( $sale_countdown_upcoming_progress_bar_enable ); ?>">
                                                            <input type="checkbox"
                                                                   class="woo-sctr-upcoming-progress-bar-check" <?php checked($sale_countdown_upcoming_progress_bar_enable ,1); ?>><label></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="thirteen wide field">
                                                    <label><?php esc_html_e( 'Upcoming sale message', 'sales-countdown-timer' ) ?></label>
                                                    <textarea rows="3"
                                                              name="sale_countdown_upcoming_message[]"
                                                              class="woo-sctr-upcoming-message"><?php echo wp_kses_post(wp_unslash($sale_countdown_upcoming_message)); ?></textarea>
                                                    <p>{countdown_timer} - <?php esc_html_e( 'The countdown timer that you set on the design tab', 'sales-countdown-timer' ) ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
						<?php
					}
					?>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="update">
                    <table class="form-table">
                        <tbody>
                        <tr valign="top">
                            <th scope="row">
                                <label for="auto-update-key"><?php esc_html_e( 'Auto update key', 'sales-countdown-timer' ) ?></label>
                            </th>
                            <td>
                                <div class="fields">

                                    <div class="ten wide field">
                                        <input type="text" name="sctv_update_key" id="auto-update-key"
                                               class="villatheme-autoupdate-key-field"
                                               value="<?php echo $this->settings->get_params( 'update_key' ); ?>">
                                    </div>
                                    <div class="six wide field">
                                        <span class="vi-ui button green villatheme-get-key-button" style="max-height: 37px;line-height: .65em"
                                              data-href="https://api.envato.com/authorization?response_type=code&client_id=villatheme-download-keys-6wzzaeue&redirect_uri=https://villatheme.com/update-key"
                                              data-id="25636260"><?php echo esc_html__( 'Get Key', 'sales-countdown-timer' ) ?></span>
                                    </div>
                                </div>
								<?php do_action( 'sales-countdown-timer_key' ) ?>
                                <p class="description"><?php _e( 'Please fill your key what you get from <a target="_blank" href="https://villatheme.com/my-download">Villatheme</a>. See <a target="_blank" href="https://villatheme.com/knowledge-base/how-to-use-auto-update-feature/">guide</a>.',
										'sales-countdown-timer' ) ?>
                                </p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p class="woo-sctr-save-wrap">
                    <button type="button" class="woo-sctr-save woo-sctr-bt-save vi-ui primary button" name="woo-sctr-save">
						<?php esc_html_e( 'Save', 'sales-countdown-timer' ); ?>
                    </button>
                    <button type="button" class="woo-sctr-check-key vi-ui button labeled icon woo-sctr-bt-save" name="woo-sctr-check_key">
                        <i class="send icon"></i> <?php esc_html_e( 'Save & Check Key', 'sales-countdown-timer' ) ?>
                    </button>
                </p>
            </form>
			<?php
			do_action( 'villatheme_support_sales-countdown-timer' );
			?>
        </div>
        <div class="woo-sctr-save-sucessful-popup">
			<?php esc_html_e( 'Settings saved', 'sales-countdown-timer' ); ?>
        </div>
		<?php
	}
	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		if ( $page === 'sales-countdown-timer' ) {
			$admin = 'VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Settings';
			$admin::remove_other_script();
			$admin::enqueue_style(
				array( 'semantic-ui-button', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-icon', 'semantic-ui-input', 'semantic-ui-label' ),
				array( 'button.min.css', 'checkbox.min.css', 'dropdown.min.css', 'form.min.css', 'icon.min.css', 'input.min.css', 'label.min.css' )
			);
			$admin::enqueue_style(
				array( 'semantic-ui-accordion', 'semantic-ui-header', 'semantic-ui-menu', 'semantic-ui-segment', 'semantic-ui-popup', 'semantic-ui-tab' ),
				array( 'accordion.min.css', 'header.min.css', 'menu.min.css', 'segment.min.css', 'popup.min.css', 'tab.css' )
			);
			$admin::enqueue_style(
				array( 'vi-sales-countdown-timer-admin-settings', 'vi-sales-countdown-timer-admin-shortcode', 'transition' ),
				array( 'admin-settings.css','shortcode-countdown.css', 'transition.min.css' )
			);
			$admin::enqueue_script(
				array( 'semantic-ui-accordion', 'semantic-ui-address', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-tab' ),
				array( 'accordion.min.js', 'address.min.js', 'checkbox.min.js', 'dropdown.min.js', 'form.min.js', 'tab.js' )
			);
			$admin::enqueue_script(
				array( 'vi-sales-countdown-timer-admin-settings', 'transition' ),
				array( 'admin-settings.js', 'transition.min.js' )
			);
			/*Color picker*/
			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ),
				array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch', ),
				false, 1 );
			wp_add_inline_style( 'vi-sales-countdown-timer-admin-settings',VI_SCT_SALES_COUNTDOWN_TIMER_Countdown_Style::get_backend_countdown_css() ?: '.wrap{}' );
		}
	}
}