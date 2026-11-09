<?php 

namespace WCFAddonsPro;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/class-aae-addon-yt-cache.php';
require_once __DIR__ . '/class-aae-addon-yt-api.php';

class Module {

	public static function init() {       
        new AAE_Addon_YT_API();        
    }
}

Module::init();

function aae_addon_yt_shortcode( $atts ) {
    $atts = shortcode_atts( [
        'playlistId' => 'PLV5iLLq16Ydps5fXtMQK3JM2Y3_VVCUf2',
        'channelIds' => '',  // new: comma-separated
    ], $atts, 'youtube_shorts' );

    $api = new AAE_Addon_YT_API(); // ensures cache exists
    $videos = [];

    if ( ! empty( $atts['channelIds'] ) ) {
        $channels = explode( ',', $atts['channelIds'] );
        $videos   = $api->cache->get_channels_videos( $channels );
    }
    elseif ( ! empty( $atts['playlistId'] ) ) {
        $videos = $api->cache->get_playlist_videos( 'PLV5iLLq16Ydps5fXtMQK3JM2Y3_VVCUf2' );
    }

    if ( empty( $videos ) ) {
        return '<p>No videos found.</p>';
    }

    ob_start();
    echo '<div class="aae-addon-yt-grid">';
    foreach ( $videos as $item ) {
        $t = esc_html( $item['snippet']['title'] );
        $u = esc_url( $item['snippet']['thumbnails']['medium']['url'] );
        $v = esc_html( $item['snippet']['resourceId']['videoId'] );
        printf(
            '<div class="aae-addon-yt-item">
                <a href="https://youtu.be/%1$s" target="_blank">
                    <img src="%2$s" alt="%3$s">
                    <p>%3$s</p>
                </a>
            </div>',
            $v, $u, $t
        );
    }
    echo '</div>';
    return ob_get_clean();
}
add_shortcode( 'aae_youtube_shorts', 'aae_addon_yt_shortcode' );