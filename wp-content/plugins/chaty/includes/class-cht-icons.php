<?php
/**
 * Premio Affilate Class
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

namespace CHT\includes;

if (defined('ABSPATH') === false) {
    exit;
}

class CHT_Widget
{

    /**
     * The Slug of this plugin.
     *
     * @var    string    $pluginSlug    The Slug of this plugin.
     * @since  1.0.0
     * @access protected
     */
    protected $pluginSlug = 'chaty-app';

    /**
     * The friendly name of this plugin.
     *
     * @var    string    $friendlyName    The friendly name of this plugin.
     * @since  1.0.0
     * @access protected
     */
    protected $friendlyName = 'Chaty Widget';

    /**
     * Object of class
     *
     * @var    object    $instance    Object of class
     * @since  1.0.0
     * @access protected
     */
    protected static $instance = null;


    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since 1.0.0
     */
    public function __construct()
    {

    }//end __construct()


    /**
     * Returns instance of class
     *
     * @since  1.0.0
     * @access public
     * @return object
     */
    public static function get_instance()
    {
        // If the single instance hasn't been set, set it now.
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;

    }//end get_instance()


    /**
     * Returns plugin slug
     *
     * @since  1.0.0
     * @access public
     * @return string
     */
    public function get_plugin_slug()
    {
        return $this->pluginSlug;

    }//end get_plugin_slug()


    /**
     * Returns plugin name
     *
     * @since  1.0.0
     * @access public
     * @return string
     */
    public function get_name()
    {
        return $this->friendlyName;

    }//end get_name()


}//end class
