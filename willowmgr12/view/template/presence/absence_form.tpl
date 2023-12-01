<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-absence" data-toggle="tooltip" title="<?= $button_save; ?>"
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
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-absence"
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
										placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
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
						<label class="col-sm-2 control-label" for="input-presence-status">
							<?= $entry_presence_status; ?>
						</label>
						<div class="col-sm-10">
							<select name="presence_status_id" id="input-presence-status" class="form-control">
								<option value="0">
									<?= $text_select ?>
								</option>
								<?php foreach ($presence_statuses as $presence_status) { ?>
								<?php if (in_array($presence_status['presence_status_id'], $config_presence_status)) { ?>
								<?php if ($presence_status['presence_status_id'] == $presence_status_id) { ?>
								<option value="<?= $presence_status['presence_status_id']; ?>" selected="selected">
									<?= $presence_status['name']; ?>
								</option>
								<?php } else { ?>
								<option value="<?= $presence_status['presence_status_id']; ?>">
									<?= $presence_status['name']; ?>
								</option>
								<?php } ?>
								<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_presence_status) { ?>
							<div class="text-danger">
								<?= $error_presence_status; ?>
							</div>
							<?php } ?>
						</div>
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
							<?php if ($error_ask_approval) { ?>
							<button type="button" id="button-ask-approval" data-loading-text="<?= $text_loading; ?>"
								class="btn btn-warning btn-xs"><i class="fa fa-check-square-o"></i>
								<?= $button_ask_approval; ?>
							</button>
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
					<?php if ($disabled) { ?>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-note">
							<?= $entry_note; ?>
						</label>
						<div class="col-sm-10">
							<div class="input-group">
								<input type="text" name="note" value="<?= $note; ?>" placeholder="<?= $entry_note; ?>"
									id="input-note" class="form-control" />
								<span class="input-group-btn">
									<button type="button" id="button-add-note" data-loading-text="<?= $text_loading; ?>"
										class="btn btn-warning"><i class="fa fa-sticky-note-o"></i>
										<?= $button_add_note; ?>
									</button>
								</span>
							</div>
						</div>
					</div>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('input[name=\'name\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=presence/absence/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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
			$('input[name=\'customer_id\']').val(item['value']);

			$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=' + item['value']);
		}
	});
	
	let customer_id = encodeURIComponent($('input[name=\'customer_id\']').val());

	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=' + customer_id);

	$('#button-add-note').on('click', function () {
		$.ajax({
			url: 'index.php?route=presence/absence/note&token=<?= $token; ?>&absence_id=<?= $absence_id; ?>',
			type: 'post',
			dataType: 'json',
			data: 'note=' + $('input[name=\'note\']').val(),
			beforeSend: function () {
				$('#button-add-note').button('loading');
			},
			complete: function () {
				$('#button-add-note').button('reset');
			},
			success: function (json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					location = 'index.php?route=presence/absence&token=<?= $token; ?>&url=<?= $url; ?>';
				}
			}
		});
	});

	$('#button-ask-approval').on('click', function () {
		var customer_id = encodeURIComponent($('select[name=\'customer_id\']').val());
		var presence_status_id = encodeURIComponent($('select[name=\'presence_status_id\']').val());
		var date = encodeURIComponent($('input[name=\'date\']').val());
		var description = encodeURIComponent($('input[name=\'description\']').val());

		$.ajax({
			url: 'index.php?route=presence/absence/askApproval&token=<?= $token; ?>',
			type: 'post',
			dataType: 'json',
			data: 'customer_id=' + customer_id + '&presence_status_id=' + presence_status_id + '&date=' + date + '&description=' + description,
			beforeSend: function () {
				$('#button-ask-approval').button('loading');
			},
			complete: function () {
				$('#button-ask-approval').button('reset');
			},
			success: function (json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					location = 'index.php?route=presence/absence&token=<?= $token; ?>&url=<?= $url; ?>';
				}
			}
		});
	});

	$('.date').datetimepicker({
		pickTime: false
	});
</script>
<?= $footer; ?>