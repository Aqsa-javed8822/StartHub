<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Control_Media;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Advance_Accordion extends Widget_Base {
	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--a-accordion';
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
		return esc_html__( 'Advanced Accordion', 'animation-addons-for-elementor-pro' );
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
		return 'wcf eicon-accordion';
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
		return array( 'animation-addons-for-elementor-pro' );
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
		return array( 'wcf--a-accordion' );
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return array( 'wcf--a-accordion' );
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
			'section_title',
			array(
				'label' => esc_html__( 'Accordion', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'accordion_style',
			array(
				'label'     => esc_html__( 'Accordion Style', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => array(
					''  => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'2' => esc_html__( 'Two', 'animation-addons-for-elementor-pro' ),
				),
				'separator' => 'after',
			)
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'tab_count',
			array(
				'label'       => esc_html__( 'Number', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'O1', 'animation-addons-for-elementor-pro' ),
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => false,
			)
		);

		$repeater->add_control(
			'tab_title',
			array(
				'label'       => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Accordion Title', 'animation-addons-for-elementor-pro' ),
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
			)
		);

		$repeater->add_control(
			'tab_content',
			array(
				'label'   => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Accordion Content', 'animation-addons-for-elementor-pro' ),
			)
		);

		$repeater->add_control(
			'tab_image',
			array(
				'label'   => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::MEDIA,
				'default' => array(
					'url' => Utils::get_placeholder_image_src(),
				),
				'dynamic' => array(
					'active' => true,
				),
			)
		);

		$repeater->add_control(
			'tab_btn_text',
			array(
				'label'       => esc_html__( 'Button Text', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Read more', 'animation-addons-for-elementor-pro' ),
				'placeholder' => esc_html__( 'Type Button Text', 'animation-addons-for-elementor-pro' ),
			)
		);

		$repeater->add_control(
			'tab_btn_link',
			array(
				'label'       => esc_html__( 'Link', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::URL,
				'options'     => array( 'url', 'is_external', 'nofollow' ),
				'default'     => array(
					'url'         => '',
					'is_external' => true,
					'nofollow'    => true,
				),
				'dynamic'     => array(
					'active' => true,
				),
				'label_block' => true,
			)
		);

		$this->add_control(
			'tabs',
			array(
				'label'       => esc_html__( 'Accordion Items', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => array(
					array(
						'tab_title'   => esc_html__( 'Accordion #1', 'animation-addons-for-elementor-pro' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'animation-addons-for-elementor-pro' ),
					),
					array(
						'tab_title'   => esc_html__( 'Accordion #2', 'animation-addons-for-elementor-pro' ),
						'tab_content' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'animation-addons-for-elementor-pro' ),
					),
				),
				'title_field' => '{{{ tab_title }}}',
			)
		);

		$this->add_control(
			'selected_icon',
			array(
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'separator'   => 'before',
				'default'     => array(
					'value'   => 'fas fa-plus',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-solid'   => array(
						'chevron-down',
						'angle-down',
						'angle-double-down',
						'caret-down',
						'caret-square-down',
					),
					'fa-regular' => array(
						'caret-square-down',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
			)
		);

		$this->add_control(
			'selected_active_icon',
			array(
				'label'       => esc_html__( 'Active Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'default'     => array(
					'value'   => 'fas fa-minus',
					'library' => 'fa-solid',
				),
				'recommended' => array(
					'fa-solid'   => array(
						'chevron-up',
						'angle-up',
						'angle-double-up',
						'caret-up',
						'caret-square-up',
					),
					'fa-regular' => array(
						'caret-square-up',
					),
				),
				'skin'        => 'inline',
				'label_block' => false,
				'condition'   => array(
					'selected_icon[value]!' => '',
				),
			)
		);

		$this->add_control(
			'title_html_tag',
			array(
				'label'     => esc_html__( 'Title HTML Tag', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
				),
				'default'   => 'div',
				'separator' => 'before',
			)
		);

		$this->add_control(
			'first_item_open',
			array(
				'label'        => esc_html__( 'First Item Opened', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'template',
				'prefix_class' => 'accordion-first-item-',
			)
		);

		$this->add_control(
			'faq_schema',
			array(
				'label'     => esc_html__( 'FAQ Schema', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
			)
		);

		$this->end_controls_section();

		// style
		$this->start_controls_section(
			'section_title_style',
			array(
				'label' => esc_html__( 'Accordion', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'border_width',
			array(
				'label'      => esc_html__( 'Border Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'range'      => array(
					'px' => array(
						'max' => 20,
					),
					'em' => array(
						'max' => 2,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .accordion-item' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .accordion-item .tab-content' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .accordion-item .tab-title.element-active' => 'border-width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .accordion-item' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .accordion-item .tab-content' => 'border-top-color: {{VALUE}};',
					'{{WRAPPER}} .accordion-item .tab-title.element-active' => 'border-bottom-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_radius',
			array(
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .accordion-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'item_gap',
			array(
				'label'      => esc_html__( 'Item Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					'{{WRAPPER}} .wcf--a-accordion' => 'gap: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_count',
			array(
				'label' => esc_html__( 'Count', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'count_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .count' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'count_typography',
				'selector' => '{{WRAPPER}} .count',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_responsive_control(
			'count_padding',
			array(
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_title',
			array(
				'label' => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'title_align',
			array(
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => array(
					'left'  => array(
						'title' => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default'   => is_rtl() ? 'right' : 'left',
				'toggle'    => false,
				'selectors' => array(
					'{{WRAPPER}} .tab-title' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_background',
			array(
				'label'     => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-title' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .accordion-icon, {{WRAPPER}} .accordion-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .accordion-icon svg' => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'tab_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .element-active .accordion-icon, {{WRAPPER}} .element-active .accordion-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .element-active .accordion-icon svg'                                           => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .accordion-title',
				'global'   => array(
					'default' => Global_Typography::TYPOGRAPHY_PRIMARY,
				),
			)
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			array(
				'name'     => 'text_stroke',
				'selector' => '{{WRAPPER}} .accordion-title',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'title_shadow',
				'selector' => '{{WRAPPER}} .accordion-title',
			)
		);

		$this->add_responsive_control(
			'title_padding',
			array(
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .tab-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_icon',
			array(
				'label'     => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array(
					'selected_icon[value]!' => '',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'      => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array(
						'max' => 100,
					),
					'em'  => array(
						'max' => 1,
					),
					'rem' => array(
						'max' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .accordion-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'icon_align',
			array(
				'label'   => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::CHOOSE,
				'options' => array(
					'left'  => array(
						'title' => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-left',
					),
					'right' => array(
						'title' => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-right',
					),
				),
				'default' => is_rtl() ? 'right' : 'left',
				'toggle'  => false,
			)
		);

		$this->add_control(
			'icon_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .accordion-icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .accordion-icon svg'      => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'icon_active_color',
			array(
				'label'     => esc_html__( 'Active Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .element-active .accordion-icon i:before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .element-active .accordion-icon svg'      => 'fill: {{VALUE}};',
				),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'      => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', 'em', 'rem', 'custom' ),
				'range'      => array(
					'px'  => array(
						'max' => 100,
					),
					'em'  => array(
						'max' => 1,
					),
					'rem' => array(
						'max' => 1,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .accordion-icon.accordion-icon-left'  => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .accordion-icon.accordion-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_toggle_style_content',
			array(
				'label' => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'content_background_color',
			array(
				'label'     => esc_html__( 'Background', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-content' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'content_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .tab-content' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'content_typography',
				'selector' => '{{WRAPPER}} .tab-content',
			)
		);

		$this->add_group_control(
			Group_Control_Text_Shadow::get_type(),
			array(
				'name'     => 'content_shadow',
				'selector' => '{{WRAPPER}} .tab-content',
			)
		);

		$this->add_responsive_control(
			'content_padding',
			array(
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'vw', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .tab-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Image
		$this->start_controls_section(
			'style_tab_image',
			array(
				'label'     => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'accordion_style' => '2' ),
			)
		);

		$this->add_responsive_control(
			'tab_img_width',
			array(
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .acc-image img' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_img_height',
			array(
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range'      => array(
					'px' => array(
						'min' => 0,
						'max' => 1000,
					),
					'%'  => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .acc-image img' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'tab_img_margin',
			array(
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .acc-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		// Button
		$this->start_controls_section(
			'acc_btn_style',
			array(
				'label'     => esc_html__( 'Button', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => array( 'accordion_style' => '2' ),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'acc_btn_typo',
				'selector' => '{{WRAPPER}} .acc-btn',
			)
		);

		$this->add_responsive_control(
			'acc_btn_padding',
			array(
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em', 'rem', 'custom' ),
				'selectors'  => array(
					'{{WRAPPER}} .acc-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs(
			'style_acc_btn_tabs'
		);

		// Normal
		$this->start_controls_tab(
			'acc_btn_normal_tab',
			array(
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'acc_btn_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .acc-btn'        => 'color: {{VALUE}}',
					'{{WRAPPER}} .acc-btn::after' => 'background-color: {{VALUE}}',
				),
			)
		);

		$this->end_controls_tab();

		// Hover
		$this->start_controls_tab(
			'acc_btn_hover_tab',
			array(
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			)
		);

		$this->add_control(
			'acc_btn_h_color',
			array(
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .acc-btn:hover'        => 'color: {{VALUE}}',
					'{{WRAPPER}} .acc-btn:hover::after' => 'background-color: {{VALUE}}',
				),
			)
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
		$this->add_render_attribute( 'wrapper', array( 'class' => 'wcf--a-accordion style-' . $settings['accordion_style'] ) );
		$id_int = substr( $this->get_id_int(), 0, 3 );
		?>
		<div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			foreach ( $settings['tabs'] as $index => $item ) :
				$tab_count = $index + 1;

				$tab_title_setting_key = $this->get_repeater_setting_key( 'tab_title', 'tabs', $index );

				$tab_content_setting_key = $this->get_repeater_setting_key( 'tab_content', 'tabs', $index );

				$this->add_render_attribute(
					$tab_title_setting_key,
					array(
						'id'            => 'tab-title-' . $id_int . $tab_count,
						'class'         => array( 'tab-title' ),
						'data-tab'      => $tab_count,
						'role'          => 'button',
						'aria-controls' => 'tab-content-' . $id_int . $tab_count,
						'aria-expanded' => 'false',
					)
				);

				$this->add_render_attribute(
					$tab_content_setting_key,
					array(
						'id'              => 'tab-content-' . $id_int . $tab_count,
						'class'           => array( 'tab-content' ),
						'data-tab'        => $tab_count,
						'role'            => 'region',
						'aria-labelledby' => 'tab-title-' . $id_int . $tab_count,
					)
				);
				?>
			<div class="accordion-item">
				<?php
				if ( '2' === $settings['accordion_style'] ) {
					$this->render_advanced_accordion_two( $settings, $tab_content_setting_key, $index, $item, $tab_title_setting_key );
				} else {
					$this->render_advanced_accordion_one( $settings, $tab_content_setting_key, $index, $item, $tab_title_setting_key );
				}
				?>
		</div>
	<?php endforeach; ?>
		<?php

		if ( isset( $settings['faq_schema'] ) && 'yes' === $settings['faq_schema'] ) {
			$json = array(
				'@context'   => 'https://schema.org',
				'@type'      => 'FAQPage',
				'mainEntity' => array(),
			);

			foreach ( $settings['tabs'] as $index => $item ) {
				$json['mainEntity'][] = array(
					'@type'          => 'Question',
					'name'           => wp_strip_all_tags( $item['tab_title'] ),
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $this->parse_text_editor( $item['tab_content'] ),
					),
				);
			}
			?>
			<script type="application/ld+json"><?php echo wp_json_encode( $json ); ?></script>
		<?php } ?>
		</div>
		<?php
	}


	protected function render_advanced_accordion_one( $settings, $tab_content_setting_key, $index, $item, $tab_title_setting_key ) {
		?>
		<<?php Utils::print_validated_html_tag( $settings['title_html_tag'] ); ?> <?php $this->print_render_attribute_string( $tab_title_setting_key ); ?>
		>
		<span class="accordion-icon accordion-icon-<?php echo esc_attr( $settings['icon_align'] ); ?>"
				aria-hidden="true">
					<span class="icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
					<span class="icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
				</span>
				<span class="accordion-title" tabindex="0">
					<?php
					if ( ! empty( $item['tab_count'] ) ) :
						?>
						<span class="count"> <?php $this->print_unescaped_setting( 'tab_count', 'tabs', $index ); ?> </span>
						<?php
					endif;
					?>

			<?php $this->print_unescaped_setting( 'tab_title', 'tabs', $index ); ?>
				</span>
		</<?php Utils::print_validated_html_tag( $settings['title_html_tag'] ); ?>>
		<div <?php $this->print_render_attribute_string( $tab_content_setting_key ); ?>>
			<?php $this->print_text_editor( $item['tab_content'] ); ?>
		</div>
		<?php
	}

	protected function render_advanced_accordion_two( $settings, $tab_content_setting_key, $index, $item, $tab_title_setting_key ) {
		$tab_btn_id = 'tab_btn_' . $index;
		$this->add_link_attributes( $tab_btn_id, $item['tab_btn_link'] );
		?>
		<<?php Utils::print_validated_html_tag( $settings['title_html_tag'] ); ?> <?php $this->print_render_attribute_string( $tab_title_setting_key ); ?>>
		<?php
		if ( ! empty( $item['tab_count'] ) ) :
			?>
			<span class="count"> <?php $this->print_unescaped_setting( 'tab_count', 'tabs', $index ); ?> </span>
			<?php
		endif;
		?>
		<span class="accordion-title" tabindex="0">
		<?php $this->print_unescaped_setting( 'tab_title', 'tabs', $index ); ?>
		</span>

		<span class="accordion-icon accordion-icon-<?php echo esc_attr( $settings['icon_align'] ); ?>"
			aria-hidden="true">
			<span class="icon-closed"><?php Icons_Manager::render_icon( $settings['selected_icon'] ); ?></span>
			<span class="icon-opened"><?php Icons_Manager::render_icon( $settings['selected_active_icon'] ); ?></span>
		</span>
		</<?php Utils::print_validated_html_tag( $settings['title_html_tag'] ); ?>>

		<!-- Accordion Content -->
		<div <?php $this->print_render_attribute_string( $tab_content_setting_key ); ?>>
			<div class="content-wrap">
				<?php if ( ! empty( $item['tab_image']['url'] ) ) : ?>
				<div class="acc-image">
					<img src="<?php echo esc_url( $item['tab_image']['url'] ); ?>" alt="<?php echo esc_attr( $item['tab_title'] ); ?>">
				</div>
				<?php endif; ?>
				<div class="acc-content">
					<?php $this->print_text_editor( $item['tab_content'] ); ?>
					<a <?php $this->print_render_attribute_string( $tab_btn_id ); ?> class="acc-btn">
						<?php echo esc_html( $item['tab_btn_text'] ); ?>
					</a>
				</div>
			</div>
		</div>
		<?php
	}
}
