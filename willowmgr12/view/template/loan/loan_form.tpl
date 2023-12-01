<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-loan" data-toggle="tooltip" title="<?= $button_save; ?>"
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
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-loan"
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
						<div class="col-md-8" id="customer-info"></div>
						<div class="col-md-4" id="loan-history"></div>
						<!-- <div id="loan-history"></div> -->
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-date-added">
							<?= $entry_date_added; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="" value="<?= $date_added; ?>"
								placeholder="<?= $entry_date_added; ?>" id="input-date-added" class="form-control"
								disabled />
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-amount">
							<?= $entry_amount; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="amount" value="<?= $amount; ?>" placeholder="<?= $entry_amount; ?>"
								id="input-amount" class="form-control" <?=$disabled; ?>></input>
							<?php if ($error_amount) { ?>
							<div class="text-danger">
								<?= $error_amount; ?>
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
								placeholder="<?= $entry_description; ?>" id="input-description" class="form-control"
								<?=$disabled; ?> />
							<?php if ($error_description) { ?>
							<div class="text-danger">
								<?= $error_description; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-installment">
							<?= $entry_installment; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="installment" value="<?= $installment; ?>"
								placeholder="<?= $entry_installment; ?>" id="input-installment" class="form-control" />
							<?php if ($error_installment) { ?>
							<div class="text-danger">
								<?= $error_installment; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-date-start">
							<?= $entry_date_start; ?>
						</label>
						<div class="col-sm-10">
							<div class="input-group date">
								<input type="text" name="date_start" value="<?= $date_start; ?>"
									placeholder="<?= $entry_date_start; ?>" id="input-date-start" class="form-control"
									data-date-format="MMM YYYY" <?=$disabled; ?> />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i
											class="fa fa-calendar-o"></i></button>
								</span>
							</div>
							<?php if ($error_date_start) { ?>
							<div class="text-danger">
								<?= $error_date_start; ?>
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
				url: 'index.php?route=loan/loan/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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
			$('#loan-history').load('index.php?route=loan/loan/history&token=<?= $token; ?>&customer_id=' + item['value']);
		}
	});

	let customer_id = encodeURIComponent($('input[name=\'customer_id\']').val());

	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=' + customer_id);

	$('#loan-history').load('index.php?route=loan/loan/history&token=<?= $token; ?>&customer_id=' + customer_id);

	$('.date').datetimepicker({
		minViewMode: 'months',
		pickTime: false
	});
</script>
<?= $footer; ?>