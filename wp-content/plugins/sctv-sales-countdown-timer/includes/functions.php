<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Function include all files in folder
 *
 * @param $path   Directory address
 * @param $ext    array file extension what will include
 * @param $prefix string Class prefix
 */
if ( ! function_exists( 'vi_include_folder' ) ) {
	function vi_include_folder( $path, $prefix = '', $ext = array( 'php' ) ) {

		/*Include all files in payment folder*/
		if ( ! is_array( $ext ) ) {
			$ext = explode( ',', $ext );
			$ext = array_map( 'trim', $ext );
		}
		$sfiles = scandir( $path );
		foreach ( $sfiles as $sfile ) {
			if ( $sfile != '.' && $sfile != '..' ) {
				if ( is_file( $path . "/" . $sfile ) ) {
					$ext_file  = pathinfo( $path . "/" . $sfile );
					$file_name = $ext_file['filename'];
					if ( $ext_file['extension'] ) {
						if ( in_array( $ext_file['extension'], $ext ) ) {
							$class = preg_replace( '/\W/i', '_', $prefix . ucfirst( $file_name ) );

							if ( ! class_exists( $class ) ) {
								require_once $path . $sfile;
								if ( class_exists( $class ) ) {
									new $class;
								}
							}
						}
					}
				}
			}
		}
	}
}
if ( ! function_exists( 'woo_ctr_time' ) ) {
	function woo_ctr_time( $time ) {
		if ( ! $time ) {
			return 0;
		}
		$temp = explode( ":", $time );
		if ( count( $temp ) == 2 ) {
			return ( absint( $temp[0] ) * 3600 + absint( $temp[1] ) * 60 );
		} else {
			return 0;
		}
	}
}
if ( ! function_exists( 'woo_ctr_time_revert' ) ) {
	function woo_ctr_time_revert( $time ) {
		$hour = floor( $time / 3600 );
		$min  = floor( ( $time - 3600 * $hour ) / 60 );

		return implode( ':', array( zeroise( $hour, 2 ), zeroise( $min, 2 ) ) );
	}
}

if ( ! function_exists( 'woo_ctr_stripslashes_deep' ) ) {
	function woo_ctr_stripslashes_deep( $value ) {

		if ( is_array( $value ) ) {

			$value = array_map( 'stripslashes_deep', $value );

		} else {

			$value = wp_kses_post( stripslashes( $value ) );

		}


		return $value;

	}

}

if ( ! function_exists( 'woo_ctr_hextorgba' ) ) {
	function woo_ctr_hextorgba( $hex, $opacity = 0.08 ) {
		$hex = str_replace( "#", "", $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgba = 'rgba(' . $r . ',' . $g . ',' . $b . ',' . floatval( $opacity ) . ')';

		return $rgba;
	}

}
if ( ! function_exists( 'woo_crt_wpversion' ) ) {
	function woo_crt_wpversion() {
		global $wp_version;
		if ( version_compare( $wp_version, '4.5.0', '<=' ) ) {
			return true;
		} else {
			false;
		}
	}
}
if ( ! function_exists( 'villatheme_sanitize_fields_message' ) ) {
	function villatheme_sanitize_fields_message( $data ) {
		if ( is_array( $data ) ) {
			return array_map( 'villatheme_sanitize_fields_message', $data );
		} else {
			return is_scalar($data)? wp_kses_post( stripslashes( $data ) ):$data;
		}
	}
}
if ( ! function_exists( 'villatheme_sanitize_fields' ) ) {
	function villatheme_sanitize_fields( $data ) {
		if ( is_array( $data ) ) {
			return array_map( 'villatheme_sanitize_fields', $data );
		} else {
			return is_scalar($data)?sanitize_text_field( stripslashes( $data ) ):$data;
		}
	}
}

