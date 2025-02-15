<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="button" data-toggle="tooltip" title="<?= $button_approve_all; ?>" class="btn btn-warning"
					onclick="confirm('<?= $text_confirm_all; ?>') ? $('#form-payroll-basic').attr('action', '<?= $approve_all; ?>').submit() : false;"><i
						class="fa fa-check"></i>
					<?= $button_approve_all; ?>
				</button>
				<button type="button" data-toggle="tooltip" title="<?= $button_approve; ?>" class="btn btn-success"
					onclick="confirm('<?= $text_confirm; ?>') ? $('#form-payroll-basic').submit() : false;"><i
						class="fa fa-check"></i></button>
				<a href="<?= $back; ?>" data-toggle="tooltip" title="<?= $button_back; ?>" class="btn btn-default"><i
						class="fa fa-reply"></i></a>
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
				<h3 class="panel-title"><i class="fa fa-check-square-o"></i>
					<?= $text_list_approve; ?>
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
								<label class="control-label" for="input-customer-group">
									<?= $entry_customer_group; ?>
								</label>
								<select name="filter[customer_group_id]" id="input-customer-group" class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<?php foreach ($customer_groups as $customer_group) { ?>
									<?php if ($customer_group['customer_group_id'] == $filter['customer_group_id']) { ?>
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
									<?php if ($customer_department['customer_department_id'] == $filter['customer_department_id']) { ?>
									<option value="<?= $customer_department['customer_department_id']; ?>"
										selected="selected">
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
									<?php if ($location['location_id'] == $filter['location_id']) { ?>
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
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-status">
									<?= $entry_status; ?>
								</label>
								<select name="filter[active]" id="input-active" class="form-control">
									<option value="*">
										<?= $text_all; ?>
									</option>
									<option value="1" <?=$filter['active']==1 ? 'selected' : '' ; ?>>
										<?= $text_active; ?>
									</option>
									<option value="-1" <?=$filter['active']==-1 ? 'selected' : '' ; ?>>
										<?= $text_inactive; ?>
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
									<div class="btn-group" role="group">
										<button type="button" id="button-filter" class="btn btn-primary"><i
												class="fa fa-search"></i>
											<?= $button_filter; ?>
										</button>
										<a href="<?= $unfilter; ?>" type="button" id="button-unfilter"
											class="btn btn-info" data-toggle="tooltip"
											title="<?= $button_unfilter; ?>"><i class="fa fa-ban"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<form method="post" action="<?= $approve; ?>" enctype="multipart/form-data" id="form-payroll-basic">
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-left">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center"><input type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
									</td>
									<td>
										<a href="<?= $sort_nip; ?>" <?=($sort=='c.nip' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_nip; ?>
										</a>
									</td>
									<td>
										<a href="<?= $sort_name; ?>" <?=($sort=='name' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_name; ?>
										</a>
									</td>
									<td>
										<a href="<?= $sort_customer_group; ?>" <?=($sort=='customer_group' ) ? 'class="'
											. strtolower($order) . '"' : '' ; ?> >
											<?= $column_customer_group; ?>
										</a>
									</td>
									<td>
										<a href="<?= $sort_customer_department; ?>" <?=($sort=='customer_department' )
											? 'class="' . strtolower($order) . '"' : '' ; ?> >
											<?= $column_customer_department; ?>
										</a>
									</td>
									<td>
										<a href="<?= $sort_location; ?>" <?=($sort=='location' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_location; ?>
										</a>
									</td>
									<td class="text-right">
										<a href="<?= $sort_gaji_pokok; ?>" <?=($sort=='pb.gaji_pokok' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_gaji_pokok; ?>
										</a>
									</td>
									<td class="text-right">
										<a href="<?= $sort_tunj_jabatan; ?>" <?=($sort=='pb.tunj_jabatan' ) ? 'class="'
											. strtolower($order) . '"' : '' ; ?> >
											<?= $column_tunj_jabatan; ?>
										</a>
									</td>
									<td class="text-right">
										<a href="<?= $sort_tunj_hadir; ?>" <?=($sort=='pb.tunj_hadir' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_tunj_hadir; ?>
										</a>
									</td>
									<td class="text-right">
										<a href="<?= $sort_tunj_pph; ?>" <?=($sort=='pb.tunj_pph' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_tunj_pph; ?>
										</a>
									</td>
									<td class="text-right">
										<a href="<?= $sort_uang_makan; ?>" <?=($sort=='pb.uang_makan' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_uang_makan; ?>
										</a>
									</td>
									<td class="text-right">
										<a href="<?= $sort_gaji_dasar; ?>" <?=($sort=='pb.gaji_dasar' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_gaji_dasar; ?>
										</a>
									</td>
									<td>
										<a href="<?= $sort_date_added; ?>" <?=($sort=='pb.date_added' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?> >
											<?= $column_date_added; ?>
										</a>
									</td>
									<td>
										<?= $column_status; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php if ($customers) { ?>
								<?php foreach ($customers as $customer) { ?>
								<tr>
									<td rowspan="2" class="text-center">
										<?php if (in_array($customer['payroll_basics'][1]['payroll_basic_id'], $selected)) { ?>
										<input type="checkbox" name="selected[]"
											value="<?= $customer['payroll_basics'][1]['payroll_basic_id']; ?>"
											checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]"
											value="<?= $customer['payroll_basics'][1]['payroll_basic_id']; ?>" />
										<?php } ?>
									</td>

									<td rowspan="2">
										<?= $customer['nip']; ?>
									</td>
									<td rowspan="2">
										<?= $customer['name']; ?>
									</td>
									<td rowspan="2">
										<?= $customer['customer_group']; ?>
									</td>
									<td rowspan="2">
										<?= $customer['customer_department']; ?>
									</td>
									<td rowspan="2">
										<?= $customer['location']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][1]['gaji_pokok']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][1]['tunj_jabatan']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][1]['tunj_hadir']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][1]['tunj_pph']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][1]['uang_makan']; ?>
									</td>
									<td class="text-right text-warning">
										<?= $customer['payroll_basics'][1]['gaji_dasar']; ?>
									</td>
									<td>
										<?= $customer['payroll_basics'][1]['date_added']; ?>
									</td>
									<td>
										<?= $customer['payroll_basics'][1]['status']; ?>
									</td>
								</tr>
								<tr class="bg-success">
									<td class="text-right">
										<?= $customer['payroll_basics'][0]['gaji_pokok']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][0]['tunj_jabatan']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][0]['tunj_hadir']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][0]['tunj_pph']; ?>
									</td>
									<td class="text-right">
										<?= $customer['payroll_basics'][0]['uang_makan']; ?>
									</td>
									<td class="text-right text-warning">
										<?= $customer['payroll_basics'][0]['gaji_dasar']; ?>
									</td>
									<td>
										<?= $customer['payroll_basics'][0]['date_added']; ?>
									</td>
									<td>
										<?= $customer['payroll_basics'][0]['status']; ?>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="14">
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
			url = 'index.php?route=payroll/payroll_basic/approve&token=<?= $token; ?>';

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

		$('button[id^=\'button-approve-\']').on('click', function () {
			console.log($(this).val())
		});

		$('input[name=\'filter[name]\']').autocomplete({
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
				$('input[name=\'filter[name]\']').val(item['label']);
			}
		});
	</script>
	<?= $footer; ?>