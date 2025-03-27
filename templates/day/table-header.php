<th class="rs-calendar <?php echo implode(' ', $th_class); ?>">
	<?php do_action('rsc_pre_get_th_value', $time_id); ?>
	<?php $th = apply_filters('rsc_get_th_value', $th, $time_id); ?>
	<?php echo apply_filters('rsc_get_week_th_value', $th, $time_id); ?>
	<?php do_action('rsc_after_get_th_value', $time_id); ?>
</th>