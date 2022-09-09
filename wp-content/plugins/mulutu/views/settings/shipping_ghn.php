<div class="shipping_method">
    <div class="status">
        <input type="checkbox" name="mulutu_options[shipping_brand]" id="shipping_brand_<?php echo $value; ?>" value="<?php echo $value; ?>">
    </div>
    <div class="content">
        <div class="title"><label for="shipping_brand_<?php echo $value; ?>"><?php echo $title; ?></label></div>
        <div class="sub-content">
            <div class="form-control">
                <label for="ghn_token" class="input-required">Token</label>
                <input class="regular-text" type="text" name="mulutu_options[ghn_token]" id="ghn_token" value="<?php echo $ghn_token; ?>">
            </div>
            <div class="form-control">
                <label for="ghn_registered_phone" class="input-required">Số điện thoại (Đã đăng ký với GHN)</label>
                <input class="regular-text" maxlength="20" type="text" name="mulutu_options[ghn_registered_phone]" id="ghn_registered_phone" value="<?php echo $ghn_registered_phone; ?>">
            </div>
        </div>
        <div class="hint">
            <a target="_blank" href="https://sso.ghn.vn/register">Đăng ký tài khoản <strong>Giao Hàng Nhanh</strong></a>
        </div>
    </div>
</div>