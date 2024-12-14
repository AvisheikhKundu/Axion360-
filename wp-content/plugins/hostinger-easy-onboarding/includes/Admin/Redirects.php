<?php

namespace Hostinger\EasyOnboarding\Admin;

use Hostinger\EasyOnboarding\Settings;

defined('ABSPATH') || exit;

class Redirects
{
    private string $platform;
    public const PLATFORM_HPANEL = 'hpanel';
    public const BUILDER_TYPE = 'prebuilt';
    public const HOMEPAGE_DISPLAY = 'page';

    public function __construct()
    {
        if ( ! Settings::get_setting('first_login_at')) {
            Settings::update_setting('first_login_at', gmdate('Y-m-d H:i:s'));
        }

        if (isset($_GET['platform'])) {
            $this->platform = sanitize_text_field($_GET['platform']);

            if ($this->platform === self::PLATFORM_HPANEL) {
                $this->loginRedirect();
            }
        }

    }

    private function loginRedirect(): void
    {
        $isPrebuildWebsite = get_option('hostinger_builder_type', '') === self::BUILDER_TYPE;
        $isWoocommercePage = in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
        $homepageId        = get_option('show_on_front') === self::HOMEPAGE_DISPLAY ? get_option('page_on_front') : null;
        $isGutenbergPage   = $homepageId ? has_blocks(get_post($homepageId)->post_content) : false;

        add_action('init', function () use ($isPrebuildWebsite, $isWoocommercePage, $homepageId, $isGutenbergPage) {
            if ($isPrebuildWebsite && ! $isWoocommercePage && $homepageId && $isGutenbergPage) {
                // Redirect to the Gutenberg editor for the homepage
                $redirectUrl = get_edit_post_link($homepageId, '');
            } else {
                $redirectUrl = admin_url('admin.php?page=hostinger');
            }

            wp_safe_redirect($redirectUrl);
            exit;
        });
    }
}
