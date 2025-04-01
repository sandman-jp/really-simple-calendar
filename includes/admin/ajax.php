<?php

namespace RSC\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\ajax')):

class ajax{
	
	function __construct(){
		add_action('wp_ajax_rsc_get_calendar', array($this, 'get_calendar'));
		add_action('wp_ajax_rsc_get_calendar_by_shortcode', array($this, 'get_calendar_by_shortcode'));
	}
	
	function get_calendar_by_shortcode(){
		
		if(!isset($_POST['rsc_data']) || !preg_match("/^\[rsc.*?\]$/", $_POST['rsc_data'])){
			return;
		}
		
		$post_data = wp_unslash($_POST['rsc_data']);
		
		RSC()->set_style(1);
		
		ob_start();
		echo do_shortcode($post_data);
		$cal = ob_get_clean();
		
		
		if($cal){
			wp_send_json_success($cal);
			die();
		}
		wp_send_json_error(__('Loading the calendar failed.', 'really-simple-calendar'));
		die();
	}
	
	function get_calendar(){
		
		if($_POST['rsc_data']){
			$params = array();
			foreach($_POST['rsc_data'] as $data){
				if(is_array($data)){
					if(preg_match('/(.+)?\[(\d+)\]/', $data['name'], $m)){
						$params[$m[1]][$m[2]] = $data['value'];
					}else{
						$params[$data['name']] = $data['value'];
					}
				}
			}
			// var_dump($params);
			if(isset($params['rsc_save_settings_nonce']) && isset($params['rsc_setting'])
					&& wp_verify_nonce( $params['rsc_save_settings_nonce'], 'rsc_'.$params['rsc_setting'])){
				
				$params['has_style'] = 1;
				$cal = RSC()->get_calendar($params);
				
				if($cal){
					wp_send_json_success($cal);
					die();
				}
			}
			
		}
		wp_send_json_error(__('Loading the calendar failed.', 'really-simple-calendar'));
		die();
	}
}

endif;