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
	public $use_save = true;
	
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
	
	function save($settings, $post_data){
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
		
		$this->locked = apply_filters('rsc_locked_fields', $this->locked);
		
		foreach($set_options as $key){
			
			// $post_data = RSC_ADMIN()
			if(isset($post_data[$key])){
				$basename = rsc_get_basename($key);
				if(empty($this->locked)){
					//administrator or full
					$this->updated[$key] = $this->update($key, $post_data[$key]);
					
				}else if(!isset($this->locked[$basename.'_lock']) || !$this->locked[$basename.'_lock']){
					
					$this->updated[$key] = $this->update($key, $post_data[$key]);
					
				}else if(isset($this->locked[$basename.'_lock']) && is_array($this->locked[$basename.'_lock'])){
					//event update
					$update_data = array();
					foreach($post_data[$key] as $k=>$v){
						if(!isset($this->locked[$basename.'_lock'][$k]) || !$this->locked[$basename.'_lock'][$k]){
							$update_data[$k] = $v;
						}
					}
					if(!empty($update_data)){
						$post_data[$key] = $update_data;
						$this->updated[$key] = $this->update($key, $update_data);
					}
				}else{
					// var_dump($key.'_lock');
					
				}
			}
			
		}
		
		foreach($this->updated as $k=>$v){
			if($v){
				$this->settings[$k] = $post_data[$k];
			}
		}
		
		return $this->settings;
	}
	
	abstract protected function get_label();
	abstract protected function echo($settings);
}

endif;
