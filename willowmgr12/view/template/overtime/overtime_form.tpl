<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-overtime" data-toggle="tooltip" title="<?= $button_save; ?>"
					class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_form; ?>
				</h3>
				<h4 class="pull-right"><i class="fa fa-comment-o fa-flip-horizontal"></i>
					<?= $text_modified; ?>
				</h4>
			</div>
			<div class="panel-body">
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-overtime"
					class="form-horizontal">
					<div class="well">
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="input-name">
										<?= $entry_name; ?>
									</label>
									<input type="text" name="name" value="<?= $name; ?>"
										placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" <?= $disabled ? 'disabled' : ''; ?> />
									<input type="hidden" name="customer_id" value="<?= $customer_id; ?>"
										id="input-customer-id" />
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12" id="customer-info"></div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-date">
							<?= $entry_date; ?>
						</label>
						<div class="col-sm-10">
							<div class="input-group date">
								<input type="text" name="date" value="<?= $date; ?>" placeholder="<?= $entry_date; ?>"
									id="input-date" class="form-control" data-date-format="D MMM YYYY" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i
											class="fa fa-calendar"></i></button>
								</span>
							</div>
							<?php if ($error_date) { ?>
							<div class="text-danger">
								<?= $error_date; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-overtime-type">
							<?= $entry_overtime_type; ?>
						</label>
						<div class="col-sm-10">
							<select name="overtime_type_id" id="input-overtime-type" class="form-control">
								<option value="0">
									<?= $text_select ?>
								</option>
								<?php foreach ($overtime_types as $overtime_type) { ?>
								<?php if ($overtime_type['overtime_type_id'] == $overtime_type_id) { ?>
								<option value="<?= $overtime_type['overtime_type_id']; ?>" selected="selected">
									<?= $overtime_type['name']; ?>
								</option>
								<?php } else { ?>
								<option value="<?= $overtime_type['overtime_type_id']; ?>">
									<?= $overtime_type['name']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_overtime_type) { ?>
							<div class="text-danger">
								<?= $error_overtime_type; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-description">
							<?= $entry_description; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="description" value="<?= $description; ?>"
								placeholder="<?= $entry_description; ?>" id="input-description" class="form-control" />
							<?php if ($error_description) { ?>
							<div class="text-danger">
								<?= $error_description; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-schedule-type">
							<?= $entry_schedule_type; ?>
						</label>
						<div class="col-sm-10">
							<select name="schedule_type_id" id="input-schedule-type" class="form-control">
								<option value="0">
									<?= $text_select ?>
								</option>
								<?php foreach ($schedule_types as $schedule_type) { ?>
								<?php if ($schedule_type['schedule_type_id'] == $schedule_type_id) { ?>
								<option value="<?= $schedule_type['schedule_type_id']; ?>" selected="selected">
									<?= $schedule_type['text']; ?>
								</option>
								<?php } else { ?>
								<option value="<?= $schedule_type['schedule_type_id']; ?>">
									<?= $schedule_type['text']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_schedule_type) { ?>
							<div class="text-danger">
								<?= $error_schedule_type; ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('input[name=\'name\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=overtime/overtime/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {
						return {
							label: item['name_set'],
							value: item['customer_id'],
							customer: item['name'],
						}
					}));
				}
			});
		},
		'select': function (item) {
			$('input[name=\'name\']').val(item['customer']);
			$('input[name=\'customer_id\']').val(item['value']).trigger('change');

			$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=' + item['value']);
		}
	});

	let customer_id = encodeURIComponent($('input[name=\'customer_id\']').val());

	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=' + customer_id);

	$('input[name=\'customer_id\']').on('change', function () {
		$.ajax({
			url: 'index.php?route=overtime/overtime/scheduleTypesByLocationGroup&token=<?= $token; ?>&customer_id=' + $('input[name=\'customer_id\']').val(),
			dataType: 'json',
			success: function (json) {
				html = '	<option value="0"><?= $text_select ?></option>';

				if (json && json != '') {
					for (i = 0; i < json.length; i++) {
						html += '<option value="' + json[i]['schedule_type_id'] + '">' + json[i]['text'] + '</option>';
					}
				}

				$('select[name=\'schedule_type_id\']').html(html);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('.date').datetimepicker({
		pickTime: false
	});
</script>
<?= $footer; ?>