<?= $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?= $breadcrumb['href']; ?>">
				<?= $breadcrumb['text']; ?>
			</a></li>
		<?php } ?>
	</ul>
	<div class="row">
		<?= $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?= $class; ?>">
			<?= $content_top; ?>
			<h1>
				<?= $heading_title; ?>
			</h1>
			<hr>
			<div class="row">
				<div class="col-md-1"></div>
				<div class="col-md-11">
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<td colspan="3">
										<h4>
											<?= $text_basic_info; ?>
										</h4>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td width="10%"></td>
									<td width="35%">
										<?= $entry_nip; ?>
									</td>
									<td class="text-right">
										<?= $nip; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_firstname; ?>
									</td>
									<td class="text-right">
										<?= $firstname; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_lastname; ?>
									</td>
									<td class="text-right">
										<?= $lastname; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_email; ?>
									</td>
									<td class="text-right">
										<?= $email; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_telephone; ?>
									</td>
									<td class="text-right">
										<?= $telephone; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_payroll_method; ?>
									</td>
									<td class="text-right">
										<?= $payroll_method; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_acc_no; ?>
									</td>
									<td class="text-right">
										<?= $acc_no; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_health_insurance_id; ?>
									</td>
									<td class="text-right">
										<?= $health_insurance_id; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_employment_insurance_id; ?>
									</td>
									<td class="text-right">
										<?= $employment_insurance_id; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_date_start; ?>
									</td>
									<td class="text-right">
										<?= $date_start; ?>
									</td>
								</tr>
								<?php foreach ($custom_fields as $custom_field) { ?>
								<?php if ($custom_field['location'] == 'account') { ?>
								<tr>
									<td></td>
									<td>
										<?= $custom_field['name']; ?>
									</td>
									<td class="text-right">
										<?= (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>
									</td>
								</tr>
								<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<td colspan="3">
										<h4>
											<?= $text_address; ?>
										</h4>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td width="10%"></td>
									<td width="35%">
										<?= $entry_address; ?>
									</td>
									<td class="text-right">
										<?= $address; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_address_id_card; ?>
									</td>
									<td class="text-right">
										<?= $address_id_card; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<td colspan="3">
										<h4>
											<?= $text_contract; ?>
										</h4>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td width="10%"></td>
									<td width="35%">
										<?= $entry_contract_type; ?>
									</td>
									<td class="text-right">
										<?= $contract_type; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_contract_status; ?>
									</td>
									<td class="text-right">
										<?= $contract_status; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_date_end; ?>
									</td>
									<td class="text-right">
										<?= $date_end; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<td colspan="3">
										<h4>
											<?= $text_placement; ?>
										</h4>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td width="10%"></td>
									<td width="35%">
										<?= $entry_customer_group; ?>
									</td>
									<td class="text-right">
										<?= $customer_group; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_customer_department; ?>
									</td>
									<td class="text-right">
										<?= $customer_department; ?>
									</td>
								</tr>
								<tr>
									<td></td>
									<td>
										<?= $entry_location; ?>
									</td>
									<td class="text-right">
										<?= $location; ?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<cite>
						<?= $note_report; ?>
					</cite>
				</div>
				<div class="buttons clearfix">
					<div class="pull-left"><a href="<?= $back; ?>" class="btn btn-default">
							<?= $button_back; ?>
						</a></div>
				</div>
			</div>
		</div>
		<?= $content_bottom; ?>
	</div>
	<?= $column_right; ?>
</div>
</div>
<script type="text/javascript">
	// Sort the custom fields
	$('.form-group[data-sort]').detach().each(function () {
		if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('.form-group').length) {
			$('.form-group').eq($(this).attr('data-sort')).before(this);
		}

		if ($(this).attr('data-sort') > $('.form-group').length) {
			$('.form-group:last').after(this);
		}

		if ($(this).attr('data-sort') == $('.form-group').length) {
			$('.form-group:last').after(this);
		}

		if ($(this).attr('data-sort') < -$('.form-group').length) {
			$('.form-group:first').before(this);
		}
	});
</script>
<?= $footer; ?>