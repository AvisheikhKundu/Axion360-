<?php
/**
 * Omnisend Client
 *
 * @package OmnisendClient
 */

namespace Omnisend\SDK\V1;

use WP_Error;

defined( 'ABSPATH' ) || die( 'no direct access' );

class SendCustomerEventResponse {

	private WP_Error $wp_error;

	/**
	 * @param WP_Error $wp_error
	 */
	public function __construct( WP_Error $wp_error ) {
		$this->wp_error = $wp_error;
	}

	public function get_wp_error(): WP_Error {
		return $this->wp_error;
	}
}
