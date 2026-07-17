<?php
/**
 * The Resend Class.
 *
 * @package Resend
 */

/**
 * Class for the Resend plugin.
 */
class Resend {
	/**
	 * Initialize all the custom hooks as necessary.
	 *
	 * @return void
	 */
	public static function init() {
	}

	/**
	 * Load and render a view template from the plugin's views directory.
	 *
	 * @param string $name The view name without the file extension.
	 * @param array  $args Optional variables to pass into the view.
	 *
	 * @return void
	 */
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

	/**
	 * Handle plugin activation tasks.
	 *
	 * Adds an option when activation is performed from the WordPress plugin screen.
	 *
	 * @return void
	 */
	public static function plugin_activation() {
		$script_name = isset( $_SERVER['SCRIPT_NAME'] ) ? sanitize_text_field( wp_unslash( $_SERVER['SCRIPT_NAME'] ) ) : '';

		if ( ! empty( $script_name ) && strpos( $script_name, '/wp-admin/plugins.php' ) !== false ) {
			add_option( 'Activated_Resend', true );
		}
	}

	/**
	 * Handle plugin deactivation tasks.
	 *
	 * @return void
	 */
	public static function plugin_deactivation() {
	}

	/**
	 * Retrieve the stored Resend API key.
	 *
	 * @return string|null The stored API key or null when not set.
	 */
	public static function get_api_key() {
		return apply_filters( 'resend_get_api_key', get_option( 'resend_api_key' ) );
	}

	/**
	 * Retrieve the default from name for outgoing emails.
	 *
	 * @return string The configured from name.
	 */
	public static function get_from_name() {
		return get_option( 'resend_from_name', 'WordPress' );
	}

	/**
	 * Retrieve the default from email address for outgoing emails.
	 *
	 * @return string The configured from email address.
	 */
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

	/**
	 * Determine whether a Resend API key appears valid.
	 *
	 * @param string $key The API key to validate.
	 *
	 * @return bool True if the key is valid, false otherwise.
	 */
	public static function is_valid_key( $key ) {
		if ( strpos( $key, 're_' ) !== 0 ) {
			return false;
		}

		return true;
	}
}
