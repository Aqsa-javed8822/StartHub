<?php
/**
 * Advanced Mailchimp integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Advanced_Mailchimp extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater field name
	 *
	 * @return string
	 */
	public function get_items_field() {
		return 'additional_fields';
	}

	/**
	 * Fields inside repeater
	 *
	 * @return array
	 */
	public function get_fields() {
		return [
			'field_label',
			'placeholder',
		];
	}

	/**
	 * Human-readable title in WPML editor
	 *
	 * @param string $field
	 * @return string
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'field_label':
				return __( 'Mailchimp: Field Label', 'animation-addons-for-elementor-pro' );

			case 'placeholder':
				return __( 'Mailchimp: Field Placeholder', 'animation-addons-for-elementor-pro' );

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
			case 'field_label':
			case 'placeholder':
				return 'LINE';

			default:
				return '';
		}
	}
}
