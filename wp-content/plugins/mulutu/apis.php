<?php

/**
 * @todo Custom wc order detail page
 * @todo Add apis
 */
$routes = include(MULUTU_ROOT . '/routes.php');
add_action('rest_api_init', function() use($routes) {
    foreach($routes as $route) {
        $uri = isset($route['reg_uri']) ? $route['reg_uri'] : $route['uri'];
        register_rest_route(MULUTU_PLUGIN_NAME . '/v1', $uri, $route['caller']);
    }
});

/**
 * get WC order info to make shipping order
 * @param  array $data
 * @return json
 */
if (!function_exists('mulutuGetWCOrderInfo')) {
    function mulutuGetWCOrderInfo($data) {
        global $mulutuOptions;

        $order = wc_get_order($data['id']);
        $orderData = $order->get_data();

        // Decorate order data
        $willRemoveData = array(
            'email', 'parent_id', 'status', 'currency', 'version', 'prices_include_tax', 'discount_total', 'discount_tax',
            'shipping_tax', 'cart_tax', 'total_tax', 'customer_id', 'date_created', 'date_modified', 'date_modified',
            'transaction_id', 'customer_ip_address', 'customer_user_agent', 'created_via', 'date_completed', 'date_paid', 'cart_hash',
            'line_items', 'tax_lines', 'shipping_lines', 'fee_lines', 'coupon_lines', 'order_key', 'payment_method', 'payment_method_title',
            'number'
        );
        foreach ($willRemoveData as $key) {
            unset($orderData[$key]);
        }
        $orderData['note'] = $orderData['customer_note'];
        unset($orderData['customer_note']);
        $orderData['total'] = intval($orderData['total']);
        $orderData['shipping_total'] = intval($orderData['shipping_total']);

        $orderItems = array();
        foreach ($order->get_items() as $itemId => $item) {
            $product = wc_get_product($item->get_product_id());
            if ($product === false) {
                http_response_code(404);
                echo json_encode(array(
                    'status' => 0
                ));
            }
            $productSKU = $product->get_sku();
            $dimensions = $product->get_dimensions(false);
            $orderItems[$itemId] = array(
                'name'     => $item->get_name(),
                'code'     => !empty($productSKU) ? $productSKU : $item->get_product_id(),
                'quantity' => $item->get_quantity(),
                'price'    => intval($product->get_price()),
                'length'   => wc_get_dimension(intval($dimensions['length']), 'cm'),
                'width'    => wc_get_dimension(intval($dimensions['width']), 'cm'),
                'height'   => wc_get_dimension(intval($dimensions['height']), 'cm'),
                'weight'   => wc_get_weight($product->get_weight(), 'g'),
            );
        };

        $history = null;

        // Shipping
        $shipping = array_merge($orderData['billing'], $orderData['shipping']);
        foreach($shipping as $key => $val) {
            $val = trim($val);
            if (empty($val) && isset($orderData['billing'][$key])) {
                $shipping[$key] = $orderData['billing'][$key];
            }
        }
        $shipping['to_name'] = "{$shipping['first_name']} {$shipping['last_name']}";
        $shipping['to_phone'] = $shipping['phone'];
        $shipping['to_address'] = $shipping['address_1'];
        unset($shipping['email']);
        unset($shipping['country']);
        unset($shipping['postcode']);
        unset($shipping['city']);
        unset($shipping['state']);
        unset($shipping['address_1']);
        unset($shipping['first_name']);
        unset($shipping['last_name']);
        unset($shipping['phone']);
        unset($shipping['company']);
        unset($shipping['address_2']);
        foreach ($orderData['meta_data'] as $meta) {
            if($meta->key == '_billing_district') {
                $shipping['to_district_id'] = intval($meta->value);
                continue;
            }
            if($meta->key == '_billing_ward') {
                $shipping['to_ward_code'] = $meta->value;
                continue;
            }
            if($meta->key == 'mulutu_order_code') {
                $shipping['mulutu_order_code'] = $meta->value;
                continue;
            }
            if($meta->key == 'mulutu_order_status') {
                $shipping['mulutu_order_status'] = $meta->value;
                continue;
            }
            if($meta->key == 'shift') {
                $shipping['shift'] = $meta->value;
                continue;
            }
        }
        unset($orderData['meta_data']);
        unset($orderData['billing']);
        unset($orderData['shipping']);
        $orderData = array_merge($orderData, $shipping);

        // Items
        $orderData['items'] = array_values($orderItems);

        // Shop
        $shop = array();
        foreach ($mulutuOptions as $key => $value) {
            if (strpos($key, 'ghn_shop_') !== false) {
                $shop[str_replace('ghn_shop_', '', $key)] = $value;
                continue;
            }
            if (strpos($key, 'ghn_') !== false) {
                $shop[str_replace('ghn_', '', $key)] = $value;
                continue;
            }
        }
        unset($shop['env']);
        $shop['id'] = intval($shop['id']);
        $shop['district_id'] = intval($shop['district_id']);

        // history
        $history = array();
        if (!empty($orderData['mulutu_order_code'])) {
            $historyHookItems = mulutuGetOrderHookItems($orderData['mulutu_order_code'], 'history');
            $history = array_map(function($item) {
                return json_decode($item->content);
            }, $historyHookItems);
        }

        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

        echo json_encode(array(
            'status' => 1,
            'data'   => array(
                'order'   => $orderData,
                'shop'    => $shop,
                'history' => $history
            )
        ), JSON_UNESCAPED_SLASHES);
        die();
    }
}

