<?php

namespace Hostinger\Admin;

defined( 'ABSPATH' ) || exit;

class Ajax {
	private const TWO_DAYS                = 86400 * 2;
	public const AJAX_METHOD_PREFIX       = 'wp_ajax_hostinger_';
	public const HIDE_PLUGIN_SPLIT_NOTICE = 'hts_plugin_split_notice_hidden';

	public const AJAX_EVENTS = array(
		'dismiss_plugin_split_notice',
	);

	public function __construct() {
		add_action( 'admin_init', array( $this, 'define_ajax_events' ), 0 );
	}

	public function define_ajax_events(): void {
		foreach ( self::AJAX_EVENTS as $event ) {
			add_action( self::AJAX_METHOD_PREFIX . $event, array( $this, $event ) );
		}
	}

	public function dismiss_plugin_split_notice(): void {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
		// @codeCoverageIgnoreStart
		if ( ! wp_verify_nonce( $nonce, 'hts_close_plugin_split' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}
		// @codeCoverageIgnoreEnd
		set_transient( self::HIDE_PLUGIN_SPLIT_NOTICE, true, self::TWO_DAYS );
	}
}
