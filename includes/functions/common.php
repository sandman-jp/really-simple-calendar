<?php
//
function RSC(){
	global $RSC;
	
	if(!$RSC){
		$RSC = new CustomFieldsCalendar();
	}
	
	return $RSC;
}

function rsc_get_default_capabilities(){
	return array(
		'editor' => 'manage',
		'author' => 'edit',
		'contributor' => 'edit',
		'subscriber' => 'read',
	);
}

function rsc_current_user_can($cap){
	
	$user_role = wp_get_current_user()->roles[0];
	if('administrator' == $user_role){
		return true;
	}
	
	$caps = get_option('calendar_capability', rsc_get_default_capabilities());
	$capabilities = array(
		'full' => array('full', 'manage', 'edit', 'read'),
		'manage' => array('manage', 'edit', 'read'),
		'edit' => array('edit', 'read'),
		'read' => array('read'),
	);
	
	$role = $caps[$user_role];
	
	if(isset($caps[$user_role]) && in_array($cap, $capabilities[$role])){
		return true;
	}
	
	return false;
}

function rsc_get_lock($is_locked=false){
	if($is_locked):
	?>
	<span class="rsc-is-locked dashicons dashicons-lock" title="<?php _e('Locked'); ?>"></span>
	<?php
	endif;
}

function rsc_param_lock($name, $is_locked=false){
	if(rsc_current_user_can('full')):
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
	<?php elseif(rsc_current_user_can('manage') && $is_locked): ?>
	<div class="rsc-setting-lock">
		<label class="rsc-check-locking">
		<span class="rsc-is-locked dashicons dashicons-lock" title="<?php _e('Locked'); ?>"></span>
		</label>
	</div>
	<?php 
	endif;
}

function rsc_esc($str){
	$txt = wp_unslash($str);
	$txt = strip_tags($txt);
	$txt = htmlspecialchars($txt, ENT_QUOTES);
	
	return $txt;
}