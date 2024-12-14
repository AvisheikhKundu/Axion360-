<?php

namespace Hostinger\EasyOnboarding\Admin;

use Hostinger\EasyOnboarding\Admin\Onboarding\Onboarding;

defined( 'ABSPATH' ) || exit;

class Actions {
	public const AI_STEP             = 'ai_step';
	public const STORE_TASKS         = 'store_tasks';
	public const SETUP_STORE         = 'setup_store';
	public const ADD_PRODUCT         = 'add_product';
	public const ADD_PAYMENT         = 'add_payment_method';
	public const ADD_SHIPPING        = 'add_shipping_method';
	public const DOMAIN_IS_CONNECTED = 'connect_domain';

    public const AMAZON_AFFILIATE    = 'amazon_affiliate';

	public const GOOGLE_KIT          = 'google_kit';
	public const ACTIONS_LIST        = array(
		self::DOMAIN_IS_CONNECTED,
	);

    public const STORE_ACTIONS_LIST        = array(
        self::SETUP_STORE,
        self::ADD_PRODUCT,
        self::ADD_PAYMENT,
        self::ADD_SHIPPING
    );

    public static function get_category_action_lists(): array {
        return array(
            Onboarding::HOSTINGER_EASY_ONBOARDING_WEBSITE_STEP_CATEGORY_ID => self::get_action_list(),
            Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID => self::get_store_action_list()
        );
    }

    public static function get_action_list(): array {
        $list = self::ACTIONS_LIST;

        if ( ! empty( Onboarding::get_first_step_data() ) ) {
            $list[] = self::AI_STEP;
        }

        if ( is_plugin_active( 'hostinger-affiliate-plugin/hostinger-affiliate-plugin.php' ) ) {
            $list[] = self::AMAZON_AFFILIATE;
        }

        if( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            $list[] = self::STORE_TASKS;
        }

        $list[] = self::GOOGLE_KIT;

        return $list;
    }

    public static function get_store_action_list(): array {
        return self::STORE_ACTIONS_LIST;
    }
}
