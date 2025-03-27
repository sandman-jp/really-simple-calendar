<th class="rs-calendar <?php echo implode(' ', $th_class); ?>">
	<?php do_action('rsc_pre_get_month_th_value', $day_index); ?>
	<?php echo apply_filters('rsc_get_month_th_value', '', $day_index); ?>
	<?php do_action('rsc_after_get_month_th_value', $day_index); ?>
</th>