<?php
namespace sandmanjp\ReallySimpleCalendar\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\mc_ajax')):
	
class mc_ajax{
	
	public $params = array();
	
	function __construct(){
		add_action('wp_ajax_smjp_rsc_mc_get_calendar', array($this, 'get_calendar'));
		add_action('wp_ajax_smjp_rsc_mc_get_calendar_by_shortcode', array($this, 'get_calendar_by_shortcode'));
	}
	
	function get_calendar(){
		
		$post_data = array();
		$params = array();
		$other_params = array();
		
		if(isset($_POST[SMJP_RSC_.'data'])){
			$post_data = wp_unslash($_POST[SMJP_RSC_.'data']);
			
			if(!empty($post_data) && is_array($post_data)){
				foreach($post_data as $data){
					if(is_array($data)){
						
						$key = sanitize_text_field($data['name']);
						$value = smjp_rsc_sanitize_value($key, $data['value']);
						
						if(strpos($key, SMJP_RS_CALENDAR) !== false){
							
							if(preg_match('/^'.SMJP_RS_CALENDAR.'_(.+)?\[(\d+)\]/', $key, $m)){
								//var_dump($m);
								$kk = SMJP_RS_CALENDAR.'_'.$m[1];
								if(!isset($params[$kk])){
									$params[$kk] = array();
								}
								$params[$kk][$m[2]] = $value;
							}else{
								$params[$key] = $value;
							}
							
						}else{
							$other_params[$key] = $value;
						}
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
				// add_filter('smjp_rsc_merge_calendar_params', array($this, 'merge_calendar_params'), 11);
				
				$params[SMJP_RS_CALENDAR.'_style'] .= PHP_EOL.$params[SMJP_RS_CALENDAR.'_style_post'];
				unset($params[SMJP_RS_CALENDAR.'_style_post']);
				
				$cal = SMJP_RSC()->get_calendar($params);
				
				if($cal){
					wp_send_json_success($cal);
					die();
				}
			}
			
		}
		wp_send_json_error(esc_html__('Loading the calendar failed.', 'really-simple-calendar'));
		die();
	}
	
	function get_calendar_by_shortcode(){
		
		//check nonce value.
		if(!isset($_POST['_nonce_val']) || !isset($_POST['_nonce_key'])){
			wp_send_json_error(esc_html__('Invailed post. Loading the calendar failed. error code E2', 'really-simple-calendar'));
			die();
		}
		
		if(!wp_verify_nonce(
				sanitize_text_field(wp_unslash($_POST['_nonce_val'])), 
				sanitize_text_field(wp_unslash(SMJP_RSC_.$_POST['_nonce_key']))
			)){
			wp_send_json_error(esc_html__('Invailed nonce. Loading the calendar failed. error code E3', 'really-simple-calendar'));
			die();
		}
		
		//check vailed shortcode.
		$key = SMJP_RSC_.'data';
		if(!isset($_POST[$key]) 
			|| !preg_match("/^\[rsc.*?\]$/", sanitize_text_field(wp_unslash($_POST[$key])))){
			wp_send_json_error(esc_html__('No data. Loading the calendar failed.', 'really-simple-calendar'));
			die();
		}
		
		$post_data = sanitize_text_field(wp_unslash($_POST[$key]));
		SMJP_RSC()->set_style(1);
		
		$post_data = preg_replace("/\sid=/", ' POST_id', $post_data);
		
		ob_start();
		echo do_shortcode($post_data);
		$cal = ob_get_clean();
		
		
		if($cal){
			wp_send_json_success($cal);
			die();
		}
		wp_send_json_error(esc_html__('Loading the calendar failed.', 'really-simple-calendar'));
		die();
	}
}

endif;