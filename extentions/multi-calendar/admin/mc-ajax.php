<?php
namespace RSC\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\mc_ajax')):
	
class mc_ajax{
	
	public $params = array();
	
	function __construct(){
		add_action('wp_ajax_rsc_mc_get_calendar', array($this, 'get_calendar'));
	}
	
	function get_calendar(){
		
		$post_data = array();
		$params = array();
		$other_params = array();
		
		if($_POST['rsc_data']){
			$post_data = $_POST['rsc_data'];
			foreach($post_data as $data){
				if(is_array($data)){
					if(strpos($data['name'], RS_CALENDAR) !== false){
						if(preg_match('/^'.RS_CALENDAR.'_(.+)?\[(\d+)\]/', $data['name'], $m)){
							$params[RS_CALENDAR.'_'.$m[1]][$m[2]] = $data['value'];
						}else{
							$params[$data['name']] = $data['value'];
						}
					}else{
						$other_params[$data['name']] = $data['value'];
					}
				}
			}
			
			$is_vailed_post = false;
			
			if(isset($other_params['post_ID'])){
				$is_vailed_post = true;
				$params['id'] = (int)$other_params['post_ID'];
			}
			
			// var_dump($params);
			if($is_vailed_post){
				
				$params['has_style'] = 1;
				$this->params = $params;
				// add_filter('rsc_merge_calendar_params', array($this, 'merge_calendar_params'), 11);
				
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
	/*
	function merge_calendar_params($params){
		foreach($this->params as $k=>$v){
			if(is_array($v)){
				
			}else if($v !== ''){
				$params[$k] = $v;
			}
		}
		
		return $params;
		
	}
	*/
}

endif;