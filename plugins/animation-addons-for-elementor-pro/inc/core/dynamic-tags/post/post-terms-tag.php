<?php
/**
 * Post Terms Dynamic Tag
 *
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Base\Tags\Post;

use WCFAddonsPro\Base\Tags\Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post-Terms Dynamic Tag.
 *
 * @since 1.0.0
 * @package WCFAddonsPro\Base\Tags\Post
 */
class Post_Terms_Tag extends Tag_Base {

	/**
	 * Unique tag name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'aae-post-terms';
	}

	/**
	 * Tag title for Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Post Terms', 'animation-addons-for-elementor-pro' );
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
		return array( TagsModule::TEXT_CATEGORY );
	}

	/**
	 * Register controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->add_control(
			'taxonomy',
			array(
				'label'       => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
				'type'        => Controls_Manager::SELECT2,
				'label_block' => true,
				'default'     => 'category',
				'options'     => $this->get_taxonomies(),
			)
		);

		$this->add_control(
			'taxonomy_show_count',
			array(
				'label'   => esc_html__( 'Terms to Show', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::NUMBER,
				'min'     => 1,
				'max'     => 100,
				'step'    => 1,
				'default' => 1,
			)
		);

		$this->add_control(
			'separator',
			array(
				'label'   => esc_html__( 'Separator', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::TEXT,
				'default' => ', ',
			)
		);

		$this->add_control(
			'link',
			array(
				'label'        => esc_html__( 'Link', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			)
		);
	}

	/**
	 * Build taxonomy options for selector.
	 *
	 * @return array<string,string> Map of taxonomy => label
	 */
	private function get_taxonomies() {
		$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
		$options    = array();

		foreach ( $taxonomies as $taxonomy ) {
			$options[ $taxonomy->name ] = $taxonomy->label;
		}

		return $options;
	}

	/**
	 * Render taxonomy terms with optional links.
	 *
	 * @return void
	 */
	public function render() {
		$post_id  = $this->get_post_id();
		$settings = $this->get_settings();

		$taxonomy   = $settings['taxonomy'];
		$separator  = $settings['separator'];
		$show_terms = $settings['taxonomy_show_count'];

		$terms = get_the_terms( $post_id, $taxonomy );

		if ( ! $terms || is_wp_error( $terms ) ) {
			return;
		}

		if ( $show_terms > 0 ) {
			$terms = array_slice( $terms, 0, $show_terms );
		}

		$terms_array = array();

		foreach ( $terms as $term ) {
			if ( 'yes' === $settings['link'] ) {
				$terms_array[] = sprintf( '<a href="%s">%s</a>', esc_url( get_term_link( $term ) ), esc_html( $term->name ) );
			} else {
				$terms_array[] = esc_html( $term->name );
			}
		}

		echo implode( $separator, $terms_array ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
