<?php

namespace sandmanjp\ReallySimpleCalendar\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\admin')):
	
define('SMJP_RSC_GENERAL_SETTINGS_PAGE', 'rsc-general-settings');
define('SMJP_RSC_ADMIN_DIR_INCLUDES', SMJP_RSC_DIR_INCLUDES.'/admin');

require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/setting.php';
require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/panel.php';
require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/view.php';
require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/event.php';
require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/style.php';
require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/config.php';
require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/contact.php';
require_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/ajax.php';

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
		
		// wp_register_style('jquery-ui', '//code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css');
		// wp_enqueue_style( 'jquery-ui' );
		// 
		wp_enqueue_style(SMJP_RSC_.'css', SMJP_RSC_ASSETS_URL.'/rsc.css', array(), SMJP_RSC_VIRSION);
		
		// $style = '@scope(.rsc-calendar-wrap){'.PHP_EOL.$style.PHP_EOL.'}';
		$style = smjp_rsc_get_style();
		
		wp_add_inline_style(SMJP_RSC_.'css', $style);
		
		wp_enqueue_style(SMJP_RSC_.'admin', SMJP_RSC_ASSETS_URL.'/admin/rsc.css', array(), SMJP_RSC_VIRSION);
		
		wp_enqueue_script(
			SMJP_RSC_.'admin', 
			SMJP_RSC_ASSETS_URL.'/admin/rsc.js', 
			array('jquery'), 
			SMJP_RSC_VIRSION, 
			array(
				'strategy' => 'defer',
				'in_footer' => true,
			),
		);
		wp_enqueue_script(
			SMJP_RSC_.'admin-event', 
			SMJP_RSC_ASSETS_URL.'/admin/event.js', 
			array(), 
			SMJP_RSC_VIRSION, 
			array(
				'strategy' => 'defer',
				'in_footer' => true,
			),
		);
		wp_enqueue_script(
			SMJP_RSC_.'admin-shortcode', 
			SMJP_RSC_ASSETS_URL.'/admin/shortcode.js', 
			array(), 
			SMJP_RSC_VIRSION, 
			array(
				'strategy' => 'defer',
				'in_footer' => true,
			),
		);
		/*
		wp_register_script(SMJP_RSC_.'admin-header');
		wp_enqueue_script(SMJP_RSC_.'admin-header');
		*/
		//for localize text
		wp_localize_script(
			SMJP_RSC_.'admin',
			'SMJP_RSC',
			array(
				'SMJP_RSC_GET_CALENDAR_BY_SHORTCODE' => esc_html('smjp_rsc_get_calendar_by_shortcode'),
				'CALENDAR_LOAD_FAILED' => esc_html__('Can\'t load the calendar. Please try it later.', 'really-simple-calendar'),
				'CALENDAR_LOAD_SUCCESS' => esc_html__('The calendar updated. <strong>Save</strong> this parameters, if you\'d like to use this preview\'s calendar.', 'really-simple-calendar'),
				'CALENDAR_LOAD_SUCCESS_SHORTCODE' => esc_html__('The calendar updated. <strong>Copy shortcode</strong> and paste on a post, if you\'d like to use this preview\'s calendar.', 'really-simple-calendar'),
			)
		);

	}
	
	function admin_menu(){
		
		add_menu_page(
			esc_html__( 'Really Simple Calendar - General Settings', 'really-simple-calendar'), 
			esc_html__( 'Really Simple Calendar', 'really-simple-calendar'),
			$this->_capability,
      SMJP_RSC_GENERAL_SETTINGS_PAGE,
      array($this->setting, 'echo'),
			'dashicons-calendar-alt',
		);
		
	}
	
	function get_panels(){
		$panels = $this->setting->panels;
		
		if(empty($panels)){
			$panel_classes = SMJP_RSC()->get_panels();
			$panel_classes = apply_filters('smjp_rsc_get_setting_panels', $panel_classes);
			
			foreach($panel_classes as $panel){
				$this->setting->add_setting_panel($panel);
			}
		}
		
		return $this->setting->panels;
	}
	
	function get_setting(){
		return apply_filters('smjp_rsc_get_admin_setting', $this->setting);
	}
	
}

endif;