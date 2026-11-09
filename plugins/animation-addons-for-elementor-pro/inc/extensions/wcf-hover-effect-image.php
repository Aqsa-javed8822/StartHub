<?php

/**
 * Test Effects extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Controls_Manager;
use Elementor\Utils;

defined('ABSPATH') || die();

class WCF_Hover_Effect_Image
{

	public static function init()
	{
		add_action('elementor/element/container/section_layout/after_section_end', [
			__CLASS__,
			'register_hover_image_controls'
		], 2);

		add_action('elementor/frontend/container/before_render', [
			__CLASS__,
			'enqueue_hover_image_scripts'
		]);
	}

	public static function enqueue_hover_image_scripts($element)
	{
		$settings = $element->get_settings_for_display();
		if (!empty($settings['wcf_enable_hover_image']) && 'yes' === $settings['wcf_enable_hover_image']) {
			wp_enqueue_script('aae--hover-image-container');
		}
	}

	public static function register_hover_image_controls($element)
	{
		$element->start_controls_section(
			'_section_wcf_hover_image_area',
			[
				'label' => sprintf('<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __('Image Reveal on Hover', 'animation-addons-for-elementor-pro')),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'wcf_enable_hover_image',
			[
				'label'              => esc_html__('Enable', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
			]
		);

		$element->add_control(
			'wcf_enable_hover_image_editor',
			[
				'label'              => esc_html__('Enable On Editor', 'animation-addons-for-elementor-pro'),
				'type'               => Controls_Manager::SWITCHER,
				'frontend_available' => true,
				'return_value'       => 'yes',
				'condition' => ['wcf_enable_hover_image!' => '']
			]
		);

		$element->add_control(
			'wcf_hover_image',
			[
				'label' => esc_html__('Choose Image', 'animation-addons-for-elementor-pro'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'background-image: url( {{URL}} );',
				],
				'condition' => ['wcf_enable_hover_image' => 'yes'],
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_width',
			[
				'label'      => esc_html__('Width', 'animation-addons-for-elementor-pro'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['wcf_enable_hover_image' => 'yes'],
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_height',
			[
				'label'      => esc_html__('Height', 'animation-addons-for-elementor-pro'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%', 'em', 'rem'],
				'separator'  => 'after',
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['wcf_enable_hover_image' => 'yes']
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_position_top',
			[
				'label'      => esc_html__('Position Top', 'animation-addons-for-elementor-pro'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'top: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['wcf_enable_hover_image' => 'yes'],
			]
		);

		$element->add_responsive_control(
			'wcf_hover_image_position_left',
			[
				'label'      => esc_html__('Position Left', 'animation-addons-for-elementor-pro'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', '%'],
				'range'      => [
					'px' => [
						'min' => -1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'left: {{SIZE}}{{UNIT}};',
				],
				'condition' => ['wcf_enable_hover_image' => 'yes']
			]
		);

		$element->add_control(
			'wcf_hover_image_zindex',
			[
				'label' => esc_html__('Z-index', 'animation-addons-for-elementor-pro'),
				'type'  => Controls_Manager::NUMBER,
				'min'   => -9999,
				'max'   => 9999,
				'selectors'  => [
					'{{WRAPPER}} .wcf-image-hover' => 'z-index: {{VALUE}};',
				],
				'condition' => ['wcf_enable_hover_image' => 'yes'],
			]
		);

		$element->end_controls_section();
	}
}

WCF_Hover_Effect_Image::init();
