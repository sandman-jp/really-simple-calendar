<?php
namespace sandmanjp\ReallySimpleCalendar\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\style')):
	
class style extends panel{
	
	public $name = 'style';
	
	public $fields = array(
		SMJP_RS_CALENDAR.'_style',
	);
	
	public $lock_fields = array(
		SMJP_RS_CALENDAR.'_style_lock',
	);
	
	function get_label(){
		return esc_html__('Style Sheet', 'really-simple-calendar');
	}
	
	function echo($settings){
		$lock_mode = 0;
		$class = $this->name;
	?>
	
	<?php do_action('smjp_rsc_before_view_settings'); ?>
	<table class="form-table rsc-table">
		
		<?php $is_locked_style = isset($settings[SMJP_RS_CALENDAR.'_style_lock']) ? $settings[SMJP_RS_CALENDAR.'_style_lock'] : 0; ?>
		<tr>
			<th scope="row">
				<?php smjp_rsc_param_lock(SMJP_RS_CALENDAR.'_style_lock', $is_locked_style); ?>
				
				<label for="rsc-calendar-style"><?php esc_html_e('Class Style', 'really-simple-calendar'); ?></label>
			</th>
			<td>
				<div class="rsc-calendar-style-area">
					<?php $is_locked_style = apply_filters('smjp_rsc_before_style_text', $is_locked_style); ?>
					<textarea name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_style" class="large-text code rsc-calendar-style-main" rows="10" <?php smjp_rsc_disabled($is_locked_style, true, $lock_mode); ?>><?php 
						if(!empty($settings[SMJP_RS_CALENDAR.'_style'])){
							echo esc_textarea($settings[SMJP_RS_CALENDAR.'_style']); 
						}else{
							echo esc_textarea(smjp_rsc_get_default_style());
						}
					?></textarea>
					<?php do_action('smjp_rsc_after_style_text', $settings); ?>
				</div>
			</td>
		</tr>
	</table>
	
	<?php
	}
	
}

endif;