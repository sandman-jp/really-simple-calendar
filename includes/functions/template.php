<?php

/* template */
function rsc_get_template_file_path($path){
	
	$roots = array(
		get_stylesheet_directory().'/rsc',
		get_template_directory().'/rsc',
		RSC_DIR_TEMPLATES,
	);
	
	$fullpath = '';
	foreach($roots as $root){
		$fullpath = $root.$path.'.php';
		
		if(file_exists($fullpath)){
			return $fullpath;
		}
	}
	return false;
}

function rsc_get_template_part($path, $sub=null, $args=array()){
	
	if(!is_null($sub) ){
		if(is_array($sub)){
			$args = $sub;
			$sub = '';
		}else{
			$path .= '-'.$sub;
		}
	}
	
	$fullpath = rsc_get_template_file_path($path);
	
	if($fullpath){
		
		if(!empty($args)){
			extract($args);
		}
		
		include $fullpath;
	}
}

function rsc_get_lock($is_locked=false){
	if($is_locked):
	?>
	<span class="rsc-is-locked dashicons dashicons-lock" title="<?php _e('Locked'); ?>"></span>
	<?php
	endif;
}

function rsc_param_lock($name, $is_locked=false){
	
	$is_disabled = apply_filters('rsc_is_disabled', false, $name);
	
	if(rsc_current_user_can('full') && !$is_disabled):
	?>
	<div class="rsc-setting-lock">
		<label class="rsc-check-locking">
			<span class="rsc-lock-inputs">
				<input type="hidden" name="<?php echo $name ?>" value="0">
				<input type="checkbox" name="<?php echo $name; ?>" value="1" <?php checked($is_locked); ?>><?php _e('Lock this.', RSC_TEXTDOMAIN); ?>
			</span>
			<span class="rsc-is-locked dashicons dashicons-lock" title="<?php _e('Locked'); ?>"></span>
			<span class="rsc-is-unlocked dashicons dashicons-unlock" title="<?php _e('Unlocked'); ?>"></span>
		</label>
	</div>
	<?php elseif((rsc_current_user_can('manage') && $is_locked) || $is_disabled): ?>
	<div class="rsc-setting-lock rsc-lock <?php echo $is_disabled ? 'rsc-is-disabled' : '' ?>">
		<label class="rsc-check-locking">
		<span class="rsc-is-locked dashicons dashicons-lock" title="<?php $is_disabled ? _e('Master Locked') : _e('Locked'); ?>"></span>
		</label>
	</div>
	<?php 
	endif;
}

function rsc_disabled($compare=true, $current=true, $name=null){
	
	$is_disabled = apply_filters('rsc_is_disabled', false, $name);
	
	if($is_disabled){
		disabled($is_disabled, $current);
	}else{
		wp_readonly($compare, $current);
	}
	
}

function rsc_esc($str){
	$txt = wp_unslash($str);
	$txt = strip_tags($txt);
	$txt = htmlspecialchars($txt, ENT_QUOTES);
	
	return $txt;
}