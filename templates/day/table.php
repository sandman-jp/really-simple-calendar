<?php
$class = 'rsc-calendar rsc-calendar-daily month-'.wp_date('m', $time_id).' '.$table_class;
?>
<table class="<?php echo $class ?>">
<?php if(!empty($th)): ?><tr><?php echo $th; ?></tr><?php endif; ?>
<tr><?php echo $td; ?></tr>
</table>