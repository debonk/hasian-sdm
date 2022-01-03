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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label" for="input-date-start">
									<?= $entry_date_start; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_start" value="<?= $filter_date_start; ?>"
										placeholder="<?= $entry_date_start; ?>" data-date-format="YYYY-MM-DD" id="input-date-start"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label" for="input-date-end">
									<?= $entry_date_end; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter_date_end" value="<?= $filter_date_end; ?>"
										placeholder="<?= $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end"
										class="form-control" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label" for="input-user">
									<?= $entry_user; ?>
								</label>
								<input type="text" name="filter_user" value="<?= $filter_user; ?>" id="input-user"
									class="form-control" />
							</div>
							<div class="form-group">
								<label class="control-label" for="input-ip">
									<?= $entry_ip; ?>
								</label>
								<input type="text" name="filter_ip" value="<?= $filter_ip; ?>" id="input-ip" class="form-control" />
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<td class="text-left">
									<?= $column_comment; ?>
								</td>
								<td class="text-left">
									<?= $column_ip; ?>
								</td>
								<td class="text-left">
									<?= $column_date_added; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php if ($activities) { ?>
							<?php foreach ($activities as $activity) { ?>
							<tr>
								<td class="text-left">
									<?= $activity['comment']; ?>
								</td>
								<td class="text-left">
									<?= $activity['ip']; ?>
								</td>
								<td class="text-left">
									<?= $activity['date_added']; ?>
								</td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="4">
									<?= $text_no_results; ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
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
		$('#button-filter').on('click', function () {
			url = 'index.php?route=report/user_activity&token=<?= $token; ?>';

			var filter_user = $('input[name=\'filter_user\']').val();

			if (filter_user) {
				url += '&filter_user=' + encodeURIComponent(filter_user);
			}
			var filter_ip = $('input[name=\'filter_ip\']').val();

			if (filter_ip) {
				url += '&filter_ip=' + encodeURIComponent(filter_ip);
			}

			var filter_date_start = $('input[name=\'filter_date_start\']').val();

			if (filter_date_start) {
				url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
			}

			var filter_date_end = $('input[name=\'filter_date_end\']').val();

			if (filter_date_end) {
				url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
			}

			location = url;
		});
	</script>
	<script type="text/javascript">
		$('.date').datetimepicker({
			pickTime: false
		});

		$(document).keypress(function (e) {
			if (e.which == 13) {
				$('#button-filter').click();
			}
		});
	</script>
</div>
<?= $footer; ?>