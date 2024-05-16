<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-presence-summary" data-toggle="tooltip" title="<?= $button_save; ?>"
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
			<?php if ($customer_id) { ?>
			<div class="panel-body">
				<div class="row">
					<div class="clearfix">
						<div class="col-sm-4" id="period-info"></div>
						<div class="col-sm-8" id="customer-info"></div>
					</div>
					<?php if (!$inactive) { ?>
					<div class="clearfix">
						<div class="panel-body col-sm-4">
							<legend>
								<?= $text_legend; ?>
							</legend>
							<fieldset>
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<td class="text-center">
													<?= $column_code; ?>
												</td>
												<td class="text-center">
													<?= $column_time_start; ?>
												</td>
												<td class="text-center">
													<?= $column_time_end; ?>
												</td>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($schedule_types as $schedule_type) { ?>
											<tr>
												<td class="text-center">
													<?= $schedule_type['code']; ?>
												</td>
												<td class="text-center">
													<?= $schedule_type['time_start']; ?>
												</td>
												<td class="text-center">
													<?= $schedule_type['time_end']; ?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</fieldset>
						</div>
						<div class="panel-body col-sm-8">
							<legend>
								<?= $text_presence_summary; ?>
							</legend>
							<div class="table-responsive">
								<table class="table table-bordered text-center">
									<thead>
										<tr>
											<?php foreach (array_keys($presence_summary) as $code) { ?>
											<td <?='style="width:' . $presence_summary_width . ';"' ; ?>>
												<?= utf8_strtoupper($code); ?>
											</td>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<tr>
											<?php foreach (array_values($presence_summary) as $value) { ?>
											<td>
												<?= $value; ?>
											</td>
											<?php } ?>
										</tr>
									</tbody>
								</table>
								<table class="table table-bordered text-center">
									<thead>
										<tr>
											<?php foreach (array_keys($late_summary) as $code) { ?>
											<td class="table-evenly-4">
												<?= $code == 't' ? $column_t : utf8_strtoupper($code); ?>
											</td>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<tr>
											<?php foreach (array_values($late_summary) as $value) { ?>
											<td>
												<?= $value; ?>
											</td>
											<?php } ?>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<form action="<?= $edit; ?>" method="post" enctype="multipart/form-data"
							id="form-presence-summary" class="form-horizontal">
							<legend>
								<?= $text_schedule_detail; ?>
							</legend>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<?php foreach ($list_days as $list_day) { ?>
											<td class="text-center table-evenly-7">
												<?= $list_day; ?>
											</td>
											<?php } ?>
										</tr>
									</thead>
									<tbody>
										<?php for ($week = 0; $week < $total_week; $week++) { ?>
										<tr>
											<?php for ($day = 0; $day < 7; $day++) { ?>
											<?php if (isset($calendar[$week . $day])) { ?>
											<td class="text-right calendar-day">
												<h4>
													<?= $calendar[$week . $day]['text']; ?>
												</h4>
												<?php if ($calendar[$week . $day]['locked']) { ?>
												<div class="text-center">
													<p><i class="fa fa-clock-o"></i>
														<?= $calendar[$week . $day]['schedule_type_code']; ?>
													</p>
													<p class="bg-<?= $calendar[$week . $day]['bg_class']; ?>"
														data-toggle="tooltip"
														title="<?= $calendar[$week . $day]['note']; ?>">
														<i class="fa fa-sign-in"></i>
														<?= $calendar[$week . $day]['time_login'] . ' - ' . $calendar[$week . $day]['time_logout']; ?></br>
														<?= $calendar[$week . $day]['presence_status']; ?>
													</p>
												</div>
												<?php } else { ?>
												<select name="<?= 'schedule' . $calendar[$week . $day]['date']; ?>"
													id="" class="form-control">
													<option value="0">
														<?= $text_off; ?>
													</option>
													<?php foreach ($schedule_types as $schedule_type) { ?>
													<?php if ($schedule_type['schedule_type_id'] == $calendar[$week . $day]['schedule_type_id']) { ?>
													<option value="<?= $schedule_type['schedule_type_id']; ?>"
														selected="selected">
														<?= $schedule_type['text']; ?>
													</option>
													<?php } else { ?>
													<option value="<?= $schedule_type['schedule_type_id']; ?>">
														<?= $schedule_type['text']; ?>
													</option>
													<?php } ?>
													<?php } ?>
												</select>
												<?php } ?>
											</td>
											<?php } else { ?>
											<td class="text-center bg-light-dark"></td>
											<?php } ?>
											<?php } ?>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</form>
						<div id="absence-info"></div>
						<div>
							<legend>
								<?= $text_log; ?>
							</legend>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="text-left">
												<?= $column_date; ?>
											</td>
											<td class="text-center">
												<?= $column_schedule; ?>
											</td>
											<td class="text-center">
												<?= $column_login; ?>
											</td>
											<td class="text-center">
												<?= $column_logout; ?>
											</td>
											<td class="text-center">
												<?= $column_duration; ?>
											</td>
											<td class="text-left">
												<?= $column_presence; ?>
											</td>
										</tr>
									</thead>
									<tbody>
										<?php if ($calendar) { ?>
										<?php foreach ($calendar as $calendar_data) { ?>
										<tr>
											<td class="text-left">
												<?= $calendar_data['text']; ?>
											</td>
											<td class="text-center">
												<?= $calendar_data['schedule_type_code']; ?>
											</td>
											<td class="text-center">
												<?= $calendar_data['time_login']; ?>
											</td>
											<td class="text-center">
												<?= $calendar_data['time_logout']; ?>
											</td>
											<td class="text-center">
												<?= $calendar_data['duration']; ?>
											</td>
											<td class="text-left">
												<?= $calendar_data['presence_status']; ?>
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
						</div>
					</div>
					<?php } else { ?>
					<div class="col-sm-12">
						<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i>
							<?= $text_inactive; ?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');

	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('#absence-info').load('index.php?route=common/absence_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>&presence_period_id=<?= $presence_period_id; ?>');

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
</script>
</div>
<?= $footer; ?>