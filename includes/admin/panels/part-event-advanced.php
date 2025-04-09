
<div class="rsc-event-unit">
	<div class="rsc-event-class rsc-event-inputs">
		<label><?php esc_html_e('Class', 'really-simple-calendar'); ?><span class="dashicons dashicons-editor-help" title="<?php esc_html_e('If this field is empty, the calendar will use text color and bg color.', 'really-simple-calendar'); ?>"></span> : </label>
		<input type="text" name="<?php echo RS_CALENDAR; ?>_event_class[<?php echo $n; ?>]" value="<?php echo $classes[$n]; ?>" class="short-text rsc-event-class" <?php echo $attr; ?>>
	</div>
	
	<?php $text_colors[$n] = empty($text_colors[$n]) ? '#ffffff' : $text_colors[$n]; ?>
	<div class="rsc-event-text-color rsc-event-inputs">
		<label><?php esc_html_e('Text', 'really-simple-calendar'); ?> : </label>
		<input type="color" name="<?php echo RS_CALENDAR; ?>_event_text_color[<?php echo $n; ?>]" value="<?php echo $text_colors[$n]; ?>" <?php echo !empty($attr) ? $attr : disabled(!empty($classes[$n])); ?>>
	</div>
	
	<?php $bg_colors[$n] = empty($bg_colors[$n]) ? '#999999' : $bg_colors[$n]; ?>
	<div class="rsc-event-bg-color rsc-event-inputs">
		<label><?php esc_html_e('BG', 'really-simple-calendar'); ?> : </label>
		<input type="color" name="<?php echo RS_CALENDAR; ?>_event_bg_color[<?php echo $n; ?>]" value="<?php echo $bg_colors[$n]; ?>" <?php echo !empty($attr) ? $attr : disabled(!empty($classes[$n])); ?>>
	</div>
</div>

<div class="rsc-event-unit">
	<div class="rsc-event-date rsc-event-inputs">
		<label><?php esc_html_e('Period', 'really-simple-calendar'); ?> : </label>
		<input type="date" name="<?php echo RS_CALENDAR; ?>_event_date[<?php echo $n; ?>]" value="<?php echo $dates[$n]; ?>" class="text" <?php echo $attr; ?>>
		<span style="padding: 0 10px"> ~ </span>
		<?php $lasts[$n] = empty($lasts[$n]) ? '' : $lasts[$n]; ?>
		<input type="date" name="<?php echo RS_CALENDAR; ?>_event_last[<?php echo $n; ?>]" value="<?php echo $lasts[$n]; ?>" class="text" <?php echo $attr; ?>>
	</div>
	
	<?php 
	$repeats[$n] = empty($repeats[$n]) ? array() : $repeats[$n]; 
	$repeats[$n] = !is_array($repeats[$n]) ? array($repeats[$n]) : $repeats[$n];
	?>
	<div class="rsc-event-repeat rsc-event-inputs">
		<label><?php esc_html_e('Repeat', 'really-simple-calendar'); ?> : </label>
		<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_repeat[<?php echo $n; ?>][]" value="" <?php echo $attr; ?>>
		
		<?php for($h=0; $h<7; $h++): ?>
		<label class="rsc-event-repeat-label"><input type="checkbox" name="<?php echo RS_CALENDAR; ?>_event_repeat[<?php echo $n; ?>][]" value="<?php echo $h; ?>" <?php checked(in_array((string)$h, $repeats[$n], true)); ?> <?php echo $attr; ?>><?php 
							$wd =$wp_locale->get_weekday_abbrev($wp_locale->get_weekday($h));
						  rsc_echo_esc($wd, 'really-simple-calendar'); 
						 ?></label>

		<?php endfor; ?>
	</div>
	
	<div class="rsc-event-exclude rsc-event-inputs">
		<div>
			<label><?php esc_html_e('Exclude', 'really-simple-calendar'); ?> : </label>
			<input type="date" class="rsc-event-exclude-date" data-time="<?php echo $n; ?>" <?php echo $attr; ?>>
		</div>
		<?php 
		$excludes[$n] = empty($excludes[$n]) ? array() : $excludes[$n]; 
		$excludes[$n] = !is_array($excludes[$n]) ? array($excludes[$n]) : $excludes[$n];
		?>
		<div class="rsc-event-exclude-list" <?php echo $attr; ?>>
			
		</div>
		
	</div>
	
</div>

