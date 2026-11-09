<?php

/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Controls_Manager;


defined('ABSPATH') || die();

class WCF_Pin_Effects
{

	public static function init()
	{
		//ping area controls
		add_action('elementor/element/section/section_advanced/after_section_end', [
			__CLASS__,
			'register_ping_area_controls'
		], 1);

		add_action('elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_ping_area_controls'
		], 1);

		// header sticky
		add_action('elementor/element/section/section_advanced/after_section_end', [
			__CLASS__,
			'register_header_sticky_controls'
		], 1);

		add_action('elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_header_sticky_controls'
		], 1);

		add_action('wp_footer', [__CLASS__, 'render_empty_footer_div']);
		add_action('elementor/frontend/before_render', [__CLASS__, 'fix_default_end_trigger']); // existing demo , customer site
	}

	public static function fix_default_end_trigger($element)
	{

		if ('container' !== $element->get_name()) return;

		$settings = $element->get_settings();

		if (isset($settings['wcf_enable_pin_area']) && $settings['wcf_enable_pin_area'] == 'yes' && isset($settings['wcf_pin_end_trigger']) && $settings['wcf_pin_end_trigger'] !='') {
		
			$element->add_render_attribute(	'_wrapper',	'data-backword-pin-end-trigger',$settings['wcf_pin_end_trigger']);			
		}		
	}

	public static function render_empty_footer_div()
	{
		// echo "<div hidden class='aae_footer_sticky_header_trigger'></div>";
		?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const smootherWrapper = document.getElementById('smooth-wrapper')
                if (smootherWrapper) {
                    const div = document.createElement("div");
                    div.className = "aae_footer_sticky_header_trigger";
                    div.hidden = true;
                    smootherWrapper.appendChild(div);
                }
               
            });
        </script>
