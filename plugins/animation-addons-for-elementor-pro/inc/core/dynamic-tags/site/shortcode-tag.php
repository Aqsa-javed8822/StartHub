<?php
/**
 * Shortcode Dynamic Tag
 *
 * Allows executing WordPress shortcodes as dynamic content.
 *
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Base\Tags\Site;

use WCFAddonsPro\Base\Tags\Tag_Base;
use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Shortcode Dynamic Tag.
 *
 * @since 1.0.0
 * @package WCFAddonsPro\Base\Tags\Site
 */
class Shortcode_Tag extends Tag_Base {

	/**
	 * Unique tag name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'aae-shortcode';
	}

	/**
	 * Tag title for Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Shortcode', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Tag group(s).
	 *
	 * @return array<int,string>
	 */
	public function get_group() {
		return array( 'aae-site' );
	}

	/**
	 * Supported categories.
	 *
	 * @return array<int,string>
	 */
	public function get_categories() {
		return array(
			TagsModule::TEXT_CATEGORY,
			TagsModule::NUMBER_CATEGORY,
			TagsModule::URL_CATEGORY,
			TagsModule::POST_META_CATEGORY,
			TagsModule::DATETIME_CATEGORY,
		);
	}

	/**
	 * Register tag controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->add_control(
			'shortcode',
			array(
				'label' => esc_html__( 'Shortcode', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::TEXTAREA,
				'ai'    => array(
					'active' => false,
				),
			)
		);
	}

	/**
	 * Render shortcode output.
	 *
	 * @return void
	 */
	public function render() {
		$settings = $this->get_settings();

		if ( empty( $settings['shortcode'] ) ) {
			return;
		}

		$shortcode_string = $settings['shortcode'];
		$value            = do_shortcode( $shortcode_string );

		$should_escape = true;

		/**
		 * Should escape shortcodes.
		 *
		 * By default, shortcodes in dynamic tags are escaped. This hook allows developers
		 * to avoid shortcodes from being escaped. Defaults to true.
		 *
		 * @since 1.0.0
		 *
		 * @param bool $should_escape Whether to escape shortcodes in dynamic tags.
		 */
		$should_escape = apply_filters( 'wcf_addons_pro_dynamic_tags_shortcode_should_escape', $should_escape );

		if ( $should_escape ) {
			$value = wp_kses_post( $value );
		}

		// PHPCS - the variable $value is safe.
		echo $value; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
