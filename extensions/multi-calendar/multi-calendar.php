<?php
/*
Plugin Name: RSC Multi Calendar
Plugin URI: https://note.com/sandman_jp/m/m0657144dd8fb
Description: Very Simple Calendar for multiple purpose.
Version: 0.1.13
License: GPLv2 or later
*/

namespace sandmanjp\ReallySimpleCalendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
define('SMJP_RSC_MC', 'smjp_rsc_mc');
define('SMJP_RSC_MC_DIR', dirname(__FILE__));
define('SMJP_RSC_MC_URL', plugins_url('/', __FILE__));

require_once SMJP_RSC_MC_DIR.'/functions.php';

if(!class_exists('sandmanjp\ReallySimpleCalendar\multi_calendar')):

class multi_calendar extends \sandmanjp\ReallySimpleCalendar\extension {
	
	public $admin;
	public $setting;
	public $ajax;
	
	private $_lock_fields;
	
	function __construct(){
		
		add_action('init', array($this, 'register'));
		add_filter('smjp_rsc_get_option', array($this, 'get_option'), 9, 2);
		add_action('smjp_rsc_before_get_calendar', array($this, 'setup_calendar_post'), 10);
		add_action('smjp_rsc_after_get_calendar', array($this, 'restore_calendar_post'), 10);
		
		add_filter('smjp_rsc_get_stylesheet', array($this, 'append_post_style'));
		add_filter('smjp_rsc_locked_fields', array($this, 'get_locked_fields'));
		if(is_admin()){
			require_once SMJP_RSC_MC_DIR.'/admin/multi-calendar.php';
			require_once SMJP_RSC_MC_DIR.'/admin/mc-setting.php';
			require_once SMJP_RSC_MC_DIR.'/admin/mc-ajax.php';
			$this->admin = new \sandmanjp\ReallySimpleCalendar\Admin\multi_calendar;
			$this->setting = new \sandmanjp\ReallySimpleCalendar\Admin\mc_setting;
			$this->ajax = new \sandmanjp\ReallySimpleCalendar\Admin\mc_ajax;
		}
		$this->_get_lock_fields();
		
	}
	
	function register(){
		register_post_type(
			SMJP_RS_CALENDAR,
			array(
				'labels' => array(
					'name' => esc_html__( 'RS Calendars', 'really-simple-calendar'),
					'singular_name' => esc_html__( 'RS Calendar', 'really-simple-calendar'),
					'add_new' => esc_html__( 'Add New', 'really-simple-calendar'),
					'add_new_item' => esc_html__( 'Add New Calendar', 'really-simple-calendar'),
					'edit_item' => esc_html__( 'Edit Calendar', 'really-simple-calendar'),
					'new_item' => esc_html__( 'New Calendar', 'really-simple-calendar'),
					'view_item' => esc_html__( 'View Calendar', 'really-simple-calendar'),
					'search_items' => esc_html__( 'Search Calendars', 'really-simple-calendar'),
					'not_found' => esc_html__( 'No Calendars found', 'really-simple-calendar'),
					'not_found_in_trash' => esc_html__( 'No Calendars found in Trash', 'really-simple-calendar'),
					'all_items' => esc_html__('All Calendars', 'really-simple-calendar'),
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
		$panels = SMJP_RSC()->get_panels();
		
		$lock_fields = array();
		
		foreach($panels as $class){
			$class = 'sandmanjp\ReallySimpleCalendar\Admin\Panel\\'.$class;
			if(class_exists($class)){
				$panel = new $class;
				$lock_fields = array_merge($lock_fields, $panel->get_lock_fields());
			}
			
		}
		
		$this->_lock_fields = $lock_fields;
	}
	
	function get_locked_fields($locked){
		
		$post_id = get_the_ID();
		if(smjp_rsc_current_user_can('full', 'mc') || !$post_id){
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
		
		if(!$post_id || get_post_type($post_id) != SMJP_RS_CALENDAR){
			return $opt;
		}
		
		if(strpos($key, SMJP_RS_CALENDAR.'_style') !== FALSE){
			
			$lock = SMJP_RS_CALENDAR.'_style_lock';
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
			// through if it isn't locked.
		}else if(strpos($key, SMJP_RS_CALENDAR.'_event') !== FALSE){
			
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
			
			// through if it isn't locked.
		}
		
		return $opt;
	}
	
	function setup_calendar_post($args){
		global $post;
		
		if(!empty($args['id'])){
			
			$post = get_post($args['id']);
			
			if($post->post_status != 'publish' && !is_user_logged_in()){
				return;
			}else if(in_array($post->post_status, array('draft', 'trash', 'auto-draft'))){
				return;
			}
			
			setup_postdata($post);
		}
	}
	
	function restore_calendar_post($args){
		
		if(!empty($args['id'])){
			wp_reset_postdata();
		}
		
	}
	
	function append_post_style($style){
		global $post;
		
		if(!empty($post->ID)){
			if(!wp_doing_ajax()){
				$post_style = get_post_meta($post->ID, SMJP_RS_CALENDAR.'_style_post', true);
				$style .= "\n".$post_style;
			}
		}
		
		return $style;
	}
}
//plugins loaded
new multi_calendar;

endif;