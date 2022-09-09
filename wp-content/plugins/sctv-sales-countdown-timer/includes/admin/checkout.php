<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Checkout {

	protected $settings;

	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 20 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 999999 );
			add_action( 'admin_init', array( $this, 'save_checkout_countdown_settings' ), 99 );
		}
		add_action( 'wp_ajax_sctv_test_mode_reset', array( $this, 'sctv_test_mode_reset' ) );
	}

	public function admin_menu() {
		add_submenu_page(
			'sales-countdown-timer',
			__( 'Checkout Countdown', 'sales-countdown-timer' ),
			__( 'Checkout Countdown', 'sales-countdown-timer' ),
			'manage_options',
			'sales-countdown-timer-checkout',
			array( $this, 'settings_callback' )
		);
	}

	public function sctv_test_mode_reset() {
		$result          = array(
			'status'  => '',
			'message' => '',
			'detail'  => '',
		);
		$current_user_id = get_current_user_id();
		if ( $current_user_id ) {
			global $wpdb;
			$wpdb->delete( $wpdb->prefix . 'woocommerce_sessions', array( 'session_key' => $current_user_id ) );
			$result['status']  = 'success';
			$result['message'] = esc_html__( 'Deleted checkout countdown data', 'sales-countdown-timer' );
		} else {
			$result['status']  = 'error';
			$result['message'] = esc_html__( 'Not found user id', 'sales-countdown-timer' );
		}
		wp_send_json( $result );
	}

	public function save_checkout_countdown_settings() {

		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page !== 'sales-countdown-timer-checkout' ) {
			return;
		}
		global $woo_ctr_settings;

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_woo_ctr_settings_checkout_page_nonce'] ) || ! wp_verify_nonce( $_POST['_woo_ctr_settings_checkout_page_nonce'],
				'woo_ctr_settings_checkout_page_action_nonce' ) ) {
			return;
		}
		$args = array(
			'checkout_countdown_enable'                        => '',
			'checkout_countdown_reset'                         => 30,
			'checkout_countdown_before_active_enable'          => '',
			'checkout_countdown_cart_total_start'              => 1,
			'checkout_countdown_start'                         => 'to_checkout_page',
			'checkout_countdown_time_minute'                   => 0,
			'checkout_countdown_time_second'                   => 0,
			'checkout_countdown_message_checkout_page'         => 'checkout now to get discount in  {countdown_timer}',
			'checkout_countdown_message_other_page'            => 'checkout now to get discount in  {countdown_timer} {checkout_button}',
			'checkout_countdown_message_checkout_page_missing' => 'Checkout now to get discount in {countdown_timer} Continue {shop_button}',
			'checkout_countdown_message_other_page_missing'    => 'Checkout now to get discount in  {countdown_timer} ',
			'checkout_countdown_free_ship'                     => '',
			'checkout_countdown_discount_type'                 => 'percent',
			'checkout_countdown_discount_amount'               => 0,
			'checkout_countdown_free_product_enable'           => '',
			'checkout_countdown_free_products'                 => array(),
			'checkout_countdown_free_product_quantity'         => '1',

			'checkout_countdown_change' => 'none',

			'checkout_countdown_custom_ids'                    => array( 'checkoutcountdownt1' ),
			'checkout_countdown_custom_minutes'                => array( 5 ),
			'checkout_countdown_custom_seconds'                => array( 0 ),
			'checkout_countdown_custom_messages_checkout_page' => array( 'checkout now to get discount in {countdown_timer} ' ),
			'checkout_countdown_custom_messages_other_page'    => array( 'checkout now to get discount in {countdown_timer} {checkout_button}' ),
			'checkout_countdown_custom_free_ships'             => array( '' ),
			'checkout_countdown_custom_free_products'          => array( '' ),
			'checkout_countdown_custom_discount_value'         => array( '' ),

			'checkout_countdown_auto_change_time_type'     => 'minute',
			'checkout_countdown_auto_change_time'          => 0,
			'checkout_countdown_auto_change_details_value' => 0,


			'checkout_countdown_display_on_page'           => array(),
			'checkout_countdown_display_on_assign_page'    => '',
			'checkout_countdown_position_on_checkout_page' => 'before_submit',
			'checkout_countdown_position_on_archive_page'  => 'sticky_top',
			'checkout_countdown_id_on_checkout_page'       => 'salescountdowntimer',
			'checkout_countdown_id_on_other_page'          => 'salescountdowntimer',
			'checkout_countdown_resize'                    => '55',
			'checkout_button_checkout_fontsize'            => '16',
			'checkout_button_checkout_color'               => '#fff',
			'checkout_button_checkout_background'          => 'olive',
			'checkout_button_checkout_title'               => 'Checkout',
			'checkout_button_checkout_link_target'         => '',
			'checkout_button_shop_fontsize'                => '16',
			'checkout_button_shop_color'                   => '#fff',
			'checkout_button_shop_background'              => '#15190f',
			'checkout_button_shop_title'                   => 'Shopping',
			'checkout_button_shop_link_target'             => '',

			'checkout_test_mode_enable' => '1',

			'checkout_countdown_save_log'     => '',
			'checkout_countdown_history_time' => 30,
		);
		foreach ( $args as $item_key => $item_arg ) {
			if ( in_array( $item_key,
				array(
					'checkout_countdown_free_products',
					'checkout_countdown_custom',
					'checkout_countdown_display_on_page',
					'checkout_countdown_custom_ids',
					'checkout_countdown_custom_minutes',
					'checkout_countdown_custom_seconds',
					'checkout_countdown_custom_free_ships',
					'checkout_countdown_custom_free_products',
					'checkout_countdown_custom_discount_value',
				) ) ) {
				$args[ $item_key ] = isset( $_POST[ $item_key ] ) ? array_map( 'sanitize_text_field', $_POST[ $item_key ] ) : array();
			} elseif ( in_array( $item_key,
				array(
					'checkout_countdown_custom_messages_checkout_page',
					'checkout_countdown_custom_messages_other_page',
				) ) ) {
				$args[ $item_key ] = isset( $_POST[ $item_key ] ) ? woo_ctr_stripslashes_deep( $_POST[ $item_key ] ) : array();
			} elseif ( in_array( $item_key,
				array(
					'checkout_countdown_message_checkout_page',
					'checkout_countdown_message_other_page',
					'checkout_countdown_message_checkout_page_missing',
					'checkout_countdown_message_other_page_missing',
				) ) ) {
				$args[ $item_key ] = isset( $_POST[ $item_key ] ) ? wp_kses_post( stripslashes( $_POST[ $item_key ] ) ) : '';
			} elseif ( in_array( $item_key,
				array(
					'checkout_countdown_time_minute',
					'checkout_countdown_time_second',
					'checkout_countdown_discount_amount',
					'checkout_countdown_free_product_quantity',
					'checkout_countdown_auto_change_time',
					'checkout_countdown_auto_change_details_value',
				) ) ) {
				$args[ $item_key ] = ( isset( $_POST[ $item_key ] ) && $_POST[ $item_key ] ) ? sanitize_text_field( stripslashes( $_POST[ $item_key ] ) ) : 0;
			} else {
				$args[ $item_key ] = isset( $_POST[ $item_key ] ) ? sanitize_text_field( stripslashes( $_POST[ $item_key ] ) ) : '';
			}
		}

		$args = wp_parse_args( $args, get_option( 'sales_countdown_timer_params', $woo_ctr_settings ) );

		update_option( 'sales_countdown_timer_params', $args );
		$woo_ctr_settings = $args;
	}

	public function settings_callback() {
		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		?>
        <div class="wrap">
            <h2 class=""><?php esc_html_e( 'Checkout Countdown Timer For WooCommerce', 'sales-countdown-timer' ) ?></h2>
            <div class="vi-ui raised">
                <form action="" class="vi-ui form" method="post">
					<?php

					wp_nonce_field( 'woo_ctr_settings_checkout_page_action_nonce',
						'_woo_ctr_settings_checkout_page_nonce' );

					?>
                    <div class="vi-ui vi-ui-main top tabular attached menu">

                        <a class="item active" data-tab="general">

							<?php esc_html_e( 'General Settings', 'sales-countdown-timer' ) ?>

                        </a>

                        <a class="item" data-tab="display_on_page">

							<?php esc_html_e( 'Display checkout countdown', 'sales-countdown-timer' ) ?>

                        </a>
                        <a class="item" data-tab="design_on_cp">

							<?php esc_html_e( 'Design on checkout page', 'sales-countdown-timer' ) ?>

                        </a>
                        <a class="item" data-tab="design_on_op">

							<?php esc_html_e( 'Design on other page', 'sales-countdown-timer' ) ?>

                        </a>

                        <a class="item" data-tab="report">
							<?php esc_html_e( 'Report', 'sales-countdown-timer' ) ?>
                        </a>


                    </div>
                    <div class="vi-ui bottom attached tab segment active" data-tab="general">
                        <div class="vi-ui yellow message"><?php esc_html_e( 'Note*: Changes in these settings will not be applied to carts which are already in a checkout countdown.',
								'sales-countdown-timer' ) ?></div>
                        <table class="form-table">
                            <tbody>
                            <tr>
								<?php
								$checkout_countdown_enable = $this->settings->get_params( 'checkout_countdown_enable' );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-enable"><?php esc_html_e( 'Enable',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="checkbox"
                                               name="checkout_countdown_enable"
                                               id="woo-stcr-checkout-countdown-enable"
                                               value="<?php esc_attr_e( $checkout_countdown_enable ) ?>"
											<?php checked( $checkout_countdown_enable, '1' ) ?>>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-reset"><?php esc_html_e( 'Reset Countdown',
											'sales-countdown-timer' ); ?></label>
                                </th>
                                <td>
									<?php
									$checkout_countdown_reset = $this->settings->get_params( 'checkout_countdown_reset' );
									?>
                                    <div class="vi-ui right labeled input">
                                        <input type="number" min="1"
                                               value="<?php esc_attr_e( $checkout_countdown_reset ); ?>"
                                               name="checkout_countdown_reset" id="woo-stcr-checkout-countdown-reset">
                                        <div class="vi-ui basic label"><?php esc_html_e( 'Days',
												'sales-countdown-timer' ); ?></div>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Reset checkout countdown for a cart after this time if customer does not place order during checkout countdown',
											'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            <tr class="top">
								<?php
								$checkout_countdown_time_minute = $this->settings->get_params( 'checkout_countdown_time_minute' ) ? $this->settings->get_params( 'checkout_countdown_time_minute' ) : 0;
								$checkout_countdown_time_second = $this->settings->get_params( 'checkout_countdown_time_second' ) ? $this->settings->get_params( 'checkout_countdown_time_second' ) : 0;
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-time-minute"><?php esc_html_e( 'Countdown time',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0"
                                                       name="checkout_countdown_time_minute"
                                                       id="woo-stcr-checkout-countdown-time-minute"
                                                       class="woo-stcr-checkout-countdown-time-minute-class"
                                                       value="<?php esc_attr_e( $checkout_countdown_time_minute ) ?>">
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Minute', 'sales-countdown-timer' ) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="vi-ui right labeled input">
                                                <input type="number"
                                                       min="0" max="59"
                                                       name="checkout_countdown_time_second"
                                                       id="woo-stcr-checkout-countdown-time-second"
                                                       class="woo-stcr-checkout-countdown-time-second-class"
                                                       value="<?php esc_attr_e( $checkout_countdown_time_second ) ?>">
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Second', 'sales-countdown-timer' ) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'The maximum valid time that this offer bellow are applied. When time hits zero, offer will be gone',
											'sales-countdown-timer' ); ?></p>
                                    <p class="description woo-stcr-checkout-countdown-warning woo-stcr-checkout-countdown-time-warning woo-sctr-hidden"><?php esc_html_e( 'Minimum valid value is 1 second',
											'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            <tr>
								<?php
								$checkout_countdown_start            = $this->settings->get_params( 'checkout_countdown_start' );
								$checkout_countdown_start_arg        = array(
									'add_to_cart'      => __( 'Add to cart', 'sales-countdown-timer' ),
									'to_checkout_page' => __( 'To checkout page', 'sales-countdown-timer' ),
									'to_cart_page'     => __( 'To cart page', 'sales-countdown-timer' ),
								);
								$checkout_countdown_cart_total_start = $this->settings->get_params( 'checkout_countdown_cart_total_start' );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-start"><?php esc_html_e( 'Starting condition',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <div class="vi-ui labeled right action input">
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Action', 'sales-countdown-timer' ) ?>
                                                </div>
                                                <select name="checkout_countdown_start"
                                                        id="woo-stcr-checkout-countdown-start"
                                                        class="vi-ui basic fluid dropdown woo-stcr-checkout-countdown-start">
													<?php
													foreach ( $checkout_countdown_start_arg as $item_id => $item_title ) {
														?>
                                                        <option value="<?php esc_attr_e( $item_id ) ?>" <?php selected( $checkout_countdown_start,
															$item_id ) ?> ><?php echo $item_title; ?></option>
														<?php
													}
													?>
                                                </select>
                                            </div>
                                            <p class="description"><?php esc_html_e( 'Start checking if a cart is qualified to get offer when:',
													'sales-countdown-timer' ); ?></p>
                                        </div>
                                        <div class="field">
                                            <div class="vi-ui labeled  input">
                                                <div class="vi-ui basic label">
													<?php printf( __( 'Min cart total(%s)', 'sales-countdown-timer' ),
														get_woocommerce_currency_symbol() ) ?>
                                                </div>
                                                <input type="number" min="1" name="checkout_countdown_cart_total_start"
                                                       id="woo-stcr-checkout-countdown-cart-total-start"
                                                       value="<?php esc_attr_e( $checkout_countdown_cart_total_start ? $checkout_countdown_cart_total_start : 10 ) ?>"
                                                       required>
                                            </div>
                                            <p class="description"><?php esc_html_e( 'Minimum cart total to get offer',
													'sales-countdown-timer' ); ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
								<?php
								$checkout_countdown_free_ship             = $this->settings->get_params( 'checkout_countdown_free_ship' );
								$checkout_countdown_discount_type         = $this->settings->get_params( 'checkout_countdown_discount_type' );
								$checkout_countdown_discount_amount       = $this->settings->get_params( 'checkout_countdown_discount_amount' );
								$checkout_countdown_free_product_enable   = $this->settings->get_params( 'checkout_countdown_free_product_enable' );
								$checkout_countdown_free_products         = $this->settings->get_params( 'checkout_countdown_free_products' );
								$checkout_countdown_free_product_quantity = $this->settings->get_params( 'checkout_countdown_free_product_quantity' );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-discount"><?php esc_html_e( 'Checkout countdown offer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <div class="vi-ui toggle checkbox">
                                                <input type="checkbox"
                                                       name="checkout_countdown_free_ship"
                                                       id="woo-stcr-checkout-countdown-free-ship"
                                                       value="<?php esc_attr_e( $checkout_countdown_free_ship ) ?>"
													<?php checked( $checkout_countdown_free_ship, '1' ) ?>>
                                                <label for="woo-stcr-checkout-countdown-free-ship"><?php esc_html_e( 'Free Shipping',
														'sales-countdown-timer' ) ?></label>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="vi-ui right action labeled input">
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Discount amount', 'sales-countdown-timer' ) ?>
                                                </div>
                                                <input type="number"
                                                       min="1"
                                                       name="checkout_countdown_discount_amount"
                                                       id="woo-stcr-checkout-countdown-discount-amount"
                                                       class="<?php echo $checkout_countdown_discount_type === 'none' ? 'woo-sctr-hidden' : ''; ?>"
                                                       placeholder="<?php esc_html_e( 'Discount amount',
													       'sales-countdown-timer' ) ?>"
                                                       value="<?php esc_attr_e( $checkout_countdown_discount_amount ) ?>"
                                                       style="width: 30%;" <?php echo $checkout_countdown_discount_type === 'none' ? '' : 'required'; ?>>
                                                <select name="checkout_countdown_discount_type"
                                                        id="woo-stcr-checkout-countdown-discount-type"
                                                        class="vi-ui fluid dropdown woo-stcr-checkout-countdown-discount-type">
                                                    <option value="none" <?php selected( $checkout_countdown_discount_type,
														'none' ) ?> ><?php esc_html_e( 'None',
															'sales-countdown-timer' ) ?></option>
                                                    <option value="percent" <?php selected( $checkout_countdown_discount_type,
														'percent' ) ?> ><?php esc_html_e( 'Percentage(%)',
															'sales-countdown-timer' ) ?></option>
                                                    <option value="fixed" <?php selected( $checkout_countdown_discount_type,
														'fixed' ) ?> ><?php printf( __( 'Fixed(%s)',
															'sales-countdown-timer' ),
															get_woocommerce_currency_symbol() ) ?></option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Customers will get this offer before checkout countdown time hits zero',
											'sales-countdown-timer' ); ?></p>
                                    <p class="description woo-stcr-checkout-countdown-warning woo-stcr-checkout-offer-warning woo-sctr-hidden"><?php esc_html_e( 'Please set the offer for your Checkout countdown',
											'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="vi-ui blue message">
                                        <div class="header"><?php esc_html_e( 'Below options are used if you want to change offer when the countdown hits specific values',
												'sales-countdown-timer' ) ?></div>
                                        <ul class="list">
                                            <li><?php esc_html_e( 'None: The current discount is applied until the countdown hits zero',
													'sales-countdown-timer' ) ?></li>
                                            <li><?php esc_html_e( 'Auto change: Decrease the current discount by a specific percentage every X minute(s) or second(s)',
													'sales-countdown-timer' ) ?></li>
                                            <li><?php esc_html_e( 'Custom: Create your own levels that you want to change the offer, message can be customized for each level',
													'sales-countdown-timer' ) ?></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
								<?php
								$checkout_countdown_change = $this->settings->get_params( 'checkout_countdown_change' );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-change"><?php esc_html_e( 'Level discount',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <select name="checkout_countdown_change"
                                            id="woo-stcr-checkout-countdown-change"
                                            class="vi-ui fluid dropdown woo-stcr-checkout-countdown-change">
                                        <option value="none" <?php selected( $checkout_countdown_change,
											'none' ) ?> ><?php esc_html_e( 'None', 'sales-countdown-timer' ) ?></option>
                                        <option value="auto_change" <?php selected( $checkout_countdown_change,
											'auto_change' ) ?> ><?php esc_html_e( 'Auto change',
												'sales-countdown-timer' ) ?></option>
                                        <option value="custom" <?php selected( $checkout_countdown_change,
											'custom' ) ?> ><?php esc_html_e( 'Custom',
												'sales-countdown-timer' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr class="woo-stcr-checkout-countdown-change-wrap woo-stcr-checkout-countdown-change-auto-change-warning woo-sctr-hidden">
                                <th></th>
                                <td>
                                    <p class="description woo-stcr-checkout-countdown-warning"><?php esc_html_e( 'This option will only be available if \'Discount amount\' has value',
											'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            <tr
                                    class="woo-stcr-checkout-countdown-change-wrap woo-stcr-checkout-countdown-change-auto-change <?php echo $checkout_countdown_change === 'auto_change' ? '' : 'woo-sctr-hidden' ?>">
								<?php

								$checkout_countdown_auto_change_time_type = $this->settings->get_params( 'checkout_countdown_auto_change_time_type' );
								$checkout_countdown_auto_change_time      = $this->settings->get_params( 'checkout_countdown_auto_change_time' );
								?>
                                <th>
                                    <label for=""><?php esc_html_e( 'Change discount every',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="field">
                                        <div class="vi-ui right action input">
                                            <input type="number"
                                                   name="checkout_countdown_auto_change_time"
                                                   id="woo-stcr-checkout-countdown-change-auto-change-time"
                                                   value="<?php esc_attr_e( $checkout_countdown_auto_change_time ) ?>" <?php echo $checkout_countdown_discount_type === 'auto_change' ? 'required' : ''; ?>/>
                                            <select name="checkout_countdown_auto_change_time_type"
                                                    id="woo-stcr-checkout-countdown-change-auto-change-time-type"
                                                    class="vi-ui  dropdown woo-stcr-checkout-countdown-change-auto-change-time-type">
                                                <option value="minute" <?php selected( $checkout_countdown_auto_change_time_type,
													'minute' ) ?> ><?php esc_html_e( 'Minute',
														'sales-countdown-timer' ) ?></option>
                                                <option value="second" <?php selected( $checkout_countdown_auto_change_time_type,
													'second' ) ?> ><?php esc_html_e( 'Second',
														'sales-countdown-timer' ) ?></option>
                                            </select>
                                        </div>
                                        <p class="description wotv-error-auto-change-time  wotv-error-auto-change-time-no-value <?php echo $checkout_countdown_auto_change_time ? 'woo-sctr-hidden' : ''; ?>">
											<?php
											esc_html_e( 'The discount won\'t be changed if not change time',
												'sales-countdown-timer' )
											?>
                                        </p>
                                        <p class="description wotv-error-auto-change-time wotv-error-auto-change-time-over-time  woo-sctr-hidden">
											<?php
											esc_html_e( 'The change time must be less than countdown time',
												'sales-countdown-timer' )
											?>
                                        </p>
                                        <p class="description wotv-error-auto-change-time wotv-error-auto-change-time-minimum-second <?php echo $checkout_countdown_auto_change_time === 'second' && (int) $checkout_countdown_auto_change_time < 15 ? '' : 'woo-sctr-hidden' ?>">
											<?php
											esc_html_e( 'The change time must be over 15 second or 1 minute',
												'sales-countdown-timer' )
											?>
                                        </p>
                                    </div>
                                </td>
                            </tr>

                            <tr
                                    class="woo-stcr-checkout-countdown-change-wrap woo-stcr-checkout-countdown-change-auto-change <?php echo $checkout_countdown_change === 'auto_change' ? '' : 'woo-sctr-hidden' ?>">
								<?php
								$checkout_countdown_auto_change_details_type  = $this->settings->get_params( 'checkout_countdown_auto_change_details_type' );
								$checkout_countdown_auto_change_details_value = $this->settings->get_params( 'checkout_countdown_auto_change_details_value' );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-change-auto-change-detail-value"><?php esc_html_e( 'Decrease discount amount',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>

                                    <div class="input vi-ui"
                                         data-tooltip="<?php esc_attr_e( 'If the value is 10 percent, the checkout discount will reduce 10% of current value each time',
										     'sales-countdown-timer' ) ?>">
                                        <div class="field">
                                            <div class="vi-ui right labeled input">
                                                <input type="number"
                                                       min="1"
                                                       name="checkout_countdown_auto_change_details_value"
                                                       id="woo-stcr-checkout-countdown-change-auto-change-detail-value"
                                                       value="<?php esc_attr_e( $checkout_countdown_auto_change_details_value ) ?>" <?php echo $checkout_countdown_discount_type === 'auto_change' ? 'required' : ''; ?>>
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Percentage', 'sales-countdown-timer' ) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr
                                    class="woo-stcr-checkout-countdown-change-wrap woo-stcr-checkout-countdown-change-custom <?php echo $checkout_countdown_change === 'custom' ? '' : 'woo-sctr-hidden' ?>">
                                <td colspan="2">
									<?php
									$checkout_countdown_custom_ids = $this->settings->get_params( 'checkout_countdown_custom_ids' );

									if ( $checkout_countdown_custom_ids && count( $checkout_countdown_custom_ids ) ) {
										ob_start();
										?>
                                        <div class="woo-stcr-checkout-countdown-change-custom-wrap-wrap">
											<?php
											for ( $i = 0; $i < count( $checkout_countdown_custom_ids ); $i ++ ) {
												$checkout_countdown_custom_id             = $checkout_countdown_custom_ids[ $i ];
												$checkout_countdown_custom_minute         = $this->settings->get_params( 'checkout_countdown_custom_minutes' )[ $i ];
												$checkout_countdown_custom_second         = $this->settings->get_params( 'checkout_countdown_custom_seconds' )[ $i ];
												$checkout_countdown_custom_message_pg     = $this->settings->get_params( 'checkout_countdown_custom_messages_checkout_page' )[ $i ];
												$checkout_countdown_custom_message_og     = $this->settings->get_params( 'checkout_countdown_custom_messages_other_page' )[ $i ];
												$checkout_countdown_custom_free_ship      = isset( $this->settings->get_params( 'checkout_countdown_custom_free_ships' )[ $i ] ) ? $this->settings->get_params( 'checkout_countdown_custom_free_ships' )[ $i ] : '';
												$checkout_countdown_custom_free_product   = isset( $this->settings->get_params( 'checkout_countdown_custom_free_products' )[ $i ] ) ? $this->settings->get_params( 'checkout_countdown_custom_free_products' )[ $i ] : '';
												$checkout_countdown_custom_discount_value = isset( $this->settings->get_params( 'checkout_countdown_custom_discount_value' )[ $i ] ) ? $this->settings->get_params( 'checkout_countdown_custom_discount_value' )[ $i ] : 0;
												?>
                                                <div class="vi-ui styled fluid accordion field woo-stcr-checkout-countdown-change-custom-wrap"
                                                     data-custom_id="<?php esc_attr_e( $i ) ?>">
                                                    <div class="title">
                                                            <span>
                                                                <i class="dropdown icon"></i><?php esc_html_e( 'Custom checkout countdown level ',
		                                                            'sales-countdown-timer' ) ?>
                                                                <span class="woo-stcr-checkout-countdown-decrease-custom-id woo-stcr-checkout-countdown-decrease-custom-id-<?php esc_attr_e( $i ) ?>"><?php echo $i + 1; ?></span>

                                                            </span>
                                                        <span class="woo-sctr-button-edit">
                                                            <span class="woo-sctr-button-edit-duplicate vi-ui positive button"><?php esc_html_e( 'Duplicate',
		                                                            'sales-countdown-timer' ); ?></span>
                                                            <span class="woo-sctr-button-edit-remove vi-ui negative button"><?php esc_html_e( 'Remove',
		                                                            'sales-countdown-timer' ); ?></span>
                                                        </span>
                                                    </div>
                                                    <div class="content">
                                                        <div class="field woo-stcr-checkout-countdown-change-custom-content">
                                                            <div class="equal width fields">
                                                                <div class="field">
                                                                    <input type="hidden"
                                                                           value="<?php esc_attr_e( $checkout_countdown_custom_id ) ?>"
                                                                           name="checkout_countdown_custom_ids[]"
                                                                           id="woo-stcr-checkout-countdown-decrease-custom-ids"
                                                                           class="woo-stcr-checkout-countdown-decrease-custom-ids">
                                                                    <div class="vi-ui  right labeled input">
                                                                        <input type="number" min="0"
                                                                               value="<?php esc_attr_e( $checkout_countdown_custom_minute ) ?>"
                                                                               name="checkout_countdown_custom_minutes[]"
                                                                               id="woo-stcr-checkout-countdown-decrease-custom-minute"
                                                                               class="woo-stcr-checkout-countdown-time-minute-class woo-stcr-checkout-countdown-decrease-custom-minute">
                                                                        <div class="vi-ui basic label">
																			<?php esc_html_e( 'Minute',
																				'sales-countdown-timer' ) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <div class="vi-ui  right labeled  input">
                                                                        <input type="number"
                                                                               min="0" max="59"
                                                                               value="<?php esc_attr_e( $checkout_countdown_custom_second ) ?>"
                                                                               name="checkout_countdown_custom_seconds[]"
                                                                               id="woo-stcr-checkout-countdown-decrease-custom-second"
                                                                               class="woo-stcr-checkout-countdown-time-second-class woo-stcr-checkout-countdown-decrease-custom-second">
                                                                        <div class="vi-ui basic label">
																			<?php esc_html_e( 'Second',
																				'sales-countdown-timer' ) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="field">
																<?php
																$message_default_custom_pg = explode( '{countdown_timer}',
																	$checkout_countdown_custom_message_pg );
																?>
                                                                <label for=""><?php esc_html_e( 'Message on checkout page',
																		'sales-countdown-timer' ) ?></label>

                                                                <textarea
                                                                        name="checkout_countdown_custom_messages_checkout_page[]"
                                                                        rows="3"
                                                                        id="woo-stcr-checkout-countdown-decrease-custom-message-cp"
                                                                        class="woo-stcr-checkout-countdown-decrease-custom-message"><?php echo $checkout_countdown_custom_message_pg ?></textarea>
                                                                <p class="description woo-sctr-warning-message-checkout-countdown-timer <?php echo count( $message_default_custom_pg ) >= 2 ? 'woo-sctr-hidden' : ''; ?>">
																	<?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}',
																		'sales-countdown-timer' ) ?>
                                                                </p>
                                                            </div>
                                                            <div class="field">
																<?php
																$message_default_custom_og = explode( '{countdown_timer}',
																	$checkout_countdown_custom_message_og );
																?>
                                                                <label for=""><?php esc_html_e( 'Message on other page',
																		'sales-countdown-timer' ) ?></label>

                                                                <textarea
                                                                        name="checkout_countdown_custom_messages_other_page[]"
                                                                        rows="3"
                                                                        id="woo-stcr-checkout-countdown-decrease-custom-message-op"
                                                                        class="woo-stcr-checkout-countdown-decrease-custom-message"><?php echo $checkout_countdown_custom_message_og ?></textarea>
                                                                <p class="description woo-sctr-warning-message-checkout-countdown-timer <?php echo count( $message_default_custom_og ) >= 2 ? 'woo-sctr-hidden' : ''; ?>">
		                                                            <?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}',
			                                                            'sales-countdown-timer' ) ?>
                                                                </p><p class="description">{countdown_timer}
                                                                    - <?php esc_html_e( 'The countdown timer that you set up on \'Sales Countdown Timer\' page',
																		'sales-countdown-timer' ) ?></p>
                                                                <p class="description">{checkout_button}
                                                                    - <?php esc_html_e( 'The checkout button',
																		'sales-countdown-timer' ) ?></p>
                                                                <p class="description">{discount_percentage}
                                                                    - <?php esc_html_e( 'The discount in percentage',
																		'sales-countdown-timer' ) ?></p>
                                                                <p class="description">{discount_fixed}
                                                                    - <?php esc_html_e( 'The discount amount in currency',
																		'sales-countdown-timer' ) ?></p>
                                                                <p class="description">{minimum_cart_total}
                                                                    - <?php esc_html_e( 'The minimum cart total required to get offer',
																		'sales-countdown-timer' ) ?></p>
                                                                <p class="description">{original_cart_total}
                                                                    - <?php esc_html_e( 'The current cart total without applying discount ',
																		'sales-countdown-timer' ) ?></p>
                                                            </div>
                                                            <div class="equal width fields">
                                                                <div class="field">
                                                                    <label for=""><?php esc_html_e( 'Free ship',
																			'sales-countdown-timer' ) ?></label>
                                                                    <div class="vi-ui checkbox toggle">
                                                                        <input type="hidden"
                                                                               value="<?php esc_attr_e( $checkout_countdown_custom_free_ship ) ?>"
                                                                               name="checkout_countdown_custom_free_ships[]">
                                                                        <input type="checkbox"
                                                                               id="woo-stcr-checkout-countdown-decrease-custom-free-ship"
                                                                               class="woo-stcr-checkout-countdown-decrease-custom-free-ship"
																			<?php checked( $checkout_countdown_custom_free_ship,
																				'1' ) ?>>
                                                                    </div>
                                                                </div>
                                                                <div class="field">
                                                                    <label for=""><?php esc_html_e( 'Discount amount',
																			'sales-countdown-timer' ) ?></label>
                                                                    <input type="number"
                                                                           min="0"
                                                                           value="<?php esc_attr_e( $checkout_countdown_custom_discount_value ) ?>"
                                                                           name="checkout_countdown_custom_discount_value[]"
                                                                           id="woo-stcr-checkout-countdown-decrease-custom-discount-value"
                                                                           class="woo-stcr-checkout-countdown-decrease-custom-discount-value">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
												<?php
											}
											?>
                                            <div class="woo-sctr-clear-both"></div>
                                        </div>
										<?php
										$checkout_countdown_custom_html = ob_get_clean();
										echo ent2ncr( $checkout_countdown_custom_html );
									}

									?>
                                </td>

                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="display_on_page">
                        <table class="form-table">
                            <tbody>
                            <tr>
								<?php
								$checkout_countdown_page_show_arg   = array(
									'checkout'  => __( 'Checkout page', 'sales-countdown-timer' ),
									'cart'      => __( 'Cart page', 'sales-countdown-timer' ),
									'shop'      => __( 'Shop page', 'sales-countdown-timer' ),
									'myaccount' => __( 'My account page', 'sales-countdown-timer' ),
									'home'      => __( 'Home page', 'sales-countdown-timer' ),
									'product'   => __( 'Single product page', 'sales-countdown-timer' ),
									'category'  => __( 'Category page', 'sales-countdown-timer' ),
									'assign'    => __( 'Other Page', 'sales-countdown-timer' ),
								);
								$checkout_countdown_display_on_page = $this->settings->get_params( 'checkout_countdown_display_on_page' );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-display-on-page"><?php esc_html_e( 'Display countdown timer on',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="field">
                                        <div class="field">
                                            <select name="checkout_countdown_display_on_page[]"
                                                    id="woo-stcr-checkout-countdown-display-on-page"
                                                    class="vi-ui dropdown fluid woo-stcr-checkout-countdown-display-on-page"
                                                    multiple>
												<?php
												foreach ( $checkout_countdown_page_show_arg as $page_key => $page_title ) {
													$selected = in_array( $page_key,
														$checkout_countdown_display_on_page ) ? 'selected ="selected"' : '';
													?>
                                                    <option value="<?php esc_attr_e( $page_key ) ?>" <?php echo $selected; ?> ><?php echo esc_html( $page_title ); ?></option>
													<?php
												}
												?>
                                            </select>
                                            <p class="description">
												<?php esc_html_e( 'Select pages to show checkout countdown. Leave blank to show checkout countdown on all pages.',
													'sales-countdown-timer' ) ?>
                                            </p>
                                        </div>
                                        <div class="field woo-stcr-checkout-countdown-assign-page <?php echo in_array( 'assign',
											$checkout_countdown_display_on_page ) ? '' : 'woo-sctr-hidden'; ?>">
                                            <input type="text" class="woo-stcr-checkout-countdown-assign-page-value"
                                                   placeholder="<?php esc_html_e( 'Ex: is_page(array(7,8,9))',
												       'sales-countdown-timer' ) ?>"
                                                   value="<?php esc_attr_e( $this->settings->get_params( 'checkout_countdown_display_on_assign_page' ) ) ?>">
                                            <p class="description"><?php esc_html_e( 'Combining 2 or more conditionals using || to show countdown timer if 1 of the conditionals matched.',
													'sales-countdown-timer' ) ?></p>
                                        </div>

                                    </div>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="vi-ui segment">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-title"><?php esc_html_e( 'Title of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>

                                        <input type="text"
                                               name="checkout_button_checkout_title"
                                               id="woo-stcr-checkout-button-title"
                                               class="woo-stcr-checkout-button-title"
                                               value="<?php esc_attr_e( $this->settings->get_params( 'checkout_button_checkout_title' ) ); ?>">
                                    </td>
                                </tr>
                                <tr>
									<?php
									$checkout_button_checkout_link_target = $this->settings->get_params( 'checkout_button_checkout_link_target' );
									?>
                                    <th>
                                        <label for="woo-stcr-checkout-button-link-target"><?php esc_html_e( 'Open in new tab when clicking Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui checkbox toggle">
                                            <input type="checkbox"
                                                   name="checkout_button_checkout_link_target"
                                                   id="woo-stcr-checkout-button-link-target"
                                                   value="<?php esc_attr_e( $checkout_button_checkout_link_target ) ?>"
												<?php checked( $checkout_button_checkout_link_target, '1' ) ?>>
                                            <label for="woo-stcr-checkout-button-link-target"><?php esc_html_e( 'Enable',
													'sales-countdown-timer' ) ?></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-fontsize"><?php esc_html_e( 'Fontsize of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui right labeled input">
                                            <input type="number"
                                                   name="checkout_button_checkout_fontsize"
                                                   min="0"
                                                   id="woo-stcr-checkout-button-fontsize"
                                                   class="woo-stcr-checkout-button-fontsize"
                                                   value="<?php esc_attr_e( $this->settings->get_params( 'checkout_button_checkout_fontsize' ) ); ?>">
                                            <div class="vi-ui basic label">
                                                px
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-color"><?php esc_html_e( 'Text color of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
										<?php
										$checkout_button_checkout_color = $this->settings->get_params( 'checkout_button_checkout_color' );
										?>
                                        <input type="text"
                                               class="color-picker woo-stcr-checkout-button-color"
                                               id="woo-stcr-checkout-button-color"
                                               name="checkout_button_checkout_color"
                                               value="<?php esc_attr_e( $checkout_button_checkout_color ) ?>"
                                               style="background:<?php esc_attr_e( $checkout_button_checkout_color ) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-background"><?php esc_html_e( 'Background color of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
										<?php
										$checkout_button_checkout_background = $this->settings->get_params( 'checkout_button_checkout_background' );
										?>
                                        <input type="text"
                                               class="color-picker woo-stcr-checkout-button-background"
                                               id="woo-stcr-checkout-button-background"
                                               name="checkout_button_checkout_background"
                                               value="<?php esc_attr_e( $checkout_button_checkout_background ) ?>"
                                               style="background:<?php esc_attr_e( $checkout_button_checkout_background ) ?>">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="vi-ui segment">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-title"><?php esc_html_e( 'Title of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>

                                        <input type="text"
                                               name="checkout_button_shop_title"
                                               id="woo-stcr-shop-button-title"
                                               class="woo-stcr-shop-button-title"
                                               value="<?php esc_attr_e( $this->settings->get_params( 'checkout_button_shop_title' ) ); ?>">
                                    </td>
                                </tr>
                                <tr>
									<?php
									$checkout_button_shop_link_target = $this->settings->get_params( 'checkout_button_shop_link_target' );
									?>
                                    <th>
                                        <label for="woo-stcr-shop-button-link-target"><?php esc_html_e( 'Open in new tab when clicking Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui checkbox toggle">
                                            <input type="checkbox"
                                                   name="checkout_button_shop_link_target"
                                                   id="woo-stcr-shop-button-link-target"
                                                   value="<?php esc_attr_e( $checkout_button_shop_link_target ) ?>"
												<?php checked( $checkout_button_shop_link_target, '1' ) ?>>
                                            <label for="woo-stcr-shop-button-link-target"><?php esc_html_e( 'Enable',
													'sales-countdown-timer' ) ?></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-fontsize"><?php esc_html_e( 'Fontsize of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui right labeled input">
                                            <input type="number"
                                                   name="checkout_button_shop_fontsize"
                                                   min="0"
                                                   id="woo-stcr-shop-button-fontsize"
                                                   class="woo-stcr-shop-button-fontsize"
                                                   value="<?php esc_attr_e( $this->settings->get_params( 'checkout_button_shop_fontsize' ) ); ?>">
                                            <div class="vi-ui basic label">
                                                px
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-color"><?php esc_html_e( 'Text color of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
										<?php
										$checkout_button_shop_color = $this->settings->get_params( 'checkout_button_shop_color' );
										?>
                                        <input type="text"
                                               class="color-picker woo-stcr-shop-button-color"
                                               id="woo-stcr-shop-button-color"
                                               name="checkout_button_shop_color"
                                               value="<?php esc_attr_e( $checkout_button_shop_color ) ?>"
                                               style="background:<?php esc_attr_e( $checkout_button_shop_color ) ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-background"><?php esc_html_e( 'Background color of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
										<?php
										$checkout_button_shop_background = $this->settings->get_params( 'checkout_button_shop_background' );
										?>
                                        <input type="text"
                                               class="color-picker woo-stcr-shop-button-background"
                                               id="woo-stcr-shop-button-background"
                                               name="checkout_button_shop_background"
                                               value="<?php esc_attr_e( $checkout_button_shop_background ) ?>"
                                               style="background:<?php esc_attr_e( $checkout_button_shop_background ) ?>">
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="design_on_cp">
                        <table class="form-table">
                            <tbody>
                            <tr>
								<?php
								$checkout_countdown_message_cp = $this->settings->get_params( 'checkout_countdown_message_checkout_page' );
								$cp_message_default            = explode( '{countdown_timer}',
									$checkout_countdown_message_cp );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-checkout-page"><?php esc_html_e( 'Checkout countdown message',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <textarea name="checkout_countdown_message_checkout_page" rows="3"
                                              id="woo-stcr-checkout-countdown-message-checkout-page"
                                              class="woo-sctr-message-checkout-countdown-timer"><?php echo $checkout_countdown_message_cp ?></textarea>
                                    <p class="description woo-sctr-warning-message-checkout-countdown-timer <?php echo count( $cp_message_default ) >= 2 ? 'woo-sctr-hidden' : ''; ?>">
										<?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}',
											'sales-countdown-timer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
								<?php
								$checkout_countdown_message_cp_missing = $this->settings->get_params( 'checkout_countdown_message_checkout_page_missing' );
								$cp_message_missing_default            = explode( '{countdown_timer}',
									$checkout_countdown_message_cp_missing );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-checkout-page-missing"><?php esc_html_e( 'Message if missing offer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <textarea name="checkout_countdown_message_checkout_page_missing" rows="3"
                                              id="woo-stcr-checkout-countdown-message-checkout-page-missing"
                                              class="woo-sctr-message-checkout-countdown-timer"><?php echo $checkout_countdown_message_cp_missing ?></textarea>
                                    <p class="description">
										<?php esc_html_e( 'This message only shows during checkout countdown when a cart is updated so that the current cart is smaller than the minimum required value.',
											'sales-countdown-timer' ) ?>
                                    </p>
                                    <p class="description woo-sctr-warning-message-checkout-countdown-timer <?php echo count( $cp_message_missing_default ) >= 2 ? 'woo-sctr-hidden' : ''; ?>">
										<?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}',
											'sales-countdown-timer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for=""><?php esc_html_e( 'Shortcode available',
											'sales-countdown-timer' ); ?></label>
                                </th>
                                <td>
                                    <p class="description">{countdown_timer}
                                        - <?php esc_html_e( 'The countdown timer that you set up on \'Sales Countdown Timer\' page',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{checkout_button}
                                        - <?php esc_html_e( 'The checkout button', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{shop_button}
                                        - <?php esc_html_e( 'The button go to store', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_percentage}
                                        - <?php esc_html_e( 'The discount in percentage. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_fixed}
                                        - <?php esc_html_e( 'The discount amount in currency. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{missing_amount}
                                        - <?php esc_html_e( 'The missing amount to get offer. Not apply to \'Checkout countdown message\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{minimum_cart_total}
                                        - <?php esc_html_e( 'The minimum cart total required to get offer',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{original_cart_total}
                                        - <?php esc_html_e( 'The current cart total without applying discount ',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            <tr>
								<?php
								$checkout_countdown_position_on_checkout_page     = $this->settings->get_params( 'checkout_countdown_position_on_checkout_page' );
								$checkout_countdown_position_on_checkout_page_arg = array(
									'before_checkout_form'   => __( 'Before checkout form', 'sales-countdown-timer' ),
									'before_customer_detail' => __( 'Before customer details',
										'sales-countdown-timer' ),
									'before_payment'         => __( 'Before payment method', 'sales-countdown-timer' ),
									'sticky_top'             => __( 'Sticky top', 'sales-countdown-timer' ),
									'sticky_bottom'          => __( 'Sticky bottom', 'sales-countdown-timer' ),
								);
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-position-on-checkout-page"><?php esc_html_e( 'Position',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <select name="checkout_countdown_position_on_checkout_page"
                                            id="woo-stcr-checkout-countdown-position-on-checkout-page"
                                            class="vi-ui fluid dropdown woo-stcr-checkout-countdown-position-on-checkout-page">
										<?php
										foreach ( $checkout_countdown_position_on_checkout_page_arg as $item_id => $item_title ) {
											?>
                                            <option value="<?php esc_attr_e( $item_id ) ?>" <?php selected( $checkout_countdown_position_on_checkout_page,
												$item_id ) ?> ><?php echo $item_title; ?></option>
											<?php
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-id-on-checkout-page"><?php esc_html_e( 'Select Countdown timer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
									<?php
									$sale_countdown_ids = $this->settings->get_params( 'sale_countdown_id' );
									if ( $sale_countdown_ids && is_array( $sale_countdown_ids ) && count( $sale_countdown_ids ) ) {
										$checkout_countdown_id_on_checkout_page = $this->settings->get_params( 'checkout_countdown_id_on_checkout_page' );
										?>
                                        <select name="checkout_countdown_id_on_checkout_page"
                                                id="woo-stcr-checkout-countdown-id-on-checkout-page"
                                                class="vi-ui fluid dropdown  woo-stcr-checkout-countdown-id-on-checkout-page">
											<?php
											foreach ( $sale_countdown_ids as $key => $value ) {
												$selected = '';
												if ( $checkout_countdown_id_on_checkout_page === $value ) {
													$selected = 'selected = "selected"';
												}
												?>
                                                <option value="<?php esc_attr_e( $value ) ?>" <?php echo $selected; ?> ><?php echo $this->settings->get_params( 'sale_countdown_name' )[ $key ] ?></option>
												<?php
											}
											?>
                                        </select>
										<?php
									}
									?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="design_on_op">
                        <table class="form-table">
                            <tbody>
                            <tr>
								<?php
								$checkout_countdown_message_op = $this->settings->get_params( 'checkout_countdown_message_other_page' );
								$op_message_default            = explode( '{countdown_timer}',
									$checkout_countdown_message_op );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-other-page"><?php esc_html_e( 'Checkout countdown message',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <textarea name="checkout_countdown_message_other_page" rows="3"
                                              id="woo-stcr-checkout-countdown-message-other-page"
                                              class="woo-sctr-message-checkout-countdown-timer"><?php echo $checkout_countdown_message_op ?></textarea>
                                    <p class="description woo-sctr-warning-message-checkout-countdown-timer <?php echo count( $op_message_default ) >= 2 ? 'woo-sctr-hidden' : ''; ?>">
										<?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}',
											'sales-countdown-timer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
								<?php
								$checkout_countdown_message_op_missing = $this->settings->get_params( 'checkout_countdown_message_other_page_missing' );
								$op_message_missing_default            = explode( '{countdown_timer}',
									$checkout_countdown_message_op_missing );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-other-page-missing"><?php esc_html_e( 'Message if missing offer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <textarea name="checkout_countdown_message_other_page_missing" rows="3"
                                              id="woo-stcr-checkout-countdown-message-other-page-missing"
                                              class="woo-sctr-message-checkout-countdown-timer"><?php echo $checkout_countdown_message_op_missing ?></textarea>
                                    <p class="description">
										<?php esc_html_e( 'This message only shows during checkout countdown when a cart is updated so that the current cart is smaller than the minimum required value.',
											'sales-countdown-timer' ) ?>
                                    </p>
                                    <p class="description woo-sctr-warning-message-checkout-countdown-timer <?php echo count( $op_message_missing_default ) >= 2 ? 'woo-sctr-hidden' : ''; ?>">
										<?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}',
											'sales-countdown-timer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for=""><?php esc_html_e( 'Shortcode available',
											'sales-countdown-timer' ); ?></label>
                                </th>
                                <td>
                                    <p class="description">{countdown_timer}
                                        - <?php esc_html_e( 'The countdown timer that you set up on \'Sales Countdown Timer\' page',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{checkout_button}
                                        - <?php esc_html_e( 'The checkout button', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{shop_button}
                                        - <?php esc_html_e( 'The button go to store', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_percentage}
                                        - <?php esc_html_e( 'The discount in percentage. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_fixed}
                                        - <?php esc_html_e( 'The discount amount in currency. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{missing_amount}
                                        - <?php esc_html_e( 'The missing amount to get offer. Not apply to \'Checkout countdown message\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{minimum_cart_total}
                                        - <?php esc_html_e( 'The minimum cart total required to get offer',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{original_cart_total}
                                        - <?php esc_html_e( 'The current cart total without applying discount ',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            <tr>
								<?php
								$checkout_countdown_position_on_archive_page     = $this->settings->get_params( 'checkout_countdown_position_on_archive_page' );
								$checkout_countdown_position_on_archive_page_arg = array(
									'sticky_top'    => __( 'Sticky top', 'sales-countdown-timer' ),
									'sticky_bottom' => __( 'Sticky bottom', 'sales-countdown-timer' ),
								);
								?>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-position-on-other-page"><?php esc_html_e( 'Position',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <select name="checkout_countdown_position_on_archive_page"
                                            id="woo-stcr-checkout-countdown-position-on-other-page"
                                            class="vi-ui fluid dropdown woo-stcr-checkout-countdown-position-on-other-page">
										<?php
										foreach ( $checkout_countdown_position_on_archive_page_arg as $item_id => $item_title ) {
											?>
                                            <option value="<?php esc_attr_e( $item_id ) ?>" <?php selected( $checkout_countdown_position_on_archive_page,
												$item_id ) ?> ><?php echo $item_title; ?></option>
											<?php
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-id-on-other-page"><?php esc_html_e( 'Select Countdown timer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
									<?php
									if ( $sale_countdown_ids && is_array( $sale_countdown_ids ) && count( $sale_countdown_ids ) ) {
										$checkout_countdown_id_on_checkout_page = $this->settings->get_params( 'checkout_countdown_id_on_other_page' );
										?>
                                        <select name="checkout_countdown_id_on_other_page"
                                                id="woo-stcr-checkout-countdown-id-on-other-page"
                                                class="vi-ui fluid dropdown  woo-stcr-checkout-countdown-id-on-other-page">
											<?php
											foreach ( $sale_countdown_ids as $key => $value ) {
												$selected = '';
												if ( $checkout_countdown_id_on_checkout_page === $value ) {
													$selected = 'selected = "selected"';
												}
												?>
                                                <option value="<?php esc_attr_e( $value ) ?>" <?php echo $selected; ?> ><?php echo $this->settings->get_params( 'sale_countdown_name' )[ $key ] ?></option>
												<?php
											}
											?>
                                        </select>
										<?php
									}
									?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="report">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-save-logs">
										<?php esc_html_e( 'Save Logs', 'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
									<?php
									$checkout_countdown_save_log = $this->settings->get_params( 'checkout_countdown_save_log' );
									?>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="checkbox"
                                               name="checkout_countdown_save_log"
                                               id="woo-stcr-checkout-save-logs"
                                               class="woo-stcr-checkout-save-logs"
                                               value="
					<?php esc_attr_e( $checkout_countdown_save_log ) ?>"

											<?php checked( $checkout_countdown_save_log, '1' ) ?> >
                                        <label for="woo-stcr-checkout-save-logs">
											<?php esc_html_e( 'Enable', 'sales-countdown-timer' ) ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-history-time">
										<?php esc_html_e( 'History time', 'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
									<?php
									$checkout_countdown_history_time = $this->settings->get_params( 'checkout_countdown_history_time' ) ?: 30;
									?>
                                    <div class="vi-ui right labeled input">
                                        <input type="number"
                                               min="0"
                                               name="checkout_countdown_history_time"
                                               id="woo-stcr-checkout-history-time"
                                               class="woo-stcr-checkout-history-time"
                                               value="<?php esc_attr_e( $checkout_countdown_history_time ) ?>">
                                        <div class="vi-ui basic label">
											<?php esc_html_e( 'Days', 'sales-countdown-timer' ) ?></div>
                                    </div>
                                    <p class="description">
										<?php echo esc_html__( 'Logs will be saved at ', 'sales-countdown-timer' ) . VI_SCT_SALES_COUNTDOWN_TIMER_CACHE . esc_html__( ' in time', 'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui segment">
                        <table class="form-table">
                            <tbody>
                            <tr>
								<?php
								$current_user_id           = get_current_user_id();
								$checkout_test_mode_enable = $this->settings->get_params( 'checkout_test_mode_enable' );
								?>
                                <th>
                                    <label for="woo-stcr-checkout-test-mode-enable"><?php esc_html_e( 'Test mode',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui checkbox toggle">
                                        <input type="checkbox"
                                               name="checkout_test_mode_enable"
                                               id="woo-stcr-checkout-test-mode-enable"
                                               class="woo-stcr-checkout-test-mode-enable"
                                               value="<?php esc_attr_e( $checkout_test_mode_enable ) ?>"
											<?php checked( $checkout_test_mode_enable, '1' ) ?>>
                                        <label for="woo-stcr-checkout-test-mode-enable"><?php esc_html_e( 'Enable',
												'sales-countdown-timer' ) ?></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'The checkout countdown will be applied only to Administrators for testing purpose',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for=""><?php esc_html_e( 'Reset checkout countdown',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <span class="vi-ui button red woo-stcr-checkout-test-mode-reset <?php echo $checkout_test_mode_enable === '1' ? '' : 'disabled' ?>"
                                          data-current_id="<?php esc_attr_e( $current_user_id ); ?>"><?php esc_html_e( 'Reset checkout countdown',
		                                    'sales-countdown-timer' ) ?></span>
                                    <p class="description"><?php esc_html_e( 'Reset current checkout discount applied to your account when Test mode is enabled',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>
                        <button type="button" class="vi-ui button primary woo-ctr-settings-checkout-page-btnsave"
                                name="woo_ctr_settings_checkout_page_btnsave">
							<?php esc_html_e( 'Save', 'sales-countdown-timer' ) ?>
                        </button>
                    </p>
                </form>
            </div>
        </div>
		<?php
	}

	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		if ( $page === 'sales-countdown-timer-checkout' ) {
			$admin = 'VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Settings';
			$admin::remove_other_script();
			$admin::enqueue_style(
				array( 'semantic-ui-button', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-icon', 'semantic-ui-input', 'semantic-ui-label' ),
				array( 'button.min.css', 'checkbox.min.css', 'dropdown.min.css', 'form.min.css', 'icon.min.css', 'input.min.css', 'label.min.css' )
			);
			$admin::enqueue_style(
				array( 'semantic-ui-accordion', 'semantic-ui-header', 'semantic-ui-menu', 'semantic-ui-message', 'semantic-ui-segment', 'semantic-ui-popup', 'semantic-ui-tab' ),
				array( 'accordion.min.css', 'header.min.css', 'menu.min.css', 'message.min.css','segment.min.css', 'popup.min.css', 'tab.css' )
			);
			$admin::enqueue_style(
				array( 'vi-sales-countdown-timer-admin-checkout', 'select2', 'transition' ),
				array( 'admin-checkout.css','select2.min.css', 'transition.min.css' )
			);
			/*Color picker*/
			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ),
				array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch', ),
				false, 1 );
			$admin::enqueue_script(
				array( 'semantic-ui-accordion', 'semantic-ui-address', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-tab' ),
				array( 'accordion.min.js', 'address.min.js', 'checkbox.min.js', 'dropdown.min.js', 'form.min.js', 'tab.js' )
			);
			$admin::enqueue_script(
				array( 'vi-sales-countdown-timer-admin-checkout', 'select2', 'transition' ),
				array( 'admin-checkout.js','select2.js ','transition.min.js' )
			);
		}

	}

}