<?php

namespace WCFAddonsPro\Settings\Tabs;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Cursor extends Tab_Base {

	public function get_id() {
		return 'settings-wcf-cursor';
	}

	public function get_title() {
		return esc_html__( 'AAE Cursor', 'animation-addons-for-elementor-pro' );
	}

	public function get_group() {
		return 'theme-style';
	}

	public function get_icon() {
		return 'wcf eicon-scroll';
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
			'wcf_enable_cursor',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Cursor', 'animation-addons-for-elementor-pro' ),
				'default'            => '',
				'return_value'       => 'yes',
				'label_on'           => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'          => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'wcf_cursor_size',
			[
				'label'      => esc_html__( 'Cursor Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 10,
						'max' => 300,
					],
				],
				'condition'  => [ 'wcf_enable_cursor!' => '' ],				
			]
		);

		$this->add_control(
			'wcf_cursor_follower_size',
			[
				'label'      => esc_html__( 'Cursor Follower Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition'  => [ 'wcf_enable_cursor!' => '' ]
			]
		);

		$this->add_control(
			'wcf_cursor_color',
			[
				'label'     => esc_html__( 'Cursor Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,

				'condition' => [ 'wcf_enable_cursor!' => '' ]
			]
		);

		$this->add_control(
			'wcf_cursor_follower_color',
			[
				'label'     => esc_html__( 'Cursor Follower Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [ 'wcf_enable_cursor!' => '' ]
			]
		);

		$this->add_control(
			'wcf_cursor_blend_mode',
			[
				'label'     => esc_html__( 'Blend Mode', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''            => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
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
				'condition' => [ 'wcf_enable_cursor!' => '' ]
			]
		);

		$dropdown_options = [];

		foreach ( Plugin::$instance->breakpoints->get_active_breakpoints() as $breakpoint_key => $breakpoint_instance ) {

			$dropdown_options[ $breakpoint_key ] = sprintf(
			/* translators: 1: Breakpoint label, 2: `>` character, 3: Breakpoint value. */
				esc_html__( '%1$s (%2$dpx)', 'animation-addons-for-elementor-pro' ),
				$breakpoint_instance->get_label(),
				$breakpoint_instance->get_value()
			);
		}

		$this->add_control(
			'wcf_cursor_breakpoint',
			[
				'label'              => esc_html__( 'Breakpoint', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'description'        => esc_html__( 'Note: Choose at which breakpoint cursor will work.', 'animation-addons-for-elementor-pro' ),
				'options'            => $dropdown_options,
				'frontend_available' => true,
				'default'            => 'mobile',
				'condition'          => [
					'wcf_enable_cursor!' => '',
				],
			]
		);

		$this->end_controls_section();
	}
}
