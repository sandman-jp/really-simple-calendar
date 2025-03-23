<?php 
global $wp_locale;
if(!isset($is_event_locked[$n])){
	$is_event_locked[$n] = 0;
}
?>
<tr data-time="<?php echo $n; ?>">
	<?php if($num[$n]): ?>
	<td><input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_number[<?php echo $n; ?>]" value="1"><span class="rsc-col-index"><?php echo $i; ?></span></td>
	<td>
		<?php rsc_param_lock(RS_CALENDAR.'_event_lock['.$n.']', $is_event_locked[$n]); ?>
	</td>
	
	<td>
		
		<?php do_action('rsv_pre_event_column', $n); ?>
		
		<div class="rsc-event-upper">
			<?php do_action('rsv_pre_event_label'); ?>
			<div class="rsc-event-label rsc-event-inputs">
				<label><?php _e('Label', RSC_TEXTDOMAIN); ?><span class="dashicons dashicons-editor-help" title="<?php _e('This is the name which will appear on pages.'); ?>"></span> : </label>
				<input type="text" name="<?php echo RS_CALENDAR; ?>_event_label[<?php echo $n; ?>]" value="<?php echo rsc_esc($labels[$n]); ?>" class="large-text" <?php wp_readonly($is_event_locked[$n]); ?> required>
			</div>
			<?php do_action('rsv_after_event_label'); ?>
		</div>
		
		<div class="rsc-event-lower">
			<?php 
			$event_field_flag = get_option(RS_CALENDAR.'_event_fields', 'simple');
			include RSC_ADMIN_DIR_INCLUDES.'/panels/part-event-'.$event_field_flag.'.php';
			?>
			
		</div>
		
		<?php do_action('rsv_after_event_column', $n); ?>
	</td>
	<td>
		<div class="rsv-event-action">
			<button class="rsc-col-copy" <?php wp_readonly($is_event_locked[$n]); ?>><span class="dashicons dashicons-admin-page"></span></button>
		</div>
		
		<div class="rsv-event-action">
			<button class="rsc-col-delete" <?php wp_readonly($is_event_locked[$n]); ?>><span class="dashicons dashicons-dismiss"></span></button>
		</div>
	</td>
	<?php else: ?>
	
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_number[<?php echo $n; ?>]" value="1">
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_lock[<?php echo $n; ?>]" value="<?php echo $is_event_locked[$n]; ?>">
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_label[<?php echo $n; ?>]" value="<?php echo rsc_esc($labels[$n]); ?>">
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_class[<?php echo $n; ?>]" value="<?php echo rsc_esc($classes[$n]); ?>">
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_text_color[<?php echo $n; ?>]" value="<?php echo rsc_esc($text_colors[$n]); ?>">
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_bg_color[<?php echo $n; ?>]" value="<?php echo rsc_esc($bg_colors[$n]); ?>">
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_date[<?php echo $n; ?>]" value="<?php echo rsc_esc($dates[$n]); ?>">
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_last[<?php echo $n; ?>]" value="<?php echo rsc_esc($lasts[$n]); ?>">
	<?php 
	if(!empty($repeats[$n])):
		foreach($repeats[$n] as $rep): 
	?>
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_repeat[<?php echo $n; ?>][]" value="<?php echo $rep; ?>">
	<?php 
		endforeach; 
	endif;
	?>
	<?php 
	if(!empty($excludes[$n])):
		foreach($excludes[$n] as $ex): 
	?>
	<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_exclude[<?php echo $n; ?>][]" value="<?php echo $ex ?>">
	<?php 
		endforeach; 
	endif;
	?>
	
	
	<?php endif; ?>
</tr>

<?php
unset($num[$n]);
unset($is_event_locked[$n]);
unset($labels[$n]);
unset($classes[$n]);
unset($dates[$n]);
unset($lasts[$n]);
unset($repeats[$n]);
unset($excludes[$n]);
unset($text_colors[$n]);
unset($bg_colors[$n]);
?>