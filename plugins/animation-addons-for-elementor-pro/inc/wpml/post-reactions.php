<?php
/**
 * Post Reactions Widget WPML integration
 */
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Post_Reactions extends \WPML_Elementor_Module_With_Items {

	/**
	 * Repeater control name
	 */
	public function get_items_field() {
		return 'reactions_list';
	}

	/**
	 * Translatable fields inside repeater
	 */
	public function get_fields() {
		return [
			'reaction_label',
		];
	}

	/**
	 * Field label in WPML editor
	 */
	protected function get_title( $field ) {
		switch ( $field ) {
			case 'reaction_label':
				return __( 'Post Reaction: Label', 'animation-addons-for-elementor' );

			default:
				return '';
		}
	}

	/**
	 * WPML editor type
	 */
	protected function get_editor_type( $field ) {
		switch ( $field ) {
			case 'reaction_label':
				return 'LINE';

			default:
				return 'LINE';
		}
	}
}
