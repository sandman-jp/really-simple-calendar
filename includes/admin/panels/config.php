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
		return __('Configuration', 'really-simple-calendar');
	}
	
	function echo($settings){
		
		$class = $this->name;
		
		$capabolity = !empty($settings[RS_CALENDAR.'_capability']) ? $settings[RS_CALENDAR.'_capability'] : $this->_default_capability;
		
		$roles = get_editable_roles();
	?>
	
	<h2><?php esc_html_e('Extentions Setting', 'really-simple-calendar'); ?></h2>
	<table class="form-table rsc-table">
		<tr>
			<th><?php esc_html_e('Advanced Event Field', 'really-simple-calendar'); ?></th>
			<td>
				<input type="hidden" name="<?php echo RS_CALENDAR; ?>_event_fields" value="simple">
				<input type="checkbox" name="<?php echo RS_CALENDAR; ?>_event_fields" value="advanced" <?php checked(get_option(RS_CALENDAR.'_event_fields'), 'advanced'); ?>>
			</td>
		</tr>
		<?php
		$extentions = get_option(RS_CALENDAR.'_extentions');
		?>
	</table>
	
	<h2><?php esc_html_e('Manage Capability', 'really-simple-calendar'); ?></h2>
	<table class="form-table rsc-table">

			<?php 
			$capabolity = apply_filters('rsc_get_user_capability', $capabolity);
			
			foreach($roles as $k=>$v){
				if('administrator' != $k){
					if(isset($capabolity[$k])){
						$cap = $capabolity[$k];
					}else{
						$cap = 'edit';
					}
			?>
				<tr>
					<th><?php echo translate_user_role($v['name']); ?></th>
					
					<td><label><select name="<?php echo RS_CALENDAR; ?>_capability[<?php echo $k; ?>]">
						<option value="full" <?php selected('full', $cap); ?>><?php esc_html_e('Full', 'really-simple-calendar'); ?></option>
						<option value="manage" <?php selected('manage', $cap); ?>><?php esc_html_e('Manage', 'really-simple-calendar'); ?></option>
						<option value="edit" <?php selected('edit', $cap); ?>><?php esc_html_e('Edit', 'really-simple-calendar'); ?></option>
						<option value="read" <?php selected('read', $cap); ?>><?php esc_html_e('Read', 'really-simple-calendar'); ?></option>
					</select></label></td>
					
				</tr>
			<?php 
				}
			}
			?>
			
	</table>

	<?php
	}
}

endif;
