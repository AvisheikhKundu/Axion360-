<?php
namespace DynamicVisibilityForElementor;

trait Options {

	/**
	 * Get Dynamic Tags Categories
	 *
	 * @return array<string>
	 */
	public static function get_dynamic_tags_categories() {
		return [
			'base',
			'text',
			'url',
			'number',
			'post_meta',
			'date',
			'datetime',
			'media',
			'image',
			'gallery',
			'color',
		];
	}

	/**
	 * Compare options
	 *
	 * @return array<string,mixed>
	 */
	public static function compare_options() {
		return [
			'not' => [
				'title' => esc_html__( 'Not set or empty', 'dynamic-visibility-for-elementor' ),
				'icon' => 'eicon-circle-o',
			],
			'isset' => [
				'title' => esc_html__( 'Valorized with any value', 'dynamic-visibility-for-elementor' ),
				'icon' => 'eicon-dot-circle-o',
			],
			'lt' => [
				'title' => esc_html__( 'Less than', 'dynamic-visibility-for-elementor' ),
				'icon' => 'fa fa-angle-left',
			],
			'gt' => [
				'title' => esc_html__( 'Greater than', 'dynamic-visibility-for-elementor' ),
				'icon' => 'fa fa-angle-right',
			],
			'contain' => [
				'title' => esc_html__( 'Contains', 'dynamic-visibility-for-elementor' ),
				'icon' => 'eicon-check',
			],
			'not_contain' => [
				'title' => esc_html__( 'Doesn\'t contain', 'dynamic-visibility-for-elementor' ),
				'icon' => 'eicon-close',
			],
			'in_array' => [
				'title' => esc_html__( 'In Array', 'dynamic-visibility-for-elementor' ),
				'icon' => 'fa fa-bars',
			],
			'value' => [
				'title' => esc_html__( 'Equal to', 'dynamic-visibility-for-elementor' ),
				'icon' => 'fa fa-circle',
			],
			'not_value' => [
				'title' => esc_html__( 'Not Equal to', 'dynamic-visibility-for-elementor' ),
				'icon' => 'eicon-exchange',
			],
		];
	}

	/**
	 * Get Post Order By Options
	 *
	 * @return array<string,string>
	 */
	public static function get_post_orderby_options() {
		return [
			'ID' => esc_html__( 'Post ID', 'dynamic-visibility-for-elementor' ),
			'author' => esc_html__( 'Post Author', 'dynamic-visibility-for-elementor' ),
			'title' => esc_html__( 'Title', 'dynamic-visibility-for-elementor' ),
			'date' => esc_html__( 'Date', 'dynamic-visibility-for-elementor' ),
			'modified' => esc_html__( 'Last Modified Date', 'dynamic-visibility-for-elementor' ),
			'parent' => esc_html__( 'Parent ID', 'dynamic-visibility-for-elementor' ),
			'rand' => esc_html__( 'Random', 'dynamic-visibility-for-elementor' ),
			'comment_count' => esc_html__( 'Comment Count', 'dynamic-visibility-for-elementor' ),
			'menu_order' => esc_html__( 'Menu Order', 'dynamic-visibility-for-elementor' ),
			'meta_value' => esc_html__( 'Meta Value', 'dynamic-visibility-for-elementor' ),
			'none' => esc_html__( 'None', 'dynamic-visibility-for-elementor' ),
			'name' => esc_html__( 'Name', 'dynamic-visibility-for-elementor' ),
			'type' => esc_html__( 'Type', 'dynamic-visibility-for-elementor' ),
			'relevance' => esc_html__( 'Relevance', 'dynamic-visibility-for-elementor' ),
			'post__in' => esc_html__( 'Preserve Post ID order given', 'dynamic-visibility-for-elementor' ),
		];
	}