/**
 * update WC order info
 * @param  array $data
 * @return json
 */
if (!function_exists('mulutuUpdateWCOrderInfo')) {
    function mulutuUpdateWCOrderInfo($data) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

        if (!empty($data['order_code'])) {
            update_post_meta($data['id'], 'mulutu_order_code', $data['order_code']);
        }
        if (!empty($data['status'])) {
            update_post_meta($data['id'], 'mulutu_order_status', $data['status']);
        }
        if (!empty($data['updated_at'])) {
            update_post_meta($data['id'], 'mulutu_order_updated_at', $data['updated_at']);
        }
        if (!empty($data['to_phone'])) {
            update_post_meta($data['id'], '_billing_phone', $data['to_phone']);
        }
        if (!empty($data['to_name'])) {
            list($firstName, $lastName) = explode(' ', $data['to_name']);
            update_post_meta($data['id'], '_billing_first_name', $firstName);
            update_post_meta($data['id'], '_billing_last_name', $lastName);
            update_post_meta($data['id'], '_shipping_first_name', $firstName);
            update_post_meta($data['id'], '_shipping_last_name', $lastName);
        }

        if (!empty($data['to_district_id'])) {
            update_post_meta($data['id'], '_billing_district', $data['to_district_id']);
        }
        if (!empty($data['to_ward_code'])) {
            update_post_meta($data['id'], '_billing_ward', $data['to_ward_code']);
        }
        if (!empty($data['to_phone'])) {
            update_post_meta($data['id'], '_billing_phone', $data['to_phone']);
        }
        if (!empty($data['billing_address_1_text'])) {
            update_post_meta($data['id'], '_billing_address_1', $data['billing_address_1_text']);
            update_post_meta($data['id'], '_shipping_address_1', $data['billing_address_1_text']);
        }
        if (!empty($data['billing_city'])) {
            update_post_meta($data['id'], '_billing_city', $data['billing_city']);
            update_post_meta($data['id'], '_shipping_city', $data['billing_city']);
        }
        if (!empty($data['shift'])) {
            update_post_meta($data['id'], 'shift', $data['shift']);
        }

        die();
    }
}

/**
 * update WC order status from hook 
 * @param  array $data
 * @return boolean
 */
