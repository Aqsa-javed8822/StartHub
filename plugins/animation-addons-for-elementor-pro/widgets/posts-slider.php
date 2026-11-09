<?php

namespace WCFAddonsPro\Widgets;

use Elementor\Control_Media;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use WCF_ADDONS\WCF_Post_Query_Trait;
use WCF_ADDONS\WCF_Slider_Trait;
use WCFAddonsPro\AAE_Post_Handler_Trait;
use WP_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Posts_Slider extends Widget_Base {

	use WCF_Slider_Trait;
	use WCF_Post_Query_Trait;
	use AAE_Post_Handler_Trait;

	public $query = null;

	/**
	 * Retrieve the widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_name() {
		return 'wcf--posts-slider';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_title() {
		return esc_html__( 'Posts Slider', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_icon() {
		return 'wcf eicon-image-box';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @return array Widget categories.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_categories() {
		return [ 'animation-addons-for-elementor-pro' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @return array Widget scripts dependencies.
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function get_script_depends() {
		return [ 'swiper', 'effect--panorama', 'wcf--posts-slider', 'wcf--posts' ];
	}

	/**
	 * Requires css files.
	 *
	 * @return array
	 */
	public function get_style_depends() {
		return [ 'swiper', 'wcf--posts-slider' ];
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
	protected function register_controls() {
		$start = is_rtl() ? 'right' : 'left';
		$end   = is_rtl() ? 'left' : 'right';

		//query
		$this->register_query_controls();

		//layout
		$this->register_layout_controls();

		//Thumbnail style
		$this->register_thumbnail_controls();

		//settings
		$this->register_settings_controls();

		//title
		$this->register_title_controls();

		//excerpt
		$this->register_excerpt_controls();

		$this->register_taxonomy_controls();

		$this->register_meta_controls();

		//read more
		$this->register_read_more_controls();

		// Post Popup
		$this->register_audio_video_play_controls();

		$this->style_posts_details_popup();

		//slide controls
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => esc_html__( 'Slider Settings', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'posts_per_page',
			[
				'label'   => esc_html__( 'Posts Limit', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
			]
		);

		$this->register_slider_controls( [ 'slides_to_show' => 3 ] );

		$this->add_control(
			'center_slide',
			[
				'label'     => esc_html__( 'Center Slide', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'  => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
					'false' => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				],
				'separator' => 'before',
				'condition' => [
					'post_layout' => [ 'layout-gallery', 'layout-gallery-2', 'layout-overlay-2' ],
				],
			]
		);

		$this->add_control(
			'slide_click_slide',
			[
				'label'     => esc_html__( 'Slide Clicked Slide', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'false',
				'options'   => [
					'true'  => esc_html__( 'Yes', 'animation-addons-for-elementor-pro' ),
					'false' => esc_html__( 'No', 'animation-addons-for-elementor-pro' ),
				],
				'condition' => [
					'post_layout' => [ 'layout-gallery', 'layout-gallery-2', 'layout-overlay-2' ],
				],
			]
		);

		$this->add_control(
			'panorama_depth',
			[
				'label'     => esc_html__( 'Panorama Depth', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 500,
				'default'   => 250,
				'condition' => [ 'post_layout' => 'layout-panorama' ],
			]
		);

		$this->add_control(
			'panorama_rotate',
			[
				'label'     => esc_html__( 'Panorama Rotate', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 360,
				'default'   => 20,
				'condition' => [ 'post_layout' => 'layout-panorama' ],
			]
		);

		$pagination_type['options'] = [
			'bullets'     => esc_html__( 'Bullets', 'animation-addons-for-elementor-pro' ),
			'fraction'    => esc_html__( 'Fraction', 'animation-addons-for-elementor-pro' ),
			'progressbar' => esc_html__( 'Progressbar', 'animation-addons-for-elementor-pro' ),
			'custom'      => esc_html__( 'Fraction Progress', 'animation-addons-for-elementor-pro' ),
		];

		$this->update_control( 'pagination_type', $pagination_type );

		$this->end_controls_section();

		//Settings
		$this->start_controls_section(
			'section_settings',
			[
				'label'     => __( 'Settings', 'animation-addons-for-elementor-pro' ),
				'condition' => [
					'post_layout!' => [ 'layout-gallery', 'layout-gallery-2', 'layout-panorama' ],
				],
			]
		);

		$this->add_responsive_control(
			'filter_direction',
			[
				'label'        => esc_html__( 'Direction', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'column'         => [
						'title' => esc_html__( 'Above', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Below', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-bottom',
					],
					'row-reverse'    => [
						'title' => esc_html__( 'After', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-' . $end,
					],
					'row'            => [
						'title' => esc_html__( 'Before', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-' . $start,
					],
				],
				'separator'    => 'before',
				'selectors'    => [
					'{{WRAPPER}} .wcf--posts-slider' => 'flex-direction: {{VALUE}}',
				],
				'prefix_class' => 'filter-direction-',
			]
		);

		$this->add_responsive_control(
			'filter_align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					''       => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
					'center' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
					'end'    => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .slide-filter' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'filter_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf--posts-slider' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'slider_width',
			[
				'label'      => esc_html__( 'Slider Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 5,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 80,
				],
				'selectors'  => [
					'{{WRAPPER}} .slider-wrapper' => 'max-width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'filter_direction' => [ 'row', 'row-reverse' ],
				],
			]
		);

		$this->end_controls_section();

		//slider navigation style controls
		$this->start_controls_section(
			'section_slider_navigation_style',
			[
				'label'     => esc_html__( 'Slider Navigation', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'navigation' => 'yes' ],
			]
		);

		$this->register_slider_navigation_style_controls();

		// Navigation Position Options

		$this->add_responsive_control(
			'arrows_type',
			[
				'label'     => esc_html__( 'Arrows Position Type', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'static',
				'options'   => [
					'static'   => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'absolute' => esc_html__( 'Absolute', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-arrow' => 'position: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'arrows_align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'    => [
						'title' => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-justify-start-h',
					],
					'center'        => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-justify-center-h',
					],
					'flex-end'      => [
						'title' => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-justify-end-h',
					],
					'space-between' => [
						'title' => esc_html__( 'Space Between', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-justify-space-between-h',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ts-navigation' => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					'arrows_type' => 'static',
				],
			]
		);

		$this->add_control(
			'prev_pos_toggle',
			[
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Arrow Prev', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
				'label_on'     => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'condition'    => [
					'arrows_type' => 'absolute',
				],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'prev_pos_left',
			[
				'label'      => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => - 1200,
						'max' => 1200,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'prev_pos_btm',
			[
				'label'      => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => - 500,
						'max' => 500,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-prev' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();

		$this->add_control(
			'next_pos_toggle',
			[
				'type'         => Controls_Manager::POPOVER_TOGGLE,
				'label'        => esc_html__( 'Arrow Next', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
				'label_on'     => esc_html__( 'Custom', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'condition'    => [
					'arrows_type' => 'absolute',
				],
			]
		);

		$this->start_popover();

		$this->add_responsive_control(
			'next_pos_right',
			[
				'label'      => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => - 1200,
						'max' => 1200,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-next' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'next_pos_btm',
			[
				'label'      => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => - 500,
						'max' => 500,
					],
					'%'  => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-arrow.wcf-arrow-next' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_popover();


		$this->end_controls_section();

		//slider pagination style controls
		$this->start_controls_section(
			'section_slider_pagination_style',
			[
				'label'     => esc_html__( 'Slider Pagination', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'pagination' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'slider-pagination_gaps',
			[
				'label'      => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .ts-pagination' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->register_slider_pagination_style_controls();

		$this->add_responsive_control(
			'pagination_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pagination_spacing',
			[
				'label'     => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'pagination_type' => 'custom' ]
			]
		);


		$this->add_responsive_control(
			'pagination_width',
			[
				'label'     => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .paginate-fill' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'pagination_type' => 'custom' ]
			]
		);

		$this->add_responsive_control(
			'pagination_height',
			[
				'label'     => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 1,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .paginate-fill' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [ 'pagination_type' => 'custom' ]
			]
		);

		$this->add_control(
			'paginate_color',
			[
				'label'     => esc_html__( 'Paginate Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .paginate-fill' => 'background: {{VALUE}};',
				],
				'condition' => [ 'pagination_type' => 'custom' ]
			]
		);

		$this->add_control(
			'paginate_active_color',
			[
				'label'     => esc_html__( 'Paginate Active Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination .paginate-fill:after' => 'background: {{VALUE}};',
				],
				'condition' => [ 'pagination_type' => 'custom' ]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'paginate_typography',
				'selector'  => '{{WRAPPER}} .swiper-pagination',
				'condition' => [ 'pagination_type' => 'custom' ]
			]
		);

		$this->add_control(
			'paginate_count_color',
			[
				'label'     => esc_html__( 'Count Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'color: {{VALUE}}',
				],
				'condition' => [ 'pagination_type' => 'custom' ]
			]
		);

		$this->end_controls_section();
	}

	protected function register_thumbnail_controls() {
		$this->start_controls_section(
			'section_style_post_image',
			[
				'label'     => esc_html__( 'Thumbnail', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_thumb'   => 'yes',
					'post_layout!' => 'layout-overlay',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 800,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'masonry_thumb_height',
			[
				'label'      => esc_html__( 'Masonry Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post.item-masonry .thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'masonry'  => 'yes',
					'columns!' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .thumb, {{WRAPPER}} .layout-gallery-2 .thumb img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .thumb' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		// Popup Image
		$this->add_responsive_control(
			'popup_thumb_width',
			[
				'label'      => esc_html__( 'Popup Image Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-post-popup-content .thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
				'separator'  => 'before',
				'condition'  => [ 'enable_popup' => 'yes' ],
			]
		);

		$this->add_responsive_control(
			'popup_thumb_height',
			[
				'label'      => esc_html__( 'Popup Image Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 500,
					],
					'vh' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .aae-post-popup-content .thumb' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'enable_popup' => 'yes' ],
			]
		);

		$this->add_control(
			'play_icon_color',
			[
				'label'     => esc_html__( 'Play Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .layout-gallery-2 .thumb .icon' => 'fill: {{VALUE}}; color: {{VALUE}}',
				],
				'condition' => [
					'post_layout' => [ 'layout-gallery-2' ],
				],
			]
		);

		$this->add_responsive_control(
			'play_icon_size',
			[
				'label'      => esc_html__( 'Play Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .layout-gallery-2 .thumb .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'post_layout' => [ 'layout-gallery-2' ],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_layout_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		//Image selector
		$this->add_control(
			'post_layout',
			[
				'label'   => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),				
				'type'    => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'layout-normal'    => [
						'title' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-2.jpg',
					],
					'layout-aside'     => [
						'title' => esc_html__( 'Aside', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-1.jpg',
					],
					'layout-overlay'   => [
						'title' => esc_html__( 'Overlay', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
					'layout-overlay-2' => [
						'title' => esc_html__( 'Overlay Two', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
					'layout-gallery'   => [
						'title' => esc_html__( 'Video Gallery', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
					'layout-gallery-2' => [
						'title' => esc_html__( 'Videos Gallery Two', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
					'layout-panorama'  => [
						'title' => esc_html__( 'Panorama Effect', 'animation-addons-for-elementor-pro' ),
						'url'   => WCF_ADDONS_PRO_URL . '/assets/image/post-layout-3.jpg',
					],
				],
				'default' => 'layout-normal',
			]
		);

		$this->add_control(
			'video_gl_notice',
			[
				'type'       => Controls_Manager::ALERT,
				'alert_type' => 'info',
				'heading'    => esc_html__( 'This layout is only for video posts.', 'animation-addons-for-elementor-pro' ),
				'condition'  => [ 'post_layout' => [ 'layout-gallery', 'layout-gallery-2' ] ]
			]
		);

		$this->add_responsive_control(
			'layout-aside-position',
			[
				'label'          => esc_html__( 'Aside Position', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::CHOOSE,
				'toggle'         => false,
				'default'        => 'row',
				'mobile_default' => 'column',
				'options'        => [
					'row'         => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-left',
					],
					'column'      => [
						'title' => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-v-align-top',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'condition'      => [
					'post_layout' => 'layout-aside',
				],
				'selectors'      => [
					'{{WRAPPER}} .swiper-slide.layout-aside' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		//layout overlay/aside
		$layout_one = new Repeater();

		$layout_one->add_control(
			'post_item',
			[
				'label'   => esc_html__( 'Item', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'title'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
					'taxonomy'  => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
					'excerpt'   => esc_html__( 'Excerpt', 'animation-addons-for-elementor-pro' ),
					'meta'      => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
					'read_more' => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'post_layout_one',
			[
				'label'        => esc_html__( 'Layout Aside/Overlay', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $layout_one->get_controls(),
				'item_actions' => [
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => true,
				],
				'default'      => [
					[
						'post_item' => 'taxonomy',
					],
					[
						'post_item' => 'title',
					],
					[
						'post_item' => 'excerpt',
					],
					[
						'post_item' => 'meta',
					],
					[
						'post_item' => 'read_more',
					],
				],
				'title_field'  => '{{{ post_item }}}',
				'condition'    => [
					'post_layout' => [
						'layout-aside',
						'layout-overlay',
						'layout-overlay-2',
						'layout-gallery',
						'layout-gallery-2',
						'layout-panorama',
					]
				]
			]
		);

		//layout normal
		$layout_two = new Repeater();

		$layout_two->add_control(
			'post_item',
			[
				'label'   => esc_html__( 'Item', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'title',
				'options' => [
					'thumb'     => esc_html__( 'Thumbnail', 'animation-addons-for-elementor-pro' ),
					'title'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
					'taxonomy'  => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
					'excerpt'   => esc_html__( 'Excerpt', 'animation-addons-for-elementor-pro' ),
					'meta'      => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
					'read_more' => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'post_layout_two',
			[
				'label'        => esc_html__( 'Layout Normal', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::REPEATER,
				'fields'       => $layout_two->get_controls(),
				'item_actions' => [
					'add'       => false,
					'duplicate' => false,
					'remove'    => false,
					'sort'      => true,
				],
				'default'      => [
					[
						'post_item' => 'taxonomy',
					],
					[
						'post_item' => 'thumb',
					],
					[
						'post_item' => 'title',
					],
					[
						'post_item' => 'excerpt',
					],
					[
						'post_item' => 'meta',
					],
					[
						'post_item' => 'read_more',
					],
				],
				'title_field'  => '{{{ post_item }}}',
				'condition'    => [ 'post_layout' => [ 'layout-normal' ] ]
			]
		);

		$this->end_controls_section();

		//layout style
		$this->register_layout_style_controls();

		// Content
		$this->register_content_style_controls();
	}

	protected function register_settings_controls() {
		$this->start_controls_section(
			'posts_section_settings',
			[
				'label' => esc_html__( 'Posts Settings', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_thumb',
			[
				'label'     => esc_html__( 'Show Thumb', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'  => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				'condition' => [
					'post_layout!' => 'layout-panorama',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'      => 'thumbnail_size',
				'exclude'   => [ 'custom' ],
				'default'   => 'medium',
				'condition' => [
					'show_thumb' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title',
			[
				'label'     => esc_html__( 'Show Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'default'   => 'yes',
				'label_off' => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'  => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'show_excerpt',
			[
				'label'     => esc_html__( 'Show Excerpt', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_taxonomy',
			[
				'label'     => esc_html__( 'Show Taxonomy', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_meta',
			[
				'label'     => esc_html__( 'Show Meta', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'     => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SWITCHER,
				'label_on'  => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off' => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'default'   => 'yes',
			]
		);

		//masonry
		$this->add_control(
			'masonry',
			[
				'label'       => esc_html__( 'Masonry', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SWITCHER,
				'label_off'   => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'    => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				'separator'   => 'before',
				'condition'   => [
					'columns!' => '1',
				],
				'render_type' => 'template',
				'selectors'   => [
					'{{WRAPPER}} .wcf-posts' => 'grid-auto-flow: dense;',
				],
			]
		);

		$this->add_control(
			'masonry_large',
			[
				'label'       => esc_html__( 'Masonry Large Items', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( '2, 6, 11', 'animation-addons-for-elementor-pro' ),
				'description' => esc_html__( 'Give the item sequence number with Coma separated.', 'animation-addons-for-elementor-pro' ),
				'ai'          => [
					'active' => false,
				],
				'condition'   => [
					'masonry'  => 'yes',
					'columns!' => '1',
				],
			]
		);

		$this->add_responsive_control(
			'col_span',
			[
				'label'          => esc_html__( 'Col Span', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '2',
				'mobile_default' => '1',
				'options'        => [
					'1' => esc_html__( '1', 'animation-addons-for-elementor-pro' ),
					'2' => esc_html__( '2', 'animation-addons-for-elementor-pro' ),
					'3' => esc_html__( '3', 'animation-addons-for-elementor-pro' ),
					'4' => esc_html__( '4', 'animation-addons-for-elementor-pro' ),
					'5' => esc_html__( '5', 'animation-addons-for-elementor-pro' ),
				],
				'selectors'      => [
					'{{WRAPPER}} .item-masonry' => 'grid-column: span {{VALUE}};',
				],
				'condition'      => [
					'masonry' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'row_span',
			[
				'label'          => esc_html__( 'Row Span', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '1',
				'mobile_default' => '1',
				'options'        => [
					'1' => esc_html__( '1', 'animation-addons-for-elementor-pro' ),
					'2' => esc_html__( '2', 'animation-addons-for-elementor-pro' ),
					'3' => esc_html__( '3', 'animation-addons-for-elementor-pro' ),
					'4' => esc_html__( '4', 'animation-addons-for-elementor-pro' ),
					'5' => esc_html__( '5', 'animation-addons-for-elementor-pro' ),
				],
				'selectors'      => [
					'{{WRAPPER}} .item-masonry' => 'grid-row: span {{VALUE}};',
				],
				'condition'      => [
					'masonry' => 'yes',
				],
			]
		);

		// Post Format
		$this->add_control(
			'post_format_a_v',
			[
				'label'        => esc_html__( 'Post Audio, Video & Gallery', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_off'    => esc_html__( 'Off', 'animation-addons-for-elementor-pro' ),
				'label_on'     => esc_html__( 'On', 'animation-addons-for-elementor-pro' ),
				'separator'    => 'before',
				'return_value' => 'yes',
				'condition'    => [
					'post_layout!' => [ 'layout-gallery', 'layout-gallery-2', 'layout-panorama' ],
				],
			]
		);

		$this->add_control(
			'g_video_icon',
			[
				'label'       => esc_html__( 'Video Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-play-circle',
					'library' => 'fa-solid',
				],
				'condition'   => [
					'post_layout' => [ 'layout-gallery-2' ],
				],
				'separator'   => 'before',
			]
		);

		$this->add_control(
			'enable_popup',
			[
				'label'        => esc_html__( 'Enable Popup', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'post_format_a_v!' => 'yes',
					'post_layout!'     => 'layout-panorama',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_layout_style_controls() {
		$this->start_controls_section(
			'section_design_layout',
			[
				'label' => esc_html__( 'General Style', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'post_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .swiper-slide.slide_item',
			]
		);

		$this->add_responsive_control(
			'post_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide.slide_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'post_margin',
			[
				'label'      => esc_html__( 'Marign', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .swiper-slide.slide_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//hover effect
		$this->add_control(
			'el_hover_effects',
			[
				'label'        => esc_html__( 'Hover Effect', 'animation-addons-for-elementor-pro' ),
				'description'  => esc_html__( 'This effect will work only on image tags.', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'effect-zoom-in',
				'options'      => [
					''                => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'effect-zoom-in'  => esc_html__( 'Zoom In', 'animation-addons-for-elementor-pro' ),
					'effect-zoom-out' => esc_html__( 'Zoom Out', 'animation-addons-for-elementor-pro' ),
					'left-move'       => esc_html__( 'Left Move', 'animation-addons-for-elementor-pro' ),
					'right-move'      => esc_html__( 'Right Move', 'animation-addons-for-elementor-pro' ),
				],
				'prefix_class' => 'wp-image-',
			]
		);

		$this->add_control(
			'alignment',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'toggle'    => false,
				'options'   => [
					'start'  => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'    => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post, {{WRAPPER}} .content' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-taxonomy'              => 'align-self: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-meta'                  => 'align-self: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-link'                  => 'align-self: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Video Thumb
		$this->start_controls_section(
			'style_g_video',
			[
				'label' => esc_html__( 'Gallery Video', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'gallery_v_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .g-thumb' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'post_layout' => 'layout-gallery' ]
			]
		);

		$this->add_responsive_control(
			'gallery_v_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
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
					'{{WRAPPER}} .g-thumb, {{WRAPPER}} .layout-gallery-2 .gallery-wrapper .swiper-slide' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'post_layout' => [ 'layout-gallery', 'layout-gallery-2' ] ]
			]
		);

		$this->add_responsive_control(
			'gallery_video_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .layout-gallery.wcf--posts-slider' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'post_layout' => 'layout-gallery' ]
			]
		);

		$this->end_controls_section();
	}

	protected function register_title_controls() {
		$this->start_controls_section(
			'section_title',
			[
				'label'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'show_title' => 'yes' ],
			]
		);

		$this->add_control(
			'title_length',
			[
				'label' => esc_html__( 'Title Length', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 2,
				'max'   => 100,
			]
		);

		$this->add_control(
			'title_tag',
			[
				'label'     => esc_html__( 'Title HTML Tag', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'h1'   => 'H1',
					'h2'   => 'H2',
					'h3'   => 'H3',
					'h4'   => 'H4',
					'h5'   => 'H5',
					'h6'   => 'H6',
					'div'  => 'div',
					'span' => 'span',
					'p'    => 'p',
				],
				'default'   => 'h3',
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_title_highlight',
			[
				'label'              => esc_html__( 'Show Highlight', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::SWITCHER,
				'separator'          => 'before',
				'label_on'           => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'          => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value'       => 'yes',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'highlight_title_length',
			[
				'label'              => esc_html__( 'Highlight Length', 'animation-addons-for-elementor-pro' ),
				'type'               => Controls_Manager::NUMBER,
				'default'            => 5,
				'min'                => 2,
				'max'                => 100,
				'condition'          => [
					'show_title_highlight' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_title_style',
			[
				'label'     => esc_html__( 'Title', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_title' );

		$this->start_controls_tab(
			'tab_title_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'title_typography',
				'selector' => '{{WRAPPER}} .wcf-post-title',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_tile_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'title_hover_style',
			[
				'label'        => esc_html__( 'Hover Type', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'prefix_class' => 'wcf--title-',
				'default'      => '',
				'options'      => [
					''          => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'underline' => esc_html__( 'Underline', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'title_color_hover',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title:hover, {{WRAPPER}} .layout-gallery .swiper-slide-active .wcf-post-title' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_hover_typography',
				'selector'  => '{{WRAPPER}} .wcf-post-title:hover',
				'condition' => [ 'title_hover_style' => '' ],
			]
		);

		$this->add_control(
			'title_under_color',
			[
				'label'     => esc_html__( 'Underline Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title a' => '--underline-color: {{VALUE}};',
				],
				'condition' => [ 'title_hover_style' => 'underline' ],
			]
		);

		$this->add_responsive_control(
			'title_under_thickness',
			[
				'label'      => esc_html__( 'Thickness', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-title a' => '--underline-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'title_hover_style' => 'underline' ],
			]
		);

		$this->add_control(
			'title_under_transition',
			[
				'label'     => esc_html__( 'Transition', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 3,
				'step'      => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title a' => '--underline-transition:  {{VALUE}}s;',
				],
				'condition' => [ 'title_hover_style' => 'underline' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'title_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'heading_highlight',
			[
				'label'     => esc_html__( 'Highlight', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'show_title_highlight' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_h_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title .highlight' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title_highlight' => 'yes',
				],
			]
		);

		$this->add_control(
			'title_hl_hover_color',
			[
				'label'     => esc_html__( 'Hover Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-title:hover .highlight' => 'color: {{VALUE}};',
				],
				'condition' => [
					'show_title_highlight' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'title_h_typography',
				'selector'  => '{{WRAPPER}} .wcf-post-title .highlight',
				'condition' => [
					'show_title_highlight' => 'yes',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_excerpt_controls() {
		$this->start_controls_section(
			'section_post_excerpt',
			[
				'label'     => esc_html__( 'Excerpt', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'show_excerpt' => 'yes' ],
			]
		);

		$this->add_control(
			'excerpt_length',
			[
				'label'   => esc_html__( 'Excerpt Length', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 5,
				'max'     => 100,
				'default' => 30,
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_style_post_excerpt',
			[
				'label'     => esc_html__( 'Excerpt', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'show_excerpt' => 'yes' ]
			]
		);

		$this->add_control(
			'excerpt_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-excerpt' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'excerpt_typography',
				'selector' => '{{WRAPPER}} .wcf-post-excerpt',
			]
		);

		$this->add_responsive_control(
			'excerpt_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_taxonomy_controls() {

		$this->start_controls_section(
			'section_taxonomy',
			[
				'label'     => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'show_taxonomy' => 'yes' ],
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

		$this->add_control(
			'taxonomy_limit',
			[
				'label'   => esc_html__( 'Limit', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => - 1,
				'max'     => 5,
				'default' => 1,
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_taxonomy_style',
			[
				'label'     => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_taxonomy' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'taxonomy_typography',
				'selector' => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->add_responsive_control(
			'taxonomy_spacing',
			[
				'label'      => esc_html__( 'Space Between', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_taxonomy' );

		$this->start_controls_tab(
			'tab_taxonomy_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'taxonomy_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a'                                                                         => 'color: {{VALUE}};',
					'{{WRAPPER}} .wcf-post-taxonomy a:first-child::before, {{WRAPPER}} .wcf-post-taxonomy a:last-child::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'taxonomy_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_taxonomy_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'tax_hover_style',
			[
				'label'        => esc_html__( 'Hover Type', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SELECT,
				'prefix_class' => 'wcf--taxonomy-',
				'default'      => '',
				'options'      => [
					''          => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'underline' => esc_html__( 'Underline', 'animation-addons-for-elementor-pro' ),
				],
				'condition'    => [ 'post_layout!' => 'layout-overlay-2' ],
			]
		);

		$this->add_control(
			'taxonomy_hover_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a:hover' => 'color: {{VALUE}};',
					'condition'                              => [ 'tax_hover_style' => '' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'taxonomy_hover_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .wcf-post-taxonomy a:hover',
				'condition' => [ 'tax_hover_style' => '' ],
			]
		);

		$this->add_control(
			'tax_under_color',
			[
				'label'     => esc_html__( 'Underline Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-ul-color: {{VALUE}};',
				],
				'condition' => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->add_responsive_control(
			'tax_under_thickness',
			[
				'label'      => esc_html__( 'Thickness', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-ul-thickness: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->add_responsive_control(
			'tax_under_btm',
			[
				'label'      => esc_html__( 'Position', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 10,
						'max' => 10,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-btm-position: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->add_control(
			'tax_under_transition',
			[
				'label'     => esc_html__( 'Transition', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 0,
				'max'       => 3,
				'step'      => 0.1,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => '--tax-ul-transition:  {{VALUE}}s;',
				],
				'condition' => [ 'tax_hover_style' => 'underline' ],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'taxonomy_border',
				'separator' => 'before',
				'selector'  => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->add_responsive_control(
			'taxonomy_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'taxonomy_box_shadow',
				'selector' => '{{WRAPPER}} .wcf-post-taxonomy a',
			]
		);

		$this->add_responsive_control(
			'taxonomy_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		//offset
		$this->add_control(
			'taxonomy_position',
			[
				'label'     => esc_html__( 'Position', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''         => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'absolute' => esc_html__( 'Absolute', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post-taxonomy' => 'position: {{VALUE}}',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'taxonomy_offset_x',
			[
				'label'      => esc_html__( 'Offset X', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'default'    => [
					'size' => 0,
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'vh', 'custom' ],
				'selectors'  => [
					'body:not(.rtl) {{WRAPPER}} .wcf-post-taxonomy' => 'left: {{SIZE}}{{UNIT}}',
					'body.rtl {{WRAPPER}} .wcf-post-taxonomy'       => 'right: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [ 'taxonomy_position!' => '' ],
			]
		);

		$this->add_responsive_control(
			'taxonomy_offset_y',
			[
				'label'      => esc_html__( 'Offset Y', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => - 1000,
						'max' => 1000,
					],
					'%'  => [
						'min' => - 200,
						'max' => 200,
					],
					'vh' => [
						'min' => - 200,
						'max' => 200,
					],
					'vw' => [
						'min' => - 200,
						'max' => 200,
					],
				],
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'vw', 'custom' ],
				'default'    => [
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-taxonomy' => 'top: {{SIZE}}{{UNIT}}',
				],
				'condition'  => [ 'taxonomy_position!' => '', ],
			]
		);

		$this->end_controls_section();
	}

	protected function register_meta_controls() {

		$this->start_controls_section(
			'section_meta',
			[
				'label'     => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [ 'show_meta' => 'yes' ]
			]
		);
		$this->add_control(
			'post_meta_style',
			[
				'label'   => esc_html__( 'Meta Type', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => esc_html__( 'Inline', 'animation-addons-for-elementor-pro' ),
					'Block'  => esc_html__( 'Block', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'post_meta',
			[
				'label'   => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'comments',
				'options' => [
					'author'     => esc_html__( 'Author', 'animation-addons-for-elementor-pro' ),
					'view'       => esc_html__( 'View', 'animation-addons-for-elementor-pro' ),
					'date'       => esc_html__( 'Date', 'animation-addons-for-elementor-pro' ),
					'time'       => esc_html__( 'Time', 'animation-addons-for-elementor-pro' ),
					'time-ago'   => esc_html__( 'Time Ago', 'animation-addons-for-elementor-pro' ),
					'comments'   => esc_html__( 'Comments', 'animation-addons-for-elementor-pro' ),
					'reviews'    => esc_html__( 'Reviews', 'animation-addons-for-elementor-pro' ),
					'read-later' => esc_html__( 'Read Later', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$repeater->add_control(
			'meta_icon',
			[
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'far fa-flag',
					'library' => 'fa-regular',
				],
			]
		);

		$this->add_control(
			'post_meta_data',
			[
				'label'       => esc_html__( 'Meta Data', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => [
					[
						'post_meta' => 'view',
					],
					[
						'post_meta' => 'date',
					],
				],
				'title_field' => '{{{ post_meta }}}',
			]
		);

		$this->add_control(
			'meta_separator',
			[
				'label'     => esc_html__( 'Separator Between', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '///',
				'ai'        => [
					'active' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta span + span:before' => 'content: "{{VALUE}}"',
				],
				'dynamic'   => [
					'active' => true,
				],
			]
		);

		$this->add_control(
			'post_by',
			[
				'label'   => esc_html__( 'Author By', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'By', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'show_avatar',
			[
				'label'        => esc_html__( 'Author Avatar', 'animation-addons-for-elementor-pro' ),
				'description'  => esc_html__( 'If you want to use the author avatar, you must chose "Author" in the meta data.', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'separator'    => 'before',
				'return_value' => 'yes',
			]
		);

		$this->add_responsive_control(
			'avatar_size',
			[
				'label'      => esc_html__( 'Avatar Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .post-author img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'show_avatar' => 'yes' ]
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'section_meta_style',
			[
				'label'     => esc_html__( 'Meta', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_meta' => 'yes',
				],
			]
		);

		$this->add_responsive_control(
			'meta_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta span + span:before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf-post-meta'                    => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'meta_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'meta_typography',
				'selector' => '{{WRAPPER}} .wcf-post-meta',
			]
		);

		$this->add_responsive_control(
			'meta_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'separator'  => 'before',
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta .meta-icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'meta_icon_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta .meta-icon' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'meta_icon_gap',
			[
				'label'      => esc_html__( 'Icon Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-meta .meta-icon' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//style admin
		$this->start_controls_section(
			'section_meta_admin_style',
			[
				'label'     => esc_html__( 'Meta Admin', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_meta'    => 'yes',
					'post_layout!' => [ 'layout-gallery-2', 'layout-gallery' ],
				],
			]
		);

		$this->add_responsive_control(
			'author_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-author'                    => 'gap: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf-post-meta .post-athor-area' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'author_by_heading',
			[
				'label' => esc_html__( 'Author By', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'admin_by_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-meta .post-by' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'admin_by_typography',
				'selector' => '{{WRAPPER}} .wcf-post-meta .post-by',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'author_images_border',
				'selector' => '{{WRAPPER}} .post-author img',
			]
		);

		$this->add_responsive_control(
			'author_images_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .post-author img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'author_images_size',
			[
				'label'      => esc_html__( 'Images Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-author img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'author_heading',
			[
				'label'     => esc_html__( 'Author', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'admin_typography',
				'selector' => '{{WRAPPER}} .post-author a',
			]
		);

		$this->start_controls_tabs( 'tabs_author' );

		$this->start_controls_tab(
			'tab_author_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'author_color',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-author a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_author_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'author_color_hover',
			[
				'label'     => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .post-author a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	protected function register_read_more_controls() {

		$this->start_controls_section(
			'section_post_read_more',
			[
				'label'     => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'read_more_text',
			[
				'label'   => esc_html__( 'Read More Text', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'selected_icon',
			[
				'label'       => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'icon_align',
			[
				'label'   => esc_html__( 'Icon Position', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'left',
				'options' => [
					'left'  => esc_html__( 'Before', 'animation-addons-for-elementor-pro' ),
					'right' => esc_html__( 'After', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'icon_indend',
			[
				'label'     => esc_html__( 'Icon Spacing', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//style
		$this->start_controls_section(
			'style_post_read_more',
			[
				'label'     => esc_html__( 'Read More', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_read_more' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'read_more_typography',
				'selector' => '{{WRAPPER}} .wcf-post-link',
			]
		);

		$this->add_responsive_control(
			'read_more_icon_size',
			[
				'label'      => esc_html__( 'Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 100,
						'step' => 1,
					],
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link i'   => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .wcf-post-link svg' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'tabs_read_more',
		);

		$this->start_controls_tab(
			'tab_read_more_normal',
			[
				'label' => esc_html__( 'Normal', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'read_more_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'read_more_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-post-link',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_read-more_hover',
			[
				'label' => esc_html__( 'Hover', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'read_more_text_hover_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link:hover' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'read_more_hover_background',
				'types'    => [ 'classic', 'gradient' ],
				'exclude'  => [ 'image' ],
				'selector' => '{{WRAPPER}} .wcf-post-link:hover',
			]
		);

		$this->add_control(
			'read_more_hover_border_color',
			[
				'label'     => esc_html__( 'Border Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .wcf-post-link:hover' => 'border-color: {{VALUE}};',
				],
				'condition' => [
					'read_more_border_border!' => '',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'read_more_border',
				'selector'  => '{{WRAPPER}} .wcf-post-link',
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'read_more_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'read_more_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'  => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'read_more_shadow',
				'selector' => '{{WRAPPER}} .wcf-post-link',
			]
		);

		$this->add_responsive_control(
			'read_more_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .wcf-post-link' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_content_style_controls() {

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'enable_blend',
			[
				'label'        => esc_html__( 'Enable Blend', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'post_layout' => [ 'layout-gallery-2' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'content_background',
				'types'     => [ 'classic', 'gradient' ],
				'selector'  => '{{WRAPPER}} .content',
				'condition' => [
					'post_layout'   => [ 'layout-overlay-2', 'layout-gallery-2' ],
					'enable_blend!' => 'yes',
				],
			]
		);

		$this->add_control(
			'bg_blend_mode',
			[
				'label'     => esc_html__( 'Blend Mode', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => [
					''           => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
					'multiply'   => esc_html__( 'Multiply', 'animation-addons-for-elementor-pro' ),
					'darken'     => esc_html__( 'Darken', 'animation-addons-for-elementor-pro' ),
					'difference' => esc_html__( 'Difference', 'animation-addons-for-elementor-pro' ),
					'exclusion'  => esc_html__( 'Exclusion', 'animation-addons-for-elementor-pro' ),
					'luminosity' => esc_html__( 'Luminosity', 'animation-addons-for-elementor-pro' ),
				],
				'selectors' => [
					'{{WRAPPER}} .g-thumb, {{WRAPPER}} .layout-gallery-2 .gallery-wrapper .swiper-slide' => 'mix-blend-mode: {{VALUE}};',
				],
				'condition' => [ 'enable_blend' => 'yes' ]
			]
		);

		$this->add_control(
			'blend_color',
			[
				'label'     => esc_html__( 'Blend Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .layout-gallery-2 .gallery-wrapper .swiper-slide' => 'background-color: {{VALUE}}',
				],
				'condition' => [ 'enable_blend' => 'yes' ],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'content_shadow',
				'selector'  => '{{WRAPPER}} .content',
				'condition' => [
					'post_layout' => [ 'layout-overlay-2' ]
				],
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
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
					'{{WRAPPER}} .content' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'post_layout' => [ 'layout-overlay-2' ]
				],
			]
		);

		$this->add_responsive_control(
			'content_margin',
			[
				'label'      => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min' => - 500,
						'max' => 100,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [
					'post_layout' => [ 'layout-overlay-2' ]
				],
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'post_layout' => [ 'layout-overlay-2', 'layout-gallery-2' ]
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_image_style_controls() {

		$this->start_controls_section(
			'section_image_style',
			[
				'label' => esc_html__( 'Image', 'animation-addons-for-elementor-pro' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'img_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 0,
						'max'  => 1000,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thumb img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'img_height',
			[
				'label'      => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range'      => [
					'px' => [
						'min'  => 1,
						'max'  => 700,
						'step' => 1,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .thumb img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'object_fit',
			[
				'label'     => esc_html__( 'Object Fit', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'condition' => [
					'img_height[size]!' => '',
				],
				'options'   => [
					''        => esc_html__( 'Default', 'animation-addons-for-elementor-pro' ),
					'fill'    => esc_html__( 'Fill', 'animation-addons-for-elementor-pro' ),
					'cover'   => esc_html__( 'Cover', 'animation-addons-for-elementor-pro' ),
					'contain' => esc_html__( 'Contain', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .thumb img' => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'object_position',
			[
				'label'     => esc_html__( 'Object Position', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'center center' => esc_html__( 'Center Center', 'animation-addons-for-elementor-pro' ),
					'center left'   => esc_html__( 'Center Left', 'animation-addons-for-elementor-pro' ),
					'center right'  => esc_html__( 'Center Right', 'animation-addons-for-elementor-pro' ),
					'top center'    => esc_html__( 'Top Center', 'animation-addons-for-elementor-pro' ),
					'top left'      => esc_html__( 'Top Left', 'animation-addons-for-elementor-pro' ),
					'top right'     => esc_html__( 'Top Right', 'animation-addons-for-elementor-pro' ),
					'bottom center' => esc_html__( 'Bottom Center', 'animation-addons-for-elementor-pro' ),
					'bottom left'   => esc_html__( 'Bottom Left', 'animation-addons-for-elementor-pro' ),
					'bottom right'  => esc_html__( 'Bottom Right', 'animation-addons-for-elementor-pro' ),
				],
				'default'   => 'center center',
				'selectors' => [
					'{{WRAPPER}} .thumb img' => 'object-position: {{VALUE}};',
				],
				'condition' => [
					'object_fit' => 'cover',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_audio_video_play_controls() {
		$this->start_controls_section(
			'section_audio_video_play',
			[
				'label'     => esc_html__( 'Post Popup', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'post_format_a_v' => 'yes' ]
			]
		);

		$this->add_control(
			'audio_video_play',
			[
				'label'       => esc_html__( 'Video Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-play-circle',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'audio_icon',
			[
				'label'       => esc_html__( 'Audio Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'fas fa-music',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_control(
			'gallery_icon',
			[
				'label'       => esc_html__( 'Gallery Icon', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::ICONS,
				'skin'        => 'inline',
				'label_block' => false,
				'default'     => [
					'value'   => 'far fa-images',
					'library' => 'fa-solid',
				],
			]
		);

		$this->add_responsive_control(
			'audio_video_play_size',
			[
				'label'      => esc_html__( 'Play Icon Size', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default'    => [
					'unit' => 'px',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .play' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'audio_video_play_color',
			[
				'label'     => esc_html__( 'Play Icon Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .play' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'audio_video_play_offset_x',
			[
				'label'      => esc_html__( 'Offset X', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .play' => '--offset-x: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'audio_video_play_offset_y',
			[
				'label'      => esc_html__( 'Offset Y', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
					'px' => [
						'min' => 0,
						'max' => 1000,
					],
				],
				'default'    => [
					'unit' => '%',
					'size' => 50,
				],
				'selectors'  => [
					'{{WRAPPER}} .play' => '--offset-y: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function style_posts_details_popup() {
		$this->start_controls_section(
			'style_post_popup',
			[
				'label'     => esc_html__( 'Post Popup', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [ 'enable_popup' => 'yes' ]
			]
		);

		$this->add_responsive_control(
			'pp_video_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 1500,
					],
					'%'  => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.wcf--popup-video.post-details-popup' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'pp_close_position',
			[
				'label'   => esc_html__( 'Close Icon Position', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'right' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
					'left'  => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_responsive_control(
			'pp_close_top',
			[
				'label'      => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.post-details-popup .wcf--popup-close' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'pp_close_left',
			[
				'label'      => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.post-details-popup .wcf--popup-close' => 'right: unset; left: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'pp_close_position' => 'left' ],
			]
		);

		$this->add_responsive_control(
			'pp_close_right',
			[
				'label'      => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => - 100,
						'max' => 100,
					],
				],
				'selectors'  => [
					'{{WRAPPER}}.post-details-popup .wcf--popup-close' => 'left: unset; right: {{SIZE}}{{UNIT}};',
				],
				'condition'  => [ 'pp_close_position' => 'right' ],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings    = $this->get_settings_for_display();
		$popup_class = $settings['enable_popup'] === 'yes' ? 'popup-enabled' : '';

		//wrapper class
		$this->add_render_attribute( 'wrapper', 'class', [
			'wcf__posts-slider ' . $popup_class,
			$settings['post_layout'],
		] );

		$this->add_render_attribute( 'wrapper', [ 'class' => 'wcf--posts-slider' ] );
		?>
        <div <?php $this->print_render_attribute_string( 'wrapper' ); ?>>
			<?php
			if ( 'layout-gallery' === $settings['post_layout'] ) {
				$this->render_posts_gallery_thumb( $settings );
			}
			if ( 'layout-gallery-2' === $settings['post_layout'] ) {
				$this->render_posts_gallery_thumb_2( $settings );
			}
			$this->render_projects( $settings )
			?>
        </div>
		<?php
	}

	protected function render_projects( $settings ) {
		if ( empty( $settings['posts_per_page'] ) ) {
			return;
		}

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}

		// Slider settings.
		$slider_settings = $this->get_slider_attributes();

		$slider_settings['centeredSlides']      = $settings['center_slide'];
		$slider_settings['slideToClickedSlide'] = $settings['slide_click_slide'];

		if ( 'layout-panorama' === $settings['post_layout'] ) {
			$slider_settings['modules']        = [ 'EffectPanorama' ];
			$slider_settings['effect']         = 'panorama';
			$slider_settings['panoramaEffect'] = [
				'depth'  => $settings['panorama_depth'],
				'rotate' => $settings['panorama_rotate'],
			];
		}

		$this->add_render_attribute(
			'slider-wrapper',
			[
				'class'         => [ 'slider-wrapper' ],
				'data-settings' => json_encode( $slider_settings ), //phpcs:ignore
			]
		);

		// wrapper class
		$this->add_render_attribute( 'slider-wrapper', 'class', [
			'wcf__posts-slider',
			$settings['post_layout'],
		] );


		?>
        <div <?php $this->print_render_attribute_string( 'slider-wrapper' ); ?>>

            <div <?php $this->print_render_attribute_string( 'carousel-wrapper' ); ?>>
                <div class="swiper-wrapper">
					<?php
					$i = 1;
					while ( $query->have_posts() ) {
						$query->the_post();
						if ( 'layout-normal' === $settings['post_layout'] ) {
							$this->render_post_layout_normal( $settings, $i );
						} elseif ( 'layout-overlay' === $settings['post_layout'] ) {
							$this->render_post_layout_side_overlay( $settings, $i );
						} elseif ( 'layout-overlay-2' === $settings['post_layout'] ) {
							$this->render_post_layout_overlay_2( $settings, $i );
						} elseif ( 'layout-gallery' === $settings['post_layout'] ) {
							$this->render_post_layout_gallery( $settings, $i );
						} elseif ( 'layout-gallery-2' === $settings['post_layout'] ) {
							$this->render_post_layout_gallery_2( $settings );
						} elseif ( 'layout-panorama' === $settings['post_layout'] ) {
							$this->render_posts_panorama_effect( $settings );
						} else {
							$this->render_post_layout_side_aside( $settings, $i );
						}

						$i ++;
					}
					wp_reset_postdata();
					?>
                </div>
            </div>

            <!-- navigation and pagination -->
			<?php if ( 1 < $settings['posts_per_page'] ) : ?>
				<?php $this->render_slider_navigation(); ?>
				<?php $this->render_slider_pagination(); ?>
			<?php endif; ?>
        </div>
		<?php
	}


	// for posts content
	protected function render_loop_header() {
		?>
        <div class="wcf-posts swiper-slide">
		<?php
	}

	protected function render_loop_footer() {
		?></div><?php
		$this->render_pagination();
	}

	protected function render_post_layout_normal( $settings, $i ) {

		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}

		$filter_class = 'slide_item' . ' ' . $i;
		?>

        <div class="swiper-slide <?php echo esc_attr( $filter_class ); ?>">
			<?php
			foreach ( $settings['post_layout_two'] as $item ) {
				if ( 'thumb' === $item['post_item'] ) {
					$this->render_thumbnail( $settings );
				}

				if ( 'taxonomy' === $item['post_item'] ) {
					$this->render_post_taxonomy();
				}

				if ( 'title' === $item['post_item'] ) {
					$this->render_title();
				}

				if ( 'excerpt' === $item['post_item'] ) {
					$this->render_excerpt();
				}

				if ( 'meta' === $item['post_item'] ) {

					if ( 'inline' === $settings['post_meta_style'] ) {
						$this->render_meta_data();
					} else {
						$this->render_meta_data_block();
					}
				}

				if ( 'read_more' === $item['post_item'] ) {
					$this->render_read_more();
				}
			}
			?>

        </div>
		<?php
	}

	protected function render_post_layout_overlay_2( $settings, $i ) {

		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}

		$filter_class = 'slide_item' . ' ' . $i;
		?>

        <div class="swiper-slide layout-overlay-2 <?php echo esc_attr( $filter_class ); ?>">
			<?php $this->render_thumbnail( $settings ); ?>
            <div class="content">
				<?php
				foreach ( $settings['post_layout_one'] as $item ) {
					if ( 'taxonomy' === $item['post_item'] ) {
						$this->render_post_taxonomy();
					}

					if ( 'title' === $item['post_item'] ) {
						$this->render_title();
					}

					if ( 'excerpt' === $item['post_item'] ) {
						$this->render_excerpt();
					}

					if ( 'meta' === $item['post_item'] ) {
						$this->render_meta_data();
					}

					if ( 'read_more' === $item['post_item'] ) {
						$this->render_read_more();
					}
				}
				?>
            </div>
        </div>
		<?php
	}

	protected function render_post_layout_side_overlay( $settings, $i ) {

		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}

		$filter_class = 'slide_item' . ' ' . $i;
		?>

        <div class="swiper-slide layout-overlay <?php echo esc_attr( $filter_class ); ?>">
			<?php $this->render_thumbnail( $settings ); ?>
            <div class="content">
				<?php
				foreach ( $settings['post_layout_one'] as $item ) {
					if ( 'taxonomy' === $item['post_item'] ) {
						$this->render_post_taxonomy();
					}

					if ( 'title' === $item['post_item'] ) {
						$this->render_title();
					}

					if ( 'excerpt' === $item['post_item'] ) {
						$this->render_excerpt();
					}

					if ( 'meta' === $item['post_item'] ) {
						$this->render_meta_data();
					}

					if ( 'read_more' === $item['post_item'] ) {
						$this->render_read_more();
					}
				}
				?>
            </div>
        </div>
		<?php
	}

	protected function render_post_layout_side_aside( $settings, $i ) {

		$masonry_large_items = explode( ',', $settings['masonry_large'] ?? '' );
		if ( 'yes' === $settings['masonry'] && in_array( $i, $masonry_large_items ) ) {
			$post_classes[] = 'item-masonry';
		}

		$filter_class = 'slide_item' . ' ' . $i;
		?>

        <div class="swiper-slide layout-aside <?php echo esc_attr( $filter_class ); ?>">
            <div class="aside_thumbnail">
				<?php $this->render_thumbnail( $settings ); ?>
            </div>
            <div class="aside_content">
				<?php
				foreach ( $settings['post_layout_one'] as $item ) {

					if ( 'taxonomy' === $item['post_item'] ) {
						$this->render_post_taxonomy();
					}

					if ( 'title' === $item['post_item'] ) {
						$this->render_title();
					}

					if ( 'excerpt' === $item['post_item'] ) {
						$this->render_excerpt();
					}

					if ( 'meta' === $item['post_item'] ) {

						if ( 'inline' === $settings['post_meta_style'] ) {
							$this->render_meta_data();
						} else {
							$this->render_meta_data_block();
						}
					}

					if ( 'read_more' === $item['post_item'] ) {
						$this->render_read_more();
					}
				}
				?>
            </div>

        </div>
		<?php
	}

	protected function render_post_layout_gallery( $settings, $i ) {
		$filter_class = 'slide_item' . ' ' . $i;

		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];
		?>

        <div class="swiper-slide <?php echo esc_attr( $filter_class ); ?>">
			<?php
			if ( $settings['show_thumb'] ) { ?>
                <div class="thumb">
					<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail_size' ); ?>
                </div>
			<?php } ?>
            <div class="content">
				<?php
				foreach ( $settings['post_layout_one'] as $item ) {
					if ( 'taxonomy' === $item['post_item'] ) {
						$this->render_post_taxonomy();
					}

					if ( 'title' === $item['post_item'] ) {
						$this->render_title();
					}

					if ( 'excerpt' === $item['post_item'] ) {
						$this->render_excerpt();
					}

					if ( 'meta' === $item['post_item'] ) {
						$this->render_meta_data();
					}

					if ( 'read_more' === $item['post_item'] ) {
						$this->render_read_more();
					}
				}
				?>
            </div>
        </div>
		<?php
	}

	protected function render_posts_gallery_thumb( $settings ) {
		if ( empty( $settings['posts_per_page'] ) ) {
			return;
		}

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}
		?>
        <div class="gallery-wrapper">
            <div class="swiper wcf-post-gallery-active">
                <div class="swiper-wrapper">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();

						$format                     = get_post_format();
						$settings['thumbnail_size'] = [
							'id' => get_post_thumbnail_id(),
						];

						?>
                        <div class="swiper-slide">
                            <div class="g-thumb">
								<?php if ( 'audio' === $format ) {
									$link = get_post_meta( get_the_ID(), '_audio_url', true );
									?>
                                    <audio controls>
                                        <source src="<?php echo $link; ?>" type="audio/mpeg">
                                    </audio>
								<?php } elseif ( 'video' === $format ) {
									$link = get_post_meta( get_the_ID(), '_video_url', true );

									// Youtube Link Checking
									if ( strpos( $link, "https://www.youtube.com/" ) === 0 ) {
										parse_str( parse_url( $link, PHP_URL_QUERY ), $yt_query );

										if ( isset( $yt_query['v'] ) ) {
											$ytVideoId = $yt_query['v'];
											$link      = "https://www.youtube.com/embed/" . $ytVideoId;
										}
									}

									// Vimeo Link Checking
									if ( strpos( $link, "https://vimeo.com/" ) === 0 ) {
										$videoId = str_replace( "https://vimeo.com/", "", $link );
										$link    = "https://player.vimeo.com/video/" . $videoId;
									}
									?>
                                    <iframe src="<?php echo $link; ?>"></iframe>
								<?php } else { ?>
									<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail_size' ); ?>
								<?php } ?>
                            </div>
                        </div>
						<?php
					}

					wp_reset_postdata();
					?>
                </div>
            </div>
        </div>
		<?php
	}

	protected function render_post_layout_gallery_2( $settings ) {
		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];
		?>

        <div class="swiper-slide">
			<?php
			if ( $settings['show_thumb'] ) { ?>
                <div class="thumb">
					<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail_size' ); ?>
                    <div class="icon">
						<?php Icons_Manager::render_icon( $settings['g_video_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                    </div>
                </div>
			<?php } ?>
        </div>
		<?php
	}

	protected function render_posts_gallery_thumb_2( $settings ) {
		if ( empty( $settings['posts_per_page'] ) ) {
			return;
		}

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}
		?>
        <div class="gallery-wrapper">
            <div class="swiper wcf-post-gallery-active">
                <div class="swiper-wrapper">
					<?php
					while ( $query->have_posts() ) {
						$query->the_post();

						$format                     = get_post_format();
						$settings['thumbnail_size'] = [
							'id' => get_post_thumbnail_id(),
						];

						?>
                        <div class="swiper-slide">
                            <div class="g-thumb">
								<?php if ( 'video' === $format ) {
									$link = get_post_meta( get_the_ID(), '_video_url', true );

									if ( strpos( $link, "https://www.youtube.com/" ) === 0 ) {
										parse_str( parse_url( $link, PHP_URL_QUERY ), $yt_query );

										if ( isset( $yt_query['v'] ) ) {
											$ytVideoId = $yt_query['v'];
											$link      = "https://www.youtube.com/embed/" . $ytVideoId . "?autoplay=1&mute=1&loop=1&controls=0&rel=0&playlist={$ytVideoId}";
										}
										?>
                                        <iframe src="<?php echo $link; ?>"></iframe>
										<?php
									} elseif ( strpos( $link, "https://vimeo.com/" ) === 0 ) {
										$videoId = str_replace( "https://vimeo.com/", "", $link );
										$link    = "https://player.vimeo.com/video/" . $videoId . "?autoplay=1&muted=1&loop=1&background=1";
										?>
                                        <iframe src="<?php echo $link; ?>"></iframe>
										<?php
									} else {
										?>
                                        <video autoplay muted loop playsinline oncontextmenu="return false">
                                            <source src="<?php echo $link; ?>" type="video/mp4">
                                        </video>
										<?php
									}
									?>
								<?php } else { ?>
									<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail_size' ); ?>
								<?php } ?>
                            </div>

                            <div class="content">
								<?php
								foreach ( $settings['post_layout_one'] as $item ) {
									if ( 'taxonomy' === $item['post_item'] ) {
										$this->render_post_taxonomy();
									}

									if ( 'title' === $item['post_item'] ) {
										$this->render_title();
									}

									if ( 'excerpt' === $item['post_item'] ) {
										$this->render_excerpt();
									}

									if ( 'meta' === $item['post_item'] ) {
										$this->render_meta_data();
									}

									if ( 'read_more' === $item['post_item'] ) {
										$this->render_read_more();
									}
								}
								?>
                            </div>
                        </div>
						<?php
					}

					wp_reset_postdata();
					?>
                </div>
            </div>
        </div>
		<?php
	}

	protected function render_posts_panorama_effect( $settings ) {
		if ( empty( $settings['posts_per_page'] ) ) {
			return;
		}

		$settings['thumbnail_size'] = [
			'id' => get_post_thumbnail_id(),
		];

		$query = $this->get_query();

		if ( ! $query->found_posts ) {
			return;
		}
		?>
        <div class="swiper-slide">
            <a href="<?php the_permalink(); ?>" class="thumb">
				<?php Group_Control_Image_Size::print_attachment_image_html( $settings, 'thumbnail_size' ); ?>
            </a>

            <div class="content">
				<?php
				foreach ( $settings['post_layout_one'] as $item ) {
					if ( 'taxonomy' === $item['post_item'] ) {
						$this->render_post_taxonomy();
					}

					if ( 'title' === $item['post_item'] ) {
						$this->render_title();
					}

					if ( 'excerpt' === $item['post_item'] ) {
						$this->render_excerpt();
					}

					if ( 'meta' === $item['post_item'] ) {
						$this->render_meta_data();
					}

					if ( 'read_more' === $item['post_item'] ) {
						$this->render_read_more();
					}
				}
				?>
            </div>
        </div>
		<?php
	}
}
