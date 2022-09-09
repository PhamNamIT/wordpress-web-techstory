<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings                      = $settings ?? new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
$template_6_time_unit_position = $settings->get_current_countdown( 'sale_countdown_template_6_time_unit_position', $index );
$template_6_value_cut_behind   = $settings->get_current_countdown( 'sale_countdown_template_6_value_cut_behind', $index );
$unit_value_cut_class = $template_6_value_cut_behind ? 'woo-sctr-countdown-two-vertical-top-cut-behind' : 'woo-sctr-countdown-two-vertical-top-cut-default';
$countdown_template_class .= $countdown_template_class ? ' woo-sctr-countdown-timer-circle':'woo-sctr-countdown-timer-circle';
$unit_pos_is_top = $template_6_value_cut_behind === 'top';
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
				<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $day_left > 0 ? zeroise( $day_left - 1, 2 ) : '00' ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1">
								<?php echo $day_left > 0 ? esc_html(zeroise( $day_left - 1, 2 )) : esc_html('00'); ?>
							</span>
						</span>
						<span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $day_left_t ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2">
								<?php echo esc_html($day_left_t);?>
							</span>
						</span>
					</span>
				</span>
				<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                    <span class="woo-sctr-countdown-flip-card">
                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-top" data-value="<?php echo  esc_attr($day_left_t); ?>"></span>
                        <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr(zeroise( $day_left_t + 1, 2 )); ?>"></span>
                        <span class="woo-sctr-countdown-date-value woo-sctr-countdown-flip-back" data-value="<?php echo  esc_attr(zeroise( $day_left_t + 1, 2 )); ?>">
                            <span class="woo-sctr-countdown-date-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr($day_left_t); ?>"></span>
                        </span>
                    </span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap  woo-sctr-countdown-two-vertical-wrap">
				<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
				      data-value="<?php echo esc_attr($day_left_t); ?>">
				</span>
				<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom">
					<?php echo esc_html($day_left_t); ?>
				</span>
			</span>
			<?php
			}
			if (!$unit_pos_is_top || $is_preview){
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
				<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $hour_left > 0 ? zeroise( $hour_left - 1, 2 ) : '00' ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1">
								<?php echo $hour_left > 0 ? esc_html(zeroise( $hour_left - 1, 2 )) : esc_html('00'); ?>
							</span>
						</span>
						<span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $hour_left_t ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2">
								<?php echo esc_html($hour_left_t);?>
							</span>
						</span>
					</span>
				</span>
				<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap  woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                    <span class="woo-sctr-countdown-flip-card">
                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-top" data-value="<?php echo  esc_attr($hour_left_t); ?>"></span>
                        <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr(zeroise( $hour_left_t + 1, 2 )); ?>"></span>
                        <span class="woo-sctr-countdown-hour-value woo-sctr-countdown-flip-back" data-value="<?php echo  esc_attr(zeroise( $hour_left_t + 1, 2 )); ?>">
                            <span class="woo-sctr-countdown-hour-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr($hour_left_t); ?>"></span>
                        </span>
                    </span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-two-vertical-wrap">
				<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
				      data-value="<?php echo esc_attr($hour_left_t); ?>"></span>
				<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom">
					<?php echo esc_html($hour_left_t); ?>
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
				<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $min_left > 0 ? zeroise( $min_left - 1, 2 ) : '59' ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1">
								<?php echo $min_left > 0 ? esc_html(zeroise( $min_left - 1, 2 )) : esc_html('59'); ?>
							</span>
						</span>
						<span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $min_left_t ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2">
								<?php echo esc_html($min_left_t);?>
							</span>
						</span>
					</span>
				</span>
				<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                    <span class="woo-sctr-countdown-flip-card">
                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-top" data-value="<?php echo  esc_attr($min_left_t); ?>"></span>
                        <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr(zeroise( $min_left_t + 1, 2 )); ?>"></span>
                        <span class="woo-sctr-countdown-minute-value woo-sctr-countdown-flip-back" data-value="<?php echo  esc_attr(zeroise( $min_left_t + 1, 2 )); ?>">
                            <span class="woo-sctr-countdown-minute-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr($min_left_t); ?>"></span>
                        </span>
                    </span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap  woo-sctr-countdown-two-vertical-wrap">
				<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
				      data-value="<?php echo esc_attr($min_left_t); ?>"></span>
				<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom">
					<?php echo esc_html($min_left_t); ?>
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
				<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-value-1-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $sec_left > 0 ? zeroise( $sec_left - 1, 2 ) : '59' ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1">
								<?php echo $sec_left > 0 ? esc_html(zeroise( $sec_left - 1, 2 )) : esc_html('59'); ?>
							</span>
						</span>
						<span class="woo-sctr-countdown-value-2-wrap woo-sctr-countdown-two-vertical-wrap">
							<span class="woo-sctr-countdown-two-vertical-top woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2 <?php echo esc_attr($unit_value_cut_class); ?>"
							      data-value="<?php echo esc_attr( $sec_left_t ); ?>"></span>
							<span class="woo-sctr-countdown-two-vertical-bottom woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2">
								<?php echo esc_html($sec_left_t);?>
							</span>
						</span>
					</span>
				</span>
				<?php
					break;
				case 'flip':
					?>
				<span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-flip-wrap woo-sctr-countdown-flip-active<?php echo $template_6_value_cut_behind ? ' woo-sctr-countdown-two-vertical-top-cut-behind' : ''; ?>">
                    <span class="woo-sctr-countdown-flip-card">
                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-top" data-value="<?php echo  esc_attr($sec_left_t); ?>"></span>
                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr(zeroise( $sec_left_t + 1, 2 )); ?>"></span>
                        <span class="woo-sctr-countdown-second-value woo-sctr-countdown-flip-back" data-value="<?php echo  esc_attr(zeroise( $sec_left_t + 1, 2 )); ?>">
                            <span class="woo-sctr-countdown-second-value  woo-sctr-countdown-flip-bottom" data-value="<?php echo  esc_attr($sec_left_t); ?>"></span>
                        </span>
                    </span>
				</span>
					<?php
					break;
				default:
					?>
			<span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-two-vertical-wrap">
				<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-top <?php echo esc_attr($unit_value_cut_class); ?>"
				      data-value="<?php echo esc_attr($sec_left_t); ?>"></span>
				<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default woo-sctr-countdown-two-vertical-bottom">
					<?php echo esc_html($sec_left_t); ?>
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
