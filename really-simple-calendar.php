<?php
/*
Plugin Name: Really Simple Calendar
Plugin URI: 
Description: Very Simple Calendar for personal.
Version: 0.1.3
Author: sandman.jp
Author URI: 
Text Domain: really-simple-calendar
Domain Path: /lang
License: GPLv2 or later
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!class_exists('RSC')){

define('RSC_VIRSION', '0.1.3');

//post type
define('RS_CALENDAR', 'rs-calendar');

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

class CustomFieldsCalendar {
	private $_shortcode;
	private $_widget;
	private $_calendar;
	private $_admin;
	private $_cell_data = array();
	
	function __construct() {
		
		$this->_shortcode = new RSC\shortcode;
		$this->_widget = new RSC\widget;
		$this->_calendar = new RSC\Calendar\calendar;
		
		if(is_admin()){
			$this->_admin = new RSC\Admin\admin;
		}else{
		}
	}
	
	public function get_calendar_post_type(){
		return $this->_post_type;
	}
	
	public function get_calendar($args=array()){
		return $this->_calendar->render($args);
	}
	
	public function render_calendar($args=array()){
		do_action('rsc_before_render_calendar', $args);
		echo $this->get_calendar($args);
		do_action('rsc_after_render_calendar');
	}
	
	public function get_calendar_params(){
		return $this->_calendar->get_params();
	}
	
	public function set_td($args){
		$this->_cell_data['td'] = $args;
	}
	
}

$RSC = new CustomFieldsCalendar;

}