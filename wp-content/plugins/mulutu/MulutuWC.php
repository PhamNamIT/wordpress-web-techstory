<?php

$platformAPIURL = mulutuConf('platform_api_url');

/**
 * Custom woocommerce
 */
class MulutuWC {

    function addGHNShippingMethod($methods) {
        foreach ($methods as $key => $method) {
            if (strpos($key, 'ghn_shipping_') !== false) {
                unset($methods[$key]);
            }
        }

        global $mulutuOptions;
        if(isset($mulutuOptions['auto_freeship_by_amount_flg']) && $mulutuOptions['auto_freeship_by_amount_flg'] == 1) {
            $cartTotal = 0;
            if (is_admin()) {
                global $post;
                $order = wc_get_order($post->ID);
                if ($order !== false) {
                    $cartTotal = $order->get_total();
                }
            } else {
                $cartTotal = WC()->cart->get_subtotal();
            }
            if ($cartTotal >= $mulutuOptions['auto_freeship_by_amount_value']) {
                $methods['ghn_shipping_freeship_by_amount_over_value'] = new MulutuGHNShippingMethod(array(
                    'id'           => 'ghn_shipping_freeship_by_amount_over_value',
                    'method_title' => 'GHN',
                    'title'        => 'Free ship (Giá trị hoá đơn lớn hơn ' . number_format($mulutuOptions['auto_freeship_by_amount_value'], 0, null, '.') . ' VNĐ)',
                    'enabled'      => 'yes',
                    'cost'         => 0
                ));
                return $methods;
            }
        }

        $serviceFees = array();
        if (!empty($_REQUEST['post_data'])) {
            parse_str(html_entity_decode(htmlentities($_REQUEST['post_data'])), $postData);
            if (isset($postData['service_fee']) && is_array($postData['service_fee'])) {
                $serviceFees = array_map(function($feeItem) {
                    return array(
                        'id'       => sanitize_text_field($feeItem['id']),
                        'title'    => sanitize_text_field($feeItem['title']),
                        'leadtime' => sanitize_text_field($feeItem['leadtime']),
                        'cost'     => sanitize_text_field($feeItem['cost'])
                    );
                }, $postData['service_fee']);
            }
        } else {
            if (!empty($_REQUEST['service_fee']) && is_array($_REQUEST['service_fee'])) {
                $serviceFees = array_map(function($feeItem) {
                    return array(
                        'id'       => sanitize_text_field($feeItem['id']),
                        'title'    => sanitize_text_field($feeItem['title']),
                        'leadtime' => sanitize_text_field($feeItem['leadtime']),
                        'cost'     => sanitize_text_field($feeItem['cost'])
                    );
                }, $_REQUEST['service_fee']);
            }
        }
        if (!empty($serviceFees)) {
            foreach ($serviceFees as $index => $fee) {
                $methods['ghn_shipping_' . $fee['id']] = new MulutuGHNShippingMethod(array(
                    'id'       => 'ghn_shipping_' . $fee['id'],
                    'title'    => $fee['title'],
                    'leadtime' => $fee['leadtime'],
                    'cost'     => $fee['cost']
                ));
            }
            return $methods;
        }

        $methods['ghn_shipping_0'] = new MulutuGHNShippingMethod(array(
            'id'           => 'ghn_shipping',
            'method_title' => 'GHN',
            'title'        => 'Đang tính toán ...',
            'enabled'      => 'yes',
            'cost'         => 0
        )); 

        return $methods;
    }

}

$objMulutuWC = new MulutuWC();
add_filter('woocommerce_shipping_methods', array($objMulutuWC, 'addGHNShippingMethod'));
