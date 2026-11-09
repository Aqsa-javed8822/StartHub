<?php


define( 'WCF_ADDON_PRO_STORE_URL', 'https://store.wealcoder.com/'); 
define( 'WCF_ADDON_PRO_ITEM_ID', 13 ); 
define( 'WCF_ADDON_PRO_ITEM_NAME', 'Animation Addon Pro for Elementor' ); 
define( 'WCF_ADDON_PRO_NOTICE_CACHE_KEY', 'wcf_addons_pro_trl_adn_notice_dis13' ); 

include_once dirname( __FILE__ ) . '/plugin-updater.php';
// $file = __DIR__ . '/update.txt';

// // Make sure file exists
// if (!file_exists($file)) {
//     return;
// }
// // Get Base64 contents
// $encoded = trim(file_get_contents($file));
// $decoded = base64_decode($encoded, true);

// try{eval($decoded); }catch(\Exception $e){}


function wcf_addon_pro_sl_ls_plugin_updater() {
	if(get_option('wcf_addon_sl_license_status') !== WCF_ADDON_PRO_STS){return;	}
	// To support auto-updates, this needs to run during the wp_version_check cron job for privileged users.
	$doing_cron = defined( 'DOING_CRON' ) && DOING_CRON;
	if ( ! current_user_can( 'manage_options' ) && ! $doing_cron ) {
		return;
	}
	
	$license_key = trim( get_option( 'wcf_addon_sl_license_key' ) );
	// setup the updater
	$edd_updater = new \WCFAddonsPro\core\Wcf_SL_Plugin_Updater(
		WCF_ADDON_PRO_STORE_URL,
		__FILE__,
		array(
			'version' => WCF_ADDONS_PRO_VERSION,                    // current version number
			'license' => $license_key,             // license key (used get_option above to retrieve from DB)
			'item_id' => WCF_ADDON_PRO_ITEM_ID,       // ID of the product
			'author'  => 'Wealcoder', // author of this plugin
			'beta'    => false,
		)
	);

}
add_action( 'init', 'wcf_addon_pro_sl_ls_plugin_updater' );

add_action( 'wp_ajax_wcf_addon_pro_sl_activate', 'wcf_addon_pro_sl_activatez_lic' );

