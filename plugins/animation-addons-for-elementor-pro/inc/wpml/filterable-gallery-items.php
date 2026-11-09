<?php
/**
 * Filterable Slider – Projects WPML integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Filterable_Gallery_Items extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater control name
	 */
	public function get_items_field() {
		return 'gallery_items';
	}

	/**
	 * Translatable fields inside repeater
	 */
	public function get_fields() {
		return [
			'gallery_item_filter_name',
		];
	}

	/**
	 * Field labels shown in WPML editor
	 */
	protected function get_title( $field ) {
		switch ( $field ) {

			case 'gallery_item_filter_name':
				return __( 'Gallery: Filter Name', 'animation-addons-for-elementor-pro' );

			default:
				return '';
		}
	}

	/**
	 * WPML editor field type
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {

			case 'gallery_item_filter_name':
				return 'LINE';

			default:
				return 'LINE';
		}
	}
}
