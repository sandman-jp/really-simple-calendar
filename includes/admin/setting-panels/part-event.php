<?php 
global $wp_locale;
if(!isset($is_event_locked[$n])){
	$is_event_locked[$n] = 0;
}
?>
<tr>
	<td><input type="hidden" name="calendar_event_number[<?php echo $n; ?>]" value="1"><span class="rsc-col-index"><?php echo $i; ?></span></td>
	<td>
		<?php rsc_param_lock('calendar_event_lock['.$n.']', $is_event_locked[$n]); ?>
	</td>
	
	<td>
		<div class="rsc-event-label rsc-event-inputs">
			<label><?php _e('Label', RSC_TEXTDOMAIN); ?><span class="dashicons dashicons-editor-help" title="<?php _e('This is the name which will appear on pages.'); ?>"></span> : </label>
			<input type="text" name="calendar_event_label[<?php echo $n; ?>]" value="<?php echo rsc_esc($labels[$n]); ?>" class="text" <?php wp_readonly($is_event_locked[$n]); ?> required>
		</div>
		<div class="rsc-event-class rsc-event-inputs">
		<label><?php _e('Class', RSC_TEXTDOMAIN); ?><span class="dashicons dashicons-editor-help" title="<?php _e('If this field is empty, the calendar will use text color	 and bg color.'); ?>"></span> : </label>
		<input type="text" name="calendar_event_class[<?php echo $n; ?>]" value="<?php echo rsc_esc($classes[$n]); ?>" class="short-text rsc-event-class" <?php wp_readonly($is_event_locked[$n]); ?>>
		</div>
	</td>
	
	
	
	<td>
		<div class="rsc-event-date rsc-event-inputs">
			<label><?php _e('Date', RSC_TEXTDOMAIN); ?> : </label>
			<input type="date" name="calendar_event_date[<?php echo $n; ?>]" value="<?php echo rsc_esc($dates[$n]); ?>" class="text" <?php wp_readonly($is_event_locked[$n]); ?>>
			~
			<input type="date" name="calendar_event_last[<?php echo $n; ?>]" value="<?php echo rsc_esc($lasts[$n]); ?>" class="text" <?php wp_readonly($is_event_locked[$n]); ?>>
		</div>
		
		<div class="rsc-event-repeat rsc-event-inputs">
			<label><?php _e('Repeat', RSC_TEXTDOMAIN); ?> : </label>
			<input type="hidden" name="calendar_event_repeat[<?php echo $n; ?>][]" value="">
			<?php for($h=0; $h<7; $h++): ?>
			<label class="rsc-event-repeat-label"><input type="checkbox" name="calendar_event_repeat[<?php echo $n; ?>][]" value="<?php echo $h ?>" <?php checked(in_array($h, $repeats[$n])); ?> <?php wp_readonly($is_event_locked[$n]); ?>><?php _e($wp_locale->get_weekday_abbrev($wp_locale->get_weekday($h))); ?></label>
			<?php endfor; ?>
		</div>
	</td>
	
	
	<td>
		<div class="rsc-event-text-color rsc-event-inputs">
			<label><?php _e('Text', RSC_TEXTDOMAIN); ?> : </label>
			<input type="color" name="calendar_event_text_color[<?php echo $n; ?>]" value="<?php echo rsc_esc($text_colors[$n]); ?>" <?php wp_readonly($is_event_locked[$n]); ?>>
		</div>
		
		<div class="rsc-event-bg-color rsc-event-inputs">
			<label><?php _e('BG', RSC_TEXTDOMAIN); ?> : </label>
			<input type="color" name="calendar_event_bg_color[<?php echo $n; ?>]" value="<?php echo rsc_esc($bg_colors[$n]); ?>" <?php wp_readonly($is_event_locked[$n]); ?>>
		</div>
	</td>
	<td>
		<button class="rsc-col-delete" <?php wp_readonly($is_event_locked[$n]); ?>><span class="dashicons dashicons-dismiss"></span></button>
	</td>
</tr>

<?php
unset($num[$n]);
unset($is_event_locked[$n]);
unset($labels[$n]);
unset($classes[$n]);
unset($dates[$n]);
unset($lasts[$n]);
unset($repeats[$n]);
unset($text_colors[$n]);
unset($bg_colors[$n]);
?>