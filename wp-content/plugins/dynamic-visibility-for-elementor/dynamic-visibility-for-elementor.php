<?php
/**
 *
 * Plugin Name: Dynamic Visibility for Elementor
 * Description: Visibility rules for widgets, containers, sections, columns or pages with advanced conditions and removing the element from the DOM.
 * Plugin URI: https://www.dynamic.ooo/widget/dynamic-visibility/?utm_source=wp-plugins&utm_campaign=plugin-uri&utm_medium=wp-dash
 * Version: 5.0.15
 * Author: Dynamic.ooo
 * Author URI: https://www.dynamic.ooo/
 * Text Domain: dynamic-visibility-for-elementor
 * Requires at least: 5.2
 * Requires PHP: 5.6
 * License: GPL-3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Elementor tested up to: 3.25.5
 * Elementor Pro tested up to: 3.25.2
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'DVE_URL', plugins_url( '/', __FILE__ ) );
define( 'DVE_PATH', plugin_dir_path( __FILE__ ) );
define( 'DVE_PLUGIN_BASE', plugin_basename( __FILE__ ) );
define( 'DVE__FILE__', __FILE__ );

require_once __DIR__ . '/constants.php';

/**
 * Load the plugin after Elementor (and other plugins) are loaded.
 */
function dynamic_visibility_for_elementor_load() {
	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'dve_fail_load' );
		return;
	}

	// Check required version
	if ( ! version_compare( ELEMENTOR_VERSION, DVE_MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
		add_action( 'admin_notices', 'dve_admin_notice_minimum_elementor_version' );
		return;
	}

	// Don't load it if Dynamic.ooo - Dynamic Content for Elementor is installed
	if ( defined( 'DCE_VERSION' ) ) {
		return;
	}

	require_once DVE_PATH . '/core/plugin.php';
}

require_once DVE_PATH . 'vendor/autoload.php';
add_action( 'plugins_loaded', 'dynamic_visibility_for_elementor_load' );

function dve_admin_notice_minimum_elementor_version() {
	$msg = sprintf( __( '%1$s requires Elementor version %2$s or greater.', 'dynamic-visibility-for-elementor' ), DVE_PRODUCT_NAME, DVE_MINIMUM_ELEMENTOR_VERSION );
	\DynamicVisibilityForElementor\AdminPages\Notices::render_notice( $msg, 'error' );
}

function dve_fail_load() {
	$msg = sprintf( __( '%1$sElementor%2$s is required for the %1$s%3$s%2$s plugin to work.', 'dynamic-visibility-for-elementor' ), '<strong>', '</strong>', DVE_PRODUCT_NAME );
	\DynamicVisibilityForElementor\AdminPages\Notices::render_notice( $msg, 'error' );
}
