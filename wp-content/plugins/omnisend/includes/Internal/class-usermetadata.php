<?php
/**
 * Omnisend plugin
 *
 * @package OmnisendPlugin
 */

namespace Omnisend\Internal;

defined( 'ABSPATH' ) || die( 'no direct access' );

class UserMetaData {
	public const LAST_SYNC = 'omni_send_core_last_sync';

	public static function mark_synced( $user_id ) {
		update_user_meta( $user_id, self::LAST_SYNC, gmdate( DATE_ATOM, time() ) );
	}

	public static function mark_sync_error( $user_id ) {
		update_user_meta( $user_id, self::LAST_SYNC, 'ERROR' );
	}

	public static function mark_sync_skipped( $user_id ) {
		update_user_meta( $user_id, self::LAST_SYNC, 'SKIPPED' );
	}
}
