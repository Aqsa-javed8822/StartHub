<?php
/**
 * Dynamic Tags Extension
 *
 * Integrates a comprehensive dynamic tags system including
 * ACF, Core WordPress tags (Post, Author, Site, Archive, Comments)
 *
 * @package WCFAddonsPro
 * @since 1.0.0
 */

namespace WCFAddonsPro\Extensions;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Dynamic Tags Extension.
 *
 * @since 1.0.0
 * @package WCFAddonsPro\Extensions
 */
class Dynamic_Tags {

	/**
	 * Singleton instance
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Get instance
	 *
	 * Ensures only one instance of the class is loaded.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * Registers hooks and actions.
	 */
	public function __construct() {
		// Load the new comprehensive dynamic tags module.
		$this->load_new_dynamic_tags_module();

		// Keep existing hooks for ACF and custom tags.
		add_action( 'elementor/dynamic_tags/register', array( $this, 'aaepro_register_dynamic_tag_group' ), 20 );
		add_action( 'elementor/dynamic_tags/register', array( $this, 'aaepro_register__dynamic_tag' ), 22 );
		add_action( 'wp_ajax_wcf_get_terms_by_taxonomy', array( $this, 'wcf_get_terms_by_taxonomy' ) );
		add_action( 'wp_ajax_nopriv_wcf_get_terms_by_taxonomy', array( $this, 'wcf_get_terms_by_taxonomy' ) );
	}

	/**
	 * Load a new comprehensive dynamic tags module.
	 *
	 * Includes Post, Author, Site, Archive, and Comments tags.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function load_new_dynamic_tags_module() {
		$module_file = WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/dynamic-tags-module.php';

		if ( file_exists( $module_file ) ) {
			require_once $module_file;
		}
	}

	/**
	 * Get terms by taxonomy.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wcf_get_terms_by_taxonomy() {
		if ( ! isset( $_POST['taxonomy'] ) ) {
			wp_send_json_error( __( 'Invalid Taxonomy', 'animation-addons-for-elementor-pro' ) );
		}

		$taxonomy = sanitize_text_field( wp_unslash( $_POST['taxonomy'] ) );
		$terms    = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'hide_empty' => false,
			)
		);

		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			wp_send_json_success( array() );
		}

		$term_options = array();
		foreach ( $terms as $term ) {
			$term_options[] = array(
				'id'   => $term->term_id,
				'name' => $term->name,
			);
		}

		wp_send_json_success( $term_options );
	}

	/**
	 * Register ACF tags.
	 *
	 * @param object $dynamic_tags_manager Dynamic tags manager.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function aaepro_register__dynamic_tag( $dynamic_tags_manager ) {
		// Note: trait-postid.php is already loaded by dynamic-tags-module.php.
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-text.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-url.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-color.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-file.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-image.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-number.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-gallery.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-taxonomy.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-user.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-link.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/acf-datetime.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/core-stats-count.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/dynamic-tags/wp-post-meta-type.php';

		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_Text() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_URL() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_Color() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_File() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_Number() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_Image() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_Gallery() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_Taxonomy() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_User() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_Link() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_ACF_DateTime() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_Core_Stats_Count() );
		$dynamic_tags_manager->register( new \WCFAddonsPro\Base\Tags\AAE_Post_Meta() );
	}

	/**
	 * Register AAE dynamic tag group.
	 *
	 * @param object $dynamicc Dynamic tags manager.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function aaepro_register_dynamic_tag_group( $dynamicc ) {
		$dynamicc->register_group(
			'aae',
			array(
				'title' => esc_html__( 'AAE', 'animation-addons-for-elementor-pro' ),
			)
		);

		$dynamicc->register_group(
			'aae-posts',
			array(
				'title' => esc_html__( 'AAE Posts', 'animation-addons-for-elementor-pro' ),
			)
		);
	}
}

// Initialize the class.
Dynamic_Tags::instance();
