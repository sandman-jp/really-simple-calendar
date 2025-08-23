<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php 
global $wp_locale;
if(!isset($is_event_locked[$n])){
	$is_event_locked[$n] = 0;
}
$lock_name = SMJP_RS_CALENDAR.'_event_lock['.$n.']';
$isnt_x = !($n=='x');

ob_start();
smjp_rsc_disabled($is_event_locked[$n], $isnt_x, $lock_name);
$sanitized_attr = ob_get_clean();

?>
<tr data-time="<?php echo wp_unslash($n); ?>" <?php echo $sanitized_attr; ?>>
	<?php if($num[$n]): ?>
	<td><input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_number[<?php echo wp_unslash($n); ?>]" value="1" <?php echo $sanitized_attr; ?>><span class="rsc-col-index"><?php echo $i; ?></span></td>
	<td>
		<?php 
		if($isnt_x){
			smjp_rsc_param_lock($lock_name, $is_event_locked[$n]); 
		}else{
			smjp_rsc_param_lock('rs_calendar_event_lock[x]', false); 
		}
		?>
	</td>
	
	<td>
		
		<?php do_action('smjp_rsc_pre_event_column'); ?>
		
		<div class="rsc-event-upper">
			<?php do_action('smjp_rsc_pre_event_label', $n, $sanitized_attr); ?>
			<div class="rsc-event-label rsc-event-inputs">
				<label><?php esc_html_e('Label', 'really-simple-calendar'); ?><span class="dashicons dashicons-editor-help" title="<?php esc_html_e('This is the name which will appear on pages.', 'really-simple-calendar'); ?>"></span> : </label>
				<input type="text" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_label[<?php echo wp_unslash($n); ?>]" value="<?php echo esc_attr($labels[$n]); ?>" class="large-text" <?php echo $sanitized_attr; ?> required>
			</div>
			<?php do_action('smjp_rsc_after_event_label'); ?>
		</div>
		
		<div class="rsc-event-lower">
			<?php 
			$event_field_flag = get_option(SMJP_RS_CALENDAR.'_event_fields', 'simple');
			include SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/part-event-'.$event_field_flag.'.php';
			?>
			
		</div>
		
		<?php do_action('smjp_rsc_after_event_column', $n, $sanitized_attr); ?>
	</td>
	<td>
		<div class="rsv-event-action">
			<button class="rsc-col-copy" <?php echo $sanitized_attr; ?>><span class="dashicons dashicons-admin-page"></span></button>
		</div>
		
		<div class="rsv-event-action">
			<button class="rsc-col-delete" <?php echo $sanitized_attr; ?>><span class="dashicons dashicons-dismiss"></span></button>
		</div>
	</td>
	<?php else: ?>
	
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_number[<?php echo wp_unslash($n); ?>]" value="1" <?php echo $sanitized_attr; ?>>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_lock[<?php echo wp_unslash($n); ?>]" value="<?php echo esc_attr($is_event_locked[$n]); ?>" <?php echo $sanitized_attr; ?>>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_label[<?php echo wp_unslash($n); ?>]" value="<?php echo esc_attr($labels[$n]); ?>" <?php echo $sanitized_attr; ?>>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_class[<?php echo wp_unslash($n); ?>]" value="<?php echo esc_attr($classes[$n]); ?>" <?php echo $sanitized_attr; ?>>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_text_color[<?php echo wp_unslash($n); ?>]" value="<?php echo sanitize_hex_color($text_colors[$n]); ?>" <?php echo $sanitized_attr; ?>>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_bg_color[<?php echo wp_unslash($n); ?>]" value="<?php echo sanitize_hex_color($bg_colors[$n]); ?>" <?php echo $sanitized_attr; ?>>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_date[<?php echo wp_unslash($n); ?>]" value="<?php echo esc_attr($dates[$n]); ?>" <?php echo $sanitized_attr; ?>>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_last[<?php echo wp_unslash($n); ?>]" value="<?php echo esc_attr($lasts[$n]); ?>" <?php echo $sanitized_attr; ?>>
	<?php 
	if(!empty($repeats[$n])):
		foreach($repeats[$n] as $rep): 
	?>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_repeat[<?php echo wp_unslash($n); ?>][]" value="<?php echo esc_attr($rep); ?>" <?php echo $sanitized_attr; ?>>
	<?php 
		endforeach; 
	endif;
	?>
	<?php 
	if(!empty($excludes[$n])):
		foreach($excludes[$n] as $ex): 
	?>
	<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_exclude[<?php echo wp_unslash($n); ?>][]" value="<?php echo esc_attr($ex); ?>" <?php echo $sanitized_attr; ?>>
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