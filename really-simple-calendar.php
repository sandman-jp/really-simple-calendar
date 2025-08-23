<?php
/*
Plugin Name: Really Simple Calendar
Plugin URI: https://note.com/sandman_jp/m/m0657144dd8fb
Description: Very Simple Calendar for multiple purpose.
Version: 0.4.13
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

define('SMJP_RSC_VIRSION', '0.4.13');

//basename
define('SMJP_RS_CALENDAR', 'smjp_rs_calendar');
define('SMJP_RSC_', 'smjp_rsc_');

//general settings
define('SMJP_RSC_DIR', dirname(__FILE__));
define('SMJP_RSC_DIR_INCLUDES', SMJP_RSC_DIR.'/includes');
define('SMJP_RSC_DIR_ASSETS', SMJP_RSC_DIR.'/assets');
define('SMJP_RSC_DIR_TEMPLATES', SMJP_RSC_DIR.'/templates');
define('SMJP_RSC_DIR_LANG', SMJP_RSC_DIR.'/lang');
define('SMJP_RSC_TEXTDOMAIN', 'really-simple-calendar');
define('SMJP_RSC_URL', plugins_url('/', __FILE__));
define('SMJP_RSC_ASSETS_URL', SMJP_RSC_URL.'assets');


require_once SMJP_RSC_DIR_INCLUDES.'/functions/common.php';
require_once SMJP_RSC_DIR_INCLUDES.'/functions/template.php';
require_once SMJP_RSC_DIR_INCLUDES.'/functions/calendar.php';
require_once SMJP_RSC_DIR_INCLUDES.'/calendar/calendar.php';
require_once SMJP_RSC_DIR_INCLUDES.'/admin/admin.php';
require_once SMJP_RSC_DIR_INCLUDES.'/shortcode.php';
require_once SMJP_RSC_DIR_INCLUDES.'/widget.php';
require_once SMJP_RSC_DIR_INCLUDES.'/extension.php';

class ReallySimpleCalendar {
	private $_shortcode;
	private $_widget;
	private $_calendar;
	private $_admin;
	private $_panels = array('view', 'event', 'style');
	
	function __construct() {
		
		add_action('plugins_loaded', array($this, 'init'));
		add_action('widgets_init', array($this, 'widgets_init'));
		
	}
	
	function init(){
		load_plugin_textdomain(SMJP_RSC_TEXTDOMAIN, false, plugin_basename( SMJP_RSC_DIR_LANG ));
		$this->_shortcode = new sandmanjp\ReallySimpleCalendar\shortcode;
		$this->_calendar = new sandmanjp\ReallySimpleCalendar\Calendar\calendar;
		
		if(is_admin()){
			$this->_admin = new sandmanjp\ReallySimpleCalendar\Admin\admin;
		}
		
		$this->import_extensions();
	}
	
	function import_extensions(){
		// import 
		$extenstions = get_option(SMJP_RS_CALENDAR.'_extensions');
		
		if($extenstions){
			foreach($extenstions as $k=>$v){
				$file = SMJP_RSC_DIR.'/extensions/'.$k.'/'.$k.'.php';
				if(!empty($v) && file_exists($file)){
					require_once $file;
				}
			}
		}
	}
	
	function widgets_init(){
		$this->_widget = new sandmanjp\ReallySimpleCalendar\widget;
	}
	
	function get_admin(){
		return $this->_admin;
	}
	
	function get_panels(){
		return $this->_panels;
	}
	
	function set_style($b){
		return $this->_calendar->has_style = $b;
	}
	
	function get_calendar($args=array()){
		
		do_action('smjp_rsc_before_get_calendar', $args);
		
		$calendar = $this->_calendar->render($args);
		
		do_action('smjp_rsc_after_get_calendar', $args);
		
		return $calendar;
	}
	
	function render_calendar($args=array()){
		do_action('smjp_rsc_before_render_calendar', $args);
		echo wp_kses_post($this->get_calendar($args));
		do_action('smjp_rsc_after_render_calendar');
	}
	
	function get_calendar_view(){
		return $this->_calendar->get_view();
	}
	
}

SMJP_RSC();

endif;