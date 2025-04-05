	<div class="rsc-form-buttons">
		<?php 
		
		if(!get_the_ID() && $panel->use_save){
			submit_button(); 
		}
		if($panel->use_preview):
		?>	
		<p class="submit"><button class="button rsc-reload-button"><?php esc_html_e('Reload preview', 'really-simple-calendar'); ?></button></p>
		<?php endif; ?>
	</div>
</form>