<?php

namespace WCFAddonsPro\Settings\Tabs;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Popup extends Tab_Base {

	public function get_id() {
		return 'settings-wcf-popup';
	}

	public function get_title() {
		return esc_html__( 'AAE Popup', 'animation-addons-for-elementor-pro' );
	}

	public function get_group() {
		return 'theme-style';
	}

	public function get_icon() {
		return 'wcf eicon-play';
	}

	protected function register_tab_controls() {
		$this->start_controls_section(
			'section_' . $this->get_id(),
			[
				'label' => $this->get_title(),
				'tab'   => $this->get_id(),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'wcf_popup_bg',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '.popup-overlay;',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'gl_top_spacing',
			[
				'label'      => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => -300,
						'max' => 300,
					],
				],
				'selectors'  => [
					'.aae-popup-builder.aae-gsap-popup-builder' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'gl_left_spacing',
			[
				'label'      => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => -300,
						'max' => 1000,
					],
				],
				'selectors'  => [
					'.aae-popup-builder.aae-gsap-popup-builder' => 'left: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'close_icon',
			[
				'label'       => esc_html__( 'Close Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'far fa-window-close',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'close_ibg_color',
			[
				'label'     => esc_html__( 'Icon Bg Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.aae-gsap-popup-builder .close-button' => 'background: {{VALUE}}!important; color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'close_i_color',
			[
				'label'     => esc_html__( 'Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.aae-gsap-popup-builder .close-button' => 'color: {{VALUE}} !important',
				],
			]
		);


		$this->add_responsive_control(
			'close_i_size',
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
					'.aae-gsap-popup-builder .close-button' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'close_position',
			[
				'label'   => esc_html__( 'Close Icon Position', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'right' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
					'left'  => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'close_top_spacing',
			[
				'label'      => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.aae-gsap-popup-builder .close-button' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);		
	

		$this->add_responsive_control(
			'close_left_spacing',
			[
				'label'      => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.aae-gsap-popup-builder .close-button' => 'right: unset; left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'close_position' => 'left' ],
			]
		);

		$this->add_responsive_control(
			'close_right_spacing',
			[
				'label'      => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'.aae-gsap-popup-builder .close-button' => 'left: unset; right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'close_position' => 'right' ],
			]
		);

		$this->end_controls_section();
	}
}
