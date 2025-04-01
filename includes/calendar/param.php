<?php

namespace RSC\calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('RSC\Calendar\param')):

class param extends setting {
	
	private $_params = false;
	
	public $options;
	
	function get_params($args){
		
		if(!empty($args)){
			foreach($args as $k=>$v){
				$this->options[$k] = $v;
			}
		}
		$params = $this->_get_view_settings();
		
		//param for admin or front
		$params = apply_filters('rsc_get_calendar_params', $params);
		
		return $params;
		
	}
	
	private function _get_view_settings(){
		
		if(empty($this->_params)){
			$this->_params = array();
			
			$date = new \DateTime('today', wp_timezone());
			
			
			$type = $this->get_option(RS_CALENDAR.'_type');
			if(empty($type)){
				$type = 'month';
			}
			$this->_params[RS_CALENDAR.'_type'] = $type;
			
			$from = $this->get_option(RS_CALENDAR.'_from');
			if(empty($from)){
				$from = 'current';
			}
			$this->_params[RS_CALENDAR.'_from'] = $from;
			
			$start_of_week = $this->get_option(RS_CALENDAR.'_start_of_week');
			
			if($start_of_week == 'today'){
				$start_of_week = wp_date('w');
			}else if(empty($start_of_week)){
				$start_of_week = 0;
			}
			$this->_params[RS_CALENDAR.'_start_of_week'] = $start_of_week;
			
			$this->_params[RS_CALENDAR.'_align'] = 0;
			
			switch($type){
			case 'week':
				// $current_day_of_week = rsc_get_start_week($date->format('U'), $start_of_week);
				break;
			case 'day':
				$this->_params['align'] = $this->get_option(RS_CALENDAR.'_align');
				$this->_params[RS_CALENDAR.'_align'] = $this->_params['align'];
				break;
			default:
			}
			
			switch($from){
				case 'previous':
					$previous_from = $this->get_option(RS_CALENDAR.'_previous_from');
					$this->_params[RS_CALENDAR.'_previous_from'] = $previous_from;
					break;
				case 'date':
					$date_from = $this->get_option(RS_CALENDAR.'_from_date');
					$this->_params[RS_CALENDAR.'_from_date'] = $date_from;
					break;
			}
			
			switch($from){
				case 'previous':
					$previous_from = $this->get_option(RS_CALENDAR.'_previous_from');
					$this->_params[RS_CALENDAR.'_previous_from'] = $previous_from;
					break;
				case 'date':
					$date_from = $this->get_option(RS_CALENDAR.'_from_date');
					$this->_params[RS_CALENDAR.'_from_date'] = $date_from;
					break;
			}
			
			$period = $this->get_option(RS_CALENDAR.'_period');
			if(empty($period)){
				$period = 'last';
			}
			$this->_params[RS_CALENDAR.'_period'] = $period;
			
			if($period == 'last'){
				$last_period = $this->get_option(RS_CALENDAR.'_period_last');
				$this->_params[RS_CALENDAR.'_period_last'] = $last_period;
			}else{
				$date_period = $this->get_option(RS_CALENDAR.'_period_date');
				$this->_params[RS_CALENDAR.'_period_date'] = $date_period;
			}
			
			
			$this->_params = apply_filters('rsc_merge_calendar_params', $this->_params);
			// var_dump($this->_params);
			$type = $this->_params[RS_CALENDAR.'_type'];
			$from = $this->_params[RS_CALENDAR.'_from'];
			$start_of_week = $this->_params[RS_CALENDAR.'_start_of_week'];
			switch($type){
			case 'week':
				$current_day_of_week = rsc_get_start_week($date->format('U'), $start_of_week);
				break;
			}
			$this->_params['align'] = $this->_params[RS_CALENDAR.'_align'];
			if(isset($previous_from)){
				$previous_from = $this->_params[RS_CALENDAR.'_previous_from'];
			}
			if(isset($date_from)){
				$date_from = $this->_params[RS_CALENDAR.'_from_date'];
			}
			if(isset($previous_from)){
				$previous_from = $this->_params[RS_CALENDAR.'_previous_from'];
			}
			if(isset($date_from)){
				$date_from = $this->_params[RS_CALENDAR.'_from_date'];
			}
			$period = $this->_params[RS_CALENDAR.'_period'];
			if(isset($last_period)){
				$last_period = $this->_params[RS_CALENDAR.'_period_last'];
			}
			if(isset($date_period)){
				$date_period = $this->_params[RS_CALENDAR.'_period_date'];
			}
			
			
			/////////////////////////////
			$day_length = 24 * 60 * 60;
			
			switch($from){
				case 'previous':
					$date->modify($previous_from.' '.$type);
					if($type == 'month'){
						$from_time = $date->modify($date->format('Y-m').'-01')->format('U');
					}else if($type == 'week'){
						$from_time = $date->format('U') - ($current_day_of_week * $day_length);
					}else{
						$from_time = $date->format('U');
					}
					break;
				case 'current':
					if($type == 'month'){
						$from_time = $date->modify($date->format('Y-m').'-01')->format('U');
					}else if($type == 'week'){
						//weekly
						$from_time = $date->format('U') - ($current_day_of_week * $day_length);
					}else{
						$from_time = $date->format('U');
					}
					break;
				case 'next':
					$date->modify('+1 '.$type);
					if($type == 'month'){
						$from_time = $date->modify($date->format('Y-m').'-01')->format('U');
					}else if($type == 'week'){
						$from_time = $date->format('U') - ($current_day_of_week * $day_length);
					}else{
						$from_time = $date->format('U');
					}
					break;
				case 'today':
					$from_time = $date->format('U');
					break;
				case 'date':
					
					$d = \DateTime::createFromFormat('Y-m-d', $date_from);
					
					if($d && $d->format('Y-m-d') == $date_from){
						$from_time = $date->modify($date_from)->format('U');
						break;
					}
				default:
					$from_time = $date->modify($date->format('Y-m').'-01')->format('U');
			}
			
			
			//
			
			if($period == 'last'){
				
				if(empty($last_period) && $last_period !== '0'){
					$date->modify('+2 '.$type);
				}else{
					// $last_period = 1;
					$date->modify('+'.$last_period.' '.$type);
				}
				
				if($type == 'month'){
					$until = $date->modify($date->format('Y-m').'-'.$date->format('t'))->format('U');
				}else if($type == 'week'){
					//weekly
					$cal_end_mergin = (6 - $current_day_of_week) * $day_length;
					$until = $date->format('U') + $cal_end_mergin;
				}else{
					$until = $date->format('U');
				}
			}else{
				
				$d = \DateTime::createFromFormat('Y-m-d', $date_period);
				
				if($d && $d->format('Y-m-d') == $date_period){
					$until = $date->modify($date_period)->format('U');
				}else{
					$until = $date->format('U');
				}
				//$until = $date->modify($this->get_option(RS_CALENDAR.'_period_date'))->format('U');
			}
			
			$this->_params = array_merge($this->_params, array(
				'from' => $from_time,
				'until' => $until,
				'start_of_week' => $start_of_week,
				'before_start' => $from_time,
				'after_end' => $until,
			));
			
			
		}
		
		
		return $this->_params;
	}
}

endif;

