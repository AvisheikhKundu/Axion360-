<?php
namespace DynamicVisibilityForElementor;

use DynamicVisibilityForElementor\Plugin;

trait Pagination {

	

	/**
	 * @param \DynamicVisibilityForElementor\Widgets\WidgetPrototype $element
	 * @param array<string> $widgets_with_pagination
	 * @return boolean
	 *
	 * @copyright Elementor
	 * @license GPLv3
	 */
	protected static function is_valid_widget_for_pagination( $element, $widgets_with_pagination ) {
		return isset( $element['widgetType'] ) && in_array( $element['widgetType'], $widgets_with_pagination, true );
	}
}
