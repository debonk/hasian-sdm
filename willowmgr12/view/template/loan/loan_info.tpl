<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
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
		<div class="row">
			<div class="col-md-8" id="customer-info"></div>
			<div class="col-md-4" id="history"></div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_info; ?>
				</h3>
			</div>
			<div class="panel-body form-horizontal">
				<div id="transaction"></div>
				<br />
				<?php if ($customer_id) { ?>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-loan">
						<?= $entry_loan; ?>
					</label>
					<div class="col-sm-10">
						<select name="loan_id" id="input-loan" class="form-control">
							<option value="0">
								<?= $text_select ?>
							</option>
							<?php foreach ($loans as $loan) { ?>
							<?php if ($loan['balance']) { ?>
							<?php if ($loan['loan_id'] == $loan_id) { ?>
							<option value="<?= $loan['loan_id']; ?>" selected="selected">
								<?= '#' . $loan['loan_id'] . ': ' . $loan['description']; ?>
							</option>
							<?php } else { ?>
							<option value="<?= $loan['loan_id']; ?>">
								<?= '#' . $loan['loan_id'] . ': ' . $loan['description']; ?>
							</option>
							<?php } ?>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-transaction-description">
						<?= $entry_description; ?>
					</label>
					<div class="col-sm-10">
						<input type="text" name="description" value="" placeholder="<?= $entry_description; ?>"
							id="input-transaction-description" class="form-control" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-2 control-label" for="input-amount"><span data-toggle="tooltip"
							title="<?= $help_amount; ?>">
							<?= $entry_amount; ?>
						</span></label>
					<div class="col-sm-10">
						<input type="text" name="amount" value="" placeholder="<?= $help_amount; ?>" id="input-amount"
							class="form-control" />
					</div>
				</div>
				<div class="text-right">
					<button type="button" id="button-transaction" data-loading-text="<?= $text_loading; ?>"
						class="btn btn-primary"><i class="fa fa-plus-circle"></i>
						<?= $button_transaction_add; ?>
					</button>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#transaction').delegate('.pagination a', 'click', function (e) {
		e.preventDefault();

		$('#transaction').load(this.href);
	});

	$('#transaction').load('index.php?route=loan/loan/transaction&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('#history').load('index.php?route=loan/loan/history&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('#button-transaction').on('click', function (e) {
		e.preventDefault();

		$.ajax({
			url: 'index.php?route=loan/loan/addtransaction&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>',
			type: 'post',
			dataType: 'json',
			data: 'description=' + encodeURIComponent($('input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('input[name=\'amount\']').val()) + '&loan_id=' + encodeURIComponent($('select[name=\'loan_id\']').val()),
			beforeSend: function () {
				$('#button-transaction').button('loading');
			},
			complete: function () {
				$('#button-transaction').button('reset');
			},
			success: function (json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#transaction').load('index.php?route=loan/loan/transaction&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

					$('#history').load('index.php?route=loan/loan/history&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

					$('select[name=\'loan_id\']').val(0);
					$('input[name=\'amount\']').val('');
					$('input[name=\'description\']').val('');
				}
			}
		});
	});
</script>
<?= $footer; ?>