<?php 

namespace WCFAddonsPro\Tiktok;

defined( 'ABSPATH' ) || die();

require_once __DIR__ . '/class-tiktok-feed.php';
require_once __DIR__ . '/class-schedule.php';

class Module {

	public static function init() {       
        TikTok_Schedule::init();     
    }
}

Module::init();
