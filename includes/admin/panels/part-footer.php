	<div class="rsc-form-buttons">
		<?php 
		
		if(!get_the_ID()){
			submit_button(); 
		}
		?>	
		<p class="submit"><button class="button rsc-reload-button"><?php esc_html_e('Reload preview', 'really-simple-calendar'); ?></button></p>
	</div>
</form>