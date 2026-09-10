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
	 * Retrieve the configured Resend API key.
	 *
	 * When the `RESEND_API_KEY` constant is defined (typically in `wp-config.php`) with
	 * a non-empty value, it always takes precedence over the value stored in the
	 * database. This lets site owners keep the key out of the database, and therefore
	 * out of database backups, entirely:
	 *
	 *     define( 'RESEND_API_KEY', 're_xxxxxxxxx' );
	 *
	 * The `resend_get_api_key` filter is still applied to the final value in either
	 * case, so existing integrations relying on it continue to work.
	 *
	 * @return string|null The configured API key or null when not set.
	 */
	public static function get_api_key() {
		$api_key = self::is_api_key_locked() ? RESEND_API_KEY : get_option( 'resend_api_key' );

		return apply_filters( 'resend_get_api_key', $api_key );
	}

	/**
	 * Determine whether the API key is locked by the `RESEND_API_KEY` constant.
	 *
	 * When locked, the key defined in `wp-config.php` always overrides any value
	 * stored in the database, and the admin UI must refuse to save or remove the
	 * stored option so it does not misrepresent what key is actually in use.
	 *
	 * @return bool True when the `RESEND_API_KEY` constant is defined and non-empty.
	 */
	public static function is_api_key_locked() {
		return defined( 'RESEND_API_KEY' ) && '' !== RESEND_API_KEY;
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
