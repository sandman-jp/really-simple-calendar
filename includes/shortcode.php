<?php

namespace RSC;

use RSC;

if ( ! defined('ABSPATH') ) {
	exit; // Exit if accessed directly
}



if(!class_exists('RSC\shortcode')):

class shortcode{
	
	function __construct(){
		add_shortcode('rsc', array($this, 'do_shortcode'));
	}
	
	function do_shortcode($atts=array()){
		
		foreach($atts as $k=>$v){
			// list($key, $val) = explode('=', $att);
			if(!get_option('calendar_'.$k.'_lock')){
				$atts['calendar_'.$k] = str_replace('"', '', $v);
			}
			
		}
		
		if(isset($atts['calendar_from'])){
			if(is_numeric($atts['calendar_from']) && (int)$atts['calendar_from'] < 0){
				$atts['calendar_previous_from'] = $atts['calendar_from'];
				$atts['calendar_from'] = 'previous';
			}else if(preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $atts['calendar_from'])){
				$atts['calendar_from_date'] = $atts['calendar_from'];
				$atts['calendar_from'] = 'date';
			}
		}
		
		if(isset($atts['calendar_period'])){
			if(is_numeric($atts['calendar_period']) && (int)$atts['calendar_period'] >= 0){
				$atts['calendar_period_last'] = $atts['calendar_period'];
				$atts['calendar_period'] = 'last';
			}else if(preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $atts['calendar_period'])){
				$atts['calendar_period_date'] = $atts['calendar_period'];
				$atts['calendar_period'] = 'date';
			}
		}
		
		return RSC()->get_calendar($atts);
		
	}
	
}

endif;