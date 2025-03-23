<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php

$panel = '';
$fields = array();
//get panels
foreach($this->_panels as $p){
	if($panel_name == $p->get_name()){
		$fields = $p->get_fields();
		$lock_fields = $p->get_lock_fields();
		$panel = $p;
		continue;
	}
}

//load settings
$settings = rsc_get_calendar_params();

foreach($fields as $key){
	if(!isset($settings[$key])){
		$op = get_option($key);
		if($op){
			//overwrite by return value
			$settings[$key] = $op;
		}else if(!isset($settings[$key])){
			//empty value 
			$settings[$key] = '';
		}
	}
}

if(rsc_current_user_can('manage') && !isset($_POST['filter_action'])){
	//save settings
	if(isset($_POST['rsc_save_settings_nonce']) && wp_verify_nonce( $_POST['rsc_save_settings_nonce'], 'rsc_'.$panel_name)){
		
		$settings = $panel->save($settings);
		
	}
}

?>

<nav class="nav-tab-wrapper">
	<?php 
	$admin_url = get_admin_url();
	$i = 0;
	foreach($this->_panels as $p):
		$active = '';
		if($p == $panel){
			$active = 'nav-tab-active';
		}
	?>
	<a href="<?php echo $admin_url.'admin.php?page='.RSC_GENERAL_SETTINGS_PAGE.'&rsc='.$p->get_name(); ?>" class="nav-tab <?php echo $active; ?>">
		<?php echo $p->get_label(); ?><?php if(!empty($p->get_info())): ?><span class="dashicons dashicons-editor-help" title="<?php echo $p->get_info(); ?>"></span><?php endif; ?>
	</a>
	<?php
		$i++;
	endforeach;
	?>
</nav>

<section id="<?php echo $panel->get_name(); ?>-settings" class="rsc-setting-panel">
<?php $panel->echo($settings); ?>
</section>
<hr>
