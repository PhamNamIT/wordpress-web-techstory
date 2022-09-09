<?php

$platformAPIURL = mulutuConf('platform_api_url');

/**
 * Custom woocommerce front
 */
class MulutuWCFront {

    function disableShippingCalcOnCart( $showShipping ) {
        if( is_cart() ) {
            return false;
        }
        return $showShipping;
    }

    function overrideCheckoutFields($fields) {
        unset($fields['billing']['billing_company']);
        unset($fields['billing']['billing_postcode']);

        $onlyVirtual = true;
        foreach( WC()->cart->get_cart() as $cartItem ) {
            if (!$cartItem['data']->is_virtual()) {
                $onlyVirtual = false;
                break;
            }
        }

        if ($onlyVirtual) {
            unset($fields['billing']['billing_address_1']);
            unset($fields['billing']['billing_address_2']);
            unset($fields['billing']['billing_city']);
            unset($fields['billing']['billing_country']);
            unset($fields['billing']['billing_state']);
            add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );
        } else {
            $fields['billing']['billing_city']['class'] = array('d-none');
            $fields['billing']['billing_address_1'] = array(
                'class'        => array('d-none'),
                'required'     => false
            );
            $fields['billing']['billing_address_1_text'] = array(
                'label'        => 'Địa chỉ',
                'placeholder'  => 'Địa chỉ',
                'class'        => array ('form-row', 'address-field', 'validate-required', 'form-row-wide'),
                'priority'     => 41,
                'required'     => true
            );
            $fields['billing']['billing_district'] = array(
                'type'       => 'select',
                'required'   => true,
                'priority'   => 40,
                'class'      => array( 'form-row', 'form-row-first', 'validate-required' ),
                'label'      => __( 'Quận / Huyện' ),
                'options'    => array(
                    -1     => __( 'Chọn quận huyện', 'Chọn quận huyện' )
                )
            );
            $fields['billing']['billing_ward'] = array(
                'type'       => 'select',
                'required'   => true,
                'priority'   => 40,
                'class'      => array( 'form-row', 'form-row-last', 'validate-required' ),
                'label'      => __( 'Phường / Xã' ),
                'options'    => array(
                    -1       => __( 'Chọn phường xã', 'Chọn phường xã' )
                )
            );
        }

        return $fields;
    }

    function debounceAddJSScriptCheckout() {
        global $mulutuOptions;
        global $platformAPIURL;

        wp_enqueue_style('mulutu-checkout-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/checkout.css'));
        wp_enqueue_style('mulutu-checkout-select2-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/select2.min.css'));
        wp_enqueue_script('mulutu-checkout-select2-js', plugins_url(MULUTU_PLUGIN_NAME . '/js/select2.min.js'));
        wp_enqueue_script('mulutu-checkout-js', plugins_url(MULUTU_PLUGIN_NAME . '/js/checkout.js'), array('jquery'), false, true);

        $weight = 0;
        $width  = 0;
        $length = 0;
        $height = 0;

        // Get weight
        if ($mulutuOptions['shipping_default_weight_flg'] == 0) {
            $weight = $mulutuOptions['shipping_default_weight_value'];
        } else {
            $cart = WC()->cart->get_cart();
            foreach($cart as $item => $values) {
                $product = wc_get_product($values['data']->get_id());
                $weight += wc_get_weight($product->get_weight(), 'g') * $values['quantity'];
            }
        }

        // Get Dimension
        if ($mulutuOptions['shipping_default_dimension_flg'] == 0) {
            $width = $mulutuOptions['shipping_default_dimension_value']['width'];
            $length = $mulutuOptions['shipping_default_dimension_value']['length'];
            $height = $mulutuOptions['shipping_default_dimension_value']['height'];
        } else {
            $cart = WC()->cart->get_cart();
            foreach($cart as $item => $values) {
                $product = wc_get_product($values['data']->get_id());
                $dimensions = $product->get_dimensions(false);
                $length += wc_get_dimension(intval($dimensions['length']) * $values['quantity'], 'cm');
                $height += wc_get_dimension(intval($dimensions['height']) * $values['quantity'], 'cm');
                $width  += wc_get_dimension(intval($dimensions['width']) * $values['quantity'], 'cm');
            }
        }

        $weight = mulutuGetDefaultOrderWeight($weight);
        $length = mulutuGetDefaultOrderDimensions('length', $length);
        $height = mulutuGetDefaultOrderDimensions('height', $height);
        $width  = mulutuGetDefaultOrderDimensions('width', $width);

        wp_localize_script('mulutu-checkout-js', 'MulutuCheckout', array(
            'ajax_url'           => admin_url('admin-ajax.php'),
            'districts_api'      => $platformAPIURL . '/api/v1/address/districts',
            'district_wards_api' => $platformAPIURL . '/api/v1/address/:district_id/wards',
            'fees_api'           => $platformAPIURL . '/api/v1/fees/calculate',
            'ghn_token'          => $mulutuOptions['ghn_token'],
            'shop_id'            => $mulutuOptions['ghn_shop_id'],
            'from_district_id'   => $mulutuOptions['ghn_district_id'],
            'from_ward_code'     => $mulutuOptions['ghn_ward_code'],
            'height'             => $height,
            'length'             => $length,
            'width'              => $width,
            'weight'             => $weight
        ));
    }
}

$objMulutuWCFront = new MulutuWCFront();

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * @todo Add Cart page support
 */
add_filter('woocommerce_cart_ready_to_calc_shipping', array($objMulutuWCFront, 'disableShippingCalcOnCart'), 99);

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * @todo Add Checkout page support
 */
add_filter('woocommerce_checkout_fields', array($objMulutuWCFront, 'overrideCheckoutFields'));
add_action('woocommerce_after_checkout_form', array($objMulutuWCFront, 'debounceAddJSScriptCheckout'));
add_filter('woocommerce_cart_needs_shipping_address', '__return_false');
