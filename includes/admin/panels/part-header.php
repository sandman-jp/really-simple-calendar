<form method="post" action="" class="rsc-calendar-form <?php echo $this->is_update ? 'rsc-ajax-update' : ''; ?>">
	<?php wp_nonce_field('rsc_'.$class, 'rsc_save_settings_nonce'); ?>
	<input type="hidden" name="rsc_setting" value="<?php echo $class; ?>">