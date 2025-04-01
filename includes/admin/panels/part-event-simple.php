
<div class="rsc-event-unit">
	<div class="rsc-event-class rsc-event-inputs">
		<label><?php esc_html_e('Class', 'really-simple-calendar'); ?><span class="dashicons dashicons-editor-help" title="<?php esc_html_e('If this field is empty, the calendar will use text color	 and bg color.'); ?>"></span> : </label>
		<input type="text" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_class[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($classes[$n]); ?>" class="short-text rsc-event-class" <?php rsc_echo_esc($attr); ?>>
	</div>
</div>

<div class="rsc-event-unit">
	<div class="rsc-event-date rsc-event-inputs">
		<label><?php esc_html_e('Date', 'really-simple-calendar'); ?> : </label>
		<input type="date" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_date[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($dates[$n]); ?>" class="text" <?php rsc_echo_esc($attr); ?>>
	</div>
</div>
<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_bg_color[<?php rsc_echo_esc($n); ?>]" value="" <?php rsc_echo_esc($attr); ?>>
<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_text_color[<?php rsc_echo_esc($n); ?>]" value="" <?php rsc_echo_esc($attr); ?>>
<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_last[<?php rsc_echo_esc($n); ?>]" value="" <?php rsc_echo_esc($attr); ?>>
<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_repeat[<?php rsc_echo_esc($n); ?>]" value="" <?php rsc_echo_esc($attr); ?>>
<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_exclude[<?php rsc_echo_esc($n); ?>]" value="" <?php rsc_echo_esc($attr); ?>>