<?php
/**
 * Chatway admin assets enqueue
 *
 * @author  : Chatway
 * @license : GPLv3
 * */

namespace Chatway\App;

class Assets {
    use Singleton;
    
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [$this, 'enqueue_admin_assets'] );

        add_action( 'wp_enqueue_scripts', [$this, 'enqueue_chatway'] );
    }

    /**
     * Enqueues the Chatway script if the user identifier option is not empty.
     *
     * @return void
     */
    public function enqueue_chatway() {
        $user_identifier = get_option( 'chatway_user_identifier', '' );
        if ( ! empty( $user_identifier ) ) :
            $dependencies = \Chatway::include_once( 'assets/js/app.asset.php' );
            wp_enqueue_script( "chatway-script", esc_url( Url::widget_script( $user_identifier ) ), [], $dependencies['version'] , true );
            $data = [
                'widgetId' => $user_identifier
            ];
            wp_localize_script( 'chatway-script', 'wpChatwaySettings',  $data );
        endif;
    }

    public function enqueue_admin_assets() {
        /**
         * prepare dynamic dependencies 
         */ 
        $file_path = \Chatway::require( 'assets/js/app.asset.php', true );
        if( file_exists( $file_path ) ) {
            $file = require $file_path;
            $version = $file['version'];
            $dependencies = $file['dependencies'];
            $dependencies[] = 'jquery';

            /**
             * enqueue admin assets 
             */ 
            wp_enqueue_style( 'chatway-fonts', \Chatway::url( 'assets/css/fonts.css' ), [], \Chatway::version(), false );
            wp_enqueue_script(
                'chatway-app', \Chatway::url( 'assets/js/app.js' ), $dependencies, $version, [
                    'in_footer' => true,
                    'strategy'  => 'defer'
                ] 
            );
            wp_enqueue_style( 'chatway-app', \Chatway::url( 'assets/css/app.css' ), [], $dependencies, false );

            wp_localize_script(
                'chatway-app', 'chatway', [
                    'images'           => \Chatway::url( 'assets/images/' ),
                    'dashboardUrl'     => Url::admin_url(),
                    'fullScreenUrl'    => Url::full_screen_url(),
                    'internalEndpoint' => Url::internal_api(),
                    'remoteEndpoint'   => Url::remote_api(),
                    'landingPage'      => Url::landing_page(),
                    "termsOfService"   => Url::terms_of_service(),
                    "privacyPolicy"    => Url::privacy_policy(),
                    'token'            => get_option( 'chatway_token', '' ),
                ] 
            );
        } 
    }
}
