<?php

namespace WCFAddonsPro\Tiktok;

class TikTok_Schedule
{
  /**
   * Fetch latest n videos for a TikTok user,
   * returning each video's MP4 play URL + a cover image.
   */
  public static function init()
  {
    add_action('wp', [__CLASS__, 'twice_daily_cron_schedule']);
    add_action('aae_tiktok_twice_daily_cron_hook', [__CLASS__, 'twice_daily_cron_function']); 
  }

  static function twice_daily_cron_function()
  {
    $setting = @json_decode(get_option('aae_tiktok_api_advanced_settings'), true);
    if (is_array($setting) && isset($setting['accessToken'])) {
      $response = wp_remote_post('https://open-api.tiktok.com/oauth/refresh_token/', [
        'headers' => [
          'Content-Type' => 'application/x-www-form-urlencoded',
        ],
        'body' => [
          'client_key'    => 'sbaw86yd959gidlkhn',
          'grant_type'    => 'refresh_token',
          'refresh_token' => $setting['refreshToken'],
        ],
        'timeout' => 15,
      ]);

      if (is_wp_error($response)) {
      } else {
        $body = wp_remote_retrieve_body($response);      
        $data = json_decode($body, true);       
        if (isset($data['data']['access_token'])) {
          $setting['accessToken'] = $data['data']['access_token'];
          $setting['refreshToken'] = $data['data']['refresh_token'];
          update_option('aae_tiktok_api_advanced_settings', json_encode($setting));
        }    
      }
    }

  }
  static function twice_daily_cron_schedule()
  {
    if (!wp_next_scheduled('aae_tiktok_twice_daily_cron_hook')) {
      wp_schedule_event(time(), 'twicedaily', 'aae_tiktok_twice_daily_cron_hook'); // twicedaily
    }
  }
}
