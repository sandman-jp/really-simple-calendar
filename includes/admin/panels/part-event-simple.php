
<div class="rsc-event-unit">
	<div class="rsc-event-class rsc-event-inputs">
		<label><?php _e('Class', 'really-simple-calendar'); ?><span class="dashicons dashicons-editor-help" title="<?php _e('If this field is empty, the calendar will use text color	 and bg color.'); ?>"></span> : </label>
		<input type="text" name="<?php echo RS_CALENDAR; ?>_event_class[<?php echo $n; ?>]" value="<?php echo rsc_esc($classes[$n]); ?>" class="short-text rsc-event-class" <?php echo $attr; ?>>
	</div>
</div>

<div class="rsc-event-unit">
	<div class="rsc-event-date rsc-event-inputs">
		<label><?php _e('Date', 'really-simple-calendar'); ?> : </label>
		<input type="date" name="<?php echo RS_CALENDAR; ?>_event_date[<?php echo $n; ?>]" value="<?php echo rsc_esc($dates[$n]); ?>" class="text" <?php echo $attr; ?>>
	</div>
</div>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_bg_color[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_text_color[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_last[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_repeat[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_exclude[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>