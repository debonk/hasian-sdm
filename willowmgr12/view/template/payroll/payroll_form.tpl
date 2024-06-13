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
			<div class="col-md-4" id="period-info"></div>
			<div class="col-md-8" id="customer-info"></div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_form; ?>
				</h3>
			</div>
			<div class="panel-body">
				<legend>
					<?= $text_payroll_basic; ?>
				</legend>
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td class="text-left">
									<?= $column_basic_date_added; ?>
								</td>
								<td class="text-right">
									<?= $column_gaji_pokok; ?>
								</td>
								<td class="text-right">
									<?= $column_tunj_jabatan; ?>
								</td>
								<td class="text-right">
									<?= $column_tunj_hadir; ?>
								</td>
								<td class="text-right">
									<?= $column_tunj_pph; ?>
								</td>
								<td class="text-right">
									<?= $column_uang_makan; ?>
								</td>
								<td class="text-right">
									<?= $column_gaji_dasar; ?>
								</td>
								<td class="text-right">
									<?= $column_action; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php if ($payroll_basic) { ?>
							<tr>
								<td class="text-left">
									<?= $payroll_basic['date_added']; ?>
								</td>
								<td class="text-right">
									<?= $payroll_basic['gaji_pokok']; ?>
								</td>
								<td class="text-right">
									<?= $payroll_basic['tunj_jabatan']; ?>
								</td>
								<td class="text-right">
									<?= $payroll_basic['tunj_hadir']; ?>
								</td>
								<td class="text-right">
									<?= $payroll_basic['tunj_pph'] ?>
								</td>
								<td class="text-right">
									<?= $payroll_basic['uang_makan']; ?>
								</td>
								<td class="text-right text-warning">
									<?= $payroll_basic['gaji_dasar']; ?>
								</td>
								<?php if ($payroll_status_check) { ?>
								<td class="text-right"><a href="<?= $payroll_basic_edit; ?>" data-toggle="tooltip"
										title="<?= $button_edit; ?>" class="btn btn-primary" target="_blank"
										rel="noopener noreferrer"><i class="fa fa-pencil"></i></a></td>
								<?php } else { ?>
								<td class="text-right"><a href="<?= $payroll_basic_edit; ?>" data-toggle="tooltip"
										title="<?= $button_view; ?>" class="btn btn-info" target="_blank"
										rel="noopener noreferrer"><i class="fa fa-eye"></i></a></td>
								<?php } ?>
							</tr>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="7">
									<?= $text_no_results; ?>
								</td>
								<td class="text-right"><a href="<?= $payroll_basic_edit; ?>" data-toggle="tooltip"
										title="<?= $button_edit; ?>" class="btn btn-primary" target="_blank"
										rel="noopener noreferrer"><i class="fa fa-pencil"></i></a></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<legend>
					<?= $text_presence_summary; ?>
				</legend>
				<div class="table-responsive">
					<table class="table table-bordered text-center" id="table-summary">
						<thead>
							<tr>
								<?php foreach (array_keys($presence_summary) as $code) { ?>
								<td <?='style="width:' . $presence_summary_width . ';"' ; ?>>
									<?= utf8_strtoupper($code); ?>
								</td>
								<?php } ?>
								<td class="text-right">
									<?= $column_action; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<tr>
								<?php foreach ($presence_summary as $code => $value) { ?>
								<?php if ($code == 'hke') { ?>
								<td id="<?= $code; ?>" class="text-bold">
									<?= $value; ?>
								</td>
								<?php } else { ?>
								<td id="total-<?= $code; ?>">
									<?= $value; ?>
								</td>
								<?php } ?>
								<?php } ?>

								<td class="text-right">
									<?php if ($payroll_status_check) { ?>
									<button type="button" id="button-presence" data-loading-text="<?= $text_loading; ?>"
										data-toggle="tooltip" title="<?= $button_edit; ?>" class="btn btn-warning"><i
											class="fa fa-pencil"></i></button>
									<?php } else { ?>
									<button type="button" class="btn btn-warning disabled"><i
											class="fa fa-pencil"></i></button>
									<?php } ?>
									<a href="<?= $presence_summary_edit; ?>" data-toggle="tooltip"
										title="<?= $button_view; ?>" class="btn btn-primary" target="_blank"
										rel="noopener noreferrer"><i class="fa fa-eye"></i></a>
								</td>
							</tr>
							<tr class="text-left">
								<td id="absence-info" colspan="<?= $presence_summary_count; ?>"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- Payroll detail info (widget) -->
				<div id="payroll-detail-info"></div>
				<div>
					<?php if ($payroll_basic) { ?>
					<?php if ($payroll_status_check) { ?>
					<div class="text-right">
						<button type="button" id="button-payroll" data-loading-text="<?= $text_loading; ?>"
							class="btn btn-warning"><i class="fa fa-save"></i>
							<?= $button_payroll_update; ?>
						</button>
					</div>
					<?php } else { ?>
					<div class="text-right">
						<button type="button" class="btn btn-warning disabled"><i class="fa fa-save"></i>
							<?= $button_payroll_update; ?>
						</button>
					</div>
					<?php } ?>
					<?php } ?>
				</div>
				<div id="payroll-old"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');

	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('#payroll-detail-info').load('index.php?route=payroll/payroll/payrolldetailinfo&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>&customer_id=<?= $customer_id; ?>');

	$('#payroll-old').load('index.php?route=payroll/payroll/getpayroll&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>&customer_id=<?= $customer_id; ?>');

	$('#absence-info').load('index.php?route=common/absence_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>&presence_period_id=<?= $presence_period_id; ?>');
</script>
<script type="text/javascript">
	let presence_items = JSON.parse('<?= $json_presence_items; ?>');

	$(document).on('click', '#button-presence', function () {
		for (const presence_group in presence_items) {
			if (Object.hasOwnProperty.call(presence_items, presence_group)) {
				const element = presence_items[presence_group];

				Object.keys(element).forEach(code => {
					value = Number($('td[id=\'total-' + code + '\']').text());

					$('td[id=\'total-' + code + '\']').html('<input class="text-center form-control" type="text" name="' + presence_group + '[' + code + ']" value="' + value + '" onkeyup="calchke()"  />');
				});
			}
		}

		$('#button-presence').replaceWith('<button id="button-presence-override" data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip" title="<?= $button_override; ?>" class="btn btn-warning"><i class="fa fa-check"></i></button>');
	});

	function calchke() {
		values = $('[name^=\'primary\'], [name^=\'additional\']').map(function () {
			if (Number.isInteger(Number($(this).val()))) {
				return (Number($(this).val()));
			} else {
				$(this).val('0');
				return 0;
			}
		}).get();

		hke = values.reduce(function (total, val) {
			return total + val;
		});

		$('#hke').text(hke);
	}

	$(document).on('click', '#button-presence-override', function () {
		$.ajax({
			url: 'index.php?route=presence/presence/overridepresence&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>&customer_id=<?= $customer_id; ?>',
			type: 'post',
			dataType: 'json',
			data: $('#table-summary input[type=\'text\']'),
			beforeSend: function () {
				$('#button-presence-override').button('loading');
			},
			complete: function () {
				$('#button-presence-override').button('reset');
			},
			success: function (json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
				}

				if (json['success']) {
					$('#table-summary input[type=\'text\']').map(function () {
						node = this;

						$(node).parent().text(Number(encodeURIComponent($(node).val())));
					})

					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#button-presence-override').replaceWith('<button type="button" id="button-presence" data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>" class="btn btn-warning"><i class="fa fa-pencil"></i></button>');

					$('#payroll-detail-info').load('index.php?route=payroll/payroll/payrolldetailinfo&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>&customer_id=<?= $customer_id; ?>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});

	});
</script>
<script type="text/javascript">
	$('#absence-info').on('click', 'button[id^=\'button-action\']', function (e) {
		if (confirm('<?= $text_confirm; ?>')) {
			var node = this;

			$.ajax({
				url: 'index.php?route=' + $(node).val() + '&token=<?= $token; ?>',
				dataType: 'json',
				crossDomain: false,
				beforeSend: function () {
					$(node).button('loading');
				},
				complete: function () {
					$(node).button('reset');
				},
				success: function (json) {
					if (json['error']) {
						alert(json['error']);
					}

					if (json['success']) {
						alert(json['success']);

						location.reload();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});

	$('#button-payroll').on('click', function (e) {
		e.preventDefault();

		$.ajax({
			url: 'index.php?route=payroll/payroll/addpayroll&token=<?= $token; ?>',
			type: 'post',
			dataType: 'json',
			data: 'presence_period_id=<?= $presence_period_id; ?>&customer_id=<?= $customer_id; ?>',
			beforeSend: function () {
				$('#button-payroll').button('loading');
			},
			complete: function () {
				$('#button-payroll').button('reset');
			},
			success: function (json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$('#payroll-old').load('index.php?route=payroll/payroll/getpayroll&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>&customer_id=<?= $customer_id; ?>');
				}
			}
		});
	});
</script>
<?= $footer; ?>