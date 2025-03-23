<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\config')):
	
class config extends panel{
	
	public $name = 'config';
	public $info = 'Admin Only';
	
	public $fields = array(
		RS_CALENDAR.'_event_fields',
		RS_CALENDAR.'_capability',
	);
	
	public $lock_fields = array();
	
	private $_default_capability;
	
	public $use_preview = false;
	
	function __construct(){
		
		$this->_default_capability = rsc_get_default_capabilities();
	}
	
	function get_label(){
		return __('Configuration', RSC_TEXTDOMAIN);
	}
	
	function echo($settings){
		
		$class = $this->name;
		
		$capabolity = !empty($settings[RS_CALENDAR.'_capability']) ? $settings[RS_CALENDAR.'_capability'] : $this->_default_capability;
		
		$roles = get_editable_roles();
	?>
	<?php include RSC_ADMIN_DIR_INCLUDES.'/panels/part-header.php'; ?>
	
	<h2><?php _e('Extentions Setting', RSC_TEXTDOMAIN); ?></h2>
	<table class="form-table rsc-table">
		<tr>
			<th><?php _e('Advanced Event Field', RSC_TEXTDOMAIN); ?></th>
			<td>
				<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_fields" value="simple">
				<input type="checkbox" name="<?php echo RS_CALENDAR; ?>_event_fields" value="advanced" <?php checked(get_option(RS_CALENDAR.'_event_fields'), 'advanced'); ?>>
			</td>
		</tr>
	</table>
	
	<h2><?php _e('Manage Capability', RSC_TEXTDOMAIN); ?></h2>
	<table class="form-table rsc-table">

			<?php 
			$capabolity = apply_filters('rsc_get_user_capability', $capabolity);
			foreach($roles as $k=>$v){
				if('administrator' != $k){
					$cap = $capabolity[$k];
			?>
				<tr>
					<th><?php echo translate_user_role($v['name']); ?></th>
					
					<td><label><select name="<?php echo RS_CALENDAR; ?>_capability[<?php echo $k ?>]">
						<option value="full" <?php selected('full', $cap); ?>><?php _e('Full', RSC_TEXTDOMAIN); ?></option>
						<option value="manage" <?php selected('manage', $cap); ?>><?php _e('Manage', RSC_TEXTDOMAIN); ?></option>
						<option value="edit" <?php selected('edit', $cap); ?>><?php _e('Edit', RSC_TEXTDOMAIN); ?></option>
						<option value="read" <?php selected('read', $cap); ?>><?php _e('Read', RSC_TEXTDOMAIN); ?></option>
					</select></label></td>
					
				</tr>
			<?php 
				}
			}
			?>
			
	</table>
	
	<?php submit_button(); ?>	
</form>
	<?php
	}
}

endif;
