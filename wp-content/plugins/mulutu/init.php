<?php

// Add ghn hook items table
if (!function_exists('do_mulutu_app_init_tasks')) {
    function do_mulutu_app_init_tasks() {
        global $wpdb;

        /**
         * @todo create wp_mulutu_ghn_webhook
         */
        $charset = $wpdb->get_charset_collate();
        $sql = "
            CREATE TABLE IF NOT EXISTS `wp_mulutu_ghn_webhook` (
                `id` int(10) NOT NULL AUTO_INCREMENT,
                `order_code` varchar(100) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
                `type` varchar(15) COLLATE utf8mb4_unicode_520_ci NOT NULL,
                `content` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
                `created_at` datetime DEFAULT NULL,
                PRIMARY KEY  (id)
            ) $charset;
        ";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);

        /**
         * @todo resync ghn-wc orders
         * Get all post which had ghn_order_code and parent not have mulutu_order_code, then sync post meta
         * meta_id > 8365 || post_id > 1625
         */
        $sql = $wpdb->prepare("
            SELECT post_parent, ID 
            FROM `wp_posts` 
            WHERE ID IN (SELECT DISTINCT post_id FROM `wp_postmeta` WHERE meta_key = 'ghn_order_code') 
                AND post_parent not IN (SELECT DISTINCT post_id FROM `wp_postmeta` WHERE meta_key = 'mulutu_order_code')
        ");
        $needSyncPostsIds = $wpdb->get_results($sql);
        if (empty($needSyncPostsIds)) {
            return;
        }
        $needSyncPostsIdsReIndexed = array();
        foreach ($needSyncPostsIds as $item) {
            $needSyncPostsIdsReIndexed[$item->ID] = $item->post_parent;
        }
        $oldPluginPostsIds = array_map(function($p) {
            return $p->ID;
        }, $needSyncPostsIds);

        // Get sync post metas
        $sqlWhereInPlaceholders = array_fill(0, count($oldPluginPostsIds), '%d');
        $sql = $wpdb->prepare(
            "SELECT post_id, meta_key, meta_value FROM `wp_postmeta` WHERE post_id IN (" . implode(',', $sqlWhereInPlaceholders) . ")",
            $oldPluginPostsIds
        );
        $syncPostsMetas = $wpdb->get_results($sql);
        foreach ($syncPostsMetas as $index => $item) {
            if (strpos($item->meta_key, 'ghn_') !== false) {
                $syncPostsMetas[$index]->meta_key = str_replace('ghn_', 'mulutu_', $item->meta_key);
            }
            $syncPostsMetas[$index]->post_id = $needSyncPostsIdsReIndexed[$syncPostsMetas[$index]->post_id];
            $syncPostsMetas[$index] = (array)$syncPostsMetas[$index];
        }

        foreach ($syncPostsMetas as $meta) {
            $wpdb->insert(
                'wp_postmeta', 
                array(
                    'post_id'    => $meta['post_id'],
                    'meta_key'   => $meta['meta_key'],
                    'meta_value' => $meta['meta_value']
                ), 
                array('%d', '%s', is_numeric($meta['meta_value']) ? '%d' : '%s')
            );
        }
    }
}
