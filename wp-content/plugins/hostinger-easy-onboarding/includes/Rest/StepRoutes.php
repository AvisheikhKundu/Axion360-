<?php
namespace Hostinger\EasyOnboarding\Rest;

use Hostinger\EasyOnboarding\Admin\Onboarding\Onboarding;
use Hostinger\EasyOnboarding\Helper;

/**
 * Avoid possibility to get file accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
    die;
}

/**
 * Class for handling Settings Rest API
 */
class StepRoutes {
    public const LIST_VISIBILITY_OPTION = 'hostinger_onboarding_list_visibility';
    /**
     * @param \WP_REST_Request $request
     *
     * @return \WP_REST_Response
     */
    public function get_steps( \WP_REST_Request $request ): \WP_REST_Response {
        $parameters = $request->get_params();

        $locale = !empty($parameters['locale']) ? sanitize_text_field($parameters['locale']) : '';
        $available_languages = get_available_languages();

        if (!empty($locale) && in_array($locale, $available_languages)) {
            switch_to_locale($locale);
        }

        $onboarding = new Onboarding();
        $onboarding->init();

        $data = array(
            'data' => array(
                'steps'  => $onboarding->get_step_categories(),
            )
        );

        $response = new \WP_REST_Response( $data );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        $response->set_status( \WP_Http::OK );

        return $response;
    }


    public function complete_step( \WP_REST_Request $request ): \WP_Error|\WP_REST_Response
    {
        $parameters = $request->get_params();

        $errors = array();

        if ( empty( $parameters['step_category_id'] ) ) {
            /* translators: %s field name that is missing */
            $errors['step_category_id'] = sprintf( __( '%s missing or empty', 'hostinger-easy-onboarding' ), 'step category id');
        }

        if ( empty( $parameters['step_id'] ) ) {
            $errors['step_id'] = sprintf( __( '%s missing or empty', 'hostinger-easy-onboarding' ), 'step category id') ;
        }

        if ( ! empty( $errors ) ) {
            return new \WP_Error(
                'data_invalid',
                __( 'Sorry, there are validation errors.', 'hostinger-easy-onboarding' ),
                array(
                    'status' => \WP_Http::BAD_REQUEST,
                    'errors' => $errors,
                )
            );
        }

        $step_category_id = sanitize_text_field( $parameters['step_category_id'] );
        $step_id = sanitize_text_field( $parameters['step_id'] );

        $onboarding = new Onboarding();
        $onboarding->init();

        $validate_step = $onboarding->validate_step( $step_category_id, $step_id );

        if(empty($validate_step)) {
            return new \WP_Error(
                'data_invalid',
                __( 'Step category and/or step does not exist.', 'hostinger-easy-onboarding' ),
                array(
                    'status' => \WP_Http::BAD_REQUEST
                )
            );
        }

        $data = array(
            'data' => array(
                'saved' => $onboarding->complete_step( $step_category_id, $step_id )
            )
        );

        if ( has_action( 'litespeed_purge_all' ) ) {
            do_action( 'litespeed_purge_all' );
        }

        $response = new \WP_REST_Response( $data );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        $response->set_status( \WP_Http::OK );

        return $response;
    }

    function toggle_list_visibility( \WP_REST_Request $request ) {
        $current_state = get_option( self::LIST_VISIBILITY_OPTION, 1 );

        $new_state = !(bool)$current_state;

        $update = update_option( self::LIST_VISIBILITY_OPTION, (int)$new_state );

        return new \WP_REST_Response( array(
            'status' => $update,
            'new_state' => $new_state
        ), 200 );
    }

        /**
     * @param \WP_REST_Request $request
     *
     * @return \WP_REST_Response|\WP_Error
     */
    public function activate_plugin( \WP_REST_Request $request ): \WP_REST_Response|\WP_Error {
        $parameters = $request->get_params();

        $plugin = !empty($parameters['plugin']) ? sanitize_text_field($parameters['plugin']) : '';

        $errors = array();

        if(empty($plugin)) {
            $errors['plugin'] = sprintf( __( '%s missing or empty', 'hostinger-easy-onboarding' ), 'plugin' );
        }

		$helper = new Helper();
        $plugin_path = $helper->get_plugin_main_file( $plugin );

        if ( is_plugin_active( $plugin_path ) ) {
            /* translators: %s plugin slug */
            $errors['plugin'] = sprintf( __( '%s is already active', 'hostinger-easy-onboarding' ), 'plugin' );
        }

        if ( ! empty( $errors ) ) {
            return new \WP_Error(
                'data_invalid',
                __( 'Sorry, there are validation errors.', 'hostinger-easy-onboarding' ),
                array(
                    'status' => \WP_Http::BAD_REQUEST,
                    'errors' => $errors,
                )
            );
        }

        $activated = activate_plugin( $plugin_path );

        if ( is_wp_error( $activated ) ) {
            return new \WP_Error(
                'data_invalid',
                __( 'Sorry, there are activation errors.', 'hostinger-easy-onboarding' ),
                array(
                    'status' => \WP_Http::BAD_REQUEST,
                    'errors' => $activated->get_error_message(),
                )
            );
        }

        $response = new \WP_REST_Response( array( 'data' => '' ) );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        $response->set_status( \WP_Http::OK );

        return $response;
    }

}