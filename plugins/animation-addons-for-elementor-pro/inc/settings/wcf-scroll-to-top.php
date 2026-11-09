<?php

namespace WCFAddonsPro\Settings\Tabs;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Scroll_To_Top extends Tab_Base {

	public function get_id() {
		return 'settings-wcf-scroll-to-top';
	}

	public function get_title() {
		return esc_html__( 'AAE Scroll to Top', 'animation-addons-for-elementor-pro' );
	}

	public function get_group() {
		return 'theme-style';
	}

	public function get_icon() {
		return 'wcf eicon-upload-circle-o';
	}

	protected function register_tab_controls() {
		$this->start_controls_section(
			'section_' . $this->get_id(),
			[
				'label' => $this->get_title(),
				'tab'   => $this->get_id(),
			]
		);

		$this->add_control(
			'wcf_enable_scroll_to_top',
			[
				'type'      => Controls_Manager::SWITCHER,
				'label'     => esc_html__( 'Scroll to Top', 'animation-addons-for-elementor-pro' ),
				'default'   => '',
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_layout',
			[
				'label'       => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'label_block' => false,
				'options'     => [
					''       => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'circle' => esc_html__( 'Progress Circle', 'animation-addons-for-elementor-pro' ),
				],
				'separator'   => 'before',
				'condition'   => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);


		$this->add_control(
			'scroll_to_icon',
			[
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-arrow-up',
					'library' => 'fa-solid',
				],
				'condition'   => [ 'wcf_enable_scroll_to_top!' => '' ]
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'unit' => 'px',
					'size' => 18,
				],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'wcf_enable_scroll_to_top!' => '' ]
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 46,
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'width: {{SIZE}}{{UNIT}};',
					'.wcf-scroll-to-top.scroll-to-circle' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 46,
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'wcf_enable_scroll_to_top!' => '',
					'wcf_scroll_to_top_layout' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 5,
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'wcf_enable_scroll_to_top!' => '',
					'wcf_scroll_to_top_layout' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_position',
			[
				'label'       => esc_html__( 'Position', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'bottom-right',
				'label_block' => false,
				'options'     => [
					'bottom-left'  => esc_html__( 'Bottom Left', 'animation-addons-for-elementor-pro' ),
					'bottom-right' => esc_html__( 'Bottom Right', 'animation-addons-for-elementor-pro' ),
				],
				'separator'   => 'before',
				'condition'   => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_position_bottom',
			[
				'label'      => esc_html__( 'Bottom', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'bottom: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_position_left',
			[
				'label'      => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'left: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'wcf_enable_scroll_to_top!'  => '',
					'wcf_scroll_to_top_position' => 'bottom-left',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_position_right',
			[
				'label'      => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'em' => [
						'min'  => 0,
						'max'  => 50,
						'step' => 1,
					],
					'%'  => [
						'min'  => 0,
						'max'  => 100,
						'step' => 1,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 15,
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [
					'wcf_enable_scroll_to_top!'  => '',
					'wcf_scroll_to_top_position' => 'bottom-right',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_z_index',
			[
				'label'      => esc_html__( 'Z Index', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 9999,
						'step' => 10,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 9999,
				],
				'selectors'  => [
					'.wcf-scroll-to-top' => 'z-index: {{SIZE}}',
				],
				'condition'  => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_progress_color',
			[
				'label'     => esc_html__( 'Progress Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'.wcf-scroll-to-top.scroll-to-circle svg.progress-circle path' => 'stroke: {{VALUE}};',
				],
				'separator' => 'before',
				'condition' => [
					'wcf_enable_scroll_to_top!' => '',
					'wcf_scroll_to_top_layout'  => 'circle',
				],
			]
		);

		$this->start_controls_tabs(
			'style_wcf_scroll_tabs'
		);

		$this->start_controls_tab(
			'style_wcf_scroll_normal_tab',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_icon_color',
			[
				'label'     => esc_html__( 'Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'.wcf-scroll-to-top' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'.wcf-scroll-to-top' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'style_wcf_scroll_hover_tab',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_icon_hover_color',
			[
				'label'     => esc_html__( 'Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.wcf-scroll-to-top:hover' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
				'separator' => 'before',
				'condition' => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_to_top_hover_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.wcf-scroll-to-top:hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'wcf_enable_scroll_to_top!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'wcf_scroll_to_top_blend_mode',
			[
				'label'     => esc_html__( 'Blend Mode', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default' => 'normal',
				'options'   => [
					'normal'      => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
					'multiply'    => 'Multiply',
					'screen'      => 'Screen',
					'overlay'     => 'Overlay',
					'darken'      => 'Darken',
					'lighten'     => 'Lighten',
					'color-dodge' => 'Color Dodge',
					'saturation'  => 'Saturation',
					'color'       => 'Color',
					'difference'  => 'Difference',
					'exclusion'   => 'Exclusion',
					'hue'         => 'Hue',
					'luminosity'  => 'Luminosity',
				],
				'selectors' => [
					'.wcf-scroll-to-top' => 'mix-blend-mode: {{VALUE}}',
				],
				'condition' => [ 'wcf_enable_scroll_to_top!' => '' ]
			]
		);

		$this->end_controls_section();
	}
}
