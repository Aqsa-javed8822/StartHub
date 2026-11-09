<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_DateTime extends \Elementor\Core\DynamicTags\Tag {
    use CustomPostIdTrait;
    public function get_name() {
        return 'aae-acf-datetime';
    }

    public function get_title() {
        return esc_html__( 'ACF DateTime', 'animation-addons-for-elementor-pro' );
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

        // Date Format
        $this->add_control(
            'date_format',
            [
                'label' => __( 'Date Format', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'description' => __( 'Use PHP date format. Default: F j, Y.', 'animation-addons-for-elementor-pro' ),
            ]
        );

        // Time Format
        $this->add_control(
            'time_format',
            [
                'label' => __( 'Time Format', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'description' => __( 'Use PHP time format. Default: g:i A.', 'animation-addons-for-elementor-pro' ),
            ]
        );

        // Fallback Text
        $this->add_control(
            'fallback_text',
            [
                'label' => __( 'Fallback Text', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __( 'No date/time available.', 'animation-addons-for-elementor-pro' ),
                'description' => __( 'Text to display if the field value is empty.', 'animation-addons-for-elementor-pro' ),
            ]
        );
    }

    public function render() {
        if ( ! function_exists( 'get_field' ) ) {
            echo esc_html__( 'ACF plugin is not active.', 'animation-addons-for-elementor-pro' );
            return;
        }

        $field_key     = $this->get_settings( 'field_key' );
        $date_format   = $this->get_settings( 'date_format' );
        $time_format   = $this->get_settings( 'time_format' );
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
        $datetime = get_field( $field_key, $post_id );
        
        if(!$datetime){
           $datetime = get_field( $field_key, 'option' ); 
        }

        if (  $datetime == '' ) {
            echo esc_html( $fallback_text );
            return;
        }
        
        if($date_format == '' && $time_format == ''){
            echo esc_html( $datetime);
        }
        
        // Convert DateTime to formatted output
        $timestamp = strtotime( $datetime );
        if ( ! $timestamp ) {
            echo esc_html( $datetime);
            return;
        }

        $formatted_date = date( $date_format, $timestamp );
        $formatted_time = date( $time_format, $timestamp );

        echo esc_html( "{$formatted_date} {$formatted_time}" );
    }

    private function get_acf_fields_options() {
    
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }

        $post_id = $this->get_custom_id();
        $fields = $post_id ? get_field_objects( $post_id ) : [];
        $options = [];

        if ( $fields ) {
            foreach ( $fields as $key => $field ) {               
                if ( in_array( $field['type'], [ 'date_time_picker', 'date_picker', 'time_picker' ], true ) ) {                   
                    $options[ $key ] = sprintf( '%s (%s)', $field['label'], $field['type'] );
                }
            }
        }

        return $options;
    }
}
