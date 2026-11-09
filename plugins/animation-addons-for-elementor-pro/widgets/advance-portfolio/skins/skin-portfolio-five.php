<?php
namespace WCFAddonsPro\Widgets\Skin;

use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Plugin;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Skin_Portfolio_Five extends Skin_Portfolio_Base {

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
		return 'skin-portfolio-five';
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
		return __( 'Portfolio Five', 'animation-addons-for-elementor-pro' );
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

		// Layout
        $this->register_layout_style_controls();

		// Content
        $this->register_content_controls();

		// Meta
		$this->register_meta_controls();

		// Date
		$this->register_date_controls();

		$this->register_section_animation_controls();

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

		$this->add_responsive_control(
			'layout_column_gap',
			[
				'label'      => esc_html__( 'Column Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .posts-list' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'layout_row_gap',
			[
				'label'      => esc_html__( 'Row Gap', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 500,
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .posts-list' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);


		// Item
		$this->add_control(
			'item_heading',
			[
				'label' => esc_html__( 'Item', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'item_border',
				'selector' => '{{WRAPPER}} .item',
			]
		);

		$this->add_responsive_control(
			'item_padding',
			[
				'label' => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem' ],
				'selectors' => [
					'{{WRAPPER}} .item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	// Register content style Controls
	protected function register_content_controls() {
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

		$this->end_controls_section();
	}


	// Register section title style Controls
	protected function register_section_animation_controls() {

		$this->start_controls_section(
			'section_portfolio_animation',
			[
				'label' => esc_html__( 'Hover Animations', 'animation-addons-for-elementor-pro' ),
			]
		);

		$this->add_control(
			'enable_apf_animation',
			[
				'label'        => esc_html__( 'Enable', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'render_type'  => 'template',
				'return_value' => 'yes',
			]
		);

		$this->end_controls_section();
	}

	protected function get_animation_settings() {
		return [
			'enable'     => $this->get_instance_value( 'enable_apf_animation' ),
			'skin'       => $this->get_id(),
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
            <div class="posts-list">
				<?php $this->render_posts(); ?>
            </div>
        </div>
		<?php
	}

	public function render_post() {
		?>
        <article class="item">
            <div class="thumb">
				<?php $this->render_thumb(); ?>
            </div>
            <div class="content">
		        <?php $this->render_title(); ?>
                <div class="meta">
                <?php
		        $this->render_category();
		        $this->render_date();
		        ?>
                </div>
            </div>
        </article>
		<?php
	}
}
