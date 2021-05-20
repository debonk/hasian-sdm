<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<td class="text-left">
					<?php echo $column_nip; ?>
				</td>
				<td class="text-left">
					<?php if ($sort == 'customer') { ?>
					<a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>">
						<?php echo $column_customer; ?>
					</a>
					<?php } else { ?>
					<a href="<?php echo $sort_customer; ?>">
						<?php echo $column_customer; ?>
					</a>
					<?php } ?>
				</td>
				<td class="text-left">
					<?php if ($sort == 'customer_group') { ?>
					<a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>">
						<?php echo $column_customer_group; ?>
					</a>
					<?php } else { ?>
					<a href="<?php echo $sort_customer_group; ?>">
						<?php echo $column_customer_group; ?>
					</a>
					<?php } ?>
				</td>
				<td class="text-left">
					<?php if ($sort == 'location') { ?>
					<a href="<?php echo $sort_location; ?>" class="<?php echo strtolower($order); ?>">
						<?php echo $column_location; ?>
					</a>
					<?php } else { ?>
					<a href="<?php echo $sort_location; ?>">
						<?php echo $column_location; ?>
					</a>
					<?php } ?>
				</td>
				<td class="text-right">
					<?php echo $column_net_salary; ?>
				</td>
				<td class="text-center">
					<?php echo $column_component; ?>
				</td>
				<td class="text-right">
					<?php echo $column_grandtotal; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($payrolls) { ?>
			<?php foreach ($payrolls as $payroll) { ?>
			<tr>
				<td class="text-left">
					<?php echo $payroll['nip']; ?>
				</td>
				<td class="text-left">
					<?php echo $payroll['customer']; ?>
				</td>
				<td class="text-left">
					<?php echo $payroll['customer_group']; ?>
				</td>
				<td class="text-left">
					<?php echo $payroll['location']; ?>
				</td>
				<td class="text-left">
					<?php echo $payroll['net_salary']; ?>
				</td>
				<td class="text-left">
				  <?php foreach ($component_codes as $code) { ?>
				  <div class="col-sm-6 nowrap">
					<?php echo $text_component[$code] . ': ' . $payroll['component_data'][$code]; ?>
					</div>
				  <?php } ?>
					</td>
				<td class="text-left">
					<?php echo $payroll['grandtotal']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="9">
					<?php echo $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left">
		<?php echo $pagination; ?>
	</div>
	<div class="col-sm-6 text-right">
		<?php echo $results; ?>
	</div>
</div>