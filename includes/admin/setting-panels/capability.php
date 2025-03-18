<?php
namespace RSC\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('RSC\Admin\capability')):
	
class capability extends panel{
	
	public $name = 'capability';
	public $label = 'Manage Capability';
	public $info = 'Admin Only';
	
	public $fields = array(
		'calendar_capability',
	);
	
	public $lock_fields = array();
	
	private $_default_capability;
	// private $_capabolity;
	
	function __construct(){
		
		$this->_default_capability = rsc_get_default_capabilities();
	}
	
	/*
	function get_user_capability($cap){
		
		$cap = $this->_default_capability;
		if(get_option('calendar_capability')){
			$cap = array_merge($this->_default_capability, get_option('calendar_capability'));
		}
		return $cap;
	}
	*/
	
	function echo($settings){
		
		$class = $this->name;
		
		$capabolity = !empty($settings['calendar_capability']) ? $settings['calendar_capability'] : $this->_default_capability;
		
		$roles = get_editable_roles();
	?>
	<?php include RSC_ADMIN_DIR_INCLUDES.'/setting-panels/part-header.php'; ?>
	
	<table class="form-table rsc-table">

			<?php 
			$capabolity = apply_filters('rsc_get_user_capability', $capabolity);
			foreach($roles as $k=>$v){
				if('administrator' != $k){
					$cap = $capabolity[$k];
			?>
				<tr>
					<th><?php echo translate_user_role($v['name']); ?></th>
					
					<td><label><select name="calendar_capability[<?php echo $k ?>]">
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
