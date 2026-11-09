<?php

/**
 * Wrapper link extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;

defined('ABSPATH') || die();

class WCF_Scroll_To
{

	public static function init()
	{	
	
		add_action('elementor/element/common/_section_style/after_section_end', [
			__CLASS__,
			'register_wrapper_controls'
		], 20, 2);
		add_action('elementor/frontend/before_render', [__CLASS__, 'before_render'], 10);
		add_action('elementor/frontend/after_render', [__CLASS__, 'after_render'], 1);
	}

	public static function enqueue_scripts() {}

	public static function register_wrapper_controls($element, $target)
	{
		
		$element->start_controls_section(
			'_section_wcf_scrollto_area',
			[
				'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('ScrollTo', 'animation-addons-for-elementor-pro')),
				'tab'   => Controls_Manager::TAB_ADVANCED				
			]
		);

		$element->add_control(
			'aae_scroll_to',
			[
				'label' => __('Enable', 'animation-addons-for-elementor-pro'),
				'type'  => Controls_Manager::SWITCHER				
			]
		);

		$element->add_control(
			'aae_enable_scroll_to_link',
			[
				'label'       => esc_html__('Link', 'animation-addons-for-elementor-pro'),
				'type'        => Controls_Manager::URL,
				'options' => ['url', 'is_external', 'nofollow'],
				'condition' => [
					'aae_scroll_to!' => ''
				],
				'assets' => [
					'scripts' => [
						[
							'name'       => 'aae-scroll-to-ele',
							'conditions' => [
								'terms' => [
									[
										'name'     => 'aae_scroll_to',
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
			'aae_scroll_duration',
			[
				'label' => __('Scroll Duration (seconds)', 'animation-addons-for-elementor-pro'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 1,
				'step' => 0.1,
				'render_type'        => 'none', // template				
				'frontend_available' => true,
				'condition' => [
					'aae_scroll_to!' => ''
				]
			]
		);

		$element->add_control(
			'aae_scroll_ease',
			[
				'label' => __('Scroll Ease', 'animation-addons-for-elementor-pro'),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'power2.out',
				'options' => [
					'power1.out' => 'power1.out',
					'power2.out' => 'power2.out',
					'power3.out' => 'power3.out',
					'expo.out'   => 'expo.out',
					'bounce.out' => 'bounce.out',
				],
				'render_type'        => 'none', // template				
				'frontend_available' => true,
				'condition' => [
					'aae_scroll_to!' => ''
				]
			]
		);

		$element->end_controls_section();
	}

	public static function before_render($container)
	{
		$settings = $container->get_settings();
	
		if ( isset($settings['aae_scroll_to']) && $settings['aae_scroll_to'] === 'yes' &&  isset($settings['aae_enable_scroll_to_link']['url'])) {
			$link     = $settings['aae_enable_scroll_to_link'];
			$duration     = $settings['aae_scroll_duration'];
			$ease     = $settings['aae_scroll_ease'];
			$url      = esc_url($link['url']);
			$target   = ! empty($link['is_external']) ? ' target="_blank"' : '';
			$nofollow = ! empty($link['nofollow']) ? ' rel="nofollow"' : '';

			$ease = ! empty($ease) ? sprintf(' data-ease="%s"', $ease) : 'power2.out';
			$duration = ! empty($duration) ? sprintf(' data-duration="%s"', $duration) : 1;

			echo '<div class="aae-scroll-to" href="' . $url . '"' . $target . $nofollow . $ease . $duration . '>';
		}
	}

	public static function after_render($container)
	{
		$settings = $container->get_settings();
		if ( isset($settings['aae_scroll_to']) && $settings['aae_scroll_to'] === 'yes' &&  isset($settings['aae_enable_scroll_to_link']['url'])) {
			echo '</div>';
		}
	}
}

WCF_Scroll_To::init();
