<?php
/**
 * Plugin Name:       Chatway Live Chat
 * Contributors:      galdub, tomeraharon
 * Description:       Chatway is a live chat app. Use Chatway to chat with your website's visitors.
 * Version:           1.2.6
 * Tested up to:      6.7
 * Author:            Chatway
 * Author URI:        https://chatway.app/
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       chatway
 * Domain Path:       /languages
 */

use Chatway\App\ExternalApi;

class Chatway {
    function __construct() {
        add_action( 'plugins_loaded', [$this, 'boot'] );
    }

    /**
     * @source chatway.php
     * You need to change version from 4 different places. 
     * 1. chatway.php comment section 
     * 2. chatway.php version() method 
     * 3. Gruntfile.js version property
     * 4. readme.txt Stable tag
     */ 
    public static function version() {
        return '1.2.6';
    }

    public function boot() {
        $this->add_textdomain();
        new Chatway\App\Assets();
        new Chatway\App\View();
        new Chatway\App\User();
    }

    private function add_textdomain() {
        load_plugin_textdomain( 'chatway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }

    /**
     * Get the path or require any file
     * @param string $file_or_dir takes the path based on the root dir
     * @param boolean $path_only (optional, default: false) if you want the path in return make the value true 
     */ 
    public static function require( $file_or_dir = '', $path_only = false ) { 
        if ( ! $path_only ) {
            require trailingslashit( plugin_dir_path( __FILE__ ) ) . $file_or_dir;
        } else {
            return trailingslashit( plugin_dir_path( __FILE__ ) ) . $file_or_dir;
        }
    }

    /**
     * Include once or include once and get the path
     * @param string $file takes the path based on the root dir
     * @param boolean $no_return (optional, default: false) if you want the path in return make the value true 
     */ 
    public static function include_once( $file = '', $no_return = false ) {
        if ( ! $no_return ) {
            return include_once( self::require( $file, true ) );
        } else {
            include_once( self::require( $file, true ) );
        }
    }

    /**
     * Get the url of any assets file like css, js, images, fonts etc.
     * @param string $file - Define the file path based on the plugin root directory 
     */ 
    public static function url( $file = '' ) {
        return esc_url( trailingslashit( plugins_url( '/', __FILE__ ) ) . $file );
    }
}

/**
 * Autoloader 
 */ 
require_once( 'autoloader.php' );
require_once( 'inc/clear-all-cache.php' );
$loader = new Chatway\AutoLoader();
$loader->register();
/**
 * register the namespace
 * 
 * @param {1} will take the namespace
 * @param {2} path of the folder 
 */ 
$loader->add_namespace( 'Chatway\App', Chatway::require( 'app', true ) );

/**
 * Register the activation and deactivation hook 
 */ 
$base = new Chatway\App\Base();
register_activation_hook( __FILE__, [ $base, 'activate' ] );
register_deactivation_hook( __FILE__, [ $base, 'deactivate' ] );


/**
 * Register the uninstall hook 
 */
if( ! function_exists( 'chatway_uninstall_hook' ) ) {
    function chatway_uninstall_hook() {
      ExternalApi::update_plugins_status( 'uninstall' );
    }
}

register_uninstall_hook( __FILE__, 'chatway_uninstall_hook' );
/**
 * Initialize the plugin 
 */ 
new Chatway();