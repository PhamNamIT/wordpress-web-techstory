<select name="mulutu_options[payment_type]" id="payment_type">
    <?php foreach ($options as $key => $value) { ?>
    <option value="<?php echo $key ?>"><?php echo $value; ?></option>
    <?php } ?>
</select>

<script type="text/javascript">
    var selectedEnv = "<?php echo $payment_type; ?>";
    document.querySelector('#payment_type').value = selectedEnv;
</script>