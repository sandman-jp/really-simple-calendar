<?php
namespace sandmanjp\ReallySimpleCalendar\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\view')):
	
class view extends panel{
	
	public $name = 'view';
	
	public $fields = array(
		SMJP_RS_CALENDAR.'_type', 
		SMJP_RS_CALENDAR.'_from',
		SMJP_RS_CALENDAR.'_from_date', 
		SMJP_RS_CALENDAR.'_previous_from',
		SMJP_RS_CALENDAR.'_period', 
		SMJP_RS_CALENDAR.'_period_last', 
		SMJP_RS_CALENDAR.'_period_date', 
		SMJP_RS_CALENDAR.'_start_of_week',
		SMJP_RS_CALENDAR.'_align',
		//
	);
	
	public $lock_fields = array(
		SMJP_RS_CALENDAR.'_type_lock', 
		SMJP_RS_CALENDAR.'_from_lock',
		SMJP_RS_CALENDAR.'_period_lock',
		SMJP_RS_CALENDAR.'_start_of_week_lock',
	);
	
	function get_label(){
		return esc_html__('View Settings', 'really-simple-calendar');
	}
	
	function echo($settings){
		$class = $this->name;
?>
	
	<?php do_action('smjp_rsc_before_view_settings'); ?>
	<table class="form-table rsc-table">
		
		<?php $is_locked_type = $settings[SMJP_RS_CALENDAR.'_type_lock']; ?>
		<tr>
			<th scope="row">
				<?php smjp_rsc_param_lock(SMJP_RS_CALENDAR.'_type_lock', $is_locked_type); ?>
				
				<label for="rsc-calendar-type"><?php esc_html_e('Calendar Type', 'really-simple-calendar'); ?></label>
			</th>
			<td>
				<?php $calendar_type = $settings[SMJP_RS_CALENDAR.'_type']; ?>
				<select id="rsc-calendar-type" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_type" <?php smjp_rsc_disabled($is_locked_type, true, SMJP_RS_CALENDAR.'_type_lock'); ?>>
					<option value="month" <?php selected( wp_unslash($calendar_type), 'month'); ?>><?php esc_html_e('Monthly', 'really-simple-calendar') ?></option>
					<option value="week" <?php selected( wp_unslash($calendar_type), 'week'); ?>><?php esc_html_e('Weekly', 'really-simple-calendar') ?></option>
					<option value="day" <?php selected( wp_unslash($calendar_type), 'day'); ?>><?php esc_html_e('Daily', 'really-simple-calendar') ?></option>
				</select>
			</td>
		</tr>
		
		<?php $is_locked_from = $settings[SMJP_RS_CALENDAR.'_from_lock']; ?>
		<tr>
			<th scope="row">
				<?php smjp_rsc_param_lock(SMJP_RS_CALENDAR.'_from_lock', $is_locked_from); ?>
				
				<?php esc_html_e('Start From', 'really-simple-calendar'); ?>
			</th>
			<td>
				<?php $calendar_start = $settings[SMJP_RS_CALENDAR.'_from']; 
				?>
				<fieldset>
					<p>
						<label>
							<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_from" value="previous" <?php checked( wp_unslash($calendar_start), 'previous'); ?> <?php smjp_rsc_disabled($is_locked_from, true, SMJP_RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Previous', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_previous_from = $settings[SMJP_RS_CALENDAR.'_previous_from'];
							if($calendar_previous_from === false || $calendar_previous_from == ''){
								$calendar_previous_from = -1;
							}
							?>
							<input type="number" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_previous_from" value="<?php echo esc_attr($calendar_previous_from); ?>" class="small-text" max="-1" <?php smjp_rsc_disabled($is_locked_from, true, SMJP_RS_CALENDAR.'_from_lock'); ?>>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>><?php echo esc_html_x('Month(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>><?php echo esc_html_x('Week(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>><?php echo esc_html_x('Day(s)', 'unit', 'really-simple-calendar'); ?></span>
						</label>
						
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_from" value="current" <?php checked( wp_unslash($calendar_start), 'current'); ?> <?php smjp_rsc_disabled($is_locked_from, true, SMJP_RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Current', 'really-simple-calendar') ?>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>>(<?php echo esc_html__('Month', 'really-simple-calendar'); ?>)</span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>>(<?php echo esc_html__('Week', 'really-simple-calendar'); ?>)</span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>>(<?php echo esc_html__('Day', 'really-simple-calendar'); ?>)</span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_from" value="next" <?php checked( wp_unslash($calendar_start), 'next'); ?> <?php smjp_rsc_disabled($is_locked_from, true, SMJP_RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Next', 'really-simple-calendar') ?>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>>(<?php echo esc_html__('Month', 'really-simple-calendar'); ?>)</span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>>(<?php echo esc_html__('Week', 'really-simple-calendar'); ?>)</span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>>(<?php echo esc_html__('Day', 'really-simple-calendar'); ?>)</span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_from" value="today" <?php checked( wp_unslash($calendar_start), 'today'); ?> <?php smjp_rsc_disabled($is_locked_from, true, SMJP_RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Today', 'really-simple-calendar') ?>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_from" value="date" <?php checked( wp_unslash($calendar_start), 'date'); ?> <?php smjp_rsc_disabled($is_locked_from, true, SMJP_RS_CALENDAR.'_from_lock'); ?>><?php esc_html_e('Date', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php 
							$calendar_from_date = $settings[SMJP_RS_CALENDAR.'_from_date'];
							if(empty($calendar_from_date)){
								$calendar_from_date = wp_date('Y-m-d');
							}
							?>
							<input type="date" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_from_date" value="<?php echo esc_attr($calendar_from_date); ?>" <?php smjp_rsc_disabled($is_locked_from, true, SMJP_RS_CALENDAR.'_from_lock'); ?>>
						</label>
						
					</p>
				</fieldset>
			</td>
		</tr>
		
		<?php $is_locked_period = $settings[SMJP_RS_CALENDAR.'_period_lock']; ?>
		<tr>
			<th scope="row">
				<?php smjp_rsc_param_lock(SMJP_RS_CALENDAR.'_period_lock', $is_locked_period); ?>
				
				<?php esc_html_e('Display Period', 'really-simple-calendar'); ?>
			</th>
			<td>
				<fieldset>
				<p>
						<?php $calendar_period = $settings[SMJP_RS_CALENDAR.'_period']; ?>
						<label>
							<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_period" value="last" <?php checked( wp_unslash($calendar_period), 'last'); ?> <?php smjp_rsc_disabled($is_locked_period, true, SMJP_RS_CALENDAR.'_period_lock'); ?>><?php esc_html_e('Period', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_period_last = $settings[SMJP_RS_CALENDAR.'_period_last'];
							if($calendar_period_last === false || $calendar_period_last == ''){
								$calendar_period_last = 0;
							}
							?>
							<input 
								type="number" 
								id="calendar_period_last" 
								name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_period_last" 
								value="<?php echo esc_attr($calendar_period_last); ?>" 
								class="small-text" 
								min="0" 
								<?php smjp_rsc_disabled($is_locked_period, true, SMJP_RS_CALENDAR.'_period_lock'); ?>
								>
							<span class="rsc-period-month rsc-period-unit" <?php echo esc_attr($calendar_type == 'month' ? '' : 'style="display:none"');?>><?php echo esc_html_x('Month(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-week rsc-period-unit" <?php echo esc_attr($calendar_type == 'week' ? '' : 'style="display:none"');?>><?php echo esc_html_x('Week(s)', 'unit', 'really-simple-calendar'); ?></span><span class="rsc-period-day rsc-period-unit" <?php echo esc_attr($calendar_type == 'day' ? '' : 'style="display:none"');?>><?php echo esc_html_x('Day(s)', 'unit', 'really-simple-calendar'); ?></span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_period" value="date" <?php checked( wp_unslash($calendar_period), 'date'); ?> <?php smjp_rsc_disabled($is_locked_period, true, SMJP_RS_CALENDAR.'_period_lock'); ?>><?php esc_html_e('Date', 'really-simple-calendar') ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_period_date = $settings[SMJP_RS_CALENDAR.'_period_date']; 
							if(empty($calendar_period_date)){
								$calendar_period_date = wp_date('Y-m-d');
							}
							?>
							<input type="date" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_period_date" value="<?php echo esc_attr($calendar_period_date); ?>" <?php smjp_rsc_disabled($is_locked_period, true, SMJP_RS_CALENDAR.'_period_lock'); ?>>
						</label>
					</p>
				</fieldset>
			</td>
		</tr>
		
		<!-- monthly or weekly -->
		<?php $is_locked_start_of_week = $settings[SMJP_RS_CALENDAR.'_start_of_week_lock']; ?>
		<tr class="rsc-period-month rsc-period-week rsc-period-unit">
			<th scope="row">
				<?php smjp_rsc_param_lock(SMJP_RS_CALENDAR.'_start_of_week_lock', $is_locked_start_of_week); ?>
				
				<label for="calendar-start-of-week"><?php esc_html_e('Start day of week', 'really-simple-calendar'); ?></label>
			</th>
			<td>
				<?php $calendar_start_of_week = $settings[SMJP_RS_CALENDAR.'_start_of_week']; ?>
				<select name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_start_of_week" id="calendar-start-of-week" <?php smjp_rsc_disabled($is_locked_start_of_week, true, SMJP_RS_CALENDAR.'_start_of_week_lock'); ?>>
					<option value="today" <?php selected( wp_unslash($calendar_start_of_week), 'today'); ?>><?php esc_html_e('Today', 'really-simple-calendar'); ?></option>
					<option value="0" <?php selected( wp_unslash($calendar_start_of_week), '0'); ?>><?php esc_html_e('Sunday', 'really-simple-calendar'); ?></option>
					<option value="1" <?php selected( wp_unslash($calendar_start_of_week), '1'); ?>><?php esc_html_e('Monday', 'really-simple-calendar'); ?></option>
					<option value="2" <?php selected( wp_unslash($calendar_start_of_week), '2'); ?>><?php esc_html_e('Tuesday', 'really-simple-calendar'); ?></option>
					<option value="3" <?php selected( wp_unslash($calendar_start_of_week), '3'); ?>><?php esc_html_e('Wednesday', 'really-simple-calendar'); ?></option>
					<option value="4" <?php selected( wp_unslash($calendar_start_of_week), '4'); ?>><?php esc_html_e('Thursday', 'really-simple-calendar'); ?></option>
					<option value="5" <?php selected( wp_unslash($calendar_start_of_week), '5'); ?>><?php esc_html_e('Friday', 'really-simple-calendar'); ?></option>
					<option value="6" <?php selected( wp_unslash($calendar_start_of_week), '6'); ?>><?php esc_html_e('Saturday', 'really-simple-calendar'); ?></option>
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
					$calendar_align = $settings[SMJP_RS_CALENDAR.'_align']; 
					if(empty($calendar_align)){
						$calendar_align = 0;
					}
					?>
					<p>
					<label>
						<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_align" value="0" <?php checked( wp_unslash($calendar_align), 0); ?>> <?php esc_html_e('Horizontal', 'really-simple-calendar'); ?>
					</label>
					</p>
					<p>
					<label>
						<input type="radio" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_align" value="1" <?php checked( wp_unslash($calendar_align), 1); ?>> <?php esc_html_e('Vertical', 'really-simple-calendar'); ?>
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