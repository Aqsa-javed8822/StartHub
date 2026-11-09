<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

class Stacked_Cards extends Widget_Base {

	public function get_name() {
		return 'aae--stacked-cards';
	}

	public function get_title() {
		return esc_html__( 'Stacked Cards', 'animation-addons-for-elementor-pro' );
	}

	public function get_icon() {
		return 'wcf eicon-info-box';
	}

	public function get_categories() {
		return [ 'animation-addons-for-elementor-pro' ];
	}

	public function get_style_depends() {
		return [ 'aae-stacked-cards' ];
	}

	public function get_script_depends() {
		return [ 'aae-stacked-cards' ];
	}

	protected function register_controls() {
		$this->register_stacked_cards_layout();

		$this->register_stacked_cards_content();

		$this->register_stacked_cards_animation();

		$this->style_stacked_cards_layout();

		$this->style_stacked_card_image();

		$this->style_stacked_card_content();

		$this->style_stacked_card_button();
	}

	protected function register_stacked_cards_layout() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_responsive_control(
			'stacked_card_layout',
			[
				'label'     => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row-reverse'    => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'row'            => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-right',
					],
					'column'         => [
						'title' => esc_html__( 'Bottom', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => 'row',
				'selectors' => [
					'{{WRAPPER}} .aae--stacked-card' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_stacked_cards_content() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content ', 'animation-addons-for-elementor-pro' ),
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'content_type',
			[
				'label'   => esc_html__( 'Content Type', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'content'  => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
					'template' => esc_html__( 'Saved Templates', 'animation-addons-for-elementor-pro' ),
				],
				'default' => 'content',
			]
		);

		$repeater->add_control(
			'elementor_templates',
			[
				'label'       => esc_html__( 'Save Template', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => false,
				'multiple'    => false,
				'options'     => wcf_addons_get_saved_template_list(),
				'condition'   => [
					'content_type' => 'template',
				],
			]
		);

		$repeater->add_control(
			'icon',
			[
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-atom',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'content_type' => 'content',
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 3,
				'default'     => esc_html__( 'Seamless Smooth Scrolling Animation for WordPress', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Type your title here', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'content_type' => 'content',
				],
			]
		);

		$repeater->add_control(
			'text',
			[
				'label'       => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => esc_html__( 'Seamless Smooth Scrolling Animation for WordPress', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Type your title here', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'content_type' => 'content',
				],
			]
		);

		$repeater->add_control(
			'btn_text',
			[
				'label'       => esc_html__( 'Button Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Explore Animations', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Type your text here', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'content_type' => 'content',
				],
			]
		);

		$repeater->add_control(
			'btn_icon',
			[
				'label'       => esc_html__( 'Button Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-arrow-right',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'content_type' => 'content',
				],
			]
		);

		$repeater->add_control(
			'btn_link',
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
				'condition'   => [
					'content_type' => 'content',
				],
			]
		);

		$repeater->add_control(
			'image',
			[
				'label'     => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'card_list',
			[
				'label'       => esc_html__( 'Stacked Cards Items', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'title' => esc_html__( 'Seamless Smooth Scrolling Animation for WordPress', 'animation-addons-for-elementor-pro' ),
						'text'  => esc_html__( 'Create a stunning WordPress experience with GSAP smooth scrolling animations.', 'animation-addons-for-elementor-pro' ),
					],
					[
						'title' => esc_html__( 'Create Dynamic Scroll Animations with GSAP ScrollTrigger', 'animation-addons-for-elementor-pro' ),
						'text'  => esc_html__( 'Create dynamic, scroll-triggered animations using GSAP ScrollTrigger.', 'animation-addons-for-elementor-pro' ),
					],
					[
						'title' => esc_html__( 'Smooth and Responsive Horizontal Scroll with Interactive Effects', 'animation-addons-for-elementor-pro' ),
						'text'  => esc_html__( 'Give your website a modern look with smooth horizontal scrolling and interactive visual effects.', 'animation-addons-for-elementor-pro' ),
					],
				],
				'title_field' => '{{{ content_type }}}',
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title Tag', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default' => 'h3',
			]
		);

		$this->end_controls_section();
	}

	protected function register_stacked_cards_animation() {
		$this->start_controls_section(
			'section_anim_settings',
			[
				'label' => __( 'Animation Settings', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_responsive_control(
			'animation_start',
			[
				'label'   => esc_html__( 'Start Position', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'custom',
				'options' => [
					'top top'       => esc_html__( 'Top Top', 'animation-addons-for-elementor-pro' ),
					'top center'    => esc_html__( 'Top Center', 'animation-addons-for-elementor-pro' ),
					'top bottom'    => esc_html__( 'Top Bottom', 'animation-addons-for-elementor-pro' ),
					'center top'    => esc_html__( 'Center Top', 'animation-addons-for-elementor-pro' ),
					'center center' => esc_html__( 'Center Center', 'animation-addons-for-elementor-pro' ),
					'center bottom' => esc_html__( 'Center Bottom', 'animation-addons-for-elementor-pro' ),
					'bottom top'    => esc_html__( 'Bottom Top', 'animation-addons-for-elementor-pro' ),
					'bottom center' => esc_html__( 'Bottom Center', 'animation-addons-for-elementor-pro' ),
					'bottom bottom' => esc_html__( 'Bottom Bottom', 'animation-addons-for-elementor-pro' ),
					'custom'        => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'anim_custom_start',
			[
				'label'       => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'top top+=100', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'top top+=100', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'animation_start' => 'custom'
				],
			]
		);

		$this->add_responsive_control(
			'animation_rotation',
			[
				'label'   => esc_html__( 'Rotation', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 0,
				'max'     => 360,
				'default' => 45,
			]
		);

		$this->end_controls_section();
	}

	protected function style_stacked_cards_layout() {
		$this->start_controls_section(
			'style_card_layout',
			[
				'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'layout_bg',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .aae--stacked-card',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'layout_border',
				'selector' => '{{WRAPPER}} .aae--stacked-card',
			]
		);

		$this->add_responsive_control(
			'layout_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .aae--stacked-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'layout_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'vh' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae--stacked-cards' => 'height: {{SIZE}}{{UNIT}} !important; max-height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_stacked_card_image() {
		$this->start_controls_section(
			'style_card_image',
			[
				'label' => __( 'Image', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'img_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'{{WRAPPER}} .image img' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .image'     => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'img_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'{{WRAPPER}} .image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'img_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_stacked_card_content() {
		$this->start_controls_section(
			'style_card_content',
			[
				'label' => __( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'card_icon_heading',
			[
				'label'     => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'card_icon_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card-icon' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'card_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .card-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'card_icon_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .card-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'title_heading',
			[
				'label'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typo',
				'selector' => '{{WRAPPER}} .title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'text_heading',
			[
				'label'     => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .text' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'text_typo',
				'selector' => '{{WRAPPER}} .text',
			]
		);

		$this->add_responsive_control(
			'text_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_stacked_card_button() {
		$this->start_controls_section(
			'style_card_btn',
			[
				'label' => __( 'Button', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'btn_typo',
				'selector' => '{{WRAPPER}} .card-btn',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'btn_border',
				'selector' => '{{WRAPPER}} .card-btn',
			]
		);

		$this->add_responsive_control(
			'btn_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .card-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .card-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Tabs
		$this->start_controls_tabs(
			'btn_style_tabs'
		);

		// Normal Tab
		$this->start_controls_tab(
			'btn_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'btn_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card-btn' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		// Hover Tab
		$this->start_controls_tab(
			'btn_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'btn_h_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card-btn:hover' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'btn_b_h_color',
			[
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .card-btn:hover' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'btn_icon_heading',
			[
				'label'     => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'btn_icon_size',
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
					'{{WRAPPER}} .btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'btn_icon_gap',
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
					'{{WRAPPER}} .card-btn' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Render
	protected function render() {
		$settings = $this->get_settings_for_display();

		$title_tag = $settings['title_tag'];

		$this->add_render_attribute( 'wrapper', [
			'class'          => 'aae--stacked-card-wrapper',
			'data-rotation'  => $settings['animation_rotation'],
			'data-start-pos' => ( 'custom' === $settings['animation_start'] ) ? $settings['anim_custom_start'] : $settings['animation_start'],
		] );
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="aae--stacked-cards">
				<?php
				if ( $settings['card_list'] ) {
				foreach ( $settings['card_list'] as $index => $card ) {
				$btn_link = 'btn_id_' . $index;
				if ( ! empty( $card['btn_link']['url'] ) ) {
					$this->add_link_attributes( $btn_link, $card['btn_link'] );
				}

				if ( 'content' === $card['content_type'] ) {
				?>
                <div class="aae--stacked-card">
                    <div class="content">
                        <div class="card-icon">
							<?php Icons_Manager::render_icon( $card['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                        </div>
                        <<?php Utils::print_validated_html_tag( $title_tag ); ?> class="title">
						<?php echo esc_html( $card['title'] ); ?>
                    </<?php Utils::print_validated_html_tag( $title_tag ); ?>>
                    <p class="text"><?php echo esc_html( $card['text'] ); ?></p>
                    <a <?php $this->print_render_attribute_string( $btn_link ); ?> class="card-btn">
						<?php echo esc_html( $card['btn_text'] ); ?>
                        <span class="btn-icon">
                                                <?php Icons_Manager::render_icon( $card['btn_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                                            </span>
                    </a>
                </div>
                <div class="image">
                    <img src="<?php echo esc_url( $card['image']['url'] ); ?>"
                         alt="<?php echo esc_attr( $card['title'] ); ?>">
                </div>
            </div>
			<?php
			} else {
				if ( ! empty( $card['elementor_templates'] ) ) {
					if ( 'publish' === get_post_status( $card['elementor_templates'] ) ) {
						?>
                        <div class="aae--stacked-card elm-card">
							<?php echo Plugin::$instance->frontend->get_builder_content( $card['elementor_templates'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
						<?php

					}
				}
			}
			}
			}
			?>
        </div>
        </div>
		<?php
	}
}