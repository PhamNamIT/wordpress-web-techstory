<div class="shipping_method_wrapper">
    <?php echo $shippingGHNSetting; ?>
</div>

<script>
    var selectedShippingBrand = document.querySelector('#shipping_brand_<?php echo $selectedShippingBrand; ?>');
    if(!!selectedShippingBrand) {
        selectedShippingBrand.checked = true;
    }
</script>