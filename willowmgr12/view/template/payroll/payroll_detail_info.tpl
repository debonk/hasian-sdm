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
				<?php if ($payroll_detail['addition']) { ?>
				<?php foreach ($payroll_detail['addition'] as $component) { ?>
				<tr>
					<td>
						<?= $component['title']; ?>
					</td>
					<td class="text-right">
						<?= $component['text']; ?>
					</td>
				</tr>
				<?php } ?>
				<tr>
					<td class="text-right">
						<?= $payroll_detail['total']['addition']['title']; ?>
					</td>
					<td class="text-right">
						<?= $payroll_detail['total']['addition']['text']; ?>
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
				<?php if ($payroll_detail['deduction']) { ?>
				<?php foreach ($payroll_detail['deduction'] as $component) { ?>
				<tr>
					<td>
						<?= $component['title']; ?>
					</td>
					<td class="text-right text-danger">
						<?= $component['text']; ?>
					</td>
				</tr>
				<?php } ?>
				<td class="text-right">
					<?= $payroll_detail['total']['deduction']['title']; ?>
				</td>
				<td class="text-right text-danger">
					<?= $payroll_detail['total']['deduction']['text']; ?>
				</td>
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