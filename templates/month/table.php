<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<table class="rsc-calendar rsc-calendar-monthly month-<?php echo esc_attr(wp_date('m', (int)$time_id)); ?> <?php echo esc_attr($table_class); ?>" data-datetime="<?php echo (int)esc_attr($time_id); ?>">
	
		<caption><?php echo esc_html($monthname); ?><span class="cfc-caption-year"><?php echo esc_html(wp_date('Y', (int)$time_id)); ?></span></caption>
		
		<thead>
			<?php echo wp_kses_post($th); ?>
		</thead>
		

		<tr><?php 
		$allowed = wp_kses_allowed_html('post');
		$allowed['time'] = array('datetime' => true);
		echo wp_kses(implode('</tr><tr>', $td), $allowed); 
		?></tr>

</table>