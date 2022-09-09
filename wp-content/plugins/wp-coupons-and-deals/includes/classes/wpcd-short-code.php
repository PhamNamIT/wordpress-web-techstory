<?php

// If accessed directly, exit
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * This class builds the short code that
 * will be used to display coupon codes
 * on the front end.
 *
 * @since 1.0
 * @author Imtiaz Rayhan
 */
class WPCD_Short_Code
{
    /**
     * Class constructor.
     * Adds the function to register the shortcode with WordPress.
     *
     * @since 1.0
     */
    public static function init()
    {
        /**
         * Shortcode register function.
         *
         * @since 1.0
         */
        add_shortcode( 'wpcd_coupon', array( __CLASS__, 'wpcd_coupon' ) );
        add_shortcode( 'wpcd_code', array( __CLASS__, 'wpcd_coupon_code' ) );
        
        if ( wcad_fs()->is_plan__premium_only( 'pro' ) or wcad_fs()->can_use_premium_code() ) {
            add_shortcode( 'wpcd_coupons', array( __CLASS__, 'wpcd_coupons_archive_func__premium_only' ) );
            add_shortcode( 'wpcd_coupons_loop', array( __CLASS__, 'wpcd_coupons_loop_func__premium_only' ) );
            add_action( 'wp_ajax_wpcd_coupons_category_action', array( __CLASS__, 'wpcd_coupons_archive_func__premium_only' ) );
            add_action( 'wp_ajax_nopriv_wpcd_coupons_category_action', array( __CLASS__, 'wpcd_coupons_archive_func__premium_only' ) );
            add_action( 'wp_ajax_wpcd_coupons_cat_vend_action', array( __CLASS__, 'wpcd_coupons_loop_func__premium_only' ) );
            add_action( 'wp_ajax_nopriv_wpcd_coupons_cat_vend_action', array( __CLASS__, 'wpcd_coupons_loop_func__premium_only' ) );
            add_action( 'wp_ajax_wpcd_coupon_clicked_action', array( __CLASS__, 'wpcd_coupon_clicked_action_func__premium_only' ) );
            add_action( 'wp_ajax_nopriv_wpcd_coupon_clicked_action', array( __CLASS__, 'wpcd_coupon_clicked_action_func__premium_only' ) );
        }
    
    }
    
