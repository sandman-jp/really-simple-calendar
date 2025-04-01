<?php
$panel = new \RSC\Admin\Panel\view();
$lock_fields = $panel->get_lock_fields();
$locked = array();
foreach($lock_fields as $key){
	$locked[$key] = get_option($key);
}
?>
<h2><?php _e('Shortcode', 'really-simple-calendar'); ?></h2>
<section class="rsc-shortcode-wrapper">
	<div class="rsc-shortcode-box description">
		<label class="shortcode"><input type="text" id="rsc-shortcode" onfocus="this.select();" class="large-text code" value="[rsc]"></label>
		<button id="rsc-copy-button" class="rsc-copy-button" title="Copy this shortcode."><span class="dashicons dashicons-clipboard"></span><span class="rsc-tooltip">Copied!</span></button>
	</div>
	
	<div class="rsc-shortcode-panel">
		<form id="rsd-shortcode-form">
			<label><?php _e('Attribute', 'really-simple-calendar'); ?>
				<select id="rsc-shortcode-attr">
					<option value=""><?php _e('Select', 'really-simple-calendar'); ?></option>
					<option value="type" data-locked="<?php echo $locked[RS_CALENDAR.'_type_lock'] ?>" <?php disabled($locked[RS_CALENDAR.'_type_lock']); ?>><?php _e('Calendar Type', 'really-simple-calendar'); ?></option>
					<option value="from" data-locked="<?php echo $locked[RS_CALENDAR.'_from_lock'] ?>" <?php disabled($locked[RS_CALENDAR.'_from_lock']); ?>><?php _e('Start From', 'really-simple-calendar'); ?></option>
					<option value="period" data-locked="<?php echo $locked[RS_CALENDAR.'_period_lock'] ?>" <?php disabled($locked[RS_CALENDAR.'_period_lock']); ?>><?php _e('Display Period', 'really-simple-calendar'); ?></option>
					<option value="start_of_week" data-locked="<?php echo $locked[RS_CALENDAR.'_start_of_week_lock'] ?>" <?php disabled($locked[RS_CALENDAR.'_start_of_week_lock']); ?>><?php _e('Start day of week', 'really-simple-calendar'); ?></option>
				</select>
			</label>
			
			<select id="rsc-shortcode-attr-type" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Calendar Type', 'really-simple-calendar'); ?></option>
				<option value="month"><?php _e('Monthly', 'really-simple-calendar'); ?></option>
				<option value="week"><?php _e('Weekly', 'really-simple-calendar'); ?></option>
				<option value="day"><?php _e('Daily', 'really-simple-calendar'); ?></option>
			</select>
			
			<select id="rsc-shortcode-attr-from" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Start From', 'really-simple-calendar'); ?></option>
				<option value="previous"><?php _e('Previous', 'really-simple-calendar'); ?></option>
				<option value="current" selected><?php _e('Current', 'really-simple-calendar'); ?></option>
				<option value="next"><?php _e('Next', 'really-simple-calendar'); ?></option>
				<option value="today"><?php _e('Today', 'really-simple-calendar'); ?></option>
				<option value="date"><?php _e('Date', 'really-simple-calendar'); ?></option>
			</select>
			
			<select id="rsc-shortcode-attr-period" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Display Period', 'really-simple-calendar'); ?></option>
				<option value="last"><?php _e('Priod', 'really-simple-calendar'); ?></option>
				<option value="date"><?php _e('Date', 'really-simple-calendar'); ?></option>
			</select>
			
			
			<select id="rsc-shortcode-attr-type-align" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Align for cells', 'really-simple-calendar'); ?></option>
				<option value="0"><?php _e('Horizontal', 'really-simple-calendar'); ?></option>
				<option value="1"><?php _e('Vertical', 'really-simple-calendar'); ?></option>
			</select>
			
			<label id="rsc-shortcode-attr-from-previous" class="rsc-shortcode-attr">
				<input type="number" max="-1" value="" class="small-text" required>
			</label>
			
			<label id="rsc-shortcode-attr-period-last" class="rsc-shortcode-attr">
				<input type="number" min="0" value="" class="small-text" required>
			</label>
			
			<label id="rsc-shortcode-attr-date" class="rsc-shortcode-attr">
				<input type="date" value="" required>
			</label>
			
			<select id="rsc-shortcode-attr-start_of_week" class="rsc-shortcode-attr">
				<option value="today"><?php _e('Today', 'really-simple-calendar'); ?></option>
				<option value="0"><?php _e('Sunday', 'really-simple-calendar'); ?></option>
				<option value="1"><?php _e('Monday', 'really-simple-calendar'); ?></option>
				<option value="2"><?php _e('Tuesday', 'really-simple-calendar'); ?></option>
				<option value="3"><?php _e('Wednesday', 'really-simple-calendar'); ?></option>
				<option value="4"><?php _e('Thursday', 'really-simple-calendar'); ?></option>
				<option value="5"><?php _e('Friday', 'really-simple-calendar'); ?></option>
				<option value="6"><?php _e('Saturday', 'really-simple-calendar'); ?></option>
			</select>
			
			<button id="rsc-add-attr-button" class="button"><?php _e('Add an attribute', 'really-simple-calendar'); ?></button>
		</form>
	</div>
</section>
<hr>