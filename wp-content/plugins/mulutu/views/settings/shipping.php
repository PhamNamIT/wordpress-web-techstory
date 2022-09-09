<input type="hidden" name="tab" value="shipping">
<label>
    <input type="checkbox" name="mulutu_options[auto_freeship_by_amount_flg]" id="auto_freeship_by_amount_flg" value="1">
    Áp dụng với hoá đơn có giá trị từ
</label>
<input type="text" size="10" maxlength="10" name="mulutu_options[auto_freeship_by_amount_value]" class="text-right" id="auto_freeship_by_amount_value" value="<?php echo $auto_freeship_by_amount_value; ?>"> <sup><strong>đ</strong></sup>

<div class="hint-block">
    <div>Với tuỳ chọn này, bên gửi sẽ tự thanh toán phí ship nếu giá trị hoá đơn lớn hơn hoặc bằng giá trị nhập bên trên</div>
</div>

<script type="text/javascript">
    document.querySelector('#auto_freeship_by_amount_flg').checked = <?php echo $auto_freeship_by_amount_flg == 1 ? 'true' : 'false'; ?>;
    document.querySelector('#auto_freeship_by_amount_value').disabled = !document.querySelector('#auto_freeship_by_amount_flg').checked;

    document.querySelector('#auto_freeship_by_amount_flg').addEventListener('change', function(event) {
        document.querySelector('#auto_freeship_by_amount_value').disabled = !event.currentTarget.checked;
    });
</script>