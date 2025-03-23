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
		return __('Style Sheet', RSC_TEXTDOMAIN);
	}
	
	function echo($settings){
		$class = $this->name;
	?>
	
	
	
	<?php include RSC_ADMIN_DIR_INCLUDES.'/panels/part-header.php'; ?>
	
	<?php do_action('rsc_before_view_settings'); ?>
	<table class="form-table rsc-table">
		
		<?php $is_locked_style = isset($settings[RS_CALENDAR.'_style_lock']) ? $settings[RS_CALENDAR.'_style_lock'] : 0; ?>
		<tr>
			<th scope="row">
				<div class="rsc-setting-lock">
					<?php rsc_param_lock(RS_CALENDAR.'_style_lock', $is_locked_style); ?>
				</div>
				
				<label for="rsc-calendar-style"><?php _e('Class Style', RSC_TEXTDOMAIN); ?></label>
			</th>
			<td>
				<textarea name="<?php echo RS_CALENDAR; ?>_style" class="large-text code" rows="10" <?php wp_readonly($is_locked_style); ?>>
<?php 
if(!empty($settings[RS_CALENDAR.'_style'])){
	echo rsc_esc($settings[RS_CALENDAR.'_style']); 
}else{
	echo rsc_get_default_style();
}
?>
				</textarea>
			</td>
		</tr>
	</table>
	
	<?php include RSC_ADMIN_DIR_INCLUDES.'/panels/part-footer.php'; ?>
	<?php
	}
	
}

endif;