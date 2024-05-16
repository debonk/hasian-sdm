<legend>
	<?= $text_payroll_calculation; ?>
</legend>
<div class="row">
	<div class="col-md-6">
		<table class="table table-bordered text-left">
			<thead>
				<tr>
					<td colspan="2">
						<?= $column_addition; ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php if ($payroll_basic_check && $presence_summary_check) { ?>
				<?php foreach ($main_components['addition'] as $main_component) { ?>
				<tr>
					<td>
						<?= $main_component['title']; ?>
					</td>
					<td class="text-right">
						<?= $main_component['value']; ?>
					</td>
				</tr>
				<?php } ?>
				<?php if ($earning_components) { ?>
				<?php foreach ($earning_components as $component) { ?>
				<tr>
					<td>
						<?= $component['title']; ?>
					</td>
					<td class="text-right">
						<?= $component['value']; ?>
					</td>
				</tr>
				<?php } ?>
				<?php } ?>
				<tr>
					<td class="text-right">
						<?= $text_total_earning; ?>
					</td>
					<td class="text-right">
						<?= $earning; ?>
					</td>
				</tr>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="4">
						<?= $text_no_results; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<div class="col-md-6">
		<table class="table table-bordered">
			<thead>
				<tr>
					<td colspan="2">
						<?= $column_deduction; ?>
					</td>
				</tr>
			</thead>
			<tbody>
				<?php if ($payroll_basic_check && $presence_summary_check) { ?>
				<?php foreach ($main_components['deduction'] as $main_component) { ?>
				<tr>
					<td>
						<?= $main_component['title']; ?>
					</td>
					<td class="text-right text-danger">
						<?= $main_component['value']; ?>
					</td>
				</tr>
				<?php } ?>
				<?php if ($deduction_components) { ?>
				<?php foreach ($deduction_components as $component) { ?>
				<tr>
					<td>
						<?= $component['title']; ?>
					</td>
					<td class="text-right text-danger">
						<?= $component['value']; ?>
					</td>
				</tr>
				<?php } ?>
				<?php } ?>
				<tr>
					<td class="text-right">
						<?= $text_total_deduction; ?>
					</td>
					<td class="text-right text-danger">
						<?= $deduction; ?>
					</td>
				</tr>
				<tr>
					<td class="text-right text-bold">
						<?= $text_grandtotal; ?>
					</td>
					<td class="text-right text-bold">
						<?= $grandtotal; ?>
					</td>
				</tr>
				<?php } else { ?>
				<tr>
					<td class="text-center" colspan="4">
						<?= $text_no_results; ?>
					</td>
				</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>