<?php
namespace DynamicVisibilityForElementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Elements {

	public static $elements = [];
	public static $elements_time = [];
	public static $elements_hidden = [];
	public static $elements_categories = [];
	public static $elements_settings = [];
	public static $elementor_data = [];
	public static $elementor_current = false;
	public static $elementor_data_current = '';
	public static $user_can_copy = false;
	public static $user_can_elementor = false;

	public function __construct() {
		add_action( 'elementor/init', [ $this, 'init' ] );
	}

	public function init() {
		if ( ! is_admin() ) {
			// elements report
			add_action( 'elementor/frontend/widget/before_render', array( $this, 'start_element' ), 11, 2 );

			
		}
	}

	

	public static function get_template_ids() {
		$templates = [];

		

		// check with Elementor PRO Theme Builder
		$pro_template_id = Helper::get_theme_builder_template_id();
		if ( $pro_template_id ) {
			$templates['pro'] = $pro_template_id;
		}

		

		return $templates;
	}

	public function start_element( $element = false, $template_id = 0 ) {
		$id = 0;

		if ( is_object( $element ) ) {
			$type = $element->get_type();
			$name = $element->get_name();
			$id = $element->get_id();
			self::$elements_settings[ $id ] = $element->get_settings();
			self::$elementor_current = $element;
		}

		if ( self::$user_can_elementor || isset( $_GET['dce-nav'] ) ) {
			if ( is_string( $element ) || is_array( $element ) || $template_id ) {
				if ( ! $template_id ) {
					if ( is_string( $element ) ) {
						$template_id = Helper::get_template_id_by_html( $element );
					}
					if ( ! $template_id && is_array( $element ) ) {
						$template_id = get_the_ID();
						$template_id = Helper::get_post_id_by_element_data( $element, $template_id );
						self::$elements_settings[ $template_id ] = $element;
					}
				}
				$template = get_post( $template_id );
				if ( $template ) {
					$type = 'template';
					$name = $template->post_name;
					$id = $template->ID;
					$template_id = $id;
					self::$elementor_current = $element;
				}
			}
		}

		if ( $id ) {
			if ( isset( self::$elements[ $type ][ $name ][ $id ] ) ) {
				++self::$elements[ $type ][ $name ][ $id ];
			} else {
				self::$elements[ $type ][ $name ][ $id ] = 1;
			}
		}

		if ( $id ) {
			if ( ! empty( self::$elementor_data_current ) ) {
				self::$elementor_data_current .= ' > ';
			}
			self::$elementor_data_current .= $type . '-' . $id;
			self::$elementor_data[ self::$elementor_data_current ] = $name;
			self::$elements_time[ $id ]['start'] = microtime( true );
		}

		return $element;
	}

	public function end_element( $element = false, $template_id = 0 ) {

		$id = 0;

		if ( is_object( $element ) ) {
			$type = $element->get_type();
			$name = $element->get_name();
			$id = $element->get_id();
		}

		if ( is_string( $element ) || is_array( $element ) || $template_id ) {
			if ( ! $template_id && ! is_array( $element ) ) {
				$template_id = Helper::get_template_id_by_html( $element );
			}
			$template = get_post( $template_id );
			if ( $template ) {
				$type = 'template';
				$name = $template->post_name;
				$id = $template->ID;
				$template_id = $id;
			}
		}

		if ( $id ) {
			$elements = explode( ' > ', self::$elementor_data_current );
			array_pop( $elements );
			self::$elementor_data_current = implode( ' > ', $elements );
			self::$elements_time[ $id ]['end'] = microtime( true );
		}

		return $element;
	}

	public function get_last_template_id() {
		if ( ! empty( self::$elementor_data_current ) ) {
			$pieces = explode( ' > ', self::$elementor_data_current );
			$pieces = array_reverse( $pieces );
			foreach ( $pieces as $key => $value ) {
				list($type, $id) = explode( '-', $value );
				if ( $type == 'template' ) {
					return $id;
				}
			}
		}
		return false;
	}

	

	public function get_widget_by_id( $id ) {
		$name = $this->get_element_name_by_id( $id, 'widget' );
		if ( $name ) {
			$widget = \Elementor\Plugin::instance()->widgets_manager->get_widget_types( $name );
			if ( $widget ) {
				return $widget;
			}
		}
		return false;
	}

	public function get_element_title_by_id( $id, $type, $name, $template_id = 0 ) {
		if ( 'template' === $type ) {
			return esc_html( get_the_title( $id ) );
		} elseif ( 'column' === $type ) {
			return esc_html__( 'Column', 'dynamic-visibility-for-elementor' );
		} elseif ( 'section' === $type ) {
			$settings = Helper::get_elementor_element_settings_by_id( $id, $template_id );
			if ( ! empty( $settings['_title'] ) ) {
				return $settings['_title'];
			}
			return esc_html__( 'Section', 'dynamic-visibility-for-elementor' );
		} elseif ( 'widget' === $type ) {
			if ( $name ) {
				$widget = \Elementor\Plugin::instance()->widgets_manager->get_widget_types( $name );
				if ( $widget ) {
					return $widget->get_title();
				}
			}
		}

		return $name;
	}

	public function get_element_name_by_id( $id, $type ) {
		if ( $type == 'widget' ) {
			foreach ( self::$elements[ $type ] as $name => $ename ) {
				foreach ( $ename as $eid => $ecount ) {
					if ( $eid == $id ) {
						return $name;
					}
				}
			}
		}
		if ( $type == 'template' ) {
			$post = get_post( $id );
			if ( $post ) {
				return $post->post_name;
			}
		}
		return $type;
	}

	public function get_element_link_by_id( $template_id, $type, $id = false ) {
		$edit_link = get_edit_post_link( $template_id );
		$pieces = explode( 'action=', $edit_link, 2 );
		$edit_link = reset( $pieces ) . 'action=elementor';
		if ( $type != 'template' && $id ) {
			$edit_link .= '&element=' . $id;
		}
		return $edit_link;
	}
}
