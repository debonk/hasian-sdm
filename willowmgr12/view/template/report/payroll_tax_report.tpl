<div class="table-responsive">
	<table class="table table-bordered text-left">
		<thead>
			<tr>
				<td>
					<?php if ($sort == 'customer') { ?>
					<a href="<?= $sort_customer; ?>" class="<?= strtolower($order); ?>">
						<?= $column_customer; ?>
					</a>
					<?php } else { ?>
					<a href="<?= $sort_customer; ?>">
						<?= $column_customer; ?>
					</a>
					<?php } ?>
				</td>
				<td>
					<?= $column_npwp; ?>
				</td>
				<td>
					<?= $column_npwp_address; ?>
				</td>
				<td>
					<?php if ($sort == 'customer_group') { ?>
					<a href="<?= $sort_customer_group; ?>" class="<?= strtolower($order); ?>">
						<?= $column_customer_group; ?>
					</a>
					<?php } else { ?>
					<a href="<?= $sort_customer_group; ?>">
						<?= $column_customer_group; ?>
					</a>
					<?php } ?>
				</td>
				<td>
					<?php if ($sort == 'location') { ?>
					<a href="<?= $sort_location; ?>" class="<?= strtolower($order); ?>">
						<?= $column_location; ?>
					</a>
					<?php } else { ?>
					<a href="<?= $sort_location; ?>">
						<?= $column_location; ?>
					</a>
					<?php } ?>
				</td>
				<td class="text-center">
					<?= $column_gender; ?>
				</td>
				<td class="text-center">
					<?= $column_non_taxed_category; ?>
				</td>
				<?php if (!$final) { ?>
				<td class="text-center">
					<?= $column_ter_category; ?>
				</td>
				<?php } ?>
				<td class="text-right">
					<?= $column_basic_salary; ?>
				</td>
				<td class="text-right">
					<?= $column_allowance; ?>
				</td>
				<td class="text-right">
					<?= $column_deduction; ?>
				</td>
				<td class="text-right">
					<?= $column_insurance_health; ?>
				</td>
				<td class="text-right">
					<?= $column_insurance_employment; ?>
				</td>
				<td class="text-right">
					<?= $column_holiday_allowance; ?>
				</td>
				<td class="text-right">
					<?= $column_gross_salary; ?>
				</td>
				<?php if (!$final) { ?>
				<td class="text-center">
					<?= $column_ter_tariff; ?>
				</td>
				<td class="text-right">
					<?= $column_tax; ?>
				</td>
				<?php } else { ?>
				<td class="text-center">
					<?= $column_tax_final; ?>
				</td>
				<td class="text-center">
					<?= $column_tax_paid; ?>
				</td>
				<td class="text-center">
					<?= $column_tax_net; ?>
				</td>
				<?php } ?>
				<td class="text-right">
					<?= $column_functional_expense; ?>
				</td>
				<td class="text-right">
					<?= $column_thp; ?>
				</td>
			</tr>
		</thead>
		<tbody>
			<?php if ($taxes) { ?>
			<?php foreach ($taxes as $tax) { ?>
			<tr>
				<td>
					<?= $tax['customer']; ?>
				</td>
				<td>
					<?= $tax['npwp']; ?>
				</td>
				<td>
					<?= $tax['npwp_address']; ?>
				</td>
				<td>
					<?= $tax['customer_group']; ?>
				</td>
				<td>
					<?= $tax['location']; ?>
				</td>
				<td class="text-center">
					<?= $tax['gender']; ?>
				</td>
				<td class="text-center">
					<?= $tax['non_taxed_category']; ?>
				</td>
				<?php if (!$final) { ?>
				<td class="text-center">
					<?= $tax['ter_category']; ?>
				</td>
				<?php } ?>
				<td class="text-right">
					<?= $tax['basic_salary']; ?>
				</td>
				<td class="text-right">
					<?= $tax['allowance']; ?>
				</td>
				<td class="text-right">
					<?= $tax['deduction']; ?>
				</td>
				<td class="text-right">
					<?= $tax['insurance_health']; ?>
				</td>
				<td class="text-right">
					<?= $tax['insurance_employment']; ?>
				</td>
				<td class="text-right">
					<?= $tax['holiday_allowance']; ?>
				</td>
				<td class="text-right">
					<?= $tax['gross_salary']; ?>
				</td>
				<?php if (!$final) { ?>
				<td class="text-center">
					<?= $tax['ter_tariff']; ?>
				</td>
				<td class="text-right text-bold">
					<?= $tax['tax']; ?>
				</td>
				<?php } else { ?>
				<td class="text-center text-bold">
					<?= $tax['tax_final']; ?>
				</td>
				<td class="text-center">
					<?= $tax['tax']; ?>
				</td>
				<td class="text-right text-bold">
					<?= $tax['tax_net']; ?>
				</td>
				<?php } ?>
				<td class="text-right">
					<?= $tax['functional_expense']; ?>
				</td>
				<td class="text-right">
					<?= $tax['thp']; ?>
				</td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="text-center" colspan="20">
					<?= isset($on_repair) ? $on_repair : $text_no_results; ?>
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