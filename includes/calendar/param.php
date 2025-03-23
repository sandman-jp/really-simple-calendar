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
		
		return $this->_get_view_settings();
		
	}
	
	private function _get_view_settings(){
		
		if(!$this->_params){
			$date = new \DateTime('today', wp_timezone());
			$type = $this->get_option(RS_CALENDAR.'_type');
			if(empty($type)){
				$type = 'month';
			}
			
			$from = $this->get_option(RS_CALENDAR.'_from');
			if(empty($from)){
				$from = 'current';
			}
			
			$start_of_week = $this->get_option(RS_CALENDAR.'_start_of_week');
			if($start_of_week == 'today'){
				$start_of_week = wp_date('w');
			}else if(empty($start_of_week)){
				$start_of_week = 0;
			}
			
			if($type == 'week'){
				$current_day_of_week = rsc_get_start_week($date->format('U'), $start_of_week);
			}
			
			$day_length = 24 * 60 * 60;
			
			switch($from){
				case 'previous':
					$previous_from = $this->get_option(RS_CALENDAR.'_previous_from');
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
					$date_from = $this->get_option(RS_CALENDAR.'_from_date');
					$d = \DateTime::createFromFormat('Y-m-d', $date_from);
					
					if($d && $d->format('Y-m-d') == $date_from){
						$from_time = $date->modify($date_from)->format('U');
						break;
					}
				default:
					$from_time = $date->modify($date->format('Y-m').'-01')->format('U');
			}
			
			
			//
			$period = $this->get_option(RS_CALENDAR.'_period');
			if(empty($period)){
				$period = 'last';
			}
			
			if($period == 'last'){
				$last_period = $this->get_option(RS_CALENDAR.'_period_last');
				
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
				$date_period = $this->get_option(RS_CALENDAR.'_period_date');
				$d = \DateTime::createFromFormat('Y-m-d', $date_period);
				
				if($d && $d->format('Y-m-d') == $date_period){
					$until = $date->modify($date_period)->format('U');
				}else{
					$until = $date->format('U');
				}
				//$until = $date->modify($this->get_option(RS_CALENDAR.'_period_date'))->format('U');
			}
			
			$this->_params = array(
				'from' => $from_time,
				'until' => $until,
				'start_of_week' => $start_of_week,
				'before_start' => $from_time,
				'after_end' => $until,
				
				RS_CALENDAR.'_type' => $type,
				RS_CALENDAR.'_from' => $from,
				RS_CALENDAR.'_period' => $period,
				RS_CALENDAR.'_start_of_week' => $start_of_week,
				RS_CALENDAR.'_align' => 0,
			);
			
			if($type == 'day'){
				$this->_params['align'] = $this->get_option(RS_CALENDAR.'_align');
			}
		}
		
		
		//param for admin or front
		$params = apply_filters('rsc_get_calendar_params', $this->_params);
		
		return $params;
	}
}

endif;

