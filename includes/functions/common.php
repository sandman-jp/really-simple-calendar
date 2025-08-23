<?php
//
function SMJP_RSC(){
	global $smjp_rsc;
	
	if(!$smjp_rsc){
		$smjp_rsc = new ReallySimpleCalendar;
	}
	
	return $smjp_rsc;
}

function SMJP_RSC_ADMIN(){
	global $smjp_rsc;
	
	if(is_admin()){
		if(!$smjp_rsc){
			$smjp_rsc = SMJP_RSC();
		}
		return $smjp_rsc->get_admin();
	}
	return null;
}

function smjp_rsc_get_default_capabilities(){
	return array(
		'editor' => 'manage',
		'author' => 'edit',
		'contributor' => 'edit',
		'subscriber' => 'read',
	);
}

function smjp_rsc_current_user_can($cap, $section='rsc'){
	
	$user_role = wp_get_current_user()->roles[0];
	if('administrator' == $user_role){
		return true;
	}
	
	$caps = get_option(SMJP_RS_CALENDAR.'_capability', smjp_rsc_get_default_capabilities());
	$capabilities = array(
		'full' => array('full', 'manage', 'edit', 'read'),
		'manage' => array('manage', 'edit', 'read'),
		'edit' => array('edit', 'read'),
		'read' => array('read'),
	);
	
	$role = $caps[$section][$user_role];
	
	if(isset($caps[$section][$user_role]) && in_array($cap, $capabilities[$role])){
		return true;
	}
	
	return false;
}

function smjp_rsc_get_basename($key){
	return preg_replace('/^('.SMJP_RS_CALENDAR.'_[a-z]+)?_*.*/', '$1', $key);
}

function smjp_rsc_sanitize_value($key, $val){
	if(is_array($val)){
		foreach($val as $k=>$v){
			$val[$k] = smjp_rsc_sanitize_value($k, $v);
		}
	}else if($key == SMJP_RS_CALENDAR.'_style'){
		$val = sanitize_textarea_field($val);
	}else{
		$val = sanitize_text_field($val);
	}
	
	return $val;
}

function smjp_rsc_update_option($key, $data){
	$post_id = get_the_ID();
	
	if($post_id){
		return update_post_meta($post_id, $key, $data);
	}else{
		return update_option($key, $data);
	}
	
}