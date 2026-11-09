<?php

namespace WCFAddonsPro\Extensions;

use WCFAddonsPro\Inc\AAE_Animation_Builder_Trait;

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

if (defined('WCF_ANIMATION_BUILDER_VERSION')) {
	return;
}

if (!class_exists('\WCFAddonsPro\Base\Animation_Builder')) {
	include_once WCF_ADDONS_PRO_PATH . 'inc/core/animation-editor.php';
}

if (!class_exists('\WCFAddonsPro\Base\Animation_Builder_Page_Type')) {
	include_once WCF_ADDONS_PRO_PATH . 'inc/core/anim-builder-page-type.php';
}

class Wcf_Animation_Builder
{
	use AAE_Animation_Builder_Trait;
	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $instance = null;
	public $page_type = null;
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

		add_action('admin_bar_menu', [$this, 'button_interface'], 500);
		add_action('init', [$this, 'custom_rewrite_rules']);
		add_filter('query_vars', [$this, 'custom_query_vars']);
		add_action('template_redirect', [$this, 'animation_builder_template_redirect']);
		add_action('wp_enqueue_scripts', [$this, 'config_enqueue_script'], 60);
		add_action('wp_footer', [$this, 'html_selector']);
		add_action('wp_ajax_wcf_anim_builder_configs_store', [$this, 'configs_store']);
		add_action('wp_ajax_wcf_anim_builder_configs_delete', [$this, 'configs_delete']);
		add_filter('page_row_actions', [$this, 'add_custom_quick_link'], 10, 2);
		add_filter('post_row_actions', [$this, 'add_custom_quick_link'], 10, 2);
		//editor 
		$this->page_type = \WCFAddonsPro\Base\Animation_Builder_Page_Type::instance();
		$builder = \WCFAddonsPro\Base\Animation_Builder::instance();
		$builder->setPageType($this->page_type);
	}

	function add_custom_quick_link($actions, $post)
	{
		// Ensure this only applies to pages , posts
		if ($post->post_type === 'page' || $post->post_type === 'post') {
			$permalink_structure = get_option('permalink_structure');

			if ($permalink_structure) {
				$animation_builder_url = home_url('/aae-animation-builder/');
			} else {
				$animation_builder_url = home_url('/');
				$animation_builder_url = add_query_arg(array(
					'aae_builder' => 1,
				), $animation_builder_url);
			}

			$editor_url            = apply_filters('wcf/animation/builder/editor/url', add_query_arg(array(
				'builder_url' => get_the_permalink($post->ID),
			), $animation_builder_url));

			$actions['wcfanimb_action'] = '<a target="_blank" href="' . esc_url($editor_url) . '">' . esc_html__('Build Animation', 'animation-addons-for-elementor-pro') . '</a>';
		}

		return $actions;
	}
	public function configs_store()
	{
		// Verify nonce
		check_ajax_referer('wcf-admin-preview-nonce', 'wcf_nonce');

		// Check user permissions
		if (!current_user_can('manage_options')) {
			wp_send_json_error(['msg' => esc_html__('Unauthorized access', 'animation-addons-for-elementor-pro')], 403);
		}

		// Get and sanitize the JSON data
		$pageTypeConfigs = isset($_POST['pageTypeConfigs']) ? wp_unslash($_POST['pageTypeConfigs']) : '';
		$animationConfigs = isset($_POST['animationConfigs']) ? wp_unslash($_POST['animationConfigs']) : '';

		// Validate JSON structure
		if (empty($pageTypeConfigs) || empty($animationConfigs)) {
			wp_send_json_error(['msg' => esc_html__('Missing configuration data', 'animation-addons-for-elementor-pro')], 400);
		}

		// Decode JSON and check for errors
		$pageTypeConfigs = json_decode($pageTypeConfigs, true);
		$animationConfigs = json_decode($animationConfigs, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			wp_send_json_error(['msg' => esc_html__('Invalid JSON format', 'animation-addons-for-elementor-pro')], 400);
		}

		// Further validation on the decoded data to ensure expected structure
		if (!is_array($pageTypeConfigs) || !is_array($animationConfigs)) {
			wp_send_json_error(['msg' => esc_html__('Invalid configuration structure', 'animation-addons-for-elementor-pro')], 400);
		}

		// Save configurations
		$this->page_type->saveConfig($pageTypeConfigs, $animationConfigs);
		wp_send_json_success(['msg' => esc_html__('Configurations updated successfully', 'animation-addons-for-elementor-pro'), 'configs' => $animationConfigs]);
	}
	public function configs_delete()
	{
		// Verify nonce
		check_ajax_referer('wcf-admin-preview-nonce', 'wcf_nonce');

		// Check user permissions
		if (!current_user_can('manage_options')) {
			wp_send_json_error(['msg' => esc_html__('Unauthorized access', 'animation-addons-for-elementor-pro')], 403);
		}

		// Get and sanitize the JSON data
		$pageTypeConfigs = isset($_POST['pageTypeConfigs']) ? wp_unslash($_POST['pageTypeConfigs']) : '';

		// Validate JSON structure
		if (empty($pageTypeConfigs)) {
			wp_send_json_error(['msg' => esc_html__('Missing configuration data', 'animation-addons-for-elementor-pro')], 400);
		}

		// Decode JSON and check for errors
		$pageTypeConfigs = json_decode($pageTypeConfigs, true);

		if (json_last_error() !== JSON_ERROR_NONE) {
			wp_send_json_error(['msg' => esc_html__('Invalid JSON format', 'animation-addons-for-elementor-pro')], 400);
		}

		// Further validation on the decoded data
		if (!is_array($pageTypeConfigs)) {
			wp_send_json_error(['msg' => esc_html__('Invalid configuration structure', 'animation-addons-for-elementor-pro')], 400);
		}
		// Delete configurations
		$this->page_type->deleteConfig($pageTypeConfigs);

		wp_send_json_success(['msg' => esc_html__('Configurations deleted successfully', 'animation-addons-for-elementor-pro')]);
	}

	public function config_enqueue_script()
	{

		if (isset($_GET['action']) && $_GET['action'] == 'animation-builder') {
			wp_enqueue_style('wcf-animbuilder-class-selector');
		}
		if (!aae__addons__pro__error_status()) {
			return;
		}
		if (isset($_GET['action']) && $_GET['action'] == 'animation-builder') {

			$deps = $this->register_builder_dependency();

			wp_enqueue_style(
				'aae-animation-builder-preview',
				WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/preview.css'
			);
			wp_register_script('wcf-animation-builder-preview', WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/preview.js', $deps, WCF_ADDONS_PRO_VERSION, true);
			wp_enqueue_script('wcf-animation-builder-preview');

			wp_register_script('wcf-custom-animation', WCF_ADDONS_PRO_URL . '/assets/build/modules/animation-builder/frontend/customAnimation.js', $deps, WCF_ADDONS_PRO_VERSION, true);
			wp_enqueue_script('wcf-custom-animation');

			$config = include plugin_dir_path(__FILE__) . '../core/configs/animation-builder-assets.php'; // adjust path
			$active_elements = $this->get_active_element_keys();
			if (is_array($active_elements) && is_array($config)) {
				foreach ($active_elements as $key) {
					if (isset($config['js'][$key])) {
						$element = $config['js'][$key];
						wp_enqueue_script($key, $element['src'], $element['deps'], WCF_ADDONS_PRO_VERSION, true);
					}
				}
			}



			$config = include plugin_dir_path(__FILE__) . 'configs/animation-builder-device.php'; // adjust path

			// sanitize / normalize
			$devices = array_map(function ($d) {
				return [
					'key'        => sanitize_key($d['key']),
					'title'      => wp_strip_all_tags($d['title']),
					'viewWidth'  => esc_attr($d['viewWidth']),
					'mediaQuery' => wp_strip_all_tags($d['mediaQuery']),
				];
			}, $config);


			wp_localize_script(
				'wcf-animation-builder-preview',
				'wcf_anim_preview_object',
				array(
					'ajaxurl'          => admin_url('admin-ajax.php'),
					'type'             => 'wcf-animation-builder',
					'nonce'            => wp_create_nonce('wcf-admin-preview-nonce'),
					'animation_config' => is_array($this->page_type->getConfig()) ? $this->page_type->getConfig() : [],
					'pageTypeConfigs'  => $this->page_type->getCurrentPageType(),
					'device_config'    => $devices,
				)
			);
		} else {

			if (is_user_logged_in()) {

				$custom_css = "
					#wpadminbar ul li.wcf--admin--animation--builder--button {
						background-color:hsl(13 97% 64%);					
					}
					#wpadminbar:not(.mobile) .ab-top-menu>li.wcf--admin--animation--builder--button:hover>.ab-item,
					#wpadminbar ul li.wcf--admin--animation--builder--button:hover{
						background-color:hsl(13 97% 64%);
						opacity: 0.9;
						color: white;
					}
				";

				wp_add_inline_style('admin-bar', $custom_css);
			}

			if ($pageConfigs = $this->page_type->getConfig()) {
				$is_custom = false;
				$activePreset = $this->getActivePresets($pageConfigs, $is_custom);

				$deps = $this->register_builder_dependency();
				wp_register_script('wcf-anim-builder-frontend', WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/frontend.js', $deps, WCF_ADDONS_PRO_VERSION, true);
				wp_enqueue_script('wcf-anim-builder-frontend');

				wp_register_script('wcf-custom-animation', WCF_ADDONS_PRO_URL . 'assets/build/modules/animation-builder/frontend/customAnimation.js', $deps, WCF_ADDONS_PRO_VERSION, true);
				if ($is_custom) {
					wp_enqueue_script('wcf-custom-animation');
				}

				$config = include plugin_dir_path(__FILE__) . '../core/configs/animation-builder-assets.php'; // adjust path
				$active_elements = $this->get_active_element_keys();
				if (is_array($active_elements) && is_array($config)) {
					foreach ($active_elements as $key) {
						if (isset($config['js'][$key]) && in_array($key, $activePreset, true)) {
							$element = $config['js'][$key];
							wp_enqueue_script($key, $element['src'], $element['deps'], WCF_ADDONS_PRO_VERSION, true);
						}
					}
				}

				$config = include plugin_dir_path(__FILE__) . 'configs/animation-builder-device.php'; // adjust path

				// sanitize / normalize
				$devices = array_map(function ($d) {
					return [
						'key'        => sanitize_key($d['key']),
						'title'      => wp_strip_all_tags($d['title']),
						'viewWidth'  => esc_attr($d['viewWidth']),
						'mediaQuery' => wp_strip_all_tags($d['mediaQuery']),
					];
				}, $config);

				wp_localize_script(
					'wcf-anim-builder-frontend',
					'wcfanimb',
					[
						'animation_config' => is_array($pageConfigs) ? $pageConfigs : [],
						'device_config'    => $devices,
					]
				);
			}
		}
	}


	public function register_builder_dependency()
	{
		wp_register_script('gsap', WCF_ADDONS_PRO_URL . 'assets/lib/gsap.min.js', [], WCF_ADDONS_PRO_VERSION, true);
		wp_register_script('ScrollTrigger', WCF_ADDONS_PRO_URL . 'assets/lib/ScrollTrigger.min.js', ['gsap'], WCF_ADDONS_PRO_VERSION, true);
		wp_register_script('MotionPathPlugin', WCF_ADDONS_PRO_URL . 'assets/lib/MotionPathPlugin.min.js', ['gsap'], WCF_ADDONS_PRO_VERSION, true);
		wp_register_script('DrawSVGPlugin', WCF_ADDONS_PRO_URL . 'assets/lib/DrawSVGPlugin.min.js', ['gsap'], WCF_ADDONS_PRO_VERSION, true);
		wp_register_script('SplitText', WCF_ADDONS_PRO_URL . 'assets/lib/SplitText.min.js', ['gsap'], WCF_ADDONS_PRO_VERSION, true);
		wp_register_script('TextPlugin', WCF_ADDONS_PRO_URL . 'assets/lib/TextPlugin.min.js', ['gsap'], WCF_ADDONS_PRO_VERSION, true);
		return ['gsap', 'ScrollTrigger', 'DrawSVGPlugin', 'MotionPathPlugin', 'SplitText', 'TextPlugin', 'wp-element'];
	}
	public function register_editor_scripts()
	{


		if (get_query_var('aae_builder') == 1) {
			// Safely include the template only once
			$template_path = WCF_ADDONS_PRO_PATH . 'templates/animation-builder-template.php';
			if (file_exists($template_path)) {
				do_action('wcf/animation/builder/head/enqueue');
			}
		}
	}


	function custom_rewrite_rules()
	{

		add_rewrite_rule(
			'^aae-animation-builder/?$', // Regex to match 
			'index.php?aae_builder=1', // Redirect to index.php
			'top'
		);
	}

	function custom_query_vars($vars)
	{
		$vars[] = 'aae_builder';
		return $vars;
	}

	function animation_builder_template_redirect()
	{

		if (is_admin()) {
			return; // Do nothing in the admin area
		}

		if (get_query_var('aae_builder') == 1) {
			// Safely include the template only once
			$template_path = WCF_ADDONS_PRO_PATH . 'templates/animation-builder-template.php';
			if (file_exists($template_path)) {
				include_once $template_path;
			} else {
				// Optional: Handle the case where the template file is missing
				wp_die('Template file not found!');
			}
			exit; // Prevent WordPress from loading other templates
		}
	}
	function wp_get_current_url()
	{

		$scheme = is_ssl() ? 'https' : 'http'; // Check if the site is using HTTPS
		$host = $_SERVER['HTTP_HOST'];         // Get the domain name
		$request_uri = $_SERVER['REQUEST_URI']; // Get the path and query string	
		// 2. Remove unwanted args.
		$strip = array(
			'preview',
			'preview_id',
			'preview_nonce',
			'aaeid',          // ← add any others you’d like nuked
		);

		$request_uri = remove_query_arg($strip, $request_uri);
		return esc_url("{$scheme}://{$host}{$request_uri}");
	}
	public function html_selector()
	{

		if (isset($_GET['action']) && $_GET['action'] == 'animation-builder') {
			wp_enqueue_style('wcf-animbuilder-class-selector');
?>
			<div class="wcfanimb-skip-selector" id="wcf-anim-builder-structure"></div>
			<div id="wcfanim-selectorPopup" class="wcfanimb-popup wcfanimb-skip-selector" style="display: none;">
				<div class="wcfanimb-wrapper wcfanimb-skip-selector">
					<div class="wcfanimb-close-btn wcfanimb-skip-selector">
						<svg xmlns="http://www.w3.org/2000/svg" class="wcfanimb-skip-selector" width="12" height="12" viewBox="0 0 12 12" fill="none">
							<g clip-path="url(#clip0_4401_4730)">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M10.9948 1.00483C11.2681 1.2782 11.2681 1.72141 10.9948 1.99478L1.99478 10.9948C1.72141 11.2681 1.2782 11.2681 1.00483 10.9948C0.731463 10.7214 0.731463 10.2782 1.00483 10.0048L10.0048 1.00483C10.2782 0.731463 10.7214 0.731463 10.9948 1.00483Z" fill="#94979B" class="wcfanimb-skip-selector" />
								<path fill-rule="evenodd" clip-rule="evenodd" d="M1.00483 1.00483C1.2782 0.731463 1.72141 0.731463 1.99478 1.00483L10.9948 10.0048C11.2681 10.2782 11.2681 10.7214 10.9948 10.9948C10.7214 11.2681 10.2782 11.2681 10.0048 10.9948L1.00483 1.99478C0.731463 1.72141 0.731463 1.2782 1.00483 1.00483Z" fill="#94979B" class="wcfanimb-skip-selector" />
							</g>
							<defs>
								<clipPath id="clip0_4401_4730">
									<rect width="12" height="12" fill="white" />
								</clipPath>
							</defs>
						</svg>
					</div>
					<div class="wcfanimb-skip-selector">
						<p class="wcfanimb-label wcfanimb-skip-selector"><?php echo esc_html__('Selected class', 'animation-addons-for-elementor-pro') ?></p>
						<div class="wcfanimb-classes wcfanimb-skip-selector">
							<p id="wcfanim-popupContent" class="wcfanimb-popupContent close wcfanimb-skip-selector"></p>
						</div>
					</div>
					<div class="wcfanim-btn-group wcfanimb-skip-selector">
						<button id="wcfanim-copySelector" class="wcfanimb-copy-btn wcfanimb-skip-selector" data-clipboard-target="#wcfanim-popupContent"><?php echo esc_html__('Copy', 'animation-addons-for-elementor-pro') ?></button>
						<button id="wcfanim-expendSelector" class="wcfanimb-select-btn expend-false wcfanimb-skip-selector"><?php echo esc_html__('Expend', 'animation-addons-for-elementor-pro') ?></button>
					</div>
				</div>
			</div>
<?php
		}
	}
	public function button_interface($wp_admin_bar)
	{

		if (is_admin()) {
			return;
		}

		$permalink_structure = get_option('permalink_structure');

		if ($permalink_structure) {
			$animation_builder_url = home_url('/aae-animation-builder/');
		} else {
			$animation_builder_url = home_url('/');
			$animation_builder_url = add_query_arg(array(
				'aae_builder' => 1,
			), $animation_builder_url);
		}

		$editor_url = apply_filters('wcf/animation/builder/admin/bar/url', add_query_arg(array(
			'builder_url' => $this->wp_get_current_url(),
		), $animation_builder_url));

		if (current_user_can('administrator')) {
			$args = array(
				'id'    => 'wcf--admin--animation--builder--button', // Unique ID for the button
				'title' => '<svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M7.9608 13.3467L8.619 11.8391C9.20482 10.4975 10.2592 9.42945 11.5744 8.84565L13.3861 8.04143C13.9621 7.78575 13.9621 6.94776 13.3861 6.69208L11.6309 5.91296C10.2819 5.31414 9.20865 4.20661 8.63287 2.81921L7.96612 1.21255C7.7187 0.616324 6.89489 0.616326 6.64747 1.21255L5.9807 2.81919C5.40493 4.20661 4.33165 5.31414 2.98264 5.91296L1.22743 6.69208C0.651402 6.94776 0.651402 7.78575 1.22743 8.04143L3.03922 8.84565C4.35442 9.42945 5.40878 10.4975 5.99456 11.8391L6.6528 13.3467C6.90582 13.9262 7.70775 13.9262 7.9608 13.3467ZM14.551 17.0174L14.7361 16.5932C15.0661 15.8367 15.6605 15.2344 16.4021 14.9049L16.9724 14.6515C17.2809 14.5145 17.2809 14.0662 16.9724 13.9292L16.4341 13.6899C15.6733 13.352 15.0683 12.7274 14.7439 11.9452L14.5539 11.4867C14.4214 11.1672 13.9796 11.1672 13.8471 11.4867L13.657 11.9452C13.3327 12.7274 12.7277 13.352 11.967 13.6899L11.4286 13.9292C11.1202 14.0662 11.1202 14.5145 11.4286 14.6515L11.9989 14.9049C12.7405 15.2344 13.3348 15.8367 13.6648 16.5932L13.85 17.0174C13.9855 17.328 14.4155 17.328 14.551 17.0174Z" fill="white"/>
						</svg>' . esc_html__('Build Animation', 'animation-addons-for-elementor-pro'),
				'parent' => 'top-secondary',
				'href'  => esc_url($editor_url),  // The URL the button should link to
				'meta'  => array(
					'class' => 'wcf--admin--animation--builder--button',
					'onclick' => '', // JavaScript action
					'target' => '_blank',
				),
			);

			$wp_admin_bar->add_node($args);
		}
	}
}

