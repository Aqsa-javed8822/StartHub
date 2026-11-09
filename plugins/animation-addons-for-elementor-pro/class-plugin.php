<?php

namespace WCFAddonsPro;

use Elementor\Plugin as ElementorPlugin;
use WCF_ADDONS\Plugin as WCFAddonsPlugin;
use WCFAddonsPro\INC\WPML as WPML;
/**
 * Class Plugin
 *
 * Main Plugin class
 *
 * @since 1.2.0
 */
class Plugin {


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
	public $categories       = null;
	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.2.0
	 * @access public
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {
		$jslib  = get_option( 'wcf_save_gsap_library' );
		$dashb2 = get_option( 'wcf_extension_dashboardv2' );
		foreach ( self::get_library_scripts() as $key => $script ) {
			if ( $key === 'split-text' && isset( $_REQUEST['elementor-preview'] ) ) {
				wp_register_script( $script['handler'], plugins_url( '/assets/lib/old/' . $script['src'], __FILE__ ), $script['dep'], $script['version'], $script['arg'] );
			} else {
				wp_register_script( $script['handler'], plugins_url( '/assets/lib/' . $script['src'], __FILE__ ), $script['dep'], $script['version'], $script['arg'] );
			}

			if ( ! $dashb2 ) {
				wp_enqueue_script( $script['handler'] );
			}
		}

		$ScrollTrigger = is_array( $jslib ) ? self::aae_is_gsap_plugin_active( $jslib, 'ScrollTrigger' ) : false;
		if ( $ScrollTrigger ) {
			wp_enqueue_script( 'ScrollTrigger' );
		}
		$Draggable = is_array( $jslib ) ? self::aae_is_gsap_plugin_active( $jslib, 'Draggable' ) : false;
		if ( $Draggable ) {
			wp_enqueue_script( 'Draggable' );
		}

		if ( $dashb2 ) {

			if ( wcf_addons_get_settings( 'wcf_save_extensions', 'gsap-extensions' ) ) {
				wp_enqueue_script( 'gsap' );
			}

			if ( wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-smooth-scroller' ) ) {
				wp_enqueue_script( 'gsap' );			
				wp_enqueue_script( 'ScrollSmoother' );
				wp_enqueue_script( 'ScrollTrigger' );
				
			}

			if ( wcf_addons_get_settings( 'wcf_save_extensions', 'scroll-trigger' ) ) {
				wp_enqueue_script( 'gsap' );
				wp_enqueue_script( 'ScrollTrigger' );
			}
		}

		foreach ( self::get_widget_scripts() as $key => $script ) {
			wp_register_script( $script['handler'], plugins_url( '/assets/js/' . $script['src'], __FILE__ ), $script['dep'], WCF_ADDONS_PRO_VERSION, $script['arg'] );
		}

		wp_enqueue_script( 'wcf--addons-ex' );

		//$kit = ElementorPlugin::$instance->documents->get(ElementorPlugin::$instance->kits_manager->get_active_id(), false);		
		// if(method_exists($kit, 'get_settings')){
		// $settings = $kit->get_settings();
		// }
		

		wp_localize_script(
			'aae-posts-read-later',
			'aae_post_later_path',
			array(
				'home_url' => home_url(),
			)
		);

		if ( isset( $_GET['elementor-preview'] ) ) {
			wp_enqueue_script( 'aae--animations--modules' );
			wp_enqueue_script( 'wcf--advanced-tooltips' );
			wp_enqueue_script( 'aae--cursor-hover-effects' );
			wp_enqueue_script( 'aae--mouse-move-effects-widget' );
			wp_enqueue_script( 'aae--horizontals--scroll' );
			wp_enqueue_script( 'aae--hover-image-container' );
			wp_enqueue_script( 'adv-sticky-pin-container' );
			wp_enqueue_script( 'aae-adv-aae-popup' );
			wp_enqueue_script( 'aae-scroll-to-ele' );
			wp_enqueue_script( 'aae--tilt-tool' );
			wp_enqueue_script( 'adv-wrapper-links-container' );
			wp_enqueue_script( 'aae-one-page-scroll' );
			wp_enqueue_script( 'SplitText' );
		}
	}

