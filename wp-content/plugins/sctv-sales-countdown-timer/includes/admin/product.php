<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Product {
	protected $settings, $product_types;

	function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
			add_action( 'woocommerce_product_write_panel_tabs', array( $this, 'woocommerce_product_write_panel_tabs' ) );
			add_action( 'woocommerce_product_options_pricing', array( $this, 'woocommerce_product_options_pricing' ) );
			add_action( 'woocommerce_variation_options', array( $this, 'woocommerce_variation_options' ) );
			add_action( 'woocommerce_variation_options_pricing', array( $this, 'woocommerce_variation_options_pricing' ), 10, 3 );
			add_action( 'woocommerce_product_bulk_edit_end', array( $this, 'woocommerce_product_bulk_edit_end' ) );

			//save data
			$this->product_types = apply_filters( 'wmc_simple_product_type_register', array(
				'simple',
				'external',
				'bundle',
				'course',
				'subscription',
				'woosb'
			) );
			foreach ( $this->product_types as $type ) {
				add_action( 'woocommerce_process_product_meta_' . $type, array( $this, 'woocommerce_process_product_meta_simple' ) );
			}
			add_action( 'woocommerce_save_product_variation', array( $this, 'woocommerce_save_product_variation' ), 10, 2 );
			add_action( 'woocommerce_product_bulk_edit_save', array( $this, 'woocommerce_product_bulk_edit_save'), 10, 1 );
		}
	}

	public function woocommerce_product_bulk_edit_end() {
		$id = $this->settings->get_params( 'sale_countdown_id' );
		?>
        <div class="sctv_bulk_edit clear" style="background: #dbf5e1;padding: 5px 0;">
            <h2><?php esc_html_e( 'Sale Countdown Timer', 'sales-countdown-timer' ) ?></h2>
            <p class="description"><?php esc_html_e( 'Note*: These settings will only save if product is on sale',
					'sales-countdown-timer' ) ?></p>
            <div class="inline-edit-group">
                <label class="alignleft">
                    <span class="title"><?php esc_html_e( 'Setting Countdown', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                        <select class="_woo_ctr_select_change_setting" name="_woo_ctr_select_change_setting">
                            <option value=""><?php esc_html_e( '— No change —', 'sales-countdown-timer' ); ?></option>
                                <option value="1"><?php esc_html_e( 'Only applied to Simple and External/Affiliate product', 'sales-countdown-timer' ); ?></option>
                                <option value="2"><?php esc_html_e( 'Applied to Simple, External/Affiliate and Variable product', 'sales-countdown-timer' ); ?></option>
                        </select>
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting sctv_bulk_edit_date_field" style="display: none;">
                <label class="alignleft" style="width: auto; max-width: 100%;">
                    <span class="title"><?php esc_html_e( 'Sale price dates and times ',
		                    'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap"
                          style="display: grid; grid-auto-columns: auto ; grid-template-columns: auto auto;grid-auto-rows: auto;">
                        <input type="text" class="short" name="_sale_price_dates_from" id="_sale_price_dates_from"
                               value=""
                               placeholder="<?php esc_html_e( 'From&hellip;', 'sales-countdown-timer' ) ?> YYYY-MM-DD"
                               maxlength="10"
                               pattern="<?php echo esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) ?>"/>
                        <input type="time" name="_sale_price_times_from" id="sale_price_times_from"
                               value="<?php woo_ctr_time_revert( 0 ) ?>">
                        <input type="text" class="short" name="_sale_price_dates_to" id="_sale_price_dates_to" value=""
                               placeholder="<?php esc_html_e( 'To&hellip;', 'sales-countdown-timer' ) ?>  YYYY-MM-DD"
                               maxlength="10"
                               pattern="<?php echo esc_attr( apply_filters( 'woocommerce_date_input_html_pattern', '[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) ?>"/>
                        <input type="time" name="_sale_price_times_to" id="sale_price_times_to"
                               value="<?php woo_ctr_time_revert( 0 ) ?>">
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting sctv_bulk_edit_loop_enable_field"
                 style="display: none;">
                <label class="alignleft">
                    <span class="title"><?php esc_html_e( 'Countdown evergreen', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                       <input type="checkbox" class="checkbox" style="" name="_woo_ctr_enable_loop_countdown"
                              id="_woo_ctr_enable_loop_countdown" value="yes">
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting sctv_bulk_edit_loop_value_field"
                 style="display: none; ">
                <label class="alignleft" style="width: 100%">
                    <span class="title"><?php esc_html_e( 'Restart countdown after', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                        <input type="number" class="short" min="0" name="_woo_ctr_loop_countdown_val"
                               id="_woo_ctr_loop_countdown_val" value="" placeholder="7" style="max-width: 100px;"/>
                        <select name="_woo_ctr_loop_countdown_type" class="_woo_ctr_loop_countdown_type"
                                style="margin-top: -5px;">
                            <option value="day"><?php esc_html_e( 'Days', 'sales-countdown-timer' ); ?></option>
                            <option value="hour"><?php esc_html_e( 'Hours', 'sales-countdown-timer' ); ?></option>
                            <option value="min"><?php esc_html_e( 'Minutes', 'sales-countdown-timer' ); ?></option>
                        </select>
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting" style="display: none;">
                <label class="alignleft">
                    <span class="title"><?php esc_html_e( 'Countdown timer profile', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                        <select class="_woo_ctr_select_countdown_timer" name="_woo_ctr_select_countdown_timer">
                            <?php
                            foreach ( $id as $k => $v ) {
	                            ?>
                                <option value="<?php esc_attr_e( $v ) ?>"><?php echo $this->settings->get_params( 'sale_countdown_name' )[ $k ]; ?></option>
	                            <?php
                            }
                            ?>
                        </select>
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting" style="display: none;">
                <label class="alignleft">
                    <span class="title"><?php esc_html_e( 'Enable progress bar', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                       <input type="checkbox" class="checkbox" style="" name="_woo_ctr_enable_progress_bar" id="_woo_ctr_enable_progress_bar" value="yes">
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting" style="display: none;">
                <label class="alignleft">
                    <span class="title"><?php esc_html_e( 'When achieving the goal', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                        <select class="_woo_ctr_action_for_over_goal" name="_woo_ctr_action_for_over_goal">
					        <option value="none"><?php esc_html_e( 'None', 'sales-countdown-timer' ) ?></option>
					        <option value="remove_sale_price"><?php esc_html_e( 'Back to regular price', 'sales-countdown-timer' ) ?></option>
					        <option value="set_private"><?php esc_html_e( 'Private product', 'sales-countdown-timer' ) ?></option>
					        <option value="set_out_of_stock"><?php esc_html_e( 'Out of stock', 'sales-countdown-timer' ) ?></option>
                        </select>
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting" style="display: none;">
                <label class="alignleft">
                    <span class="title"><?php esc_html_e( 'Goal', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                        <input type="number" name="_woo_ctr_progress_bar_goal" class="_woo_ctr_progress_bar_goal"
                               min="0" placeholder="" value="">
                    </span>
                </label>
            </div>
            <div class="inline-edit-group sctv_bulk_edit_setting" style="display: none;">
                <label class="alignleft">
                    <span class="title"><?php esc_html_e( 'Initial quantity', 'sales-countdown-timer' ) ?></span>
                    <span class="input-text-wrap">
                        <input type="number" name="_woo_ctr_progress_bar_initial" class="_woo_ctr_progress_bar_initial" min="0" placeholder="" value="">
                    </span>
                </label>
            </div>
            <div class="clear"></div>
        </div>
		<?php
	}

	public function woocommerce_product_save_countdown( $product_id, $sale_price, $regular_price, $gmt_offset, $date_on_sale_from, $date_on_sale_to, $sale_price_times_from, $sale_price_times_to) {
	    if (!$product_id){
	        return;
        }
		delete_transient( 'sales_countdown_timer_params_product_' . $product_id );
		$schedule = $schedule_start= get_option('sctv_schedule_sales',array());
		$schedule_remove_sale=$schedule_sale=array();
		if (count($schedule)){
			foreach ($schedule as $key => $schedule_data){
				$schedule_time = $schedule_data['time'] ?? 0;
				$schedule_hook = $schedule_data['hook'] ?? '';
				$schedule_arg = $schedule_data['arg'] ?? array();
				if ($schedule_time < (current_time('timestamp') - $gmt_offset * 3600) || empty($schedule_arg)){
					unset($schedule[$key]);
					continue;
				}
				if ($schedule_hook==='sctv_schedule_sale_product' ){
					if (in_array($product_id,$schedule_arg[0])) {
						$schedule_sale_old_arg = $schedule_arg[0];
						wp_unschedule_event( $schedule_time, 'sctv_schedule_sale_product', $schedule_arg  );
						unset( $schedule_sale_old_arg[ array_search( $product_id, $schedule_sale_old_arg ) ] );
						if ( ! empty( $schedule_sale_old_arg ) ) {
							$schedule[ $key ]['arg'] = array(array_values( $schedule_sale_old_arg ));
							wp_schedule_single_event( $schedule_time, 'sctv_schedule_sale_product',   $schedule[ $key ]['arg']);
						}else{
							unset($schedule[$key]);
							continue;
						}
					}
				}
				if ( $schedule_hook==='sctv_schedule_remove_sale_product'){
					if (in_array($product_id,$schedule_arg[0])) {
						$schedule_remove_sale_old_arg = $schedule_arg[0];
						wp_unschedule_event( $schedule_time, 'sctv_schedule_remove_sale_product',  $schedule_arg  );
						unset( $schedule_remove_sale_old_arg[ array_search( $product_id, $schedule_remove_sale_old_arg ) ] );
						if ( ! empty( $schedule_remove_sale_old_arg ) ) {
							$schedule[ $key ]['arg'] = array(array_values( $schedule_remove_sale_old_arg ));
							wp_schedule_single_event( $schedule_time, 'sctv_schedule_remove_sale_product',$schedule[ $key ]['arg']  );
						}else{
							unset($schedule[$key]);
							continue;
						}
					}
				}
			}
		}
		if (!$date_on_sale_from || !$date_on_sale_to || $date_on_sale_from == $date_on_sale_to){
		    $this->delete_countdown_timer($product_id);
			return;
		}
		if (!$regular_price ){
			$this->delete_countdown_timer($product_id);
			return;
        }
		$now = current_time( 'timestamp' );
		$date_on_sale_from1 = ( $date_on_sale_from - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_from - $gmt_offset * 3600 ) : '';
		$date_on_sale_to1   = ( $date_on_sale_to - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_to - $gmt_offset * 3600 ) : '';
		if ( $now < $date_on_sale_from  && $sale_price) {
			update_post_meta( $product_id, '_price', $regular_price );
			wp_schedule_single_event( $date_on_sale_from1, 'sctv_schedule_sale_product',  array(array($product_id))  );
		}
		wp_schedule_single_event( $date_on_sale_to1, 'sctv_schedule_remove_sale_product',array(array($product_id)) );

		update_post_meta( $product_id, '_sale_price_dates_from', $date_on_sale_from1 );
		update_post_meta( $product_id, '_sale_price_dates_to', $date_on_sale_to1 );
		update_post_meta( $product_id, '_sale_price_times_from', $sale_price_times_from);
		update_post_meta( $product_id, '_sale_price_times_to', $sale_price_times_to );

		update_post_meta( $product_id, '_woo_ctr_select_countdown_timer', isset( $_REQUEST['_woo_ctr_select_countdown_timer'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_select_countdown_timer'] ) : '' );
		update_post_meta( $product_id, '_woo_ctr_enable_progress_bar', isset( $_REQUEST['_woo_ctr_enable_progress_bar'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_enable_progress_bar'] ) : '' );
		update_post_meta( $product_id, '_woo_ctr_action_for_over_goal', isset( $_REQUEST['_woo_ctr_action_for_over_goal'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_action_for_over_goal'] ) : '' );
		update_post_meta( $product_id, '_woo_ctr_progress_bar_goal', isset( $_REQUEST['_woo_ctr_progress_bar_goal'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_progress_bar_goal'] ) : '' );
		update_post_meta( $product_id, '_woo_ctr_progress_bar_initial', isset( $_REQUEST['_woo_ctr_progress_bar_initial'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_progress_bar_initial'] ) : '' );
		update_post_meta( $product_id, '_woo_ctr_enable_loop_countdown', isset( $_REQUEST['_woo_ctr_enable_loop_countdown'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_enable_loop_countdown'] ) : '' );
		update_post_meta( $product_id, '_woo_ctr_loop_countdown_val', isset( $_REQUEST['_woo_ctr_loop_countdown_val'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_loop_countdown_val'] ) : '' );
		update_post_meta( $product_id, '_woo_ctr_loop_countdown_type', isset( $_REQUEST['_woo_ctr_loop_countdown_type'] ) ? sanitize_text_field( $_REQUEST['_woo_ctr_loop_countdown_type'] ) : '' );
	}

	public function woocommerce_product_bulk_edit_save( $product ) {
	    $type = isset($_REQUEST['_woo_ctr_select_change_setting']) ? wc_clean($_REQUEST['_woo_ctr_select_change_setting']) :'';
	    if (!$type){
	        return;
        }
		$date_on_sale_from     = isset( $_REQUEST['_sale_price_dates_from'] ) ? strtotime(wc_clean( $_REQUEST['_sale_price_dates_from'] )) : 0;
		$date_on_sale_to       = isset( $_REQUEST['_sale_price_dates_to'] ) ? strtotime(wc_clean( $_REQUEST['_sale_price_dates_to'] )) : 0;
		$sale_price_times_from = isset( $_REQUEST['_sale_price_times_from']) ? sanitize_text_field( $_REQUEST['_sale_price_times_from'] ) : '00:00';
		$sale_price_times_to   = isset( $_REQUEST['_sale_price_times_to'] ) ? sanitize_text_field( $_REQUEST['_sale_price_times_to'] ) : '00:00';
		$time_from             = woo_ctr_time( $sale_price_times_from );
		$time_to               = woo_ctr_time( $sale_price_times_to );
		if ( $date_on_sale_from ) {
			$date_on_sale_from += $time_from;
		} elseif ( $date_on_sale_to ) {
			$date_on_sale_from = strtotime( date( "Y-m-d" ) );
		}
		if ( $date_on_sale_to ) {
			$date_on_sale_to += $time_to;
		}
		$gmt_offset            = get_option( 'gmt_offset' );
		if ($product->is_type( $this->product_types )){
			$product_id    = method_exists( $product, 'get_id' ) ? $product->get_id() : $product->id;
			$sale_price    = method_exists( $product, 'get_sale_price' ) ? $product->get_sale_price('edit') : $product->sale_price;
			$regular_price = method_exists( $product, 'get_regular_price' ) ? $product->get_regular_price('edit') : $product->regular_price;
			$this->woocommerce_product_save_countdown( $product_id, $sale_price, $regular_price, $gmt_offset, $date_on_sale_from, $date_on_sale_to, $sale_price_times_from, $sale_price_times_to );
			delete_option( 'sctv_schedule_sales' );
        }elseif ($product->is_type( 'variable' ) && $type=== '2' ){
			$variation_ids = method_exists( $product, 'get_children' ) ? $product->get_children() : $product->children;
			if ( $variation_ids && is_array( $variation_ids ) && $count = count( $variation_ids ) ) {
				foreach ( $variation_ids as $k => $variation_id ) {
					$sale_price    = get_post_meta( $variation_id, '_sale_price', true );
					$regular_price= get_post_meta( $variation_id, '_regular_price', true );
					$this->woocommerce_product_save_countdown( $variation_id, $sale_price, $regular_price, $gmt_offset, $date_on_sale_from, $date_on_sale_to, $sale_price_times_from, $sale_price_times_to );
				}
			}
			delete_option( 'sctv_schedule_sales' );
        }
	}

	public function admin_enqueue_scripts() {
		global $pagenow, $post_type;
		if ( $pagenow == 'edit.php' && $post_type == 'product' ) {
			wp_enqueue_script( 'sales-countdown-timer-admin-list-product',
				VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'admin-list-product.js',
				array( 'jquery' ),
				VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
		}
		$screen = get_current_screen();
		if ( $screen->id == 'product' ) {
			wp_enqueue_script( 'sales-countdown-timer-admin-product',
				VI_SCT_SALES_COUNTDOWN_TIMER_JS . 'admin-product.js',
				array( 'jquery' ),VI_SCT_SALES_COUNTDOWN_TIMER_VERSION );
			wp_enqueue_style( 'sales-countdown-timer-admin-product',
				VI_SCT_SALES_COUNTDOWN_TIMER_CSS . 'admin-product.css' );
		}
	}

	public function woocommerce_product_write_panel_tabs() {
		ob_start();
	}

	public function woocommerce_variation_options() {
		ob_start();
	}

	public function woocommerce_product_options_pricing() {
		global $post;
		$html = ob_get_clean();
		preg_match_all( '/<p class=\"form-field sale_price_dates_fields\"(.+?)<\/p>/si', $html, $datefields );
		$html = str_replace( $datefields[0], '', $html );
		echo $html;
		$product_object        = wc_get_product( $post->ID );
		$sale_from             = $product_object->get_date_on_sale_from( 'edit' ) ? $product_object->get_date_on_sale_from( 'edit' )->getOffsetTimestamp() : 0;
		$sale_to               = $product_object->get_date_on_sale_to( 'edit' ) ? $product_object->get_date_on_sale_to( 'edit' )->getOffsetTimestamp() : 0;
		$sale_price_dates_from = $sale_from ? date_i18n( 'Y-m-d', $sale_from ) : '';
		$sale_price_dates_to   = $sale_to ? date_i18n( 'Y-m-d', $sale_to ) : '';
		$sale_price_time_from  = $sale_from % 86400;
		$sale_price_time_to    = $sale_to % 86400;

		echo '<p class="form-field sale_price_dates_field">
				<label for="_sale_price_dates_from">' . esc_html__( 'Sale price dates and times',
				'sales-countdown-timer' ) . '</label>
				<input type="text" class="short" name="_sale_price_dates_from" id="_sale_price_dates_from" value="' . esc_attr( $sale_price_dates_from ) . '" placeholder="' . esc_html( _x( 'From&hellip;',
				'placeholder',
				'sales-countdown-timer' ) ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern',
				'[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
				<input type="time" name="_sale_price_times_from" id="sale_price_times_from" value="' . woo_ctr_time_revert( $sale_price_time_from ) . '">
				<input type="text" class="short" name="_sale_price_dates_to" id="_sale_price_dates_to" value="' . esc_attr( $sale_price_dates_to ) . '" placeholder="' . esc_html( _x( 'To&hellip;',
				'placeholder',
				'sales-countdown-timer' ) ) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern',
				'[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
				<input type="time" name="_sale_price_times_to" id="sale_price_times_to" value="' . woo_ctr_time_revert( $sale_price_time_to ) . '">
				<a href="#" class="description cancel_sale_schedule">' . esc_html__( 'Cancel',
				'sales-countdown-timer' ) . '</a>' . wc_help_tip( __( 'Dates and times value are set in your website timezone.',
				'sales-countdown-timer' ) ) . '
			</p>';
		echo '<div class="woo-sctr-countdown-timer-admin-product">';
		woocommerce_wp_checkbox(
			array(
				'id'    => '_woo_ctr_enable_loop_countdown',
				'label' => __( 'Countdown evergreen', 'sales-countdown-timer' ),
				'value' => wc_bool_to_string( get_post_meta( $post->ID, '_woo_ctr_enable_loop_countdown', true ) ),
			)
		);
		$loop_countdown_type = get_post_meta( $post->ID, '_woo_ctr_loop_countdown_type', true );
		$loop_countdown_val  = get_post_meta( $post->ID, '_woo_ctr_loop_countdown_val', true ) ?? 7;
		echo '<p class="form-field sale_loop_countdown_field">
				<label for="_woo_ctr_loop_countdown_val">' . esc_html__( 'Restart countdown after',
				'sales-countdown-timer' ) . '</label>
				<input type="number" class="short" min="0" name="_woo_ctr_loop_countdown_val" id="_woo_ctr_loop_countdown_val" value="' . esc_attr( $loop_countdown_val ) . '"  />
				<select name="_woo_ctr_loop_countdown_type" class="_woo_ctr_loop_countdown_type">
				    <option value="day" ' . ( $loop_countdown_type === 'day' ? 'selected' : '' ) . '>' . esc_html__( 'Days', 'sales-countdown-timer' ) . '</option>
				    <option value="hour" ' . ( $loop_countdown_type === 'hour' ? 'selected' : '' ) . '>' . esc_html__( 'Hours', 'sales-countdown-timer' ) . '</option>
				    <option value="min" ' . ( $loop_countdown_type === 'min' ? 'selected' : '' ) . '>' . esc_html__( 'Minutes', 'sales-countdown-timer' ) . '</option>
                </select>
			</p>';
		$id      = $this->settings->get_params( 'sale_countdown_id' );
		$options = array();
		foreach ( $id as $k => $v ) {
			$options[ $v ] = $this->settings->get_params( 'sale_countdown_name' )[ $k ];
		}
		woocommerce_wp_select(
			array(
				'id'          => '_woo_ctr_select_countdown_timer',
				'value'       => get_post_meta( $post->ID, '_woo_ctr_select_countdown_timer', true ),
				'label'       => __( 'Countdown timer profile', 'sales-countdown-timer' ),
				'options'     => $options,
				'desc_tip'    => 'true',
				'description' => __( 'Select countdown timer settings.', 'sales-countdown-timer' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'    => '_woo_ctr_enable_progress_bar',
				'label' => __( 'Enable progress bar', 'sales-countdown-timer' ),
				'value' => wc_bool_to_string( get_post_meta( $post->ID, '_woo_ctr_enable_progress_bar', true ) ),
			)
		);

		$option_for_goal = array(
			'none'              => __( 'None', 'sales-countdown-timer' ),
			'remove_sale_price' => __( 'Back to regular price', 'sales-countdown-timer' ),
			'set_private'       => __( 'Private product', 'sales-countdown-timer' ),
			'set_out_of_stock'  => __( 'Out of stock', 'sales-countdown-timer' ),
		);

		woocommerce_wp_select(
			array(
				'id'          => '_woo_ctr_action_for_over_goal',
				'value'       => get_post_meta( $post->ID, '_woo_ctr_action_for_over_goal', true ),
				'label'       => __( 'When achieving the goal', 'sales-countdown-timer' ),
				'options'     => $option_for_goal,
				'desc_tip'    => 'true',
				'description' => __( 'Select an action when achieving the goal before sale ends',
					'sales-countdown-timer' ),
			)
		);
		echo '<p class="form-field"><label for="_woo_ctr_progress_bar_goal">' . esc_html__( 'Goal',
				'sales-countdown-timer' ) . '</label><input type="number" value="' . ( get_post_meta( $post->ID,
				'_woo_ctr_progress_bar_goal',
				true ) ? ( get_post_meta( $post->ID,
				'_woo_ctr_progress_bar_goal',
				true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_goal" id="_woo_ctr_progress_bar_goal">' . wc_help_tip( esc_html__( 'Your product goal',
				'sales-countdown-timer' ) ) . '</p>';
		echo '<p class="form-field"><label for="_woo_ctr_progress_bar_initial">' . esc_html__( 'Initial quantity',
				'sales-countdown-timer' ) . '</label><input type="number" value="' . ( get_post_meta( $post->ID,
				'_woo_ctr_progress_bar_initial',
				true ) ? ( get_post_meta( $post->ID,
				'_woo_ctr_progress_bar_initial',
				true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_initial" id="_woo_ctr_progress_bar_initial">' . wc_help_tip( esc_html__( 'This is the virtual quantity of sold products',
				'sales-countdown-timer' ) ) . '</p>';

		echo '</div>';
	}

	public function woocommerce_variation_options_pricing( $loop, $variation_data, $variation ) {
		$html = ob_get_clean();
		preg_match_all( '/<div class=\"form-field sale_price_dates_fields hidden\"(.+?)<\/div>/si',
			$html,
			$datefields );
		$html = str_replace( $datefields[0], '', $html );
		echo $html;
		$variation_object      = wc_get_product( $variation->ID );
		$sale_from             = $variation_object->get_date_on_sale_from( 'edit' ) ? $variation_object->get_date_on_sale_from( 'edit' )->getOffsetTimestamp() : 0;
		$sale_to               = $variation_object->get_date_on_sale_to( 'edit' ) ? $variation_object->get_date_on_sale_to( 'edit' )->getOffsetTimestamp() : 0;
		$sale_price_dates_from = $sale_from ? date_i18n( 'Y-m-d', $sale_from ) : '';
		$sale_price_dates_to   = $sale_to ? date_i18n( 'Y-m-d', $sale_to ) : '';
		$sale_price_time_from  = $sale_from % 86400;
		$sale_price_time_to    = $sale_to % 86400;

		echo '<div class="form-field sale_price_dates_field hidden">
					<p class="form-row form-row-first">
						<label>' . __( 'Sale start date', 'sales-countdown-timer' ) . '</label>
						<input type="text" class="sale_price_dates_from" name="variable_sale_price_dates_from[' . $loop . ']" value="' . esc_attr( $sale_price_dates_from ) . '" placeholder="' . _x( 'From&hellip;',
				'placeholder',
				'sales-countdown-timer' ) . ' YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern',
				'[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
						<input type="time" name="variable_sale_price_times_from[' . $loop . ']" class="variable_sale_price_times_from" value="' . woo_ctr_time_revert( $sale_price_time_from ) . '">
					</p>
					<p class="form-row form-row-last">
						<label>' . __( 'Sale end date', 'sales-countdown-timer' ) . '</label>
						<input type="text" class="sale_price_dates_to" name="variable_sale_price_dates_to[' . esc_attr( $loop ) . ']" value="' . esc_attr( $sale_price_dates_to ) . '" placeholder="' . esc_html_x( 'To&hellip;',
				'placeholder',
				'sales-countdown-timer' ) . '  YYYY-MM-DD" maxlength="10" pattern="' . esc_attr( apply_filters( 'woocommerce_date_input_html_pattern',
				'[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])' ) ) . '" />
						<input type="time" name="variable_sale_price_times_to[' . $loop . ']" class="variable_sale_price_times_to" value="' . woo_ctr_time_revert( $sale_price_time_to ) . '">
					</p>
				</div>';
		echo '<div class="woo-sctr-countdown-timer-admin-product">';
		woocommerce_wp_checkbox(
			array(
				'id'          => '_woo_ctr_enable_loop_countdown' . $loop,
				'class'       => '_woo_ctr_enable_loop_countdown',
				'desc_tip'    => 'true',
				'description' => __( 'Loop when countdown finish', 'sales-countdown-timer' ),
				'name'        => '_woo_ctr_enable_loop_countdown[' . $loop . ']',
				'label'       => __( 'Countdown evergreen', 'sales-countdown-timer' ),
				'value'       => wc_bool_to_string( get_post_meta( $variation->ID, '_woo_ctr_enable_loop_countdown', true ) ),
			)
		);
		$loop_countdown_type = get_post_meta( $variation->ID, '_woo_ctr_loop_countdown_type', true );
		$loop_countdown_val  = get_post_meta( $variation->ID, '_woo_ctr_loop_countdown_val', true ) ?? 7;
		echo '<p class="form-field sale_loop_countdown_field variation_sale_loop_countdown_field">
				<label for="_woo_ctr_loop_countdown_val' . $loop . '">' . esc_html__( 'Restart countdown after',
				'sales-countdown-timer' ) . '</label>
				<input type="number" class="short" min="0" name="_woo_ctr_loop_countdown_val[' . $loop . ']" id="_woo_ctr_loop_countdown_val' . $loop . '" value="' . esc_attr( $loop_countdown_val ) . '"  />
				<select name="_woo_ctr_loop_countdown_type[' . $loop . ']" class="_woo_ctr_loop_countdown_type">
				    <option value="day" ' . ( $loop_countdown_type === 'day' ? 'selected' : '' ) . '>' . esc_html__( 'Days', 'sales-countdown-timer' ) . '</option>
				    <option value="hour" ' . ( $loop_countdown_type === 'hour' ? 'selected' : '' ) . '>' . esc_html__( 'Hours', 'sales-countdown-timer' ) . '</option>
				    <option value="min" ' . ( $loop_countdown_type === 'min' ? 'selected' : '' ) . '>' . esc_html__( 'Minutes', 'sales-countdown-timer' ) . '</option>
                </select>
			</p>';
		$id      = $this->settings->get_params( 'sale_countdown_id' );
		$options = array();
		foreach ( $id as $k => $v ) {
			$options[ $v ] = $this->settings->get_params( 'sale_countdown_name' )[ $k ];
		}
		woocommerce_wp_select(
			array(
				'id'          => '_woo_ctr_select_countdown_timer' . $loop,
				'name'        => '_woo_ctr_select_countdown_timer[' . $loop . ']',
				'value'       => get_post_meta( $variation->ID, '_woo_ctr_select_countdown_timer', true ),
				'label'       => __( 'Countdown timer profile', 'sales-countdown-timer' ),
				'options'     => $options,
				'desc_tip'    => 'true',
				'description' => __( 'Select countdown timer settings.', 'sales-countdown-timer' ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'          => '_woo_ctr_display_enable' . $loop,
				'class'       => '_woo_ctr_display_enable',
				'desc_tip'    => 'true',
				'description' => __( 'Display countdown timer of this variation when customers have not selected any variation',
					'sales-countdown-timer' ),
				'name'        => '_woo_ctr_display_enable[' . $loop . ']',
				'label'       => __( 'Default', 'sales-countdown-timer' ),
				'value'       => wc_bool_to_string( $variation->ID == get_post_meta( $variation->post_parent,
						'_woo_ctr_display_enable',
						true ) ),
			)
		);
		woocommerce_wp_checkbox(
			array(
				'id'    => '_woo_ctr_enable_progress_bar' . $loop,
				'class' => '_woo_ctr_enable_progress_bar',
				'name'  => '_woo_ctr_enable_progress_bar[' . $loop . ']',
				'label' => __( 'Enable progress bar', 'sales-countdown-timer' ),
				'value' => wc_bool_to_string( get_post_meta( $variation->ID, '_woo_ctr_enable_progress_bar', true ) ),
			)
		);

		$option_for_goal = array(
			'none'              => __( 'None', 'sales-countdown-timer' ),
			'remove_sale_price' => __( 'Back to regular price', 'sales-countdown-timer' ),
			'set_private'       => __( 'Private variation', 'sales-countdown-timer' ),
			'set_out_of_stock'  => __( 'Out of stock', 'sales-countdown-timer' ),
		);

		woocommerce_wp_select(
			array(
				'id'          => '_woo_ctr_action_for_over_goal' . $loop,
				'name'        => '_woo_ctr_action_for_over_goal[' . $loop . ']',
				'value'       => get_post_meta( $variation->ID, '_woo_ctr_action_for_over_goal', true ),
				'label'       => __( 'If achieve the goal', 'sales-countdown-timer' ),
				'options'     => $option_for_goal,
				'desc_tip'    => 'true',
				'description' => __( 'Select an action when achieving the goal before sale ends',
					'sales-countdown-timer' ),
			)
		);
		echo '<p class="form-field form-row form-row-first"><label for="_woo_ctr_progress_bar_goal' . $loop . '">' . esc_html__( 'Goal',
				'sales-countdown-timer' ) . '</label>' . wc_help_tip( esc_html__( 'Your product goal',
				'sales-countdown-timer' ) ) . '<input type="number" value="' . ( get_post_meta( $variation->ID,
				'_woo_ctr_progress_bar_goal',
				true ) ? ( get_post_meta( $variation->ID,
				'_woo_ctr_progress_bar_goal',
				true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_goal[' . $loop . ']" id="_woo_ctr_progress_bar_goal' . $loop . '" class="_woo_ctr_progress_bar_goal"></p>';
		echo '<p class="form-field form-row form-row-last"><label for="_woo_ctr_progress_bar_initial' . $loop . '">' . esc_html__( 'Initial quantity',
				'sales-countdown-timer' ) . '</label>' . wc_help_tip( esc_html__( 'This is the virtual quantity of sold products',
				'sales-countdown-timer' ) ) . '<input type="number" value="' . ( get_post_meta( $variation->ID,
				'_woo_ctr_progress_bar_initial',
				true ) ? ( get_post_meta( $variation->ID,
				'_woo_ctr_progress_bar_initial',
				true ) ) : '' ) . '" min="0" name="_woo_ctr_progress_bar_initial[' . $loop . ']" id="_woo_ctr_progress_bar_initial' . $loop . '" class="_woo_ctr_progress_bar_initial"></p>';
		echo '</div>';
	}

	public function woocommerce_process_product_meta_simple( $post_id ) {
		$gmt_offset            = get_option( 'gmt_offset' );
		$sale_price         = isset( $_POST['_sale_price'] ) ? wc_clean( wp_unslash( $_POST['_sale_price'] ) ) : null;
		$regular_price      = isset( $_POST['_regular_price'] ) ? wc_clean( wp_unslash( $_POST['_regular_price'] ) ) : null;
		delete_transient( 'sales_countdown_timer_params_product_' . $post_id );
		$schedule = $schedule_start = get_option('sctv_schedule_sales',array());
		if (count($schedule)){
			foreach ($schedule as $key => $schedule_data){
				$schedule_time = $schedule_data['time'] ?? 0;
				$schedule_hook = $schedule_data['hook'] ?? '';
				$schedule_arg = $schedule_data['arg'] ?? array();
				if ($schedule_time < (current_time('timestamp') - $gmt_offset * 3600) || empty($schedule_arg)){
					unset($schedule[$key]);
					continue;
				}
				if ($schedule_hook==='sctv_schedule_sale_product' ){
					if (in_array($post_id,$schedule_arg[0])) {
						$schedule_sale_old_arg = $schedule_arg[0];
						wp_unschedule_event( $schedule_time, 'sctv_schedule_sale_product', $schedule_arg  );
						unset( $schedule_sale_old_arg[ array_search( $post_id, $schedule_sale_old_arg ) ] );
						if ( ! empty( $schedule_sale_old_arg ) ) {
							$schedule[ $key ]['arg'] = array(array_values( $schedule_sale_old_arg ));
							wp_schedule_single_event( $schedule_time, 'sctv_schedule_sale_product',   $schedule[ $key ]['arg']);
						}else{
							unset($schedule[$key]);
							continue;
						}
					}
				}
				if ( $schedule_hook==='sctv_schedule_remove_sale_product'){
					if (in_array($post_id,$schedule_arg[0])) {
						$schedule_remove_sale_old_arg = $schedule_arg[0];
						wp_unschedule_event( $schedule_time, 'sctv_schedule_remove_sale_product',  $schedule_arg  );
						unset( $schedule_remove_sale_old_arg[ array_search( $post_id, $schedule_remove_sale_old_arg ) ] );
						if ( ! empty( $schedule_remove_sale_old_arg ) ) {
							$schedule[ $key ]['arg'] = array(array_values( $schedule_remove_sale_old_arg ));
							wp_schedule_single_event( $schedule_time, 'sctv_schedule_remove_sale_product',$schedule[ $key ]['arg']  );
						}else{
							unset($schedule[$key]);
							continue;
						}
					}
				}
			}
		}
		if (!$regular_price){
			$this->delete_countdown_timer($post_id);
			if($schedule != $schedule_start ){
				delete_option( 'sctv_schedule_sales' );
			}
		    return;
        }
		$date_on_sale_from     = isset( $_POST['_sale_price_dates_from'] ) ? strtotime( wc_clean($_POST['_sale_price_dates_from']) ) : 0;
		$date_on_sale_to       = isset( $_POST['_sale_price_dates_to'] ) ? strtotime( wc_clean($_POST['_sale_price_dates_to']) ) : 0;
		$sale_price_times_from = isset( $_POST['_sale_price_times_from'] ) ? sanitize_text_field( $_POST['_sale_price_times_from'] ) : '00:00' ;
		$sale_price_times_to   = isset( $_POST['_sale_price_times_to'] ) ?sanitize_text_field( $_POST['_sale_price_times_to'] ) : '00:00';
		$time_from             = woo_ctr_time( $sale_price_times_from );
		$time_to               = woo_ctr_time( $sale_price_times_to );
		if ( $date_on_sale_from ) {
			$date_on_sale_from += $time_from;
		} elseif ( $date_on_sale_to ) {
			$date_on_sale_from = strtotime( date( "Y-m-d" ) );
			$sale_price_times_from = '00:00' ;
		}
		if ( $date_on_sale_to ) {
			$date_on_sale_to += $time_to;
		}
		if (!$date_on_sale_from || !$date_on_sale_to ||  $date_on_sale_from == $date_on_sale_to){
			$this->delete_countdown_timer($post_id);
			if($schedule != $schedule_start ){
				delete_option( 'sctv_schedule_sales' );
			}
			return;
		}
		$now = current_time( 'timestamp' );
		$date_on_sale_from1 = ( $date_on_sale_from - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_from - $gmt_offset * 3600 ) : 0;
		$date_on_sale_to1   = ( $date_on_sale_to - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_to - $gmt_offset * 3600 ) : 0;
		if ( $now < $date_on_sale_from && $sale_price) {
			update_post_meta( $post_id, '_price', wc_format_decimal( $regular_price ) );
			wp_schedule_single_event( $date_on_sale_from1, 'sctv_schedule_sale_product',  array(array($post_id))  );
			$schedule[]= array(
				'time'=> $date_on_sale_from1,
				'hook'=> 'sctv_schedule_sale_product',
				'arg'=> array(array($post_id)),
			);
		}
		$schedule[]= array(
			'time'=> $date_on_sale_to1,
			'hook'=> 'sctv_schedule_remove_sale_product',
			'arg'=>array(array($post_id)),
		);
		wp_schedule_single_event( $date_on_sale_to1, 'sctv_schedule_remove_sale_product',array(array($post_id)) );
		update_post_meta( $post_id, '_sale_price_dates_from', $date_on_sale_from1 );
		update_post_meta( $post_id, '_sale_price_dates_to', $date_on_sale_to1 );
		update_post_meta( $post_id, '_sale_price_times_from', $sale_price_times_from );
		update_post_meta( $post_id, '_sale_price_times_to', $sale_price_times_to );
		if($schedule != $schedule_start ){
			delete_option( 'sctv_schedule_sales' );
		}
		update_post_meta( $post_id, '_woo_ctr_enable_loop_countdown', isset( $_POST['_woo_ctr_enable_loop_countdown'] ) ? sanitize_text_field( $_POST['_woo_ctr_enable_loop_countdown'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_loop_countdown_val', isset( $_POST['_woo_ctr_loop_countdown_val'] ) ? sanitize_text_field( $_POST['_woo_ctr_loop_countdown_val'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_loop_countdown_type', isset( $_POST['_woo_ctr_loop_countdown_type'] ) ? sanitize_text_field( $_POST['_woo_ctr_loop_countdown_type'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_select_countdown_timer', isset( $_POST['_woo_ctr_select_countdown_timer'] ) ? sanitize_text_field( $_POST['_woo_ctr_select_countdown_timer'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_enable_progress_bar', isset( $_POST['_woo_ctr_enable_progress_bar'] ) ? sanitize_text_field( $_POST['_woo_ctr_enable_progress_bar'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_action_for_over_goal', isset( $_POST['_woo_ctr_action_for_over_goal'] ) ? sanitize_text_field( $_POST['_woo_ctr_action_for_over_goal'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_progress_bar_goal', isset( $_POST['_woo_ctr_progress_bar_goal'] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_goal'] ) : '' );
		update_post_meta( $post_id, '_woo_ctr_progress_bar_initial', isset( $_POST['_woo_ctr_progress_bar_initial'] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_initial'] ) : '' );
	}

	public function woocommerce_save_product_variation( $variation_id, $i ) {
		$sale_price         = isset( $_POST['variable_sale_price'][ $i ] ) ? wc_clean( wp_unslash( $_POST['variable_sale_price'][ $i ] ) ) : null;
		$regular_price      = isset( $_POST['variable_regular_price'][ $i ] ) ? wc_clean( wp_unslash( $_POST['variable_regular_price'][ $i ] ) ) : null;
		delete_transient( 'sales_countdown_timer_params_product_' . $variation_id );
		$gmt_offset            = get_option( 'gmt_offset' );
		$schedule = $schedule_start = get_option('sctv_schedule_sales',array());
		if (count($schedule)){
			foreach ($schedule as $key => $schedule_data){
				$schedule_time = $schedule_data['time'] ?? 0;
				$schedule_hook = $schedule_data['hook'] ?? '';
				$schedule_arg = $schedule_data['arg'] ?? array();
				if ($schedule_time < (current_time('timestamp') - $gmt_offset * 3600) || empty($schedule_arg)){
					unset($schedule[$key]);
					continue;
				}
				if ($schedule_hook==='sctv_schedule_sale_product' ){
					if (in_array($variation_id,$schedule_arg[0])) {
						$schedule_sale_old_arg = $schedule_arg[0];
						wp_unschedule_event( $schedule_time, 'sctv_schedule_sale_product', $schedule_arg  );
						unset( $schedule_sale_old_arg[ array_search( $variation_id, $schedule_sale_old_arg ) ] );
						if ( ! empty( $schedule_sale_old_arg ) ) {
							$schedule[ $key ]['arg'] = array(array_values( $schedule_sale_old_arg ));
							wp_schedule_single_event( $schedule_time, 'sctv_schedule_sale_product',   $schedule[ $key ]['arg']);
						}else{
							unset($schedule[$key]);
							continue;
						}
					}
				}
				if ( $schedule_hook==='sctv_schedule_remove_sale_product'){
					if (in_array($variation_id,$schedule_arg[0])) {
						$schedule_remove_sale_old_arg = $schedule_arg[0];
						wp_unschedule_event( $schedule_time, 'sctv_schedule_remove_sale_product',  $schedule_arg  );
						unset( $schedule_remove_sale_old_arg[ array_search( $variation_id, $schedule_remove_sale_old_arg ) ] );
						if ( ! empty( $schedule_remove_sale_old_arg ) ) {
							$schedule[ $key ]['arg'] = array(array_values( $schedule_remove_sale_old_arg ));
							wp_schedule_single_event( $schedule_time, 'sctv_schedule_remove_sale_product',$schedule[ $key ]['arg']  );
						}else{
							unset($schedule[$key]);
							continue;
						}
					}
				}
			}
		}
		if ( !$regular_price){
			$this->delete_countdown_timer($variation_id);
			if( ($i == count( $_POST['variable_regular_price']) -1 )){
				delete_option( 'sctv_schedule_sales' );
			}
			return;
		}
		$date_on_sale_from     = isset( $_POST['variable_sale_price_dates_from'][ $i ] ) ? strtotime( wc_clean($_POST['variable_sale_price_dates_from'][ $i ]) ) : 0;
		$date_on_sale_to       = isset( $_POST['variable_sale_price_dates_to'][ $i ] ) ? strtotime( wc_clean($_POST['variable_sale_price_dates_to'][ $i ]) ) : 0;
		$sale_price_times_from = isset( $_POST['variable_sale_price_times_from'][ $i ] ) ? sanitize_text_field( $_POST['variable_sale_price_times_from'][ $i ] ) : '00:00';
		$sale_price_times_to   = isset( $_POST['variable_sale_price_times_to'][ $i ] ) ? sanitize_text_field( $_POST['variable_sale_price_times_to'][ $i ] ) : '00:00';
		$time_from             = woo_ctr_time( $sale_price_times_from );
		$time_to               = woo_ctr_time( $sale_price_times_to );
		if ( $date_on_sale_from ) {
			$date_on_sale_from += $time_from;
		} else {
			$date_on_sale_from     = strtotime( date( "Y-m-d" ) );
			$sale_price_times_from = '00:00';
		}
		if ( $date_on_sale_to ) {
			$date_on_sale_to += $time_to;
		}
		if (!$date_on_sale_from || !$date_on_sale_to || $date_on_sale_from == $date_on_sale_to){
		    $this->delete_countdown_timer($variation_id);
			if( ($i == count( $_POST['variable_regular_price']) - 1 )){
				delete_option( 'sctv_schedule_sales' );
			}
			return;
		}
		$now = current_time( 'timestamp' );
		$date_on_sale_from1 = ( $date_on_sale_from - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_from - $gmt_offset * 3600 ) : 0;
		$date_on_sale_to1   = ( $date_on_sale_to - $gmt_offset * 3600 ) > 0 ? ( $date_on_sale_to - $gmt_offset * 3600 ) : 0;

		if ( $now < $date_on_sale_from && $sale_price ) {
			update_post_meta( $variation_id, '_price', wc_format_decimal( $regular_price ) );
			wp_schedule_single_event( $date_on_sale_from1, 'sctv_schedule_sale_product',  array(array($variation_id))  );
			$schedule[]= array(
				'time'=> $date_on_sale_from1,
				'hook'=> 'sctv_schedule_sale_product',
				'arg'=> array(array($variation_id)),
			);
		}
		$schedule[]= array(
			'time'=> $date_on_sale_to1,
			'hook'=> 'sctv_schedule_remove_sale_product',
			'arg'=>array(array($variation_id)),
		);
		wp_schedule_single_event( $date_on_sale_to1, 'sctv_schedule_remove_sale_product',array(array($variation_id)) );
		update_post_meta( $variation_id, '_sale_price_dates_from', $date_on_sale_from1 );
		update_post_meta( $variation_id, '_sale_price_dates_to', $date_on_sale_to1 );
		update_post_meta( $variation_id, '_sale_price_times_from', $sale_price_times_from );
		update_post_meta( $variation_id, '_sale_price_times_to', $sale_price_times_to );
		if( ($i == count( $_POST['variable_regular_price']) -1 )  ){
			delete_option( 'sctv_schedule_sales' );
		}
		if ( isset( $_POST['_woo_ctr_display_enable'][ $i ] ) ) {
			update_post_meta( wp_get_post_parent_id( $variation_id ), '_woo_ctr_display_enable', isset( $_POST['variable_enabled'][ $i ] ) ? $variation_id : '' );
		}
		update_post_meta( $variation_id, '_woo_ctr_enable_loop_countdown', isset( $_POST['_woo_ctr_enable_loop_countdown'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_enable_loop_countdown'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_loop_countdown_val', isset( $_POST['_woo_ctr_loop_countdown_val'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_loop_countdown_val'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_loop_countdown_type', isset( $_POST['_woo_ctr_loop_countdown_type'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_loop_countdown_type'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_select_countdown_timer', isset( $_POST['_woo_ctr_select_countdown_timer'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_select_countdown_timer'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_enable_progress_bar', isset( $_POST['_woo_ctr_enable_progress_bar'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_enable_progress_bar'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_action_for_over_goal', isset( $_POST['_woo_ctr_action_for_over_goal'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_action_for_over_goal'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_progress_bar_goal', isset( $_POST['_woo_ctr_progress_bar_goal'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_goal'][ $i ] ) : '' );
		update_post_meta( $variation_id, '_woo_ctr_progress_bar_initial', isset( $_POST['_woo_ctr_progress_bar_initial'][ $i ] ) ? sanitize_text_field( $_POST['_woo_ctr_progress_bar_initial'][ $i ] ) : '' );
	}
	public function delete_countdown_timer($product_id){
	    if (!$product_id){
	        return;
        }
		delete_post_meta( $product_id, '_sale_price_dates_from' );
		delete_post_meta( $product_id, '_sale_price_dates_to' );
		delete_post_meta( $product_id, '_sale_price_times_from' );
		delete_post_meta( $product_id, '_sale_price_times_to' );
    }
}