	/**
	 * Get Order By Meta Value - Types
	 *
	 * @return array<string,string>
	 */
	public static function get_post_orderby_meta_value_types() {
		return [
			'NUMERIC' => esc_html__( 'Numeric', 'dynamic-visibility-for-elementor' ),
			'BINARY' => esc_html__( 'Binary', 'dynamic-visibility-for-elementor' ),
			'CHAR' => esc_html__( 'Character', 'dynamic-visibility-for-elementor' ),
			'DATE' => esc_html__( 'Date', 'dynamic-visibility-for-elementor' ),
			'DATETIME' => esc_html__( 'DateTime', 'dynamic-visibility-for-elementor' ),
			'DECIMAL' => esc_html__( 'Decimal', 'dynamic-visibility-for-elementor' ),
			'SIGNED' => esc_html__( 'Signed', 'dynamic-visibility-for-elementor' ),
			'TIME' => esc_html__( 'Time', 'dynamic-visibility-for-elementor' ),
			'UNSIGNED' => esc_html__( 'Unsigned', 'dynamic-visibility-for-elementor' ),
		];
	}

	/**
	 * Get Term Order By Options
	 *
	 * @return array<string,string>
	 */
	public static function get_term_orderby_options() {
		return [
			'parent' => esc_html__( 'Parent', 'dynamic-visibility-for-elementor' ),
			'count' => esc_html__( 'Count (number of associated posts)', 'dynamic-visibility-for-elementor' ),
			'term_order' => esc_html__( 'Order', 'dynamic-visibility-for-elementor' ),
			'name' => esc_html__( 'Name', 'dynamic-visibility-for-elementor' ),
			'slug' => esc_html__( 'Slug', 'dynamic-visibility-for-elementor' ),
			'term_group' => esc_html__( 'Group', 'dynamic-visibility-for-elementor' ),
			'term_id' => 'ID',
		];
	}

	/**
	 * Get Public Taxonomies
	 *
	 * @return array<string,string>
	 */
	public static function get_public_taxonomies() {
		$taxonomies = get_taxonomies( [ 'public' => true ] );

		$taxonomy_array = [];

		foreach ( $taxonomies as $taxonomy ) {
			$taxonomy_object = get_taxonomy( $taxonomy );
			$taxonomy_array[ $taxonomy ] = sanitize_text_field( $taxonomy_object->labels->name );
		}

		return $taxonomy_array;
	}

