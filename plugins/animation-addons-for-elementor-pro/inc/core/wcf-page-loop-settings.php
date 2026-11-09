<?php
/**
 * WCF_Page_Loop_Settings
 */

namespace WCFAddonsPro\Core;

use Elementor\Core\DocumentTypes\PageBase;
use Elementor\Controls_Manager;
use Elementor\Core\Settings\Manager as Settings_Manager;

defined( 'ABSPATH' ) || exit;

class WCF_Page_Loop_Settings {

	public static function init() {

		add_action(
			'elementor/documents/register_controls',
			[ __CLASS__, 'register_controls' ],
			21
		);

		add_filter(
			'wcf-addons/js/data',
			[ __CLASS__, 'push_settings_to_frontend' ],
			20
		);
		
		add_filter(
			'wcf-addons-editor/js/data',
			[ __CLASS__, 'push_settings_to_frontend' ],
			20
		);

		add_action(
			'wp_ajax_aae_get_posts_by_type',
			[ __CLASS__, 'ajax_get_posts_by_type' ]
		);		
	}

	public static function register_controls( $document ) {

		if ( ! $document instanceof PageBase ) {
			return;
		}


		$document->start_controls_section(
			'aae_page_loop_preview',
			[

				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Preview Settings', 'animation-addons-for-elementor-pro' ) ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);

		$document->add_control(
			'aae_loop_page_source',
			[
				'label'   => __( 'Loop Source', 'animation-addons-for-elementor-pro' ),
				'type'    => Controls_Manager::SELECT,
				'default' => '',
				'options' => self::get_post_type_options(),
			]
		);

		$document->add_control(
			'aae_loop_page_post',
			[
				'label'    => __( 'Source Item', 'animation-addons-for-elementor-pro' ),
				'type'     => Controls_Manager::SELECT2,
				'multiple' => false,
				'options'  => self::get_posts_by_type(),	
			]
		);

		$document->add_control(
			'aae_apply_loop_preview',
			[
				'type' => Controls_Manager::BUTTON,
				'label' => esc_html__( 'Apply & Preview', 'animation-addons-for-elementor-pro' ),
				'label_block' => true,
				'show_label' => false,
				'text' => esc_html__( 'Apply & Preview', 'animation-addons-for-elementor-pro' ),
				'event' => 'aaeelementorThemeBuilder:ApplyPreview',
			]
		);

		$document->end_controls_section();
	}

	private static function get_post_type_options() {

		$options = [
			'post' => __( 'Posts', 'animation-addons-for-elementor-pro' ),
			'page' => __( 'Pages', 'animation-addons-for-elementor-pro' ),
		];

		$post_types = get_post_types(
			[
				'public'   => true,
				'_builtin' => false,
			],
			'objects'
		);

		foreach ( $post_types as $type ) {
			$options[ $type->name ] = $type->label;
		}

		return $options;
	}

	public static function get_posts_by_type( ) {
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );		
		$page_settings_model = $page_settings_manager->get_model( get_queried_object_id() );
		$post_type = $page_settings_model->get_settings( 'aae_loop_page_source' );	
		
		$query = new \WP_Query(
			[
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => 30,		
				'no_found_rows' => true, 
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,		
			]
		);

		$results = [];

		foreach ( $query->posts as $post ) {
			$results[$post->ID] = $post->post_title .  ' (#' . $post->ID . ')';
		}

		return $results;
	}

	public static function ajax_get_posts_by_type() {

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error();
		}

		$post_type = sanitize_text_field( $_GET['aae_loop_page_source'] ?? 'post' );
		$search    = sanitize_text_field( $_GET['q'] ?? '' );

		$query = new \WP_Query(
			[
				'post_type'      => $post_type,
				'post_status'    => 'publish',
				'posts_per_page' => 20,
				's'              => $search,
				'no_found_rows' => true, 
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
			]
		);

		$results = [];

		foreach ( $query->posts as $post ) {
			$results[] = [
				'id'   => $post->ID,
				'text' => $post->post_title .  ' (#' . $post->ID . ')',
			];
		}

		wp_send_json(
			[
				'results' => $results,
			]
		);
	}

	public static function push_settings_to_frontend( $data ) {

		$manager = Settings_Manager:: get_settings_managers( 'page' );
		$model   = $manager->get_model( get_queried_object_id() );

		if ( ! $model ) {
			return $data;
		}

		$data['aae_loop_source'] = $model->get_settings( 'aae_loop_page_source' );
		$data['aae_loop_post']   = absint(
			$model->get_settings( 'aae_loop_page_post' )
		);

		return $data;
	}
	
}

WCF_Page_Loop_Settings::init();