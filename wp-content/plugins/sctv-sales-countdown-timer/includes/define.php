<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_LANGUAGES', VI_SCT_SALES_COUNTDOWN_TIMER_DIR . "languages" . DIRECTORY_SEPARATOR );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_ADMIN', VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "admin" . DIRECTORY_SEPARATOR );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_FRONTEND', VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "frontend" . DIRECTORY_SEPARATOR );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_TEMPLATES', VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "templates" . DIRECTORY_SEPARATOR );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_CACHE', WP_CONTENT_DIR . "/cache/vi-woo-checkout-countdown/" );
$plugin_url = plugins_url( 'sctv-sales-countdown-timer/assets' );
$plugin_url = str_replace( '/includes', '/assets', $plugin_url );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_CSS', $plugin_url . "/css/" );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_CSS_DIR', VI_SCT_SALES_COUNTDOWN_TIMER_DIR . "css" . DIRECTORY_SEPARATOR );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_JS', $plugin_url . "/js/" );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_JS_DIR', VI_SCT_SALES_COUNTDOWN_TIMER_DIR . "js" . DIRECTORY_SEPARATOR );
define( 'VI_SCT_SALES_COUNTDOWN_TIMER_IMAGES', $plugin_url . "/images/" );


/*Include functions file*/
if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "functions.php" ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "functions.php";
}

if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "data.php" ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "data.php";
}
if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "countdown-schedule.php" ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "countdown-schedule.php";
}

if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "countdown-style.php" ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "countdown-style.php";
}

if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "check_update.php" ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "check_update.php";
}
if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "update.php" ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "update.php";
}

if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "support.php" ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . "support.php";
}

if ( is_file( VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . '3rd/elementor/elementor.php' ) ) {
	require_once VI_SCT_SALES_COUNTDOWN_TIMER_INCLUDES . '3rd/elementor/elementor.php';
}


vi_include_folder( VI_SCT_SALES_COUNTDOWN_TIMER_ADMIN, 'VI_SCT_SALES_COUNTDOWN_TIMER_Admin_' );
vi_include_folder( VI_SCT_SALES_COUNTDOWN_TIMER_FRONTEND, 'VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_' );
