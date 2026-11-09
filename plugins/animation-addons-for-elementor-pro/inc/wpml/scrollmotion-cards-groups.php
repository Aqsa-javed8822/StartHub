<?php
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Scrollmotion_Cards_Groups extends \WPML_Elementor_Module_With_Items {

	public function get_items_field() {
		return 'list_groups';
	}

	public function get_fields() {
		return [
			'group_title',
		];
	}

	protected function get_title( $field ) {
		switch ( $field ) {
			case 'group_title':
				return __( 'Scroll Motion Card: Group Title', 'animation-addons-for-elementor-pro' );
			default:
				return '';
		}
	}

	protected function get_editor_type( $field ) {
		return 'LINE';
	}
}
