<?php

namespace Hostinger\EasyOnboarding;

use Hostinger\WpHelper\Utils;
use Hostinger\WpMenuManager\Menus;
use Hostinger\EasyOnboarding\Admin\Actions;
use Hostinger\EasyOnboarding\Admin\Onboarding\Onboarding;

defined('ABSPATH') || exit;

class Helper
{
	public const HOSTINGER_FREE_SUBDOMAIN_URL = 'hostingersite.com';
	public const HOSTINGER_DEV_FREE_SUBDOMAIN_URL = 'hostingersite.dev';
	public const HOSTINGER_PAGE = '/wp-admin/admin.php?page=hostinger';
	public const CLIENT_WOO_COMPLETED_ACTIONS = 'woocommerce_task_list_tracked_completed_tasks';
	private const PROMOTIONAL_LINKS = array(
		'fr_FR' => 'https://www.hostinger.fr/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'es_ES' => 'https://www.hostinger.es/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'ar'    => 'https://www.hostinger.ae/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'zh_CN' => 'https://www.hostinger.com.hk/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'id_ID' => 'https://www.hostinger.co.id/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'lt_LT' => 'https://www.hostinger.lt/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'pt_PT' => 'https://www.hostinger.pt/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'uk'    => 'https://www.hostinger.com.ua/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'tr_TR' => 'https://www.hostinger.com.tr/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
		'en_US' => 'https://www.hostinger.com/cpanel-login?r=%2Fjump-to%2Fnew-panel%2Fsection%2Freferrals&utm_source=Banner&utm_medium=HostingerWPplugin',
	);
    const HOSTINGER_LOCALES = [
        'lt_LT' => 'hostinger.lt',
        'uk_UA' => 'hostinger.com.ua',
        'id_ID' => 'hostinger.co.id',
        'en_US' => 'hostinger.com',
        'es_ES' => 'hostinger.es',
        'es_AR' => 'hostinger.com.ar',
        'es_MX' => 'hostinger.mx',
        'es_CO' => 'hostinger.co',
        'pt_BR' => 'hostinger.com.br',
        'ro_RO' => 'hostinger.ro',
        'fr_FR' => 'hostinger.fr',
        'it_IT' => 'hostinger.it',
        'pl_PL' => 'hostinger.pl',
        'en_PH' => 'hostinger.ph',
        'ar_AE' => 'hostinger.ae',
        'ms_MY' => 'hostinger.my',
        'ko_KR' => 'hostinger.kr',
        'vi_VN' => 'hostinger.vn',
        'th_TH' => 'hostinger.in.th',
        'tr_TR' => 'hostinger.web.tr',
        'pt_PT' => 'hostinger.pt',
        'de_DE' => 'hostinger.de',
        'en_IN' => 'hostinger.in',
        'ja_JP' => 'hostinger.jp',
        'nl_NL' => 'hostinger.nl',
        'en_GB' => 'hostinger.co.uk',
        'el_GR' => 'hostinger.gr',
        'cs_CZ' => 'hostinger.cz',
        'hu_HU' => 'hostinger.hu',
        'sv_SE' => 'hostinger.se',
        'da_DK' => 'hostinger.dk',
        'fi_FI' => 'hostinger.fi',
        'sk_SK' => 'hostinger.sk',
        'no_NO' => 'hostinger.no',
        'hr_HR' => 'hostinger.hr',
        'zh_HK' => 'hostinger.com.hk',
        'he_IL' => 'hostinger.co.il',
        'lv_LV' => 'hostinger.lv',
        'et_EE' => 'hostinger.ee',
        'ur_PK' => 'hostinger.pk',
    ];

	private const HPANEL_DOMAIN_URL = 'https://hpanel.hostinger.com/websites/';

	/**
	 *
	 * Check if plugin is active
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function is_plugin_active($plugin_slug): bool
	{
		$active_plugins = (array) get_option('active_plugins', array());
		foreach ($active_plugins as $active_plugin) {
			if (strpos($active_plugin, $plugin_slug . '.php') !== false) {
				return true;
			}
		}

		return false;
	}

	public static function get_api_token(): string
	{
		$api_token  = '';
		$token_file = HOSTINGER_EASY_ONBOARDING_WP_TOKEN;

		if (file_exists($token_file) && ! empty(file_get_contents($token_file))) {
			$api_token = file_get_contents($token_file);
		}

		return $api_token;
	}

	/**
	 *
	 * Get the host info (domain, subdomain, subdirectory)
	 *
	 * @since    1.7.0
	 * @access   public
	 */
	public function get_host_info(): string
	{
		$host     = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field($_SERVER['HTTP_HOST']) : '';
		$site_url = get_site_url();
		$site_url = preg_replace('#^https?://#', '', $site_url);

		if (! empty($site_url) && ! empty($host) && strpos($site_url, $host) === 0) {
			if ($site_url === $host) {
				return $host;
			} else {
				return substr($site_url, strlen($host) + 1);
			}
		}

		return $host;
	}

