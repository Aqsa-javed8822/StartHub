<?php

namespace WCFAddonsEX\Extensions;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use WCFAddonsEX\Plugin;

defined( 'ABSPATH' ) || die();

class WCF_PortfolioFilter {
	private static $instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init() {
		add_action( 'wcf_addon_pro_portfolio_filter', [ $this, 'register_controls' ] );
	}

	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	public function register_controls( $element ) {

		$element->start_controls_section(
			'filter_bar',
			[
				'label' => esc_html__( 'Filter Bar', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$element->add_control(
			'show_filter_bar',
			[
				'label'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'  => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
			]
		);
	

		$element->add_control(
			'aae_ajax_filter',
			[
				'label'     => esc_html__( 'Ajax Filter', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_off' => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'  => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
			]
		);

		$element->add_control(
			'taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => 'category',
				'options'     => $this->get_taxonomies(),
				'condition'   => [
					'show_filter_bar' => 'yes',
				],
			]
		);

		$element->end_controls_section();

		$element->start_controls_section(
			'section_design_filter',
			[
				'label'     => esc_html__( 'Filter Bar', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_filter_bar' => 'yes',
				],
			]
		);

		$element->add_control(
			'color_filter',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .filter button'      => 'color: {{VALUE}}',
					'{{WRAPPER}} .filter button span' => 'color: {{VALUE}}',
				],
			]
		);

		$element->add_control(
			'color_filter_active',
			[
				'label'     => esc_html__( 'Active Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .filter .mixitup-control-active,  {{WRAPPER}} .filter button:hover'           => 'color: {{VALUE}};',
					'{{WRAPPER}} .filter .mixitup-control-active span,  {{WRAPPER}} .filter button:hover span' => 'color: {{VALUE}};',
				],
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_filter',
				'selector' => '{{WRAPPER}} .filter button',
			]
		);

		$element->add_responsive_control(
			'filter_item_spacing',
			[
				'label'      => esc_html__( 'Space Between', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 30,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .filter' => 'gap: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$element->add_responsive_control(
			'filter_spacing',
			[
				'label'      => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'default'    => [
					'size' => 50,
				],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .filter' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_PortfolioFilter::instance()->init();
