<div class="table-responsive">
	<table class="table table-bordered text-left">
		<thead>
			<tr>
				<td>
					<a href="<?= $sort_group_item; ?>"
						class="<?= ($sort == 'group_item') ? strtolower($order) : ''; ?>">
						<?= $column_group_item; ?>
					</a>
				</td>
				<?php foreach ($groups_title as $group => $group_title) { ?>
				<td class="text-center">
					<a href="<?= $sort_group_count[$group]; ?>"
						class="<?= ($sort == $group . '_count') ? strtolower($order) : ''; ?>">
						<?= $group_title; ?>
					</a>
				</td>
				<?php } ?>
				<td class="text-right">
					<?= $column_net_salary; ?>
				</td>
				<td class="text-right">
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
					<?= $payroll['item']; ?>
				</td>
				<?php foreach ($payroll['group_data'] as $group_data) { ?>
				<td class="text-center">
					<?= $group_data; ?>
				</td>
				<?php } ?>
				<td class="text-right">
					<?= $payroll['net_salary']; ?>
				</td>
				<td class="text-right">
					<?= $payroll['component']; ?>
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