if (!function_exists('mulutuHookUpdateWCOrderStatus')) {
    function mulutuHookUpdateWCOrderStatus($data) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

        if ($data['Status'] == 'ready_to_pick') {
            if (strpos(strtolower($data['OrderCode']), '_pr') !== false) {
                mulutuCreateGHNPartialReturnOrder($data);
                return;
            }
        }

        global $wpdb;
        $sql = $wpdb->prepare("SELECT post_id FROM wp_postmeta WHERE meta_key=%s AND meta_value=%s LIMIT 1 OFFSET 0", array(
            'mulutu_order_code',
            strval($data['OrderCode'])
        ));
        $postId = $wpdb->get_var($sql, 0, 0);

        // update order status
        update_post_meta($postId, 'mulutu_order_status', $data['Status']);

        // Add history to hook item table
        $history = array(
            'status'        => $data['Status'],
            'time'          => $data['Time'],
            'fee'           => $data['Fee'],
            'shipper_name'  => $data['ShipperName'],
            'shipper_phone' => $data['ShipperPhone'],
            'type'          => $data['Type'],
            'warehouse'     => $data['Warehouse']
        );
        mulutuAddOrderHookItem($data['OrderCode'], json_encode($history, JSON_UNESCAPED_UNICODE), $data['Time']);

        echo json_encode(array(
            'status'  => 1,
            'history' => $history
        ));
        die();
    }
}

/**
 * update WC order status from hook 
 * @param  array $data
 * @return boolean
 */
if (!function_exists('mulutuHookUpdateWCOrderTicket')) {
    function mulutuHookUpdateWCOrderTicket($data) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

        mulutuAddOrderHookItem(
            $data['OrderCode'], 
            json_encode($data->get_json_params(), JSON_UNESCAPED_UNICODE), 
            $data['UpdatedAt'], 
            'ticket'
        );
        echo json_encode(array(
            'status' => 1,
            'ticket' => $data->get_json_params()
        ));
        die();
    }
}

/**
 * generate Mulutu order url
 * @param  array $data
 * @return json
 */
