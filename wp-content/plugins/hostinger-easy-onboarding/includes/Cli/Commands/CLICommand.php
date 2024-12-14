<?php

namespace Hostinger\EasyOnboarding\Cli\Commands;

/**
 * Interface OnboardingStatusInterface
 *
 * This interface defines the methods for checking the status of Hostinger Easy Onboarding.
 */
interface CLICommand {
    /**
     * Defines the WP-CLI command for Hostinger onboarding status.
     *
     * Adds the 'hostinger onboarding' command to WP-CLI with a short and long description.
     *
     * @return void
     */
    public static function define_command(): void;

}
