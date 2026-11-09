<?php

namespace WCFAddonsPro\Base;

use WCFAddonsPro\Inc\AAE_Animation_Builder_Trait;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
class Animation_Builder
{

	use AAE_Animation_Builder_Trait;

	private static $instance = null;
	public $page_type        = null;
	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 */
	public static function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct()
	{
		// dashboard settings
		add_action('admin_menu', array($this, 'register_sub_menu'), 30);
		add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));

		add_action('wp_ajax_aae_save_anim_builder_settings', array($this, 'save_dashboard_settings'));
		add_action('admin_head', array($this, 'remove_notice_for_setting_page'));
		add_filter('show_admin_bar', array($this, 'hide_admin_bar_for_iframe'));
		add_action('wp', array($this, 'init'));
		add_action('animation/builder/before_enqueue_scripts', array($this, 'editor_script'));
		add_action('animation/builder/before_enqueue_styles', array($this, 'editor_style'));
		add_action('animation/builder/editor/footer', array($this, 'loader_footer_style'));
		add_filter('wcf_animation_builder_body_class', array($this, 'editor_classes'));
	}
	public function setPageType($obj)
	{
		$this->page_type = $obj;
	}
	/**
	 * Register submenu
	 *
	 * Adds a submenu under a parent menu.
	 */
	public function register_sub_menu()
	{
		add_submenu_page(
			'wcf_addons_page',
			esc_html__('Animation Builder', 'animation-addons-for-elementor-pro'),
			esc_html__('Animation Builder', 'animation-addons-for-elementor-pro'),
			'manage_options',
			'aae-anim-builder',
			array($this, 'setting_render')
		);
	}

	public function save_dashboard_settings()
	{

		check_ajax_referer('wcf_admin_nonce', 'nonce');

		if (! current_user_can('manage_options')) {
			wp_send_json_error(esc_html__('you are not allowed to do this action', 'animation-addons-for-elementor-pro'));
		}

		if (! isset($_POST['form_fields'])) {
			return;
		}

		if (! isset($_POST['setting_name'])) {
			return;
		}

		$form_data    = sanitize_text_field(wp_unslash($_POST['form_fields']));
		$setting_name = sanitize_text_field(wp_unslash($_POST['setting_name']));
		update_option($setting_name, $form_data);

		$data   = json_decode($form_data, true);
		$counts = $this->count_total_and_active_elements($data);

		$return_message = array(
			'message' => 'Settings Updated',
			'count'   => array(
				'total'  => $counts['total'],
				'active' => $counts['active'],
			),
		);
		wp_send_json($return_message);
	}

	/**
	 * Count total and active elements in the animation builder data.
	 *
	 * @param array $data The decoded form data.
	 * @return array ['total' => int, 'active' => int]
	 */
	private function count_total_and_active_elements($data)
	{
		$active_count = 0;
		$total_count  = 0;
		if (is_array($data) && isset($data['elements'])) {
			foreach ($data['elements'] as $group) {
				if (isset($group['elements']) && is_array($group['elements'])) {
					foreach ($group['elements'] as $element) {
						++$total_count;
						if (! empty($element['is_active'])) {
							++$active_count;
						}
					}
				}
			}
		}
		return array(
			'total'  => $total_count,
			'active' => $active_count,
		);
	}

	public function init()
	{

		$animation_builder = get_query_var('aae_builder');
		if ($animation_builder == '') {
			return;
		}

		add_filter('show_admin_bar', '__return_false');

		// Remove all WordPress actions
		remove_all_actions('wp_head');
		remove_all_actions('wp_print_styles');
		remove_all_actions('wp_print_head_scripts');
		remove_all_actions('wp_footer');

		// Handle `wp_head`
		add_action('wp_head', 'wp_enqueue_scripts', 1);
		add_action('wp_head', 'wp_print_styles', 8);
		add_action('wp_head', 'wp_print_head_scripts', 9);
		add_action('wp_head', 'wp_site_icon');
		add_action('wp_head', array($this, 'editor_head_trigger'), 30);

		// Handle `wp_footer`
		add_action('wp_footer', 'wp_print_footer_scripts', 20);
		add_action('wp_footer', 'wp_auth_check_html', 30);
		add_action('wp_footer', array($this, 'wp_footer'));

		// Handle `wp_enqueue_scripts`
		remove_all_actions('wp_enqueue_scripts');

		// Also remove all scripts hooked into after_wp_tiny_mce.
		remove_all_actions('after_wp_tiny_mce');
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 999999);
		add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'), 999999);
	}

	/**
	 * Render submenu
	 *
	 * Outputs the submenu content.
	 */
	public function setting_render()
	{

		echo '<div class="wrap">';
		echo '<div id="aae-anim-builder">Loading...</div>';
		echo '<div id="aae-anim-builder--toast"></div>';
		echo '</div>';
	}

	/**
	 * Enqueue admin scripts
	 *
	 * Loads necessary styles and scripts for the admin panel.
	 */
	public function admin_scripts()
	{

		if (
			isset($_GET['page']) &&
			$_GET['page'] === 'aae-anim-builder' &&
			strpos($_SERVER['PHP_SELF'], 'admin.php') !== false
		) {
			wp_enqueue_style(
				'aae-animation-builder-settings',
				WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder-settings/main.css'
			);


			wp_enqueue_script(
				'aae-animation-builder-settings',
				WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder-settings/main.js',
				array('react', 'react-dom', 'wp-element', 'wp-i18n'),
				WCF_ADDONS_PRO_VERSION,
				true
			);

			// Your updated code implementation.
			$config       = include plugin_dir_path(__FILE__) . '/configs/animation-builder-settings.php';
			$merge_config = array();
			$form_data    = get_option('aae_anim_builder_settings');
			$db_data      = json_decode($form_data, true);

			if (is_array($db_data)) {
				$merge_config['settings'] = $this->sync_with_config_structure($config['settings'], $db_data);
				$config                   = $merge_config;
			}

			$config['count']          = $this->count_total_and_active_elements($db_data);
			$config['count']['total'] = $this->count_total_and_active_elements($config['settings'])['total'];

			wp_localize_script(
				'aae-animation-builder-settings',
				'WCF_ADDONS_ADMIN',
				array(
					'ajaxurl' => admin_url('admin-ajax.php'),
					'nonce'   => wp_create_nonce('wcf_admin_nonce'),
					'config'  => apply_filters('animation-builder-admin-setting-config', $config),
				)
			);
		}
	}

	/**
	 * Deep sync function that respects parent-child relationships
	 * If a parent key doesn't exist in config, the entire branch is removed
	 * Only preserves data for keys that exist in current config structure
	 * Always uses config values for display fields (title, label, description, etc.)
	 * Only preserves user settings like is_active from database
	 *
	 * @param array $config The config structure to sync with.
	 * @param array $db_data The data from the database.
	 *
	 * @return array
	 */
	private function sync_with_config_structure($config, $db_data)
	{
		$result = array();

		$preserve_fields = array('is_active');

		$config_fields = array(
			'title',
			'label',
			'icon',
			'demo_url',
			'doc_url',
			'youtube_url',
			'description',
		);

		foreach ($config as $key => $config_value) {
			if (! isset($db_data[$key])) {
				$result[$key] = $config_value;
				continue;
			}

			$db_value = $db_data[$key];

			if (isset($config_value['elements']) && is_array($config_value['elements'])) {
				$result[$key] = $config_value;

				foreach ($config_fields as $field) {
					if (isset($config_value[$field])) {
						$result[$key][$field] = $config_value[$field];
					}
				}

				foreach ($preserve_fields as $field) {
					if (isset($db_value[$field])) {
						$result[$key][$field] = $db_value[$field];
					}
				}

				if (isset($db_value['elements']) && is_array($db_value['elements'])) {
					$result[$key]['elements'] = array();

					foreach ($config_value['elements'] as $element_key => $element_config) {
						if (! isset($db_value['elements'][$element_key])) {
							$result[$key]['elements'][$element_key] = $element_config;
							continue;
						}

						$element_db = $db_value['elements'][$element_key];

						if (isset($element_config['elements']) && is_array($element_config['elements'])) {
							$result[$key]['elements'][$element_key] = $element_config;
							foreach ($config_fields as $field) {
								if (isset($element_config[$field])) {
									$result[$key]['elements'][$element_key][$field] = $element_config[$field];
								}
							}

							foreach ($preserve_fields as $field) {
								if (isset($element_db[$field])) {
									$result[$key]['elements'][$element_key][$field] = $element_db[$field];
								}
							}

							if (isset($element_db['elements']) && is_array($element_db['elements'])) {
								$result[$key]['elements'][$element_key]['elements'] = array();

								foreach ($element_config['elements'] as $nested_key => $nested_config) {
									if (isset($element_db['elements'][$nested_key])) {
										$result[$key]['elements'][$element_key]['elements'][$nested_key] = $nested_config;
										foreach ($preserve_fields as $field) {
											if (isset($element_db['elements'][$nested_key][$field])) {
												$result[$key]['elements'][$element_key]['elements'][$nested_key][$field] =
													$element_db['elements'][$nested_key][$field];
											}
										}
									} else {
										$result[$key]['elements'][$element_key]['elements'][$nested_key] = $nested_config;
									}
								}
							}
						} else {
							$result[$key]['elements'][$element_key] = $element_config;

							foreach ($preserve_fields as $field) {
								if (isset($element_db[$field])) {
									$result[$key]['elements'][$element_key][$field] = $element_db[$field];
								}
							}
						}
					}
				} else {
					$result[$key]['elements'] = $config_value['elements'];
				}
			} elseif (is_array($config_value) && is_array($db_value)) {
				$result[$key] = $this->sync_with_config_structure($config_value, $db_value);
			} else {
				$result[$key] = $db_value;
			}
		}

		return $result;
	}

	public function remove_notice_for_setting_page()
	{
		if (
			isset($_GET['page']) &&
			($_GET['page'] === 'aae-anim-builder' || 'aae-page-importer' == $_GET['page']) &&
			strpos($_SERVER['PHP_SELF'], 'admin.php') !== false
		) {

			remove_all_actions('admin_notices');
			remove_all_actions('all_admin_notices');
			remove_all_actions('network_admin_notices');
			remove_all_actions('user_admin_notices');
			remove_all_actions('update_nag');
		}
	}

	function editor_classes($classes)
	{
		$class = array_merge($classes, array('wcfab2025', 'wcf-animation-builder-editor'));
		return $class;
	}

	public function loader_footer_style()
	{
		echo '<style>#wp-auth-check-wrap{display:none;}</style>';
	}

	/**
	 * Enqueue styles.
	 *
	 * Registers all the editor styles and enqueues them.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_styles()
	{
		/**
		 * Before editor enqueue styles.
		 *
		 * Fires before Animation editor styles are enqueued.
		 *
		 * @since 1.0.0
		 */
		do_action('animation/builder/before_enqueue_styles');
	}

	public function enqueue_scripts()
	{
		remove_action('wp_enqueue_scripts', array($this, __FUNCTION__), 999999);

		global $wp_styles, $wp_scripts;

		// Reset global variable
		$wp_styles  = new \WP_Styles(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		$wp_scripts = new \WP_Scripts(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited

		/**
		 * Before editor enqueue scripts.
		 *
		 * Fires before Animation Builder editor scripts are enqueued.
		 *
		 * @since 1.0.0
		 */
		do_action('animation/builder/before_enqueue_scripts');
	}


	/**
	 * WP footer.
	 *
	 * Prints Elementor editor with all the editor templates, and render controls,
	 * widgets and content elements.
	 *
	 * Fired by `wp_footer` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function wp_footer()
	{

		/**
		 * Elementor editor footer.
		 *
		 * Fires on Elementor editor before closing the body tag.
		 *
		 * Used to prints scripts or any other HTML before closing the body tag.
		 *
		 * @since 1.0.0
		 */
		do_action('animation/builder/editor/footer');
	}

	public function editor_head_trigger()
	{
		/**
		 * Animation Builder editor head.
		 *
		 * Fires on Animation Builder editor head tag.
		 *
		 * Used to prints scripts or any other data in the head tag.
		 *
		 * @since 1.0.0
		 */
		do_action('animation/builder/editor/wp_head');
	}
	function hide_admin_bar_for_iframe($show_admin_bar)
	{
		// Check if the 'iframe' query parameter is set
		if ($this->is_edit_mode()) {
			return false; // Disable admin bar
		}
		return $show_admin_bar;
	}


	public function is_edit_mode()
	{

		if (isset($_GET['action']) && $_GET['action'] == 'animation-builder') {
			return true;
		}

		return false;
	}

	public function editor_style()
	{
		wp_enqueue_style('wcf-pro-animation-builder', WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/main.css');
	}
	public function editor_script()
	{
		wp_enqueue_media();
		wp_enqueue_style( 'media-views' );
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script(
			'wcf-pro-animation-builder',
			WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/main.js',
			array(
				'react',
				'react-dom',
				'wp-dom-ready',
				'wp-element',
				'wp-hooks',
			),
			WCF_ADDONS_PRO_VERSION,
			true
		);
		$config          = include plugin_dir_path(__FILE__) . '/configs/animation-builder-assets.php'; // adjust path
		$active_elements = $this->get_active_element_keys();
		if (is_array($active_elements) && is_array($config)) {
			foreach ($active_elements as $key) {
				if (isset($config['js'][$key])) {
					$element = $config['js'][$key];
					wp_enqueue_script($key, $element['editorSrc'], $element['editorDeps'], WCF_ADDONS_PRO_VERSION, true);
				}
			}
		}

		$url = isset($_GET['builder_url']) ? $_GET['builder_url'] : home_url('/');

		$final_url = add_query_arg(
			array(
				'action' => 'animation-builder',
			),
			$url
		);

		$config = include plugin_dir_path(__FILE__) . '../extensions/configs/animation-builder-device.php'; // adjust path

		// sanitize / normalize
		$devices = array_map(
			function ($d) {
				return array(
					'key'        => sanitize_key($d['key']),
					'title'      => wp_strip_all_tags($d['title']),
					'viewWidth'  => esc_attr($d['viewWidth']),
					'mediaQuery' => wp_strip_all_tags($d['mediaQuery']),
				);
			},
			$config
		);

		$localize_data = apply_filters(
			'wcf/animation/builder/editor/data',
			array(
				'ajaxurl'       => admin_url('admin-ajax.php'),
				'nonce'         => wp_create_nonce('wcf_admin_nonce'),
				'id'            => get_the_id(),
				'iframe_url'    => esc_url($final_url),
				'debug'         => defined('WP_DEBUG') && WP_DEBUG ? true : false,
				'device_config' => $devices,
			)
		);

		wp_localize_script('wcf-pro-animation-builder', 'WCF_ADDONS_ANIMATION_BUILDER', $localize_data);
	}
}
