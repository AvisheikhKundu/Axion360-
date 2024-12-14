<?php
/**
 * Omnisend Client
 *
 * @package OmnisendClient
 */

namespace Omnisend\SDK\V1;

use WP_Error;

defined( 'ABSPATH' ) || die( 'no direct access' );

class GetContactResponse {

	private Contact $contact;
	private WP_Error $wp_error;

	/**
	 * @param Contact|null $contact
	 * @param WP_Error $wp_error
	 */
	public function __construct( ?Contact $contact, WP_Error $wp_error ) {
		$this->contact  = $contact;
		$this->wp_error = $wp_error;
	}

	public function get_contact(): Contact {
		return $this->contact;
	}

	public function get_wp_error(): WP_Error {
		return $this->wp_error;
	}
}
