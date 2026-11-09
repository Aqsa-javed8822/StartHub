<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_Taxonomy extends \Elementor\Core\DynamicTags\Tag {
    use CustomPostIdTrait;
    public function get_name() {
        return 'aae-acf-taxonomy';
    }

    public function get_title() {
        return esc_html__( 'ACF Taxonomy Field', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        // Field Key Dropdown
        $this->add_control(
            'field_key',
            [
                'label' => __( 'Field Key', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'options' => $this->get_acf_taxonomy_fields(),
                'description' => __( 'Select an ACF taxonomy field.', 'animation-addons-for-elementor-pro' ),
            ]
        );

        // Term Display Options
        $this->add_control(
            'term_display',
            [
                'label' => __( 'Term Display', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'name'    => __( 'Term Name', 'animation-addons-for-elementor-pro' ),
                    'links'   => __( 'Term Links', 'animation-addons-for-elementor-pro' ),
                    'count'   => __( 'Term Count', 'animation-addons-for-elementor-pro' )            
                ],
                'default' => 'name',
            ]
        );

        // Separator for Multiple Terms
        $this->add_control(
            'separator',
            [
                'label' => __( 'Separator', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => ', ',
                'description' => __( 'Separator for multiple terms.', 'animation-addons-for-elementor-pro' ),
            ]
        );
    }

    public function render() {
        if ( ! function_exists( 'get_field' ) ) {
            echo esc_html__( 'ACF plugin is not active.', 'animation-addons-for-elementor-pro' );
            return;
        }

        $field_key = $this->get_settings( 'field_key' );
        $term_display = $this->get_settings( 'term_display' );
        $separator = $this->get_settings( 'separator' );

        if ( ! $field_key ) {
            if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
                 echo esc_html__( 'Taxonomy Field key is not selected.', 'animation-addons-for-elementor-pro' );
            }
            return;
        }

        $post_id = $this->get_custom_id();
        $terms = get_field( $field_key, $post_id );     
        if ( empty( $terms ) ) {            
            return;
        }
       
        // If terms are returned as IDs, fetch term objects using term IDs
        if ( is_array( $terms ) && isset( $terms[0] ) && is_int( $terms[0] ) ) {
            $terms = array_map( 'get_term', $terms );
        }

        // Prepare output based on return value
        $output = [];
        if ( is_array( $terms ) ) {
            foreach ( $terms as $term ) {
                $output[] = $this->get_term_output( $term, $term_display );
            }
        } elseif ( is_int( $terms ) ) {
            $terms = get_term($terms);
            $output[] = $this->get_term_output( $terms, $term_display );
        } elseif ( is_object( $terms ) ) {
            $output[] = $this->get_term_output( $terms, $term_display );
        }

        echo implode( esc_html( $separator ), $output );
    }

    private function get_term_output( $term, $term_display ) {
        switch ( $term_display ) {
            case 'links':
                return sprintf(
                    '<a href="%s">%s</a>',
                    esc_url( get_term_link( $term ) ),
                    esc_html( $term->name )
                );
            case 'count':
                return sprintf(
                    '%s (%d)',
                    esc_html( $term->name ),
                    intval( $term->count )
                );         
            case 'name':
            default:
                return esc_html( $term->name );
        }
    }

    private function get_acf_taxonomy_fields() {
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }
        $post_id = $this->get_custom_id();
        
        $fields = get_field_objects( $post_id );
       
        $options = [];

        if ( $fields ) {
            foreach ( $fields as $key => $field ) {
                if ( $field['type'] === 'taxonomy' ) {
                    $options[ $key ] = sprintf( '%s (%s)', $field['label'], $field['type'] );
                }
            }
        }

        return $options;
    }
}
