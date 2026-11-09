<?php
/**
 * Video Box Slider – Repeater integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Video_Box_Slider extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater field name
	 */
	public function get_items_field() {
		return 'video_slides';
	}

	/**
	 * Fields inside repeater
	 */
	public function get_fields() {
		return [
			'title',
			'subtitle',
		];
	}

	/**
	 * Field label shown in WPML String Translation
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'title':
				return __( 'Video Box Slider: Title', 'animation-addons-for-elementor' );

			case 'subtitle':
				return __( 'Video Box Slider: Sub Title', 'animation-addons-for-elementor' );

			default:
				return '';
		}
	}

	/**
	 * Editor type
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'title':
            case 'subtitle':
				return 'LINE';

			default:
				return '';
		}
	}
}
