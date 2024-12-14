<?php

namespace Hostinger\EasyOnboarding\Admin;

use Hostinger\EasyOnboarding\Admin\Onboarding\Onboarding;
use Hostinger\EasyOnboarding\Admin\Actions as Admin_Actions;
use Hostinger\EasyOnboarding\Admin\Hooks as Admin_Hooks;

defined( 'ABSPATH' ) || exit;

class Ajax {
    public function __construct() {
		add_action( 'init', array( $this, 'define_ajax_events' ), 0 );
	}

    /**
     * @return void
     */
	public function define_ajax_events(): void {
		$events = array(
            'identify_action',
		);

		foreach ( $events as $event ) {
			add_action( 'wp_ajax_hostinger_' . $event, array( $this, $event ) );
		}
	}

    /**
     * @return void
     */
    public function identify_action(): void {
        $nonce          = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';
        $security_check = $this->request_security_check( $nonce );

        if ( ! empty( $security_check ) ) {
            wp_send_json_error( $security_check );
        }

        $action = sanitize_text_field( $_POST['action_name'] ) ?? '';

        if ( in_array( $action, Admin_Actions::ACTIONS_LIST, true ) ) {
            setcookie( $action, $action, time() + ( 86400 ), '/' );
            wp_send_json_success( $action );
        } else {
            wp_send_json_error( 'Invalid action' );
        }
    }

    /**
     * @param $nonce
     *
     * @return false|string
     */
	public function request_security_check( $nonce ) {
		if ( ! wp_verify_nonce( $nonce, 'hts-ajax-nonce' ) ) {
			return 'Invalid nonce';
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return 'Lack of permissions';
		}

		return false;
	}
}
