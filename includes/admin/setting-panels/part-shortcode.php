<?php
$panel = new \RSC\Admin\Panel\bulk();
$lock_fields = $panel->get_lock_fields();
$locked = array();
foreach($lock_fields as $key){
	$locked[$key] = get_option($key);
}
?>
<h2>Shortcode</h2>
<section class="rsc-shortcode-wrapper">
	<div class="rsc-shortcode-box description">
		<label class="shortcode"><input type="text" id="rsc-shortcode" onfocus="this.select();" class="large-text code" value="[rsc]"></label>
		<button id="rsc-copy-button" class="rsc-copy-button" title="Copy this shortcode."><span class="dashicons dashicons-clipboard"></span><span class="rsc-tooltip">Copied!</span></button>
	</div>
	
	<div class="rsc-shortcode-panel">
		<form id="rsd-shortcode-form">
			<label><?php _e('Attribute', RSC_TEXTDOMAIN); ?>
				<select id="rsc-shortcode-attr">
					<option value=""><?php _e('Select Attribute', RSC_TEXTDOMAIN); ?></option>
					<option value="type" data-locked="<?php echo $locked['calendar_type_lock'] ?>" <?php disabled($locked['calendar_type_lock']); ?>><?php _e('Calendar Type', RSC_TEXTDOMAIN); ?></option>
					<option value="from" data-locked="<?php echo $locked['calendar_from_lock'] ?>" <?php disabled($locked['calendar_from_lock']); ?>><?php _e('Start from', RSC_TEXTDOMAIN); ?></option>
					<option value="period" data-locked="<?php echo $locked['calendar_period_lock'] ?>" <?php disabled($locked['calendar_period_lock']); ?>><?php _e('Display Period', RSC_TEXTDOMAIN); ?></option>
					<option value="start_of_week" data-locked="<?php echo $locked['calendar_start_of_week_lock'] ?>" <?php disabled($locked['calendar_start_of_week_lock']); ?>><?php _e('Start day of week', RSC_TEXTDOMAIN); ?></option>
				</select>
			</label>
			
			<select id="rsc-shortcode-attr-type" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Select Calendar Type', RSC_TEXTDOMAIN); ?></option>
				<option value="month"><?php _e('Monthly', RSC_TEXTDOMAIN); ?></option>
				<option value="week"><?php _e('Weekly', RSC_TEXTDOMAIN); ?></option>
				<option value="day"><?php _e('Daily', RSC_TEXTDOMAIN); ?></option>
			</select>
			
			<select id="rsc-shortcode-attr-from" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Select Start From', RSC_TEXTDOMAIN); ?></option>
				<option value="previous"><?php _e('Previous', RSC_TEXTDOMAIN); ?></option>
				<option value="current" selected><?php _e('Current', RSC_TEXTDOMAIN); ?></option>
				<option value="next"><?php _e('Next', RSC_TEXTDOMAIN); ?></option>
				<option value="today"><?php _e('Today', RSC_TEXTDOMAIN); ?></option>
				<option value="date"><?php _e('Date', RSC_TEXTDOMAIN); ?></option>
			</select>
			
			<select id="rsc-shortcode-attr-period" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Select Display Period', RSC_TEXTDOMAIN); ?></option>
				<option value="last"><?php _e('Priod', RSC_TEXTDOMAIN); ?></option>
				<option value="date"><?php _e('Date', RSC_TEXTDOMAIN); ?></option>
			</select>
			
			
			<select id="rsc-shortcode-attr-type-align" class="rsc-shortcode-attr" required>
				<option value=""><?php _e('Select Calendar Align', RSC_TEXTDOMAIN); ?></option>
				<option value="0"><?php _e('Horizontal', RSC_TEXTDOMAIN); ?></option>
				<option value="1"><?php _e('Vertical', RSC_TEXTDOMAIN); ?></option>
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
				<option value="today"><?php _e('Today'); ?></option>
				<option value="0"><?php _e('Sunday'); ?></option>
				<option value="1"><?php _e('Monday'); ?></option>
				<option value="2"><?php _e('Tuesday'); ?></option>
				<option value="3"><?php _e('Wednesday'); ?></option>
				<option value="4"><?php _e('Thursday'); ?></option>
				<option value="5"><?php _e('Friday'); ?></option>
				<option value="6"><?php _e('Saturday'); ?></option>
			</select>
			
			<button id="rsc-add-attr-button" class="button"><?php _e('Add an attribute.', RSC_TEXTDOMAIN); ?></button>
		</form>
	</div>
</section>
<hr>