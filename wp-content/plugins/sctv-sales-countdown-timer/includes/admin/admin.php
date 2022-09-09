<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Admin_Admin {
	public function __construct() {
		add_action( 'init', array( $this, 'init' ) );
		add_filter( 'plugin_action_links_sctv-sales-countdown-timer/sctv-sales-countdown-timer.php', array( $this, 'settings_link', ) );
	}

	public function settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=sales-countdown-timer" title="' . __( 'Settings', 'sales-countdown-timer' ) . '">' . __( 'Settings', 'sales-countdown-timer' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sctv-sales-countdown-timer' );
		load_textdomain( 'sales-countdown-timer', VI_SCT_SALES_COUNTDOWN_TIMER_LANGUAGES . "sales-countdown-timer-$locale.mo" );
		load_plugin_textdomain( 'sales-countdown-timer', false, VI_SCT_SALES_COUNTDOWN_TIMER_LANGUAGES );
	}

	public function init() {
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support_Pro' ) ) {
			new VillaTheme_Support_Pro(
				array(
					'support'   => 'https://villatheme.com/supports/forum/plugins/sales-countdown-timer/',
					'docs'      => 'http://docs.villatheme.com/?item=sales-countdown-timer',
					'review'    => 'https://codecanyon.net/downloads',
					'css'       => VI_SCT_SALES_COUNTDOWN_TIMER_CSS,
					'image'     => VI_SCT_SALES_COUNTDOWN_TIMER_IMAGES,
					'slug'      => 'sales-countdown-timer',
					'menu_slug' => 'sales-countdown-timer',
					'version'   => VI_SCT_SALES_COUNTDOWN_TIMER_VERSION
				)
			);
		}
	}

}