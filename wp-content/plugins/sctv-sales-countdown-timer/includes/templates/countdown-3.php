<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="<?php echo esc_attr( $countdown_template_class ); ?>">
    <div class="<?php echo esc_attr( $unit_day_class ); ?>">
		<span class="woo-sctr-countdown-date woo-sctr-countdown-unit">
			<?php
			switch ( $animation_style ) {
				case 'slide':
					?>
				<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-value-disabled"><?php echo esc_html( $day_left_t ); ?></span>
					<span class="woo-sctr-countdown-date-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-date-value-1 woo-sctr-countdown-value-1">
							<?php echo $day_left > 0 ? esc_html( zeroise( $day_left - 1, 2 ) ) : esc_html( '00' ); ?>
						</span>
						<span class="woo-sctr-countdown-date-value-2 woo-sctr-countdown-value-2"><?php echo esc_html( $day_left_t ); ?></span>
					</span>
				</span>
					<?php
					break;
				default:
					echo sprintf( '<span class="woo-sctr-countdown-date-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">%s</span>', esc_attr( $day_left_t ) );
			}
			?>
			<span class="woo-sctr-countdown-date-text woo-sctr-countdown-text"><?php echo esc_html( $day ); ?></span>
		</span>
		<?php
		if ( strpos( $unit_day_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
			echo sprintf( '<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr( $time_separator ) );
		}
		?>
    </div>
    <div class="<?php echo esc_attr( $unit_hour_class ); ?>">
		<span class="woo-sctr-countdown-hour woo-sctr-countdown-unit">
			<?php
			switch ( $animation_style ) {
				case 'slide':
					?>
				<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-value-disabled"><?php echo esc_html( $hour_left_t ); ?></span>
					<span class="woo-sctr-countdown-hour-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-hour-value-1 woo-sctr-countdown-value-1">
							<?php echo $hour_left > 0 ? esc_html( zeroise( $hour_left - 1, 2 ) ) : esc_html( '00' ); ?>
						</span>
						<span class="woo-sctr-countdown-hour-value-2 woo-sctr-countdown-value-2"><?php echo esc_html( $hour_left_t ); ?></span>
					</span>
				</span>
					<?php
					break;
				default:
					echo sprintf( '<span class="woo-sctr-countdown-hour-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">%s</span>', esc_attr( $hour_left_t ) );
			}
			?>
			<span class="woo-sctr-countdown-hour-text woo-sctr-countdown-text"><?php echo esc_html( $hour ); ?></span>
		</span>
		<?php
		if ( strpos( $unit_hour_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
			echo sprintf( '<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr( $time_separator ) );
		}
		?>
    </div>
    <div class="<?php echo esc_attr( $unit_minute_class ); ?>">
		<span class="woo-sctr-countdown-minute woo-sctr-countdown-unit">
			<?php
			switch ( $animation_style ) {
				case 'slide':
					?>
				<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-value-disabled"><?php echo esc_html( $min_left_t ); ?></span>
					<span class="woo-sctr-countdown-minute-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-minute-value-1 woo-sctr-countdown-value-1">
							<?php echo $min_left > 0 ? esc_html( zeroise( $min_left - 1, 2 ) ) : esc_html( '59' ); ?>
						</span>
						<span class="woo-sctr-countdown-minute-value-2 woo-sctr-countdown-value-2"><?php echo esc_html( $min_left_t ); ?></span>
					</span>
				</span>
					<?php
					break;
				default:
					echo sprintf( '<span class="woo-sctr-countdown-minute-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">%s</span>', esc_attr( $min_left_t ) );
			}
			?>
			<span class="woo-sctr-countdown-minute-text woo-sctr-countdown-text"><?php echo esc_html( $minute ); ?></span>
		</span>
		<?php
		if ( strpos( $unit_minute_class, 'woo-sctr-countdown-unit-wrap-two' ) ) {
			echo sprintf( '<span class="woo-sctr-countdown-time-separator">%s</span>', esc_attr( $time_separator ) );
		}
		?>
    </div>
    <div class="<?php echo esc_attr( $unit_second_class ); ?>">
		<span class="woo-sctr-countdown-second woo-sctr-countdown-unit">
			<?php
			switch ( $animation_style ) {
				case 'slide':
					?>
				<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value">
					<span class="woo-sctr-countdown-value-disabled"><?php echo esc_html( $sec_left_t ); ?></span>
					<span class="woo-sctr-countdown-second-value-container woo-sctr-countdown-value-container">
						<span class="woo-sctr-countdown-second-value-1 woo-sctr-countdown-value-1">
							<?php echo $sec_left > 0 ? esc_html( zeroise( $sec_left - 1, 2 ) ) : esc_html( '59' ); ?>
						</span>
						<span class="woo-sctr-countdown-second-value-2 woo-sctr-countdown-value-2"><?php echo esc_html( $sec_left_t ); ?></span>
					</span>
				</span>
					<?php
					break;
				default:
					echo sprintf( '<span class="woo-sctr-countdown-second-value woo-sctr-countdown-value woo-sctr-countdown-value-animation-default">%s</span>', esc_attr( $sec_left_t ) );
			}
			?>
			<span class="woo-sctr-countdown-second-text woo-sctr-countdown-text"><?php echo esc_html( $second ); ?></span>
		</span>
    </div>
</div>
