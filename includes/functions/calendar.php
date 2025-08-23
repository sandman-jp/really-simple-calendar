<?php

//get calendar
function smjp_rsc_get_calendar($atts=array()){
	return SMJP_RSC()->render_calendar($atts);
}

//get calendar params
function smjp_rsc_get_calendar_view(){
	return SMJP_RSC()->get_calendar_view();
}

//get start week
function smjp_rsc_get_start_week($time, $start=0){
	$w = wp_date('w', $time) - $start;
	
	if($w < 0){
		$w = 7 + $w;
	}
	
	return $w;
}

function smjp_rsc_get_style($style=false){
	if(!$style){
		$style = get_option(SMJP_RS_CALENDAR.'_style', smjp_rsc_get_default_style());
	}
	
	$style = apply_filters('smjp_rsc_get_stylesheet', $style);
	
	return sanitize_textarea_field($style, false);
}

function smjp_rsc_get_default_style(){
	
	$content = file_get_contents(SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/style-default.txt');
	
	return sanitize_textarea_field($content, false);
}
