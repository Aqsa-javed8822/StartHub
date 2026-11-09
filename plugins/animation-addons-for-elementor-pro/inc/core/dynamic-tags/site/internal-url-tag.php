<?php
/**
 * Internal URL Dynamic Tag
 *
 * Creates links to internal WordPress content (posts, taxonomies, attachments, authors).
 * Uses custom SELECT2 control with AJAX autocomplete (no Elementor Pro dependency).
 *
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Base\Tags\Site;

use WCFAddonsPro\Base\Tags\Data_Tag_Base;
use Elementor\Controls_Manager;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use WCFAddonsPro\Core\DynamicTags\Controls\Select2_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Internal URL Dynamic Tag.
 *
 * @since 1.0.0
 * @package WCFAddonsPro\Base\Tags\Site
 */
class Internal_URL_Tag extends Data_Tag_Base {

	/**
	 * Unique tag name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'aae-internal-url';
	}

	/**
	 * Tag title for Elementor UI.
	 *
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Internal URL', 'animation-addons-for-elementor-pro' );
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
		return array( TagsModule::URL_CATEGORY );
	}

	/**
	 * Get panel template.
	 *
	 * @return string
	 */
	public function get_panel_template() {
		return ' ({{ url }})';
	}

	/**
	 * Get internal URL based on type.
	 *
	 * @param array $options Options (unused).
	 * @return string
	 */
	protected function get_value( array $options = array() ) {
		$settings = $this->get_settings();
		$type     = isset( $settings['type'] ) ? $settings['type'] : '';
		$url      = '';

		if ( 'post' === $type && ! empty( $settings['post_id'] ) ) {
			$url = get_permalink( (int) $settings['post_id'] );
		} elseif ( 'taxonomy' === $type && ! empty( $settings['taxonomy_id'] ) ) {
			$url = get_term_link( (int) $settings['taxonomy_id'] );
		} elseif ( 'attachment' === $type && ! empty( $settings['attachment_id'] ) ) {
			$url = get_attachment_link( (int) $settings['attachment_id'] );
		} elseif ( 'author' === $type && ! empty( $settings['author_id'] ) ) {
			$url = get_author_posts_url( (int) $settings['author_id'] );
		}

		if ( ! is_wp_error( $url ) ) {
			return esc_url( $url );
		}

		return '';
	}

	/**
	 * Register tag controls.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->add_control(
			'type',
			array(
				'label'   => esc_html__( 'Type', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post',
				'options' => array(
					'post'       => esc_html__( 'Content', 'animation-addons-for-elementor-pro' ),
					'taxonomy'   => esc_html__( 'Taxonomy', 'animation-addons-for-elementor-pro' ),
					'attachment' => esc_html__( 'Media', 'animation-addons-for-elementor-pro' ),
					'author'     => esc_html__( 'Author', 'animation-addons-for-elementor-pro' ),
				),
			)
		);

		// Post selector with AJAX autocomplete.
		$this->add_control(
			'post_id',
			array(
				'label'       => esc_html__( 'Search & Select', 'animation-addons-for-elementor-pro' ),
				'type'        => Select2_Control::CONTROL_ID,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type to search...', 'animation-addons-for-elementor-pro' ),
				'ajax'        => array(
					'action' => 'aae_dt_get_posts',
					'params' => array(
						'post_type' => 'any',
					),
				),
				'condition'   => array(
					'type' => 'post',
				),
			)
		);

		// Taxonomy selector with AJAX autocomplete.
		$this->add_control(
			'taxonomy_id',
			array(
				'label'       => esc_html__( 'Search & Select', 'animation-addons-for-elementor-pro' ),
				'type'        => Select2_Control::CONTROL_ID,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type to search...', 'animation-addons-for-elementor-pro' ),
				'ajax'        => array(
					'action' => 'aae_dt_get_terms',
					'params' => array(
						'taxonomy' => 'category',
					),
				),
				'condition'   => array(
					'type' => 'taxonomy',
				),
			)
		);

		// Attachment selector with AJAX autocomplete.
		$this->add_control(
			'attachment_id',
			array(
				'label'       => esc_html__( 'Search & Select', 'animation-addons-for-elementor-pro' ),
				'type'        => Select2_Control::CONTROL_ID,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type to search...', 'animation-addons-for-elementor-pro' ),
				'ajax'        => array(
					'action' => 'aae_dt_get_attachments',
				),
				'condition'   => array(
					'type' => 'attachment',
				),
			)
		);

		// Author selector with AJAX autocomplete.
		$this->add_control(
			'author_id',
			array(
				'label'       => esc_html__( 'Search & Select', 'animation-addons-for-elementor-pro' ),
				'type'        => Select2_Control::CONTROL_ID,
				'label_block' => true,
				'placeholder' => esc_html__( 'Type to search...', 'animation-addons-for-elementor-pro' ),
				'ajax'        => array(
					'action' => 'aae_dt_get_authors',
				),
				'condition'   => array(
					'type' => 'author',
				),
			)
		);
	}
}
