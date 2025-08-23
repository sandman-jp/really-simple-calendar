<?php

/* template */
function smjp_rsc_get_template_file_path($path){
	
	$roots = array(
		get_stylesheet_directory().'/rsc',
		get_template_directory().'/rsc',
		SMJP_RSC_DIR_TEMPLATES,
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

function smjp_rsc_get_template_part($path, $sub=null, $args=array()){
	
	if(!is_null($sub) ){
		if(is_array($sub)){
			$args = $sub;
			$sub = '';
		}else{
			$path .= '-'.$sub;
		}
	}
	
	$fullpath = smjp_rsc_get_template_file_path($path);
	
	if($fullpath){
		
		if(!empty($args)){
			extract($args);
		}
		
		include $fullpath;
	}
}

function smjp_rsc_get_lock($is_locked=false){
	if($is_locked):
	?>
	<span class="rsc-is-locked dashicons dashicons-lock" title="<?php esc_html_e('Locked', 'really-simple-calendar'); ?>"></span>
	<?php
	endif;
}

function smjp_rsc_param_lock($name, $is_locked=false){
	
	$is_disabled = apply_filters('smjp_rsc_is_disabled', false, $name);
	
	if(smjp_rsc_current_user_can('full') && !$is_disabled):
	?>
	<div class="rsc-setting-lock">
		<label class="rsc-check-locking">
			<span class="rsc-lock-inputs">
				<input type="hidden" name="<?php echo esc_attr($name); ?>" value="0">
				<input type="checkbox" name="<?php echo esc_attr($name); ?>" value="1" <?php checked( wp_unslash($is_locked)); ?>><?php esc_html_e('Lock this.', 'really-simple-calendar'); ?>
			</span>
			<span class="rsc-is-locked dashicons dashicons-lock" title="<?php esc_html_e('Locked', 'really-simple-calendar'); ?>"></span>
			<span class="rsc-is-unlocked dashicons dashicons-unlock" title="<?php esc_html_e('Unlocked', 'really-simple-calendar'); ?>"></span>
		</label>
	</div>
	<?php elseif((smjp_rsc_current_user_can('manage') && $is_locked) || $is_disabled): ?>
	<div class="rsc-setting-lock rsc-lock <?php echo esc_attr($is_disabled ? 'rsc-is-disabled' : ''); ?>">
		<label class="rsc-check-locking">
		<span class="rsc-is-locked dashicons dashicons-lock" title="<?php $is_disabled ? esc_html_e('Master Locked', 'really-simple-calendar') : esc_html_e('Locked', 'really-simple-calendar'); ?>"></span>
		</label>
	</div>
	<?php 
	endif;
}

function smjp_rsc_disabled($compare=true, $current=true, $name=null){
	
	$is_disabled = apply_filters('smjp_rsc_is_disabled', false, $name);
	
	if($is_disabled){
		disabled(wp_unslash($is_disabled), wp_unslash($current));
	}else{
		wp_readonly(wp_unslash($compare), wp_unslash($current));
	}
	
}

function smjp_rsc_get_esc($str, $quotes = true){
	
	$txt = wp_unslash($str);
	$txt = wp_strip_all_tags($txt);
	if($quotes){
		$txt = htmlspecialchars($txt, ENT_QUOTES);
	}else{
		$txt = htmlspecialchars($txt, ENT_NOQUOTES);
	}
	
	return $txt;
}
/*
function smjp_rsc_echo_esc($str){
	
	echo smjp_rsc_get_esc($str);
}

function smjp_rsc_remove_script_style($str){
	return preg_replace('@<(script|style)[^>]*?>.*?</\\1>@si', '', $str);
}
*/