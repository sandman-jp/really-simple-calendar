<?php

namespace RSC\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\admin')):
	
define('RSC_GENERAL_SETTINGS_PAGE', 'rsc-general-settings');
define('RSC_ADMIN_DIR_INCLUDES', RSC_DIR_INCLUDES.'/admin');

require_once RSC_ADMIN_DIR_INCLUDES.'/setting.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/panels/panel.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/panels/view.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/panels/event.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/panels/style.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/panels/config.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/panels/contact.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/ajax.php';

class admin{
	
	private $_capability = 'read';
	private $_ajax;
	private $_post;
	
	
	public $setting;
	
	function __construct(){
		
		if(!is_admin()){
			return ;
		}
		
		$this->setting = new setting();
		
		add_action('admin_menu', array($this, 'admin_menu'), 11);
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 11);
		
		$this->_ajax = new ajax();
	}
	
	
	
	function admin_enqueue_scripts() {
		
		
		// wp_enqueue_script( 'jquery-ui-tabs' );
		
		//global $post;
		$screen_id = get_current_screen()->id;
		
		wp_register_style('jquery-ui', '//code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css');
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style('rsc-css', RSC_ASSETS_URL.'/rsc.css', array(), RSC_VIRSION);
		wp_enqueue_style('rsc-admin', RSC_ASSETS_URL.'/admin/rsc.css', array(), RSC_VIRSION);
		wp_enqueue_script('rsc-admin', RSC_ASSETS_URL.'/admin/rsc.js', array('jquery'), RSC_VIRSION, true);
		
		wp_register_script('rsc-admin-header', false);
		wp_enqueue_script('rsc-admin-header');
		
		//for localize text
		wp_localize_script(
			'rsc-admin-header',
			'RSC',
			array(
				'CALENDAR_LOAD_FAILED' => __('Can\'t load the calendar. Please try it later.', RSC_TEXTDOMAIN),
				'CALENDAR_LOAD_SUCCESS' => __('The calendar updated. <strong>Save</strong> this parameters, if you\'d like to use this preview\'s calendar.', RSC_TEXTDOMAIN),
				'CALENDAR_LOAD_SUCCESS_SHORTCODE' => __('The calendar updated. <strong>Copy shortcode</strong> and paste on a post, if you\'d like to use this preview\'s calendar.', RSC_TEXTDOMAIN),
			)
		);
	}
	
	function admin_menu(){
		
		add_menu_page(
			__( 'Really Simple Calendar - General Settings', RSC_TEXTDOMAIN), 
			__( 'Really Simple Calendar', RSC_TEXTDOMAIN),
			$this->_capability,
      RSC_GENERAL_SETTINGS_PAGE,
      array($this->setting, 'echo'),
			'dashicons-calendar-alt',
		);
		
	}
	
	function get_panels(){
		$panels = $this->setting->panels;
		
		if(empty($panels)){
			$panel_classes = RSC()->get_panels();
			$panel_classes = apply_filters('rsc_get_setting_panels', $panel_classes);
			
			foreach($panel_classes as $panel){
				$this->setting->add_setting_panel($panel);
			}
		}
		
		return $this->setting->panels;
	}
	
	function get_setting(){
		return apply_filters('rsc_get_admin_setting', $this->setting);
	}
	
}

endif;