	public static function get_anim_timing_functions() {
		$tf_p = [
			'linear' => esc_html__( 'Linear', 'dynamic-visibility-for-elementor' ),
			'ease' => esc_html__( 'Ease', 'dynamic-visibility-for-elementor' ),
			'ease-in' => esc_html__( 'Ease In', 'dynamic-visibility-for-elementor' ),
			'ease-out' => esc_html__( 'Ease Out', 'dynamic-visibility-for-elementor' ),
			'ease-in-out' => esc_html__( 'Ease In Out', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.755, 0.05, 0.855, 0.06)' => esc_html__( 'easeInQuint', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.23, 1, 0.32, 1)' => esc_html__( 'easeOutQuint', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.86, 0, 0.07, 1)' => esc_html__( 'easeInOutQuint', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.6, 0.04, 0.98, 0.335)' => esc_html__( 'easeInCirc', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.075, 0.82, 0.165, 1)' => esc_html__( 'easeOutCirc', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.785, 0.135, 0.15, 0.86)' => esc_html__( 'easeInOutCirc', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.95, 0.05, 0.795, 0.035)' => esc_html__( 'easeInExpo', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.19, 1, 0.22, 1)' => esc_html__( 'easeOutExpo', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(1, 0, 0, 1)' => esc_html__( 'easeInOutExpo', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.6, -0.28, 0.735, 0.045)' => esc_html__( 'easeInBack', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.175, 0.885, 0.32, 1.275)' => esc_html__( 'easeOutBack', 'dynamic-visibility-for-elementor' ),
			'cubic-bezier(0.68, -0.55, 0.265, 1.55)' => esc_html__( 'easeInOutBack', 'dynamic-visibility-for-elementor' ),
		];
		return $tf_p;
	}

	public static function number_format_currency() {
		$nf_c = [
			'en-US' => esc_html__( 'English (US)', 'dynamic-visibility-for-elementor' ),
			'af-ZA' => esc_html__( 'Afrikaans', 'dynamic-visibility-for-elementor' ),
			'sq-AL' => esc_html__( 'Albanian', 'dynamic-visibility-for-elementor' ),
			'ar-AR' => esc_html__( 'Arabic', 'dynamic-visibility-for-elementor' ),
			'hy-AM' => esc_html__( 'Armenian', 'dynamic-visibility-for-elementor' ),
			'ay-BO' => esc_html__( 'Aymara', 'dynamic-visibility-for-elementor' ),
			'az-AZ' => esc_html__( 'Azeri', 'dynamic-visibility-for-elementor' ),
			'eu-ES' => esc_html__( 'Basque', 'dynamic-visibility-for-elementor' ),
			'be-BY' => esc_html__( 'Belarusian', 'dynamic-visibility-for-elementor' ),
			'bn-IN' => esc_html__( 'Bengali', 'dynamic-visibility-for-elementor' ),
			'bs-BA' => esc_html__( 'Bosnian', 'dynamic-visibility-for-elementor' ),
			'en-GB' => esc_html__( 'British English', 'dynamic-visibility-for-elementor' ),
			'bg-BG' => esc_html__( 'Bulgarian', 'dynamic-visibility-for-elementor' ),
			'ca-ES' => esc_html__( 'Catalan', 'dynamic-visibility-for-elementor' ),
			'ck-US' => esc_html__( 'Cherokee', 'dynamic-visibility-for-elementor' ),
			'hr-HR' => esc_html__( 'Croatian', 'dynamic-visibility-for-elementor' ),
			'cs-CZ' => esc_html__( 'Czech', 'dynamic-visibility-for-elementor' ),
			'da-DK' => esc_html__( 'Danish', 'dynamic-visibility-for-elementor' ),
			'nl-NL' => esc_html__( 'Dutch', 'dynamic-visibility-for-elementor' ),
			'nl-BE' => esc_html__( 'Dutch (Belgi?)', 'dynamic-visibility-for-elementor' ),
			'en-UD' => esc_html__( 'English (Upside Down)', 'dynamic-visibility-for-elementor' ),
			'eo-EO' => esc_html__( 'Esperanto', 'dynamic-visibility-for-elementor' ),
			'et-EE' => esc_html__( 'Estonian', 'dynamic-visibility-for-elementor' ),
			'fo-FO' => esc_html__( 'Faroese', 'dynamic-visibility-for-elementor' ),
			'tl-PH' => esc_html__( 'Filipino', 'dynamic-visibility-for-elementor' ),
			'fi-FI' => esc_html__( 'Finland', 'dynamic-visibility-for-elementor' ),
			'fb-FI' => esc_html__( 'Finnish', 'dynamic-visibility-for-elementor' ),
			'fr-CA' => esc_html__( 'French (Canada)', 'dynamic-visibility-for-elementor' ),
			'fr-FR' => esc_html__( 'French (France)', 'dynamic-visibility-for-elementor' ),
			'gl-ES' => esc_html__( 'Galician', 'dynamic-visibility-for-elementor' ),
			'ka-GE' => esc_html__( 'Georgian', 'dynamic-visibility-for-elementor' ),
			'de-DE' => esc_html__( 'German', 'dynamic-visibility-for-elementor' ),
			'el-GR' => esc_html__( 'Greek', 'dynamic-visibility-for-elementor' ),
			'gn-PY' => esc_html__( 'Guaran?', 'dynamic-visibility-for-elementor' ),
			'gu-IN' => esc_html__( 'Gujarati', 'dynamic-visibility-for-elementor' ),
			'he-IL' => esc_html__( 'Hebrew', 'dynamic-visibility-for-elementor' ),
			'hi-IN' => esc_html__( 'Hindi', 'dynamic-visibility-for-elementor' ),
			'hu-HU' => esc_html__( 'Hungarian', 'dynamic-visibility-for-elementor' ),
			'is-IS' => esc_html__( 'Icelandic', 'dynamic-visibility-for-elementor' ),
			'id-ID' => esc_html__( 'Indonesian', 'dynamic-visibility-for-elementor' ),
			'ga-IE' => esc_html__( 'Irish', 'dynamic-visibility-for-elementor' ),
			'it-IT' => esc_html__( 'Italian', 'dynamic-visibility-for-elementor' ),
			'ja-JP' => esc_html__( 'Japanese', 'dynamic-visibility-for-elementor' ),
			'jv-ID' => esc_html__( 'Javanese', 'dynamic-visibility-for-elementor' ),
			'kn-IN' => esc_html__( 'Kannada', 'dynamic-visibility-for-elementor' ),
			'kk-KZ' => esc_html__( 'Kazakh', 'dynamic-visibility-for-elementor' ),
			'km-KH' => esc_html__( 'Khmer', 'dynamic-visibility-for-elementor' ),
			'tl-ST' => esc_html__( 'Klingon', 'dynamic-visibility-for-elementor' ),
			'ko-KR' => esc_html__( 'Korean', 'dynamic-visibility-for-elementor' ),
			'ku-TR' => esc_html__( 'Kurdish', 'dynamic-visibility-for-elementor' ),
			'la-VA' => esc_html__( 'Latin', 'dynamic-visibility-for-elementor' ),
			'lv-LV' => esc_html__( 'Latvian', 'dynamic-visibility-for-elementor' ),
			'fb-LT' => esc_html__( 'Leet Speak', 'dynamic-visibility-for-elementor' ),
			'li-NL' => esc_html__( 'Limburgish', 'dynamic-visibility-for-elementor' ),
			'lt-LT' => esc_html__( 'Lithuanian', 'dynamic-visibility-for-elementor' ),
			'mk-MK' => esc_html__( 'Macedonian', 'dynamic-visibility-for-elementor' ),
			'mg-MG' => esc_html__( 'Malagasy', 'dynamic-visibility-for-elementor' ),
			'ms-MY' => esc_html__( 'Malay', 'dynamic-visibility-for-elementor' ),
			'ml-IN' => esc_html__( 'Malayalam', 'dynamic-visibility-for-elementor' ),
			'mt-MT' => esc_html__( 'Maltese', 'dynamic-visibility-for-elementor' ),
			'mr-IN' => esc_html__( 'Marathi', 'dynamic-visibility-for-elementor' ),
			'mn-MN' => esc_html__( 'Mongolian', 'dynamic-visibility-for-elementor' ),
			'ne-NP' => esc_html__( 'Nepali', 'dynamic-visibility-for-elementor' ),
			'se-NO' => esc_html__( 'Northern S?mi', 'dynamic-visibility-for-elementor' ),
			'nb-NO' => esc_html__( 'Norwegian (bokmal)', 'dynamic-visibility-for-elementor' ),
			'nn-NO' => esc_html__( 'Norwegian (nynorsk)', 'dynamic-visibility-for-elementor' ),
			'ps-AF' => esc_html__( 'Pashto', 'dynamic-visibility-for-elementor' ),
			'fa-IR' => esc_html__( 'Persian', 'dynamic-visibility-for-elementor' ),
			'pl-PL' => esc_html__( 'Polish', 'dynamic-visibility-for-elementor' ),
			'pt-BR' => esc_html__( 'Portuguese (Brazil)', 'dynamic-visibility-for-elementor' ),
			'pt-PT' => esc_html__( 'Portuguese (Portugal)', 'dynamic-visibility-for-elementor' ),
			'pa-IN' => esc_html__( 'Punjabi', 'dynamic-visibility-for-elementor' ),
			'qu-PE' => esc_html__( 'Quechua', 'dynamic-visibility-for-elementor' ),
			'ro-RO' => esc_html__( 'Romanian', 'dynamic-visibility-for-elementor' ),
			'rm-CH' => esc_html__( 'Romansh', 'dynamic-visibility-for-elementor' ),
			'ru-RU' => esc_html__( 'Russian', 'dynamic-visibility-for-elementor' ),
			'sa-IN' => esc_html__( 'Sanskrit', 'dynamic-visibility-for-elementor' ),
			'sr-RS' => esc_html__( 'Serbian', 'dynamic-visibility-for-elementor' ),
			'zh-CN' => esc_html__( 'Simplified Chinese (China)', 'dynamic-visibility-for-elementor' ),
			'sk-SK' => esc_html__( 'Slovak', 'dynamic-visibility-for-elementor' ),
			'sl-SI' => esc_html__( 'Slovenian', 'dynamic-visibility-for-elementor' ),
			'so-SO' => esc_html__( 'Somali', 'dynamic-visibility-for-elementor' ),
			'es-LA' => esc_html__( 'Spanish', 'dynamic-visibility-for-elementor' ),
			'es-CL' => esc_html__( 'Spanish (Chile)', 'dynamic-visibility-for-elementor' ),
			'es-CO' => esc_html__( 'Spanish (Colombia)', 'dynamic-visibility-for-elementor' ),
			'es-MX' => esc_html__( 'Spanish (Mexico)', 'dynamic-visibility-for-elementor' ),
			'es-ES' => esc_html__( 'Spanish (Spain)', 'dynamic-visibility-for-elementor' ),
			'es-VE' => esc_html__( 'Spanish (Venezuela)', 'dynamic-visibility-for-elementor' ),
			'sw-KE' => esc_html__( 'Swahili', 'dynamic-visibility-for-elementor' ),
			'sv-SE' => esc_html__( 'Swedish', 'dynamic-visibility-for-elementor' ),
			'sy-SY' => esc_html__( 'Syriac', 'dynamic-visibility-for-elementor' ),
			'tg-TJ' => esc_html__( 'Tajik', 'dynamic-visibility-for-elementor' ),
			'ta-IN' => esc_html__( 'Tamil', 'dynamic-visibility-for-elementor' ),
			'tt-RU' => esc_html__( 'Tatar', 'dynamic-visibility-for-elementor' ),
			'te-IN' => esc_html__( 'Telugu', 'dynamic-visibility-for-elementor' ),
			'th-TH' => esc_html__( 'Thai', 'dynamic-visibility-for-elementor' ),
			'zh-HK' => esc_html__( 'Traditional Chinese (Hong Kong)', 'dynamic-visibility-for-elementor' ),
			'zh-TW' => esc_html__( 'Traditional Chinese (Taiwan)', 'dynamic-visibility-for-elementor' ),
			'tr-TR' => esc_html__( 'Turkish', 'dynamic-visibility-for-elementor' ),
			'uk-UA' => esc_html__( 'Ukrainian', 'dynamic-visibility-for-elementor' ),
			'ur-PK' => esc_html__( 'Urdu', 'dynamic-visibility-for-elementor' ),
			'uz-UZ' => esc_html__( 'Uzbek', 'dynamic-visibility-for-elementor' ),
			'vi-VN' => esc_html__( 'Vietnamese', 'dynamic-visibility-for-elementor' ),
			'cy-GB' => esc_html__( 'Welsh', 'dynamic-visibility-for-elementor' ),
			'xh-ZA' => esc_html__( 'Xhosa', 'dynamic-visibility-for-elementor' ),
			'yi-DE' => esc_html__( 'Yiddish', 'dynamic-visibility-for-elementor' ),
			'zu-ZA' => esc_html__( 'Zulu', 'dynamic-visibility-for-elementor' ),
		];
		return $nf_c;
	}

	public static function get_gsap_ease() {
		$tf_p = [
			'easeNone' => esc_html__( 'None', 'dynamic-visibility-for-elementor' ),
			'easeIn' => esc_html__( 'In', 'dynamic-visibility-for-elementor' ),
			'easeOut' => esc_html__( 'Out', 'dynamic-visibility-for-elementor' ),
			'easeInOut' => esc_html__( 'InOut', 'dynamic-visibility-for-elementor' ),
		];
		return $tf_p;
	}

	public static function get_gsap_timing_functions() {
		$tf_p = [
			'Power0' => esc_html__( 'Linear', 'dynamic-visibility-for-elementor' ),
			'Power1' => esc_html__( 'Power1', 'dynamic-visibility-for-elementor' ),
			'Power2' => esc_html__( 'Power2', 'dynamic-visibility-for-elementor' ),
			'Power3' => esc_html__( 'Power3', 'dynamic-visibility-for-elementor' ),
			'Power4' => esc_html__( 'Power4', 'dynamic-visibility-for-elementor' ),
			'SlowMo' => esc_html__( 'SlowMo', 'dynamic-visibility-for-elementor' ),
			'Back' => esc_html__( 'Back', 'dynamic-visibility-for-elementor' ),
			'Elastic' => esc_html__( 'Elastic', 'dynamic-visibility-for-elementor' ),
			'Bounce' => esc_html__( 'Bounce', 'dynamic-visibility-for-elementor' ),
			'Circ' => esc_html__( 'Circ', 'dynamic-visibility-for-elementor' ),
			'Expo' => esc_html__( 'Expo', 'dynamic-visibility-for-elementor' ),
			'Sine' => esc_html__( 'Sine', 'dynamic-visibility-for-elementor' ),
		];
		return $tf_p;
	}

	public static function get_anim_in() {
		$anim = [
			[
				'label' => 'Fading',
				'options' => [
					'fadeIn' => 'Fade In',
					'fadeInDown' => 'Fade In Down',
					'fadeInLeft' => 'Fade In Left',
					'fadeInRight' => 'Fade In Right',
					'fadeInUp' => 'Fade In Up',
				],
			],
			[
				'label' => 'Zooming',
				'options' => [
					'zoomIn' => 'Zoom In',
					'zoomInDown' => 'Zoom In Down',
					'zoomInLeft' => 'Zoom In Left',
					'zoomInRight' => 'Zoom In Right',
					'zoomInUp' => 'Zoom In Up',
				],
			],
			[
				'label' => 'Bouncing',
				'options' => [
					'bounceIn' => 'Bounce In',
					'bounceInDown' => 'Bounce In Down',
					'bounceInLeft' => 'Bounce In Left',
					'bounceInRight' => 'Bounce In Right',
					'bounceInUp' => 'Bounce In Up',
				],
			],
			[
				'label' => 'Sliding',
				'options' => [
					'slideInDown' => 'Slide In Down',
					'slideInLeft' => 'Slide In Left',
					'slideInRight' => 'Slide In Right',
					'slideInUp' => 'Slide In Up',
				],
			],
			[
				'label' => 'Rotating',
				'options' => [
					'rotateIn' => 'Rotate In',
					'rotateInDownLeft' => 'Rotate In Down Left',
					'rotateInDownRight' => 'Rotate In Down Right',
					'rotateInUpLeft' => 'Rotate In Up Left',
					'rotateInUpRight' => 'Rotate In Up Right',
				],
			],
			[
				'label' => 'Attention Seekers',
				'options' => [
					'bounce' => 'Bounce',
					'flash' => 'Flash',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'headShake' => 'Head Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
				],
			],
			[
				'label' => 'Light Speed',
				'options' => [
					'lightSpeedIn' => 'Light Speed In',
				],
			],
			[
				'label' => 'Specials',
				'options' => [
					'rollIn' => 'Roll In',
				],
			],
		];
		return $anim;
	}

	public static function get_anim_out() {
		$anim = [
			[
				'label' => 'Fading',
				'options' => [
					'fadeOut' => 'Fade Out',
					'fadeOutDown' => 'Fade Out Down',
					'fadeOutLeft' => 'Fade Out Left',
					'fadeOutRight' => 'Fade Out Right',
					'fadeOutUp' => 'Fade Out Up',
				],
			],
			[
				'label' => 'Zooming',
				'options' => [
					'zoomOut' => 'Zoom Out',
					'zoomOutDown' => 'Zoom Out Down',
					'zoomOutLeft' => 'Zoom Out Left',
					'zoomOutRight' => 'Zoom Out Right',
					'zoomOutUp' => 'Zoom Out Up',
				],
			],
			[
				'label' => 'Bouncing',
				'options' => [
					'bounceOut' => 'Bounce Out',
					'bounceOutDown' => 'Bounce Out Down',
					'bounceOutLeft' => 'Bounce Out Left',
					'bounceOutRight' => 'Bounce Out Right',
					'bounceOutUp' => 'Bounce Out Up',
				],
			],
			[
				'label' => 'Sliding',
				'options' => [
					'slideOutDown' => 'Slide Out Down',
					'slideOutLeft' => 'Slide Out Left',
					'slideOutRight' => 'Slide Out Right',
					'slideOutUp' => 'Slide Out Up',
				],
			],
			[
				'label' => 'Rotating',
				'options' => [
					'rotateOut' => 'Rotate Out',
					'rotateOutDownLeft' => 'Rotate Out Down Left',
					'rotateOutDownRight' => 'Rotate Out Down Right',
					'rotateOutUpLeft' => 'Rotate Out Up Left',
					'rotateOutUpRight' => 'Rotate Out Up Right',
				],
			],
			[
				'label' => 'Attention Seekers',
				'options' => [
					'bounce' => 'Bounce',
					'flash' => 'Flash',
					'pulse' => 'Pulse',
					'rubberBand' => 'Rubber Band',
					'shake' => 'Shake',
					'headShake' => 'Head Shake',
					'swing' => 'Swing',
					'tada' => 'Tada',
					'wobble' => 'Wobble',
					'jello' => 'Jello',
				],
			],
			[
				'label' => 'Light Speed',
				'options' => [
					'lightSpeedOut' => 'Light Speed Out',
				],
			],
			[
				'label' => 'Specials',
				'options' => [
					'rollOut' => 'Roll Out',
				],
			],
		];
		return $anim;
	}

	public static function get_anim_open() {
		$anim_p = [
			'noneIn' => _x( 'None', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'enterFromFade' => _x( 'Fade', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'enterFromLeft' => _x( 'Left', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'enterFromRight' => _x( 'Right', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'enterFromTop' => _x( 'Top', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'enterFromBottom' => _x( 'Bottom', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'enterFormScaleBack' => _x( 'Zoom Back', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'enterFormScaleFront' => _x( 'Zoom Front', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipInLeft' => _x( 'Flip Left', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipInRight' => _x( 'Flip Right', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipInTop' => _x( 'Flip Top', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipInBottom' => _x( 'Flip Bottom', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
		];

		return $anim_p;
	}

	public static function get_anim_close() {
		$anim_p = [
			'noneOut' => _x( 'None', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'exitToFade' => _x( 'Fade', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'exitToLeft' => _x( 'Left', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'exitToRight' => _x( 'Right', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'exitToTop' => _x( 'Top', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'exitToBottom' => _x( 'Bottom', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'exitToScaleBack' => _x( 'Zoom Back', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'exitToScaleFront' => _x( 'Zoom Front', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipOutLeft' => _x( 'Flip Left', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipOutRight' => _x( 'Flip Right', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipOutTop' => _x( 'Flip Top', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
			'flipOutBottom' => _x( 'Flip Bottom', 'Ajax Page', 'dynamic-visibility-for-elementor' ),
		];
		return $anim_p;
	}

	public static function bootstrap_button_sizes() {
		return [
			'xs' => esc_html__( 'Extra Small', 'dynamic-visibility-for-elementor' ),
			'sm' => esc_html__( 'Small', 'dynamic-visibility-for-elementor' ),
			'md' => esc_html__( 'Medium', 'dynamic-visibility-for-elementor' ),
			'lg' => esc_html__( 'Large', 'dynamic-visibility-for-elementor' ),
			'xl' => esc_html__( 'Extra Large', 'dynamic-visibility-for-elementor' ),
		];
	}

	public static function get_sql_operators() {
		$compare = self::get_wp_meta_compare();
		$compare['IS NULL'] = 'IS NULL';
		$compare['IS NOT NULL'] = 'IS NOT NULL';
		return $compare;
	}

	public static function get_wp_meta_compare() {
		return [
			'=' => '=',
			'>' => '&gt;',
			'>=' => '&gt;=',
			'<' => '&lt;',
			'<=' => '&lt;=',
			'!=' => '!=',
			'LIKE' => 'LIKE',
			'RLIKE' => 'RLIKE',
			'NOT LIKE' => 'NOT LIKE',
			'IN' => 'IN (...)',
			'NOT IN' => 'NOT IN (...)',
			'BETWEEN' => 'BETWEEN',
			'NOT BETWEEN' => 'NOT BETWEEN',
			'EXISTS' => 'EXISTS',
			'NOT EXISTS' => 'NOT EXISTS',
			'REGEXP' => 'REGEXP',
			'NOT REGEXP' => 'NOT REGEXP',
		];
	}

	public static function get_gravatar_styles() {
		$gravatar_images = array(
			'404' => esc_html__( '404 (empty with fallback)', 'dynamic-visibility-for-elementor' ),
			'retro' => esc_html__( '8bit', 'dynamic-visibility-for-elementor' ),
			'monsterid' => esc_html__( 'Monster (Default)', 'dynamic-visibility-for-elementor' ),
			'wavatar' => esc_html__( 'Cartoon face', 'dynamic-visibility-for-elementor' ),
			'indenticon' => esc_html__( 'The Quilt', 'dynamic-visibility-for-elementor' ),
			'mp' => esc_html__( 'Mystery', 'dynamic-visibility-for-elementor' ),
			'mm' => esc_html__( 'Mystery Man', 'dynamic-visibility-for-elementor' ),
			'robohash' => esc_html__( 'RoboHash', 'dynamic-visibility-for-elementor' ),
			'blank' => esc_html__( 'Transparent GIF', 'dynamic-visibility-for-elementor' ),
			'gravatar_default' => esc_html__( 'The Gravatar logo', 'dynamic-visibility-for-elementor' ),
		);
		return $gravatar_images;
	}

	public static function get_post_formats() {
		return [
			'standard' => esc_html__( 'Standard', 'dynamic-visibility-for-elementor' ),
			'aside' => esc_html__( 'Aside', 'dynamic-visibility-for-elementor' ),
			'chat' => esc_html__( 'Chat', 'dynamic-visibility-for-elementor' ),
			'gallery' => esc_html__( 'Gallery', 'dynamic-visibility-for-elementor' ),
			'link' => esc_html__( 'Link', 'dynamic-visibility-for-elementor' ),
			'image' => esc_html__( 'Image', 'dynamic-visibility-for-elementor' ),
			'quote' => esc_html__( 'Quote', 'dynamic-visibility-for-elementor' ),
			'status' => esc_html__( 'Status', 'dynamic-visibility-for-elementor' ),
			'video' => esc_html__( 'Video', 'dynamic-visibility-for-elementor' ),
			'audio' => esc_html__( 'Audio', 'dynamic-visibility-for-elementor' ),
		];
	}

	public static function get_button_sizes() {
		return [
			'xs' => esc_html__( 'Extra Small', 'dynamic-visibility-for-elementor' ),
			'sm' => esc_html__( 'Small', 'dynamic-visibility-for-elementor' ),
			'md' => esc_html__( 'Medium', 'dynamic-visibility-for-elementor' ),
			'lg' => esc_html__( 'Large', 'dynamic-visibility-for-elementor' ),
			'xl' => esc_html__( 'Extra Large', 'dynamic-visibility-for-elementor' ),
		];
	}

	public static function get_jquery_display_mode() {
		return [
			'' => esc_html__( 'None', 'dynamic-visibility-for-elementor' ),
			'slide' => esc_html__( 'Slide', 'dynamic-visibility-for-elementor' ),
			'fade' => esc_html__( 'Fade', 'dynamic-visibility-for-elementor' ),
		];
	}

	public static function get_string_comparison() {
		return [
			'empty' => esc_html__( 'empty', 'dynamic-visibility-for-elementor' ),
			'not_empty' => esc_html__( 'not empty', 'dynamic-visibility-for-elementor' ),
			'equal_to' => esc_html__( 'equals to', 'dynamic-visibility-for-elementor' ),
			'not_equal' => esc_html__( 'not equals', 'dynamic-visibility-for-elementor' ),
			'gt' => esc_html__( 'greater than', 'dynamic-visibility-for-elementor' ),
			'ge' => esc_html__( 'greater than or equal', 'dynamic-visibility-for-elementor' ),
			'lt' => esc_html__( 'less than', 'dynamic-visibility-for-elementor' ),
			'le' => esc_html__( 'less than or equal', 'dynamic-visibility-for-elementor' ),
			'contain' => esc_html__( 'contains', 'dynamic-visibility-for-elementor' ),
			'not_contain' => esc_html__( 'not contains', 'dynamic-visibility-for-elementor' ),
			'is_checked' => esc_html__( 'is checked', 'dynamic-visibility-for-elementor' ),
			'not_checked' => esc_html__( 'not checked', 'dynamic-visibility-for-elementor' ),
		];
	}

	/**
	 * Get HTML Tags
	 *
	 * @param array<string> $tags_to_add
	 * @param bool $add_none
	 * @return array<string,string>
	 */
	public static function get_html_tags( array $tags_to_add = [], bool $add_none = false ) {
		$default = [
			'h1' => 'H1',
			'h2' => 'H2',
			'h3' => 'H3',
			'h4' => 'H4',
			'h5' => 'H5',
			'h6' => 'H6',
			'div' => 'div',
			'span' => 'span',
			'p' => 'p',
		];

		if ( $add_none ) {
			$none = [
				'' => esc_html__( 'None', 'dynamic-visibility-for-elementor' ),
			];
			$default = array_merge( $none, $default );
		}

		$tags_to_add = array_combine( $tags_to_add, $tags_to_add );

		return array_merge( $default, $tags_to_add );
	}
}
