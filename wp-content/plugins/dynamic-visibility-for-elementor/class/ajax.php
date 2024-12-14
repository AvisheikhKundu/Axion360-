<?php
namespace DynamicVisibilityForElementor;

use Elementor\Controls_Manager;
use Elementor\Core\Common\Modules\Ajax\Module as Elementor_Ajax;
use Elementor\Widget_Base;
use Elementor\Core\Base\Module;
use Elementor\TemplateLibrary\Source_Local;

class Ajax {

	public $query_control;

	public function __construct() {
		$this->init();
	}

	function init() {
		add_action( 'wp_ajax_dce_visibility_is_hidden', array( $this, 'dce_visibility_is_hidden' ) );

		// Ajax Select2 autocomplete
		include_once DVE_PATH . '/modules/query-control/module.php';
		$this->query_control = new \DynamicVisibilityForElementor\Modules\QueryControl\Module();
	}

	function dce_visibility_is_hidden() {
		// The $_REQUEST contains all the data sent via ajax
		if ( isset( $_REQUEST['element_id'] ) && isset( $_REQUEST['post_id'] ) ) {
			$element_id = sanitize_text_field( $_REQUEST['element_id'] );
			$post_id = intval( $_REQUEST['post_id'] );
			$settings = Helper::get_settings_by_id( $element_id, $post_id );
			if ( isset( $settings['enabled_visibility'] ) && $settings['enabled_visibility'] ) {
				if ( Extensions\DynamicVisibility::is_hidden( $settings ) ) {
					echo $element_id;
					wp_die();
				}
			}
		}

		echo '0';
		// Always die in functions echoing ajax content
		wp_die(); // this is required to terminate immediately and return a proper response
	}
}
