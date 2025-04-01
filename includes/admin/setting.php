<?php

namespace RSC\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\setting')):

class setting{
	
	public $panels = array();
	public $fields = array();
	public $lock_fields = array();
	
	public $post_data = array();
	private $_panel_classes;
	
	function __construct(){
		
		add_action('admin_init', array($this, 'init'));
		add_action('admin_head', array($this, 'admin_head'));
	}
	
	function init(){
		$this->_panel_classes = RSC()->get_panels();
		
		if(isset($_GET['page']) && $_GET['page'] == RSC_GENERAL_SETTINGS_PAGE){
			$this->set_panel();
		}
	}
	
	function get_post_data(){
		return $this->post_data;
	}
	
	function add_setting_panel($class){
		
		$class = 'RSC\Admin\Panel\\'.$class;
		if(class_exists($class)){
			$this->panels[] = new $class;
		}else{
			unset($this->_panel_classes[$class]);
		}
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
	
	function set_panel(){
		
		$current_user = wp_get_current_user();
		
		//get manage panels
		$this->_panel_classes = apply_filters('rsc_get_admin_manage_panel', $this->_panel_classes);
		
		if(in_array('administrator', $current_user->roles)){
			$this->_panel_classes[] = 'config';
		}else if(isset($_GET['rsc']) && $_GET['rsc'] == 'config'){
			wp_die( __( 'Sorry, you are not allowed to access this page.' ), 403 );
		}
		$this->_panel_classes[] = 'contact';
	}
	
	function get_settings(){
		$settings = rsc_get_calendar_params();
		
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
	
	function echo(){
		//show setting page
	?>
	<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	
	<div class="rsc-general-setting">
	
	<?php
	$options = array();
	$panel = null;
	$panel_name = 'view';
	
	if(isset($_GET['rsc']) && !in_array($_GET['rsc'], $this->_panel_classes)){
		echo '<section><div class="error"><p>Can\'t to find your requested panel.</p></div></section>';
		exit;
	}else if(isset($_GET['rsc'])){
		$panel_name = $_GET['rsc'];
	}
	
	//get panels
	if(empty($this->panels)){
		echo '<section><div class="error"><p>Can\'t to find any panels.</p></div></section>';
		exit;
	}
	?>
	
	<?php 
	if(rsc_current_user_can('manage')){
		
		include_once RSC_ADMIN_DIR_INCLUDES.'/part-settings.php';
	};
	?>
	
	<?php
	if((isset($panel) && $panel->use_preview) || (empty($panel) && rsc_current_user_can('read'))):
	?>
	
	<!-- shortcode -->
	<?php if(rsc_current_user_can('edit')){
		include_once RSC_ADMIN_DIR_INCLUDES.'/panels/part-shortcode.php';
	} ?>
	
	<!-- preview -->
	<h2><?php _e('Preview', 'really-simple-calendar'); ?></h2>
	<div class="rsc-preview">
		<div id="rsc-calendar-message"></div>
		<section id="rsc-calendar-wrap">
			<style>
				<?php echo rsc_get_style();?>
			</style>
			<?php rsc_get_calendar(); ?>
		</section>
	</div>
	<?php endif; ?>
	
	</div>
	</div>
	<?php
	}
}

endif;