	public function is_preview_domain(): bool
	{
		if (function_exists('getallheaders')) {
			$headers = getallheaders();
		}

		if (isset($headers['X-Preview-Indicator']) && $headers['X-Preview-Indicator']) {
			return true;
		}

		return false;
	}

	public function is_free_subdomain(): bool
	{
		$site_url = preg_replace('#^https?://#', '', get_site_url());

		return ! empty($site_url) && (strpos($site_url, self::HOSTINGER_FREE_SUBDOMAIN_URL) !== false || strpos($site_url, self::HOSTINGER_DEV_FREE_SUBDOMAIN_URL));
	}

	public function is_hostinger_admin_page(): bool
	{

		if (! isset($_SERVER['REQUEST_URI'])) {
			return false;
		}

		$current_uri = sanitize_text_field($_SERVER['REQUEST_URI']);

		if (defined('DOING_AJAX') && \DOING_AJAX) {
			return false;
		}

		if (isset($current_uri) && strpos($current_uri, '/wp-json/') !== false) {
			return false;
		}

		if (strpos($current_uri, self::HOSTINGER_PAGE) !== false) {
			return true;
		}

		return false;
	}

	/**
	 *
	 * Error log
	 *
	 * @since    1.9.6
	 * @access   public
	 */
	public function error_log(string $message): void
	{
		if (defined('WP_DEBUG') && \WP_DEBUG === true) {
			error_log(print_r($message, true));
		}
	}

	public function default_woocommerce_survey_steps_completed(array $steps): bool
	{
		$completed_actions = get_option(self::CLIENT_WOO_COMPLETED_ACTIONS, array());

		return empty(array_diff($steps, $completed_actions));
	}

	public function is_this_page(string $page): bool
	{

		if (! isset($_SERVER['REQUEST_URI'])) {
			return false;
		}

		$current_uri = sanitize_text_field($_SERVER['REQUEST_URI']);

		if (defined('DOING_AJAX') && \DOING_AJAX) {
			return false;
		}

		if (isset($current_uri) && strpos($current_uri, '/wp-json/') !== false) {
			return false;
		}

		if (strpos($current_uri, $page) !== false) {
			return true;
		}

		return false;
	}

	public function get_promotional_link_url(string $locale): string
	{
		if (isset(self::PROMOTIONAL_LINKS[$locale])) {
			return self::PROMOTIONAL_LINKS[$locale];
		}

		return self::PROMOTIONAL_LINKS['en_US'];
	}

	public function get_hpanel_domain_url(): string
	{
		$parsed_url = parse_url(get_site_url());
		$host       = $parsed_url['host'];
		$host_parts = explode('.', $host);
		$subdomain  = (count($host_parts) > 2) ? array_shift($host_parts) . '.' : '';
		$domain     = implode('.', $host_parts);

		return self::HPANEL_DOMAIN_URL . $domain . ($subdomain ? "/wordpress/dashboard/$subdomain$domain" : '');
	}

	public function check_transient_eligibility($transient_request_key, $cache_time = 3600): bool
	{
		try {
			// Set transient
			set_transient($transient_request_key, true, $cache_time);

			// Check if transient was set successfully
			if (false === get_transient($transient_request_key)) {
				throw new \Exception('Unable to create transient in WordPress.');
			}

			// If everything is fine, return true
			return true;
		} catch (\Exception $exception) {
			// If there's an exception, log the error and return false
			$this->error_log('Error checking eligibility: ' . $exception->getMessage());

			return false;
		}
	}

	public static function woocommerce_onboarding_choice(): bool
	{
		return (bool) get_option('hostinger_woo_onboarding_choice', false);
	}

	/**
	 * @return bool
	 */
	public static function is_woocommerce_site(): bool
	{
		return class_exists('WooCommerce');
	}

	/**
	 * @return bool
	 */
	public static function show_woocommerce_onboarding(): bool
	{
		$woo_onboarding_enabled     = get_option('hostinger_woo_onboarding_enabled', false);
		$woo_setup_wizard_completed = get_option('woocommerce_onboarding_profile', false);

		return (self::is_woocommerce_site() && ! self::woocommerce_onboarding_choice() && $woo_onboarding_enabled && ! $woo_setup_wizard_completed);
	}

