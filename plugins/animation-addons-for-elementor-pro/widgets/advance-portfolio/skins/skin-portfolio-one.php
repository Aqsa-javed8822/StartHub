<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Icons_Manager;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Portfolio_One extends Skin_Portfolio_Base {

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
		return 'skin-portfolio-one';
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
		return __( 'Portfolio One', 'animation-addons-for-elementor-pro' );
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

		//Slider Controls Style
		$this->start_controls_section(
			'section_slider_options',
			[
				'label' => __( 'Slider', 'animation-addons-for-elementor-pro' ),
			]
		);
		$default = [
			'slides_to_show'       => '3',
			'space_between'        => 70,
			'navigation'           => '',
			'pagination'           => '',
		];
		$this->register_slider_controls( $default);
		$this->end_controls_section();

		// Layout Style
		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'image_position',
			[
				'label' => esc_html__( 'Image Position', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'column',
				'options' => [
					'column' => [
						'title' => esc_html__( 'Top', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-v-align-top',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Bottom', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .item' => 'flex-direction: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			'layout_align',
			[
				'label' => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'animation-addons-for-elementor-pro' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .item' => 'text-align: {{VALUE}};',
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

		// Date
        $this->register_date_controls();
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

		$this->parent->add_render_attribute(
			'wrapper',
			[
				'class'         => [ 'wcf__slider-wrapper wcf--advance-portfolio ' . $this->get_id() ],
				'data-settings' => json_encode( $slider_settings ), //phpcs:ignore
			]
		);
		?>
        <div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
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
		<?php
	}

	public function render_post() {
		?>
        <div class="swiper-slide">
            <article class="item">
                <div class="thumb">
					<?php $this->render_thumb(); ?>
                </div>
                <div class="content">
					<?php
					$this->render_title();
					$this->render_date();
					?>
                    <div class="icon">
						<?php Icons_Manager::render_icon( $this->get_instance_value( 'icon' ), [ 'aria-hidden' => 'true' ] ); ?>
                    </div>
                </div>
            </article>
        </div>
		<?php
	}

}
