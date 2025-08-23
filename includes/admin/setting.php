<?php

namespace sandmanjp\ReallySimpleCalendar\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\setting')):

class setting{
	
	public $panel = null;
	public $panels = array();
	public $fields = array();
	public $lock_fields = array();
	
	public $post_data = array();
	private $_panel_classes;
	private $_settings = array();
	private $_error_message = '';
	
	function __construct(){
		
		add_action('admin_init', array($this, 'init'));
		// add_action('admin_head', array($this, 'admin_head'));
	}
	
	function init(){
		$this->_panel_classes = SMJP_RSC()->get_panels();
		
		//just checking URL
		if(!isset($_GET['page']) || sanitize_text_field(wp_unslash($_GET['page'])) != SMJP_RSC_GENERAL_SETTINGS_PAGE){
			return;
		}
		$panel_name = isset($_GET['rsc']) ? sanitize_text_field(wp_unslash($_GET['rsc'])) : 'view';
		
		
		$this->set_panels();
		
		$panels = apply_filters('smjp_rsc_get_setting_panels', $this->_panel_classes);
		
		foreach($panels as $panel){
			$this->add_setting_panel($panel);
		}
		
		$this->_error_message = $this->_is_unveiled_panel();
		if(!empty($this->error_message)){
			return;
		}
		
		// $this->set_panels();
		$this->panel = $this->get_panel($panel_name);
		
		$this->fields = $this->panel->get_fields();
		$this->lock_fields = $this->panel->get_lock_fields();
		$this->_settings = $this->get_settings();
		
		
		//save settings
		$nonce_name = SMJP_RSC_.'save_settings_nonce';
		if(isset($_POST[$nonce_name])
			 && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_name])), SMJP_RSC_.$panel_name)){
			$this->_settings = $this->save();
		}
	}
	
	function _is_unveiled_panel(){
		
		$message = false;
		//just checking URL
		if(isset($_GET['rsc']) && !in_array(sanitize_text_field(wp_unslash($_GET['rsc'])), $this->_panel_classes)){
			return esc_html_e('Can\'t to find your requested panel.', 'really-simple-calendar');
		}
		
		//get panels
		if(empty($this->panels)){
			return esc_html_e('Couldn\'t to find any panels.', 'really-simple-calendar');
		}
		
		return $message;
	}
	
	function get_post_data(){
		return $this->post_data;
	}
	
	function add_setting_panel($class){
		
		$class = 'sandmanjp\ReallySimpleCalendar\Admin\Panel\\'.$class;
		if(class_exists($class)){
			$this->panels[] = new $class;
		}else{
			unset($this->_panel_classes[$class]);
		}
	}
	
	function get_panel($panel_name){
		if(!empty($this->panel)){
			return $this->panel;
		}
		//get panels
		foreach($this->panels as $p){
			if($panel_name == $p->get_name()){
				return $p;
			}
		}
		return null;
	}
	
	/*
	 * $_GET['rsc'] is URL parameter.
	 */
	function set_panels(){
		
		$current_user = wp_get_current_user();
		
		//get manage panels
		$this->_panel_classes = apply_filters('smjp_rsc_get_admin_manage_panel', $this->_panel_classes);
		
		if(in_array('administrator', $current_user->roles)){
			$this->_panel_classes[] = 'config';
		}else if(isset($_GET['rsc']) && sanitize_text_field(wp_unslash($_GET['rsc'])) == 'config'){//just checking URL
			wp_die(esc_html__('Sorry, you are not allowed to access this page.', 'really-simple-calendar'), 403 );
		}
		$this->_panel_classes[] = 'contact';
	}
	
	function get_settings(){
		$settings = smjp_rsc_get_calendar_view();
		
		foreach($this->fields as $key){
			if(!isset($settings[$key])){
				$op = get_option($key);
				if($op){
					//overwrite by return value
					$settings[$key] = $op;
				}else if(!isset($settings[$key])){
					//empty value 
					$settings[$key] = '';
				}
			}
		}
		return $settings;
	}
	
	function save(){
		//load settings
		$settings = $this->get_settings();
		
		if(smjp_rsc_current_user_can('manage') && !isset($_POST['filter_action'])){
			
			$this->post_data = array();
			
			foreach($_POST as $k=>$v){
				if(preg_match('/^'.SMJP_RS_CALENDAR.'_/', $k)){
					$this->post_data[$k] = smjp_rsc_sanitize_value($k, wp_unslash($v));
				}
			}
			$settings = $this->panel->save($settings, $this->post_data);
		}
		return $settings;
	}
	
	function echo(){
		
		//show setting page
	?>
	<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html(get_admin_page_title()); ?></h1>
	
	<div class="rsc-general-setting">
	
	<?php
	$panel = $this->panel;
	$settings = $this->_settings;
	?>
	
	<?php 
	if(smjp_rsc_current_user_can('manage')){
		include_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/part-settings.php';
	};
	?>
	
	<?php
	if((isset($panel) && $panel->use_preview) || (empty($panel) && smjp_rsc_current_user_can('read'))):
	?>
	
	<!-- shortcode -->
	<?php if(smjp_rsc_current_user_can('edit')){
		include_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/part-shortcode.php';
	} ?>
	
	<!-- preview -->
	<h2><?php esc_html_e('Preview', 'really-simple-calendar'); ?></h2>
	<div class="rsc-preview">
		<div id="rsc-calendar-message"></div>
		<section class="rsc-calendar-wrap">
			<?php smjp_rsc_get_calendar(); ?>
		</section>
	</div>
	<?php endif; ?>
	
	</div>
	</div>
	<?php
	}
}

endif;