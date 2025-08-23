<textarea name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_style_post" class="large-text code rsc-calendar-style-post" rows="10" <?php smjp_rsc_disabled($is_locked_style, true, $lock_mode); ?>><?php 
	if(!empty($settings[SMJP_RS_CALENDAR.'_style_post'])){
		echo esc_textarea($settings[SMJP_RS_CALENDAR.'_style_post']); 
	}
?></textarea>