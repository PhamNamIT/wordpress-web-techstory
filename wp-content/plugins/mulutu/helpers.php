<?php

if (!function_exists('mulutuRenderView')) {
    function mulutuRenderView($path, $data = null) {
        if (!file_exists(MULUTU_ROOT . "/views/{$path}.php")) {
            return '';
        }
        if (is_array($data)){
            extract($data);
        }
        ob_start();
        include MULUTU_ROOT . "/views/{$path}.php";
        return ob_get_clean();
    }
}

if (!function_exists('mulutuAddOrderHookItem')) {
    function mulutuAddOrderHookItem($orderCode, $content, $createdAt, $type = 'history') {
        global $wpdb;
        $wpdb->insert('wp_mulutu_ghn_webhook', array(
            'order_code' => $orderCode,
            'type'       => $type,
            'content'    => $content,
            'created_at' => $createdAt
        ));
    }
}

if (!function_exists('mulutuGetOrderHookItems')) {
    function mulutuGetOrderHookItems($orderCode, $type = null) {
        global $wpdb;
        $query = "SELECT order_code, type, content, created_at FROM wp_mulutu_ghn_webhook WHERE order_code=%s";
        $params = array(
            strval($orderCode)
        );
        if (!empty($type)) {
            $query .= ' AND type=%s';
            $params[] = $type;
        }

        return $wpdb->get_results($wpdb->prepare($query, $params));
    }
}

if (!function_exists('mulutuGetDefaultOrderWeight')) {
    function mulutuGetDefaultOrderWeight($value) {
        global $mulutuOptions;
        $weight = intval(round($value, 0, PHP_ROUND_HALF_UP));
        if ($weight == 0) {
            return $mulutuOptions['shipping_default_weight_value'] > 0 ? $mulutuOptions['shipping_default_weight_value'] : 1000;
        }
        if ($weight < 1) {
            return 1;
        }
        return $weight;
    }
}

if (!function_exists('mulutuGetDefaultOrderDimensions')) {
    function mulutuGetDefaultOrderDimensions($key, $value) {
        global $mulutuOptions;
        $dim = intval(round($value, 0, PHP_ROUND_HALF_UP));
        if ($dim == 0) {
            return $mulutuOptions['shipping_default_dimension_value'][$key] > 0 ? $mulutuOptions['shipping_default_dimension_value'][$key] : 10;
        }
        if ($dim < 1) {
            return 1;
        }
        return $dim;
    }
}

if (!function_exists('mulutuOption')) {
    function mulutuOption($key, $default = null) {
        global $mulutuOptions;
        return isset($mulutuOptions[$key]) ? $mulutuOptions[$key] : $default;
    }
}

if (!function_exists('mulutuEnv')) {
    function mulutuEnv() {
        global $mulutuOptions;
        
        return !empty($mulutuOptions['env']) ? $mulutuOptions['env'] : 'dev';
    }
}

if (!function_exists('mulutuConf')) {
    function mulutuConf($key, $env = 'local') {
        $conf = include(MULUTU_ROOT . '/const.php');
        $env = mulutuEnv();
        if (file_exists(MULUTU_ROOT . '/.env.local')) {
            $env = 'local';
        }
        return !empty($conf[$key][$env]) ? $conf[$key][$env] : null;
    }
}

if (!function_exists('mulutuRoute')) {
    function mulutuRoute($key, $queries = array(), $includedRoutes = null) {
        $routes = $includedRoutes != null ? $includedRoutes : include(MULUTU_ROOT . '/routes.php');
        if (!empty($queries)) {
            return $routes[$key] . '?' . http_build_query($queries);
        }
        return $routes[$key];
    }
}
