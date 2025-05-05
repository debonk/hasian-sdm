<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<?php if ($period_pending_check) { ?>
				<button type="button" id="button-apply-schedule" data-loading-text="<?= $text_loading; ?>"
					class="btn btn-info"><i class="fa fa-clipboard"></i>
					<?= $button_apply_schedule; ?>
				</button>
				<?php } else { ?>
				<a href="<?= $presence; ?>" target="_blank" rel="noopener noreferrer" type="button"
					class="btn btn-default"><i class="fa fa-external-link"></i>
					<?= $button_presence; ?>
				</a>
				<?php if ($period_processing_check) { ?>
				<button type="button" id="button-recap-presence" data-toggle="tooltip" title="<?= $button_recap; ?>"
					class="btn btn-warning"><i class="fa fa-share-square-o"></i>
					<?= $button_recap; ?>
				</button>
				<?php } else { ?>
				<button type="button" class="btn btn-warning disabled"><i class="fa fa-share-square-o"></i>
					<?= $button_recap; ?>
				</button>
				<?php } ?>
				<?php } ?>

				<a href="<?= $print; ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip"
					title="<?= $button_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a>

				<?php if (!$period_pending_check) { ?>
				<a href="<?= $export; ?>" type="button" class="btn btn-info" data-toggle="tooltip"
					title="<?= $button_export; ?>"><i class="fa fa-upload"></i>
				</a>
				<?php } else { ?>
				<button type="button" class="btn btn-info disabled" data-toggle="tooltip"
					title="<?= $button_export; ?>"><i class="fa fa-download"></i>
				</button>
				<?php } ?>
				<?php if (!$schedule_lock) { ?>
				<a href="<?= $import; ?>" type="button" class="btn btn-warning" data-toggle="tooltip"
					title="<?= $button_import; ?>"><i class="fa fa-download"></i>
				</a>
				<button type="button" id="button-delete" data-toggle="tooltip" title="<?= $button_delete; ?>"
					class="btn btn-danger"
					onclick="confirm('<?= $text_confirm; ?>') ? $('#form-schedule').attr('action', '<?= $delete; ?>').submit() : false;"><i
						class="fa fa-trash-o"></i></button>
				<?php } else { ?>
				<button type="button" class="btn btn-warning disabled" data-toggle="tooltip"
					title="<?= $button_import; ?>"><i class="fa fa-download"></i>
				</button>
				<button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>"
					class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>
				<?php } ?>
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
		<?php if ($information) { ?>
		<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i>
			<?= $information; ?>
		</div>
		<?php } ?>
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
		<div class="row">
			<div class="col-sm-3"></div>
			<div class="col-sm-6" id="period-info"></div>
		</div>
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
								<label class="control-label" for="input-presence-period">
									<?= $entry_presence_period; ?>
								</label>
								<select name="presence_period_id" id="input-presence-period" class="form-control">
									<?php foreach ($presence_periods as $presence_period) { ?>
									<?php if ($presence_period['presence_period_id'] == $presence_period_id) { ?>
									<option value="<?= $presence_period['presence_period_id']; ?>" selected="selected">
										<?= date('M y',strtotime($presence_period['period'])); ?>
									</option>
									<?php } else { ?>
									<option value="<?= $presence_period['presence_period_id']; ?>">
										<?= date('M y',strtotime($presence_period['period'])); ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
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
				<form method="post" action="<?= $action; ?>" enctype="multipart/form-data" id="form-schedule">
					<input type="hidden" name="hke" value="<?= $default_hke; ?>" id="input-hke" />
					<div id="schedule-report"></div>
				</form>
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

	$('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');

	$('#schedule-report').load('index.php?route=presence/schedule/report&token=<?= $token . $url; ?>');
	// $('#schedule-report').load('index.php?route=presence/schedule/report&token=<?= $token; ?>' + encodeURI('<?= $url; ?>'));

	$('#schedule-report').on('click', '.pagination a, thead a', function (e) {
		e.preventDefault();

		$('#schedule-report').load(this.href);
		console.log(this.href);
		// let url = this.href.replace(/&amp;/g, '&').replace(/&/g, '&amp;');

		if (history.replaceState) {
			history.replaceState({}, 'Data List', this.href.replace('/report', ''));
		}
	});

	$('#schedule-report').on('click', 'tbody a', function () {
		location = this.href;
	});

	$('#button-filter').on('click', function () {
		url = 'index.php?route=presence/schedule&token=<?= $token; ?>';

		let filter = [];

		let filter_items = JSON.parse('<?= $filter_items; ?>');

		for (let i = 0; i < filter_items.length; i++) {
			filter[filter_items[i]] = $('.well [name=\'filter[' + filter_items[i] + ']\']').val();

			if (filter[filter_items[i]]) {
				url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
			}
		}

		let presence_period_id = $('select[name=\'presence_period_id\']').val();

		if (presence_period_id) {
			url += '&presence_period_id=' + encodeURIComponent(presence_period_id);
		}

		location = url;
	});

	$('#button-apply-schedule').on('click', function (e) {
		if (confirm('<?= $text_confirm; ?>')) {
			$.ajax({
				url: 'index.php?route=presence/schedule/applySchedule&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>',
				dataType: 'json',
				crossDomain: false,
				beforeSend: function () {
					$('#button-apply-schedule').button('loading');
				},
				complete: function () {
					$('#button-apply-schedule').button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						$('#button-apply-schedule').replaceWith('<button type="button" class="btn btn-info disabled"><i class="fa fa-clipboard"></i> <?= $button_apply_schedule; ?></button>');
						$('#button-delete').replaceWith('<button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>');
						$('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});

	$('#button-recap-presence').on('click', function (e) {
		let hke;
		if (hke = prompt('<?= $text_confirm_recap; ?>', '<?= $default_hke; ?>')) {
			$('#input-hke').val(hke);
			$('#form-schedule').submit();
		}
	});
</script>
<script type="text/javascript">
	$('input[name=\'filter[name]\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=presence/schedule/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request) + '&presence_period_id=<?php echo $presence_period_id; ?>',
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