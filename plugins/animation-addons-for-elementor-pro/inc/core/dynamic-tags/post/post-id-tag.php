<?php
/**
 * Post ID Dynamic Tag
 *
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Base\Tags\Post;

use WCFAddonsPro\Base\Tags\Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post ID Dynamic Tag.
 *
 * @since 1.0.0
 * @package WCFAddonsPro\Base\Tags\Post
 */
class Post_ID_Tag extends Tag_Base {

	/**
	 * Unique tag name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'aae-post-id';
	}

	/**
	 * Tag title for Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post ID', 'animation-addons-for-elementor-pro' );
	}

	/**
	 * Tag group(s).
	 *
	 * @return array<int,string>
	 */
	public function get_group() {
		return array( 'aae-posts' );
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
		);
	}

	/**
	 * Render the numeric post ID.
	 *
	 * @return void
	 */
	public function render() {
		echo absint( $this->get_post_id() );
	}
}
