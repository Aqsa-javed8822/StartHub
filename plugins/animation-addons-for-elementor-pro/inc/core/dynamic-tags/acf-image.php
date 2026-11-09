<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Core\DynamicTags\Data_Tag;
use Elementor\Controls_Manager;

class AAE_ACF_Image extends Data_Tag {
    use CustomPostIdTrait;
    public function get_name() {
        return 'aae-acf-image';
    }

    public function get_title() {
        return esc_html__( 'ACF Image', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::IMAGE_CATEGORY ];
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
            $options = $this->get_acf_fields_options(); 
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
    }

    public function is_valid_for_widget( $widget_name ) {
        // Add the widget names where this dynamic tag is allowed.
        $allowed_widgets = [ 'image', 'custom-widget-name', 'image-box' ];

        return in_array( $widget_name, $allowed_widgets, true );
    }

    public function get_value( array $options = [] ) {
        $image_data = [
            'id' => null,
            'url' => null,
            'alt' => '',
            'width' => 0,
            'height' => 0,
        ];

        $field_key = $this->get_settings( 'field_key' );
        $taxonomy = $this->get_settings( 'taxonomy' );  
      
        if(!is_null($taxonomy) && $taxonomy !=''){
            $this->tax = $taxonomy;
        }
        if ( empty( $field_key ) ) {
            return $image_data; // Return empty data if no field key is set
        }
        $post_id = $this->get_custom_id();
        $image = get_field( $field_key, $post_id );  
        
        if(!$image){
             $image = get_field( $field_key, 'option' );  
        }

        if ( is_array( $image ) ) {
            $image_data['id'] = isset( $image['ID'] ) ? absint( $image['ID'] ) : null;
            $image_data['url'] = isset( $image['url'] ) ? esc_url( $image['url'] ) : '';
            $image_data['alt'] = isset( $image['alt'] ) ? sanitize_text_field( $image['alt'] ) : '';
            $image_data['width'] = isset( $image['width'] ) ? absint( $image['width'] ) : 0;
            $image_data['height'] = isset( $image['height'] ) ? absint( $image['height'] ) : 0;
        } elseif ( is_string( $image ) ) {
            $image_data['url'] = esc_url( $image );
        } elseif ( is_int( $image ) ) {
            $image_url = wp_get_attachment_url( $image );
            $image_data['url'] = esc_url( $image_url );
            $image_data['id'] = $image;
        }

        return $image_data;
    }

    private function get_acf_fields_options() {
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }

        $post_id = get_the_ID(); // Get the current post ID
        if ( ! $post_id ) {
            return [];
        }
        $post_id = $this->get_custom_id($post_id);
        $fields = get_field_objects( $post_id );
        if ( ! $fields ) {
            return [];
        }

        $options = [];
        foreach ( $fields as $field_key => $field_data ) {
            if ( $field_data['type'] === 'image' ) {
                $options[ $field_key ] = sprintf(
                    '%s (%s)',
                    $field_data['label'],
                    $field_data['type']
                );
            }
        }

        return $options;
    }

    public function get_supported_fields() {
        return [
            'image',
        ];
    }
}
