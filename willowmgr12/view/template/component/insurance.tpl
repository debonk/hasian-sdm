<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-insurance" data-toggle="tooltip" title="<?= $button_save; ?>"
					class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_edit; ?>
				</h3>
			</div>
			<div class="panel-body">
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-insurance"
					class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-min-wage">
							<?= $entry_min_wage; ?>
						</label>
						<div class="col-sm-9">
							<input type="text" name="insurance_min_wage" value="<?= $insurance_min_wage; ?>"
								placeholder="<?= $entry_min_wage; ?>" id="input-min-wage" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-date-start">
							<?= $entry_date_start; ?>
						</label>
						<div class="col-sm-9">
							<div class="input-group date">
								<input type="text" name="insurance_date_start" value="<?= $insurance_date_start; ?>"
									placeholder="<?= $entry_date_start; ?>" id="input-date-start" class="form-control"
									data-date-format="MMM YYYY" />
								<span class="input-group-btn">
									<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
								</span>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-min-wage-old">
							<?= $entry_min_wage_old; ?>
						</label>
						<div class="col-sm-9">
							<input type="text" name="insurance_min_wage_old" value="<?= $insurance_min_wage_old; ?>"
								placeholder="<?= $entry_min_wage_old; ?>" id="input-min-wage-old" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-calculation-base">
							<?= $entry_calculation_base; ?>
						</label>
						<div class="col-sm-9">
							<label class="radio-inline">
								<input type="radio" name="insurance_calculation_base" value="wage_min"
									<?=$insurance_calculation_base=='wage_min' ? 'checked' : '' ; ?> />
								<?= $text_wage_min; ?>
							</label>
							<label class="radio-inline">
								<input type="radio" name="insurance_calculation_base" value="wage_real"
									<?=$insurance_calculation_base=='wage_real' ? 'checked' : '' ; ?> />
								<?= $text_wage_real; ?>
							</label>
							<label class="radio-inline">
								<input type="radio" name="insurance_calculation_base" value="wage_both"
									<?=$insurance_calculation_base=='wage_both' ? 'checked' : '' ; ?> />
								<?= $text_wage_both; ?>
							</label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-activation-health"><span data-toggle="tooltip"
								title="<?= $help_activation; ?>">
								<?= $entry_activation_health; ?>
							</span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="insurance_activation_health" value="<?= $insurance_activation_health; ?>"
								placeholder="<?= $entry_activation_health; ?>" id="input-activation-health" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-activation-non-jht"><span data-toggle="tooltip"
								title="<?= $help_activation; ?>">
								<?= $entry_activation_non_jht; ?>
							</span></label>
						<div class="col-sm-9">
							<input type="text" name="insurance_activation_non_jht" value="<?= $insurance_activation_non_jht; ?>"
								placeholder="<?= $entry_activation_non_jht; ?>" id="input-activation-non-jht" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-activation-jht"><span data-toggle="tooltip"
								title="<?= $help_activation; ?>">
								<?= $entry_activation_jht; ?>
							</span>
						</label>
						<div class="col-sm-9">
							<input type="text" name="insurance_activation_jht" value="<?= $insurance_activation_jht; ?>"
								placeholder="<?= $entry_activation_jht; ?>" id="input-activation-jht" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-status">
							<?= $entry_status; ?>
						</label>
						<div class="col-sm-9">
							<select name="insurance_status" id="input-status" class="form-control">
								<?php if ($insurance_status) { ?>
								<option value="1" selected="selected">
									<?= $text_enabled; ?>
								</option>
								<option value="0">
									<?= $text_disabled; ?>
								</option>
								<?php } else { ?>
								<option value="1">
									<?= $text_enabled; ?>
								</option>
								<option value="0" selected="selected">
									<?= $text_disabled; ?>
								</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-sort-order">
							<?= $entry_sort_order; ?>
						</label>
						<div class="col-sm-9">
							<input type="text" name="insurance_sort_order" value="<?= $insurance_sort_order; ?>"
								placeholder="<?= $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('.date').datetimepicker({
			minViewMode: 'months',
			pickTime: false
		});
	</script>
</div>
<?= $footer; ?>