<?php
	}

	public static function register_ping_area_controls($element)
	{
	
		$element->start_controls_section(
			'_section_pin-area',
			[
				'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('Sticky/Pin Element', 'animation-addons-for-elementor-pro')),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_responsive_control(
			'wcf_enable_pin_area',
			[
				
				'label'              => esc_html__('Enable', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'no',
				'separator'          => 'before',
				'return_value'       => 'yes',
				'options'            => [
					'no'    => esc_html__('No', 'animation-addons-for-elementor-pro'),
					'yes'  => esc_html__('Yes', 'animation-addons-for-elementor-pro'),

				],
				'render_type'        => 'ui',
				'frontend_available' => true,
			]
		);


		$element->start_controls_tabs(
			'aae_addon_pro_pin_style8_tabs',
			

		);

		$element->start_controls_tab(
			'aae_addon_pro_pin9_content_tab',
			[
				
				'label' => esc_html__('Content', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_enable_pin_area' => ['yes'],
				],
			]
		);
	

		$element->add_responsive_control(
			'wcf_pin_area_trigger',
			[
				'label'       => esc_html__('Pin Trigger', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''       => esc_html__('Default', 'animation-addons-for-elementor-pro'),
					'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				],
				'condition'   => ['wcf_enable_pin_area' => 'yes'],
				'render_type' => 'none',
				'assets' => [
					'scripts' => [
						[
							'name'       => 'adv-sticky-pin-container',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'wcf_enable_pin_area',
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
			'wcf_custom_pin_area',
			[
				'label'              => esc_html__('Custom Pin Area', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('Add the section class where the element will be pin. please use the parent section or container class.', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__('.pin_area', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_pin_area_trigger' => 'custom',
					'wcf_enable_pin_area' => 'yes',
				]
			]
		);

		$element->add_responsive_control(
			'wcf_pin_end_trigger_type', 
			[ 
				'label' => esc_html__('End Trigger', 'animation-addons-for-elementor-pro'),
				 'type' => Controls_Manager::SELECT,
				  'default' => 'default',
				  'separator' => 'before',
				  'condition'          => [
					'wcf_enable_pin_area' => 'yes',					
					],
				  'options' => [
					 'default' => esc_html__('Default', 'animation-addons-for-elementor-pro'), 
					 'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'), 
					], 
					'render_type' => 'ui',
					'frontend_available' => true, 
					
			]);
					  

		$element->add_responsive_control(
			'wcf_pin_end_trigger',
			[
				
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__('.my-end-trigger', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => '',
				'condition'          => [
					'wcf_enable_pin_area'      => 'yes',
					'wcf_pin_end_trigger_type' => 'custom'
				],
				'separator'  => 'after',
				'show_label' => false
			]
		);

		$element->add_responsive_control(
			'wcf_pin_status',
			[
				'label'     => esc_html__('Pin', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'true',
				'options'   => [
					'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
					'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
					'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'wcf_pin_custom',
			[
				//'label'       => esc_html__('Custom Pin', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type' => 'none',
				'placeholder' => esc_html__('.pin_class', 'animation-addons-for-elementor-pro'),
				'condition'   => [
					'wcf_pin_status' => 'custom',
					'wcf_enable_pin_area' => 'yes',
				],
				'show_label' => false
			]
		);

		$element->add_responsive_control(
			'wcf_pin_area_start',
			[
				'label'              => esc_html__('Pin Start', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'default'            => 'top top',
				'frontend_available' => true,
				'options'            => [
					'top top'       => esc_html__('Top Top', 'animation-addons-for-elementor-pro'),
					'top center'    => esc_html__('Top Center', 'animation-addons-for-elementor-pro'),
					'top bottom'    => esc_html__('Top Bottom', 'animation-addons-for-elementor-pro'),
					'center top'    => esc_html__('Center Top', 'animation-addons-for-elementor-pro'),
					'center center' => esc_html__('Center Center', 'animation-addons-for-elementor-pro'),
					'center bottom' => esc_html__('Center Bottom', 'animation-addons-for-elementor-pro'),
					'bottom top'    => esc_html__('Bottom Top', 'animation-addons-for-elementor-pro'),
					'bottom center' => esc_html__('Bottom Center', 'animation-addons-for-elementor-pro'),
					'bottom bottom' => esc_html__('Bottom Bottom', 'animation-addons-for-elementor-pro'),
					'custom'        => esc_html__('custom', 'animation-addons-for-elementor-pro'),
				],
				'render_type'        => 'none',
				'condition'          => ['wcf_enable_pin_area' => 'yes'],

			]
		);

		$element->add_responsive_control(
			'wcf_pin_area_start_custom',
			[
				
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__('top top', 'animation-addons-for-elementor-pro'),
				'placeholder'        => esc_html__('top top+=100', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_enable_pin_area' => 'yes',
					'wcf_pin_area_start'   => 'custom',
				],
				'show_label' => false
			]
		);

		$element->add_responsive_control(
			'wcf_pin_area_end',
			[
				'label'              => esc_html__('Pin End', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'default'            => 'bottom bottom',
				'frontend_available' => true,
				'render_type'        => 'none',
				'options'            => [
					'top top'       => esc_html__('Top Top', 'animation-addons-for-elementor-pro'),
					'top center'    => esc_html__('Top Center', 'animation-addons-for-elementor-pro'),
					'top bottom'    => esc_html__('Top Bottom', 'animation-addons-for-elementor-pro'),
					'center top'    => esc_html__('Center Top', 'animation-addons-for-elementor-pro'),
					'center center' => esc_html__('Center Center', 'animation-addons-for-elementor-pro'),
					'center bottom' => esc_html__('Center Bottom', 'animation-addons-for-elementor-pro'),
					'bottom top'    => esc_html__('Bottom Top', 'animation-addons-for-elementor-pro'),
					'bottom center' => esc_html__('Bottom Center', 'animation-addons-for-elementor-pro'),
					'bottom bottom' => esc_html__('Bottom Bottom', 'animation-addons-for-elementor-pro'),
					'custom'        => esc_html__('custom', 'animation-addons-for-elementor-pro'),
				],
				'condition'          => ['wcf_enable_pin_area' => 'yes'],
			]
		);

		$element->add_responsive_control(
			'wcf_pin_area_end_custom',
			[
				
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => esc_html__('bottom top', 'animation-addons-for-elementor-pro'),
				'placeholder'        => esc_html__('+=50% center', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_enable_pin_area' => 'yes',
					'wcf_pin_area_end'     => 'custom',
				],
				'show_label' => false
			]
		);

		

		$element->add_responsive_control(
			'wcf_pin_spacing',
			[
				'label'     => esc_html__('PinSpacing', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
					'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
					'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area' => 'yes',
				],				
			]
		);

		$element->add_responsive_control(
			'wcf_pin_spacing_custom',
			[
				'label'       => esc_html__('Custom Pin Spacing', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'   => [
					'wcf_pin_spacing' => 'custom',
					'wcf_enable_pin_area' => 'yes',
				],
				'placeholder' => 'margin',
				'description' => esc_html__(
					'Custom spacing behavior for pinned elements:
					• Use "margin" → adds margin instead of padding',
					'animation-addons-for-elementor-pro'
				),
			]
		);
	
		$element->add_control(
			'wwcf_pin_typepin_alert',
			[
				'label'           => esc_html__('Important Note', 'animation-addons-for-elementor-pro'),
				'type'            => Controls_Manager::RAW_HTML,
				'raw'             => esc_html__('Disable ScrollSmoother. You will get settings from elementor page settings', 'animation-addons-for-elementor-pro'),
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition'       => ['wcf_pin_type' => 'fixed'],
				'render_type'     => 'none',
			]
		);		

		$element->add_control(
			'wcf_pin_markers',
			[
				'label'     => esc_html__('Pin Markers', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
					'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'wcf_enable_pin_area' => ['yes'],
				],
			]
		);

		//
		$element->end_controls_tab();

		$element->start_controls_tab(
			'aae_addon_pro_pin9_normal_tab',
			[
				
				'label' => esc_html__('Active Style', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_enable_pin_area' => ['yes'],
				],
			]
		);

		$element->add_control(
			'wcf_pin_active_cls',
			[
				'label' => esc_html__('Toggle Class', 'animation-addons-for-elementor-pro'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
				'placeholder' => esc_html__('sticky-style-active', 'animation-addons-for-elementor-pro'),
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'wcf_pin_actie1_background',
				'types' => ['classic', 'gradient', 'video'],
				'selector' => '{{WRAPPER}}.aae-pro-sticky-active',
			]
		);



		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}

	public static function register_header_sticky_controls($element)
	{
		$element->start_controls_section(
			'aae_sec_header_sticky-area',
			[
				'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('Header Sticky', 'animation-addons-for-elementor-pro')),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_responsive_control(
			'aae_enable_header_sticky_area',
			[
				'label'              => esc_html__('Enable', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'no',
				'separator'          => 'before',
				'options'            => [
					'no'    => esc_html__('No', 'animation-addons-for-elementor-pro'),
					'yes'  => esc_html__('Yes', 'animation-addons-for-elementor-pro'),

				],
				'render_type'        => 'ui',
				'frontend_available' => true,
			]
		);


		$element->start_controls_tabs(
			'aae_pro_sticky_header_tabs',

		);

		$element->start_controls_tab(
			'aae_pro_header_sticky_tab',
			[
				'condition'       => ['wcf_enable_pin_area' => 'yes'],
				'label' => esc_html__('Content', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'aae_enable_header_sticky_area' => ['yes'],
				],
			]
		);	

		$element->add_responsive_control(
			'aae_header_sticky_end_trigger',
			[
				'label'              => esc_html__('End Trigger', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__('.my-end-trigger', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'   => '',
				'condition'          => [
					'aae_enable_header_sticky_area' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'aae_header_sticky_start_position',
			[
				'label'              => esc_html__('Start Position', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => 300,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'   => 300,
				'condition'          => [
					'aae_enable_header_sticky_area' => 'yes',
				],
				'assets' => [
					'scripts' => [
						[
							'name'       => 'adv-sticky-pin-container',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_enable_header_sticky_area',
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
			'aae_header_sticky_z_index',
			[
				'label'              => esc_html__('Z-Index', 'animation-addons-for-elementor-pro'),
				'type' 				=> Controls_Manager::NUMBER,
				'default' 			=> 9999,
				'ai'                 => false,
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_enable_header_sticky_area' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'aae_header_up_scroll_sticky',
			[
				'label'              => esc_html__('Up Scroll Sticky', 'animation-addons-for-elementor-pro'),
				'type' 				=> Controls_Manager::SWITCHER,
				'label_on' 			=> esc_html__('Yes', 'animation-addons-for-elementor-pro'),
				'label_off' 		=> esc_html__('No', 'animation-addons-for-elementor-pro'),
				'return_value' => 'yes',
				'default' => 'yes',
				'ai'                 => false,
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_enable_header_sticky_area' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'aae_header_sticky_ease',
			[
				'label'              => esc_html__('Ease', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'power2.out',
				'render_type'        => 'none', // template
				'options'            => [
					'power2.out' => esc_html__('Power2.out', 'animation-addons-for-elementor-pro'),
					'bounce'     => esc_html__('Bounce', 'animation-addons-for-elementor-pro'),
					'back'       => esc_html__('Back', 'animation-addons-for-elementor-pro'),
					'elastic'    => esc_html__('Elastic', 'animation-addons-for-elementor-pro'),
					'slowmo'     => esc_html__('Slowmo', 'animation-addons-for-elementor-pro'),
					'stepped'    => esc_html__('Stepped', 'animation-addons-for-elementor-pro'),
					'sine'       => esc_html__('Sine', 'animation-addons-for-elementor-pro'),
					'expo'       => esc_html__('Expo', 'animation-addons-for-elementor-pro'),
				],
				'condition'          => [
					'aae_enable_header_sticky_area' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'aae_header_sticky_duration',
			[
				'label'              => esc_html__('Duration', 'animation-addons-for-elementor-pro'),
				'type' 				=> Controls_Manager::NUMBER,
				'min'				=> 0,
				'default' 			=> 0.8,
				'ai'                 => false,
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_enable_header_sticky_area' => 'yes',
				],
			]
		);

		//
		$element->end_controls_tab();

		$element->start_controls_tab(
			'aae_pro_header_sticky_style_tab',
			[
				'condition'       => ['aae_enable_header_sticky_area' => 'yes'],
				'label' => esc_html__('Style', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'aae_enable_header_sticky_area' => ['yes'],
				],
			]
		);

		$element->add_control(
			'aae_pro_header_sticky_style_cls',
			[
				'label' => esc_html__('Style Class', 'animation-addons-for-elementor-pro'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => '',
				'frontend_available' => true,
				'placeholder' => esc_html__('sticky-style', 'animation-addons-for-elementor-pro'),
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name'     => 'aae_pro_header_sticky_background',
				'types'    => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}}.aae-pro-header-sticky-active',
				'condition' => [
					'aae_enable_header_sticky_area' => 'yes',
				],
			]
		);

		$element->add_responsive_control(
			'aae_pro_header_sticky_padding',
			[
				'label'      => esc_html__('Padding', 'animation-addons-for-elementor-pro'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}}.aae-pro-header-sticky-active' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'aae_enable_header_sticky_area' => 'yes',
				],
			]
		);

		$element->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'aae_pro_header_sticky_border',
				'selector' => '{{WRAPPER}}.aae-pro-header-sticky-active',
				'condition' => [
					'aae_enable_header_sticky_area' => 'yes',
				],
			]
		);



		$element->end_controls_tab();

		$element->end_controls_tabs();

		$element->end_controls_section();
	}
}

WCF_Pin_Effects::init();
