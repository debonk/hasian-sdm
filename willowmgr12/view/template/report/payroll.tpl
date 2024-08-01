<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<!-- <div class="pull-right">
				<button type="button" id="button-export" class="btn btn-default"><i class="fa fa-upload"></i>
					<?= $button_export; ?>
				</button>
			</div> -->
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
		<div class="row">
			<div class="col-sm-6" id="period-info"></div>
			<div class="col-sm-6" id="payroll-info"></div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-bar-chart"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="flex-container">
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-period">
									<?= $entry_period; ?>
								</label>
								<div class="input-group month">
									<input type="text" name="filter[period]" value="<?= $filter['period']; ?>"
										placeholder="<?= $entry_period; ?>" id="input-period" class="form-control"
										data-date-format="MMM YYYY" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i
												class="fa fa-calendar-o"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-group">
									<?= $entry_group; ?>
								</label>
								<select name="filter[group]" id="input-group" class="form-control">
									<option value="">
										<?= $text_none ?>
									</option>
									<?php foreach ($groups as $group) { ?>
									<option value="<?= $group['value']; ?>" <?=$group['value']==$filter['group']
										? 'selected' : '' ; ?>>
										<?= $group['text']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div>
							<div class="form-group">
								<label>
									<?= '&nbsp;'; ?>
								</label>
								<div>
									<button type="button" id="button-filter" class="btn btn-primary pull-right"><i
											class="fa fa-search"></i>
										<?= $button_filter; ?>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="payroll-report"></div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#period-info').load('index.php?route=common/period_info&token=<?= $token . $url; ?>');
	$('#payroll-info').load('index.php?route=common/payroll_info&token=<?= $token . $url; ?>');

	$('#payroll-report').load('index.php?route=<?= $report_route; ?>&token=<?= $token . $url; ?>');

	$('#payroll-report').on('click', '.pagination a', function (e) {
		e.preventDefault();

		$('#payroll-report').load(this.href);
	});

	$('#payroll-report').on('click', 'td a', function (e) {
		e.preventDefault();

		$('#payroll-report').load(this.href);
	});

	// $('#button-export').on('click', function () {
	// 	url = 'index.php?route=report/payroll/export&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>';

	// 	location = url;
	// });
</script>
<script type="text/javascript">
	$('#button-filter').on('click', function () {
		url = 'index.php?route=report/payroll&token=<?= $token; ?>';

		let filter = [];

		let filter_items = JSON.parse('<?= $filter_items; ?>');

		for (let i = 0; i < filter_items.length; i++) {
			filter[filter_items[i]] = $('.well [name=\'filter[' + filter_items[i] + ']\']').val();

			if (filter[filter_items[i]] && filter[filter_items[i]] != '*') {
				url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
			}
		}

		location = url;
	});

	$('.month').datetimepicker({
		minViewMode: 'months',
		pickTime: false
	});
</script>
<?= $footer; ?>