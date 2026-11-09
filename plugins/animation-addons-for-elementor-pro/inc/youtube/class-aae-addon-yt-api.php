<?php

namespace WCFAddonsPro;
use WP_REST_Response;
use WP_REST_Request;
use WP_Error;
/**
 * YouTube API Client & REST routes
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}


if ( ! class_exists( 'AAE_Addon_YT_API' ) ) {
    class AAE_Addon_YT_API {

        /** @var string YouTube Data API key. */
        private $api_key;
        private $playlist_id;
        private $username;

        /** @var AAE_Addon_YT_Cache */
        public $cache;

        public function __construct() { 
         
            if(get_option('aae_youtube_video_advanced_settings')){
                $saved_option = get_option('aae_youtube_video_advanced_settings');
                $saved_option = json_decode( $saved_option, true );
                
                if(isset($saved_option['api_key']) && $saved_option['api_key'] !=''){
                    $this->api_key     = $saved_option['api_key'];
                }

                if(isset($saved_option['playlist_id']) && $saved_option['playlist_id'] !=''){
                    $this->playlist_id     = $saved_option['api_key'];
                }

                if(isset($saved_option['username']) && $saved_option['username'] !=''){
                    $this->username     = $saved_option['username'];
                }
            }
            
            $this->cache = new AAE_Addon_YT_Cache( $this->api_key );
            // Hook up our REST endpoints:
            add_action( 'rest_api_init', [ $this, 'register_routes' ] );
        }

        /**
         * Register our /aae-addon-yt/v1/videos route.
         */
        public function register_routes() {
               
            //playlist
            register_rest_route( 'aae-addon-yt/v1', '/videos', [
                'methods'             => 'GET',
                'callback'            => [ $this, 'fetch_videos' ],
                'permission_callback' => '__return_true',
            ] );

            //shorts
            register_rest_route( 'aae-addon-yt/v1', '/shorts', [
                'methods'             => 'GET',
                'callback'            => [ $this, 'shorts_list' ],
                'permission_callback' => '__return_true',              
            ] );

            register_rest_route( 'aae-addon-yt/v1', '/username-videos', [
                'methods'             => 'GET',
                'callback'            => [ $this, 'username_videos' ],
                'permission_callback' => '__return_true',               
            ] );
             // new status route
            register_rest_route( 'aae-addon-yt/v1', '/status', [
                'methods'             => 'GET',
                'callback'            => [ $this, 'api_status' ],
                'permission_callback' => '__return_true',
            ] );

            register_rest_route( 'aae-addon-yt/v1', '/playlist-status', [
                'methods'             => 'GET',
                'callback'            => [ $this, 'playlist_status' ],
                'permission_callback' => '__return_true',
            ] );

             // New: username → channel ID
            register_rest_route(
                'aae-addon-yt/v1',
                '/username-to-id',
                [
                    'methods'             => 'GET',
                    'callback'            => [ $this, 'username_to_id' ],
                    'permission_callback' => '__return_true'                    
                ]
            );
        }  

         /**
         * GET /wp-json/aae-addon-yt/v1/shorts
         *
         * @param WP_REST_Request $request
         * @return WP_REST_Response|WP_Error
         */
        public function shorts_list( WP_REST_Request $request ) {
            $username  = $this->username;
            if($request->get_param( 'maxResults' )){
                $maxResults = $request->get_param( 'maxResults' );
            }else{
                $maxResults = 10;
            }          
           if($request->get_param( 'nextpage' )){
                $pageToken = $request->get_param( 'nextpage' );
            }else{
               $pageToken  = '';
            }    
            
            $data = $this->cache->get_short_videos( $username, $maxResults, $pageToken );
            if ( ! $data ) {
                return new WP_Error(
                    'aae_yt_no_shorts',
                    'Could not fetch Shorts for the given parameters.',
                    [ 'status' => 404 ]
                );
            }

            return rest_ensure_response( $data );
        }
        
        /**
         * GET /wp-json/aae-addon-yt/v1/username-videos?username=…
         *
         * @param WP_REST_Request $request
         * @return WP_REST_Response|WP_Error
         */
        public function username_videos( WP_REST_Request $request ) {
            
            if($request->get_param( 'username' )){
                $username = $request->get_param( 'username' );
            }else{
                $username = $this->username;
            }

            if($request->get_param( 'maxResults' )){
                $maxResults = $request->get_param( 'maxResults' );
            }else{
                $maxResults = 10;
            }          
           if($request->get_param( 'nextpage' )){
                $pageToken = $request->get_param( 'nextpage' );
            }else{
               $pageToken  = '';
            }   

            $videos   = $this->cache->get_videos_by_username( $username , $pageToken , $maxResults);

            if ( $videos === false ) {
                return new WP_Error(
                    'aae_yt_no_videos',
                    "Could not fetch videos for username {$username}.",
                    [ 'status' => 404 ]
                );
            }

            return rest_ensure_response( $videos );
        }

         /**
         * GET /wp-json/aae-addon-yt/v1/username-to-id?username=…
         *
         * @param WP_REST_Request $request
         * @return WP_REST_Response|WP_Error
         */
        public function username_to_id( WP_REST_Request $request ) {
            if($request->get_param( 'username' )){
                $username = sanitize_text_field( $request->get_param( 'username' ) );
            }else{
                $username = $this->username;
            } 
            if ( empty( $username ) ) {
                return new WP_Error(
                    'aae_yt_missing_username',
                    'The `username` parameter is required.',
                    [ 'status' => 400 ]
                );
            }

            $channel_id = $this->cache->get_channel_id_by_username( $username );
            if ( ! $channel_id ) {
                return new WP_Error(
                    'aae_yt_username_not_found',
                    "Could not resolve username `{$username}`.",
                    [ 'status' => 404 ]
                );
            }

            return rest_ensure_response( [
                'username'  => $username,
                'channelId' => $channel_id,
            ] );
        }

         /**
         * GET /wp-json/aae-addon-yt/v1/playlist-status?playlistId=…
         */
        public function playlist_status( \WP_REST_Request $request ) {

            if($request->get_param( 'playlistId' )){
                $playlist_id = sanitize_text_field( $request->get_param( 'playlistId' ) );
            }else{
                $playlist_id = sanitize_text_field( $this->playlist_id );
            }           
            
            if ( empty( $playlist_id ) ) {
                return new WP_Error(
                    'aae_yt_no_playlist',
                    'Parameter playlistId is required.',
                    [ 'status' => 400 ]
                );
            }

            $status = $this->cache->get_playlist_status( $playlist_id );
            if ( ! $status ) {
                return new WP_Error(
                    'aae_yt_status_not_found',
                    'Unable to retrieve playlist status.',
                    [ 'status' => 404 ]
                );
            }

            return rest_ensure_response( $status );
        }

        /**
         * GET /wp-json/aae-addon-yt/v1/status
         *   → { status: "ok" } or WP_Error
         */
        public function api_status( \WP_REST_Request $request ) {
            if ( empty( $this->api_key ) ) {
                return new WP_Error(
                    'aae_yt_no_key',
                    'YouTube API key is not configured.',
                    [ 'status' => 500 ]
                );
            }

            if($request->get_param( 'key' )){
                $this->api_key = sanitize_text_field( $request->get_param( 'key' ) );
            }

            // Ping Google’s discovery endpoint to verify reachability
            $discovery_url = add_query_arg(
                'key',
                $this->api_key,
                'https://www.googleapis.com/discovery/v1/apis/youtube/v3/rest'
            );

            $resp = wp_remote_get( $discovery_url, [ 'timeout' => 10 ] );
            if ( is_wp_error( $resp ) || wp_remote_retrieve_response_code( $resp ) !== 200 ) {
                delete_option( 'aae_yt_api_key' );
                return new WP_Error(
                    'aae_yt_unreachable',
                    'Unable to reach YouTube API.',
                    [ 'status' => 502 ]
                );
            }
            update_option( 'aae_yt_api_key', $this->api_key );
            return rest_ensure_response( [
                'status'        => 'ok',
                'api_key_set'   => true,
                'api_endpoint'  => 'youtube/v3',
            ] );
        }    

        /**
         * Handle GET /wp-json/aae-addon-yt/v1/videos?playlistId=...
         * long video
         */
        public function fetch_videos( \WP_REST_Request $request ) {
            
            if($request->get_param( 'playlistId' )){
                $playlist_id = sanitize_text_field( $request->get_param( 'playlistId' ) );
            }else{
                $playlist_id = sanitize_text_field( $this->playlist_id );
            }
           
            if ( empty( $playlist_id ) ) {
                return new \WP_REST_Response( 'playlistId is required', 400 );
            }

            if($request->get_param( 'maxResults' )){
                $maxResults = $request->get_param( 'maxResults' );
            }else{
                $maxResults = 10;
            }    

            if($request->get_param( 'nextpage' )){
                $pageToken = $request->get_param( 'nextpage' );
            }else{
               $pageToken  = '';
            }  

            $videos = $this->cache->get_playlists_videos_Page( $playlist_id, $pageToken, $maxResults );

            if ( empty( $videos ) ) {
                return new \WP_REST_Response( 'No videos found', 404 );
            }

            return rest_ensure_response( $videos );
        }
    }
}

