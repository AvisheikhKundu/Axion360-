<?php
/**
 * Chaty Admin Class
 *
 * @author  : Premio <contact@premio.io>
 * @license : GPL2
 * */

namespace CHT\admin;

use CHT\includes\CHT_Widget;

if (defined('ABSPATH') === false) {
    exit;
}

/*
 * Class CHT_Admin_Base
 * @since 1.0
 */

require_once 'class-social-icons.php';

/**
 * Admin base class for the Chaty plugin.
 */
class CHT_Admin_Base
{

    public $page;

    public $socials;

    public $colors;

    public $pluginSlug;

    protected $token;

    protected static $response = null;

    protected $upgradeSlug;

    /**
     * Constructor for the class
     *
     * @return void
     * @since  1.0.0
     * @access public
     */
    public function __construct()
    {
        $plugin           = CHT_Widget::get_instance();
        $this->pluginSlug = $plugin->get_plugin_slug();
        $this->socials      = CHT_Social_Icons::get_instance()->get_icons_list();
        $this->colors       = CHT_Social_Icons::get_instance()->get_colors();
        $this->token        = $this->get_token();
        $this->upgradeSlug  = $this->pluginSlug.'-upgrade';

        if (is_admin()) {
            // admin actions
            add_action('admin_menu', [$this, 'cht_admin_setting_page']);
            // Adds all of the options for the administrative settings
            add_action('admin_init', [$this, 'cht_register_inputs']);
            add_action('admin_init', [$this, 'change_menu_text']);
            add_action('admin_head', [$this, 'cht_inline_css_admin']);
        }

        // add_action('updated_option', array($this, 'cht_clear_all_caches'));
        // Send message to owner
        add_action('wp_ajax_wcp_admin_send_message_to_owner', [$this, 'wcp_admin_send_message_to_owner']);

        // ADD Upgrade link to plugin
        add_filter('plugin_action_links_'.CHT_PLUGIN_BASE, [$this, 'plugin_action_links']);

        add_action('admin_footer', [$this, 'add_deactivate_modal']);
        add_action('wp_ajax_chaty_plugin_deactivate', [$this, 'chaty_plugin_deactivate']);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_styles'], 99);

        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts'], 99);

        add_action("wp_ajax_chaty_update_status", [$this, 'chaty_update_status']);

        // load language files
        add_action('plugins_loaded', [$this, 'chaty_text']);

        add_action("wp_ajax_update_popup_status", [$this, 'update_popup_status']);
        add_action("wp_ajax_update_channel_setting", [$this, 'update_channel_setting']);

        /*
         * Hide Chaty CTA
         * */
        add_action('wp_ajax_hide_chaty_cta', array($this, 'hide_chaty_cta'));

        add_action("admin_head", [$this, "admin_head"]);

        add_action("admin_init", [$this, "check_for_redirection"]);

        add_action('wp_ajax_get_chatway_status', [$this, 'get_chatway_status']);
        add_filter('check_for_chatway', [$this, 'check_for_chatway']);
        add_filter('check_for_chatway_status', [$this, 'check_for_chatway_status']);
    }//end __construct()


    function get_chatway_status()
    {
        echo $this->check_for_chatway_status();
        wp_die();
    }

    public function check_for_chatway()
    {
        $plugin = 'chatway-live-chat/chatway.php';
        return is_plugin_active($plugin);
    }

