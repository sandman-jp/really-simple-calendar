<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<td <?php if($is_in_term): ?>id="cell_<?php echo (int)esc_html($time_id); ?>"<?php endif; ?> class="<?php echo esc_attr(implode(' ', $td_classes)); ?>" data-time="<?php echo (int)esc_attr($time_id); ?>">
	
<?php if($is_in_term): ?>
		<?php do_action('smjp_rsc_pre_get_td_value', $time_id); ?>
		
		<?php 
		$val = apply_filters('smjp_rsc_get_td_value', '', $time_id);
		$dw = strtolower(jddayofweek(wp_date('w', $time_id)-1, 2));
		$val = apply_filters('smjp_rsc_get_td_value_'.$dw, $val, $time_id);
		$val = apply_filters('smjp_rsc_get_td_value_'.$time_id, $val, $time_id);
		$val = apply_filters('smjp_rsc_get_week_td_value', $val, $time_id);
		
		if(!empty($val)):
			echo wp_kses_post($val); ?>
		<?php else: ?>
			<?php do_action('smjp_rsc_get_no_td_value', $time_id); ?>
		<?php endif; ?>
		
		<?php do_action('smjp_rsc_after_get_td_value', $time_id); ?>
<?php else: ?>
	<!-- out of term -->
	<?php do_action('smjp_rsc_get_out_of_term_td_value', $time_id); ?>
<?php endif; ?>
  
</td>
