<?php

if ( ! class_exists( 'MulutuSettings' ) ) {
    class MulutuSettings {

        public static $ADMIN_PAGE_SLUG = 'mulutu-admin';

        private $mulutuOptions;
        private $settingsTab           = 'general';
        private $settingsTabsAvailable = array(
            'general', 'shipping'
        );

        public function __construct($mulutuOptions) {
            $this->mulutuOptions = $mulutuOptions;

            if (!empty($_REQUEST['tab'])) {
                $this->settingsTab = sanitize_text_field($_REQUEST['tab']);
                if (!in_array($this->settingsTab, $this->settingsTabsAvailable)) {
                    $this->settingsTab = 'general';
                }
            }

            add_action('admin_menu', array($this, 'mulutuAddPluginPage'));
            add_action('admin_init', array($this, 'mulutuPageInit'));
            add_action('admin_enqueue_scripts', array($this, 'loadSettingAssets'));
        }

        public function mulutuAddPluginPage() {
            add_menu_page(
                'Tuỳ chỉnh', // page_title
                'Mulutu', // menu_title
                'manage_options', // capability
                'mulutu', // menu_slug
                array($this, 'mulutuCreateAdminPage'), // function
                'dashicons-products', // icon_url
                55 // position
            );
            add_submenu_page(
                'mulutu',
                'Tuỳ chỉnh', // page_title
                'Tuỳ chỉnh', // menu_title
                'manage_options',
                'mulutu'
            );
            add_submenu_page(
                'mulutu',
                'Liên hệ',
                'Liên hệ',
                'manage_options',
                'https://m.me/mulutu.vn'
            );
        }

        public function mulutuPageInit() {
            register_setting(
                'mulutu_option_group', // option_group
                'mulutu_options', // option_name
                array($this, 'mulutuSanitize') // sanitize_callback
            );

            add_settings_section(
                'mulutu_setting_section', // id
                null, // title
                null, // callback
                MulutuSettings::$ADMIN_PAGE_SLUG // page
            );

            if ($this->settingsTab === 'general') {
                $this->addSettingsGeneral();
                return;
            }

            if ($this->settingsTab === 'shipping') {
                $this->addSettingsShippingFee();
                return;
            }
        }

        public function loadSettingAssets($hook) {
            if($hook == 'toplevel_page_mulutu') {
                if($this->settingsTab == 'general') {
                    $platformAPIURL = mulutuConf('platform_api_url');

                    wp_enqueue_style('mulutu-settings-select2-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/select2.min.css'));
                    wp_enqueue_script('mulutu-settings-select2-js', plugins_url(MULUTU_PLUGIN_NAME . '/js/select2.min.js'));
                    wp_enqueue_style('mulutu-loader-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/loader.css'));
                    wp_enqueue_style('mulutu-otp-modal-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/jquery.modal.min.css'));
                    wp_enqueue_script('mulutu-otp-modal-js', plugins_url(MULUTU_PLUGIN_NAME . '/js/jquery.modal.min.js'));
                    wp_enqueue_style('mulutu-settings-css', plugins_url(MULUTU_PLUGIN_NAME . '/css/settings.css'));
                    wp_enqueue_script('mulutu-settings-js', plugins_url(MULUTU_PLUGIN_NAME . '/js/settings.js'), array('jquery'), false, true);

                    wp_localize_script('mulutu-settings-js', 'Settings', array(
                        'ghn_token'   => mulutuOption('ghn_token'),
                        'ghn_registered_phone' => mulutuOption('ghn_registered_phone'),
                        'ghn_shop_id' => mulutuOption('ghn_shop_id', ''),
                        'webhook_url' => MULUTU_PLUGIN_NAME . '/v1/wc-orders/hook/order-status',
                        'platform_api_url' => array(
                            'otp'                         => $platformAPIURL . '/api/v1/otp',
                            'shops'                       => $platformAPIURL . '/api/v1/shops',
                            'districts'                   => $platformAPIURL . '/api/v1/address/districts',
                            'wards'                       => $platformAPIURL . '/api/v1/address/:district_id/wards',
                            'create_affiliate'            => $platformAPIURL . '/api/v1/affiliate/create',
                            'create_affiliate_with_shop'  => $platformAPIURL . '/api/v1/affiliate/create/with-shop',
                            'create_shop_webhook_mapping' => $platformAPIURL . '/api/v1/shops/ghn-webhook-mapping',
                        )
                    ));
                }
            }
        }

        private function addSettingsGeneral () {
            add_settings_field(
                'env', // id
                'Môi trường', // title
                array($this, 'envCallback'), // callback
                MulutuSettings::$ADMIN_PAGE_SLUG, // page
                'mulutu_setting_section' // section
            );

            add_settings_field(
                'shipping_brand', // id
                'Nhà vận chuyển', // title
                array($this, 'shippingBrandCallback'), // callback
                MulutuSettings::$ADMIN_PAGE_SLUG, // page
                'mulutu_setting_section' // section
            );

            if (!empty($this->mulutuOptions['ghn_token'])) {
                add_settings_field(
                    'shops', // id
                    'Cửa hàng', // title
                    array($this, 'shopsListCallback'), // callback
                    MulutuSettings::$ADMIN_PAGE_SLUG, // page
                    'mulutu_setting_section' // section
                );
            }
        }

        private function addSettingsShippingFee () {
            add_settings_field(
                'payment_type', // id
                'Thanh toán phí', // title
                array($this, 'paymentTypeCallback'), // callback
                MulutuSettings::$ADMIN_PAGE_SLUG, // page
                'mulutu_setting_section' // section
            );

            add_settings_field(
                'auto_freeship_by_amount_flg', // id
                'Freeship', // title
                array($this, 'shippingFeeCallback'), // callback
                MulutuSettings::$ADMIN_PAGE_SLUG, // page
                'mulutu_setting_section' // section
            );

            add_settings_field(
                'default_weight_value', // id
                'Khối lượng', // title
                array($this, 'shippingDefaultWeightCallback'), // callback
                MulutuSettings::$ADMIN_PAGE_SLUG, // page
                'mulutu_setting_section' // section
            );

            add_settings_field(
                'default_dimension_value', // id
                'Kích thước', // title
                array($this, 'shippingDefaultDimensionCallback'), // callback
                MulutuSettings::$ADMIN_PAGE_SLUG, // page
                'mulutu_setting_section' // section
            );

            add_settings_field(
                'include_cod_flg', // id
                'Thu hộ COD ', // title
                array($this, 'shippingIncludeCODCallback'), // callback
                MulutuSettings::$ADMIN_PAGE_SLUG, // page
                'mulutu_setting_section' // section
            );
        }

        public function mulutuSanitize($input) {
            $sanitaryValues = array();

            $checkKeys = array(
                'env', 
                'shipping_brand',
                'ghn_token',
                'ghn_registered_phone',
                'ghn_shop_id',
                'ghn_shop_name',
                'ghn_shop_phone',
                'ghn_shop_address',
                'ghn_district_id',
                'ghn_ward_code',
                'ghn_district_name',
                'ghn_ward_name',
                'payment_type'
            );
            foreach($checkKeys as $key) {
                if (isset($input[$key])) {
                    $sanitaryValues[$key] = sanitize_text_field($input[$key]);
                }
            }

            $sanitaryValues['ghn_token'] = !empty($sanitaryValues['ghn_token']) ? $sanitaryValues['ghn_token'] : $this->mulutuOptions['ghn_token'];
            if (empty($sanitaryValues['ghn_token'])) {
                add_settings_error('token', '400', 'Token không hợp lệ');
                return false;
            }

            $sanitaryValues['ghn_registered_phone'] = !empty($sanitaryValues['ghn_registered_phone']) ? $sanitaryValues['ghn_registered_phone'] : $this->mulutuOptions['ghn_registered_phone'];
            if (empty($sanitaryValues['ghn_registered_phone'])) {
                add_settings_error('ghn_registered_phone', '400', 'Số điện thoại không hợp lệ');
                return false;
            }
            if (!empty($sanitaryValues['ghn_registered_phone']) && !preg_match('/^\d{9,12}$/', $sanitaryValues['ghn_registered_phone'])) {
                add_settings_error('ghn_registered_phone', '400', 'Số điện thoại không hợp lệ');
                return false;
            }

            // Freeship
            if (isset($input['auto_freeship_by_amount_value'])) {
                $minAmount = sanitize_text_field($input['auto_freeship_by_amount_value']);
                $sanitaryValues['auto_freeship_by_amount_value'] = (is_numeric($minAmount) && $minAmount < 100000000000) ? $minAmount : 0;
            }
            if (isset($input['auto_freeship_by_amount_flg'])) {
                $sanitaryValues['auto_freeship_by_amount_flg'] = sanitize_text_field($input['auto_freeship_by_amount_flg']) == 1 ? 1 : 0;
            } else {
                if ($this->settingsTab == 'shipping') {
                    $sanitaryValues['auto_freeship_by_amount_flg'] = 0;
                    // $sanitaryValues['auto_freeship_by_amount_value'] = 0;
                }
            }

            // Default weight
            if (isset($input['shipping_default_weight_flg'])) {
                $sanitaryValues['shipping_default_weight_flg'] = sanitize_text_field($input['shipping_default_weight_flg']) == 1 ? 1 : 0;

                $sanitaryValues['shipping_default_weight_value'] = sanitize_text_field($input['shipping_default_weight_value']);
                if (!is_numeric($sanitaryValues['shipping_default_weight_value'])) {
                    add_settings_error(
                        'shipping_default_weight_value',
                        'err_shipping_default_weight_value',
                        'Giá trị của khối lượng mặc định không hợp lệ'
                    );
                    $sanitaryValues['shipping_default_weight_value'] = 0;
                }
            }

            // Default dimension
            if (isset($input['shipping_default_dimension_flg'])) {
                $sanitaryValues['shipping_default_dimension_flg'] = sanitize_text_field($input['shipping_default_dimension_flg']) == 1 ? 1 : 0;

                $dimensionsKey = array('width', 'height', 'length');
                foreach ($dimensionsKey as $key) {
                    if (isset($input['shipping_default_dimension_value'][$key])) {
                        $sanitaryValues['shipping_default_dimension_value'][$key] = sanitize_text_field($input['shipping_default_dimension_value'][$key]);
                        if (!is_numeric($sanitaryValues['shipping_default_dimension_value'][$key])) {
                            add_settings_error(
                                'shipping_default_dimension_value',
                                'err_shipping_default_dimension_value',
                                'Giá trị của kích thước mặc định không hợp lệ'
                            );
                            $sanitaryValues['shipping_default_dimension_value'][$key] = 0;
                        }
                    }
                }
            }

            $sanitaryValues['include_cod_flg'] = 0;
            if (isset($input['include_cod_flg'])) {
                if(sanitize_text_field($input['include_cod_flg']) == 1) {
                    $sanitaryValues['include_cod_flg'] = 1;
                }
            }

            if ($this->mulutuOptions == '') {
                $this->mulutuOptions = array();
            }

            return array_merge($this->mulutuOptions, $sanitaryValues);
        }

        public function mulutuCreateAdminPage() {
            echo mulutuRenderView('settings/admin_page', array(
                'tab' => $this->settingsTab
            ));
        }

        public function envCallback() {
            $env = mulutuEnv();

            $options = array(
                'dev'   => 'Test',
                'prod'  => 'Production'
            );

            echo mulutuRenderView('settings/env', array(
                'options' => $options,
                'env' => $env 
            ));
        }

        public function shippingBrandCallback() {
            $shippingBrand = esc_attr(mulutuOption('shipping_brand', 'mulutu'));

            echo mulutuRenderView('settings/shipping_brand', array(
                'selectedShippingBrand' => $shippingBrand,
                'shippingGHNSetting' => mulutuRenderView('settings/shipping_ghn', array(
                    'title'                => 'GHN',
                    'value'                => 'ghn',
                    'ghn_token'            => mulutuOption('ghn_token'),
                    'ghn_registered_phone' => mulutuOption('ghn_registered_phone')
                ))
            ));
        }

        public function shopsListCallback() {
            if (empty($this->mulutuOptions['ghn_token'])) {
                return;
            }

            echo mulutuRenderView('settings/shops', array('shopInfo' => $this->mulutuOptions));
        }

        public function paymentTypeCallback() {
            echo mulutuRenderView('settings/payment_type', array(
                'options' => array(
                    1 => 'Bên gửi trả phí',
                    2 => 'Bên nhận trả phí'
                ),
                'payment_type' => mulutuOption('payment_type', 2)
            ));
        }

        public function shippingFeeCallback() {
            echo mulutuRenderView('settings/shipping', array(
                'auto_freeship_by_amount_flg'   => mulutuOption('auto_freeship_by_amount_flg', 0),
                'auto_freeship_by_amount_value' => mulutuOption('auto_freeship_by_amount_value', 0)
            ));
        }

        public function shippingDefaultWeightCallback() {
            echo mulutuRenderView('settings/shipping_default_weight', array(
                'shipping_default_weight_flg'   => mulutuOption('shipping_default_weight_flg', 0),
                'shipping_default_weight_value' => mulutuOption('shipping_default_weight_value', false) && $this->mulutuOptions['shipping_default_weight_value'] > 0 ? $this->mulutuOptions['shipping_default_weight_value'] : 0
            ));
        }

        public function shippingDefaultDimensionCallback() {
            echo mulutuRenderView('settings/shipping_default_dimension', array(
                'shipping_default_dimension_flg'   => mulutuOption('shipping_default_dimension_flg', 0),
                'shipping_default_dimension_value' => mulutuOption('shipping_default_dimension_value', false) && is_array($this->mulutuOptions['shipping_default_dimension_value']) ? $this->mulutuOptions['shipping_default_dimension_value'] : ['width' => 0, 'length' => 0, 'height' => 0]
            ));
        }

        public function shippingIncludeCODCallback() {
            echo mulutuRenderView('settings/shipping_include_cod', array(
                'include_cod_flg' => mulutuOption('include_cod_flg', 1)
            ));
        }
    }
}