    public function check_for_chatway_status()
    {
        if ( !function_exists( 'get_plugins' ) || !function_exists( 'is_plugin_active' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        $plugin = 'chatway-live-chat/chatway.php';
        if (!isset($all_plugins[$plugin])) {
            return 'not-installed';
        } else if(!is_plugin_active($plugin)){
            return 'not-activated';
        } else if(!get_option( 'chatway_user_identifier', false )) {
            return 'not-logged-in';
        } else {
            $token      = get_option( 'chatway_token', '' );
            $user_id    = get_option( 'chatway_user_identifier', '' );
            if( empty( $token ) || empty( $user_id ) ) {
                return 'not-logged-in';
            }

            $response = wp_safe_remote_get(
                'https://prod-api.chatway.app/api/profile?include[]=',
                [
                    'redirect' => 'follow',
                    'headers'  => [
                        'Accept'        => 'application/json',
                        'Authorization' => 'Bearer ' . $token
                    ],
                ]
            );
            
            if (is_wp_error($response)) {
                return 'not-logged-in';
            }

            $response = json_decode(wp_remote_retrieve_body($response), true);

            if(isset($response['message']) && $response['message'] == 'Unauthenticated.') {
                delete_option( 'chatway_token' );
                delete_option( 'chatway_user_identifier' );
                return 'not-logged-in';
            } else if(!isset($response['data']['attributes']['registration_step']) || $response['data']['attributes']['registration_step'] != 'ONBOARDED') {
                return 'not-onboarded';
            }
        }
        return 'completed';
    }

    /**
     * Checks for redirection and performs the necessary actions if certain conditions are met.
     *
     * @return void
     */
    function check_for_redirection() {
        if(isset($_GET['page']) && $_GET['page'] == 'cht-manage-live-chat') {
            wp_redirect(admin_url('admin.php?page=chatway'));
            exit;
        }
        if(!defined("DOING_AJAX")) {
            $chaty_status = get_option("cht_redirect");
            if($chaty_status) {
                delete_option("cht_redirect");
                wp_redirect(admin_url("admin.php?page=chaty-app"));
                exit;
            }

            $page = isset($_GET['page']) ? $_GET['page'] : "";
            if (!empty($page)) {
                if (in_array($page, ["widget-analytics", "chaty-contact-form-feed", "recommended-chaty-plugins", "chaty-app-upgrade"])) {
                    $isShown = get_option("chaty_update_message");
                    if ($isShown === false) {
                        wp_redirect(admin_url("admin.php?page=chaty-app"));
                        exit;
                    }
                }
            }
        }
    }

    /**
     * Add custom CSS to the wp-admin head section
     *
     * @return void
     * @since  1.0.0
     * @access public
     */
    function admin_head() {
        ?>
        <style>
            #adminmenu .toplevel_page_chaty-app > ul > li:last-child {
                padding: 5px 10px;
            }
            #adminmenu .toplevel_page_chaty-app > ul > li:last-child a {
                display: flex;
                background-color: #B78DEB;
                border-radius: 6px;
                font-size: 12px;
                gap: 4px;
                padding: 4px 8px;
                color: #ffffff;
                align-items: center;
                transition: all 0.2s linear;
                font-weight: normal;
                box-shadow: 0px 6px 8px 0px #B78DEB3D;
                justify-content: center;
            }
            #adminmenu .toplevel_page_chaty-app > ul > li:last-child a:hover, #adminmenu .toplevel_page_chaty-app > ul > li:last-child a.current {
                box-shadow: 0px 6px 8px 0px #B78DEB3D;
                color: #ffffff;
                background-color: #9565d0;
                font-weight: normal;
            }
            #adminmenu .toplevel_page_chaty-app > ul > li:last-child a span {
                flex: 0 0 16px;
                height: 16px;
                background-color: #c5a4ef;
                border-radius: 4px;
                padding: 2px;
                display: inline-flex;
                transition: all 0.2s linear;
            }
            #adminmenu .toplevel_page_chaty-app > ul > li:last-child a:hover span {
                background-color: #B78DEB;
            }
            #adminmenu .toplevel_page_chaty-app > ul > li:last-child a span svg {
                width: 100%;
                height: 100%;
            }
        </style>
        <?php
    }


    /**
     * Hide the Chaty CTA
     *
     * This function is used to hide the Chaty Call-To-Action (CTA) based on the provided input.
     * It checks the validity of the request by verifying the nonce value.
     * If the request is valid, it sets the "hide_chaty_cta" option to "yes" and returns a success response.
     * If the request is not valid, it returns an error response.
     *
     * @return void
     * @global array $_POST The input data received via POST request
     * @global WP_REST_Response $response The JSON response object
     * @since  Version 1.0.0
     * @access public
     */
    function hide_chaty_cta()
    {
        $response = array();
        $response['status'] = 0;
        $response['error'] = 0;
        $response['data'] = array();
        $response['message'] = "";
        $postData = filter_input_array(INPUT_POST);
        $errorCounter = 0;
        if (!isset($postData['nonce']) || empty($postData['nonce'])) {
            $response['message'] =  esc_html__("Your request is not valid", 'chaty');
            $errorCounter++;
        } else {
            $nonce = esc_attr($postData['nonce']);
            if(!wp_verify_nonce($nonce, 'hide_chaty_cta')) {
                $response['message'] =  esc_html__("Your request is not valid", 'chaty');
                $errorCounter++;
            }
        }
        if($errorCounter == 0) {
            $response['status'] = 1;
            add_option("hide_chaty_cta", "yes");
        }
        wp_send_json($response); die;
    }


    /**
     * Add custom links to the plugin action links on the plugin page
     *
     * @param array $links An array of plugin action links.
     *
     * @return array The modified plugin action links.
     * @since  1.0.0
     * @access public
     *
     */
    public function plugin_action_links($links)
    {
        $links['need_help'] = '<a target="_blank" href="https://premio.io/help/chaty/?utm_source=pluginspage" >'.__('Need help?', 'chaty').'</a>';
        $links['go_pro']    = '<a style="color: #FF5983; font-weight: bold; display: inline-block; border: solid 1px #FF5983; border-radius: 4px; padding: 0 5px;" href="'.CHT_PRO_URL.'" class="chaty-plugins-gopro">'.esc_attr__('Upgrade', 'chaty').'</a>';
        return $links;

    }//end plugin_action_links()


    /**
     * Deactivate the Chaty plugin.
     *
     * @return void
     * @since 1.0.0
     * @access public
     */
    public function chaty_plugin_deactivate()
    {
        $errorCounter        = 0;
        $response            = [];
        $response['status']  = 0;
        $response['message'] = "";
        $response['valid']   = 1;
        $reason = filter_input(INPUT_POST, 'reason');
        $nonce  = filter_input(INPUT_POST, 'nonce');
        if (empty($reason)) {
            $errorCounter++;
            $response['message'] = "Please provide reason";
        } else if (empty($nonce)) {
            $response['message'] = esc_attr__("Your request is not valid", 'chaty');
            $errorCounter++;
            $response['valid'] = 0;
        } else if (!current_user_can("manage_options")) {
            $response['message'] = esc_attr__("Your request is not valid", 'chaty');
            $errorCounter++;
            $response['valid'] = 0;
        } else {
            if (!wp_verify_nonce($nonce, 'chaty_deactivate_nonce')) {
                $response['message'] = esc_attr__("Your request is not valid", 'chaty');
                $errorCounter++;
                $response['valid'] = 0;
            }
        }

        if ($errorCounter == 0) {
            global $current_user;
            $email = "none@none.none";

            $emailId = filter_input(INPUT_POST, 'email_id');
            if (isset($emailId) && !empty($emailId) && filter_var($emailId, FILTER_VALIDATE_EMAIL)) {
                $email = $emailId;
            }

            $domain    = site_url();
            $user_name = $current_user->first_name." ".$current_user->last_name;

            $response['status'] = 1;

            // sending message to Crisp
            $postMessage = [];

            $messageData          = [];
            $messageData['key']   = "Plugin";
            $messageData['value'] = "Chaty";
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "Plugin Version";
            $messageData['value'] = CHT_VERSION;
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "Domain";
            $messageData['value'] = $domain;
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "Email";
            $messageData['value'] = $email;
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "WordPress Version";
            $messageData['value'] = esc_attr(get_bloginfo('version'));
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "PHP Version";
            $messageData['value'] = PHP_VERSION;
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "Message";
            $messageData['value'] = $reason;
            $postMessage[]        = $messageData;

            $apiParams = [
                'domain'  => $domain,
                'email'   => $email,
                'url'     => site_url(),
                'name'    => $user_name,
                'message' => $postMessage,
                'plugin'  => "Chaty",
                'type'    => "Uninstall",
            ];

            // Sending message to Crisp API
            $apiResponse = wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", ['body' => $apiParams, 'timeout' => 15, 'sslverify' => true]);

            if (is_wp_error($apiResponse)) {
                wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", ['body' => $apiParams, 'timeout' => 15, 'sslverify' => false]);
            }
        }//end if

        wp_send_json($response);
        wp_die();

    }//end chaty_plugin_deactivate()


    /**
     * Sanitize options value
     *
     * @param mixed $value The value to be sanitized
     * @return mixed The sanitized value
     * @since  1.0.0
     * @access public
     */
    public static function chaty_sanitize_options($value)
    {
        $value = stripslashes($value);
        $value = filter_var($value);
        $value = htmlspecialchars($value);
        return $value;

    }//end chaty_sanitize_options()


    /**
     * Add deactivate modal to the plugin's settings page
     *
     * This function is responsible for checking if the current user has the capability to manage options. If the user
     * has the capability, it checks if the current page is the plugins.php page. If it is, it includes the chaty-deactivate-form.php
     * file in order to display the deactivate modal.
     *
     * @return void
     * @since  1.0.0
     * @access public
     */
    public function add_deactivate_modal()
    {
        if (current_user_can("manage_options")) {
            global $pagenow;

            if ('plugins.php' !== $pagenow) {
                return;
            }

            include CHT_DIR.'/views/admin/chaty-deactivate-form.php';
        }

    }//end add_deactivate_modal()


    /**
     * Remove underscores and replace them with spaces in a given text
     *
     * @param string $text The text to remove underscores from
     *
     * @return string The modified text with spaces instead of underscores
     * @since 1.0.0
     * @access public
     *
     */
    public function del_space($text)
    {
        return str_replace('_', ' ', $text);

    }//end del_space()


    /**
     * Generate inline CSS for admin menu styling
     *
     * @return string Inline CSS for admin menu styling
     * @since 1.0.0
     *
     */
    public function cht_inline_css_admin()
    {
        ?>
        <style>
            #toplevel_page_chaty-app img:hover, #toplevel_page_chaty-app img {
                opacity: 0 !important;
            }

            #toplevel_page_chaty-app:hover .dashicons-before {
                background-color: #00b9eb;
            }

            #toplevel_page_chaty-app .dashicons-before {
                background-color: #A0A3A8;
                -webkit-mask: url('<?php echo esc_url(plugins_url('chaty/admin/assets/images/chaty.svg')) ?>') no-repeat center;
                mask: url('<?php echo esc_url(plugins_url('chaty/admin/assets/images/chaty.svg')) ?>') no-repeat center;
            }

            .current#toplevel_page_chaty-app .dashicons-before {
                background-color: #fff;
            }
        </style>
        <?php
    }//end cht_inline_css_admin()


    /**
     * Enqueues CSS styles for a specific page in wp-admin.
     *
     * @param string $page The page name to enqueue styles for.
     *
     * @return void
     * @since 1.0.0
     *
     */
    public function enqueue_styles($page)
    {
        if ($page == 'toplevel_page_chaty-app' || $page == 'chaty_page_chaty-contact-form-feed' || $page == "chaty_page_chaty-upgrade") {
            $queryArgs = [
                'family' => 'Rubik:400,700|Oswald:400,600',
                'subset' => 'latin,latin-ext',
            ];
            wp_enqueue_style('google_fonts', add_query_arg($queryArgs, "//fonts.googleapis.com/css"), [], CHT_VERSION);
            wp_enqueue_style($this->pluginSlug.'spectrum', plugins_url('../admin/assets/css/spectrum.min.css', __FILE__), [], CHT_VERSION);
            wp_enqueue_style($this->pluginSlug.'intlTelInput', plugins_url('../admin/assets/css/intlTelInput.min.css', __FILE__), [], CHT_VERSION);
            wp_enqueue_style($this->pluginSlug. 'sumoselect', plugins_url('../admin/assets/css/sumoselect.css', __FILE__), [], CHT_VERSION);
            // WP change this
            if ($page == 'chaty_page_chaty-contact-form-feed') {
                wp_enqueue_style('jquery-ui-css', plugins_url('../admin/assets/css/datepicker.min.css', __FILE__), [], CHT_VERSION);
            }

            wp_enqueue_style($this->pluginSlug, plugins_url('../admin/assets/css/cht-style.min.css', __FILE__), [], CHT_VERSION);
            wp_enqueue_style($this->pluginSlug."-tailwind", plugins_url('../admin/assets/css/app.css', __FILE__), [], CHT_VERSION);
            wp_enqueue_style($this->pluginSlug."-preview", plugins_url('../admin/assets/css/preview.css', __FILE__), [], CHT_VERSION);
        }

        if ($page == "chaty_page_chaty-upgrade" || $page == "chaty_page_widget-analytics") {
            $queryArgs = [
                'family' => 'Poppins:400,700',
                'subset' => 'latin,latin-ext',
            ];
            wp_enqueue_style('google-chaty-fonts', add_query_arg($queryArgs, "//fonts.googleapis.com/css"), [], CHT_VERSION);
        }

        if($page == "chaty_page_chaty-app-upgrade") {
            wp_enqueue_style($this->pluginSlug, plugins_url('../admin/assets/css/pricing-table.css', __FILE__), [], CHT_VERSION);
            $queryArgs = [
                'family' => 'Poppins:wght@400;500;600;700&display=swap',
                'subset' => 'latin,latin-ext',
            ];
            wp_enqueue_style('google-poppins-fonts', add_query_arg($queryArgs, "//fonts.googleapis.com/css2"), [], CHT_VERSION);
        }

    }//end enqueue_styles()


    /**
     * Enqueues pricing styles for the plugin.
     *
     * @return void
     */
    public function enqueue_pricing_styles()
    {
        wp_enqueue_style($this->pluginSlug."-select2", plugins_url('../admin/assets/css/select2.min.css', __FILE__), [], CHT_VERSION);

        $queryArgs = [
            'family' => 'Lato:100,300,400,500,700',
            'subset' => 'latin,latin-ext',
        ];
        wp_enqueue_style('google-lato-fonts', add_query_arg($queryArgs, "//fonts.googleapis.com/css"), [], CHT_VERSION);

    }//end enqueue_pricing_styles()


    /**
     * Enqueue JS to wp-admin
     *
     * @since  1.0.0
     * @access public
     * @return $script
     */
    public function enqueue_scripts($page)
    {
        if ($page == 'chaty_page_widget-analytics' || $page == "chaty_page_chaty-contact-form-feed") {
            wp_enqueue_script('jquery-ui-datepicker');
            return;
        }

        if($page == "chaty_page_chaty-app-upgrade") {
            wp_enqueue_script($this->pluginSlug.'slick-script', plugins_url('../admin/assets/js/slick.min.js', __FILE__), ['jquery'], CHT_VERSION, true);
        }

        // delete_option("chaty_update_message");
        $isShown = get_option("chaty_update_message");
        if ($isShown === false) {
            wp_enqueue_script($this->pluginSlug.'mailcheck', plugins_url('../admin/assets/js/mailcheck.js', __FILE__), ['jquery'], CHT_VERSION, true);
            return;
        }

        if ($page != "toplevel_page_chaty-app") {
            return;
        }

        wp_enqueue_script($this->pluginSlug.'spectrum', plugins_url('../admin/assets/js/spectrum.min.js', __FILE__), ['jquery'], CHT_VERSION, true);
        wp_enqueue_script($this->pluginSlug.'sumoselect', plugins_url('../admin/assets/js/sumoselect.js', __FILE__), ['jquery'], CHT_VERSION, true);
        wp_enqueue_script($this->pluginSlug.'intlTelInput', plugins_url('../admin/assets/js/intlTelInput.min.js', __FILE__), ['jquery'], CHT_VERSION, true);

        // WP change this
        wp_enqueue_editor();
        wp_enqueue_media();
        wp_enqueue_script($this->pluginSlug.'chaty', plugins_url('../admin/assets/js/cht-scripts.min.js', __FILE__), ['jquery', 'wp-color-picker', 'jquery-ui-draggable', 'jquery-ui-droppable', 'jquery-ui-sortable', 'wp-hooks'], CHT_VERSION, true);
        wp_enqueue_script($this->pluginSlug.'preview', plugins_url('../admin/assets/js/preview.min.js', __FILE__), ['jquery'], CHT_VERSION, true);
        wp_enqueue_script($this->pluginSlug.'acolorpicker', plugins_url('../admin/assets/js/acolorpicker.js', __FILE__), ['jquery'], CHT_VERSION, true);
        wp_enqueue_script($this->pluginSlug.'widget-script', plugins_url('../admin/assets/js/app.js', __FILE__), ['jquery', 'wp-hooks'], CHT_VERSION, true);

        wp_enqueue_script($this->pluginSlug. 'picmo-js', plugins_url('../admin/assets/js/picmo-umd.min.js', __FILE__), array('jquery'), CHT_VERSION, true);
        wp_enqueue_script($this->pluginSlug. 'picmo-latest-js', plugins_url('../admin/assets/js/picmo-latest-umd.min.js', __FILE__), array('jquery'), CHT_VERSION, true);


        wp_localize_script(
            $this->pluginSlug.'chaty',
            'cht_nonce_ajax',
            [
                'cht_nonce' => wp_create_nonce('cht_nonce_ajax'),
                'has_js_access' => current_user_can("unfiltered_html")?true:false,
                'js_message' => esc_html__("Please remove the JavaScript from the channels or ask the website's administrator to give you access to add JavaScript.", "chaty"),
                'remove' => esc_html__("Remove", "chaty"),
                'icon_img' => esc_url(CHT_PLUGIN_URL."admin/assets/images/icon-picker.png")
            ]
        );
        $whatsapp_settings = [];
        foreach ($this->socials as $social) {
            $whatsapp_settings[$social['slug']] = "";
        }

        wp_localize_script(
            $this->pluginSlug.'chaty',
            'cht_settings',
            [
                'plugin_url'       => CHT_PLUGIN_URL,
                'channel_settings' => $whatsapp_settings,
                'user_country' => $this->get_user_country()
            ]
        );

    }//end enqueue_scripts()

    /**
     * Retrieves the user's country based on their IP address.
     *
     * @return string The user's country code, or "-" if not available.
     */
    public function get_user_country() {
        $user_country = get_transient("chaty_user_country");
        if($user_country === false ){
            $url = "https://www.cloudflare.com/cdn-cgi/trace";
            $data = wp_remote_get($url);
            $user_country = "-";
            if(!empty($data) && is_array($data) && isset($data['body'])) {
                $data_code = explode('loc=', $data['body']);
                $data_code = explode('tls=', $data_code[1]);
                $user_country = isset($data_code[0])?trim($data_code[0]):"-";
            }
            set_transient("chaty_user_country", $user_country, 7*DAY_IN_SECONDS);
        }
        $user_country = !empty($user_country)?strtolower($user_country):"-";
        return $user_country;
    }

    /**
     * Displays the Chaty admin setting page.
     *
     * @return void
     */
    public function cht_admin_setting_page()
    {
        if (current_user_can('manage_options')) {
            $this->page = add_menu_page(
                esc_attr__('Chaty', 'chaty'),
                esc_attr__('Chaty', 'chaty'),
                'manage_options',
                $this->pluginSlug,
                [
                    $this,
                    'display_cht_admin_page',
                ],
                plugins_url('chaty/admin/assets/images/chaty.svg')
            );

            add_submenu_page(
                $this->pluginSlug,
                esc_attr__('Dashboard', 'chaty'),
                esc_attr__('Dashboard', 'chaty'),
                'manage_options',
                $this->pluginSlug,
                [
                    $this,
                    'display_cht_admin_page',
                ]
            );

            $widget_page = add_submenu_page(
                $this->pluginSlug,
                esc_attr__('Settings Admin', 'chaty'),
                esc_attr__('+ Create New Widget', 'chaty'),
                'manage_options',
                "chaty-upgrade",
                [
                    $this,
                    "chaty_widget_page",
                ]
            );

            $chatwayStatus = apply_filters('check_for_chatway', false);
            add_submenu_page(
                $this->pluginSlug,
                esc_attr__('Chatway Live Chat', 'chaty'),
                esc_attr__('Chatway Live Chat', 'chaty'),
                'manage_options',
                $chatwayStatus?'cht-manage-live-chat':'chaty-live-chat',
                array($this, 'manage_chatway')
            );

            $integrationPage = add_submenu_page(
                $this->pluginSlug,
                esc_attr__('Settings Admin', 'chaty'),
                esc_attr__('Integrations', 'chaty'),
                'manage_options',
                "chaty-integration",
                [
                    $this,
                    "chaty_integration_page",
                ]
            );

            // creating admin sub menu for chaty
            $upgradePage = add_submenu_page(
                $this->pluginSlug,
                esc_attr__('Widget Analytics', 'chaty'),
                esc_attr__('Widget Analytics', 'chaty'),
                'manage_options',
                'widget-analytics',
                [
                    $this,
                    'display_cht_admin_widget_analytics',
                ]
            );
            add_action('admin_print_styles-'.$upgradePage, [$this, 'enqueue_styles']);

            // creating admin sub menu for chaty
            $feed_page = add_submenu_page(
                $this->pluginSlug,
                esc_attr__('Contact form leads', 'chaty'),
                esc_attr__('Contact form leads', 'chaty'),
                'manage_options',
                "chaty-contact-form-feed",
                [
                    $this,
                    'chaty_contact_form_feed',
                ]
            );
            add_action('admin_print_styles-'.$feed_page, [$this, 'enqueue_styles']);
            // creating admin sub menu for chaty
            $getData = filter_input_array(INPUT_GET);
            if (isset($getData['hide_chaty_recommended_plugin']) && isset($getData['nonce'])) {
                if (current_user_can('manage_options')) {
                    $nonce = $getData['nonce'];
                    if (wp_verify_nonce($nonce, "chaty_recommended_plugin")) {
                        update_option('hide_chaty_recommended_plugin', true);
                    }
                }
            }

            $recommendedPlugin = get_option("hide_chaty_recommended_plugin");
            if ($recommendedPlugin === false) {
                add_submenu_page(
                    $this->pluginSlug,
                    esc_html__('Recommended Plugins', 'chaty'),
                    esc_html__('Recommended Plugins', 'chaty'),
                    'manage_options',
                    'recommended-chaty-plugins',
                    [
                        $this,
                        'recommended_plugins',
                    ]
                );
            }

            $upgradePage = add_submenu_page(
                $this->pluginSlug,
                esc_html__('Upgrade to Pro', 'chaty'),
                esc_html__('Upgrade to Pro', 'chaty'),
                'manage_options',
                $this->upgradeSlug,
                [
                    $this,
                    'display_cht_admin_upgrade_page',
                ]
            );
            add_action('admin_print_styles-'.$upgradePage, [$this, 'enqueue_pricing_styles']);
        }//end if

        // Load public-facing style sheet and JavaScript.
        add_action('admin_print_styles-'.$this->page, [$this, 'enqueue_styles']);

    }//end cht_admin_setting_page()

    /**
     * Manages the Chatway settings and views.
     *
     * @return void
     */
    public function manage_chatway() {
        $chatwayStatus = apply_filters('check_for_chatway', false);
        if(!$chatwayStatus) {
            $onlyChatway = true;
            include_once CHT_DIR . '/views/admin/install-chatway.php';
        } else {
            echo '<script>window.location="'.esc_url('admin.php?page=chatway').'"</script>';
            exit;
        }
    }

    /**
     * Includes the necessary files for the contact form feed functionality.
     *
     * @return void
     */
    public function chaty_contact_form_feed()
    {
        include_once CHT_DIR.'/views/admin/contact-form-feed.php';
        include_once CHT_DIR.'/views/admin/first-popup.php';
        include_once CHT_DIR.'/views/admin/help.php';

    }//end chaty_contact_form_feed()


    /**
     * Includes the recommended plugins view.
     *
     * @return void
     */
    public function recommended_plugins()
    {
        include_once CHT_DIR.'/views/admin/recommended-plugins.php';

    }//end recommended_plugins()


    /**
     * Display integration setting
     *
     * @since  1.0.0
     * @access public
     */
    public function chaty_integration_page() {
        include_once CHT_DIR.'/views/admin/chaty-admin-integration.php';
        include_once CHT_DIR.'/views/admin/help.php';
    }// end chaty_integration_page()


    /**
     * Display widget analytics
     *
     * @since  1.0.0
     * @access public
     * @return $analytics
     */
    public function display_cht_admin_widget_analytics()
    {
        include_once CHT_DIR.'/views/admin/pro_analytics.php';
        include_once CHT_DIR.'/views/admin/first-popup.php';
        include_once CHT_DIR.'/views/admin/help.php';

    }//end display_cht_admin_widget_analytics()


    /**
     * Returns upgrade URL
     *
     * @since  1.0.0
     * @access public
     * @return $url
     */
    public function getUpgradeMenuItemUrl()
    {
        return CHT_PRO_URL;

    }//end getUpgradeMenuItemUrl()

    public function getDashboardUrl()
    {
        return admin_url("admin.php?page=chaty-app");
    }


    /**
     * Chaty Upgrade page
     *
     * @since  1.0.0
     * @access public
     * @return $url
     */
    public function chaty_widget_page()
    {
        include_once CHT_DIR.'/views/admin/chaty_widget.php';
        include_once CHT_DIR.'/views/admin/first-popup.php';
        include_once CHT_DIR.'/views/admin/help.php';

    }//end chaty_widget_page()


    /**
     * Chaty Dashboard page
     *
     * @since  1.0.0
     * @access public
     * @return $dashboard_data
     */
    public function display_cht_admin_page()
    {
        $isShown = get_option("chaty_update_message");
        if ($isShown === false) {
            include_once CHT_DIR.'/views/admin/update.php';
        } else {
            $status = get_option("cht_active");
            // delete_option("cht_is_default_deleted");
            if (isset($_GET['widget'])) {
                $step = filter_input(INPUT_GET, 'step');
                $step = ($step !== false && is_numeric($step) && $step > 0)?$step:0;
                $channel_class = "";
                $fonts         = self::get_font_list();
                if (!in_array($step, [0, 1, 2, 3])) {
                    $step = 0;
                }
                $hasWooCommerce = 0;
                if(is_plugin_active("woocommerce/woocommerce.php")) {
                    $hasWooCommerce = 1;
                }
                include_once CHT_DIR.'/views/admin/admin.php';
            } else {
                include_once CHT_DIR.'/views/admin/dashboard.php';
            }

            $popupStatus = get_option("chaty_intro_popup");
            if ($popupStatus == "show") {
                include_once CHT_DIR.'/views/admin/chaty-popup.php';
            }

            include_once CHT_DIR.'/views/admin/first-popup.php';
        }//end if

        $showMessage = filter_input(INPUT_GET, 'show_message');
        if ($showMessage == 1) {
            if (isset($_GET['widget'])) { ?>
                <div class="toast-message bottom-pos">
                    <div class="toast-close-btn"><a href="javascript:;"></a></div>
                    <div class="toast-message-body">Your settings has been saved. <a href="<?php echo esc_url(admin_url("admin.php?page=chaty-app")) ?>">View Dashboard</a></div>
                </div>
            <?php } else { ?>
                <div class="toast-message">
                    <div class="toast-close-btn"><a href="javascript:;"></a></div>
                    <div class="toast-message-title">Settings Updated</div>
                    <div class="toast-message-body">Your settings has been saved</div>
                </div>
            <?php }
        }

        require_once CHT_DIR.'/views/admin/help.php';

    }//end display_cht_admin_page()

    /**
     * Chaty Upgrade page
     *
     * @since  1.0.0
     * @access public
     * @return $upgrade_data
     */
    public function display_cht_admin_upgrade_page()
    {
        wp_enqueue_script($this->pluginSlug.'select2-js', plugins_url('../admin/assets/js/select2.min.js', __FILE__), ['jquery'], CHT_VERSION, true);
        include_once CHT_DIR.'/views/admin/upgrade.php';
        include_once CHT_DIR.'/views/admin/help.php';

    }//end display_cht_admin_upgrade_page()


    /**
     * Returns EDD token
     *
     * @since  1.0.0
     * @access public
     * @return $token
     */
    protected function get_token()
    {
        return get_option('cht_license_key');

    }//end get_token()


    /**
     * Returns Website URL
     *
     * @since  1.0.0
     * @access public
     * @return $token
     */
    public function get_site()
    {
        $permalink = get_home_url();
        return $permalink;

    }//end get_site()

    /**
     * Gets the current color for the plugin.
     *
     * @return string The current color in uppercase.
     */
    public function get_current_color()
    {
        $defColor    = get_option('cht_color');
        $customColor = get_option('cht_custom_color');
        if (!$defColor) {
            $color = $customColor;
        } else {
            $color = $defColor;
        }

        $color = strtoupper($color);
        return $color;

    }//end get_current_color()


    /**
     * Retrieves the position style for the plugin.
     *
     * @return string The CSS style string for the position.
     */
    public function get_position_style()
    {
        $position = get_option('cht_position');

        if ($position === 'custom') {
            $posSide = get_option('positionSide');
            $bot     = (get_option('cht_bottom_spacing')) ? get_option('cht_bottom_spacing') : '25';
            $side    = (get_option('cht_side_spacing')) ? get_option('cht_side_spacing') : '25';
            if ($posSide === 'right') {
                $posStyle = 'left: auto; bottom: '.$bot.'px; right: '.$side.'px';
            } else {
                $posStyle = 'left: '.$side.'px; bottom: '.$bot.'px; right: auto';
            }
        } else if ($position === 'right') {
            $posStyle = 'left: auto; bottom: 25px; right: 25px';
        } else {
            $posStyle = 'left: 25px; bottom: 25px; right: auto';
        }

        return $posStyle;

    }//end get_position_style()


    public function change_menu_text()
    {
        global $submenu;
        if(isset($submenu['chaty-app'])) {
            $totalItems = count($submenu['chaty-app'])-1;
            if(isset($submenu['chaty-app'][$totalItems][0])) {
                $submenu['chaty-app'][$totalItems][0] = '<span><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M13.0518 4.01946C12.9266 3.91499 12.7747 3.84781 12.6132 3.82557C12.4517 3.80333 12.2872 3.82693 12.1385 3.89367L9.3713 5.12414L7.76349 2.22571C7.68664 2.09039 7.5753 1.97785 7.44081 1.89956C7.30632 1.82127 7.15348 1.78003 6.99786 1.78003C6.84224 1.78003 6.6894 1.82127 6.55491 1.89956C6.42042 1.97785 6.30908 2.09039 6.23224 2.22571L4.62442 5.12414L1.85724 3.89367C1.70822 3.82703 1.54352 3.8034 1.38178 3.82545C1.22003 3.84751 1.06768 3.91437 0.941941 4.01849C0.816207 4.1226 0.722106 4.25982 0.670275 4.41461C0.618444 4.56941 0.610951 4.73562 0.648642 4.89446L2.0377 10.8171C2.06427 10.9318 2.11383 11.0399 2.18339 11.1348C2.25295 11.2297 2.34107 11.3096 2.44239 11.3695C2.57957 11.4516 2.73642 11.495 2.8963 11.4952C2.97402 11.4951 3.05133 11.484 3.12599 11.4624C5.65792 10.7624 8.33233 10.7624 10.8643 11.4624C11.0955 11.5232 11.3413 11.4898 11.5479 11.3695C11.6498 11.3103 11.7384 11.2307 11.8081 11.1357C11.8777 11.0406 11.9269 10.9321 11.9525 10.8171L13.3471 4.89446C13.3843 4.73558 13.3764 4.56945 13.3243 4.41482C13.2721 4.2602 13.1777 4.12326 13.0518 4.01946V4.01946Z" fill="white"/>
</svg></span> '.esc_html__( 'Upgrade to Pro' , 'chaty');
            }
        }
    }

    /**
     * Registers inputs for the plugin.
     *
     * @return void
     */
    public function cht_register_inputs()
    {
        $page = filter_input(INPUT_GET, 'page');
        if ($page == "chaty-upgrade") {
            $chtActive = get_option('cht_active');
            if ($chtActive === false) {
                wp_redirect(admin_url("admin.php?page=chaty-app&widget=0"));
                exit;
            }
        }

        if (current_user_can("manage_options")) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'chaty_contact_form_leads';
            $postData = filter_input_array(INPUT_GET);
            if(isset($postData['remove_chaty_leads'])) {
                if(wp_verify_nonce($postData['remove_chaty_leads'], "remove_chaty_leads")) {
                    if(isset($postData['chaty_leads']) && !empty($postData['chaty_leads'])) {
                        if(isset($postData['action']) && $postData['action'] == "delete_message") {
                            $chatyLeads = esc_attr($postData['chaty_leads']);
                            if($chatyLeads == "remove-all") {
                                $wpdb->query("TRUNCATE TABLE {$table_name}");
                            } else {
                                $chatyLeads = explode(",", $chatyLeads);
                                if ($chatyLeads) {
                                    foreach ($chatyLeads as $chatyLead) {
                                        $wpdb->delete($table_name, ["id" => esc_sql($chatyLead)]);
                                    }
                                }
                            }

                            $paged = isset($postData['paged']) && !empty($postData['paged']) && is_numeric($postData['paged']) && $postData['paged'] > 0 ? $postData['paged'] : 1;
                            $search = isset($postData['search']) && !empty($postData['search']) ? $postData['search'] : "";
                            $url = admin_url("admin.php?page=chaty-contact-form-feed");
                            if (intval($paged) > 1) {
                                $url .= "&paged=" . $paged;
                            }
                            if (!empty($search)) {
                                $url .= "&search=" . $search;
                            }
                            wp_redirect($url);
                            exit;
                        }
                    }
                }
            }

            $postData = filter_input_array(INPUT_GET);
            if (isset($postData['download_chaty_file']) && $postData['download_chaty_file'] == "chaty_contact_leads" && isset($postData['nonce'])) {
                if (wp_verify_nonce($postData['nonce'], "download_chaty_contact_leads")) {
                    $uploadDir = wp_upload_dir();
                    $file      = $uploadDir['basedir']."/chaty_contact_leads.csv";
                    $fp        = fopen($file, "w") or die("Error Couldn't open for writing!");

                    global $wpdb;
                    $contactListsTable = $wpdb->prefix.'chaty_contact_form_leads';
                    $results           = $wpdb->get_results("SELECT * FROM ".$contactListsTable." ORDER BY ID DESC");

                    if(!empty($results)) {
                        $fields = array(
                            esc_html__("id", "chaty"),
                            esc_html__("Widget", "chaty"),
                            esc_html__("Name", "chaty"),
                            esc_html__("Email", "chaty"),
                            esc_html__("Phone number", "chaty"),
                            esc_html__("Message", "chaty"),
                            esc_html__("Date", "chaty"),
                            esc_html__("Reference Page", "chaty")
                        );

                        fputcsv($fp, $fields);
                    }

                    foreach ($results as $res) {
                        if ($res->widget_id == 0) {
                            $widgetName = "Default";
                        } else {
                            $widgetName = get_option("cht_widget_title_".$res->widget_id);
                            if (empty($widgetName)) {
                                $widgetName = "Widget #".($res->widget_id + 1);
                            }
                        }

                        $fields = [
                            $res->id,
                            $widgetName,
                            $res->name,
                            $res->email,
                            $res->phone_number,
                            nl2br($res->message),
                            $res->created_on,
                            $res->ref_page,
                        ];

                        fputcsv($fp, $fields);
                    }//end foreach

                    fclose($fp);

                    $file_content = wp_remote_get($file);
                    header("Content-Disposition: attachment; filename=".basename($file));
                    header("Content-Length: ".filesize($file));
                    header("Content-Type: application/octet-stream;");
                    readfile($file);
                    exit;
                }//end if
            }//end if

            /*
             * Adding settings fields
             */
            // Section One
            foreach ($this->socials as $social) {
                add_settings_field(
                    'cht_social_'.$social['slug'],
                    ucfirst($social['slug']),
                    '',
                    $this->pluginSlug
                );
            }

            // Section Two
            add_settings_field('cht_devices', 'Devices', '', $this->pluginSlug);
            add_settings_field('cht_color', 'Color', '', $this->pluginSlug);
            add_settings_field('cht_custom_color', 'Color', '', $this->pluginSlug);
            add_settings_field('cht_position', 'Position', '', $this->pluginSlug);
            add_settings_field('cht_widget_font', 'Position', '', $this->pluginSlug);
            add_settings_field('positionSide', 'PositionSide', '', $this->pluginSlug);
            add_settings_field('cht_bottom_spacing', 'Bottom spacing', '', $this->pluginSlug);
            add_settings_field('cht_side_spacing', 'Side spacing', '', $this->pluginSlug);
            add_settings_field('cht_cta', 'CTA', '', $this->pluginSlug);
            add_settings_field('cht_cta_switcher', 'CTA switcher', '', $this->pluginSlug);
            add_settings_field('chaty_attention_effect', 'CTA effect', '', $this->pluginSlug);
            add_settings_field('chaty_default_state', 'CTA state', '', $this->pluginSlug);
            add_settings_field('chaty_trigger_on_time', 'CTA trigger on time', '', $this->pluginSlug);
            add_settings_field('chaty_trigger_time', 'CTA time to trigger', '', $this->pluginSlug);
            // add_settings_field('chaty_trigger_hide', 'CTA hide on time', '', $this->pluginSlug);
            // add_settings_field('chaty_trigger_hide_time', 'CTA hide time', '', $this->pluginSlug);
            add_settings_field('chaty_trigger_on_exit', 'CTA exit intent', '', $this->pluginSlug);
            add_settings_field('chaty_trigger_on_scroll', 'CTA trigger on scroll', '', $this->pluginSlug);
            add_settings_field('chaty_trigger_on_page_scroll', 'CTA page scroll', '', $this->pluginSlug);
            add_settings_field('cht_close_button', 'CTA close button', '', $this->pluginSlug);
            add_settings_field('cht_close_button_text', 'CTA close button text', '', $this->pluginSlug);
            add_settings_field('chaty_icons_view', 'Icon view', '', $this->pluginSlug);
            add_settings_field('cht_created_on', 'Created time', '', $this->pluginSlug);
            add_settings_field('cht_widget_title', 'Chaty title', '', $this->pluginSlug);

            // section three
            add_settings_field('cht_active', 'Active', '', $this->pluginSlug);
            add_settings_field('cht_cta_action', 'CTA Action', '', $this->pluginSlug);
            add_settings_field('cht_cta_text_color', 'CTA Text Color', '', $this->pluginSlug);
            add_settings_field('cht_cta_bg_color', 'CTA BG Color', '', $this->pluginSlug);
            add_settings_field('cht_pending_messages', 'CTA Pending message', '', $this->pluginSlug);
            add_settings_field('cht_number_of_messages', 'CTA Number of Messages', '', $this->pluginSlug);
            add_settings_field('cht_number_color', 'CTA Color', '', $this->pluginSlug);
            add_settings_field('cht_number_bg_color', 'CTA BG Color', '', $this->pluginSlug);

            // token
            add_settings_field('cht_license_key', 'Token', '', $this->pluginSlug);

            // slug
            add_settings_field('cht_numb_slug', 'Numb', '', $this->pluginSlug);

            add_settings_field('chaty_updated_on', 'Updated time', '', $this->pluginSlug);

            /*
             * Registering settings fields
             */

            $nonce = filter_input(INPUT_POST, 'nonce');

            // check for nonce
            if (!empty($nonce) && wp_verify_nonce($nonce, "chaty_plugin_nonce")) {


                // register field section one
                foreach ($this->socials as $social) {

                    /* Checking for JavaScript */
                    if(in_array($social['slug'], ['Link', 'Custom_Link', 'Custom_Link_3', 'Custom_Link_4', 'Custom_Link_5'])) {
                        register_setting($this->pluginSlug, 'cht_social_'.$social['slug'], [
                            'sanitize_callback' => function($val){
                                if( is_super_admin() ) {
                                    return $val;
                                } else {
                                    $val['value'] = esc_url($val['value']);
                                    return $val;
                                }
                            }
                        ]);
                    } else {
                        register_setting($this->pluginSlug, 'cht_social_'.$social['slug']);
                    }
                }

                // register field section two
                register_setting($this->pluginSlug, 'cht_devices', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_color', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_custom_color', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_position', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_widget_font', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'positionSide', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_bottom_spacing', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_side_spacing', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_cta', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_cta_switcher', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_widget_size', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_widget_img', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'widget_icon', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_attention_effect', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_default_state', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_trigger_on_time', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_trigger_time', 'chaty_sanitize_options');
                // register_setting($this->pluginSlug, 'chaty_trigger_hide_time', 'chaty_sanitize_options');
                // register_setting($this->pluginSlug, 'chaty_trigger_hide', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_trigger_on_exit', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_trigger_on_scroll', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_trigger_on_page_scroll', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_close_button', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_close_button_text', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'chaty_icons_view', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_created_on', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_widget_title', 'chaty_sanitize_options');

                // register field section three
                register_setting($this->pluginSlug, 'cht_active', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_cta_action', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_cta_text_color', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_cta_bg_color', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_pending_messages', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_number_of_messages', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_number_color', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cht_number_bg_color', 'chaty_sanitize_options');


                register_setting($this->pluginSlug, 'cta_type', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cta_heading_text', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cta_body_text', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cta_header_text_color', 'chaty_sanitize_options');
                register_setting($this->pluginSlug, 'cta_header_bg_color', 'chaty_sanitize_options');

                // register field section token
                register_setting($this->pluginSlug, 'cht_license_key', 'chaty_sanitize_options');

                // register field section slug
                register_setting($this->pluginSlug, 'cht_numb_slug', 'chaty_sanitize_options');

                register_setting($this->pluginSlug, 'chaty_updated_on', 'chaty_sanitize_options');

                cht_clear_all_caches();
            }//end if
        }//end if

    }//end cht_register_inputs()


    /**
     * Checking for devices desktop/mobile
     *
     * @since  1.0.0
     * @access public
     * @return $devices
     */
    public function device()
    {
        return 'desktop_active mobile_active';

    }//end device()


    /**
     * Return custom widget URL if uploaded
     *
     * @since  1.0.0
     * @access public
     * @return $url
     */
    public function getCustomWidgetImg()
    {
        $value = get_option('cht_widget_img');
        return isset($value['url']) ? $value['url'] : '';

    }//end getCustomWidgetImg()


    /**
     * Uploads custom widget image
     *
     * @since  1.0.0
     * @access public
     * @return $url
     */
    public function uploadCustomWidget($value, $old_value, $option)
    {
        $option      = !empty($option) ? $option : 'cht_widget_img';
        $allowed_ext = [
            'jpeg',
            'png',
            'jpg',
            'svg',
        ];
        if (!function_exists('wp_handle_upload')) {
            include_once ABSPATH.'wp-admin/includes/file.php';
        }

        if (isset($_FILES[$option])) {
            $file = &$_FILES[$option];
            $type = wp_check_filetype($file['name']);

            if (!in_array($type['ext'], $allowed_ext)) {
                return $old_value;
            }

            if (isset($old_value['file'])) {
                wp_delete_file($old_value['file']);
            }

            $overrides = ['test_form' => false];
            $movefile  = wp_handle_upload($file, $overrides);
            if ($movefile && empty($movefile['error'])) {
                return [
                    'file' => $movefile['file'],
                    'url'  => $movefile['url'],
                ];
            }
        }//end if

        return [];

    }//end uploadCustomWidget()

    /**
     * Returns CTA text
     *
     * @since  1.0.0
     * @access public
     * @return $cta_text
     */
    public function getCallToAction()
    {
        if (get_option('cht_cta')) {
            $res = nl2br(get_option('cht_cta'));
            $res = str_replace(["\n", "\r"], "", $res);
            return $res;
        }

        return '';

    }//end getCallToAction()

    /**
     * Returns a list of fonts.
     *
     * @return array The list of fonts.
     */
    public static function get_font_list()
    {
        return [
            // System fonts.
            "System Stack"                   => 'Default',
            'Arial'                          => 'Default',
            'Tahoma'                         => 'Default',
            'Verdana'                        => 'Default',
            'Helvetica'                      => 'Default',
            'Times New Roman'                => 'Default',
            'Trebuchet MS'                   => 'Default',
            'Georgia'                        => 'Default',

            // Google Fonts (last update: 23/10/2018).
            'ABeeZee'                        => 'Google Fonts',
            'Abel'                           => 'Google Fonts',
            'Abhaya Libre'                   => 'Google Fonts',
            'Abril Fatface'                  => 'Google Fonts',
            'Aclonica'                       => 'Google Fonts',
            'Acme'                           => 'Google Fonts',
            'Actor'                          => 'Google Fonts',
            'Adamina'                        => 'Google Fonts',
            'Advent Pro'                     => 'Google Fonts',
            'Aguafina Script'                => 'Google Fonts',
            'Akronim'                        => 'Google Fonts',
            'Aladin'                         => 'Google Fonts',
            'Aldrich'                        => 'Google Fonts',
            'Alef'                           => 'Google Fonts',
            'Alef Hebrew'                    => 'Google Fonts',
            // Hack for Google Early Access.
            'Alegreya'                       => 'Google Fonts',
            'Alegreya SC'                    => 'Google Fonts',
            'Alegreya Sans'                  => 'Google Fonts',
            'Alegreya Sans SC'               => 'Google Fonts',
            'Alex Brush'                     => 'Google Fonts',
            'Alfa Slab One'                  => 'Google Fonts',
            'Alice'                          => 'Google Fonts',
            'Alike'                          => 'Google Fonts',
            'Alike Angular'                  => 'Google Fonts',
            'Allan'                          => 'Google Fonts',
            'Allerta'                        => 'Google Fonts',
            'Allerta Stencil'                => 'Google Fonts',
            'Allura'                         => 'Google Fonts',
            'Almendra'                       => 'Google Fonts',
            'Almendra Display'               => 'Google Fonts',
            'Almendra SC'                    => 'Google Fonts',
            'Amarante'                       => 'Google Fonts',
            'Amaranth'                       => 'Google Fonts',
            'Amatic SC'                      => 'Google Fonts',
            'Amethysta'                      => 'Google Fonts',
            'Amiko'                          => 'Google Fonts',
            'Amiri'                          => 'Google Fonts',
            'Amita'                          => 'Google Fonts',
            'Anaheim'                        => 'Google Fonts',
            'Andada'                         => 'Google Fonts',
            'Andika'                         => 'Google Fonts',
            'Angkor'                         => 'Google Fonts',
            'Annie Use Your Telescope'       => 'Google Fonts',
            'Anonymous Pro'                  => 'Google Fonts',
            'Antic'                          => 'Google Fonts',
            'Antic Didone'                   => 'Google Fonts',
            'Antic Slab'                     => 'Google Fonts',
            'Anton'                          => 'Google Fonts',
            'Arapey'                         => 'Google Fonts',
            'Arbutus'                        => 'Google Fonts',
            'Arbutus Slab'                   => 'Google Fonts',
            'Architects Daughter'            => 'Google Fonts',
            'Archivo'                        => 'Google Fonts',
            'Archivo Black'                  => 'Google Fonts',
            'Archivo Narrow'                 => 'Google Fonts',
            'Aref Ruqaa'                     => 'Google Fonts',
            'Arima Madurai'                  => 'Google Fonts',
            'Arimo'                          => 'Google Fonts',
            'Arizonia'                       => 'Google Fonts',
            'Armata'                         => 'Google Fonts',
            'Arsenal'                        => 'Google Fonts',
            'Artifika'                       => 'Google Fonts',
            'Arvo'                           => 'Google Fonts',
            'Arya'                           => 'Google Fonts',
            'Asap'                           => 'Google Fonts',
            'Asap Condensed'                 => 'Google Fonts',
            'Asar'                           => 'Google Fonts',
            'Asset'                          => 'Google Fonts',
            'Assistant'                      => 'Google Fonts',
            'Astloch'                        => 'Google Fonts',
            'Asul'                           => 'Google Fonts',
            'Athiti'                         => 'Google Fonts',
            'Atma'                           => 'Google Fonts',
            'Atomic Age'                     => 'Google Fonts',
            'Aubrey'                         => 'Google Fonts',
            'Audiowide'                      => 'Google Fonts',
            'Autour One'                     => 'Google Fonts',
            'Average'                        => 'Google Fonts',
            'Average Sans'                   => 'Google Fonts',
            'Averia Gruesa Libre'            => 'Google Fonts',
            'Averia Libre'                   => 'Google Fonts',
            'Averia Sans Libre'              => 'Google Fonts',
            'Averia Serif Libre'             => 'Google Fonts',
            'Bad Script'                     => 'Google Fonts',
            'Bahiana'                        => 'Google Fonts',
            'Bai Jamjuree'                   => 'Google Fonts',
            'Baloo'                          => 'Google Fonts',
            'Baloo Bhai'                     => 'Google Fonts',
            'Baloo Bhaijaan'                 => 'Google Fonts',
            'Baloo Bhaina'                   => 'Google Fonts',
            'Baloo Chettan'                  => 'Google Fonts',
            'Baloo Da'                       => 'Google Fonts',
            'Baloo Paaji'                    => 'Google Fonts',
            'Baloo Tamma'                    => 'Google Fonts',
            'Baloo Tammudu'                  => 'Google Fonts',
            'Baloo Thambi'                   => 'Google Fonts',
            'Balthazar'                      => 'Google Fonts',
            'Bangers'                        => 'Google Fonts',
            'Barlow'                         => 'Google Fonts',
            'Barlow Condensed'               => 'Google Fonts',
            'Barlow Semi Condensed'          => 'Google Fonts',
            'Barrio'                         => 'Google Fonts',
            'Basic'                          => 'Google Fonts',
            'Battambang'                     => 'Google Fonts',
            'Baumans'                        => 'Google Fonts',
            'Bayon'                          => 'Google Fonts',
            'Belgrano'                       => 'Google Fonts',
            'Bellefair'                      => 'Google Fonts',
            'Belleza'                        => 'Google Fonts',
            'BenchNine'                      => 'Google Fonts',
            'Bentham'                        => 'Google Fonts',
            'Berkshire Swash'                => 'Google Fonts',
            'Bevan'                          => 'Google Fonts',
            'Bigelow Rules'                  => 'Google Fonts',
            'Bigshot One'                    => 'Google Fonts',
            'Bilbo'                          => 'Google Fonts',
            'Bilbo Swash Caps'               => 'Google Fonts',
            'BioRhyme'                       => 'Google Fonts',
            'BioRhyme Expanded'              => 'Google Fonts',
            'Biryani'                        => 'Google Fonts',
            'Bitter'                         => 'Google Fonts',
            'Black And White Picture'        => 'Google Fonts',
            'Black Han Sans'                 => 'Google Fonts',
            'Black Ops One'                  => 'Google Fonts',
            'Bokor'                          => 'Google Fonts',
            'Bonbon'                         => 'Google Fonts',
            'Boogaloo'                       => 'Google Fonts',
            'Bowlby One'                     => 'Google Fonts',
            'Bowlby One SC'                  => 'Google Fonts',
            'Brawler'                        => 'Google Fonts',
            'Bree Serif'                     => 'Google Fonts',
            'Bubblegum Sans'                 => 'Google Fonts',
            'Bubbler One'                    => 'Google Fonts',
            'Buda'                           => 'Google Fonts',
            'Buenard'                        => 'Google Fonts',
            'Bungee'                         => 'Google Fonts',
            'Bungee Hairline'                => 'Google Fonts',
            'Bungee Inline'                  => 'Google Fonts',
            'Bungee Outline'                 => 'Google Fonts',
            'Bungee Shade'                   => 'Google Fonts',
            'Butcherman'                     => 'Google Fonts',
            'Butterfly Kids'                 => 'Google Fonts',
            'Cabin'                          => 'Google Fonts',
            'Cabin Condensed'                => 'Google Fonts',
            'Cabin Sketch'                   => 'Google Fonts',
            'Caesar Dressing'                => 'Google Fonts',
            'Cagliostro'                     => 'Google Fonts',
            'Cairo'                          => 'Google Fonts',
            'Calligraffitti'                 => 'Google Fonts',
            'Cambay'                         => 'Google Fonts',
            'Cambo'                          => 'Google Fonts',
            'Candal'                         => 'Google Fonts',
            'Cantarell'                      => 'Google Fonts',
            'Cantata One'                    => 'Google Fonts',
            'Cantora One'                    => 'Google Fonts',
            'Capriola'                       => 'Google Fonts',
            'Cardo'                          => 'Google Fonts',
            'Carme'                          => 'Google Fonts',
            'Carrois Gothic'                 => 'Google Fonts',
            'Carrois Gothic SC'              => 'Google Fonts',
            'Carter One'                     => 'Google Fonts',
            'Catamaran'                      => 'Google Fonts',
            'Caudex'                         => 'Google Fonts',
            'Caveat'                         => 'Google Fonts',
            'Caveat Brush'                   => 'Google Fonts',
            'Cedarville Cursive'             => 'Google Fonts',
            'Ceviche One'                    => 'Google Fonts',
            'Chakra Petch'                   => 'Google Fonts',
            'Changa'                         => 'Google Fonts',
            'Changa One'                     => 'Google Fonts',
            'Chango'                         => 'Google Fonts',
            'Charmonman'                     => 'Google Fonts',
            'Chathura'                       => 'Google Fonts',
            'Chau Philomene One'             => 'Google Fonts',
            'Chela One'                      => 'Google Fonts',
            'Chelsea Market'                 => 'Google Fonts',
            'Chenla'                         => 'Google Fonts',
            'Cherry Cream Soda'              => 'Google Fonts',
            'Cherry Swash'                   => 'Google Fonts',
            'Chewy'                          => 'Google Fonts',
            'Chicle'                         => 'Google Fonts',
            'Chivo'                          => 'Google Fonts',
            'Chonburi'                       => 'Google Fonts',
            'Cinzel'                         => 'Google Fonts',
            'Cinzel Decorative'              => 'Google Fonts',
            'Clicker Script'                 => 'Google Fonts',
            'Coda'                           => 'Google Fonts',
            'Coda Caption'                   => 'Google Fonts',
            'Codystar'                       => 'Google Fonts',
            'Coiny'                          => 'Google Fonts',
            'Combo'                          => 'Google Fonts',
            'Comfortaa'                      => 'Google Fonts',
            'Coming Soon'                    => 'Google Fonts',
            'Concert One'                    => 'Google Fonts',
            'Condiment'                      => 'Google Fonts',
            'Content'                        => 'Google Fonts',
            'Contrail One'                   => 'Google Fonts',
            'Convergence'                    => 'Google Fonts',
            'Cookie'                         => 'Google Fonts',
            'Copse'                          => 'Google Fonts',
            'Corben'                         => 'Google Fonts',
            'Cormorant'                      => 'Google Fonts',
            'Cormorant Garamond'             => 'Google Fonts',
            'Cormorant Infant'               => 'Google Fonts',
            'Cormorant SC'                   => 'Google Fonts',
            'Cormorant Unicase'              => 'Google Fonts',
            'Cormorant Upright'              => 'Google Fonts',
            'Courgette'                      => 'Google Fonts',
            'Cousine'                        => 'Google Fonts',
            'Coustard'                       => 'Google Fonts',
            'Covered By Your Grace'          => 'Google Fonts',
            'Crafty Girls'                   => 'Google Fonts',
            'Creepster'                      => 'Google Fonts',
            'Crete Round'                    => 'Google Fonts',
            'Crimson Text'                   => 'Google Fonts',
            'Croissant One'                  => 'Google Fonts',
            'Crushed'                        => 'Google Fonts',
            'Cuprum'                         => 'Google Fonts',
            'Cute Font'                      => 'Google Fonts',
            'Cutive'                         => 'Google Fonts',
            'Cutive Mono'                    => 'Google Fonts',
            'Damion'                         => 'Google Fonts',
            'Dancing Script'                 => 'Google Fonts',
            'Dangrek'                        => 'Google Fonts',
            'David Libre'                    => 'Google Fonts',
            'Dawning of a New Day'           => 'Google Fonts',
            'Days One'                       => 'Google Fonts',
            'Dekko'                          => 'Google Fonts',
            'Delius'                         => 'Google Fonts',
            'Delius Swash Caps'              => 'Google Fonts',
            'Delius Unicase'                 => 'Google Fonts',
            'Della Respira'                  => 'Google Fonts',
            'Denk One'                       => 'Google Fonts',
            'Devonshire'                     => 'Google Fonts',
            'Dhurjati'                       => 'Google Fonts',
            'Didact Gothic'                  => 'Google Fonts',
            'Diplomata'                      => 'Google Fonts',
            'Diplomata SC'                   => 'Google Fonts',
            'Do Hyeon'                       => 'Google Fonts',
            'Dokdo'                          => 'Google Fonts',
            'Domine'                         => 'Google Fonts',
            'Donegal One'                    => 'Google Fonts',
            'Doppio One'                     => 'Google Fonts',
            'Dorsa'                          => 'Google Fonts',
            'Dosis'                          => 'Google Fonts',
            'Dr Sugiyama'                    => 'Google Fonts',
            'Droid Arabic Kufi'              => 'Google Fonts',
            // Hack for Google Early Access.
            'Droid Arabic Naskh'             => 'Google Fonts',
            // Hack for Google Early Access.
            'Duru Sans'                      => 'Google Fonts',
            'Dynalight'                      => 'Google Fonts',
            'EB Garamond'                    => 'Google Fonts',
            'Eagle Lake'                     => 'Google Fonts',
            'East Sea Dokdo'                 => 'Google Fonts',
            'Eater'                          => 'Google Fonts',
            'Economica'                      => 'Google Fonts',
            'Eczar'                          => 'Google Fonts',
            'El Messiri'                     => 'Google Fonts',
            'Electrolize'                    => 'Google Fonts',
            'Elsie'                          => 'Google Fonts',
            'Elsie Swash Caps'               => 'Google Fonts',
            'Emblema One'                    => 'Google Fonts',
            'Emilys Candy'                   => 'Google Fonts',
            'Encode Sans'                    => 'Google Fonts',
            'Encode Sans Condensed'          => 'Google Fonts',
            'Encode Sans Expanded'           => 'Google Fonts',
            'Encode Sans Semi Condensed'     => 'Google Fonts',
            'Encode Sans Semi Expanded'      => 'Google Fonts',
            'Engagement'                     => 'Google Fonts',
            'Englebert'                      => 'Google Fonts',
            'Enriqueta'                      => 'Google Fonts',
            'Erica One'                      => 'Google Fonts',
            'Esteban'                        => 'Google Fonts',
            'Euphoria Script'                => 'Google Fonts',
            'Ewert'                          => 'Google Fonts',
            'Exo'                            => 'Google Fonts',
            'Exo 2'                          => 'Google Fonts',
            'Expletus Sans'                  => 'Google Fonts',
            'Fahkwang'                       => 'Google Fonts',
            'Fanwood Text'                   => 'Google Fonts',
            'Farsan'                         => 'Google Fonts',
            'Fascinate'                      => 'Google Fonts',
            'Fascinate Inline'               => 'Google Fonts',
            'Faster One'                     => 'Google Fonts',
            'Fasthand'                       => 'Google Fonts',
            'Fauna One'                      => 'Google Fonts',
            'Faustina'                       => 'Google Fonts',
            'Federant'                       => 'Google Fonts',
            'Federo'                         => 'Google Fonts',
            'Felipa'                         => 'Google Fonts',
            'Fenix'                          => 'Google Fonts',
            'Finger Paint'                   => 'Google Fonts',
            'Fira Mono'                      => 'Google Fonts',
            'Fira Sans'                      => 'Google Fonts',
            'Fira Sans Condensed'            => 'Google Fonts',
            'Fira Sans Extra Condensed'      => 'Google Fonts',
            'Fjalla One'                     => 'Google Fonts',
            'Fjord One'                      => 'Google Fonts',
            'Flamenco'                       => 'Google Fonts',
            'Flavors'                        => 'Google Fonts',
            'Fondamento'                     => 'Google Fonts',
            'Fontdiner Swanky'               => 'Google Fonts',
            'Forum'                          => 'Google Fonts',
            'Francois One'                   => 'Google Fonts',
            'Frank Ruhl Libre'               => 'Google Fonts',
            'Freckle Face'                   => 'Google Fonts',
            'Fredericka the Great'           => 'Google Fonts',
            'Fredoka One'                    => 'Google Fonts',
            'Freehand'                       => 'Google Fonts',
            'Fresca'                         => 'Google Fonts',
            'Frijole'                        => 'Google Fonts',
            'Fruktur'                        => 'Google Fonts',
            'Fugaz One'                      => 'Google Fonts',
            'GFS Didot'                      => 'Google Fonts',
            'GFS Neohellenic'                => 'Google Fonts',
            'Gabriela'                       => 'Google Fonts',
            'Gaegu'                          => 'Google Fonts',
            'Gafata'                         => 'Google Fonts',
            'Galada'                         => 'Google Fonts',
            'Galdeano'                       => 'Google Fonts',
            'Galindo'                        => 'Google Fonts',
            'Gamja Flower'                   => 'Google Fonts',
            'Gentium Basic'                  => 'Google Fonts',
            'Gentium Book Basic'             => 'Google Fonts',
            'Geo'                            => 'Google Fonts',
            'Geostar'                        => 'Google Fonts',
            'Geostar Fill'                   => 'Google Fonts',
            'Germania One'                   => 'Google Fonts',
            'Gidugu'                         => 'Google Fonts',
            'Gilda Display'                  => 'Google Fonts',
            'Give You Glory'                 => 'Google Fonts',
            'Glass Antiqua'                  => 'Google Fonts',
            'Glegoo'                         => 'Google Fonts',
            'Gloria Hallelujah'              => 'Google Fonts',
            'Goblin One'                     => 'Google Fonts',
            'Gochi Hand'                     => 'Google Fonts',
            'Gorditas'                       => 'Google Fonts',
            'Gothic A1'                      => 'Google Fonts',
            'Goudy Bookletter 1911'          => 'Google Fonts',
            'Graduate'                       => 'Google Fonts',
            'Grand Hotel'                    => 'Google Fonts',
            'Gravitas One'                   => 'Google Fonts',
            'Great Vibes'                    => 'Google Fonts',
            'Griffy'                         => 'Google Fonts',
            'Gruppo'                         => 'Google Fonts',
            'Gudea'                          => 'Google Fonts',
            'Gugi'                           => 'Google Fonts',
            'Gurajada'                       => 'Google Fonts',
            'Habibi'                         => 'Google Fonts',
            'Halant'                         => 'Google Fonts',
            'Hammersmith One'                => 'Google Fonts',
            'Hanalei'                        => 'Google Fonts',
            'Hanalei Fill'                   => 'Google Fonts',
            'Handlee'                        => 'Google Fonts',
            'Hanuman'                        => 'Google Fonts',
            'Happy Monkey'                   => 'Google Fonts',
            'Harmattan'                      => 'Google Fonts',
            'Headland One'                   => 'Google Fonts',
            'Heebo'                          => 'Google Fonts',
            'Henny Penny'                    => 'Google Fonts',
            'Herr Von Muellerhoff'           => 'Google Fonts',
            'Hi Melody'                      => 'Google Fonts',
            'Hind'                           => 'Google Fonts',
            'Hind Guntur'                    => 'Google Fonts',
            'Hind Madurai'                   => 'Google Fonts',
            'Hind Siliguri'                  => 'Google Fonts',
            'Hind Vadodara'                  => 'Google Fonts',
            'Holtwood One SC'                => 'Google Fonts',
            'Homemade Apple'                 => 'Google Fonts',
            'Homenaje'                       => 'Google Fonts',
            'IBM Plex Mono'                  => 'Google Fonts',
            'IBM Plex Sans'                  => 'Google Fonts',
            'IBM Plex Sans Condensed'        => 'Google Fonts',
            'IBM Plex Serif'                 => 'Google Fonts',
            'IM Fell DW Pica'                => 'Google Fonts',
            'IM Fell DW Pica SC'             => 'Google Fonts',
            'IM Fell Double Pica'            => 'Google Fonts',
            'IM Fell Double Pica SC'         => 'Google Fonts',
            'IM Fell English'                => 'Google Fonts',
            'IM Fell English SC'             => 'Google Fonts',
            'IM Fell French Canon'           => 'Google Fonts',
            'IM Fell French Canon SC'        => 'Google Fonts',
            'IM Fell Great Primer'           => 'Google Fonts',
            'IM Fell Great Primer SC'        => 'Google Fonts',
            'Iceberg'                        => 'Google Fonts',
            'Iceland'                        => 'Google Fonts',
            'Imprima'                        => 'Google Fonts',
            'Inconsolata'                    => 'Google Fonts',
            'Inder'                          => 'Google Fonts',
            'Indie Flower'                   => 'Google Fonts',
            'Inika'                          => 'Google Fonts',
            'Inknut Antiqua'                 => 'Google Fonts',
            'Irish Grover'                   => 'Google Fonts',
            'Istok Web'                      => 'Google Fonts',
            'Italiana'                       => 'Google Fonts',
            'Italianno'                      => 'Google Fonts',
            'Itim'                           => 'Google Fonts',
            'Jacques Francois'               => 'Google Fonts',
            'Jacques Francois Shadow'        => 'Google Fonts',
            'Jaldi'                          => 'Google Fonts',
            'Jim Nightshade'                 => 'Google Fonts',
            'Jockey One'                     => 'Google Fonts',
            'Jolly Lodger'                   => 'Google Fonts',
            'Jomhuria'                       => 'Google Fonts',
            'Josefin Sans'                   => 'Google Fonts',
            'Josefin Slab'                   => 'Google Fonts',
            'Joti One'                       => 'Google Fonts',
            'Jua'                            => 'Google Fonts',
            'Judson'                         => 'Google Fonts',
            'Julee'                          => 'Google Fonts',
            'Julius Sans One'                => 'Google Fonts',
            'Junge'                          => 'Google Fonts',
            'Jura'                           => 'Google Fonts',
            'Just Another Hand'              => 'Google Fonts',
            'Just Me Again Down Here'        => 'Google Fonts',
            'K2D'                            => 'Google Fonts',
            'Kadwa'                          => 'Google Fonts',
            'Kalam'                          => 'Google Fonts',
            'Kameron'                        => 'Google Fonts',
            'Kanit'                          => 'Google Fonts',
            'Kantumruy'                      => 'Google Fonts',
            'Karla'                          => 'Google Fonts',
            'Karma'                          => 'Google Fonts',
            'Katibeh'                        => 'Google Fonts',
            'Kaushan Script'                 => 'Google Fonts',
            'Kavivanar'                      => 'Google Fonts',
            'Kavoon'                         => 'Google Fonts',
            'Kdam Thmor'                     => 'Google Fonts',
            'Keania One'                     => 'Google Fonts',
            'Kelly Slab'                     => 'Google Fonts',
            'Kenia'                          => 'Google Fonts',
            'Khand'                          => 'Google Fonts',
            'Khmer'                          => 'Google Fonts',
            'Khula'                          => 'Google Fonts',
            'Kirang Haerang'                 => 'Google Fonts',
            'Kite One'                       => 'Google Fonts',
            'Knewave'                        => 'Google Fonts',
            'KoHo'                           => 'Google Fonts',
            'Kodchasan'                      => 'Google Fonts',
            'Kosugi'                         => 'Google Fonts',
            'Kosugi Maru'                    => 'Google Fonts',
            'Kotta One'                      => 'Google Fonts',
            'Koulen'                         => 'Google Fonts',
            'Kranky'                         => 'Google Fonts',
            'Kreon'                          => 'Google Fonts',
            'Kristi'                         => 'Google Fonts',
            'Krona One'                      => 'Google Fonts',
            'Krub'                           => 'Google Fonts',
            'Kumar One'                      => 'Google Fonts',
            'Kumar One Outline'              => 'Google Fonts',
            'Kurale'                         => 'Google Fonts',
            'La Belle Aurore'                => 'Google Fonts',
            'Laila'                          => 'Google Fonts',
            'Lakki Reddy'                    => 'Google Fonts',
            'Lalezar'                        => 'Google Fonts',
            'Lancelot'                       => 'Google Fonts',
            'Lateef'                         => 'Google Fonts',
            'Lato'                           => 'Google Fonts',
            'League Script'                  => 'Google Fonts',
            'Leckerli One'                   => 'Google Fonts',
            'Ledger'                         => 'Google Fonts',
            'Lekton'                         => 'Google Fonts',
            'Lemon'                          => 'Google Fonts',
            'Lemonada'                       => 'Google Fonts',
            'Libre Barcode 128'              => 'Google Fonts',
            'Libre Barcode 128 Text'         => 'Google Fonts',
            'Libre Barcode 39'               => 'Google Fonts',
            'Libre Barcode 39 Extended'      => 'Google Fonts',
            'Libre Barcode 39 Extended Text' => 'Google Fonts',
            'Libre Barcode 39 Text'          => 'Google Fonts',
            'Libre Baskerville'              => 'Google Fonts',
            'Libre Franklin'                 => 'Google Fonts',
            'Life Savers'                    => 'Google Fonts',
            'Lilita One'                     => 'Google Fonts',
            'Lily Script One'                => 'Google Fonts',
            'Limelight'                      => 'Google Fonts',
            'Linden Hill'                    => 'Google Fonts',
            'Lobster'                        => 'Google Fonts',
            'Lobster Two'                    => 'Google Fonts',
            'Londrina Outline'               => 'Google Fonts',
            'Londrina Shadow'                => 'Google Fonts',
            'Londrina Sketch'                => 'Google Fonts',
            'Londrina Solid'                 => 'Google Fonts',
            'Lora'                           => 'Google Fonts',
            'Love Ya Like A Sister'          => 'Google Fonts',
            'Loved by the King'              => 'Google Fonts',
            'Lovers Quarrel'                 => 'Google Fonts',
            'Luckiest Guy'                   => 'Google Fonts',
            'Lusitana'                       => 'Google Fonts',
            'Lustria'                        => 'Google Fonts',
            'M PLUS 1p'                      => 'Google Fonts',
            'M PLUS Rounded 1c'              => 'Google Fonts',
            'Macondo'                        => 'Google Fonts',
            'Macondo Swash Caps'             => 'Google Fonts',
            'Mada'                           => 'Google Fonts',
            'Magra'                          => 'Google Fonts',
            'Maiden Orange'                  => 'Google Fonts',
            'Maitree'                        => 'Google Fonts',
            'Mako'                           => 'Google Fonts',
            'Mali'                           => 'Google Fonts',
            'Mallanna'                       => 'Google Fonts',
            'Mandali'                        => 'Google Fonts',
            'Manuale'                        => 'Google Fonts',
            'Marcellus'                      => 'Google Fonts',
            'Marcellus SC'                   => 'Google Fonts',
            'Marck Script'                   => 'Google Fonts',
            'Margarine'                      => 'Google Fonts',
            'Markazi Text'                   => 'Google Fonts',
            'Marko One'                      => 'Google Fonts',
            'Marmelad'                       => 'Google Fonts',
            'Martel'                         => 'Google Fonts',
            'Martel Sans'                    => 'Google Fonts',
            'Marvel'                         => 'Google Fonts',
            'Mate'                           => 'Google Fonts',
            'Mate SC'                        => 'Google Fonts',
            'Maven Pro'                      => 'Google Fonts',
            'McLaren'                        => 'Google Fonts',
            'Meddon'                         => 'Google Fonts',
            'MedievalSharp'                  => 'Google Fonts',
            'Medula One'                     => 'Google Fonts',
            'Meera Inimai'                   => 'Google Fonts',
            'Megrim'                         => 'Google Fonts',
            'Meie Script'                    => 'Google Fonts',
            'Merienda'                       => 'Google Fonts',
            'Merienda One'                   => 'Google Fonts',
            'Merriweather'                   => 'Google Fonts',
            'Merriweather Sans'              => 'Google Fonts',
            'Metal'                          => 'Google Fonts',
            'Metal Mania'                    => 'Google Fonts',
            'Metamorphous'                   => 'Google Fonts',
            'Metrophobic'                    => 'Google Fonts',
            'Michroma'                       => 'Google Fonts',
            'Milonga'                        => 'Google Fonts',
            'Miltonian'                      => 'Google Fonts',
            'Miltonian Tattoo'               => 'Google Fonts',
            'Mina'                           => 'Google Fonts',
            'Miniver'                        => 'Google Fonts',
            'Miriam Libre'                   => 'Google Fonts',
            'Mirza'                          => 'Google Fonts',
            'Miss Fajardose'                 => 'Google Fonts',
            'Mitr'                           => 'Google Fonts',
            'Modak'                          => 'Google Fonts',
            'Modern Antiqua'                 => 'Google Fonts',
            'Mogra'                          => 'Google Fonts',
            'Molengo'                        => 'Google Fonts',
            'Molle'                          => 'Google Fonts',
            'Monda'                          => 'Google Fonts',
            'Monofett'                       => 'Google Fonts',
            'Monoton'                        => 'Google Fonts',
            'Monsieur La Doulaise'           => 'Google Fonts',
            'Montaga'                        => 'Google Fonts',
            'Montez'                         => 'Google Fonts',
            'Montserrat'                     => 'Google Fonts',
            'Montserrat Alternates'          => 'Google Fonts',
            'Montserrat Subrayada'           => 'Google Fonts',
            'Moul'                           => 'Google Fonts',
            'Moulpali'                       => 'Google Fonts',
            'Mountains of Christmas'         => 'Google Fonts',
            'Mouse Memoirs'                  => 'Google Fonts',
            'Mr Bedfort'                     => 'Google Fonts',
            'Mr Dafoe'                       => 'Google Fonts',
            'Mr De Haviland'                 => 'Google Fonts',
            'Mrs Saint Delafield'            => 'Google Fonts',
            'Mrs Sheppards'                  => 'Google Fonts',
            'Mukta'                          => 'Google Fonts',
            'Mukta Mahee'                    => 'Google Fonts',
            'Mukta Malar'                    => 'Google Fonts',
            'Mukta Vaani'                    => 'Google Fonts',
            'Muli'                           => 'Google Fonts',
            'Mystery Quest'                  => 'Google Fonts',
            'NTR'                            => 'Google Fonts',
            'Nanum Brush Script'             => 'Google Fonts',
            'Nanum Gothic'                   => 'Google Fonts',
            'Nanum Gothic Coding'            => 'Google Fonts',
            'Nanum Myeongjo'                 => 'Google Fonts',
            'Nanum Pen Script'               => 'Google Fonts',
            'Neucha'                         => 'Google Fonts',
            'Neuton'                         => 'Google Fonts',
            'New Rocker'                     => 'Google Fonts',
            'News Cycle'                     => 'Google Fonts',
            'Niconne'                        => 'Google Fonts',
            'Niramit'                        => 'Google Fonts',
            'Nixie One'                      => 'Google Fonts',
            'Nobile'                         => 'Google Fonts',
            'Nokora'                         => 'Google Fonts',
            'Norican'                        => 'Google Fonts',
            'Nosifer'                        => 'Google Fonts',
            'Notable'                        => 'Google Fonts',
            'Nothing You Could Do'           => 'Google Fonts',
            'Noticia Text'                   => 'Google Fonts',
            'Noto Kufi Arabic'               => 'Google Fonts',
            // Hack for Google Early Access.
            'Noto Naskh Arabic'              => 'Google Fonts',
            // Hack for Google Early Access.
            'Noto Sans'                      => 'Google Fonts',
            'Noto Sans Hebrew'               => 'Google Fonts',
            // Hack for Google Early Access.
            'Noto Sans JP'                   => 'Google Fonts',
            'Noto Sans KR'                   => 'Google Fonts',
            'Noto Serif'                     => 'Google Fonts',
            'Noto Serif JP'                  => 'Google Fonts',
            'Noto Serif KR'                  => 'Google Fonts',
            'Nova Cut'                       => 'Google Fonts',
            'Nova Flat'                      => 'Google Fonts',
            'Nova Mono'                      => 'Google Fonts',
            'Nova Oval'                      => 'Google Fonts',
            'Nova Round'                     => 'Google Fonts',
            'Nova Script'                    => 'Google Fonts',
            'Nova Slim'                      => 'Google Fonts',
            'Nova Square'                    => 'Google Fonts',
            'Numans'                         => 'Google Fonts',
            'Nunito'                         => 'Google Fonts',
            'Nunito Sans'                    => 'Google Fonts',
            'Odor Mean Chey'                 => 'Google Fonts',
            'Offside'                        => 'Google Fonts',
            'Old Standard TT'                => 'Google Fonts',
            'Oldenburg'                      => 'Google Fonts',
            'Oleo Script'                    => 'Google Fonts',
            'Oleo Script Swash Caps'         => 'Google Fonts',
            'Open Sans'                      => 'Google Fonts',
            'Open Sans Condensed'            => 'Google Fonts',
            'Open Sans Hebrew'               => 'Google Fonts',
            // Hack for Google Early Access.
            'Open Sans Hebrew Condensed'     => 'Google Fonts',
            // Hack for Google Early Access.
            'Oranienbaum'                    => 'Google Fonts',
            'Orbitron'                       => 'Google Fonts',
            'Oregano'                        => 'Google Fonts',
            'Orienta'                        => 'Google Fonts',
            'Original Surfer'                => 'Google Fonts',
            'Oswald'                         => 'Google Fonts',
            'Over the Rainbow'               => 'Google Fonts',
            'Overlock'                       => 'Google Fonts',
            'Overlock SC'                    => 'Google Fonts',
            'Overpass'                       => 'Google Fonts',
            'Overpass Mono'                  => 'Google Fonts',
            'Ovo'                            => 'Google Fonts',
            'Oxygen'                         => 'Google Fonts',
            'Oxygen Mono'                    => 'Google Fonts',
            'PT Mono'                        => 'Google Fonts',
            'PT Sans'                        => 'Google Fonts',
            'PT Sans Caption'                => 'Google Fonts',
            'PT Sans Narrow'                 => 'Google Fonts',
            'PT Serif'                       => 'Google Fonts',
            'PT Serif Caption'               => 'Google Fonts',
            'Pacifico'                       => 'Google Fonts',
            'Padauk'                         => 'Google Fonts',
            'Palanquin'                      => 'Google Fonts',
            'Palanquin Dark'                 => 'Google Fonts',
            'Pangolin'                       => 'Google Fonts',
            'Paprika'                        => 'Google Fonts',
            'Parisienne'                     => 'Google Fonts',
            'Passero One'                    => 'Google Fonts',
            'Passion One'                    => 'Google Fonts',
            'Pathway Gothic One'             => 'Google Fonts',
            'Patrick Hand'                   => 'Google Fonts',
            'Patrick Hand SC'                => 'Google Fonts',
            'Pattaya'                        => 'Google Fonts',
            'Patua One'                      => 'Google Fonts',
            'Pavanam'                        => 'Google Fonts',
            'Paytone One'                    => 'Google Fonts',
            'Peddana'                        => 'Google Fonts',
            'Peralta'                        => 'Google Fonts',
            'Permanent Marker'               => 'Google Fonts',
            'Petit Formal Script'            => 'Google Fonts',
            'Petrona'                        => 'Google Fonts',
            'Philosopher'                    => 'Google Fonts',
            'Piedra'                         => 'Google Fonts',
            'Pinyon Script'                  => 'Google Fonts',
            'Pirata One'                     => 'Google Fonts',
            'Plaster'                        => 'Google Fonts',
            'Play'                           => 'Google Fonts',
            'Playball'                       => 'Google Fonts',
            'Playfair Display'               => 'Google Fonts',
            'Playfair Display SC'            => 'Google Fonts',
            'Podkova'                        => 'Google Fonts',
            'Poiret One'                     => 'Google Fonts',
            'Poller One'                     => 'Google Fonts',
            'Poly'                           => 'Google Fonts',
            'Pompiere'                       => 'Google Fonts',
            'Pontano Sans'                   => 'Google Fonts',
            'Poor Story'                     => 'Google Fonts',
            'Poppins'                        => 'Google Fonts',
            'Port Lligat Sans'               => 'Google Fonts',
            'Port Lligat Slab'               => 'Google Fonts',
            'Pragati Narrow'                 => 'Google Fonts',
            'Prata'                          => 'Google Fonts',
            'Preahvihear'                    => 'Google Fonts',
            'Press Start 2P'                 => 'Google Fonts',
            'Pridi'                          => 'Google Fonts',
            'Princess Sofia'                 => 'Google Fonts',
            'Prociono'                       => 'Google Fonts',
            'Prompt'                         => 'Google Fonts',
            'Prosto One'                     => 'Google Fonts',
            'Proza Libre'                    => 'Google Fonts',
            'Puritan'                        => 'Google Fonts',
            'Purple Purse'                   => 'Google Fonts',
            'Quando'                         => 'Google Fonts',
            'Quantico'                       => 'Google Fonts',
            'Quattrocento'                   => 'Google Fonts',
            'Quattrocento Sans'              => 'Google Fonts',
            'Questrial'                      => 'Google Fonts',
            'Quicksand'                      => 'Google Fonts',
            'Quintessential'                 => 'Google Fonts',
            'Qwigley'                        => 'Google Fonts',
            'Racing Sans One'                => 'Google Fonts',
            'Radley'                         => 'Google Fonts',
            'Rajdhani'                       => 'Google Fonts',
            'Rakkas'                         => 'Google Fonts',
            'Raleway'                        => 'Google Fonts',
            'Raleway Dots'                   => 'Google Fonts',
            'Ramabhadra'                     => 'Google Fonts',
            'Ramaraja'                       => 'Google Fonts',
            'Rambla'                         => 'Google Fonts',
            'Rammetto One'                   => 'Google Fonts',
            'Ranchers'                       => 'Google Fonts',
            'Rancho'                         => 'Google Fonts',
            'Ranga'                          => 'Google Fonts',
            'Rasa'                           => 'Google Fonts',
            'Rationale'                      => 'Google Fonts',
            'Ravi Prakash'                   => 'Google Fonts',
            'Redressed'                      => 'Google Fonts',
            'Reem Kufi'                      => 'Google Fonts',
            'Reenie Beanie'                  => 'Google Fonts',
            'Revalia'                        => 'Google Fonts',
            'Rhodium Libre'                  => 'Google Fonts',
            'Ribeye'                         => 'Google Fonts',
            'Ribeye Marrow'                  => 'Google Fonts',
            'Righteous'                      => 'Google Fonts',
            'Risque'                         => 'Google Fonts',
            'Roboto'                         => 'Google Fonts',
            'Roboto Condensed'               => 'Google Fonts',
            'Roboto Mono'                    => 'Google Fonts',
            'Roboto Slab'                    => 'Google Fonts',
            'Rochester'                      => 'Google Fonts',
            'Rock Salt'                      => 'Google Fonts',
            'Rokkitt'                        => 'Google Fonts',
            'Romanesco'                      => 'Google Fonts',
            'Ropa Sans'                      => 'Google Fonts',
            'Rosario'                        => 'Google Fonts',
            'Rosarivo'                       => 'Google Fonts',
            'Rouge Script'                   => 'Google Fonts',
            'Rozha One'                      => 'Google Fonts',
            'Rubik'                          => 'Google Fonts',
            'Rubik Mono One'                 => 'Google Fonts',
            'Ruda'                           => 'Google Fonts',
            'Rufina'                         => 'Google Fonts',
            'Ruge Boogie'                    => 'Google Fonts',
            'Ruluko'                         => 'Google Fonts',
            'Rum Raisin'                     => 'Google Fonts',
            'Ruslan Display'                 => 'Google Fonts',
            'Russo One'                      => 'Google Fonts',
            'Ruthie'                         => 'Google Fonts',
            'Rye'                            => 'Google Fonts',
            'Sacramento'                     => 'Google Fonts',
            'Sahitya'                        => 'Google Fonts',
            'Sail'                           => 'Google Fonts',
            'Saira'                          => 'Google Fonts',
            'Saira Condensed'                => 'Google Fonts',
            'Saira Extra Condensed'          => 'Google Fonts',
            'Saira Semi Condensed'           => 'Google Fonts',
            'Salsa'                          => 'Google Fonts',
            'Sanchez'                        => 'Google Fonts',
            'Sancreek'                       => 'Google Fonts',
            'Sansita'                        => 'Google Fonts',
            'Sarala'                         => 'Google Fonts',
            'Sarina'                         => 'Google Fonts',
            'Sarpanch'                       => 'Google Fonts',
            'Satisfy'                        => 'Google Fonts',
            'Sawarabi Gothic'                => 'Google Fonts',
            'Sawarabi Mincho'                => 'Google Fonts',
            'Scada'                          => 'Google Fonts',
            'Scheherazade'                   => 'Google Fonts',
            'Schoolbell'                     => 'Google Fonts',
            'Scope One'                      => 'Google Fonts',
            'Seaweed Script'                 => 'Google Fonts',
            'Secular One'                    => 'Google Fonts',
            'Sedgwick Ave'                   => 'Google Fonts',
            'Sedgwick Ave Display'           => 'Google Fonts',
            'Sevillana'                      => 'Google Fonts',
            'Seymour One'                    => 'Google Fonts',
            'Shadows Into Light'             => 'Google Fonts',
            'Shadows Into Light Two'         => 'Google Fonts',
            'Shanti'                         => 'Google Fonts',
            'Share'                          => 'Google Fonts',
            'Share Tech'                     => 'Google Fonts',
            'Share Tech Mono'                => 'Google Fonts',
            'Shojumaru'                      => 'Google Fonts',
            'Short Stack'                    => 'Google Fonts',
            'Shrikhand'                      => 'Google Fonts',
            'Siemreap'                       => 'Google Fonts',
            'Sigmar One'                     => 'Google Fonts',
            'Signika'                        => 'Google Fonts',
            'Signika Negative'               => 'Google Fonts',
            'Simonetta'                      => 'Google Fonts',
            'Sintony'                        => 'Google Fonts',
            'Sirin Stencil'                  => 'Google Fonts',
            'Six Caps'                       => 'Google Fonts',
            'Skranji'                        => 'Google Fonts',
            'Slabo 13px'                     => 'Google Fonts',
            'Slabo 27px'                     => 'Google Fonts',
            'Slackey'                        => 'Google Fonts',
            'Smokum'                         => 'Google Fonts',
            'Smythe'                         => 'Google Fonts',
            'Sniglet'                        => 'Google Fonts',
            'Snippet'                        => 'Google Fonts',
            'Snowburst One'                  => 'Google Fonts',
            'Sofadi One'                     => 'Google Fonts',
            'Sofia'                          => 'Google Fonts',
            'Song Myung'                     => 'Google Fonts',
            'Sonsie One'                     => 'Google Fonts',
            'Sorts Mill Goudy'               => 'Google Fonts',
            'Source Code Pro'                => 'Google Fonts',
            'Source Sans Pro'                => 'Google Fonts',
            'Source Serif Pro'               => 'Google Fonts',
            'Space Mono'                     => 'Google Fonts',
            'Special Elite'                  => 'Google Fonts',
            'Spectral'                       => 'Google Fonts',
            'Spectral SC'                    => 'Google Fonts',
            'Spicy Rice'                     => 'Google Fonts',
            'Spinnaker'                      => 'Google Fonts',
            'Spirax'                         => 'Google Fonts',
            'Squada One'                     => 'Google Fonts',
            'Sree Krushnadevaraya'           => 'Google Fonts',
            'Sriracha'                       => 'Google Fonts',
            'Srisakdi'                       => 'Google Fonts',
            'Stalemate'                      => 'Google Fonts',
            'Stalinist One'                  => 'Google Fonts',
            'Stardos Stencil'                => 'Google Fonts',
            'Stint Ultra Condensed'          => 'Google Fonts',
            'Stint Ultra Expanded'           => 'Google Fonts',
            'Stoke'                          => 'Google Fonts',
            'Strait'                         => 'Google Fonts',
            'Stylish'                        => 'Google Fonts',
            'Sue Ellen Francisco'            => 'Google Fonts',
            'Suez One'                       => 'Google Fonts',
            'Sumana'                         => 'Google Fonts',
            'Sunflower'                      => 'Google Fonts',
            'Sunshiney'                      => 'Google Fonts',
            'Supermercado One'               => 'Google Fonts',
            'Sura'                           => 'Google Fonts',
            'Suranna'                        => 'Google Fonts',
            'Suravaram'                      => 'Google Fonts',
            'Suwannaphum'                    => 'Google Fonts',
            'Swanky and Moo Moo'             => 'Google Fonts',
            'Syncopate'                      => 'Google Fonts',
            'Tajawal'                        => 'Google Fonts',
            'Tangerine'                      => 'Google Fonts',
            'Taprom'                         => 'Google Fonts',
            'Tauri'                          => 'Google Fonts',
            'Taviraj'                        => 'Google Fonts',
            'Teko'                           => 'Google Fonts',
            'Telex'                          => 'Google Fonts',
            'Tenali Ramakrishna'             => 'Google Fonts',
            'Tenor Sans'                     => 'Google Fonts',
            'Text Me One'                    => 'Google Fonts',
            'The Girl Next Door'             => 'Google Fonts',
            'Tienne'                         => 'Google Fonts',
            'Tillana'                        => 'Google Fonts',
            'Timmana'                        => 'Google Fonts',
            'Tinos'                          => 'Google Fonts',
            'Titan One'                      => 'Google Fonts',
            'Titillium Web'                  => 'Google Fonts',
            'Trade Winds'                    => 'Google Fonts',
            'Trirong'                        => 'Google Fonts',
            'Trocchi'                        => 'Google Fonts',
            'Trochut'                        => 'Google Fonts',
            'Trykker'                        => 'Google Fonts',
            'Tulpen One'                     => 'Google Fonts',
            'Ubuntu'                         => 'Google Fonts',
            'Ubuntu Condensed'               => 'Google Fonts',
            'Ubuntu Mono'                    => 'Google Fonts',
            'Ultra'                          => 'Google Fonts',
            'Uncial Antiqua'                 => 'Google Fonts',
            'Underdog'                       => 'Google Fonts',
            'Unica One'                      => 'Google Fonts',
            'UnifrakturCook'                 => 'Google Fonts',
            'UnifrakturMaguntia'             => 'Google Fonts',
            'Unkempt'                        => 'Google Fonts',
            'Unlock'                         => 'Google Fonts',
            'Unna'                           => 'Google Fonts',
            'VT323'                          => 'Google Fonts',
            'Vampiro One'                    => 'Google Fonts',
            'Varela'                         => 'Google Fonts',
            'Varela Round'                   => 'Google Fonts',
            'Vast Shadow'                    => 'Google Fonts',
            'Vesper Libre'                   => 'Google Fonts',
            'Vibur'                          => 'Google Fonts',
            'Vidaloka'                       => 'Google Fonts',
            'Viga'                           => 'Google Fonts',
            'Voces'                          => 'Google Fonts',
            'Volkhov'                        => 'Google Fonts',
            'Vollkorn'                       => 'Google Fonts',
            'Vollkorn SC'                    => 'Google Fonts',
            'Voltaire'                       => 'Google Fonts',
            'Waiting for the Sunrise'        => 'Google Fonts',
            'Wallpoet'                       => 'Google Fonts',
            'Walter Turncoat'                => 'Google Fonts',
            'Warnes'                         => 'Google Fonts',
            'Wellfleet'                      => 'Google Fonts',
            'Wendy One'                      => 'Google Fonts',
            'Wire One'                       => 'Google Fonts',
            'Work Sans'                      => 'Google Fonts',
            'Yanone Kaffeesatz'              => 'Google Fonts',
            'Yantramanav'                    => 'Google Fonts',
            'Yatra One'                      => 'Google Fonts',
            'Yellowtail'                     => 'Google Fonts',
            'Yeon Sung'                      => 'Google Fonts',
            'Yeseva One'                     => 'Google Fonts',
            'Yesteryear'                     => 'Google Fonts',
            'Yrsa'                           => 'Google Fonts',
            'Zeyada'                         => 'Google Fonts',
            'Zilla Slab'                     => 'Google Fonts',
            'Zilla Slab Highlight'           => 'Google Fonts',
        ];

    }//end get_font_list()

    /**
     * Register text domain for Chaty
     *
     * @since  1.0.0
     * @access public
     * @return $chaty_text
     */
    public function chaty_text()
    {
        load_plugin_textdomain("chaty", false, dirname(plugin_basename(__FILE__)).'/languages/');

    }//end chaty_text()


    /**
     * Update Chaty Status
     *
     * @since  1.0.0
     * @access public
     * @return $status
     */
    public function chaty_update_status()
    {
        $nonce = filter_input(INPUT_POST, 'nonce');
        if (!empty($nonce) && wp_verify_nonce($nonce, 'chaty_update_status')) {
            $status = filter_input(INPUT_POST, 'status');
            $email  = filter_input(INPUT_POST, 'email');
            update_option("chaty_update_message", 2);
            if ($status == 1) {
                $url = 'https://premioapps.com/premio/signup/email.php';
                $apiParams = [
                    'plugin' => 'chaty',
                    'email'  => $email,
                ];

                // Signup Email for Chaty
                $apiResponse = wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => true]);

                if (is_wp_error($apiResponse)) {
                    wp_safe_remote_post($url, ['body' => $apiParams, 'timeout' => 15, 'sslverify' => false]);
                }

                $response['status'] = 1;
            }
        }//end if

    }//end chaty_update_status()

    /**
     * Update Chaty Popup Status
     *
     * @since  1.0.0
     * @access public
     * @return $status
     */
    public function update_popup_status()
    {
        if (!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], 'chaty_update_popup_status')) {
            update_option("chaty_intro_popup", "hide");
        }

        echo esc_attr("1");
        die;

    }//end update_popup_status()

    /**
     * Update Chaty Channel Settings
     *
     * @since  1.0.0
     * @access public
     * @return $channels
     */
    public function update_channel_setting()
    {
        if (!empty($_REQUEST['nonce']) && wp_verify_nonce($_REQUEST['nonce'], "Contact_Us-settings")) {
            update_option("chaty_contact_us_setting", "hide");
        }

        echo esc_attr("1");
        die;

    }//end update_channel_setting()

    /**
     * Send Help Message to owner
     *
     * @since  1.0.0
     * @access public
     * @return $response
     */
    public function wcp_admin_send_message_to_owner()
    {
        $response            = [];
        $response['status']  = 0;
        $response['error']   = 0;
        $response['errors']  = [];
        $response['message'] = "";
        $errorArray          = [];
        $errorMessage        = esc_html__("%1\$s is required", 'chaty');

        $textareaText = filter_input(INPUT_POST, 'textarea_text');
        $userEmail    = filter_input(INPUT_POST, 'user_email');
        $nonce        = filter_input(INPUT_POST, 'nonce');

        if (empty($textareaText)) {
            $error        = [
                "key"     => "textarea_text",
                "message" => esc_attr__("Please enter your message", "wcp"),
            ];
            $errorArray[] = $error;
        }

        if (empty($userEmail)) {
            $error        = [
                "key"     => "user_email",
                "message" => sprintf($errorMessage, esc_attr__("Email", "wcp")),
            ];
            $errorArray[] = $error;
        } else if (!filter_var($userEmail, FILTER_VALIDATE_EMAIL)) {
            $error        = [
                'key'     => "user_email",
                "message" => "Email is not valid",
            ];
            $errorArray[] = $error;
        }

        if (empty($errorArray)) {
            if (empty($nonce)) {
                $error        = [
                    'key'     => "nonce",
                    "message" => "Your request is not valid",
                ];
                $errorArray[] = $error;
            } else if (!wp_verify_nonce($nonce, "chaty_send_message_to_owner")) {
                $error        = [
                    'key'     => "nonce",
                    "message" => "Your request is not valid",
                ];
                $errorArray[] = $error;
            }
        }

        if (empty($errorArray)) {
            global $current_user;
            $textMessage = $textareaText;
            $email       = $userEmail;
            $domain      = site_url();
            $user_name   = $current_user->first_name." ".$current_user->last_name;

            // sending message to Crisp
            $postMessage = [];

            $messageData          = [];
            $messageData['key']   = "Plugin";
            $messageData['value'] = "Chaty";
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "Domain";
            $messageData['value'] = $domain;
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "Email";
            $messageData['value'] = $email;
            $postMessage[]        = $messageData;

            $messageData          = [];
            $messageData['key']   = "Message";
            $messageData['value'] = $textMessage;
            $postMessage[]        = $messageData;

            $apiParams = [
                'domain'  => $domain,
                'email'   => $email,
                'url'     => site_url(),
                'name'    => $user_name,
                'message' => $postMessage,
                'plugin'  => "Chaty",
                'type'    => "Need Help",
            ];

            // Sending message to Crisp API
            $apiResponse = wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", ['body' => $apiParams, 'timeout' => 15, 'sslverify' => true]);

            if (is_wp_error($apiResponse)) {
                $apiResponse = wp_safe_remote_post("https://premioapps.com/premio/send-message-api.php", ['body' => $apiParams, 'timeout' => 15, 'sslverify' => false]);
            }

            $response['status'] = 1;
        } else {
            $response['error']  = 1;
            $response['errors'] = $errorArray;
        }//end if

        wp_send_json($response);
        wp_die();

    }//end wcp_admin_send_message_to_owner()

    /**
     * Cleans a string and returns only the numbers.
     *
     * @param string $string The string to be cleaned.
     * @return string The cleaned string with only numbers.
     */
    function cleanStringForNumbers($string) {
        if(!empty($string)) {
            $string = trim($string);
            $hasPlus = 0;
            if(isset($string[0]) && $string[0] == "+") {
                $hasPlus = 1;
            }
            $string = str_replace("-", "", $string);
            $string = preg_replace('/[^0-9\-]/', '', $string); // Removes special chars.
            if ($hasPlus) {
                return "+" . $string;
            }
        }
        return $string;
    }

    /**
     * Validates a color value and returns it if it matches the given formats.
     *
     * @param string $color The color value to validate.
     * @param string $default_color (optional) The default color value to return if the given color is invalid.
     * @return string The validated color value, or the default color value if the given color is invalid.
     */
    function validate_color($color, $default_color = "") {
        if( preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return $color;
        } else {
            // Check if it's a RGB color
            $rgbPattern = "/^rgb\((\d{1,3}), (\d{1,3}), (\d{1,3})\)$/";
            if(preg_match($rgbPattern, $color)) {
                return $color;
            }

            // Check if it's a RGBA color
            $rgbaPattern = "/^rgba\((\d{1,3}), (\d{1,3}), (\d{1,3}), (0(\.\d{1,2})?|1)\)$/";
            if(preg_match($rgbaPattern, $color)) {
                return $color;
            }
        }
        return $default_color;
    }


}//end class

