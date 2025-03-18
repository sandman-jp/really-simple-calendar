<?php

//get calendar
function rsc_get_calendar($atts=array()){
	return RSC()->render_calendar($atts);
}

//get calendar params
function rsc_get_calendar_params(){
	return RSC()->get_calendar_params();
}

//get start week
function rsc_get_start_week($time, $start=0){
	$w = wp_date('w', $time) - $start;
	
	if($w < 0){
		$w = 7 + $w;
	}
	
	return $w;
}
