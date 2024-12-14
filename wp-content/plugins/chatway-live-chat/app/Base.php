<?php
/**
 * Chatway admin base
 *
 * @author  : Chatway
 * @license : GPLv3
 * */

namespace Chatway\App;

class Base {
    use Singleton;
    
    public function __construct() {
        add_action( 'admin_init', [$this, 'plugin_redirect'] );
    }

    /**
     * Redirects to the Chatway admin page if the chatway_redirection option is set and DOING_AJAX is not defined.
     * This function checks if the chatway_redirection option is set to true. If it is, it deletes the option and redirects
     * to the admin.php?page=chatway URL using wp_redirect function. It then exits the script execution.
     *
     * @return void
     */
    public function plugin_redirect() {
        if ( ! defined( "DOING_AJAX" ) && get_option( 'chatway_redirection', false ) ) {
            delete_option( 'chatway_redirection' );
            exit( wp_redirect( admin_url("admin.php?page=chatway") ) );
        }
    }

    /**
     * Activates the plugin by setting up a temporary redirection key.
     * The user will be redirected to the Plugin Page on installation.
     * The temporary redirection key is removed as soon as it's called for the first time.
     *
     * @return void
     */
    public function activate() {
        ExternalApi::update_plugins_status( 'install' );
        /**
         * We want to take the user to the Plugin Page on installation.
         * Hence setting up a temporary redirection key.
         * It gets removed as soon as it's called for the first time.
         * Ussage at : plugin_redirect, and called with admin_init
         */
        if ( ! defined( "DOING_AJAX" ) ) {
            add_option( 'chatway_redirection', true );
        }

    }

    public function deactivate() {
        delete_option( 'chatway_redirection' );
    }
}
