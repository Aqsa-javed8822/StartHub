<?php

/**
 * Animation Effects extension class.
 */

namespace WCFAddonsEX\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Plugin;

defined('ABSPATH') || die();

class WCF_Text_Animation_Effects
{

	private static function get_text_elements()
	{
		return [
			['name' => 'heading', 'section' => 'section_title'],
			['name' => 'e-heading', 'section' => 'section_title'],
			['name' => 'text-editor', 'section' => 'section_editor'],
			['name' => 'wcf--title', 'section' => 'section_content'],
			['name' => 'wcf--text', 'section' => 'section_content'],
			['name' => 'wcf--blog--post--title', 'section' => 'section_content'],
		];
	}

	public static function init()
	{

		foreach (self::get_text_elements() as $element) {
			add_action('elementor/element/' . $element['name'] . '/' . $element['section'] . '/after_section_end', [
				__CLASS__,
				'register_text_animation_controls',
			], 10, 2);
		}
		// fix optimize markup issue
		// add_filter('elementor/widget/render_content', [
		// 	__CLASS__,
		// 	'render_content'
		// ], 10, 2);

	}

	public static function render_content($content, $element)
	{

		if ($element->get_type() === 'widget' && Plugin::$instance->experiments->is_feature_active('e_optimized_markup') === true && in_array($element->get_name(), ['heading', 'text-editor'])) {
			$content = '<div class="elementor-widget-container">' . $content . '</div>';
		}

		return $content;
	}

