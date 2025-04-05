<?php
namespace RSC\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\mc_setting')):
	
class mc_setting extends setting{
	
	private $_panels = array();
	private $_panel_classes;
	
	private $_settings = array();
	
	function __construct(){
		
		if(!is_admin()){
			return ;
		}
		$this->_panel_classes = RSC()->get_panels();
		
		add_action('admin_head', array($this, 'init'));
		add_action('edit_form_after_editor', array($this, 'settings'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 11);
		
	}
	
	function init(){
		$screen = get_current_screen();
		if($screen->id == RS_CALENDAR){
			//get manage panels
			$this->_panel_classes = apply_filters('rsc_get_multi_post_panel', $this->_panel_classes);
			
			//switch setting class
			add_filter('rsc_get_admin_setting', array($this, 'get_setting'));
			add_filter('rsc_is_disabled', array($this, 'is_disabled'), 10, 2);
			add_filter('rsc_get_event_serch', array($this, 'get_event_search'));
			add_filter('rsc_locked_fields', array($this, 'locked_fields'));
			add_action('rsc_get_default_shortcode', array($this, 'get_default_shortcode'));
		}
	}
	
	function admin_enqueue_scripts(){
		$screen = get_current_screen();
		if($screen->id == RS_CALENDAR){
			wp_enqueue_style('rsc-mc-admin', RSC_MC_URL.'/assets/rsc-mc.css', array(), RSC_VIRSION);
			wp_enqueue_script('rsc-mc-admin', RSC_MC_URL.'/assets/rsc-mc.js', array('jquery'), RSC_VIRSION, true);
		}
	}
	
	function get_setting($setting){
		return $this;
	}
	
	function is_disabled($is_disabled, $name){
		
		if($is_disabled){
			return $is_disabled;
		}
		
		if(strpos($name, RS_CALENDAR.'_event_lock') !== false && $name != RS_CALENDAR.'_event_lock[x]'){
			preg_match("/.+?\[(\d+)\]/", $name, $m);
			$defaults = get_option(RS_CALENDAR.'_event_number');
			// var_dump($name);
			return isset($defaults[$m[1]]);
		}
		
		//get lock signup_nonce_fields()
		$panels = RSC_ADMIN()->get_panels();
		$lock_fields = array();
		
		foreach($panels as $panel){
			$lock_fields = array_merge($lock_fields, $panel->get_lock_fields());
		}
		
		if(in_array($name, $lock_fields)){
			$is_disabled = get_option($name);
		}
		
		
		return $is_disabled;
	}
	
	function settings(){
		$panels = apply_filters('rsc_get_setting_panels', $this->_panel_classes);
		// var_dump($panels);
		foreach($panels as $panel){
			$this->add_setting_panel($panel);
		}
		
		?>
		<div class="wrap">
		
		<div class="rsc-general-setting">
		
		<?php
		$options = array();
		$panel = null;
		$panel_name = 'view';
		
		//get panels
		if(empty($this->panels)){
			echo '<section><div class="error"><p>Can\'t to find any panels.</p></div></section>';
			exit;
		}
		?>
		
		<?php 
		if(rsc_current_user_can('manage')):
			include_once RSC_MC_DIR.'/admin/part-settings.php';
		?>
		<p class="submit"><button class="button rsc-reload-button"><?php esc_html_e('Reload preview', 'really-simple-calendar'); ?></button></p>
		
		<?php endif; ?>
		
		<?php
		if($panel->use_preview):
		?>
		
		<!-- shortcode -->
		<?php include_once RSC_ADMIN_DIR_INCLUDES.'/panels/part-shortcode.php'; ?>
		
		<!-- preview -->
		<h2><?php esc_html_e('Preview', 'really-simple-calendar'); ?></h2>
		<div class="rsc-preview">
			<div id="rsc-calendar-message"></div>
			<section id="rsc-calendar-wrap">
				<?php rsc_get_calendar(); ?>
			</section>
		</div>
		<?php endif; ?>
		
		</div>
		</div>
		<?php
	}
	
	function get_event_search($search){
		ob_start();
		?>
		<div class="tablenav top">
			<div class="alignright actions">
				<label><input id="rsc-hide-general-events" type="checkbox"> <?php esc_html_e('Hide general events.', 'really-simple-calendar'); ?></label>
			</div>
		</div>
		<?php
		
		return ob_get_clean();
	}
	
	function locked_fields($locked){
		
		$calendar_id = get_the_ID();
		
		if($calendar_id && is_array($locked)){
			foreach($locked as $k=>$v){
				if(empty($v)){
					$locked[$k] = get_post_meta($calendar_id, $k, true);
				}
			}
		}
		
		return $locked;
	}
	
	function get_default_shortcode($val){
		
		return '[rsc id="'.get_the_ID().'"]';
	}
}

endif;