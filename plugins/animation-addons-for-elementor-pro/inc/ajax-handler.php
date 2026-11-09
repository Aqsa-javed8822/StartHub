<?php

namespace WCFAddonsPro;

use Elementor\Plugin;

defined( 'ABSPATH' ) || die();

/**
 * Ajax Handler class.
 *
 * @package WCFAddonsPro
 */
class Ajax_Handler {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public static function init() {
		add_action( 'wp_ajax_mailchimp_api', array( __CLASS__, 'mailchimp_lists' ) );
		add_action( 'wp_ajax_nopriv_mailchimp_api', array( __CLASS__, 'mailchimp_lists' ) );

		add_action( 'wp_ajax_wcf_mailchimp_ajax', array( __CLASS__, 'mailchimp_prepare_ajax' ) );
		add_action( 'wp_ajax_nopriv_wcf_mailchimp_ajax', array( __CLASS__, 'mailchimp_prepare_ajax' ) );

		add_action( 'wp_ajax_wcf_mailchimp_list_fields', array( __CLASS__, 'wcf_mailchimp_list_fields' ) );
		add_action( 'wp_ajax_nopriv_wcf_mailchimp_list_fields', array( __CLASS__, 'wcf_mailchimp_list_fields' ) );
	}

	/**
	 * Mailchimp subscriber all list handler Ajax call.
	 *
	 * @return void
	 */
	public static function mailchimp_lists() {

		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'wcf-addons-editor' ) ) {
			exit( 'No naughty business please' );
		}

		$api = ! empty( $_REQUEST['api'] ) ? $_REQUEST['api'] : '';
		update_option( 'aae_mailchimp_api', $api );
		$response = Widgets\Mailchimp\Mailchimp_Api::get_mailchimp_lists( $api );

		wp_send_json( $response );
	}

	/**
	 * Mailchimp subscriber all list handler Ajax call.
	 *
	 * @return void
	 */
	public static function wcf_mailchimp_list_fields() {

		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'wcf-addons-editor' ) ) {
			exit( 'No naughty business please' );
		}

		$api     = ! empty( $_REQUEST['api'] ) ? $_REQUEST['api'] : '';
		$list_id = ! empty( $_REQUEST['list_id'] ) ? $_REQUEST['list_id'] : '';

		$response = Widgets\Mailchimp\Mailchimp_Api::get_form_fields( $api, $list_id );

		wp_send_json( $response );
	}

	/**
	 * Mailchimp subscriber handler Ajax call.
	 *
	 * @return void
	 */
	public static function mailchimp_prepare_ajax() {
		if ( ! isset( $_REQUEST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'wcf-addons-frontend' ) ) {
			exit( 'No naughty business please' );
		}

		parse_str( isset( $_POST['subscriber_info'] ) ? wp_unslash( $_POST['subscriber_info'] ) : '', $subscriber );

		$response = Widgets\Mailchimp\Mailchimp_Api::insert_subscriber_to_mailchimp( $subscriber );

		wp_send_json( $response );
	}
}

Ajax_Handler::init();
