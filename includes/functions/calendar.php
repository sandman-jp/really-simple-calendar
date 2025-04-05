<?php

//get calendar
function rsc_get_calendar($atts=array()){
	return RSC()->render_calendar($atts);
}

//get calendar params
function rsc_get_calendar_view(){
	return RSC()->get_calendar_view();
}

//get start week
function rsc_get_start_week($time, $start=0){
	$w = wp_date('w', $time) - $start;
	
	if($w < 0){
		$w = 7 + $w;
	}
	
	return $w;
}

function rsc_get_style($style=false){
	if(!$style){
		$style = get_option(RS_CALENDAR.'_style');
	}
	if(empty($style)){
		$style = rsc_get_default_style();
	}
	return rsc_get_esc($style, false);
}

function rsc_get_default_style(){
	
	$content = file_get_contents(RSC_ADMIN_DIR_INCLUDES.'/panels/style-default.txt');
	
	return rsc_get_esc($content, false);
}
