<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<h1>
				<?php echo $heading_title; ?>
			</h1>
			<div class="pull-right">
				<a href="<?= $schedule; ?>" target="_blank" rel="noopener noreferrer" type="button"
					class="btn btn-default"><i class="fa fa-external-link fa-rotate-180"></i>
					<?= $button_schedule; ?>
				</a>
				<?php if ($submitted_status_check) { ?>
				<button type="button" id="button-export" data-loading-text="<?php echo $text_loading; ?>"
					class="btn btn-default"><i class="fa fa-upload"></i>
					<?php echo $button_export; ?>
				</button>
				<?php } else { ?>
				<button type="button" id="button-export" class="btn btn-default disabled"><i class="fa fa-upload"></i>
					<?php echo $button_export; ?>
				</button>
				<?php } ?>
				<?php if ($payroll_status_check) { ?>
				<button type="button" value="<?php echo $presence_period_id; ?>" id="button-presence-submit"
					data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning"><i class="fa fa-share-square-o"></i>
					<?php echo $button_presence_submit; ?>
				</button>
				<button type="button" id="button-delete" data-toggle="tooltip" title="<?php echo $button_delete; ?>"
					class="btn btn-danger"
					onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-presence-total').submit() : false;"><i
						class="fa fa-trash-o"></i></button>
				<?php } else { ?>
				<button type="button" value="" class="btn btn-warning disabled"><i class="fa fa-share-square-o"></i>
					<?php echo $button_presence_submit; ?>
				</button>
				<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>"
					class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>
				<?php } ?>
				<a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>">
						<?php echo $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($information) { ?>
		<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i>
			<?php echo $information; ?>
		</div>
		<?php } ?>
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-sm-6" id="period-info"></div>
			<div class="col-sm-6" id="presence-info"></div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i>
					<?php echo $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-presence-period">
									<?php echo $entry_presence_period; ?>
								</label>
								<select name="presence_period_id" id="input-presence-period" class="form-control">
									<?php foreach ($presence_periods as $presence_period) { ?>
									<?php if ($presence_period['presence_period_id'] == $presence_period_id) { ?>
									<option value="<?php echo $presence_period['presence_period_id']; ?>" selected="selected">
										<?php echo date('M y',strtotime($presence_period['period'])); ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $presence_period['presence_period_id']; ?>">
										<?php echo date('M y',strtotime($presence_period['period'])); ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-name">
									<?php echo $entry_name; ?>
								</label>
								<input type="text" name="filter_name" value="<?php echo $filter_name; ?>"
									placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-customer-group">
									<?php echo $entry_customer_group; ?>
								</label>
								<select name="filter_customer_group_id" id="input-customer-group" class="form-control">
									<option value="*">
										<?php echo $text_all ?>
									</option>
									<?php foreach ($customer_groups as $customer_group) { ?>
									<?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected">
										<?php echo $customer_group['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $customer_group['customer_group_id']; ?>">
										<?php echo $customer_group['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-location">
									<?php echo $entry_location; ?>
								</label>
								<select name="filter_location_id" id="input-location" class="form-control">
									<option value="*">
										<?php echo $text_all ?>
									</option>
									<?php foreach ($locations as $location) { ?>
									<?php if ($location['location_id'] == $filter_location_id) { ?>
									<option value="<?php echo $location['location_id']; ?>" selected="selected">
										<?php echo $location['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $location['location_id']; ?>">
										<?php echo $location['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-payroll-include">
									<?php echo $entry_payroll_include; ?>
								</label>
								<select name="filter_payroll_include" id="input-payroll-include" class="form-control">
									<option value="*">
										<?php echo $text_all ?>
									</option>
									<?php if (isset($filter_payroll_include) && $filter_payroll_include) { ?>
									<option value="1" selected="selected">
										<?php echo $text_yes; ?>
									</option>
									<?php } else { ?>
									<option value="1">
										<?php echo $text_yes; ?>
									</option>
									<?php } ?>
									<?php if (isset($filter_payroll_include) && !$filter_payroll_include) { ?>
									<option value="0" selected="selected">
										<?php echo $text_no; ?>
									</option>
									<?php } else { ?>
									<option value="0">
										<?php echo $text_no; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-presence-code">
									<?php echo $entry_presence_status; ?>
								</label>
								<select name="filter_presence_code" id="input-presence-code" class="form-control">
									<option value="*">
										<?php echo $text_all ?>
									</option>
									<?php foreach ($presence_statuses as $presence_status) { ?>
									<?php if ($presence_status['code']) { ?>
									<?php if ($presence_status['code'] == $filter_presence_code) { ?>
									<option value="<?php echo $presence_status['code']; ?>" selected="selected">
										<?php echo $presence_status['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?php echo $presence_status['code']; ?>">
										<?php echo $presence_status['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?php echo $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<form method="post" action="<?php echo $delete; ?>" enctype="multipart/form-data" id="form-presence-total">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center" rowspan="2"><input type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
									<td class="text-left" rowspan="2">
										<?php if ($sort == 'nip') { ?>
										<a href="<?php echo $sort_nip; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_nip; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_nip; ?>">
											<?php echo $column_nip; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left" rowspan="2">
										<?php if ($sort == 'name') { ?>
										<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>">
											<?php echo $column_name; ?>
										</a>
										<?php } else { ?>
										<a href="<?php echo $sort_name; ?>">
											<?php echo $column_name; ?>
										</a>
										<?php } ?>
									</td>
									<td class="text-left" rowspan="2">
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
									<td class="text-left" rowspan="2">
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
									<td class="text-center" colspan="12">
										<?php echo $column_presence_summary; ?>
									</td>
									<td class="text-right" rowspan="2">
										<?php echo $column_action; ?>
									</td>
								</tr>
								<tr>
									<td class="text-center presence-summary">
										<?php echo $column_hke; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_h; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_s; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_i; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_ns; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_ia; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_a; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_c; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_t1; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_t2; ?>
									</td>
									<td class="text-center presence-summary">
										<?php echo $column_t3; ?>
									</td>
									<td class="text-left">
										<?php echo $column_note; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php if ($customers) { ?>
								<?php foreach ($customers as $customer) { ?>
								<tr>
									<td class="text-center">
										<?php if (in_array($customer['customer_id'], $selected)) { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>"
											checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
										<?php } ?>
									</td>
									<td class="text-left">
										<?php echo $customer['nip']; ?>
									</td>
									<td class="text-left">
										<?php echo $customer['name']; ?>
									</td>
									<td class="text-left">
										<?php echo $customer['customer_group']; ?>
									</td>
									<td class="text-left">
										<?php echo $customer['location']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['hke']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_h']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_s']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_i']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_ns']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_ia']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_a']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_c']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_t1']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_t2']; ?>
									</td>
									<td class="text-center">
										<?php echo $customer['total_t3']; ?>
									</td>
									<td class="text-left">
										<?php echo $customer['note']; ?>
									</td>
									<td class="text-right"><a href="<?php echo $customer['edit']; ?>" data-toggle="tooltip"
											title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="18">
										<?php echo $text_no_results; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
				<div class="row">
					<div class="col-sm-6 text-left">
						<?php echo $pagination; ?>
					</div>
					<div class="col-sm-6 text-right">
						<?php echo $results; ?>
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

		$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

		$('#presence-info').load('index.php?route=presence/presence/presenceInfo&token=<?php echo $token; ?><?php echo $url; ?>');

		$('#button-filter').on('click', function () {
			url = 'index.php?route=presence/presence&token=<?php echo $token; ?>';

			var presence_period_id = $('select[name=\'presence_period_id\']').val();

			if (presence_period_id) {
				url += '&presence_period_id=' + encodeURIComponent(presence_period_id);
			}

			var filter_name = $('input[name=\'filter_name\']').val();

			if (filter_name) {
				url += '&filter_name=' + encodeURIComponent(filter_name);
			}

			var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();

			if (filter_customer_group_id != '*') {
				url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
			}

			var filter_location_id = $('select[name=\'filter_location_id\']').val();

			if (filter_location_id != '*') {
				url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
			}

			var filter_payroll_include = $('select[name=\'filter_payroll_include\']').val();

			if (filter_payroll_include != '*') {
				url += '&filter_payroll_include=' + encodeURIComponent(filter_payroll_include);
			}

			var filter_presence_code = $('select[name=\'filter_presence_code\']').val();

			if (filter_presence_code != '*') {
				url += '&filter_presence_code=' + encodeURIComponent(filter_presence_code);
			}

			location = url;
		});

		$('#button-presence-submit').on('click', function (e) {
			if (confirm('<?php echo $text_submit_confirm; ?>')) {
				$.ajax({
					url: 'index.php?route=presence/presence/submitpresence&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>',
					dataType: 'json',
					crossDomain: false,
					beforeSend: function () {
						$('#button-presence-submit').button('loading');
					},
					complete: function () {
						$('#button-presence-submit').button('reset');
					},
					success: function (json) {
						$('.alert').remove();

						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}

						if (json['success']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

							$('#button-presence-submit').replaceWith('<button type="button" value="" class="btn btn-warning disabled"><i class="fa fa-share-square-o"></i> <?php echo $button_presence_submit; ?></button>');
							$('#button-delete').replaceWith('<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>');
							$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});

		$('#button-export').on('click', function () {
			url = 'index.php?route=presence/presence/export&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>' + '<?php echo $url; ?>';

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('input[name=\'filter_name\']').autocomplete({
			'source': function (request, response) {
				$.ajax({
					url: 'index.php?route=presence/presence/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request) + '&presence_period_id=<?php echo $presence_period_id; ?>',
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
</div>
<?php echo $footer; ?>