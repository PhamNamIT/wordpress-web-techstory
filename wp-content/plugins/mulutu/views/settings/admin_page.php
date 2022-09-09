<style type="text/css">
    .text-right {
        text-align: right;
    }
    .hint-block {
        border-left: 5px solid #1f93d8;
        padding-left: 5px;
        font-size: small;
        line-height: 1.5;
    }
    .hint-block .title {
        font-weight: bold;
    }
    .hint-block div {
        margin-top: 5px;
    }
</style>

<div class="wrap">
    <h2>Mulutu</h2>
    <?php settings_errors(); ?>
    <div id="messages"></div>
    <h2 class="nav-tab-wrapper">
        <a class="nav-tab nav-tab-general" href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => 'mulutu'))); ?>">
            Cài đặt chung
        </a>
        <a class="nav-tab nav-tab-shipping" href="<?php echo admin_url('admin.php?' . http_build_query(array('page' => 'mulutu', 'tab' => 'shipping'))); ?>">
            Vận chuyển
        </a>
    </h2>

    <form name="mulutu_settings" method="post" action="options.php">
        <?php
        settings_fields('mulutu_option_group');
        do_settings_sections(MulutuSettings::$ADMIN_PAGE_SLUG);
        submit_button();
        ?>
    </form>
</div>

<?php echo mulutuRenderView('settings/facebook_help'); ?>

<script type="text/javascript">
    (function() {
        document.querySelector('.nav-tab-<?php echo $tab ?>').classList.add('nav-tab-active');
    })();
</script>