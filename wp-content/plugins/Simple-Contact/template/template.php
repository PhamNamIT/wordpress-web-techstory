<?php
if (get_option('desktop_location')=='right'){
    $class_device='aml_dk-wrap aml_dk-desktop aml_dk-style-gradient-default aml_dk-style-default aml_dk-bottom-right aml_dk-md aml_dk-channel-4';
}else{
    $class_device='aml_dk-wrap aml_dk-desktop aml_dk-style-gradient-default aml_dk-style-default aml_dk-bottom-left aml_dk-md aml_dk-channel-4';
}
if (get_option('mobile_location')=='bottom-right'){
    $class_device_mobile='aml_dk-wrap aml_dk-mobile aml_dk-style-default aml_dk-bottom-right aml_dk-md aml_dk-channel-4';
}else{
    $class_device_mobile='aml_dk-wrap aml_dk-mobile aml_dk-style-horizontal-default aml_dk-style-default aml_dk-bottom-center aml_dk-md aml_dk-channel-4';
}
?>

<script type="text/javascript">
if(screen.width <=767){
    <?php $screen = 'mobile';?>
}else{
    <?php $screen = 'default';?>
}
</script>

<?php
function isMobileDevice() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

if(isMobileDevice()){ ?>
<div class="simple-contact-mobile">
   <div id="aml_dk_wrap" class="<?php echo $class_device_mobile; ?>" style="z-index: 2147483647;">
      <a target="_blank" href="https://tiepthitute.com" class="aml-powered-by aml-pb-style-horizontal-default aml_dk-style-default" style="color: #555; ">Developed by <span class="aml-powered-by-b">Tiepthitute</span></a>
      <div class="aml_dk-flex-container aml-flc-style-horizontal-default aml_dk-style-default" <?php if (get_option('mobile_location')=='bottom-center'){ ?>  style=" background: #fff;" <?php } ?>>
          <?php if (get_option('location_mobile') =='location_mobile'){ ?>
         <div class="aml_dk-flex-item aml_dk-channel-google_map ">
         <a target="_blank" href="<?php echo get_option('tdh_location'); ?>">
            <?php if (get_option('mobile_location')=='bottom-center'){ ?>
            <span class="aml-text-content ">Bản đồ</span>
            <?php  } ?>
             </a>
         </div>
         <?php  } ?>
        <?php  if (get_option('mobile_location')=='bottom-right') { ?>
            <?php if (get_option('messenger_mobile') =='messenger_mobile'){ ?>
         <div class="aml_dk-flex-item aml_dk-channel-facebook ">
          <a href="https://m.me/<?php echo get_option('tdh_messenger'); ?>" target="_blank">
          <?php if (get_option('mobile_location')=='bottom-center'){ ?>
            <span class="aml-text-content ">Messenger</span>
             <?php  } ?>
             </a>
         </div>
         <?php } ?>
         <?php } else { ?>
                     <?php if (get_option('mobile_mobile') =='mobile_mobile'){ ?>
         <div class="aml_dk-flex-item aml_dk-channel-click_to_call ">
          <a href="tel:<?php echo get_option('tdh_mobile'); ?>" target="_blank">
          <?php if (get_option('mobile_location')=='bottom-center'){ ?>
            <span class="aml-text-content ">Gọi ngay</span>
             <?php  } ?>
             </a>
         </div>
          <?php } ?>
         <?php } ?>
         <?php if (get_option('zalo_mobile') =='zalo_mobile'){ ?>
                  <div class="aml_dk-flex-item aml_dk-channel-zalo ">
         <a href="https://zalo.me/<?php echo get_option('tdh_zalo'); ?>" target="_blank" >
          <?php if (get_option('mobile_location')=='bottom-center'){ ?>
            <span class="aml-text-content ">Zalo</span>
             <?php  } ?>
             </a>
            </div>
             <?php } ?>
              <?php  if (get_option('mobile_location')=='bottom-right') { ?> 
            <?php if (get_option('mobile_mobile') =='mobile_mobile'){ ?>
         <div class="aml_dk-flex-item aml_dk-channel-click_to_call ">
          <a href="tel:<?php echo get_option('tdh_mobile'); ?>" target="_blank">
          <?php if (get_option('mobile_location')=='bottom-center'){ ?>
            <span class="aml-text-content ">Gọi ngay</span>
             <?php  } ?>
             </a>
         </div>
          <?php } ?>
          <?php } ?>
          <?php  if (get_option('mobile_location')=='bottom-center') { ?>
           <?php if (get_option('messenger_mobile') =='messenger_mobile'){ ?>
         <div class="aml_dk-flex-item aml_dk-channel-facebook ">
          <a href="https://m.me/<?php echo get_option('tdh_messenger'); ?>" target="_blank">
          <?php if (get_option('mobile_location')=='bottom-center'){ ?>
            <span class="aml-text-content ">Messenger</span>
             <?php  } ?>
             </a>
         </div>
         <?php } ?>
          <?php  }  ?>
          
        <?php  if (get_option('mobile_location')=='bottom-right') { ?> 
            <?php if ( get_option('tdh_zalo_oa') && get_option('zalo_oa_mobile') =='zalo_oa_mobile'){ ?> 
            <div class="aml_dk-flex-item  "> 
                <div class="zalo-chat-widget" style="width: 50px; height: 50px;" data-oaid=" <?php echo get_option('tdh_zalo_oa'); ?>" data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width="350" data-height="420"></div>
                <script src="https://sp.zalo.me/plugins/sdk.js"></script>
            </div>
            <?php  }  ?>
        <?php  }  ?>
        
        <?php  if (get_option('mobile_location')=='bottom-center') { ?> 
        <?php if ( get_option('tdh_zalo_oa') && get_option('zalo_oa_mobile') =='zalo_oa_mobile'){ ?> 
        <div class="aml_dk-flex-item  aml_dk-channel-zalo-oa"> 
        <div class="zalo-chat-widget" style="width: 50px; height: 50px;" data-oaid=" <?php echo get_option('tdh_zalo_oa'); ?>" data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width="350" data-height="420"></div>
        <script src="https://sp.zalo.me/plugins/sdk.js"></script>
        <span class="aml-text-content aml-tooltiptext">Chat ngay</span>
        </div>
        <?php  }  ?>
        <?php  }  ?>
          
      </div>
   </div>
</div>

<?php
}
else { ?>
    



<div class="simple-contact-desktop" style="display: block;">
   <div id="aml_dk_wrap" class="<?php echo $class_device; ?>" >
      <a target="_blank" href="https://tiepthitute.com" class="aml-powered-by aml-pb-style-gradient-default aml_dk-style-default" style="color: #555; " >Developed by <span class="aml-powered-by-b">Tiepthitute</span></a>
      <div class="aml_dk-flex-container aml-flc-style-gradient-default aml_dk-style-default" style=" background-image: unset;">
  <?php if (get_option('tdh_location') && get_option('location_desktop') =='location_desktop'){ ?>
         <div class="aml_dk-flex-item aml-tooltip">
            <a target="_blank" href="<?php echo get_option('tdh_location'); ?>"><div class="aml_dk-channel-google_map"></div></a>
            <span class="aml-text-content aml-tooltiptext">Bản đồ</span>
         </div>
          <?php } ?>
           <?php if (get_option('tdh_messenger') && get_option('messenger_desktop') =='messenger_desktop'){ ?>
         <div class="aml_dk-flex-item aml-tooltip">
            <a href="https://m.me/<?php echo get_option('tdh_messenger'); ?>" target="_blank"><div class="aml_dk-channel-facebook"></div></a>
            <span class="aml-text-content aml-tooltiptext">Facebook Messenger</span>
         </div>
          <?php } ?>
         <?php if (get_option('tdh_zalo') && get_option('zalo_desktop') =='zalo_desktop'){ ?>
         <div class="aml_dk-flex-item aml-tooltip">
            <a href="https://zalo.me/<?php echo get_option('tdh_zalo'); ?>" target="_blank" ><div class="aml_dk-channel-zalo"></div></a>
            <span class="aml-text-content aml-tooltiptext">Chat với chúng tôi qua Zalo</span>
         </div>
         <?php } ?>

           <?php if ( get_option('tdh_mobile') && get_option('mobile_desktop') =='mobile_desktop'){ ?>
          <div  class="aml_dk-flex-item aml-tooltip">
            <a href="tel:<?php echo get_option('tdh_mobile'); ?>" target="_blank"><div class="aml_dk-channel-click_to_call"></div></a>
            <span class="aml-text-content aml-tooltiptext">Gọi ngay</span>
         </div>
          <?php } ?>      
          
         <?php if ( get_option('tdh_zalo_oa') && get_option('zalo_oa_desktop') =='zalo_oa_desktop'){ ?> 
        <div  class="aml_dk-flex-item aml-tooltip">
            <div class="zalo-chat-widget" style="width: 50px; height: 50px;" data-oaid=" <?php echo get_option('tdh_zalo_oa'); ?>" data-welcome-message="Rất vui khi được hỗ trợ bạn!" data-autopopup="0" data-width="350" data-height="420"></div>
            <script src="https://sp.zalo.me/plugins/sdk.js"></script>
        <span class="aml-text-content aml-tooltiptext">Chat ngay</span>
        </div>
          <?php } ?>    
          
             
      </div>
      

   </div>
</div>
<?php
}
?>