	/**
	 * @return bool
	 */
	public function can_show_store_ready_message(): bool
	{
		if (! self::is_woocommerce_site() || ! self::woocommerce_onboarding_choice()) {
			return false;
		}

		$store_ready_message_shown = get_option('hostinger_woo_ready_message_shown', null);

		if ($store_ready_message_shown === null) {
			return false;
		}

		if ((int) $store_ready_message_shown !== 0) {
			return false;
		}

		if (! $this->default_woocommerce_survey_completed()) {
			return false;
		}

		return true;
	}

	public function default_woocommerce_survey_completed(): bool
	{
		$completed_actions          = get_option(self::CLIENT_WOO_COMPLETED_ACTIONS, array());
		$required_completed_actions = array('products', 'payments');

		return empty(array_diff($required_completed_actions, $completed_actions));
	}

	public function is_hostinger_menu_page(): bool
	{
		$pages = [
			'wp-admin/admin.php?page=' . Menus::MENU_SLUG
		];

		$subpages = Menus::getMenuSubpages();

		foreach ($subpages as $page) {
			if (isset($page['menu_slug'])) {
				$pages[] = 'wp-admin/admin.php?page=' . $page['menu_slug'];
			}
		}

		$utils = new Utils();

		foreach ($pages as $page) {
			if ($utils->isThisPage($page)) {
				return true;
			}
		}

		return false;
	}

	/**
	 * @param string $plugin_slug
	 *
	 * @return string | \WP_Error
	 */
	public function get_plugin_main_file(string $plugin_slug): string|\WP_Error
	{
		$plugin_dir = WP_PLUGIN_DIR . '/' . $plugin_slug;
		if (! is_dir($plugin_dir)) {
			return new \WP_Error('plugin_not_found', __('Plugin directory not found', 'hostinger-easy-onboarding'));
		}

		$plugin_files = glob($plugin_dir . '/*.php');
		if (empty($plugin_files)) {
			return new \WP_Error('plugin_file_not_found', __('No PHP files found in plugin directory', 'hostinger-easy-onboarding'));
		}

		foreach ($plugin_files as $plugin_file) {
			$plugin_data = get_plugin_data($plugin_file, false, false);
			if (! empty($plugin_data['Name'])) {
				return $plugin_slug . '/' . basename($plugin_file);
			}
		}

		return new \WP_Error('plugin_main_file_not_found', __('Plugin main file not found', 'hostinger-easy-onboarding'));
	}

	public function is_woocommerce_store_ready(): bool
	{
		$store_steps = Actions::get_category_action_lists()[Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID] ?? array();

		$onboarding = new Onboarding();

		if (
			! $onboarding->is_completed(Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID, Actions::ADD_PAYMENT) ||
			! $onboarding->is_completed(Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID, Actions::ADD_PRODUCT)
		) {
			return false;
		}

		return true;
	}

	public function is_woocommerce_onboarding_completed(): bool
	{
		$all_woo_steps = Actions::get_category_action_lists()[Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID];
		$onboarding = new Onboarding();

		foreach ($all_woo_steps as $step) {
			if (! $onboarding->is_completed(Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID, $step)) {
				return false;
			}
		}

		return true;
	}

	public static function should_skip_event(): bool
	{
		return (defined('DOING_AUTOSAVE') && \DOING_AUTOSAVE) ||
			(defined('WP_CLI') && \WP_CLI) ||
			(defined('DOING_AJAX') && \DOING_AJAX) ||
			(defined('DOING_CRON') && \DOING_CRON);
	}

    public function is_woocommerce_payments_ready(): bool
    {
        $onboarding = new Onboarding();

        if ( $onboarding->is_completed(Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID, Actions::ADD_PAYMENT) ) {
            return true;
        }

        return false;
    }

    public function is_website_onboarding_completed(): bool {
        $all_steps = Actions::get_category_action_lists()[ Onboarding::HOSTINGER_EASY_ONBOARDING_WEBSITE_STEP_CATEGORY_ID ];
        $onboarding = new Onboarding();

        foreach ($all_steps as $step) {
            if ( ! $onboarding->is_completed(Onboarding::HOSTINGER_EASY_ONBOARDING_WEBSITE_STEP_CATEGORY_ID, $step)) {
                return false;
            }
        }

        return true;
    }

    public function getHostingerPluginUrl() : string {
        $websiteLocale  = get_locale() ?? 'en_US';
        $resellerLocale = get_option( 'hostinger_reseller', '' );
        $baseDomain     = $resellerLocale ? : ( self::HOSTINGER_LOCALES[$websiteLocale] ?? 'hostinger.com' );

        $pluginUrl = rtrim( $baseDomain, '/' ) . '/';
        $pluginUrl .= str_replace( ABSPATH, '', HOSTINGER_EASY_ONBOARDING_ABSPATH );

        return $pluginUrl;
    }
}

$hostinger_helper = new Helper();
$hostinger_helper->is_woocommerce_onboarding_completed();
