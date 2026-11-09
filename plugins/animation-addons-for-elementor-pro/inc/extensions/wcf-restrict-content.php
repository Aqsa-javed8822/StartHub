<?php

namespace WCFAddonsPro\Extensions;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
   
    class Content_Restriction_Container_Extension {
           
        public $tax = null;    
        public function __construct() {
            // Add new controls to the Container element
            add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'add_restriction_controls' ] );
    
            // Apply rendering logic for the Container element
            add_filter( 'elementor/frontend/container/before_render', [ $this, 'apply_content_restriction' ] );
            
            // Widgets         
             
            add_action( 'elementor/element/common/_section_style/after_section_end', [
                $this,
                'add_widgtecontrols_section'
            ], 20 );
            
            add_filter('elementor/frontend/widget/should_render', [$this, 'should_render'] , 13, 2);
        }
        public function get_custom_id($post_id = null)
        {
            // Use the current post ID if none is provided.
            if (is_null($post_id)) {
                $post_id = get_the_ID();
            }
             // Fallback logic for 'wcf-addons-template' post type.
            if (get_post_type($post_id) === 'wcf-addons-template') {
            
                $args = [
                    'numberposts' => 1,
                    'post_type'   => 'post',
                    'orderby'     => 'menu_order',
                    'order'       => 'ASC',
                ];
                
                $meta_type = get_post_meta($post_id, 'wcf-addons-template-meta_type', true);
                $meta_location = get_post_meta($post_id, 'wcf-addons-template-meta_location', true);
               
                if ($meta_type === 'single' && !empty($meta_location)) {
                    $explode = explode('-sing', $meta_location);
                    if (isset($explode[0])) {                 
                        if ($explode[0] !='') {                                       
                            $args['post_type'] = $explode[0];
                            $latest_posts = get_posts($args);      
                            if (!is_wp_error( $latest_posts ) && !empty($latest_posts) && isset($latest_posts[0])) {                           
                                $post_id = $latest_posts[0]->ID;
                            }
                        }
                    }elseif($meta_location==='singulars'){
                        $latest_posts = get_posts($args);
                        // Update $post_id if a valid post is found.
                        if (!is_wp_error( $latest_posts ) && !empty($latest_posts) && isset($latest_posts[0])) {
                            $post_id = $latest_posts[0]->ID;
                        }
                    }
                }elseif ($meta_type === 'archive' && !empty($meta_location) && $this->tax !='') {
                
                    $tax_args = [
                        'taxonomy' => 'category',
                        'orderby'    => 'id', // Order by term ID (creation order).
                        'order'      => 'DESC', // Get the latest one.
                        'number'     => 1, // Limit to 1 result.
                        'hide_empty' => false, // Include terms without posts.
                    ];              
                    
                    if (preg_match('/^(.*?)-/', $meta_location, $matches))
                    {
                    
                        if (!empty($matches[1])) {                                       
                            $tax_args['taxonomy'] = $this->tax;  
                            $taxonomy_term = get_terms($tax_args);  
                           
                            if (!is_wp_error( $taxonomy_term ) && !empty($taxonomy_term)) 
                            {                           
                                $post_id = $taxonomy_term[0];                   
                            } 
                           
                        }
                        
                    }elseif($meta_location==='archives'){
                    
                        $tax_args['taxonomy'] = 'category';                  
                        $taxonomy_term = get_terms($tax_args);  
                        if (!is_wp_error( $taxonomy_term ) && !empty($latest_taxonomy_term)) 
                        {
                            $post_id = $taxonomy_term[0];                   
                        } 
                        
                    }
                }
                
            }elseif(is_tax() ){
                $term = get_queried_object();
                return $term->taxonomy . '_' . $term->term_id;
            }         
          
            return $post_id;
        }
        public function should_render( $should_render, $widget ) {
            $settings = $widget->get_settings_for_display();
        
            // Only apply restrictions if the widget has content protection enabled
            if ( isset( $settings['enable_protection'] ) && $settings['enable_protection'] === 'yes' ) {
                if ( ! $this->check_restriction( $settings ) ) {
                    // If the restrictions are not met, prevent the widget from rendering
                    return false;
                }
            }
            
            return $should_render;
        }
        public function add_widgtecontrols_section( $element ) {
        
        	$element->start_controls_section(
                '_section_wcf_content_protection',
                [
                    'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Content Protection', 'animation-addons-for-elementor-pro' ) ),
                    'tab'   => Controls_Manager::TAB_ADVANCED,
                ]
            );
            
               // Enable Protection
               $element->add_control(
                    'enable_protection',
                    [
                        'label'        => __( 'Enable Protection', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => __( 'No', 'animation-addons-for-elementor-pro' ),
                        'default'      => 'no',
                    ]
                );

                // Restrict by Page Type
                $element->add_control(
                    'restrict_by_page_type',
                    [
                        'label'       => __( 'Restrict by Page Type', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::SELECT2,
                        'multiple'    => true,
                        'options'     => [
                            'front_page'         => __( 'Front Page', 'animation-addons-for-elementor-pro' ),
                            'blog_page'          => __( 'Blog Page', 'animation-addons-for-elementor-pro' ),
                            'single'             => __( 'Single Post', 'animation-addons-for-elementor-pro' ),
                            'page'               => __( 'Page', 'animation-addons-for-elementor-pro' ),
                            'archive'            => __( 'Archive Page', 'animation-addons-for-elementor-pro' ),
                            'search'             => __( 'Search Results Page', 'animation-addons-for-elementor-pro' ),
                            'author'             => __( 'Author Archive', 'animation-addons-for-elementor-pro' ),
                            'taxonomy'           => __( 'Taxonomy Archive', 'animation-addons-for-elementor-pro' ),
                            'custom_post_type'   => __( 'Custom Post Type Archive', 'animation-addons-for-elementor-pro' ),
                        ],
                        'default'     => [],
                        'condition'   => [ 'enable_protection' => 'yes' ],
                    ]
                );

                // Restrict by User Roles
                $element->add_control(
                    'restrict_by_user_role',
                    [
                        'label'       => __( 'Restrict by User Role', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::SELECT2,
                        'multiple'    => true,
                        'options'     => $this->get_user_roles(),
                        'default'     => [],
                        'condition'   => [ 'enable_protection' => 'yes' ],
                    ]
                );
            
                // Restrict by Login Status
                $element->add_control(
                    'restrict_logged_status',
                    [
                        'label'     => __( 'Restrict by Login Status', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                            ''          => __( 'None', 'animation-addons-for-elementor-pro' ),
                            'logged_in' => __( 'Logged In', 'animation-addons-for-elementor-pro' ),
                            'logged_out' => __( 'Logged Out', 'animation-addons-for-elementor-pro' ),
                        ],
                        'default'   => '',
                        'condition' => [ 'enable_protection' => 'yes' ],
                    ]
                );   
                
                $element->add_control(
                    'restrict_recent_visit',
                    [
                        'label'        => __( 'Restrict By Recent Visit post', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => __( 'No', 'animation-addons-for-elementor-pro' ),
                        'default'      => 'no',
                        'condition' => [ 'enable_protection' => 'yes' ],
                    ]
                );              
                $element->add_control(
                    'restrict_acf_fld',
                    [
                        'label'        => __( 'Restrict By Acf Field', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => __( 'No', 'animation-addons-for-elementor-pro' ),
                        'default'      => 'no',
                        'description' => __( 'Check Cookie content exist', 'animation-addons-for-elementor-pro' ),
                        'condition' => [ 'enable_protection' => 'yes' ],
                    ]
                );  
                
                
                $options = $this->get_acf_fields_options();            
                if(!empty($options)){            
                    $element->add_control(
                        'acf_field_key',
                        [
                            'label' => __( 'ACF Field Key', 'animation-addons-for-elementor-pro' ),
                            'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for search functionality.
                            'options' => $options,
                            'description' => __( 'Search and select an ACF field.', 'animation-addons-for-elementor-pro' ),
                            'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                        ]
                    );
                }else{
                    $element->add_control(
                        'acf_field_key',
                        [
                            'label' => __('ACF Field Key', 'animation-addons-for-elementor-pro'),
                            'type' => \Elementor\Controls_Manager::TEXT,                   
                            'default' => '',
                            'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                            
                        ]
                    );
                }
                
                $element->add_control(
                    'acf_operator',
                    [
                        'label'       => __( 'ACF Operator', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::SELECT,
                        'options'     => [
                            '=='           => __( 'Equals (Exact Match)', 'animation-addons-for-elementor-pro' ),
                            '!='           => __( 'Not Equals', 'animation-addons-for-elementor-pro' ),
                            'contains'     => __( 'Contains (Array Overlap)', 'animation-addons-for-elementor-pro' ),
                            'not_contains' => __( 'Does Not Contain (Array Overlap)', 'animation-addons-for-elementor-pro' )
                        ],
                        'default'     => '==',
                        'description' => __( 'Select the comparison operator.', 'animation-addons-for-elementor-pro' ),
                        'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                    ]
                );
                
                $element->add_control(
                    'acf_field_value',
                    [
                        'label' => __('ACF Field Value', 'animation-addons-for-elementor-pro'),
                        'type' => \Elementor\Controls_Manager::TEXT,                   
                        'placeholder' => 'one,1,two,2',
                        'description' => __( 'ex1: one | ex2: one, two, three', 'animation-addons-for-elementor-pro' ),
                        'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                    ]
                );
                
            
            $element->end_controls_section();
        }
        public function add_restriction_controls( $element ) {
        
            $element->start_controls_section(
                'content_restriction_section',
                [
                    'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Content Protection', 'animation-addons-for-elementor-pro' ) ),
                    'tab'   => Controls_Manager::TAB_ADVANCED,
                ]
            );
    
            // Enable Protection
                $element->add_control(
                    'enable_protection',
                    [
                        'label'        => __( 'Enable Protection', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => __( 'No', 'animation-addons-for-elementor-pro' ),
                        'default'      => 'no',
                    ]
                );
    
            // Restrict by Page Type
                $element->add_control(
                    'restrict_by_page_type',
                    [
                        'label'       => __( 'Restrict by Page Type', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::SELECT2,
                        'multiple'    => true,
                        'options'     => [
                            'front_page'         => __( 'Front Page', 'animation-addons-for-elementor-pro' ),
                            'blog_page'          => __( 'Blog Page', 'animation-addons-for-elementor-pro' ),
                            'single'             => __( 'Single Post', 'animation-addons-for-elementor-pro' ),
                            'page'               => __( 'Page', 'animation-addons-for-elementor-pro' ),
                            'archive'            => __( 'Archive Page', 'animation-addons-for-elementor-pro' ),
                            'search'             => __( 'Search Results Page', 'animation-addons-for-elementor-pro' ),
                            'author'             => __( 'Author Archive', 'animation-addons-for-elementor-pro' ),
                            'taxonomy'           => __( 'Taxonomy Archive', 'animation-addons-for-elementor-pro' ),
                            'custom_post_type'   => __( 'Custom Post Type Archive', 'animation-addons-for-elementor-pro' ),
                        ],
                        'default'     => [],
                        'condition'   => [ 'enable_protection' => 'yes' ],
                    ]
                );
    
            // Restrict by User Roles
                $element->add_control(
                    'restrict_by_user_role',
                    [
                        'label'       => __( 'Restrict by User Role', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::SELECT2,
                        'multiple'    => true,
                        'options'     => $this->get_user_roles(),
                        'default'     => [],
                        'condition'   => [ 'enable_protection' => 'yes' ],
                    ]
                );
    
                // Restrict by Login Status
                $element->add_control(
                    'restrict_logged_status',
                    [
                        'label'     => __( 'Restrict by Login Status', 'animation-addons-for-elementor-pro' ),
                        'type'      => Controls_Manager::SELECT,
                        'options'   => [
                            ''          => __( 'None', 'animation-addons-for-elementor-pro' ),
                            'logged_in' => __( 'Logged In', 'animation-addons-for-elementor-pro' ),
                            'logged_out' => __( 'Logged Out', 'animation-addons-for-elementor-pro' ),
                        ],
                        'default'   => '',
                        'condition' => [ 'enable_protection' => 'yes' ],
                    ]
                );
                
                $element->add_control(
                    'restrict_recent_visit',
                    [
                        'label'        => __( 'Restrict By Recent Visit post', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => __( 'No', 'animation-addons-for-elementor-pro' ),
                        'default'      => 'no',
                        'description' => __( 'Check Cookie content exist', 'animation-addons-for-elementor-pro' ),
                        'condition' => [ 'enable_protection' => 'yes' ],
                    ]
                );   
                
                $element->add_control(
                    'restrict_acf_fld',
                    [
                        'label'        => __( 'Restrict By Acf Field', 'animation-addons-for-elementor-pro' ),
                        'type'         => Controls_Manager::SWITCHER,
                        'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
                        'label_off'    => __( 'No', 'animation-addons-for-elementor-pro' ),
                        'default'      => 'no',
                        'description' => __( 'Check Cookie content exist', 'animation-addons-for-elementor-pro' ),
                        'condition' => [ 'enable_protection' => 'yes' ],
                    ]
                );  
                
                $options = $this->get_acf_fields_options();            
                if(!empty($options)){            
                    $element->add_control(
                        'acf_field_key',
                        [
                            'label' => __( 'ACF Field Key', 'animation-addons-for-elementor-pro' ),
                            'type' => \Elementor\Controls_Manager::SELECT2, // SELECT2 for search functionality.
                            'options' => $options,
                            'description' => __( 'Search and select an ACF field.', 'animation-addons-for-elementor-pro' ),
                            'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                        ]
                    );
                }else{
                    $element->add_control(
                        'acf_field_key',
                        [
                            'label' => __('ACF Field Key', 'animation-addons-for-elementor-pro'),
                            'type' => \Elementor\Controls_Manager::TEXT,                   
                            'default' => '',
                            'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                        ]
                    );
                }
                
                $element->add_control(
                    'acf_operator',
                    [
                        'label'       => __( 'ACF Operator', 'animation-addons-for-elementor-pro' ),
                        'type'        => Controls_Manager::SELECT,
                        'options'     => [
                            '=='           => __( 'Equals (Exact Match)', 'animation-addons-for-elementor-pro' ),
                            '!='           => __( 'Not Equals', 'animation-addons-for-elementor-pro' ),
                            'contains'     => __( 'Contains (Array Overlap)', 'animation-addons-for-elementor-pro' ),
                            'not_contains' => __( 'Does Not Contain (Array Overlap)', 'animation-addons-for-elementor-pro' ),
                        ],
                        'default'     => '==',
                        'description' => __( 'Select the comparison operator.', 'animation-addons-for-elementor-pro' ),
                        'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                    ]
                );
                
                $element->add_control(
                    'acf_field_value',
                    [
                        'label' => __('ACF Field Value', 'animation-addons-for-elementor-pro'),
                        'type' => \Elementor\Controls_Manager::TEXT,                   
                        'placeholder' => 'one,1,two,2',
                        'description' => __( 'ex1: one | ex2: one, two, three', 'animation-addons-for-elementor-pro' ),
                        'condition' => [ 'enable_protection' => 'yes', 'restrict_acf_fld' => 'yes' ],
                    ]
                );
                             
    
            $element->end_controls_section();
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
                    if ( in_array( $field['type'], $this->get_supported_fields(), true ) ) {
                        $options[ $key ] = sprintf( '%s (%s)', $field['label'], $field['type'] );
                    }
                }
            }
          
            // Include Global ACF Fields (Options Page).
            $global_fields = get_field_objects( 'options' );
            if ( $global_fields ) {
                foreach ( $global_fields as $key => $field ) {
                    if ( in_array( $field['type'], $this->get_supported_fields(), true ) ) {
                        $options[ $key ] = sprintf( '[Global] %s (%s)', $field['label'], $field['type'] );
                    }
                }
            }
    
            return $options;
        }
        
        public function get_supported_fields() {
            return [
                'date_picker',
                'text',
                'textarea',
                'number',
                'email',
                'password',               
                'select',
                'checkbox',
                'radio',
                'true_false'   
                
            ];
        }
        
    
        public function apply_content_restriction( $container ) {
            $settings = $container->get_settings_for_display();
    
            if ( isset( $settings['enable_protection'] ) && $settings['enable_protection'] === 'yes' ) {
                if ( ! $this->check_restriction( $settings ) ) {
                    $container->add_render_attribute( '_wrapper', 'style', 'display: none;' );
                }
            }
        }
        /**
         * Get ACF field type by key *or* name.
         *
         * @param string      $selector Field key (field_abc) *or* field name (my_date).
         * @param int|false   $post_id  Post ID if using a field name, false if key.
         *
         * @return string|null          Field type slug (e.g. 'image') or null if not found.
         */
        function aae_get_acf_field_type( string $selector, $post_id = false ): ?string {
            // Decide which ACF helper to use
            $field = str_starts_with( $selector, 'field_' )
                ? acf_get_field( $selector )
                : get_field_object( $selector, $post_id ?: get_the_ID(), false );

            return $field['type'] ?? null;
        }
    
        private function check_restriction( $settings ) {
            // Restrict by Page Type
            if ( ! empty( $settings['restrict_by_page_type'] ) ) {
                $current_page_type = $this->get_current_page_type();
                if ( ! in_array( $current_page_type, $settings['restrict_by_page_type'] ) ) {
                    return false;
                }
            }
    
            // Restrict by User Roles
            if ( ! empty( $settings['restrict_by_user_role'] ) ) {
                $user = wp_get_current_user();
                if ( ! array_intersect( $settings['restrict_by_user_role'], $user->roles ) ) {
                    return false;
                }
            }
    
            // Restrict by Login Status
            if ( $settings['restrict_logged_status'] === 'logged_in' && ! is_user_logged_in() ) {
                return false;
            } elseif ( $settings['restrict_logged_status'] === 'logged_out' && is_user_logged_in() ) {
                return false;
            }
            
            if ( $settings['restrict_recent_visit'] === 'yes' ) {
                $visited_posts = isset( $_COOKIE['aae_visited_posts'] ) ? json_decode( stripslashes( $_COOKIE['aae_visited_posts'] ), true ) : [];
                if(!$visited_posts){
                    return false;
                }            
            } 
            
            if ( isset( $settings['acf_field_key'] ) && $settings['restrict_acf_fld'] == 'yes' && ! empty( $settings['acf_field_value'] ) ) {
            
                $post_id         = is_single() ? get_the_ID() : get_queried_object_id();  // Get the current post ID
             
                $acf_field_value = (array) get_field( $settings['acf_field_key'], $post_id ); 
                $field_type = $this->aae_get_acf_field_type( $settings['acf_field_key'], $post_id );
                $expected_values = is_string($settings['acf_field_value']) ? explode( ',',$settings['acf_field_value']) : $settings['acf_field_value'];
                $field = get_field_object( $settings['acf_field_key'] , $post_id, false );

                if($field_type === 'date_picker' && is_array($acf_field_value)) {                  
                    $fmt      = $field['return_format'];                                   // might be d/m/Y … who
                    $raw      = get_field( $settings['acf_field_key'] , $post_id );
                    $acf_data = \DateTime::createFromFormat( $fmt, $raw, wp_timezone() );                   
                    $expected_values = (array) $acf_data->format( 'Ymd' );   
                    
                    $acf_data = \DateTime::createFromFormat( $fmt, $acf_field_value[0], wp_timezone() );                   
                    $acf_field_value = (array) $acf_data->format( 'Ymd' );  
                    
                }           

                if (  $settings['acf_operator'] && $settings['acf_operator'] == 'contains' ) {                   
                    if ( empty( array_intersect( $acf_field_value, $expected_values ) ) ) {
                        return false;
                    }
                }elseif($settings['acf_operator'] && $settings['acf_operator'] == 'not_contains'){
                    if ( ! empty( array_intersect( $acf_field_value, $expected_values ) ) ) {
                        return false;
                    }
                }elseif($settings['acf_operator'] && $settings['acf_operator'] == '=='){

                    if(is_array($acf_field_value) && is_array($expected_values)){                      
                        if ( serialize($acf_field_value) != serialize($expected_values) ) {                          
                            return false;
                        }
                    }
                    
                }elseif($settings['acf_operator'] && $settings['acf_operator'] == '!='){
                    if(is_array($acf_field_value) && is_array($expected_values)){
                        if ( serialize($acf_field_value) == serialize($expected_values) ) {
                            return false;
                        }
                    }                   
                }
                
            }
    
            return true;
        }
    
        private function get_current_page_type() {
            if ( is_front_page() ) {
                return 'front_page';
            } elseif ( is_home() ) {
                return 'blog_page';
            } elseif ( is_singular( 'post' ) ) {
                return 'single';
            } elseif ( is_page() ) {
                return 'page';
            } elseif ( is_archive() && ! is_tax() && ! is_author() && ! is_post_type_archive() ) {
                return 'archive';
            } elseif ( is_search() ) {
                return 'search';
            } elseif ( is_author() ) {
                return 'author';
            } elseif ( is_tax() ) {
                return 'taxonomy';
            } elseif ( is_post_type_archive() ) {
                return 'custom_post_type';
            }
    
            return '';
        }
    
        private function get_user_roles() {
            global $wp_roles;
            return $wp_roles->get_names();
        }
    }

    add_action( 'elementor/init', function(){
        new Content_Restriction_Container_Extension();
    } );

