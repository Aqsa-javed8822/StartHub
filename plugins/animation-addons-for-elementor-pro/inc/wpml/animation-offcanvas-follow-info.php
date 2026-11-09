<?php
/**
 * Animated Offcanvas – Social Links (WPML)
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Animated_Offcanvas_Follow_Info extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'follow_info';
	}

	public function get_fields() {
		return [
			'link',
		];
	}

	protected function get_title( $field ) {
		return __( 'Offcanvas: Social Link', 'animation-addons-for-elementor' );
	}

	protected function get_editor_type( $field ) {
		return 'LINK';
	}
}