	public static function register_text_animation_controls($element)
	{
		$element->start_controls_section(
			'_section_wcf_text_animation',
			[
				'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('Text Animation', 'animation-addons-for-elementor-pro')),
			]
		);

		$animation = [
			'none'        => esc_html__('none', 'animation-addons-for-elementor-pro'),
			'char'        => esc_html__('Character', 'animation-addons-for-elementor-pro'),
			'word'        => esc_html__('Word', 'animation-addons-for-elementor-pro'),
			'text_move'   => esc_html__('Text Move', 'animation-addons-for-elementor-pro'),
			'text_reveal' => esc_html__('Text Reveal', 'animation-addons-for-elementor-pro'),
			'text_scale'  => esc_html__('Text Scale', 'animation-addons-for-elementor-pro'),
		];

		if (in_array($element->get_name(), ['heading', 'wcf--title'])) {
			$animation['text_invert'] = esc_html__('Text Invert', 'animation-addons-for-elementor-pro');
			$animation['text_spin']   = esc_html__('3D Spin', 'animation-addons-for-elementor-pro');
		}

		$element->add_responsive_control(
			'wcf_text_animation',
			[
				'label'              => esc_html__('Animation', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'none',
				'separator'          => 'before',
				'options'            => $animation,
				'render_type'        => 'template', // template
				'prefix_class'       => 'wcf-t-animation-',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'aae_asset_deps_view',
			[
				'label' => esc_html__('View', 'animation-addons-for-elementor-pro'),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'default' => 'traditional',
				'condition'          => [
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
				],
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'on_scroll',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'on_page_load',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'play_with_scroll',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'mouseover',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'click',
									],
								],

							],
						]
					],
				],
			]
		);

		$element->add_control(
			'aae_text_panel_alert',
			[
				'type' => \Elementor\Controls_Manager::ALERT,
				'alert_type' => 'warning',
				'heading' => esc_html__('Advance animation alert', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_text_animation!' => ["none"],
					'wcf-animation!' => ["none"],
				],
				'content' => esc_html__('Warning: This widget already has an animation set in the Advanced tab.', 'animation-addons-for-elementor-pro'),
			]
		);
		$element->add_responsive_control(
			'aae_text_trigger',
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
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
				],
				'frontend_available' => true,

			]
		);
		// text control
		$element->add_responsive_control(
			'aae_trigger_text_selector',
			[
				'label'              => esc_html__('Trigger Selector', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('Selector for trigger element. Example: .my-class, #my-id', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'placeholder'        => ".my-class",
				'default'            => '',
				'render_type'        => 'none', // template
				'condition'          => [
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_text_trigger' => ['mouseover', 'click'],
				],
				'frontend_available' => true,
			]
		);

		// on scroll

		$element->add_responsive_control(
			'aae_anim_txt_wrapper',
			[
				'label'       => esc_html__('Text Wrapper', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::SELECT,
				'default'     => '',
				'options'     => [
					''       => esc_html__('Default', 'animation-addons-for-elementor-pro'),
					'custom' => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				],
				'condition'   => [
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);




		$element->add_responsive_control(
			'aae_anim_txt_s_t',
			[
				'label'              => esc_html__('Start Trigger', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('Add the section class where the element will be pin. please use the parent section or container class.', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__('.start_area', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_anim_txt_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_txt_e_t',
			[
				'label'              => esc_html__('End Trigger', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('Add the section class where the element will be pin. please use the parent section or container class.', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'ai'                 => false,
				'placeholder'        => esc_html__('.end_area', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_anim_txt_wrapper' => 'custom'
				],
			]
		);


		$element->add_responsive_control(
			'aae_anim_txt_s',
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
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_anim_txt_wrapper' => 'custom'
				],

			]
		);



		$element->add_responsive_control(
			'aae_anim_txt_s_cus',
			[
				'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'default'            => 'top top',
				'placeholder'        => esc_html__('top top+=100', 'animation-addons-for-elementor-pro'),
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_anim_txt_s'   => 'custom',
					'aae_anim_txt_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_txt_e',
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
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_anim_txt_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_txt_e_cus',
			[
				'label'              => esc_html__('Custom', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => 'bottom top',
				'placeholder'        => esc_html__('bottom top+=100', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_anim_txt_e'     => 'custom',
					'aae_anim_txt_wrapper' => 'custom'
				],
			]
		);

		$element->add_control(
			'aae_anim_txt_markers',
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
					'aae_text_trigger' => ['on_scroll', 'play_with_scroll'],
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
					'aae_anim_txt_wrapper' => 'custom'
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_invert_s',
			[
				'label'              => esc_html__('Start', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'separator'          => 'before',
				'default'            => 'top 85%',
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_text_animation' => ['text_invert'],
				],
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'on_scroll',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'on_page_load',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'play_with_scroll',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'mouseover',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'click',
									],
								],

							],
						]
					],
				],
			]
		);

		$element->add_responsive_control(
			'aae_anim_invert_e',
			[
				'label'              => esc_html__('End', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('First value is element position, Second value is display position', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'separator'          => 'before',
				'default'            => 'bottom center',
				'frontend_available' => true,
				'render_type'        => 'none',
				'condition'          => [
					'wcf_text_animation' => ['text_invert'],
				],
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'on_scroll',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'on_page_load',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'play_with_scroll',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'mouseover',
									],
								],

							],
						],
						[
							'name'       => 'aae--animations--modules',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_text_trigger',
										'operator' => '===',
										'value'    => 'click',
									],
								],

							],
						]
					],
				],
			]
		);

		// end on scroll

		$element->add_responsive_control(
			'text_delay',
			[
				'label'              => esc_html__('Delay', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.1,
				'default'            => 0.15,
				'condition'          => [
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_spin', 'text_scale'],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'text_duration',
			[
				'label'              => esc_html__('Duration', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.1,
				'default'            => 1,
				'condition'          => [
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_scale'],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'text_stagger',
			[
				'label'              => esc_html__('Stagger', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'min'                => 0,
				'max'                => 10,
				'step'               => 0.01,
				'default'            => 0.02,
				'condition'          => [
					'wcf_text_animation' => ['char', 'word', 'text_reveal', 'text_move', 'text_scale'],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);


		$element->add_responsive_control(
			'text_translate_x',
			[
				'label'              => esc_html__('Transform-X', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 20,
				'condition'          => [
					'wcf_text_animation' => ['char', 'word'],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'text_translate_y',
			[
				'label'              => esc_html__('Transform-Y', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 0,
				'condition'          => [
					'wcf_text_animation' => ['char', 'word'],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'text_rotation_di',
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
					'wcf_text_animation' => ['text_move'],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'text_rotation',
			[
				'label'              => esc_html__('Rotation Value', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::NUMBER,
				'default'            => '-80',
				'condition'          => [
					'wcf_text_animation' => ['text_move'],
				],
				'frontend_available' => true,
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'text_transform_origin',
			[
				'label'              => esc_html__('transformOrigin', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => 'top center -50',
				'placeholder'        => esc_html__('top center', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_text_animation' => ['text_move'],
				],
				'render_type'        => 'none',
			]
		);

		//3d spin
		$element->add_control(
			'spin_text_color',
			[
				'label'     => esc_html__('Spin Text Color', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .duplicate-text' => 'color: {{VALUE}} !important',
				],
				'condition' => [
					'wcf_text_animation' => ['text_spin'],
				],
			]
		);

		$element->add_responsive_control(
			'spin_text_start',
			[
				'label'              => esc_html__('Start', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => 'top 50%',
				'placeholder'        => esc_html__('top center', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_text_animation' => ['text_spin'],
					'aae_text_trigger'     => 'on_scroll',
				],
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'spin_text_end',
			[
				'label'              => esc_html__('End', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => 'bottom 30%',
				'placeholder'        => esc_html__('bottom 30%', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_text_animation' => ['text_spin'],
					'aae_text_trigger'     => 'on_scroll',
				],
				'render_type'        => 'none',
			]
		);

		$element->add_control(
			'spin_text_scrub',
			[
				'label'              => esc_html__('Scrub', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf_text_animation!' => ['none', 'text_invert'],
					'aae_text_trigger'     => 'on_scroll',
				],
			]
		);

		$element->add_control(
			'spin_text_toggle_action',
			[
				'label'              => esc_html__('toggleActions', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::TEXT,
				'frontend_available' => true,
				'default'            => 'play none none reverse',
				'placeholder'        => esc_html__('play none none reverse', 'animation-addons-for-elementor-pro'),
				'condition'          => [
					'wcf_text_animation' => ['text_spin'],
					'aae_text_trigger'     => 'on_scroll',
				],
				'render_type'        => 'none',
			]
		);

		$element->add_responsive_control(
			'scale_text_ease',
			[
				'label'              => esc_html__('Ease', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'back',
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
				'condition'          => ['wcf_text_animation' => 'text_scale'],
				'render_type'        => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'text_scale_num',
			[
				'label'     => esc_html__('Scale', 'animation-addons-for-elementor-pro'),
				'type'      => Controls_Manager::NUMBER,
				'frontend_available' => true,
				'render_type'        => 'none',
				'min'       => 0,
				'max'       => 10,
				'default'   => 1.5,
				'condition' => [
					'wcf_text_animation' => 'text_scale',
				],
			]
		);

		$element->add_responsive_control(
			'text_scale_break',
			[
				'label'              => esc_html__('Text Break By', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SELECT,
				'frontend_available' => true,
				'render_type'        => 'none',
				'default'            => 'lines',
				'options'            => [
					'lines' => esc_html__('Lines', 'animation-addons-for-elementor-pro'),
					'words' => esc_html__('Words', 'animation-addons-for-elementor-pro'),
					'chars' => esc_html__('Chars', 'animation-addons-for-elementor-pro'),
				],
				'condition'          => [
					'wcf_text_animation' => 'text_scale',
				],
			]
		);

		$element->add_control(
			'txt_anim_aaehr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$element->add_control(
			'txt_anim_aaehr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$element->add_control(
			'wcf_text_animation_editor',
			[
				'label'              => esc_html__('Enable On Editor', 'animation-addons-for-elementor-pro'),
				'description'        => esc_html__('For better performance in editor mode, keep the setting turned off.', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition'          => [
					'wcf_text_animation!' => 'none',
				],
			]
		);

		$element->add_control(
			'play_image_animation',
			[
				'label'       => esc_html__('Play Animation', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::BUTTON,
				'separator'   => 'before',
				'button_type' => 'success',
				'text'        => esc_html__('Play Now', 'animation-addons-for-elementor-pro'),
				'event'       => 'wcf:editor:play_animation',
				'condition'   => [
					'wcf_text_animation!'       => 'none',
					'wcf_text_animation_editor' => 'yes'
				],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Text_Animation_Effects::init();
