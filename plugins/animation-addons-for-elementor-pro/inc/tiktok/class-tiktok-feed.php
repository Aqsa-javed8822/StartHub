<?php 

namespace WCFAddonsPro\Tiktok;

class TikTok_Feed_Service {

    const TRANSIENT_KEY = 'tiktok_feed_%s';
    const TRANSIENT_TTL = HOUR_IN_SECONDS;
    /**
     * Fetch latest n videos for a TikTok user,
     * returning each video's MP4 play URL + a cover image.
     */
    public static function get_user_videos( string $username, int $count = 6 ): array {
        $cache_key = sprintf( self::TRANSIENT_KEY, $username );
        if ( $cached = get_transient( $cache_key ) ) {
            return $cached;
        }

        $html = wp_remote_retrieve_body( wp_safe_remote_get( "https://www.tiktok.com/@{$username}" ) );
      
        if ( ! $html || ! preg_match( '/<script id="SIGI_STATE" type="application\/json">(.+?)<\/script>/', $html, $m ) ) {
            return [];
        }

        $data   = json_decode( $m[1], true );
        $videos = [];
        foreach ( $data['ItemModule'] as $item ) {
            // playAddr is the HLS/mp4 URL, cover is the poster
            $videos[] = [
                'src'    => $item['video']['playAddr'],
                'poster' => $item['video']['cover'],
            ];
            if ( count( $videos ) >= $count ) {
                break;
            }
        }

        set_transient( $cache_key, $videos, self::TRANSIENT_TTL );
        return $videos;
    }
}
