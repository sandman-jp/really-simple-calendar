<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\event')):
	
class event extends panel{
	
	public $name = 'event';
	public $label = 'Event Settings';
	
	public $fields = array(
		'calendar_event_number',
		'calendar_event_label',
		'calendar_event_name',
		'calendar_event_repeat',
		'calendar_event_date',
		'calendar_event_last',
		'calendar_event_class',
		'calendar_event_text_color',
		'calendar_event_bg_color',
	);
	public $lock_fields = array(
		'calendar_event_lock',
	);
	
	
	function echo($settings){
		$class = $this->name;
		
		$now = time();
		
		$default_zero = array($now => '0');
		$default_empty = array($now => '');
		
		$num = !empty($settings['calendar_event_number']) ? $settings['calendar_event_number'] : $default_zero;
		$is_event_locked = !empty($settings['calendar_event_lock']) ? $settings['calendar_event_lock'] : $default_zero;
		
		$labels = !empty($settings['calendar_event_label']) ? $settings['calendar_event_label'] : $default_empty;
		$classes = !empty($settings['calendar_event_class']) ? $settings['calendar_event_class'] : $default_empty;
		$dates = !empty($settings['calendar_event_date']) ? $settings['calendar_event_date'] : $default_empty;
		$lasts = !empty($settings['calendar_event_last']) ? $settings['calendar_event_last'] : $default_empty;
		$repeats = !empty($settings['calendar_event_repeat']) ? $settings['calendar_event_repeat'] : array($now => array());
		$text_colors = !empty($settings['calendar_event_text_color']) ? $settings['calendar_event_text_color'] : array($now => '#ffffff');
		$bg_colors = !empty($settings['calendar_event_bg_color']) ? $settings['calendar_event_bg_color'] : array($now => '#999999');
		
		$num['x'] = '0';
		$is_event_locked['x'] = '0';
		$labels['x'] = '';
		$classes['x'] = '';
		$dates['x'] = '';
		$lasts['x'] = '';
		$repeats['x'] = array();
		$text_colors['x'] = '#ffffff';
		$bg_colors['x'] = '#999999';
		
		?>
		
		
		<?php include RSC_ADMIN_DIR_INCLUDES.'/setting-panels/part-header.php'; ?>
	
	<!-- column template -->
	<script id="event-column-template" type="text/html">
		
		<?php
		$n = 'x';
		$i = 'x';
		include RSC_ADMIN_DIR_INCLUDES.'/setting-panels/part-event.php';
		?>
	</script>
	
	<table class="form-table rsc-table rsc-table-event widefat striped">
		<thead>
			<tr>
				<td colspan="9" class="rsc-event-acion">
						<div class="actions alignright">
							<?php
							if(!empty($_POST['search_ftom']) && preg_match('/^\d{4}\-\d{2}\-\d{2}$/', $_POST['search_ftom'])){
								$search_from = $_POST['search_ftom'];
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
							<label for="rsc-search-ftom"><?php _e('Term', RSC_TEXTDOMAIN); ?> : </label>
							<input type="date" id="rsc-search-ftom" name="search_ftom" value="<?php echo $search_from; ?>">
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
				</td>
			</tr>
		</thead>
		<tbody class="rsc-table-list">
			<?php 
			
			if(isset($_POST['filter_action'])){
				if(!empty($_POST['search_order'])){
					//sort by order
					if($_POST['search_order'] == 'asc'){
						asort($dates);
					}else if($_POST['search_order'] == 'desc'){
						arsort($dates);
					}
					$num = array();
					foreach($dates as $k=>$v){
						
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
				}
			}
			
			$i = 0;
			// var_dump($repeats);
			foreach($num as $n=>$v){
				include RSC_ADMIN_DIR_INCLUDES.'/setting-panels/part-event.php';
				$i++;
			};
			?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="9">
				<button class="rsc-add-button button"><?php _e('Add Event', RSC_TEXTDOMAIN); ?></button>
				</td>
			</tr>
		</tfoot>
	</table>
		
	
	<?php include RSC_ADMIN_DIR_INCLUDES.'/setting-panels/part-footer.php'; ?>
<?php
	}
}

endif;