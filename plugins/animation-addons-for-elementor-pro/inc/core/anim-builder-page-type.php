<?php

namespace WCFAddonsPro\Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Animation_Builder_Page_Type {

    private static $instance = null;
    private $gl = [ 'category' , 'author' , 'post_tag' , 'archive' , 'custom-taxonomy' ];
    /**
     * Option name prefix for storing unknown entity configs.
     */
    public $option_name = 'cfanim_build_config_';

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return self An instance of the class.
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Class constructor
     */
    public function __construct() {
        // Initialize hooks or default actions here if needed
    }

    /**
     * Save configuration for a given entity.
     *
     * @param int|string $id Object ID (post ID, term ID, or URL).
     * @param array $config Configuration data.
     * @param string $type Type of entity ('post', 'term', 'url', 'author', etc.).
     * @return bool True if saved successfully, false otherwise.
     */
    public function saveConfig($config, $animconf) {
       
        if( $config['store_type'] == 'option' && $config['type'] == 'post'){
            return update_option($config['option'], $animconf); 
        } else if( $config['store_type'] == 'post_meta' && isset($config['option']) && isset($config['id'])){
			return update_post_meta($config['id'], $config['option'] , $animconf);    
        }else if( $config['store_type'] == 'term_meta' && $config['type'] == 'blog_tax' && isset($config['id'])){
			return update_term_meta($config['id'], $config['option'] , $animconf);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'blog_tax' && isset($config['id'])){
			return update_option($config['option'] , $animconf);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'custom_taxonomy' && isset($config['option'])){
			return update_option($config['option'] , $animconf);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'author' && isset($config['taxonomy']) && $config['taxonomy'] == 'author'){
			return update_option($config['option'] , $animconf);    
        }else if( $config['store_type'] == 'option' && $config['type'] == '404' && isset($config['option'])){
			return update_option($config['option'] , $animconf);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'search' && isset($config['option'])){
			return update_option($config['option'] , $animconf);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'archive' && isset($config['option'])){
			return update_option($config['option'] , $animconf);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'url' && isset($config['option'])){
			return update_option($config['option'] , $animconf);    
        }
               
    }

    /**
     * Retrieve configuration for a given entity.
     *
     * @param int|string $id Object ID (post ID, term ID, or URL).
     * @param string $type Type of entity ('post', 'term', 'url', 'author', etc.).
     * @return array|false Configuration data or false if not found.
     */
    public function getConfig($page_type = false) {
        $config = [];
        if($page_type){
           $config = $page_type;
        }else{
            $config = $this->getCurrentPageType();   
        }    
         
        if( $config['store_type'] == 'option' && $config['type'] == 'post'){
            return get_option($config['option']); 
        } else if( $config['store_type'] == 'post_meta' && isset($config['option']) && isset($config['id'])){
			return get_post_meta($config['id'], $config['option'] , true);    
        }else if( $config['store_type'] == 'term_meta' && $config['type'] == 'blog_tax' && isset($config['id'])){
			return get_term_meta($config['id'], $config['option'] , true);    
        }else if( $config['store_type'] == 'option' && isset($config['type']) && $config['type'] == 'blog_tax' && isset($config['id'])){
			return get_option($config['option']);    
        }else if( $config['store_type'] == 'option' && isset($config['type']) && $config['type'] == 'custom_taxonomy' && isset($config['option'])){
			return get_option($config['option']);    
        }else if( $config['store_type'] == 'option' && isset($config['type']) && $config['type'] == 'author' && isset($config['taxonomy']) && $config['taxonomy'] == 'author'){
			return get_option($config['option']);    
        }else if( $config['store_type'] == 'option' && isset($config['type']) && $config['type'] == '404' && isset($config['option'])){
			return get_option($config['option']);    
        }else if( $config['store_type'] == 'option' && isset($config['type']) && $config['type'] == 'search' && isset($config['option'])){
			return get_option($config['option']);    
        }else if( $config['store_type'] == 'option' && isset($config['type']) && $config['type'] == 'archive' && isset($config['option'])){
			return get_option($config['option']);    
        }else if( $config['store_type'] == 'option' && isset($config['type']) && $config['type'] == 'url' && isset($config['option'])){
			return get_option($config['option']);    
        }
			 return [];
    }

