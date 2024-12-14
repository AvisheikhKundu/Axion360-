<?php
/**
 * Omnisend plugin
 *
 * Plugin Name: Omnisend Email Marketing
 * Description: Omnisend main plugin that enables integration with Omnisend.
 * Version: 1.5.5
 * Author: Omnisend
 * Author URI: https://www.omnisend.com
 * Developer: Omnisend
 * Developer URI: https://developers.omnisend.com
 * Text Domain: omnisend
 * ------------------------------------------------------------------------
 * Copyright 2024 Omnisend
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package OmnisendPlugin
 */

use Omnisend\Internal\Options;
use Omnisend\Internal\Connection;

defined( 'ABSPATH' ) || die( 'no direct access' );

const OMNISEND_CORE_PLUGIN_VERSION = '1.5.5';
const OMNISEND_CORE_SETTINGS_PAGE  = 'omnisend';
const OMNISEND_CORE_PLUGIN_NAME    = 'Email Marketing by Omnisend';
const OMNISEND_MENU_TITLE          = 'Omnisend Email Marketing';

const OMNISEND_CORE_CRON_SCHEDULE_EVERY_MINUTE = 'omni_send_core_every_minute';

const OMNISEND_CORE_CRON_SYNC_CONTACT = 'omni_send_cron_sync_contacts';

// Change for different environment.
const OMNISEND_CORE_API_V3      = 'https://api.omnisend.com/v3';
const OMNISEND_CORE_API_V5      = 'https://api.omnisend.com/v5';
const OMNISEND_CORE_SNIPPET_URL = 'https://omnisnippet1.com/inshop/launcher-v2.js';

// Omnisend for Woo plugin.
const OMNISEND_CORE_WOOCOMMERCE_PLUGIN_API_KEY_OPTION = 'omnisend_api_key';



spl_autoload_register( array( 'Omnisend_Core_Bootstrap', 'autoloader' ) );

register_uninstall_hook( __FILE__, 'Omnisend_Core_Bootstrap::uninstall' );
add_action( 'plugins_loaded', 'Omnisend_Core_Bootstrap::load' );

class Omnisend_Core_Bootstrap {
	public static function load(): void {
		self::load_react();
		// Cron every minute only for short period of time (after connection) to sync WP users to Omnisend. After sync cron is disabled.
		add_filter( 'cron_schedules', array( 'Omnisend_Core_Bootstrap', 'cron_schedules' ) ); //phpcs:ignore WordPress.WP.CronInterval.CronSchedulesInterval
		add_action( 'rest_api_init', 'Omnisend_Core_Bootstrap::omnisend_register_connection_routes' );
		add_action( 'in_admin_header', 'Omnisend_Core_Bootstrap::hide_notices' );

		add_action( 'admin_notices', 'Omnisend_Core_Bootstrap::admin_notices' );
		add_action( 'admin_menu', 'Omnisend_Core_Bootstrap::add_admin_menu' );
		add_action( 'admin_enqueue_scripts', 'Omnisend_Core_Bootstrap::load_omnisend_admin_styles' );
		add_action( 'wp_enqueue_scripts', 'Omnisend_Core_Bootstrap::load_omnisend_site_styles' );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'Omnisend_Core_Bootstrap::add_links_in_plugin_settings' );

		add_action( 'admin_init', 'Omnisend\Internal\Connection::connect_with_omnisend_for_woo_plugin' );

