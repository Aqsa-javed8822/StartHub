<?php
/**
 * Horizontal Scroll extension class.
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

class WCF_Horizontal_Scroll {

	public static function init() {
		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_horizontal_scroll_controls'
		], 1 );
	}

	public static function enqueue_scripts() {

	}
	

	public static function register_horizontal_scroll_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_horizontal_scroll_area',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Horizontal Scroll', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'important_note',
			[
				'label'           => esc_html__( 'Important Note', 'animation-addons-for-elementor-pro' ),
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__( 'Please use full width Container to work properly.', 'animation-addons-for-elementor-pro' ),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			]
		);
	

		$element->add_responsive_control(
			'wcf_enable_horizontal_scroll',
			[
				'label'              => esc_html__( 'Enable', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'no',
				'separator'          => 'before',
				'options'            => [
					'no'    => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
					'yes'  => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
					
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);
		

		$element->add_responsive_control(
			'horizontal_scroll_width',
			[
				'label'              => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'              => [
					'px' => [
						'min' => 100,
						'max' => 50000,
					],
					'%'  => [
						'min' => 10,
						'max' => 1000,
					],
				],
				'default'            => [
					'unit' => '%',
					'size' => 900,
				],
				'description'        => esc_html__( 'Set the total width of the horizontal scroll area in percentage (%).', 'animation-addons-for-elementor-pro' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [ 'wcf_enable_horizontal_scroll' => 'yes' ],
					'assets' => [
					'scripts' => [
						[
							'name'       => 'aae--horizontals--scroll',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'wcf_enable_horizontal_scroll',
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

		$element->add_responsive_control(
			'horizontal_scroll_end',
			[
				'label'              => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'range'              => [
					'px' => [
						'min' => 100,
						'max' => 10000,
					],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [ 'wcf_enable_horizontal_scroll' => 'yes' ]
			]
		);
	
		$element->end_controls_section();
	}
}

WCF_Horizontal_Scroll::init();
