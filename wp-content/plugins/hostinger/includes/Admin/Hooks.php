<?php

namespace Hostinger\Admin;

use Hostinger\Helper;
use Hostinger\WpHelper\Utils;

defined( 'ABSPATH' ) || exit;

class Hooks {
	/**
	 * @var Helper
	 */
	private Helper $helper;

	/**
	 * @var Utils
	 */
	private Utils $utils;

	public function __construct( $utils ) {
		$this->helper = new Helper();
		$this->utils  = $utils ?? new Utils();
		add_action( 'admin_footer', array( $this, 'rate_plugin' ) );
		add_action( 'admin_init', array( $this, 'message_about_plugin_split' ) );
        add_filter( 'wp_kses_allowed_html', array( $this, 'custom_kses_allowed_html' ), 10, 1 );
	}

	/**
	 * @return void
	 */
	public function rate_plugin(): void {
		if ( ! $this->utils->isThisPage( 'wp-admin/admin.php?page=' . Menu::MENU_SLUG ) ) {
			return;
		}

		require_once HOSTINGER_ABSPATH . 'includes/Admin/Views/Partials/RateUs.php';
	}

	public function message_about_plugin_split(): void {
		if ( $this->helper->should_plugin_split_notice_shown() ) {
            add_action( 'admin_notices', array( $this, 'custom_admin_notice' ) );
		}
	}

	public function custom_admin_notice() {
		?>
		<div id="hostinger-plugin-split-notice" class="hts-plugin-split notice is-dismissible">
			<h2><?php echo esc_html__( 'Hostinger plugin updates', 'hostinger' ); ?></h2>
			<p><?php echo esc_html__( 'The Hostinger plugin has been split into two different plugins:', 'hostinger' ); ?></p>
			<ul>
				<li><strong><?php echo esc_html__( 'Hostinger Tools', 'hostinger' ); ?></strong> <?php echo esc_html__( 'offers a toolkit for easier site maintenance.', 'hostinger' ); ?></li>
				<li><strong><?php echo esc_html__( 'Hostinger Easy Onboarding', 'hostinger' ); ?></strong> <?php echo esc_html__( 'provides guidance and learning resources for beginners to get started with building a site using WordPress.', 'hostinger' ); ?></li>
			</ul>
			<button id="plugin-split-close" type="button" class="plugin-split-close notice-dismiss"><?php echo esc_html__( 'Got it', 'hostinger' ); ?></button>
		</div>
		<?php
		wp_nonce_field( 'hts_close_plugin_split', 'hts_close_plugin_split_nonce', true );
	}

    public function custom_kses_allowed_html( $allowed ) {
        // Add SVG elements and attributes to the allowed list
        $allowed['svg'] = array(
            'xmlns' => true,
            'width' => true,
            'height' => true,
            'viewBox' => true,
            'fill' => true,
            'style' => true,
            'class' => true,
        );
        $allowed['path'] = array(
            'd' => true,
            'fill' => true,
        );

        return $allowed;
    }
}
