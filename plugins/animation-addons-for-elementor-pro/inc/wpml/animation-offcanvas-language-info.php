<?php
/**
 * Animated Offcanvas – Language Info (WPML)
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Animated_Offcanvas_Language_Info extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'language_info';
	}

	public function get_fields() {
		return [
			'list_title',
			'link',
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'list_title':
				return __( 'Offcanvas: Language Title', 'animation-addons-for-elementor' );

			case 'link':
				return __( 'Offcanvas: Language Link', 'animation-addons-for-elementor' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		return $field === 'link' ? 'LINK' : 'LINE';
	}
}
