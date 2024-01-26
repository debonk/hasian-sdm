<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
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
    <?php if ($information) { ?>
			<div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $information; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
			<?php } ?>
			<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i>
					<?= $text_period_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label" for="input-period">
									<?= $entry_period; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_period" value="<?= $filter_period; ?>"
										placeholder="<?= $entry_period; ?>" data-date-format="MMM YY" id="input-period"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label" for="input-payroll-status">
									<?= $entry_payroll_status; ?>
								</label>
								<select name="filter_payroll_status" id="input-payroll-status" class="form-control">
									<option value="*"></option>
									<?php foreach ($payroll_statuses as $payroll_status) { ?>
									<?php if ($payroll_status['payroll_status_id'] == $filter_payroll_status) { ?>
									<option value="<?= $payroll_status['payroll_status_id']; ?>" selected="selected">
										<?= $payroll_status['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $payroll_status['payroll_status_id']; ?>">
										<?= $payroll_status['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<form method="post" action="" enctype="multipart/form-data" id="form-payroll">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td class="text-left">
										<?= $column_period; ?>
									</td>
									<td class="text-left">
										<?= $column_payroll_status; ?>
									</td>
									<td class="text-left">
										<?= $column_fund_acc_name; ?>
									</td>
									<td class="text-left">
										<?= $column_fund_acc_no; ?>
									</td>
									<td class="text-right">
										<?= $column_sum_grandtotal; ?>
									</td>
									<td class="text-left">
										<?= $column_date_released; ?>
									</td>
									<td class="text-right">
										<?= $column_action; ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<?php if ($payroll_periods) { ?>
								<?php foreach ($payroll_periods as $payroll_period) { ?>
								<tr>
									<td class="text-left">
										<?= $payroll_period['period']; ?>
									</td>
									<td class="text-left">
										<?= $payroll_period['payroll_status']; ?>
									</td>
									<td class="text-left">
										<?= $payroll_period['fund_acc_name']; ?>
									</td>
									<td class="text-left">
										<?= $payroll_period['fund_acc_no']; ?>
									</td>
									<td class="text-right nowrap">
										<?= $payroll_period['total_payroll']; ?>
									</td>
									<td class="text-left">
										<?= $payroll_period['date_release']; ?>
									</td>
									<td class="text-right nowrap">
										<?php if ($payroll_period['complete_check']) { ?>
										<button value="<?= $payroll_period['presence_period_id']; ?>" type="button" id="button-uncomplete<?= $payroll_period['presence_period_id']; ?>" class="btn btn-warning" data-toggle="tooltip"
											title="<?= $button_uncomplete; ?>"><i class="fa fa-file-zip-o"></i>
										</button>
										<?php } elseif ($payroll_period['release_check']) { ?>
										<a href="<?= $payroll_period['release']; ?>" data-toggle="tooltip" title="<?= $button_release; ?>"
											class="btn btn-primary" id="button-release<?= $payroll_period['presence_period_id']; ?>"><i
												class="fa fa-share-alt"></i></a>
										<?php } else { ?>
										<a class="btn btn-primary disabled"
											id="button-release<?= $payroll_period['presence_period_id']; ?>"><i
												class="fa fa-share-alt"></i></a>
										<?php } ?>
										<?php if ($payroll_period['view_check']) { ?>
										<a href="<?= $payroll_period['view']; ?>" data-toggle="tooltip" title="<?= $button_view; ?>"
											class="btn btn-info" id="button-view<?= $payroll_period['presence_period_id']; ?>"><i
												class="fa fa-eye"></i></a>
										<?php } else { ?>
										<a class="btn btn-info disabled" id="button-view<?= $payroll_period['presence_period_id']; ?>"><i
												class="fa fa-eye"></i></a>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="8">
										<?= $text_no_results; ?>
									</td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
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
		$(document).keypress(function (e) {
			if (e.which == 13) {
				$("#button-filter").click();
			}
		});

		$('#button-filter').on('click', function () {
			url = 'index.php?route=payroll/payroll_release&token=<?= $token; ?>';

			var filter_payroll_status = $('select[name=\'filter_payroll_status\']').val();

			if (filter_payroll_status != '*') {
				url += '&filter_payroll_status=' + encodeURIComponent(filter_payroll_status);
			}

			var filter_period = $('input[name=\'filter_period\']').val();

			if (filter_period) {
				url += '&filter_period=' + encodeURIComponent(filter_period);
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('button[id^=\'button-uncomplete\']').on('click', function (e) {
			if (confirm('<?php echo $text_confirm; ?>')) {
				$.ajax({
					url: 'index.php?route=payroll/payroll_release/uncompletepayroll&token=<?php echo $token; ?>&presence_period_id=' + $(this).val(),
					dataType: 'json',
					crossDomain: true,
					beforeSend: function () {
						$('#button-uncomplete').button('loading');
					},
					complete: function () {
						$('#button-uncomplete').button('reset');
					},
					success: function (json) {
						$('.alert').remove();

						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						}

						if (json['success']) {
							location.reload();
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});

		$('.date').datetimepicker({
			minViewMode: 'months',
			pickTime: false
		});
	</script>
</div>
<?= $footer; ?>