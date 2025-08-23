<?php

namespace sandmanjp\ReallySimpleCalendar\calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('sandmanjp\ReallySimpleCalendar\Calendar\style')):

class style extends setting {
	
	private $_params = false;
	
	public $options;
	
	function set_style($args){
		$this->options = array();
		
		if(!empty($args)){
			foreach($args as $k=>$v){
				$this->options[$k] = $v;
			}
		}
		
		add_filter('smjp_rsc_after_render_calendar_tables', array($this, 'add_style'));
	}
	
	function add_style($html){
		
		$style = $this->get_option(SMJP_RS_CALENDAR.'_style');
		apply_filters('smjp_rsc_get_stylesheet', $style);
		ob_start();
	?>
	<style><?php echo esc_textarea(smjp_rsc_get_style($style)); ?></style>
	<?php
		return ob_get_clean().$html;
	}
	
}

endif;