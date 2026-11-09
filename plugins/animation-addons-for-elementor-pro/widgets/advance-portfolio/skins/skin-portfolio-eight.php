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

class Skin_Portfolio_Eight extends Skin_Portfolio_Base {

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
		return 'skin-portfolio-eight';
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
		return __( 'Portfolio Eight', 'animation-addons-for-elementor-pro' );
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

		$this->register_content_style_controls();

		// Meta
		$this->register_meta_controls();
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

		$this->add_responsive_control(
			'content_padding',
			[
				'label'      => esc_html__( 'Padding', 'animation-addons-for-elementor-pro' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors'  => [
					'{{WRAPPER}} .slide_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'content_justify_align',
			[
				'label'     => esc_html__( 'Justify Content', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start' => [
						'title' => esc_html__( 'Start', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-flex eicon-justify-start-v',
					],
					'center'     => [
						'title' => esc_html__( 'Center', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-flex eicon-justify-center-v',
					],
					'flex-end'   => [
						'title' => esc_html__( 'End', 'animation-addons-for-elementor-pro' ),
						'icon'  => 'eicon-flex eicon-justify-end-v',
					],
				],
				'default'   => '',
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .slide-parts .part .section' => 'justify-content: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'content_align',
			[
				'label'     => esc_html__( 'Alignment', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::CHOOSE,
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
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .slide_content' => 'text-align: {{VALUE}}; justify-content: {{VALUE}}',
				],
			]
		);


		$this->end_controls_section();
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
				'data-animation-settings' => json_encode( [ 'skin' => $this->get_id() ] ),
			]
		);
		?>
        <div <?php $this->parent->print_render_attribute_string( 'wrapper' ); ?>>
            <div class="slider_items">
	            <?php $this->render_posts(); ?>
            </div>
            <div id="main-<?php echo esc_attr( $this->get_id() ); ?>" class="slide-parts">
                <div class="content"></div>
            </div>
        </div>
		<?php
	}

	public function render_post( ) {
	    ?>
        <div class="slide_item">
            <div class="slide_image">
	            <?php $this->render_thumb(); ?>
            </div>
            <div class="slide_content">
	            <?php
	            $this->render_title();
	            $this->render_category();
	            ?>
            </div>
        </div>
        <?php
    }
}
