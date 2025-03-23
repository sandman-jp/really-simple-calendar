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
			if(!get_option(RS_CALENDAR.'_'.$k.'_lock')){
				$atts[RS_CALENDAR.'_'.$k] = str_replace('"', '', $v);
			}
			
		}
		
		if(isset($atts[RS_CALENDAR.'_from'])){
			if(is_numeric($atts[RS_CALENDAR.'_from']) && (int)$atts[RS_CALENDAR.'_from'] < 0){
				$atts[RS_CALENDAR.'_previous_from'] = $atts[RS_CALENDAR.'_from'];
				$atts[RS_CALENDAR.'_from'] = 'previous';
			}else if(preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $atts[RS_CALENDAR.'_from'])){
				$atts[RS_CALENDAR.'_from_date'] = $atts[RS_CALENDAR.'_from'];
				$atts[RS_CALENDAR.'_from'] = 'date';
			}
		}
		
		if(isset($atts[RS_CALENDAR.'_period'])){
			if(is_numeric($atts[RS_CALENDAR.'_period']) && (int)$atts[RS_CALENDAR.'_period'] >= 0){
				$atts[RS_CALENDAR.'_period_last'] = $atts[RS_CALENDAR.'_period'];
				$atts[RS_CALENDAR.'_period'] = 'last';
			}else if(preg_match("/^\d{4}\-\d{2}\-\d{2}$/", $atts[RS_CALENDAR.'_period'])){
				$atts[RS_CALENDAR.'_period_date'] = $atts[RS_CALENDAR.'_period'];
				$atts[RS_CALENDAR.'_period'] = 'date';
			}
		}
		
		return RSC()->get_calendar($atts);
		
	}
	
}

endif;