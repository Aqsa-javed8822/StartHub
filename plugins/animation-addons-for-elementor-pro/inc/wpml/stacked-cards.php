<?php
/**
 * Stacked Cards WPML integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Stacked_Cards extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater field name
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'card_list';
	}

	/**
	 * Fields inside repeater
	 *
	 * @return array
	 */
	public function get_fields() {
		return [
			'title',
			'text',
			'link',
			'btn_link',
		];
	}

	/**
	 * Label shown in WPML String Translation
	 *
	 * @param string $field
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Stacked Card: Title', 'animation-addons-for-elementor' );

			case 'text':
				return __( 'Stacked Card: Text', 'animation-addons-for-elementor' );

			case 'link':
				return __( 'Stacked Card: Link', 'animation-addons-for-elementor' );

			case 'btn_link':
				return __( 'Stacked Card: Button Link', 'animation-addons-for-elementor' );

			default:
				return '';
		}
	}

	/**
	 * Editor type for WPML
	 *
	 * @param string $field
	 * @return string
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'text':
				return 'AREA';

			case 'link':
			case 'btn_link':
				return 'LINK';

			default:
				return 'LINE';
		}
	}
}
