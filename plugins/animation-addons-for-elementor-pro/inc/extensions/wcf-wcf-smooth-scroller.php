<?php
/**
 * WCF_Smooth_Scroller extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Controls_Manager;
use Elementor\Core\DocumentTypes\PageBase;

defined( 'ABSPATH' ) || die();

class WCF_Smooth_Scroller {

	public static function init() {
		add_action( 'elementor/documents/register_controls', [
			__CLASS__,
			'register_sm_scroll_controls'
		],20 );
		add_filter('wcf-addons/js/data', [
			__CLASS__,
			'push_settings_to_frontend'
		],20);
	}

	public static function push_settings_to_frontend($data) {
		$page_settings_manager = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' );
		$page_settings_model = $page_settings_manager->get_model( get_queried_object_id() );
		$aae_disable_smoother = $page_settings_model->get_settings( 'aae_disable_smoother' );
		if($aae_disable_smoother){
			$data['page_smoother']['disable'] = $aae_disable_smoother == 'yes' ? true : false;
		}
		$data['page_smoother']['disableInEditor'] = aae_is_smoother_disabled_in_editor();
		$data['isLoggedIn'] = is_user_logged_in();
		return $data;
	}

	public static function register_sm_scroll_controls( $document ) {
	    // only for “Pages” (or Posts—anything that uses PageBase)
		if ( ! $document instanceof PageBase ) {
			return;
		}
	
		// start a new section under the Style tab (or TAB_SETTINGS, TAB_LAYOUT, etc)
		$document->start_controls_section(
			'aae_page_settings_smoother',
			[
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'ScrollSmoother', 'animation-addons-for-elementor-pro' ) ),
				// choose TAB_SETTINGS, TAB_STYLE, or TAB_ADVANCED
				'tab'   => Controls_Manager::TAB_SETTINGS,
			]
		);
	
		// add your custom control
		$document->add_control(
			'aae_disable_smoother',
			[
				'label'        => __( 'Disable', 'animation-addons-for-elementor-pro' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'animation-addons-for-elementor-pro' ),
				'label_off'    => __( 'No',  'animation-addons-for-elementor-pro' ),
				'return_value' => 'yes',
				'default'      => '',
				'render_type' => 'ui',
				'description'  => __( 'Disable ScrollSmoother For This Page','animation-addons-for-elementor-pro'),
			]
		);

		$document->end_controls_section();
	}
}

WCF_Smooth_Scroller::init();

/**
 * Whether ScrollSmoother should be disabled while editing in Elementor.
 * Reads the global dashboard option (Scroll Smoother modal → "Disable in Editor Mode").
 * Can be overridden via the filter `aae/smoother/disable_in_editor`.
 *
 * @return bool
 */
if ( ! function_exists( 'aae_is_smoother_disabled_in_editor' ) ) {
	function aae_is_smoother_disabled_in_editor() {
		// Default: disabled in editor. User can explicitly enable via dashboard switch.
		$disabled = true;

		// 1. Dashboard modal option (wcf_smooth_scroller JSON blob)
		$raw = get_option( 'wcf_smooth_scroller', '' );
		if ( ! empty( $raw ) ) {
			$decoded = is_string( $raw ) ? json_decode( $raw, true ) : (array) $raw;
			if ( is_array( $decoded ) && array_key_exists( 'disableInEditor', $decoded ) ) {
				$disabled = (bool) $decoded['disableInEditor'];
			}
		}

		// 2. Standalone simple toggle (works without React rebuild).
		// Presence of this option overrides the default ONLY if explicitly set to a value.
		$standalone = get_option( 'aae_disable_smoother_in_editor', null );
		if ( $standalone !== null && $standalone !== false ) {
			$disabled = (bool) $standalone;
		}

		return (bool) apply_filters( 'aae/smoother/disable_in_editor', $disabled );
	}
}
