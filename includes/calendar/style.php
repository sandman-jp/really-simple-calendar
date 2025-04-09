<?php

namespace RSC\calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('RSC\Calendar\style')):

class style extends setting {
	
	private $_params = false;
	
	public $options;
	
	function set_style($args){
		
		if(!empty($args)){
			foreach($args as $k=>$v){
				$this->options[$k] = $v;
			}
		}
		
		if(isset($args['has_style'])){
			add_filter('rsc_after_render_calendar_tables', array($this, 'add_style'));
		}
	}
	
	function add_style($html){
		
		$style = $this->get_option(RS_CALENDAR.'_style');
		
		ob_start();
	?>
	<style><?php echo rsc_get_style($style); ?></style>
	<?php
		return ob_get_clean().$html;
	}
	
}

endif;