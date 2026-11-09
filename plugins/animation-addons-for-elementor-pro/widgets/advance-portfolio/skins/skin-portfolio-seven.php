<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Portfolio_Seven extends Skin_Portfolio_Base {

	protected $thumb_slides = [];

	/**
	 * Get skin ID.
	 *
	 * Retrieve the skin ID.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_id() {
		return 'skin-portfolio-seven';
	}

	/**
	 * Get skin title.
	 *
	 * Retrieve the skin title.
	 *
	 * @since 1.0.0
	 * @access public
	 * @abstract
	 */
	public function get_title() {
		return __( 'Portfolio Seven', 'animation-addons-for-elementor-pro' );
	}

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
		parent::_register_controls_actions();

		add_action( 'elementor/element/wcf--a-portfolio/section_layout/before_section_end', [ $this, 'inject_controls' ] );
	}

	public function inject_controls() {
		$this->parent->start_injection( [
			'at' => 'after',
			'of' => 'title_tag',
		] );

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'default' => [
					'value' => 'fas fa-long-arrow-alt-right',
					'library' => 'fa-solid',
				],
				'label_block' => false,
			]
		);

		$this->parent->end_injection();
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

		// Slider Controls
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => __( 'Slider', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->register_slider_controls();

		$this->end_controls_section();

		// Layout Style Controls
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'thumb_slider_width',
			[
				'label'      => esc_html__( 'Thumb slider width', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .wcf__thumb-slider-wrapper' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'thumb_slider_height',
			[
				'label'      => esc_html__( 'Thumb slider Height', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .wcf__thumb-slider-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control( 'thumb_slider_direction',
			[
				'label'     => esc_html__( 'Thumb slider direction', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row-reverse' => [
						'title' => esc_html__( 'After', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-left',
					],
					'row'         => [
						'title' => esc_html__( 'Before', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--advance-portfolio.skin-portfolio-seven' => 'flex-direction: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Icon Style
		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .icon' => 'color: {{VALUE}};fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_rotate',
			[
				'label'     => esc_html__( 'Rotate', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => - 360,
						'max' => 360,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .icon' => 'rotate: {{SIZE}}deg;',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label'      => esc_html__( 'Margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'default'    => [
					'top'      => 0,
					'right'    => 0,
					'bottom'   => 1,
					'left'     => 0,
					'unit'     => 'em',
					'isLinked' => false,
				],
				'selectors'  => [
					'{{WRAPPER}} .icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Content
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label' => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .main-slider .swiper-slide' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		// Meta
		$this->register_meta_controls();

		// Slider Navigation Style
		$this->start_controls_section(
			'section_navigation_style',
			[
				'label' => esc_html__( 'Slider Navigation', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'navigation' ) => 'yes'
				]
			]
		);

		$this->register_slider_navigation_style_controls();

		$this->end_controls_section();

		// Slider Pagination Style
		$this->start_controls_section(
			'section_pagination_style',
			[
				'label'     => esc_html__( 'Slider Pagination', 'animation-addons-for-elementor-pro' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					$this->get_control_id( 'pagination' ) => 'yes'
				]
			]
		);

		$this->register_slider_pagination_style_controls();

		$this->add_responsive_control(
			'pagination_gap',
			[
				'label' => esc_html__( 'Spacing', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => -300,
						'max' => 300,
						'step' => 1,
					],
					'%' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => -20,
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination' => 'bottom: {{SIZE}}{{UNIT}};',
				],
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

		parent::register_slider_controls();

		$default = [
			'slides_to_show'       => '5',
		];

		$default = array_merge(  $default, $default_value );

		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->update_control(
			'slides_to_show',
			[
				'label'       => esc_html__( 'Thumb Slides to Show', 'animation-addons-for-elementor-pro' ),
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


		$this->add_responsive_control(
			'thumb_display',
			[
				'label'          => esc_html__( 'Thumb Slider Display', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SELECT,
				'separator'      => 'before',
				'default'        => 'block',
				'mobile_default' => 'none',
				'options'        => [
					'block' => esc_html__( 'Block', 'animation-addons-for-elementor-pro' ),
					'none'  => esc_html__( 'None', 'animation-addons-for-elementor-pro' ),
				],
				'selectors'      => [
					'{{WRAPPER}} .wcf__thumb-slider-wrapper' => 'display: {{VALUE}};',
				],
			]
		);

	}

	/**
	 * get thumb slider settings.
	 *
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 *
	 * @return array
	 */
	protected function get_thumb_slider_attributes( ) {

		//slider settings
		$slider_settings = [
			'loop'                 => 'true' === $this->get_instance_value( 'loop' ),
			'slidesPerView'        => $this->get_instance_value( 'slides_to_show' ),
			'spaceBetween'         => $this->get_instance_value( 'space_between' ),
			'speed'                => $this->get_instance_value( 'speed' ),
			'allowTouchMove'       => 'true' === $this->get_instance_value( 'allow_touch_move' ),
			'loopAdditionalSlides' => $this->get_instance_value( 'slides_to_show' ),
			'watchSlidesProgress'  => true,
			'slideToClickedSlide'  => true,
			'centeredSlides'       => true,
			'direction'            => "vertical",

		];

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
			'thumb-carousel-wrapper',
			[
				'class'        => 'wcf__thumb_slider swiper',
				'thumbsSlider' => " ",
				'style'        => 'position: static',
			]
		);

		return $slider_settings;

	}

	/**
	 * Render button widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function render() {

		$slider_settings = $this->get_slider_attributes();

		//forcefully overwrite default settings
		$slider_settings['slidesPerView'] = 'auto';
		//slider breakpoints
		$active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

		foreach ( $active_breakpoints as $breakpoint_name => $breakpoint ) {

			$slider_settings['breakpoints'][ $breakpoint->get_value() ]['slidesPerView'] = $slider_settings['slidesPerView'];
		}

		$thumb_slider_settings = $this->get_thumb_slider_attributes();

		$this->parent->add_render_attribute(
			'wrapper',
			[
				'class'         => [ 'wcf__slider-wrapper wcf--advance-portfolio ' . $this->get_id() ],
				'data-settings' => json_encode( $slider_settings ), //phpcs:ignore
			]
		);

		$this->parent->add_render_attribute(
			'thumb-wrapper',
			[
				'class'         => [ 'wcf__thumb-slider-wrapper ' ],
				'data-settings' => json_encode( $thumb_slider_settings ), //phpcs:ignore
			]
		);

		?>
        <div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="main-slider">
                <div <?php $this->parent->print_render_attribute_string( 'carousel-wrapper' ) ?>>
                    <div class="swiper-wrapper">
						<?php $this->render_posts(); ?>
                    </div>
                </div>

                <!--navigation -->
				<?php $this->render_slider_navigation(); ?>

                <!--pagination -->
				<?php $this->render_slider_pagination(); ?>
            </div>
            <!--thumb slider-->
            <div <?php $this->parent->print_render_attribute_string( 'thumb-wrapper' ); ?>>
                <div <?php $this->parent->print_render_attribute_string( 'thumb-carousel-wrapper' ) ?>>
                    <div class="swiper-wrapper">
						<?php echo implode( '', $this->thumb_slides ); //phpcs:ignore ?>
                    </div>
                </div>
            </div>
        </div>

		<?php
	}

	public function render_post() {
		$this->slider_thumbnails();
		?>
        <div class="swiper-slide">
            <div class="content">
                <div class="icon">
		            <?php Icons_Manager::render_icon( $this->get_instance_value( 'icon' ), [ 'aria-hidden' => 'true' ] ); ?>
                </div>
                <?php
                $this->render_category();
                $this->render_title();
                ?>
            </div>
            <div class="thumb"><?php $this->render_thumb(); ?></div>
        </div>
		<?php
	}

	public function slider_thumbnails() {

		$thumb = $this->get_post_thumbnail();

		$slide_html = '<div  class="swiper-slide"><div class="thumb-wrap">' . $thumb . '</div></div>';

		$this->thumb_slides[] = $slide_html;

		return $this->thumb_slides;
	}

	protected function get_post_thumbnail() {
		return 	get_the_post_thumbnail( null, 'post-thumbnail', '' );
	}
}
