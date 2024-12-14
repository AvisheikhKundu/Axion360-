<?php

namespace Hostinger\EasyOnboarding\Admin;

use Hostinger\EasyOnboarding\Admin\Actions as Admin_Actions;
use Hostinger\EasyOnboarding\Admin\Onboarding\Onboarding;
use Hostinger\EasyOnboarding\AmplitudeEvents\Actions as AmplitudeActions;
use Hostinger\EasyOnboarding\AmplitudeEvents\Amplitude;
use Hostinger\EasyOnboarding\Helper;
use Hostinger\EasyOnboarding\WooCommerce\GatewayManager;
use Hostinger\EasyOnboarding\WooCommerce\Options as WooCommerceOptions;
use Hostinger\WpHelper\Utils;

defined( 'ABSPATH' ) || exit;


class Hooks
{
    public const WOOCOMMERCE_PAGES = [
        'admin.php?page=wc-admin',
        'edit.php?post_type=shop_order',
        'admin.php?page=wc-admin&path=/customers',
        'edit.php?post_type=shop_coupon&legacy_coupon_menu=1',
        'admin.php?page=wc-admin&path=/marketing',
        'admin.php?page=wc-reports',
        'admin.php?page=wc-settings',
        'admin.php?page=wc-status',
        'admin.php?page=wc-admin&path=/extensions',
        'edit.php?post_type=product',
        'post-new.php?post_type=product',
        'edit.php?post_type=product&page=product-reviews',
        'edit.php?post_type=product&page=product_attributes',
        'edit-tags.php?taxonomy=product_cat&post_type=product',
        'edit-tags.php?taxonomy=product_tag&post_type=product',
        'admin.php?page=wc-admin&path=/analytics/overview',
        'admin.php?page=wc-admin',
        'admin.php?page=wc-orders',
    ];

    public const COMPLETED_REMINDER_VISIBLE_PAGES = [
        'admin.php?page=googlesitekit-splash',
        'admin.php?page=googlesitekit-dashboard'
    ];

    public const COMPLETED_REMINDER_OPTION_NAME = 'hostinger_setup_completed';

    public const WOOPAYMENTS_REMINDER_OPTION_NAME = 'hostinger_woopayments_completed';

    public const WOO_ONBOARDING_NOTICE_TRANS = 'hostinger_return_to_onboarding';
    /**
     * @var Onboarding
     */
    private Onboarding $onboarding;

    private Helper $helper;

	public const DAY_IN_SECONDS = 86400;

