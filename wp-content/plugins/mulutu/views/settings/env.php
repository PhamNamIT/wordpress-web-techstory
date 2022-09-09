<select name="mulutu_options[env]" id="env">
    <?php foreach ($options as $key => $value) { ?>
    <option value="<?php echo $key ?>"><?php echo $value; ?></option>
    <?php } ?>
</select>

<script type="text/javascript">
    var selectedEnv = "<?php echo $env; ?>";
    document.querySelector('#env').value = selectedEnv;
</script>