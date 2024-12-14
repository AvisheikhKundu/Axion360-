<?php 
/**
 * Chatway reusable urls
 *
 * @author  : Chatway
 * @license : GPLv3
 * */

namespace Chatway\App;

class Url {
    use Singleton;

    public static $environment = 'prod'; // dev | prod

    private static function base_url( $key, $endpoint ) {
        $urls = [];
        if( self::$environment === 'dev' ) {
            $urls = [
                'api' => 'https://dev-api.chatway.app/api',
                'app' => 'https://dev-go.chatway.app',
                'widget' => 'https://dev-cdn.chatway.app'
            ];
        } else {
            $urls = [
                'api' => 'https://api.chatway.app/api',
                'app' => 'https://go.chatway.app',
                'widget' => 'https://cdn.chatway.app'
            ];
        }

        return $urls[$key] . $endpoint;
    }
    
    public static function iframe_src( $token = '' ) {
        return self::base_url( 'app', '/wordpress?token=' . $token );
    }

    public static function full_screen_url() {
        return self::base_url( 'app', '/fullscreen' );

    }

    public static function remote_api( $endpoint = '' ) {
        return self::base_url( 'api', $endpoint );
    }

    public static function widget_script( $identifier = '' ) {
        return self::base_url( 'widget', '/widget.js?include[]=faqs&id=' . $identifier );
    }

    public static function internal_api( $endpoint = '' ) {
        return 'chatway/v1' . $endpoint;
    }

    public static function site_url( $slug = '' ) {
        return get_site_url() . '/' . $slug;
    }

    public static function admin_url( $route = '' ) {
        return admin_url( 'admin.php?page=chatway' ) . $route;
    }

    public static function landing_page() {
        return "https://chatway.app/";
    }

    public static function terms_of_service() {
        return "https://chatway.app/terms-of-service/";
    }

    public static function privacy_policy() {
        return "https://chatway.app/privacy-policy/";
    }

    public static function contact_us() {
        return 'https://chatway.app/contact-us/';
    }
}