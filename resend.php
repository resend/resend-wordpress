<?php

/**
 * @package Resend
 */
/**
 * Plugin Name: Resend
 * Plugin URI: https://resend.com
 * Description: The best API to reach humans instead of spam folders. Build, test, and deliver transactional emails at scale.
 * Version: 1.0.0
 * Requires at least: 5.8
 * Requires PHP: 7.2
 * Author: Resend
 * Author URI: https://resend.com
 * Text Domain: resend
 */

// Make sure we don't expose any info if called directly
if ( ! function_exists( 'add_action' ) ) {
	echo 'Hi there! I\'m just a plugin, not much I can do when called directly.';
	exit;
}

define( 'RESEND_VERSION', '1.0.0' );
define( 'RESEND__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( function_exists( 'wp_mail' ) ) {
	function wp_mail_already_declared_notice() {
		$class   = 'notice notice-error';
		$message = __( 'Resend is active, but something else is blocking it from sending emails. Another plugin or custom code is taking over email handling (wp_mail). To use Resend, you\'ll need to disable the conflict.', 'resend' );

		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	}

	add_action( 'admin_notices', 'wp_mail_already_declared_notice' );
}

register_activation_hook( __FILE__, array( 'Resend', 'plugin_activation' ) );
register_deactivation_hook( __FILE__, array( 'Resend', 'plugin_deactivation' ) );

require_once RESEND__PLUGIN_DIR . 'class-resend.php';

add_action( 'init', array( 'Resend', 'init' ) );

if ( is_admin() ) {
	require_once RESEND__PLUGIN_DIR . 'class-resend-admin.php';
	add_action( 'init', array( 'Resend_Admin', 'init' ) );
}

if ( ! function_exists( 'wp_mail' ) ) {
	include RESEND__PLUGIN_DIR . 'wp-mail.php';
}
