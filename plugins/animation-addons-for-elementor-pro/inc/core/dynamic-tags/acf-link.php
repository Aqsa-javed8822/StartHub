<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_Link extends \Elementor\Core\DynamicTags\Tag {
    use CustomPostIdTrait;
    public function get_name() {
        return 'aae-acf-link';
    }

    public function get_title() {
        return esc_html__( 'ACF Link', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
    }

    protected function register_controls() {
        $tax = $this->get_cpt_taxonomies();    
        
        if(!empty($tax)){
            $this->add_control(
                'field_key',
                [
                    'label' => __('Field Key', 'animation-addons-for-elementor-pro'),
                    'type' => \Elementor\Controls_Manager::TEXT,                   
                    'default' => '',
                ]
            );
            $this->add_control(
                'taxonomy',
                [
                    'label' => __('Taxonomy', 'animation-addons-for-elementor-pro'),
                    'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for search functionality.
                    'options' => $tax,               
                    'default' => '',
                ]
            );
        }else{          
            
            $options = $this->get_acf_link_fields(); 
            if(!empty($options)){            
                $this->add_control(
                    'field_key',
                    [
                        'label' => __( 'Field Key', 'animation-addons-for-elementor-pro' ),
                        'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for search functionality.
                        'options' => $options,
                        'description' => __( 'Search and select an ACF field.', 'animation-addons-for-elementor-pro' ),
                    ]
                );
            }else{
                $this->add_control(
                    'field_key',
                    [
                        'label' => __('Field Key', 'animation-addons-for-elementor-pro'),
                        'type' => \Elementor\Controls_Manager::TEXT,                   
                        'default' => '',
                    ]
                );
            }
        }

        // Fallback Text
        $this->add_control(
            'fallback_text',
            [
                'label' => __( 'Fallback Text', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'description' => __( 'Text to display if the link field is empty.', 'animation-addons-for-elementor-pro' ),
            ]
        );
    }

    /**
     * Get ACF Link Fields
     * Dynamically retrieves all ACF fields of type 'link' for the current post type.
     *
     * @return array
     */
    private function get_acf_link_fields() {
        if ( ! function_exists( 'acf_get_field_groups' ) || ! function_exists( 'acf_get_fields' ) ) {
            return [];
        }

        $field_groups = acf_get_field_groups( [
            'post_type' => get_post_type($this->get_custom_id()),
        ] );

        $link_fields = [];

        foreach ( $field_groups as $group ) {
            $fields = acf_get_fields( $group['key'] );
            if ( is_array( $fields ) ) {
                foreach ( $fields as $field ) {
                    if ( $field['type'] === 'link' ) {
                        $link_fields[ $field['key'] ] = $field['label'];
                    }
                }
            }
        }

        return $link_fields;
    }

    public function render() {
    
        if ( ! function_exists( 'get_field' ) ) {
            echo esc_html__( 'ACF plugin is not active.', 'animation-addons-for-elementor-pro' );
            return;
        }

        $field_key     = $this->get_settings( 'field_key' );
        $fallback_text = $this->get_settings( 'fallback_text' );
        $taxonomy = $this->get_settings( 'taxonomy' );  
      
        if(!is_null($taxonomy) && $taxonomy !=''){
            $this->tax = $taxonomy;
        }
        if ( empty( $field_key ) ) {
            if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
                echo esc_html__( 'No field selected.', 'animation-addons-for-elementor-pro' );
            }
            return;
        }

        $post_id = $this->get_custom_id();
        $link    = get_field( $field_key, $post_id );

        if(!$link){
            $link    = get_field( $field_key, 'option' );  
        }
        
        if ( empty( $link ) ) {
            if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
                echo esc_html( $fallback_text ?: __( 'No link available.', 'animation-addons-for-elementor-pro' ) );
            }
            return;
        }

        // Display the link
        if ( is_array( $link ) ) {
            $url    = $link['url'] ?? '';
            $title  = $link['title'] ?? $url;
            $target = $link['target'] ?? '_self';

            printf(
                '<a href="%s" target="%s">%s</a>',
                esc_url( $url ),
                esc_attr( $target ),
                esc_html( $title )
            );
        } else {
            echo esc_html( $link );
        }
    }
}
