<?php
//
function RSC(){
	global $RSC;
	
	if(!$RSC){
		$RSC = new CustomFieldsCalendar();
	}
	
	return $RSC;
}

//get rsc post type
function rsc_get_post_type(){
	return RSC()->get_calendar_post_type();
}

//get calendar
function rsc_get_calendar($atts=array()){
	return RSC()->render_calendar($atts);
}

//get start week
function rsc_get_start_week($time, $start=0){
	$w = wp_date('w', $time) - $start;
	
	if($w < 0){
		$w = 7 + $w;
	}
	
	return $w;
}

/* template */
function rsc_get_template_file_path($path){
	
	$roots = array(
		get_stylesheet_directory().'/rsc',
		get_template_directory().'/rsc',
		RSC_DIR_TEMPLATES,
	);
	
	$fullpath = '';
	foreach($roots as $root){
		$fullpath = $root.$path.'.php';
		
		if(file_exists($fullpath)){
			return $fullpath;
		}
	}
	return false;
}

function rsc_get_template_part($path, $sub=null, $args=array()){
	
	if(!is_null($sub) ){
		if(is_array($sub)){
			$args = $sub;
			$sub = '';
		}else{
			$path .= '-'.$sub;
		}
	}
	
	$fullpath = rsc_get_template_file_path($path);
	
	
	if($fullpath){
		
		if(!empty($args)){
			extract($args);
		}
		
		include $fullpath;
	}
}