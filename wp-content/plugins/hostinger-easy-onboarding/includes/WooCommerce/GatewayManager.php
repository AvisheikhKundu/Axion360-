<?php

namespace Hostinger\EasyOnboarding\WooCommerce;

use WC_Payment_Gateways;
use WC_Payment_Gateway;

defined( 'ABSPATH' ) || exit;

class GatewayManager {
    public const STRIPE_DYNAMIC_GATEWAY_ID = 'cpsw_stripe_element';
    /**
     * @var WC_Payment_Gateways
     */
    private WC_Payment_Gateways $payment_gateways;

    /**
     * @param WC_Payment_Gateways $payment_gateways
     */
    public function __construct( WC_Payment_Gateways $payment_gateways ) {
        $this->payment_gateways = $payment_gateways;
    }

    /**
     * @return bool
     */
    public function isAnyGatewayActive(): bool {
        $payment_gateways = $this->payment_gateways->payment_gateways();

        if ( empty( $payment_gateways ) ) {
            return false;
        }

        foreach ( $payment_gateways as $gateway ) {
            // Does not have enabled property.
            if( $gateway->id === self::STRIPE_DYNAMIC_GATEWAY_ID ) {
                continue;
            }

            if ( isset( $gateway->settings['enabled'] ) && ( $gateway->settings['enabled'] === 'yes' ) ) {
                return true;
            }

            if ( $gateway->enabled === 'yes' ) {
                return true;
            }
        }

        return false;
    }
}
