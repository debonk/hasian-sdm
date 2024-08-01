<div class="table-responsive">
	<table class="table table-bordered text-left">
		<thead>
			<tr>
				<td>
					<?= $column_nip; ?>
				</td>
				<td>
					<a href="<?= $sort_name; ?>" class="<?= ($sort == 'name') ? strtolower($order) : ''; ?>">
						<?= $column_name; ?>
					</a>
				</td>
				<td>
					<a href="<?= $sort_customer_group; ?>" class="<?= ($sort == 'customer_group') ? strtolower($order) : ''; ?>">
						<?= $column_customer_group; ?>
					</a>
				</td>
				<td>
					<a href="<?= $sort_customer_department; ?>" class="<?= ($sort == 'customer_department') ? strtolower($order) : ''; ?>">
						<?= $column_customer_department; ?>
					</a>
				</td>
				<td>
					<a href="<?= $sort_location; ?>" class="<?= ($sort == 'location') ? strtolower($order) : ''; ?>">
						<?= $column_location; ?>
					</a>
				</td>
				<td class="text-right">
					<?= $column_net_salary; ?>
				</td>
				<td class="text-center">
					<?= $column_component; ?>
				</td>
				<td class="text-right">
					<?= $column_grandtotal; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($payrolls) { ?>
			<?php foreach ($payrolls as $payroll) { ?>
			<tr>
				<td>
					<?= $payroll['nip']; ?>
				</td>
				<td>
					<?= $payroll['name']; ?>
				</td>
				<td>
					<?= $payroll['customer_group']; ?>
				</td>
				<td>
					<?= $payroll['customer_department']; ?>
				</td>
				<td>
					<?= $payroll['location']; ?>
				</td>
				<td class="text-right">
					<?= $payroll['net_salary']; ?>
				</td>
				<td>
				  <?php foreach ($component_codes as $code) { ?>
				  <div class="col-sm-6 nowrap">
					<?= $text_component[$code] . ': ' . $payroll['component_data'][$code]; ?>
					</div>
				  <?php } ?>
					</td>
				<td class="text-right">
					<?= $payroll['grandtotal']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="9">
					<?= $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6 text-left">
		<?= $pagination; ?>
	</div>
	<div class="col-sm-6 text-right">
		<?= $results; ?>
	</div>
</div>