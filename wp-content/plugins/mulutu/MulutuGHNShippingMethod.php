<?php

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    function mulutu_ghn_shipping_method_init() {
        if ( ! class_exists( 'MulutuGHNShippingMethod' ) ) {
            class MulutuGHNShippingMethod extends WC_Shipping_Method {
                /**
                 * Constructor for GHN shipping class
                 *
                 * @access public
                 * @return void
                 */
                public function __construct($options) {
                    $this->id                 = !empty($options['id']) ? $options['id'] : 'ghn_shipping_method'; // Id for ghn shipping method. Should be unique.
                    $this->method_title       = !empty($options['method_title']) ? $options['method_title'] : __( 'Mulutu-GHN Shipping Method' );  // Title shown in admin
                    $this->method_description = !empty($options['method_description']) ? $options['method_description'] : __( 'Description of GHN shipping method' ); // Description shown in admin

                    $this->enabled            = !empty($options['enabled']) ? $options['enabled'] : "yes"; // This can be added as an setting but for this example its forced enabled
                    $this->title              = !empty($options['title']) ? $options['title'] : "GHN"; // This can be added as an setting but for this example its forced.

                    $this->cost               = !empty($options['cost']) ? $options['cost'] : 0;

                    $this->leadtime           = !empty($options['leadtime']) ? $options['leadtime'] : 0;

                    $this->init();
                }

                /**
                 * Init settings
                 *
                 * @access public
                 * @return void
                 */
                function init() {
                    // // Load the settings API
                    // $this->init_form_fields(); // This is part of the settings API. Override the method to add own settings
                    // $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

                    // // Save settings in admin if you have any defined
                    // add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
                }

                /**
                 * calculate_shipping function.
                 *
                 * @access public
                 * @param mixed $package
                 * @return void
                 */
                public function calculate_shipping( $package = array() ) {
                    $rate = array(
                        'label' => $this->title,
                        'cost' => $this->cost,
                        'calc_tax' => 'per_order'
                    );

                    if ($this->cost !== 0) {
                        $rate['label'] = $this->title . "#{$this->leadtime}#";
                    }

                    // Register the rate
                    $this->add_rate( $rate );
                }
            }
        }
    }

    add_action( 'woocommerce_shipping_init', 'mulutu_ghn_shipping_method_init' );
}