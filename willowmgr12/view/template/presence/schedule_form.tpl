<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-presence-summary" data-toggle="tooltip" title="<?php echo $button_save; ?>"
					class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1>
				<?php echo $heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?php echo $breadcrumb['href']; ?>">
						<?php echo $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?php echo $text_form; ?>
				</h3>
			</div>
			<?php if ($customer_id) { ?>
			<div class="panel-body">
				<div class="row">
					<div class="clearfix">
						<div class="col-sm-4" id="period-info"></div>
						<div class="col-sm-8" id="customer-info"></div>
					</div>
					<div class="clearfix">
						<div class="panel-body col-sm-5">
							<legend>
								<?php echo $text_legend; ?>
							</legend>
							<fieldset>
								<div class="table-responsive">
									<table class="table table-bordered">
										<thead>
											<tr>
												<td class="text-center">
													<?php echo $column_code; ?>
												</td>
												<td class="text-center">
													<?php echo $column_time_start; ?>
												</td>
												<td class="text-center">
													<?php echo $column_time_end; ?>
												</td>
											</tr>
										</thead>
										<tbody>
											<?php foreach ($schedule_types as $schedule_type) { ?>
											<tr>
												<td class="text-center">
													<?php echo $schedule_type['code']; ?>
												</td>
												<td class="text-center">
													<?php echo $schedule_type['time_start']; ?>
												</td>
												<td class="text-center">
													<?php echo $schedule_type['time_end']; ?>
												</td>
											</tr>
											<?php } ?>
										</tbody>
									</table>
								</div>
							</fieldset>
						</div>
						<div class="panel-body col-sm-7">
							<legend>
								<?php echo $text_presence_summary; ?>
							</legend>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="text-center table-evenly-8">
												<?php echo $column_hke; ?>
											</td>
											<td class="text-center table-evenly-8">
												<?php echo $column_h; ?>
											</td>
											<td class="text-center table-evenly-8">
												<?php echo $column_s; ?>
											</td>
											<td class="text-center table-evenly-8">
												<?php echo $column_i; ?>
											</td>
											<td class="text-center table-evenly-8">
												<?php echo $column_ns; ?>
											</td>
											<td class="text-center table-evenly-8">
												<?php echo $column_ia; ?>
											</td>
											<td class="text-center table-evenly-8">
												<?php echo $column_a; ?>
											</td>
											<td class="text-center table-evenly-8">
												<?php echo $column_c; ?>
											</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<?php if ($presence_summary) { ?>
											<td class="text-center nowrap">
												<?php echo $presence_summary['hke']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['h']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['s']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['i']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['ns']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['ia']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['a']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['c']; ?>
											</td>
											<?php } else { ?>
											<td class="text-center" colspan="8">
												<?php echo $text_no_results; ?>
											</td>
											<?php } ?>
										</tr>
									</tbody>
								</table>
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="text-center table-evenly-4">
												<?php echo $column_t; ?>
											</td>
											<td class="text-center table-evenly-4">
												<?php echo $column_t1; ?>
											</td>
											<td class="text-center table-evenly-4">
												<?php echo $column_t2; ?>
											</td>
											<td class="text-center table-evenly-4">
												<?php echo $column_t3; ?>
											</td>
										</tr>
									</thead>
									<tbody>
										<tr>
											<?php if ($presence_summary) { ?>
											<td class="text-center">
												<?php echo $presence_summary['t']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['t1']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['t2']; ?>
											</td>
											<td class="text-center">
												<?php echo $presence_summary['t3']; ?>
											</td>
											<?php } else { ?>
											<td class="text-center" colspan="4">
												<?php echo $text_no_results; ?>
											</td>
											<?php } ?>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<form action="<?php echo $edit; ?>" method="post" enctype="multipart/form-data" id="form-presence-summary"
							class="form-horizontal">
							<legend>
								<?php echo $text_schedule_detail; ?>
							</legend>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<?php foreach ($list_days as $list_day) { ?>
											<td class="text-center table-evenly-7">
												<?php echo $list_day; ?>
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
													<?php echo $calendar[$week . $day]['text']; ?>
												</h4>
												<?php if ($calendar[$week . $day]['locked']) { ?>
												<div class="text-center">
													<p><i class="fa fa-clock-o"></i>
														<?php echo $calendar[$week . $day]['schedule_type_code']; ?>
													</p>
													<p class="bg-<?php echo $calendar[$week . $day]['bg_class']; ?>" data-toggle="tooltip"
														title="<?php echo $calendar[$week . $day]['note']; ?>">
														<i class="fa fa-sign-in"></i>
														<?php echo $calendar[$week . $day]['time_login'] . ' - ' . $calendar[$week . $day]['time_logout']; ?></br>
														<?php echo $calendar[$week . $day]['presence_status']; ?>
													</p>
												</div>
												<?php } else { ?>
												<select name="<?php echo 'schedule' . $calendar[$week . $day]['date']; ?>" id=""
													class="form-control">
													<option value="0">
														<?php echo $text_off; ?>
													</option>
													<?php foreach ($schedule_types as $schedule_type) { ?>
													<?php if ($schedule_type['schedule_type_id'] == $calendar[$week . $day]['schedule_type_id']) { ?>
													<option value="<?php echo $schedule_type['schedule_type_id']; ?>" selected="selected">
														<?php echo $schedule_type['text']; ?>
													</option>
													<?php } else { ?>
													<option value="<?php echo $schedule_type['schedule_type_id']; ?>">
														<?php echo $schedule_type['text']; ?>
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
								<?php echo $text_log; ?>
							</legend>
							<div class="table-responsive">
								<table class="table table-bordered">
									<thead>
										<tr>
											<td class="text-left">
												<?php echo $column_date; ?>
											</td>
											<td class="text-center">
												<?php echo $column_schedule; ?>
											</td>
											<td class="text-center">
												<?php echo $column_login; ?>
											</td>
											<td class="text-center">
												<?php echo $column_logout; ?>
											</td>
											<td class="text-center">
												<?php echo $column_duration; ?>
											</td>
											<td class="text-left">
												<?php echo $column_presence; ?>
											</td>
										</tr>
									</thead>
									<tbody>
										<?php if ($calendar) { ?>
										<?php foreach ($calendar as $calendar_data) { ?>
										<tr>
											<td class="text-left">
												<?php echo $calendar_data['text']; ?>
											</td>
											<td class="text-center">
												<?php echo $calendar_data['schedule_type_code']; ?>
											</td>
											<td class="text-center">
												<?php echo $calendar_data['time_login']; ?>
											</td>
											<td class="text-center">
												<?php echo $calendar_data['time_logout']; ?>
											</td>
											<td class="text-center">
												<?php echo $calendar_data['duration']; ?>
											</td>
											<td class="text-left">
												<?php echo $calendar_data['presence_status']; ?>
											</td>
										</tr>
										<?php } ?>
										<?php } else { ?>
										<tr>
											<td class="text-center" colspan="8">
												<?php echo $text_no_results; ?>
											</td>
										</tr>
										<?php } ?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

	$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

	$('#absence-info').load('index.php?route=common/absence_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

	$('#absence-info').on('click', 'button[id^=\'button-action\']', function (e) {
		if (confirm('<?php echo $text_confirm; ?>')) {
			var node = this;

			$.ajax({
				url: 'index.php?route=' + $(node).val() + '&token=<?php echo $token; ?>',
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
<?php echo $footer; ?>