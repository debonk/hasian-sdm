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
			</div>
			<div class="panel-body">
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-allowance"
					class="form-horizontal">
					<div class="well">
						<div class="row">
							<div class="col-sm-3"></div>
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="input-customer">
										<?= $entry_name; ?>
									</label>
									<select name="customer_id" id="input-customer" class="form-control" <?= $disabled; ?>>
										<option value="0">
											<?= $text_customer_select ?>
										</option>
										<?php foreach ($customers as $customer) { ?>
										<?php if ($customer['customer_id'] == $customer_id) { ?>
										<option value="<?= $customer['customer_id']; ?>" selected="selected">
											<?= $customer['name_department']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $customer['customer_id']; ?>">
											<?= $customer['name_department']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div id="customer-info"></div>
					</div>
			</div>
			</form>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	var select_customer = $('select[name=\'customer_id\']').val();

	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=' + encodeURIComponent(select_customer));

	$('#input-customer').change(function () {
		var select_customer = $('select[name=\'customer_id\']').val();

		$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=' + encodeURIComponent(select_customer));
	});
</script>
<?= $footer; ?>