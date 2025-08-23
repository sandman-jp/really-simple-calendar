<?php
namespace sandmanjp\ReallySimpleCalendar\Admin\Panel;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\config')):
	
class config extends panel{
	
	public $name = 'config';
	public $info = 'Admin Only';
	
	public $fields = array(
		SMJP_RS_CALENDAR.'_event_fields',
		SMJP_RS_CALENDAR.'_extensions',
		SMJP_RS_CALENDAR.'_capability',
	);
	
	public $lock_fields = array();
	
	private $_default_capability;
	
	public $use_preview = false;
	
	function __construct(){
	
		$this->_default_capability = smjp_rsc_get_default_capabilities();
	}
	
	function get_label(){
		return esc_html__('Configuration', 'really-simple-calendar');
	}
	
	function echo($settings){
		
		$class = $this->name;
		
		$capabolity = !empty($settings[SMJP_RS_CALENDAR.'_capability']) ? $settings[SMJP_RS_CALENDAR.'_capability'] : $this->_default_capability;
		
		$roles = get_editable_roles();
	?>
	
	<h2><?php esc_html_e('Extension Settings', 'really-simple-calendar'); ?></h2>
	<table class="form-table rsc-table">
		<?php $extenstions = get_option(SMJP_RS_CALENDAR.'_extensions'); ?>
		<tr>
			<th><label for="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_fields"><?php esc_html_e('Advanced Event Field', 'really-simple-calendar'); ?></label></th>
			<td>
				<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_fields" value="simple">
				<input 
					type="checkbox" 
					id="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_fields" 
					name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_event_fields" 
					value="advanced" 
					<?php checked(get_option(SMJP_RS_CALENDAR.'_event_fields'), 'advanced'); ?>
					>
			</td>
		</tr>
		
		<tr>
			<th><label for="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions_multi-calendar"><?php esc_html_e('Multi Calendar', 'really-simple-calendar'); ?></label></th>
			<td>
				<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions[multi-calendar]" value="">
				<input 
					type="checkbox" 
					id="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions_multi-calendar" 
					name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions[multi-calendar]" 
					value="1"
					<?php isset($extenstions['multi-calendar']) ? checked($extenstions['multi-calendar'], '1') : ''; ?>
					>
				<!-- <small>(Comming soon)</small> -->
			</td>
		</tr>
		
		<tr>
			<th><label for="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions_custom-label"><?php esc_html_e('Custom Label', 'really-simple-calendar'); ?></label></th>
			<td>
				<input type="hidden" name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions[custom-label]" value="" disabled>
				<input 
					type="checkbox" 
					id="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions_custom-label"
					name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_extensions[custom-label]" 
					value="1" 
					disabled
					<?php /* isset($extenstions['custom-label']) ? checked($extenstions['custom-label'], '1') : ''; */ ?>
					>
				<small>(Comming soon)</small>
			</td>
		</tr>
		
	</table>
	
	<h2><?php esc_html_e('Manage Capability', 'really-simple-calendar'); ?></h2>
	<table class="form-table rsc-table-list rsc-table-each">

			<?php 
			$capabolity = apply_filters('smjp_rsc_get_user_capability', $capabolity);
			$sections = array('rsc'=>'Really Simple Calendar');
			$sections = apply_filters('smjp_rsc_capability_section', $sections);
			?>
			<tr>
				<th></th>
				<?php foreach($sections as $k=>$v): ?>
				<th><?php echo $v; ?></th>
				<?php endforeach; ?>
			</tr>
			<?php
			foreach($roles as $k=>$v){
				if('administrator' != $k){
			?>
				<tr>
					<th><?php echo esc_html(translate_user_role($v['name'])); ?></th>
					<?php 
					foreach($sections as $kk=>$vv): 
						if(isset($capabolity[$kk][$k])){
							$cap = $capabolity[$kk][$k];
						}else{
							$cap = 'edit';
						}
					?>
					<td><label><select name="<?php echo esc_attr(SMJP_RS_CALENDAR); ?>_capability[<?php echo esc_attr($kk); ?>][<?php echo esc_attr($k); ?>]">
						<option value="full" <?php selected('full', $cap); ?>><?php esc_html_e('Full', 'really-simple-calendar'); ?></option>
						<option value="manage" <?php selected('manage', $cap); ?>><?php esc_html_e('Manage', 'really-simple-calendar'); ?></option>
						<option value="edit" <?php selected('edit', $cap); ?>><?php esc_html_e('Edit', 'really-simple-calendar'); ?></option>
						<option value="read" <?php selected('read', $cap); ?>><?php esc_html_e('Read', 'really-simple-calendar'); ?></option>
					</select></label></td>
					<?php endforeach; ?>
					
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
