<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\event')):
	
class event extends panel{
	
	public $name = 'event';
	
	public $fields = array(
		RS_CALENDAR.'_event_number',
		RS_CALENDAR.'_event_label',
		RS_CALENDAR.'_event_name',
		RS_CALENDAR.'_event_repeat',
		RS_CALENDAR.'_event_date',
		RS_CALENDAR.'_event_last',
		RS_CALENDAR.'_event_exclude',
		RS_CALENDAR.'_event_class',
		RS_CALENDAR.'_event_text_color',
		RS_CALENDAR.'_event_bg_color',
	);
	public $lock_fields = array(
		RS_CALENDAR.'_event_lock',
	);
	
	function get_label(){
		return __('Event Settings', RSC_TEXTDOMAIN);
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
							//unset($post_data[$kk]);
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
			update_option($key, $this->settings[$key]);
			update_option(RS_CALENDAR.'_event_lock', $this->settings[RS_CALENDAR.'_event_lock']);
			
		}else{
			//for single value.
			if(!isset($this->locked[$key.'_lock']) || (isset($this->locked[$key.'_lock']) && !$this->locked[$key.'_lock'])){
				$this->settings[$key] = $post_data;
				update_option($key, $post_data);
			}
			
		}
		return $this->settings;
	}
	
	function echo($settings){
		$class = $this->name;
		
		$now = time();
		
		$default_zero = array($now => '0');
		$default_empty = array($now => '');
		
		$num = !empty($settings[RS_CALENDAR.'_event_number']) ? $settings[RS_CALENDAR.'_event_number'] : $default_zero;
		$is_event_locked = !empty($settings[RS_CALENDAR.'_event_lock']) ? $settings[RS_CALENDAR.'_event_lock'] : $default_zero;
		
		$labels = !empty($settings[RS_CALENDAR.'_event_label']) ? $settings[RS_CALENDAR.'_event_label'] : $default_empty;
		$classes = !empty($settings[RS_CALENDAR.'_event_class']) ? $settings[RS_CALENDAR.'_event_class'] : $default_empty;
		$dates = !empty($settings[RS_CALENDAR.'_event_date']) ? $settings[RS_CALENDAR.'_event_date'] : $default_empty;
		$lasts = !empty($settings[RS_CALENDAR.'_event_last']) ? $settings[RS_CALENDAR.'_event_last'] : $default_empty;
		$repeats = !empty($settings[RS_CALENDAR.'_event_repeat']) ? $settings[RS_CALENDAR.'_event_repeat'] : array($now => array());
		$excludes = !empty($settings[RS_CALENDAR.'_event_exclude']) ? $settings[RS_CALENDAR.'_event_exclude'] : array($now => array());
		$text_colors = !empty($settings[RS_CALENDAR.'_event_text_color']) ? $settings[RS_CALENDAR.'_event_text_color'] : array($now => '#ffffff');
		$bg_colors = !empty($settings[RS_CALENDAR.'_event_bg_color']) ? $settings[RS_CALENDAR.'_event_bg_color'] : array($now => '#999999');
		
		$num['x'] = '1';
		$is_event_locked['x'] = '0';
		$labels['x'] = '';
		$classes['x'] = '';
		$dates['x'] = '';
		$lasts['x'] = '';
		$repeats['x'] = array();
		$excludes['x'] = array();
		$text_colors['x'] = '#ffffff';
		$bg_colors['x'] = '#999999';
		
		?>
		
		
		<?php include RSC_ADMIN_DIR_INCLUDES.'/panels/part-header.php'; ?>
	
	<!-- column template -->
	<script id="event-column-template" type="text/html">
		
		<?php
		$n = 'x';
		$i = 'x';
		include RSC_ADMIN_DIR_INCLUDES.'/panels/part-event.php';
		?>
	</script>
	<script>
		var rsc_event_exclude_list = [];
		<?php 
		foreach($num as $k=>$n):
			if(isset($excludes[$k])):
		?>
		rsc_event_exclude_list[<?php echo $k ?>] = <?php echo json_encode($excludes[$k]);?>;
		<?php 
			endif;
		endforeach; 
		?>
	</script>
	<p class="search-box">
		<label class="screen-reader-text" for="rsc-search-input">投稿を検索:</label>
		<input type="search" id="rsc-search-input" name="s" value="">
		<input type="submit" id="search-submit" class="button" value="投稿を検索">
	</p>
	
	<div class="tablenav top">
		<div class="alignleft actions">
			<?php
			
			if(!empty($_POST['search_from']) && preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $_POST['search_from'])){
				$search_from = $_POST['search_from'];
			}else{
				$search_from = '';
			}
			
			if(!empty($_POST['search_to']) && preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $_POST['search_to'])){
				$search_to = $_POST['search_to'];
			}else{
				$search_to = '';
			}
			$search_order = '';
			if(!empty($_POST['search_order'])){
				$search_order = $_POST['search_order'];
			}
			
			?>
			<label for="rsc-search-ftom"><?php _e('Period', RSC_TEXTDOMAIN); ?> : </label>
			<input type="date" id="rsc-search-ftom" name="search_from" value="<?php echo $search_from; ?>">
			<span>~</span>
			<input type="date" name="search_to" value="<?php echo $search_to; ?>">
			<label for="rsc-search-order"><?php _e('Order', RSC_TEXTDOMAIN); ?> : </label>
			<select id="rsc-search-order" name="search_order">
				<option value="" <?php selected($search_order, ''); ?>><?php _e('Saving', RSC_TEXTDOMAIN); ?></option>
				<option value="asc" <?php selected($search_order, 'asc'); ?>><?php _e('Ascending', RSC_TEXTDOMAIN); ?></option>
				<option value="desc" <?php selected($search_order, 'desc'); ?>><?php _e('Descending', RSC_TEXTDOMAIN); ?></option>
			</select>
			<input type="submit" name="filter_action" class="button" value="<?php _e('Filter'); ?>">
		</div>
		<br class="clear">
	</div>
	
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
				
				// if(isset($_POST['filter_action'])){
				if(!empty($_POST['search_order'])){
					//sort by order
					if($_POST['search_order'] == 'asc'){
						asort($dates);
					}else if($_POST['search_order'] == 'desc'){
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
				// var_dump($repeats);
				foreach($num as $n=>$v){
					include RSC_ADMIN_DIR_INCLUDES.'/panels/part-event.php';
					$i++;
				};
				?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4">
					<button class="rsc-add-button button"><span class="dashicons dashicons-plus-alt2"></span> <?php _e('Add Event', RSC_TEXTDOMAIN); ?></button>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<?php include RSC_ADMIN_DIR_INCLUDES.'/panels/part-footer.php'; ?>
<?php
	}
}

endif;