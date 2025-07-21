<?php

class Resend_Admin {

	public const NONCE = 'resend-nonce';

	private static $initiated = false;

	private static $status = array();

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}
	}

	public static function init_hooks() {
		self::$initiated = true;

		// Admin
		add_action( 'admin_init', array( 'Resend_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'Resend_Admin', 'admin_menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( 'Resend_Admin', 'load_resources' ) );

		// AJAX handlers
		add_action( 'wp_ajax_resend_enter_key', array( 'Resend_Admin', 'ajax_enter_api_key' ) );
		add_action( 'wp_ajax_resend_send_test', array( 'Resend_Admin', 'ajax_send_test_email' ) );

		// Plugin links
		add_filter( 'plugin_action_links', array( 'Resend_Admin', 'plugin_action_links' ), 10, 2 );
		add_filter( 'plugin_action_links_' . plugin_basename( plugin_dir_path( __FILE__ ) . '/resend.php' ), array( 'Resend_Admin', 'admin_plugin_settings_link' ) );
	}

	public static function admin_init() {
		if ( get_option( 'Activated_Resend' ) ) {
			delete_option( 'Activated_Resend' );
			if ( ! headers_sent() ) {
				$admin_url = self::get_page_url( 'init' );
				wp_redirect( $admin_url );
			}
		}
	}

	public static function admin_menu() {
		$hook = add_options_page( __( 'Resend', 'resend' ), __( 'Resend', 'resend' ), 'manage_options', 'resend', array( 'Resend_Admin', 'display_page' ) );

		if ( $hook ) {
			add_action( "load-$hook", array( 'Resend_Admin', 'admin_help' ) );
		}
	}

	public static function admin_plugin_settings_link( $links ) {
		$settings_link = '<a href="' . esc_url( self::get_page_url() ) . '">' . __( 'Settings', 'resend' ) . '</a>';
		array_unshift( $links, $settings_link );
		return $links;
	}

	public static function load_resources() {
		global $hook_suffix;

		if ( in_array(
			$hook_suffix,
			apply_filters(
				'resend_admin_page_hook_suffixes',
				array_merge(
					array(
						'index.php',
						'settings_page_resend',
					)
				)
			)
		) ) {
			$resend_css_path = 'public/resend.css';
			wp_register_style( 'resend', plugin_dir_url( __FILE__ ) . $resend_css_path, array(), self::get_asset_file_version( $resend_css_path ) );
			wp_enqueue_style( 'resend' );

			$resend_font_inter_css_path = 'public/fonts/inter.css';
			wp_register_style( 'resend-font-inter', plugin_dir_url( __FILE__ ) . $resend_font_inter_css_path, array(), self::get_asset_file_version( $resend_font_inter_css_path ) );
			wp_enqueue_style( 'resend-font-inter' );

			$resend_admin_css_path = 'public/resend-admin.css';
			wp_register_style( 'resend-admin', plugin_dir_url( __FILE__ ) . $resend_admin_css_path, array(), self::get_asset_file_version( $resend_admin_css_path ) );
			wp_enqueue_style( 'resend-admin' );

			$resend_admin_js_path = 'public/resend-admin.js';
			wp_register_script( 'resend-admin', plugin_dir_url( __FILE__ ) . $resend_admin_js_path, array( 'jquery' ), self::get_asset_file_version( $resend_admin_js_path ), array( 'in_footer' => true ) );
			wp_enqueue_script( 'resend-admin' );
			wp_localize_script(
				'resend-admin',
				'resendAjax',
				array(
					'resend_url' => self::get_page_url(),
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'nonce'    => wp_create_nonce( self::NONCE ),
				)
			);
		}
	}

	public static function display_page() {
		if ( ! Resend::get_api_key() || ( isset( $_GET['view'] ) && 'start' === $_GET['view'] ) ) {
			self::display_start_page();
		} elseif ( isset( $_GET['view'] ) && 'stats' === $_GET['view'] ) {
			self::display_stats_page();
		} else {
			self::display_configuration_page();
		}
	}

	public static function display_start_page() {
		$api_key = Resend::get_api_key();

		if ( $api_key ) {
			self::display_configuration_page();
			return;
		}

		Resend::view( 'start' );
	}

	public static function display_stats_page() {
		delete_option( 'resend_api_key' );

		Resend::view( 'stats' );
	}

	public static function display_configuration_page() {
		Resend::view( 'config' );
	}

	/**
	 * Add a notice with the given type and message.
	 *
	 * @param string $type
	 * @param null|string $message
	 * @return void
	 */
	public static function add_status( $type = 'resend-error', $message ) {
		self::$status = array(
			'type'    => $type,
			'message' => $message,
		);
	}

	/**
	 * Get the JSON status payload for the given type.
	 */
	public static function json_status( $type, $message = null ) {
		if ( ! empty( self::$status ) ) {
			$message = self::get_status_message( self::$status['type'], self::$status['message'] );
		} else {
			$message = self::get_status_message( $type, $message );
		}

		return array(
			'type'    => $type,
			'message' => $message,
		);
	}

	/**
	 * Get the status message based on the given type or use the provided message.
	 *
	 * @return string
	 */
	protected static function get_status_message( $type, $message = null ) {
		if ( $message ) {
			if ( is_array( $message ) ) {
				$message = $message['message'] ?? $message['error'];
			}

			$message = wp_kses( $message, array() );

			return $message;
		}

		$message = '';

		switch ( $type ) {
			case 'not-allowed':
				$message = __( 'You are not allowed to perform this action!', 'resend' );
				break;
			case 'new-key-valid':
				$message = __( 'Resend API key has updated successfull.', 'resend' );
				break;
			case 'no-change-to-key':
				$message = __( 'Unable to update your API key.', 'resend' );
				break;
			case 'new-key-empty':
				$message = __( 'You did not enter an API key. Please try again.', 'resend' );
				break;
			case 'new-key-invalid':
				$message = __( 'The API key you entered is invalid. Please double-check it.', 'resend' );
				break;
			case 'test-email-not-set':
				$message = __( 'Please provide a valid email address to send a test email.', 'resend' );
				break;
			case 'test-email-sent':
				$message = __( 'Test email sent!', 'resend' );
				break;
			case 'test-email-failed':
				$message = __( 'Failed to send a test email.', 'resend' );
				break;
			default:
				$message = $type;
		}

		return $message;
	}

	/**
	 * Add help to the Resend page.
	 */
	public static function admin_help() {
		$current_screen = get_current_screen();

		if ( current_user_can( 'manage_options' ) ) {
			if ( ! Resend::get_api_key() || ( isset( $_GET['view'] ) && 'start' === $_GET['view'] ) ) {
				// Setup page
				$current_screen->add_help_tab(
					array(
						'id'      => 'overview',
						'title'   => __( 'Overview', 'resend' ),
						'content' =>
							'<p>' . esc_html__( 'Resend is the best way to reach humans instead of spam folders. Deliver transactional and marketing emails at scale.', 'resend' ) . '</p>' .
							'<p>' . esc_html__( 'On this page, you are able to connect Resend to your site.', 'resend' ) . '</p>',
					)
				);

				$current_screen->add_help_tab(
					array(
						'id'      => 'setup-signup',
						'title'   => __( 'New to Resend', 'resend' ),
						'content' =>
							'<p>' . esc_html__( 'You need to enter an API key to connect Resend to your site.', 'resend' ) . '</p>' .
							/* translators: %s: sign up link */
							'<p>' . sprintf( __( 'Sign up for an account on %s to get an API key.', 'resend' ), '<a href="https://resend.com/home" target="_blank">Resend.com</a>' ) . '</p>',

					)
				);
			} elseif ( isset( $_GET['view'] ) && 'stats' === $_GET['view'] ) {
			} else {
				// Configuration page
				$current_screen->add_help_tab(
					array(
						'id'      => 'overview',
						'title'   => __( 'Overview', 'resend' ),
						'content' =>
							'<p>' . esc_html__( 'Resend is the best way to reach humans instead of spam folders. Deliver transactional and marketing emails at scale.', 'resend' ) . '</p>' .
							'<p>' . esc_html__( 'On this page, you are able to update your Resend settings and view your email history.', 'resend' ) . '</p>',
					)
				);
			}
		}

		$current_screen->set_help_sidebar(
			'<p><strong>' . esc_html__( 'For more information:', 'resend' ) . '</strong></p>' .
			'<p><a href="https://resend.com/docs" target="_blank">' . esc_html__( 'Resend Documentation', 'resend' ) . '</a></p>' .
			'<p><a href="https://resend.com/help" target="_blank">' . esc_html__( 'Resend Support', 'resend' ) . '</a></p>'
		);
	}

	public static function ajax_enter_api_key() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( self::json_status( 'not-allowed' ) );
		}

		check_admin_referer( self::NONCE );

		$new_key = sanitize_text_field( isset( $_POST['key'] ) ? wp_unslash( $_POST['key'] ) : '' );
		$old_key = Resend::get_api_key();

		$result = array( false, 'no-change-to-key' );

		if ( empty( $new_key ) ) {
			if ( ! empty( $old_key ) ) {
				delete_option( 'resend_api_key' );
			}
			$result = array( false, 'new-key-empty' );
		} elseif ( $new_key !== $old_key ) {
			if ( Resend::is_valid_key( $new_key ) ) {
				update_option( 'resend_api_key', $new_key );
				$result = array( true, 'new-key-valid' );
			} else {
				$result = array( false, 'new-key-invalid' );
			}
		}

		list($is_successful, $status) = $result;

		$is_successful
			? wp_send_json_success( self::json_status( $status ) )
			: wp_send_json_error( self::json_status( $status ) );
	}

	public static function ajax_send_test_email() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( self::json_status( 'not-allowed' ) );
		}

		check_admin_referer( self::NONCE );

		$email = sanitize_email( isset( $_POST['email'] ) ? wp_unslash( $_POST['email'] ) : '' );
		if ( ! $email || ! is_email( $email ) ) {
			wp_send_json_error( self::json_status( 'test-email-not-set' ) );
		}

		$subject = 'Resend Test: ' . html_entity_decode( get_bloginfo( 'name' ) );
		$message = 'This is a test email sent using the Resend plugin.';
		$result  = wp_mail( $email, $subject, $message );

		$result
			? wp_send_json_success( self::json_status( 'test-email-sent' ) )
			: wp_send_json_error( self::json_status( 'test-email-failed' ) );
	}

	public static function plugin_action_links( $links, $file ) {
		if ( plugin_basename( plugin_dir_url( __FILE__ ) . '/resend.php' ) === $file ) {
			$links[] = '<a href="' . esc_url( self::get_page_url() ) . '">' . esc_html__( 'Settings', 'resend' ) . '</a>';
		}

		return $links;
	}

	public static function get_page_url( $page = 'config' ) {
		$base_url = admin_url( 'options-general.php' );
		$args     = array( 'page' => 'resend' );

		if ( 'stats' === $page ) {
			$args = array(
				'page' => 'resend',
				'view' => 'stats',
			);
		} elseif ( 'init' === $page ) {
			$args = array(
				'page' => 'resend',
				'view' => 'start',
			);
		}

		return add_query_arg( $args, $base_url );
	}

	public static function get_asset_file_version( $relative_path ) {
		$full_path = RESEND__PLUGIN_DIR . $relative_path;

		return RESEND_VERSION;
	}
}
