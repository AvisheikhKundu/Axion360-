<?php

namespace Hostinger\EasyOnboarding\Admin\Onboarding\Steps;

defined( 'ABSPATH' ) || exit;

class Step {
    /**
     * @var string
     */
    private string $id = '';

    /**
     * @var bool
     */
    private bool $is_completed = false;

    /**
     * @var string
     */
    private string $title = '';

    /**
     * @var string
     */
    private string $title_completed = '';

    /**
     * @var string
     */
    private string $description = '';

    /**
     * @var string
     */
    private string $image_url = '';

    /**
     * @var Button
     */
    private Button $primary_button;

    /**
     * @var Button
     */
    private Button $secondary_button;

    /**
     * @param string $id
     * @param string $title
     * @param string $description
     * @param string $image_url
     */
    public function __construct(string $id, string $title = '', string $description = '', string $image_url = '') {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->image_url = $image_url;
        $this->primary_button = new Button( '' );
        $this->secondary_button = new Button( '' );
    }

    /**
     * @return string
     */
    public function get_id(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return void
     */
    public function set_id(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function get_is_completed(): bool
    {
        return $this->is_completed;
    }

    /**
     * @param bool $is_completed
     *
     * @return void
     */
    public function set_is_completed(bool $is_completed): void
    {
        $this->is_completed = $is_completed;
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
    public function set_title(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function get_description(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return void
     */
    public function set_description(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function get_image_url(): string
    {
        return $this->image_url;
    }

    /**
     * @param string $image_url
     *
     * @return void
     */
    public function set_image_url(string $image_url): void
    {
        $this->image_url = $image_url;
    }

    /**
     * @return Button
     */
    public function get_primary_button(): Button
    {
        return $this->primary_button;
    }

    /**
     * @param Button $primary_button
     *
     * @return void
     */
    public function set_primary_button(Button $primary_button): void
    {
        $this->primary_button = $primary_button;
    }

    /**
     * @return Button
     */
    public function get_secondary_button(): Button
    {
        return $this->secondary_button;
    }

    /**
     * @param Button $secondary_button
     *
     * @return void
     */
    public function set_secondary_button(Button $secondary_button): void
    {
        $this->secondary_button = $secondary_button;
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
    public function set_title_completed(string $title_completed): void
    {
        $this->title_completed = $title_completed;
    }

    /**
     * @return array
     */
    public function to_array(): array
    {
        $primary_button = !empty($this->get_primary_button()) ? $this->get_primary_button()->to_array() : [];
        $secondary_button = !empty($this->get_secondary_button()) ? $this->get_secondary_button()->to_array() : [];

        return array(
            'id'               => $this->get_id(),
            'is_completed'     => $this->get_is_completed(),
            'title'            => $this->get_title(),
            'title_completed'  => $this->get_title_completed(),
            'description'      => $this->get_description(),
            'image_url'        => $this->get_image_url(),
            'primary_button'   => $primary_button,
            'secondary_button' => $secondary_button
        );
    }
}
