<style type="text/css">
.default_dimension {
    display: block !important;
    border-left: 4px solid #DEDEDE;
    margin-left: 30px;
}
.default_dimension li {
    margin-left: 5px;
}
</style>

<label>
    <input type="radio" name="mulutu_options[shipping_default_dimension_flg]" class="shipping_default_dimension_flg" id="shipping_default_dimension_flg_sum_all" value="1">
    Tổng các kích thước từng món của đơn hàng
</label>
<br>
<label>
    <input type="radio" name="mulutu_options[shipping_default_dimension_flg]" class="shipping_default_dimension_flg" id="shipping_default_dimension_flg" value="0">
    Giá trị mặc định
</label>
<ul class="default_dimension">
    <li>
        <label>Dài
            <input type="text" size="10" maxlength="10" name="mulutu_options[shipping_default_dimension_value][length]" class="shipping_default_dimension_value text-right" id="shipping_default_dimension_value_length" value="<?php echo $shipping_default_dimension_value['length']; ?>">
            <sub><strong>cm</strong></sub>
        </label>
    </li>
    <li>
        <label>Rộng
            <input type="text" size="10" maxlength="10" name="mulutu_options[shipping_default_dimension_value][width]" class="shipping_default_dimension_value text-right" id="shipping_default_dimension_value_width" value="<?php echo $shipping_default_dimension_value['width']; ?>">
            <sub><strong>cm</strong></sub>
        </label>
    </li>
    <li>
        <label>Cao
            <input type="text" size="10" maxlength="10" name="mulutu_options[shipping_default_dimension_value][height]" class="shipping_default_dimension_value text-right" id="shipping_default_dimension_value_height" value="<?php echo $shipping_default_dimension_value['height']; ?>">
            <sub><strong>cm</strong></sub>
        </label>
    </li>
</ul>

<script type="text/javascript">
    document.querySelectorAll('.shipping_default_dimension_flg').forEach(function(item) {
        item.checked = (item.value == <?php echo $shipping_default_dimension_flg; ?>);
    });
</script>

<div class="hint-block">
    <div class="title">Lưu ý:</div>
    <div> - Khi không thể tính toán được khối lượng hoặc kích thước của đơn hàng (ví dụ: tổng khối lượng đơn hàng bằng 0) <br>thì các giá trị mặc định tương ứng sẽ được sử dụng để tính toán phí vận chuyển.</div>
    <div> - Khi không thể tính toán được khối lượng hoặc kích thước của đơn hàng và các giá trị mặc định (khối lượng, kích thước) tương ứng bằng 0 <br>thì MULUTU sẽ sử dụng khối lượng mặc định là <strong>1000gam</strong>, kích thước mặc định(dài x rộng x cao) là <strong>10x10x10cm</strong> để tính toán phí vận chuyển.</div>
</div>