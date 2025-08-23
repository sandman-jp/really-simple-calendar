<?php
namespace sandmanjp\ReallySimpleCalendar\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if(!class_exists('sandmanjp\ReallySimpleCalendar\Admin\mc_setting')):
	
class mc_setting extends setting{
	
	private $_is_rsc_post = false;
	
	private $_panels = array();
	private $_panel_classes;
	
	private $_settings = array();
	private $_post_data = array();
	
	public $panel = null;
	
	public $is_style_lock = false;
	
	function __construct(){
		
		if(!is_admin()){
			return ;
		}
		$this->_panel_classes = SMJP_RSC()->get_panels();
		
		
		add_action('current_screen', array($this, 'init'));
		add_action('edit_form_after_editor', array($this, 'settings'));
		add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 11);
		add_action('admin_footer', array($this, 'in_footer_scripts'), 20);
		
		add_action('save_post', array($this, 'save_calendar'), 10, 3);
	}
	
	function init(){
		$screen = get_current_screen();
		
		if($screen->id != SMJP_RS_CALENDAR || $screen->base != 'post'){
			return;
		}
		$this->_is_rsc_post = true;
		
		//get manage panels
		$this->_panel_classes = apply_filters('smjp_rsc_get_multi_post_panel', $this->_panel_classes);
		
		//switch setting class
		add_filter('smjp_rsc_get_admin_setting', array($this, 'get_setting'));
		add_filter('smjp_rsc_is_disabled', array($this, 'is_disabled'), 10, 2);
		add_filter('smjp_rsc_get_event_serch', array($this, 'get_event_search'));
		add_filter('smjp_rsc_locked_fields', array($this, 'locked_fields'));
		add_action('smjp_rsc_get_default_shortcode', array($this, 'get_default_shortcode'));
	
		//style
		add_filter('smjp_rsc_get_panel_fields', array($this, 'add_post_style_field'));
		add_filter('smjp_rsc_option_fields', array($this, 'add_post_style_field'));
		add_filter('smjp_rsc_before_style_text', array($this, 'locking_main_style_text'));
		add_action('smjp_rsc_after_style_text', array($this, 'append_style_text'));
	}
	
	function admin_enqueue_scripts(){
		
		if(!$this->_is_rsc_post){
			return;
		}
		wp_enqueue_style('rsc-mc-admin', SMJP_RSC_MC_URL.'/assets/rsc-mc.css', array(), SMJP_RSC_VIRSION);
		wp_enqueue_script('rsc-mc-admin', SMJP_RSC_MC_URL.'/assets/rsc-mc.js', array('jquery'), SMJP_RSC_VIRSION, true);
	}
	
	function in_footer_scripts(){
		if(!$this->_is_rsc_post){
			return;
		}
		//先に出力
		wp_print_footer_scripts();
		
		$output = 'SMJP_RSC.SMJP_RSC_GET_CALENDAR_BY_SHORTCODE = "smjp_rsc_mc_get_calendar_by_shortcode"';
		wp_print_inline_script_tag( $output, array(
			'id' => 'smjp_rsc_mc_shortcode_ajax', 
		));
		
	
	}
	
	function get_setting($setting){
		return $this;
	}
	
	function is_disabled($is_disabled, $name){
		
		if($is_disabled){
			return $is_disabled;
		}
		
		if(strpos($name, SMJP_RS_CALENDAR.'_event_lock') !== false && $name != SMJP_RS_CALENDAR.'_event_lock[x]'){
			preg_match("/.+?\[(\d+)\]/", $name, $m);
			$defaults = get_option(SMJP_RS_CALENDAR.'_event_number');
			// var_dump($name);
			return isset($defaults[$m[1]]);
		}
		
		//get lock signup_nonce_fields()
		$panels = SMJP_RSC_ADMIN()->get_panels();
		$lock_fields = array();
		
		foreach($panels as $panel){
			$lock_fields = array_merge($lock_fields, $panel->get_lock_fields());
		}
		
		if(in_array($name, $lock_fields)){
			$is_disabled = get_option($name);
		}
		
		
		return $is_disabled;
	}
	
	function settings($post){
		if(get_post_type() != SMJP_RS_CALENDAR){
			return;
		}
		$panels = apply_filters('smjp_rsc_get_setting_panels', $this->_panel_classes);
		// var_dump($panels);
		foreach($panels as $panel){
			$this->add_setting_panel($panel);
		}
		
		?>
		<?php wp_nonce_field(SMJP_RSC_MC, SMJP_RSC_.'save_settings_nonce'); ?>
		<?php wp_nonce_field(SMJP_RSC_.'multi', SMJP_RSC_.'preview_settings_nonce', false); ?>
		<input type="hidden" id="<?php echo esc_attr(SMJP_RSC_); ?>setting" name="<?php echo esc_attr(SMJP_RSC_); ?>setting" value="multi">
		
		<div class="wrap">
		
		<div class="rsc-general-setting">
		
		<?php
		$options = array();
		// $panel = null;
		$panel_name = 'view';
		
		//get panels
		if(empty($this->panels)){
			echo '<section><div class="error"><p>Can\'t to find any panels.</p></div></section>';
			exit;
		}
		?>
		
		<?php 
		if(smjp_rsc_current_user_can('manage', 'mc')):
			include_once SMJP_RSC_MC_DIR.'/admin/part-settings.php';
		?>
		<p class="submit"><button class="button rsc-reload-button"><?php esc_html_e('Reload preview', 'really-simple-calendar'); ?></button></p>
		
		<?php endif; ?>
		
		<?php
		$this->panel = $this->get_panel($panel_name);
		?>
		
		<!-- shortcode -->
		<?php 
		if(smjp_rsc_current_user_can('edit', 'mc')){
			include_once SMJP_RSC_ADMIN_DIR_INCLUDES.'/panels/part-shortcode.php'; 
		}
		?>
		
		<?php if($this->panel->use_preview): ?>
		<!-- preview -->
		<h2><?php esc_html_e('Preview', 'really-simple-calendar'); ?></h2>
		<div class="rsc-preview">
			<div id="rsc-calendar-message"></div>
			<section id="rsc-calendar-wrap">
				<?php smjp_rsc_get_calendar(); ?>
			</section>
		</div>
		<?php endif; ?>
		
		</div>
		</div>
		<?php
	}
	
	function get_event_search($search){
		ob_start();
		?>
		<div class="tablenav top">
			<div class="alignright actions">
				<label><input id="rsc-hide-general-events" type="checkbox"> <?php esc_html_e('Hide general events.', 'really-simple-calendar'); ?></label>
			</div>
		</div>
		<?php
		
		return ob_get_clean();
	}
	
	function locked_fields($locked){
		
		$calendar_id = get_the_ID();
		
		if($calendar_id && is_array($locked)){
			foreach($locked as $k=>$v){
				if(empty($v)){
					$locked[$k] = get_post_meta($calendar_id, $k, true);
				}
			}
		}
		
		return $locked;
	}
	
	function get_default_shortcode($val){
		
		return '[rsc id="'.get_the_ID().'"]';
	}
	
	function save_calendar($post_id, $post_obj, $is_update){
		global $post;
		$tmp = false;
		if(!empty($post)){
			$tmp = $post;
		}
		
		$post = $post_obj;
		setup_postdata($post);
		
		if(smjp_rsc_current_user_can('manage', 'mc') && $post->post_type == SMJP_RS_CALENDAR){
			
			
			$post_settings = false;
			//$post_id = get_the_ID();
			//save settings
			if($post_id){
				$post_content = json_decode($post->post_content, true);
				
				if(!empty($post_content) && is_array($post_content)){
					
					$this->_post_data = array();
					$post_settings = $post_content['data'];
					$settings = $this->get_settings();
					
					for($i=0; $i < count($post_settings); $i++){
						
						$ps = $post_settings[$i];
						$key = $ps['name'];
						$val = $ps['value'];
						
						if(preg_match('/^('.SMJP_RS_CALENDAR.'_event_.+)?\[(\d+)\](\[\])?$/', $key, $m)){
							//イベントの場合
							$k = $m[1];
							
							if(!isset($this->_post_data[$k])){
								$this->_post_data[$k] = array();
							}
							if(isset($m[3])){
								//配列なら(repeats & excludes)
								if(!isset($this->_post_data[$k][$m[2]])){
									$this->_post_data[$k][$m[2]] = array();
								}
								$this->_post_data[$k][$m[2]][] = $val;
								
							}else{
								$this->_post_data[$k][$m[2]] = $val;
							}
						}else{
							//イベント以外
							$this->_post_data[$key] = $val;
						}
						
					}
					// var_dump($this->_post_data);
					
					$panels = SMJP_RSC_ADMIN()->get_panels();
					foreach($panels as $p){
						$p->save($settings, $this->_post_data);
					}
				}
				
			}
			
		}
		
		if(!empty($tmp)){
			wp_reset_postdata();
		}
	}
	
	function add_post_style_field($fields){
		$fields = array_diff($fields, array(SMJP_RS_CALENDAR.'_style'));
		$fields[] = SMJP_RS_CALENDAR.'_style_post';
		return $fields;
	}
	
	function locking_main_style_text($is_locked_style){
		$this->is_style_lock = $is_locked_style;
		return 1;
	}
	function append_style_text($settings){
		$lock_mode = 0;
		$is_locked_style = $this->is_style_lock;
		include SMJP_RSC_MC_DIR.'/admin/part-style.php';
	}
}

endif;