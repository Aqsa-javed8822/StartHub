<?php

namespace WCFAddonsPros\Free;
use Elementor\Controls_Manager;
defined( 'ABSPATH' ) || exit;

class AAE_One_Page_Nav {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {

        if ( ! defined( 'ELEMENTOR_VERSION' ) || version_compare( ELEMENTOR_VERSION, '3.28.3', '<' ) ) {
            return;
        }

	    add_action( 'elementor/element/wcf--one-page-nav/section_navigation/after_section_end',[$this,'smooth'],10,2);
	    add_action( 'elementor/element/wcf--nav-menu/section_mobile_menu_settings/after_section_end',[$this,'smooth'],10,2);
       
	}

   
    
    public function smooth($element, $args){
        
        $element->start_controls_section(
            'aae_onepsll',
            [
                'label' => esc_html__( 'Smooth Scroll', 'animation-addons-for-elementor-pro' ),
            ]
        );
    
        $element->add_control(
            'aae_scmscroll_enb',
            [
                'label'        => esc_html__( 'Enable Smooth Scroll', 'animation-addons-for-elementor-pro' ),
                'type'         => \Elementor\Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
                'label_off'    => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
                'return_value' => 'yes',
                'default'      => 'yes',                
                'assets' => [
                    'scripts' => [
                        [
                            'name' => 'aae-one-page-scroll',
                            'conditions' => [
                                'terms' => [
                                    [
                                        'name' => 'aae_scmscroll_enb',
                                        'operator' => '===',
                                        'value' => 'yes',
                                    ],
                                ],
                            ],
                        ],
                    ],
			    ],
            ]
        );  

        $element->add_control(
			'onpsc_duration',
			[
				'label' => esc_html__( 'Duration', 'animation-addons-for-elementor-pro' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 100,
				'step' => 0.1,
				'default' => 1,
                'frontend_available' => true,
                'condition' => [
					'aae_scmscroll_enb' => 'yes',
				],
			]
		);

        $element->add_control(
            'ease_type',
            [
                'label'   => __( 'Ease', 'animation-addons-for-elementor-pro' ),
                'type'    => \Elementor\Controls_Manager::SELECT,
                'frontend_available' => true,
                'condition' => [
					'aae_scmscroll_enb' => 'yes',
				],
                'default' => 'power2.out',
                'options' => [
                    // Power Eases
                    'power1.in'     => 'Power1 In',
                    'power1.out'    => 'Power1 Out',
                    'power1.inOut'  => 'Power1 InOut',
        
                    'power2.in'     => 'Power2 In',
                    'power2.out'    => 'Power2 Out',
                    'power2.inOut'  => 'Power2 InOut',
        
                    'power3.in'     => 'Power3 In',
                    'power3.out'    => 'Power3 Out',
                    'power3.inOut'  => 'Power3 InOut',
        
                    'power4.in'     => 'Power4 In',
                    'power4.out'    => 'Power4 Out',
                    'power4.inOut'  => 'Power4 InOut',
        
                    // Other Popular Eases
                    'expo.in'       => 'Expo In',
                    'expo.out'      => 'Expo Out',
                    'expo.inOut'    => 'Expo InOut',
        
                    'circ.in'       => 'Circ In',
                    'circ.out'      => 'Circ Out',
                    'circ.inOut'    => 'Circ InOut',
        
                    'back.in'       => 'Back In',
                    'back.out'      => 'Back Out',
                    'back.inOut'    => 'Back InOut',
        
                    'bounce.in'     => 'Bounce In',
                    'bounce.out'    => 'Bounce Out',
                    'bounce.inOut'  => 'Bounce InOut',
        
                    'elastic.in'    => 'Elastic In',
                    'elastic.out'   => 'Elastic Out',
                    'elastic.inOut' => 'Elastic InOut',
                ],
            ]
        );
       
        if($element->get_type()==='widget' && $element->get_name() === 'wcf--one-page-nav'){


            $element->add_control(
                'aae_preset_nav_pos',
                [
                    'label'   => __( 'Sticky', 'animation-addons-for-elementor-pro' ),
                    'type'    => \Elementor\Controls_Manager::SELECT,
                    'frontend_available' => true,
                    'condition' => [
                        'aae_scmscroll_enb' => 'yes',
                    ],
                    'default' => 'center-right',
                    'options' => [                       
                        'custom'        => 'Custom',                        
                        'top-left'    => 'Top Left',
                        'top-center'    => 'Top Center',
                        'top-right'     => 'Top Right',
                        'bottom-center' => 'Bottom Center',
                        'bottom-left'   => 'Bottom Left',
                        'bottom-right'  => 'Bottom Right',                      
                        'center-left'   => 'Center Left',
                        'center-right'  => 'Center Right',
                        'center-center' => 'Center Center',            
                    ],
                ]
            );
            /* Sticky option for preset staert */
            $element->add_control(
                'aae_pospre_xhr',
                [
                    'type' => \Elementor\Controls_Manager::DIVIDER,
                ]
            );
            $element->add_control(
                'aae_pospre_x',
                [
                    'label' => esc_html__( 'Horizontal', 'animation-addons-for-elementor-pro' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 700,
                    'step' => 0.01,                   
                    'default' => '',
                    'frontend_available' => true,
                    'condition' => [
                        'aae_scmscroll_enb' => 'yes',   
                        'aae_preset_nav_pos' => ['center-right', 'top-left','top-right','bottom-left','bottom-right','center-left']                    
                    ],
                ]
            );

            $element->add_control(
                'aae_pospre_x_plusminus',
                [
                    'label' => esc_html__( 'Sign', 'animation-addons-for-elementor-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( '+', 'animation-addons-for-elementor-pro' ),
                    'label_off' => esc_html__( '-', 'animation-addons-for-elementor-pro' ),
                    'return_value' => '+',
                    'frontend_available' => true,
                    'label_block' => true,
                    'show_label'=> false,
                    'default' => '+',
                    'condition' => [
                        'aae_scmscroll_enb' => 'yes',   
                        'aae_preset_nav_pos' => ['center-right','center-left']                    
                    ],
                ]
            );
            $element->add_control(
                'aae_pospre_yhr',
                [
                    'type' => \Elementor\Controls_Manager::DIVIDER,
                ]
            );
            $element->add_control(
                'aae_pospre_y',
                [
                    'label' => esc_html__( 'Vertical', 'animation-addons-for-elementor-pro' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 700,
                    'step' => 0.01,
                    'default' => '',
                    'frontend_available' => true,
                    'condition' => [
                        'aae_scmscroll_enb' => 'yes',   
                        'aae_preset_nav_pos' => ['top-center','top-right', 'top-left','top-right','bottom-center','bottom-left','bottom-right', 'center-right','center-left']                    
                    ],
                ]
            );

            $element->add_control(
                'aae_pospre_y_plusminus',
                [
                    'label' => esc_html__( 'Sign', 'animation-addons-for-elementor-pro' ),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__( '+', 'animation-addons-for-elementor-pro' ),
                    'label_off' => esc_html__( '-', 'animation-addons-for-elementor-pro' ),
                    'return_value' => '+',
                    'default' => '+',
                    'frontend_available' => true,
                    'label_block' => true,
                    'show_label'=> false,
                    'condition' => [
                        'aae_scmscroll_enb' => 'yes',   
                        'aae_preset_nav_pos' => ['center-right','center-left']                    
                    ],
                ]
            );

            /* Sticky option for preset end */

            $element->add_control(
                'aae_pos_x',
                [
                    'label' => esc_html__( 'Horizontal', 'animation-addons-for-elementor-pro' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.01,
                    'default' => 0.1,
                    'frontend_available' => true,
                    'condition' => [
                        'aae_scmscroll_enb' => 'yes',   
                        'aae_preset_nav_pos' => ['custom']                    
                    ],
                ]
            );
            

            $element->add_control(
                'aae_pos_y',
                [
                    'label' => esc_html__( 'Vertical', 'animation-addons-for-elementor-pro' ),
                    'type' => \Elementor\Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 1,
                    'step' => 0.01,
                    'default' => 0.1,
                    'frontend_available' => true,
                    'condition' => [
                        'aae_scmscroll_enb' => 'yes',
                        'aae_preset_nav_pos' => ['custom']                
                    ],
                ]
            );
        }
        
    
        $element->end_controls_section();
    }
    
}

AAE_One_Page_Nav::instance();
