<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use Elementor\Core\DynamicTags\Data_Tag;

class AAE_ACF_Gallery extends Data_Tag {
    use CustomPostIdTrait;

    public function get_name() {
        return 'aae-acf-gallery';
    }

    public function get_title() {
        return esc_html__( 'ACF Gallery', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::GALLERY_CATEGORY ];
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

    public function get_value( array $options = [] ) {
        $gallery_data = [];

        $field_key = $this->get_settings( 'field_key' );
        $taxonomy = $this->get_settings( 'taxonomy' );  
      
        if(!is_null($taxonomy) && $taxonomy !=''){
            $this->tax = $taxonomy;           
        }
        if ( empty( $field_key ) ) {
            return $gallery_data; 
        }
            
       
        $post_id = $this->get_custom_id();
        $gallery = get_field( $field_key,$post_id);   
      
        if(!$gallery){
            $gallery = get_field( $field_key,'option');   
        }
        if ( is_array( $gallery ) ) {
            foreach ( $gallery as $image ) {
                // If the gallery item is an array, retrieve multiple properties
                if ( isset( $image['ID'] ) && isset( $image['url'] ) ) {
                    $gallery_data[] = [
                        'id' => absint( $image['ID'] ),
                        'url' => esc_url( $image['url'] ),
                        'alt' => isset( $image['alt'] ) ? sanitize_text_field( $image['alt'] ) : '',
                        'width' => isset( $image['width'] ) ? absint( $image['width'] ) : 0,
                        'height' => isset( $image['height'] ) ? absint( $image['height'] ) : 0,
                    ];
                }else{
                    $gallery_data[] = [
                        'id' => null,
                        'url' => esc_url( $image )                       
                    ];
                }
            }
        }
    
        return $gallery_data;
    }

    private function get_acf_fields_options() {
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }

        $post_id = get_the_ID(); // Get the current post ID
        $post_id = $this->get_custom_id();
        if ( ! $post_id ) {
            return [];
        }

        $fields = get_field_objects( $post_id );
        if ( ! $fields ) {
            return [];
        }

        $options = [];
        foreach ( $fields as $field_key => $field_data ) {
            if ( $field_data['type'] === 'gallery' ) {
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
            'gallery',
        ];
    }
}
