<?php

namespace Hostinger\EasyOnboarding\Admin\Onboarding;

use Hostinger\EasyOnboarding\Admin\Actions;
use Hostinger\EasyOnboarding\Admin\Actions as Admin_Actions;
use Hostinger\EasyOnboarding\Admin\Onboarding\Steps\Button;
use Hostinger\EasyOnboarding\Admin\Onboarding\Steps\Step;
use Hostinger\EasyOnboarding\Admin\Onboarding\Steps\StepCategory;
use Hostinger\EasyOnboarding\AmplitudeEvents\Actions as AmplitudeActions;
use Hostinger\EasyOnboarding\AmplitudeEvents\Amplitude;
use Hostinger\EasyOnboarding\Helper;

defined( 'ABSPATH' ) || exit;

class Onboarding {
    private const HOSTINGER_ADD_DOMAIN_URL  = 'https://hpanel.hostinger.com/add-domain/';
    private const HOSTINGER_WEBSITES_URL    = 'https://hpanel.hostinger.com/websites';
    public const HOSTINGER_EASY_ONBOARDING_STEPS_OPTION_NAME    = 'hostinger_easy_onboarding_steps';
    public const HOSTINGER_EASY_ONBOARDING_WEBSITE_STEP_CATEGORY_ID   = 'website_setup';
    public const HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID   = 'online_store_setup';
    /**
     * @var Helper
     */
    private Helper $helper;

    /**
     * @var array
     */
    private array $step_categories = array();

    /**
     * @return void
     */
    public function init(): void {
        $this->helper = new Helper();

        $this->load_step_categories();
    }

    /**
     * @return void
     */
    private function load_step_categories(): void {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $website_step_category = new StepCategory(
            self::HOSTINGER_EASY_ONBOARDING_WEBSITE_STEP_CATEGORY_ID,
            __( 'Website setup', 'hostinger-easy-onboarding' )
        );

        $first_step_data = self::get_first_step_data();

        if ( ! empty( $first_step_data ) ) {
            $first_step = new Step( Actions::AI_STEP );

            $first_step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/ai_step.svg' );

            $first_step->set_title_completed( __( 'Started creating your site', 'hostinger-easy-onboarding' ) );

            if ( ! empty( $first_step_data['title'] ) ) {
                $first_step->set_title( $first_step_data['title'] );
            }

            if ( ! empty( $first_step_data['description'] ) ) {
                $first_step->set_description( $first_step_data['description'] );
            }

            if ( ! empty( $first_step_data['primary_button_title'] ) ) {
                $button = new Button( $first_step_data['primary_button_title'] );

                if ( ! empty( $first_step_data['primary_button_url'] ) ) {
                    $button->set_url( $first_step_data['primary_button_url'] );
                }

                $first_step->set_primary_button( $button );
            }

            if ( ! empty( $first_step_data['secondary_button_title'] ) ) {
                $button = new Button( $first_step_data['secondary_button_title'] );

                if ( ! empty( $first_step_data['secondary_button_url'] ) ) {
                    $button->set_url( $first_step_data['secondary_button_url'] );
                } else {
                    $button->set_is_skippable( true );
                }

                $first_step->set_secondary_button( $button );
            }

            $website_step_category->add_step( $first_step );
        }

        if ( is_plugin_active( 'hostinger-affiliate-plugin/hostinger-affiliate-plugin.php' ) ) {
            $website_step_category->add_step( $this->get_amazon_affiliate_step() );
        }

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            $website_step_category->add_step( $this->get_started_with_store() );
        }

        // Connect domain.
        $website_step_category->add_step( $this->get_add_domain_step() );

        $website_step_category->add_step( $this->get_google_kit_step() );

        // Add category.
        $this->step_categories[] = $website_step_category;

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            $store_step_category = new StepCategory(
                self::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID,
                __('Online store setup', 'hostinger-easy-onboarding')
            );

            // Setup online store.
            $store_step_category->add_step( $this->get_setup_online_store() );

            // Add product.
            $store_step_category->add_step( $this->get_add_product_step() );

            // Add payment method.
            $store_step_category->add_step( $this->get_payment_method_step() );

            // Add shipping method.
            $store_step_category->add_step( $this->get_shipping_method_step() );

