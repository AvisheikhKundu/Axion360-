<?php
/**
 * Omnisend Client
 *
 * @package OmnisendClient
 */

namespace Omnisend\SDK\V1;

use Omnisend\Internal\Options;

defined( 'ABSPATH' ) || die( 'no direct access' );

class Omnisend {

	/**
	 * Factory to create Omnisend client.
	 *
	 * @param $plugin string plugin using client name
	 * @param $version string plugin using client version
	 * @return Client
	 */
	public static function get_client( $plugin, $version ): Client {
		return new \Omnisend\Internal\V1\Client( Options::get_api_key(), (string) $plugin, (string) $version );
	}

	/**
	 * Check and return if plugin connected to Omnisend account. If connection does not exist, it will not be possible
	 * to send data to Omnisend.
	 *
	 * @return bool
	 */
	public static function is_connected(): bool {
		return Options::get_api_key() != '';
	}
}
