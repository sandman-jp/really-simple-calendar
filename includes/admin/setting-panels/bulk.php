<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\bulk')):
	
class bulk extends panel{
	
	public $name = 'bulk';
	public $label = 'Bulk Settings';
	
	public $fields = array(
		'calendar_type', 
		'calendar_from',
		'calendar_from_date', 
		'calendar_previous_from',
		'calendar_period', 
		'calendar_period_last', 
		'calendar_period_date', 
		'calendar_start_of_week',
		'calendar_align',
		//
	);
	
	public $lock_fields = array(
		'calendar_type_lock', 
		'calendar_from_lock',
		'calendar_period_lock',
		'calendar_start_of_week_lock',
	);
	
	function echo($settings){
		$class = $this->name;
?>
<form method="post" action="" class="rsc-calendar-form rsc-ajax-update">
	<?php wp_nonce_field('rsc_'.$class, 'rsc_save_settings_nonce'); ?>
	<input type="hidden" name="rsc_setting" value="<?php echo $class; ?>">
	
	<?php do_action('rsc_before_bulk_settings'); ?>
	<table class="form-table rsc-table">
		
		<?php $is_locked_type = $settings['calendar_type_lock']; ?>
		<tr>
			<th scope="row">
				<div class="rsc-setting-lock">
					<?php rsc_param_lock('calendar_type_lock', $is_locked_type); ?>
				</div>
				
				<label for="rsc-calendar-type"><?php _e('Calendar Type', RSC_TEXTDOMAIN); ?></label>
			</th>
			<td>
				<?php $calendar_type = $settings['calendar_type']; ?>
				<select id="rsc-calendar-type" name="calendar_type" <?php wp_readonly($is_locked_type); ?>>
					<option value="month" <?php selected( $calendar_type, 'month'); ?>><?php _e('Monthly Calendar', RSC_TEXTDOMAIN) ?></option>
					<option value="week" <?php selected( $calendar_type, 'week'); ?>><?php _e('Weekly Calendar', RSC_TEXTDOMAIN) ?></option>
					<option value="day" <?php selected( $calendar_type, 'day'); ?>><?php _e('Daily Calendar', RSC_TEXTDOMAIN) ?></option>
				</select>
			</td>
		</tr>
		
		<?php $is_locked_from = $settings['calendar_from_lock']; ?>
		<tr>
			<th scope="row">
				<div class="rsc-setting-lock">
					<?php rsc_param_lock('calendar_from_lock', $is_locked_from); ?>
				</div>
				
				<?php _e('Start From', RSC_TEXTDOMAIN); ?>
			</th>
			<td>
				<?php $calendar_start = $settings['calendar_from']; 
				?>
				<fieldset>
					<p>
						<label>
							<input type="radio" name="calendar_from" value="previous" <?php checked( $calendar_start, 'previous'); ?> <?php wp_readonly($is_locked_from); ?>><?php _e('Previous', RSC_TEXTDOMAIN) ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_previous_from = $settings['calendar_previous_from'];
							if($calendar_previous_from === false || $calendar_previous_from == ''){
								$calendar_previous_from = -1;
							}
							?>
							<input type="number" name="calendar_previous_from" value="<?php echo $calendar_previous_from; ?>" class="small-text" max="-1" <?php wp_readonly($is_locked_from); ?>>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>><?php echo _x('Month', 'relative', RSC_TEXTDOMAIN); ?></span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>><?php echo _x('Week', 'relative', RSC_TEXTDOMAIN); ?></span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>><?php echo _x('Day', 'relative', RSC_TEXTDOMAIN); ?></span>
						</label>
						
					</p>
					<p>
						<label>
							<input type="radio" name="calendar_from" value="current" <?php checked( $calendar_start, 'current'); ?> <?php wp_readonly($is_locked_from); ?>><?php _e('Current', RSC_TEXTDOMAIN) ?>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>>(<?php echo __('Month', RSC_TEXTDOMAIN); ?>)</span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>>(<?php echo __('Week', RSC_TEXTDOMAIN); ?>)</span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>>(<?php echo __('Day', RSC_TEXTDOMAIN); ?>)</span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="calendar_from" value="next" <?php checked( $calendar_start, 'next'); ?> <?php wp_readonly($is_locked_from); ?>><?php _e('Next', RSC_TEXTDOMAIN) ?>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>>(<?php echo __('Month', RSC_TEXTDOMAIN); ?>)</span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>>(<?php echo __('Week', RSC_TEXTDOMAIN); ?>)</span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>>(<?php echo __('Day', RSC_TEXTDOMAIN); ?>)</span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="calendar_from" value="today" <?php checked( $calendar_start, 'today'); ?> <?php wp_readonly($is_locked_from); ?>><?php _e('Today', RSC_TEXTDOMAIN) ?>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="calendar_from" value="date" <?php checked( $calendar_start, 'date'); ?> <?php wp_readonly($is_locked_from); ?>><?php _e('Date', RSC_TEXTDOMAIN) ?>
						</label>
						<label class="rsc-input-others">
							<?php 
							$calendar_from_date = $settings['calendar_from_date'];
							if(empty($calendar_from_date)){
								$calendar_from_date = wp_date('Y-m-d');
							}
							?>
							<input type="date" name="calendar_from_date" value="<?php echo $calendar_from_date; ?>" <?php wp_readonly($is_locked_from); ?>>
						</label>
						
					</p>
				</fieldset>
			</td>
		</tr>
		
		<?php $is_locked_period = $settings['calendar_period_lock']; ?>
		<tr>
			<th scope="row">
				<div class="rsc-setting-lock">
					<?php rsc_param_lock('calendar_period_lock', $is_locked_period); ?>
				</div>
				
				<?php _e('Display Period', RSC_TEXTDOMAIN); ?>
			</th>
			<td>
				<fieldset>
				<p>
						<?php $calendar_period = $settings['calendar_period']; ?>
						<label>
							<input type="radio" name="calendar_period" value="last" <?php checked( $calendar_period, 'last'); ?> <?php wp_readonly($is_locked_period); ?>><?php _e('Period', RSC_TEXTDOMAIN) ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_period_last = $settings['calendar_period_last'];
							if($calendar_period_last === false || $calendar_period_last == ''){
								$calendar_period_last = 0;
							}
							?>
							<input type="number" id="calendar_period_last" name="calendar_period_last" value="<?php echo $calendar_period_last; ?>" class="small-text" min="0" <?php wp_readonly($is_locked_period); ?>>
							<span class="rsc-period-month rsc-period-unit" <?php echo $calendar_type == 'month' ? '' : 'style="display:none"';?>><?php echo _x('Month', 'relative', RSC_TEXTDOMAIN); ?></span><span class="rsc-period-week rsc-period-unit" <?php echo $calendar_type == 'week' ? '' : 'style="display:none"';?>><?php echo _x('Week', 'relative', RSC_TEXTDOMAIN); ?></span><span class="rsc-period-day rsc-period-unit" <?php echo $calendar_type == 'day' ? '' : 'style="display:none"';?>><?php echo _x('Day', 'relative', RSC_TEXTDOMAIN); ?></span>
						</label>
					</p>
					<p>
						<label>
							<input type="radio" name="calendar_period" value="date" <?php checked( $calendar_period, 'date'); ?> <?php wp_readonly($is_locked_period); ?>><?php _e('Date', RSC_TEXTDOMAIN) ?>
						</label>
						<label class="rsc-input-others">
							<?php
							$calendar_period_date = $settings['calendar_period_date']; 
							if(empty($calendar_period_date)){
								$calendar_period_date = wp_date('Y-m-d');
							}
							?>
							<input type="date" name="calendar_period_date" value="<?php echo $calendar_period_date; ?>" <?php wp_readonly($is_locked_period); ?>>
						</label>
					</p>
				</fieldset>
			</td>
		</tr>
		
		<!-- monthly or weekly -->
		<?php $is_locked_start_of_week = $settings['calendar_start_of_week_lock']; ?>
		<tr class="rsc-period-month rsc-period-week rsc-period-unit">
			<th scope="row">
				<div class="rsc-setting-lock">
					<?php rsc_param_lock('calendar_start_of_week_lock', $is_locked_start_of_week); ?>
				</div>
				
				<label for="calendar-start-of-week"><?php _e('Start day of week', RSC_TEXTDOMAIN); ?></label>
			</th>
			<td>
				<?php $calendar_start_of_week = $settings['calendar_start_of_week']; ?>
				<select name="calendar_start_of_week" id="calendar-start-of-week" <?php wp_readonly($is_locked_start_of_week); ?>>
					<option value="today" <?php selected( $calendar_start_of_week, 'today'); ?>><?php _e('Today'); ?></option>
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
				<?php _e('Align for Cells', RSC_TEXTDOMAIN); ?>
			</th>
			<td>
				<fieldset>
					<?php 
					$calendar_align = $settings['calendar_align']; 
					if(empty($calendar_align)){
						$calendar_align = 0;
					}
					?>
					<p>
					<label>
						<input type="radio" name="calendar_align" value="0" <?php checked( $calendar_align, 0); ?>> <?php _e('Horizontal'); ?>
					</label>
					</p>
					<p>
					<label>
						<input type="radio" name="calendar_align" value="1" <?php checked( $calendar_align, 1); ?>> <?php _e('Vertical'); ?>
					</label>
					</p>
				</fieldset>
			</td>
		</tr>
		
	</table>
	<?php do_action('rsc_after_bulk_settings'); ?>
	<?php submit_button(); ?>
</form>

<?php
	}
}

endif;