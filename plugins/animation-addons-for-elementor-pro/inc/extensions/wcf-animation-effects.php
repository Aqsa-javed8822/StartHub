<?php

/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;
use Elementor\Repeater;

defined('ABSPATH') || die();

class WCF_Animation_Effects
{

	public static function init()
	{

		//animation controls
		add_action('elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'register_animation_controls',
		], 1);

		add_action('elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_animation_controls'
		], 1);

		add_action('elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'register_parallax',
		], 2);

		add_action('elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_parallax'
		], 2);

		add_action('elementor/frontend/widget/before_render', [__CLASS__, 'wcf_attributes']);
		add_action('elementor/frontend/container/before_render', [__CLASS__, 'wcf_attributes']);

		add_action('elementor/preview/enqueue_scripts', [__CLASS__, 'enqueue_scripts']);

		add_action('elementor/frontend/before_render', [__CLASS__, 'remove_transition_from_container']);
	}	

	public static function remove_transition_from_container($element)
	{

			if ('container' !== $element->get_name()) return;

			$settings = $element->get_settings_for_display();

			if (isset($settings['wcf-animation']) && $settings['wcf-animation'] !== 'none') {
				$element->add_render_attribute('_wrapper', 'class', 'aae-disable-transition');
			}
		
	}

	public static function enqueue_scripts() {}

	/**
	 * Set attributes based extension settings
	 *
	 * @param Element_Base $section
	 *
	 * @return void
	 */
	public static function wcf_attributes($element)
	{
		if (! empty($element->get_settings('wcf_enable_scroll_smoother'))) {
			$attributes = [];

			if (! empty($element->get_settings('data-speed'))) {
				$attributes['data-speed'] = $element->get_settings('data-speed');
			}
			if (! empty($element->get_settings('data-lag'))) {
				$attributes['data-lag'] = $element->get_settings('data-lag');
			}

			$element->add_render_attribute('_wrapper', $attributes);
		}
	}

	public static function register_parallax($element)
	{

		$element->start_controls_section(
			'_section_wcf_parallax',
			[
				'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('Parallax Effect', 'animation-addons-for-elementor-pro')),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);
		//smooth scroll animation
		$element->add_control(
			'wcf_enable_scroll_smoother',
			[
				'label'        => esc_html__('Enable Scroll Smoother', 'animation-addons-for-elementor-pro'),
				'description'  => esc_html__('If you want to use scroll smooth, please enable global settings first', 'animation-addons-for-elementor-pro'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Yes', 'animation-addons-for-elementor-pro'),
				'label_off'    => esc_html__('No', 'animation-addons-for-elementor-pro'),
				'return_value' => 'yes',
				'render_type'  => 'none',                                                                                                                      // template
				'separator'    => 'before',
			]
		);

		$element->add_control(
			'data-speed',
			[
				'label'     => esc_html__('Speed', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0.9,
				'render_type'        => 'none', // template
				'condition' => ['wcf_enable_scroll_smoother' => 'yes'],
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae-scroll-to-ele',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'wcf_enable_scroll_smoother',
										'operator' => '==',
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
			'data-lag',
			[
				'label'     => esc_html__('Lag', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 0,
				'render_type'        => 'none', // template
				'condition' => ['wcf_enable_scroll_smoother' => 'yes'],
			]
		);
		// enable if container background image is set	
		
		$element->end_controls_section();
	}
	public static function register_animation_controls($element)
	{
		
		$element->start_controls_section(
			'_section_wcf_animation',
			[
				'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('Animation', 'animation-addons-for-elementor-pro')),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_responsive_control(
			'wcf-animation',
			[
				'label'              => esc_html__('Animation', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => [
					'none'   => esc_html__('None', 'animation-addons-for-elementor-pro'),
					'fade'   => esc_html__('Fade animation', 'animation-addons-for-elementor-pro'),
					'move'   => esc_html__('3D Move', 'animation-addons-for-elementor-pro'),
					'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				],
				'render_type'        => 'template', // template
				'frontend_available' => true,
			]
		);	

		$element->add_responsive_control(
			'aae_method',
			[
				'label'              => esc_html__('Method', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'from',
				'render_type'        => 'none', // template
				'options'            => [
					'from' => esc_html__('From', 'animation-addons-for-elementor-pro'),
					'to'     => esc_html__('To', 'animation-addons-for-elementor-pro'),
				],
				'condition'          => [
					'wcf-animation' => ['custom', 'fade', "move"],

				],
				'frontend_available' => true,		
				
				
			]
		);

	

		$element->add_responsive_control(
			'aae_trigger',
			[
				'label'              => esc_html__('Trigger', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'on_scroll',
				'render_type'        => 'none', // template
				'options'            => [
					'on_scroll'        => esc_html__('On Scroll', 'animation-addons-for-elementor-pro'),
					'on_page_load'     => esc_html__('On Page Load', 'animation-addons-for-elementor-pro'),
					'play_with_scroll' => esc_html__('Play With Scroll', 'animation-addons-for-elementor-pro'),
					'mouseover'        => esc_html__('On Hover', 'animation-addons-for-elementor-pro'),
					'click'            => esc_html__('On Click', 'animation-addons-for-elementor-pro'),
				],
				'condition'          => [
					'wcf-animation' => ['custom', 'fade', 'move'],
				],
				'frontend_available' => true,				
				
			]
		);
		// text control
		$element->add_responsive_control(
			'aae_trigger_selector',
			[
				'label'              => esc_html__('Trigger Selector', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('Selector for trigger element. Example: .my-class, #my-id', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'placeholder'        => ".my-class",
				'default'            => '',
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_trigger' => ['mouseover', 'click'],
				],
				'frontend_available' => true,
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_method',
										'operator' => '===',
										'value'    => 'from',
									],
								],
							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_method',
										'operator' => '===',
										'value'    => 'to',
									],
								],
							],
						]					
					],
				],
			]
		);

		// on scroll

		$element->add_responsive_control(
			'aae_anim_wrapper',
			[
				'label'       => esc_html__('Wrapper', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''       => esc_html__('Default', 'animation-addons-for-elementor-pro'),
					'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				],
				'condition'   => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);




		$element->add_responsive_control(
			'aae_anim_s_t',
			[
				'label'              => esc_html__('Start Trigger', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('Add the section class where the element will be pin. please use the parent section or container class.', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__('.start_area', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_anim_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_e_t',
			[
				'label'              => esc_html__('End Trigger', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('Add the section class where the element will be pin. please use the parent section or container class.', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__('.end_area', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_anim_wrapper' => 'custom'
				],
			]
		);


		$element->add_responsive_control(
			'aae_anim_s',
			[
				'label'              => esc_html__('Start', 'animation-addons-for-elementor-pro'),
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
				'condition'          => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_anim_wrapper' => 'custom'
				],

			]
		);



		$element->add_responsive_control(
			'aae_anim_s_cus',
			[
				'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'default'            => esc_html__('top top', 'animation-addons-for-elementor-pro'),
				'placeholder'        => esc_html__('top top+=100', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_anim_s'   => 'custom',
					'aae_anim_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_e',
			[
				'label'              => esc_html__('End', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'separator'          => 'before',
				'default'            => 'bottom top',
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
				'condition'          => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_anim_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_e_cus',
			[
				'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => esc_html__('bottom top', 'animation-addons-for-elementor-pro'),
				'placeholder'        => esc_html__('bottom top+=100', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_anim_e'     => 'custom',
					'aae_anim_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_markers',
			[
				'label'     => esc_html__('Markers', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'   => esc_html__('True', 'animation-addons-for-elementor-pro'),
					'false'  => esc_html__('False', 'animation-addons-for-elementor-pro'),
				],
				'frontend_available' => true,
				'render_type' => 'none',
				'condition'          => [
					'aae_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf-animation' => ['custom', 'fade', 'move'],
					'aae_anim_wrapper' => 'custom'
				],
			]
		);

		// end on scroll

		$element->add_responsive_control(
			'delay',
			[
				'label'              => esc_html__('Delay', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.1,
				'default'            => .15,
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf-animation!' => ['custom', 'none'],
				],
				'frontend_available' => true,
			]
		);		

		$element->add_responsive_control(
			'fade-from',
			[
				'label'              => esc_html__('Fade from', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'bottom',
				'render_type'        => 'none', // template
				'options'            => [
					'top'    => esc_html__('Top', 'animation-addons-for-elementor-pro'),
					'bottom' => esc_html__('Bottom', 'animation-addons-for-elementor-pro'),
					'left'   => esc_html__('Left', 'animation-addons-for-elementor-pro'),
					'right'  => esc_html__('Right', 'animation-addons-for-elementor-pro'),
					'in'     => esc_html__('In', 'animation-addons-for-elementor-pro'),
					'scale'  => esc_html__('Zoom', 'animation-addons-for-elementor-pro'),
				],
				'frontend_available' => true,
				'condition'          => [
					'wcf-animation' => 'fade',
				],
			]
		);

		$element->add_responsive_control(
			'data-duration',
			[
				'label'              => esc_html__('Duration', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 1.5,
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf-animation!' => ['custom', 'none']

				],
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'ease',
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
					'none'       => esc_html__('None', 'animation-addons-for-elementor-pro'),
				],
				'condition'          => [
					'wcf-animation!' => 'none',
				],
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'fade-offset',
			[
				'label'              => esc_html__('Fade offset', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 50,
				'render_type'        => 'none', // template
				'condition'          => [
					'fade-from!' => ['in', 'scale'],
					'wcf-animation' => 'fade',
				],
				'frontend_available' => true,
			]
		);

		//scale
		$element->add_responsive_control(
			'wcf-a-scale',
			[
				'label'              => esc_html__('Start Scale', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0.7,
				'condition'          => [
					'fade-from' => 'scale',
					'wcf-animation' => 'fade',
				],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		//move
		$element->add_responsive_control(
			'wcf_a_rotation_di',
			[
				'label'              => esc_html__('Rotation Direction', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'x',
				'separator'          => 'before',
				'options'            => [
					'x' => esc_html__('X', 'animation-addons-for-elementor-pro'),
					'y' => esc_html__('Y', 'animation-addons-for-elementor-pro'),
				],
				'condition'          => [
					'wcf-animation' => 'move',
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'wcf_a_rotation',
			[
				'label'              => esc_html__('Rotation Value', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'default'            => '-80',
				'condition'          => [
					'wcf-animation' => 'move',
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'wcf_a_transform_origin',
			[
				'label'              => esc_html__('TransformOrigin', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => esc_html__('top center -50', 'animation-addons-for-elementor-pro'),
				'placeholder'        => esc_html__('top center', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf-animation' => 'move',
				],
				'render_type'        => 'none',
			]
		);

		// custom 
		$repeater = new Repeater();

		$repeater->add_control(
			'property',
			[
				'label'   => __('Property', 'animation-addons-for-elementor-pro'),
				'type'    => Controls_Manager::SELECT2,
				'multiple' => false,
				'options' => [
					'none'   => __('None', 'animation-addons-for-elementor-pro'),
					'opacity'   => __('Opacity', 'animation-addons-for-elementor-pro'),
					'x'  => __('X', 'animation-addons-for-elementor-pro'),
					'y'  => __('Y', 'animation-addons-for-elementor-pro'),
					'width'   => __('Width', 'animation-addons-for-elementor-pro'),
					'height' => __('Height', 'animation-addons-for-elementor-pro'),
					'scale' => __('Scale', 'animation-addons-for-elementor-pro'),
					'repeat' => __('Repeat', 'animation-addons-for-elementor-pro'),
					'rotate' => __('Rotate', 'animation-addons-for-elementor-pro'),
					'rotateX' => __('RotateX', 'animation-addons-for-elementor-pro'),
					'rotateY' => __('RotateY', 'animation-addons-for-elementor-pro'),
					'transformOrigin' => __('TransformOrigin', 'animation-addons-for-elementor-pro'),
					'color' => __('Color', 'animation-addons-for-elementor-pro'),
					'background' => __('Background', 'animation-addons-for-elementor-pro'),
					'border' => __('Border', 'animation-addons-for-elementor-pro'),
					'boxShadow' => __('BoxShadow', 'animation-addons-for-elementor-pro'),
					'force3D' => __('Force3D', 'animation-addons-for-elementor-pro'),
					'delay' => __('Delay', 'animation-addons-for-elementor-pro'),
					'duration' => __('Duration', 'animation-addons-for-elementor-pro'),
					'maxWidth' => __('Max Width', 'animation-addons-for-elementor-pro'),
					'maxHeight' => __('Max Height', 'animation-addons-for-elementor-pro'),
					'minWidth' => __('Min Width', 'animation-addons-for-elementor-pro'),
					'minHeight' => __('Min Height', 'animation-addons-for-elementor-pro'),
					'mixBlendMode' => __('Mix Blend Mode', 'animation-addons-for-elementor-pro'),
					'padding' => __('Padding', 'animation-addons-for-elementor-pro'),
					'borderRadius' => __('Border Radius', 'animation-addons-for-elementor-pro'),
					'repeatDelay' => __('Repeat Delay', 'animation-addons-for-elementor-pro'),
					'scaleX' => __('ScaleX', 'animation-addons-for-elementor-pro'),
					'scaleY' => __('ScaleY', 'animation-addons-for-elementor-pro'),
					'xPercent' => __('XPercent', 'animation-addons-for-elementor-pro'),
					'yPercent' => __('YPercent', 'animation-addons-for-elementor-pro'),
					'autoAlpha' => __('Auto Alpha', 'animation-addons-for-elementor-pro'),
					'yoyo' => __('YoYo', 'animation-addons-for-elementor-pro'),
				],
				'default' => 'Porperty',
				'render_type'        => 'ui',
				'frontend_available' => true,
			]
		);

		$repeater->add_responsive_control(
			'value',
			[
				'label'   => __('Value', 'animation-addons-for-elementor-pro'),
				'type'    => Controls_Manager::TEXT,
				'default' => '',
				'render_type'        => 'ui',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'aae_ani_custom_props',
			[
				'label'        => __('Custom Properties', 'animation-addons-for-elementor-pro'),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $repeater->get_controls(),
				'condition' => ['wcf-animation' => 'custom'],
				'label_block' => true,
				'title_field'  => '{{{ property }}}',
				'separator'    => 'before',
				'render_type'        => 'ui',
				'frontend_available' => true,
			]
		);

	
		$element->add_control(
			'wcf_ed_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$element->add_control(
			'wcf_ed_hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);


		$element->add_control(
			'wcf_enable_animation_editor',
			[
				'label'              => esc_html__('Enable On Editor', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('For better performance in editor mode, keep the setting turned off.', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf-animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'play_animation_content',
			[
				'label' => esc_html__('Play Animation', 'animation-addons-for-elementor-pro'),
				'type' => \Elementor\Controls_Manager::BUTTON,
				'separator' => 'before',
				'button_type' => 'success',
				'text' => esc_html__('Play', 'animation-addons-for-elementor-pro'),
				'event' => 'wcf:editor:play_animation',
				'condition'          => [
					'wcf-animation!' => 'none',
					'wcf_enable_animation_editor' => 'yes'
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Animation_Effects::init();
