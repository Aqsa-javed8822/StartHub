<?php
/**
 * Scroll Motion Cards WPML integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Scroll_Elements extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater field name
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'scroll_items';
	}

	/**
	 * Fields inside repeater to translate
	 *
	 * @return array
	 */
	public function get_fields() {
		return [
			'scroll_title',
			'scroll_content',
		];
	}

	/**
	 * Field label shown in WPML String Translation
	 *
	 * @param string $field
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'scroll_title':
				return __( 'Scroll Elements: Title', 'animation-addons-for-elementor-pro' );

			case 'scroll_content':
				return __( 'Scroll Elements: Content', 'animation-addons-for-elementor-pro' );

			default:
				return '';
		}
	}

	/**
	 * Editor type
	 *
	 * @param string $field
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'scroll_title':
				return 'LINE';

			case 'scroll_content':
				return 'VISUAL';

			default:
				return '';
		}
	}
}