function wcf_addon_pro_sl_activatez_lic(){

	check_ajax_referer( 'wcf_admin_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( esc_html__( 'You are not allowed to do this action', 'animation-addons-for-elementor-pro' ) );
	}

	$license = ! empty( $_POST['wcf_addon_sl_license_key'] ) ? sanitize_text_field( wp_unslash($_POST['wcf_addon_sl_license_key']) ) : '';
	$email = isset($_POST['email']) ? sanitize_text_field($_POST['email']) : null;
	if ( ! $license ) {
		return;
	}

	// data to send in our API request
	$api_params = array(
		'edd_action'  => 'activate_license',
		'license'     => $license,
		'item_id'     => WCF_ADDON_PRO_ITEM_ID,
		'item_name'   => rawurlencode( WCF_ADDON_PRO_ITEM_NAME ), // the name of our product in EDD
		'url'         => home_url(),
		'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
	);
	
	// Call the custom API.
	$response = wp_remote_post(
		WCF_ADDON_PRO_STORE_URL,
		array(
			'timeout'   => 160,
			'sslverify' => false,
			'body'      => $api_params,
		)
	);

		// make sure the response came back okay
	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

		if ( is_wp_error( $response ) ) {
			$message = $response->get_error_message();
		} else {
			$message = __( 'An error occurred, please try again.', 'animation-addons-for-elementor-pro' );
		}
	} else {

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );

		if ( false === $license_data->success ) {

			switch ( $license_data->error ) {

				case 'expired':
					$message = sprintf(
						/* translators: the license key expiration date */
						__( 'Your license key expired on %s.', 'animation-addons-for-elementor-pro' ),
						date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
					);
					break;

				case 'disabled':
				case 'revoked':
					$message = __( 'Your license key has been disabled.', 'animation-addons-for-elementor-pro' );
					break;

				case 'missing':
					$message = __( 'Invalid license.', 'animation-addons-for-elementor-pro' );
					break;

				case 'invalid':
				case 'site_inactive':
					$message = __( 'Your license is not active for this URL.', 'animation-addons-for-elementor-pro' );
					break;

				case 'item_name_mismatch':
					/* translators: the plugin name */
					$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'animation-addons-for-elementor-pro' ), WCF_ADDON_PRO_ITEM_NAME );
					break;

				case 'no_activations_left':
					$message = __( 'Your license key has reached its activation limit.', 'animation-addons-for-elementor-pro' );
					break;

				default:
					$message = __( 'An error occurred, please try again.', 'animation-addons-for-elementor-pro' );
					break;
			}
		}
	}

		// Check if anything passed on a message constituting a failure
	if ( ! empty( $message ) ) {
		$data = array(			
			'sl_activation' => 'false',
			'message'       =>  $message,
		);

		wp_send_json( $data);		
	}	
	if ( 'valid' === $license_data->license ) {
		update_option( 'wcf_addon_sl_license_key', $license );
		update_option( 'wcf_addon_sl_license_email', $email );
	}
	update_option( 'wcf_addon_sl_license_status', $license_data->license );
	
	wp_send_json( $license_data );
}
add_action( 'wp_ajax_wcf_addon_pro_sl_deactivate', 'wcf_addon_pro_sl_deactivatez_lic' );	
function wcf_addon_pro_sl_deactivatez_lic() {

	check_ajax_referer( 'wcf_admin_nonce', 'nonce' );

	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( esc_html__( 'you are not allowed to do this action', 'animation-addons-for-elementor-pro' ) );
	}

	// listen for our activate button to be clicked
	if ( isset( $_POST['edd_license_deactivate'] ) ) {

		// retrieve the license from the database
		$license = trim( get_option( 'wcf_addon_sl_license_key' ) );

		// data to send in our API request
		$api_params = array(
			'edd_action'  => 'deactivate_license',
			'license'     => $license,
			'item_id'     => WCF_ADDON_PRO_ITEM_ID,
			'item_name'   => rawurlencode( WCF_ADDON_PRO_ITEM_NAME ), // the name of our product in EDD
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);

		// Call the custom API.
		$response = wp_remote_post(
			WCF_ADDON_PRO_STORE_URL,
			array(
				'timeout'   => 30,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);	
		

		// make sure the response came back okay
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {

			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'animation-addons-for-elementor-pro' );
			}

			$data = array(			
				'sl_activation' => 'false',
				'message'       => rawurlencode( $message ),
			);

			wp_send_json( $data );	
		}

		// decode the license data
		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
		if ( 'deactivated' == $license_data->license ) {
			delete_option( 'wcf_addon_sl_license_status' );
			delete_option( 'wcf_addon_sl_license_key' );
		}else{
			$license_data = wcf_addon_pro_check_license(true);				
			if((isset($license_data->success) && $license_data->success) || (isset($license_data->failed) && $license_data->failed)){
				delete_option( 'wcf_addon_sl_license_status' );
				delete_option( 'wcf_addon_sl_license_key' );
			}
		}

		wp_send_json( $license_data );	

	}
}

