<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Portfolio_Nine extends Skin_Portfolio_Base {

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
		return 'skin-portfolio-nine';
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
		return __( 'Portfolio Nine', 'animation-addons-for-elementor-pro' );
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
			'section_title',
			[
				'label'     => esc_html__( 'Section Title', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::TEXT,
				'separator' => 'before',
				'default'   => esc_html__( 'WORK', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'section_title_tag',
			[
				'label' => esc_html__( 'Section Title Tag', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'h2',
				'options' => [
					'h1' => esc_html__( 'H1', 'animation-addons-for-elementor-pro' ),
					'h2' => esc_html__( 'H2', 'animation-addons-for-elementor-pro' ),
					'h3' => esc_html__( 'H3', 'animation-addons-for-elementor-pro' ),
					'h4' => esc_html__( 'H4', 'animation-addons-for-elementor-pro' ),
					'h5' => esc_html__( 'H5', 'animation-addons-for-elementor-pro' ),
					'h6' => esc_html__( 'H6', 'animation-addons-for-elementor-pro' ),
				],
			]
		);

		$this->add_control(
			'section_description',
			[
				'label'   => esc_html__( 'Section Description', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXTAREA,
				'rows'    => 5,
				'default' => esc_html__( 'View the full case study of our recent featured and awesome works that we created for our clients.', 'animation-addons-for-elementor-pro' ),
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

		$this->register_layout_style_controls();

		$this->register_section_title_style_controls();

		$this->register_section_desc_style_controls();

		$this->register_section_pagination_style_controls();

		$this->register_content_style_controls();

		// Date
		$this->register_date_controls();
	}

	// Register Layout Controls
	protected function register_layout_style_controls() {

		$this->start_controls_section(
			'section_layout_style',
			[
				'label' => esc_html__( 'Layout', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control( 'layout_direction',
            [
				'label'     => esc_html__( 'Direction', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row'            => [
						'title' => esc_html__( 'Row - horizontal', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-arrow-right',
					],
					'column'         => [
						'title' => esc_html__( 'Column - vertical', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-arrow-down',
					],
					'row-reverse'    => [
						'title' => esc_html__( 'Row - reversed', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-arrow-left',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Column - reversed', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-arrow-up',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skin-portfolio-nine' => 'flex-direction: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'layout_gap',
			[
				'label'      => esc_html__( 'Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .skin-portfolio-nine' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_header_margin',
			[
				'label'      => esc_html__( 'Header margin', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .widget_header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register content style Controls
	protected function register_content_style_controls() {

		$this->start_controls_section(
			'section_content_style',
			[
				'label' => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'     => 'content_background',
				'types'    => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .content',
			]
		);

		$this->add_responsive_control(
			'content_padding',
			[
				'label' => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'content_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_width',
			[
				'label'      => esc_html__( 'Width', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
			]
		);

		$this->end_controls_section();
	}

	// Register section title style Controls
	protected function register_section_title_style_controls() {

		$this->start_controls_section(
			'section_section_title_style',
			[
				'label' => esc_html__( 'Section Title', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_title_typography',
				'selector' => '{{WRAPPER}} .section-title',
			]
		);

		$this->add_control(
			'section_title_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'section_title_margin',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .section-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_title_align',
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
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .section-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register section description style Controls
	protected function register_section_desc_style_controls() {

		$this->start_controls_section(
			'section_section_desc_style',
			[
				'label' => esc_html__( 'Section Description', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'section_desc_typography',
				'selector' => '{{WRAPPER}} .section-desc',
			]
		);

		$this->add_control(
			'section_desc_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .section-desc' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'section_desc_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .section-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'section_desc_align',
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
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .section-desc' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register section pagination style Controls
	protected function register_section_pagination_style_controls() {

		$this->start_controls_section(
			'section_pagination_style',
			[
				'label' => esc_html__( 'Pagination', 'animation-addons-for-elementor-pro' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'pagination_current_heading',
			[
				'label' => esc_html__( 'Current', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_current_typography',
				'selector' => '{{WRAPPER}} .current',
			]
		);

		$this->add_control(
			'pagination_current_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .current' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'pagination_total_heading',
			[
				'label' => esc_html__( 'Total', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'pagination_total_typography',
				'selector' => '{{WRAPPER}} .total',
			]
		);

		$this->add_control(
			'pagination_total_color',
			[
				'label'     => esc_html__( 'Text Color', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .total' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'section_pagination_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'separator'  => 'before',
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .post-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function get_animation_settings() {
		return [
			'skin'           => $this->get_id(),
		];
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
		$this->parent->add_render_attribute(
			'wrapper',
			[
				'class' => [ 'wcf--advance-portfolio ' . $this->get_id() ],
				'data-animation-settings' => json_encode($this->get_animation_settings()),
			]
		);
		?>
        <div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
			<?php $this->render_widget_header(); ?>
            <div class="posts-list">
				<?php $this->render_posts(); ?>
            </div>
        </div>
		<?php
	}

	public function render_post() {
		?>
        <article class="item">
            <div class="thumb" data-speed="0.4" data-lag="0">
				<?php $this->render_thumb(); ?>
            </div>
            <div class="content">
		        <?php
		        $this->render_title();
		        $this->render_date();
		        ?>
            </div>
        </article>
		<?php
	}

	protected function render_section_title() {
		if ( empty( $this->get_instance_value( 'section_title' ) ) ) {
			return;
		}

		$title_tag = $this->get_instance_value( 'section_title_tag' );
		?>
        <<?php Utils::print_validated_html_tag( $title_tag ); ?> class="section-title">
		<?php echo wp_kses_post( $this->get_instance_value( 'section_title' ) ) ?>
        </<?php Utils::print_validated_html_tag( $title_tag ); ?>>
		<?php
	}

	protected function render_section_description() {
		if ( empty( $this->get_instance_value( 'section_description' ) ) ) {
			return;
		}
		?>
        <div class="section-desc">
			<?php echo wp_kses_post( $this->get_instance_value( 'section_description' ) ) ?>
        </div>
		<?php
	}

	protected function render_pagination() {
		?>
        <div class="post-pagination">
            <span class="current">1</span>
            <span class="total">07</span>
        </div>
		<?php
	}

	protected function render_widget_header(){
	    ?>
        <div class="widget_header">
	        <?php $this->render_section_title(); ?>
	        <?php $this->render_section_description(); ?>
            <?php $this->render_pagination(); ?>
        </div>
        <?php
	}
}
