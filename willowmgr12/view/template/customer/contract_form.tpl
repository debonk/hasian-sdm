<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
					id="button-delete" value="<?= $customer_id; ?>"><i class="fa fa-trash-o"></i></button>
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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_add; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div id="customer-info"></div>
				<br />
				<fieldset>
					<legend>
						<?= $text_history; ?>
					</legend>
					<div id="history"></div>
				</fieldset>
				<br />
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-contract"
					class="form-horizontal">
					<fieldset>
						<legend>
							<?= $text_apply; ?>
						</legend>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-contract-type">
								<?= $entry_contract_type; ?>
							</label>
							<div class="col-sm-10">
								<select name="contract_type_id" id="input-contract-type" class="form-control">
									<option value="">
										<?= $text_select ?>
									</option>
									<?php foreach ($contract_types as $contract_type) { ?>
									<?php if ($contract_type['index'] == $contract_type_id) { ?>
									<option value="<?= $contract_type['index']; ?>" selected="selected">
										<?= $contract_type['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $contract_type['index']; ?>">
										<?= $contract_type['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
								<?php if ($error_contract_type) { ?>
								<div class="text-danger">
									<?= $error_contract_type; ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-contract-start">
								<?= $entry_contract_start; ?>
							</label>
							<div class="col-sm-10">
								<div class="input-group date">
									<input type="text" name="contract_start" value="<?= $contract_start; ?>"
										placeholder="<?= $entry_contract_start; ?>" id="input-contract-start"
										class="form-control" data-date-format="D MMM YYYY" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i
												class="fa fa-calendar"></i></button>
									</span>
								</div>
								<?php if ($error_contract_start) { ?>
								<div class="text-danger">
									<?= $error_contract_start; ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-contract-end">
								<?= $entry_contract_end; ?>
							</label>
							<div class="col-sm-10">
								<div class="input-group date">
									<input type="text" name="contract_end" value="<?= $contract_end; ?>"
										placeholder="<?= $entry_contract_end; ?>" id="input-contract-end"
										class="form-control" data-date-format="D MMM YYYY" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i
												class="fa fa-calendar"></i></button>
									</span>
								</div>
								<?php if ($error_contract_end) { ?>
								<div class="text-danger">
									<?= $error_contract_end; ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-description">
								<?= $entry_description; ?>
							</label>
							<div class="col-sm-10">
								<input type="text" name="description" value="<?= $description; ?>"
									placeholder="<?= $entry_description; ?>" id="input-description"
									class="form-control" />
							</div>
						</div>
						<button type="submit" form="form-contract" data-toggle="tooltip" title="<?= $button_save; ?>"
							class="btn btn-primary pull-right"><i class="fa fa-save"></i>
							<?= $button_save; ?>
						</button>
					</fieldset>
				</form>
				<form action="<?= $resign; ?>" method="post" enctype="multipart/form-data" id="form-resign"
					class="form-horizontal">
					<fieldset>
						<legend class="text-warning">
							<?= $text_resign . ' ' . $help_resign; ?>
						</legend>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-date-end">
								<?= $entry_date_end; ?>
							</label>
							<div class="col-sm-10">
								<div class="input-group date">
									<input type="text" name="date_end" value="<?= $date_end; ?>"
										placeholder="<?= $entry_date_end; ?>" id="input-date-end" class="form-control"
										data-date-format="D MMM YYYY" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i
												class="fa fa-calendar"></i></button>
									</span>
								</div>
								<?php if ($error_date_end) { ?>
								<div class="text-danger">
									<?= $error_date_end; ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-end-reason">
								<?= $entry_end_reason; ?>
							</label>
							<div class="col-sm-10">
								<input type="text" name="end_reason" value="<?= $end_reason; ?>"
									placeholder="<?= $entry_end_reason; ?>" id="input-end-reason"
									class="form-control" />
								<?php if ($error_end_reason) { ?>
								<div class="text-danger">
									<?= $error_end_reason; ?>
								</div>
								<?php } ?>
							</div>
						</div>
						<button type="button" form="form-resign" data-toggle="tooltip" title="<?= $button_resign; ?>"
							class="btn btn-warning pull-right" onclick="confirm('<?= $text_confirm; ?>') ? $('#form-resign').submit() : false;"><i class="fa fa-exclamation-triangle"></i>
							<?= $button_resign; ?>
						</button>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('#history').on('click', '.pagination a', function (e) {
		e.preventDefault();

		$('#history').load(this.href);
	});

	$('#history').load('index.php?route=customer/contract/history&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('.date').datetimepicker({
		pickTime: false
	});

	$('select[name=\'contract_type_id\']').on('change', function () {
		let contract_start = $('input[name=\'contract_start\']').val();

		$.ajax({
			url: 'index.php?route=customer/contract/contractType&token=<?= $token; ?>&contract_type_id=' + this.value,
			type: 'post',
			dataType: 'json',
			data: 'contract_start=' + $('input[name=\'contract_start\']').val(),
			crossDomain: false,
			success: function (json) {
				$('input[name=\'contract_end\']').val(json['contract_end']);

				if (json['locked'] == true) {
					$('input[name=\'contract_end\']').attr('disabled', true);
				} else {
					$('input[name=\'contract_end\']').attr('disabled', false);
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#button-delete').on('click', function (e) {
		if (confirm('<?= $text_confirm; ?>')) {
			$.ajax({
				url: 'index.php?route=customer/contract/delete&token=<?= $token; ?>',
				type: 'post',
				dataType: 'json',
				data: 'customer_id=<?= $customer_id; ?>',
				crossDomain: false,
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						// $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						location.reload();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
</script>
<?= $footer; ?>