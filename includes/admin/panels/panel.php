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
	
	public $is_update = true;
	public $updated = array();
	public $use_preview = true;
	
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
		return rsc_update_option($key, $post_data);
		
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
			
			$post_data = RSC_ADMIN()->get_setting()->get_post_data();
			
			if(isset($post_data[$key])){
				if(empty($this->locked)){
					//administrator or full
					$this->updated[$key] = $this->update($key, $post_data[$key]);
					
				}else if(!isset($this->locked[$key.'_lock']) || (isset($this->locked[$key.'_lock']) && !$this->locked[$key.'_lock'])){
					
					$this->updated[$key] = $this->update($key, $post_data[$key]);
					
				}
			}
			
		}
		
		return $this->settings;
	}
	
	abstract protected function get_label();
	abstract protected function echo($settings);
}

endif;
