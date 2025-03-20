<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\panel')):
	
abstract class panel{
	public $name;
	public $label;
	public $info;
	
	public $fields = array();
	public $lock_fields = array();
	
	public $settings = array();
	public $locked = array();
	
	function get_name(){
		return $this->name;
	}
	
	function get_info(){
		return $this->info;
	}
	
	function get_lock_fields(){
		return apply_filters('rsc_get_panel_lock_fields', $this->lock_fields);
	}
	
	function get_fields(){
		$fields = array_merge($this->lock_fields, $this->fields);
		return apply_filters('rsc_get_panel_fields', $fields);
	}
	
	function update($key, $post_data){
		$this->settings[$key] = $post_data;
		update_option($key, $post_data);
		
	}
	
	function save($settings){
		$this->settings = $settings;
		
		//keys for options
		$set_options = apply_filters('rsc_option_fields', $this->get_fields());
		$this->locked = array();
		
		if(!rsc_current_user_can('full')){
			//get locked fields.
			foreach($this->lock_fields as $key){
				$this->locked[$key] = get_option($key);
			}
		}
		
		foreach($set_options as $key){
			
			$post_data = '';
			if(isset($_POST[$key])){
				$post_data = $_POST[$key]; 
			}
			
			if($post_data === ''){
				continue;
			}
			
			if(empty($this->locked)){
				//administrator or full
				$this->settings[$key] = $post_data;
				update_option($key, $post_data);
				
			}else if(!isset($this->locked[$key.'_lock']) || (isset($this->locked[$key.'_lock']) && !$this->locked[$key.'_lock'])){
				
				$this->update($key, $post_data);
				
			}
			
		}
		
		return $this->settings;
	}
	
	abstract protected function get_label();
	abstract protected function echo($settings);
}

endif;
