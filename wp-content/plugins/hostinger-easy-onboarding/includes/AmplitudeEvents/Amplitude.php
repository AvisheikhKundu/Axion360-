<?php

namespace Hostinger\EasyOnboarding\AmplitudeEvents;

defined('ABSPATH') || exit;

use Hostinger\Amplitude\AmplitudeManager;
use Hostinger\EasyOnboarding\AmplitudeEvents\Actions as AmplitudeActions;
use Hostinger\WpHelper\Utils as Helper;
use Hostinger\WpHelper\Requests\Client;
use Hostinger\WpHelper\Config;
use Hostinger\WpHelper\Constants;

class Amplitude
{
    public const WEBSITE_BUILDER_TYPE = 'ai';
    /**
     * @var Helper
     */
    private Helper $helper;

    /**
     * @var Client
     */
    private Client $client;

    /**
     * @var Config
     */
    private Config $configHandler;

    private array $options = [];

    public function __construct()
    {
        $this->helper        = new Helper();
        $this->configHandler = new Config();
        $this->client        = new Client(
            $this->configHandler->getConfigValue('base_rest_uri', Constants::HOSTINGER_REST_URI), [
                Config::TOKEN_HEADER  => Helper::getApiToken(),
                Config::DOMAIN_HEADER => $this->helper->getHostInfo(),
            ]
        );
        $this->options['builder_type']        = get_option('hostinger_builder_type', '');
        $this->options['website_id']          = get_option('hostinger_website_id', '');
        $this->options['subscription_id']     = get_option('hostinger_subscription_id', '');
        $this->options['event_data']          = get_option('hostinger_amplitude_event_data', []);
        $this->options['edit_count']          = get_option('hostinger_amplitude_edit_count', 0);
        $this->options['ai_version']          = get_option('hostinger_ai_version', '');
    }

    /**
     * @param $params
     *
     * @return array
     */
    public function send_event(array $params): array
    {
        $amplitudeManager = new AmplitudeManager($this->helper, $this->configHandler, $this->client);

        return $amplitudeManager->sendRequest($amplitudeManager::AMPLITUDE_ENDPOINT, $params);
    }

    public function sendEditAmplitudeEvent(): void
    {
        $edit_count = $this->incrementAmplitudeEditEventCount();

        $params = [
            'action'          => AmplitudeActions::WP_EDIT,
            'wp_builder_type' => $this->options['builder_type'],
            'website_id'      => $this->options['website_id'],
            'subscription_id' => $this->options['subscription_id'],
            'edit_count'      => $edit_count,
        ];

        $this->send_event($params);
    }

    public function canSendEditAmplitudeEvent(): bool
    {
        $today               = date('Y-m-d');
        $event_data          = $this->options['event_data'];
        $isAiWebsiteNotGenerated = !$this->options['ai_version'];

        if (!$this->options['builder_type'] || !$this->options['website_id'] || !$this->options['subscription_id']) {
            return false;
        }

        if ($this->options['builder_type'] == self::WEBSITE_BUILDER_TYPE && $isAiWebsiteNotGenerated) {
            return false;
        }

        if (!is_array($event_data)) {
            $event_data = [];
        }

        // Check if we already have data for today
        $today_event = $event_data[$today] ?? ['count' => 0, 'last_reset' => 0];

        // Only update if the event count is less than 3
        if ($today_event['count'] < 3) {
            $today_event['count'] += 1;
            $event_data[$today] = $today_event;

            update_option('hostinger_amplitude_event_data', $event_data);
            wp_cache_delete('hostinger_amplitude_event_data', 'options');

            return true;
        }

        return false;
    }

    public function incrementAmplitudeEditEventCount(): int
    {
        $edit_count = (int) $this->options['edit_count'] + 1;

        update_option('hostinger_amplitude_edit_count', $edit_count);
        wp_cache_delete('hostinger_amplitude_event_data', 'options');

        return $edit_count;
    }
}
