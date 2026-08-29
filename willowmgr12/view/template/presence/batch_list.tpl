<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary">
					<i class="fa fa-plus"></i>
				</a>
				<button type="button" form="form-batch" data-toggle="tooltip" title="<?= $button_delete; ?>"
					class="btn btn-danger"
					onclick="confirm('<?= $text_confirm; ?>') ? $('#form-batch').submit() : false;">
					<i class="fa fa-trash-o"></i>
				</button>
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
				<h3 class="panel-title"><i class="fa fa-list"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="flex-container">
						<div class="flex-item">
							<div class="form-group">
								<label class="control-label" for="input-name">
									<?= $entry_name; ?>
								</label>
								<input type="text" name="filter[name]" value="<?= $filter['name']; ?>"
									placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
							</div>
						</div>
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
								<label class="control-label" for="input-date">
									<?= $entry_date; ?>
								</label>
								<div class="input-group date">
									<input type="text" name="filter[date]" value="<?= $filter['date']; ?>"
										placeholder="<?= $entry_date; ?>" id="input-date" class="form-control"
										data-date-format="D MMM YYYY" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i
												class="fa fa-calendar"></i></button>
									</span>
								</div>
							</div>
						</div>
						<div>
							<div class="form-group">
								<label>&nbsp;</label>
								<div>
									<div class="btn-group" role="group">
										<button type="button" id="button-filter" class="btn btn-primary">
											<i class="fa fa-search"></i>
											<?= $button_filter; ?>
										</button>
										<a href="<?= $unfilter; ?>" type="button" id="button-unfilter"
											class="btn btn-info" data-toggle="tooltip" title="<?= $button_unfilter; ?>">
											<i class="fa fa-ban"></i>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-batch">
					<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<td style="width: 1px;" class="text-center">
										<input type="checkbox"
											onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
									</td>
									<th>
										<a href="<?= $sort_name; ?>" <?=($sort=='name' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?>>
											<?= $column_name; ?>
										</a>
									</th>
									<th>
										<a href="<?= $sort_date; ?>" <?=($sort=='date' ) ? 'class="' .
											strtolower($order) . '"' : '' ; ?>>
											<?= $column_date; ?>
										</a>
									</th>
									<th class="text-center">
										<?= $column_schedule_type; ?>
									</th>
									<th>
										<?= $column_presence_status; ?>
									</th>
									<th>
										<?= $column_rules; ?>
									</th>
									<th>
										<?= $column_description; ?>
									</th>
									<th>
										<?= $column_username; ?>
									</th>
									<th class="text-right">
										<?= $column_action; ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php if ($batches) { ?>
								<?php foreach ($batches as $batch) { ?>
								<tr>
									<td class="text-center">
										<?php if (in_array($batch['batch_id'], $selected)) { ?>
										<input type="checkbox" name="selected[]" value="<?= $batch['batch_id']; ?>"
											checked="checked" />
										<?php } else { ?>
										<input type="checkbox" name="selected[]" value="<?= $batch['batch_id']; ?>" />
										<?php } ?>
									</td>
									<td>
										<?= $batch['name']; ?>
									</td>
									<td>
										<?= $batch['date']; ?>
									</td>
									<td class="text-center">
										<?= $batch['schedule_type']; ?>
									</td>
									<td>
										<?= $batch['presence_status']; ?>
									</td>
									<td><small class="text-muted">
											<?= $batch['rules_summary']; ?>
										</small></td>
									<td>
										<?= $batch['description']; ?>
									</td>
									<td>
										<?= $batch['username']; ?>
									</td>
									<td class="text-right">
										<a href="<?= $batch['edit']; ?>" class="btn btn-primary" data-toggle="tooltip"
											title="<?= $button_edit; ?>">
											<i class="fa fa-pencil"></i>
										</a>
									</td>
								</tr>
								<?php } ?>
								<?php } else { ?>
								<tr>
									<td class="text-center" colspan="9">
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
</div>

<script type="text/javascript">
	$(document).keypress(function (e) {
		if (e.which == 13) {
			$("#button-filter").click();
		}
	});

	$('#button-filter').on('click', function () {
		url = 'index.php?route=presence/batch&token=<?= $token; ?>';
		let filter_items = JSON.parse('<?= $filter_items; ?>');
		for (let i = 0; i < filter_items.length; i++) {
			let val = $('.well [name="filter[' + filter_items[i] + ']"]').val();
			if (val && val !== '*') {
				url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(val);
			}
		}
		location = url;
	});

	$('.month').datetimepicker({ minViewMode: 'months', pickTime: false });
	$('.date').datetimepicker({ pickTime: false });
</script>

<?= $footer; ?>