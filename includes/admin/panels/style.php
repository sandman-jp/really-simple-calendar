<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\style')):
	
class style extends panel{
	
	public $name = 'style';
	
	public $fields = array(
		RS_CALENDAR.'_style',
	);
	
	public $lock_fields = array(
		RS_CALENDAR.'_style_lock',
	);
	
	function get_label(){
		return __('Style Sheet', 'really-simple-calendar');
	}
	
	function echo($settings){
		$lock_mode = 0;
		$class = $this->name;
	?>
	
	<?php do_action('rsc_before_view_settings'); ?>
	<table class="form-table rsc-table">
		
		<?php $is_locked_style = isset($settings[RS_CALENDAR.'_style_lock']) ? $settings[RS_CALENDAR.'_style_lock'] : 0; ?>
		<tr>
			<th scope="row">
				<?php rsc_param_lock(RS_CALENDAR.'_style_lock', $is_locked_style); ?>
				
				<label for="rsc-calendar-style"><?php esc_html_e('Class Style', 'really-simple-calendar'); ?></label>
			</th>
			<td>
				<textarea name="<?php echo RS_CALENDAR; ?>_style" class="large-text code" rows="10" <?php rsc_disabled($is_locked_style, true, $lock_mode); ?>><?php 
					if(!empty($settings[RS_CALENDAR.'_style'])){
						rsc_echo_esc($settings[RS_CALENDAR.'_style']); 
					}else{
						echo rsc_get_default_style();
					}
				?></textarea>
			</td>
		</tr>
	</table>
	
	<?php
	}
	
}

endif;