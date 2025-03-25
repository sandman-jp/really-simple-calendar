<?php

namespace RSC\Calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('RSC\Calendar\calender')):

require_once RSC_DIR_INCLUDES.'/calendar/setting.php';
require_once RSC_DIR_INCLUDES.'/calendar/param.php';
require_once RSC_DIR_INCLUDES.'/calendar/event.php';
require_once RSC_DIR_INCLUDES.'/calendar/style.php';

class calendar {
	
	private $_params;
	public $has_style = false;
	
	function __construct(){
		add_filter('rsc_get_month_th_value', array($this, 'get_month_th_value'), 10, 2);
		
		add_filter('rsc_get_week_th_value', array($this, 'get_th_value'), 10, 2);
		add_filter('rsc_get_day_th_value', array($this, 'get_th_value'), 10, 2);
		
		add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 11);
		
	}
	
	function enqueue_scripts() {
		wp_enqueue_style('rsc-css', RSC_ASSETS_URL.'/rsc.css', array(), RSC_VIRSION);
		$style = get_option(RS_CALENDAR.'_style');
		if(empty($style)){
			$style = rsc_get_default_style();
		}
		wp_add_inline_style('rsc-css', $style);
		wp_enqueue_script('rsc-js', RSC_ASSETS_URL.'/rsc.js', array('jquery', 'jquery-ui-sortable'), RSC_VIRSION, true);
	}
	
	function get_params($args=array()){
		$param = new param();
		return $param->get_params($args);
	}
	
	function set_events($args){
		$event = new event();
		$event->set_events($args);
	}
	
	function set_style($args){
		$args['has_style'] = $this->has_style;
		
		$event = new style();
		$event->set_style($args);
	}
	
	
	function get_month_th_value($th='', $day_index=0){
		global $wp_locale;
		
		$th = $wp_locale->get_weekday_abbrev($wp_locale->get_weekday($day_index));
		
		return $th;
	}
	
	function get_th_value($th='', $time_id=null){
		
		if(!empty($time_id)){
			$th = wp_date('m/d', $time_id);
			$th .= ' <span>('.mb_substr(wp_date('D', $time_id), 0, 1).')</span>';
		}
		
		return $th;
	}
	
	//render calendar
	function render($args){
		$this->set_events($args);
		$this->set_style($args);
		
		$args = $this->get_params($args);
		$args = apply_filters('rsc_before_render_calendar_tables', $args);
		
		//カレンダーの取得
		$table_func = '_get_'.$args[RS_CALENDAR.'_type'].'_tables';
		
		$calendar_tables = $this->{$table_func}($args);
		
		$calendar_tables = apply_filters('rsc_after_render_calendar_tables', $calendar_tables);
		
		return $calendar_tables;
	}
	
	private function _get_empty_cells($column_num=7){
		$td = array();
		for($i=0; $i<$column_num; $i++){
			$td[] = '<td class="rsc-cell"></td>';
		}
		return $td;
	}
	
	//for daily tables
	private function _get_day_tables($args){
		global $wp_locale;
		
		//1日の長さ
		$day_length = 24 * 60 * 60;
		$table_num = 0;
		$column_num = ($args['until'] - $args['from']) / $day_length;
		$i = 0;
		$tableheader = '';
		
		//開始
		$time_id = $args['before_start'];
		
		$current_m = (int)wp_date('n', $args['from']);
		$current_ym = (int)wp_date('Ym', $args['from']);
		
		
		
		$cells = array();
		$month_cells = array();
		
		//一列目にCFの項目だけ表示するthがある場合
		$column_header = '';
		
		ob_start();
		while($time_id <= $args['after_end']):
			
			//cell number in col
			$dw = rsc_get_start_week($time_id, $args['start_of_week']);
			
			//曜日クラス
			$weekday = 'day';
			
			if(wp_date('w', $time_id) == 6){
				$weekday = 'sat';
			}else if(wp_date('w', $time_id) == 0){
				$weekday = 'sun';
			}
			
			//表示期間内かどうか
			$is_in_term = ($time_id >= $args['from'] && $time_id <= $args['until']);
			
			$td_classes = array('rsc-cell', 'week-'.$dw, 'rsc-'.$weekday, 'rsc-'.$time_id, 'rsc-'.wp_date('Ymd', $time_id));
			
			$today = new \DateTime('today', wp_timezone());
			if($time_id == $today->format('U')){
				$td_classes[] = 'rsc-today';
			}
			
			
			$values = array();
			
			if(!empty($values)){
				$have_data = false;
				foreach($values as $k=>$v){
					//どんなデータでもあれば
					if(!empty($v)){
						$have_data = true;
						break;
					}
				}
				if($have_data){
					$td_classes[] = 'have-data';
				}
			}
			
			//start table cell including
			$cell_args = array(
				'is_in_term' => $is_in_term,
				'time_id' => $time_id,
				'td_classes' => $td_classes,
				'values' => $values,
			);
			
			if($args[RS_CALENDAR.'_type'] == 'week' || $args[RS_CALENDAR.'_type'] == 'month'){
				$cell_args['start_of_week'] = $args['start_of_week'];
			}
			
			//日タイプ用ヘッダー
			ob_start(); //for td
			$th = wp_date('D', $time_id);
			
			rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', 'header', array(
				'th' => $th,
				'time_id' => $time_id,
				'is_in_term' => $is_in_term,
				'th_class' => array_merge($td_classes, array('rsc-'.$weekday)),
			));
			$tableheader .= ob_get_clean();
			
			//各セル
			ob_start();
			rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', 'cell', $cell_args);
			
			$cell = ob_get_clean();
			
			$tablename = 'table';
			if($args['align'] == 1){
				$cells[] = $tableheader.$cell;
				$tableheader = '';
				$tablename = $tablename.'-vertical';
			}else{
				$cells[] = $cell;
			}
			//1日進める
			$time_id += $day_length;
			
			if($time_id > $args['after_end']){
				
				$table_class = 'alignwide';
				if($args['align'] == 1){
					$cal_cells = implode('</tr><tr>', $cells);
				}else{
					$cal_cells = implode('', $cells);
				}
				
				rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/'.$tablename, array(
					'th' => $tableheader,
					'td' => $cal_cells,
					'monthname' => $wp_locale->get_month($current_m),
					'time_id' => $time_id,
					'table_class' => $table_class,
				));
				$cells = array();
				$month_cells = array();
				$tableheader = '';
			}
			
			$i++;
			
		endwhile;
		
		return ob_get_clean();
	}
	
	//for weekly tables
	private function _get_week_tables($args){
		global $wp_locale;
		
		
		//1日の長さ
		$day_length = 24 * 60 * 60;
		$table_num = 0;
		$column_num = 7;
		$i = 0;
		$tableheader = '';
		
		//カレンダーの（範囲外）開始日
		$args = array_merge($args, array(
			'min_day_of_week' => rsc_get_start_week($args['from'], $args['start_of_week']), //カレンダーを開始する日の曜日
			'max_day_of_week' => rsc_get_start_week($args['until'], $args['start_of_week']), //カレンダーを終了する日の曜日
		));
		
		$cal_start = $args['from'];
		
		$args['before_start'] = $cal_start - ($args['min_day_of_week'] * $day_length); 
		
		//カレンダーの（範囲外）終了日
		$cal_end = $args['until'];
		
		if($args['max_day_of_week'] < 6){
			$args['after_end'] = $cal_end + ($day_length * (6 - $args['max_day_of_week']));
		}else{
			$args['after_end'] = $cal_end;
		}
		
		//開始
		$time_id = $args['before_start'];
		
		$is_new_month = true;
		$current_m = (int)wp_date('n', $args['from']);
		$current_ym = (int)wp_date('Ym', $args['from']);
		
		
		$cells = $this->_get_empty_cells($column_num);
		
		$month_cells = array();
		
		//一列目にCFの項目だけ表示するthがある場合
		$column_header = '';
		
		ob_start();
		while($time_id <= $args['after_end']):
			
			//cell number in col
			$dw = rsc_get_start_week($time_id, $args['start_of_week']);
			
			//曜日クラス
			$weekday = 'day';
			
			if(wp_date('w', $time_id) == 6){
				$weekday = 'sat';
			}else if(wp_date('w', $time_id) == 0){
				$weekday = 'sun';
			}
			
			//表示期間内かどうか
			$is_in_term = ($time_id >= $args['from'] && $time_id <= $args['until']);
			
			$td_classes = array('rsc-cell', 'week-'.$dw, 'rsc-'.$weekday, 'rsc-'.$time_id, 'rsc-'.wp_date('Ymd', $time_id));
			
			$today = new \DateTime('today', wp_timezone());
			if($time_id == $today->format('U')){
				$td_classes[] = 'rsc-today';
			}
			
			
			$values = array();
			
			if(!empty($values)){
				$have_data = false;
				foreach($values as $k=>$v){
					//どんなデータでもあれば
					if(!empty($v)){
						$have_data = true;
						break;
					}
				}
				if($have_data){
					$td_classes[] = 'have-data';
				}
			}
			
			//start table cell including
			$cell_args = array(
				'is_in_term' => $is_in_term,
				'time_id' => $time_id,
				'td_classes' => $td_classes,
				'values' => $values,
			);
			
			$cell_args['start_of_week'] = $args['start_of_week'];
			
			ob_start();
			rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', 'cell', $cell_args);
			
			$cells[$dw] = ob_get_clean();
			
			
			//日・週タイプ用ヘッダー
			ob_start(); //for td
			$th = wp_date('D', $time_id);
			
			rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', 'header', array(
				'th' => $th,
				'time_id' => $time_id,
				'is_in_term' => $is_in_term,
				'th_class' => array_merge($td_classes, array('rsc-'.$weekday)),
			));
			$tableheader .= ob_get_clean();
			
			
			//1日進める
			$time_id += $day_length;
		
			//週が変わったら出力
			if($dw ==  6) {
				$table_class = 'alignwide';
				$month_cells[] = $column_header.implode('', $cells);
				rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', array(
					'th' => $tableheader,
					'td' => $month_cells,
					'monthname' => $wp_locale->get_month($current_m),
					'time_id' => $time_id,
					'table_class' => $table_class,
				));
				
				$cells = $this->_get_empty_cells();
				$current_m = (int)wp_date('n', $time_id);
				$current_ym = (int)wp_date('Ym', $time_id);
				$month_cells = array();
				$tableheader = '';
				
			}
			
			$i++;
			
		endwhile;
		
		return ob_get_clean();
	}
	
	//for monthly tables
	private function _get_month_tables($args){
		global $wp_locale;
		
		//1日の長さ
		$day_length = 24 * 60 * 60;
		$table_num = 0;
		$column_num = 7;
		$i = 0;
		$tableheader = '';
		//月タイプ用
		
		$args = array_merge($args, array(
			'min_day_of_week' => rsc_get_start_week($args['from'], $args['start_of_week']), //カレンダーを開始する日の曜日
			'max_day_of_week' => rsc_get_start_week($args['until'], $args['start_of_week']), //カレンダーを終了する日の曜日
		));
		
		$cal_start = $args['from'];
		$date = new \DateTime(wp_date('Y-m-01', $cal_start), wp_timezone());
		$cal_start = $date->format('U');
	
		//カレンダーの（範囲外）終了日
		$cal_end = $args['until'];
		$date = new \DateTime(wp_date('Y-m-t', $cal_end), wp_timezone());
		$cal_end = $date->format('U');
		
		if($args['max_day_of_week'] < 6){
			$args['after_end'] = $cal_end + ($day_length * (6 - $args['max_day_of_week']));
		}else{
			$args['after_end'] = $cal_end;
		}
		
		//開始
		$time_id = $args['before_start'];
		
		ob_start();
		
		for($i=0; $i<$column_num; $i++){
		
			$day_index = ($i + $args['start_of_week']) % 7;
			
			$weekday = 'day';
			
			if($day_index == 6){
				$weekday = 'sat';
			}else if($day_index == 0){
				$weekday = 'sun';
			}
			
			rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', 'header', array(
				'day_index' => $day_index,
				'th_class' => array('rsc-'.$weekday),
			));
		
		}; 
		
		$tableheader = ob_get_clean();
		
		$is_new_month = true;
		$current_m = (int)wp_date('n', $args['from']);
		$current_ym = (int)wp_date('Ym', $args['from']);
		
		$cells = $this->_get_empty_cells($column_num);
		
		$month_cells = array();
		
		//一列目にCFの項目だけ表示するthがある場合
		$column_header = '';
		
		ob_start();
		while($time_id <= $args['after_end']):
			
			//cell number in col
			$dw = rsc_get_start_week($time_id, $args['start_of_week']);
			
			//曜日クラス
			$weekday = 'day';
			
			if(wp_date('w', $time_id) == 6){
				$weekday = 'sat';
			}else if(wp_date('w', $time_id) == 0){
				$weekday = 'sun';
			}
			
			//表示期間内かどうか
			$is_in_term = ($time_id >= $args['from'] && $time_id <= $args['until']);
			
			
			$td_classes = array('rsc-cell', 'week-'.$dw, 'rsc-'.$weekday, 'rsc-'.$time_id, 'rsc-'.wp_date('Ymd', $time_id));
			
			$today = new \DateTime('today', wp_timezone());
			if($time_id == $today->format('U')){
				$td_classes[] = 'rsc-today';
			}
			
			
			$values = array();
			
			if(!empty($values)){
				$have_data = false;
				foreach($values as $k=>$v){
					//どんなデータでもあれば
					if(!empty($v)){
						$have_data = true;
						break;
					}
				}
				if($have_data){
					$td_classes[] = 'have-data';
				}
			}
			
			//start table cell including
			$cell_args = array(
				'is_in_term' => $is_in_term,
				'time_id' => $time_id,
				'td_classes' => $td_classes,
				'values' => $values,
			);
			
			$cell_args['start_of_week'] = $args['start_of_week'];
			
			ob_start();
			rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', 'cell', $cell_args);
			
			$cells[$dw] = ob_get_clean();
			// 
			
			//1日進める
			$time_id += $day_length;
			
			$week_cells = implode('', $cells);
			$data = trim(strip_tags($week_cells));
			
			//月が変わったら出力
			if(wp_date('Ym', $time_id) > $current_ym){
				
				$table_class = 'alignwide';
				if(!empty($data)){
					$month_cells[] = $column_header.$week_cells;
				}
				
				rsc_get_template_part('/'.$args[RS_CALENDAR.'_type'].'/table', array(
					'th' => $tableheader,
					'td' => $month_cells,
					'monthname' => $wp_locale->get_month($current_m),
					'time_id' => $time_id,
					'table_class' => $table_class,
				));
				
				$cells = $this->_get_empty_cells();
				$current_m = (int)wp_date('n', $time_id);
				$current_ym = (int)wp_date('Ym', $time_id);
				$month_cells = array();
		
			}else if($dw == 6) {
				//週の終わり
				if(!empty($data)){
					$month_cells[] = $column_header.$week_cells;
				}
				$cells = $this->_get_empty_cells();
			};
			
			
			$i++;
			
		endwhile;
		
		return ob_get_clean();
	}
	
}

endif;
