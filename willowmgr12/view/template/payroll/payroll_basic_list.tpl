<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
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
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-name">
									<?= $entry_name; ?>
								</label>
								<input type="text" name="filter_name" value="<?= $filter_name; ?>" placeholder="<?= $entry_name; ?>"
									id="input-name" class="form-control" />
							</div>
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
						<div class="col-sm-4">
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
							<div class="form-group">
								<label class="control-label" for="input-location">
								  <?= $entry_location; ?>
								</label>
								<select name="filter_location_id" id="input-location" class="form-control">
								  <option value="*">
									<?= $text_all ?>
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
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-status">
									<?= $entry_status; ?>
								</label>
								<select name="filter_active" id="input-status" class="form-control">
									<option value="*">
										<?= $text_active; ?>
									</option>
									<?php if ($filter_active) { ?>
									<option value="1" selected="selected">
										<?= $text_inactive; ?>
									</option>
									<?php } else { ?>
									<option value="1">
										<?= $text_inactive; ?>
									</option>
									<?php } ?>
									<?php if (!$filter_active && !is_null($filter_active)) { ?>
									<option value="0" selected="selected">
										<?= $text_all; ?>
									</option>
									<?php } else { ?>
									<option value="0">
										<?= $text_all; ?>
									</option>
									<?php } ?>
								</select>
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover text-left">
						<thead>
							<tr>
								<td>
									<a href="<?= $sort_nip; ?>" <?=($sort=='c.nip' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
										<?= $column_nip; ?>
									</a>
								</td>
								<td>
									<a href="<?= $sort_name; ?>" <?=($sort=='name' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
										<?= $column_name; ?>
									</a>
								</td>
								<td>
									<a href="<?= $sort_customer_group; ?>" <?=($sort=='customer_group' ) ? 'class="' . strtolower($order)
										. '"' : '' ; ?> >
										<?= $column_customer_group; ?>
									</a>
								</td>
								<td>
									<a href="<?= $sort_customer_department; ?>" <?=($sort=='customer_department' ) ? 'class="' . strtolower($order)
										. '"' : '' ; ?> >
										<?= $column_customer_department; ?>
									</a>
								</td>
								<td>
									<a href="<?= $sort_location; ?>" <?=($sort=='location' ) ? 'class="' . strtolower($order)
										. '"' : '' ; ?> >
										<?= $column_location; ?>
									</a>
								</td>
								<td class="text-right">
									<a href="<?= $sort_gaji_pokok; ?>" <?=($sort=='pb.gaji_pokok' ) ? 'class="' . strtolower($order) . '"'
										: '' ; ?> >
										<?= $column_gaji_pokok; ?>
									</a>
								</td>
								<td class="text-right">
									<a href="<?= $sort_tunj_jabatan; ?>" <?=($sort=='pb.tunj_jabatan' ) ? 'class="' . strtolower($order)
										. '"' : '' ; ?> >
										<?= $column_tunj_jabatan; ?>
									</a>
								</td>
								<td class="text-right">
									<a href="<?= $sort_tunj_hadir; ?>" <?=($sort=='pb.tunj_hadir' ) ? 'class="' . strtolower($order) . '"'
										: '' ; ?> >
										<?= $column_tunj_hadir; ?>
									</a>
								</td>
								<td class="text-right">
									<a href="<?= $sort_tunj_pph; ?>" <?=($sort=='pb.tunj_pph' ) ? 'class="' . strtolower($order) . '"'
										: '' ; ?> >
										<?= $column_tunj_pph; ?>
									</a>
								</td>
								<td class="text-right">
									<a href="<?= $sort_uang_makan; ?>" <?=($sort=='pb.uang_makan' ) ? 'class="' . strtolower($order) . '"'
										: '' ; ?> >
										<?= $column_uang_makan; ?>
									</a>
								</td>
								<td class="text-right">
									<a href="<?= $sort_gaji_dasar; ?>" <?=($sort=='pb.gaji_dasar' ) ? 'class="' . strtolower($order) . '"'
										: '' ; ?> >
										<?= $column_gaji_dasar; ?>
									</a>
								</td>
								<td>
									<a href="<?= $sort_date_added; ?>" <?=($sort=='pb.date_added' ) ? 'class="' . strtolower($order) . '"'
										: '' ; ?> >
										<?= $column_date_added; ?>
									</a>
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
								<td>
									<?= $customer['nip']; ?>
								</td>
								<td>
									<?= $customer['name']; ?>
								</td>
								<td>
									<?= $customer['customer_group']; ?>
								</td>
								<td>
									<?= $customer['customer_department']; ?>
								</td>
								<td>
									<?= $customer['location']; ?>
								</td>
								<td class="text-right">
									<?= $customer['gaji_pokok']; ?>
								</td>
								<td class="text-right">
									<?= $customer['tunj_jabatan']; ?>
								</td>
								<td class="text-right">
									<?= $customer['tunj_hadir']; ?>
								</td>
								<td class="text-right">
									<?= $customer['tunj_pph']; ?>
								</td>
								<td class="text-right">
									<?= $customer['uang_makan']; ?>
								</td>
								<td class="text-right text-warning">
									<?= $customer['gaji_dasar']; ?>
								</td>
								<td>
									<?= $customer['date_added']; ?>
								</td>
								<td class="text-right"><a href="<?= $customer['edit']; ?>" data-toggle="tooltip"
										title="<?= $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
							</tr>
							<?php } ?>
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
			url = 'index.php?route=payroll/payroll_basic&token=<?= $token; ?>';

			let filter_name = $('input[name=\'filter_name\']').val();

			if (filter_name) {
				url += '&filter_name=' + encodeURIComponent(filter_name);
			}

			let filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();

			if (filter_customer_group_id != '*') {
				url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
			}

			let filter_customer_department_id = $('select[name=\'filter_customer_department_id\']').val();

			if (filter_customer_department_id != '*') {
				url += '&filter_customer_department_id=' + encodeURIComponent(filter_customer_department_id);
			}

			let filter_location_id = $('select[name=\'filter_location_id\']').val();

			if (filter_location_id != '*') {
				url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
			}

			let filter_active = $('select[name=\'filter_active\']').val();

			if (filter_active != '*') {
				url += '&filter_active=' + encodeURIComponent(filter_active);
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('input[name=\'filter_name\']').autocomplete({
			'source': function (request, response) {
				$.ajax({
					url: 'index.php?route=presence/presence/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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
<?= $footer; ?>