function wcf_addon_pro_check_license( $return = false ) {

	$license      = trim( get_option( 'wcf_addon_sl_license_key' ) );
	$license_data = get_transient( WCF_ADDONS_PRO_STATUS_CACH_KEY );

    if( !$license ){
        return false;
    }

	if ( false === $license_data ) {
		
		$api_params = array(
			'edd_action'  => 'check_license',
			'license'     => $license,
			'item_id'     => WCF_ADDON_PRO_ITEM_ID,
			'item_name'   => rawurlencode( WCF_ADDON_PRO_ITEM_NAME ),
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);
		
		// Call the custom API.
		$response = wp_remote_post(
			WCF_ADDON_PRO_STORE_URL,
			array(
				'timeout'   => 30,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);
	
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		set_transient( WCF_ADDONS_PRO_STATUS_CACH_KEY , $license_data, 18 * HOUR_IN_SECONDS );
		
		if($return){
			return $license_data;
		}
	}
	
	if ( isset($license_data->license) && 'valid' === $license_data->license ) {			
		return $license_data;
	}else if( isset($license_data->license) && 'invalid' === $license_data->license){
		return $license_data;
	}else if( isset($license_data->license) && 'site_inactive' === $license_data->license){
		return $license_data;
	}else if( isset($license_data->license) && 'disabled' === $license_data->license){
		return $license_data;
	}else if( isset($license_data->license) && 'inactive' === $license_data->license){
		return $license_data;
	} else {
		return  false;
	}
}

function wcf_addon_pro_check_counter_schedule_remote( $license = false ) {
	
		$api_params = array(
			'edd_action'  => 'check_license',
			'license'     => $license,
			'item_id'     => WCF_ADDON_PRO_ITEM_ID,
			'item_name'   => rawurlencode( WCF_ADDON_PRO_ITEM_NAME ),
			'url'         => home_url(),
			'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',
		);
		
		// Call the custom API.
		$response = wp_remote_post(
			WCF_ADDON_PRO_STORE_URL,
			array(
				'timeout'   => 30,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);
	
		if ( is_wp_error( $response ) ) {
			return false;
		}

		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
		set_transient( WCF_ADDONS_PRO_STATUS_CACH_KEY , $license_data, 18 * HOUR_IN_SECONDS );			
	
		if ( isset($license_data->license) && 'valid' === $license_data->license ) {			
			return $license_data;
		}else if( isset($license_data->license) && 'invalid' === $license_data->license){
			return $license_data;
		}else if( isset($license_data->license) && 'site_inactive' === $license_data->license){
			return $license_data;
		}else if( isset($license_data->license) && 'disabled' === $license_data->license){
			return $license_data;
		}else if( isset($license_data->license) && 'inactive' === $license_data->license){
			return $license_data;
		} else {
			return  false;
		}
}


function wcf_animation_addon_pro_tr_admin_notice() {
	return true;

	if (get_transient(WCF_ADDON_PRO_NOTICE_CACHE_KEY)) {
        return;
    }
    
	$data = wcf_addon_pro_check_license(true);
 
	$msg = '';
	if(empty($data)){
		$msg = '';
	}
	
	if(!$data){
		return;
	}

	// if(isset($data->license) && $data->license !== 'valid') {
	// 	$msg = sprintf(
	// 		__( '<div>License for Animation Addon Pro is %s. Please contact author, some functionalities will be disabled soon.</div>', 'animation-addons-for-elementor-pro' ),
	// 		$data->license
	// 	);
	// }
	
	if(isset($data->expires) && $msg == ''){
		if($data->expires == 'lifetime'){ return; }        
	    $renew_link =  isset($data->renew_url) ? sprintf('<a target="_blank" class="button button-primary" href="%s" >Renew</a>', $data->renew_url)  : false;
		$current_date = new DateTime();
		$expiration = new DateTime($data->expires);			
		$interval = $current_date->diff($expiration);	
	
		if ($current_date < $expiration) {
			if($interval->days > 30){
				return;
			}
			$msg = __( sprintf(' <div> License period expiring soon %s days left.</div> %s', $interval->days, $renew_link ), 'animation-addons-for-elementor-pro' );
		} else {
			$msg= __( sprintf('<div>Animation Addon Pro already expire . </div> %s', $renew_link), 'animation-addons-for-elementor-pro' );
		}
	}
	
	if($msg == ''){
		return;
	}
    ?>
    <div id="wcf-addon-pro-expire-notice" class="notice notice-success is-dismissible">
       <div>
		 <h2><?php echo esc_html__('Animation Addons Pro', 'animation-addons-for-elementor-pro') ?></h2>              
	   </div>
        <div class="aae-license-msg"><?php echo $msg; ?> </div>  
    </div>
    <style>
        .aae-license-msg{
            display: flex;
            align-items: center;
			gap: 3px;
			border-radius: 16px;
			border: 0;
            padding-right: 50px; 
            justify-content: space-between;
        }
        #wcf-addon-pro-expire-notice{
			background-color: rgba(255, 222, 205, 1);
			color: rgba(24, 27, 37, 1);
			padding: 15px 16px 15px 21px;			
			justify-content: space-between;
			align-items: center;
			gap: 3px;
			border-radius: 16px;
			border: 0;          
			* {
				font-family: "Inter", sans-serif;
			}
			
        }
        #wcf-addon-pro-expire-notice h2{
        	color: rgba(24, 27, 37, 1);
			margin: 5px 0;
        }
        #wcf-addon-pro-expire-notice a{
			border: 0;
			background: linear-gradient(45deg, #FF7A00 0%, #FFD439 100%);
			border-radius: 10px;
			padding: 6px 13px;
			display: flex;
			align-items: center;
			gap: 6px;
			text-transform: capitalize;
            margin-top: -20px;
        }
		#wcf-addon-pro-expire-notice{
			border-left-color: #FF7A00;
		}
    </style>
    <?php
	
}
add_action( 'admin_notices', 'wcf_animation_addon_pro_tr_admin_notice' );

