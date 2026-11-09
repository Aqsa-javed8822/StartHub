<?php
/**
 * Animated Offcanvas – Contact Info (WPML)
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Animated_Offcanvas_Contact_Info extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'contact_info';
	}

	public function get_fields() {
		return [
			'list_title',
			'list_content',
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'list_title':
				return __( 'Offcanvas: Contact Title', 'animation-addons-for-elementor' );

			case 'list_content':
				return __( 'Offcanvas: Contact Content', 'animation-addons-for-elementor' );

			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		return $field === 'list_content' ? 'AREA' : 'LINE';
	}
}
