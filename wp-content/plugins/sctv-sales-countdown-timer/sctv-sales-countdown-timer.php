<?php
/**
 * Plugin Name: Sales Countdown Timer Premium for WooCommerce and WordPress
 * Plugin URI: https://villatheme.com/extensions/sales-countdown-timer/
 * Description: Create a sense of urgency with a countdown to the beginning or end of sales, store launch or other events for higher conversions.
 * Version: 1.0.1.4
 * Author: VillaTheme
 * Author URI: http://villatheme.com
 * Text Domain: sales-countdown-timer
 * Domain Path: /languages
 * Copyright 2019-2021 VillaTheme.com. All rights reserved.
 * Requires PHP: 7.0
 * Requires at least: 5.0
 * Tested up to: 5.7
 * WC requires at least: 4.0
 * WC tested up to: 5.4
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_VERSION', '1.0.1.4' );
/**
 * Detect plugin. For use on Front End only.
 */

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_DIR', plugin_dir_path( __FILE__ ) );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES', VI_SCT_SALES_COUNTDOWN_TIMER_DIR . "includes" . DIRECTORY_SEPARATOR );
$init_file = VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "define.php";
require_once $init_file;

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER
 */
class VI_SCT_SALES_COUNTDOWN_TIMER {
	public function __construct() {

		register_activation_hook( __FILE__, array( $this, 'install' ) );
	}

	/**
	 * When active plugin Function will be call
	 */
	public function install() {
		global $wp_version;
		if ( version_compare( $wp_version, "4.4", "<" ) ) {
			deactivate_plugins( basename( __FILE__ ) ); // Deactivate our plugin
			wp_die( "This plugin requires WordPress version 4.4 or higher." );
		}
	}

}

new VI_SCT_SALES_COUNTDOWN_TIMER();