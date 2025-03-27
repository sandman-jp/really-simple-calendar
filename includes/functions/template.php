<?php

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