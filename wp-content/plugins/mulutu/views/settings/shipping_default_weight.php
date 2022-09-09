<label>
    <input type="radio" name="mulutu_options[shipping_default_weight_flg]" class="shipping_default_weight_flg" id="shipping_default_weight_flg_sum_all" value="1">
    Bằng tổng khối lượng các đồ vật trong đơn hàng
</label>
<br>
<label>
    <input type="radio" name="mulutu_options[shipping_default_weight_flg]" class="shipping_default_weight_flg" id="shipping_default_weight_flg" value="0">
    Giá trị mặc định
</label>
<input type="text" size="10" maxlength="10" class="text-right" name="mulutu_options[shipping_default_weight_value]" id="shipping_default_weight_value" value="<?php echo $shipping_default_weight_value; ?>"> <sub><strong>gam</strong></sub>

<script type="text/javascript">
    document.querySelectorAll('.shipping_default_weight_flg').forEach(function(item) {
        item.checked = (item.value == <?php echo $shipping_default_weight_flg; ?>);
    });
</script>