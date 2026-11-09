<?php
/**
 * Test Effects extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

class WCF_Cursor_Move_Effects {

	public static function init() {
		add_action( 'elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'register_mouse_move_effect_controls',
		],3 );

		add_action( 'elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_mouse_move_effect_controls'
		],3 );
	}

	public static function register_mouse_move_effect_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_mouse_move_area',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Mouse Move Effect', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_enable_mouse_move_effect',
			[
				'label'              => esc_html__( 'Enable', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_mouse_movee_editor',
			[
				'label'              => esc_html__( 'Enable On Editor', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'For better performance in editor mode, keep the setting turned off.', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf_enable_mouse_move_effect!' => ''
				],
			]
		);

		$element->add_control(
			'wcf_mouse_move_area_trigger',
			[
				'label'              => esc_html__( 'Movement Wrapper', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => '',
				'options'            => [
					''       => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'custom' => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				],
				'condition'          => [ 'wcf_enable_mouse_move_effect!' => '' ],
				'frontend_available' => true,
				'render_type'        => 'none',
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae--mouse-move-effects-widget',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'wcf_enable_mouse_move_effect',
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

		$element->add_control(
			'wcf_custom_mouse_move_area',
			[
				'label'              => esc_html__( 'Custom Area', 'animation-addons-for-elementor-pro' ),
				'description'        => esc_html__( 'Please use the parent section or container class where the element will be movable.', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__( '.movement_area', 'animation-addons-for-elementor-pro' ),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_mouse_move_area_trigger'   => 'custom',
					'wcf_enable_mouse_move_effect!' => '',
				]
			]
		);

		$element->add_control(
			'wcf_mouse_move_x',
			[
				'label'              => esc_html__( 'Move X', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 70,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_mouse_move_effect!' => '' ],
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'wcf_mouse_move_y',
			[
				'label'              => esc_html__( 'Move Y', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 70,
				'frontend_available' => true,
				'condition'          => [ 'wcf_enable_mouse_move_effect!' => '' ],
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'wcf_mouse_move_duration',
			[
				'label'              => esc_html__( 'Duration', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0.5,
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf_enable_mouse_move_effect!' => '',
				],
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'wcf_mouse_move_custom',
			[
				'label'       => esc_html__( 'Customs', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXTAREA,
				'rows'        => 5,
				'placeholder' => esc_html__( 'property:value, property2:value2', 'animation-addons-for-elementor-pro' ),
				'render_type' => 'none', // template
				'frontend_available' => true,
				'condition'   => [
					'wcf_enable_mouse_move_effect!' => '',
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Cursor_Move_Effects::init();
