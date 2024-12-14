<?php
/**
 * Omnisend plugin
 *
 * @package OmnisendPlugin
 */

namespace Omnisend\Internal;

defined( 'ABSPATH' ) || die( 'no direct access' );

class Snippet {


	public static function add() {
		$brand_id = Options::get_brand_id();
		if ( $brand_id ) {
			require_once __DIR__ . '/../../view/snippet.html';
		}
	}

	public static function set_contact_cookie_id( $contact_id ) {
		$host   = wp_parse_url( home_url(), PHP_URL_HOST );
		$expiry = strtotime( '+1 year' );
		setcookie( 'omnisendContactID', $contact_id, $expiry, '/', $host );
	}
}
