<?php
/**
 * Vertical Marquee integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Vertical_Marquee extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater field name
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'content_list';
	}

	/**
	 * Fields inside repeater to translate
	 *
	 * @return array
	 */
	public function get_fields() {
		return [ 'marquee_content' ];
	}

	/**
	 * Human readable label in WPML editor
	 *
	 * @param string $field
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'marquee_content':
				return __( 'Vertical Marquee: Content', 'animation-addons-for-elementor-pro' );
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
			case 'marquee_content':
				return 'VISUAL';
			default:
				return '';
		}
	}
}
