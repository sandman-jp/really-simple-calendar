<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<?php
$allowed = wp_kses_allowed_html('post');
$allowed['time'] = array('datetime' => true);

$class = 'rsc-calendar rsc-calendar-daily-vertical month-'.wp_date('m', (int)$time_id).' '.$table_class;
?>
<div class="rsc-table-wrapper">
<table class="<?php echo esc_html($class); ?>">

<tr><?php echo wp_kses($td, $allowed); ?></tr>

</table>
</div>