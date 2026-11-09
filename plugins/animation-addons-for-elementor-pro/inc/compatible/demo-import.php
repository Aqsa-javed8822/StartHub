<?php

namespace WCFAddonsPros\compatible;

defined( 'ABSPATH' ) || exit;

class AAE_Demo_Import_Compatibility {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {	
	    add_action('aae/addons/options/import', [$this, 'cpt_option'],2, 13);
	}

    public function cpt_option($option, $data){
     
    }

}

AAE_Demo_Import_Compatibility::instance();
