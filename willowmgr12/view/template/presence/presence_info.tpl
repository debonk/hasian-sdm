<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> <?php echo $text_sum_presence; ?></h3>
  </div>
  <div class="panel-body">
	<div class="table-responsive">
	  <table class="table table-bordered">
		<thead>
		<tr>
		  <td class="text-center table-evenly-7"><?php echo $column_h; ?></td>
		  <td class="text-center table-evenly-7"><?php echo $column_s; ?></td>
		  <td class="text-center table-evenly-7"><?php echo $column_i; ?></td>
		  <td class="text-center table-evenly-7"><?php echo $column_ns; ?></td>
		  <td class="text-center table-evenly-7"><?php echo $column_ia; ?></td>
		  <td class="text-center table-evenly-7"><?php echo $column_a; ?></td>
		  <td class="text-center table-evenly-7"><?php echo $column_c; ?></td>
		</tr>
		</thead>
		<tbody>
		  <tr>
		    <?php if ($presences_summary_total) { ?>
			<td class="text-center"><?php echo $presences_summary_total['sum_h']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_s']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_i']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_ns']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_ia']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_a']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_c']; ?></td>
		    <?php } else { ?>
		    <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
		    <?php } ?>
		  </tr>
 		</tbody>
	  </table>
	</div>
	<div class="table-responsive">
	  <table class="table table-bordered">
		<thead>
		<tr>
		  <td class="text-center table-evenly-3"><?php echo $column_t1; ?></td>
		  <td class="text-center table-evenly-3"><?php echo $column_t2; ?></td>
		  <td class="text-center table-evenly-3"><?php echo $column_t3; ?></td>
		</tr>
		</thead>
		<tbody>
		  <tr>
		    <?php if ($presences_summary_total) { ?>
			<td class="text-center"><?php echo $presences_summary_total['sum_t1']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_t2']; ?></td>
			<td class="text-center"><?php echo $presences_summary_total['sum_t3']; ?></td>
		    <?php } else { ?>
		    <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
		    <?php } ?>
		  </tr>
		</tbody>
	  </table>
	</div>
  </div>
</div>
