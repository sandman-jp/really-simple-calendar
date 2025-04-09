<?php

namespace RSC\calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('RSC\Calendar\view')):

class view extends setting {
	
	private $_params = false;
	
	public $options;
	
	function get_view($args){
		
		if(!empty($args)){
			foreach($args as $k=>$v){
				$this->options[$k] = $v;
			}
		}
		$params = $this->_get_view_params();
		
		//param for admin or front
		$params = apply_filters('rsc_get_calendar_view', $params);
		
		return $params;
		
	}
	
	private function _get_view_params(){
		
		// if(empty($this->_params)){
		$this->_params = array();
		
		$date = new \DateTime('today', wp_timezone());
		
		
		$fields = array(
			'type',
			'from',
			'start_of_week',
			'align',
			'previous_from',
			'from_date',
			'period',
			'period_last',
			'period_date',
		);
		
		foreach($fields as $fld){
			$val = $this->get_option(RS_CALENDAR.'_'.$fld);
			if($fld == 'type' && empty($val)){
				$val = 'month';
			}else if($fld == 'from' && empty($val)){
				$val = 'current';
			}else if($fld == 'period' && empty($val)){
				$val = 'last';
			}
			$this->_params[RS_CALENDAR.'_'.$fld] = $val;
		}
		
		$type = $this->_params[RS_CALENDAR.'_type'];
		$from = $this->_params[RS_CALENDAR.'_from'];
		
		$start_of_week = $this->_params[RS_CALENDAR.'_start_of_week'];
		if($start_of_week == 'today'){
			$start_of_week = wp_date('w');
		}else if(empty($start_of_week)){
			$start_of_week = 0;
		}
		
		if($type == 'week'){
			$current_day_of_week = rsc_get_start_week($date->format('U'), $start_of_week);
		}
		
		
		$this->_params['align'] = $this->_params[RS_CALENDAR.'_align'];
		
		$previous_from = $this->_params[RS_CALENDAR.'_previous_from'];
		$date_from = $this->_params[RS_CALENDAR.'_from_date'];
		
		$period = $this->_params[RS_CALENDAR.'_period'];
		$last_period = $this->_params[RS_CALENDAR.'_period_last'];
		$date_period = $this->_params[RS_CALENDAR.'_period_date'];
		
		
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
		
		
		// }
		
		
		return $this->_params;
	}
}

endif;

