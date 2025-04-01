<?php 
global $wp_locale;
if(!isset($is_event_locked[$n])){
	$is_event_locked[$n] = 0;
}
$lock_name = RS_CALENDAR.'_event_lock['.$n.']';
$isnt_x = !($n=='x');
ob_start();
rsc_disabled($is_event_locked[$n], $isnt_x, $lock_name);
$attr = ob_get_clean();
?>
<tr data-time="<?php rsc_echo_esc($n); ?>">
	<?php if($num[$n]): ?>
	<td><input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_number[<?php rsc_echo_esc($n); ?>]" value="1" <?php rsc_echo_esc($attr); ?>><span class="rsc-col-index"><?php echo $i; ?></span></td>
	<td>
		<?php 
		if($isnt_x){
			rsc_param_lock($lock_name, $is_event_locked[$n]); 
		}else{
			rsc_param_lock('rs_calendar_event_lock[x]', false); 
		}
		?>
	</td>
	
	<td>
		
		<?php do_action('rsc_pre_event_column', $n); ?>
		
		<div class="rsc-event-upper">
			<?php do_action('rsc_pre_event_label'); ?>
			<div class="rsc-event-label rsc-event-inputs">
				<label><?php esc_html_e('Label', 'really-simple-calendar'); ?><span class="dashicons dashicons-editor-help" title="<?php esc_html_e('This is the name which will appear on pages.'); ?>"></span> : </label>
				<input type="text" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_label[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($labels[$n]); ?>" class="large-text" <?php rsc_echo_esc($attr); ?> required>
			</div>
			<?php do_action('rsc_after_event_label'); ?>
		</div>
		
		<div class="rsc-event-lower">
			<?php 
			$event_field_flag = get_option(RS_CALENDAR.'_event_fields', 'simple');
			include RSC_ADMIN_DIR_INCLUDES.'/panels/part-event-'.$event_field_flag.'.php';
			?>
			
		</div>
		
		<?php do_action('rsc_after_event_column', $n); ?>
	</td>
	<td>
		<div class="rsv-event-action">
			<button class="rsc-col-copy" <?php rsc_echo_esc($attr); ?>><span class="dashicons dashicons-admin-page"></span></button>
		</div>
		
		<div class="rsv-event-action">
			<button class="rsc-col-delete" <?php rsc_echo_esc($attr); ?>><span class="dashicons dashicons-dismiss"></span></button>
		</div>
	</td>
	<?php else: ?>
	
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_number[<?php rsc_echo_esc($n); ?>]" value="1" <?php rsc_echo_esc($attr); ?>>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_lock[<?php rsc_echo_esc($n); ?>]" value="<?php echo $is_event_locked[$n]; ?>" <?php rsc_echo_esc($attr); ?>>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_label[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($labels[$n]); ?>" <?php rsc_echo_esc($attr); ?>>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_class[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($classes[$n]); ?>" <?php rsc_echo_esc($attr); ?>>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_text_color[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($text_colors[$n]); ?>" <?php rsc_echo_esc($attr); ?>>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_bg_color[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($bg_colors[$n]); ?>" <?php rsc_echo_esc($attr); ?>>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_date[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($dates[$n]); ?>" <?php rsc_echo_esc($attr); ?>>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_last[<?php rsc_echo_esc($n); ?>]" value="<?php rsc_echo_esc($lasts[$n]); ?>" <?php rsc_echo_esc($attr); ?>>
	<?php 
	if(!empty($repeats[$n])):
		foreach($repeats[$n] as $rep): 
	?>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_repeat[<?php rsc_echo_esc($n); ?>][]" value="<?php echo $rep; ?>" <?php rsc_echo_esc($attr); ?>>
	<?php 
		endforeach; 
	endif;
	?>
	<?php 
	if(!empty($excludes[$n])):
		foreach($excludes[$n] as $ex): 
	?>
	<input type="hidden" name="<?php rsc_echo_esc(RS_CALENDAR); ?>_event_exclude[<?php rsc_echo_esc($n); ?>][]" value="<?php echo $ex ?>" <?php rsc_echo_esc($attr); ?>>
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