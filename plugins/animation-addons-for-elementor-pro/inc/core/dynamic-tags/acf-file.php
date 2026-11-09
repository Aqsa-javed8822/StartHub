<?php
/**
 * ACF File Dynamic Tag
 * 
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Base\Tags;

use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_File extends Data_Tag_Base {
    
    public function get_name() {
        return 'aae-acf-file';
    }

    public function get_title() {
        return esc_html__( 'ACF File', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories() {
        return [ TagsModule::URL_CATEGORY ];
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
            $this->add_control(
                'field_key',
                [
                    'label' => __( 'Field Key', 'animation-addons-for-elementor-pro' ),
                    'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for search functionality.
                    'options' => $this->get_acf_fields_options(),
                    'description' => __( 'Search and select an ACF field.', 'animation-addons-for-elementor-pro' ),
                ]
            );
        }
    }

    protected function get_value( array $options = [] ) {
        if ( ! function_exists( 'get_field' ) || ! function_exists( 'get_field_objects' ) ) {
            return '';
        }

        $field_key = $this->get_settings( 'field_key' );
        $taxonomy = $this->get_settings( 'taxonomy' );  
      
        if ( ! is_null( $taxonomy ) && $taxonomy !== '' ) {
            $this->tax = $taxonomy;          
        }
        
        if ( ! $field_key ) {
            return '';
        }
        
        $post_id = $this->get_custom_id();
        $file = get_field( $field_key, $post_id );

        if ( ! $file ) {
            $file = get_field( $field_key, 'option' );
        }
        
        if ( empty( $file ) ) {
            return '';
        }

        if ( is_array( $file ) && isset( $file['url'] ) ) {
            return esc_url( $file['url'] );
        }
        
        return esc_url( $file );
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
                if ( $field['type'] === 'file' ) {
                    $options[ $key ] = $field['label'];
                }
            }
        }

        // Include Global ACF Fields (Options Page).
        $global_fields = get_field_objects( 'options' );
        if ( $global_fields ) {
            foreach ( $global_fields as $key => $field ) {
                if ( $field['type'] === 'file' ) {
                    $options[ $key ] = '[Global] ' . $field['label'];
                }
            }
        }

        return $options;
    }
}
