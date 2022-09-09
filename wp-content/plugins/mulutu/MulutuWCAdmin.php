<?php

$platformURL = mulutuConf('platform_url');
$ghnOrderTrackingURL = mulutuConf('order_tracking_url');
$callbackUrlSample = site_url('?rest_route=/' . MULUTU_PLUGIN_NAME . '/v1/wc-orders/');

$GHNStatus = include_once (MULUTU_ROOT . '/GHNStatus.php');

/**
 * Custom woocommerce
 */
class MulutuWCAdmin {

    function addShippingBrandStatusAdminListColumn( $columns ) {
        wp_enqueue_style('mulutu-order-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/order.css'));
        wp_enqueue_script('mulutu-wc-order-js', plugins_url(MULUTU_PLUGIN_NAME . '/js/order.js'), array('jquery'), true);
        wp_localize_script('mulutu-wc-order-js', 'GHN_ORDER_STATUS', include(MULUTU_ROOT . '/GHNStatus.php'));

        $columns['shipping_brand_status'] = 'Trạng thái vận chuyển';
        $columns['shipping_brand_action'] = '';
        return $columns;
    }

    function addShippingBrandStatusAdminListColumnContent($column) {
        global $post;

        $orderStatus = null;
        $orderCode = null;

        if ($column == 'shipping_brand_status') {
            if (empty($orderStatus)) {
                $orderStatus = get_post_meta($post->ID, 'mulutu_order_status', true);
            }

            if (empty($orderCode)) {
                $orderCode = get_post_meta($post->ID, 'mulutu_order_code', true);
            }

            if (!empty($orderStatus)) {
                global $GHNStatus;
                echo $GHNStatus[$orderStatus];
            }
            return;
        }

        if ($column == 'shipping_brand_action') {
            $isVirtualOrder = true;

            $order = wc_get_order($post->ID);
            if ($order) {
                foreach ($order->get_items() as $itemId => $item) {
                    $product = wc_get_product($item->get_product_id());
                    if (!$product->is_virtual()) {
                        $isVirtualOrder = false;
                        break;
                    }
                }
                if ($isVirtualOrder) {
                    return null;
                }
            }

            global $platformURL;
            global $callbackUrlSample;

            $preOrderInfoAPIURL = "/?rest_route=/mulutu/v1/mulutu-orders/{$post->ID}";

            if (empty($orderCode)) {
                $orderCode = get_post_meta($post->ID, 'mulutu_order_code', true);
            }

            if(!empty($orderCode)) {
                $orderInfoAPIURL = "{$platformURL}/orders/{$orderCode}/frame?" . http_build_query(array(
                    'cb' => $callbackUrlSample . $post->ID
                ));
                echo '<a href="' . $orderInfoAPIURL . '" class="button btn-wclist-order-detail" data-api-url="' . $preOrderInfoAPIURL . '">Đơn hàng ' . $orderCode . '</a>';

                $orderStatus = get_post_meta($post->ID, 'mulutu_order_status', true);
                if (!empty($orderStatus)) {
                    $prOrderCode = get_post_meta($post->ID, 'mulutu_order_code_pr', true);
                    if (!empty($prOrderCode)) {
                        global $ghnOrderTrackingURL;
                        $referenceUrl = $ghnOrderTrackingURL . $prOrderCode;
                        echo "<a style='color: orange; display:block; margin-top:8px;' target='_blank' href='{$referenceUrl}'>
                        <strong>{$prOrderCode}</strong> 
                        <span style='color:#333'>(Giao trả 1 phần)</span>
                        </a>";
                    }
                }

                return;
            }

            echo '<a href="#" class="button btn-wclist-order" data-api-url="' . $preOrderInfoAPIURL . '">Tạo đơn hàng</a>';
            return;
        }
    }

    function customOrderDetail($post) {
        echo mulutuRenderView('meta_box_order', array(
            'orderInfoAPIURL'   => "/?rest_route=/mulutu/v1/mulutu-orders/{$post->ID}",
            'wcOrderInfoAPIURL' => "/?rest_route=/mulutu/v1/wc-orders/{$post->ID}"
        ));
    }
}

$objMulutuWCAdmin = new MulutuWCAdmin();

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * @todo Add WC order list [Order action] column
 */
add_filter('manage_edit-shop_order_columns', array($objMulutuWCAdmin, 'addShippingBrandStatusAdminListColumn'));
add_action('manage_shop_order_posts_custom_column', array($objMulutuWCAdmin, 'addShippingBrandStatusAdminListColumnContent'));

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/**
 * @todo Custom wc order detail page
 * @todo Add action button
 */
add_action('add_meta_boxes', function() {
    global $post;
    $isVirtualOrder = true;
    $order = wc_get_order($post->ID);
    if ($order) {
        foreach ($order->get_items() as $itemId => $item) {
            $product = wc_get_product($item->get_product_id());
            if (!$product->is_virtual()) {
                $isVirtualOrder = false;
                break;
            }
        }
        if ($isVirtualOrder) {
            return;
        }
    }

    global $objMulutuWCAdmin;

    wp_enqueue_style('mulutu-order-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/order.css'));
    wp_enqueue_script('mulutu-wc-order-js', plugins_url(MULUTU_PLUGIN_NAME . '/js/order.js'), array('jquery'), true);
    wp_localize_script('mulutu-wc-order-js', 'GHN_ORDER_STATUS', include(MULUTU_ROOT . '/GHNStatus.php'));

    add_meta_box('mulutu-shop-order', 'Đơn hàng', array($objMulutuWCAdmin, 'customOrderDetail'), 'shop_order', 'side', 'high');
});
