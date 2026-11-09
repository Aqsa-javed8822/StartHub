<?php

namespace WCFAddonsPro\Base\Tags;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class AAE_ACF_User extends \Elementor\Core\DynamicTags\Tag {
    use CustomPostIdTrait;
    public function get_name() {
        return 'aae-acf-user';
    }

    public function get_title() {
        return esc_html__( 'ACF User', 'animation-addons-for-elementor-pro' );
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
                'options' => $this->get_acf_user_fields(),
                'description' => __( 'Select an ACF user field.', 'animation-addons-for-elementor-pro' ),
            ]
        );

        // User Display Options
        $this->add_control(
            'user_display',
            [
                'label' => __( 'User Display', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'display_name'      => __( 'Display Name', 'animation-addons-for-elementor-pro' ),
                    'display_name_link' => __( 'Display Name With Link', 'animation-addons-for-elementor-pro' ),
                    'name'              => __( 'User Name', 'animation-addons-for-elementor-pro' ),
                    'email'             => __( 'User Email', 'animation-addons-for-elementor-pro' ),
                    'first'             => __( 'First Name', 'animation-addons-for-elementor-pro' ),
                    'last'              => __( 'Last Name', 'animation-addons-for-elementor-pro' ),
                    'avatar'            => __( 'User Avatar', 'animation-addons-for-elementor-pro' ),
                    'name_link'         => __( 'User Name with Link', 'animation-addons-for-elementor-pro' ),
                ],
                'default' => 'name',
            ]
        );

        // Custom Meta Field Control
        $this->add_control(
            'custom_meta_field',
            [
                'label' => __( 'Custom Meta Field', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'description' => __( 'Enter a custom user meta field key to fetch.', 'animation-addons-for-elementor-pro' ),
            ]
        );

        // Separator for Multiple Users
        $this->add_control(
            'separator',
            [
                'label' => __( 'Separator', 'animation-addons-for-elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => ', ',
                'description' => __( 'Separator for multiple users.', 'animation-addons-for-elementor-pro' ),
            ]
        );
    }

    public function render() {
        if ( ! function_exists( 'get_field' ) ) {
            echo esc_html__( 'ACF plugin is not active.', 'animation-addons-for-elementor-pro' );
            return;
        }

        $field_key = $this->get_settings( 'field_key' );
        $user_display = $this->get_settings( 'user_display' );
        $custom_meta_field = $this->get_settings( 'custom_meta_field' );
        $separator = $this->get_settings( 'separator' );

        if ( ! $field_key ) {
            if(\Elementor\Plugin::$instance->editor->is_edit_mode()){
                echo esc_html__( 'User Field key is not selected.', 'animation-addons-for-elementor-pro' );
            }
           
            return;
        }

        $post_id = $this->get_custom_id();
        $users = get_field( $field_key, $post_id );

        if ( empty( $users ) ) {            
            return;
        }
        
        // If multiple users are selected, process the array
        if ( is_array( $users ) ) {
            $output = [];
            foreach ( $users as $user_id ) {   
            
                if(isset($user_id->data))
                {
                    $id = $user_id->data->ID;
                }elseif(is_int($user_id)){
                    $id = $user_id;
                } else{
                    $id = $user_id['ID'];
                }
                
                $user = get_user_by( 'id', $id );       
                
                if ( $user ) {
                    $output[] = $this->get_user_output( $user, $user_display, $custom_meta_field );
                }
                
            }
           
            echo implode( esc_html( $separator ), $output );
        }elseif ( is_object( $users ) ) {
            $user = $users;          
            echo $this->get_user_output( $user, $user_display, $custom_meta_field );
        }else {
            // For single user (if ACF field returns a single user ID)
            $user = get_user_by( 'id', $users );
            if ( $user ) {
                echo $this->get_user_output( $user, $user_display, $custom_meta_field );
            }
        }
    }

    private function get_user_output( $user, $user_display, $custom_meta_field ) {
        if ( ! empty( $custom_meta_field ) ) {
            // Fetch custom user meta field            
            $meta_value = get_user_meta( $user->ID, $custom_meta_field, true );
            if ( $meta_value ) {
                return esc_html( $meta_value );
            }
        }

        switch ( $user_display ) {
            case 'email':
                return esc_html( $user->user_email );
            case 'first':
                return esc_html( $user->first_name );
            case 'last':
                return esc_html( $user->last_name );
            case 'avatar':
                return get_avatar( $user->ID );
            case 'object':
                return '<pre>' . esc_html( print_r( $user, true ) ) . '</pre>';
            case 'user_id':
                return intval( $user->ID );
            case 'display_name_link':           
            case 'name_link':           
                $user_link = get_author_posts_url( $user->ID ); // User profile URL
                return '<a href="' . esc_url( $user_link ) . '" target="_blank">' . esc_html( $user->display_name ) . '</a>';
            case 'name':            
            default:
                return esc_html( $user->display_name );
        }
    }

    private function get_acf_user_fields() {
        if ( ! function_exists( 'get_field_objects' ) ) {
            return [];
        }
        $post_id = $this->get_custom_id();
        $fields = get_field_objects( $post_id );
        $options = [];

        if ( $fields ) {
            foreach ( $fields as $key => $field ) {
                if ( $field['type'] === 'user' ) {
                    $options[ $key ] = sprintf( '%s (%s)', $field['label'], $field['type'] );
                }
            }
        }

        return $options;
    }
}
