	<div class="rsc-form-buttons">
		<?php 
		
		if(!get_the_ID()){
			submit_button(); 
		}
		?>	
		<p class="submit"><button class="button rsc-reload-button"><?php _e('Reload preview', RSC_TEXTDOMAIN); ?></button></p>
	</div>
</form>