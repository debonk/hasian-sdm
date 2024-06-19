<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right"><a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>"
					class="btn btn-primary"><i class="fa fa-plus"></i></a>
				<button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
					onclick="confirm('<?= $text_confirm; ?>') ? $('#form-customer').submit() : false;"><i
						class="fa fa-trash-o"></i></button>
			</div>
			<h1>
				<?= $heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?= $breadcrumb['href']; ?>">
						<?= $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?= $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="flex-container">
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-name">
									<?= $entry_name; ?>
								</label>
								<input type="text" name="filter[name]" value="<?= $filter['name']; ?>"
									placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
							</div>
						</div>
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-customer-department">
									<?= $entry_customer_department; ?>
								</label>
								<select name="filter[customer_department_id]" id="input-customer-department"
									class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<?php foreach ($customer_departments as $customer_department) { ?>
									<option value="<?= $customer_department['customer_department_id']; ?>"
										<?=$customer_department['customer_department_id']==$filter['customer_department_id']
										? 'selected' : '' ; ?>>
										<?= $customer_department['name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-customer-group">
									<?= $entry_customer_group; ?>
								</label>
								<select name="filter[customer_group_id]" id="input-customer-group" class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<?php foreach ($customer_groups as $customer_group) { ?>
									<option value="<?= $customer_group['customer_group_id']; ?>"
										<?=$customer_group['customer_group_id']==$filter['customer_group_id']
										? 'selected' : '' ; ?>>
										<?= $customer_group['name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-location">
									<?= $entry_location; ?>
								</label>
								<select name="filter[location_id]" id="input-location" class="form-control">
									<option value="">
										<?= $text_all ?>
									</option>
									<?php foreach ($locations as $location) { ?>
									<option value="<?= $location['location_id']; ?>"
										<?=$location['location_id']==$filter['location_id'] ? 'selected' : '' ; ?>>
										<?= $location['name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
					</div>
					<div class="flex-container">
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-date-added">
									<?= $entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date_start]" value="<?= $filter['date_start']; ?>"
										placeholder="<?= $entry_date_start; ?>" data-date-format="MMM YY" id="input-date-added"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-contract-type">
									<?= $entry_contract_type; ?>
								</label>
								<select name="filter[contract_type_id]" id="input-contract-type" class="form-control">
									<option value="">
										<?= $text_all ?>
									</option>
									<?php foreach ($contract_types as $contract_type) { ?>
									<option value="<?= $contract_type['contract_type_id']; ?>"
										<?=$contract_type['contract_type_id']==$filter['contract_type_id'] ? 'selected' : '' ; ?>>
										<?= $contract_type['name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-active">
									<?= $entry_active; ?>
								</label>
								<select name="filter[active]" id="input-active" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<option value="1" <?=$filter['active'] == 1 ? 'selected' : '' ; ?>>
										<?= $text_active; ?>
									</option>
									<option value="-1" <?=$filter['active'] == -1 ? 'selected' : '' ; ?>>
										<?= $text_inactive; ?>
									</option>
								</select>
							</div>
						</div>
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-status">
									<?= $entry_status; ?>
								</label>
								<select name="filter[status]" id="input-status" class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<option value="1" <?=$filter['status'] ? 'selected' : '' ; ?>>
										<?= $text_enabled; ?>
									</option>
									<option value="0" <?=!$filter['status'] && !is_null($filter['status']) ? 'selected' : '' ; ?>>
										<?= $text_disabled; ?>
									</option>
								</select>
							</div>
						</div>
						<div>
							<div class="form-group">
								<label>
									<?= '&nbsp;'; ?>
								</label>
								<div>
									<button type="button" id="button-filter" class="btn btn-primary pull-right"><i
											class="fa fa-search"></i>
										<?= $button_filter; ?>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-center">
										<?= $column_image; ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'nip') { ?>
										<a href="<?= $sort_nip; ?>" class="<?= strtolower($order); ?>">
											<?= $column_nip; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_nip; ?>">
											<?= $column_nip; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'name') { ?>
										<a href="<?= $sort_name; ?>" class="<?= strtolower($order); ?>">
											<?= $column_name; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_name; ?>">
											<?= $column_name; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
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
									<td class="text-left">
										<?php if ($sort == 'customer_department') { ?>
										<a href="<?= $sort_customer_department; ?>" class="<?= strtolower($order); ?>">
											<?= $column_customer_department; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_customer_department; ?>">
											<?= $column_customer_department; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
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
									<td class="text-left">
										<?php if ($sort == 'date_start') { ?>
										<a href="<?= $sort_date_start; ?>" class="<?= strtolower($order); ?>">
											<?= $column_date_start; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_date_start; ?>">
											<?= $column_date_start; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'contract_type') { ?>
										<a href="<?= $sort_contract_type; ?>" class="<?= strtolower($order); ?>">
											<?= $column_contract_type; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_contract_type; ?>">
											<?= $column_contract_type; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left">
										<?php if ($sort == 'date_end') { ?>
										<a href="<?= $sort_date_end; ?>" class="<?= strtolower($order); ?>">
											<?= $column_date_end; ?>
										</a>
										<?php } else { ?>
										<a href="<?= $sort_date_end; ?>">
											<?= $column_date_end; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-right">
										<?= $column_action; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php if ($customers) { ?>
								<?php foreach ($customers as $customer) { ?>
								<tr class="<?= $customer['bg_class']; ?>">
									<td class="text-center">
										<?php if (in_array($customer['customer_id'], $selected)) { ?>
										<input type="checkbox" name="selected[]" value="<?= $customer['customer_id']; ?>"
											checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?= $customer['customer_id']; ?>" />
										<?php } ?>
									</td>
									<td class="text-center">
										<?php if ($customer['image']) { ?>
										<img src="<?= $customer['image']; ?>" alt="<?= $customer['name']; ?>" class="img-thumbnail" />
										<?php } else { ?>
										<span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
										<?php } ?>
									</td>
									<td class="text-left">
										<?= $customer['nip']; ?>
									</td>
									<td class="text-left">
										<?= $customer['name']; ?>
									</td>
									<td class="text-left">
										<?= $customer['customer_group']; ?>
									</td>
									<td class="text-left">
										<?= $customer['customer_department']; ?>
									</td>
									<td class="text-left">
										<?= $customer['location']; ?>
									</td>
									<td class="text-left">
										<?= $customer['date_start']; ?>
									</td>
									<td class="text-left">
										<?= $customer['contract_type']; ?>
									</td>
									<td class="text-left">
										<?= $customer['date_end']; ?>
									</td>
									<td class="text-right nowrap">
										<a href="<?= $customer['view']; ?>" data-toggle="tooltip" title="<?= $button_view; ?>"
											class="btn btn-info" target="_blank" rel="noopener noreferrer"><i class="fa fa-eye"></i></a>
										<div class="btn-group" data-toggle="tooltip" title="<?= $button_login; ?>">
											<button type="button" data-toggle="dropdown" class="btn btn-info dropdown-toggle"><i
													class="fa fa-lock"></i></button>
											<ul class="dropdown-menu pull-right">
												<li><a
														href="index.php?route=customer/customer/login&token=<?= $token; ?>&customer_id=<?= $customer['customer_id']; ?>&store_id=0"
														target="_blank" rel="noopener noreferrer">
														<?= $text_default; ?>
													</a></li>
												<?php foreach ($stores as $store) { ?>
												<li><a
														href="index.php?route=customer/customer/login&token=<?= $token; ?>&customer_id=<?= $customer['customer_id']; ?>&store_id=<?= $store['store_id']; ?>"
														target="_blank" rel="noopener noreferrer">
														<?= $store['name']; ?>
													</a></li>
												<?php } ?>
											</ul>
										</div>
										<?php if ($customer['unlock']) { ?>
										<a href="<?= $customer['unlock']; ?>" data-toggle="tooltip" title="<?= $button_unlock; ?>"
											class="btn btn-warning"><i class="fa fa-unlock"></i></a>
										<?php } else { ?>
										<button type="button" class="btn btn-warning" disabled><i class="fa fa-unlock"></i></button>
										<?php } ?>
										<a href="<?= $customer['contract']; ?>" data-toggle="tooltip" title="<?= $button_contract; ?>"
											class="btn btn-primary" target="_blank" rel="noopener noreferrer"><i class="fa fa-file-text-o"></i></a>
										<a href="<?= $customer['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
											class="btn btn-primary"><i class="fa fa-pencil"></i></a>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="11">
										<?= $text_no_results; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-6 text-left">
						<?= $pagination; ?>
					</div>
					<div class="col-sm-6 text-right">
						<?= $results; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$(document).keypress(function (e) {
			if (e.which == 13) {
				$("#button-filter").click();
			}
		});

		$('#button-filter').on('click', function () {
			url = 'index.php?route=customer/customer&token=<?= $token; ?>';

			let filter = [];

			let filter_items = JSON.parse('<?= $filter_items; ?>');

			for (let i = 0; i < filter_items.length; i++) {
				filter[filter_items[i]] = $('.well [name=\'filter[' + filter_items[i] + ']\']').val();

				if (filter[filter_items[i]]) {
					url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
				}
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('input[name=\'filter[name]\']').autocomplete({
			'source': function (request, response) {
				$.ajax({
					url: 'index.php?route=customer/customer/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
					dataType: 'json',
					success: function (json) {
						response($.map(json, function (item) {
							return {
								label: item['name'],
								value: item['customer_id']
							}
						}));
					}
				});
			},
			'select': function (item) {
				$('input[name=\'filter[name]\']').val(item['label']);
			}
		});
	</script>
	<script type="text/javascript">
		$('.date').datetimepicker({
			minViewMode: 'months',
			pickTime: false
		});
	</script>
</div>
<?= $footer; ?>