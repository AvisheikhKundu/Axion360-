<?php

namespace DynamicVisibilityForElementor;

class Controls {

	public $controls = [];
	public $group_controls = [];
	public static $namespace = '\\DynamicVisibilityForElementor\\Controls\\';

	public function __construct() {
		$this->init();
	}

	public function init() {
		$this->controls = $this->get_controls();
	}

	public function get_controls() {
		$controls['ooo_query'] = 'Control_OOO_Query';

		return $controls;
	}

	/**
	* On Controls Registered
	*
	* @since 0.0.1
	*
	* @access public
	*/
	public function on_controls_registered() {
		$this->register_controls();
	}

	/**
	* On Controls Registered
	*
	* @since 1.0.4
	*
	* @access public
	*/
	public function register_controls() {
		$controls_manager = \Elementor\Plugin::$instance->controls_manager;

		foreach ( $this->controls as $key => $name ) {
			$class = self::$namespace . $name;
			if ( version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
				/**
				 * @var \Elementor\Base_Control $new_class
				 */
				$new_class = new $class();
				$controls_manager->register( $new_class );
			} else {
				$controls_manager->register_control( $key, new $class() );
			}
		}
	}
}
