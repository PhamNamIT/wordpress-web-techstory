<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$countdown_template_class .= $countdown_template_class ? ' woo-sctr-countdown-timer-circle':'woo-sctr-countdown-timer-circle';
?>
<div class="<?php echo esc_attr($countdown_template_class); ?>">
	<div class="<?php echo esc_attr($unit_day_class); ?>">
		<span class="woo-sctr-countdown-date woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $day_deg > 180 ? esc_attr(' woo-sctr-over50') : ''; ?>">
			<?php
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
						<span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1">
								<?php echo $day_left > 0 ? esc_html(zeroise($day_left -1, 2)) : esc_html('00'); ?>
							</span>
							<span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($day_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-countdown-date-text woo-sctr-countdown-text"><?php echo esc_html($day); ?></span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo esc_html($day_left_t); ?>
					</span>
					<span class="woo-sctr-countdown-date-text woo-sctr-countdown-text"><?php echo esc_html($day); ?></span>
				</span>
				<?php
			}
			?>
			<span class="woo-sctr-left-half-clipper">
				<span class="woo-sctr-first50-bar<?php echo $day_deg > 180 ? '': esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
				<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr( $day_deg ); ?>"></span>
			</span>
		</span>
		<?php
		if (strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' )){
			echo sprintf('<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr($time_separator));
		}
		?>
	</div>
	<div class="<?php echo esc_attr($unit_hour_class); ?>">
		<span class="woo-sctr-countdown-hour woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $hour_deg > 180 ? esc_attr(' woo-sctr-over50') : ''; ?>">
			<?php
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
						<span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1">
								<?php echo $hour_left > 0 ? esc_html(zeroise($hour_left -1, 2)) : esc_html('00'); ?>
							</span>
							<span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($hour_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text"><?php echo esc_html($hour); ?></span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo esc_html($hour_left_t); ?>
					</span>
					<span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text"><?php echo esc_html($hour); ?></span>
				</span>
				<?php
			}
			?>
			<span class="woo-sctr-left-half-clipper">
				<span class="woo-sctr-first50-bar<?php echo $hour_deg > 180 ? '': esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
				<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr( $hour_deg ); ?>"></span>
			</span>
		</span>
		<?php
		if (strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' )){
			echo sprintf('<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr($time_separator));
		}
		?>
	</div>
	<div class="<?php echo esc_attr($unit_minute_class); ?>">
		<span class="woo-sctr-countdown-minute woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $min_deg > 180 ? esc_attr(' woo-sctr-over50') : ''; ?>">
			<?php
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
						<span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1">
								<?php echo $min_left > 0 ? esc_html(zeroise($min_left -1, 2)) : esc_html('59'); ?>
							</span>
							<span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($hour_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text"><?php echo esc_html($minute); ?></span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo esc_html($min_left_t); ?>
					</span>
					<span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text"><?php echo esc_html($minute); ?></span>
				</span>
				<?php
			}
			?>
			<span class="woo-sctr-left-half-clipper">
				<span class="woo-sctr-first50-bar<?php echo $min_deg > 180 ? '': esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
				<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr( $min_deg ); ?>"></span>
			</span>
		</span>
		<?php
		if (strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' )){
			echo sprintf('<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr($time_separator));
		}
		?>
	</div>
	<div class="<?php echo esc_attr($unit_second_class); ?>">
		<span class="woo-sctr-countdown-second woo-sctr-countdown-unit woo-sctr-countdown-circle-container<?php echo $sec_deg > 180 ? esc_attr(' woo-sctr-over50') : ''; ?>">
			<?php
			switch ($animation_style){
				case 'slide':
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
						<span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
							<span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1">
								<?php echo $sec_left > 0 ? esc_html(zeroise($sec_left -1, 2)) : esc_html('59'); ?>
							</span>
							<span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo esc_html($sec_left_t); ?></span>
						</span>
					</span>
					<span class="woo-sctr-countdown-second-text woo-sctr-countdown-text"><?php echo esc_html($second); ?></span>
				</span>
					<?php
					break;
				default:
					?>
				<span class="woo-sctr-countdown-circle">
					<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">
						<?php echo esc_html($sec_left_t); ?>
					</span>
					<span class="woo-sctr-countdown-second-text woo-sctr-countdown-text"><?php echo esc_html($second); ?></span>
				</span>
				<?php
			}
			?>
			<span class="woo-sctr-left-half-clipper">
				<span class="woo-sctr-first50-bar<?php echo $sec_deg > 180 ? '': esc_attr(' woo-sctr-countdown-hidden'); ?>"></span>
				<span class="woo-sctr-value-bar" data-deg="<?php echo esc_attr( $sec_deg ); ?>"></span>
			</span>
		</span>
	</div>
</div>
