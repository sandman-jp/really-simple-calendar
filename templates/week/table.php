<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php
$allowed = wp_kses_allowed_html('post');
$allowed['time'] = array('datetime' => true);

$class = 'rsc-calendar rsc-calendar-weekly month-'.wp_date('m', (int)$time_id).' '.$table_class;
?>
<table class="<?php echo esc_attr($class); ?>">
<?php if(!empty($th)): ?><tr><?php echo wp_kses($th, $allowed); ?></tr>
<?php endif; ?>
<tr><?php 
$html = '</tr></table><table class="'.esc_attr($class).'"><tr>';
echo wp_kses(implode($html, $td), $allowed); 
?></tr>
</table>