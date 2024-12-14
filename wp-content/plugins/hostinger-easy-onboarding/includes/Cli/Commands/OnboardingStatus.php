<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace Hostinger\EasyOnboarding\Cli\Commands;

use Hostinger\EasyOnboarding\Helper;
use WP_CLI;

defined( 'ABSPATH' ) || exit;

/**
 * Class OnboardingStatus
 *
 * This class defines the WP-CLI command for checking the status of Hostinger Easy Onboarding.
 */
class OnboardingStatus implements CLICommand {
    /**
     * Defines the WP-CLI command for Hostinger onboarding status.
     *
     * Adds the 'hostinger onboarding' command to WP-CLI with a short and long description.
     *
     * @return void
     */
    public static function define_command(): void {
        if ( class_exists( '\WP_CLI' ) ) {
            WP_CLI::add_command(
                'hostinger onboarding',
                self::class,
                [
                    'shortdesc' => 'Check the status of Hostinger Easy Onboarding',
                    'longdesc'  => 'This command allows you to check the status of Hostinger Easy Onboarding Progress for the WooCommerce store.' . "\n\n" .
                        '## EXAMPLES' . "\n\n" .
                        '  wp hostinger onboarding woocommerce_status' . "\n" .
                        '  Returns whether Hostinger Easy Onboarding for WooCommerce Store setup is completed or is ready to sell in JSON.',
                ]
            );
        }
    }

    public function woocommerce_status(): void
    {
        $helper = new Helper();

        $onboarding = [
            'woocommerce_onboarding_ready_to_sell' => $helper->is_woocommerce_store_ready(),
            'woocommerce_onboarding_status' => $helper->is_woocommerce_onboarding_completed(),
        ];

        WP_CLI::line(wp_json_encode($onboarding));
    }
}