	public function __construct() {
		$this->helper = new Helper();
        $this->onboarding = new Onboarding();

        add_action( 'admin_init', array( $this, 'init_onboarding' ), 0 );

		// Admin footer actions
		add_action( 'admin_footer', array( $this, 'rate_plugin' ) );

		// Admin init actions
		add_action( 'admin_init', array( $this, 'admin_init_actions' ) );
		add_action( 'admin_init', array( $this, 'set_woocommerce_options' ), 0 );

		// Admin notices
		add_action( 'admin_notices', array( $this, 'omnisend_discount_notice' ) );

        // Return back to onboarding.
		add_action( 'admin_footer', array( $this, 'back_to_onboarding_notice' ) );
        add_filter( 'admin_body_class', array( $this, 'add_onboarding_notice_class' ) );

		// Admin Styles
		add_action( 'admin_head', array( $this, 'hide_notices' ) );

		// WooCommerce filters
		add_filter( 'woocommerce_prevent_automatic_wizard_redirect', function () {
			return true;
		}, 999 );
		add_filter( 'woocommerce_enable_setup_wizard', function () {
			return false;
		}, 999 );

		// Spectra
		add_filter( 'uagb_enable_redirect_activation', function () {
			return false;
		}, 999 );

        // Hooking up one action before and removing astra redirect to onboarding
        if ( function_exists( 'astra_sites_redirect_to_onboarding' ) ) {
            add_action('admin_menu', function () {
                remove_action('admin_init', 'astra_sites_redirect_to_onboarding');
            });
        }

        add_filter('get_edit_post_link', [$this, 'change_shop_page_edit_url'], 99, 3);

        add_action( 'admin_menu', array( $this, 'disable_beaver_builder_redirect' ) );

        add_action( 'admin_menu', array( $this, 'disable_monsterinsights_redirect' ) );

        if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action('updated_option', [$this, 'check_if_payment_gateway_enabled'], 20, 3);
        }
    }

    public function init_onboarding() {
        $this->onboarding->init();
    }

    public function hide_notices() {
		$helper = new Helper();
		?>
		<style>

            <?php

            if ( is_plugin_active( 'ultimate-addons-for-gutenberg/ultimate-addons-for-gutenberg.php' ) || is_plugin_active( 'astra-sites/astra-sites.php' ) ) {
                ?>
                .interface-interface-skeleton .editor-header__settings button[aria-controls="zip-ai-page-level-settings:zip-ai-page-settings-panel"] {
                    display: none !important;
                }
                <?php
            }

            ?>
			<?php if ( ! $helper->is_woocommerce_onboarding_completed() ) : ?>
            .post-php.post-type-product #wpadminbar .wpforms-menu-notification-counter,
            .post-php.post-type-product #wpadminbar .aioseo-menu-notification-counter,
            .post-php.post-type-product .woocommerce-layout__header-tasks-reminder-bar,
            .post-php.post-type-product .litespeed_icon.notice.is-dismissible,
            .post-php.post-type-product .monsterinsights-menu-notification-indicator,
            .post-php.post-type-product .aioseo-review-plugin-cta,
            .post-php.post-type-product .omnisend-connection-notice-hidden,
            .post-php.post-type-product #astra-upgrade-pro-wc {
                display: none !important;
            }

            .notice.hts-notice {
                display: block !important;
            }

			<?php endif; ?>

			<?php if ( $this->is_woocommerce_admin_page() && ! $helper->is_woocommerce_onboarding_completed() ) : ?>
            #wpadminbar .wpforms-menu-notification-counter,
            #wpadminbar .aioseo-menu-notification-counter,
            .woocommerce-layout__header-tasks-reminder-bar,
            .litespeed_icon.notice.is-dismissible,
            .monsterinsights-menu-notification-indicator,
            .aioseo-review-plugin-cta,
            .omnisend-connection-notice-hidden,
            #astra-upgrade-pro-wc,
            .notice {
                display: none !important;
            }

            .notice.hts-notice {
                display: block !important;
            }

			<?php endif; ?>
		</style>
		<?php
	}

	public function admin_init_actions(): void {
		$this->hide_astra_builder_selection_screen();
		$this->hide_metaboxes();
		$this->hide_monsterinsight_notice();
		$this->check_plugins_and_remove_duplicates();
	}
	public function check_plugins_and_remove_duplicates() {
		// Check if the script has already run
		if (get_option('hostinger_woocommerce_pages_cleanup_done')) {
			return;
		}

		// Check if Astra Sites and WooCommerce are activated
		if (is_plugin_active('astra-sites/astra-sites.php') && is_plugin_active('woocommerce/woocommerce.php')) {
			// List of pages to check
			$page_slugs = array("shop", "cart", "checkout", "my-account");
			$astra_page_slugs = array("shop-2", "cart-2", "checkout-2", "my-account-2");
			$pages_to_remove = array();
			$pages_to_rename = array();

			$query = new \WP_Query(array(
				'post_type'   => 'page',
				'post_status' => 'publish',
				'post_name__in' => array_merge($page_slugs, $astra_page_slugs),
				'posts_per_page' => -1
			));

			if ($query->have_posts()) {
				$pages = $query->posts;
				$page_ids = array();

				foreach ($pages as $page) {
					$page_ids[$page->post_name] = $page->ID;
				}

				foreach ($page_slugs as $index => $slug) {
					$astra_slug = $astra_page_slugs[$index];
					if (isset($page_ids[$slug]) && isset($page_ids[$astra_slug])) {
						$pages_to_remove[] = $page_ids[$slug];
						$pages_to_rename[$astra_slug] = $page_ids[$astra_slug];
					}
				}
			}

			// Reset post data after query
			wp_reset_postdata();

			// Assign the 'shop-2', 'cart-2', 'checkout-2' pages as WooCommerce pages
			foreach ($pages_to_rename as $astra_slug => $page_id) {
				switch ($astra_slug) {
					case 'shop-2':
						update_option('woocommerce_shop_page_id', $page_id);
						break;
					case 'cart-2':
						update_option('woocommerce_cart_page_id', $page_id);
						break;
					case 'checkout-2':
						update_option('woocommerce_checkout_page_id', $page_id);
						break;
						case 'my-account-2':
						update_option('woocommerce_my_account_page_id', $page_id);
						break;
				}
			}

			// Remove the original 'shop', 'cart', 'checkout' pages
			foreach ($pages_to_remove as $page_id) {
				wp_delete_post($page_id, true);
			}

			// Rename 'shop-2', 'cart-2', 'checkout-2' to 'shop', 'cart', 'checkout'
			foreach ($pages_to_rename as $astra_slug => $page_id) {
				$new_slug = str_replace('-2', '', $astra_slug);
				$new_title = $new_slug == 'my-account' ? 'My Account' : ucfirst($new_slug);
				wp_update_post(array(
					'ID' => $page_id,
					'post_name' => $new_slug,
					'post_title' => $new_title
				));
			}

			// Set the option to indicate the script has run
			update_option('hostinger_woocommerce_pages_cleanup_done', 1);
		}
	}

	public function hide_metaboxes(): void {
		$this->hide_plugin_metabox( 'google-analytics-for-wordpress/googleanalytics.php', 'monsterinsights-metabox', 'metaboxhidden_product' );
		$this->hide_plugin_metabox( 'all-in-one-seo-pack/all_in_one_seo_pack.php', 'aioseo-settings', 'aioseo-settings' );
		$this->hide_plugin_metabox( 'litespeed-cache/litespeed-cache.php', 'litespeed_meta_boxes', 'litespeed_meta_boxes' );
		$this->hide_astra_theme_metabox();
		$this->hide_custom_fields_metabox();

        // Hide panels in Gutenberg editor
        $this->hide_plugin_panel('all-in-one-seo-pack/all_in_one_seo_pack.php', 'meta-box-aioseo-settings');
	}

	public function hide_astra_theme_metabox(): void {
		if ( ! $this->is_astra_theme_active() ) {
			return;
		}
		$this->hide_metabox( 'astra_settings_meta_box', 'astra_metabox' );
	}

	public function hide_custom_fields_metabox(): void {
		$this->hide_metabox( 'postcustom', 'custom_fields_metabox' );
	}

	public function hide_metabox( string $metabox_id, string $transient_suffix ): void {
		$helper        = new Utils();
		$user_id       = get_current_user_id();
		$transient_key = $transient_suffix . '_' . $user_id;
		$hide_metabox  = get_transient( $transient_key );

		if ( $hide_metabox ) {
			return;
		}

		$hide_metabox = get_user_meta( $user_id, 'metaboxhidden_product', true );

		if ( ! is_array( $hide_metabox ) ) {
			$hide_metabox = array();
		}

		if ( $helper->isThisPage( 'post-new.php?post_type=product' ) ) {
			if ( ! in_array( $metabox_id, $hide_metabox ) ) {
				array_push( $hide_metabox, $metabox_id );
			}

			update_user_meta( $user_id, 'metaboxhidden_product', $hide_metabox );
			set_transient( $transient_key, 'hidden', self::DAY_IN_SECONDS );
		}
	}

	public function is_astra_theme_active(): bool {
		$theme = wp_get_theme();

		return $theme->get( 'Name' ) === 'Astra';
	}

	public function hide_monsterinsight_notice(): void {
		if ( is_plugin_active( 'google-analytics-for-wordpress/googleanalytics.php' ) ) {
			define( 'MONSTERINSIGHTS_DISABLE_TRACKING', true );
		}
	}

	public function rate_plugin(): void {
		$promotional_banner_hidden = get_transient( 'hts_hide_promotional_banner_transient' );
		$two_hours_in_seconds      = 7200;

		if ( $promotional_banner_hidden && time() > $promotional_banner_hidden + $two_hours_in_seconds ) {
			require_once HOSTINGER_EASY_ONBOARDING_ABSPATH . 'includes/Admin/Views/Partials/RateUs.php';
		}
	}

	public function omnisend_discount_notice(): void {
		$omnisend_notice_hidden = get_transient( 'hts_omnisend_notice_hidden' );

		if ( $omnisend_notice_hidden === false && $this->helper->is_this_page( '/wp-admin/admin.php?page=omnisend' ) && ( Helper::is_plugin_active( 'class-omnisend-core-bootstrap' ) || Helper::is_plugin_active( 'omnisend-woocommerce' ) ) ) : ?>
			<div class="notice notice-info hts-admin-notice hts-omnisend">
				<p><?php echo wp_kses( __( 'Use the special discount code <b>ONLYHOSTINGER30</b> to get 30% off on Omnisend for 6 months when you upgrade.', 'hostinger-easy-onboarding' ), array( 'b' => array() ) ); ?></p>
				<div>
					<a class="button button-primary"
					   href="https://your.omnisend.com/LXqyZ0"
					   target="_blank"><?= esc_html__( 'Get Discount', 'hostinger-easy-onboarding' ); ?></a>
					<button type="button" class="notice-dismiss"></button>
				</div>
			</div>
		<?php endif;
		wp_nonce_field( 'hts_close_omnisend', 'hts_close_omnisend_nonce', true );
	}

	public function hide_astra_builder_selection_screen(): void {
		add_filter( 'st_enable_block_page_builder', '__return_true' );
	}

	public function hide_plugin_metabox( string $plugin_slug, string $metabox_id, string $transient_suffix ): void {
		$helper        = new Utils();
		$user_id       = get_current_user_id();
		$transient_key = $transient_suffix . '_' . $user_id;
		$hide_metabox  = get_transient( $transient_key );

		if ( $hide_metabox ) {
			return;
		}

		$hide_metabox = get_user_meta( $user_id, 'metaboxhidden_product', true );

		if ( ! is_plugin_active( $plugin_slug ) ) {
			return;
		}

		if ( ! is_array( $hide_metabox ) ) {
			$hide_metabox = array();
		}

		if ( $helper->isThisPage( 'post-new.php?post_type=product' ) ) {
			if ( ! in_array( $metabox_id, $hide_metabox ) ) {
				array_push( $hide_metabox, $metabox_id );
			}

			update_user_meta( $user_id, 'metaboxhidden_product', $hide_metabox );
			set_transient( $transient_key, 'hidden', self::DAY_IN_SECONDS );
		}
	}

    public function hide_plugin_panel( string $plugin_slug, $panel_id ) {
        if ( ! is_plugin_active( $plugin_slug ) ) {
            return;
        }

        $user_id   = get_current_user_id();
        $flag_name = 'hostinger_' . $panel_id . '_changed';

        $hidden_once = get_user_meta( $user_id, $flag_name, true );

        if( $hidden_once ) {
            return;
        }

        $persisted_preferences = get_user_meta( $user_id, 'wp_persisted_preferences', true );

        if ( empty( $persisted_preferences ) ) {
            $persisted_preferences = array(
                    'core/edit-post' => array(
                        'welcomeGuide' => '',
                        'isComplementaryAreaVisible' => 1,
                        'inactivePanels' => array(
                            $panel_id
                        )
                    ),
                    'core/edit-site' => array(
                        'welcomeGuide' => '',
                        'isComplementaryAreaVisible' => 1
                    ),
                    '_modified' => wp_date( 'c' ),
            );
        } else {
            if( !empty( $persisted_preferences['core/edit-post']['inactivePanels'] ) && !in_array( $panel_id, $persisted_preferences['core/edit-post']['inactivePanels'] ) ) {
                $persisted_preferences['core/edit-post']['inactivePanels'][] = $panel_id;
            }
        }

        update_user_meta( $user_id, 'wp_persisted_preferences', $persisted_preferences );
        update_user_meta( $user_id, $flag_name, 1 );
    }

    private function is_completed_reminder_page(): bool
    {
        if ( ! isset($_SERVER['REQUEST_URI'])) {
            return false;
        }

        $current_uri = sanitize_text_field($_SERVER['REQUEST_URI']);

        if (defined('DOING_AJAX') && \DOING_AJAX) {
            return false;
        }

        if (isset($current_uri) && strpos($current_uri, '/wp-json/') !== false) {
            return false;
        }

        foreach (self::COMPLETED_REMINDER_VISIBLE_PAGES as $page) {
            if (strpos($current_uri, $page) !== false) {
                return true;
            }
        }

        return false;
    }

    private function is_woocommerce_admin_page(): bool
    {
        // Product edit etc.
        if (get_post_type() === 'product') {
            return true;
        }

        if ( ! isset($_SERVER['REQUEST_URI'])) {
            return false;
        }

        $current_uri = sanitize_text_field($_SERVER['REQUEST_URI']);

        if (defined('DOING_AJAX') && \DOING_AJAX) {
            return false;
        }

        if (isset($current_uri) && strpos($current_uri, '/wp-json/') !== false) {
            return false;
        }

        if (isset($current_uri) && (strpos($current_uri, 'paymentsonboarding') !== false || strpos($current_uri, '/payments/onboarding') !== false)) {
            return false;
        }

        foreach (self::WOOCOMMERCE_PAGES as $page) {
            if (strpos($current_uri, $page) !== false) {
                return true;
            }
        }

        return false;
    }

    public function back_to_onboarding_notice(): void
    {
        $text = __('Just a few more steps to launch your online store', 'hostinger-easy-onboarding');
        $button_url = admin_url('admin.php?page=hostinger-get-onboarding&subPage=woo-commerce-online-store-setup');
        $button_class = '';
        $button_text = __('Continue setup', 'hostinger-easy-onboarding');

        if ( $this->is_onboarding_complete_reminder_visible() ) {
            $text = __('Congrats, you’re ready to show off your site!', 'hostinger-easy-onboarding');
            $button_url = admin_url('admin.php?page=hostinger-get-onboarding');
            $button_class = '';
            $button_text = __('View checklist', 'hostinger-easy-onboarding');
        }

        if( $this->is_woopayments_reminder_visible() ) {
            $text = __('Congrats, you have connected WooPayments!', 'hostinger-easy-onboarding');
            $button_url = admin_url('admin.php?page=hostinger-get-onboarding&subPage=woo-commerce-online-store-setup');
            $button_class = '';
            $button_text = __('Continue setup', 'hostinger-easy-onboarding');
        }

        if ( $this->is_onboarding_reminder_visible() || $this->is_onboarding_complete_reminder_visible() || $this->is_woopayments_reminder_visible() ) {
            ?>
            <div class="hostinger-onboarding-reminder">
                <div class="hostinger-onboarding-reminder__wrap">
                    <div class="hostinger-onboarding-reminder__logo">
                        <svg width="93" height="108" viewBox="0 0 93 108" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                  d="M66.5145 0V32.2221L92.8421 47.4468V13.2693L66.5145 0ZM0.000915527 0.00183105V50.5657H85.6265L59.5744 36.4074L25.6372 36.3911V13.6097L0.000915527 0.00183105ZM66.5145 94.0237V71.4387L32.3155 71.4152C32.3474 71.5655 5.83099 57.0306 5.83099 57.0306L92.8421 57.4371V108L66.5145 94.0237ZM0.000912095 60.9814L0 94.0237L25.6372 107.292V75.8458L0.000912095 60.9814Z"
                                  fill="#673DE6"/>
                        </svg>
                    </div>
                    <div class="hostinger-onboarding-reminder__text">
                        <?php echo $text; ?>
                    </div>
                    <div class="hostinger-onboarding-reminder__cta <?php echo $button_class; ?>">
                        <a href="<?php
                        echo $button_url; ?>">
                            <?php
                            echo $button_text; ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php
        }

        if ( $this->is_onboarding_complete_reminder_visible() ) {
            update_option( self::COMPLETED_REMINDER_OPTION_NAME, 1 );
        }

        if ( $this->is_woopayments_reminder_visible() ) {
            update_option( self::WOOPAYMENTS_REMINDER_OPTION_NAME, 1 );
        }
    }

    public function add_onboarding_notice_class($classes)
    {
        if ($this->is_onboarding_reminder_visible() || $this->is_onboarding_complete_reminder_visible()) {
            $classes .= ' hostinger-onboarding-reminder-visible';
        }

        return $classes;
    }

    private function is_onboarding_complete_reminder_visible()
    {
        $setup_completed = get_option( self::COMPLETED_REMINDER_OPTION_NAME, false );

        $onboarding_completed = $this->helper->is_website_onboarding_completed();

        if ( is_plugin_active('woocommerce/woocommerce.php') ) {
            $onboarding_completed = $this->helper->is_woocommerce_onboarding_completed() && $this->helper->is_website_onboarding_completed();
        }

        return $onboarding_completed
               && empty( $setup_completed )
               && $this->is_completed_reminder_page();
    }

    private function is_woopayments_reminder_visible()
    {
        $woopayments_completed = get_option( self::WOOPAYMENTS_REMINDER_OPTION_NAME, false );

        return is_plugin_active('woocommerce/woocommerce.php')
               && is_plugin_active('woocommerce-payments/woocommerce-payments.php')
               && $this->helper->is_woocommerce_payments_ready()
               && empty( $woopayments_completed )
               && $this->helper->is_this_page( 'paymentsoverview' );
    }

    private function is_onboarding_reminder_visible()
    {
        return is_plugin_active('woocommerce/woocommerce.php')
               && ! $this->helper->is_woocommerce_onboarding_completed()
               && $this->is_woocommerce_admin_page();
    }

    public function change_shop_page_edit_url($link, $post_id, $context): string
    {
        if ( ! is_plugin_active('woocommerce/woocommerce.php')) {
            return $link;
        }

        $shop_page_id = wc_get_page_id( 'shop' );

        if( $shop_page_id != $post_id ) {
            return $link;
        }

        if( current_theme_supports( 'block-templates' ) ) {
            return admin_url( 'site-editor.php?postType=wp_template&postId=woocommerce/woocommerce//archive-product' );
        }

        return admin_url( 'customize.php?autofocus[section]=woocommerce_product_catalog' );
    }

    public function disable_beaver_builder_redirect() {
        if ( !is_plugin_active( 'ultimate-addons-for-beaver-builder-lite/bb-ultimate-addon.php' ) ) {
            return false;
        }

        $redirect = get_option( 'uabb_lite_redirect', false );

        if ( empty( $redirect ) ) {
            return false;
        }

        update_option( 'uabb_lite_redirect', false );

        $this->flush_object_cache();
    }

    public function disable_monsterinsights_redirect() {
        if ( !is_plugin_active( 'google-analytics-for-wordpress/googleanalytics.php' ) ) {
            return false;
        }

        if ( ! get_transient( '_monsterinsights_activation_redirect' ) ) {
            return false;
        }

        // Delete the redirect transient.
        delete_transient( '_monsterinsights_activation_redirect' );

        $this->flush_object_cache();
    }

    private function flush_object_cache(): void {
        if ( has_action( 'litespeed_purge_all_object' ) ) {
            do_action('litespeed_purge_all_object');
        }
    }

    public function check_if_payment_gateway_enabled( $option, $old_value, $value ) {
        if ( ! str_contains( $option, 'woocommerce' ) ) {
            return;
        }

        if ( $this->onboarding->is_completed( Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID, Admin_Actions::ADD_PAYMENT ) ) {
            return;
        }

        $payment_gateway_manager = new GatewayManager( \WC_Payment_Gateways::instance() );

        if( $payment_gateway_manager->isAnyGatewayActive() ) {
            $this->onboarding->complete_step( Onboarding::HOSTINGER_EASY_ONBOARDING_STORE_STEP_CATEGORY_ID, Admin_Actions::ADD_PAYMENT );

            $amplitude = new Amplitude();

            $params = array(
                'action'    => AmplitudeActions::WOO_ITEM_COMPLETED,
                'step_type' => Admin_Actions::ADD_PAYMENT,
            );

            $amplitude->send_event( $params );
        }
    }

    /**
     * @return void
     */
    public function set_woocommerce_options(): void {
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            return;
        }

        $woocommerce_options_set = get_option( 'hostinger_onboarding_woo_options_set', null );
        $onboarding_choice_done = get_option( 'hostinger_onboarding_choice_done', null );

        if ( $onboarding_choice_done === null && $woocommerce_options_set === null ) {
            $woocommerceOptions = new WooCommerceOptions();

            $woocommerceOptions->hideSetupTaskList();
            $woocommerceOptions->disableWooCommerceShipingZoneCreation();
            $woocommerceOptions->skipOnboarding();

            update_option( 'hostinger_onboarding_woo_options_set', true );
        }
    }
}

