<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Countdown_Style {
	public static $settings;

	public function __construct() {
		self::$settings = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
	}

	public static function get_frontend_countdown_css() {
		self::$settings = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$shortcode_ids  = self::$settings->get_params( 'sale_countdown_id' );
		$css            = '';
		$css .= self::$settings->get_params( 'custom_css' );
		if ( $shortcode_ids && is_array( $shortcode_ids ) && $count_ids = count( $shortcode_ids ) ) {
			for ( $i = 0; $i < $count_ids; $i ++ ) {
				if ( ! self::$settings->get_current_countdown( 'sale_countdown_active', $i ) ) {
					continue;
				}
				$id = $shortcode_ids[ $i ];

				$animation_style          = self::$settings->get_current_countdown( 'sale_countdown_animation_style',
					$i );
				$sticky_width             = self::$settings->get_current_countdown( 'sale_countdown_sticky_width', $i );
				$sticky_time_unit_disable = self::$settings->get_current_countdown( 'sale_countdown_sticky_time_unit_disable',
					$i );
				$resize_loop              = self::$settings->get_current_countdown( 'sale_countdown_loop_resize', $i );
				$resize_mobile            = self::$settings->get_current_countdown( 'sale_countdown_mobile_resize',
					$i );
				$resize_sticky            = self::$settings->get_current_countdown( 'sale_countdown_sticky_resize',
					$i );
				$circle_smooth_animation  = self::$settings->get_current_countdown( 'sale_countdown_circle_smooth_animation',
					$i );
				$time_separator           = self::$settings->get_current_countdown( 'sale_countdown_time_separator',
					$i );

				$layout_style                 = self::$settings->get_current_countdown( 'sale_countdown_layout', $i );
				$layout_1_border_color        = self::$settings->get_current_countdown( 'sale_countdown_layout_1_border_color',
					$i );
				$layout_1_sticky_border_color = self::$settings->get_current_countdown( 'sale_countdown_layout_1_sticky_border_color',
					$i );

				$display_type = self::$settings->get_current_countdown( 'sale_countdown_display_type', $i );
				$css          .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout',
					$i,
					array( 'sale_countdown_layout_fontsize' ),
					array( 'font-size' ),
					array( 'px' )
				);
				if ( $sticky_width ) {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout.woo-sctr-countdown-timer-layout-same-line{width:100%;}';
				} else {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout.woo-sctr-countdown-timer-layout-same-line{width:auto;}';
				}
				if ( $sticky_time_unit_disable ) {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout.woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-text{display:none !important; visibility: hidden !important; }';
				}
				$css .= '@media screen and (max-width:600px){';
				if ( $resize_mobile ) {
					$css .= self::add_inline_style_reduce(
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout',
						$i,
						array( 'sale_countdown_layout_fontsize' ),
						array( 'font-size' ),
						array( 'px' ),
						$resize_mobile
					);
				}
				$css .= '}';
//				switch ( $layout_style ) {
//					case '1':
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1',
							$i,
							array(
								'sale_countdown_layout_1_background',
								'sale_countdown_layout_1_color',
								'sale_countdown_layout_1_border_radius',
								'sale_countdown_layout_1_padding',
							),
							array( 'background', 'color', 'border-radius', 'padding' ),
							array( '', '', 'px', 'px' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1 .woo-sctr-countdown-timer-text-wrap',
							$i,
							array(
								'sale_countdown_layout_1_color',
							),
							array( 'color' ),
							array( '' )
						);
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1{';
						if ( $layout_1_border_color ) {
							$css .= esc_attr__( 'border: 1px solid ' ) . $layout_1_border_color . ';';
						}
						$css .= '}';

						/* sticky style */
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line',
							$i,
							array(
								'sale_countdown_layout_1_sticky_background',
							),
							array( 'background' ),
							array( '' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-text-wrap',
							$i,
							array(
								'sale_countdown_layout_1_sticky_color',
							),
							array( 'color' ),
							array( '' )
						);
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line{';
						if ( $layout_1_sticky_border_color ) {
							$css .= esc_attr__( 'border: 1px solid ' ) . $layout_1_sticky_border_color . ';';
						}
						$css .= '}';
						if ( $resize_loop ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1',
								$i,
								array( 'sale_countdown_layout_1_padding' ),
								array( 'padding' ),
								array( 'px' ),
								$resize_loop
							);
						}
						if ( $resize_sticky ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line',
								$i,
								array( 'sale_countdown_layout_1_padding' ),
								array( 'padding' ),
								array( 'px' ),
								$resize_sticky
							);
						}
						if ( $resize_mobile ) {
							$css .= '@media screen and (max-width:600px){';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1',
								$i,
								array( 'sale_countdown_layout_1_padding' ),
								array( 'padding' ),
								array( 'px' ),
								$resize_mobile
							);
							if ( $resize_sticky ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line',
									$i,
									array( 'sale_countdown_layout_1_padding' ),
									array( 'padding' ),
									array( 'px' ),
									$resize_sticky,
									$resize_mobile
								);
							}
							$css .= '}';
						}
//						break;
//				}
				if ( $time_separator !== 'blank' ) {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer{';
					$css .= esc_attr__( 'grid-gap:5px;' );
					$css .= '}';
					if ( $resize_loop ) {
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer,';
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer .woo-sctr-countdown-unit-wrap.woo-sctr-countdown-unit-wrap-two{';
						$css .= esc_attr__( 'grid-gap: ' ) . 5 * $resize_loop / 100 . 'px;';
						$css .= '}';
					}
					if ( $resize_sticky ) {
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer,';
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line  .woo-sctr-countdown-timer .woo-sctr-countdown-unit-wrap.woo-sctr-countdown-unit-wrap-two{';
						$css .= esc_attr__( 'grid-gap: ' ) . 5 * $resize_sticky / 100 . 'px;';
						$css .= '}';
					}
					if ( $resize_mobile ) {
						$css .= '@media screen and (max-width:600px){';
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer,';
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer .woo-sctr-countdown-unit-wrap.woo-sctr-countdown-unit-wrap-two{';
						$css .= esc_attr__( 'grid-gap: ' ) . 5 * $resize_mobile / 100 . 'px;';
						$css .= '}';
						$css .= '}';
					}
				} else {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer{';
					$css .= esc_attr__( 'grid-gap:10px;' );
					$css .= '}';
					if ( $resize_loop ) {
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer{';
						$css .= esc_attr__( 'grid-gap: ' ) . 10 * $resize_loop / 100 . 'px;';
						$css .= '}';
					}
					if ( $resize_sticky ) {
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer{';
						$css .= esc_attr__( 'grid-gap: ' ) . 10 * $resize_sticky / 100 . 'px;';
						$css .= '}';
					}
					if ( $resize_mobile ) {
						$css .= '@media screen and (max-width:600px){';
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer{';
						$css .= esc_attr__( 'grid-gap: ' ) . 10 * $resize_mobile / 100 . 'px;';
						$css .= '}';
						$css .= '}';
					}
				}
				if ( $circle_smooth_animation ) {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer .woo-sctr-value-bar{transition: transform 1s ease;}';
				}
//				switch ( $display_type ) {
//					case '2':
						$template_2_time_unit_position = self::$settings->get_current_countdown( 'sale_countdown_template_2_time_unit_position', $i );
						$template_2_item_border_color  = self::$settings->get_current_countdown( 'sale_countdown_template_2_item_border_color', $i );
						$css                           .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit{';
						if ( $template_2_item_border_color ) {
							$css .= esc_attr__( 'border: 1px solid ' ) . $template_2_item_border_color . ';';
						}
						if ( $template_2_time_unit_position === 'bottom' ) {
							$css .= esc_attr__( 'grid-template-rows: 65% 35%;' );
						} else {
							$css .= esc_attr__( 'grid-template-rows: 35% 65%;' );
						}
						$css .= '}';
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit',
							$i,
							array(
								'sale_countdown_template_2_item_border_radius',
								'sale_countdown_template_2_item_height',
								'sale_countdown_template_2_item_width',
							),
							array( 'border-radius', 'height', 'width' ),
							array( 'px', 'px', 'px' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-value',
							$i,
							array(
								'sale_countdown_template_2_value_color',
								'sale_countdown_template_2_value_background',
								'sale_countdown_template_2_value_fontsize',
							),
							array( 'color', 'background', 'font-size' ),
							array( '', '', 'px' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-text',
							$i,
							array(
								'sale_countdown_template_2_time_unit_color',
								'sale_countdown_template_2_time_unit_background',
								'sale_countdown_template_2_time_unit_fontsize',
							),
							array( 'color', 'background', 'font-size' ),
							array( '', '', 'px' )
						);
						if ( $resize_loop ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit',
								$i,
								array(
									'sale_countdown_template_2_item_border_radius',
									'sale_countdown_template_2_item_height',
									'sale_countdown_template_2_item_width',
								),
								array( 'border-radius', 'height', 'width' ),
								array( 'px', 'px', 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-value',
								$i,
								array(
									'sale_countdown_template_2_value_fontsize',
								),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_2_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
						}
						if ( $resize_sticky ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit',
								$i,
								array(
									'sale_countdown_template_2_item_border_radius',
									'sale_countdown_template_2_item_height',
									'sale_countdown_template_2_item_width',
								),
								array( 'border-radius', 'height', 'width' ),
								array( 'px', 'px', 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-2 .woo-sctr-countdown-value',
								$i,
								array(
									'sale_countdown_template_2_value_fontsize',
								),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-2 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_2_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
						}
						if ( $resize_mobile ) {
							$css .= '@media screen and (max-width:600px){';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit',
								$i,
								array(
									'sale_countdown_template_2_item_border_radius',
									'sale_countdown_template_2_item_height',
									'sale_countdown_template_2_item_width',
								),
								array( 'border-radius', 'height', 'width' ),
								array( 'px', 'px', 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-value',
								$i,
								array(
									'sale_countdown_template_2_value_fontsize',
								),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_2_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= '}';
						}
//						break;
//					case '3':
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-value',
							$i,
							array(
								'sale_countdown_template_3_value_color',
								'sale_countdown_template_3_value_background',
								'sale_countdown_template_3_value_fontsize',
							),
							array( 'color', 'background', 'font-size' ),
							array( '', '', 'px' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-text',
							$i,
							array(
								'sale_countdown_template_3_time_unit_color',
								'sale_countdown_template_3_time_unit_background',
								'sale_countdown_template_3_time_unit_fontsize',
							),
							array( 'color', 'background', 'font-size' ),
							array( '', '', 'px' )
						);
						if ( $resize_loop ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-value',
								$i,
								array( 'sale_countdown_template_3_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_3_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
						}
						if ( $resize_sticky ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-3 .woo-sctr-countdown-value',
								$i,
								array( 'sale_countdown_template_3_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-3 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_3_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
						}
						if ( $resize_mobile ) {
							$css .= '@media screen and (max-width:600px){';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-value',
								$i,
								array( 'sale_countdown_template_3_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_3_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= '}';
						}
//						break;
//					case '4':
						$template_4_value_diameter     = self::$settings->get_current_countdown( 'sale_countdown_template_4_value_diameter', $i );
						$template_4_value_border_width = self::$settings->get_current_countdown( 'sale_countdown_template_4_value_border_width', $i );
						$css                           .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container',
							$i,
							array(
								'sale_countdown_template_4_value_border_color1',
								'sale_countdown_template_4_value_color',
								'sale_countdown_template_4_value_fontsize',
							),
							array( 'background', 'color', 'font-size' ),
							array( '', '', 'px' )
						);
						$css                           .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar',
							$i,
							array( 'sale_countdown_template_4_value_border_color2' ),
							array( 'background-color' ),
							array( '' )
						);
						$css                           .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar',
							$i,
							array(
								'sale_countdown_template_4_value_border_color2',
								'sale_countdown_template_4_value_border_width',
							),
							array( 'border-color', 'border-width' ),
							array( '', 'px' )
						);
						$css                           .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after',
							$i,
							array( 'sale_countdown_template_4_value_background' ),
							array( 'background' ),
							array( '' )
						);
						$css                           .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-text',
							$i,
							array(
								'sale_countdown_template_4_time_unit_color',
								'sale_countdown_template_4_time_unit_background',
								'sale_countdown_template_4_time_unit_fontsize',
							),
							array( 'color', 'background', 'font-size' ),
							array( '', '', 'px' )
						);
						if ( $template_4_value_diameter ) {
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container {';
							$css .= esc_attr__( 'width:' ) . $template_4_value_diameter . 'px;';
							$css .= esc_attr__( 'height:' ) . $template_4_value_diameter . 'px;';
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper,';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-over50 .woo-sctr-first50-bar {';
							$css .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter . 'px,' . $template_4_value_diameter . 'px,' . $template_4_value_diameter / 2 . 'px);';
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-value-bar {';
							$css .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter / 2 . 'px,' . $template_4_value_diameter . 'px,0);';
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {';
							if ( $template_4_value_border_width ) {
								$css .= esc_attr__( 'width:' ) . ( $template_4_value_diameter - 2 * $template_4_value_border_width ) . 'px;';
								$css .= esc_attr__( 'height:' ) . ( $template_4_value_diameter - 2 * $template_4_value_border_width ) . 'px;';
								$css .= esc_attr__( 'top:' ) . $template_4_value_border_width . 'px;';
								$css .= esc_attr__( 'left:' ) . $template_4_value_border_width . 'px;';
							}
							$css .= '}';
						}
						if ( $resize_loop ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container',
								$i,
								array( 'sale_countdown_template_4_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar',
								$i,
								array( 'sale_countdown_template_4_value_border_width' ),
								array( 'border-width' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_4_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							if ( $template_4_value_diameter ) {
								$template_4_value_diameter_ap     = $resize_loop * $template_4_value_diameter / 100;
								$template_4_value_border_width_ap = $resize_loop * $template_4_value_border_width / 100;
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container {';
								$css                              .= esc_attr__( 'width:' ) . $template_4_value_diameter_ap . 'px;';
								$css                              .= esc_attr__( 'height:' ) . $template_4_value_diameter_ap . 'px;';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper ,';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-over50 .woo-sctr-first50-bar {';
								$css                              .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter_ap . 'px,' . $template_4_value_diameter_ap . 'px,' . $template_4_value_diameter_ap / 2 . 'px) ;';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-value-bar {';
								$css                              .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter_ap / 2 . 'px,' . $template_4_value_diameter_ap . 'px,0);';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {';
								if ( $template_4_value_border_width ) {
									$css .= esc_attr__( 'width:' ) . ( $template_4_value_diameter_ap - 2 * $template_4_value_border_width_ap ) . 'px;';
									$css .= esc_attr__( 'height:' ) . ( $template_4_value_diameter_ap - 2 * $template_4_value_border_width_ap ) . 'px;';
									$css .= esc_attr__( 'top:' ) . $template_4_value_border_width_ap . 'px;';
									$css .= esc_attr__( 'left:' ) . $template_4_value_border_width_ap . 'px;';
								}
								$css .= '}';
							}
						}
						if ( $resize_sticky ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container',
								$i,
								array( 'sale_countdown_template_4_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar',
								$i,
								array( 'sale_countdown_template_4_value_border_width' ),
								array( 'border-width' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-4 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_4_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							if ( $template_4_value_diameter ) {
								$template_4_value_diameter_ap     = $resize_sticky * $template_4_value_diameter / 100;
								$template_4_value_border_width_ap = $resize_sticky * $template_4_value_border_width / 100;
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container {';
								$css                              .= esc_attr__( 'width:' ) . $template_4_value_diameter_ap . 'px;';
								$css                              .= esc_attr__( 'height:' ) . $template_4_value_diameter_ap . 'px;';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper ,';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line  .woo-sctr-countdown-timer-4 .woo-sctr-over50 .woo-sctr-first50-bar {';
								$css                              .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter_ap . 'px,' . $template_4_value_diameter_ap . 'px,' . $template_4_value_diameter_ap / 2 . 'px);';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-4 .woo-sctr-value-bar {';
								$css                              .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter_ap / 2 . 'px,' . $template_4_value_diameter_ap . 'px,0);';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {';
								if ( $template_4_value_border_width ) {
									$css .= esc_attr__( 'width:' ) . ( $template_4_value_diameter_ap - 2 * $template_4_value_border_width_ap ) . 'px;';
									$css .= esc_attr__( 'height:' ) . ( $template_4_value_diameter_ap - 2 * $template_4_value_border_width_ap ) . 'px;';
									$css .= esc_attr__( 'top:' ) . $template_4_value_border_width_ap . 'px;';
									$css .= esc_attr__( 'left:' ) . $template_4_value_border_width_ap . 'px;';
								}
								$css .= '}';
							}
						}
						if ( $resize_mobile ) {
							$css .= '@media screen and (max-width:600px){';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container ',
								$i,
								array( 'sale_countdown_template_4_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar',
								$i,
								array( 'sale_countdown_template_4_value_border_width' ),
								array( 'border-width' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_4_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							if ( $template_4_value_diameter ) {
								$template_4_value_diameter_ap     = $resize_mobile * $template_4_value_diameter / 100;
								$template_4_value_border_width_ap = $resize_mobile * $template_4_value_border_width / 100;
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container {';
								$css                              .= esc_attr__( 'width:' ) . $template_4_value_diameter_ap . 'px;';
								$css                              .= esc_attr__( 'height:' ) . $template_4_value_diameter_ap . 'px;';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper ,';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-over50 .woo-sctr-first50-bar {';
								$css                              .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter_ap . 'px,' . $template_4_value_diameter_ap . 'px,' . $template_4_value_diameter_ap / 2 . 'px)';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-value-bar {';
								$css                              .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter_ap / 2 . 'px,' . $template_4_value_diameter_ap . 'px,0);';
								$css                              .= '}';
								$css                              .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {';
								if ( $template_4_value_border_width ) {
									$css .= esc_attr__( 'width:' ) . ( $template_4_value_diameter_ap - 2 * $template_4_value_border_width_ap ) . 'px;';
									$css .= esc_attr__( 'height:' ) . ( $template_4_value_diameter_ap - 2 * $template_4_value_border_width_ap ) . 'px;';
									$css .= esc_attr__( 'top:' ) . $template_4_value_border_width_ap . 'px;';
									$css .= esc_attr__( 'left:' ) . $template_4_value_border_width_ap . 'px;';
								}
								$css .= '}';
							}

							$css .= '}';
						}
//						break;
//					case '5':
						$template_5_value_fontsize    = self::$settings->get_current_countdown( 'sale_countdown_template_5_value_fontsize', $i );
						$template_5_item_diameter     = self::$settings->get_current_countdown( 'sale_countdown_template_5_item_diameter', $i );
						$template_5_item_border_width = self::$settings->get_current_countdown( 'sale_countdown_template_5_item_border_width', $i );
						$css                          .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container ',
							$i,
							array(
								'sale_countdown_template_5_item_diameter',
								'sale_countdown_template_5_item_diameter',
							),
							array( 'width', 'height' ),
							array( 'px', 'px' )
						);
						$css                          .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-countdown-circle',
							$i,
							array( 'sale_countdown_template_5_item_border_width' ),
							array( 'border-width' ),
							array( 'px' )
						);
						$css                          .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle .woo-sctr-countdown-value',
							$i,
							array(
								'sale_countdown_template_5_value_color',
								'sale_countdown_template_5_value_fontsize',
							),
							array( 'color', 'font-size' ),
							array( '', 'px' )
						);
						$css                          .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-text',
							$i,
							array(
								'sale_countdown_template_5_time_unit_color',
								'sale_countdown_template_5_time_unit_fontsize',
							),
							array( 'color', 'font-size' ),
							array( '', 'px' )
						);

						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper{';
						if ( $template_5_item_diameter ) {
							$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter . 'px,' . $template_5_item_diameter . 'px,' . $template_5_item_diameter / 2 . 'px)';
						}
						$css .= '}';
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-value-bar {';
						if ( $template_5_item_diameter ) {
							$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter / 2 . 'px,' . $template_5_item_diameter . 'px,0);';
						}
						if ( $template_5_item_border_width ) {
							$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width . 'px;';
						}
						$css .= '}';
						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-over50 .woo-sctr-first50-bar{';
						if ( $template_5_item_diameter ) {
							$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter . 'px,' . $template_5_item_diameter . 'px,' . $template_5_item_diameter / 2 . 'px);';
						}
						if ( $template_5_item_border_width ) {
							$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width . 'px;';
						}
						$css .= '}';
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-date .woo-sctr-countdown-circle',
							$i,
							array(
								'sale_countdown_template_5_date_border_color1',
								'sale_countdown_template_5_date_background',
							),
							array( 'border-color', 'background' ),
							array( '', '' )
						);
						$css .= self::add_inline_style(
							array(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-date .woo-sctr-value-bar',
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-date.woo-sctr-over50 .woo-sctr-first50-bar'
							),
							$i,
							array( 'sale_countdown_template_5_date_border_color2' ),
							array( 'border-color' ),
							array( '' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-hour .woo-sctr-countdown-circle',
							$i,
							array(
								'sale_countdown_template_5_hour_border_color1',
								'sale_countdown_template_5_hour_background',
							),
							array( 'border-color', 'background' ),
							array( '', '' )
						);
						$css .= self::add_inline_style(
							array(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-hour .woo-sctr-value-bar',
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-hour.woo-sctr-over50 .woo-sctr-first50-bar'
							),
							$i,
							array( 'sale_countdown_template_5_hour_border_color2' ),
							array( 'border-color' ),
							array( '' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute .woo-sctr-countdown-circle',
							$i,
							array(
								'sale_countdown_template_5_minute_border_color1',
								'sale_countdown_template_5_minute_background',
							),
							array( 'border-color', 'background' ),
							array( '', '' )
						);
						$css .= self::add_inline_style(
							array(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute .woo-sctr-value-bar',
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute.woo-sctr-over50 .woo-sctr-first50-bar'
							),
							$i,
							array( 'sale_countdown_template_5_minute_border_color2' ),
							array( 'border-color' ),
							array( '' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-second .woo-sctr-countdown-circle',
							$i,
							array(
								'sale_countdown_template_5_second_border_color1',
								'sale_countdown_template_5_second_background',
							),
							array( 'border-color', 'background' ),
							array( '', '' )
						);
						$css .= self::add_inline_style(
							array(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-second .woo-sctr-value-bar',
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-second.woo-sctr-over50 .woo-sctr-first50-bar'
							),
							$i,
							array( 'sale_countdown_template_5_second_border_color2' ),
							array( 'border-color' ),
							array( '' )
						);
						if ( $resize_loop ) {
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container ',
								$i,
								array(
									'sale_countdown_template_5_item_diameter',
									'sale_countdown_template_5_item_diameter',
								),
								array( 'width', 'height' ),
								array( 'px', 'px' ),
								$resize_loop
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-countdown-circle',
								$i,
								array( 'sale_countdown_template_5_item_border_width' ),
								array( 'border-width' ),
								array( 'px' ),
								$resize_loop
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-value',
								$i,
								array( 'sale_countdown_template_5_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_5_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							$template_5_item_diameter_ap     = $template_5_item_diameter ? $template_5_item_diameter * $resize_loop / 100 : '';
							$template_5_item_border_width_ap = $template_5_item_border_width ? $template_5_item_border_width * $resize_loop / 100 : '';
							$css                             .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper{';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap / 2 . 'px)';
							}
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-value-bar {';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap / 2 . 'px,' . $template_5_item_diameter_ap . 'px,0);';
							}
							if ( $template_5_item_border_width_ap ) {
								$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width_ap . 'px;';
							}
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-over50 .woo-sctr-first50-bar {';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap / 2 . 'px);';
							}
							if ( $template_5_item_border_width_ap ) {
								$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width_ap . 'px;';
							}
							$css .= '}';
						}
						if ( $resize_sticky ) {
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container',
								$i,
								array(
									'sale_countdown_template_5_item_diameter',
									'sale_countdown_template_5_item_diameter',
								),
								array( 'width', 'height' ),
								array( 'px', 'px' ),
								$resize_sticky
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-5  .woo-sctr-countdown-circle',
								$i,
								array( 'sale_countdown_template_5_item_border_width' ),
								array( 'border-width' ),
								array( 'px' ),
								$resize_sticky
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-5 .woo-sctr-countdown-value',
								$i,
								array( 'sale_countdown_template_5_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-5 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_5_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							$template_5_item_diameter_ap     = $template_5_item_diameter ? $template_5_item_diameter * $resize_sticky / 100 : '';
							$template_5_item_border_width_ap = $template_5_item_border_width ? $template_5_item_border_width * $resize_sticky / 100 : '';
							$css                             .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper{';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap / 2 . 'px)';
							}
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-5  .woo-sctr-value-bar {';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap / 2 . 'px,' . $template_5_item_diameter_ap . 'px,0);';
							}
							if ( $template_5_item_border_width_ap ) {
								$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width_ap . 'px;';
							}
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-5 .woo-sctr-over50 .woo-sctr-first50-bar {';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap / 2 . 'px);';
							}
							if ( $template_5_item_border_width_ap ) {
								$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width_ap . 'px;';
							}
							$css .= '}';
						}
						if ( $resize_mobile ) {
							$css                             .= '@media screen and (max-width:600px){';
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container ',
								$i,
								array(
									'sale_countdown_template_5_item_diameter',
									'sale_countdown_template_5_item_diameter',
								),
								array( 'width', 'height' ),
								array( 'px', 'px' ),
								$resize_mobile
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-countdown-circle',
								$i,
								array( 'sale_countdown_template_5_item_border_width' ),
								array( 'border-width' ),
								array( 'px' ),
								$resize_mobile
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle .woo-sctr-countdown-value',
								$i,
								array( 'sale_countdown_template_5_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css                             .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_5_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$template_5_item_diameter_ap     = $template_5_item_diameter ? $template_5_item_diameter * $resize_mobile / 100 : '';
							$template_5_item_border_width_ap = $template_5_item_border_width ? $template_5_item_border_width * $resize_mobile / 100 : '';
							$css                             .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper{';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap / 2 . 'px)';
							}
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-value-bar {';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap / 2 . 'px,' . $template_5_item_diameter_ap . 'px,0);';
							}
							if ( $template_5_item_border_width_ap ) {
								$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width_ap . 'px;';
							}
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-over50 .woo-sctr-first50-bar {';
							if ( $template_5_item_diameter_ap ) {
								$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap . 'px,' . $template_5_item_diameter_ap / 2 . 'px);';
							}
							if ( $template_5_item_border_width_ap ) {
								$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width_ap . 'px;';
							}
							$css .= '}';
							$css .= '}';
						}
//						break;
//					case '6':
						$template_6_value_box_shadow    = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_box_shadow',
							$i );
						$template_6_value_cut_behind    = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_cut_behind',
							$i );
						$template_6_value_cut_color     = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_cut_color',
							$i );
						$template_6_value_border_radius = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_border_radius',
							$i );
						$css                            .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-unit-two-vertical-wrap',
							$i,
							array( 'sale_countdown_template_6_time_unit_grid_gap' ),
							array( 'grid-gap' ),
							array( 'px' )
						);
						$css                            .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap',
							$i,
							array(
								'sale_countdown_template_6_value_width',
								'sale_countdown_template_6_value_height',
								'sale_countdown_template_6_value_border_radius',
							),
							array( 'width', 'height', 'border-radius' ),
							array( 'px', 'px', 'px' )
						);
						$css                            .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap{';
						if ( $template_6_value_box_shadow ) {
							$css .= esc_attr__( 'box-shadow: 0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08);' );
						} else {
							$css .= esc_attr__( 'box-shadow: unset;' );
						}
						$css .= '}';
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap  span',
							$i,
							array( 'sale_countdown_template_6_value_fontsize' ),
							array( 'font-size' ),
							array( 'px' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6  .woo-sctr-countdown-two-vertical-top',
							$i,
							array(
								'sale_countdown_template_6_value_color1',
								'sale_countdown_template_6_value_background1',
							),
							array( 'color', 'background' ),
							array( '', '' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6   .woo-sctr-countdown-two-vertical-bottom',
							$i,
							array(
								'sale_countdown_template_6_value_color2',
								'sale_countdown_template_6_value_background2',
							),
							array( 'color', 'background' ),
							array( '', '' )
						);
						if ( ! $template_6_value_cut_behind ) {
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6  .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default{';

							if ( $template_6_value_cut_color ) {
								$css .= esc_attr__( 'border-bottom: 1px solid ' ) . $template_6_value_cut_color . ';';
							}
							$css .= '}';
						} else {
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6  .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind::after{';
							if ( $template_6_value_cut_color ) {
								$css .= esc_attr__( 'border-bottom: 1px solid ' ) . $template_6_value_cut_color . ';';
							}
							$css .= '}';
						}
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6  .woo-sctr-countdown-text',
							$i,
							array(
								'sale_countdown_template_6_time_unit_color',
								'sale_countdown_template_6_time_unit_fontsize',
							),
							array( 'color', 'font-size' ),
							array( '', 'px' )
						);
						if ( $animation_style === 'slide' ) {
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-slide  .woo-sctr-countdown-value',
								$i,
								array(
									'sale_countdown_template_6_value_width',
									'sale_countdown_template_6_value_height',
									'sale_countdown_template_6_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' )
							);

							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-two-vertical-wrap{';
							$css .= esc_attr__( 'border-radius: unset !important;' );
							$css .= '}';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-slide  .woo-sctr-countdown-value {';
							if ( $template_6_value_box_shadow ) {
								$css .= esc_attr__( 'box-shadow: 0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08) ;' );
							} else {
								$css .= esc_attr__( 'box-shadow: unset ;' );
							}
							$css .= '}';
						} elseif ( $animation_style == 'flip' ) {
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
								$i,
								array(
									'sale_countdown_template_6_value_width',
									'sale_countdown_template_6_value_height',
								),
								array( 'width', 'height' ),
								array( 'px', 'px' )
							);
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card',
								$i,
								array(
									'sale_countdown_template_6_value_fontsize',
									'sale_countdown_template_6_value_border_radius',
								),
								array( 'font-size', 'border-radius' ),
								array( 'px', 'px' )
							);
							$css .= self::add_inline_style(
								array(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top',
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom',
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before',
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after',
								),
								$i,
								array(
									'sale_countdown_template_6_value_color1',
									'sale_countdown_template_6_value_background1',
								),
								array( 'color', 'background' ),
								array( '', '' )
							);
							if ( $template_6_value_border_radius ) {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								$css .= esc_attr__( 'border-radius:' ) . $template_6_value_border_radius . 'px ' . $template_6_value_border_radius . 'px 0 0;';
								$css .= '}';

								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								$css .= esc_attr__( 'border-radius: 0 0 ' ) . $template_6_value_border_radius . 'px ' . $template_6_value_border_radius . 'px;';
								$css .= '}';
							}
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom ',
								$i,
								array(
									'sale_countdown_template_6_value_color2',
									'sale_countdown_template_6_value_background2',
								),
								array( 'color', 'background' ),
								array( '', '' )
							);
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
								$i,
								array(
									'sale_countdown_template_6_value_height',
								),
								array( 'height' ),
								array( 'px' )
							);
							if ( ! $template_6_value_cut_behind ) {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_6_value_cut_color ) {
									$css .= esc_attr__( 'border-top: 1px solid ' ) . $template_6_value_cut_color . ' ;';
								}
								$css .= '}';
							} else {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								$css .= esc_attr__( 'border-top: unset;' );
								$css .= esc_attr__( 'border: unset ;' );
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip .woo-sctr-countdown-two-vertical-top-cut-behind .woo-sctr-countdown-flip-bottom:before{';
								if ( $template_6_value_cut_color ) {
									$css .= esc_attr__( 'border-top: 1px solid ' ) . woo_ctr_hextorgba( $template_6_value_cut_color, 0.8 ) . ';';
								}
								$css .= '}';
							}
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card {';
							if ( $template_6_value_box_shadow ) {
								$css .= esc_attr__( 'box-shadow: 0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08) ;' );
							} else {
								$css .= esc_attr__( 'box-shadow: unset ;' );
							}
							$css .= '}';
						}
						if ( $resize_loop ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-unit-two-vertical-wrap',
								$i,
								array( 'sale_countdown_template_6_time_unit_grid_gap' ),
								array( 'grid-gap' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap',
								$i,
								array(
									'sale_countdown_template_6_value_width',
									'sale_countdown_template_6_value_height',
									'sale_countdown_template_6_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap  span',
								$i,
								array( 'sale_countdown_template_6_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6  .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_6_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							if ( $animation_style === 'slide' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-slide  .woo-sctr-countdown-value',
									$i,
									array(
										'sale_countdown_template_6_value_width',
										'sale_countdown_template_6_value_height',
										'sale_countdown_template_6_value_border_radius',
									),
									array( 'width', 'height', 'border-radius' ),
									array( 'px', 'px', 'px' ),
									$resize_loop
								);
							} elseif ( $animation_style === 'flip' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
									$i,
									array(
										'sale_countdown_template_6_value_width',
										'sale_countdown_template_6_value_height',
									),
									array( 'width', 'height' ),
									array( 'px', 'px' ),
									$resize_loop
								);
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card',
									$i,
									array(
										'sale_countdown_template_6_value_fontsize',
										'sale_countdown_template_6_value_border_radius',
									),
									array( 'font-size', 'border-radius' ),
									array( 'px', 'px' ),
									$resize_loop
								);
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								if ( $template_6_value_border_radius ) {
									$css .= esc_attr__( 'border-radius:' ) . ( $template_6_value_border_radius * $resize_loop / 100 ) . 'px ' . ( $template_6_value_border_radius * $resize_loop / 100 ) . 'px 0 0;';
								}
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_6_value_border_radius ) {
									$css .= esc_attr__( 'border-radius: 0 0 ' ) . ( $template_6_value_border_radius * $resize_loop / 100 ) . 'px ' . ( $template_6_value_border_radius * $resize_loop / 100 ) . 'px;';
								}
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
									$i,
									array(
										'sale_countdown_template_6_value_height',
									),
									array( 'height' ),
									array( 'px' ),
									$resize_loop
								);
							}
						}
						if ( $resize_sticky ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-unit-two-vertical-wrap',
								$i,
								array( 'sale_countdown_template_6_time_unit_grid_gap' ),
								array( 'grid-gap' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap',
								$i,
								array(
									'sale_countdown_template_6_value_width',
									'sale_countdown_template_6_value_height',
									'sale_countdown_template_6_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap  span',
								$i,
								array( 'sale_countdown_template_6_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6  .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_6_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							if ( $animation_style === 'slide' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-slide  .woo-sctr-countdown-value',
									$i,
									array(
										'sale_countdown_template_6_value_width',
										'sale_countdown_template_6_value_height',
										'sale_countdown_template_6_value_border_radius',
									),
									array( 'width', 'height', 'border-radius' ),
									array( 'px', 'px', 'px' ),
									$resize_sticky
								);
							} elseif ( $animation_style === 'flip' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
									$i,
									array(
										'sale_countdown_template_6_value_width',
										'sale_countdown_template_6_value_height',
									),
									array( 'width', 'height' ),
									array( 'px', 'px' ),
									$resize_sticky
								);
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card',
									$i,
									array(
										'sale_countdown_template_6_value_fontsize',
										'sale_countdown_template_6_value_border_radius',
									),
									array( 'font-size', 'border-radius' ),
									array( 'px', 'px' ),
									$resize_sticky
								);
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								if ( $template_6_value_border_radius ) {
									$css .= esc_attr__( 'border-radius:' ) . ( $template_6_value_border_radius * $resize_sticky / 100 ) . 'px ' . ( $template_6_value_border_radius * $resize_sticky / 100 ) . 'px 0 0;';
								}
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_6_value_border_radius ) {
									$css .= esc_attr__( 'border-radius: 0 0 ' ) . ( $template_6_value_border_radius * $resize_sticky / 100 ) . 'px ' . ( $template_6_value_border_radius * $resize_sticky / 100 ) . 'px;';
								}
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
									$i,
									array(
										'sale_countdown_template_6_value_height',
									),
									array( 'height' ),
									array( 'px' ),
									$resize_sticky
								);
							}
						}
						if ( $resize_mobile ) {
							$css .= '@media screen and (max-width:600px){';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-unit-two-vertical-wrap',
								$i,
								array( 'sale_countdown_template_6_time_unit_grid_gap' ),
								array( 'grid-gap' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap',
								$i,
								array(
									'sale_countdown_template_6_value_width',
									'sale_countdown_template_6_value_height',
									'sale_countdown_template_6_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap  span',
								$i,
								array( 'sale_countdown_template_6_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6  .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_6_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							if ( $animation_style === 'slide' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-slide  .woo-sctr-countdown-value',
									$i,
									array(
										'sale_countdown_template_6_value_width',
										'sale_countdown_template_6_value_height',
										'sale_countdown_template_6_value_border_radius',
									),
									array( 'width', 'height', 'border-radius' ),
									array( 'px', 'px', 'px' ),
									$resize_mobile
								);
							} elseif ( $animation_style === 'flip' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
									$i,
									array(
										'sale_countdown_template_6_value_width',
										'sale_countdown_template_6_value_height',
									),
									array( 'width', 'height' ),
									array( 'px', 'px' ),
									$resize_mobile
								);
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card ',
									$i,
									array(
										'sale_countdown_template_6_value_fontsize',
										'sale_countdown_template_6_value_border_radius',
									),
									array( 'font-size', 'border-radius' ),
									array( 'px', 'px' ),
									$resize_mobile
								);
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								if ( $template_6_value_border_radius ) {
									$css .= esc_attr__( 'border-radius:' ) . ( $template_6_value_border_radius * $resize_mobile / 100 ) . 'px ' . ( $template_6_value_border_radius * $resize_mobile / 100 ) . 'px 0 0;';
								}
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_6_value_border_radius ) {
									$css .= esc_attr__( 'border-radius: 0 0 ' ) . ( $template_6_value_border_radius * $resize_mobile / 100 ) . 'px ' . ( $template_6_value_border_radius * $resize_mobile / 100 ) . 'px;';
								}
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
									$i,
									array(
										'sale_countdown_template_6_value_height',
									),
									array( 'height' ),
									array( 'px', 'px' ),
									$resize_mobile
								);
							}
							$css .= '}';
						}
//						break;
//					case '7':
						$template_7_value_box_shadow    = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_box_shadow', $i );
						$template_7_value_cut_behind    = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_cut_behind', $i );
						$template_7_value_cut_color     = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_cut_color', $i );
						$template_7_value_border_radius = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_border_radius', $i );
						$css                            .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-unit-two-vertical-wrap',
							$i,
							array( 'sale_countdown_template_7_time_unit_grid_gap' ),
							array( 'grid-gap' ),
							array( 'px' )
						);
						$css                            .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap',
							$i,
							array(
								'sale_countdown_template_7_value_width',
								'sale_countdown_template_7_value_height',
								'sale_countdown_template_7_value_border_radius',
							),
							array( 'width', 'height', 'border-radius' ),
							array( 'px', 'px', 'px' )
						);
						$css                            .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap{';
						if ( $template_7_value_box_shadow ) {
							$css .= esc_attr__( 'box-shadow: 0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08) ;' );
						} else {
							$css .= esc_attr__( 'box-shadow: unset ;' );
						}
						$css .= '}';
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap span',
							$i,
							array( 'sale_countdown_template_7_value_fontsize' ),
							array( 'font-size' ),
							array( 'px' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-two-vertical-top',
							$i,
							array(
								'sale_countdown_template_7_value_color1',
								'sale_countdown_template_7_value_background1',
							),
							array( 'color', 'background' ),
							array( '', '' )
						);
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-two-vertical-bottom',
							$i,
							array(
								'sale_countdown_template_7_value_color2',
								'sale_countdown_template_7_value_background2',
							),
							array( 'color', 'background' ),
							array( '', '' )
						);
						if ( ! $template_7_value_cut_behind ) {
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default{';

							if ( $template_7_value_cut_color ) {
								$css .= esc_attr__( 'border-bottom: 1px solid ' ) . $template_7_value_cut_color . ';';
							}
							$css .= '}';
						} else {
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind::after{';
							if ( $template_7_value_cut_color ) {
								$css .= esc_attr__( 'border-bottom: 1px solid ' ) . $template_7_value_cut_color . ';';
							}
							$css .= '}';
						}
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-text',
							$i,
							array(
								'sale_countdown_template_7_time_unit_color',
								'sale_countdown_template_7_time_unit_fontsize',
							),
							array( 'color', 'font-size' ),
							array( '', 'px' )
						);
						if ( $animation_style === 'slide' ) {
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-two-vertical-wrap{';
							$css .= esc_attr__( 'border-radius: unset;' );
							$css .= '}';
							$css .= self::add_inline_style(
								array(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value-wrap',
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value'
								),
								$i,
								array(
									'sale_countdown_template_7_value_width',
									'sale_countdown_template_7_value_height',
									'sale_countdown_template_7_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' )
							);
						} elseif ( $animation_style === 'flip' ) {
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
								$i,
								array(
									'sale_countdown_template_7_value_width',
									'sale_countdown_template_7_value_height',
								),
								array( 'width', 'height' ),
								array( 'px', 'px' )
							);
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card',
								$i,
								array( 'sale_countdown_template_7_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' )
							);
							$css .= self::add_inline_style(
								array(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top',
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip   .woo-sctr-countdown-flip-bottom',
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip .woo-sctr-countdown-flip-back::before',
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip .woo-sctr-countdown-flip-back::after',
								),
								$i,
								array(
									'sale_countdown_template_7_value_color1',
									'sale_countdown_template_7_value_background1',
								),
								array( 'color', 'background' ),
								array( '', '' )
							);
							if ( $template_7_value_border_radius ) {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								$css .= esc_attr__( 'border-radius:' ) . $template_7_value_border_radius . 'px ' . $template_7_value_border_radius . 'px 0 0;';
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								$css .= esc_attr__( 'border-radius: 0 0 ' ) . $template_7_value_border_radius . 'px ' . $template_7_value_border_radius . 'px;';
								$css .= '}';
							}
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
								$i,
								array(
									'sale_countdown_template_7_value_height',
								),
								array( 'height' ),
								array( 'px' )
							);
							$css .= self::add_inline_style(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom ',
								$i,
								array(
									'sale_countdown_template_7_value_color2',
									'sale_countdown_template_7_value_background2',
								),
								array( 'color', 'background' ),
								array( '', '' )
							);
							if ( ! $template_7_value_cut_behind ) {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_7_value_cut_color ) {
									$css .= esc_attr__( 'border-top: 1px solid ' ) . $template_7_value_cut_color . ' ;';
								}
								$css .= '}';
							} else {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								$css .= esc_attr__( 'border-top: unset  ;' );
								$css .= esc_attr__( 'border: unset ;' );
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip .woo-sctr-countdown-two-vertical-top-cut-behind .woo-sctr-countdown-flip-bottom:before{';
								if ( $template_7_value_cut_color ) {
									$css .= esc_attr__( 'border-top: 1px solid ' ) . $template_7_value_cut_color . ';';
								}
								$css .= '}';
							}
						}
						if ( $resize_loop ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-unit-two-vertical-wrap',
								$i,
								array( 'sale_countdown_template_7_time_unit_grid_gap' ),
								array( 'grid-gap' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap',
								$i,
								array(
									'sale_countdown_template_7_value_width',
									'sale_countdown_template_7_value_height',
									'sale_countdown_template_7_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap span',
								$i,
								array( 'sale_countdown_template_7_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_7_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);

							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-value-wrap-wrap{';
							$css .= esc_attr__( 'grid-gap: ' ) . 5 * $resize_sticky / 100 . 'px;';
							$css .= '}';
							if ( $animation_style === 'slide' ) {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-two-vertical-wrap{';
								$css .= esc_attr__( 'border-radius: unset;' );
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									array(
										'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value-wrap',
										'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value'
									),
									$i,
									array(
										'sale_countdown_template_7_value_width',
										'sale_countdown_template_7_value_height',
										'sale_countdown_template_7_value_border_radius',
									),
									array( 'width', 'height', 'border-radius' ),
									array( 'px', 'px', 'px' ),
									$resize_loop
								);
							} elseif ( $animation_style === 'flip' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
									$i,
									array(
										'sale_countdown_template_7_value_width',
										'sale_countdown_template_7_value_height',
									),
									array( 'width', 'height' ),
									array( 'px', 'px' ),
									$resize_loop
								);
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card',
									$i,
									array( 'sale_countdown_template_7_value_fontsize' ),
									array( 'font-size' ),
									array( 'px' ),
									$resize_loop
								);
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								if ( $template_7_value_border_radius ) {
									$css .= esc_attr__( 'border-radius:' ) . ( $template_7_value_border_radius * $resize_loop / 100 ) . 'px ' . ( $template_7_value_border_radius * $resize_loop / 100 ) . 'px 0 0;';
								}
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_7_value_border_radius ) {
									$css .= esc_attr__( 'border-radius: 0 0 ' ) . ( $template_7_value_border_radius * $resize_loop / 100 ) . 'px ' . ( $template_7_value_border_radius * $resize_loop / 100 ) . 'px;';
								}
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
									$i,
									array(
										'sale_countdown_template_7_value_height',
										'sale_countdown_template_7_value_height',
									),
									array( 'line-height', 'height' ),
									array( 'px', 'px' ),
									$resize_loop
								);
							}
						}
						if ( $resize_sticky ) {
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7 .woo-sctr-countdown-value-wrap-wrap{';
							$css .= esc_attr__( 'grid-gap: ' ) . 5 * $resize_sticky / 100 . 'px;';
							$css .= '}';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7 .woo-sctr-countdown-unit-two-vertical-wrap',
								$i,
								array( 'sale_countdown_template_7_time_unit_grid_gap' ),
								array( 'grid-gap' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap',
								$i,
								array(
									'sale_countdown_template_7_value_width',
									'sale_countdown_template_7_value_height',
									'sale_countdown_template_7_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap span',
								$i,
								array( 'sale_countdown_template_7_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7  .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_7_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
							if ( $animation_style === 'slide' ) {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-two-vertical-wrap{';
								$css .= esc_attr__( 'border-radius: unset;' );
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value-wrap, .woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value',
									$i,
									array(
										'sale_countdown_template_7_value_width',
										'sale_countdown_template_7_value_height',
										'sale_countdown_template_7_value_border_radius',
									),
									array( 'width', 'height', 'border-radius' ),
									array( 'px', 'px', 'px' ),
									$resize_sticky
								);
							} elseif ( $animation_style === 'flip' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
									$i,
									array(
										'sale_countdown_template_7_value_width',
										'sale_countdown_template_7_value_height',
									),
									array( 'width', 'height' ),
									array( 'px', 'px' ),
									$resize_sticky
								);
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card',
									$i,
									array( 'sale_countdown_template_7_value_fontsize' ),
									array( 'font-size' ),
									array( 'px' ),
									$resize_sticky
								);
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								if ( $template_7_value_border_radius ) {
									$css .= esc_attr__( 'border-radius:' ) . ( $template_7_value_border_radius * $resize_sticky / 100 ) . 'px ' . ( $template_7_value_border_radius * $resize_sticky / 100 ) . 'px 0 0;';
								}
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_7_value_border_radius ) {
									$css .= esc_attr__( 'border-radius: 0 0 ' ) . ( $template_7_value_border_radius * $resize_sticky / 100 ) . 'px ' . ( $template_7_value_border_radius * $resize_sticky / 100 ) . 'px;';
								}
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
									$i,
									array(
										'sale_countdown_template_7_value_height',
									),
									array( 'height' ),
									array( 'px' ),
									$resize_sticky
								);
							}
						}
						if ( $resize_mobile ) {
							$css .= '@media screen and (max-width:600px){';
							$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-value-wrap-wrap{';
							$css .= esc_attr__( 'grid-gap: ' ) . 5 * $resize_mobile / 100 . 'px;';
							$css .= '}';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-unit-two-vertical-wrap',
								$i,
								array( 'sale_countdown_template_7_time_unit_grid_gap' ),
								array( 'grid-gap' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap',
								$i,
								array(
									'sale_countdown_template_7_value_width',
									'sale_countdown_template_7_value_height',
									'sale_countdown_template_7_value_border_radius',
								),
								array( 'width', 'height', 'border-radius' ),
								array( 'px', 'px', 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap span',
								$i,
								array( 'sale_countdown_template_7_value_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_7_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							if ( $animation_style === 'slide' ) {
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value-wrap, .woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-value',
									$i,
									array(
										'sale_countdown_template_7_value_width',
										'sale_countdown_template_7_value_height',
										'sale_countdown_template_7_value_border_radius',
									),
									array( 'width', 'height', 'border-radius' ),
									array( 'px', 'px', 'px' ),
									$resize_mobile
								);
							} elseif ( $animation_style === 'flip' ) {
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-slide .woo-sctr-countdown-two-vertical-wrap{';
								$css .= esc_attr__( 'border-radius: unset;' );
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-wrap',
									$i,
									array(
										'sale_countdown_template_7_value_width',
										'sale_countdown_template_7_value_height',
									),
									array( 'width', 'height' ),
									array( 'px', 'px' ),
									$resize_mobile
								);
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-card',
									$i,
									array( 'sale_countdown_template_7_value_fontsize' ),
									array( 'font-size' ),
									array( 'px' ),
									$resize_mobile
								);
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-top,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::before,';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '   .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-back::after {';
								if ( $template_7_value_border_radius ) {
									$css .= esc_attr__( 'border-radius:' ) . ( $template_7_value_border_radius * $resize_mobile / 100 ) . 'px ' . ( $template_7_value_border_radius * $resize_mobile / 100 ) . 'px 0 0;';
								}
								$css .= '}';
								$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom {';
								if ( $template_7_value_border_radius ) {
									$css .= esc_attr__( 'border-radius: 0 0 ' ) . ( $template_7_value_border_radius * $resize_mobile / 100 ) . 'px ' . ( $template_7_value_border_radius * $resize_mobile / 100 ) . 'px;';
								}
								$css .= '}';
								$css .= self::add_inline_style_reduce(
									'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-7.woo-sctr-shortcode-countdown-unit-animation-flip  .woo-sctr-countdown-flip-bottom::after',
									$i,
									array(
										'sale_countdown_template_7_value_height',
									),
									array( 'height' ),
									array( 'px' ),
									$resize_mobile
								);
							}
							$css .= '}';
						}
//						break;
//					default:
						$template_1_value_border_color = self::$settings->get_current_countdown( 'sale_countdown_template_1_value_border_color', $i );
						$css                           .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-value',
							$i,
							array(
								'sale_countdown_template_1_value_color',
								'sale_countdown_template_1_value_background',
								'sale_countdown_template_1_value_border_radius',
								'sale_countdown_template_1_value_width',
								'sale_countdown_template_1_value_height',
								'sale_countdown_template_1_value_font_size',
							),
							array( 'color', 'background', 'border-radius', 'width', 'height', 'font-size' ),
							array( '', '', 'px', 'px', 'px', 'px' )
						);

						$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-value{';
						if ( $template_1_value_border_color ) {
							$css .= esc_attr__( 'border: 1px solid ' ) . $template_1_value_border_color . ';';
						}
						$css .= '}';
						$css .= self::add_inline_style(
							'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-text',
							$i,
							array(
								'sale_countdown_template_1_time_unit_color',
								'sale_countdown_template_1_time_unit_background',
								'sale_countdown_template_1_time_unit_fontsize',
							),
							array( 'color', 'background', 'font-size' ),
							array( '', '', 'px' )
						);
						if ( $resize_loop ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-value',
								$i,
								array(
									'sale_countdown_template_1_value_width',
									'sale_countdown_template_1_value_height',
									'sale_countdown_template_1_value_font_size',
								),
								array( 'width', 'height', 'font-size' ),
								array( 'px', 'px', 'px' ),
								$resize_loop
							);
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-loop.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_1_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_loop
							);
						}

						if ( $resize_sticky ) {
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-1 .woo-sctr-countdown-value',
								$i,
								array(
									'sale_countdown_template_1_value_width',
									'sale_countdown_template_1_value_height',
									'sale_countdown_template_1_value_font_size',
								),
								array( 'width', 'height', 'font-size' ),
								array( 'px', 'px', 'px' ),
								$resize_sticky
							);

							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-1 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_1_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_sticky
							);
						}
						if ( $resize_mobile ) {
							$css .= '@media screen and (max-width:600px){';
							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-value',
								$i,
								array(
									'sale_countdown_template_1_value_width',
									'sale_countdown_template_1_value_height',
									'sale_countdown_template_1_value_font_size',
								),
								array( 'width', 'height', 'font-size' ),
								array( 'px', 'px', 'px' ),
								$resize_mobile
							);

							$css .= self::add_inline_style_reduce(
								'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-text',
								$i,
								array( 'sale_countdown_template_1_time_unit_fontsize' ),
								array( 'font-size' ),
								array( 'px' ),
								$resize_mobile
							);
							$css .= '}';
						}
//				}
				//progress bar css
				$progress_bar_template_1_width      = self::$settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_width', $i );
				$progress_bar_template_1_width_type = self::$settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_width_type', $i );
				$css                                .= '.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-wrap{';
				if ( $progress_bar_template_1_width ) {
					$css .= esc_attr__( 'width: ' ) . $progress_bar_template_1_width . $progress_bar_template_1_width_type . ' ;';
				} else {
					$css .= esc_attr__( 'width: 100%;' );
				}
				$css .= '}';
				$css .= self::add_inline_style(
					'.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-message',
					$i,
					array(
						'sale_countdown_progress_bar_template_1_message_color',
						'sale_countdown_progress_bar_template_1_font_size',
					),
					array( 'color', 'font-size' ),
					array( '', 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-wrap',
					$i,
					array(
						'sale_countdown_progress_bar_template_1_height',
						'sale_countdown_progress_bar_template_1_border_radius',
						'sale_countdown_progress_bar_template_1_background',
					),
					array( 'height', 'border-radius', 'background' ),
					array( 'px', 'px', '' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-fill',
					$i,
					array( 'sale_countdown_progress_bar_template_1_color', ),
					array( 'background' ),
					array( '' )
				);

				//sale countdown in single product
				$countdown_wrap_border_radius_in_single = self::$settings->get_current_countdown( 'sale_countdown_wrap_border_radius_in_single', $i );
				$countdown_wrap_border_color_in_single  = self::$settings->get_current_countdown( 'sale_countdown_wrap_border_color_in_single', $i );
				$css                                    .= '.woo-sctr-single-product-shortcode-' . $id . '{';
				if ( $countdown_wrap_border_radius_in_single ) {
					$css .= esc_attr__( 'border-radius: ' ) . $countdown_wrap_border_radius_in_single . 'px ;';
				}
				if ( $countdown_wrap_border_color_in_single ) {
					$css .= esc_attr__( 'border: 1px solid ' ) . $countdown_wrap_border_color_in_single . ' ;';
				}
				$css .= '}';
			}
		}

		return $css;
	}

	public static function get_backend_countdown_css() {
		self::$settings = new  VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$shortcode_ids  = self::$settings->get_params( 'sale_countdown_id' );
		$css            = '';
		if ( $shortcode_ids && is_array( $shortcode_ids ) && $count_ids = count( $shortcode_ids ) ) {
			for ( $i = 0; $i < $count_ids; $i ++ ) {
				if ( ! self::$settings->get_current_countdown( 'sale_countdown_active', $i ) ) {
					continue;
				}
				$id = $shortcode_ids[ $i ];

				$layout_1_border_color        = self::$settings->get_current_countdown( 'sale_countdown_layout_1_border_color',
					$i );
				$layout_1_sticky_border_color = self::$settings->get_current_countdown( 'sale_countdown_layout_1_sticky_border_color',
					$i );

				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-layout',
					$i,
					array( 'sale_countdown_layout_fontsize' ),
					array( 'font-size' ),
					array( 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1',
					$i,
					array(
						'sale_countdown_layout_1_background',
						'sale_countdown_layout_1_color',
						'sale_countdown_layout_1_border_radius',
						'sale_countdown_layout_1_padding',
					),
					array( 'background', 'color', 'border-radius', 'padding' ),
					array( '', '', 'px', 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1 .woo-sctr-countdown-timer-text-wrap',
					$i,
					array(
						'sale_countdown_layout_1_color',
					),
					array( 'color' ),
					array( '' )
				);
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1{';
				if ( $layout_1_border_color ) {
					$css .= esc_attr__( 'border: 1px solid ' ) . $layout_1_border_color . ';';
				}
				$css .= '}';

				/* sticky style */
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line',
					$i,
					array(
						'sale_countdown_layout_1_sticky_background',
					),
					array( 'background' ),
					array( '' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line .woo-sctr-countdown-timer-text-wrap',
					$i,
					array(
						'sale_countdown_layout_1_sticky_color',
					),
					array( 'color' ),
					array( '' )
				);
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-layout-1.woo-sctr-countdown-timer-layout-same-line{';
				if ( $layout_1_sticky_border_color ) {
					$css .= esc_attr__( 'border: 1px solid ' ) . $layout_1_sticky_border_color . ';';
				}
				$css .= '}';

				$template_2_item_border_color = self::$settings->get_current_countdown( 'sale_countdown_template_2_item_border_color', $i );
				$css                          .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit{';
				if ( $template_2_item_border_color ) {
					$css .= esc_attr__( 'border: 1px solid ' ) . $template_2_item_border_color . ';';
				}
				$css .= '}';
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-unit',
					$i,
					array(
						'sale_countdown_template_2_item_border_radius',
						'sale_countdown_template_2_item_height',
						'sale_countdown_template_2_item_width',
					),
					array( 'border-radius', 'height', 'width' ),
					array( 'px', 'px', 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-value',
					$i,
					array(
						'sale_countdown_template_2_value_color',
						'sale_countdown_template_2_value_background',
						'sale_countdown_template_2_value_fontsize',
					),
					array( 'color', 'background', 'font-size' ),
					array( '', '', 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-2 .woo-sctr-countdown-text',
					$i,
					array(
						'sale_countdown_template_2_time_unit_color',
						'sale_countdown_template_2_time_unit_background',
						'sale_countdown_template_2_time_unit_fontsize',
					),
					array( 'color', 'background', 'font-size' ),
					array( '', '', 'px' )
				);

				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-value',
					$i,
					array(
						'sale_countdown_template_3_value_color',
						'sale_countdown_template_3_value_background',
						'sale_countdown_template_3_value_fontsize',
					),
					array( 'color', 'background', 'font-size' ),
					array( '', '', 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-3 .woo-sctr-countdown-text',
					$i,
					array(
						'sale_countdown_template_3_time_unit_color',
						'sale_countdown_template_3_time_unit_background',
						'sale_countdown_template_3_time_unit_fontsize',
					),
					array( 'color', 'background', 'font-size' ),
					array( '', '', 'px' )
				);

				$template_4_value_diameter     = self::$settings->get_current_countdown( 'sale_countdown_template_4_value_diameter', $i );
				$template_4_value_border_width = self::$settings->get_current_countdown( 'sale_countdown_template_4_value_border_width', $i );
				$css                           .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container',
					$i,
					array(
						'sale_countdown_template_4_value_border_color1',
						'sale_countdown_template_4_value_color',
						'sale_countdown_template_4_value_fontsize',
					),
					array( 'background', 'color', 'font-size' ),
					array( '', '', 'px' )
				);
				$css                           .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container.woo-sctr-over50 .woo-sctr-first50-bar',
					$i,
					array( 'sale_countdown_template_4_value_border_color2' ),
					array( 'background-color' ),
					array( '' )
				);
				$css                           .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container .woo-sctr-value-bar',
					$i,
					array(
						'sale_countdown_template_4_value_border_color2',
						'sale_countdown_template_4_value_border_width',
					),
					array( 'border-color', 'border-width' ),
					array( '', 'px' )
				);
				$css                           .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after',
					$i,
					array( 'sale_countdown_template_4_value_background' ),
					array( 'background' ),
					array( '' )
				);
				$css                           .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-countdown-text',
					$i,
					array(
						'sale_countdown_template_4_time_unit_color',
						'sale_countdown_template_4_time_unit_background',
						'sale_countdown_template_4_time_unit_fontsize',
					),
					array( 'color', 'background', 'font-size' ),
					array( '', '', 'px' )
				);
				if ( $template_4_value_diameter ) {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container {';
					$css .= esc_attr__( 'width:' ) . $template_4_value_diameter . 'px;';
					$css .= esc_attr__( 'height:' ) . $template_4_value_diameter . 'px;';
					$css .= '}';
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-left-half-clipper,';
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-over50 .woo-sctr-first50-bar {';
					$css .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter . 'px,' . $template_4_value_diameter . 'px,' . $template_4_value_diameter / 2 . 'px);';
					$css .= '}';
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-4 .woo-sctr-value-bar {';
					$css .= esc_attr__( 'clip: rect(0,' ) . $template_4_value_diameter / 2 . 'px,' . $template_4_value_diameter . 'px,0);';
					$css .= '}';
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-4 .woo-sctr-countdown-value-circle-container:after {';
					if ( $template_4_value_border_width ) {
						$css .= esc_attr__( 'width:' ) . ( $template_4_value_diameter - 2 * $template_4_value_border_width ) . 'px;';
						$css .= esc_attr__( 'height:' ) . ( $template_4_value_diameter - 2 * $template_4_value_border_width ) . 'px;';
						$css .= esc_attr__( 'top:' ) . $template_4_value_border_width . 'px;';
						$css .= esc_attr__( 'left:' ) . $template_4_value_border_width . 'px;';
					}
					$css .= '}';
				}

				$template_5_value_fontsize    = self::$settings->get_current_countdown( 'sale_countdown_template_5_value_fontsize', $i );
				$template_5_item_diameter     = self::$settings->get_current_countdown( 'sale_countdown_template_5_item_diameter', $i );
				$template_5_item_border_width = self::$settings->get_current_countdown( 'sale_countdown_template_5_item_border_width', $i );
				$css                          .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container ',
					$i,
					array(
						'sale_countdown_template_5_item_diameter',
						'sale_countdown_template_5_item_diameter',
					),
					array( 'width', 'height' ),
					array( 'px', 'px' )
				);
				$css                          .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-countdown-circle',
					$i,
					array( 'sale_countdown_template_5_item_border_width' ),
					array( 'border-width' ),
					array( 'px' )
				);
				$css                          .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle .woo-sctr-countdown-value',
					$i,
					array(
						'sale_countdown_template_5_value_color',
						'sale_countdown_template_5_value_fontsize',
					),
					array( 'color', 'font-size' ),
					array( '', 'px' )
				);
				$css                          .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-text',
					$i,
					array(
						'sale_countdown_template_5_time_unit_color',
						'sale_countdown_template_5_time_unit_fontsize',
					),
					array( 'color', 'font-size' ),
					array( '', 'px' )
				);

				$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-circle-container:not(.woo-sctr-over50) .woo-sctr-left-half-clipper{';
				if ( $template_5_item_diameter ) {
					$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter . 'px,' . $template_5_item_diameter . 'px,' . $template_5_item_diameter / 2 . 'px)';
				}
				$css .= '}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5  .woo-sctr-value-bar {';
				if ( $template_5_item_diameter ) {
					$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter / 2 . 'px,' . $template_5_item_diameter . 'px,0);';
				}
				if ( $template_5_item_border_width ) {
					$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width . 'px;';
				}
				$css .= '}';
				$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-over50 .woo-sctr-first50-bar{';
				if ( $template_5_item_diameter ) {
					$css .= esc_attr__( 'clip: rect(0,' ) . $template_5_item_diameter . 'px,' . $template_5_item_diameter . 'px,' . $template_5_item_diameter / 2 . 'px);';
				}
				if ( $template_5_item_border_width ) {
					$css .= esc_attr__( 'border-width:' ) . $template_5_item_border_width . 'px;';
				}
				$css .= '}';
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-date .woo-sctr-countdown-circle',
					$i,
					array(
						'sale_countdown_template_5_date_border_color1',
						'sale_countdown_template_5_date_background',
					),
					array( 'border-color', 'background' ),
					array( '', '' )
				);
				$css .= self::add_inline_style(
					array(
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-date .woo-sctr-value-bar',
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-date.woo-sctr-over50 .woo-sctr-first50-bar'
					),
					$i,
					array( 'sale_countdown_template_5_date_border_color2' ),
					array( 'border-color' ),
					array( '' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-hour .woo-sctr-countdown-circle',
					$i,
					array(
						'sale_countdown_template_5_hour_border_color1',
						'sale_countdown_template_5_hour_background',
					),
					array( 'border-color', 'background' ),
					array( '', '' )
				);
				$css .= self::add_inline_style(
					array(
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-hour .woo-sctr-value-bar',
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-hour.woo-sctr-over50 .woo-sctr-first50-bar'
					),
					$i,
					array( 'sale_countdown_template_5_hour_border_color2' ),
					array( 'border-color' ),
					array( '' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute .woo-sctr-countdown-circle',
					$i,
					array(
						'sale_countdown_template_5_minute_border_color1',
						'sale_countdown_template_5_minute_background',
					),
					array( 'border-color', 'background' ),
					array( '', '' )
				);
				$css .= self::add_inline_style(
					array(
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute .woo-sctr-value-bar',
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-minute.woo-sctr-over50 .woo-sctr-first50-bar'
					),
					$i,
					array( 'sale_countdown_template_5_minute_border_color2' ),
					array( 'border-color' ),
					array( '' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-second .woo-sctr-countdown-circle',
					$i,
					array(
						'sale_countdown_template_5_second_border_color1',
						'sale_countdown_template_5_second_background',
					),
					array( 'border-color', 'background' ),
					array( '', '' )
				);
				$css .= self::add_inline_style(
					array(
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-second .woo-sctr-value-bar',
						'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-5 .woo-sctr-countdown-second.woo-sctr-over50 .woo-sctr-first50-bar'
					),
					$i,
					array( 'sale_countdown_template_5_second_border_color2' ),
					array( 'border-color' ),
					array( '' )
				);

				$template_6_value_box_shadow    = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_box_shadow',
					$i );
				$template_6_value_cut_behind    = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_cut_behind',
					$i );
				$template_6_value_cut_color     = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_cut_color',
					$i );
				$template_6_value_border_radius = self::$settings->get_current_countdown( 'sale_countdown_template_6_value_border_radius',
					$i );
				$css                            .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-unit-two-vertical-wrap',
					$i,
					array( 'sale_countdown_template_6_time_unit_grid_gap' ),
					array( 'grid-gap' ),
					array( 'px' )
				);
				$css                            .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap',
					$i,
					array(
						'sale_countdown_template_6_value_width',
						'sale_countdown_template_6_value_height',
						'sale_countdown_template_6_value_border_radius',
					),
					array( 'width', 'height', 'border-radius' ),
					array( 'px', 'px', 'px' )
				);
				$css                            .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap{';
				if ( $template_6_value_box_shadow ) {
					$css .= esc_attr__( 'box-shadow: 0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08);' );
				} else {
					$css .= esc_attr__( 'box-shadow: unset;' );
				}
				$css .= '}';
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6 .woo-sctr-countdown-two-vertical-wrap  span',
					$i,
					array( 'sale_countdown_template_6_value_fontsize' ),
					array( 'font-size' ),
					array( 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6  .woo-sctr-countdown-two-vertical-top',
					$i,
					array(
						'sale_countdown_template_6_value_color1',
						'sale_countdown_template_6_value_background1',
					),
					array( 'color', 'background' ),
					array( '', '' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6   .woo-sctr-countdown-two-vertical-bottom',
					$i,
					array(
						'sale_countdown_template_6_value_color2',
						'sale_countdown_template_6_value_background2',
					),
					array( 'color', 'background' ),
					array( '', '' )
				);
				if ( ! $template_6_value_cut_behind ) {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6  .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default{';

					if ( $template_6_value_cut_color ) {
						$css .= esc_attr__( 'border-bottom: 1px solid ' ) . $template_6_value_cut_color . ';';
					}
					$css .= '}';
				} else {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-6  .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind::after{';
					if ( $template_6_value_cut_color ) {
						$css .= esc_attr__( 'border-bottom: 1px solid ' ) . woo_ctr_hextorgba( $template_6_value_cut_color,
								1 ) . ';';
					}
					$css .= '}';
				}
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . '  .woo-sctr-countdown-timer-6  .woo-sctr-countdown-text',
					$i,
					array(
						'sale_countdown_template_6_time_unit_color',
						'sale_countdown_template_6_time_unit_fontsize',
					),
					array( 'color', 'font-size' ),
					array( '', 'px' )
				);

				$template_7_value_box_shadow    = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_box_shadow', $i );
				$template_7_value_cut_behind    = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_cut_behind', $i );
				$template_7_value_cut_color     = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_cut_color', $i );
				$template_7_value_border_radius = self::$settings->get_current_countdown( 'sale_countdown_template_7_value_border_radius', $i );
				$css                            .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-unit-two-vertical-wrap',
					$i,
					array( 'sale_countdown_template_7_time_unit_grid_gap' ),
					array( 'grid-gap' ),
					array( 'px' )
				);
				$css                            .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap',
					$i,
					array(
						'sale_countdown_template_7_value_width',
						'sale_countdown_template_7_value_height',
						'sale_countdown_template_7_value_border_radius',
					),
					array( 'width', 'height', 'border-radius' ),
					array( 'px', 'px', 'px' )
				);
				$css                            .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap{';
				if ( $template_7_value_box_shadow ) {
					$css .= esc_attr__( 'box-shadow: 0 3px 4px 0 rgba(0,0,0, 0.15), inset 2px 4px 0 0 rgba(255,255, 255, 0.08) ;' );
				} else {
					$css .= esc_attr__( 'box-shadow: unset ;' );
				}
				$css .= '}';
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-wrap span',
					$i,
					array( 'sale_countdown_template_7_value_fontsize' ),
					array( 'font-size' ),
					array( 'px' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-two-vertical-top',
					$i,
					array(
						'sale_countdown_template_7_value_color1',
						'sale_countdown_template_7_value_background1',
					),
					array( 'color', 'background' ),
					array( '', '' )
				);
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-two-vertical-bottom',
					$i,
					array(
						'sale_countdown_template_7_value_color2',
						'sale_countdown_template_7_value_background2',
					),
					array( 'color', 'background' ),
					array( '', '' )
				);
				if ( ! $template_7_value_cut_behind ) {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-default{';

					if ( $template_7_value_cut_color ) {
						$css .= esc_attr__( 'border-bottom: 1px solid ' ) . $template_7_value_cut_color . ';';
					}
					$css .= '}';
				} else {
					$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7 .woo-sctr-countdown-two-vertical-top.woo-sctr-countdown-two-vertical-top-cut-behind::after{';
					if ( $template_7_value_cut_color ) {
						$css .= esc_attr__( 'border-bottom: 1px solid ' ) . $template_7_value_cut_color . ';';
					}
					$css .= '}';
				}
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-7  .woo-sctr-countdown-text',
					$i,
					array(
						'sale_countdown_template_7_time_unit_color',
						'sale_countdown_template_7_time_unit_fontsize',
					),
					array( 'color', 'font-size' ),
					array( '', 'px' )
				);

				$template_1_value_border_color = self::$settings->get_current_countdown( 'sale_countdown_template_1_value_border_color', $i );
				$css                           .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-value',
					$i,
					array(
						'sale_countdown_template_1_value_color',
						'sale_countdown_template_1_value_background',
						'sale_countdown_template_1_value_border_radius',
						'sale_countdown_template_1_value_width',
						'sale_countdown_template_1_value_height',
						'sale_countdown_template_1_value_font_size',
					),
					array( 'color', 'background', 'border-radius', 'width', 'height', 'font-size' ),
					array( '', '', 'px', 'px', 'px', 'px' )
				);

				$css .= '.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-value{';
				if ( $template_1_value_border_color ) {
					$css .= esc_attr__( 'border: 1px solid ' ) . $template_1_value_border_color . ';';
				}
				$css .= '}';
				$css .= self::add_inline_style(
					'.woo-sctr-shortcode-countdown-timer-wrap.woo-sctr-shortcode-countdown-timer-wrap-shortcode-' . $id . ' .woo-sctr-countdown-timer-1 .woo-sctr-countdown-text',
					$i,
					array(
						'sale_countdown_template_1_time_unit_color',
						'sale_countdown_template_1_time_unit_background',
						'sale_countdown_template_1_time_unit_fontsize',
					),
					array( 'color', 'background', 'font-size' ),
					array( '', '', 'px' )
				);
				//progress bar css
				$css .= self::add_inline_style(
					'.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-message',
					$i,
					array(
						'sale_countdown_progress_bar_template_1_message_color',
						'sale_countdown_progress_bar_template_1_font_size',
					),
					array( 'color', 'font-size' ),
					array( '', 'px' )
				);

				$progress_bar_template_1_width      = self::$settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_width', $i );
				$progress_bar_template_1_width_type = self::$settings->get_current_countdown( 'sale_countdown_progress_bar_template_1_width_type', $i );
				$css                                .= '.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-wrap{';
				if ( $progress_bar_template_1_width ) {
					$css .= esc_attr__( 'width: ' ) . $progress_bar_template_1_width . $progress_bar_template_1_width_type . ' ;';
				} else {
					$css .= esc_attr__( 'width: 100%;' );
				}
				$css               .= '}';
				$css               .= self::add_inline_style(
					'.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-wrap',
					$i,
					array(
						'sale_countdown_progress_bar_template_1_height',
						'sale_countdown_progress_bar_template_1_border_radius',
						'sale_countdown_progress_bar_template_1_background',
					),
					array( 'height', 'border-radius', 'background' ),
					array( 'px', 'px', '' )
				);
				$css               .= self::add_inline_style(
					'.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-fill',
					$i,
					array( 'sale_countdown_progress_bar_template_1_color', ),
					array( 'background' ),
					array( '' )
				);
				$progress_bar_type = self::$settings->get_current_countdown( 'sale_countdown_progress_bar_type', $i );
				$css               .= '.woo-sctr-progress-bar-wrap-container.woo-sctr-progress-bar-wrap-container-shortcode-' . $id . ' .woo-sctr-progress-bar-fill{';
				if ( $progress_bar_type === 'decrease' ) {
					$css .= 'width:80%;';
				} else {
					$css .= 'width:20%;';
				}
				$css .= '}';
			}
		}

		return $css;
	}

	private static function add_inline_style( $element, $i, $name, $style, $suffix = '' ) {
		$element = is_array( $element ) ? implode( ',', $element ) : $element;
		$return  = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$get_value  = self::$settings->get_current_countdown( $name[ $key ], $i );
				$get_suffix = isset( $suffix[ $key ] ) ? $suffix[ $key ] : '';
				if ( $get_value ) {
					$return .= $style[ $key ] . ':' . $get_value . $get_suffix . ';';
				}
			}
		}
		$return .= '}';

		return $return;
	}

	private static function add_inline_style_reduce( $element, $i, $name, $style, $suffix = '', $reduce = 0, $default = 0 ) {
		$element = is_array( $element ) ? implode( ',', $element ) : $element;
		$return  = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$get_value = self::$settings->get_current_countdown( $name[ $key ], $i );
				if ( $reduce > 0 && $get_value ) {
					if ( $default > 0 ) {
						$get_value = $get_value * $default / 100;
					}
					$get_value = $get_value * $reduce / 100;
				}
				$return .= $style[ $key ] . ':' . $get_value . $suffix[ $key ] . ';';
			}
		}
		$return .= '}';

		return $return;
	}
}