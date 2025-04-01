<form method="post" action="" class="rsc-calendar-form <?php echo $panel->is_update ? 'rsc-ajax-update' : ''; ?>">
	<?php wp_nonce_field('rsc_'.$panel->name, 'rsc_save_settings_nonce'); ?>
	<input type="hidden" name="rsc_setting" value="<?php echo $panel->name; ?>">