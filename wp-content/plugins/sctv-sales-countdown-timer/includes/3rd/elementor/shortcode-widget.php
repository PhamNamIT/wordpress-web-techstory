<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class VISCT_Elementor_Reviews_Widget extends Elementor\Widget_Base {

	public static $slug = 'visct-elementor-reviews-widget';

	public function get_name() {
		return 'sales-countdown-timer';
	}

	public function get_title() {
		return esc_html__( 'Sales Countdown Timer', 'sales-countdown-timer' );
	}

	public function get_icon() {
		return 'fas fa-clock';
	}

	public function get_categories() {
		return [ 'wordpress' ];
	}

	protected function _register_controls() {
		$settings      = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		$ids           = $settings->get_params( 'sale_countdown_id' );
		$available_ids = array();
		if ( $ids && is_array( $ids ) && ! empty( $ids ) ) {
			foreach ( $ids as $k => $id ) {
				$available_ids[ $id ] = $settings->get_params( 'sale_countdown_name' )[ $k ] ?? '';
			}
		}
		$this->start_controls_section(
			'general',
			[
				'label' => esc_html__( 'General', 'sales-countdown-timer' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'profile_id',
			[
				'label'   => esc_html__( 'Countdown Profile', 'sales-countdown-timer' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'salescountdowntimer',
				'options' => $available_ids,
			]
		);
		$this->add_control(
			'sale_from',
			[
				'label'   => esc_html__( 'Form', 'sales-countdown-timer' ),
				'type'    => \Elementor\Controls_Manager::DATE_TIME,
				'default' => date( "Y-m-d H:i", current_time( 'timestamp' ) ),
			]
		);
		$this->add_control(
			'sale_to',
			[
				'label'   => esc_html__( 'To', 'sales-countdown-timer' ),
				'type'    => \Elementor\Controls_Manager::DATE_TIME,
				'default' => date( "Y-m-d H:i", current_time( 'timestamp' ) + 30 * 86400 ),
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'design',
			[
				'label' => esc_html__( 'Design', 'sales-countdown-timer' ),
				'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'message',
			[
				'label'       => esc_html__( 'Message', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => 'Hurry Up! Offer ends in {countdown_timer}',
				'description' => esc_html__( 'The countdown timer will not show if message does not include {countdown_timer}', 'sales-countdown-timer' ),
			]
		);
		$this->add_control(
			'message_position',
			[
				'label'       => esc_html__( 'Message Position', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default'          => esc_html__( 'Default', 'sales-countdown-timer' ),
					'inline_countdown' => esc_html__( 'The same line as the countdown timer', 'sales-countdown-timer' ),
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'time_units',
			[
				'label'       => esc_html__( 'Time units', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT2,
				'default'     => [ 'day', 'hour', 'minute', 'second' ],
				'options'     => [
					'day'    => esc_html__( 'Day', 'sales-countdown-timer' ),
					'hour'   => esc_html__( 'Hour', 'sales-countdown-timer' ),
					'minute' => esc_html__( 'Minute', 'sales-countdown-timer' ),
					'second' => esc_html__( 'Second', 'sales-countdown-timer' ),
				],
				'label_block' => true,
				'multiple'    => true
			]
		);
		$this->add_control(
			'time_separator',
			[
				'label'       => esc_html__( 'Time separator', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'blank',
				'options'     => [
					'blank' => esc_html__( 'Blank', 'sales-countdown-timer' ),
					'colon' => esc_html__( 'Colon(:)', 'sales-countdown-timer' ),
					'comma' => esc_html__( 'Comma(,)', 'sales-countdown-timer' ),
					'dot'   => esc_html__( 'Dot(.)', 'sales-countdown-timer' ),
				],
				'label_block' => true,
			]
		);
		$this->add_control(
			'datetime_format',
			[
				'label'       => esc_html__( 'Datetime format style', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '1',
				'options'     => [
					'1'      => esc_html__( '01 days 02 hrs 03 mins 04 secs', 'sales-countdown-timer' ),
					'2'      => esc_html__( '01 days 02 hours 03 minutes 04 seconds', 'sales-countdown-timer' ),
					'3'      => esc_html__( '01:02:03:04', 'sales-countdown-timer' ),
					'4'      => esc_html__( '01d:02h:03m:04s', 'sales-countdown-timer' ),
					'#other' => esc_html__( 'Custom', 'sales-countdown-timer' ),
				],
				'label_block' => true
			]
		);
		$this->add_control(
			'datetime_format_custom_date',
			[
				'label'       => esc_html__( 'Day', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'days', 'sales-countdown-timer' ),
				'condition'   => array(
					'datetime_format' => '#other'
				)
			]
		);
		$this->add_control(
			'datetime_format_custom_hour',
			[
				'label'       => esc_html__( 'Hours', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'hours', 'sales-countdown-timer' ),
				'condition'   => array(
					'datetime_format' => '#other'
				)
			]
		);
		$this->add_control(
			'datetime_format_custom_minute',
			[
				'label'       => esc_html__( 'Minutes', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'minutes', 'sales-countdown-timer' ),
				'condition'   => array(
					'datetime_format' => '#other'
				)
			]
		);
		$this->add_control(
			'datetime_format_custom_second',
			[
				'label'       => esc_html__( 'Seconds', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'seconds', 'sales-countdown-timer' ),
				'condition'   => array(
					'datetime_format' => '#other'
				)
			]
		);
		$this->add_control(
			'display_type',
			[
				'label'       => esc_html__( 'Display type', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '1',
				'options'     => [
					'1' => __( 'Square Countdown Timer', 'sales-countdown-timer' ),
					'2' => __( 'Square Countdown Timer 1', 'sales-countdown-timer' ),
					'3' => __( 'Basic Countdown Timer', 'sales-countdown-timer' ),
					'4' => __( 'Circular Countdown Timer', 'sales-countdown-timer' ),
					'5' => __( 'Circular Countdown Timer 1', 'sales-countdown-timer' ),
					'6' => __( 'Sleek Countdown Timer', 'sales-countdown-timer' ),
					'7' => __( 'Sleek Countdown Timer 1', 'sales-countdown-timer' ),
				],
				'label_block' => true
			]
		);
		$this->add_control(
			'animation_style_1',
			[
				'label'       => esc_html__( 'Animation style', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => __( 'Default', 'sales-countdown-timer' ),
					'slide'   => __( 'Slide', 'sales-countdown-timer' ),
				],
				'label_block' => true,
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'display_type',
							'operator' => '!in',
							'value'    => [ '6', '7' ],
						]
					],
				]
			]
		);
		$this->add_control(
			'animation_style_2',
			[
				'label'       => esc_html__( 'Animation style', 'sales-countdown-timer' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => __( 'Default', 'sales-countdown-timer' ),
					'slide'   => __( 'Slide', 'sales-countdown-timer' ),
					'flip'    => __( '3D Flip', 'sales-countdown-timer' ),
				],
				'label_block' => true,
				'conditions'  => [
					'terms' => [
						[
							'name'     => 'display_type',
							'operator' => 'in',
							'value'    => [ '6', '7' ],
						]
					],
				]
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		$settings  = $this->get_settings_for_display();
		$shortcode = $this->visct_get_shortcode( $settings );
		$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
		echo $shortcode;
	}

	public function render_plain_content() {
		$settings  = $this->get_settings_for_display();
		$shortcode = $this->visct_get_shortcode( $settings );
		echo $shortcode;
	}

	private function visct_get_shortcode( $settings ) {
		$sale_from      = $settings['sale_from'] ?? '';
		$sale_from      = $sale_from ? explode( ' ', $sale_from ) : array();
		$sale_from_date = $sale_from[0] ?? '';
		$sale_from_time = $sale_from[1] ?? '';
		$sale_to        = $settings['sale_to'] ?? '';
		$sale_to        = $sale_from ? explode( ' ', $sale_to ) : array();
		$sale_to_date   = $sale_to[0] ?? '';
		$sale_to_time   = $sale_to[1] ?? '';
		$time_units     = ! empty( $settings['time_units'] ) ? implode( ',', $settings['time_units'] ) : '';
		$display_type   = $settings['display_type'] ?? '1';
		if ( in_array( $display_type, array( '6', '7' ) ) ) {
			$animation_style = $settings['animation_style_2'] ?? '';
		} else {
			$animation_style = $settings['animation_style_1'] ?? '';
		}
		$shortcode = "[sales_countdown_timer id='{$settings['profile_id']}' active='1'
		 sale_from_date='{$sale_from_date}' sale_from_time='{$sale_from_time}' sale_to_date='{$sale_to_date}' sale_to_time='{$sale_to_time}' 
		 message='{$settings['message']}' message_position='{$settings['message_position']}' time_units='{$time_units}' 
		 time_separator='{$settings['time_separator']}' datetime_format='{$settings['datetime_format']}' 
		 datetime_format_custom_date='{$settings['datetime_format_custom_date']}' datetime_format_custom_hour='{$settings['datetime_format_custom_hour']}' 
		 datetime_format_custom_minute='{$settings['datetime_format_custom_minute']}' datetime_format_custom_second='{$settings['datetime_format_custom_second']}' 
		 display_type='{$settings['display_type']}' animation_style='{$animation_style}']";

		return $shortcode;
	}
}