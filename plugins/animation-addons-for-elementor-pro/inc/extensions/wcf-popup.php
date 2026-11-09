<?php
/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Controls_Manager;

defined( 'ABSPATH' ) || die();

/**
 * Animation Effects extension class.
 *
 * @package WCFAddonsEX\Extensions
 */
class WCF_Popup {

	public static function init() {
		// popup controls
		add_action(
			'elementor/element/container/section_layout/after_section_end',
			array(
				__CLASS__,
				'register_popup_controls',
			),
			4
		);
	}

	public static function register_popup_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_popup_area',
			array(
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Popup', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'wcf_enable_popup',
			array(
				'label'              => esc_html__( 'Enable Popup', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			)
		);

		$element->add_control(
			'wcf_enable_popup_editor',
			array(
				'label'              => esc_html__( 'Enable On Editor', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => array( 'wcf_enable_popup!' => '' ),
			)
		);

		$element->add_control(
			'popup_content_type',
			array(
				'label'     => esc_html__( 'Content Type', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => array(
					'content'  => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
					'template' => esc_html__( 'Saved Templates', 'animation-addons-for-elementor-pro' ),
				),
				'default'   => 'content',
				'condition' => array( 'wcf_enable_popup!' => '' ),
				'assets'    => array(
					'scripts' => array(
						array(
							'name'       => 'aae-adv-aae-popup',
							'conditions' => array(
								'terms' => array(
									array(
										'name'     => 'wcf_enable_popup',
										'operator' => '===',
										'value'    => 'yes',
									),
								),
							),
						),
					),
				),
			)
		);

		$element->add_control(
			'popup_elementor_templates',
			array(
				'label'       => esc_html__( 'Save Template', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => false,
				'multiple'    => false,
				'options'     => wcf_addons_get_saved_template_list(),
				'condition'   => array(
					'popup_content_type' => 'template',
					'wcf_enable_popup!'  => '',
				),
			)
		);

		$element->add_control(
			'popup_content',
			array(
				'label'     => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'default'   => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::WYSIWYG,
				'condition' => array(
					'popup_content_type' => 'content',
					'wcf_enable_popup!'  => '',
				),
			)
		);

		$element->add_control(
			'popup_condition',
			array(
				'label'              => esc_html__( 'Open Condition', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SELECT,
				'options'            => array(
					'click'      => esc_html__( 'Click', 'animation-addons-for-elementor-pro' ),
					'pageloaded' => esc_html__( 'Page Loaded', 'animation-addons-for-elementor-pro' ),
				),
				'frontend_available' => true,
				'default'            => 'click',
				'condition'          => array( 'wcf_enable_popup!' => '' ),
			)
		);

		$element->add_control(
			'wcf_enable_login_user',
			array(
				'label'              => esc_html__( 'Enable On Login User', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'default'            => '',
				'condition'          => array( 'popup_condition' => 'pageloaded' ),
			)
		);

		$element->add_control(
			'wcf_load_after_xtime',
			array(
				'label'              => esc_html__( 'Show After X time(milisecond)', 'animation-addons-for-elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'min'                => -1,
				'frontend_available' => true,
				'max'                => 80000,
				'step'               => 1000,
				'default'            => 2000,
				'condition'          => array( 'popup_condition' => 'pageloaded' ),
			)
		);

		$element->add_control(
			'wcf_show_up_to_xtime',
			array(
				'label'              => esc_html__( 'Show UpTo X time', 'animation-addons-for-elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'min'                => 1,
				'frontend_available' => true,
				'max'                => 50,
				'step'               => 1,
				'default'            => 5,
				'condition'          => array( 'popup_condition' => 'pageloaded' ),
			)
		);

		$element->add_control(
			'wcf_load_after_x_pageviews',
			array(
				'label'              => esc_html__( 'Show After X Page Views', 'animation-addons-for-elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::NUMBER,
				'min'                => 0,
				'frontend_available' => true,
				'max'                => 50,
				'step'               => 1,
				'default'            => 0,
				'condition'          => array( 'popup_condition' => 'pageloaded' ),
			)
		);

		$element->add_control(
			'wcf_show_x_devices',
			array(
				'label'              => esc_html__( 'Show in X Devices', 'animation-addons-for-elementor-pro' ),
				'type'               => \Elementor\Controls_Manager::SELECT2,
				'label_block'        => true,
				'frontend_available' => true,
				'multiple'           => true,
				'options'            => array(
					'mobile'  => esc_html__( 'Mobile', 'animation-addons-for-elementor-pro' ),
					'teblet'  => esc_html__( 'Teblet', 'animation-addons-for-elementor-pro' ),
					'desktop' => esc_html__( 'Desktop', 'animation-addons-for-elementor-pro' ),
				),
				'default'            => array(),
				'condition'          => array( 'popup_condition' => 'pageloaded' ),
			)
		);

		$element->add_control(
			'popup_trigger_cursor',
			array(
				'label'     => esc_html__( 'Cursor', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'default',
				'options'   => array(
					'default'  => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'none'     => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'pointer'  => esc_html__( 'Pointer', 'animation-addons-for-elementor-pro' ),
					'grabbing' => esc_html__( 'Grabbing', 'animation-addons-for-elementor-pro' ),
					'move'     => esc_html__( 'Move', 'animation-addons-for-elementor-pro' ),
					'text'     => esc_html__( 'Text', 'animation-addons-for-elementor-pro' ),
				),
				'selectors' => array(
					'{{WRAPPER}}' => 'cursor: {{VALUE}};',
				),
				'condition' => array( 'wcf_enable_popup!' => '' ),
			)
		);

		$element->end_controls_section();
	}
}

WCF_Popup::init();
