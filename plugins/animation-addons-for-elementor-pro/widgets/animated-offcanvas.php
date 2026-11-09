<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class Animated_Offcanvas extends Widget_Base {

	public function get_name() {
		return 'wcf--animated-offcanvas';
	}

	public function get_title() {
		return esc_html__( 'Animated Off-Canvas', 'animation-addons-for-elementor-pro' );
	}

	public function get_icon() {
		return 'wcf eicon-menu-bar';
	}

	public function get_categories() {
		return [ 'animation-addons-for-elementor-pro' ];
	}

	public function get_keywords() {
		return [ 'offcanvas', 'menu', 'nav' ];
	}

	public function get_style_depends() {
		return [ 'wcf--animated-offcanvas' ];
	}

	public function get_script_depends() {
		return [ 'wcf--animated-offcanvas'];
	}

	function menu_list() {

		$return_menus = [];
		$menus        = wp_get_nav_menus();
		if ( is_array( $menus ) ) {
			foreach ( $menus as $menu ) {
				$return_menus[ $menu->term_id ] = esc_html( $menu->name );
			}
		}

		return $return_menus;
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Settings', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'menu_button_text',
			[
				'label'       => esc_html__( 'Open Button Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Menu',
				'placeholder' => esc_html__( 'Menu', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'enabled_icon!' => 'yes',
				],
			]
		);

		$this->add_control(
			'enabled_icon',
			[
				'label'   => __( 'Enable Icon?', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SWITCHER,
				'yes'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
				'no'      => __( 'No', 'animation-addons-for-elementor-pro' ),
				'default' => '',
			]
		);

		$this->add_control(
			'menu_open_icon',
			[
				'label'       => esc_html__( 'Open Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'enabled_icon' => 'yes',
				],
			]
		);

		$this->add_control(
			'default_close_switch',
			[
				'label'     => __( 'Default Close Icon?', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'yes'       => __( 'Yes', 'animation-addons-for-elementor-pro' ),
				'no'        => __( 'No', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'close_text',
			[
				'label'       => esc_html__( 'Close Button Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'Close',
				'placeholder' => esc_html__( 'close text', 'animation-addons-for-elementor-pro' ),
				'condition'   => [ 'default_close_switch!' => [ 'yes' ] ],
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => [ 'default_close_switch!' => [ 'yes' ] ],
				'default'     => []
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'aae_onepsll',
			[
				'label' => esc_html__( 'Smooth Scroll', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'aae_scmscroll_enb',
			[
				'label'        => esc_html__( 'Enable Smooth Scroll', 'animation-addons-for-elementor-pro' ),
				'type'         => \Elementor\Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'assets'       => [
					'scripts' => [
						[
							'name'       => 'aae-one-page-scroll',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_scmscroll_enb',
										'operator' => '===',
										'value'    => 'yes',
									],
								],
							],
						],
					],
				],
			]
		);

		$this->add_control(
			'onpsc_duration',
			[
				'label'              => esc_html__( 'Duration', 'animation-addons-for-elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 100,
				'step'               => 0.1,
				'default'            => 1,
				'frontend_available' => true,
				'condition'          => [
					'aae_scmscroll_enb' => 'yes',
				],
			]
		);

		$this->add_control(
			'ease_type',
			[
				'label'              => __( 'Ease', 'animation-addons-for-elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::SELECT,
				'frontend_available' => true,
				'condition'          => [
					'aae_scmscroll_enb' => 'yes',
				],
				'default'            => 'power2.out',
				'options'            => [
					// Power Eases
					'power1.in'    => 'Power1 In',
					'power1.out'   => 'Power1 Out',
					'power1.inOut' => 'Power1 InOut',

					'power2.in'    => 'Power2 In',
					'power2.out'   => 'Power2 Out',
					'power2.inOut' => 'Power2 InOut',

					'power3.in'    => 'Power3 In',
					'power3.out'   => 'Power3 Out',
					'power3.inOut' => 'Power3 InOut',

					'power4.in'    => 'Power4 In',
					'power4.out'   => 'Power4 Out',
					'power4.inOut' => 'Power4 InOut',

					// Other Popular Eases
					'expo.in'      => 'Expo In',
					'expo.out'     => 'Expo Out',
					'expo.inOut'   => 'Expo InOut',

					'circ.in'    => 'Circ In',
					'circ.out'   => 'Circ Out',
					'circ.inOut' => 'Circ InOut',

					'back.in'    => 'Back In',
					'back.out'   => 'Back Out',
					'back.inOut' => 'Back InOut',

					'bounce.in'    => 'Bounce In',
					'bounce.out'   => 'Bounce Out',
					'bounce.inOut' => 'Bounce InOut',

					'elastic.in'    => 'Elastic In',
					'elastic.out'   => 'Elastic Out',
					'elastic.inOut' => 'Elastic InOut',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'section_custom_content',
			[
				'label' => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'show_logo',
			[
				'label'        => esc_html__( 'Show Logo / Image', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'custom_image',
			[
				'label'     => __( 'Add Logo/Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'dynamic'   => [
					'active' => true,
				],
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [ 'show_logo' => 'yes' ],
			]
		);

		$this->add_control(
			'menu_selected',
			[
				'label'   => esc_html__( 'Menu', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'primary',
				'options' => $this->menu_list()
			]
		);

		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_title',
			[
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => \Elementor\Controls_Manager::TEXT,
				'default'     => esc_html__( 'List Title', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_content',
			[
				'label'       => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'type'        => \Elementor\Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Email', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'list_type',
			[
				'label'   => esc_html__( 'Content Type', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => [
					''        => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'email'   => esc_html__( 'Email', 'animation-addons-for-elementor-pro' ),
					'phone'   => esc_html__( 'Phone', 'animation-addons-for-elementor-pro' ),
					'address' => esc_html__( 'Address', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link Value', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '#',
				'label_block' => true,
				'dynamic' => [
					'active' => true,
				],
				'condition'   => [ 'list_type' => [ 'email', 'phone' ] ]
			]
		);


		$this->add_control(
			'contact_info',
			[
				'label'       => esc_html__( 'Contact Info', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'title_field' => '{{{ list_title }}}',
			]
		);

		$language = new \Elementor\Repeater();

		$language->add_control(
			'list_title',
			[
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'List Title', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
			]
		);

		$language->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link Value', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '#',
				'label_block' => true,
			]
		);

		$this->add_control(
			'language_info',
			[
				'label'       => esc_html__( 'Language Info', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $language->get_controls(),
				'title_field' => '{{{ list_title }}}',
			]
		);

		// Social Media
		$follow = new \Elementor\Repeater();

		$follow->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::URL,
				'options'     => [ 'url', 'is_external', 'nofollow' ],
				'default'     => [
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				],
				'label_block' => true,
			]
		);

		$follow->add_control(
			'icon',
			[
				'label'   => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'follow_info',
			[
				'label'  => esc_html__( 'Follow Info', 'animation-addons-for-elementor-pro' ),
				'type'   => Controls_Manager::REPEATER,
				'fields' => $follow->get_controls(),
			]
		);

		$this->end_controls_section();


		// Off Canvas
		$this->start_controls_section(
			'style_offcanvas',
			[
				'label' => esc_html__( 'Off-Canvas', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'offcanvas_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.cwt--offcanvas-area',
			]
		);

		$this->add_responsive_control(
			'offcanvas_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}}.cwt--offcanvas-area' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'col_swap',
			[
				'label'     => esc_html__( 'Swap Column?', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''  => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
					'1' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-left' => 'order: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Off Canvas Left
		$this->start_controls_section(
			'style_left',
			[
				'label' => __( 'Off-Canvas Left', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'left_display',
			[
				'label'     => esc_html__( 'Display', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-left'  => 'display: {{VALUE}};',
					'.oc-{{ID}} .cwt--offcanvas-inner' => 'grid-template-columns: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'left_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-inner' => '--width-left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'left_display!' => 'none' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'left_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.oc-{{ID}} .cwt--offcanvas-left',
			]
		);

		$this->add_responsive_control(
			'left_margin',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-left' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();

		// Off Canvas Right
		$this->start_controls_section(
			'style_right',
			[
				'label' => __( 'Off-Canvas Right', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'right_display',
			[
				'label'     => esc_html__( 'Display', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-right' => 'display: {{VALUE}};',
					'.oc-{{ID}} .cwt--offcanvas-inner' => 'grid-template-columns: 100%;',
				],
			]
		);

		$this->add_responsive_control(
			'right_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-inner' => '--width-right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'right_display!' => 'none' ],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'right_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.oc-{{ID}} .cwt--offcanvas-right',
			]
		);

		$this->add_responsive_control(
			'right_margin',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-right' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();

		// Off Canvas Logo
		$this->start_controls_section(
			'style_logo',
			[
				'label' => esc_html__( 'Logo / Image', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'logo_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-logo img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'logo_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-logo img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Off Canvas Menu
		$this->start_controls_section(
			'style_menu',
			[
				'label' => esc_html__( 'Menu', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'menu_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-menu li a' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_control(
			'menu_h_color',
			[
				'label'     => esc_html__( 'Hover Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-menu li a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'menu_b_color',
			[
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-menu li a, .cwt--offcanvas-menu .menu' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'off_menu_close',
				'selector' => '.oc-{{ID}} .cwt--offcanvas-menu li a',
			]
		);

		$this->add_responsive_control(
			'menu_ht_auto',
			[
				'label'     => esc_html__( 'Height Auto', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''     => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
					'auto' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),

				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-menu' => 'height: {{VALUE}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'oc_menu_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-menu li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'oc_menu_l2_padding',
			[
				'label'      => esc_html__( 'Level Two Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-menu .dp-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'oc_menu_l3_padding',
			[
				'label'      => esc_html__( 'Level Three Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-menu .dp-menu .dp-menu a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'oc_menu_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'expand_icon_heading',
			[
				'label'     => esc_html__( 'Expand Icon', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'expand_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .menu-item-has-children a .nav-direction-icon::after' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'expand_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .menu-item-has-children a .nav-direction-icon::after' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'expand_p_right',
			[
				'label'      => esc_html__( 'Padding Right', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .menu-item-has-children a .nav-direction-icon::after' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Off Canvas Contact
		$this->start_controls_section(
			'style_contact',
			[
				'label' => esc_html__( 'Contact', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'contact_display',
			[
				'label'     => esc_html__( 'Display', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),

				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-contact' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'contact_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 30,
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-contact' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'contact_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-contact li a, .cwt--offcanvas-contact li span' => 'color: {{VALUE}};',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'contact_typo',
				'selector' => '.oc-{{ID}} .cwt--offcanvas-contact li a, .cwt--offcanvas-contact li span',
			]
		);

		// Label
		$this->add_control(
			'contact_lb_heading',
			[
				'label'     => esc_html__( 'Label', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'contact_lb_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-contact li p' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'contact_lb_typo',
				'selector' => '.oc-{{ID}} .cwt--offcanvas-contact li p',
			]
		);

		$this->add_responsive_control(
			'contact_pb_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-contact li p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Follow/Social Media
		$this->start_controls_section(
			'style_follow',
			[
				'label' => esc_html__( 'Follow/ Social Media', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'social_display',
			[
				'label'     => esc_html__( 'Display', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),

				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-follow' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'follow_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-footer .f-icon' => 'fill: {{VALUE}}; color: {{VALUE}};',

				],
			]
		);

		$this->add_control(
			'follow_h_color',
			[
				'label'     => esc_html__( 'Hover Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-footer .f-icon:hover' => 'fill: {{VALUE}}; color: {{VALUE}};',

				],
			]
		);

		$this->add_responsive_control(
			'follow_i_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 16,
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-footer .f-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'follow_i_gap',
			[
				'label'      => esc_html__( 'Icon Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-follow' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'follow_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-follow' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Search
		$this->start_controls_section(
			'style_search',
			[
				'label' => esc_html__( 'Search', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'search_display',
			[
				'label'     => esc_html__( 'Search Display', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-search' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'search_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-footer form' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'search_icon',
			[
				'label'       => esc_html__( 'Search Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-search',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'search_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}}.cwt--offcanvas-area .cwt--offcanvas-footer form button'                                                   => 'color: {{VALUE}}; fill: {{VALUE}};',
					'.oc-{{ID}}.cwt--offcanvas-area .cwt--offcanvas-footer .default-search__again-form form input'                        => 'color: {{VALUE}};border-color:{{VALUE}}',
					'.oc-{{ID}}.cwt--offcanvas-area .cwt--offcanvas-footer .default-search__again-form form input::placeholder'           => 'color: {{VALUE}};',
					'.oc-{{ID}}.cwt--offcanvas-area .cwt--offcanvas-footer .default-search__again-form form input::-ms-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Language
		$this->start_controls_section(
			'style_language',
			[
				'label' => esc_html__( 'Language', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'display_lang',
			[
				'label'     => esc_html__( 'Display', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
					'none' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-lang .language' => 'display: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'lang_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-lang .language li a'        => 'color: {{VALUE}};',
					'.oc-{{ID}} .cwt--offcanvas-lang .language li a::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'lang_typo',
				'selector' => '.oc-{{ID}} .cwt--offcanvas-lang .language li a',
			]
		);

		$this->end_controls_section();

		// Open Button
		$this->start_controls_section(
			'style_open_btn',
			[
				'label' => esc_html__( 'Open Button', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'open_btn_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cwt--animated-offcanvas' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'open_i_typo',
				'selector'  => '{{WRAPPER}} .cwt--animated-offcanvas',
				'condition' => [ 'enabled_icon!' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'open_i_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .cwt--animated-offcanvas' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'enabled_icon' => 'yes' ],
			]
		);

		$this->end_controls_section();

		// Close Button
		$this->start_controls_section(
			'style_close_btn',
			[
				'label' => esc_html__( 'Close Button', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'close_typo',
				'selector'  => '.oc-{{ID}} .cwt--offcanvas-close .close-btn',
				'condition' => [ 'default_close_switch!' => [ 'yes' ] ],
			]
		);

		$this->add_responsive_control(
			'close_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-close .close-btn span' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'default_close_switch' => [ 'yes' ] ],
			]
		);

		$this->add_responsive_control(
			'close_circle_size',
			[
				'label'      => esc_html__( 'Circle Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-close' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'close_btn_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-close .close-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'close_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'.oc-{{ID}} .cwt--offcanvas-close' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Tabs
		$this->start_controls_tabs(
			'style_close_tabs'
		);

		// Normal Tab
		$this->start_controls_tab(
			'close_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'close_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-close .close-btn'      => 'fill: {{VALUE}}; color: {{VALUE}}',
					'.oc-{{ID}} .cwt--offcanvas-close .close-btn span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'close_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.oc-{{ID}} .cwt--offcanvas-close .close-btn',
			]
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'close_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'close_h_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.oc-{{ID}} .cwt--offcanvas-close .close-btn:hover'      => 'fill: {{VALUE}}; color: {{VALUE}}',
					'.oc-{{ID}} .cwt--offcanvas-close .close-btn:hover span' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'close_h_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.oc-{{ID}} .cwt--offcanvas-close .close-btn:hover',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$contact_info  = $settings['contact_info'];
		$menu_selected = $settings['menu_selected'];
		$remove_span   = isset( $settings['aae_scmscroll_enb'] ) && $settings['aae_scmscroll_enb'] == 'yes' ? true : false;
		?>
		<?php include_once( WCF_ADDONS_PRO_PATH . 'inc/offcanvas-walker-nav.php' ); ?>

        <!-- Offcanves start -->
        <div class="open-offcanvas cwt--animated-offcanvas">
			<?php
			if ( 'yes' === $settings['enabled_icon'] ) {
				Icons_Manager::render_icon( $settings['menu_open_icon'], [ 'aria-hidden' => 'true' ] );
			} else {
				echo esc_html( $settings['menu_button_text'] );
			}
			?>
        </div>
		
        <div hidden> <!-- fIX  UI break issue  -->
			<div class="cwt-element-transfer-to-body cwt--offcanvas-area oc-<?php echo $this->get_id(); ?>">
				<div class="cwt--offcanvas-inner">
					<div class="cwt--offcanvas-left">
						<?php if ( $settings['show_logo'] == 'yes' && $settings['custom_image']['url'] ) { ?>
							<div class="cwt--offcanvas-logo">
								<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
									<img src="<?php echo esc_url( $settings['custom_image']['url'] ); ?>" alt="Logo">
								</a>
							</div>
						<?php } ?>
						<?php if ( $contact_info ) { ?>
							<ul class="cwt--offcanvas-contact">
								<?php foreach ( $contact_info as $contact ) { ?>
									<li>
										<p><?php echo esc_html( $contact['list_title'] ) ?></p>
										<?php if ( $contact['list_type'] === 'email' ) { ?>
											<a href="mailto:<?php echo esc_attr( $contact['link'] ); ?>"><?php echo esc_html( $contact['list_content'] ) ?></a>
										<?php } ?>
										<?php if ( $contact['list_type'] === 'phone' ) { ?>
											<a href="tel:<?php echo esc_attr( $contact['link'] ); ?>"><?php echo esc_html( $contact['list_content'] ) ?></a>
										<?php } ?>
										<?php if ( $contact['list_type'] === 'address' ) { ?>
											<span><?php echo nl2br( $contact['list_content'] ) ?></span>
										<?php } ?>
									</li>
								<?php } ?>
							</ul>
						<?php } ?>
						<div class="cwt--offcanvas-footer">
							<?php if ( ! empty( $settings['follow_info'] ) ) { ?>
								<ul class="cwt--offcanvas-follow">
									<?php
									foreach ( $settings['follow_info'] as $index => $item ) {
										$f_link_id = 'f_link_' . $index;
										$this->add_link_attributes( $f_link_id, $item['link'] );
										?>
										<a class="f-icon" <?php echo $this->get_render_attribute_string( $f_link_id ); ?>>
											<?php Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
										</a>
										<?php
									} ?>
								</ul>
							<?php } ?>

							<div class="cwt--offcanvas-search">
								<div class="default-search__again-form">
									<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>"
										class="search-form">
										<input name="s" type="text" value="<?php echo get_search_query(); ?>"
											placeholder="<?php echo esc_attr__( 'Search here', 'animation-addons-for-elementor-pro' ); ?>">
										<?php do_action( 'aae_search_form_fields', $this->get_id() ); ?>
										<button aria-label="Search Button">
											<?php Icons_Manager::render_icon( $settings['search_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										</button>
									</form>
								</div>
							</div>
						</div>
					</div>
					<div class="cwt--offcanvas-right">
						<div class="cwt--offcanvas-lang">
							<?php if ( ! empty( $settings['language_info'] ) ) { ?>
								<ul class="language">
									<?php foreach ( $settings['language_info'] as $item ) { ?>
										<li>
											<a href="<?php echo esc_html( $item['link'] ); ?>"><?php echo esc_html( $item['list_title'] ); ?></a>
										</li>
									<?php } ?>
								</ul>
							<?php } ?>
							<div class="offcanvas-close__button-wrapper cwt--offcanvas-close offcanvas--close--button-js">
								<button class="close-btn">
									<?php if ( $settings['default_close_switch'] == 'yes' ) { ?>
										<span></span>
										<span></span>
									<?php } else { ?>
										<?php echo esc_html( $settings['close_text'] ); ?>
										<?php if ( info_render_elementor_icons( $settings['close_icon'] ) ) { ?>
											<?php Icons_Manager::render_icon( $settings['close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
										<?php } ?>
									<?php } ?>
								</button>
							</div>
						</div>
						<?php
						wp_nav_menu( array(
							'menu'            => $menu_selected,
							'container'       => 'nav',
							'container_class' => 'cwt--offcanvas-menu',
							'walker'          => new \WCFAddonsPro\Inc\Offcanvas_Walker_Nav( [ 'remove_span' => $remove_span ] ),
							'fallback_cb'     => '\WCFAddonsPro\Inc\Offcanvas_Walker_Nav::fallback',
						) );
						?>
					</div>
				</div>
			</div>
		</div>
        <!-- Offcanves end -->
		<?php
	}
}