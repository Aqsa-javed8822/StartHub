<?php
namespace WCFAddonsPro\Widgets;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Loop Carousel Widget
 *
 * Displays posts in a carousel layout using loop item templates
 */
class Loop_Carousel extends Loop_Grid {
	/**
	 * Get a widget name.
	 *
	 * @since 2.4.16
	 * @return string
	 */
	public function get_name() {
		return 'aae--loop-carousel';
	}

	/**
	 * Get widget title.
	 *
	 * @since 2.4.16
	 * @return string
	 */
	public function get_title() {
		return __( 'Loop Carousel', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Get widget icon.
	 *
	 * @since 2.4.16
	 * @return string
	 */
	public function get_icon() {
		return 'wcf eicon-posts-carousel';
	}

	/**
	 * Get widget categories.
	 *
	 * @since 2.4.16
	 * @return array
	 */
	public function get_categories() {
		return array( 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Get widget keywords.
	 *
	 * @since 2.4.16
	 * @return array
	 */
	public function get_keywords() {
		return array( 'loop', 'grid', 'posts', 'dynamic', 'template', 'listing', 'archive', 'builder', 'custom', 'elementor', 'pro', 'slider' );
	}

	/**
	 * Get style dependencies.
	 *
	 * @since 2.4.16
	 * @return array
	 */
	public function get_style_depends() {
		return array();
	}

	/**
	 * Get script dependencies.
	 *
	 * @since 2.4.16
	 * @return array
	 */
	public function get_script_depends() {
		return array();
	}

	/**
	 * Register widget controls.
	 *
	 * @since 2.4.16
	 * @return void
	 */
	protected function register_controls() {}

	/**
	 * Render widget output.
	 *
	 * @since 2.4.16
	 * @return void
	 */
	protected function render() {}
}
