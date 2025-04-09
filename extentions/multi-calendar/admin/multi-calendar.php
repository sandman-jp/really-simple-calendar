<?php
namespace RSC\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\multi_calendar')):
	
class multi_calendar{
	
	function __construct(){
		add_action('save_post', array($this, 'save_update_time'), 10, 2);
	}
	
	function save_update_time($post_id, $post){
		// die();
		if(isset($_POST['rsc_update_time'])){
			update_post_meta($post_id, 'rsc_update_time', (int)$_POST['rsc_update_time']);
		}
		
	}
}

endif;