
<div class="rsc-event-unit">
	<div class="rsc-event-class rsc-event-inputs">
		<label><?php esc_html_e('Class', 'really-simple-calendar'); ?><span class="dashicons dashicons-editor-help" title="<?php esc_html_e('If this field is empty, the calendar will use text color and bg color.', 'really-simple-calendar'); ?>"></span> : </label>
		<input type="text" name="<?php echo RS_CALENDAR; ?>_event_class[<?php echo $n; ?>]" value="<?php echo $classes[$n]; ?>" class="short-text rsc-event-class" <?php echo $attr; ?>>
	</div>
</div>

<div class="rsc-event-unit">
	<div class="rsc-event-date rsc-event-inputs">
		<label><?php esc_html_e('Date', 'really-simple-calendar'); ?> : </label>
		<input type="date" name="<?php echo RS_CALENDAR; ?>_event_date[<?php echo $n; ?>]" value="<?php echo $dates[$n]; ?>" class="text" <?php echo $attr; ?>>
	</div>
</div>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_bg_color[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_text_color[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_last[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_repeat[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>
<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_exclude[<?php echo $n; ?>]" value="" <?php echo $attr; ?>>