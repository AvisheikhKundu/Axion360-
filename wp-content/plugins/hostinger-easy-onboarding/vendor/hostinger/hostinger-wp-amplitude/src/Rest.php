<?php
/**
 * Rest API Routes
 *
 * @package HostingerAffiliatePlugin
 */

namespace Hostinger\Amplitude;

use Hostinger\WpHelper\Utils as Helper;
use Hostinger\WpHelper\Requests\Client;
use Hostinger\WpHelper\Config;
use Hostinger\WpHelper\Constants;


/**
 * Avoid possibility to get file accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Class for handling Rest Api Routes
 */
class Rest {
    public const REST_NAMESPACE = 'hostinger-amplitude/v1';

    /**
     * @var Helper
     */
    private Helper $helper;

    /**
     * @var Config
     */
    private Config $configHandler;

    /**
     * @var Client
     */
    private Client $client;

    public function __construct() {
        $this->helper          = new Helper();
        $this->configHandler   = new Config();
        $this->client          = new Client(
            $this->configHandler->getConfigValue( 'base_rest_uri', Constants::HOSTINGER_REST_URI ),
            array(
                Config::TOKEN_HEADER  => $this->helper::getApiToken(),
                Config::DOMAIN_HEADER => $this->helper->getHostInfo()
            )
        );
    }

	/**
	 * Init rest routes
	 *
	 * @return void
	 */
	public function init(): void {
		add_action( 'rest_api_init', [ $this, 'registerRoutes' ] );
	}

	/**
	 * @return void
	 */
	public function registerRoutes(): void {
		$this->registerAmplitudeRoute();
        $this->registerExperimentsRoute();
	}

    /**
     * @return void
     */
	public function registerAmplitudeRoute(): void {
		register_rest_route(
			self::REST_NAMESPACE,
			'hostinger-amplitude-event',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'sendAmplitudeEvent' ),
				'permission_callback' => array( $this, 'permissionCheck' ),
			)
		);
	}

    public function registerExperimentsRoute(): void {
        register_rest_route(
            self::REST_NAMESPACE,
            'hostinger-amplitude-experiments',
            array(
                'methods'             => 'GET',
                'callback'            => array( $this, 'getExperiments' ),
                'permission_callback' => array( $this, 'permissionCheck' ),
            )
        );
    }

    /**
     * @return \WP_REST_Response
     */
    public function getExperiments( ): \WP_REST_Response {
        $data = array();

        $response = new \WP_REST_Response( );

        try {
            $response->set_status( \WP_Http::OK );

            $request = $this->client->get( '/v3/wordpress/amplitude/experiments', array( 'domain' => $this->helper->getHostInfo() ) );

            $data = $request;

            if(!empty($request['body'])) {
                $json = json_decode($request['body'], true);

                if(!empty($json['data'])) {
                    $data = array(
                        'status' => 'success',
                        'data' => $json['data']
                    );
                }
            }

        } catch ( \Exception $exception ) {

            $response->set_status( \WP_Http::BAD_REQUEST );

            $this->helper->errorLog( 'Error sending request: ' . $exception->getMessage() );

            $data = array(
                'status' => 'error',
                'message' => $exception->getMessage()
            );
        }

        $response->set_data( $data );

        $response->set_headers( array( 'Cache-Control' => 'no-cache' ) );

        return $response;
    }

	public function sendAmplitudeEvent( $request ) {
		$params          = $request->get_param( 'params' );

		$amplitudeManger = new AmplitudeManager( $this->helper, $this->configHandler, $this->client );
		$status = $amplitudeManger->sendRequest( $amplitudeManger::AMPLITUDE_ENDPOINT, !empty($params) ? $params : [] );

		$response = new \WP_REST_Response( array( 'status' => $status ) );
		$response->set_headers(array('Cache-Control' => 'no-cache'));
		$response->set_status( \WP_Http::OK );

		return $response;
	}

	/**
	 * @param WP_REST_Request $request WordPress rest request.
	 *
	 * @return bool
	 */
	public function permissionCheck( $request ): bool {
		if ( empty( is_user_logged_in() ) ) {
			return false;
		}

		// Implement custom capabilities when needed.
		return current_user_can( 'manage_options' );
	}
}
