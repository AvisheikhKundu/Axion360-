<?php
namespace DynamicVisibilityForElementor;

trait Woo {

	public function get_fields() {
		$fields = array();
		$fields['product'] = [
			'_price' => __( 'Price', 'dynamic-visibility-for-elementor' ),
			'_sale_price' => __( 'Sale Price', 'dynamic-visibility-for-elementor' ),
			'_regular_price' => __( 'Regular Price', 'dynamic-visibility-for-elementor' ),
			'_average_rating' => __( 'Average Rating', 'dynamic-visibility-for-elementor' ),
			'_stock_status' => __( 'Stock Status', 'dynamic-visibility-for-elementor' ),
			'_on_sale' => __( 'On Sale', 'dynamic-visibility-for-elementor' ),
			'_featured' => __( 'Featured', 'dynamic-visibility-for-elementor' ),
			'_product_type' => __( 'Product Type', 'dynamic-visibility-for-elementor' ),
		];
		return $fields;
	}

}
