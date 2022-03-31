<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-allowance" data-toggle="tooltip" title="<?= $button_save; ?>"
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
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-allowance"
					class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-allowance-period">
							<?= $entry_allowance_period; ?>
						</label>
						<div class="col-sm-10">
							<?php if ($allowance_customers) { ?>
							<input type="text" value="<?= $allowance_period; ?>" class="form-control" disabled />
							<?php } else { ?>
							<div class="input-group date">
								<input type="text" name="allowance_period" value="<?= $allowance_period; ?>"
									placeholder="<?= $entry_allowance_period; ?>" id="input-allowance-period" class="form-control"
									data-date-format="D MMM YYYY" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							<?php } ?>
							<?php if ($error_allowance_period) { ?>
							<div class="text-danger">
								<?= $error_allowance_period; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-fund-account">
							<?= $entry_fund_account; ?>
						</label>
						<div class="col-sm-10">
							<select name="fund_account_id" id="input-fund-account" class="form-control">
								<option value="0">
									<?= $text_select ?>
								</option>
								<?php foreach ($fund_accounts as $fund_account) { ?>
								<?php if ($fund_account['fund_account_id'] == $fund_account_id) { ?>
								<option value="<?= $fund_account['fund_account_id']; ?>" selected="selected">
									<?= $fund_account['fund_account_text']; ?>
								</option>
								<?php } else { ?>
								<option value="<?= $fund_account['fund_account_id']; ?>">
									<?= $fund_account['fund_account_text']; ?>
								</option>
								<?php } ?>
								<?php } ?>
							</select>
							<?php if ($error_fund_account) { ?>
							<div class="text-danger">
								<?= $error_fund_account; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-date-process">
							<?= $entry_date_process; ?>
						</label>
						<div class="col-sm-10">
							<div class="input-group date">
								<input type="text" name="date_process" value="<?= $date_process; ?>"
									placeholder="<?= $entry_date_process; ?>" id="input-date-process" class="form-control"
									data-date-format="D MMM YYYY" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
							<?php if ($error_date_process) { ?>
							<div class="text-danger">
								<?= $error_date_process; ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</form>
				<table id="allowance-customer" class="table table-bordered table-hover">
					<thead>
						<tr>
							<td class="text-left">
								<?= $column_customer; ?>
							</td>
							<td class="text-left">
								<?= $column_email; ?>
							</td>
							<td class="text-left">
								<?= $column_method; ?>
							</td>
							<td class="text-left">
								<?= $column_portion; ?>
							</td>
							<td class="text-right">
								<?= $column_amount; ?>
							</td>
							<td class="text-right">
								<?= $column_action; ?>
							</td>
						</tr>
					</thead>
					<tbody>
						<?php if ($allowance_customers) { ?>
						<?php foreach ($allowance_customers as $allowance_customer) { ?>
						<tr>
							<td class="text-left">
								<?= $allowance_customer['customer_text']; ?>
							</td>
							<td class="text-left">
								<?= $allowance_customer['email']; ?>
							</td>
							<td class="text-left">
								<?= $allowance_customer['method']; ?>
							</td>
							<td class="text-left">
								<?= $allowance_customer['portion']; ?>
							</td>
							<td id="amount<?= $allowance_customer['customer_id']; ?>" class="text-right nowrap"
								value="<?= $allowance_customer['amount_value']; ?>">
								<?= $allowance_customer['amount']; ?>
							</td>
							<td class="text-right">
								<button id="button-save-<?= $allowance_customer['customer_id']; ?>"
									class="btn btn-warning collapse" type="button"
									value="<?= $allowance_customer['customer_id']; ?>" data-toggle="tooltip"
									title="<?= $button_save; ?>"><i class="fa fa-check"></i></button>
								<button id="button-edit-<?= $allowance_customer['customer_id']; ?>" class="btn btn-primary"
									type="button" value="<?= $allowance_customer['customer_id']; ?>" data-toggle="tooltip"
									title="<?= $button_edit; ?>"><i class="fa fa-pencil"></i></button>
								<button id="button-delete<?= $allowance_customer['customer_id']; ?>" class="btn btn-danger"
									type="button" value="<?= $allowance_customer['customer_id']; ?>" data-toggle="tooltip"
									title="<?= $button_delete; ?>"><i class="fa fa-minus-circle"></i></button>
							</td>
						</tr>
						<?php } ?>
						<?php } else { ?>
						<tr>
							<td colspan="6" class="text-center">
								<?= $text_calculate; ?></button>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
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
		$('button[id=\'button-save-\']').on('click', function (f) {
			var idx = $(this).val();

			$.ajax({
				url: 'index.php?route=release/allowance/editAllowanceCustomer&token=<?= $token; ?>&allowance_id=<?= $allowance_id; ?>&customer_id=' + idx,
				type: 'post',
				dataType: 'json',
				data: 'amount=' + encodeURIComponent($('input[name=\'amount' + idx + '\']').val()),
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');


						html = '<td id="amount' + idx + '" class="text-right nowrap" value="' + json['amount_value'] + '">' + json['amount'] + '</td>';
						$('#amount' + idx).replaceWith(html);

						$('button[id$=-' + idx + ']').toggle();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('button[id^=\'button-edit\']').on('click', function (e) {
			var idx = $(this).val();
			var amountValue = $('#amount' + idx).attr('value');

			html = '<td id="amount' + idx + '"><input type="text" name="amount' + idx + '" value="' + amountValue + '"/></td>';
			$('#amount' + idx).replaceWith(html);

			$('button[id$=-' + idx + ']').toggle();
		});

		$('button[id^=\'button-save-\']').on('click', function (f) {
			var idx = $(this).val();

			$.ajax({
				url: 'index.php?route=release/allowance/editAllowanceCustomer&token=<?= $token; ?>&allowance_id=<?= $allowance_id; ?>&customer_id=' + idx,
				type: 'post',
				dataType: 'json',
				data: 'amount=' + encodeURIComponent($('input[name=\'amount' + idx + '\']').val()),
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');


						html = '<td id="amount' + idx + '" class="text-right nowrap" value="' + json['amount_value'] + '">' + json['amount'] + '</td>';
						$('#amount' + idx).replaceWith(html);

						$('button[id$=-' + idx + ']').toggle();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('button[id^=\'button-delete\']').on('click', function (e) {
			if (confirm('<?= $text_confirm; ?>')) {
				var node = this;

				$.ajax({
					url: 'index.php?route=release/allowance/deleteAllowanceCustomer&token=<?= $token; ?>&allowance_id=<?= $allowance_id; ?>&customer_id=' + $(node).val(),
					dataType: 'json',
					crossDomain: false,
					beforeSend: function () {
						$(node).button('loading');
					},
					complete: function () {
						$(node).button('reset');
					},
					success: function (json) {
						$('.alert').remove();

						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}

						if (json['success']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

							$(node).parent().parent().remove();
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
		$('.date').datetimepicker({
			pickTime: false
		});
	</script>
</div>
<?= $footer; ?>