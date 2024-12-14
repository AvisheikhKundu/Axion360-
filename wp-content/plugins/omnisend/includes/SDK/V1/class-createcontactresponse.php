<?php
/**
 * Omnisend Client
 *
 * @package OmnisendClient
 */

namespace Omnisend\SDK\V1;

use WP_Error;

defined( 'ABSPATH' ) || die( 'no direct access' );

class CreateContactResponse {

	private string $contact_id;

	private WP_Error $wp_error;

	/**
	 * @param string $contact_id
	 * @param WP_Error $wp_error
	 */
	public function __construct( string $contact_id, WP_Error $wp_error ) {
		$this->contact_id = $contact_id;
		$this->wp_error   = $wp_error;
	}

	public function get_contact_id(): string {
		return $this->contact_id;
	}

	public function get_wp_error(): WP_Error {
		return $this->wp_error;
	}
}
