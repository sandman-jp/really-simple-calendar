<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>


<?php
if(isset($_GET['rsc'])){
	$panel_name = $_GET['rsc'];
	
}else{
	$panel_name = 'bulk';
}

//get panels
foreach($this->_panels as $p){
	if($panel_name == $p->get_name()){
		$fields = $p->get_fields();
		$lock_fields = $p->get_lock_fields();
		continue;
	}
}

//load settings
$settings = rsc_get_calendar_params();

foreach($fields as $key){
	if(!isset($settings[$key])){
		$op = get_option($key);
		if($op){
			//overwrite by return value
			$settings[$key] = $op;
		}else if(!isset($settings[$key])){
			//empty value 
			$settings[$key] = '';
		}
	}
}

if(rsc_current_user_can('manage') && !isset($_POST['filter_action'])){
	//save settings
	if(isset($_POST['rsc_save_settings_nonce']) && wp_verify_nonce( $_POST['rsc_save_settings_nonce'], 'rsc_'.$panel_name)){
		
		//keys for options
		$set_options = apply_filters('rsc_option_fields', $fields);
		$locked = array();
		
		if(!rsc_current_user_can('full')){
			//get locked fields.
			foreach($lock_fields as $key){
				$locked[$key] = get_option($key);
			}
		}
		
		foreach($set_options as $key){
			
			$post_data = '';
			if(isset($_POST[$key])){
				$post_data = $_POST[$key]; 
			}
			
			if($post_data === ''){
				continue;
			}
			if(empty($locked)){
				//administrator or full
				$settings[$key] = $post_data;
				update_option($key, $post_data);
				
			}else{
				//manage
				//make new setting params
				if(is_array($post_data)){
					
					//for array like events.
					foreach($locked as $k=>$v){
						if(!empty($v)){
							foreach($v as $kk=>$vv){
								//if unlocked.
								if(!$vv){
									//overwrite settings.
									if(isset($post_data[$kk])){
										//update
										$settings[$key][$kk] = $post_data[$kk];
									}else{
										//if removed
										unset($settings[$key][$kk]);
									}
								}else{
									//nothing to do if locked.
								}
								//delete from post data
								// unset($post_data[$kk]);
							}
						}
					};
					
					if(!empty($post_data)){
						//var_dump($post_data);
						
						unset($settings[$key]);
						$settings[$key] = array();
						//new event data.
						foreach($post_data as $k=>$v){
							$settings[$key][$k] = $v;
							// 
							// var_dump($settings[$key]);
							// var_dump($key);
							// var_dump($k);
							// var_dump($v);
							// var_dump('-----------------------------');
						}
						
						//and add event lock.
						//$settings['calendar_event_lock'][$k] = 0;
						
					}
					//save array
					update_option($key, $settings[$key]);
					update_option('calendar_event_lock', $settings['calendar_event_lock']);
				}else{
					//for single value.
					if(!isset($locked[$key.'_lock']) || (isset($locked[$key.'_lock']) && !$locked[$key.'_lock'])){
						$settings[$key] = $post_data;
						update_option($key, $post_data);
					}
					
				}
			}
			
		}
	
	}
}

?>

<nav class="nav-tab-wrapper">
	<?php 
	$admin_url = get_admin_url();
	$i = 0;
	foreach($this->_panels as $p):
		$active = '';
		if($p->get_name() == $panel_name){
			$active = 'nav-tab-active';
			$panel = $p;
		}
	?>
	<a href="<?php echo $admin_url.'admin.php?page='.RSC_GENERAL_SETTINGS_PAGE.'&rsc='.$p->get_name(); ?>" class="nav-tab <?php echo $active; ?>">
		<?php echo $p->get_label(); ?><?php if(!empty($p->get_info())): ?><span class="dashicons dashicons-editor-help" title="<?php echo $p->get_info(); ?>"></span><?php endif; ?>
	</a>
	<?php
		$i++;
	endforeach;
	?>
</nav>

<section id="<?php echo $panel->get_name(); ?>-settings" class="rsc-setting-panel">
<?php $panel->echo($settings); ?>
</section>
<hr>
