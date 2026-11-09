<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_Number extends \Elementor\Core\DynamicTags\Tag {
    use CustomPostIdTrait;
    public function get_name() {
        return 'aae-acf-number';
    }

    public function get_title() {
        return esc_html__( 'ACF Number', 'animation-addons-for-elementor-pro' );
    }

    public function get_group() {
        return [ 'aae' ];
    }

    public function get_categories(): array {
        return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY , \Elementor\Modules\DynamicTags\Module::NUMBER_CATEGORY ]; // Can also use `TEXT_CATEGORY` for numeric fields.
    }

    protected function register_controls() {
        // Field Key Dropdown    
        
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

    public function render() {
        if ( ! function_exists( 'get_field' ) ) {
            return; // Ensure ACF is active, but return nothing for compatibility.
        }

        $field_key = $this->get_settings( 'field_key' );

        if ( ! $field_key ) {
            return; // Return nothing if the field key is not selected.
        }
        $post_id = $this->get_custom_id();
        $number = get_field( $field_key, $post_id );
        $taxonomy = $this->get_settings( 'taxonomy' );  
        if(!$number){
           $number = get_field( $field_key, 'option' ); 
        }
        if(!is_null($taxonomy) && $taxonomy !=''){
            $this->tax = $taxonomy;
        }
        if ( empty( $number ) ) {
            return; // Return nothing if the field is empty.
        }

        // Output the number (can be formatted if needed)
        echo esc_html( $number );
    }

    public function get_value( array $options = [] ) {
        if ( ! function_exists( 'get_field' ) ) {
            return ''; // Ensure ACF is active, but return nothing for compatibility.
        }

        $field_key = $this->get_settings( 'field_key' );

        if ( ! $field_key ) {
            return '';
        }
        $post_id = $this->get_custom_id();
        $number = get_field( $field_key ,$post_id );

        if ( empty( $number ) ) {
            return '';
        }

        // Return the number as string
        return esc_html( $number );
    }

    private function get_acf_fields_options() {
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }

        $post_id = get_the_ID(); // Current post ID.
        $post_id = $this->get_custom_id($post_id);
        // Fallback for Elementor templates or archives.
        if ( ! $post_id ) {
            $post_id = apply_filters( 'elementor/dynamic_tags/post_id', 0 );
        }

        $fields = $post_id ? get_field_objects( $post_id ) : [];
        $options = [];

        if ( $fields ) {
            foreach ( $fields as $key => $field ) {
                if ( $field['type'] === 'number' ) {
                    $options[ $key ] = $field['label'];
                }
            }
        }

        // Include Global ACF Fields (Options Page).
        $global_fields = get_field_objects( 'options' );
        if ( $global_fields ) {
            foreach ( $global_fields as $key => $field ) {
                if ( $field['type'] === 'number' ) {
                    $options[ $key ] = '[Global] ' . $field['label'];
                }
            }
        }

        return $options;
    }
}
