<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Portfolio_two extends Skin_Portfolio_Base {

	protected $content_slides = [];

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
		return 'skin-portfolio-two';
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
		return __( 'Portfolio Two', 'animation-addons-for-elementor-pro' );
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
			'sliders_direction',
			[
				'label'     => esc_html__( 'Direction', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'         => [
						'title' => esc_html__( 'Row - horizontal', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-arrow-right',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Row - reversed', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-arrow-left',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .wcf--advance-portfolio' => 'flex-direction: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_align',
			[
				'label'     => esc_html__( 'Align Items', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'default'   => '',
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-flex eicon-align-start-v',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-flex eicon-align-center-v',
					],
					'flex-end'   => [
						'title' => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-flex eicon-align-end-v',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--advance-portfolio' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'sliders_gap',
			[
				'label' => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type' =>Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 300,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .wcf--advance-portfolio' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		// Slider wrapper
		$this->add_control(
			'slider_wrapper_heading',
			[
				'label' => esc_html__( 'Slider Wrapper', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'slider_wrapper_width',
			[
				'label'          => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .main-slider' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_wrapper_max_width',
			[
				'label'          => esc_html__( 'Max Width', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .main-slider' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'slider_wrapper_max_height',
			[
				'label'      => esc_html__( 'Max Height', 'animation-addons-for-elementor-pro' ),
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
					'{{WRAPPER}} .main-slider .wcf__slider' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

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

		// Content
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'content_height',
			[
				'label'          => esc_html__( 'Height', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px' ],
				'range'          => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .wcf__thumb-slider-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .wcf__thumb_slider .swiper-slide' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();

		// Date
		$this->register_date_controls();
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
			'effect'     => 'cards',
			'grab_cursor'     => 'yes',
			//navigation
			'navigation'           => 'yes',
			//pagination
			'pagination'           => 'yes',
			'pagination_type'      => 'bullets',
			'direction'            => 'ltr',
		];

		$default = array_merge(  $default, $default_value );


		$this->add_control(
			'effect',
			[
				'label'   => esc_html__( 'Effect', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => $default['effect'],
				'options' => [
					'cards'    => esc_html__( 'Cards', 'animation-addons-for-elementor-pro' ),
					'flip'     => esc_html__( 'Flip', 'animation-addons-for-elementor-pro' ),
					'creative' => esc_html__( 'Creative', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'grab_cursor',
			[
				'label'        => esc_html__( 'grab Cursor', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Show', 'animation-addons-for-elementor-pro' ),
				'label_off'    => esc_html__( 'Hide', 'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => $default['grab_cursor'],
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

		$this->add_responsive_control(
			'slider_max_width',
			[
				'label'          => esc_html__( 'Max Width', 'animation-addons-for-elementor-pro' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range'          => [
					'%'  => [
						'min' => 1,
						'max' => 100,
					],
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
					'vw' => [
						'min' => 1,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .wcf__slider' => 'max-width: {{SIZE}}{{UNIT}};',
				],
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
			'effect'      => $this->get_instance_value( 'effect' ),
			'grab_cursor' => 'true' === $this->get_instance_value( 'grab_cursor' ),
			'speed'       => 1000,
		];

		if ( 'creative' === $this->get_instance_value( 'effect' ) ) {
			$slider_settings['creativeEffect'] = [
				'prev' => [
					'shadow'    => true,
					'translate' => [ "-125%", 0, - 800 ],
					'rotate'    => [ 0, 0, - 90 ],
				],
				'next' => [
					'shadow'    => true,
					'translate' => [ "125%", 0, - 800 ],
					'rotate'    => [ 0, 0, 90 ],
				]
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
			'spaceBetween'        => 10,
			'slidesPerView'       => 1,
			'speed'               => 1000,
			'freeMode'            => true,
			'watchSlidesProgress' => true,
			'direction'           => "vertical",

		];

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
	                    <?php echo implode( '', $this->content_slides ); //phpcs:ignore ?>
                    </div>
                </div>
            </div>
        </div>

		<?php
	}

	public function render_post() {
		$this->slider_content();
		?>
        <div class="swiper-slide">
            <div class="thumb"><?php $this->render_thumb(); ?></div>
        </div>
		<?php
	}

	public function slider_content() {

		$title = $this->get_post_title();
		$date = $this->get_post_date();

		$slide_html = '<div  class="swiper-slide"><div class="content">' . $title . $date. '</div></div>';

		$this->content_slides[] = $slide_html;

		return $this->content_slides;
	}

	protected function get_post_title() {
		$title_tag  = $this->parent->get_settings( 'title_tag' );

		$title_html = '<' . $title_tag . ' class="title">';
		$title_html .= '<a href="' . esc_url( get_the_permalink() ) . '">' . get_the_title();
		$title_html .= '</a></' . $title_tag . '>';

		return $title_html;
	}

	protected function get_post_date() {
		return '<div class="date">' . esc_html( get_the_date() ) . '</div>';
	}
}
