<?php
/**
 * Omnisend plugin
 *
 * @package OmnisendPlugin
 */

namespace Omnisend\Internal;

use Omnisend_Core_Bootstrap;

defined( 'ABSPATH' ) || die( 'no direct access' );

class Connection {

	public static $landing_page_url = 'https://app.omnisend.com/registrationv2?utm_source=wordpress_plugin&utm_content=landing_page';

	public static function display(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.' ) );
		}

		Options::set_landing_page_visited();

		if ( self::show_connected_store_view() ) {
			?>
			<div id="omnisend-connected"></div>
			<?php
			return;
		}

		if ( self::show_connection_view() ) {
			?>
			<div id="omnisend-connection"></div>
			<?php
			return;
		}

		self::resolve_wordpress_settings();

		require_once __DIR__ . '/../../view/landing-page.html';
	}

	public static function resolve_wordpress_settings(): void {
		$url      = 'https://api.omnisend.com/wordpress/settings?version=' . OMNISEND_CORE_PLUGIN_VERSION;
		$response = wp_remote_get( $url );

		if ( ! is_wp_error( $response ) ) {
			$body = wp_remote_retrieve_body( $response );

			$data = json_decode( $body, true );
			if ( ! empty( $data['exploreOmnisendLink'] ) ) {
				self::$landing_page_url = $data['exploreOmnisendLink'];
			}
		}
	}

	private static function get_account_data( $api_key ): array {
		$response = wp_remote_get(
			OMNISEND_CORE_API_V3 . '/accounts',
			array(
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-Key'    => $api_key,
				),
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) ) {
			return array();
		}

		$body = wp_remote_retrieve_body( $response );

		if ( empty( $body ) ) {
			return array();
		}

		$arr = json_decode( $body, true );

		return is_array( $arr ) ? $arr : array();
	}

	public static function show_connected_store_view(): bool {
		return Options::is_store_connected();
	}

	public static function show_connection_view(): bool {
		$connected = Options::is_store_connected();

		if ( ! $connected && ! empty( $_GET['action'] ) && 'show_connection_form' == $_GET['action'] ) {
			if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ?? '' ) ), 'show_connection_form' ) ) {
				die( 'nonce verification failed: ' . __FILE__ . ':' . __LINE__ );
			}
			return true;
		}

		return false;
	}

	private static function connect_store( $api_key ): bool {
		$data = array(
			'website'         => site_url(),
			'platform'        => 'wordpress',
			'version'         => OMNISEND_CORE_PLUGIN_VERSION,
			'phpVersion'      => phpversion(),
			'platformVersion' => get_bloginfo( 'version' ),
		);

		$response = wp_remote_post(
			OMNISEND_CORE_API_V3 . '/accounts',
			array(
				'body'    => wp_json_encode( $data ),
				'headers' => array(
					'Content-Type' => 'application/json',
					'X-API-Key'    => $api_key,
				),
				'timeout' => 10,
			)
		);

		if ( is_wp_error( $response ) ) {
			return false;
		}

		$http_code = wp_remote_retrieve_response_code( $response );
		if ( $http_code >= 400 ) {
			return false;
		}

		$body = wp_remote_retrieve_body( $response );
		if ( ! $body ) {
			return false;
		}

		$arr = json_decode( $body, true );

		return ! empty( $arr['verified'] );
	}

	public static function connect_with_omnisend_for_woo_plugin(): void {
		if ( Options::is_connected() ) {
			return; // Already connected.
		}

		if ( ! Omnisend_Core_Bootstrap::is_omnisend_woocommerce_plugin_active() ) {
			return;
		}

		$api_key = get_option( OMNISEND_CORE_WOOCOMMERCE_PLUGIN_API_KEY_OPTION );
		if ( ! $api_key ) {
			return;
		}

		$response = self::get_account_data( $api_key );
		if ( empty( $response['brandID'] ) ) {
			return;
		}

		Options::set_api_key( $api_key );
		Options::set_brand_id( $response['brandID'] );
		Options::set_store_connected();
	}

	public static function omnisend_post_connection() {
		$connected = Options::is_store_connected();

		// phpcs:ignore WordPress.WP.CapitalPDangit.MisspelledInText
		$wordpress_platform = 'wordpress'; // WordPress is lowercase as it's required by integration.

		if ( ! current_user_can( 'manage_options' ) ) {
			return rest_ensure_response(
				array(
					'success' => false,
					'error'   => 'You do not have sufficient permissions to perform this action.',
				)
			);
		}

		if ( ! isset( $_POST['action_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['action_nonce'] ) ), 'connect' ) ) {
			return rest_ensure_response(
				array(
					'success' => false,
					'error'   => 'Nonce verification failed.',
				)
			);
		}

		if ( empty( $_POST['api_key'] ) ) {
			return rest_ensure_response(
				array(
					'success' => false,
					'error'   => 'API key is required.',
				)
			);
		}

		if ( ! $connected && ! empty( $_POST['api_key'] ) ) {
			$api_key  = sanitize_text_field( wp_unslash( $_POST['api_key'] ) );
			$response = self::get_account_data( $api_key );
			$brand_id = ! empty( $response['brandID'] ) ? $response['brandID'] : '';

			if ( ! $brand_id ) {
				return rest_ensure_response(
					array(
						'success' => false,
						'error'   => 'The connection did not go through. Check if the API key is correct.',
					)
				);
			}

			if ( $response['verified'] === true && $response['platform'] !== $wordpress_platform ) {
				return rest_ensure_response(
					array(
						'success' => false,
						'error'   => 'This Omnisend account is already connected to non-WordPress site. Log in to access it.',
					)
				);
			}

			$connected = false;
			if ( $response['platform'] === $wordpress_platform ) {
				$connected = true;
			}

			if ( $response['platform'] === '' ) {
				$connected = self::connect_store( $api_key );
			}

			if ( $connected ) {
				Options::set_api_key( $api_key );
				Options::set_brand_id( $brand_id );
				Options::set_store_connected();

				if ( ! wp_next_scheduled( OMNISEND_CORE_CRON_SYNC_CONTACT ) && ! Omnisend_Core_Bootstrap::is_omnisend_woocommerce_plugin_connected() ) {
					wp_schedule_event( time(), OMNISEND_CORE_CRON_SCHEDULE_EVERY_MINUTE, OMNISEND_CORE_CRON_SYNC_CONTACT );
				}
				return rest_ensure_response(
					array(
						'success' => true,
						'error'   => '',
					)
				);
			}

			Options::disconnect(); // Store was not connected, clean up.
			return rest_ensure_response(
				array(
					'success' => false,
					'error'   => 'The connection did not go through. Check if the API key is correct.',
				)
			);
		}

		return rest_ensure_response(
			array(
				'success' => false,
				'error'   => 'Something went wrong. Please try again.',
			)
		);
	}
}
