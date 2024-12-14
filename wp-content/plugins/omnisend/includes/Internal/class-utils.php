<?php
/**
 * Omnisend Contact Utils
 *
 * @package OmnisendClient
 */

namespace Omnisend\Internal;

! defined( 'ABSPATH' ) && die( 'no direct access' );

class Utils {

	/**
	 * Validate custom property name.
	 *
	 * @param $name
	 *
	 * @return bool
	 */
	public static function is_valid_custom_property_name( $name ): bool {
		return is_string( $name ) && preg_match( '/^[a-zA-Z0-9_]{1,128}$/', $name );
	}

	/**
	 * Clean up custom property name.
	 *
	 * @param $name
	 *
	 * @return string
	 */
	public static function clean_up_custom_property_name( $name ): string {
		$name = preg_replace( '/[^a-zA-Z0-9_]/', '', $name );
		return substr( $name, 0, 128 );
	}

	/**
	 * Validate tag.
	 *
	 * @param $tag
	 *
	 * @return bool
	 */
	public static function is_valid_tag( $tag ): bool {
		return is_string( $tag ) && preg_match( '/^[a-zA-Z0-9_\- ]{1,128}$/', $tag );
	}

	/**
	 * Clean up tag name.
	 *
	 * @param $tag
	 *
	 * @return string
	 */
	public static function clean_up_tag( $tag ): string {
		$tag = preg_replace( '/[^A-Za-z0-9_\- ]/', '', $tag );

		return substr( $tag, 0, 128 );
	}
}
