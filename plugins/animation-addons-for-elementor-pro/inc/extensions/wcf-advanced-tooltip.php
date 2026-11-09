<?php
/**
 * Advanced Tooltip extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;


defined( 'ABSPATH' ) || die();

class WCF_Advanced_Tooltip {
 
	public static function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'add_controls_section'
		], 19 );

		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'add_controls_section'
		], 19 );
	}

	public static function add_controls_section( $element ) {

		$element->start_controls_section(
			'_section_wcf_advanced_tooltip',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Tooltip', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_advanced_tooltip_enable',
			[
				'label'              => __( 'Enable Tooltip?', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'On', 'animation-addons-for-elementor-pro' ),
				'label_off'          => __( 'Off', 'animation-addons-for-elementor-pro' ),
				'return_value'       => 'enable',
				'default'            => '',
				'frontend_available' => true,				
			]
		);

		$element->start_controls_tabs( 'wcf_tooltip_tabs' );

		$element->start_controls_tab( 'wcf_tooltip_settings', [
			'label'     => __( 'Settings', 'animation-addons-for-elementor-pro' ),
			'condition' => [
				'wcf_advanced_tooltip_enable!' => '',
			],
		] );

		$element->add_control(
			'wcf_advanced_tooltip_content',
			[
				'label'              => __( 'Content', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXTAREA,
				'rows'               => 5,
				'default'            => __( 'I am a tooltip', 'animation-addons-for-elementor-pro' ),
				'dynamic'            => [ 'active' => true ],
				'frontend_available' => true,
				'condition'          => [
					'wcf_advanced_tooltip_enable!' => '',
				],
				'assets' => [
					'scripts' => [
						[
							'name'       => 'wcf--advanced-tooltips',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'wcf_advanced_tooltip_enable',
										'operator' => '===',
										'value'    => 'enable',
									],
								],
							],
						]					
					],
				],
			]
		);

		$element->add_responsive_control(
			'wcf_advanced_tooltip_position',
			[
				'label'        => __( 'Position', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'top',
				'options'      => [
					'top'    => __( 'Top', 'animation-addons-for-elementor-pro' ),
					'bottom' => __( 'Bottom', 'animation-addons-for-elementor-pro' ),
					'left'   => __( 'Left', 'animation-addons-for-elementor-pro' ),
					'right'  => __( 'Right', 'animation-addons-for-elementor-pro' ),
				],
				'condition'    => [
					'wcf_advanced_tooltip_enable!' => '',
				],
				'prefix_class' => 'wcf-advanced-tooltip%s-',
			]
		);

		$element->add_control(
			'wcf_advanced_tooltip_animation',
			[
				'label'              => esc_html__( 'Animation', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::ANIMATION,
				'frontend_available' => true,
				'default'            => 'fadeIn',
				'render_type'        => 'template', // template
				'condition'          => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_advanced_tooltip_duration',
			[
				'label'              => __( 'Animation Duration (ms)', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 100,
				'max'                => 5000,
				'step'               => 50,
				'default'            => 1000,
				'frontend_available' => true,
				'condition'          => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_advanced_tooltip_arrow',
			[
				'label'              => __( 'Arrow', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'label_on'           => __( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'          => __( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value'       => 'true',
				'default'            => 'true',
				'frontend_available' => true,
				'condition'          => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_advanced_tooltip_trigger',
			[
				'label'              => __( 'Trigger', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'hover',
				'options'            => [
					'click' => __( 'Click', 'animation-addons-for-elementor-pro' ),
					'hover' => __( 'Hover', 'animation-addons-for-elementor-pro' ),
				],
				'frontend_available' => true,
				'condition'          => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_advanced_tooltip_distance',
			[
				'label'     => __( 'Distance', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '0',
				],
				'range'     => [
					'px' => [
						'min' => - 100,
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-advanced-tooltip' => '--tooltip-arrow-distance: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_advanced_tooltip_align',
			[
				'label'     => __( 'Text Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => 'center',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .wcf-advanced-tooltip' => 'text-align: {{VALUE}};'
				],
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->end_controls_tab();

		$element->start_controls_tab( 'wcf_advanced_tooltip_styles', [
			'label'     => __( 'Styles', 'animation-addons-for-elementor-pro' ),
			'condition' => [
				'wcf_advanced_tooltip_enable!' => '',
			],
		] );

		$element->add_responsive_control(
			'wcf_advanced_tooltip_width',
			[
				'label'     => __( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '120',
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 800,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-advanced-tooltip' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_advanced_tooltip_arrow_size',
			[
				'label'     => __( 'Tooltip Arrow Size (px)', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => '5',
				],
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-advanced-tooltip::after' => 'border-width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
					'wcf_advanced_tooltip_arrow'   => 'true',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'           => 'wcf_advanced_tooltip_typography',
				'separator'      => 'after',
				'fields_options' => [
					'typography'  => [
						'default' => 'yes'
					],
					'font_family' => [
						'default' => 'Nunito',
					],
					'font_weight' => [
						'default' => '500', // 100, 200, 300, 400, 500, 600, 700, 800, 900, normal, bold
					],
					'font_size'   => [
						'default' => [
							'unit' => 'px', // px, em, rem, vh
							'size' => '14', // any number
						],
					],
				],
				'selector'       => '{{WRAPPER}} .wcf-advanced-tooltip',
				'condition'      => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_advanced_tooltip_background_color',
			[
				'label'     => __( 'Background Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#000000',
				'selectors' => [
					'{{WRAPPER}} .wcf-advanced-tooltip'        => 'background: {{VALUE}};',
					'{{WRAPPER}} .wcf-advanced-tooltip::after' => '--tooltip-arrow-color: {{VALUE}}',
				],
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_control(
			'wcf_advanced_tooltip_color',
			[
				'label'     => __( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .wcf-advanced-tooltip' => 'color: {{VALUE}};',
				],
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_advanced_tooltip_border_radius',
			[
				'label' => __('Border Radius', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .wcf-advanced-tooltip' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_advanced_tooltip_padding',
			[
				'label'      => __( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-advanced-tooltip' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'wcf_advanced_tooltip_box_shadow',
				'selector'  => '{{WRAPPER}} .wcf-advanced-tooltip',
				'separator' => '',
				'condition' => [
					'wcf_advanced_tooltip_enable!' => '',
				],
			]
		);

		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}
}

WCF_Advanced_Tooltip::init();
