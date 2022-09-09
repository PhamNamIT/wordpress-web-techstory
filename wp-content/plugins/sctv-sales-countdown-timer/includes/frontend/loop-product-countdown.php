<?php

/**
 * Class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Loop_Product_Countdown
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_SCT_SALES_COUNTDOWN_TIMER_Frontend_Loop_Product_Countdown {
	protected $settings;

	public function __construct() {
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$this->settings = new VI_SCT_SALES_COUNTDOWN_TIMER_Data();
			/*countdown timer position*/
			$theme = wp_get_theme()->template ?? wp_get_theme()->get_stylesheet();
			if (in_array($theme,array('topdeal','goodstore'))){
				add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'woocommerce_after_shop_loop_item_title' ) ,10);
				add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'woocommerce_loop_add_to_cart_link', ), 99, 2 );
			}else{
				add_action( 'woocommerce_before_template_part', array( $this, 'countdown_before_template_loop' ) );
				add_action( 'woocommerce_after_template_part', array( $this, 'countdown_after_template_loop' ) );
				if ($theme !=='casa') {
					add_filter( 'woocommerce_product_get_image', array( $this, 'woocommerce_product_get_image' ), 99, 2 );
				}
				add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'woocommerce_loop_add_to_cart_link', ), 99, 2 );
			}
		}
	}
	public function woocommerce_after_shop_loop_item_title(){
		global $product;
		if ( ! $product->is_in_stock() ) {
			return;
		}
		$product_id = $product->get_id();
		if ( $product->is_type( 'variable' ) ) {
			$product_id = get_post_meta( $product_id, '_woo_ctr_display_enable', true );
			if ( $product_id && get_post_meta( $product_id, '_sale_price', true ) ) {
				$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
			} else {
				$id = '';
			}
		} else {
			if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' ) ) {
				return;
			}
			$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		}
		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return;
			}
			if ( ! $this->settings->get_current_countdown( 'sale_countdown_archive_page_enable', $index ) ) {
				return;
			}
			$position = $this->settings->get_current_countdown( 'sale_countdown_archive_page_position', $index );
			if ($position !=='before_price'){
				return;
			}
			$assign_page = wp_unslash($this->settings->get_current_countdown('sale_countdown_archive_page_assign', $index));
			if ( $assign_page ) {
				if ( stristr( $assign_page, "return" ) === false ) {
					$assign_page = "return (" . $assign_page . ");";
				}
				if ( ! eval( $assign_page ) ) {
					return;
				}
			}
			$pg_position = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position', $index );
			$shortcode   = do_shortcode( '[sctv_product_countdown_timer is_single ="" product_id="' . $product_id . '" progress_bar_enable="1" progress_bar_position ="' . $pg_position . '" countdown_enable="1" resize_archive_page_enable="1"  countdown_id = "' . $id . '"]' );
			echo $shortcode;
		}
	}

	public function countdown_before_template_loop( $template_name ) {
		if ( ! in_array( $template_name,
			array(
				'loop/price.php',
				'loop/sale-flash.php',
			) ) ) {
			return;
		}

		global $product;
		if ( ! $product->is_in_stock() ) {
			return;
		}
		$product_id = $product->get_id();
		if ( $product->is_type( 'variable' ) ) {
			$product_id = get_post_meta( $product_id, '_woo_ctr_display_enable', true );
			if ( $product_id && get_post_meta( $product_id, '_sale_price', true ) ) {
				$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
			} else {
				$id = '';
			}
		} else {
			if (  ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' )  ) {
				return;
			}
			$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		}
		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return;
			}
			if ( ! $this->settings->get_current_countdown( 'sale_countdown_archive_page_enable', $index ) ) {
				return;
			}
			switch ( $this->settings->get_current_countdown( 'sale_countdown_archive_page_position', $index ) ) {
				case 'before_saleflash':
					if ( $template_name !== 'loop/sale-flash.php' ) {
						return;
					}
					break;
				case 'before_price':
					if ( $template_name !== 'loop/price.php' ) {
						return;
					}
					break;
				default:
					return;
			}
			$assign_page = wp_unslash($this->settings->get_current_countdown('sale_countdown_archive_page_assign', $index));
			if ( $assign_page ) {
				if ( stristr( $assign_page, "return" ) === false ) {
					$assign_page = "return (" . $assign_page . ");";
				}
				if ( ! eval( $assign_page ) ) {
					return;
				}
			}
			$pg_position = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position', $index );
			$shortcode   = do_shortcode( '[sctv_product_countdown_timer is_single ="" product_id="' . $product_id . '" progress_bar_enable="1" progress_bar_position ="' . $pg_position . '" countdown_enable="1" resize_archive_page_enable="1"  countdown_id = "' . $id . '"]' );
			echo $shortcode;
		}

	}

	public function countdown_after_template_loop( $template_name ) {
		if ( ! in_array( $template_name,
			array(
				'loop/price.php',
				'loop/sale-flash.php',
			) ) ) {
			return;
		}
		global $product;
		if ( ! $product->is_in_stock() ) {
			return;
		}
		$product_id = $product->get_id();
		if ( $product->is_type( 'variable' ) ) {
			$product_id = get_post_meta( $product_id, '_woo_ctr_display_enable', true );
			if ( $product_id && get_post_meta( $product_id, '_sale_price', true ) ) {
				$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
			} else {
				$id = '';
			}
		} else {
			if (  ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' )  ) {
				return;
			}
			$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		}

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return;
			}

			if ( ! $this->settings->get_current_countdown( 'sale_countdown_archive_page_enable', $index ) ) {
				return;
			}
			switch ( $this->settings->get_current_countdown( 'sale_countdown_archive_page_position', $index ) ) {
				case 'after_saleflash':
					if ( $template_name !== 'loop/sale-flash.php' ) {
						return;
					}
					break;
				case 'after_price':
					if ( $template_name !== 'loop/price.php' ) {
						return;
					}
					break;
				default:
					return;
			}
			$assign_page = wp_unslash($this->settings->get_current_countdown('sale_countdown_archive_page_assign', $index));
			if ( $assign_page ) {
				if ( stristr( $assign_page, "return" ) === false ) {
					$assign_page = "return (" . $assign_page . ");";
				}
				if ( ! eval( $assign_page ) ) {
					return;
				}
			}

			$pg_position = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position', $index );
			$shortcode   = do_shortcode( '[sctv_product_countdown_timer is_single ="" product_id="' . $product_id . '" progress_bar_enable="1" progress_bar_position ="' . $pg_position . '" countdown_enable="1" resize_archive_page_enable="1"  countdown_id = "' . $id . '"]' );
			echo $shortcode;
		}

	}

	public function woocommerce_product_get_image( $html, $product ) {
		if ( is_admin() ) {
			return $html;
		}
		if ( ! $product ) {
			return $html;
		}
		if ( ! $product->is_in_stock() ) {
			return $html;
		}
		$product_id = $product->get_id();
		if ( $product->is_type( 'variable' ) ) {
			$product_id = get_post_meta( $product_id, '_woo_ctr_display_enable', true );
			if ( $product_id && get_post_meta( $product_id, '_sale_price', true ) ) {
				$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
			} else {
				$id = '';
			}
		} else {
			if (  ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' )  ) {
				return $html;
			}
			$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		}

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return $html;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return $html;
			}

			if ( ! $this->settings->get_current_countdown( 'sale_countdown_archive_page_enable', $index ) ) {
				return $html;
			}
			if ( $this->settings->get_current_countdown( 'sale_countdown_archive_page_position',
					$index ) !== 'product_image' ) {
				return $html;
			}
			$assign_page = wp_unslash($this->settings->get_current_countdown('sale_countdown_archive_page_assign', $index));
			if ( $assign_page ) {
				if ( stristr( $assign_page, "return" ) === false ) {
					$assign_page = "return (" . $assign_page . ");";
				}
				if ( ! eval( $assign_page ) ) {
					return $html;
				}
			}

			$pg_position       = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position', $index );
			$countdown         = do_shortcode( '[sctv_product_countdown_timer is_single ="" product_id="' . $product_id . '" progress_bar_enable="" progress_bar_position ="' . $pg_position . '" countdown_enable="1" resize_archive_page_enable="1"  countdown_id = "' . $id . '"]' );
			$progress_bar_html = do_shortcode( '[sctv_product_countdown_timer is_single ="" product_id="' . $product_id . '" progress_bar_enable="1" progress_bar_position ="' . $pg_position . '" countdown_enable=""  countdown_id = "' . $id . '"]' );
			if ( $pg_position == 'above_countdown' ) {
				return $progress_bar_html . '<div class="woo-sctr-countdown-timer-product-image-cate-shop-wrap">' . $html . $countdown . '</div>';

			} elseif ( $pg_position == 'below_countdown' ) {
				return '<div class="woo-sctr-countdown-timer-product-image-cate-shop-wrap">' . $html . $countdown . '</div>' . $progress_bar_html;
			}
		}

		return $html;
	}

	public function woocommerce_loop_add_to_cart_link( $html, $product ) {
		if ( is_admin() ) {
			return $html;
		}
		if ( ! $product ) {
			return $html;
		}
		if ( ! $product->is_in_stock() ) {
			return $html;
		}
		$product_id = $product->get_id();
		if ( $product->is_type( 'variable' ) ) {
			$product_id = get_post_meta( $product_id, '_woo_ctr_display_enable', true );
			if ( $product_id && get_post_meta( $product_id, '_sale_price', true ) ) {
				$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
			} else {
				$id = '';
			}
		} else {
			if ( ! $product->get_date_on_sale_from( 'edit' ) && ! $product->get_date_on_sale_to( 'edit' )  ) {
				return $html;
			}
			$id = get_post_meta( $product_id, '_woo_ctr_select_countdown_timer', true );
		}

		if ( $id !== '' ) {
			$index = array_search( $id, $this->settings->get_params( 'sale_countdown_id' ) );
			if ( $index === false ) {
				return $html;
			}
			if ( ! $this->settings->get_params( 'sale_countdown_active' )[ $index ] ) {
				return $html;
			}

			if ( ! $this->settings->get_current_countdown( 'sale_countdown_archive_page_enable', $index ) ) {
				return $html;
			}
			$sale_countdown_archive_page_position = $this->settings->get_current_countdown( 'sale_countdown_archive_page_position',
				$index );
			if ( ! in_array( $sale_countdown_archive_page_position,
				array(
					'before_cart',
					'after_cart',
				) ) ) {
				return $html;
			}

			$assign_page = wp_unslash($this->settings->get_current_countdown('sale_countdown_archive_page_assign', $index));
			if ( $assign_page ) {
				if ( stristr( $assign_page, "return" ) === false ) {
					$assign_page = "return (" . $assign_page . ");";
				}
				if ( ! eval( $assign_page ) ) {
					return $html;
				}
			}
			$pg_position = $this->settings->get_current_countdown( 'sale_countdown_progress_bar_position', $index );
			$shortcode   = do_shortcode( '[sctv_product_countdown_timer is_single ="" product_id="' . $product_id . '" progress_bar_enable="1" progress_bar_position ="' . $pg_position . '" countdown_enable="1" resize_archive_page_enable="1" countdown_id = "' . $id . '"]' );

			if ( $sale_countdown_archive_page_position === 'before_cart' ) {
				return $shortcode . $html;
			} else {
				return $html . $shortcode;
			}

		}
		return $html;
	}
}