	/**
	 * Function widget_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public static function widget_styles() {
		// widget style
		wp_register_style( 'wcf-animbuilder-class-selector', plugins_url( '/assets/css/animbuilder-copy.css', __FILE__ ), array(), WCF_ADDONS_PRO_VERSION );
		foreach ( self::get_widget_style() as $key => $style ) {
			wp_register_style( $style['handler'], plugins_url( '/assets/css/' . $style['src'], __FILE__ ), $style['dep'], $style['version'], $style['media'] );
		}

		// wp_enqueue_style('wcf--addons-pro');
		wp_enqueue_style( 'wcf--addons-ex' );
	}

	/**
	 * Editor scripts
	 *
	 * Enqueue plugin javascripts integrations for Elementor editor.
	 *
	 * @since 1.2.1
	 * @access public
	 */
	public function editor_scripts() {

		wp_enqueue_script(
			'aae--nested-motion-card',
			WCF_ADDONS_PRO_URL . '/assets/build/modules/nested-motion-card/editor/index.js',
			array(
				'nested-elements',
				'elementor-editor',
				'elementor-common',
				'wp-element',
				'jquery',
			),
			WCF_ADDONS_PRO_VERSION,
			true
		);

		wp_enqueue_script(
			'aae-nested-megamenu-editor',
			plugins_url( '/assets/build/modules/mega-menu/editor/index.js', __FILE__ ),
			array(
				'nested-elements',
			),
			WCF_ADDONS_PRO_VERSION,
			true
		);

		wp_enqueue_script(
			'wcf-addon-pro-editor',
			plugins_url( '/assets/js/editor.js', __FILE__ ),
			array(
				'elementor-editor',
			),
			WCF_ADDONS_PRO_VERSION,
			true
		);

		wp_enqueue_script(
			'aae-elementor-sync-placeholders',
			plugins_url( '/assets/js/elementor-editor-sync-placeholders.js', __FILE__ ),
			array( 'jquery' ),
			WCF_ADDONS_PRO_VERSION,
			true
		);

		$data = apply_filters(
			'wcf-addons-pro-editor/js/data',
			array(
				'logo' => WCF_ADDONS_URL . 'assets/images/aae-logo.png',
			)
		);

		wp_localize_script( 'wcf-addon-pro-editor', 'WCF_Addons_Pro_Editor', $data );
	}
	static function aae_is_gsap_plugin_active( $data, $plugin_key ) {
		return ! empty( $data['elements']['gsap-library']['elements'][ $plugin_key ]['is_active'] );
	}
	/**
	 * Function widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_widget_scripts() {

		$scrollSmoother = wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-smooth-scroller' ) ? 'ScrollSmoother' : '';

		// $ScrollTrigger =
		return array(

			'filterable-slider'                       => array(
				'handler' => 'wcf--filterable-slider',
				'src'     => 'filterable-slider' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'table-of-contents'                       => array(
				'handler' => 'wcf--table-of-content',
				'src'     => 'table-of-content' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'ScrollToPlugin', 'gsap', 'ScrollTrigger' ),
				'version' => false,
				'arg'     => true,
			),
			'advanced-tools-tips'                     => array(
				'handler' => 'wcf--advanced-tooltips',
				'src'     => 'advanced-tooltips' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-tilt-tips'                      => array(
				'handler' => 'aae--tilt-tool',
				'src'     => 'tilt' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-scrollto-widget'                => array(
				'handler' => 'wcf--scroll-elements',
				'src'     => 'scroll-to' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'ScrollToPlugin', 'gsap', 'ScrollTrigger' ),
				'version' => false,
				'arg'     => true,
			),
			'advanced-cursor-gl'                      => array(
				'handler' => 'wcf--advanced-cursor',
				'src'     => 'adv-cursor' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced--horizontal--scroll'            => array(
				'handler' => 'aae--horizontals--scroll',
				'src'     => 'horizontal-scroll' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),

			'advanced--cursor-hover-effects'          => array(
				'handler' => 'aae--cursor-hover-effects',
				'src'     => 'cursor-hover-effects' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),

			'advanced--hover-image-gl'                => array(
				'handler' => 'aae--hover-image-container',
				'src'     => 'hover-image-gl' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-mouse-move-effects-gl'          => array(
				'handler' => 'aae--mouse-move-effects-widget',
				'src'     => 'mouse-move-effects' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-portfolio--skins-gl'            => array(
				'handler' => 'advanced--portfolio--skins',
				'src'     => 'advanced-portfolio-skins' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'ScrollSmoother', 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'advanced-adv-posts-pro-gl'               => array(
				'handler' => 'advanced--adv-posts-pro',
				'src'     => 'adv-posts-pro' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-features-posts-gl'          => array(
				'handler' => 'advanced--aae--features--posts',
				'src'     => 'adv-features-posts' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-image-accord'               => array(
				'handler' => 'advanced--aae--image-accordion',
				'src'     => 'image-accordion' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-filterable-gallery.'        => array(
				'handler' => 'aae--filterable-gallery',
				'src'     => 'filterable-gallery' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-wrapper-links-container-gl' => array(
				'handler' => 'adv-wrapper-links-container',
				'src'     => 'adv-wrapper-links-container' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-sticky-pin-gl'              => array(
				'handler' => 'adv-sticky-pin-container',
				'src'     => 'sticky-pin' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'ScrollSmoother' ),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-animation-wcgt-gl'          => array(
				'handler' => 'aae-adv-aae-popup',
				'src'     => 'aae-popup' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-video-mask'                 => array(
				'handler' => 'aae-video-mask-widget',
				'src'     => 'aae-video-mask' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-aae-video-popup-mix'        => array(
				'handler' => 'aae-video-popup-mix',
				'src'     => 'aae-video-popup' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-aae-scroll-to-menu'             => array(
				'handler' => 'aae-scroll-to-ele',
				'src'     => 'aae-scroll-to-ele' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'ScrollToPlugin', 'gsap', 'ScrollTrigger' ),
				'version' => false,
				'arg'     => true,
			),
			'filterable-posts'                        => array(
				'handler' => 'wcf--filterable-posts',
				'src'     => 'filterable-posts' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'flip' ),
				'version' => false,
				'arg'     => true,
			),
			'advance-accordion'                       => array(
				'handler' => 'wcf--a-accordion',
				'src'     => 'advance-accordion' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'image-compare'                           => array(
				'handler' => 'wcf--image-compare',
				'src'     => 'image-compare' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'Draggable' ),
				'version' => false,
				'arg'     => true,
			),
			'mega-menu'                               => array(
				'handler' => 'aae--mega-menu',
				'src'     => 'mega-menu' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'animated-offcanvas'                      => array(
				'handler' => 'wcf--animated-offcanvas',
				'src'     => 'animated-offcanvas' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'wcf-lottie'                              => array(
				'handler' => 'wcf-lottie',
				'src'     => 'lottie' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'lottie' ),
				'version' => false,
				'arg'     => true,
			),
			'wcf--post-reactions'                     => array(
				'handler' => 'wcf--post-reactions',
				'src'     => 'post-reactions' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'wcf-posts'                               => array(
				'handler' => 'wcf--posts',
				'src'     => 'post' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'flip' ),
				'version' => false,
				'arg'     => true,
			),
			'wcf-addons-ex'                           => array(
				'handler' => 'wcf--addons-ex',
				'src'     => 'wcf-addons-ex' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'jquery', 'elementor-frontend-modules', $scrollSmoother ),
				'version' => WCF_ADDONS_PRO_VERSION,
				'arg'     => true,
			),
			'portfolio'                               => array(
				'handler' => 'wcf--portfolio',
				'src'     => 'portfolio' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'flip' ),
				'version' => false,
				'arg'     => true,
			),
			'pop-up-builder'                          => array(
				'handler' => 'aae--poup-builder',
				'src'     => 'pop-up-builder' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'mailchimp'                               => array(
				'handler' => 'wcf--mailchimp',
				'src'     => 'mailchimp' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'posts-slider'                            => array(
				'handler' => 'wcf--posts-slider',
				'src'     => 'posts-slider' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'nested-nt-slider'                        => array(
				'handler' => 'aae--nested-slider',
				'src'     => 'aae-slider-frontend' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),

			'category-slider'                         => array(
				'handler' => 'wcf--category-slider',
				'src'     => 'category-slider' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'video-story'                             => array(
				'handler' => 'aae-video-story',
				'src'     => 'video-story' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),

			'breaking-news-slider'                    => array(
				'handler' => 'wcf--breaking-news-slider',
				'src'     => 'breaking-news-slider' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'one-page-scroll'                         => array(
				'handler' => 'aae-one-page-scroll',
				'src'     => 'one-page-scroll' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'ScrollToPlugin', 'gsap', 'ScrollTrigger' ),
				'version' => false,
				'arg'     => true,
			),
			'aae-filtering'                           => array(
				'handler' => 'aae--filtering',
				'src'     => 'filtering' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'post-rating'                             => array(
				'handler' => 'aae-post-rating',
				'src'     => 'post-rating' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'notification'                            => array(
				'handler' => 'aae-notification',
				'src'     => 'notification' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'button-pro'                              => array(
				'handler' => 'aae--button-pro',
				'src'     => 'button-pro' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'advanced-button'                         => array(
				'handler' => 'aae--advanced-button',
				'src'     => 'advanced-button' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'youtube-videos'                          => array(
				'handler' => 'aae--youtube-video',
				'src'     => 'aae--youtube-video' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'tiktok-feed'                             => array(
				'handler' => 'aae-tiktok-feed',
				'src'     => 'tiktok' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'posts-read-later'                        => array(
				'handler' => 'aae-posts-read-later',
				'src'     => 'read-later' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'advanced-search'                         => array(
				'handler' => 'aae-a-search',
				'src'     => 'a-search' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'stacked-cards'                           => array(
				'handler' => 'aae-stacked-cards',
				'src'     => 'stacked-cards' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'vertical-marquee'                        => array(
				'handler' => 'aae-vertical-marquee',
				'src'     => 'vertical-marquee' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => WCF_ADDONS_PRO_VERSION,
				'arg'     => true,
			),
			'scrollable-video'                        => array(
				'handler' => 'aae-scrollable-video',
				'src'     => 'scrollable-video' . AAE_ADDONS_PRO_MIN,
				'dep'     => array(),
				'version' => WCF_ADDONS_PRO_VERSION,
				'arg'     => true,
			),
			// 'scrollmotion-cards'                      => array(
			// 	'handler' => 'aae-scrollmotion-cards',
			// 	'src'     => 'scrollmotion-cards' . AAE_ADDONS_PRO_MIN,
			// 	'dep'     => array( 'gsap' ),
			// 	'version' => WCF_ADDONS_PRO_VERSION,
			// 	'arg'     => true,
			// ),
			'aae-animations-modules'                  => array(
				'handler' => 'aae--animations--modules',
				'src'     => 'animations' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'gsap', 'ScrollTrigger', 'SplitText', 'wcf--addons-ex' ),
				'version' => WCF_ADDONS_PRO_VERSION,
				'arg'     => true,
			),
			'hero-text-explode'                       => array(
				'handler' => 'aae-hero-text-explode',
				'src'     => 'hero-text-explode' . AAE_ADDONS_PRO_MIN,
				'dep'     => array( 'gsap', 'SplitText', 'Physics2DPlugin' ),
				'version' => false,
				'arg'     => true,
			),
		);
	}

	/**
	 * Function lib_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_library_scripts() {

		if ( ! get_option( 'wcf_save_extensions' ) ) {
			return array();
		}

		$scripts = array(
			'gsap'                 => array(
				'handler' => 'gsap',
				'src'     => 'gsap.min.js',
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
			'scroll-smoother'      => array(
				'handler' => 'ScrollSmoother',
				'src'     => 'ScrollSmoother.min.js',
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'scroll-to'            => array(
				'handler' => 'ScrollToPlugin',
				'src'     => 'ScrollToPlugin.min.js',
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'Draggable'            => array(
				'handler' => 'Draggable',
				'src'     => 'Draggable.min.js',
				'dep'     => array( 'wcf--addons-ex' ),
				'version' => false,
				'arg'     => true,
			),
			'flip'                 => array(
				'handler' => 'flip',
				'src'     => 'Flip.min.js',
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'scroll-trigger'       => array(
				'handler' => 'ScrollTrigger',
				'src'     => 'ScrollTrigger.min.js',
				'dep'     => array(),
				'version' => 3.13,
				'arg'     => true,
			),
			'split-text'           => array(
				'handler' => 'SplitText',
				'src'     => 'SplitText.min.js',
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'physics-2d'           => array(
				'handler' => 'Physics2DPlugin',
				'src'     => 'Physics2DPlugin.min.js',
				'dep'     => array( 'gsap' ),
				'version' => false,
				'arg'     => true,
			),
			'lottie'               => array(
				'handler' => 'lottie',
				'src'     => 'lottie-player.js',
				'dep'     => array(),
				'version' => 5.13,
				'arg'     => true,
			),
			'lottie-interactivity' => array(
				'handler' => 'lottie-interactivity',
				'src'     => 'lottie-interactivity.min.js',
				'dep'     => array(),
				'version' => 1.6,
				'arg'     => true,
			),
			'effect-panorama'      => array(
				'handler' => 'effect--panorama',
				'src'     => 'effect-panorama.min.js',
				'dep'     => array(),
				'version' => false,
				'arg'     => true,
			),
		);

		if ( ! defined( 'WCF_ADDONS_DASHBOARD_V2' ) ) {

			if ( ! wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-gsap' ) ) {
				unset( $scripts['gsap'] );
			}

			if ( ! wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-smooth-scroller' ) ) {
				unset( $scripts['scroll-smoother'] );
			}
		}

		return $scripts;
	}

	/**
	 * Function widget_style
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public static function get_widget_style() {
		return array(
			// 'animation-addons-for-elementor-pro' => [
			// 'handler' => 'wcf--addons-pro',
			// 'src'     => 'wcf-addons-pro.min.css',
			// 'dep'     => [],
			// 'version' => WCF_ADDONS_PRO_VERSION,
			// 'media'   => 'all',
			// ],
			'advance-pricing-table' => array(
				'handler' => 'wcf--advance-pricing-table',
				'src'     => 'widgets/advance-pricing-table.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'mega-menu'             => array(
				'handler' => 'aae--mega-menu',
				'src'     => 'widgets/mega-menu.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'advance-portfolio'     => array(
				'handler' => 'wcf--advance-portfolio',
				'src'     => 'widgets/advance-portfolio.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'scroll-elements-style' => array(
				'handler' => 'wcf--scroll-elements-css',
				'src'     => 'widgets/scroll-elements.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'toggle-switcher'       => array(
				'handler' => 'wcf--toggle-switch',
				'src'     => 'widgets/toggle-switch.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'filterable-gallery'    => array(
				'handler' => 'wcf--filterable-gallery',
				'src'     => 'widgets/filterable-gallery.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'image-compare'         => array(
				'handler' => 'wcf--image-compare',
				'src'     => 'widgets/image-compare.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'table-of-content'      => array(
				'handler' => 'wcf--table-of-content',
				'src'     => 'widgets/table-of-content.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'image-accordion'       => array(
				'handler' => 'wcf--image-accordion',
				'src'     => 'widgets/image-accordion.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'author-box'            => array(
				'handler' => 'wcf--author-box',
				'src'     => 'widgets/author-box.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'flip-box'              => array(
				'handler' => 'wcf--flip-box',
				'src'     => 'widgets/flip-box.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'filterable-slider'     => array(
				'handler' => 'wcf--filterable-slider',
				'src'     => 'widgets/filterable-slider.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'advance-accordion'     => array(
				'handler' => 'wcf--a-accordion',
				'src'     => 'widgets/advance-accordion.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'animated-offcanvas'    => array(
				'handler' => 'wcf--animated-offcanvas',
				'src'     => 'widgets/animated-offcanvas.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'posts-reaction'        => array(
				'handler' => 'wcf--post-reactions',
				'src'     => 'widgets/post-reaction.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'posts-pro'             => array(
				'handler' => 'wcf--post-pro',
				'src'     => 'widgets/posts.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'posts-filterable'      => array(
				'handler' => 'wcf--filterable-posts',
				'src'     => 'widgets/filterable-posts.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'grid-hover-posts'      => array(
				'handler' => 'wcf--grid-hover-posts',
				'src'     => 'widgets/grid-hover-posts.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'wcf-addons-ex'         => array(
				'handler' => 'wcf--addons-ex',
				'src'     => 'wcf-addons-ex.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'wcf-popup-builder'     => array(
				'handler' => 'wcf--popup-builder',
				'src'     => 'popup-builder.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'category-showcase'     => array(
				'handler' => 'wcf--category-showcase',
				'src'     => 'widgets/category-showcase.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'video-box'             => array(
				'handler' => 'wcf--video-box',
				'src'     => 'widgets/video-box.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'video-box-slider'      => array(
				'handler' => 'wcf--video-box-slider',
				'src'     => 'widgets/video-box-slider.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'mailchimp'             => array(
				'handler' => 'wcf--mailchimp',
				'src'     => 'widgets/mailchimp.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'portfolio'             => array(
				'handler' => 'wcf--portfolio',
				'src'     => 'widgets/portfolio.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'video-mask'            => array(
				'handler' => 'wcf--video-mask',
				'src'     => 'widgets/video-mask.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'posts-slider'          => array(
				'handler' => 'wcf--posts-slider',
				'src'     => 'widgets/posts-slider.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'video-story'           => array(
				'handler' => 'aae-video-story',
				'src'     => 'widgets/video-story.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'breaking-news-slider'  => array(
				'handler' => 'wcf--breaking-news-slider',
				'src'     => 'widgets/breaking-news-slider.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'post-rating'           => array(
				'handler' => 'aae-post-rating',
				'src'     => 'widgets/post-rating.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'notification'          => array(
				'handler' => 'aae-notification',
				'src'     => 'widgets/notification.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'button-pro'            => array(
				'handler' => 'aae--button-pro',
				'src'     => 'widgets/button-pro.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'advanced-button'       => array(
				'handler' => 'aae--advanced-button',
				'src'     => 'widgets/advanced-button.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'advanced-testimonial'  => array(
				'handler' => 'aae-a-testimonial',
				'src'     => 'widgets/advanced-testimonial.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'youtube-video'         => array(
				'handler' => 'aae-ytube-videos',
				'src'     => 'widgets/youtube-video.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'weather'               => array(
				'handler' => 'aae--weather',
				'src'     => 'widgets/weather.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'tiktok-feed'           => array(
				'handler' => 'aae-tiktok-feed',
				'src'     => 'widgets/tiktok.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'posts-read-later'      => array(
				'handler' => 'aae-posts-read-later',
				'src'     => 'widgets/read-later.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'post-timeline'         => array(
				'handler' => 'aae-post-timeline',
				'src'     => 'widgets/post-timeline.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'live-events'           => array(
				'handler' => 'aae-live-event',
				'src'     => 'widgets/live-event.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'stacked-cards'         => array(
				'handler' => 'aae-stacked-cards',
				'src'     => 'widgets/stacked-cards.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'vertical-marquee'      => array(
				'handler' => 'aae-vertical-marquee',
				'src'     => 'widgets/vertical-marquee.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'scrollable-video'      => array(
				'handler' => 'aae-scrollable-video',
				'src'     => 'widgets/scrollable-video.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'scrollmotion-cards'    => array(
				'handler' => 'aae-scrollmotion-cards',
				'src'     => 'widgets/scrollmotion-cards.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'hero-text-explode'     => array(
				'handler' => 'aae-hero-text-explode',
				'src'     => 'widgets/hero-text-explode.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
			'aae-video-popup'     => array(
				'handler' => 'aae-video-popup',
				'src'     => 'widgets/video-popup.min.css',
				'dep'     => array(),
				'version' => false,
				'media'   => 'all',
			),
		);
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_widgets() {

		if ( defined( 'WCF_ADDONS_DASHBOARD_V2' ) ) {
			$widgets_keys = array_keys( self::get_widgets() );

			foreach ( WCFAddonsPlugin::get_widgets() as $slug => $data ) {

				$index = array_search( $slug, $widgets_keys ); // Find the index of the element

				if ( $index !== false ) {

					// If upcoming don't register.
					if ( $data['is_upcoming'] ) {
						continue;
					}

					if ( $data['is_active'] ) {

						if ( file_exists( __DIR__ . '/widgets/' . $slug . '.php' ) || file_exists( __DIR__ . '/widgets/' . $slug . '/' . $slug . '.php' ) ) {

							if ( is_dir( __DIR__ . '/widgets/' . $slug ) ) {
								require_once __DIR__ . '/widgets/' . $slug . '/' . $slug . '.php';
							} else {
								require_once __DIR__ . '/widgets/' . $slug . '.php';
							}

							$class = explode( '-', $slug );
							$class = array_map( 'ucfirst', $class );
							$class = implode( '_', $class );

							$class = 'WCFAddonsPro\\Widgets\\' . $class;

							ElementorPlugin::instance()->widgets_manager->register( new $class() );
						}
					}
				}
			}
		} else {

			foreach ( self::get_widgets() as $slug => $data ) {

				// If upcoming don't register.
				if ( $data['is_upcoming'] ) {
					continue;
				}

				if ( $data['is_active'] ) {

					if ( file_exists( __DIR__ . '/widgets/' . $slug . '.php' ) || file_exists( __DIR__ . '/widgets/' . $slug . '/' . $slug . '.php' ) ) {

						if ( is_dir( __DIR__ . '/widgets/' . $slug ) ) {
							require_once __DIR__ . '/widgets/' . $slug . '/' . $slug . '.php';
						} else {
							require_once __DIR__ . '/widgets/' . $slug . '.php';
						}

						$class = explode( '-', $slug );
						$class = array_map( 'ucfirst', $class );
						$class = implode( '_', $class );
						$class = 'WCFAddonsPro\\Widgets\\' . $class;
						ElementorPlugin::instance()->widgets_manager->register( new $class() );
					}
				}
			}
		}
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor Extensions.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function register_extensions() {

		$extensions = $this->get_extensions();
		// Free Extensions
		require_once WCF_ADDONS_PRO_PATH . 'inc/core/wcf-page-loop-settings.php';
		if ( defined( 'WCF_ADDONS_DASHBOARD_V2' ) ) {

			$widgets = get_option( 'wcf_save_extensions' );

			$saved_widgets = is_array( $widgets ) ? array_keys( $widgets ) : array();

			$ext_keys = self::get_extensions();
			self::get_search_active_keys( $ext_keys, $saved_widgets, $acwidgets );

			foreach ( WCFAddonsPlugin::get_extensions() as $slug => $data ) {
				if ( is_array( $acwidgets ) && in_array( $slug, $acwidgets ) ) {

					if ( $data['is_upcoming'] ) {
						continue;
					}

					if ( file_exists( WCF_ADDONS_PRO_PATH . 'inc/extensions/wcf-' . $slug . '.php' ) ) {
						include_once WCF_ADDONS_PRO_PATH . 'inc/extensions/wcf-' . $slug . '.php';
					}
				}
			}

			if ( is_array( $saved_widgets ) && in_array( 'wcf-smooth-scroller', $saved_widgets ) ) {
				include_once WCF_ADDONS_PRO_PATH . 'inc/extensions/wcf-' . 'wcf-smooth-scroller' . '.php';
			}
		} else {

			$allextensions = array();
			foreach ( $extensions as $index => $extension ) {
				// if gsap not enbale
				if ( 'gsap-extensions' === $index && ! wcf_addons_get_settings( 'wcf_save_extensions', 'wcf-gsap' ) ) {
					continue;
				}

				$allextensions = array_merge( $allextensions, $extension['elements'] );
			}

			foreach ( $allextensions as $slug => $data ) {

				// If upcoming don't register.
				if ( $data['is_upcoming'] ) {
					continue;
				}

				if ( $data['is_pro'] ) {
					include_once WCF_ADDONS_PRO_PATH . 'inc/extensions/wcf-' . $slug . '.php';
				}
			}
		}
	}

	function get_search_active_keys( $array, $saved_widgets, &$active ) {
		foreach ( $array as $key => $value ) {
			// Check if the current key is one we're looking for
			if ( is_array( $value ) && array_key_exists( 'is_extension', $value ) ) {

				if ( is_array( $saved_widgets ) && in_array( $key, $saved_widgets ) ) {
					$active[] = $key;
				}
			}

			// If value is an array, recurse into it
			if ( is_array( $value ) ) {
				self::get_search_active_keys( $value, $saved_widgets, $active );
			}
		}
	}

	public function get_extensions() {
		$extensions = array(
			'general-extensions' => array(
				'title'    => 'General Extension',
				'elements' => array(
					'wrapper-link'     => array(
						'label'        => 'Wrapper Link',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'tilt-effect'      => array(
						'label'        => 'Tilt Effect',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'scrollto'         => array(
						'label'        => 'ScrollTo',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'advanced-tooltip' => array(
						'label'        => 'Advanced Tooltip',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'custom-fonts'     => array(
						'label'        => 'Custom Fonts',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
				),
			),
			'gsap-extensions'    => array(
				'title'    => 'Gsap Extension',
				'elements' => array(
					'smooth-scroller'         => array(
						'label'        => 'Smooth Scroller',
						'is_pro'       => true,
						'is_extension' => true,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'cursor-hover-effect'     => array(
						'label'        => 'Cursor Hover Effect',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'horizontal-scroll'       => array(
						'label'        => 'Horizontal',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'hover-effect-image'      => array(
						'label'        => 'Hover Effect Image',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'cursor-move-effect'      => array(
						'label'        => 'Cursor Move Effect',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => false,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'animation-builder'       => array(
						'label'        => 'Animation Builder',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'custom-cpt'              => array(
						'label'        => 'Custom Post Type',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'custom-icon'             => array(
						'label'        => 'Custom Icon',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'dynamic-tags'            => array(
						'label'        => 'Dynamic Tags',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'restrict-content'        => array(
						'label'        => 'Content Restriction',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'animation-effects'       => array(
						'label'        => 'Animation Effects',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'image-animation-effects' => array(
						'label'        => 'Image Animation Effects',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'pin-element'             => array(
						'label'        => 'pin-element',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
					'popup'                   => array(
						'label'        => 'Popup',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),

					'portfolio-filter'        => array(
						'label'        => 'Portfolio Filter',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),

					'text-animation-effects'  => array(
						'label'        => 'Text Animation Effects',
						'is_pro'       => true,
						'is_extension' => false,
						'is_upcoming'  => true,
						'demo_url'     => '',
						'doc_url'      => '',
						'youtube_url'  => '',
					),
				),
			),
		);

		return $extensions;
	}

	/**
	 * Include Widgets skins
	 *
	 * Load widgets skins
	 *
	 * @since 0.0.1
	 * @access private
	 */
	private function include_skins_files() {
		foreach ( self::get_widget_skins() as $slug => $data ) {

			// is widget all skins are not active
			if ( ! $data['is_active'] ) {
				continue;
			}

			foreach ( $data['skins'] as $skin_slug => $skin ) {
				if ( ! $skin['is_active'] ) {
					continue;
				}

				require_once WCF_ADDONS_PRO_WIDGETS_PATH . $slug . '/skins/' . $skin_slug . '.php';

				$class = explode( '-', $skin_slug );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class );
				$class = 'WCFAddonsPro\\Widgets\\Skin\\' . $class;

				// has base base skin dont need register
				if ( isset( $skin['is_base_skin'] ) ) {
					continue;
				}

				add_action(
					'elementor/widget/' . $data['widget_name'] . '/skins_init',
					function ( $widget ) use ( $class ) {
						$widget->add_skin( new $class( $widget ) );
					}
				);
			}
		}
	}

	/**
	 * Get Widgets List.
	 *
	 * @return array
	 */
	public static function get_widgets() {

		return array(
			// 'toggle-switcher'       => [
			// 'label'       => __('Toggle Switcher', 'animation-addons-for-elementor-pro'),
			// 'is_active'   => true,
			// 'is_upcoming' => false,
			// 'demo_url'    => '',
			// 'doc_url'     => '',
			// 'youtube_url' => '',
			// ],
			'youtube-video'         => array(
				'label'       => __( 'Youtube Video', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'advance-pricing-table' => array(
				'label'       => __( 'Advanced Pricing Table', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'image-compare'         => array(
				'label'       => __( 'Image Compare', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'mega-menu'             => array(
				'label'       => __( 'Mega Menu', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'button-pro'            => array(
				'label'       => __( 'Advanced Button', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'advanced-button'       => array(
				'label'       => __( 'Advance Button', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'scroll-elements'       => array(
				'label'       => __( 'Scroll Elements', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'advance-portfolio'     => array(
				'label'       => __( 'Advanced Portfolio', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'filterable-gallery'    => array(
				'label'       => __( 'Filterable Gallery', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'breadcrumbs'           => array(
				'label'       => __( 'Breadcrumbs', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'table-of-contents'     => array(
				'label'       => __( 'Table Of Content', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'image-accordion'       => array(
				'label'       => __( 'Image Accordion', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'author-box'            => array(
				'label'       => __( 'Author Box', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'flip-box'              => array(
				'label'       => __( 'Flip Box', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'advance-accordion'     => array(
				'label'       => __( 'Advanced Accordion', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'animated-offcanvas'    => array(
				'label'       => __( 'Animated Offcanvas', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'lottie'                => array(
				'label'       => __( 'WCF Lottie', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'draw-svg'              => array(
				'label'       => __( 'DrawSvg', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'posts-pro'             => array(
				'label'       => __( 'Advanced Posts', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'posts-filter'          => array(
				'label'       => __( 'Filterable Posts', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'post-rating-form'      => array(
				'label'       => __( 'Post Rating Form', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'post-rating'           => array(
				'label'       => __( 'Post Rating', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'feature-posts'         => array(
				'label'       => __( 'Post Featured Image', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'current-date'          => array(
				'label'       => __( 'Current Date', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'banner-posts'          => array(
				'label'       => __( 'Banner Posts', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'grid-hover-posts'      => array(
				'label'       => __( 'Grid Hover Posts', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'video-box'             => array(
				'label'       => __( 'Video box', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'video-box-slider'      => array(
				'label'       => __( 'Video box Slider', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'mailchimp'             => array(
				'label'       => __( 'Mailchimp', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'advanced-mailchimp'    => array(
				'label'       => __( 'Advanced Mailchimp', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'portfolio'             => array(
				'label'       => __( 'Portfolio box', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'video-mask'            => array(
				'label'       => __( 'Video mask', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'posts-slider'          => array(
				'label'       => __( 'Posts Slider', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'video-popup'           => array(
				'label'       => __( 'Video Popup', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'category-showcase'     => array(
				'label'       => __( 'Category Showcase', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'category-slider'       => array(
				'label'       => __( 'Category Slider', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'video-story'           => array(
				'label'       => __( 'Video Story', 'animation-addons-for-elementor-pro' ),
				'is_active'   => false,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'breaking-news-slider'  => array(
				'label'       => __( 'Breaking News Slider', 'animation-addons-for-elementor-pro' ),
				'is_active'   => false,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'post-reactions'        => array(
				'label'       => __( 'Post reactions', 'animation-addons-for-elementor-pro' ),
				'is_active'   => false,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'notification'          => array(
				'label'       => __( 'Notification', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'advanced-testimonial'  => array(
				'label'       => __( 'Advanced Testimonial', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'nested-slider'         => array(
				'label'       => __( 'Nested Slider', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'weather'               => array(
				'label'       => __( 'Weather', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'tiktok-feed'           => array(
				'label'       => __( 'Tiktok Feed', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'posts-read-later'      => array(
				'label'       => __( 'Posts Read Later', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'post-timeline'         => array(
				'label'       => __( 'Post Timeline', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'live-events'           => array(
				'label'       => __( 'Live Events', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'stacked-cards'         => array(
				'label'       => __( 'Stacked Cards', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'vertical-marquee'      => array(
				'label'       => __( 'Vertical Marquee', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'scrollable-video'      => array(
				'label'       => __( 'Scrollable Video', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'nested-motion-card'    => array(
				'label'       => __( 'Nested Motion Card', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'hero-text-explode'     => array(
				'label'       => __( 'Hero Text Explode', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
		);
	}

	/**
	 * Get Widget Skins List.
	 *
	 * @return array
	 */
	public static function get_widget_skins() {

		return apply_filters(
			'wcf_widget_skins',
			array(
				'advance-pricing-table' => array( // widget file/dir name.
					'label'       => __( 'Advanced Pricing Table', 'animation-addons-for-elementor-pro' ),
					'widget_name' => 'wcf--a-pricing-table',
					'is_active'   => true,
					'skins'       => array( // skin file names.
						'skin-pricing-table-base' => array(
							'is_active'    => true,
							'is_base_skin' => true,
						),
						'skin-pricing-table-1'    => array( 'is_active' => true ),
						'skin-pricing-table-2'    => array( 'is_active' => true ),
					),
				),
				'advance-portfolio'     => array( // widget file/dir name.
					'label'       => __( 'Advanced Portfolio', 'animation-addons-for-elementor-pro' ),
					'widget_name' => 'wcf--a-portfolio',
					'is_active'   => true,
					'skins'       => array(
						'skin-portfolio-base'  => array(
							'is_active'    => true,
							'is_base_skin' => true,
						),
						'skin-portfolio-one'   => array( 'is_active' => true ),
						'skin-portfolio-two'   => array( 'is_active' => true ),
						'skin-portfolio-three' => array( 'is_active' => true ),
						'skin-portfolio-four'  => array( 'is_active' => true ),
						'skin-portfolio-five'  => array( 'is_active' => true ),
						'skin-portfolio-six'   => array( 'is_active' => true ),
						'skin-portfolio-seven' => array( 'is_active' => true ),
						'skin-portfolio-eight' => array( 'is_active' => true ),
						'skin-portfolio-nine'  => array( 'is_active' => true ),
					),
				),
			)
		);
	}

	/**
	 * Get Widget Skins List.
	 *
	 * @param object $elements_manager Element Manager.
	 *
	 * @return void
	 */
	public function widget_categories( $elements_manager ) {
		$categories = array();

		$categories['animation-addons-for-elementor-pro'] = array(
			'title' => esc_html__( 'AAE Pro', 'animation-addons-for-elementor-pro' ),
			'icon'  => 'fa fa-plug',
		);

		$old_categories = $elements_manager->get_categories();

		$top_categories = array();
		foreach ( array( 'layout', 'basic' ) as $top_key ) {
			if ( isset( $old_categories[ $top_key ] ) ) {
				$top_categories[ $top_key ] = $old_categories[ $top_key ];
				unset( $old_categories[ $top_key ] );
			}
		}

		$categories = array_merge( $top_categories, $categories, $old_categories );

		$set_categories = function ( $categories ) {
			$this->categories = $categories;
		};

		$set_categories->call( $elements_manager, $categories );
	}

	/**
	 * Include Plugin files
	 *
	 * @access private
	 */
	private function include_files() {

		require_once WCF_ADDONS_PRO_PATH . 'inc/hook.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/global-elements.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/mega-menu/init.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/image-selector-control.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/posts/init.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/ajax-handler.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/trait-wcf-slider.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/trait-animation-builder.php';

		require_once WCF_ADDONS_PRO_PATH . 'inc/post-rating-handler.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/live-event-handler.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/free/one-page-nav-widget.php';
	
		require_once WCF_ADDONS_PRO_PATH . 'inc/youtube/module.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/tiktok/module.php';
		include_once WCF_ADDONS_PRO_PATH . 'inc/author-settings.php';

		// compatibility with an old version.
		require_once WCF_ADDONS_PRO_PATH . 'inc/compatible/wpml.php';
		require_once WCF_ADDONS_PRO_PATH . 'inc/compatible/icon-loader.php';
		if ( is_admin() ) {
			require_once WCF_ADDONS_PRO_PATH . 'inc/compatible/demo-import.php';
		}

		// extensions.
		$this->register_extensions();
        // WPML Support
		$wpml_file = WCF_ADDONS_PRO_PATH . 'inc/wpml-manager.php';

		if ( defined( 'ICL_SITEPRESS_VERSION' ) && file_exists( $wpml_file ) ) {
			include_once $wpml_file;
		}
	}

	/**
	 * Initialize the elementor plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function elementor_init() {
		add_action( 'elementor/kit/register_tabs', array( $this, 'register_setting_tabs' ) );

		$this->include_skins_files();
	}

	/**
	 * Register setting tabs.
	 *
	 * @param object $base Base.
	 *
	 * @return void
	 */
	public function register_setting_tabs( $base ) {
		$them_settings = array(
			'preloader'        => array(
				'label'       => __( 'Preloader', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'cursor'           => array(
				'label'       => __( 'Cursor', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'scroll-to-top'    => array(
				'label'       => __( 'Scroll to Top', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'scroll-indicator' => array(
				'label'       => __( 'Scroll Indicator', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
			'popup'            => array(
				'label'       => __( 'AAE Popup', 'animation-addons-for-elementor-pro' ),
				'is_active'   => true,
				'is_pro'      => true,
				'is_upcoming' => false,
				'demo_url'    => '',
				'doc_url'     => '',
				'youtube_url' => '',
			),
		);
	

		foreach ( $them_settings as $slug => $data ) {

			// If upcoming don't register.
			if ( $data['is_upcoming'] ) {
				continue;
			}

			// If not pro, don't register.
			if ( ! $data['is_pro'] ) {
				continue;
			}

			if ( $data['is_active'] ) {
				if ( is_dir( WCF_ADDONS_PRO_PATH . 'inc/settings/wcf-' . $slug ) ) {
					require_once WCF_ADDONS_PRO_PATH . 'inc/settings/wcf-' . $slug . '/wcf-' . $slug . '.php';
				} else {
					require_once WCF_ADDONS_PRO_PATH . 'inc/settings/wcf-' . $slug . '.php';
					
				}

				$key = 'settings-wcf-' . $slug;

				$class = explode( '-', $slug );
				$class = array_map( 'ucfirst', $class );
				$class = implode( '_', $class );
				$class = 'WCFAddonsPro\\Settings\\Tabs\\' . $class;
				$base->register_tab( $key, $class );
			}
		}
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		if ( function_exists( 'wcf__addons__pro__status' ) && wcf__addons__pro__status() ) {
			$this->include_files();
			add_action( 'elementor/elements/categories_registered', array( $this, 'widget_categories' ) );

			// Register widget scripts.
			add_action( 'wp_enqueue_scripts', array( $this, 'widget_scripts' ), 60 );

			// Register widget style.
			add_action( 'wp_enqueue_scripts', array( $this, 'widget_styles' ) );

			// Register widgets.
			add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ), 18 );

			// Register editor scripts.
			add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'editor_scripts' ), 99999 );

			// elementor loaded.
			add_action( 'elementor/init', array( $this, 'elementor_init' ), 0 );
			
			// WPML Support 
			add_filter( 'wpml_elementor_widgets_to_translate', [  WPML\WPML_Manager::class, 'add_widgets_to_translate' ] );
		}
	}
}

require_once WCF_ADDONS_PRO_PATH . 'inc/helper.php';
require_once WCF_ADDONS_PRO_PATH . 'inc/health-status.php';
if(file_exists(WCF_ADDONS_PRO_PATH . 'inc/core/update.php')){
	require_once WCF_ADDONS_PRO_PATH . 'inc/core/update.php';
}
require_once WCF_ADDONS_PRO_PATH . 'inc/core/dashboard.php';

// Instantiate Plugin Class.
Plugin::instance();
