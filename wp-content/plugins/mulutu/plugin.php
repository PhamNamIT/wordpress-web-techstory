<?php

/**
 * @package Mulutu
 */
/*
*
* Plugin Name: Mulutu
* Plugin URI: https://mulutu.vn/
* Description: Plugin provides basic function for GHN Panel.
* Version: 1.1.3
* Text Domain: mulutu
* Author: Mulutu team
* Author URI: https://mulutu.vn
* License: GPLv2 or later
*/

defined('ABSPATH') or die('Forbidden');

if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
    die('
        <span>
        Require 
        <a href="https://woocommerce.com/" target="_blank" style="color: #0073aa;text-decoration: none;">
        Woocommerce plugin
        </a>.
        </span>'
    );
}

define('MULUTU_ROOT', __DIR__);
define('MULUTU_PLUGIN_NAME', 'mulutu');

// Init
include_once (MULUTU_ROOT . '/init.php');
register_activation_hook(__FILE__, 'do_mulutu_app_init_tasks');

$mulutuOptions = get_option('mulutu_options');

// Start App
include_once (MULUTU_ROOT . '/helpers.php');
include_once (MULUTU_ROOT . '/apis.php');
include_once (MULUTU_ROOT . '/MulutuGHNShippingMethod.php');
include_once (MULUTU_ROOT . '/MulutuWC.php');
if (is_admin()) {
    include_once (MULUTU_ROOT . '/MulutuWCAdmin.php');
    include_once (MULUTU_ROOT . '/MulutuSettings.php');
    new MulutuSettings($mulutuOptions);
} else {
    include_once (MULUTU_ROOT . '/MulutuWCFront.php');
}
