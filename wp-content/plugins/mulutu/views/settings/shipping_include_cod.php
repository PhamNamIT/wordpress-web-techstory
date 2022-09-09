<label>
    <input type="checkbox" name="mulutu_options[include_cod_flg]" id="include_cod_flg" value="1">
    Thu hộ COD
</label>

<script type="text/javascript">
    document.querySelector('#include_cod_flg').checked = <?php echo $include_cod_flg == 1 ? "true" : "false"; ?>;
</script>