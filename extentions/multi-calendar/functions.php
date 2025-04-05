<?php

function rsc_merge_params($params, $lock_fields=array()){
	$metas = array();
	$post_id = get_the_ID();
	
	if($post_id){
		
		foreach($params as $k=>$v){
			$meta = get_post_meta($post_id, $k, true);
			
			if($meta != ''){
				if(is_array($v)){
					$params[$k] = $v + $meta;
				}else{
					//check bulk lock
					$locked = false;
					// $lock_fields = $lock_fields;
					$base = rsc_get_basename($k);
					// var_dump($base);
					foreach($lock_fields as $field){
						if(strpos($field, $base) === 0){
							$locked = get_option($field);
						}
					}
					
					if(!$locked && $v != $meta){
						$params[$k] = $meta;
					}else{
						// var_dump($k.' = '.$params[$k].';');
					}
				}
			}
		}
	}
	
	return $params;
}