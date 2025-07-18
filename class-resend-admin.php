<?php

class Resend_Admin {

	public const NONCE = 'resend-nonce';

	private static $initiated = false;

	private static $notices = array();

	public static function init() {
		if ( ! self::$initiated ) {
			self::init_hooks();
		}

		if ( isset( $_POST['action'] ) && 'enter-key' === $_POST['action'] ) {
			self::enter_api_key();
		} elseif ( isset( $_POST['action'] ) && 'send-test' === $_POST['action'] ) {
			self::send_test_email();
		}
	}

	public static function init_hooks() {
		self::$initiated = true;

		add_action( 'admin_init', array( 'Resend_Admin', 'admin_init' ) );
		add_action( 'admin_menu', array( 'Resend_Admin', 'admin_menu' ), 5 );
		add_action( 'admin_enqueue_scripts', array( 'Resend_Admin', 'load_resources' ) );

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
			wp_register_script( 'resend-admin.js', plugin_dir_url( __FILE__ ) . $resend_admin_js_path, array(), self::get_asset_file_version( $resend_admin_js_path ) );
			wp_enqueue_script( 'resend-admin.js' );
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

	public static function add_status( $message, $type = 'resend-error' ) {
		self::$notices['status'] = array(
			'type'    => $type,
			'message' => $message,
		);
	}

	public static function display_status() {
		if ( ! empty( self::$notices ) ) {
			foreach ( self::$notices as $index => $type ) {
				if ( is_array( $type ) ) {
					$message = '';

					if ( isset( $type['message'] ) ) {
						$raw_message = is_array( $type['message'] ) && isset( $type['message']['message'] ) ? $type['message']['message'] : $type['message'];
						$message     = wp_kses( $raw_message, array() );
					}

					if ( isset( $type['type'] ) ) {
						$type = wp_kses( $type['type'], array() );
						Resend::view( 'notice', compact( 'type', 'message' ) );

						unset( self::$notices[ $index ] );
					}
				} else {
					Resend::view( 'notice', compact( 'type' ) );

					unset( self::$notices[ $index ] );
				}
			}
		}
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

	public static function enter_api_key() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) ) {
			return false;
		}

		$new_key = $_POST['key'];
		$old_key = Resend::get_api_key();

		if ( empty( $new_key ) ) {
			if ( ! empty( $old_key ) ) {
				delete_option( 'resend_api_key' );
			}
			self::$notices['status'] = 'new-key-empty';
		} elseif ( $new_key !== $old_key ) {
			self::save_key( $new_key );
		}

		return true;
	}

	public static function save_key( $api_key ) {
		$key_status = Resend::verify_key( $api_key );

		if ( 'valid' === $key_status ) {
			update_option( 'resend_api_key', $api_key );

			self::$notices['status'] = 'new-key-valid';
		} else {
			self::$notices['status'] = 'new-key-invalid';
		}
	}

	public static function send_test_email() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], self::NONCE ) ) {
			return false;
		}

		if ( isset( $_POST['email'] ) && is_email( $_POST['email'] ) ) {
			$to = sanitize_email( $_POST['email'] );
		} else {
			self::$notices['status'] = 'test-email-not-set';
			return;
		}

		$subject = 'Resend Test: ' . html_entity_decode( get_bloginfo( 'name' ) );
		$message = 'This is a test email sent using the Resend plugin.';
		$headers = array();

		$response = wp_mail( $to, $subject, $message, $headers );

		if ( false !== $response ) {
			self::$notices['status'] = 'test-email-sent';
		} else {
			// self::$notices['status'] = 'test-email-failed';
		}

		return true;
	}

	public static function plugin_action_links( $links, $file ) {
		if ( plugin_basename( plugin_dir_url( __FILE__ ) . '/resend.php' ) === $file ) {
			$links[] = '<a href="' . esc_url( self::get_page_url() ) . '">' . esc_html__( 'Settings', 'resend' ) . '</a>';
		}

		return $links;
	}

	public static function get_page_url( $page = 'config' ) {
		$args = array( 'page' => 'resend' );

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

		return add_query_arg( $args, menu_page_url( 'resend', false ) );
	}

	public static function get_asset_file_version( $relative_path ) {
		$full_path = RESEND__PLUGIN_DIR . $relative_path;

		return RESEND_VERSION;
	}
}
