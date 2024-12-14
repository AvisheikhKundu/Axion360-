<?php

namespace Hostinger\EasyOnboarding;

use Hostinger\EasyOnboarding\Admin\Surveys;
use Hostinger\EasyOnboarding\Rest\Routes;
use Hostinger\EasyOnboarding\Rest\StepRoutes;
use Hostinger\EasyOnboarding\Rest\TutorialRoutes;
use Hostinger\EasyOnboarding\Rest\WelcomeRoutes;
use Hostinger\EasyOnboarding\Rest\WooRoutes;
use Hostinger\EasyOnboarding\Admin\Assets as AdminAssets;
use Hostinger\EasyOnboarding\Admin\Hooks as AdminHooks;
use Hostinger\EasyOnboarding\Admin\Menu as AdminMenu;
use Hostinger\EasyOnboarding\Admin\Ajax as AdminAjax;
use Hostinger\EasyOnboarding\Admin\Partnership;
use Hostinger\EasyOnboarding\Admin\Redirects as AdminRedirects;
use Hostinger\EasyOnboarding\Preview\Assets as PreviewAssets;
use Hostinger\EasyOnboarding\Admin\Onboarding\AutocompleteSteps;
use Hostinger\Surveys\SurveyManager;
use Hostinger\Surveys\Rest as SurveysRest;
use Hostinger\WpHelper\Config;
use Hostinger\WpHelper\Constants;
use Hostinger\WpHelper\Utils as Helper;
use Hostinger\WpHelper\Requests\Client;
use Hostinger\EasyOnboarding\Cli;

defined( 'ABSPATH' ) || exit;

class Bootstrap {
	protected Loader $loader;

	public function __construct() {
		$this->loader = new Loader();
	}

	public function run(): void {
		$this->load_dependencies();
		$this->set_locale();
		$this->loader->run();
	}

	private function load_dependencies(): void {
		$this->load_onboarding_dependencies();
		$this->load_public_dependencies();


		if ( is_admin() ) {
			$this->load_admin_dependencies();
		}

        if ( defined( 'WP_CLI' ) && WP_CLI ) {
            new Cli();
        }
	}

	private function set_locale() {
		$plugin_i18n = new I18n();
		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );
	}

	private function surveys(): void
	{
		$helper = new Helper();
		$config = new Config();
		$client = new Client(
			$config->getConfigValue( 'base_rest_uri', Constants::HOSTINGER_REST_URI ),
			[
				Config::TOKEN_HEADER  => $helper->getApiToken(),
				Config::DOMAIN_HEADER => $helper->getHostInfo(),
			]
		);

		if ( class_exists( SurveyManager::class ) ) {
			$surveysRest   = new SurveysRest( $client );
			$surveyManager = new SurveyManager( $helper, $config, $surveysRest );
			$surveys       = new Surveys( $surveyManager );
			$surveys->init();
		}
	}

	private function load_admin_dependencies(): void
    {
		$this->surveys();
        new AdminAssets();
        new AdminHooks();
        new AdminMenu();
        new AdminRedirects();
        new AdminAjax();
        new Partnership();
    }

    private function load_public_dependencies(): void {
        new PreviewAssets();
        new Hooks();
        new Updates();

        $welcome_routes  = new WelcomeRoutes();
        $step_routes     = new StepRoutes();
        $woo_routes      = new WooRoutes();
        $tutorial_routes = new TutorialRoutes();

        $routes = new Routes( $welcome_routes, $step_routes, $woo_routes, $tutorial_routes );
        $routes->init();
    }

	private function load_onboarding_dependencies(): void {
        new AutocompleteSteps();
	}
}
