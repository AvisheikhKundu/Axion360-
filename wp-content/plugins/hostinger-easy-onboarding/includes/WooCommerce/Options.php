<?php

namespace Hostinger\EasyOnboarding\WooCommerce;

defined( 'ABSPATH' ) || exit;

class Options {
    /**
     * Hides Setup task list in WooCommerce
     *
     * @return void
     */
    public function hideSetupTaskList(): void {
        $hidden_tasks = get_option( 'woocommerce_task_list_hidden_lists', false );

        if($hidden_tasks === false) {
            update_option( 'woocommerce_task_list_hidden_lists', array( 'setup' ) );
        }
    }

    /**
     * Disables automatic creation of shipping zones
     *
     * @return void
     */
    public function disableWooCommerceShipingZoneCreation(): void {
        update_option( 'woocommerce_admin_created_default_shipping_zones', 'yes' );
        update_option( 'woocommerce_admin_reviewed_default_shipping_zones', 'yes' );
    }

    /**
     * Skips WooCommerce native onboarding
     *
     * @return void
     */
    public function skipOnboarding(): void {
        $onboarding_profile = get_option( 'woocommerce_onboarding_profile', false );

        if($onboarding_profile === false) {
            update_option( 'woocommerce_onboarding_profile', array( 'skipped' => true ) );
        }
    }
}
