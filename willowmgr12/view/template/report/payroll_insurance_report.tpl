<div class="table-responsive">
	<table class="table table-bordered">
		<thead>
			<tr>
				<td class="text-left" rowspan="2">
					<?= $column_nip; ?>
				</td>
				<td class="text-left" rowspan="2">
					<?= $column_name; ?>
				</td>
				<td class="text-left" rowspan="2">
					<?= $column_customer_group; ?>
				</td>
				<td class="text-left" rowspan="2">
					<?= $column_customer_department; ?>
				</td>
				<td class="text-left" rowspan="2">
					<?= $column_location; ?>
				</td>
				<?php foreach ($titles as $title) { ?>
				<td class="text-center" colspan="2">
					<?= $title; ?>
				</td>
				<?php } ?>
			</tr>
			<tr>
				<?php foreach ($titles as $title) { ?>
				<td class="text-right">
					<?= $column_company; ?>
				</td>
				<td class="text-right">
					<?= $column_customer; ?>
				</td>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php if ($customer_count) { ?>
			<?php foreach ($insurances as $insurance) { ?>
			<tr>
				<td class="text-left">
					<?= $insurance['nip']; ?>
				</td>
				<td class="text-left">
					<?= $insurance['name']; ?>
				</td>
				<td class="text-left">
					<?= $insurance['customer_group']; ?>
				</td>
				<td class="text-left">
					<?= $insurance['customer_department']; ?>
				</td>
				<td class="text-left">
					<?= $insurance['location']; ?>
				</td>
				<?php foreach ($titles as $title) { ?>
				<td class="text-right">
					<?= $insurance['insurances_data'][$title][1]; ?>
				</td>
				<td class="text-right">
					<?= $insurance['insurances_data'][$title][0]; ?>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
			<tr>
				<td class="text-right" colspan="5"><b>
						<?= $text_total; ?>
					</b></td>
				<?php foreach ($titles as $title) { ?>
				<td class="text-right text-bold">
					<?= $insurances_total[$title][1]; ?>
				</td>
				<td class="text-right text-bold">
					<?= $insurances_total[$title][0]; ?>
				</td>
				<?php } ?>
			</tr>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="13">
					<?= $text_no_results; ?>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>
<div class="row">
	<div class="col-sm-6"></div>
	<div class="col-sm-6 text-right">
		<?= $result_count; ?>
	</div>
</div>
<script type="text/javascript">
	$('#period-info').html('<?= $period_info; ?>');

	let alert = '<?= $information; ?>';

	$('.alert').remove();

	if (alert) {
		$('#content > .container-fluid').prepend('<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> ' + alert + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
	}
</script>