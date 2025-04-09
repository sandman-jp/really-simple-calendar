<?php
/*
Plugin Name: Really Simple Calendar
Plugin URI: https://note.com/sandman_jp/m/m0657144dd8fb
Description: Very Simple Calendar for multiple purpose.
Version: 0.4.2-beta
Author: sandman.jp
Author URI: 
Text Domain: really-simple-calendar
Domain Path: /lang
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!class_exists('ReallySimpleCalendar')):

define('RSC_VIRSION', '0.4.2');

//basename
define('RS_CALENDAR', 'rs_calendar');

//general settings
define('RSC_DIR', dirname(__FILE__));
define('RSC_DIR_INCLUDES', RSC_DIR.'/includes');
define('RSC_DIR_ASSETS', RSC_DIR.'/assets');
define('RSC_DIR_TEMPLATES', RSC_DIR.'/templates');
define('RSC_DIR_LANG', RSC_DIR.'/lang');
define('RSC_TEXTDOMAIN', 'really-simple-calendar');
define('RSC_URL', plugins_url('/', __FILE__));
define('RSC_ASSETS_URL', RSC_URL.'assets');


//common settings
load_plugin_textdomain(RSC_TEXTDOMAIN, false, plugin_basename( RSC_DIR_LANG ));

require_once RSC_DIR_INCLUDES.'/functions/common.php';
require_once RSC_DIR_INCLUDES.'/functions/template.php';
require_once RSC_DIR_INCLUDES.'/functions/calendar.php';
require_once RSC_DIR_INCLUDES.'/calendar/calendar.php';
require_once RSC_DIR_INCLUDES.'/admin/admin.php';
require_once RSC_DIR_INCLUDES.'/shortcode.php';
require_once RSC_DIR_INCLUDES.'/widget.php';

class ReallySimpleCalendar {
	private $_shortcode;
	private $_widget;
	private $_calendar;
	private $_admin;
	private $_panels = array('view', 'event', 'style');
	
	function __construct() {
		
		add_action('plugins_loaded', array($this, 'init'));
		add_action('widgets_init', array($this, 'widgets_init'));
		/*
		//修正分
		add_filter('pre_option', array($this, 'get_option'), 10, 3);
		*/
	}
	
	function init(){
		$this->_shortcode = new RSC\shortcode;
		$this->_calendar = new RSC\Calendar\calendar;
		
		if(is_admin()){
			$this->_admin = new RSC\Admin\admin;
		}
		
		// import plugins
		$extenstions = get_option(RS_CALENDAR.'_extentions');
		
		if($extenstions){
			foreach($extenstions as $k=>$v){
				$file = RSC_DIR.'/extentions/'.$k.'/'.$k.'.php';
				if(!empty($v) && file_exists($file)){
					require_once $file;
				}
			}
		}
	}
	
	function widgets_init(){
		$this->_widget = new RSC\widget;
	}
	
	function get_admin(){
		return $this->_admin;
	}
	
	function get_panels(){
		return $this->_panels;
	}
	/*
	//古いoption名を変更
	function get_option($pre, $option, $default_value){
		if($pre){
			return $pre;
		}else if(strpos($option, RS_CALENDAR.'_') === 0){
			$search = str_replace(RS_CALENDAR.'_', 'calendar_', $option);
			$old = get_option($search);
			if($old){
				delete_option($search);
				rsc_update_option($option, $old);
				return $old;
			}
		}
		return $pre;
	}
	*/
	
	function set_style($b){
		return $this->_calendar->has_style = $b;
	}
	
	function get_calendar($args=array()){
		global $post;
		$tmp = $post;
		
		if(!empty($args['id'])){
			$post = get_post($args['id']);
			setup_postdata($post);
		}
		
		$calendar = $this->_calendar->render($args);
		
		if(!empty($args['id'])){
			$post = $tmp;
			wp_reset_postdata();
		}
		return $calendar;
	}
	
	function render_calendar($args=array()){
		do_action('rsc_before_render_calendar', $args);
		echo $this->get_calendar($args);
		do_action('rsc_after_render_calendar');
	}
	
	function get_calendar_view(){
		return $this->_calendar->get_view();
	}
	
}

RSC();

endif;