

<table class="rsc-calendar rsc-calendar-monthly month-<?php echo wp_date('m', $time_id) ?> <?php echo $table_class; ?>" data-datetime="<?php echo $time_id; ?>">
	
		<caption><?php echo $monthname; ?><span class="cfc-caption-year"><?php echo wp_date('Y', $time_id) ?></span></caption>
		
		<thead>
			<?php echo $th; ?>
		</thead>
		

		<tr><?php echo implode('</tr><tr>', $td); ?></tr>

</table>