<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-batch" data-toggle="tooltip" title="<?= $button_save; ?>"
					class="btn btn-primary">
					<i class="fa fa-save"></i>
				</button>
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default">
					<i class="fa fa-reply"></i>
				</a>
			</div>
			<h1><?= $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i>
			<?= $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_form; ?></h3>
			</div>
			<div class="panel-body">

				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-batch"
					class="form-horizontal">

					<!-- ======================================================
                         Section 1: Basic Info
                         ====================================================== -->
					<fieldset>
						<legend><?= $text_basic_info; ?></legend>

						<!-- Name -->
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-name"><?= $entry_name; ?></label>
							<div class="col-sm-10">
								<input type="text" name="name" value="<?= $name; ?>" id="input-name"
									class="form-control" placeholder="<?= $help_name; ?>" required />
								<?php if (!empty($error_name)) { ?>
								<div class="text-danger"><?= $error_name; ?></div>
								<?php } ?>
							</div>
						</div>

						<!-- Date -->
						<div class="form-group required">
							<label class="col-sm-2 control-label" for="input-date"><?= $entry_date; ?></label>
							<div class="col-sm-4">
								<div class="input-group date">
									<input type="text" name="date" value="<?= $date; ?>"
										placeholder="<?= $entry_date; ?>" id="input-date" class="form-control"
										data-date-format="D MMM YYYY" />
									<span class="input-group-btn">
										<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
									</span>
								</div>
								<?php if (!empty($error_date)) { ?>
								<div class="text-danger"><?= $error_date; ?></div>
								<?php } ?>
							</div>
						</div>

						<!-- Schedule Type -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-schedule-type">
								<span data-toggle="tooltip" title="<?= $help_schedule_type; ?>">
									<?= $entry_schedule_type; ?>
								</span>
							</label>
							<div class="col-sm-10">
								<select name="schedule_type_id" id="input-schedule-type" class="form-control">
									<option value="0"><?= $text_no_shift; ?></option>
									<?php foreach ($schedule_types as $st) { ?>
									<option value="<?= $st['schedule_type_id']; ?>"
										<?= ($st['schedule_type_id'] == $schedule_type_id) ? 'selected' : ''; ?>>
										<?= $st['text']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>

						<!-- Presence Status -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-presence-status">
								<?= $entry_presence_status; ?>
							</label>
							<div class="col-sm-10">
								<select name="presence_status_id" id="input-presence-status" class="form-control">
									<option value=""><?= $text_select; ?></option>
									<?php foreach ($presence_statuses as $ps) { ?>
									<option value="<?= $ps['presence_status_id']; ?>"
										<?= ($ps['presence_status_id'] == $presence_status_id) ? 'selected' : ''; ?>>
										<?= $ps['name']; ?>
									</option>
									<?php } ?>
								</select>
							</div>
						</div>

						<!-- Description -->
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-description">
								<?= $entry_description; ?>
							</label>
							<div class="col-sm-10">
								<input type="text" name="description" value="<?= $description; ?>"
									id="input-description" class="form-control" placeholder="Optional note or reason" />
							</div>
						</div>
					</fieldset>

					<hr />

					<!-- ======================================================
                         Section 2: Filter Rules
                         ====================================================== -->
					<fieldset>
						<legend><?= $text_filter_rule; ?></legend>

						<!-- Location Filter -->
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span data-toggle="tooltip" title="<?= $help_rule; ?>"><?= $entry_location; ?></span>
							</label>
							<div class="col-sm-10">
								<div class="checkbox">
									<label>
										<input type="checkbox" id="check-loc-all" />
										<strong><?= $text_select_all; ?></strong>
									</label>
								</div>
								<div class="well well-sm" style="max-height: 200px; overflow-y: auto;">
									<?php foreach ($locations as $loc) { ?>
									<div class="checkbox" style="margin: 3px 0;">
										<label>
											<input type="checkbox" name="filter[location][]"
												value="<?= $loc['location_id']; ?>" class="filter-loc"
												<?= in_array($loc['location_id'], (array)$filter['location']) ? 'checked' : ''; ?> />
											<?= $loc['name']; ?>
										</label>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>

						<!-- Customer Group Filter -->
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span data-toggle="tooltip" title="<?= $help_rule; ?>"><?= $entry_customer_group; ?></span>
							</label>
							<div class="col-sm-10">
								<div class="checkbox">
									<label>
										<input type="checkbox" id="check-cg-all" />
										<strong><?= $text_select_all; ?></strong>
									</label>
								</div>
								<div class="well well-sm" style="max-height: 200px; overflow-y: auto;">
									<?php foreach ($customer_groups as $cg) { ?>
									<div class="checkbox" style="margin: 3px 0;">
										<label>
											<input type="checkbox" name="filter[customer_group][]"
												value="<?= $cg['customer_group_id']; ?>" class="filter-cg"
												<?= in_array($cg['customer_group_id'], (array)$filter['customer_group']) ? 'checked' : ''; ?> />
											<?= $cg['name']; ?>
										</label>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>

						<!-- Customer Department Filter -->
						<div class="form-group">
							<label class="col-sm-2 control-label">
								<span data-toggle="tooltip" title="<?= $help_rule; ?>"><?= $entry_customer_department; ?></span>
							</label>
							<div class="col-sm-10">
								<div class="checkbox">
									<label>
										<input type="checkbox" id="check-cd-all" />
										<strong><?= $text_select_all; ?></strong>
									</label>
								</div>
								<div class="well well-sm" style="max-height: 200px; overflow-y: auto;">
									<?php foreach ($customer_departments as $cd) { ?>
									<div class="checkbox" style="margin: 3px 0;">
										<label>
											<input type="checkbox" name="filter[customer_department][]"
												value="<?= $cd['customer_department_id']; ?>" class="filter-cd"
												<?= in_array($cd['customer_department_id'], (array)$filter['customer_department']) ? 'checked' : ''; ?> />
											<?= $cd['name']; ?>
										</label>
									</div>
									<?php } ?>
								</div>
							</div>
						</div>

					</fieldset>

				</form>

			</div><!-- /.panel-body -->
		</div><!-- /.panel -->
	</div><!-- /.container-fluid -->
</div><!-- /#content -->

<script type="text/javascript">
	$('.date').datetimepicker({ pickTime: false });

	function setupCheckAll(masterId, childClass) {
		var $master = $(masterId);
		var $children = $(childClass);

		$master.on('change', function () {
			$children.prop('checked', this.checked);
		});

		$children.on('change', function () {
			$master.prop('checked', $children.length === $children.filter(':checked').length);
		});
	}

	setupCheckAll('#check-loc-all', '.filter-loc');
	setupCheckAll('#check-cg-all', '.filter-cg');
	setupCheckAll('#check-cd-all', '.filter-cd');
</script>

<?= $footer; ?>
