<?php

namespace RSC\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\admin')):
	
define('RSC_GENERAL_SETTINGS_PAGE', 'rsc-general-settings');
define('RSC_ADMIN_DIR_INCLUDES', RSC_DIR_INCLUDES.'/admin');

require_once RSC_ADMIN_DIR_INCLUDES.'/setting-panels/panel.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/setting-panels/view.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/setting-panels/event.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/setting-panels/style.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/setting-panels/capability.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/setting-panels/contact.php';
require_once RSC_ADMIN_DIR_INCLUDES.'/ajax.php';

class admin{
	
	private $_capability = 'read';
	private $_ajax;
	private $_post;
	private $_panels = array();
	private $_panel_classes = array('view', 'event', 'style', 'contact');
	
	function __construct(){
		
		if(!is_admin()){
			return ;
		}
		
		add_action('admin_init', array($this, 'init'));
		add_action('admin_head', array($this, 'admin_head'));
						
		add_action('admin_menu', array($this, 'admin_menu'), 11);
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 11);
		
		$this->_ajax = new ajax();
	}
	
	function init(){
		
		if(isset($_GET['page']) && $_GET['page'] == RSC_GENERAL_SETTINGS_PAGE){
			
			$current_user = wp_get_current_user();
			
			//get manage panels
			$this->_panel_classes = apply_filters('rsc_get_admin_manage_panel', $this->_panel_classes);
			
			if(in_array('administrator', $current_user->roles)){
				$this->_panel_classes[] = 'capability';
			}else if(isset($_GET['rsc']) && $_GET['rsc'] == 'capability'){
				wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );
			}
		}
		
	}
	
	function add_setting_panel($class){
		
		$class = 'RSC\Admin\\Panel\\'.$class;
		
		$this->_panels[] = new $class;
	}
	
	function admin_head(){
		
		$screen = get_current_screen();
		
		if($screen->id == 'toplevel_page_'.RSC_GENERAL_SETTINGS_PAGE){
			
			$panels = apply_filters('rsc_get_setting_panels', $this->_panel_classes);
			
			foreach($panels as $panel){
				$this->add_setting_panel($panel);
			}
		}
	}
	
	function admin_enqueue_scripts() {
		
		
		wp_enqueue_script( 'jquery-ui-tabs' );
		
		//global $post;
		$screen_id = get_current_screen()->id;
		
		wp_register_style('jquery-ui', '//code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css');
		wp_enqueue_style( 'jquery-ui' );
		wp_enqueue_style('rsc-css', RSC_ASSETS_URL.'/rsc.css', array(), RSC_VIRSION);
		wp_enqueue_style('rsc-admin', RSC_ASSETS_URL.'/admin/rsc.css', array(), RSC_VIRSION);
		wp_enqueue_script('rsc-admin', RSC_ASSETS_URL.'/admin/rsc.js', array('jquery', 'jquery-ui-sortable'), RSC_VIRSION, true);
		
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
			__( 'Really Simple Calendar General Settings', RSC_TEXTDOMAIN), 
			__( 'Really Simple Calendar', RSC_TEXTDOMAIN),
			$this->_capability,
      RSC_GENERAL_SETTINGS_PAGE,
      array($this, 'general_settings_page'),
			'dashicons-calendar-alt',
		);
		
	}
	
	function general_settings_page(){
		//show setting page
	?>
	<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<div class="rsc-general-setting">
	
	<?php
	$options = array();
	$panel = null;
	
	//get panels
	if(empty($this->_panels)){
		echo '<section>Can\'t to find any panels.</section>';
		exit;
	}
	?>
	
	<?php 
	if(rsc_current_user_can('manage')){
		include_once RSC_ADMIN_DIR_INCLUDES.'/general-settings.php';
	};
	?>
	
	<!-- shortcode -->
	<?php if(rsc_current_user_can('edit')){
		include_once RSC_ADMIN_DIR_INCLUDES.'/setting-panels/part-shortcode.php';
	} ?>
	
	<!-- preview -->
	<h2><?php _e('Preview', RSC_TEXTDOMAIN); ?></h2>
	<div class="rsc-preview">
	<div id="rsc-calendar-message"></div>
	<section id="rsc-calendar-wrap">
		<?php rsc_get_calendar(); ?>
	</section>
	</div>

	
	</div>
	</div>
	<?php
	}
	
}

endif;