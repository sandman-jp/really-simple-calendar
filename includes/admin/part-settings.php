<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php


if(!empty($this->_error_message)){
	echo '<section><div class="error"><p>'.esc_html($this->_error_message).'</p></div></section>';
	exit;
}

if(!empty($panel->updated)){
	$is_updated = false;
	foreach($panel->updated as $k=>$v){
		if($panel->updated){
			$is_updated = true;
		}
	}
	if($is_updated){
		add_action('smjp_rsc_before_view_panel', function(){
			echo '<div class="updated"><p>';
			esc_html_e('Parameters are saved.', 'really-simple-calendar');
			echo '</p></div>';
		});
	}else{
		add_action('smjp_rsc_before_view_panel', function(){
			echo '<div class="error"><p>';
			esc_html_e('Parameters are\'nd saved.', 'really-simple-calendar');
			echo '</p></div>';
		});
	}
}
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
		$target_url = $admin_url.'admin.php?page='.SMJP_RSC_GENERAL_SETTINGS_PAGE.'&rsc='.$p->get_name();
	?>
	<a 
		href="<?php echo esc_url($target_url); ?>" 
		class="nav-tab <?php echo esc_attr($active); ?>"
		>
		<?php echo esc_html($p->get_label()); ?><?php if(!empty($p->get_info())): ?><span class="dashicons dashicons-editor-help" title="<?php echo esc_attr($p->get_info()); ?>"></span><?php endif; ?>
	</a>
	<?php
		$i++;
	endforeach;
	?>
</nav>

<section id="<?php echo esc_attr($panel->get_name()); ?>-settings" class="rsc-setting-panel">
<?php include SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/part-header.php'; ?>
<?php $panel->echo($settings); ?>
<?php include SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/part-footer.php'; ?>
</section>
<hr>
