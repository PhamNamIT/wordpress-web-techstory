<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings                      = $settings ?? new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
$template_7_time_unit_position = $settings->get_current_countdown( 'sale_countdown_template_7_time_unit_position', $index );
$template_7_value_cut_behind   = $settings->get_current_countdown( 'sale_countdown_template_7_value_cut_behind', $index );
$unit_value_cut_class =$template_7_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default';
$countdown_template_class .= $countdown_template_class ? ' woo-sctr-countdown-timer-circle':'woo-sctr-countdown-timer-circle';
$day_left_t_arg = str_split( $day_left_t );
$hour_left_t_arg = str_split( $hour_left_t );
$min_left_t_arg = str_split( $min_left_t );
$sec_left_t_arg = str_split( $sec_left_t );
list( $day_left_t1, $day_left_t2 ) = array_map( 'intval', $day_left_t_arg );
list( $hour_left_t1, $hour_left_t2 ) = array_map( 'intval', $hour_left_t_arg );
list( $min_left_t1, $min_left_t2 ) = array_map( 'intval', $min_left_t_arg );
list( $sec_left_t1, $sec_left_t2 ) = array_map( 'intval', $sec_left_t_arg );
$unit_pos_is_top = $template_7_value_cut_behind === 'top';
$unit_pos_top_class = $unit_pos_is_top ? '': ' woo-sctr-countdown-hidden';
$unit_pos_bottom_class = !$unit_pos_is_top ? '': ' woo-sctr-countdown-hidden';
?>
<div class="<?php echo esc_attr($countdown_template_class); ?>">
	<div class="<?php echo esc_attr($unit_day_class); ?>">
		<span class="woo-sctr-countdown-date woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($day));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-date-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
					<span class="woo-sctr-countdown-date-1-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-date-1-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $day_left_t1 > 0 ? $day_left_t1 - 1 : 0 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($day_left_t1 > 0 ? $day_left_t1 - 1 : 0); ?></span>
							</span>
							<span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $day_left_t1 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($day_left_t1); ?></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-date-2-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-date-2-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $day_left_t2 > 0 ? $day_left_t2 - 1 : 9 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($day_left_t2 > 0 ? $day_left_t2 - 1 : 9); ?></span>
							</span>
							<span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $day_left_t2 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($day_left_t2); ?></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-date-wrap-wrap woo-sctr-countdown-value-wrap-wrap<?php echo esc_attr($template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''); ?>">
					<span class="woo-sctr-countdown-date-1-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $day_left_t1 ); ?>"></span>
							<span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $day_left_t1 < 9 ? $day_left_t1 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $day_left_t1 < 9 ? $day_left_t1 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $day_left_t1 ); ?>"></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-date-2-wrap woo-sctr-countdown-flip-wrap woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $day_left_t2 ); ?>"></span>
							<span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $day_left_t2 < 9 ? $day_left_t2 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $day_left_t2 < 9 ? $day_left_t2 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $day_left_t2 ); ?>"></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-date-value-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
				<span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr($day_left_t1); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($day_left_t1); ?></span>
				</span>
				<span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $day_left_t2 ); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($day_left_t2); ?></span>
				</span>
			</span>
			<?php
			}
			if (!$unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom%s">%s</span>',
                    esc_attr($unit_pos_bottom_class),esc_attr($day));
			}
			?>
		</span>
		<?php
		if (strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' )){
			echo sprintf('<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr($time_separator));
		}
		?>
	</div>
	<div class="<?php echo esc_attr($unit_hour_class); ?>">
		<span class="woo-sctr-countdown-hour woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($hour));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-hour-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
					<span class="woo-sctr-countdown-hour-1-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-hour-1-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $hour_left_t1 > 0 ? $hour_left_t1 - 1 : 0 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($hour_left_t1 > 0 ? $hour_left_t1 - 1 : 0); ?></span>
							</span>
							<span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $hour_left_t1 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($hour_left_t1); ?></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-hour-2-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-hour-2-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $hour_left_t2 > 0 ? $hour_left_t2 - 1 : 9 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($hour_left_t2 > 0 ? $hour_left_t2 - 1 : 9); ?></span>
							</span>
							<span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $hour_left_t2 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($hour_left_t2); ?></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-hour-wrap-wrap woo-sctr-countdown-value-wrap-wrap<?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
					<span class="woo-sctr-countdown-hour-1-wrap woo-sctr-countdown-flip-wrap woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $hour_left_t1 ); ?>"></span>
							<span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $hour_left_t1 < 9 ? $hour_left_t1 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $hour_left_t1 < 9 ? $hour_left_t1 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $hour_left_t1 ); ?>"></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-hour-2-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $hour_left_t2 ); ?>"></span>
							<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $hour_left_t2 < 9 ? $hour_left_t2 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $hour_left_t2 < 9 ? $hour_left_t2 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $hour_left_t2 ); ?>"></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-hour-value-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
				<span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr($hour_left_t1); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($hour_left_t1); ?></span>
				</span>
				<span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $hour_left_t2 ); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($hour_left_t2); ?></span>
				</span>
			</span>
			<?php
			}
			if (!$unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom%s">%s</span>',
                    esc_attr($unit_pos_bottom_class),esc_attr($hour));
			}
			?>
		</span>
		<?php
		if (strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' )){
			echo sprintf('<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr($time_separator));
		}
		?>
	</div>
	<div class="<?php echo esc_attr($unit_minute_class); ?>">
		<span class="woo-sctr-countdown-minute woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($minute));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-minute-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
					<span class="woo-sctr-countdown-minute-1-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-minute-1-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $min_left_t1 > 0 ? $min_left_t1 - 1 : 5 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($min_left_t1 > 0 ? $min_left_t1 - 1 : 5); ?></span>
							</span>
							<span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $min_left_t1 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($min_left_t1); ?></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-minute-2-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-minute-2-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $min_left_t2 > 0 ? $min_left_t2 - 1 : 9 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($min_left_t2 > 0 ? $min_left_t2 - 1 : 9); ?></span>
							</span>
							<span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $min_left_t2 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($min_left_t2); ?></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-minute-wrap-wrap woo-sctr-countdown-value-wrap-wrap<?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
					<span class="woo-sctr-countdown-minute-1-wrap woo-sctr-countdown-flip-wrap woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $min_left_t1 ); ?>"></span>
							<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $min_left_t1 < 5 ? $min_left_t1 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $min_left_t1 < 5 ? $min_left_t1 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $min_left_t1 ); ?>"></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-minute-2-wrap woo-sctr-countdown-flip-wrap woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $min_left_t2 ); ?>"></span>
							<span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $min_left_t2 < 9 ? $min_left_t2 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $min_left_t2 < 9 ? $min_left_t2 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $min_left_t2 ); ?>"></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-minute-value-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
				<span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr($min_left_t1); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($min_left_t1); ?></span>
				</span>
				<span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $min_left_t2 ); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($min_left_t2); ?></span>
				</span>
			</span>
			<?php
			}
			if (!$unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom%s">%s</span>',
                    esc_attr($unit_pos_bottom_class),esc_attr($minute));
			}
			?>
		</span>
		<?php
		if (strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' )){
			echo sprintf('<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr($time_separator));
		}
		?>
	</div>
	<div class="<?php echo esc_attr($unit_second_class); ?>">
		<span class="woo-sctr-countdown-second woo-sctr-countdown-unit woo-sctr-countdown-unit-two-vertical-wrap">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($second));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-second-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
					<span class="woo-sctr-countdown-second-1-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-second-1-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $sec_left_t1 > 0 ? $sec_left_t1 - 1 : 5 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($sec_left_t1 > 0 ? $sec_left_t1 - 1 : 5); ?></span>
							</span>
							<span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $sec_left_t1 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($sec_left_t1); ?></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-second-2-wrap woo-sctr-countdown-value-wrap">
						<span class="woo-sctr-countdown-second-2-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
								      data-value="<?php echo esc_attr( $sec_left_t2 > 0 ? $sec_left_t2 - 1 : 9 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom" ><?php echo esc_html($sec_left_t2 > 0 ? $sec_left_t2 - 1 : 9); ?></span>
							</span>
							<span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
								<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $sec_left_t2 ); ?>"></span>
								<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($sec_left_t2); ?></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-second-wrap-wrap woo-sctr-countdown-value-wrap-wrap<?php echo $template_7_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
					<span class="woo-sctr-countdown-second-1-wrap  woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $sec_left_t1 ); ?>"></span>
							<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $sec_left_t1 < 5 ? $sec_left_t1 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $sec_left_t1 < 5 ? $sec_left_t1 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $sec_left_t1 ); ?>"></span>
							</span>
						</span>
					</span>
					<span class="woo-sctr-countdown-second-2-wrap woo-sctr-countdown-flip-wrap woo-sctr-countdown-flip-active">
						<span class="woo-sctr-countdown-flip-card">
							<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-top" data-value="<?php echo esc_attr( $sec_left_t2 ); ?>"></span>
							<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $sec_left_t2 < 9 ? $sec_left_t2 + 1 : 0 ); ?>"></span>
							<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-back" data-value="<?php echo esc_attr( $sec_left_t2 < 9 ? $sec_left_t2 + 1 : 0 ); ?>">
								<span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-bottom" data-value="<?php echo esc_attr( $sec_left_t2 ); ?>"></span>
							</span>
						</span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-second-wrap-wrap woo-sctr-countdown-value-wrap-wrap">
				<span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr($sec_left_t1); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($sec_left_t1); ?></span>
				</span>
				<span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 woo-sctr-countdown-two-vertical-wrap">
					<span class="woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>" data-value="<?php echo esc_attr( $sec_left_t2 ); ?>"></span>
					<span class="woo-sctr-countdown-two-vertical-bottom"><?php echo esc_html($sec_left_t2); ?></span>
				</span>
			</span>
			<?php
			}
			if (!$unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom%s">%s</span>',
                    esc_attr($unit_pos_bottom_class),esc_attr($second));
			}
			?>
		</span>
	</div>
</div>
