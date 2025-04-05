<?php

namespace RSC\calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
define('RSC_MC_DIR', dirname(__FILE__));
define('RSC_MC_URL', plugins_url('/', __FILE__));

require_once RSC_MC_DIR.'/functions.php';

if(!class_exists('RSC\Calendar\multi_calendar')):

class multi_calendar {
	
	public $setting;
	public $ajax;
	
	private $_lock_fields;
	
	function __construct(){
		
		add_action('init', array($this, 'register'));
		add_filter('rsc_get_option', array($this, 'get_option'), 9, 2);
		
		add_filter('rsc_locked_fields', array($this, 'get_locked_fields'));
		if(is_admin()){
			require_once RSC_MC_DIR.'/admin/mc-setting.php';
			require_once RSC_MC_DIR.'/admin/mc-ajax.php';
			$this->setting = new \RSC\Admin\mc_setting;
			$this->ajax = new \RSC\Admin\mc_ajax;
		}
		$this->_get_lock_fields();
		
	}
	
	function register(){
		register_post_type(
			RS_CALENDAR,
			array(
				'labels' => array(
					'name' => __( 'RS Calendars', 'really-simple-calendar'),
					'singular_name' => __( 'RS Calendar', 'really-simple-calendar'),
					'add_new' => __( 'Add New', 'really-simple-calendar'),
					'add_new_item' => __( 'Add New Calendar', 'really-simple-calendar'),
					'edit_item' => __( 'Edit Calendar', 'really-simple-calendar'),
					'new_item' => __( 'New Calendar', 'really-simple-calendar'),
					'view_item' => __( 'View Calendar', 'really-simple-calendar'),
					'search_items' => __( 'Search Calendars', 'really-simple-calendar'),
					'not_found' => __( 'No Calendars found', 'really-simple-calendar'),
					'not_found_in_trash' => __( 'No Calendars found in Trash', 'really-simple-calendar'),
				),
				'menu_icon' => 'dashicons-calendar-alt',
				'public' => false,
				'hierarchical' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'show_in_nav_menus' => false,
				'show_in_admin_bar' => false,
				'show_in_rest' => false,
				'_builtin' => false,
				'capability_type' => 'post',
				'supports' => array('title'),
				'rewrite' => false,
				'query_var' => false,
			)
		);
	} 
	
	private function _get_lock_fields(){
		$panels = RSC()->get_panels();
		
		$lock_fields = array();
		
		foreach($panels as $class){
			$class = 'RSC\Admin\Panel\\'.$class;
			if(class_exists($class)){
				$panel = new $class;
				$lock_fields = array_merge($lock_fields, $panel->get_lock_fields());
			}
			
		}
		
		$this->_lock_fields = $lock_fields;
	}
	
	function get_locked_fields($locked){
		
		$post_id = get_the_ID();
		if(rsc_current_user_can('full') || !$post_id){
			return $locked;
		}
		
		foreach($locked as $k=>$v){
			if(!$v){
				$locked[$k] = get_post_meta($post_id, $k, true);
			}
		}
		
		
		return $locked;
	}
	
	function get_option($opt, $key){
		
		$post_id = get_the_ID();
		
		if(!$post_id){
			return $opt;
		}
		
		if(strpos($key, RS_CALENDAR.'_style') !== FALSE){
			
			$lock = RS_CALENDAR.'_style_lock';
			$is_locked = get_post_meta($post_id, $lock, true);
			
			if(!$is_locked){
				$is_locked = get_option($lock);
			}
			//
			if(!$is_locked){
				$meta = get_post_meta($post_id, $key, true);
				if($meta !== false){
					return $meta;
				}
				
			}
			
		}else if(strpos($key, RS_CALENDAR.'_event') !== FALSE){
			
			if(empty($opt)){
				$opt = array();
			}
			if(isset($this->ajax->params[$key])){
				$meta = $this->ajax->params[$key];
			}else{
				$meta = get_post_meta( $post_id, $key, true );
			}
			
			if($meta && is_array($meta)){
				// var_dump($opt);
				
				foreach($meta as $k=>$v){
					$opt[$k] = $v;
				}
				
			}
			
			return $opt;
		}else{
			
			//for view params
			foreach($this->_lock_fields as $lock){
				$key_name = str_replace('_lock', '', $lock);
				if(strpos($key, $key_name) !== false){
					//var_dump($key);
					$is_locked = false;
					//$is_locked = get_post_meta($post_id, $lock, true);
					if(!$is_locked){
						$is_locked = get_option($lock);
					}
					//
					if(!$is_locked){
						
						$meta = get_post_meta($post_id, $key, true);
						if($meta !== false){
							return $meta;
						}
						
					}
				}
			}
		}
		
		return $opt;
	}
	
}
//plugins loaded
new multi_calendar;

endif;