<?php

namespace WCFAddonsPros\compatible;

defined( 'ABSPATH' ) || exit;

class AAE_WPML_Compatibility {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		
		add_action( 'admin_init', [ $this, 'register_wpml_setting' ] );
		add_action( 'admin_init', [ $this, 'add_permalink_checkbox_field' ] );

		// Conditionally hook rewrite rule filter
		if ( is_admin() && get_option( 'aae_enable_wpml_rewrite_fix' ) === '1' ) {
			add_filter( 'mod_rewrite_rules', [ $this, 'maybe_fix_wpml_rewritebase' ] );
		}

        add_action('admin_enqueue_scripts', [ $this, 'enqueue_admin_script' ]);
        add_action('wp_ajax_aae_addon_toggle_wpml_rewrite', [ $this, 'handle_ajax_toggle' ]);

	}

    public function handle_ajax_toggle() {
        check_ajax_referer('aae_wpml_nonce', 'nonce');
        $value = isset($_POST['enabled']) && $_POST['enabled'] === '1' ? '1' : '0';
        update_option('aae_enable_wpml_rewrite_fix', $value);
        wp_send_json_success(['message' => 'Setting saved']);
    }


    public function enqueue_admin_script($hook) {
       
        if ( 'options-permalink.php' !== $hook ) return;

        if ( ! $this->is_wpml_active() ) {
			return;
		}
         
        wp_enqueue_script(
            'aae-addon-wpml',
            WCF_ADDONS_PRO_URL. 'assets/js/aae-wpml.js',
            [ 'jquery' ],
            false,
            true
        );

        wp_localize_script('aae-addon-wpml', 'aaeWpmlAjax', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('aae_wpml_nonce')
        ]);
    }

	/**
	 * Register custom setting
	 */
	public function register_wpml_setting() {
		register_setting( 'permalink', 'aae_enable_wpml_rewrite_fix' );
	}

	/**
	 * Add checkbox field to Permalink settings page
	 */
	public function add_permalink_checkbox_field() {
        if ( ! $this->is_wpml_active() ) {
			return;
		}
		add_settings_field(
			'aae_enable_wpml_rewrite_fix',
			__( 'Enable WPML Rewrite', 'animation-addons-for-elementor-pro' ),
			[ $this, 'render_checkbox_field' ],
			'permalink',
			'optional'
		);
	}

	/**
	 * Render the checkbox
	 */
	public function render_checkbox_field() {
		$value = get_option( 'aae_enable_wpml_rewrite_fix', '0' );
		echo '<label><input type="checkbox" id="aae_enable_wpml_rewrite_fix" name="aae_enable_wpml_rewrite_fix" value="1" ' . checked( 1, $value, false ) . '> ';
		echo esc_html__( 'Enable custom WPML RewriteBase adjustment', 'animation-addons-for-elementor-pro' ) . '</label>';
	}

	/**
	 * Check if WPML is active
	 *
	 * @return bool
	 */
	public function is_wpml_active() {
		return defined( 'ICL_SITEPRESS_VERSION' )
			|| class_exists( 'SitePress' )
			|| function_exists( 'icl_object_id' )
			|| ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'sitepress-multilingual-cms/sitepress.php' ) );
	}

	/**
	 * Filter mod_rewrite_rules when enabled and WPML is active
	 *
	 * @param string $rules
	 * @return string
	 */
	public function maybe_fix_wpml_rewritebase( $rules ) {
		
		if ( ! $this->is_wpml_active() ) {
			return $rules;
		}

		$home_root = parse_url(home_url());
		
		if ( isset( $home_root['path'] ) ) {
			$home_root = trailingslashit($home_root['path']);
		} else {
			$home_root = '/';
		}
	
		$wpml_root = parse_url(get_option('home'));
		
		if ( isset( $wpml_root['path'] ) ) {
			$wpml_root = trailingslashit($wpml_root['path']);
		} else {
			$wpml_root = '/';
		}
	
		$rules = str_replace("RewriteBase $home_root", "RewriteBase $wpml_root", $rules);
		$rules = str_replace("RewriteRule . $home_root", "RewriteRule . $wpml_root", $rules);
	
		return $rules;
	}
}

AAE_WPML_Compatibility::instance();