    /**
     * Shortcode attributes and arguments to build the shortcode.
     *
     * @param $atts array shortcode attributes.
     *
     * @return string
     *
     * @since 1.0
     */
    public static function wpcd_coupon( $atts )
    {
        global  $wpcd_atts ;
        global  $wpcd_coupon ;
        /**
         * These are the shortcode attributes.
         *
         * @since 1.0
         */
        $wpcd_atts = shortcode_atts( array(
            'id'    => '',
            'total' => '-1',
        ), $atts, 'wpcd_coupon' );
        /**
         * Arguments to be used for a custom Query.
         *
         * @since 1.0
         */
        $wpcd_arg = array(
            'p'              => esc_attr( $wpcd_atts['id'] ),
            'posts_per_page' => esc_attr( $wpcd_atts['total'] ),
            'post_type'      => 'wpcd_coupons',
            'post_status'    => 'publish',
        );
        /**
         * New custom query to get post and post data
         * from the custom coupon post type.
         *
         * @since 1.0
         */
        $wpcd_coupon = new WP_Query( $wpcd_arg );
        $output = '';
        //Hide expired coupon feature
        $today = date( 'd-m-Y' );
        $hide_expired_coupon = get_option( 'wpcd_hide-expired-coupon' );
        $enable_stats = get_option( 'wpcd_enable-stats-count' );
        while ( $wpcd_coupon->have_posts() ) {
            $wpcd_coupon->the_post();
            global  $coupon_id ;
            $template = new WPCD_Template_Loader();
            $coupon_id = get_the_ID();
            $expire_date = get_post_meta( $coupon_id, 'coupon_details_expire-date', true );
            $coupon_template = get_post_meta( $coupon_id, 'coupon_details_coupon-template', true );
            $coupon_type = get_post_meta( $coupon_id, 'coupon_details_coupon-type', true );
            
            if ( !empty($enable_stats) && $enable_stats == 'on' ) {
                $view_count = get_post_meta( $coupon_id, 'coupon_view_count', true );
                
                if ( empty($view_count) || !is_numeric( $view_count ) ) {
                    $view_count = 1;
                } else {
                    $view_count = intval( $view_count ) + 1;
                }
                
                update_post_meta( $coupon_id, 'coupon_view_count', $view_count );
            }
            
            
            if ( $coupon_type === 'Image' ) {
                ob_start();
                $template->get_template_part( 'shortcode-image' );
                $output = ob_get_clean();
                if ( WPCD_Amp::wpcd_amp_is() ) {
                    WPCD_Amp::instance()->setCss( 'shortcode_image' );
                }
                wp_reset_postdata();
                return $output;
            }
            
            // Hide expired coupon feature (default is Not to hide).
            
            if ( !empty($hide_expired_coupon) || $hide_expired_coupon == "on" ) {
                $expire_date = get_post_meta( $coupon_id, 'coupon_details_expire-date', true );
                
                if ( !empty($expire_date) ) {
                    if ( (string) (int) $expire_date != $expire_date ) {
                        $expire_date = strtotime( $expire_date );
                    }
                    
                    if ( $coupon_template !== 'Template Four' ) {
                        if ( $expire_date < strtotime( $today ) ) {
                            continue;
                        }
                    } else {
                        $second_expire_date = get_post_meta( $coupon_id, 'coupon_details_second-expire-date', true );
                        $third_expire_date = get_post_meta( $coupon_id, 'coupon_details_third-expire-date', true );
                        if ( $expire_date < strtotime( $today ) && $second_expire_date < strtotime( $today ) && $third_expire_date < strtotime( $today ) ) {
                            continue;
                        }
                    }
                
                }
            
            }
            
            
            if ( wcad_fs()->is_plan__premium_only( 'pro' ) or wcad_fs()->can_use_premium_code() ) {
                
                if ( $coupon_template == 'Template One' ) {
                    $argcss = 'shortcode_one';
                    ob_start();
                    $template->get_template_part( 'shortcode-one__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Two' ) {
                    $argcss = 'shortcode_two';
                    ob_start();
                    $template->get_template_part( 'shortcode-two__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Three' ) {
                    $argcss = 'shortcode_three';
                    ob_start();
                    $template->get_template_part( 'shortcode-three__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Four' ) {
                    $argcss = 'shortcode_four';
                    ob_start();
                    $template->get_template_part( 'shortcode-four__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Five' ) {
                    $argcss = 'shortcode_five';
                    ob_start();
                    $template->get_template_part( 'shortcode-five__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Six' ) {
                    $argcss = 'shortcode_six';
                    ob_start();
                    $template->get_template_part( 'shortcode-six__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Seven' ) {
                    $argcss = 'shortcode_seven';
                    ob_start();
                    $template->get_template_part( 'shortcode-seven__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Eight' ) {
                    $argcss = 'shortcode_eight';
                    ob_start();
                    $template->get_template_part( 'shortcode-eight__premium_only' );
                    $output = ob_get_clean();
                } elseif ( $coupon_template == 'Template Nine' ) {
                    $argcss = 'shortcode_nine';
                    ob_start();
                    $template->get_template_part( 'shortcode-nine__premium_only' );
                    $output = ob_get_clean();
                } else {
                    $argcss = 'shortcode_default';
                    ob_start();
                    $template->get_template_part( 'shortcode-default' );
                    $output = ob_get_clean();
                }
            
            } else {
                $argcss = 'shortcode_default';
                ob_start();
                $template->get_template_part( 'shortcode-default' );
                $output = ob_get_clean();
            }
            
            
            if ( WPCD_Amp::wpcd_amp_is() ) {
                WPCD_Amp::instance()->setCss( 'shortcode_common' );
                WPCD_Amp::instance()->setCss( $argcss );
                $user_stylesheets = WPCD_Assets::wpcd_stylesheets( true, $coupon_id, $coupon_template );
                WPCD_Amp::instance()->setCss( $user_stylesheets, false );
            }
        
        }
        wp_reset_postdata();
        return $output;
    }
    
    /**
     * Builds the only coupon code shortcode.
     *
     * @param $atts
     *
     * @return string
     *
     * @since 1.0
     */
    public static function wpcd_coupon_code( $atts )
    {
        global  $wpcd_code_atts ;
        global  $wpcd_coupon_code ;
        /**
         * These are the shortcode attributes.
         *
         * @since 1.4
         */
        $wpcd_code_atts = shortcode_atts( array(
            'id'    => '',
            'total' => '-1',
        ), $atts, 'wpcd_code' );
        /**
         * Arguments to be used for a custom Query.
         *
         * @since 1.4
         */
        $wpcd_code_arg = array(
            'p'              => esc_attr( $wpcd_code_atts['id'] ),
            'posts_per_page' => esc_attr( $wpcd_code_atts['total'] ),
            'post_type'      => 'wpcd_coupons',
            'post_status'    => 'publish',
        );
        /**
         * New custom query to get post and post data
         * from the custom coupon post type.
         *
         * @since 1.4
         */
        $wpcd_coupon_code = new WP_Query( $wpcd_code_arg );
        $template = new WPCD_Template_Loader();
        ob_start();
        $template->get_template_part( 'shortcode-code' );
        // Return Variables
        $output = ob_get_clean();
        if ( WPCD_Amp::wpcd_amp_is() ) {
            WPCD_Amp::instance()->setCss( 'shortcode_common' );
        }
        wp_reset_postdata();
        return $output;
    }

}