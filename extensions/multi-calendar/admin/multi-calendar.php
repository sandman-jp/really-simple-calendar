<?php
namespace sandmanjp\ReallySimpleCalendar\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\multi_calendar')):
	
class multi_calendar{
	
	
	function __construct(){
		add_filter('smjp_rsc_capability_section', array($this, 'add_capability_section'));
	}
	
	function add_capability_section($caps){
		$caps = $caps + array('mc'=>'Multi Calendar');
		return $caps;
	}
	
}

endif;