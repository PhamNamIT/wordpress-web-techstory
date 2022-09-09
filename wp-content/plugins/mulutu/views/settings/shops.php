<?php
$needUpdateAffiliate = false;
if(!empty($_REQUEST['settings-updated']) && sanitize_text_field($_REQUEST['settings-updated']) == 'true') {
    $needUpdateAffiliate = true;
}
if (empty($shopInfo['ghn_shop_id'])) {
    $needUpdateAffiliate = false;
}
?>
<script type="text/javascript">
    var needUpdateAffiliate = <?php echo $needUpdateAffiliate ? 'true' : 'false'; ?>;
</script>

<p>
    <a href="#" class="button btn-shop-edit">Chọn cửa hàng có sẵn</a> 
    HOẶC 
    <a href="#" class="button btn-shop-create">Tạo cửa hàng mới</a>
</p>

<div id="shops-list"></div>

<?php
$hiddenFields = array(
    'ghn_district_id',
    'ghn_ward_code',
    'ghn_shop_id',
    'ghn_shop_name',
    'ghn_shop_phone',
    'ghn_shop_address',
    'ghn_district_name',
    'ghn_ward_name'
);
foreach ($hiddenFields as $field) {
    $value = isset($shopInfo[$field]) ? $shopInfo[$field] : '';
    $shopInfo[$field] = $value;
    echo "<input type='hidden' id='mulutu_options_{$field}' name='mulutu_options[{$field}]' value='{$value}' />";
}
?>
<table id="shop-form" class="form-table d-none">
    <tbody>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label>Địa chỉ</label>
            </th>
            <td class="forminp forminp-text">
                <input id="view_ghn_shopaddress" type="text">
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label>Quận/Huyện</label>
            </th>
            <td class="forminp forminp-text">
                <select class="select2" id="view_ghn_shop_districts"></select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                <label>Phường/Xã</label>
            </th>
            <td class="forminp forminp-text">
                <select class="select2" id="view_ghn_shop_wards"></select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                &nbsp;
            </th>
            <td class="forminp forminp-text">
                <label><input id="view_ghn_shopward" type="checkbox"> Mặc định</label>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row" class="titledesc">
                &nbsp;
            </th>
            <td class="forminp forminp-text">
                <button class="button button-primary btn-submit-shop">Thêm mới</button>
                <button class="button btn-cancel">Huỷ</button>
            </td>
        </tr>
    </tbody>
</table>

<?php if(!empty($shopInfo['ghn_shop_name'])) { ?>
    <div id="shop-info">
        <div class="shop-info__item">
            <span class="titledesc">Tên cửa hàng:</span>
            <span class="forminp forminp-text">
                <?php echo $shopInfo['ghn_shop_name']; ?>
            </span>
        </div>
        <div class="shop-info__item">
            <span class="titledesc">Điện thoại:</span>
            <span class="forminp forminp-text">
                <?php echo $shopInfo['ghn_shop_phone']; ?>
            </span>
        </div>
        <div class="shop-info__item">
            <span class="titledesc">Địa chỉ:</span>
            <span class="forminp forminp-text">
                <?php echo $shopInfo['ghn_shop_address']; ?>, 
                <?php echo $shopInfo['ghn_ward_name']; ?>, 
                <?php echo $shopInfo['ghn_district_name']; ?>
            </span>
        </div>
    </div>
<?php } ?>

<div id="otp-modal" class="modal">
    <div class="loader-wrapper">
        <div class="message"></div>
        <div class="loader">
            <div class="bar1"></div>
            <div class="bar2"></div>
            <div class="bar3"></div>
            <div class="bar4"></div>
            <div class="bar5"></div>
            <div class="bar6"></div>
        </div>
    </div>

    <div class="content d-none">
        <h3>Vui lòng nhập OTP để xác nhận</h3>
        <div>
            <input type="text" id="otp" placeholder="123456" autocomplete="off">
            <button class="button button-primary" id="btn-otp-submit">Xác nhận</button>
            <p id="otp-message"></p>
        </div>
        <p>Gửi lại mã OTP: <span id="countdown"></span></p>
    </div>
</div>
