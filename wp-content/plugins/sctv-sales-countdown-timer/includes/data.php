<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Data {
	private $params;
	private $default;

	/**
	 * VI_SCT_SALES_COUNTDOWN_TIMER_Data constructor.
	 * Init setting
	 */
	public function __construct() {
		$this->prefix = 'vi-sales-countdown-timer-';
		global $woo_ctr_settings;
		if ( ! $woo_ctr_settings ) {
			$woo_ctr_settings = get_option( 'sales_countdown_timer_params', array() );
		}
		if (isset($woo_ctr_settings['id']) && !isset($woo_ctr_settings['sale_countdown_id'])){
			$woo_ctr_settings_t = array();
			$woo_ctr_settings_t['sale_countdown_id']             = $woo_ctr_settings['id'];
			$woo_ctr_settings_t['sale_countdown_active']         = $woo_ctr_settings['active'];
			$woo_ctr_settings_t['sale_countdown_name']           = $woo_ctr_settings['names'];
			$woo_ctr_settings_t['sale_countdown_message']        = $woo_ctr_settings['message'];
			$woo_ctr_settings_t['sale_countdown_fom_date']       = $woo_ctr_settings['sale_from_date'];
			$woo_ctr_settings_t['sale_countdown_fom_time']       = $woo_ctr_settings['sale_from_time'];
			$woo_ctr_settings_t['sale_countdown_to_date']        = $woo_ctr_settings['sale_to_date'];
			$woo_ctr_settings_t['sale_countdown_to_time']        = $woo_ctr_settings['sale_to_time'];
			$woo_ctr_settings_t['sale_countdown_time_separator'] = $woo_ctr_settings['time_separator'];
			foreach ( $woo_ctr_settings['display_type'] as $item ) {
				if ( $item == 2 ) {
					$woo_ctr_settings_t['sale_countdown_display_type'][] = '1';
				} elseif ( $item == 1 ) {
					$woo_ctr_settings_t['sale_countdown_display_type'][] = '2';
				} else {
					$woo_ctr_settings_t['sale_countdown_display_type'][] = $item;
				}
			}
			$woo_ctr_settings_t['sale_countdown_datetime_format']                       = $woo_ctr_settings['count_style'];
			$woo_ctr_settings_t['sale_countdown_upcoming_enable']                       = $woo_ctr_settings['upcoming'];
			$woo_ctr_settings_t['sale_countdown_upcoming_message']                      = $woo_ctr_settings['upcoming_message'];
			$woo_ctr_settings_t['sale_countdown_single_product_position']               = $woo_ctr_settings['position'];
			$woo_ctr_settings_t['sale_countdown_archive_page_position']                 = $woo_ctr_settings['archive_page_position'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_message']                  = $woo_ctr_settings['progress_bar_message'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_type']                     = $woo_ctr_settings['progress_bar_type'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_order_status']             = $woo_ctr_settings['progress_bar_order_status'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_position']                 = $woo_ctr_settings['progress_bar_position'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_template_1_width']         = $woo_ctr_settings['progress_bar_width'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_template_1_height']        = $woo_ctr_settings['progress_bar_height'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_template_1_color']         = $woo_ctr_settings['progress_bar_color'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_template_1_background']    = $woo_ctr_settings['progress_bar_bg_color'];
			$woo_ctr_settings_t['sale_countdown_progress_bar_template_1_border_radius'] = $woo_ctr_settings['progress_bar_border_radius'];
			$woo_ctr_settings_t['sale_countdown_template_1_time_unit_background']       = $woo_ctr_settings['datetime_unit_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_2_time_unit_background']       = $woo_ctr_settings['datetime_unit_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_3_time_unit_background']       = $woo_ctr_settings['datetime_unit_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_4_time_unit_background']       = $woo_ctr_settings['datetime_unit_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_1_time_unit_color']            = $woo_ctr_settings['datetime_unit_color'];
			$woo_ctr_settings_t['sale_countdown_template_2_time_unit_color']            = $woo_ctr_settings['datetime_unit_color'];
			$woo_ctr_settings_t['sale_countdown_template_3_time_unit_color']            = $woo_ctr_settings['datetime_unit_color'];
			$woo_ctr_settings_t['sale_countdown_template_4_time_unit_color']            = $woo_ctr_settings['datetime_unit_color'];
			$woo_ctr_settings_t['sale_countdown_template_1_time_unit_fontsize']         = $woo_ctr_settings['datetime_unit_font_size'];
			$woo_ctr_settings_t['sale_countdown_template_2_time_unit_fontsize']         = $woo_ctr_settings['datetime_unit_font_size'];
			$woo_ctr_settings_t['sale_countdown_template_3_time_unit_fontsize']         = $woo_ctr_settings['datetime_unit_font_size'];
			$woo_ctr_settings_t['sale_countdown_template_4_time_unit_fontsize']         = $woo_ctr_settings['datetime_unit_font_size'];
			$woo_ctr_settings_t['sale_countdown_template_1_value_color']                = $woo_ctr_settings['datetime_value_color'];
			$woo_ctr_settings_t['sale_countdown_template_2_value_color']                = $woo_ctr_settings['datetime_value_color'];
			$woo_ctr_settings_t['sale_countdown_template_3_value_color']                = $woo_ctr_settings['datetime_value_color'];
			$woo_ctr_settings_t['sale_countdown_template_4_value_color']                = $woo_ctr_settings['datetime_value_color'];
			$woo_ctr_settings_t['sale_countdown_template_1_value_background']           = $woo_ctr_settings['datetime_value_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_2_value_background']           = $woo_ctr_settings['datetime_value_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_3_value_background']           = $woo_ctr_settings['datetime_value_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_4_value_background']           = $woo_ctr_settings['datetime_value_bg_color'];
			$woo_ctr_settings_t['sale_countdown_template_1_value_font_size']            = $woo_ctr_settings['datetime_value_font_size'];
			$woo_ctr_settings_t['sale_countdown_template_2_value_fontsize']             = $woo_ctr_settings['datetime_value_font_size'];
			$woo_ctr_settings_t['sale_countdown_template_3_value_fontsize']             = $woo_ctr_settings['datetime_value_font_size'];
			$woo_ctr_settings_t['sale_countdown_template_4_value_fontsize']             = $woo_ctr_settings['datetime_value_font_size'];
			$woo_ctr_settings_t['sale_countdown_layout_1_padding']                      = $woo_ctr_settings['countdown_timer_padding'];
			$woo_ctr_settings_t['sale_countdown_layout_1_border_radius']                = $woo_ctr_settings['countdown_timer_border_radius'];
			$woo_ctr_settings_t['sale_countdown_layout_1_color']                        = $woo_ctr_settings['countdown_timer_color'];
			$woo_ctr_settings_t['sale_countdown_layout_1_background']                   = $woo_ctr_settings['countdown_timer_bg_color'];
			$woo_ctr_settings_t['sale_countdown_layout_1_border_color']                 = $woo_ctr_settings['countdown_timer_border_color'];
			$woo_ctr_settings_t['sale_countdown_template_1_value_border_radius']        = $woo_ctr_settings['countdown_timer_item_border_radius'];
			$woo_ctr_settings_t['sale_countdown_template_2_item_border_radius']         = $woo_ctr_settings['countdown_timer_item_border_radius'];
			$woo_ctr_settings_t['sale_countdown_template_1_value_border_color']         = $woo_ctr_settings['countdown_timer_item_border_color'];
			$woo_ctr_settings_t['sale_countdown_template_2_item_border_color']          = $woo_ctr_settings['countdown_timer_item_border_color'];
			$woo_ctr_settings_t['sale_countdown_template_4_value_border_color2']        = $woo_ctr_settings['countdown_timer_item_border_color'];
			$woo_ctr_settings_t['sale_countdown_template_1_value_height']               = $woo_ctr_settings['countdown_timer_item_height'];
			$woo_ctr_settings_t['sale_countdown_template_2_item_height']                = $woo_ctr_settings['countdown_timer_item_height'];
			$woo_ctr_settings_t['sale_countdown_template_1_value_width']                = $woo_ctr_settings['countdown_timer_item_width'];
			$woo_ctr_settings_t['sale_countdown_template_2_item_width']                 = $woo_ctr_settings['countdown_timer_item_width'];
			$woo_ctr_settings_t['sale_countdown_template_1_time_unit_position']         = $woo_ctr_settings['datetime_unit_position'];
			$woo_ctr_settings_t['sale_countdown_template_2_time_unit_position']         = $woo_ctr_settings['datetime_unit_position'];
			$woo_ctr_settings_t['sale_countdown_template_4_time_unit_position']         = $woo_ctr_settings['datetime_unit_position'];
			$woo_ctr_settings_t['sale_countdown_loop_resize']                           = $woo_ctr_settings['size_on_archive_page'];
			$woo_ctr_settings_t['sale_countdown_circle_smooth_animation']               = $woo_ctr_settings['circle_smooth_animation'];
			$woo_ctr_settings_t['sale_countdown_animation_style']                       = $woo_ctr_settings['animation_style'];
			foreach ( $woo_ctr_settings['stick_to_top'] as $k => $value ) {
				$woo_ctr_settings_t['sale_countdown_single_product_sticky'] [] = isset( $woo_ctr_settings['stick_to_top'][ $k ] ) ? 'sticky_top' : 'none';
			}
			$shop_page     = $woo_ctr_settings['shop_page'];
			$category_page = $woo_ctr_settings['category_page'];
			for ( $i = 0; $i < count( $shop_page ); $i ++ ) {
				$archive_page                                              = isset( $shop_page[ $i ] ) ? ',shop' : '';
				$archive_page                                              .= isset( $category_page[ $i ] ) ? ',category' : '';
				$woo_ctr_settings_t['sale_countdown_archive_page_enable'][ $i ]          = $archive_page ? '1' : '';
				$woo_ctr_settings_t['sale_countdown_archive_page_show'][ $i ]            = trim( $archive_page, ',' );
				$woo_ctr_settings_t['sale_countdown_upcoming_progress_bar_enable'][ $i ] = '1';
			}
			set_transient( '_sales_countdown_timer_set_schedule', current_time( 'timestamp' ), 180 * DAY_IN_SECONDS );
			$woo_ctr_settings = $woo_ctr_settings_t;
			update_option( 'sales_countdown_timer_params', $woo_ctr_settings );
		}elseif(empty($woo_ctr_settings) && !get_transient('_sales_countdown_timer_demo_product_init')){
			set_transient( '_sales_countdown_timer_demo_product_init', current_time( 'timestamp' ), 180 * DAY_IN_SECONDS );
		}
		if (!empty($woo_ctr_settings['sale_countdown_archive_page_show']) && empty($woo_ctr_settings['sale_countdown_archive_page_assign'])){
			$sale_countdown_archive_page_assign=array();
			foreach ($woo_ctr_settings['sale_countdown_archive_page_show'] as $item ){
				$assign='';
				$page_show = $item ? explode( ',', $item ) : array();
				if (in_array('shop', $page_show)){
					$assign .='||is_shop()';
				}
				if (in_array('category', $page_show)){
					$assign .='||is_product_category()';
				}
				if (in_array('related_product', $page_show)){
					$assign .='|| is_product() ||is_single()';
				}
				$sale_countdown_archive_page_assign[]= trim($assign,'||');
			}
			unset($woo_ctr_settings['sale_countdown_archive_page_show']);
			$woo_ctr_settings['sale_countdown_archive_page_assign'] = $sale_countdown_archive_page_assign;
			update_option( 'sales_countdown_timer_params', $woo_ctr_settings );
		}
		$coundown =array(
			'sale_countdown_id'                            => array( 'salescountdowntimer' ),
			'sale_countdown_active'                        => array( '1' ),
			'sale_countdown_loop_enable'                   => array( '' ),
			'sale_countdown_loop_time_val'                 => array( '7' ),
			'sale_countdown_loop_time_type'                => array( 'day' ),
			'sale_countdown_name'                          => array( 'Countdown timer' ),
			'sale_countdown_fom_date'                      => array( date( "Y-m-d", current_time( 'timestamp' ) ) ),
			'sale_countdown_fom_time'                      => array( '' ),
			'sale_countdown_to_date'                       => array(
				date( "Y-m-d",
					current_time( 'timestamp' ) + 30 * 86400 ),
			),
			'sale_countdown_to_time'                       => array( '' ),
			'sale_countdown_message'                       => array( 'Hurry Up! Offer ends in {countdown_timer}' ),
			'sale_countdown_message_position'              => array( 'default' ),
			'sale_countdown_time_separator'                => array( 'blank' ),
			'sale_countdown_time_units'                    => array( '' ),
			'sale_countdown_datetime_format'               => array( '1' ),
			'sale_countdown_datetime_format_custom_date'   => array( '' ),
			'sale_countdown_datetime_format_custom_hour'   => array( '' ),
			'sale_countdown_datetime_format_custom_minute' => array( '' ),
			'sale_countdown_datetime_format_custom_second' => array( '' ),
			'sale_countdown_mobile_resize'                 => array( '80' ),
			'sale_countdown_loop_resize'                   => array( '75' ),
			'sale_countdown_sticky_resize'                 => array( '55' ),

			'sale_countdown_animation_style'          => array( 'default' ),
			'sale_countdown_circle_smooth_animation'  => array( '1' ),
			'sale_countdown_sticky_width'             => array( '0' ),
			'sale_countdown_sticky_time_unit_disable' => array( '0' ),
			'sale_countdown_add_to_cart_button'       => array( '0' ),

			'sale_countdown_layout'                       => array( '1' ),
			'sale_countdown_layout_fontsize'              => array( '16' ),
			'sale_countdown_layout_1_background'          => array( '#fff' ),
			'sale_countdown_layout_1_color'               => array( '#666666' ),
			'sale_countdown_layout_1_border_color'        => array( '' ),
			'sale_countdown_layout_1_border_radius'       => array( '0' ),
			'sale_countdown_layout_1_padding'             => array( '0' ),
			'sale_countdown_layout_1_sticky_background'   => array( '#fff' ),
			'sale_countdown_layout_1_sticky_color'        => array( '#212121' ),
			'sale_countdown_layout_1_sticky_border_color' => array( '' ),

			'sale_countdown_display_type'                    => array( '1' ),
			'sale_countdown_template_1_time_unit_position'   => array( 'bottom' ),
			'sale_countdown_template_1_time_unit_color'      => array( '' ),
			'sale_countdown_template_1_time_unit_background' => array( 'transparent' ),
			'sale_countdown_template_1_time_unit_fontsize'   => array( '14' ),

			'sale_countdown_template_1_value_color'         => array( '#212121' ),
			'sale_countdown_template_1_value_background'    => array( 'transparent' ),
			'sale_countdown_template_1_value_border_color'  => array( '#666666' ),
			'sale_countdown_template_1_value_border_radius' => array( '3' ),
			'sale_countdown_template_1_value_width'         => array( '50' ),
			'sale_countdown_template_1_value_height'        => array( '50' ),
			'sale_countdown_template_1_value_font_size'     => array( '30' ),

			'sale_countdown_template_2_item_border_color'    => array( '' ),
			'sale_countdown_template_2_item_border_radius'   => array( '3' ),
			'sale_countdown_template_2_item_height'          => array( '70' ),
			'sale_countdown_template_2_item_width'           => array( '70' ),
			'sale_countdown_template_2_value_padding'        => array( '0' ),
			'sale_countdown_template_2_value_color'          => array( '#212121' ),
			'sale_countdown_template_2_value_background'     => array( '#eeeeee' ),
			'sale_countdown_template_2_value_fontsize'       => array( '30' ),
			'sale_countdown_template_2_time_unit_color'      => array( '#212121' ),
			'sale_countdown_template_2_time_unit_background' => array( '#eeeeee' ),
			'sale_countdown_template_2_time_unit_fontsize'   => array( '14' ),
			'sale_countdown_template_2_time_unit_position'   => array( 'bottom' ),

			'sale_countdown_template_3_value_color'          => array( '#212121' ),
			'sale_countdown_template_3_value_background'     => array( '' ),
			'sale_countdown_template_3_value_fontsize'       => array( '30' ),
			'sale_countdown_template_3_time_unit_color'      => array( '#666666' ),
			'sale_countdown_template_3_time_unit_background' => array( '' ),
			'sale_countdown_template_3_time_unit_fontsize'   => array( '14' ),

			'sale_countdown_template_4_value_border_color1'  => array( '#ececec' ),
			'sale_countdown_template_4_value_border_color2'  => array( '#cccccc' ),
			'sale_countdown_template_4_value_color'          => array( '#212121' ),
			'sale_countdown_template_4_value_background'     => array( '#fff' ),
			'sale_countdown_template_4_value_fontsize'       => array( '30' ),
			'sale_countdown_template_4_value_border_width'   => array( '3' ),
			'sale_countdown_template_4_value_diameter'       => array( '70' ),
			'sale_countdown_template_4_time_unit_color'      => array( '#212121' ),
			'sale_countdown_template_4_time_unit_background' => array( '' ),
			'sale_countdown_template_4_time_unit_fontsize'   => array( '14' ),
			'sale_countdown_template_4_time_unit_position'   => array( 'bottom' ),

			'sale_countdown_template_5_item_border_width'    => array( '4' ),
			'sale_countdown_template_5_item_diameter'        => array( '90' ),
			'sale_countdown_template_5_value_color'          => array( '#212121' ),
			'sale_countdown_template_5_value_fontsize'       => array( '30' ),
			'sale_countdown_template_5_time_unit_color'      => array( '#212121' ),
			'sale_countdown_template_5_time_unit_fontsize'   => array( '14' ),
			'sale_countdown_template_5_date_border_color1'   => array( '#ececec' ),
			'sale_countdown_template_5_date_border_color2'   => array( '#cccccc' ),
			'sale_countdown_template_5_date_background'      => array( '' ),
			'sale_countdown_template_5_hour_border_color1'   => array( '#ececec' ),
			'sale_countdown_template_5_hour_border_color2'   => array( '#cccccc' ),
			'sale_countdown_template_5_hour_background'      => array( '' ),
			'sale_countdown_template_5_minute_border_color1' => array( '#ececec' ),
			'sale_countdown_template_5_minute_border_color2' => array( '#cccccc' ),
			'sale_countdown_template_5_minute_background'    => array( '' ),
			'sale_countdown_template_5_second_border_color1' => array( '#ececec' ),
			'sale_countdown_template_5_second_border_color2' => array( '#cccccc' ),
			'sale_countdown_template_5_second_background'    => array( '' ),

			'sale_countdown_template_6_time_unit_position'  => array( 'bottom' ),
			'sale_countdown_template_6_value_width'         => array( '48' ),
			'sale_countdown_template_6_value_height'        => array( '40' ),
			'sale_countdown_template_6_value_border_radius' => array( '10' ),
			'sale_countdown_template_6_value_fontsize'      => array( '30' ),
			'sale_countdown_template_6_value_color1'        => array( '#ccc' ),
			'sale_countdown_template_6_value_color2'        => array( '#fff' ),
			'sale_countdown_template_6_value_background1'   => array( '#1f1e1e' ),
			'sale_countdown_template_6_value_background2'   => array( '#383636' ),
			'sale_countdown_template_6_value_box_shadow'    => array( '1' ),
			'sale_countdown_template_6_value_cut_color'     => array( '#000' ),
			'sale_countdown_template_6_value_cut_behind'    => array( '' ),
			'sale_countdown_template_6_time_unit_color'     => array( '' ),
			'sale_countdown_template_6_time_unit_fontsize'  => array( '14' ),
			'sale_countdown_template_6_time_unit_grid_gap'  => array( '4' ),

			'sale_countdown_template_7_time_unit_position'  => array( 'bottom' ),
			'sale_countdown_template_7_value_width'         => array( '50' ),
			'sale_countdown_template_7_value_height'        => array( '75' ),
			'sale_countdown_template_7_value_border_radius' => array( '8' ),
			'sale_countdown_template_7_value_fontsize'      => array( '50' ),
			'sale_countdown_template_7_value_color1'        => array( '#ccc' ),
			'sale_countdown_template_7_value_color2'        => array( '#fff' ),
			'sale_countdown_template_7_value_background1'   => array( '#1f1e1e' ),
			'sale_countdown_template_7_value_background2'   => array( '#383636' ),
			'sale_countdown_template_7_value_box_shadow'    => array( '1' ),
			'sale_countdown_template_7_value_cut_color'     => array( '#000' ),
			'sale_countdown_template_7_value_cut_behind'    => array( '' ),
			'sale_countdown_template_7_time_unit_color'     => array( '' ),
			'sale_countdown_template_7_time_unit_fontsize'  => array( '16' ),
			'sale_countdown_template_7_time_unit_grid_gap'  => array( '5' ),

			'sale_countdown_single_product_sticky'                 => array( 'sticky_top' ),
			'sale_countdown_single_product_position'               => array( 'after_price' ),
			'sale_countdown_archive_page_enable'                   => array( '' ),
			'sale_countdown_archive_page_assign'                   => array( '' ),
			'sale_countdown_archive_page_position'                 => array( 'after_price' ),
			'sale_countdown_progress_bar_message'                  => array( '{quantity_sold}/{goal} sold' ),
			'sale_countdown_progress_bar_type'                     => array( 'increase' ),
			'sale_countdown_progress_bar_order_status'             => array( '' ),
			'sale_countdown_progress_bar_position'                 => array( 'below_countdown' ),
			'sale_countdown_progress_bar_template'                 => array( '1' ),
			'sale_countdown_progress_bar_message_position'         => array( 'below_progressbar' ),
			'sale_countdown_progress_bar_template_1_background'    => array( '#eeeeee' ),
			'sale_countdown_progress_bar_template_1_color'         => array( '#ffb600' ),
			'sale_countdown_progress_bar_template_1_message_color' => array( '' ),
			'sale_countdown_progress_bar_template_1_border_radius' => array( '20' ),
			'sale_countdown_progress_bar_template_1_height'        => array( '12' ),
			'sale_countdown_progress_bar_template_1_width'         => array( '' ),
			'sale_countdown_progress_bar_template_1_width_type'    => array( 'px' ),
			'sale_countdown_progress_bar_template_1_font_size'     => array( '13' ),
			'sale_countdown_upcoming_enable'                       => array( '1' ),
			'sale_countdown_upcoming_progress_bar_enable'          => array( '' ),
			'sale_countdown_upcoming_message'                      => array( 'Sale starts in {countdown_timer}' ),

			'sale_countdown_wrap_border_radius_in_single'            => array( '0' ),
			'sale_countdown_wrap_border_color_in_single'             => array( '' ),
			'sale_countdown_progress_bar_position_in_single'         => array( 'below_countdown' ),
			'sale_countdown_progress_bar_message_position_in_single' => array( 'below_progressbar' ),
		);
		$checkout = array(
			'checkout_countdown_enable'       => '',
			'checkout_countdown_reset'        => 7,
			'checkout_countdown_save_log'     => '',
			'checkout_countdown_history_time' => 30,
			'checkout_countdown_before_active_enable'          => '',
			'checkout_countdown_cart_total_start'              => '100',
			'checkout_countdown_start'                         => 'add_to_cart',
			'checkout_countdown_time_minute'                   => 5,
			'checkout_countdown_time_second'                   => 0,
			'checkout_countdown_message_checkout_page'         => 'Place your order within {countdown_timer} to save {discount_fixed}. Order total will rise to {original_cart_total} after time out',
			'checkout_countdown_message_other_page'            => 'Congratulation! Place your order within {countdown_timer} to get {discount_percentage} OFF cart. Hurry Up! {checkout_button}',
			'checkout_countdown_message_checkout_page_missing' => 'Minimum purchase to get discount is {minimum_cart_total} and you will miss this chance after {countdown_timer}. Continue {shop_button}',
			'checkout_countdown_message_other_page_missing'    => 'Add more products to cart to reach {minimum_cart_total} and checkout within {countdown_timer} to get discount',
			'checkout_countdown_free_ship'                     => '',
			'checkout_countdown_discount_type'                 => 'percent',
			'checkout_countdown_discount_amount'               => '5',
			'checkout_countdown_free_product_enable'           => '',
			'checkout_countdown_free_products'                 => array(),
			'checkout_countdown_free_product_quantity'         => '1',
			'checkout_countdown_change'                        => 'auto_change',
			'checkout_countdown_custom_ids'                    => array( 'checkoutcountdownt1' ),
			'checkout_countdown_custom_minutes'                => array( 5 ),
			'checkout_countdown_custom_seconds'                => array( 0 ),
			'checkout_countdown_custom_messages_checkout_page' => array( 'Place your order within {countdown_timer} to save {discount_fixed}. Order total will rise to {original_cart_total} after time out' ),
			'checkout_countdown_custom_messages_other_page'    => array( 'Congratulation! Place your order within {countdown_timer} to get {discount_percentage} OFF cart. Hurry Up! {checkout_button}' ),
			'checkout_countdown_custom_free_ships'             => array( '' ),
			'checkout_countdown_custom_free_products'          => array( '' ),
			'checkout_countdown_custom_discount_value'         => array( 5 ),

			'checkout_countdown_auto_change_time_type'         => 'minute',
			'checkout_countdown_auto_change_time'              => '1',
			'checkout_countdown_auto_change_details_value'     => '10',

			'checkout_countdown_id_on_checkout_page'       => 'salescountdowntimer',
			'checkout_countdown_id_on_other_page'          => 'salescountdowntimer',
			'checkout_countdown_display_on_page'           => array( 'checkout', 'cart', 'shop', 'home', 'product' ),
			'checkout_countdown_display_on_assign_page'    => '',
			'checkout_countdown_position_on_checkout_page' => 'before_submit',
			'checkout_countdown_position_on_archive_page'  => 'sticky_bottom',
			'checkout_button_checkout_fontsize'            => '16',
			'checkout_button_checkout_color'               => '#fff',
			'checkout_button_checkout_background'          => '#d26e4b',
			'checkout_button_checkout_title'               => 'Checkout',
			'checkout_button_checkout_link_target'         => '',
			'checkout_button_shop_fontsize'                => '16',
			'checkout_button_shop_color'                   => '#fff',
			'checkout_button_shop_background'              => '#000',
			'checkout_button_shop_title'                   => 'Shopping',
			'checkout_button_shop_link_target'             => '',

			'checkout_test_mode_enable' => '',
		);
		$this->default     = array_merge(
			array(
				'update_key'                                   => '',
				'custom_css'                => '',
			),
			$coundown, $checkout
		);

		$this->params = apply_filters( 'woo_ctr_settings_args', wp_parse_args( $woo_ctr_settings, $this->default ) );
	}

	public function get_params( $name = "" , $language = '' ) {
		if ( ! $name ) {
			return $this->params;
		}
		$name_t = $language ? $name . $language :$name;

		return apply_filters( 'sctv_countdown_settings-' . $name_t, $this->params[ $name_t ] ??  false );
	}

	public function get_default( $name = "" ) {
		if ( ! $name ) {
			return $this->default;
		} elseif ( isset( $this->default[ $name ] ) ) {
			return apply_filters( 'sctv_countdown_settings_default-' . $name, $this->default[ $name ] );
		} else {
			return false;
		}
	}

	public function get_current_countdown( $name = "", $i = 0,  $default = false ) {
		if ( empty( $name ) ) {
			return false;
		}
		if ( $default !== false ) {
			$result = $this->get_params( $name )[ $i ] ?? $default;
		} else {
			$result = $this->get_params( $name )[ $i ] ?? $this->get_default( $name )[0] ?? false;
		}
		return $result;
	}

	public function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );

		} else {
			return esc_attr__( $this->prefix . $name );

		}
	}
	public static function extend_post_allowed_html() {
		return array_merge( wp_kses_allowed_html( 'post' ), array(
				'input' => array(
					'type'         => 1,
					'id'           => 1,
					'name'         => 1,
					'class'        => 1,
					'placeholder'  => 1,
					'autocomplete' => 1,
					'style'        => 1,
					'value'        => 1,
					'data-*'       => 1,
					'size'         => 1,
				),
				'form'  => array(
					'type'   => 1,
					'id'     => 1,
					'name'   => 1,
					'class'  => 1,
					'style'  => 1,
					'method' => 1,
					'action' => 1,
					'data-*' => 1,
				),
				'style' => array(
					'id'    => 1,
					'class' => 1,
					'type'  => 1,
				),
			)
		);
	}

}

new VI_SCT_SALES_COUNTDOWN_TIMER_Data();