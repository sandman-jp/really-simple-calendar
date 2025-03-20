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
	
	
	abstract protected function get_label();
	abstract protected function echo($settings);
}

endif;
