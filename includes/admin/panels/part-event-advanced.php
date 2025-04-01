
<div class="rsc-event-unit">
	<div class="rsc-event-class rsc-event-inputs">
		<label><?php _e('Class', RSC_TEXTDOMAIN); ?><span class="dashicons dashicons-editor-help" title="<?php _e('If this field is empty, the calendar will use text color	 and bg color.'); ?>"></span> : </label>
		<input type="text" name="<?php echo RS_CALENDAR; ?>_event_class[<?php echo $n; ?>]" value="<?php echo rsc_esc($classes[$n]); ?>" class="short-text rsc-event-class" <?php echo $attr; ?>>
	</div>
	
	<?php $text_colors[$n] = empty($text_colors[$n]) ? '#ffffff' : $text_colors[$n]; ?>
	<div class="rsc-event-text-color rsc-event-inputs">
		<label><?php _e('Text', RSC_TEXTDOMAIN); ?> : </label>
		<input type="color" name="<?php echo RS_CALENDAR; ?>_event_text_color[<?php echo $n; ?>]" value="<?php echo rsc_esc($text_colors[$n]); ?>" <?php echo !empty($attr) ? $attr : disabled(!empty($classes[$n])); ?>>
	</div>
	
	<?php $bg_colors[$n] = empty($bg_colors[$n]) ? '#999999' : $bg_colors[$n]; ?>
	<div class="rsc-event-bg-color rsc-event-inputs">
		<label><?php _e('BG', RSC_TEXTDOMAIN); ?> : </label>
		<input type="color" name="<?php echo RS_CALENDAR; ?>_event_bg_color[<?php echo $n; ?>]" value="<?php echo rsc_esc($bg_colors[$n]); ?>" <?php echo !empty($attr) ? $attr : disabled(!empty($classes[$n])); ?>>
	</div>
</div>

<div class="rsc-event-unit">
	<div class="rsc-event-date rsc-event-inputs">
		<label><?php _e('Period', RSC_TEXTDOMAIN); ?> : </label>
		<input type="date" name="<?php echo RS_CALENDAR; ?>_event_date[<?php echo $n; ?>]" value="<?php echo rsc_esc($dates[$n]); ?>" class="text" <?php echo $attr; ?>>
		<span style="padding: 0 10px"> ~ </span>
		<?php $lasts[$n] = empty($lasts[$n]) ? '' : $lasts[$n]; ?>
		<input type="date" name="<?php echo RS_CALENDAR; ?>_event_last[<?php echo $n; ?>]" value="<?php echo rsc_esc($lasts[$n]); ?>" class="text" <?php echo $attr; ?>>
	</div>
	
	<?php $repeats[$n] = empty($repeats[$n]) ? array() : $repeats[$n]; ?>
	<div class="rsc-event-repeat rsc-event-inputs">
		<label><?php _e('Repeat', RSC_TEXTDOMAIN); ?> : </label>
		<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_repeat[<?php echo $n; ?>][]" value="">
		
		<?php for($h=0; $h<7; $h++): ?>
		<label class="rsc-event-repeat-label"><input type="checkbox" name="<?php echo RS_CALENDAR; ?>_event_repeat[<?php echo $n; ?>][]" value="<?php echo $h ?>" <?php checked(in_array((string)$h, $repeats[$n], true)); ?> <?php echo $attr; ?>><?php _e($wp_locale->get_weekday_abbrev($wp_locale->get_weekday($h))); ?></label>

		<?php endfor; ?>
	</div>
	
	<div class="rsc-event-exclude rsc-event-inputs">
		<div>
			<label><?php _e('Exclude', RSC_TEXTDOMAIN); ?> : </label>
			<input type="date" class="rsc-event-exclude-date" data-time="<?php echo $n; ?>" <?php echo $attr; ?>>
		</div>
		<?php $excludes[$n] = empty($excludes[$n]) ? array() : $excludes[$n]; ?>
		<div class="rsc-event-exclude-list" <?php echo $attr; ?>>
			
		</div>
		
	</div>
	
</div>

