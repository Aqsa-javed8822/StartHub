<?php

namespace WCFAddonsPro;
/**
 * YouTube Data Cache Layer (uses WP Transients)
 *
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'AAE_Addon_YT_Cache' ) ) {

    class AAE_Addon_YT_Cache {

        /** @var string */
        private $api_key;

        public function __construct( $api_key ) {
            $this->api_key = $api_key;
        }

        /**
         * Fetch (and cache) a page of videos from a YouTube playlist.
         *
         * @param string $playlist_id  The YouTube playlist ID.
         * @param string $page_token   Optional. Opaque cursor from previous page.
         * @param int    $max_results  Optional. How many items per page (1–50).
         * @return array|false         [
         *     'items'         => array of playlistItems,
         *     'nextPageToken' => string or '',
         *     'prevPageToken' => string or '',
         * ] or false on error.
         */
        public function get_playlists_videos_Page( $playlist_id, $page_token = '', $max_results = 10 ) {
            $playlist_id  = sanitize_text_field( $playlist_id );
            $max_results  = min( 50, absint( $max_results ) );
            $page_token   = sanitize_text_field( $page_token );

            // Build a unique transient key per playlist‐page
            $cache_key = 'aae_yt_pl_' . md5( "{$playlist_id}|{$page_token}|{$max_results}" );

            // Return cached if available
            if ( false !== ( $cached = get_transient( $cache_key ) ) ) {
                return $cached;
            }

            // Build query args
            $args = [
                'part'       => 'snippet,contentDetails',
                'playlistId' => $playlist_id,
                'maxResults' => $max_results,
                'key'        => $this->api_key,
            ];
            if ( $page_token ) {
                $args['pageToken'] = $page_token;
            }

            // Perform the request
            $url  = add_query_arg( $args, 'https://www.googleapis.com/youtube/v3/playlistItems' );
            $resp = wp_remote_get( $url, [ 'timeout' => 15 ] );
            if ( is_wp_error( $resp ) || 200 !== wp_remote_retrieve_response_code( $resp ) ) {
                return false;
            }

            $body = json_decode( wp_remote_retrieve_body( $resp ), true );
            if ( empty( $body['items'] ) ) {
                return false;
            }

            // Prepare the result
            $result = [
                'items'         => $body['items'],
                'nextPageToken' => $body['nextPageToken'] ?? '',
                'prevPageToken' => $body['prevPageToken'] ?? '',
            ];

            // Cache for 1 day
            set_transient( $cache_key, $result, DAY_IN_SECONDS );

            return $result;
        }       

        /**
         * Fetch (and cache) videos by YouTube username, via search.list
         * (no playlist ID needed).
         *
         * @param string $username     YouTube username or custom URL name.
         * @param string $page_token   Optional. Opaque cursor from previous page.
         * @param int    $max_results  Optional. How many items per page (1–50).
         * @return array|false         [
         *     'items'         => array of video snippets,
         *     'nextPageToken' => string or '',
         *     'prevPageToken' => string or '',
         * ] or false on error.
         */
        public function get_videos_by_username( $username, $page_token = '', $max_results = 10 ) {
            $username    = sanitize_text_field( $username );
            $max_results = min( 50, absint( $max_results ) );

            // 1) Resolve username → channel ID (30-day cache)
            $user_key = 'aae_yt_userid_' . md5( $username );
            if ( false === ( $channel_id = get_transient( $user_key ) ) ) {
                $channel_id = $this->get_channel_id_by_username( $username );
                if ( ! $channel_id ) {
                    return false;
                }
                set_transient( $user_key, $channel_id, 30 * DAY_IN_SECONDS );
            }

            // 2) Build a unique cache key per page
            $cache_key = 'aae_yt_search_' . md5( "{$channel_id}|{$page_token}|{$max_results}" );
            if ( false !== ( $cached = get_transient( $cache_key ) ) ) {
                return $cached;
            }

            // 3) Call search.list
            $args = [
                'part'          => 'snippet',
                'channelId'     => $channel_id,
                'type'          => 'video',
                'order'         => 'date',
                'maxResults'    => $max_results,
                'key'           => $this->api_key,
            ];
            if ( $page_token ) {
                $args['pageToken'] = sanitize_text_field( $page_token );
            }

            $url  = add_query_arg( $args, 'https://www.googleapis.com/youtube/v3/search' );
            $resp = wp_remote_get( $url, [ 'timeout' => 15 ] );
            if ( is_wp_error( $resp ) || 200 !== wp_remote_retrieve_response_code( $resp ) ) {
                return false;
            }

            $body = json_decode( wp_remote_retrieve_body( $resp ), true );
            if ( empty( $body['items'] ) ) {
                return false;
            }

            // 4) Package & cache for 1 day
            $result = [
                'items'         => $body['items'],
                'nextPageToken' => $body['nextPageToken'] ?? '',
                'prevPageToken' => $body['prevPageToken'] ?? '',
            ];
            set_transient( $cache_key, $result, DAY_IN_SECONDS );

            return $result;
        }

        
        /**
         * Fetch & cache YouTube Shorts (<4m) for a channel, paginated.
         *
         * @param string $channel_id  Optional YouTube channel ID ("UC…"). Omit for global search.
         * @param int    $max_results How many items to fetch (1–50).
         * @param string $page_token  Optional pageToken for pagination.
         * @return array|false        [
         *     'items'         => array of video snippets,
         *     'nextPageToken' => string or empty,
         *     'prevPageToken' => string or empty,
         * ] or false on error.
         */
        public function get_short_videos( $username = '', $max_results = 10, $page_token = '', $order='date' ) {
            // Build a unique transient key for these params
            $channel_id = $this->get_channel_id_by_username( $username );
           
            $transient_key = 'aae_yt_shorts_' . md5( $channel_id . '|' . $max_results . '|' . $page_token. '|'. $order );

            // Return cached if available
            if ( false !== ( $cached = get_transient( $transient_key ) ) ) {
                return $cached;
            }

            // Prepare API args
            $args = [
                'part'          => 'snippet',
                'type'          => 'video',
                'videoDuration' => 'short',
                'order'         => $order,
                'maxResults'    => min( 50, absint( $max_results ) ),
                'key'           => $this->api_key,
            ];

            if ( $channel_id ) {
                $args['channelId'] = sanitize_text_field( $channel_id );
            }
             if ( $page_token ) {
                $args['pageToken'] = sanitize_text_field( $page_token );
            }
           
            $url  = add_query_arg( $args, 'https://www.googleapis.com/youtube/v3/search' );
          
            $resp = wp_remote_get( $url, [ 'timeout' => 15 ] );
            if ( is_wp_error( $resp ) || 200 !== wp_remote_retrieve_response_code( $resp ) ) {
                return false;
            }

            $body = json_decode( wp_remote_retrieve_body( $resp ), true );
            $data = [
                'items'         => $body['items']         ?? [],
                'nextPageToken' => $body['nextPageToken'] ?? '',
                'prevPageToken' => $body['prevPageToken'] ?? '',
            ];

            // Cache for 1 hour
            set_transient( $transient_key, $data, 5*HOUR_IN_SECONDS );
            return $data;
        }
        /**
         * Convert a YouTube username into its channel ID,
         * with a 30-day transient cache.
         *
         * @param string $username
         * @return string|false  Channel ID or false on error.
         */
        public function get_channel_id_by_username( $username ) {
            $username = sanitize_text_field( $username );
            $trans_key = 'aae_yt_userid_' . md5( $username );

            // 1) Return cached ID if available
            if ( false !== ( $cached = get_transient( $trans_key ) ) ) {
                return $cached;
            }

            $base = 'https://www.googleapis.com/youtube/v3/';

            // 2) Try legacy username lookup
            $url = add_query_arg( [
                'part'        => 'id',
                'forUsername' => $username,
                'key'         => $this->api_key,
            ], $base . 'channels' );

            $resp = wp_remote_get( $url, [ 'timeout' => 10 ] );
            $body = json_decode( wp_remote_retrieve_body( $resp ), true );
            if ( ! is_wp_error( $resp ) && ! empty( $body['items'][0]['id'] ) ) {
                set_transient( $trans_key, $body['items'][0]['id'], 30 * DAY_IN_SECONDS );
                return $body['items'][0]['id'];
            }

            // 3) Fallback: search by channel name
            $url = add_query_arg( [
                'part'       => 'snippet',
                'q'          => $username,
                'type'       => 'channel',
                'maxResults' => 1,
                'key'         => $this->api_key,
            ], $base . 'search' );

            $resp = wp_remote_get( $url, [ 'timeout' => 10 ] );
            $body = json_decode( wp_remote_retrieve_body( $resp ), true );
            if ( ! is_wp_error( $resp ) && ! empty( $body['items'][0]['snippet']['channelId'] ) ) {
                $channel_id = $body['items'][0]['snippet']['channelId'];
                set_transient( $trans_key, $channel_id, 30 * DAY_IN_SECONDS );
                return $channel_id;
            }

            // 4) Failure: don’t cache negative results (so you retry later)
            return false;
        }
         /**
         * Fetch and cache playlist metadata (status & details).
         *
         * @param string $playlist_id
         * @return array|false
         */
        public function get_playlist_status( $playlist_id ) {
            $transient_key = 'aae_yt_playlist_status_' . md5( $playlist_id );
            $cached = get_transient( $transient_key );
            if ( $cached !== false ) {
                return $cached;
            }

            $endpoint = add_query_arg( [
                'part' => 'status,contentDetails',
                'id'   => $playlist_id,
                'key'  => $this->api_key,
            ], 'https://www.googleapis.com/youtube/v3/playlists' );

            $response = wp_remote_get( $endpoint, [ 'timeout' => 15 ] );
            if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                return false;
            }

            $body = json_decode( wp_remote_retrieve_body( $response ), true );
            if ( empty( $body['items'][0] ) ) {
                return false;
            }

            // Cache for 1 hour
            set_transient( $transient_key, $body['items'][0], 5*HOUR_IN_SECONDS );
            return $body['items'][0];
        }
         /**
         * Resolve a channel ID or username → uploads playlist ID.
         *
         * @param string $channel
         * @return string|false  uploads playlist ID or false on error
         */
        public function get_uploads_playlist_id( $channel ) {
            $args = [
                'part' => 'contentDetails',
                'key'  => $this->api_key,
            ];
            // treat purely alphanumeric as username? adjust to your needs
            if ( preg_match( '/^[A-Za-z0-9_]+$/', $channel ) ) {
                $args['forUsername'] = $channel;
            } else {
                $args['id'] = $channel;
            }

            $endpoint = add_query_arg( $args, 'https://www.googleapis.com/youtube/v3/channels' );
            $resp     = wp_remote_get( $endpoint, [ 'timeout' => 15 ] );
            if ( is_wp_error( $resp ) || 200 !== wp_remote_retrieve_response_code( $resp ) ) {
                return false;
            }

            $data = json_decode( wp_remote_retrieve_body( $resp ), true );
            if ( empty( $data['items'][0]['contentDetails']['relatedPlaylists']['uploads'] ) ) {
                return false;
            }

            return $data['items'][0]['contentDetails']['relatedPlaylists']['uploads'];
        }
    }
}
