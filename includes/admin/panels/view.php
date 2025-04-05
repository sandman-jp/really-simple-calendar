<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\view')):
	
class view extends panel{
	
	public $name = 'view';
	
	public $fields = array(
		RS_CALENDAR.'_type', 
		RS_CALENDAR.'_from',
		RS_CALENDAR.'_from_date', 
		RS_CALENDAR.'_previous_from',
		RS_CALENDAR.'_period', 
		RS_CALENDAR.'_period_last', 
		RS_CALENDAR.'_period_date', 
		RS_CALENDAR.'_start_of_week',
		RS_CALENDAR.'_align',
		//
	);
	
	public $lock_fields = array(
		RS_CALENDAR.'_type_lock', 
		RS_CALENDAR.'_from_lock',
		RS_CALENDAR.'_period_lock',
		RS_CALENDAR.'_start_of_week_lock',
	);
	
	function get_label(){
		return __('View Settings', 'really-simple-calendar');
	}
	
	function echo($settings){
		$class = $this->name;
?>
	
	<?php do_action('rsc_before_view_settings'); ?>
	<table class="form-table rsc-table">
		
		<?php $is_locked_type = $settings[RS_CALENDAR.'_type_lock']; ?>
		<tr>
			<th scope="row">
				<?php rsc_param_lock(RS_CALENDAR.'_type_lock', $is_locked_type); ?>
				
				<label for="rsc-calendar-type"><?php esc_html_e('Calendar Type', 'really-simple-calendar'); ?></label>
			</th>
			<td>
				<?php $calendar_type = $settings[RS_CALENDAR.'_type']; ?>
				<select id="rsc-calendar-type" name="<?php echo RS_CALENDAR; ?>_type" <?php rsc_disabled($is_locked_type, true, RS_CALENDAR.'_type_lock'); ?>>
					<option value="month" <?php selected( $calendar_type, 'month'); ?>><?php esc_html_e('Monthly', 'really-simple-calendar') ?></option>
					<option value="week" <?php selected( $calendar_type, 'week'); ?>><?php esc_html_e('Weekly', 'really-simple-calendar') ?></option>
					<option value="day" <?php selected( $calendar_type, 'day'); ?>><?php esc_html_e('Daily', 'really-simple-calendar') ?></option>
				</select>
			</td>
		</tr>
		
		<?php $is_locked_from = $settings[RS_CALENDAR.'_from_lock']; ?>
		<tr>
			<th scope="row">
				<?php rsc_param_lock(RS_CALENDAR.'_from_lock', $is_locked_from); ?>
				
				<?php esc_html_e('Start From', 'really-simple-calendar'); ?>
			</th>
			<td>
				<?php $calendar_start = $settings[RS_CALENDAR.'_from']; 
				?>
				<fieldset>
					<p>
						<label>
							<input type="radio" name="<?php echo RS_CALENDAR; ?>_from" value="previous" <?php checked( $calendar_start, 'previous'); ?> <?php rsc_disabled($is_locked_from, true, RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Previous', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_previous_from = $settings[RS_CALENDAR.'_previous_from'];
							if($calendar_previous_from === false || $calendar_previous_from == ''){
								$calendar_previous_from = -1;
							}
							?>
							<input type="number" name="<?php echo RS_CALENDAR; ?>_previous_from" value="<?php echo $calendar_previous_from; ?>" class="small-text" max="-1" <?php rsc_disabled($is_locked_from, true, RS_CALENDAR.'_from_lock'); ?>>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>><?php echo _x('Month(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>><?php echo _x('Week(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>><?php echo _x('Day(s)', 'unit', 'really-simple-calendar'); ?></span>
						</label>
						
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo RS_CALENDAR; ?>_from" value="current" <?php checked( $calendar_start, 'current'); ?> <?php rsc_disabled($is_locked_from, true, RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Current', 'really-simple-calendar') ?>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>>(<?php echo __('Month', 'really-simple-calendar'); ?>)</span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>>(<?php echo __('Week', 'really-simple-calendar'); ?>)</span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>>(<?php echo __('Day', 'really-simple-calendar'); ?>)</span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo RS_CALENDAR; ?>_from" value="next" <?php checked( $calendar_start, 'next'); ?> <?php rsc_disabled($is_locked_from, true, RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Next', 'really-simple-calendar') ?>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>>(<?php echo __('Month', 'really-simple-calendar'); ?>)</span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>>(<?php echo __('Week', 'really-simple-calendar'); ?>)</span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>>(<?php echo __('Day', 'really-simple-calendar'); ?>)</span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo RS_CALENDAR; ?>_from" value="today" <?php checked( $calendar_start, 'today'); ?> <?php rsc_disabled($is_locked_from, true, RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Today', 'really-simple-calendar') ?>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo RS_CALENDAR; ?>_from" value="date" <?php checked( $calendar_start, 'date'); ?> <?php rsc_disabled($is_locked_from, true, RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Date', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php 
							$calendar_from_date = $settings[RS_CALENDAR.'_from_date'];
							if(empty($calendar_from_date)){
								$calendar_from_date = wp_date('Y-m-d');
							}
							?>
							<input type="date" name="<?php echo RS_CALENDAR; ?>_from_date" value="<?php echo $calendar_from_date; ?>" <?php rsc_disabled($is_locked_from, true, RS_CALENDAR.'_from_lock'); ?>>
						</label>
						
					</p>
				</fieldset>
			</td>
		</tr>
		
		<?php $is_locked_period = $settings[RS_CALENDAR.'_period_lock']; ?>
		<tr>
			<th scope="row">
				<?php rsc_param_lock(RS_CALENDAR.'_period_lock', $is_locked_period); ?>
				
				<?php esc_html_e('Display Period', 'really-simple-calendar'); ?>
			</th>
			<td>
				<fieldset>
				<p>
						<?php $calendar_period = $settings[RS_CALENDAR.'_period']; ?>
						<label>
							<input type="radio" name="<?php echo RS_CALENDAR; ?>_period" value="last" <?php checked( $calendar_period, 'last'); ?> <?php rsc_disabled($is_locked_period, true, RS_CALENDAR.'_period_lock'); ?>><?php esc_html_e('Period', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_period_last = $settings[RS_CALENDAR.'_period_last'];
							if($calendar_period_last === false || $calendar_period_last == ''){
								$calendar_period_last = 0;
							}
							?>
							<input type="number" id="calendar_period_last" name="<?php echo RS_CALENDAR; ?>_period_last" value="<?php echo $calendar_period_last; ?>" class="small-text" min="0" <?php rsc_disabled($is_locked_period, true, RS_CALENDAR.'_period_lock'); ?>>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>><?php echo _x('Month(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>><?php echo _x('Week(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>><?php echo _x('Day(s)', 'unit', 'really-simple-calendar'); ?></span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo RS_CALENDAR; ?>_period" value="date" <?php checked( $calendar_period, 'date'); ?> <?php rsc_disabled($is_locked_period, true, RS_CALENDAR.'_period_lock'); ?>><?php esc_html_e('Date', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_period_date = $settings[RS_CALENDAR.'_period_date']; 
							if(empty($calendar_period_date)){
								$calendar_period_date = wp_date('Y-m-d');
							}
							?>
							<input type="date" name="<?php echo RS_CALENDAR; ?>_period_date" value="<?php echo $calendar_period_date; ?>" <?php rsc_disabled($is_locked_period, true, RS_CALENDAR.'_period_lock'); ?>>
						</label>
					</p>
				</fieldset>
			</td>
		</tr>
		
		<!-- monthly or weekly -->
		<?php $is_locked_start_of_week = $settings[RS_CALENDAR.'_start_of_week_lock']; ?>
		<tr class="rsc-period-month rsc-period-week rsc-period-unit">
			<th scope="row">
				<?php rsc_param_lock(RS_CALENDAR.'_start_of_week_lock', $is_locked_start_of_week); ?>
				
				<label for="calendar-start-of-week"><?php esc_html_e('Start day of week', 'really-simple-calendar'); ?></label>
			</th>
			<td>
				<?php $calendar_start_of_week = $settings[RS_CALENDAR.'_start_of_week']; ?>
				<select name="<?php echo RS_CALENDAR; ?>_start_of_week" id="calendar-start-of-week" <?php rsc_disabled($is_locked_start_of_week, true, RS_CALENDAR.'_start_of_week_lock'); ?>>
					<option value="today" <?php selected( $calendar_start_of_week, 'today'); ?>><?php _e('Today', 'really-simple-calendar'); ?></option>
					<option value="0" <?php selected( $calendar_start_of_week, '0'); ?>><?php _e('Sunday'); ?></option>
					<option value="1" <?php selected( $calendar_start_of_week, '1'); ?>><?php _e('Monday'); ?></option>
					<option value="2" <?php selected( $calendar_start_of_week, '2'); ?>><?php _e('Tuesday'); ?></option>
					<option value="3" <?php selected( $calendar_start_of_week, '3'); ?>><?php _e('Wednesday'); ?></option>
					<option value="4" <?php selected( $calendar_start_of_week, '4'); ?>><?php _e('Thursday'); ?></option>
					<option value="5" <?php selected( $calendar_start_of_week, '5'); ?>><?php _e('Friday'); ?></option>
					<option value="6" <?php selected( $calendar_start_of_week, '6'); ?>><?php _e('Saturday'); ?></option>
				</select>
			</td>
		</tr>
		
		<!-- daily -->
		<tr class="rsc-period-day rsc-period-unit">
			<th scope="row">
				<?php esc_html_e('Align for cells', 'really-simple-calendar'); ?>
			</th>
			<td>
				<fieldset>
					<?php 
					$calendar_align = $settings[RS_CALENDAR.'_align']; 
					if(empty($calendar_align)){
						$calendar_align = 0;
					}
					?>
					<p>
					<label>
						<input type="radio" name="<?php echo RS_CALENDAR; ?>_align" value="0" <?php checked( $calendar_align, 0); ?>> <?php esc_html_e('Horizontal', 'really-simple-calendar'); ?>
					</label>
					</p>
					<p>
					<label>
						<input type="radio" name="<?php echo RS_CALENDAR; ?>_align" value="1" <?php checked( $calendar_align, 1); ?>> <?php esc_html_e('Vertical', 'really-simple-calendar'); ?>
					</label>
					</p>
				</fieldset>
			</td>
		</tr>
		
	</table>
	

<?php
	}
}

endif;