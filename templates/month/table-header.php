<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<th class="rs-calendar <?php echo esc_attr(implode(' ', $th_class)); ?>">
	<?php do_action('smjp_rsc_pre_get_month_th_value', $day_index); ?>
	<?php $th = apply_filters('smjp_rsc_get_month_th_value', '', $day_index); ?>
	<?php echo wp_kses_post($th);//remove script and style tags. ?>
	<?php do_action('smjp_rsc_after_get_month_th_value', $day_index); ?>
</th>