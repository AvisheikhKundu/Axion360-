<?php

namespace Hostinger;

defined( 'ABSPATH' ) || exit;

class Activator {
	public const INSTALLATION_OPTION_NAME = 'hts_new_installation';
	/**
	 * @return void
	 */
	public static function activate(): void {
		$options = new DefaultOptions();
		$options->add_options();

		self::update_installation_state_on_activation();
	}

	/**
	 * Saves installation state.
	 *
	 * @return void
	 */
	public static function update_installation_state_on_activation(): void {
		$installation_state = get_option( self::INSTALLATION_OPTION_NAME, false );

		if ( $installation_state !== 'old' ) {
			add_option( self::INSTALLATION_OPTION_NAME, 'new' );
		}
	}
}