new CHT_Admin_Base();

/**
 * Updates options and redirects to relevant admin page.
 *
 * @param mixed $old_value The old option value.
 * @param mixed $value The new option value to be updated to.
 *
 * @return void
 */
add_action('update_option_chaty_updated_on', function ($old_value, $value) {
    $show_first = get_option("show_first_chaty_box");
    if ($show_first === false) {
        add_option("show_first_chaty_box", 1);
    }

    delete_option("cht_is_chatway_added");
    add_option("cht_is_chatway_added", true);

    delete_option("cht_is_default_deleted");

    if ($old_value != $value) {
        $post_data = filter_input_array(INPUT_POST);
        $step      = isset($post_data['current_step']) && is_numeric($post_data['current_step']) ? $post_data['current_step'] : 1;
        if (!in_array($step, [0,1,2,3])) {
            $step = 0;
        }

        if (isset($post_data['save_button'])) {
            if (empty($widgetIndex)) {
                $widgetIndex = 0;
            }

            wp_safe_redirect(admin_url("admin.php?page=chaty-app&show_message=1&step=".$step."&widget=".$widgetIndex));
            exit;
        } else {
            $buttonType = isset($post_data['button_type'])?$post_data['button_type']:1;
            if($buttonType == 1) {
                wp_safe_redirect(admin_url("admin.php?page=chaty-app&show_message=1&step=".$step."&widget=0"));
                exit;
            }
        }

        wp_safe_redirect(admin_url("admin.php?page=chaty-app&show_message=1"));
        exit;
    }
},10,2);