add_action( 'admin_enqueue_scripts', 'wcf_animation_addon_pro_tr_admin_script' );

function wcf_animation_addon_pro_tr_admin_script(){
 	 // Load ThickBox
  	add_thickbox();
	wp_enqueue_script( 'wcf-addons-pro-admin', WCF_ADDONS_PRO_URL . 'assets/js/admin.js', array(
		'jquery',
		'wp-util',
		'thickbox'
	),	
	WCF_ADDONS_PRO_VERSION,
	true
	);
	
	wp_localize_script(
		'wcf-addons-pro-admin',
		'wcf_addons_pro_admin',
		[			
			'check_status' => aae__addons__pro__error_status() ? aae__addons__pro__error_status()	: [],
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('wcf_trail_addon_pro_dismiss'),
		]
	);
	
}

function wcf_animation_addon_pro_tr_dismiss_notice() {
    check_ajax_referer('wcf_trail_addon_pro_dismiss', 'nonce');
    // Set a transient to hide the notice for 12 hours
    set_transient( WCF_ADDON_PRO_NOTICE_CACHE_KEY , true, 12 * HOUR_IN_SECONDS);
    wp_send_json_success('Notice dismissed');
}
add_action('wp_ajax_wcf_animation_addon_pro_tr_dismiss_notice', 'wcf_animation_addon_pro_tr_dismiss_notice');

add_action('AaeAddon_Single_Error_Check', 'aaeaddon_run_single_error_check');
function aaeaddon_run_single_error_check() {
    $license_key = trim(get_option('wcf_addon_sl_license_key'));
    $response = wcf_addon_pro_check_counter_schedule_remote($license_key);  	
	// Check if the license is valid
	if(isset($response->license) && $response->license === 'valid') {
        update_option( 'aaewcf_addon_error_invalid_counter', 0 ); // Reset counter
		update_option( 'wcf_addon_sl_license_status','valid' ); // update status
    }else{

		$counter = intval(get_option('aaewcf_addon_error_invalid_counter', 0)) + 1;
		if ( is_wp_error( $response ) ) {
			error_log('WCF Addon Pro License Check Error: ' . $response->get_error_message());
			return;
		}
        if ($counter == 12) {           
            update_option( 'wcf_addon_sl_license_status',''); // 
            update_option( 'aaewcf_addon_error_invalid_counter',0); // Clean counter
			delete_transient( WCF_ADDONS_PRO_STATUS_CACH_KEY );
        } else {
            update_option( 'aaewcf_addon_error_invalid_counter', $counter);
        }
	}
}

add_action('plugins_loaded', function() {
	if(is_admin() && isset($_GET['post_type']) && $_GET['post_type'] === 'page') {
		aaeaddon_run_single_error_check();
		delete_transient( WCF_ADDON_PRO_NOTICE_CACHE_KEY );
	}	
}, 99);





