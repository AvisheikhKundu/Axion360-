<?php 
/**
 * Chatway external/remote APIs
 *
 * @author  : Chatway
 * @license : GPLv3
 * */

namespace Chatway\App;

class ExternalApi {
    use Singleton;
    
    /**
     * @return 'invalid' | 'server-down' | 'valid'
     */ 
    static function get_token_status() {
        $token    = get_option( 'chatway_token', '' );
        $response = wp_remote_get( 
            Url::remote_api( "/market-apps/connected?channel=wordpress" ), 
            [
                'redirect' => 'follow',
                'headers'  => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ],
            ]
        ); 

        $response_code = wp_remote_retrieve_response_code( $response );

        if ( 200 === $response_code ) return 'valid';
        if ( 521 === $response_code ) return 'server-down';

        return 'invalid';
    }

    /**
     * Send the plugin status to the Chatway server
     * @param string $status install | uninstall
     * @return boolean
     */
    static function update_plugins_status( $status = 'install' ) {
        $token      = get_option( 'chatway_token', '' );
        $user_id    = get_option( 'chatway_user_identifier', '' );
        
        if( empty( $token ) || empty( $user_id ) ) return false;

        $response = wp_remote_post( 
            Url::remote_api( "/wordpress/" . $status ), 
            [
                'redirect' => 'follow',
                'headers'  => [
                    'Accept'        => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ],
            ]
        );  

        $response_code = wp_remote_retrieve_response_code( $response );

        if ( 200 === $response_code ) return true;

        return false;
    }
}