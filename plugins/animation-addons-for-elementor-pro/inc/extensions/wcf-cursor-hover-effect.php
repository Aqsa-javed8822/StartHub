<?php
/**
 * Test Effects extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class WCF_Cursor_Hover_Effects {

	public static function init() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_cursor_hover_effect_controls'
		],2 );

		add_action( 'elementor/element/wcf--a-portfolio/section_layout/after_section_end', [
			__CLASS__,
			'register_cursor_hover_effect_controls'
		],2 );
	}

	public static function enqueue_scripts() {

	}

	public static function register_cursor_hover_effect_controls( $element ) {
		$tab  = Controls_Manager::TAB_CONTENT;

		if ( 'container' === $element->get_name() ) {
			$tab = Controls_Manager::TAB_ADVANCED; 
		}

		$element->start_controls_section(
			'_section_wcf_cursor_hover_area',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Cursor hover effect', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => $tab,
			]
		);

		$element->add_control(
			'wcf_enable_cursor_hover_effect',
			[
				'label'              => esc_html__( 'Enable q', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_cursor_hover_effect_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [ 'wcf_enable_cursor_hover_effect!' => '' ]
			]
		);

		$element->add_control(
			'wcf_enable_cursor_hover_effect_text',
			[
				'label'              => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'separator'          => 'after',
				'default'            => esc_html__( 'View', 'animation-addons-for-elementor-pro' ),
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae--cursor-hover-effects',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'wcf_enable_cursor_hover_effect',
										'operator' => '===',
										'value'    => 'yes',
									],
								],
							],
						]					
					],
				],
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'wcf_cursor_hover_cursor_typography',
				'selector' => '.wcf-hover-cursor-effect.active-{{ID}}',
			]
		);

		$element->add_control(
			'wcf_cursor_hover_cursor_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'color: {{VALUE}}',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'wcf_cursor_hover_cursor_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '.wcf-hover-cursor-effect.active-{{ID}}',
			]
		);

		$element->add_responsive_control(
			'wcf_cursor_hover_cursor_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
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
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_cursor_hover_cursor_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'separator'  => 'after',
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
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'wcf_cursor_hover_cursor_border',
				'selector' => '.wcf-hover-cursor-effect.active-{{ID}}',
			]
		);

		$element->add_control(
			'wcf_cursor_hover_cursor_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'.wcf-hover-cursor-effect.active-{{ID}}' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Cursor_Hover_Effects::init();
