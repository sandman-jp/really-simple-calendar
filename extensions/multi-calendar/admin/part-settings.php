<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php

$panel = '';
//get panels
foreach($this->panels as $p){
	$this->fields = array_merge($this->fields, $p->get_fields());
	$this->lock_fields = array_merge($this->lock_fields, $p->get_lock_fields());
	
	if($panel_name == $p->get_name()){
		$panel = $p;
	}
}

//load default settings
$settings = $this->get_settings();
$settings = smjp_rsc_merge_params($settings, $this->lock_fields);

$settings[SMJP_RS_CALENDAR.'_style'] = get_option(SMJP_RS_CALENDAR.'_style');
?>
<?php do_action('smjp_rsc_before_view_panel'); ?>

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
	<a href="#<?php echo $p->get_name(); ?>-settings" class="nav-tab <?php echo $active; ?>">
		<?php echo $p->get_label(); ?><?php if(!empty($p->get_info())): ?><span class="dashicons dashicons-editor-help" title="<?php echo $p->get_info(); ?>"></span><?php endif; ?>
	</a>
	<?php
		$i++;
	endforeach;
	?>
</nav>

<?php foreach($this->panels as $p): ?>

	<section id="<?php echo $p->get_name(); ?>-settings" class="rsc-setting-panel">
	<?php $p->echo($settings); ?>
	</section>
	
<?php endforeach; ?>

<hr>
