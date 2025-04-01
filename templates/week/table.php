<?php
$class = 'rsc-calendar rsc-calendar-weekly month-'.wp_date('m', $time_id).' '.$table_class;
?>
<table class="<?php echo $class; ?>">
<?php if(!empty($th)): ?><tr><?php echo $th; ?></tr>
<?php endif; ?>
<tr><?php echo implode('</tr></table><table class="'.$class.'"><tr>', $td); ?></tr>
</table>