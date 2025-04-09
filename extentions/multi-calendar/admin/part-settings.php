<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<?php
$panel = '';
//get panels
foreach($this->panels as $p){
	$this->fields = array_merge($this->fields, $p->get_fields());
	$this->lock_fields = array_merge($this->lock_fields, $p->get_lock_fields());
	
	if($panel_name == $p->get_name()){
		$panel = $p;
	}
}

//load default settings
$settings = $this->get_settings();
$settings = rsc_merge_params($settings, $this->lock_fields);

if(rsc_current_user_can('manage') && !isset($_POST['filter_action'])){
	$post_settings = false;
	$post_id = get_the_ID();
	//save settings
	if($post_id){
		$post_content = json_decode(get_the_content(), true);
		
		$is_updated = false;
		$update_time = get_post_meta($post_id, 'rsc_update_time', true);
		if($update_time && isset($post_content['update_time']) && $update_time == $post_content['update_time']){
			$is_updated = true;
			delete_post_meta($post_id, 'rsc_update_time');
		}
		
		if($is_updated && !empty($post_content) && is_array($post_content)){
			// var_dump($post_settings);
			$this->post_data = array();
			$post_settings = $post_content['data'];
			
			for($i=0; $i < count($post_settings); $i++){
				
				$ps = $post_settings[$i];
				$key = $ps['name'];
				$val = $ps['value'];
				if(preg_match('/^('.RS_CALENDAR.'_event_.+)?\[(\d+)\](\[\])?$/', $key, $m)){
					//イベントの場合
					$k = $m[1];
					
					if(!isset($this->post_data[$k])){
						$this->post_data[$k] = array();
					}
					if(isset($m[3])){
						//配列なら(repeats & excludes)
						if(!isset($this->post_data[$k][$m[2]])){
							$this->post_data[$k][$m[2]] = array();
						}
						$this->post_data[$k][$m[2]][] = $val;
						
					}else{
						$this->post_data[$k][$m[2]] = $val;
					}
					
				}else{
					//イベント以外
					$this->post_data[$key] = $val;
				}
				
			}
			foreach($this->panels as $p){
				$p->save($settings, $this->post_data);
			}
		}
		/*
		wp_update_post(array(
			'ID' => get_the_ID(),
			'post_content' => '',
		));
		*/
		$settings = $this->get_settings();
		$settings = rsc_merge_params($settings, $this->lock_fields);
		
	}
}

?>
<?php do_action('rsc_before_view_panel'); ?>

<nav class="nav-tab-wrapper">
	<?php 
	$admin_url = get_admin_url();
	$i = 0;
	foreach($this->panels as $p):
		$active = '';
		if($p == $panel){
			$active = 'nav-tab-active';
		}
	?>
	<a href="#<?php echo $p->get_name(); ?>-settings" class="nav-tab <?php echo $active; ?>">
		<?php echo $p->get_label(); ?><?php if(!empty($p->get_info())): ?><span class="dashicons dashicons-editor-help" title="<?php echo $p->get_info(); ?>"></span><?php endif; ?>
	</a>
	<?php
		$i++;
	endforeach;
	?>
</nav>

<?php foreach($this->panels as $p): ?>

	<section id="<?php echo $p->get_name(); ?>-settings" class="rsc-setting-panel">
	<?php $p->echo($settings); ?>
	</section>
	
<?php endforeach; ?>

<hr>
