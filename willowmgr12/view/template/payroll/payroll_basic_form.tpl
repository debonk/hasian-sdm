<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-payroll-basic" data-toggle="tooltip" title="<?= $button_save; ?>"
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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_edit; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div id="customer-info"></div>
				<br />
				<div id="history"></div>
				<br />
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-payroll-basic"
					class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label">
							<?= $entry_gaji_dasar; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="gaji_dasar" value="<?= $gaji_dasar; ?>"
								placeholder="<?= $entry_gaji_dasar; ?>" class="form-control currency"
								readonly="readonly"></input>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-gaji-pokok">
							<?= $entry_gaji_pokok; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="gaji_pokok" value="<?= $gaji_pokok; ?>"
								placeholder="<?= $entry_gaji_pokok; ?>" id="input-gaji-pokok"
								class="form-control currency"></input>
							<?php if ($error_gaji_pokok) { ?>
							<div class="text-danger">
								<?= $error_gaji_pokok; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-tunj-jabatan">
							<?= $entry_tunj_jabatan; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="tunj_jabatan" value="<?= $tunj_jabatan; ?>"
								placeholder="<?= $entry_tunj_jabatan; ?>" id="input-tunj-jabatan"
								class="form-control currency" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-tunj-hadir">
							<?= $entry_tunj_hadir; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="tunj_hadir" value="<?= $tunj_hadir; ?>"
								placeholder="<?= $entry_tunj_hadir; ?>" id="input-tunj-hadir"
								class="form-control currency" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-uang-makan">
							<?= $entry_uang_makan; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="uang_makan" value="<?= $uang_makan; ?>"
								placeholder="<?= $entry_uang_makan; ?>" id="input-uang-makan"
								class="form-control currency" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-tunj-pph">
							<?= $entry_tunj_pph; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="tunj_pph" value="<?= $tunj_pph; ?>"
								placeholder="<?= $entry_tunj_pph; ?>" id="input-tunj-pph"
								class="form-control currency" />
						</div>
					</div>
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

	$('#history').load('index.php?route=payroll/payroll_basic/history&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('#form-payroll-basic input').keyup(function () {
		let gaji_pokok = getNumber($('input[name=\'gaji_pokok\']').val());
		let tunj_jabatan = getNumber($('input[name=\'tunj_jabatan\']').val());
		let tunj_hadir = getNumber($('input[name=\'tunj_hadir\']').val());
		let tunj_pph = getNumber($('input[name=\'tunj_pph\']').val());
		let total_uang_makan = getNumber($('input[name=\'uang_makan\']').val()) * 25;
		let gaji_dasar = gaji_pokok + tunj_jabatan + tunj_hadir + tunj_pph + total_uang_makan;

		$('input[name=\'gaji_dasar\']').val(gaji_dasar).trigger('blur');
	});
</script>
<?= $footer; ?>