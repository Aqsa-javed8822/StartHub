<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Skin_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

abstract class Skin_Portfolio_Base extends Skin_Base {

	/**
	 * Register skin controls actions.
	 *
	 * Run on init and used to register new skins to be injected to the widget.
	 * This method is used to register new actions that specify the location of
	 * the skin in the widget.
	 *
	 * Example usage:
	 * `add_action( 'elementor/element/{widget_id}/{section_id}/before_section_end', [ $this, 'register_controls' ] );`
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function _register_controls_actions() {
		add_action( 'elementor/element/wcf--a-portfolio/section_layout/after_section_end', [ $this, 'register_controls' ] ,20);
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	public function register_controls( Widget_Base $widget ) {
		$this->parent = $widget;
	}

	// Date Controls
	protected function register_date_controls() {
		
		$this->start_controls_section(
			'section_date_style',
			[
				'label' => esc_html__( 'Date', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .date' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typo',
				'selector' => '{{WRAPPER}} .date',
			]
		);

		$this->end_controls_section();
    }

	// Category Controls
	protected function register_meta_controls() {

		$this->start_controls_section(
			'section_meta',
			[
				'label' => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'post_taxonomy',
			[
				'label'       => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => 'category',
				'options'     => $this->get_taxonomies(),
			]
		);
		
		$this->end_controls_section();

	    // Style
		$this->start_controls_section(
			'section_meta_style',
			[
				'label' => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'meta_color',
			[
				'label' => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .category' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'meta_typo',
				'selector' => '{{WRAPPER}} .category',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Register the slider controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_slider_controls( $default_value = [] ) {

		$default = [
			'slides_to_show'       => 'auto',
			'autoplay'             => 'yes',
			'autoplay_delay'       => 3000,
			'autoplay_interaction' => 'true',
			'allow_touch_move'     => 'false',
			'loop'                 => 'true',
			'mousewheel'           => '',
			'speed'                => 500,
			'space_between'        => 20,
			'effect'               => 'slide',
			//navigation
			'navigation'           => 'yes',
			//pagination
			'pagination'           => 'yes',
			'pagination_type'      => 'bullets',
			'direction'            => 'ltr',
		];

		$default = array_merge(  $default, $default_value );

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'       => esc_html__( 'Slides to Show', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => $default['slides_to_show'],
				'required'    => true,
				'options'     => [
					                 'auto' => esc_html__( 'Auto', 'animation-addons-for-elementor-pro' ),
				                 ] + $slides_to_show,
				'render_type' => 'template',
				'selectors'   => [
					'{{WRAPPER}} .wcf__slider' => '--slides-to-show: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'   => esc_html__( 'Autoplay', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $default['autoplay'],
				'options' => [
					'yes' => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
					'no'  => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'autoplay_delay',
			[
				'label'     => esc_html__( 'Autoplay delay', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => $default['autoplay_delay'],
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'autoplay_interaction',
			[
				'label'     => esc_html__( 'Autoplay Interaction', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $default['autoplay_interaction'],
				'options'   => [
					'true'  => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
					'false' => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
			]
		);

		$this->add_control(
			'allow_touch_move',
			[
				'label'     => esc_html__( 'Allow Touch Move', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => $default['allow_touch_move'],
				'options'   => [
					'true'  => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
					'false' => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		// Loop requires a re-render so no 'render_type = none'
		$this->add_control(
			'loop',
			[
				'label'   => esc_html__( 'Loop', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $default['loop'],
				'options' => [
					'true'  => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
					'false' => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'mousewheel',
			[
				'label'       => esc_html__( 'Mousewheel', 'animation-addons-for-elementor-pro' ),
				'description' => esc_html__( 'If you want to use mousewheel, please disable loop.', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_on'    => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'   => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'     => $default['mousewheel'],
			]
		);

		$this->add_control(
			'speed',
			[
				'label'   => esc_html__( 'Animation Speed', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => $default['speed'],
			]
		);

		$this->add_control(
			'space_between',
			[
				'label'       => esc_html__( 'Space Between', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => $default['space_between'],
				'render_type' => 'template',
				'selectors'   => [
					'{{WRAPPER}} .wcf__slider' => '--space-between: {{VALUE}}px;',
				],
			]
		);

		$this->add_control(
			'effect',
			[
				'label'   => esc_html__( 'Effect', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $default['effect'],
				'options' => [
					'slide'  => esc_html__( 'Slide', 'animation-addons-for-elementor-pro' ),
					'fade'  => esc_html__( 'Fade', 'animation-addons-for-elementor-pro' ),
					'coverflow' => esc_html__( 'Coverflow', 'animation-addons-for-elementor-pro' ),
					'flip' => esc_html__( 'Flip', 'animation-addons-for-elementor-pro' ),
					'cube' => esc_html__( 'Cube', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		//slider navigation
		$this->add_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => $default['navigation'],
			]
		);

		$this->add_control(
			'navigation_previous_icon',
			[
				'label'            => esc_html__( 'Previous Arrow Icon', 'animation-addons-for-elementor-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon'  => 'eicon-chevron-left',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'      => [
					'fa-regular' => [
						'arrow-alt-circle-left',
						'caret-square-left',
					],
					'fa-solid'   => [
						'angle-double-left',
						'angle-left',
						'arrow-alt-circle-left',
						'arrow-circle-left',
						'arrow-left',
						'caret-left',
						'caret-square-left',
						'chevron-circle-left',
						'chevron-left',
						'long-arrow-alt-left',
					],
				],
				'condition'        => [
					$this->get_control_id( 'navigation' ) => 'yes'
				],
			]
		);

		$this->add_control(
			'navigation_next_icon',
			[
				'label'            => esc_html__( 'Next Arrow Icon', 'animation-addons-for-elementor-pro' ),
				'type'             => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin'             => 'inline',
				'label_block'      => false,
				'skin_settings'    => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon'  => 'eicon-chevron-right',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended'      => [
					'fa-regular' => [
						'arrow-alt-circle-right',
						'caret-square-right',
					],
					'fa-solid'   => [
						'angle-double-right',
						'angle-right',
						'arrow-alt-circle-right',
						'arrow-circle-right',
						'arrow-right',
						'caret-right',
						'caret-square-right',
						'chevron-circle-right',
						'chevron-right',
						'long-arrow-alt-right',
					],
				],
				'condition'        => [
					$this->get_control_id( 'navigation' ) => 'yes'
				],
			]
		);

		//slider pagination
		$this->add_control(
			'pagination',
			[
				'label'     => esc_html__( 'Pagination', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'separator' => 'before',
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => $default['navigation'],
			]
		);

		$this->add_control(
			'pagination_type',
			[
				'label'     => esc_html__( 'Pagination Type', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => $default['pagination_type'],
				'options'   => [
					'bullets'     => esc_html__( 'Bullets', 'animation-addons-for-elementor-pro' ),
					'fraction'    => esc_html__( 'Fraction', 'animation-addons-for-elementor-pro' ),
					'progressbar' => esc_html__( 'Progressbar', 'animation-addons-for-elementor-pro' ),
				],
				'condition'        => [
					$this->get_control_id( 'pagination' ) => 'yes'
				],
			]
		);

		$this->add_control(
			'direction',
			[
				'label'     => esc_html__( 'Direction', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'separator' => 'before',
				'default'   => $default['direction'],
				'options'   => [
					'ltr' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
					'rtl' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
				],
			]
		);
	}

	/**
	 * Register the slider pagination style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function register_slider_pagination_style_controls( $default_selectors = [] ) {

		$selectors = [
			'bullets_size'           => [
				'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
			'bullets_inactive_color' => [
				'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}}; opacity: 1',
			],
			'bullets_color'          => [
				'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
			],
			'bullets_border'         => '{{WRAPPER}} .swiper-pagination-bullet',
			//fraction
			'fraction_current_color' => [
				'{{WRAPPER}} .swiper-pagination-current' => 'color: {{VALUE}}',
			],
			'fraction_total_color'   => [
				'{{WRAPPER}} .swiper-pagination-total' => 'color: {{VALUE}}',
			],
			'fraction_midline_color' => [
				'{{WRAPPER}} .mid-line' => 'background-color: {{VALUE}}',
			],
			//progressbar
			'progress_color'         => [ '{{WRAPPER}} .swiper-pagination-progressbar' => 'background-color: {{VALUE}}' ],
			'progress_fill_color'    => [ '{{WRAPPER}} .swiper-pagination-progressbar-fill' => 'background-color: {{VALUE}}' ],
		];

		$selectors = array_merge( $selectors, $default_selectors );

		//pagination bullets
		$this->add_control(
			'bullets_size',
			[
				'label'     => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 2,
						'max' => 100,
					],
				],
				'selectors' => $selectors['bullets_size'],
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'bullets'
				]
			]
		);

		$this->add_control(
			'bullets_inactive_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['bullets_inactive_color'],
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'bullets'
				]
			]
		);

		$this->add_control(
			'bullets_color',
			[
				'label'     => esc_html__( 'Active Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['bullets_color'],
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'bullets'
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'bullets_border',
				'selector'  => $selectors['bullets_border'],
				'separator' => 'before',
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'bullets'
				]
			]
		);

		//pagination fraction
		$this->add_control(
			'fraction_current_color',
			[
				'label'     => esc_html__( 'Current Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['fraction_current_color'],
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'fraction'
				]
			]
		);

		$this->add_control(
			'fraction_total_color',
			[
				'label'     => esc_html__( 'Total Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['fraction_total_color'],
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'fraction'
				]
			]
		);

		$this->add_control(
			'fraction_midline_color',
			[
				'label'     => esc_html__( 'Mid Line Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['fraction_midline_color'],
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'fraction'
				]
			]
		);

		//pagination progressbar
		$this->add_control(
			'progress_color',
			[
				'label'     => esc_html__( 'Progressbar Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['progress_color'],
				'condition'        => [
					$this->get_control_id( 'pagination_type' ) => 'progressbar'
				]
			]
		);

		$this->add_control(
			'progress_fill_color',
			[
				'label'     => esc_html__( 'Progressbar Fill Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['progress_fill_color'],
				'condition' => [
					$this->get_control_id( 'pagination_type' ) => 'progressbar'
				]
			]
		);
	}

	/**
	 * Register the slider navigation style controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 */
	protected function register_slider_navigation_style_controls( $default_selectors = [] ) {

		$selectors = [
			'arrow_size'              => [ '{{WRAPPER}} .wcf-arrow' => 'font-size: {{SIZE}}{{UNIT}};' ],
			'arrow_padding'           => [ '{{WRAPPER}} .wcf-arrow' => 'padding: {{SIZE}}{{UNIT}};' ],
			'arrow_color'             => [ '{{WRAPPER}} .wcf-arrow' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
			'arrow_background'        => '{{WRAPPER}} .wcf-arrow',
			'arrow_hover_color'       => [ '{{WRAPPER}} .wcf-arrow:hover, {{WRAPPER}} .wcf-arrow:focus' => 'color: {{VALUE}}; fill: {{VALUE}};' ],
			'arrow_hover_background'   => '{{WRAPPER}} .wcf-arrow:hover, {{WRAPPER}} .wcf-arrow:focus',
			'arrow_hover_border_color' => [ '{{WRAPPER}} .wcf-arrow:hover, {{WRAPPER}} .wcf-arrow:focus' => 'border-color: {{VALUE}};' ],
			'arrow_border'             => '{{WRAPPER}} .wcf-arrow',
			'arrow_border_radius'      => [ '{{WRAPPER}} .wcf-arrow' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};' ],
		];

		$selectors = array_merge( $selectors, $default_selectors );

		$this->add_control(
			'arrow_size',
			[
				'label'     => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 5,
						'max' => 100,
					],
				],
				'selectors' => $selectors['arrow_size'],
			]
		);

