<?php

namespace WCFAddonsPro\Settings\Tabs;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;
use Elementor\Group_Control_Background;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Scroll_Indicator extends Tab_Base {

	public function get_id() {
		return 'settings-wcf-scroll-indicator';
	}

	public function get_title() {
		return esc_html__( 'AAE Scroll Indicator', 'animation-addons-for-elementor-pro' );
	}

	public function get_group() {
		return 'theme-style';
	}

	public function get_icon() {
		return 'wcf eicon-progress-tracker';
	}

	protected function get_specific_post_types() {
		$args = array(
			'public'            => true,
			'show_in_nav_menus' => true,
		);

		$post_types = get_post_types( $args, 'objects' );

		//unset unnecessary post type
		unset( $post_types['page'] );
		unset( $post_types['wcf-addons-template'] );

		$selection_options = [];


		foreach ( $post_types as $post_type ) {
			$selection_options[ $post_type->name ] = esc_html__( $post_type->label, 'animation-addons-for-elementor-pro' );
		}

		return $selection_options;
	}

	protected function get_specific_pages() {

		$pages = get_pages();

		$selection_options = [];

		foreach ( $pages as $page ) {
			$selection_options[ $page->ID ] = esc_html__( $page->post_title, 'animation-addons-for-elementor-pro' );
		}

		return $selection_options;
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
			'wcf_enable_scroll_indicator',
			[
				'type'               => Controls_Manager::SWITCHER,
				'label'              => esc_html__( 'Scroll Indicator', 'animation-addons-for-elementor-pro' ),
				'default'            => '',
				'label_on'           => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'          => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_display',
			[
				'label'       => esc_html__( 'Display', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'entire-website',
				'label_block' => false,
				'options'     => [
					'entire-website'        => esc_html__( 'Entire Website', 'animation-addons-for-elementor-pro' ),
					'specific-pages'        => esc_html__( 'Specific Pages', 'animation-addons-for-elementor-pro' ),
					'specific-s-post-types' => esc_html__( 'Specific Post Types Singular', 'animation-addons-for-elementor-pro' ),
				],
				'separator'   => 'before',
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_s_pages',
			[
				'label'       => esc_html__( 'Specific Pages', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_specific_pages(),
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
					'wcf_scroll_indicator_display' => 'specific-pages',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_s_s_post_types',
			[
				'label'       => esc_html__( 'Specific Post Types Singular', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple'    => true,
				'options'     => $this->get_specific_post_types(),
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
					'wcf_scroll_indicator_display' => 'specific-s-post-types',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_settings_header',
			[
				'label'     => esc_html__( 'Settings', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_position',
			[
				'label'       => esc_html__( 'Position', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'bottom',
				'options'     => [
					'top'    => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
					'bottom' => esc_html__( 'Bottom', 'animation-addons-for-elementor-pro' )
				],
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_z_index',
			[
				'label'   => esc_html__( 'Z-index', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 99999,
				'default' => 99,
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
				],
			]
		);

		$this->add_responsive_control(
			'wcf_scroll_indicator_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_background',
			[
				'label' => esc_html__( 'Background Color', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::COLOR,
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
				],
			]
		);

		$this->add_control(
			'wcf_scroll_indicator_color',
			[
				'label' => esc_html__( 'Indicator Color', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::COLOR,
				'condition'   => [
					'wcf_enable_scroll_indicator!' => '',
				],
			]
		);

		$this->end_controls_section();
	}
}
