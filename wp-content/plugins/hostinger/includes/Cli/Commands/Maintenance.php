<?php

namespace Hostinger\Cli\Commands;

use Hostinger\Admin\PluginSettings;
use WP_CLI;
use Hostinger\Settings;

defined( 'ABSPATH' ) || exit;

class Maintenance {

	public static function define_command(): void {
		if ( class_exists( '\WP_CLI' ) ) {
			WP_CLI::add_command(
				'hostinger',
				self::class,
				array(
					'shortdesc' => 'List of Hostinger commands.',
					'longdesc'  => 'Available Hostinger commands:' . "\n\n" .
								'  wp hostinger maintenance mode <0|1>' . "\n" .
								'  Manage the maintenance mode of the site. Use 1 to enable and 0 to disable maintenance mode.' . "\n\n" .
								'  wp hostinger maintenance status' . "\n" .
								'  Display the current maintenance mode status.' . "\n\n" .
								'## SUBCOMMANDS' . "\n\n" .
								'* mode <0|1>' . "\n" .
								': Enable (1) or disable (0) maintenance mode.' . "\n\n" .
								'* status' . "\n" .
								': Display the current maintenance mode status.' . "\n\n" .
								'## EXAMPLES' . "\n\n" .
								'  wp hostinger maintenance mode 1' . "\n" .
								'  Enables the maintenance mode.' . "\n\n" .
								'  wp hostinger maintenance mode 0' . "\n" .
								'  Disables the maintenance mode.' . "\n\n" .
								'  wp hostinger maintenance status' . "\n" .
								'  Returns whether maintenance mode is enabled or disabled.' . "\n",
				)
			);

			WP_CLI::add_command(
				'hostinger maintenance',
				self::class,
				array(
					'shortdesc' => 'Manage the maintenance mode of the site.',
					'longdesc'  => 'This command allows you to enable or disable maintenance mode for the site.' . "\n\n" .
								'## OPTIONS' . "\n\n" .
								'mode <0|1>' . "\n" .
								': Enable (1) or disable (0) maintenance mode.' . "\n\n" .
								'## EXAMPLES' . "\n\n" .
								'  wp hostinger maintenance mode 1' . "\n" .
								'  Enables the maintenance mode.' . "\n\n" .
								'  wp hostinger maintenance mode 0' . "\n" .
								'  Disables the maintenance mode.' . "\n\n" .
								'  wp hostinger maintenance status' . "\n" .
								'  Returns whether maintenance mode is enabled or disabled.' . "\n",
				)
			);
		}
	}

	/**
	 * Command allows enable/disable maintenance mode.
	 *
	 * @param array $args
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function mode( array $args ): void {
		if ( empty( $args ) ) {
			WP_CLI::error( 'Arguments cannot be empty. Use 0 or 1' );
		}

		$plugin_settings = new PluginSettings();
		$plugin_options  = $plugin_settings->get_plugin_settings();

		switch ( $args[0] ) {
			case '1':
				$plugin_options->set_maintenance_mode( true );
				WP_CLI::success( 'Maintenance mode ENABLED' );
				break;
			case '0':
				$plugin_options->set_maintenance_mode( false );
				WP_CLI::success( 'Maintenance mode DISABLED' );
				break;
			default:
				throw new \Exception( 'Invalid maintenance mode value' );
		}

		$plugin_settings->save_plugin_settings( $plugin_options );

        if ( has_action( 'litespeed_purge_all' ) ) {
            do_action( 'litespeed_purge_all' );
        }
	}

	/**
	 * Command return maintenance mode status.
	 *
	 * @return bool
	 */
	public function status(): bool {
		$plugin_settings = new PluginSettings();
		$plugin_options  = $plugin_settings->get_plugin_settings();

		if ( $plugin_options->get_maintenance_mode() ) {
			WP_CLI::success( 'Maintenance mode ENABLED' );
		} else {
			WP_CLI::success( 'Maintenance mode DISABLED' );
		}

		return (bool) $plugin_options->get_maintenance_mode();
	}
}
