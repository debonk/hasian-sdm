<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-cutoff" data-toggle="tooltip" title="<?= $button_save; ?>"
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
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-cutoff"
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
						<label class="col-sm-2 control-label" for="input-description">
							<?= $entry_description; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="description" value="<?= $description; ?>"
								placeholder="<?= $entry_description; ?>" id="input-description"
								class="form-control"></input>
							<?php if ($error_description) { ?>
							<div class="text-danger">
								<?= $error_description; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-principle">
							<?= $entry_principle; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="principle" value="<?= $principle; ?>"
								placeholder="<?= $entry_principle; ?>" id="input-principle" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-business-name">
							<?= $entry_business_name; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="business_name" value="<?= $business_name; ?>"
								placeholder="<?= $entry_business_name; ?>" id="input-business-name"
								class="form-control" />
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-amount">
							<?= $entry_amount; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="amount" value="<?= $amount; ?>" placeholder="<?= $entry_amount; ?>"
								id="input-amount" class="form-control" />
							<?php if ($error_amount) { ?>
							<div class="text-danger">
								<?= $error_amount; ?>
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
				url: 'index.php?route=cutoff/cutoff/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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

	$('.date').datetimepicker({
		pickTime: false
	});
</script>
<?= $footer; ?>