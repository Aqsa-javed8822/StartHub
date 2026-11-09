<?php

namespace WCFAddonsPro\Settings\Tabs;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Group_Control_Background;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Preloader extends Tab_Base {

	public function get_id() {
		return 'settings-wcf-preloader';
	}

	public function get_title() {
		return esc_html__( 'AAE Preloader', 'animation-addons-for-elementor-pro' );
	}

	public function get_group() {
		return 'theme-style';
	}

	public function get_icon() {
		return 'wcf eicon-loading';
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
			'wcf_enable_preloader',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Preloader', 'animation-addons-for-elementor-pro' ),
				'default'            => '',
				'label_on'           => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'          => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'wcf_preloader_layout',
			[
				'label'       => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'whirlpool',
				'label_block' => false,
				'options'     => [
					'whirlpool'          => esc_html__( 'Whirlpool', 'animation-addons-for-elementor-pro' ),
					'floating-circles'   => esc_html__( 'Floating Circle', 'animation-addons-for-elementor-pro' ),
					'eight-spinning'     => esc_html__( 'Eight Spinning', 'animation-addons-for-elementor-pro' ),
					'double-torus'       => esc_html__( 'Double Torus', 'animation-addons-for-elementor-pro' ),
					'tube-tunnel'        => esc_html__( 'Tube Tunnel', 'animation-addons-for-elementor-pro' ),
					'speeding-wheel'     => esc_html__( 'Speeding Wheel', 'animation-addons-for-elementor-pro' ),
					'loading'            => esc_html__( 'Loading', 'animation-addons-for-elementor-pro' ),
					'dot-loading'        => esc_html__( 'Dot Loading', 'animation-addons-for-elementor-pro' ),
					'fountainTextG'      => esc_html__( 'FountainTextG', 'animation-addons-for-elementor-pro' ),
					'circle-loading'     => esc_html__( 'Circle Loading', 'animation-addons-for-elementor-pro' ),
					'dot-circle-rotator' => esc_html__( 'Dot Circle Rotator', 'animation-addons-for-elementor-pro' ),
					'bubblingG'          => esc_html__( 'BubblingG', 'animation-addons-for-elementor-pro' ),
					'coffee'          => esc_html__( 'Coffee', 'animation-addons-for-elementor-pro' ),
					'orbit-loading'          => esc_html__( 'Orbit Loading', 'animation-addons-for-elementor-pro' ),
					'battery'          => esc_html__( 'Battery', 'animation-addons-for-elementor-pro' ),
					'equalizer'          => esc_html__( 'Equalizer', 'animation-addons-for-elementor-pro' ),
					'square-swapping'          => esc_html__( 'Square Swapping', 'animation-addons-for-elementor-pro' ),
					'jackhammer'          => esc_html__( 'Jackhammer', 'animation-addons-for-elementor-pro' ),

				],
				'separator'   => 'before',
				'condition'   => [
					'wcf_enable_preloader!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_preloader_background',
			[
				'label' => esc_html__( 'Background Color', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'wcf_preloader_color',
			[
				'label' => esc_html__( 'Primary Color', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->add_control(
			'wcf_preloader_color2',
			[
				'label' => esc_html__( 'Secondary Color', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::COLOR,
			]
		);

		$this->end_controls_section();
	}
}
