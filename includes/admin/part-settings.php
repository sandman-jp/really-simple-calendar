<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php

$panel = '';

//get panels
foreach($this->panels as $p){
	if($panel_name == $p->get_name()){
		$this->fields = $p->get_fields();
		$this->lock_fields = $p->get_lock_fields();
		$panel = $p;
		continue;
	}
}

//load settings
$settings = $this->get_settings();

if(rsc_current_user_can('manage') && !isset($_POST['filter_action'])){
	//save settings
	if(isset($_POST['rsc_save_settings_nonce']) && wp_verify_nonce( $_POST['rsc_save_settings_nonce'], 'rsc_'.$panel_name)){
		$this->post_data = $_POST;
		$settings = $panel->save($settings, $this->post_data);
		
	}
}
if(!empty($panel->updated)){
	$is_updated = false;
	foreach($panel->updated as $k=>$v){
		if($panel->updated){
			$is_updated = true;
		}
	}
	if($is_updated){
		add_action('rsv_before_view_panel', function(){
			echo '<div class="updated"><p>'.__('Parameters are saved.', 'really-simple-calendar').'</p></div>';
		});
	}else{
		add_action('rsv_before_view_panel', function(){
			echo '<div class="error"><p>'.__('Parameters are\'nd saved.', 'really-simple-calendar').'</p></div>';
		});
	}
}
?>
<?php do_action('rsv_before_view_panel'); ?>

<nav class="nav-tab-wrapper">
	<?php 
	$admin_url = get_admin_url();
	$i = 0;
	foreach($this->panels as $p):
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
<?php include RSC_ADMIN_DIR_INCLUDES.'/panels/part-header.php'; ?>
<?php $panel->echo($settings); ?>
<?php include RSC_ADMIN_DIR_INCLUDES.'/panels/part-footer.php'; ?>
</section>
<hr>
