<?php 
/**
 * Chatway internal user APIs
 *
 * @author  : Chatway
 * @license : GPLv3
 * */

namespace Chatway\App;
/**
 * @since 1.0.0
 * Create an internal API group for users by extending Api class 
 */ 
class User extends Api
{
    use Singleton;
    
    public function config() {
        // this prefix will used in api endpoint - example: /chatway/v1/user
        $this->prefix = 'user';
    }

    /**
     * @method POST
     * @api /chatway/v1/user/save 
     * 
     * Save user current user data. Initiall it receives user identifier and token
     */ 
    public function post_save() {
        $params          = $this->request->get_params();
        $user_identifier = sanitize_text_field( isset( $params['user_identifier'] ) ? $params['user_identifier'] : '' );
        $token           = sanitize_text_field( isset( $params['token'] ) ? $params['token'] : '' );
        
        // clear the cache of the user is new
        $old_identifier = get_option( 'chatway_user_cache_identifier' );
        if ( $old_identifier != $user_identifier ) {
            chatway_clear_all_caches();
        }

        // delete all data
        delete_option( 'chatway_user_identifier' );
        delete_option( 'chatway_user_cache_identifier' );
        delete_option( 'chatway_token' );

        if ( ! empty( $user_identifier ) && ! empty( $token ) ) {
            // save user identifier and token to DB
            add_option( 'chatway_user_identifier', $user_identifier );
            add_option( 'chatway_user_cache_identifier', $user_identifier );
            add_option( 'chatway_token', $token );

            return [
                'code'    => 200,
                'message' => 'success',
            ]; 
        }

        return [
            'code'    => 401,
            'message' => 'error',
        ]; 
    }

    /**
     * @method GET
     * @api /chatway/v1/user/logout 
     * 
     * Remote everything related to the current user from DB
     */ 
    public function get_logout() {
        delete_option( 'chatway_user_identifier' );
        delete_option( 'chatway_token' );

        return [
            'code'    => 200,
            'message' => 'success',
        ];
    }
}