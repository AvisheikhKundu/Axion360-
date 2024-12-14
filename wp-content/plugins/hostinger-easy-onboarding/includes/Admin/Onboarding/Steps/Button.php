<?php

namespace Hostinger\EasyOnboarding\Admin\Onboarding\Steps;

defined( 'ABSPATH' ) || exit;

class Button {
    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $title_completed;

    /**
     * @var bool
     */
    private bool $is_skippable;

    /**
     * @var string
     */
    private string $url = '';

    /**
     * @param string $title
     * @param string $title_completed
     * @param bool $is_skippable
     * @param string $url
     */
    public function __construct( string $title, string $title_completed = '', bool $is_skippable = false, string $url = '' ) {
        $this->title = $title;
        $this->title_completed = $title_completed;
        $this->is_skippable = $is_skippable;
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function get_title(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return void
     */
    public function set_title( string $title ): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function get_title_completed(): string
    {
        return $this->title_completed;
    }

    /**
     * @param string $title_completed
     *
     * @return void
     */
    public function set_title_completed( string $title_completed ): void
    {
        $this->title_completed = $title_completed;
    }

    /**
     * @return bool
     */
    public function get_is_skippable(): bool
    {
        return $this->is_skippable;
    }

    /**
     * @param bool $is_skippable
     *
     * @return void
     */
    public function set_is_skippable( bool $is_skippable ): void
    {
        $this->is_skippable = $is_skippable;
    }

    /**
     * @return string
     */
    public function get_url(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return void
     */
    public function set_url(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return array
     */
    public function to_array(): array
    {
        return array(
            'title'     => $this->get_title(),
            'title_completed' => $this->get_title_completed(),
            'is_skippable' => $this->get_is_skippable(),
            'url' => $this->get_url()
        );
    }
}
