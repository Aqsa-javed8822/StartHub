<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WCF_ADDONS\WCF_Button_Trait;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Flip Box
 *
 * Elementor widget for flip-box.
 *
 * @since 1.0.0
 */
class Flip_Box extends Widget_Base {
	use  WCF_Button_Trait;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--flip-box';
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
		return esc_html__( 'Flip Box', 'animation-addons-for-elementor-pro' );
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
		return 'wcf eicon-flip-box';
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
		return [ 'animation-addons-for-elementor-pro' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'wcf--flip-box' ];
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
		$this->start_controls_section(
			'section_flip_box',
			[
				'label' => esc_html__( 'Flip Box', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->start_controls_tabs( 'flipbox_content_tabs' );

		$this->start_controls_tab(
			'flipbox_content_front',
			[
				'label' => __( 'Front', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'flipbox_front_content_type',
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

		$this->add_control(
			'flipbox_front_templates',
			[
				'label'       => esc_html__( 'Save Template', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => false,
				'multiple'    => false,
				'options'     => wcf_addons_get_saved_template_list(),
				'condition'   => [
					'flipbox_front_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'flipbox_img_or_icon',
			[
				'label'     => esc_html__( 'Icon Type', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none' => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'img'  => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
					'icon' => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => 'icon',
				'condition' => [
					'flipbox_front_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_image',
			[
				'label'     => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'flipbox_img_or_icon'        => 'img',
					'flipbox_front_content_type' => 'content',
				],
				'ai'        => [
					'active' => false,
				],				
			]
		);

		$this->add_control(
			'flipbox_icon',
			[
				'label'     => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-snowflake',
					'library' => 'fa-solid',
				],
				'condition' => [
					'flipbox_img_or_icon'        => 'icon',
					'flipbox_front_content_type' => 'content',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail',
				'default'   => 'full',
				'condition' => [
					'flipbox_front_content_type' => 'content',
					'flipbox_image[url]!'        => '',
					'flipbox_img_or_icon'        => 'img',
				],
			]
		);

		$this->add_control(
			'flipbox_front_title',
			[
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'separator'   => 'before',
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
				'default'     => esc_html__( 'Front Title', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'flipbox_front_content_type' => 'content',
				],
				'ai'          => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'flipbox_front_title_tag',
			[
				'label'     => esc_html__( 'Title Tag', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => [
					'h1'   => esc_html__( 'H1', 'animation-addons-for-elementor-pro' ),
					'h2'   => esc_html__( 'H2', 'animation-addons-for-elementor-pro' ),
					'h3'   => esc_html__( 'H3', 'animation-addons-for-elementor-pro' ),
					'h4'   => esc_html__( 'H4', 'animation-addons-for-elementor-pro' ),
					'h5'   => esc_html__( 'H5', 'animation-addons-for-elementor-pro' ),
					'h6'   => esc_html__( 'H6', 'animation-addons-for-elementor-pro' ),
					'span' => esc_html__( 'Span', 'animation-addons-for-elementor-pro' ),
					'p'    => esc_html__( 'P', 'animation-addons-for-elementor-pro' ),
					'div'  => esc_html__( 'Div', 'animation-addons-for-elementor-pro' ),
				],
				'condition' => [
					'flipbox_front_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_front_text',
			[
				'label'       => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default'     => esc_html__( 'This is front side content.', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'flipbox_front_content_type' => 'content',
				],
				'ai'          => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'flipbox_front_vertical_position',
			[
				'label'                => esc_html__( 'Vertical Position', 'animation-addons-for-elementor-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'top'    => [
						'title' => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => esc_html__( 'Middle', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'              => 'middle',
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .flip-box-front' => 'justify-content: {{VALUE}}',
				],
				'condition'            => [
					'flipbox_front_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_content_alignment',
			[
				'label'       => esc_html__( 'Content Alignment', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options'     => [
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
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .flip-box-front-inner' => 'text-align: {{VALUE}}',
				],
				'condition'   => [
					'flipbox_front_content_type' => 'content',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'flipbox_content_back',
			[
				'label' => __( 'Back', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'flipbox_back_content_type',
			[
				'label'   => __( 'Content Type', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'content'  => __( 'Content', 'animation-addons-for-elementor-pro' ),
					'template' => __( 'Saved Templates', 'animation-addons-for-elementor-pro' ),
				],
				'default' => 'content',
			]
		);

		$this->add_control(
			'flipbox_back_templates',
			[
				'label'       => esc_html__( 'Save Template', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => false,
				'multiple'    => false,
				'options'     => wcf_addons_get_saved_template_list(),
				'condition'   => [
					'flipbox_back_content_type' => 'template',
				],
			]
		);

		$this->add_control(
			'flipbox_img_or_icon_back',
			[
				'label'     => esc_html__( 'Icon Type', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'none' => __( 'None', 'animation-addons-for-elementor-pro' ),
					'img'  => __( 'Image', 'animation-addons-for-elementor-pro' ),
					'icon' => __( 'Icon', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => 'icon',
				'condition' => [
					'flipbox_back_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_image_back',
			[
				'label'     => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::MEDIA,
				'default'   => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'flipbox_back_content_type' => 'content',
					'flipbox_img_or_icon_back'  => 'img',
				],
				'ai'        => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'flipbox_icon_back',
			[
				'label'     => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::ICONS,
				'default'   => [
					'value'   => 'fas fa-snowflake',
					'library' => 'fa-solid',
				],
				'condition' => [
					'flipbox_back_content_type' => 'content',
					'flipbox_img_or_icon_back'  => 'icon',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail_back',
				'default'   => 'full',
				'condition' => [
					'flipbox_back_content_type' => 'content',
					'flipbox_image_back[url]!'  => '',
					'flipbox_img_or_icon_back'  => 'img',
				],
			]
		);

		$this->add_control(
			'flipbox_back_title',
			[
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'separator'   => 'before',
				'dynamic'     => [
					'active' => true,
				],
				'label_block' => true,
				'default'     => esc_html__( 'Back Title', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'flipbox_back_content_type' => 'content',
				],
				'ai'          => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'flipbox_back_title_tag',
			[
				'label'     => __( 'Title Tag', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'h2',
				'options'   => [
					'h1'   => __( 'H1', 'animation-addons-for-elementor-pro' ),
					'h2'   => __( 'H2', 'animation-addons-for-elementor-pro' ),
					'h3'   => __( 'H3', 'animation-addons-for-elementor-pro' ),
					'h4'   => __( 'H4', 'animation-addons-for-elementor-pro' ),
					'h5'   => __( 'H5', 'animation-addons-for-elementor-pro' ),
					'h6'   => __( 'H6', 'animation-addons-for-elementor-pro' ),
					'span' => __( 'Span', 'animation-addons-for-elementor-pro' ),
					'p'    => __( 'P', 'animation-addons-for-elementor-pro' ),
					'div'  => __( 'Div', 'animation-addons-for-elementor-pro' ),
				],
				'condition' => [
					'flipbox_back_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_back_text',
			[
				'label'       => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'default'     => __( 'This is back side content.', 'animation-addons-for-elementor-pro' ),
				'condition'   => [
					'flipbox_back_content_type' => 'content',
				],
				'ai'          => [
					'active' => false,
				],
			]
		);

		$this->add_control(
			'flipbox_back_vertical_position',
			[
				'label'                => __( 'Vertical Position', 'animation-addons-for-elementor-pro' ),
				'type'                 => Controls_Manager::CHOOSE,
				'options'              => [
					'top'    => [
						'title' => __( 'Top', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'middle' => [
						'title' => __( 'Middle', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'              => 'middle',
				'selectors_dictionary' => [
					'top'    => 'flex-start',
					'middle' => 'center',
					'bottom' => 'flex-end',
				],
				'selectors'            => [
					'{{WRAPPER}} .flip-box-back' => 'justify-content: {{VALUE}}',
				],
				'condition'            => [
					'flipbox_back_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_back_content_alignment',
			[
				'label'       => esc_html__( 'Content Alignment', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options'     => [
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
				'default'     => 'center',
				'selectors'   => [
					'{{WRAPPER}} .flip-box-back-inner' => 'text-align: {{VALUE}}',
				],
				'condition'   => [
					'flipbox_back_content_type' => 'content',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		//Flip box  Settings
		$this->start_controls_section(
			'section_flip_box_settings',
			[
				'label' => esc_html__( 'Settings', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'flip_box_type',
			[
				'label'       => esc_html__( 'Flip-box Type', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'animate-left',
				'label_block' => false,
				'options'     => [
					'animate-left'     => esc_html__( 'Flip Left', 'animation-addons-for-elementor-pro' ),
					'animate-right'    => esc_html__( 'Flip Right', 'animation-addons-for-elementor-pro' ),
					'animate-up'       => esc_html__( 'Flip Top', 'animation-addons-for-elementor-pro' ),
					'animate-down'     => esc_html__( 'Flip Bottom', 'animation-addons-for-elementor-pro' ),
					'animate-zoom-in'  => esc_html__( 'Zoom In', 'animation-addons-for-elementor-pro' ),
					'animate-zoom-out' => esc_html__( 'Zoom Out', 'animation-addons-for-elementor-pro' ),
					'animate-fade-in'  => esc_html__( 'Fade In', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'flip_box_3d',
			[
				'label'        => esc_html__( '3D Depth', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'flip-box--3d',
				'default'      => '',
				'condition'    => [
					'flip_box_type' => [
						'animate-left',
						'animate-right',
						'animate-up',
						'animate-down',
					]
				],
			]
		);

		$this->add_responsive_control(
			'flip_box_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 100,
						'step' => 1,
						'max'  => 1000,
					],
					'%'  => [
						'min'  => 0,
						'step' => 3,
						'max'  => 100,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 300,
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf__flip_box' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'link_type',
			[
				'label'       => esc_html__( 'Link Type', 'animation-addons-for-elementor-pro' ),
				'description' => esc_html__( 'Type button will work if back content type content selected.', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'button',
				'separator'   => 'before',
				'options'     => [
					'none'    => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'button'  => esc_html__( 'Button', 'animation-addons-for-elementor-pro' ),
					'wrapper' => esc_html__( 'Wrapper', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'link',
			[
				'label'       => esc_html__( 'Link', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::URL,
				'dynamic'     => [
					'active' => true,
				],
				'placeholder' =>  'https://your-link.com',
				'condition'   => [
					'link_type' => 'wrapper',
				],
			]
		);

		$this->end_controls_section();

		//button content
		$this->start_controls_section(
			'section_button_content',
			[
				'label'     => esc_html__( 'Button', 'animation-addons-for-elementor-pro' ),
				'condition' => [
					'flipbox_back_content_type' => 'content',
					'link_type'                 => 'button',
				],
			]
		);

		$this->register_button_content_controls();

		$this->end_controls_section();

		//flip box style
		$this->register_flip_box_style_controls();

		//front image style
		$this->register_front_image_style_controls();

		//back image style
		$this->register_back_image_style_controls();

		//front icon
		$this->register_front_icon_style_controls();

		//back icon
		$this->register_back_icon_style_controls();

		//title and content style
		$this->register_flip_box_title_content_style_controls();

		//button style
		$this->start_controls_section(
			'section_btn_style',
			[
				'label'     => esc_html__( 'Button', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'flipbox_back_content_type' => 'content',
					'link_type'                 => 'button',
				],
			]
		);

		$this->register_button_style_controls();

		$this->add_responsive_control(
			'button_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function register_flip_box_style_controls() {

		$this->start_controls_section(
			'section_flipbox_style',
			[
				'label' => esc_html__( 'Flip Box', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'flipbox_front_bg_heading',
			[
				'label' => __( 'Front Background', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'flipbox_front_bg_color',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .flip-box-front',
			]
		);

		$this->add_control(
			'flipbox_back_bg_heading',
			[
				'label'     => __( 'Back Background Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'flipbox_back_bg_color',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .flip-box-back',
			]
		);

		$this->add_responsive_control(
			'flipbox_front_back_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-front' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .flip-box-back'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'filbpox_border',
				'selector' => '{{WRAPPER}} .flip-box-front, {{WRAPPER}} .flip-box-back',
			]
		);

		$this->add_control(
			'flipbox_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-front' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .flip-box-back'  => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'flipbox_shadow',
				'selector' => '{{WRAPPER}} .flip-box-front, {{WRAPPER}} .flip-box-back',
			]
		);

		$this->end_controls_section();

	}

	protected function register_front_image_style_controls() {

		$this->start_controls_section(
			'section_flipbox_front_image_style',
			[
				'label'     => esc_html__( 'Front Image', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'flipbox_img_or_icon'        => 'img',
					'flipbox_front_content_type' => 'content',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_front_img_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
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
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_front_img_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 700,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_front_img_object_fit',
			[
				'label'     => esc_html__( 'Object Fit', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [
					'filpbox_front_img_height[size]!' => '',
				],
				'options'   => [
					''        => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'fill'    => esc_html__( 'Fill', 'animation-addons-for-elementor-pro' ),
					'cover'   => esc_html__( 'Cover', 'animation-addons-for-elementor-pro' ),
					'contain' => esc_html__( 'Contain', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_front_img_object_position',
			[
				'label'     => esc_html__( 'Object Position', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'center center' => esc_html__( 'Center Center', 'animation-addons-for-elementor-pro' ),
					'center left'   => esc_html__( 'Center Left', 'animation-addons-for-elementor-pro' ),
					'center right'  => esc_html__( 'Center Right', 'animation-addons-for-elementor-pro' ),
					'top center'    => esc_html__( 'Top Center', 'animation-addons-for-elementor-pro' ),
					'top left'      => esc_html__( 'Top Left', 'animation-addons-for-elementor-pro' ),
					'top right'     => esc_html__( 'Top Right', 'animation-addons-for-elementor-pro' ),
					'bottom center' => esc_html__( 'Bottom Center', 'animation-addons-for-elementor-pro' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'animation-addons-for-elementor-pro' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => 'center center',
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image img' => 'object-position: {{VALUE}};',
				],
				'condition' => [
					'filpbox_front_img_object_fit' => 'cover',
				],
			]
		);


		$this->add_control(
			'filpbox_front_img_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image img' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function register_back_image_style_controls() {

		$this->start_controls_section(
			'section_flipbox_back_image_style',
			[
				'label'     => esc_html__( 'Back Image', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'flipbox_img_or_icon_back'  => 'img',
					'flipbox_back_content_type' => 'content',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_back_img_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
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
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_back_img_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 700,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_back_img_object_fit',
			[
				'label'     => esc_html__( 'Object Fit', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [
					'filpbox_back_img_height[size]!' => '',
				],
				'options'   => [
					''        => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'fill'    => esc_html__( 'Fill', 'animation-addons-for-elementor-pro' ),
					'cover'   => esc_html__( 'Cover', 'animation-addons-for-elementor-pro' ),
					'contain' => esc_html__( 'Contain', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'filpbox_back_img_object_position',
			[
				'label'     => esc_html__( 'Object Position', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'center center' => esc_html__( 'Center Center', 'animation-addons-for-elementor-pro' ),
					'center left'   => esc_html__( 'Center Left', 'animation-addons-for-elementor-pro' ),
					'center right'  => esc_html__( 'Center Right', 'animation-addons-for-elementor-pro' ),
					'top center'    => esc_html__( 'Top Center', 'animation-addons-for-elementor-pro' ),
					'top left'      => esc_html__( 'Top Left', 'animation-addons-for-elementor-pro' ),
					'top right'     => esc_html__( 'Top Right', 'animation-addons-for-elementor-pro' ),
					'bottom center' => esc_html__( 'Bottom Center', 'animation-addons-for-elementor-pro' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'animation-addons-for-elementor-pro' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => 'center center',
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image img' => 'object-position: {{VALUE}};',
				],
				'condition' => [
					'filpbox_back_img_object_fit' => 'cover',
				],
			]
		);


		$this->add_control(
			'filpbox_back_img_border_radius',
			[
				'label'     => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image img' => 'border-radius: {{SIZE}}px;',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function register_front_icon_style_controls() {

		$this->start_controls_section(
			'section_flipbox_front_icon_style',
			[
				'label'     => esc_html__( 'Front Icon', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'flipbox_img_or_icon'        => 'icon',
					'flipbox_front_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_front_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'flipbox_front_icon_bg_color',
			[
				'label'     => esc_html__( 'Icon BG Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'flipbox_front_icon_size',
			[
				'label'      => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'flipbox_front_icon_padding',
			[
				'label'     => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range'     => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'flipbox_front_icon_border',
				'selector' => '{{WRAPPER}} .flip-box-front .flip-box-icon-image',
			]
		);

		$this->add_responsive_control(
			'flipbox_front_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-front .flip-box-icon-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function register_back_icon_style_controls() {

		$this->start_controls_section(
			'section_flipbox_back_icon_style',
			[
				'label'     => esc_html__( 'Back Icon', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'flipbox_img_or_icon_back'  => 'icon',
					'flipbox_back_content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'flipbox_back_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'flipbox_back_icon_bg_color',
			[
				'label'     => esc_html__( 'Icon BG Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'flipbox_back_icon_size',
			[
				'label'      => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'flipbox_back_icon_padding',
			[
				'label'     => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'range'     => [
					'em' => [
						'min' => 0,
						'max' => 5,
					],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'flipbox_back_icon_border',
				'selector' => '{{WRAPPER}} .flip-box-back .flip-box-icon-image',
			]
		);

		$this->add_responsive_control(
			'flipbox_back_icon_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-back .flip-box-icon-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

	}

	protected function register_flip_box_title_content_style_controls() {

		$this->start_controls_section(
			'section_flipbox_title_content_style',
			[
				'label' => esc_html__( 'Title/Content', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'flipbox_title_content_style_tabs' );

		$this->start_controls_tab( 'flipbox_title_content_front_style', [
			'label' => esc_html__( 'Front', 'animation-addons-for-elementor-pro' ),
		] );

		//title
		$this->add_control(
			'flipbox_front_title_style_heading',
			[
				'label' => esc_html__( 'Title Style', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'flipbox_front_title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'flipbox_front_title_typography',
				'selector' => '{{WRAPPER}} .flip-box-front .flip-box-title',
			]
		);

		$this->add_responsive_control(
			'flipbox_front_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-front .flip-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//content
		$this->add_control(
			'flipbox_front_content_style_heading',
			[
				'label'     => esc_html__( 'Content Style', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'flipbox_front_content_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-front .flip-box-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'flipbox_front_content_typography',
				'selector' => '{{WRAPPER}} .flip-box-front .flip-box-desc',
			]
		);

		$this->add_responsive_control(
			'flipbox_front_content_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-front .flip-box-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'flipbox_title_content_back_style', [
			'label' => esc_html__( 'Back', 'animation-addons-for-elementor-pro' ),
		] );

		//==back==
		//title
		$this->add_control(
			'flipbox_back_title_heading',
			[
				'label' => esc_html__( 'Title Style', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'flipbox_back_title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'flipbox_back_title_typography',
				'selector' => '{{WRAPPER}} .flip-box-back .flip-box-title',
			]
		);

		$this->add_responsive_control(
			'flipbox_back_title_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-back .flip-box-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//content
		$this->add_control(
			'flipbox_back_content_heading',
			[
				'label'     => esc_html__( 'Content Style', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'flipbox_back_content_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .flip-box-back .flip-box-desc' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'flipbox_back_content_typography',
				'selector' => '{{WRAPPER}} .flip-box-back .flip-box-desc',
			]
		);

		$this->add_responsive_control(
			'flipbox_back_content_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .flip-box-back .flip-box-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

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

		$this->add_render_attribute( 'wrapper', 'class', [
			'wcf__flip_box',
			'flip-box-' . $settings['flip_box_type'],
			$settings['flip_box_3d'],
		] );

		//link tag
		$link_tag = 'div';
		if ( ! empty( $settings['link']['url'] ) && 'wrapper' === $settings['link_type'] ) {
			$link_tag = 'a';
			$this->add_link_attributes( 'wrapper', $settings['link'] );
		}
		?>
        <<?php Utils::print_validated_html_tag( $link_tag ); ?> <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
        <div class="flip-box-inner">
			<?php
			$this->render_flip_box_front( $settings );
			$this->render_flip_box_back( $settings );
			?>
        </div>
        </<?php Utils::print_validated_html_tag( $link_tag ); ?>>
		<?php
	}

	protected function render_flip_box_front( $settings ) {
		$front_settings = [
			'icon_type'  => $settings['flipbox_img_or_icon'],
			'icon'       => $settings['flipbox_icon'],
			'image'      => $settings['flipbox_image'],
			'image_size' => $settings['thumbnail_size'],
			'title'      => 'flipbox_front_title', //unescape settings
			'title_tag'  => $settings['flipbox_front_title_tag'],
			'content'    => $settings['flipbox_front_text'],
		];
		?>
        <div class="flip-box-front">
            <div class="flip-box-front-inner">
				<?php
				if ( 'template' === $settings['flipbox_front_content_type'] ) {
					if ( ! empty( $settings['flipbox_front_templates'] ) ) {
						echo Plugin::$instance->frontend->get_builder_content( $settings['flipbox_front_templates'], true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					$this->render_flip_box_icon( $front_settings );
					$this->render_flip_box_title( $front_settings );
					$this->render_flip_box_content( $front_settings );
				} ?>
            </div>
        </div>
		<?php
	}

	protected function render_flip_box_back( $settings ) {
		$front_settings = [
			'icon_type'  => $settings['flipbox_img_or_icon_back'],
			'icon'       => $settings['flipbox_icon_back'],
			'image'      => $settings['flipbox_image_back'],
			'image_size' => $settings['thumbnail_back_size'],
			'title'      => 'flipbox_back_title',  //unescape settings
			'title_tag'  => $settings['flipbox_back_title_tag'],
			'content'    => $settings['flipbox_back_text'],
		];
		?>
        <div class="flip-box-back">
            <div class="flip-box-back-inner">
				<?php
				if ( 'template' === $settings['flipbox_back_content_type'] ) {
					if ( ! empty( $settings['flipbox_back_templates'] ) ) {
						echo Plugin::$instance->frontend->get_builder_content( $settings['flipbox_back_templates'], true ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					}
				} else {
					$this->render_flip_box_icon( $front_settings );
					$this->render_flip_box_title( $front_settings );
					$this->render_flip_box_content( $front_settings );
					if ( 'button' === $settings['link_type'] ) {
						$this->render_button( $settings );
					}
				} ?>
            </div>
        </div>
		<?php
	}

	protected function render_flip_box_icon( $settings ) {

		if ( 'none' === $settings['icon_type'] ) {
			return;
		}
		?>
        <div class="flip-box-icon-image">
			<?php

			if ( 'img' === $settings['icon_type'] ) {
				Group_Control_Image_Size::print_attachment_image_html( $settings, 'image', 'image' );
			}

			if ( 'icon' === $settings['icon_type'] ) {
				Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
			}
			?>
        </div>
		<?php
	}

	protected function render_flip_box_title( $settings ) {
		if ( empty( $settings['title'] ) ) {
			return;
		}
		?>
        <<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?> class="flip-box-title">
		<?php $this->print_unescaped_setting( $settings['title'] ); ?>
        </<?php Utils::print_validated_html_tag( $settings['title_tag'] ); ?>>
		<?php
	}

	protected function render_flip_box_content( $settings ) {
		if ( Utils::is_empty( $settings['content'] ) ) {
			return;
		}
		?>
        <div class="flip-box-desc">
			<?php echo $this->parse_text_editor( $settings['content'] ) // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
		<?php
	}
}
