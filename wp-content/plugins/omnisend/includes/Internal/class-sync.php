<?php
/**
 * Omnisend Contact Utils
 *
 * @package OmnisendClient
 */

namespace Omnisend\Internal;

use Omnisend\SDK\V1\Contact;
use Omnisend\SDK\V1\Omnisend;

! defined( 'ABSPATH' ) && die( 'no direct access' );

class Sync {

	/**
	 * Sync and sets omnisend contact id for user
	 *
	 * @param $user_id
	 * @return void
	 */
	public static function identify_user_by_id( $user_id ): void {
		$user = get_userdata( $user_id );
		if ( $user ) {
			$omnisend_contact_id = self::sync_contact( $user );
			if ( $omnisend_contact_id ) {
				Snippet::set_contact_cookie_id( $omnisend_contact_id );
			}
		}
	}

	/**
	 * @param int $limit number of users to sync
	 */
	public static function sync_contacts( int $limit = 100 ): void {
		$wp_user_query = new \WP_User_Query(
			array(
				'number'     => $limit,
				// meta_query is required to work as sync information is stored in contact metadata.
				'meta_query' => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					'relation' => 'OR',
					array(
						'key'     => UserMetaData::LAST_SYNC,
						'compare' => 'NOT EXISTS',
						'value'   => '',
					),
				),
			)
		);
		$users         = $wp_user_query->get_results();

		if ( empty( $users ) ) {
			wp_clear_scheduled_hook( OMNISEND_CORE_CRON_SYNC_CONTACT );
			return;
		}

		foreach ( $users as $user ) {
			self::sync_contact( $user );
		}
	}

	/**
	 * @param \WP_User $user
	 * @return string
	 */
	private static function sync_contact( $user ): string {
		$contact = new Contact();
		$contact->add_tag( 'WordPress' );

		if ( ! filter_var( $user->user_email, FILTER_VALIDATE_EMAIL ) ) {
			UserMetaData::mark_sync_skipped( $user->ID );
			return '';
		}

		$contact->set_email( $user->user_email );

		$first_name = get_user_meta( $user->ID, 'first_name', true );
		if ( $first_name ) {
			$contact->set_first_name( $first_name );
		}

		$last_name = get_user_meta( $user->ID, 'last_name', true );
		if ( $last_name ) {
			$contact->set_last_name( $last_name );
		}

		$roles        = get_user_meta( $user->ID, 'wp_capabilities', true );
		$parsed_roles = array();
		if ( is_array( $roles ) ) {
			foreach ( $roles as $role => $active ) {
				if ( $active ) {
					$parsed_roles[] = $role;
				}
			}

			if ( ! empty( $parsed_roles ) ) {
				$contact->add_custom_property( 'wordpress_roles', array_unique( $parsed_roles ) );
			}
		}

		$response = Omnisend::get_client( OMNISEND_CORE_PLUGIN_NAME, OMNISEND_CORE_PLUGIN_VERSION )->create_contact( $contact );
		if ( $response->get_contact_id() ) {
			UserMetaData::mark_synced( $user->ID );
		} else {
			UserMetaData::mark_sync_error( $user->ID );
		}

		return $response->get_contact_id();
	}
}
