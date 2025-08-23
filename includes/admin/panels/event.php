<?php
namespace sandmanjp\ReallySimpleCalendar\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\event')):
	
class event extends panel{
	
	public $name = 'event';
	
	public $fields = array(
		SMJP_RS_CALENDAR.'_event_number',
		SMJP_RS_CALENDAR.'_event_label',
		SMJP_RS_CALENDAR.'_event_repeat',
		SMJP_RS_CALENDAR.'_event_date',
		SMJP_RS_CALENDAR.'_event_last',
		SMJP_RS_CALENDAR.'_event_exclude',
		SMJP_RS_CALENDAR.'_event_class',
		SMJP_RS_CALENDAR.'_event_text_color',
		SMJP_RS_CALENDAR.'_event_bg_color',
	);
	public $lock_fields = array(
		SMJP_RS_CALENDAR.'_event_lock',
	);
	
	
	function get_label(){
		return esc_html__('Event Settings', 'really-simple-calendar');
	}
	
	function update($key, $post_data){
		
		if(is_array($post_data)){
			
			if(!is_array($this->settings[$key])){
				$this->settings[$key] = array();
			}
			
			//for array like events.
			foreach($this->locked as $k=>$v){
				
				if(!empty($v)){
					foreach($v as $kk=>$vv){
						//if locked.
						if($vv && isset($this->settings[$key][$kk])){
							//変更しない
							$post_data[$kk] = $this->settings[$key][$kk];
						}
						//
					}
				}
				
			};
			
			foreach($post_data as $k=>$v){
				$this->settings[$key][$k] = $v; 
			}
			
			foreach($this->settings[$key] as $k=>$v){
				
				if(!isset($post_data[$k])){
					//var_dump($k);
					unset($this->settings[$key][$k]);
				}
			}
			//save array
			$this->updated[$key] = smjp_rsc_update_option($key, $this->settings[$key]);
			$this->updated[SMJP_RS_CALENDAR.'_event_lock'] = smjp_rsc_update_option(SMJP_RS_CALENDAR.'_event_lock', $this->settings[SMJP_RS_CALENDAR.'_event_lock']);
			
		}else{
			//for single value.
			if(!isset($this->locked[$key.'_lock']) || (isset($this->locked[$key.'_lock']) && !$this->locked[$key.'_lock'])){
				$this->settings[$key] = $post_data;
				$this->updated[$key] = smjp_rsc_update_option($key, $post_data);
			}
			
		}
		return $this->settings;
	}
	
	private function _get_list_navi($search_from, $search_to, $search_order){
		ob_start();
		?>
		<div class="tablenav top">
			<div class="alignright actions">
				
				<label for="rsc-search-from"><?php esc_html_e('Period', 'really-simple-calendar'); ?> : </label>
				<input type="date" id="rsc-search-from" name="search_from" value="<?php echo esc_attr($search_from); ?>">
				
				<span>~</span>
				
				<input type="date" name="search_to" value="<?php echo esc_attr($search_to); ?>">
				<label for="rsc-search-order"><?php esc_html_e('Order', 'really-simple-calendar'); ?> : </label>
				
				<select id="rsc-search-order" name="search_order">
					<option value="" <?php selected(wp_unslash($search_order), ''); ?>><?php esc_html_e('Saving', 'really-simple-calendar'); ?></option>
					<option value="asc" <?php selected(wp_unslash($search_order), 'asc'); ?>><?php esc_html_e('Ascending', 'really-simple-calendar'); ?></option>
					<option value="desc" <?php selected(wp_unslash($search_order), 'desc'); ?>><?php esc_html_e('Descending', 'really-simple-calendar'); ?></option>
				</select>
				<input type="submit" name="filter_action" class="button" value="<?php esc_html_e('Filter', 'really-simple-calendar'); ?>">
			</div>
			<br class="clear">
		</div>
		<?php 
		$event_serch = ob_get_clean();
		$event_search = apply_filters('smjp_rsc_get_event_serch', $event_serch);
		return $event_search;
		
	}
	
	function _set_event_item_template(){
		
		$num = array('x' => '1');
		$is_event_locked = array('x' => '0');
		$labels = array('x' => '');
		$classes = array('x' => '');
		$dates = array('x' => '');
		$lasts = array('x' => '');
		$repeats = array('x' => array());
		$excludes = array('x' => array());
		$text_colors = array('x' => '#ffffff');
		$bg_colors = array('x' => '#999999');
		
		$n = 'x';
		$i = 'x';
		
		ob_start();
		include SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/part-event.php';
		$template = ob_get_clean();
		
		// wp_register_script(SMJP_RSC_.'admin-event');
		
		wp_add_inline_script(SMJP_RSC_.'admin-event', $template, 'after');
		
		add_filter('script_loader_tag', array($this, 'replace_script_attributes'));
	}
	
	function replace_script_attributes($tag){
		
		$tag = str_replace(
			'id="smjp_rsc_admin-event-js-after"', 
			'id="event-column-template" type="text/html"', 
			$tag
		);
		
		return $tag;
	}
	
