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
				<a href="<?= $import; ?>" type="button" class="btn btn-warning" data-toggle="tooltip"
					title="<?= $button_import; ?>" ><i class="fa fa-download"></i>
				</a>
				<a href="<?= $print; ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip"
					title="<?= $button_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a>
				<button type="button" id="button-delete" data-toggle="tooltip" title="<?= $button_delete; ?>"
					class="btn btn-danger" onclick="confirm('<?= $text_confirm; ?>') ? $('#form-schedule').submit() : false;"><i
						class="fa fa-trash-o"></i></button>
				<?php } else { ?>
				<a href="<?= $presence; ?>" target="_blank" rel="noopener noreferrer" type="button" class="btn btn-default"><i
						class="fa fa-external-link"></i>
					<?= $button_presence; ?>
				</a>
				<?php if ($period_processing_check) { ?>
				<button type="button" id="button-recap-presence" data-toggle="tooltip" title="<?= $button_recap; ?>"
					class="btn btn-warning"
					onclick="confirm('<?= $text_confirm_recap; ?>') ? $('#form-schedule').submit() : false;"><i
						class="fa fa-share-square-o"></i>
					<?= $button_recap; ?>
				</button>
				<?php } else { ?>
				<button type="button" class="btn btn-warning disabled"><i class="fa fa-share-square-o"></i>
					<?= $button_recap; ?>
				</button>
				<?php } ?>
				<a href="<?= $print; ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip"
					title="<?= $button_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a>
				<button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger disabled"><i
						class="fa fa-trash-o"></i></button>
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
					<div class="row">
						<div class="col-sm-4">
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
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-name">
									<?= $entry_name; ?>
								</label>
								<input type="text" name="filter_name" value="<?= $filter_name; ?>" placeholder="<?= $entry_name; ?>"
									id="input-name" class="form-control" />
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-customer-group">
									<?= $entry_customer_group; ?>
								</label>
								<select name="filter_customer_group_id" id="input-customer-group" class="form-control">
									<option value="*">
										<?= $text_all_customer_group; ?>
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
										<?= $text_all_customer_department; ?>
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
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-location">
									<?= $entry_location; ?>
								</label>
								<select name="filter_location_id" id="input-location" class="form-control">
									<option value="*">
										<?= $text_all_location ?>
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
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<form method="post" action="<?= $action; ?>" enctype="multipart/form-data" id="form-schedule">
					<div id="schedule-report"></div>
				</form>
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

		$('#schedule-report').load('index.php?route=presence/schedule/report&token=<?= $token; ?>' + '<?= $url; ?>');

		$('#schedule-report').on('click', '.pagination a, thead a', function (e) {
			e.preventDefault();

			$('#schedule-report').load(this.href);
		});

		$('#schedule-report').on('click', 'tbody a', function () {
			location = this.href;
		});

		$('#button-filter').on('click', function () {
			url = 'index.php?route=presence/schedule&token=<?= $token; ?>';

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

			var filter_customer_department_id = $('select[name=\'filter_customer_department_id\']').val();

			if (filter_customer_department_id != '*') {
				url += '&filter_customer_department_id=' + encodeURIComponent(filter_customer_department_id);
			}

			var filter_location_id = $('select[name=\'filter_location_id\']').val();

			if (filter_location_id != '*') {
				url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
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
	</script>
	<script type="text/javascript">
		$('#button-import-bak').on('click', function (e) {
			// var node = this;
			// alert('galkdgjkd');
			$('#form-import').remove();

			$('body').prepend('<form enctype="multipart/form-data" id="form-import" style="display: none;"><input type="file" name="file" /></form>');

			$('#form-import input[name=\'file\']').trigger('click');

			if (typeof timer != 'undefined') {
				clearInterval(timer);
			}

			timer = setInterval(function () {
				if ($('#form-import input[name=\'file\']').val() != '') {
					clearInterval(timer);

					$.ajax({
						url: 'index.php?route=presence/schedule/import&token=<?=$token; ?>&presence_period_id=<?=$presence_period_id; ?>',
						type: 'post',
						dataType: 'json',
						data: new FormData($('#form-import')[0]),
						cache: false,
						contentType: false,
						processData: false,
						beforeSend: function () {
							$('.alert').remove();
							$('#button-import').button('loading');
						},
						success: function (json) {
							$('#button-import').button('reset');
							console.log(json['check']);

							if (json['error']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

							}

							if (json['success']) {
								// location.reload();
							}
						},
						error: function (xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			}, 500);
		});
	</script>
	<script type="text/javascript">
		$('input[name=\'filter_name\']').autocomplete({
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
				$('input[name=\'filter_name\']').val(item['label']);
			}
		});
	</script>
</div>
<?= $footer; ?>