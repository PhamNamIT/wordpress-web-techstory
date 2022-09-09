<?php

return array(
    'get_wc_order_info' => array(
        'uri' => '/wc-orders/:id',
        'reg_uri' => '/wc-orders/(?P<id>\d+)',
        'caller' => array(
            'methods'  => 'GET',
            'callback' => 'mulutuGetWCOrderInfo',
            'permission_callback' => '__return_true'
        )
    ),
    'update_wc_order_info' => array(
        'uri' => '/wc-orders/:id',
        'reg_uri' => '/wc-orders/(?P<id>\d+)',
        'caller' => array(
            'methods'  => 'POST',
            'callback' => 'mulutuUpdateWCOrderInfo',
            'permission_callback' => '__return_true'
        )
    ),
    'hook_update_wc_order_status' => array(
        'uri' => '/wc-orders/hook/order-status',
        'caller' => array(
            'methods'  => 'POST',
            'callback' => 'mulutuHookUpdateWCOrderStatus',
            'permission_callback' => '__return_true'
        )
    ),
    'hook_update_wc_order_ticket' => array(
        'uri' => '/wc-orders/hook/ticket',
        'caller' => array(
            'methods'  => 'POST',
            'callback' => 'mulutuHookUpdateWCOrderTicket',
            'permission_callback' => '__return_true'
        )
    ),
    'get_order_url' => array(
        'uri' => '/mulutu-orders/(?P<id>\d+)',
        'caller' => array(
            'methods'  => 'GET',
            'callback' => 'mulutuGetOrderUrl',
            'permission_callback' => '__return_true'
        )
    )
);
