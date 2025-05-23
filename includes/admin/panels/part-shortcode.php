<?php
$panel = new \RSC\Admin\Panel\view();
$lock_fields = $panel->get_lock_fields();
$locked = array();
foreach($lock_fields as $key){
	$locked[$key] = get_option($key);
}
$default_value = apply_filters('rsc_get_default_shortcode', '[rsc]');
?>
<h2><?php esc_html_e('Shortcode', 'really-simple-calendar'); ?></h2>
<section class="rsc-shortcode-wrapper">
	<div class="rsc-shortcode-box description">
		<label class="shortcode"><input type="text" id="rsc-shortcode" name="rsc-shortcode" onfocus="this.select();" class="large-text code" value="<?php rsc_echo_esc($default_value, false); ?>"></label>
		<button id="rsc-copy-button" class="rsc-copy-button" title="Copy this shortcode."><span class="dashicons dashicons-clipboard"></span><span class="rsc-tooltip">Copied!</span></button>
	</div>
	
	<div id="rsc-shortcode-panel" class="rsc-shortcode-panel">
		<div  form="rsd-shortcode-form" name="rsc-shortcode-form">
			<label><?php esc_html_e('Attribute', 'really-simple-calendar'); ?>
				<select id="rsc-shortcode-attr"  form="rsd-shortcode-form" name="rsc-shortcode-attr">
					<option value=""><?php esc_html_e('Select', 'really-simple-calendar'); ?></option>
					<option value="type" data-locked="<?php echo $locked[RS_CALENDAR.'_type_lock']; ?>" <?php disabled($locked[RS_CALENDAR.'_type_lock']); ?>><?php esc_html_e('Calendar Type', 'really-simple-calendar'); ?></option>
					<option value="from" data-locked="<?php echo $locked[RS_CALENDAR.'_from_lock']; ?>" <?php disabled($locked[RS_CALENDAR.'_from_lock']); ?>><?php esc_html_e('Start From', 'really-simple-calendar'); ?></option>
					<option value="period" data-locked="<?php echo $locked[RS_CALENDAR.'_period_lock']; ?>" <?php disabled($locked[RS_CALENDAR.'_period_lock']); ?>><?php esc_html_e('Display Period', 'really-simple-calendar'); ?></option>
					<option value="start_of_week" data-locked="<?php echo $locked[RS_CALENDAR.'_start_of_week_lock']; ?>" <?php disabled($locked[RS_CALENDAR.'_start_of_week_lock']); ?>><?php esc_html_e('Start day of week', 'really-simple-calendar'); ?></option>
				</select>
			</label>
			
			<select id="rsc-shortcode-attr-type" class="rsc-shortcode-attr"  form="rsd-shortcode-form" name="rsc-shortcode-attr-type" required>
				<option value=""><?php esc_html_e('Calendar Type', 'really-simple-calendar'); ?></option>
				<option value="month"><?php esc_html_e('Monthly', 'really-simple-calendar'); ?></option>
				<option value="week"><?php esc_html_e('Weekly', 'really-simple-calendar'); ?></option>
				<option value="day"><?php esc_html_e('Daily', 'really-simple-calendar'); ?></option>
			</select>
			
			<select id="rsc-shortcode-attr-from" class="rsc-shortcode-attr"  form="rsd-shortcode-form" name="rsc-shortcode-attr-from" required>
				<option value=""><?php esc_html_e('Start From', 'really-simple-calendar'); ?></option>
				<option value="previous"><?php esc_html_e('Previous', 'really-simple-calendar'); ?></option>
				<option value="current" selected><?php esc_html_e('Current', 'really-simple-calendar'); ?></option>
				<option value="next"><?php esc_html_e('Next', 'really-simple-calendar'); ?></option>
				<option value="today"><?php esc_html_e('Today', 'really-simple-calendar'); ?></option>
				<option value="date"><?php esc_html_e('Date', 'really-simple-calendar'); ?></option>
			</select>
			
			<select id="rsc-shortcode-attr-period" class="rsc-shortcode-attr"  form="rsd-shortcode-form" name="rsc-shortcode-attr-period" required>
				<option value=""><?php esc_html_e('Display Period', 'really-simple-calendar'); ?></option>
				<option value="last"><?php esc_html_e('Priod', 'really-simple-calendar'); ?></option>
				<option value="date"><?php esc_html_e('Date', 'really-simple-calendar'); ?></option>
			</select>
			
			
			<select id="rsc-shortcode-attr-type-align" class="rsc-shortcode-attr"  form="rsd-shortcode-form" name="rsc-shortcode-attr-align" required>
				<option value=""><?php esc_html_e('Align for cells', 'really-simple-calendar'); ?></option>
				<option value="0"><?php esc_html_e('Horizontal', 'really-simple-calendar'); ?></option>
				<option value="1"><?php esc_html_e('Vertical', 'really-simple-calendar'); ?></option>
			</select>
			
			<label id="rsc-shortcode-attr-from-previous" class="rsc-shortcode-attr">
				<input type="number" max="-1" value="" class="small-text"  form="rsd-shortcode-form" name="rsc-shortcode-attr-from-previous" required>
			</label>
			
			<label id="rsc-shortcode-attr-period-last" class="rsc-shortcode-attr">
				<input type="number" min="0" value="" class="small-text"  form="rsd-shortcode-form" name="rsc-shortcode-attr-period-last" required>
			</label>
			
			<label id="rsc-shortcode-attr-date" class="rsc-shortcode-attr">
				<input type="date" value=""  form="rsd-shortcode-form" name="rsc-shortcode-attr-date" required>
			</label>
			
			<select id="rsc-shortcode-attr-start_of_week" class="rsc-shortcode-attr"  form="rsd-shortcode-form" name="rsc-shortcode-attr-start_of_week">
				<option value="today"><?php _e('Today', 'really-simple-calendar'); ?></option>
				<option value="0"><?php _e('Sunday'); ?></option>
				<option value="1"><?php _e('Monday'); ?></option>
				<option value="2"><?php _e('Tuesday'); ?></option>
				<option value="3"><?php _e('Wednesday'); ?></option>
				<option value="4"><?php _e('Thursday'); ?></option>
				<option value="5"><?php _e('Friday'); ?></option>
				<option value="6"><?php _e('Saturday'); ?></option>
			</select>
			
			<button id="rsc-add-attr-button" class="button"><?php esc_html_e('Add an attribute', 'really-simple-calendar'); ?></button>
		</div>
	</div>
</section>
<hr>