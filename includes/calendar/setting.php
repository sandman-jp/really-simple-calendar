<?php

namespace RSC\calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('RSC\Calendar\setting')):

class setting {
	
	private $_params = false;
	
	public $options;
	
	function get_option($key){
		$opt = get_option($key);
		$opt = apply_filters('rsc_get_option', $opt, $key);
		$opt = apply_filters('rsc_get_option_'.$key, $opt);
		$opt = isset($this->options[$key]) ? $this->options[$key] : $opt;
		return $opt;
	}
	
}

endif;