		if ( ! self::is_omnisend_woocommerce_plugin_active() || ! self::is_omnisend_woocommerce_plugin_connected() ) {
			add_action( 'wp_footer', 'Omnisend\Internal\Snippet::add' );

			add_action( 'user_register', 'Omnisend\Internal\Sync::identify_user_by_id' );
			add_action(
				'wp_login',
				function ( $user_login, $user ) {
					Omnisend\Internal\Sync::identify_user_by_id( $user->ID );
				},
				10,
				2
			);
			add_action( 'profile_update', 'Omnisend\Internal\Sync::identify_user_by_id' );

			add_action( OMNISEND_CORE_CRON_SYNC_CONTACT, 'Omnisend\Internal\Sync::sync_contacts' );
		}
		self::migrate_options();
	}


	public static function omnisend_app_market() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		?>
		<div id="omnisend-app-market"></div>
		<?php
	}

	public static function omnisend_hostinger_discount_notice() {
		?>
		<div id="omnisend-hostinger-discount-notice"></div>
		<?php
	}

	public static function omnisend_register_connection_routes() {
		register_rest_route(
			'omnisend/v1',
			'/connect',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => 'Omnisend\Internal\Connection::omnisend_post_connection',
				'permission_callback' => function () {
					return is_user_logged_in();
				},
			)
		);
	}


	public static function add_admin_menu() {
		$page_title    = OMNISEND_CORE_PLUGIN_NAME;
		$menu_title    = OMNISEND_MENU_TITLE . ( self::show_notification_icon() ? ' <span class="update-plugins count-1"><span class="plugin-count">1</span></span>' : '' );
		$capability    = 'manage_options';
		$menu_slug     = OMNISEND_CORE_SETTINGS_PAGE;
		$function      = 'Omnisend\Internal\Connection::display';
		$omnisend_icon = plugin_dir_url( __FILE__ ) . 'assets/img/omnisend-logo.png';
		$position      = 2;

		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $omnisend_icon, $position );
		add_submenu_page( $menu_slug, $page_title, 'Home', $capability, $menu_slug, $function );
		add_submenu_page(
			$menu_slug,
			'Add-Ons',
			'Add-Ons',
			$capability,
			'omnisend-app-market',
			array( 'Omnisend_Core_Bootstrap', 'omnisend_app_market' )
		);
	}

	public static function add_links_in_plugin_settings( $actions ) {
		$omnisend_link = array(
			'<a style="color: #35938F; font-weight: bold" href="' . admin_url( 'admin.php?page=omnisend' ) . '">Go to Omnisend</a>',
		);
		$add_ons_link  = array(
			'<a href="' . admin_url( 'admin.php?page=omnisend-app-market' ) . '">Add-ons</a>',
		);
		$actions       = array_merge( $omnisend_link, $add_ons_link, $actions );
		return $actions;
	}

	public static function cron_schedules( $schedules ) {
		$schedules[ OMNISEND_CORE_CRON_SCHEDULE_EVERY_MINUTE ] = array(
			'interval' => 60,
			'display'  => __( 'Every minute', 'omnisend' ),
		);

		return $schedules;
	}


	private static function migrate_options() {
		$landing_page_visited            = Options::is_landing_page_visited();
		$landing_page_notification_state = Options::get_landing_page_notification_state();
		$landing_page_last_visit_time    = Options::get_landing_page_last_visit_time();

		if ( $landing_page_visited && ( $landing_page_notification_state === NOTIFICATION_NOT_SHOWN || $landing_page_last_visit_time === 0 ) ) {
			Options::set_landing_page_visited();
		}
	}

	private static function show_notification_icon(): bool {
		$last_visit_time    = Options::get_landing_page_last_visit_time();
		$notification_state = Options::get_landing_page_notification_state();
		$current_time       = time();

		return ! Options::is_connected() &&
		( ( ! Options::is_landing_page_visited() ) || ( $notification_state === NOTIFICATION_DELAYED && ( $current_time - $last_visit_time ) > Options::get_notification_delay_time() ) );
	}

	public static function load_omnisend_admin_styles(): void {
		wp_enqueue_style(
			'roboto.css',
			plugin_dir_url( __FILE__ ) . 'assets/fonts/roboto/roboto.css?' . time(),
			array(),
			OMNISEND_CORE_PLUGIN_VERSION,
		);
		wp_enqueue_style(
			'styles.css',
			plugin_dir_url( __FILE__ ) . 'styles/styles.css?' . time(),
			array(),
			OMNISEND_CORE_PLUGIN_VERSION,
		);
		wp_enqueue_style(
			'site-styles.css',
			plugin_dir_url( __FILE__ ) . 'styles/site-styles.css?' . time(),
			array(),
			OMNISEND_CORE_PLUGIN_VERSION,
		);
		wp_enqueue_style(
			'notice-styles.css',
			plugin_dir_url( __FILE__ ) . 'styles/notice-styles.css?' . time(),
			array(),
			OMNISEND_CORE_PLUGIN_VERSION,
		);
	}

	public static function load_omnisend_site_styles(): void {
		wp_enqueue_style(
			'site-styles.css',
			plugin_dir_url( __FILE__ ) . 'styles/site-styles.css?' . time(),
			array(),
			OMNISEND_CORE_PLUGIN_VERSION,
		);
	}

	public static function hide_notices(): void {
		$screen = get_current_screen();
		if ( $screen && ( $screen->id === 'toplevel_page_omnisend' || $screen->id === 'omnisend-email-marketing_page_omnisend-app-market' ) ) {
			echo '<style>[class*="notice"]:not([class*="components"], .omnisend-notice, .notice), .notice:not(.omnisend-notice) { display: none !important; }</style>';
		}
	}

	public static function admin_notices(): void {
		if ( Options::is_connected() && self::is_omnisend_woocommerce_plugin_active() && ! get_option( OMNISEND_CORE_WOOCOMMERCE_PLUGIN_API_KEY_OPTION ) ) {
			echo '<div class="notice notice-error omnisend-notice"><p>Since you have already connected the <strong>Omnisend</strong> plugin, to use <strong>Omnisend for Woocommerce</strong> please contact <a href=mailto:"support@omnisend.com">customer support</a>.</p></div>';
		} elseif ( ! Options::is_connected() && ( is_plugin_active( 'woocommerce/woocommerce.php' ) || self::is_omnisend_woocommerce_plugin_active() ) && ! self::is_omnisend_woocommerce_plugin_connected() ) {
			echo '<div class="notice notice-error omnisend-notice"><p>If you are using WooCommerce, we strongly recommend starting with the <a href="https://wordpress.org/plugins/omnisend-connect/" target="_blank"><strong>Omnisend for WooCommerce</strong></a> plugin. Install it and follow the instructions.</p></div>';
		}

		if ( isset( $_SERVER['REQUEST_URI'] ) ) {
			$request_uri = esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			if ( strpos( $request_uri, '/wp-admin/admin.php?page=omnisend' ) !== false && self::is_hostinger_plugin_active() ) {
					self::omnisend_hostinger_discount_notice();

			}
		}
	}

	public static function load_react(): void {

		add_action(
			'admin_enqueue_scripts',
			function ( $suffix ) {
				$asset_file_page = plugin_dir_path( __FILE__ ) . 'build/notices.asset.php';
				if ( file_exists( $asset_file_page ) && ( 'toplevel_page_omnisend' === $suffix || self::normalize_menu_title_to_suffix() === $suffix ) ) {
					$assets = require_once $asset_file_page;
					wp_enqueue_script(
						'notices-script',
						plugin_dir_url( __FILE__ ) . 'build/notices.js',
						$assets['dependencies'],
						$assets['version'],
						true
					);
					foreach ( $assets['dependencies'] as $style ) {
						wp_enqueue_style( $style );
					}
				}
			}
		);

		if ( Connection::show_connected_store_view() ) {
			add_action(
				'admin_enqueue_scripts',
				function ( $suffix ) {
					$asset_file_page = plugin_dir_path( __FILE__ ) . 'build/connected.asset.php';
					if ( file_exists( $asset_file_page ) && 'toplevel_page_omnisend' === $suffix ) {
						$assets = require_once $asset_file_page;
						wp_enqueue_script(
							'connected-script',
							plugin_dir_url( __FILE__ ) . 'build/connected.js',
							$assets['dependencies'],
							$assets['version'],
							true
						);
						foreach ( $assets['dependencies'] as $style ) {
							wp_enqueue_style( $style );
						}
					}
				}
			);
		}

		if ( Connection::show_connection_view() ) {
			add_action(
				'admin_enqueue_scripts',
				function ( $suffix ) {
					$asset_file_page = plugin_dir_path( __FILE__ ) . 'build/connection.asset.php';
					if ( file_exists( $asset_file_page ) && 'toplevel_page_omnisend' === $suffix ) {
						$assets = require_once $asset_file_page;
						wp_enqueue_script(
							'connection-script',
							plugin_dir_url( __FILE__ ) . 'build/connection.js',
							$assets['dependencies'],
							$assets['version'],
							true
						);
						foreach ( $assets['dependencies'] as $style ) {
							wp_enqueue_style( $style );
						}
						wp_localize_script(
							'connection-script',
							'omnisend_connection',
							array(
								'nonce'        => wp_create_nonce( 'wp_rest' ),
								'action_nonce' => wp_create_nonce( 'connect' ),
								'site_url'     => site_url(),
							)
						);
					}
				}
			);
		}

		add_action(
			'admin_enqueue_scripts',
			function ( $suffix ) {
				$asset_file_page = plugin_dir_path( __FILE__ ) . 'build/appMarket.asset.php';
				if ( file_exists( $asset_file_page ) && self::normalize_menu_title_to_suffix() === $suffix ) {
					$assets = require_once $asset_file_page;
					wp_enqueue_script(
						'omnisend-app-market-script',
						plugin_dir_url( __FILE__ ) . 'build/appMarket.js',
						$assets['dependencies'],
						$assets['version'],
						true
					);
					foreach ( $assets['dependencies'] as $style ) {
						wp_enqueue_style( $style );
					}
				}
			}
		);
	}

	// when menu title is changed, this function should be updated or checked as well.
	private static function normalize_menu_title_to_suffix(): string {
		return str_replace( ' ', '-', strtolower( OMNISEND_MENU_TITLE ) ) . '_page_omnisend-app-market';
	}

	public static function is_omnisend_woocommerce_plugin_active(): bool {
		return in_array( 'omnisend-connect/omnisend-woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}

	public static function is_hostinger_plugin_active(): bool {
		return in_array( 'hostinger/hostinger.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}

	public static function is_omnisend_woocommerce_plugin_connected(): bool {
		return self::is_omnisend_woocommerce_plugin_active() && get_option( 'omnisend_account_id', null ) !== null;
	}

	/**
	 * Autoloader function to load classes dynamically.
	 *
	 * @param string $class_name The name of the class to load.
	 */
	public static function autoloader( $class_name ): void {
		$namespace = 'Omnisend';

		if ( strpos( $class_name, $namespace ) !== 0 ) {
			return;
		}

		$class       = str_replace( $namespace . '\\', '', $class_name );
		$class_parts = explode( '\\', $class );
		$class_file  = 'class-' . strtolower( array_pop( $class_parts ) ) . '.php';

		$directory = plugin_dir_path( __FILE__ );
		$path      = $directory . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . implode( DIRECTORY_SEPARATOR, $class_parts ) . DIRECTORY_SEPARATOR . $class_file;

		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}

	public static function uninstall(): void {
		Options::delete_all();
	}
}