            $this->step_categories[] = $store_step_category;
        }
    }

    /**
     * @return array
     */
    public function get_step_categories(): array {
        return array_map(
            function ( $item ) {
                return $item->to_array();
            },
            $this->step_categories
        );
    }

    /**
     * @param string $step_category_id
     * @param string $step_id
     *
     * @return bool
     */
    public function complete_step( string $step_category_id, string $step_id ): bool {
        if ( !$this->validate_step( $step_category_id, $step_id ) ) {
            return false;
        }

        $onboarding_steps = $this->get_saved_steps();

        if(empty($onboarding_steps[$step_category_id])) {
            $onboarding_steps[$step_category_id] = array();
        }

        $onboarding_steps[$step_category_id][$step_id] = true;

        $this->maybe_send_store_events( $onboarding_steps );

        return update_option( self::HOSTINGER_EASY_ONBOARDING_STEPS_OPTION_NAME, $onboarding_steps );
    }

    /**
     * @param string $step_category_id
     * @param string $step_id
     *
     * @return bool
     */
    public function validate_step( string $step_category_id, string $step_id ): bool {
        $step_categories = $this->get_step_categories();

        if(empty($step_categories)) {
            return false;
        }

        // Try to match step category id.
        $found = false;
        foreach($step_categories as $step_category) {
            if($step_category['id'] == $step_category_id) {
                if(!empty($step_category['steps'])) {
                    foreach($step_category['steps'] as $step) {
                        if($step['id'] == $step_id) {
                            $found = true;
                            break;
                        }
                    }
                }
                break;
            }
        }

        if(empty($found)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $step_category_id
     * @param string $step_id
     *
     * @return bool
     */
    public function is_completed( string $step_category_id, string $step_id ) : bool {
        $onboarding_steps = $this->get_saved_steps();

        if(empty($onboarding_steps[$step_category_id][$step_id])) {
            return false;
        }

        return (bool)$onboarding_steps[$step_category_id][$step_id];
    }

    /**
     * @return array
     */
    private function get_saved_steps(): array {
        return get_option( self::HOSTINGER_EASY_ONBOARDING_STEPS_OPTION_NAME, array() );
    }

    private function get_add_domain_step(): Step
    {
        $step = new Step( Actions::DOMAIN_IS_CONNECTED );

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/connect_domain.svg' );

        $step->set_title( __( 'Connect a domain', 'hostinger-easy-onboarding' ) );

        $button = new Button( __( 'Connect domain', 'hostinger-easy-onboarding' ) );

        if ( $this->helper->is_free_subdomain() || $this->helper->is_preview_domain() ) {
            $step->set_title_completed(__('Connect on hPanel', 'hostinger-easy-onboarding'));

            $step->set_description(
                __(
                    'Visit hPanel and connect a real domain. If you already did this, please wait up to 24h until the domain fully connects',
                    'hostinger-easy-onboarding'
                )
            );

            $site_url   = preg_replace( '#^https?://#', '', get_site_url() );
            $hpanel_url = self::HOSTINGER_WEBSITES_URL . '/' . $site_url;

            $button->set_title( __( 'Connect on hPanel', 'hostinger-easy-onboarding' ) );
            $button->set_url( $hpanel_url );

        } else {
            $step->set_title_completed(__('Connected a domain', 'hostinger-easy-onboarding'));

            $step->set_description(
                __(
                    'Every website needs a domain that makes it easy to access and remember. Get ours in just a few clicks.',
                    'hostinger-easy-onboarding'
                )
            );

            $site_url   = preg_replace( '#^https?://#', '', get_site_url() );
            $hpanel_url = self::HOSTINGER_ADD_DOMAIN_URL . $site_url . '/select';

            $query_parameters = array(
                'websiteType' => 'wordpress',
                'redirectUrl' => self::HOSTINGER_WEBSITES_URL,
            );

            $button->set_url( $hpanel_url . '?' . http_build_query( $query_parameters ) );
        }

        $step->set_primary_button( $button );

        return $step;
    }

    private function get_amazon_affiliate_step(): Step
    {
        $step = new Step(Actions::AMAZON_AFFILIATE);

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/amazon_affiliate.svg' );

        $step->set_title( __( 'Connect your Amazon account to the site', 'hostinger-easy-onboarding' ) );

        $step->set_title_completed( __( 'Connected your Amazon account', 'hostinger-easy-onboarding' ) );

        $step->set_description( __( 'Join the Amazon Affiliate Program to start earning commissions. Link your Amazon affiliate account to your website, start promoting products and earn rewards.', 'hostinger-easy-onboarding' ) );

        $button = new Button( __( 'Connect Amazon to site', 'hostinger-easy-onboarding' ) );

        $button->set_url( admin_url( 'admin.php?page=hostinger-amazon-affiliate' ) );

        $step->set_primary_button( $button );

        return $step;
    }

    private function get_started_with_store(): Step
    {
        $step = new Step( Actions::STORE_TASKS );

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/store_tasks.svg' );

        $step->set_title( __( 'Set up your online store', 'hostinger-easy-onboarding' ) );

        $step->set_description( __( 'Get ready to sell online. Add your first product, then set up shipping and payments.', 'hostinger-easy-onboarding' ) );

        $primary_button = new Button( __( 'Get started', 'hostinger-easy-onboarding' ) );

        $primary_button->set_url( admin_url( 'admin.php?page=hostinger-get-onboarding&subPage=woo-commerce-online-store-setup' ) );

        $primary_button->set_title_completed( __( 'View list', 'hostinger-easy-onboarding' ) );

        $step->set_primary_button( $primary_button );

        return $step;
    }

    private function get_setup_online_store(): Step
    {
        $step = new Step( Actions::SETUP_STORE );

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/store_tasks.svg' );

        $step->set_title( __( 'Store info', 'hostinger-easy-onboarding' ) );

        $step->set_description( __( 'We\'ll use this information to help you set up your store faster.', 'hostinger-easy-onboarding' ) );

        $primary_button = new Button( __( 'View Details', 'hostinger-easy-onboarding' ) );

        $primary_button->set_url( admin_url( 'admin.php?page=wc-settings' ) );

        $step->set_primary_button( $primary_button );

        return $step;
    }

    private function get_add_product_step(): Step
    {
        $step = new Step( Actions::ADD_PRODUCT );

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/add_product.svg' );

        $step->set_title( __( 'Add your first product or service', 'hostinger-easy-onboarding' ) );

        $step->set_description( __( 'Sell products, services and digital downloads. Set up and customize each item to fit your business needs.', 'hostinger-easy-onboarding' ) );

        $primary_button = new Button( __( 'Add product', 'hostinger-easy-onboarding' ) );

        $primary_button->set_url( admin_url( 'post-new.php?post_type=product' ) );

        $step->set_primary_button( $primary_button );

        $secondary_button = new Button( __( 'Not interested', 'hostinger-easy-onboarding' ) );

        $secondary_button->set_is_skippable( true );

        $step->set_secondary_button( $secondary_button );

        return $step;
    }

    private function get_payment_method_step(): Step
    {
        $step = new Step( Actions::ADD_PAYMENT );

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/add_payment_method.svg' );

        $step->set_title( __( 'Set up a payment method', 'hostinger-easy-onboarding' ) );

        $step->set_description( __( 'Get ready to accept customer payments. Let them pay for your products and services with ease.', 'hostinger-easy-onboarding' ) );

        $primary_button = new Button( __( 'Set up payment method', 'hostinger-easy-onboarding' ) );

        $primary_button->set_url( admin_url( 'admin.php?page=hostinger-get-onboarding&subPage=hostinger-store-add-payment-method' ) );

        $step->set_primary_button( $primary_button );

        return $step;
    }

    private function get_shipping_method_step(): Step
    {
        $step = new Step( Actions::ADD_SHIPPING );

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/add_shipping_method.svg' );

        $step->set_title( __( 'Manage shipping', 'hostinger-easy-onboarding' ) );

        $step->set_description( __( 'Choose the ways you\'d like to ship orders to customers. You can always add others later.', 'hostinger-easy-onboarding' ) );

        $primary_button = new Button( __( 'Shipping methods', 'hostinger-easy-onboarding' ) );

        $primary_button->set_url( admin_url( 'admin.php?page=hostinger-get-onboarding&subPage=hostinger-store-add-shipping-method' ) );

        $step->set_primary_button( $primary_button );

        $secondary_button = new Button( __( 'Not needed', 'hostinger-easy-onboarding' ) );

        $secondary_button->set_is_skippable( true );

        $step->set_secondary_button( $secondary_button );

        return $step;
    }

    private function get_google_kit_step(): Step
    {
        $step = new Step( Admin_Actions::GOOGLE_KIT );

        $step->set_image_url( HOSTINGER_EASY_ONBOARDING_ASSETS_URL . '/images/steps/google_kit.svg' );

        $step->set_title( __( 'Get found on Google', 'hostinger-easy-onboarding' ) );

        $step->set_description( __( 'Make sure that your website shows up when visitors are looking for your business on Google.', 'hostinger-easy-onboarding' ) );

        $primary_button = new Button( __( 'Set up Google Site Kit', 'hostinger-easy-onboarding' ) );

        $primary_button->set_url( admin_url( 'admin.php?page=googlesitekit-splash' ) );

        $primary_button->set_title_completed( __( 'Manage', 'hostinger-easy-onboarding' ));

        $step->set_primary_button( $primary_button );

        $secondary_button = new Button( __( 'Not needed', 'hostinger-easy-onboarding' ) );

        $secondary_button->set_is_skippable( true );

        $step->set_secondary_button( $secondary_button );

        return $step;
    }

    public function maybe_send_store_events( array $steps ) : void {
        if ( $this->is_store_ready( $steps ) ) {
            $this->send_event( AmplitudeActions::WOO_READY_TO_SELL, true );
        }

        if ( $this->is_store_completed( $steps ) ) {
            $this->send_event( AmplitudeActions::WOO_SETUP_COMPLETED, true );
        }
    }

    private function is_store_ready( array $steps ): bool {
        $store_steps = $steps[Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID] ?? array();
        return !empty( $store_steps[Admin_Actions::ADD_PAYMENT] ) && !empty( $store_steps[Admin_Actions::ADD_PRODUCT] );
    }

    private function is_store_completed( $steps ): bool {
        $all_woo_steps = Admin_Actions::get_category_action_lists()[ Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID ];
        $completed_woo_steps = !empty($steps[Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID]) ? $steps[Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID] : array();

        foreach ( $all_woo_steps as $step_key ) {
            if ( empty( $completed_woo_steps[ $step_key ] ) ) {
                return false;
            }
        }

        return true;
    }

    private function send_event( string $action, bool $once = false ): bool {
        if ( $once ) {
            $option_name = 'hostinger_amplitude_' . $action;

            $event_sent = get_option( $option_name, false );

            if ( $event_sent ) {
                return false;
            }
        }

        $amplitude = new Amplitude();

        $params = array( 'action' => $action );

        $event = $amplitude->send_event( $params );

        if( $once ) {
            update_option( $option_name, true );
        }

        return !empty( $event );
    }

    public static function get_first_step_data(): array
    {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';

        $result = array();

        if ( \is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            return $result;
        }

        if ( get_option( 'template' ) == 'hostinger-ai-theme' ) {
            $hostinger_ai_version = get_option( 'hostinger_ai_version', false );

            if ( empty( $hostinger_ai_version ) ) {
                $result['title'] = __( 'Create a site with AI', 'hostinger-easy-onboarding' );
                $result['description'] = __( 'Build a professional, custom-designed site in moments. Just a few clicks and AI handles the rest.', 'hostinger-easy-onboarding' );
                $result['primary_button_title'] = __( 'Create site with AI', 'hostinger-easy-onboarding' );
                $result['primary_button_url'] = admin_url( 'admin.php?page=hostinger-ai-website-creation&redirect=hostinger-easy-onboarding' );
                $result['secondary_button_title'] = __( 'Not now', 'hostinger-easy-onboarding' );
            } else {
                $result['title'] = __( 'Want to create a new AI site?', 'hostinger-easy-onboarding' );
                $result['description'] = __( 'Your new site will replace the current one. Use the same description or change it.', 'hostinger-easy-onboarding' );
                $result['primary_button_title'] = __( 'Keep current site', 'hostinger-easy-onboarding' );
                $result['secondary_button_title'] = __( 'Create new site', 'hostinger-easy-onboarding' );
                $result['secondary_button_url'] = admin_url( 'admin.php?page=hostinger-ai-website-creation&redirect=hostinger-easy-onboarding' );
            }

            return $result;
        }

        $whitelist_plans = array(
            'business_economy',
            'business_enterprise',
            'business_professional',
            'cloud_economy',
            'cloud_enterprise',
            'cloud_professional',
            'gcp_business_8',
            'hostinger_business',
        );

        $hosting_plan = get_option( 'hostinger_hosting_plan', false );

        if ( !empty( $hosting_plan ) && in_array( $hosting_plan, $whitelist_plans ) ) {
            $result['title'] = __( 'Create content with AI', 'hostinger-easy-onboarding' );
            $result['description'] = __( 'Build a professional, custom-designed site in moments. Just a few clicks and AI handles the rest.', 'hostinger-easy-onboarding' );
            $result['primary_button_title'] = __( 'Generate post', 'hostinger-easy-onboarding' );
            $result['primary_button_url'] = admin_url( 'admin.php?page=hostinger-ai-assistant' );
            $result['secondary_button_title'] = __( 'Not now', 'hostinger-easy-onboarding' );
        }

        return $result;
    }
}