if (!function_exists('mulutuGetOrderUrl')) {
    function mulutuGetOrderUrl($data) {
        global $mulutuOptions;

        $platformURL = mulutuConf('platform_url');

        // create order info link
        $orderCode = get_post_meta($data['id'], 'mulutu_order_code', true);
        if (!empty($orderCode)) {
            $orderStatus = get_post_meta($data['id'], 'mulutu_order_status', true);
            $orderLastUpdatedAt = get_post_meta($data['id'], 'mulutu_order_updated_at', true);
            $callback = site_url('?rest_route=/' . MULUTU_PLUGIN_NAME . '/v1/wc-orders/' . $data['id']);
            echo json_encode(array(
                'status' => 1,
                'data'   => array(
                    'action' => 'view_order',
                    'text'   => 'Đơn hàng ' . $orderCode,
                    'url'    => "{$platformURL}/orders/{$orderCode}/frame?" . http_build_query(array(
                        'cb' => $callback
                    )),
                    'updated_at' => $orderLastUpdatedAt
                )
            ));
            die();
        }

        // Create order info
        $order      = wc_get_order($data['id']);
        $orderData  = $order->get_data();

        // Get Dimension
        $orderItems = array(
            'weight' => 0,
            'height' => 0,
            'width'  => 0,
            'length' => 0
        );

        if ($mulutuOptions['shipping_default_weight_flg'] == 0) {
            $orderItems['weight'] = $mulutuOptions['shipping_default_weight_value'];
        } else {
            foreach ($order->get_items() as $itemId => $item) {
                $product = wc_get_product($item->get_product_id());
                $quantity = $item->get_quantity();
                $orderItems['weight'] += wc_get_weight($product->get_weight(), 'g') * $quantity;
            };
        }

        if ($mulutuOptions['shipping_default_dimension_flg'] == 0) {
            $width  = $mulutuOptions['shipping_default_dimension_value']['width'];
            $length = $mulutuOptions['shipping_default_dimension_value']['length'];
            $height = $mulutuOptions['shipping_default_dimension_value']['height'];
        } else {
            foreach ($order->get_items() as $itemId => $item) {
                $product = wc_get_product($item->get_product_id());
                $quantity = $item->get_quantity();
                $dimensions = $product->get_dimensions(false);
                $orderItems['length'] += wc_get_dimension(intval($dimensions['length']), 'cm') * $quantity;
                $orderItems['height'] += wc_get_dimension(intval($dimensions['height']), 'cm') * $quantity;
                $orderItems['width']  += wc_get_dimension(intval($dimensions['width']), 'cm') * $quantity;
            }
        }

        $orderItems['weight'] = mulutuGetDefaultOrderWeight($orderItems['weight']);
        $orderItems['length'] = mulutuGetDefaultOrderDimensions('length', $orderItems['length']);
        $orderItems['height'] = mulutuGetDefaultOrderDimensions('height', $orderItems['height']);
        $orderItems['width']  = mulutuGetDefaultOrderDimensions('width', $orderItems['width']);

        $billingDistrictId = null;
        $billingWardCode = null;
        foreach ($orderData['meta_data'] as $value) {
            $value = $value->get_data();
            if ($value['key'] == '_billing_district') {
                $billingDistrictId = $value['value'];
            }
            if ($value['key'] == '_billing_ward') {
                $billingWardCode = $value['value'];
            }
        }

        // Get shipping service id
        $shippingServiceId = 0;
        $shippingInfo = $order->get_items('shipping');

        if(!empty($shippingInfo)) {
            $shippingServiceId = end($shippingInfo)->get_method_id();
            $shippingServiceId = str_replace('ghn_shipping_', '', $shippingServiceId);
            if (!is_numeric($shippingServiceId)) {
                $shippingServiceId = 0;
            }
        }

        $query = array(
            'tdi'        => $billingDistrictId,
            'twc'        => "{$billingWardCode}",
            'h'          => $orderItems['height'],
            'l'          => $orderItems['length'],
            'wd'         => $orderItems['width'],
            'w'          => $orderItems['weight'],
            'iv'         => $orderData['total'] - $orderData['shipping_total'],
            'cp'         => null,
            'cod'        => $orderData['total'] - $orderData['shipping_total'],
            's_id'       => $shippingServiceId,
            'pm_t'       => $mulutuOptions['payment_type'],
            'cb'         => site_url('?rest_route=/' . MULUTU_PLUGIN_NAME . '/v1/wc-orders/' . $data['id'])
        );

        if ($mulutuOptions['include_cod_flg'] == 0) {
            $query['cod'] = 0;
        }

        if($shippingServiceId == 'freeship_by_amount_over_value') {
            $query['pm_t'] = 1;
            $query['s_id'] = 0;
        }

        // Get shop_id, token, callback_url here
        if (!empty($mulutuOptions)) {
            $query['fdi'] = !empty($mulutuOptions['ghn_district_id']) ? $mulutuOptions['ghn_district_id'] : '';
            $query['fwc'] = !empty($mulutuOptions['ghn_ward_code']) ? $mulutuOptions['ghn_ward_code'] : '';
            $query['sid'] = !empty($mulutuOptions['ghn_shop_id']) ? $mulutuOptions['ghn_shop_id'] : '';
            $query['tk']  = !empty($mulutuOptions['ghn_token']) ? $mulutuOptions['ghn_token'] : '';
        }

        echo json_encode(array(
            'status' => 1,
            'data'   => array(
                'action' => 'create_order',
                'text'   => 'Tạo đơn hàng',
                'url'    => $platformURL . '/orders/create/frame?' . http_build_query($query)
            )
        ));
        die();
    }
}

/**
 * create GHN partial rerturn order
 * @param  array $data
 * @return json
 */
if (!function_exists('mulutuCreateGHNPartialReturnOrder')) {
    function mulutuCreateGHNPartialReturnOrder($baseGHNOrder) {
        header('Access-Control-Allow-Origin:*');
        header('Access-Control-Allow-Headers":"Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');

        $returnOrderCode = str_replace('_pr', '', $baseGHNOrder['OrderCode']);
        $returnOrderCode = str_replace('_PR', '', $returnOrderCode);

        global $wpdb;
        $sql = $wpdb->prepare(
            "SELECT post_id FROM wp_postmeta WHERE meta_key=%s AND meta_value=%s LIMIT 1 OFFSET 0",
            array(
                'mulutu_order_code',
                strval($returnOrderCode)
            )
        );
        $postId = $wpdb->get_var($sql, 0, 0);
        if (!empty($postId)) {
            update_post_meta($postId, 'mulutu_order_code_pr', $baseGHNOrder['OrderCode']);
            echo json_encode(array(
                'status'  => 1,
                'message' => 'Cập nhật đơn giao trả 1 phần thành công' 
            ));
        } else {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Cập nhật đơn giao trả 1 phần không thành công' 
            ));
        }
        
        die();
    }
}
