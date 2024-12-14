<?php
namespace DynamicVisibilityForElementor;

trait Validation {

	/**
	 * Validate HTML Tag
	 *
	 * @param string $tag
	 * @return string
	 */
	public static function validate_html_tag( $tag ) {
		$allowed_tags = self::ALLOWED_HTML_WRAPPER_TAGS;
		return in_array( strtolower( $tag ), $allowed_tags, true ) ? $tag : 'div';
	}

	/**
	 * Validate User Fields
	 *
	 * @template T of string|array<string>
	 * @param T $fields
	 * @return ?T
	 */

	/**
	 * Validate User Fields
	 *
	 * @param string|array<string> $fields
	 * @return ($fields is string ? ?string : array<string>)
	 */
	public static function validate_user_fields( $fields ) {
		$not_allowed = self::NOT_ALLOWED_USER_FIELDS;

		if ( is_string( $fields ) ) {
			return in_array( $fields, $not_allowed, true ) ? null : $fields;
		} elseif ( is_array( $fields ) ) {
			$fields = array_filter( $fields, function ( $field ) use ( $not_allowed ) {
				return ! in_array( $field, $not_allowed, true );
			});
			return $fields;
		}
	}

	/**
	 * Validate Post Types
	 *
	 * @param string|array<string>|void $post_type
	 * @return string|array<string>|null
	 */
	public static function validate_post_types( $post_type ) {
		$allowed_post_types = Helper::get_public_post_types();

		if ( is_string( $post_type ) && array_key_exists( $post_type, $allowed_post_types ) ) {
			return $post_type;
		} elseif ( is_array( $post_type ) ) {
			$post_type = array_filter( $post_type, function ( $type ) use ( $allowed_post_types ) {
				return array_key_exists( $type, $allowed_post_types );
			});
			return $post_type;
		}
		return null;
	}

	/**
	 * Validate Post ID
	 *
	 * @param int|string|array<int|string>|array<int,string> $id
	 * @return int|array<int|string>|null
	 */
	public static function validate_post_id( $id ) {
		if ( is_string( $id ) || is_int( $id ) ) {
			if ( is_post_publicly_viewable( intval( $id ) ) ) {
				return intval( $id );
			}
		} elseif ( is_array( $id ) ) {
			$ids = array_filter( $id, function ( $id ) {
				return is_post_publicly_viewable( intval( $id ) );
			});
			return $ids;
		}
		return null;
	}

	/**
	 * Validate Server Key
	 *
	 * @param string $key
	 * @return string|null
	 */
	public static function validate_server_key( $key ) {
		$allowed = [ 'REMOTE_ADDR' ];
		$allowed = apply_filters( 'dynamicooo/tokens/server-whitelist', $allowed );
		return in_array( $key, $allowed, true ) ? $key : null;
	}

	/**
	 * Validate Session Key
	 *
	 * @param string $key
	 * @return string|null
	 */
	public static function validate_session_key( $key ) {
		$allowed = [];
		$allowed = apply_filters( 'dynamicooo/tokens/session-whitelist', $allowed );
		return in_array( $key, $allowed, true ) ? $key : null;
	}
}
