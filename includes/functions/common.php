<?php
//
function RSC(){
	global $RSC;
	
	if(!$RSC){
		$RSC = new ReallySimpleCalendar;
	}
	
	return $RSC;
}

function RSC_ADMIN(){
	global $RSC;
	
	if(is_admin()){
		if(!$RSC){
			$RSC = RSC();
		}
		return $RSC->get_admin();
	}
	return null;
}

function rsc_get_default_capabilities(){
	return array(
		'editor' => 'manage',
		'author' => 'edit',
		'contributor' => 'edit',
		'subscriber' => 'read',
	);
}

function rsc_current_user_can($cap){
	
	$user_role = wp_get_current_user()->roles[0];
	if('administrator' == $user_role){
		return true;
	}
	
	$caps = get_option(RS_CALENDAR.'_capability', rsc_get_default_capabilities());
	$capabilities = array(
		'full' => array('full', 'manage', 'edit', 'read'),
		'manage' => array('manage', 'edit', 'read'),
		'edit' => array('edit', 'read'),
		'read' => array('read'),
	);
	
	$role = $caps[$user_role];
	
	if(isset($caps[$user_role]) && in_array($cap, $capabilities[$role])){
		return true;
	}
	
	return false;
}

function rsc_get_basename($key){
	return preg_replace('/^('.RS_CALENDAR.'_[a-z]+)?_*.*/', '$1', $key);
}

function rsc_update_option($key, $data){
	$post_id = get_the_ID();
	
	if($post_id){
		return update_post_meta($post_id, $key, $data);
	}else{
		return update_option($key, $data);
	}
	
}