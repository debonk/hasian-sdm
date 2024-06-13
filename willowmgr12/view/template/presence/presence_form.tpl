<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-presence-summary" data-toggle="tooltip"
					title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?php echo $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="row">
			<div class="col-sm-4" id="period-info"></div>
			<div class="col-sm-8" id="customer-info"></div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?php echo $text_form; ?>
				</h3>
			</div>
			<?php if ($customer_id) { ?>
			<div class="panel-body col-sm-8">
				<legend>
					<?php echo $text_presence_summary; ?>
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
				</div>
			</div>
			<div class="panel-body col-sm-4">
				<legend>
					<?php echo $text_late_summary; ?>
				</legend>
				<div class="table-responsive">
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
			<div class="panel-body">
				<form action="<?php echo $edit; ?>" method="post" enctype="multipart/form-data"
					id="form-presence-summary" class="form-horizontal">
					<legend>
						<?php echo $text_presence_detail; ?>
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
										<div class="text-center" data-toggle="tooltip"
											title="<?php echo $calendar[$week . $day]['note']; ?>">
											<p class="bg-<?php echo $calendar[$week . $day]['bg_class']; ?>"><i
													class="fa fa-clock-o"></i>
												<?php echo $calendar[$week . $day]['schedule_type']; ?></br>
												<i class="fa fa-sign-in"></i>
												<?php echo $calendar[$week . $day]['time_login'] . ' - ' . $calendar[$week . $day]['time_logout']; ?>
											</p>
										</div>
										<select name="<?php echo 'detail' . $calendar[$week . $day]['date']; ?>" id=""
											class="form-control">
											<?php if ($calendar[$week . $day]['locked']) { ?>
											<option value="<?php echo $calendar[$week . $day]['presence_status_id']; ?>"
												selected="selected">
												<?php echo $calendar[$week . $day]['presence_status']; ?>
											</option>
											<?php } else { ?>
											<option value="0">
												<?php echo '-'; ?>
											</option>
											<?php foreach ($presence_statuses as $presence_status) { ?>
											<?php if ($presence_status['presence_status_id'] == $calendar[$week . $day]['presence_status_id']) { ?>
											<option value="<?php echo $presence_status['presence_status_id']; ?>"
												selected="selected">
												<?php echo $presence_status['name']; ?>
											</option>
											<?php } else { ?>
											<option value="<?php echo $presence_status['presence_status_id']; ?>">
												<?php echo $presence_status['name']; ?>
											</option>
											<?php } ?>
											<?php } ?>
											<?php } ?>
										</select>
									</td>
									<?php } else { ?>
									<td class="text-center calendar-day-np"></td>
									<?php } ?>
									<?php } ?>
								</tr>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</form>
			</div>
			<div class="panel-body" id="absence-info"></div>
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

						// location.reload();
						location = 'index.php?route=presence/presence/edit&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?><?php echo $url; ?>';
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