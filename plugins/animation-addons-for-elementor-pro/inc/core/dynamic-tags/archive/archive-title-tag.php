<?php
/**
 * Archive Title Dynamic Tag
 *
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Base\Tags\Archive;

use WCFAddonsPro\Base\Tags\Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Archive Title Dynamic Tag.
 *
 * @since 1.0.0
 * @package WCFAddonsPro\Base\Tags\Archive
 */
class Archive_Title_Tag extends Tag_Base {

	/**
	 * Unique tag name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'aae-archive-title';
	}

	/**
	 * Tag title for Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Archive Title', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Tag group(s).
	 *
	 * @return array<int,string>
	 */
	public function get_group() {
		return array( 'aae-archive' );
	}

	/**
	 * Supported categories.
	 *
	 * @return array<int,string>
	 */
	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	/**
	 * Render archive title.
	 *
	 * @return void
	 */
	public function render() {
		if (\Elementor\Plugin::$instance->editor->is_edit_mode()) {
			echo 'Archive Title Preview';
			return;
		}
		echo wp_kses_post( get_the_archive_title() );
	}
}
