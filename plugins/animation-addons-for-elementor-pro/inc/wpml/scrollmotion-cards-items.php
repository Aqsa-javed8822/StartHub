<?php
namespace WCFAddonsPro\INC\WPML\WIDGET;

defined( 'ABSPATH' ) || die();

class Scrollmotion_Cards_Items extends \WPML_Elementor_Module_With_Items {

    public function get_items_field() {
        return 'list_groups'; // IMPORTANT
    }

    public function get_fields() {
        return [ 'list_title' ];
    }

    protected function get_title( $field ) {
        return __( 'Scroll Motion Card: Item Title', 'animation-addons-for-elementor-pro' );
    }

    protected function get_editor_type( $field ) {
        return 'LINE';
    }

    /**
     * OVERRIDE to extract nested repeater items
     */
    public function get_items( $element ) {

        $groups = $element['settings']['list_groups'] ?? [];
        $items  = [];

        foreach ( $groups as $group ) {
            if ( empty( $group['items'] ) ) {
                continue;
            }

            foreach ( $group['items'] as $item ) {
                $items[] = $item;
            }
        }

        return $items;
    }
}
