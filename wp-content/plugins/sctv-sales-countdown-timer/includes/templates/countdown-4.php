<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$settings                      = $settings ?? new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
$template_4_time_unit_position = $settings->get_current_countdown( 'sale_countdown_template_4_time_unit_position', $index );
$countdown_template_class .= $countdown_template_class ? ' woo-sctr-countdown-timer-circle':'woo-sctr-countdown-timer-circle';
$unit_pos_is_top = $template_4_time_unit_position === 'top';
$unit_pos_top_class = $unit_pos_is_top ? '': ' woo-sctr-countdown-hidden';
$unit_pos_bottom_class = !$unit_pos_is_top ? '': ' woo-sctr-countdown-hidden';
?>
<div class="<?php echo esc_attr( $countdown_template_class ); ?>">
	<div class="<?php echo esc_attr( $unit_day_class ); ?>">
		<span class="woo-sctr-countdown-date woo-sctr-countdown-unit">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-date-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($day));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $day_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value  woo-sctr-countdown-value-circle">
						<span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1">
								<?php echo $day_left > 0 ? esc_html( zeroise( $day_left - 1, 2 ) ) : esc_html( '00' ); ?>
							</span>
							<span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($day_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $day_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($day_deg); ?>"></span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-date-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $day_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-value-circle woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo  esc_html($day_left_t); ?>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $day_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($day_deg); ?>"></span>
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
	<div class="<?php echo esc_attr( $unit_hour_class ); ?>">
		<span class="woo-sctr-countdown-hour woo-sctr-countdown-unit">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($hour));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $hour_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value  woo-sctr-countdown-value-circle">
						<span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1">
								<?php echo $hour_left > 0 ? esc_html( zeroise( $hour_left - 1, 2 ) ) : esc_html( '00' ); ?>
							</span>
							<span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($hour_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $hour_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($hour_deg); ?>"></span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-hour-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $hour_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-value-circle woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo  esc_html($hour_left_t); ?>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $hour_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($hour_deg); ?>"></span>
					</span>
				</span>
				<?php
			}
			if (!$unit_pos_is_top || $is_preview){
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
	<div class="<?php echo esc_attr( $unit_minute_class ); ?>">
		<span class="woo-sctr-countdown-minute woo-sctr-countdown-unit">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($minute));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $min_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value  woo-sctr-countdown-value-circle">
						<span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1">
								<?php echo $min_left > 0 ? esc_html( zeroise( $min_left - 1, 2 ) ) : esc_html( '59' ); ?>
							</span>
							<span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($min_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $min_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($min_deg); ?>"></span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-minute-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $min_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-value-circle woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo  esc_html($min_left_t); ?>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $min_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($min_deg); ?>"></span>
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
	<div class="<?php echo esc_attr( $unit_second_class ); ?>">
		<span class="woo-sctr-countdown-second woo-sctr-countdown-unit">
			<?php
			if ($unit_pos_is_top|| $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-top%s">%s</span>',
                    esc_attr($unit_pos_top_class),esc_attr($second));
			}
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $sec_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-circle">
						<span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1">
								<?php echo $sec_left > 0 ? esc_html( zeroise( $sec_left - 1, 2 ) ) : esc_html( '59' ); ?>
							</span>
							<span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($sec_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $sec_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($sec_deg); ?>"></span>
					</span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-second-value-wrap woo-sctr-countdown-value-wrap woo-sctr-countdown-value-circle-container<?php echo $sec_deg > 180 ? esc_attr(' woo-sctr-over50'):''; ?>">
					<span class="woo-sctr-countdown-value-circle woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo  esc_html($sec_left_t); ?>
					</span>
					<span class="woo-sctr-left-half-clipper">
						<span class="woo-sctr-first50-bar<?php echo $sec_deg > 180 ?'':esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
						<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr($sec_deg); ?>"></span>
					</span>
				</span>
				<?php
			}
			if (!$unit_pos_is_top || $is_preview){
				echo sprintf('<span class="woo-sctr-countdown-second-text woo-sctr-countdown-text woo-sctr-datetime-format-position-bottom%s">%s</span>',
                    esc_attr($unit_pos_bottom_class),esc_attr($second));
			}
			?>
		</span>
	</div>
</div>
