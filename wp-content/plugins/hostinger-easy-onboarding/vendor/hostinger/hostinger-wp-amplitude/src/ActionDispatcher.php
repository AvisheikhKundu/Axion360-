<?php

namespace Hostinger\Amplitude;

use Hostinger\Amplitude\AmplitudeManager;
use Hostinger\WpHelper\Config;
use Hostinger\WpHelper\Requests\Client;
use Hostinger\WpHelper\Utils as Helper;

class ActionDispatcher
{
    private const TRANSIENT_KEY = 'hostinger_login_data';
    private const EXPIRATION_TIME_SECONDS = 10800; // 3 hours
    private const AMPLITUDE_LOGIN_ACTION = 'wordpress.autologin.success';

    private Config $configHandler;
    private Client $client;
    private Helper $helper;

    public function __construct(
        Helper $helper,
        Config $configHandler,
        Client $client
    ) {
        $this->helper        = $helper;
        $this->configHandler = $configHandler;
        $this->client        = $client;

        add_action( 'hostinger_autologin_user_logged_in', [ $this, 'userAlreadyLoggedIn' ] );
        add_action( 'hostinger_autologin', [ $this, 'handleAutoLogin' ] );
        add_action( 'wp_logout', [ $this, 'clearLoginData' ] );
    }

    public function handleAutoLogin( array $data ) : void {
        $this->processLoginData( $data );
        $this->loginEvent( $this->helper, $this->configHandler, $this->client, 'new_login' );
    }

    public function userAlreadyLoggedIn( array $data ) : void {
        $this->processLoginData( $data );
        $this->loginEvent( $this->helper, $this->configHandler, $this->client, 'logged_in' );
    }


    public function processLoginData( array $data ) : void {
        $sanitized_data = $this->sanitizeLoginData( $data );
        set_transient( self::TRANSIENT_KEY, $sanitized_data, self::EXPIRATION_TIME_SECONDS );
    }

    public function loginEvent( Helper $helper, Config $config, Client $client, string $status ) : void {
        $amplitudeManager = new AmplitudeManager( $helper, $config, $client );
        $params           = [
            'action' => self::AMPLITUDE_LOGIN_ACTION,
            'status' => $status,
        ];

        $amplitudeManager->sendRequest( $amplitudeManager::AMPLITUDE_ENDPOINT, $params );
    }

    private function sanitizeLoginData( array $data ) : array {
        if ( ! is_array( $data ) ) {
            return [];
        }

        return array_map( 'sanitize_text_field', $data );
    }

    public function clearLoginData() : void {
        delete_transient( self::TRANSIENT_KEY );
    }
}
