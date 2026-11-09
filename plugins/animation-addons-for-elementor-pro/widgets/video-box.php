<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Controls_Manager;
use Elementor\Embed;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Utils;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Video Box
 *
 * Elementor widget for Video Box.
 *
 * @since 1.0.0
 */
class Video_Box extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--video-box';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'Video Box', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'wcf eicon-youtube';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_categories() {
		return [ 'weal-coder-addon' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_script_depends() {
		return [ 'aae-video-popup-mix'];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'wcf--video-box' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_controls() {

		// Layout Controls
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'layout_style',
			[
				'label'   => esc_html__( 'Style', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Style One', 'animation-addons-for-elementor-pro' ),
					'2' => esc_html__( 'Style Two', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'video_box_align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'left',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .wcf--video-box' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// thumbnail Controls
		$this->start_controls_section(
			'section_image',
			[
				'label' => __( 'Thumbnail', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'thumbnail_type',
			[
				'type'    => Controls_Manager::CHOOSE,
				'label'   => esc_html__( 'Type', 'animation-addons-for-elementor-pro' ),
				'default' => 'image',
				'options' => [
					'image' => [
						'title' => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-image-bold',
					],
					'video' => [
						'title' => esc_html__( 'Video', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-video-camera',
					],
				],
				'dynamic'     => [
					'active' => true,
				],
				'toggle'  => false,
			]
		);

		$this->add_control(
			'image',
			[
				'label'     => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [ 'thumbnail_type' => 'image' ],
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'video',
			[
				'label'       => esc_html__( 'Video', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::MEDIA,
				'media_types' => [ 'video' ],
				'default'     => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'   => [ 'thumbnail_type' => 'video' ],
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'image_size',
				'exclude'   => [ 'custom' ],
				'include'   => [],
				'default'   => 'large',
				'condition' => [ 'thumbnail_type' => 'image' ],
			]
		);

		$this->end_controls_section();

		// Video Box Style
		$this->start_controls_section(
			'section_box_style',
			[
				'label' => __( 'Video Box', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'video_box_border',
				'selector' => '{{WRAPPER}} .wcf--video-box',
			]
		);

		$this->add_control(
			'video_box_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf--video-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// thumb Style
		$this->start_controls_section(
			'section_thumb_style',
			[
				'label' => __( 'Thumbnail', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf--video-box  img' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf--video-box .thumb video' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		// Content Controls
		$this->register_content_controls();

		$this->register_button_controls();

		$this->register_video_controls();
	}

	protected function register_content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'title',
			[
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Adam Smith', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Type your title', 'animation-addons-for-elementor-pro' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'   => esc_html__( 'Title HTML Tag', 'animation-addons-for-elementor-pro' ),
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
				'default' => 'h4',
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label'       => esc_html__( 'Sub Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Developer', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Type your title', 'animation-addons-for-elementor-pro' ),
				'dynamic'     => [
					'active' => true,
				],
			]
		);

		$this->end_controls_section();

		// Style
		$this->start_controls_section(
			'section_content_style',
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
				'size_units' => [ 'px', 'em', 'rem' ],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .content',
			]
		);

		// Title
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
				'label'     => esc_html__( 'Title Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--video-box .title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .wcf--video-box .title',
			]
		);

		$this->add_responsive_control(
			'title_space',
			[
				'label'      => esc_html__( 'Space', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .title' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Title
		$this->add_control(
			'subtitle_heading',
			[
				'label'     => esc_html__(  'Sub Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'subtitle_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf--video-box .subtitle' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'subtitle_typography',
				'selector' => '{{WRAPPER}} .wcf--video-box .subtitle',
			]
		);

		$this->add_responsive_control(
			'subtitle_space',
			[
				'label'      => esc_html__( 'Space', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors'  => [
					'{{WRAPPER}} .subtitle' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_button_controls() {
		$this->start_controls_section(
			'section_button',
			[
				'label' => __( 'Button', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label'       => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'Play', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Play', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'btn_icon',
			[
				'label'            => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
			]
		);

		$this->add_control(
			'icon_indent',
			[
				'label'     => esc_html__( 'Icon Spacing', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'active_ripple',
			[
				'label'        => esc_html__( 'Active Ripple', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'yes', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'ripple_color',
			[
				'label'     => esc_html__( 'Ripple Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn:before' => 'color: {{VALUE}}',
					'{{WRAPPER}} .wcf-popup-btn:after'  => 'color: {{VALUE}}',
				],
				'condition' => [ 'active_ripple' => 'yes' ],
			]
		);

		$this->add_control(
			'active_spinner',
			[
				'label'        => esc_html__( 'Active spinner', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'yes', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'sipper_image',
			[
				'label'     => esc_html__( 'Spinner Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [ 'active_spinner' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'separator' => 'before',
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => __( 'Button', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'button_typography',
				'selector' => '{{WRAPPER}} .wcf-popup-btn',
			]
		);

		$this->add_responsive_control(
			'button_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'separator'  => 'before',
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-popup-btn' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'button_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 500,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-popup-btn' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'button_border',
				'selector'  => '{{WRAPPER}} .wcf-popup-btn',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'button_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-popup-btn'        => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wcf-popup-btn:after'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wcf-popup-btn:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .wcf-popup-btn .spinner_image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .wcf-popup-btn',
			]
		);

		$this->start_controls_tabs( 'tabs_button_style');

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'button_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn' => 'fill: {{VALUE}}; color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-popup-btn',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'button_hover_text_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus' => 'color: {{VALUE}};',
					'{{WRAPPER}} .wcf-popup-btn:hover svg, {{WRAPPER}} .wcf-popup-btn:focus svg' => 'fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'button_background_hover',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus',
			]
		);

		$this->add_control(
			'button_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-popup-btn:hover, {{WRAPPER}} .wcf-popup-btn:focus' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::HOVER_ANIMATION,
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_video_controls() {
		$this->start_controls_section(
			'section_video_content',
			[
				'label' => __( 'Video', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'video_link',
			[
				'label'       => esc_html__( 'Video Link', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'dynamic'     => [
					'active' => true,
				],
				'input_type'  => 'url',
				'placeholder' => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
				'description' => esc_html__( 'YouTube/Vimeo link, or link to video file (mp4 is recommended).', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
				'default'     => 'https://www.youtube.com/watch?v=XHOmBV4js_E',
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$this->add_render_attribute( 'wrapper', 'class', 'wcf--video-box style-' . $settings['layout_style'] );

		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<div class="thumb">
				<?php
				if ( 'video' !== $settings['thumbnail_type'] ) {
					Group_Control_Image_Size::print_attachment_image_html( $settings, 'image_size', 'image' );
				} else {
					?><video src="<?php echo esc_url( $settings['video']['url'] ); ?>" muted="muted"></video><?php
				}
				?>
			</div>
			<div class="content">
				<<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="title">
				<?php $this->print_unescaped_setting( 'title' ); ?>
				</<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
				<div class="subtitle"><?php $this->print_unescaped_setting( 'subtitle' ); ?></div>
			</div>
			<?php $this->render_popup_button( $settings ); ?>
		</div>
		<?php
	}

	protected function render_popup_button( $settings ) {
		$this->add_render_attribute( 'button', 'class', 'wcf-popup-btn ' );
		$video_link = $settings['video_link'];
		// Youtube Link Checking
		if ( strpos( $video_link, "https://www.youtube.com/" ) === 0 ) {
			parse_str( parse_url( $video_link, PHP_URL_QUERY ), $query );

			if ( isset( $query['v'] ) ) {
				$ytVideoId = $query['v'];
				$video_link = "https://www.youtube.com/embed/" . $ytVideoId;
			}
		}

		// Vimeo Link Checking
		if ( strpos( $video_link, "https://vimeo.com/" ) === 0 ) {
			$videoId    = str_replace( "https://vimeo.com/", "", $video_link );
			$video_link = "https://player.vimeo.com/video/" . $videoId;
		}

		if ( ! empty( $settings['video_link'] ) ) {
			$this->add_render_attribute( 'button', 'data-src', $video_link );
		} else {
			$this->add_render_attribute( 'button', 'role', 'button' );
		}

		if ( ! empty( $settings['active_ripple'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'ripple' );
		}

		if ( ! empty( $settings['hover_animation'] ) ) {
			$this->add_render_attribute( 'button', 'class', 'elementor-animation-' . $settings['hover_animation'] );
		}

		$migrated = isset( $settings['__fa4_migrated']['btn_icon'] );
		$is_new   = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();
		?>
		<a <?php $this->print_render_attribute_string('button'); ?>>
			<?php
			if ( ! empty( $settings['active_spinner'] ) ) {
				echo '<img class="spinner_image" src="' . esc_url( $settings['sipper_image']['url'] ). '">'; //ignore:phpcs
			}
			?>
			<?php $this->print_unescaped_setting( 'btn_text' ); ?>
			<?php if ( $is_new || $migrated ) :
				Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] );
			else : ?>
				<i class="<?php echo esc_attr( $settings['icon'] ); ?>" aria-hidden="true"></i>
			<?php endif; ?>
		</a>
		<?php
	}
}
