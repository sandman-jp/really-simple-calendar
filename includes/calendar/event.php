<?php

namespace RSC\calendar;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if(!class_exists('RSC\Calendar\event')):

class event extends setting{
	public $options;
	
	private $_nums;
	private $_labels;
	private $_classes;
	private $_dates;
	private $_lasts;
	private $_repeats;
	private $_excludes;
	private $_text_colors;
	private $_bg_colors;
	
	private $_days = array();
	private $_weeks = array();
	
	function set_events($args){
		global $wp_locale;
		
		foreach($args as $k=>$v){
			$this->options[$k] = $v;
		}
		
		
		$this->_weeks = array_fill(0, 7, array());
		
		if(empty($this->_nums)){
			$this->_nums = $this->get_option(RS_CALENDAR.'_event_number');
			if(!$this->_nums){
				return;
			}
		}
		
		if(empty($this->_dates)){
				$this->_dates = $this->get_option(RS_CALENDAR.'_event_date');
		}
		if(empty($this->_lasts)){
			$this->_lasts = $this->get_option(RS_CALENDAR.'_event_last');
		}
		if(empty($this->_repeats)){
			$this->_repeats = $this->get_option(RS_CALENDAR.'_event_repeat');
		}
		if(empty($this->_excludes)){
			$this->_excludes = $this->get_option(RS_CALENDAR.'_event_exclude');
		}
		
		
		foreach($this->_nums as $k=>$n){
			
			$current = wp_date('Y-m-d', $k);
			
			$date = $this->_dates[$k];
			$last = $this->_lasts[$k];
			$repeat = $this->_repeats[$k];
			
			//最初の値はほぼ必ず空なので、後々用に削っとく
			if(isset($repeat[0]) && $repeat[0] === ''){
				unset($this->_repeats[$k][0]);
				unset($repeat[0]);
			}
			
			if(empty($repeat) && !empty($date)){
				
				if(!empty($last)){
					//曜日指定がなく、期間があるのは全曜日にフックする
					for($i=0; $i<7; $i++){
						$dw_name = strtolower(jddayofweek($i-1, 2)); //ジュリアン日は月曜日から始まるので
						$this->_weeks[$i][] = $k;
						add_filter('rsc_get_td_value_'.$dw_name, array($this, 'add_event'), 10, 2);
					}
				}else{
					//single event
					$dt = new \DateTime($date, wp_timezone());
					$time_id = $dt->format('U');
					$this->_days[$time_id][] = $k;
					add_filter('rsc_get_td_value_'.$time_id, array($this, 'add_event'), 10, 2);
				}
			}
			
			
			//くり返し
			for($i=0; $i<7; $i++){
				
				if(is_array($repeat) && in_array($i, $repeat)){
					
					$dw_name = strtolower(jddayofweek($i-1, 2)); //ジュリアン日は月曜日から始まるので
					
					$this->_weeks[$i][] = $k;
					add_filter('rsc_get_td_value_'.$dw_name, array($this, 'add_event'), 10, 2);
				}
			}
			
		};
	}
	
	function add_event($html, $time_id){
		return $this->_add_single_event($html, $time_id);
	}
	
	private function _create_event($events, $time_id){
		$html = '';
		
		foreach($events as $k):
			//リピート設定チェック
			if(!empty($this->_dates[$k]) && $this->_dates[$k] > wp_date('Y-m-d', $time_id)){
				//繰り返しの開始日
				continue;
			};
			
			if(!empty($this->_lasts[$k]) && $this->_lasts[$k] < wp_date('Y-m-d', $time_id)){
				//繰り返しの終了日
				continue;
			};
			
			if(!empty($this->_excludes[$k]) && in_array(wp_date('Y-m-d', $time_id), $this->_excludes[$k])){
				//繰り返しの除外終了日
				continue;
			};
			
			if(empty($this->_labels)){
				$this->_labels = $this->get_option(RS_CALENDAR.'_event_label');
			}
			$label = rsc_get_esc($this->_labels[$k]);
			if(empty($this->_classes)){
				$this->_classes = $this->get_option(RS_CALENDAR.'_event_class');
			}
			
			//クラスか色指定か
			$style = '';
			$class = '';
			if(empty($this->_classes[$k])){
				if(empty($_text_colors)){
					$this->_text_colors = $this->get_option(RS_CALENDAR.'_event_text_color');
				}
				$text_color = rsc_get_esc($this->_text_colors[$k]);
				
				if(empty($_bg_colors)){
					$this->_bg_colors = $this->get_option(RS_CALENDAR.'_event_bg_color');
				}
				$bg_color = rsc_get_esc($this->_bg_colors[$k]);
				
				$style = 'style="color:'.$text_color.'; background-color:'.$bg_color.';"';
			}else{
				$class = rsc_get_esc($this->_classes[$k]);
			}
			
			ob_start();
		?>
		<span class="rsc-calendar-event-wrapper rsc-calendar-event-<?php echo $k; ?>"><span class="rsc-calendar-event <?php echo $class; ?>" title="<?php echo $label; ?>" <?php echo $style; ?>><?php echo $label; ?></span></span>
		<?php
			$html .= ob_get_clean();
			
		endforeach;
		
		return $html;
	}
	
	private function _add_single_event($html, $time_id){
			
		$days = array();
		if(isset($this->_days[$time_id])){
			$days = $this->_days[$time_id];
		}
		
		$repeats = $this->_weeks[wp_date('w', $time_id)];
		
		
		$html = '';
		
		$html .= $this->_create_event($repeats, $time_id);
		$html .= $this->_create_event($days, $time_id);
			
		
		$html = apply_filters('rsc_add_evet', $html);
		
		return $html;
		
	}
}

endif;