		$this->add_control(
			'arrow_padding',
			[
				'label'     => esc_html__( 'padding', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'selectors' => $selectors['arrow_padding'],
			]
		);

		$this->start_controls_tabs( 'tabs_arrow_style' );

		$this->start_controls_tab(
			'tab_arrow_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'arrow_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['arrow_color'],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'arrow_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => $selectors['arrow_background'],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_arrow_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'arrow_hover_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['arrow_hover_color'],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'arrow_hover_background',
				'types' => [ 'classic', 'gradient' ],
				'selector' => $selectors['arrow_hover_background'],
			]
		);

		$this->add_control(
			'arrow_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => $selectors['arrow_hover_border_color'],
				'condition' => [ 'arrow_border_border!' => '' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'arrow_border',
				'selector'  => $selectors['arrow_border'],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'arrow_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => $selectors['arrow_border_radius'],
			]
		);
	}

	/**
	 * get slider settings.
	 *
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_slider_attributes( ) {

		//slider settings
		$slider_settings = [
			'loop'           => 'true' === $this->get_instance_value( 'loop' ),
			'speed'          => $this->get_instance_value( 'speed' ),
			'allowTouchMove' => 'true' === $this->get_instance_value( 'allow_touch_move' ),
			'slidesPerView'  => $this->get_instance_value( 'slides_to_show' ),
			'spaceBetween'   => $this->get_instance_value( 'space_between' ),
			'effect'         => $this->get_instance_value( 'effect' ),
		];

		if ( 'yes' === $this->get_instance_value('autoplay') ) {
			$slider_settings['autoplay'] = [
				'delay'                => $this->get_instance_value('autoplay_delay'),
				'disableOnInteraction' => $this->get_instance_value('autoplay_interaction'),
			];
		}

		if ( ! empty( $this->get_instance_value('navigation') ) ) {
			$slider_settings['navigation'] = [
				'nextEl' => '.elementor-element-' . $this->parent->get_id() . ' .wcf-arrow-next',
				'prevEl' => '.elementor-element-' . $this->parent->get_id() . ' .wcf-arrow-prev',
			];
		}

		if ( ! empty( $this->get_instance_value( 'pagination' ) ) ) {
			$slider_settings['pagination'] = [
				'el'        => '.elementor-element-' . $this->parent->get_id() . ' .swiper-pagination',
				'clickable' => true,
				'type'      => $this->get_instance_value( 'pagination_type' ),
			];
		}

		if ( ! empty( $this->get_instance_value('mousewheel') ) ) {
			$slider_settings['mousewheel'] = [
				'releaseOnEdges' => true,
			];
		}

		//slider breakpoints
		$active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

		foreach ( $active_breakpoints as $breakpoint_name => $breakpoint ) {
			$slides_to_show = ! empty( $this->get_instance_value( 'slides_to_show_' . $breakpoint_name ) ) ? $this->get_instance_value( 'slides_to_show_' . $breakpoint_name ) : $this->get_instance_value('slides_to_show');

			$slider_settings['breakpoints'][ $breakpoint->get_value() ]['slidesPerView'] = $slides_to_show;
		}

		$this->parent->add_render_attribute(
			'carousel-wrapper',
			[
				'class' => 'wcf__slider swiper',
				'dir'   => $this->get_instance_value('direction'),
				'style' => 'position: static',
			]
		);

		return $slider_settings;

	}

	/**
	 * Render slider navigation.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render_slider_navigation() {

		if ( empty( $this->get_instance_value( 'navigation' ) ) ) {
			return;
		}

		?>
        <div class="ts-navigation">
            <div class="wcf-arrow wcf-arrow-prev" role="button" tabindex="0">
				<?php $this->render_swiper_button( 'previous' ); ?>
            </div>
            <div class="wcf-arrow wcf-arrow-next" role="button" tabindex="0">
				<?php $this->render_swiper_button( 'next' ); ?>
            </div>
        </div>
		<?php
	}

	/**
	 * Render slider pagination.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render_slider_pagination() {

		if ( empty( $this->get_instance_value( 'pagination' ) ) ) {
		    return;
		}
		?>
        <div class="ts-pagination">
            <div class="swiper-pagination"></div>
        </div>
		<?php
	}

	/**
	 * Render swiper button.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render_swiper_button( $type ) {
		$direction     = 'next' === $type ? 'right' : 'left';
		$icon_settings = $this->get_instance_value( 'navigation_' . $type . '_icon' );

		if ( empty( $icon_settings['value'] ) ) {
			$icon_settings = [
				'library' => 'eicons',
				'value'   => 'eicon-chevron-' . $direction,
			];
		}

		Icons_Manager::render_icon( $icon_settings, [ 'aria-hidden' => 'true' ] );
	}

	// Get Taxonomy
	protected function get_taxonomies() {
		$taxonomies = get_taxonomies( [ 'show_in_nav_menus' => true ], 'objects' );

		$options = [ '' => '' ];

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	public function render_posts( ) {

		$query = $this->parent->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		while ( $query->have_posts() ) {
			$query->the_post();
			$this->render_post();
		}

		wp_reset_postdata();
	}

	public function render_post( ) {}

	protected function render_title() {
	    $title_tag = $this->parent->get_settings('title_tag');
		?>
		<<?php Utils::print_validated_html_tag(  $title_tag ); ?> class="title">
            <a href="<?php echo esc_url( get_the_permalink() ); ?>"><?php the_title(); ?></a>
        </<?php Utils::print_validated_html_tag(  $title_tag ); ?>>
		<?php
	}

	protected function render_date() {
		?>
        <div class="date"><?php echo esc_html(get_the_date()); ?></div>
		<?php
	}

	protected function render_thumb() {
	    $thumb_size = $this->parent->get_settings('thumbnail_size');
		?>
		<a href="<?php echo esc_url( get_the_permalink() ); ?>" aria-label="<?php the_title(); ?>">
			<?php the_post_thumbnail( $thumb_size ); ?>
        </a>
		<?php
	}

	protected function render_category() {
		$taxonomy = $this->get_instance_value('post_taxonomy');
		if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		$terms = get_the_terms( get_the_ID(), $taxonomy );
		if ( empty( $terms[0] ) ) {
			return;
		}
		?>
        <div class="category"><?php echo esc_html( $terms[0]->name ); ?></div>
		<?php
	}
}