    /**
     * Delete configuration for a given entity.
     *
     * @param int|string $id Object ID (post ID, term ID, or URL).
     * @param string $type Type of entity ('post', 'term', 'url', 'author', etc.).
     * @return bool True if deleted successfully, false otherwise.
     */
    public function deleteConfig($page_type = null) {
        $config = [];
        if($page_type){
           $config = $page_type;
        }else{
            $config = $this->getCurrentPageType();   
        }    
         
        if( $config['store_type'] == 'option' && $config['type'] == 'post'){
            return delete_option($config['option']); 
        } else if( $config['store_type'] == 'post_meta' && isset($config['option']) && isset($config['id'])){
			return delete_post_meta($config['id'], $config['option']);    
        }else if( $config['store_type'] == 'term_meta' && $config['type'] == 'blog_tax' && isset($config['id'])){
			return delete_term_meta($config['id'], $config['option']);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'blog_tax' && isset($config['id'])){
			return delete_option($config['option']);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'custom_taxonomy' && isset($config['option'])){
			return delete_option($config['option']);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'author' && isset($config['taxonomy']) && $config['taxonomy'] == 'author'){
			return delete_option($config['option']);    
        }else if( $config['store_type'] == 'option' && $config['type'] == '404' && isset($config['option'])){
			return delete_option($config['option']);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'search' && isset($config['option'])){
			return delete_option($config['option']);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'archive' && isset($config['option'])){
			return delete_option($config['option']);    
        }else if( $config['store_type'] == 'option' && $config['type'] == 'url' && isset($config['option'])){
			return delete_option($config['option']);    
        }
				return [];
    }

    /**
     * Determine the type of the current page and return a configuration identifier.
     *
     * @return array Configuration option type and option name (e.g., ['type' => 'post', 'option' => 123]).
     */
	public function getCurrentPageType() {
		
		// Check if it's the front page
		if (is_front_page()) {
			if ('page' === get_option('show_on_front')) {
				// Static front page
				return [
					'type'       => 'post',
					'store_type' => 'option',
					'option'     => $this->option_name.'front_'.get_option('page_on_front')
				];
			} else {
				// Latest posts on the front page
				return [
					'type'       => 'post',
					'store_type' => 'option',
					'option'     => $this->option_name.'blog'
				];
			}
		}
	
		// Check if it's the posts page
		if (is_home()) {
		
			if ('page' === get_option('show_on_front')) {
				return [
					'type'       => 'post',
					'store_type' => 'option',					
					'option'     => $this->option_name.'front_'.get_option('page_on_front')
				];
			}
	
			return [
				'type'       => 'post',
				'store_type' => 'option',				
				'option'     => $this->option_name.'home_'.get_the_ID()
			];
		}
		
		// Handle other page types
		if (is_singular()) {
			
			return [				
				'store_type' => 'post_meta',
				'id'   => get_queried_object_id(),
				'option'     => $this->option_name.get_post_type()
			
			];
		}
	
		if (is_tag() || is_category()) {
		
			$term = get_queried_object();
			return [	
				'type'       => 'blog_tax',
				'id'   => get_queried_object_id(),
				'store_type' => in_array($term->taxonomy, $this->gl) ? 'option' : 'term_meta',
				'option'     => in_array($term->taxonomy, $this->gl) ? $this->option_name.$term->taxonomy : $this->option_name.$term->taxonomy.'_'.get_queried_object_id(),
				'taxonomy'   =>$term->taxonomy,
			];
		}
	
		if (is_tax()) {
			$term = get_queried_object();
			return [
				'type'       => 'custom_taxonomy',
				'store_type' => 'option',
				'option'     => $this->option_name.$term->taxonomy,
				'taxonomy'   => $term->taxonomy,
			];
		}
	
		if (is_author()) {
			
			return [
				'type'       => 'author',			
				'store_type' => 'option',
				'option'     =>  $this->option_name.'author' ,
				'taxonomy'   => 'author'
			];
			
		}
		
		if (is_404()) {		    
			
			return [
				'type'       => '404',
				'store_type' => 'option',
				'option'     => $this->option_name.'404',
			];
		}
		
		if ( is_search() ) {
		
			return [
				'type'       => 'search',
				'store_type' => 'option',
				'option'     => $this->option_name.'search'
			];
		}
		
		$req = parse_url($GLOBALS['wp']->request);
		
		if (is_archive()) {
		
			return [
				'type'       => 'archive',
				'store_type' => 'option',
				'option'     => $this->option_name.isset($req['path']) ? $req['path'] : 'archive'
			];
		}
		
		return [
			'type'       => 'url',
			'store_type' => 'option',
			'option'     => $this->option_name. isset($req['path']) ? $req['path'] : 'unknown'
		];
	}
	
}
