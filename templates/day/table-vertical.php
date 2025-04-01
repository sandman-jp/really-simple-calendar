<?php
$class = 'rsc-calendar rsc-calendar-daily-vertical month-'.wp_date('m', $time_id).' '.$table_class;
?>
<div class="rsc-table-wrapper">
<table class="<?php echo $class; ?>">

<tr><?php echo $td; ?></tr>

</table>
</div>