/**
 * ================================================================
 *  Initialize Animation Builder - Safety Check & Singleton Pattern
 * ================================================================
 * This section performs a pre-check to ensure that the plugin environment
 * is stable before proceeding. It verifies if any critical error exists 
 * (like missing dependencies or invalid licensing) using the 
 * `aae__addons__pro__error_status()` function.
 * 
 * If everything is okay, it then bootstraps the animation system by 
 * initializing the `Wcf_Animation_Builder` singleton. This class is 
 * responsible for managing all advanced animation effects within 
 * the Elementor environment.
 * 
 * Using a singleton pattern ensures memory efficiency and consistency 
 * in managing the animation logic across the entire plugin lifecycle.
 */
if (! aae__addons__pro__error_status()) {
	return;
}
/**
 * 💖 A Little Bit of Dev Love Before We Animate 💖
 * ------------------------------------------------
 * Before we bring life to your elements with stunning animations,
 * let’s first make sure everything is safe and sound.
 * 
 * ✅ Step 1: Check for any critical errors in the AAE Pro Addons environment
 *    - If something’s broken (like missing dependencies or license issues),
 *      we gracefully bow out to keep things stable.
 * 
 * ✨ Step 2: Fire up the Animation Builder (singleton style!)
 *    - This class is the heart of all custom animations in Elementor.
 *    - We use a singleton to ensure a single, consistent instance runs the show.
 * 
 * Writing clean, cautious, and creative code — because every pixel deserves love ❤️
 */
Wcf_Animation_Builder::instance();
