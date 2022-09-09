<?php
/**
 * @package HTG
 */
/*
Plugin Name: Simple Contact 
Plugin URI: https://tiepthitute.com/plugin-simple-contact/
Description: Tạo liên hệ nhanh trên website
Author: Developed by Tiepthitute
Author URI: https://tiepthitute.com/plugin-simple-contact/
License: GPLv2 or later
*/

function ht_social_contact_styles() {
    wp_enqueue_style( 'ht-social',  plugin_dir_url( __FILE__ ) . '/assets/css/style.css' );  
    wp_enqueue_script('jquery', plugin_dir_url( __FILE__ ) . '/assets/js/script.js');                    
}
add_action( 'wp_enqueue_scripts', 'ht_social_contact_styles' );
// create custom plugin settings menu
add_action('admin_menu', 'ht_social_create_menu');

function ht_social_create_menu() {
	//create new top-level menu
	add_menu_page('Simple Contact', 'Simple Contact', 'administrator', __FILE__, 'ht_social_plugin_settings_page' , plugins_url('/assets/images/target.svg', __FILE__),5 );

	//call register settings function
	add_action( 'admin_init', 'register_ht_social_plugin_settings' );
}
function my_admin_theme_style() {
    wp_enqueue_style('my-admin-theme', plugins_url('plugin-admin.css', __FILE__));
}
add_action('admin_enqueue_scripts', 'my_admin_theme_style');
add_action('login_enqueue_scripts', 'my_admin_theme_style');
function register_ht_social_plugin_settings() {
	//register our settings
    register_setting( 'ht-social-settings-group', 'tdh_location' );
    register_setting( 'ht-social-settings-group', 'location_desktop' );
    register_setting( 'ht-social-settings-group', 'location_mobile' );
    
    register_setting( 'ht-social-settings-group', 'tdh_mobile' );
    register_setting( 'ht-social-settings-group', 'mobile_desktop' );
    register_setting( 'ht-social-settings-group', 'mobile_mobile' );
    
    register_setting( 'ht-social-settings-group', 'tdh_zalo' );
    register_setting( 'ht-social-settings-group', 'zalo_desktop' );
    register_setting( 'ht-social-settings-group', 'zalo_mobile' );
    
    register_setting( 'ht-social-settings-group', 'tdh_zalo_oa' );
    register_setting( 'ht-social-settings-group', 'zalo_oa_desktop' );
    register_setting( 'ht-social-settings-group', 'zalo_oa_mobile' );
    
    register_setting( 'ht-social-settings-group', 'tdh_messenger' );
    register_setting( 'ht-social-settings-group', 'messenger_desktop' );
    register_setting( 'ht-social-settings-group', 'messenger_mobile' );
    
    
    register_setting( 'ht-social-settings-group', 'desktop_location' );
    register_setting( 'ht-social-settings-group', 'mobile_location' );
}
function ht_social_plugin_settings_page() {
    
?>
<div class="wrap">
<h1>SIMPLE CONTACT</h1>
<div class="wrap">
       <h2>CÀI ĐẶT</h2>
       <form id="form-simple-admin" method="post" action="options.php">
           <?php settings_fields( 'ht-social-settings-group' ); ?>
           <?php do_settings_sections( 'ht-social-settings-group' ); ?>
             
            <div class="setup_infor">
                <ul class="lis-item">
                    <li class="method">phương thức</li>
                    <li class="infomation">Thông tin</li>
                    <li class="desktop-enable">Máy tính</li>
                    <li class="mobile-enable">Điện thoại</li>
                </ul>
                <div class="list-item">
                    <div class="infor_child"><label>Vị trí</label>
                        <input type="text" name="tdh_location" size="45" value="<?php echo get_option('tdh_location'); ?>" />
                        <input type="checkbox" name="location_desktop" value="location_desktop" class="desktop_enable" <?php  if(get_option('location_desktop')=='location_desktop'){ echo "checked";} ?> />
                        <input type="checkbox" class="mobile_enable" name="location_mobile" value="location_mobile" <?php  if(get_option('location_mobile')=='location_mobile'){ echo "checked";} ?>  />
                    </div>
                    
                    <div class="infor_child">
                        <label>Điện thoại </label>
                        <input type="text" name="tdh_mobile" size="45" value="<?php echo get_option('tdh_mobile'); ?>" />
                        <input type="checkbox" name="mobile_desktop" value="mobile_desktop" class="desktop_enable" <?php  if(get_option('mobile_desktop')=='mobile_desktop'){ echo "checked";} ?> />
                        <input type="checkbox" class="mobile_enable" name="mobile_mobile" value="mobile_mobile" <?php  if(get_option('mobile_mobile')=='mobile_mobile'){ echo "checked";} ?>  />
                    </div>
    
                    <div class="infor_child">
                        <label>Zalo</label>
                        <input type="text" name="tdh_zalo" size="45" value="<?php echo get_option('tdh_zalo'); ?>" />
                        <input type="checkbox" name="zalo_desktop" value="zalo_desktop" class="desktop_enable" <?php  if(get_option('zalo_desktop')=='zalo_desktop'){ echo "checked";} ?> />
                        <input type="checkbox" class="mobile_enable" name="zalo_mobile" value="zalo_mobile" <?php  if(get_option('zalo_mobile')=='zalo_mobile'){ echo "checked";} ?>  />
                    </div>
                    
                    <div class="infor_child">
                        <label>Zalo OA</label>
                        <input type="text" name="tdh_zalo_oa" size="45" value="<?php echo get_option('tdh_zalo_oa'); ?>" />
                        <input type="checkbox" name="zalo_oa_desktop" value="zalo_oa_desktop" class="desktop_enable" <?php  if(get_option('zalo_oa_desktop')=='zalo_oa_desktop'){ echo "checked";} ?> />
                        <input type="checkbox" class="mobile_enable" name="zalo_oa_mobile" value="zalo_oa_mobile" <?php  if(get_option('zalo_oa_mobile')=='zalo_oa_mobile'){ echo "checked";} ?>  />
                    </div>
                    
                    <div class="infor_child">
                        <label>Messenger</label>
                        <input type="text" name="tdh_messenger" size="45" value="<?php echo get_option('tdh_messenger'); ?>" />
                        <input type="checkbox" name="messenger_desktop" value="messenger_desktop" class="desktop_enable" <?php  if(get_option('messenger_desktop')=='messenger_desktop'){ echo "checked";} ?> />
                        <input type="checkbox" class="mobile_enable" name="messenger_mobile" value="messenger_mobile" <?php  if(get_option('messenger_mobile')=='messenger_mobile'){ echo "checked";} ?>  />
                    </div>
                </div>
            </div>
            <div class="device_location">
                <label>Máy tinh</label>
                <select name="desktop_location">
                    <option value="left"   <?php  if(get_option('desktop_location')=='left'){ echo "selected";} ?> >Trái</option>
                    <option value="right" <?php  if(get_option('desktop_location')=='right'){ echo "selected";} ?> >Phải</option>
                </select>
                
                <label>Điện thoại</label>
                <select name="mobile_location">
                    <option value="bottom-right" <?php  if(get_option('mobile_location')=='bottom-right'){ echo "selected";} ?>>Dọc Phải</option>
                    <option value="bottom-center" <?php  if(get_option('mobile_location')=='bottom-center'){ echo "selected";} ?>>Ngang dưới</option>
                </select>
            </div>
            <div class="input-submit"><input type="submit" name="submit" class="button-primary" value="Lưu cài đặt" /></div>        
        </form>
        <div class="copyright">Developed by <a href="https://tiepthitute.com">Tiepthitute</a></div>
    </div> 
<?php } ?>
<?php
add_action('wp_footer', 'ht_social_view'); 
function ht_social_view() { 
    include_once('template/template.php'); 
    }
?>