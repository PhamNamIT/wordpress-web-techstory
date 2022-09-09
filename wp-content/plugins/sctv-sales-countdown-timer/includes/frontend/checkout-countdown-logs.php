<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Checkout_Countdown_Logs
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Checkout_Countdown_Logs {
	protected $settings;

	public function __construct() {
		$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
		if ( $this->settings->get_params( 'checkout_countdown_save_log' ) ) {
			add_action( 'template_redirect', array( $this, 'init' ) );
		}
	}

	/**
	 * Detect IP
	 */
	public function init() {

		if ( ! isset( $_GET['link'] ) ) {
			return false;
		}
		if ( wp_verify_nonce( $_GET['link'], 'sctv_checkout_countdown_link' ) ) {
			$this->save_click();
		} else {
			return false;
		}
	}

	/**
	 * Save click
	 */
	private function save_click() {
		/*Check Save Logs Option*/
		if ( ! WC()->cart->is_empty() && is_checkout() ) {
			$cart_hash = WC()->cart->get_cart_hash();
			$file_name = mktime( 0, 0, 0, date( "m" ), date( "d" ), date( "Y" ) ) . '.txt';
			$file_path = VI_SCT_SALES_COUNTDOWN_TIMER_CACHE . $file_name;
			if ( ! is_dir( VI_SCT_SALES_COUNTDOWN_TIMER_CACHE ) ) {
				wp_mkdir_p( VI_SCT_SALES_COUNTDOWN_TIMER_CACHE );
				file_put_contents( VI_SCT_SALES_COUNTDOWN_TIMER_CACHE . '.htaccess',
					'<IfModule !mod_authz_core.c>
Order deny,allow
Deny from all
</IfModule>
<IfModule mod_authz_core.c>
  <RequireAll>
    Require all denied
  </RequireAll>
</IfModule>
' );
			}
			if ( is_file( $file_path ) ) {
				file_put_contents( $file_path, ',' . $cart_hash, FILE_APPEND );
			} else {

				file_put_contents( $file_path, $cart_hash );
			}
		} else {
			return false;
		}
	}
}