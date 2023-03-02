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
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-name">
									<?= $entry_name; ?>
								</label>
								<input type="text" name="filter_name" value="<?= $filter_name; ?>" placeholder="<?= $entry_name; ?>"
									id="input-name" class="form-control" />
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-customer-department">
									<?= $entry_customer_department; ?>
								</label>
								<select name="filter_customer_department_id" id="input-customer-department" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php foreach ($customer_departments as $customer_department) { ?>
									<?php if ($customer_department['customer_department_id'] == $filter_customer_department_id) { ?>
									<option value="<?= $customer_department['customer_department_id']; ?>" selected="selected">
										<?= $customer_department['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $customer_department['customer_department_id']; ?>">
										<?= $customer_department['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-customer-group">
									<?= $entry_customer_group; ?>
								</label>
								<select name="filter_customer_group_id" id="input-customer-group" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php foreach ($customer_groups as $customer_group) { ?>
									<?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
									<option value="<?= $customer_group['customer_group_id']; ?>" selected="selected">
										<?= $customer_group['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $customer_group['customer_group_id']; ?>">
										<?= $customer_group['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-location">
									<?= $entry_location; ?>
								</label>
								<select name="filter_location_id" id="input-location" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php foreach ($locations as $location) { ?>
									<?php if ($location['location_id'] == $filter_location_id) { ?>
									<option value="<?= $location['location_id']; ?>" selected="selected">
										<?= $location['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $location['location_id']; ?>">
										<?= $location['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-date-added">
									<?= $entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_start" value="<?= $filter_date_start; ?>"
										placeholder="<?= $entry_date_start; ?>" data-date-format="MMM YY" id="input-date-added"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-status">
									<?= $entry_status; ?>
								</label>
								<select name="filter_status" id="input-status" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php if ($filter_status) { ?>
									<option value="1" selected="selected">
										<?= $text_enabled; ?>
									</option>
									<?php } else { ?>
									<option value="1">
										<?= $text_enabled; ?>
									</option>
									<?php } ?>
									<?php if (!$filter_status && !is_null($filter_status)) { ?>
									<option value="0" selected="selected">
										<?= $text_disabled; ?>
									</option>
									<?php } else { ?>
									<option value="0">
										<?= $text_disabled; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-active">
									<?= $entry_active; ?>
								</label>
								<select name="filter_active" id="input-active" class="form-control">
									<?php if ($filter_active == '*') { ?>
									<option value="*" selected="selected">
										<?= $text_all; ?>
									</option>
									<?php } else { ?>
									<option value="*">
										<?= $text_all; ?>
									</option>
									<?php } ?>
									<?php if (is_null($filter_active)) { ?>
									<option value="1" selected="selected">
										<?= $text_active; ?>
									</option>
									<?php } else { ?>
									<option value="1">
										<?= $text_active; ?>
									</option>
									<?php } ?>
									<?php if (!$filter_active && !is_null($filter_active)) { ?>
									<option value="0" selected="selected">
										<?= $text_inactive; ?>
									</option>
									<?php } else { ?>
									<option value="0">
										<?= $text_inactive; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
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
										<?php if ($sort == 'c.nip') { ?>
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
										<?php if ($sort == 'c.date_start') { ?>
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
										<?php if ($sort == 'c.date_end') { ?>
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
								<tr>
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
										<a href="<?= $customer['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
											class="btn btn-primary"><i class="fa fa-pencil"></i></a>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="10">
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

			var filter_name = $('input[name=\'filter_name\']').val();

			if (filter_name) {
				url += '&filter_name=' + encodeURIComponent(filter_name);
			}

			var filter_customer_department_id = $('select[name=\'filter_customer_department_id\']').val();

			if (filter_customer_department_id != '*') {
				url += '&filter_customer_department_id=' + encodeURIComponent(filter_customer_department_id);
			}

			var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();

			if (filter_customer_group_id != '*') {
				url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
			}

			var filter_location_id = $('select[name=\'filter_location_id\']').val();

			if (filter_location_id != '*') {
				url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
			}

			var filter_status = $('select[name=\'filter_status\']').val();

			if (filter_status != '*') {
				url += '&filter_status=' + encodeURIComponent(filter_status);
			}

			var filter_date_start = $('input[name=\'filter_date_start\']').val();

			if (filter_date_start) {
				url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
			}

			var filter_active = $('select[name=\'filter_active\']').val();

			if (filter_active != '1') {
				url += '&filter_active=' + encodeURIComponent(filter_active);
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('input[name=\'filter_name\']').autocomplete({
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
				$('input[name=\'filter_name\']').val(item['label']);
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