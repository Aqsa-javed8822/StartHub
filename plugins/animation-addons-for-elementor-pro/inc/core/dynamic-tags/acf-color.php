<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_Color extends \Elementor\Core\DynamicTags\Tag {
    use CustomPostIdTrait;
    public function get_name() {
        return 'aae-acf-color';
    }

    public function get_title() {
        return esc_html__( 'ACF Color', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::COLOR_CATEGORY ];
    }

    protected function register_controls() {
        // Field Key Dropdown
        $this->add_control(
            'field_key',
            [
                'label' => __( 'Field Key', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for better searchability.
                'options' => $this->get_acf_fields_options(),
                'description' => __( 'Search and select an ACF color field.', 'animation-addons-for-elementor-pro' ),
            ]
        );
    }

    public function render() {
        if ( ! function_exists( 'get_field' ) || ! function_exists( 'get_field_objects' ) ) {
            echo esc_html__( 'ACF plugin is not active.', 'animation-addons-for-elementor-pro' );
            return;
        }

        $field_key = $this->get_settings( 'field_key' );
      
        if ( ! $field_key ) {
            echo esc_html__( 'Field key is not selected.', 'animation-addons-for-elementor-pro' );
            return;
        }

        $post_id = $this->get_custom_id();
      
        $color = get_field( $field_key, $post_id );

        if(!$color){
            $color = get_field( $field_key, 'option' );
        }

        if ( ! $color ) {
            echo esc_html__( 'Color field is empty or invalid.', 'animation-addons-for-elementor-pro' );
            return;
        }

        // Output the color.
        echo esc_html( $color );
    }

    private function get_acf_fields_options() {
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }
       
        $post_id = $this->get_custom_id();
        // Fallback for Elementor templates or archives.
        if ( ! $post_id ) {
            $post_id = apply_filters( 'elementor/dynamic_tags/post_id', 0 );
        }

        $fields = $post_id ? get_field_objects( $post_id ) : [];
        $options = [];

        if ( $fields ) {
            foreach ( $fields as $key => $field ) {
                if ( $field['type'] === 'color_picker' ) {
                    $options[ $key ] = $field['label'];
                }
            }
        }

        // Include Global ACF Fields (Options Page).
        $global_fields = get_field_objects( 'options' );
       
        if ( $global_fields ) {
            foreach ( $global_fields as $key => $field ) {
                if ( $field['type'] === 'color_picker' ) {
                    $options[ $key ] = '[Global] ' . $field['label'];
                }
            }
        }

        return $options;
    }
}
