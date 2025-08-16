<?php

class Resend {

	public static function init() {
		//
	}

	public static function view( $name, array $args = array() ) {
		$args = apply_filters( 'resend_view_arguments', $args, $name );

		foreach ( $args as $key => $val ) {
			$$key = $val;
		}

		$file = RESEND__PLUGIN_DIR . 'views/' . basename( $name ) . '.php';

		if ( file_exists( $file ) ) {
			include $file;
		}
	}

	public static function plugin_activation() {
		if ( ! empty( $_SERVER['SCRIPT_NAME'] ) && strpos( $_SERVER['SCRIPT_NAME'], '/wp-admin/plugins.php' ) !== false ) {
			add_option( 'Activated_Resend', true );
		}
	}

	public static function plugin_deactivation() {
		//
	}

	public static function get_api_key() {
		return apply_filters( 'resend_get_api_key', get_option( 'resend_api_key' ) );
	}

	public static function get_from_name() {
		return get_option( 'resend_from_name', 'WordPress' );
	}

	public static function get_from_address() {
		$sitename = wp_parse_url( network_home_url(), PHP_URL_HOST );

		$default_from_email = '';

		if ( null !== $sitename ) {
			$default_from_email .= 'wordpress@';
			if ( str_starts_with( $sitename, 'www.' ) ) {
				$sitename = substr( $sitename, 4 );
			}
			$default_from_email .= $sitename;
		}

		return get_option( 'resend_from_address', $default_from_email );
	}

	public static function is_valid_key( $key ) {
		if ( strpos( $key, 're_' ) !== 0 ) {
			return false;
		}

		return true;
	}
}