	function echo($settings){
		$this->_set_event_item_template();
		
		$lock_mode = 0;
		$class = $this->name;
		
		$now = time();
		
		$default_zero = array($now => '0');
		$default_empty = array($now => '');
		
		$num = !empty($settings[SMJP_RS_CALENDAR.'_event_number']) ? $settings[SMJP_RS_CALENDAR.'_event_number'] : $default_zero;
		$is_event_locked = !empty($settings[SMJP_RS_CALENDAR.'_event_lock']) ? $settings[SMJP_RS_CALENDAR.'_event_lock'] : $default_zero;
		
		$labels = !empty($settings[SMJP_RS_CALENDAR.'_event_label']) ? $settings[SMJP_RS_CALENDAR.'_event_label'] : $default_empty;
		$classes = !empty($settings[SMJP_RS_CALENDAR.'_event_class']) ? $settings[SMJP_RS_CALENDAR.'_event_class'] : $default_empty;
		$dates = !empty($settings[SMJP_RS_CALENDAR.'_event_date']) ? $settings[SMJP_RS_CALENDAR.'_event_date'] : $default_empty;
		$lasts = !empty($settings[SMJP_RS_CALENDAR.'_event_last']) ? $settings[SMJP_RS_CALENDAR.'_event_last'] : $default_empty;
		$repeats = !empty($settings[SMJP_RS_CALENDAR.'_event_repeat']) ? $settings[SMJP_RS_CALENDAR.'_event_repeat'] : array($now => array());
		$excludes = !empty($settings[SMJP_RS_CALENDAR.'_event_exclude']) ? $settings[SMJP_RS_CALENDAR.'_event_exclude'] : array($now => array());
		$text_colors = !empty($settings[SMJP_RS_CALENDAR.'_event_text_color']) ? $settings[SMJP_RS_CALENDAR.'_event_text_color'] : array($now => '#ffffff');
		$bg_colors = !empty($settings[SMJP_RS_CALENDAR.'_event_bg_color']) ? $settings[SMJP_RS_CALENDAR.'_event_bg_color'] : array($now => '#999999');
		
		//
		ob_start();
		?>
			var rsc_event_exclude_list = [];
			<?php 
			foreach($num as $k=>$n):
				if(isset($excludes[$k])):
			?>
			rsc_event_exclude_list[<?php echo esc_attr($k); ?>] = <?php echo wp_json_encode($excludes[$k]);?>;
			<?php 
				endif;
			endforeach; 
			?>
		<?php
		$event_js = ob_get_clean();
		wp_add_inline_script(SMJP_RSC_.'admin', $event_js, 'after');
		
		$search_from = empty($_POST['search_from']) ? '' : sanitize_text_field(wp_unslash($_POST['search_from']));
		if(!preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $search_from)){
			//not date
			$search_from = '';
		}
		
		$search_to = empty($_POST['search_to']) ? '' : sanitize_text_field(wp_unslash($_POST['search_to']));
		if(!preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $search_to)){
			//not date
			$search_to = '';
		}
		
		$search_order = empty($_POST['search_order']) ? '' : sanitize_text_field(wp_unslash($_POST['search_order']));
		
		$allowed = wp_kses_allowed_html('post');
		$allowed = array_merge($allowed, array(
			'input' => array(
				'type' => true,
				'name' => true,
				'value' => true,
				'class' => true,
				'id' => true,
				'disabled' => true,
				'readonly' => true,
				'checked' => true,
			),
			'select' => array(
				'name' => true,
				'value' => true,
				'class' => true,
				'id' => true,
				'disabled' => true,
				'readonly' => true,
			),
			'option' => array(
				'value' => true,
				'disabled' => true,
				'readonly' => true,
				'selected' => true,
			),
			'label' => array(
				'id' => true,
				'for' => true,
				'class' => true,
			),
		));
		
		//top navigation
		$list_navi = $this->_get_list_navi($search_from, $search_to, $search_order);
		echo wp_kses($list_navi, $allowed); //remove script and style tags
		?>
<div class="rsc-table-list-wrapper">
	<table class="form-table rsc-table rsc-table-list widefat striped">
		<thead>
			<tr>
				<td colspan="4" class="">
						
				</td>
			</tr>
		</thead>
		<tbody class="rsc-table-body">
			<?php
			if(!empty($_POST['search_order'])){
				$search_order = sanitize_text_field(wp_unslash($_POST['search_order']));
				//sort by order
				if($search_order == 'asc'){
					asort($dates);
				}else if($search_order == 'desc'){
					arsort($dates);
				}
			}
			$num = array();
			foreach($dates as $k=>$v){
				$num[$k] = 0;
				if(empty($search_from) && empty($search_to)){
					$num[$k] = 1;
				}else if(empty($search_to) && !empty($search_from) && $v >= $search_from){
					$num[$k] = 1;
				}else if(empty($search_from) && !empty($search_to) && $v <= $search_to){
					$num[$k] = 1;
				}else if($v >= $search_from && $v <= $search_to){
					$num[$k] = 1;
				}
			}
			
			// }
			
			$i = 0;
			
			foreach($num as $n=>$v){
				include SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/part-event.php';
				$i++;
			};
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="4">
				<button class="rsc-add-button button"><span class="dashicons dashicons-plus-alt2"></span> <?php esc_html_e('Add Event', 'really-simple-calendar'); ?></button>
				</td>
			</tr>
		</tfoot>
	</table>
</div>
	
<?php
	}
}

endif;