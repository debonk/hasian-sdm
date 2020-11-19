<?php if ($grandtotal) { ?>
  <legend><?php echo $text_payroll_old; ?></legend>
  <div class="table-responsive">
	<table class="table table-bordered">
	<thead>
	  <tr>
		<td class="text-left"><?php echo $column_date_added; ?></td>
		<td class="text-right"><?php echo $column_net_salary; ?></td>
		<?php foreach ($component_codes as $code) { ?>
		  <td class="text-right"><?php echo $text_component[$code]; ?></td>
		<?php } ?>
		<td class="text-right"><?php echo $column_grandtotal; ?></td>
	  </tr>
	</thead>
	<tbody>
	  <tr>
		<td class="text-left"><?php echo $payroll_date_added; ?></td>
		<td class="text-right"><?php echo $net_salary; ?></td>
		<?php foreach ($component_codes as $code) { ?>
		  <td class="text-right"><?php echo $component_data[$code]; ?></td>
		<?php } ?>
		<td class="text-right"><?php echo $grandtotal; ?></td>
	  </tr>
	</tbody>
	</table>
  </div>
 <?php } ?>