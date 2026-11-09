<?php
/**
 * Wrapper link extension class.
 */

namespace WCFAddonsPro\Extensions;

use Elementor\Element_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Plugin;
use Elementor\Utils;

defined( 'ABSPATH' ) || die();

class WCF_Wrapper_link {

	public static function init() {
		add_action(
			'elementor/element/container/section_layout/after_section_end',
			array(
				__CLASS__,
				'register_wrapper_controls',
			),
			10
		);
		add_action( 'elementor/frontend/before_render', array( __CLASS__, 'before_render' ), 1 );
	}

	public static function enqueue_scripts() {
	}

	public static function register_wrapper_controls( $element ) {

		$element->start_controls_section(
			'_section_wcf_wrapper_link_area',
			array(
				'label' => sprintf( '<i class="wcf-logo"></i> %s <span class="wcfpro_text aae-icon-unlock"><span>', __( 'Wrapper Link', 'animation-addons-for-elementor-pro' )),
				'tab'   => Controls_Manager::TAB_ADVANCED,
			)
		);

		$element->add_control(
			'wcf_enable_wrapper_link',
			array(
				'label' => __( 'Enable', 'animation-addons-for-elementor-pro' ),
				'type'  => Controls_Manager::SWITCHER,
			)
		);

		$element->add_control(
			'wcf_wrapper_link',
			array(
				'label'     => esc_html__( 'Link', 'animation-addons-for-elementor-pro' ),
				'type'      => Controls_Manager::URL,
				'options'   => array( 'url', 'is_external', 'nofollow' ),
				'condition' => array(
					'wcf_enable_wrapper_link!' => '',
				),
				'dynamic'   => array( 'active' => true ),
				'assets'    => array(
					'scripts' => array(
						array(
							'name'       => 'adv-wrapper-links-container',
							'conditions' => array(
								'terms' => array(
									array(
										'name'     => 'wcf_enable_wrapper_link',
										'operator' => '===',
										'value'    => 'yes',
									),
								),
							),
						),
					),
				),
			)
		);

		$element->end_controls_section();
	}

	public static function before_render( $element ) {
		if ( empty( $element->get_settings( 'wcf_enable_wrapper_link' ) ) ) {
			return;
		}

		// Use get_settings_for_display to properly resolve dynamic tags
		$wrapper_link = $element->get_settings_for_display( 'wcf_wrapper_link' );
		
		// Handle both array format and string format (when dynamic tag returns just URL)
		if ( is_string( $wrapper_link ) ) {
			$wrapper_link = array(
				'url' => $wrapper_link,
			);
		}
		
		$attributes = array();

		if ( ! empty( $wrapper_link['url'] ) ) {
			$allowed_protocols = array_merge( wp_allowed_protocols(), array( 'skype', 'viber' ) );

			$attributes['href'] = esc_url( $wrapper_link['url'], $allowed_protocols );
		}

		// Return early if no valid URL
		if ( empty( $attributes['href'] ) ) {
			return;
		}

		if ( ! empty( $wrapper_link['is_external'] ) ) {
			$attributes['target'] = '_blank';
		}

		if ( ! empty( $wrapper_link['nofollow'] ) ) {
			$attributes['rel'] = 'nofollow';
		}

		$element->add_render_attribute(
			'_wrapper',
			'data-wcf-wrapper-link',
			wp_json_encode( $attributes )
		);
	}
}

WCF_Wrapper_link::init();