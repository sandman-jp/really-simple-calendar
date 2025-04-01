
<div class="rsc-event-unit">
	<div class="rsc-event-class rsc-event-inputs">
		<label><?php esc_html_e('Class', 'really-simple-calendar'); ?><span class="dashicons dashicons-editor-help" title="<?php esc_html_e('If this field is empty, the calendar will use text color and bg color.', 'really-simple-calendar'); ?>"></span> : </label>
		<input type="text" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_class[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($classes[$n]); ?>" class="short-text rsc-event-class" <?php rsc_echo_esc($attr); ?>>
	</div>
	
	<?php $text_colors[$n] = empty($text_colors[$n]) ? '#ffffff' : $text_colors[$n]; ?>
	<div class="rsc-event-text-color rsc-event-inputs">
		<label><?php esc_html_e('Text', 'really-simple-calendar'); ?> : </label>
		<input type="color" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_text_color[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($text_colors[$n]); ?>" <?php echo !empty($attr) ? $attr : disabled(!empty($classes[$n])); ?>>
	</div>
	
	<?php $bg_colors[$n] = empty($bg_colors[$n]) ? '#999999' : $bg_colors[$n]; ?>
	<div class="rsc-event-bg-color rsc-event-inputs">
		<label><?php esc_html_e('BG', 'really-simple-calendar'); ?> : </label>
		<input type="color" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_bg_color[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($bg_colors[$n]); ?>" <?php echo !empty($attr) ? $attr : disabled(!empty($classes[$n])); ?>>
	</div>
</div>

<div class="rsc-event-unit">
	<div class="rsc-event-date rsc-event-inputs">
		<label><?php esc_html_e('Period', 'really-simple-calendar'); ?> : </label>
		<input type="date" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_date[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($dates[$n]); ?>" class="text" <?php rsc_echo_esc($attr); ?>>
		<span style="padding: 0 10px"> ~ </span>
		<?php $lasts[$n] = empty($lasts[$n]) ? '' : $lasts[$n]; ?>
		<input type="date" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_last[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($lasts[$n]); ?>" class="text" <?php rsc_echo_esc($attr); ?>>
	</div>
	
	<?php $repeats[$n] = empty($repeats[$n]) ? array() : $repeats[$n]; ?>
	<div class="rsc-event-repeat rsc-event-inputs">
		<label><?php esc_html_e('Repeat', 'really-simple-calendar'); ?> : </label>
		<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_repeat[<?php rsc_echo_esc($n); ?>][]" value="">
		
		<?php for($h=0; $h<7; $h++): ?>
		<label class="rsc-event-repeat-label"><input type="checkbox" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_repeat[<?php rsc_echo_esc($n); ?>][]" value="<?php echo $h ?>" <?php checked(in_array((string)$h, $repeats[$n], true)); ?> <?php rsc_echo_esc($attr); ?>><?php esc_html_e($wp_locale->get_weekday_abbrev($wp_locale->get_weekday($h))); ?></label>

		<?php endfor; ?>
	</div>
	
	<div class="rsc-event-exclude rsc-event-inputs">
		<div>
			<label><?php esc_html_e('Exclude', 'really-simple-calendar'); ?> : </label>
			<input type="date" class="rsc-event-exclude-date" data-time="<?php rsc_echo_esc($n); ?>" <?php rsc_echo_esc($attr); ?>>
		</div>
		<?php $excludes[$n] = empty($excludes[$n]) ? array() : $excludes[$n]; ?>
		<div class="rsc-event-exclude-list" <?php rsc_echo_esc($attr); ?>>
			
		</div>
		
	</div>
	
</div>

