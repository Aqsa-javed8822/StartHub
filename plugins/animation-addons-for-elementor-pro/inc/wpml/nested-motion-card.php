<?php
/**
 * Nested Motion Card – WPML integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || exit;

class Nested_Motion_Card extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater field key
	 */
	public function get_items_field() {
		return 'card_items';
	}

	/**
	 * Fields inside repeater
	 */
	public function get_fields() {
		return [
			'card_title',
		];
	}

	/**
	 * Labels shown in WPML String Translation
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'card_title':
				return __( 'Motion Card: Title', 'animation-addons-for-elementor-pro' );
			default:
				return '';
		}
	}

	/**
	 * Editor type
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'card_title':
				return 'LINE';
			default:
				return '';
		}
	}
}
