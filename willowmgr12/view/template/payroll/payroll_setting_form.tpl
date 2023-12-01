<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" id="button-save" form="form-payroll-setting" data-toggle="tooltip"
					title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
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
				<form method="post" action="<?= $action; ?>" enctype="multipart/form-data" id="form-payroll-setting"
					class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab">
								<?= $tab_general; ?>
							</a></li>
						<li><a href="#tab-presence-status" data-toggle="tab">
								<?= $tab_presence_status; ?>
							</a></li>
						<li><a href="#tab-payroll-status" data-toggle="tab">
								<?= $tab_payroll_status; ?>
							</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<div class="form-group">
								<label class="col-sm-2 control-label"><span data-toggle="tooltip"
										title="<?= $help_presence_lock; ?>">
										<?= $entry_presence_lock; ?>
									</span></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($locations as $location) { ?>
										<div class="checkbox">
											<label>
												<?php if (in_array($location['location_id'], $payroll_setting_presence_lock)) { ?>
												<input type="checkbox" name="payroll_setting_presence_lock[]"
													value="<?= $location['location_id']; ?>" checked="checked" />
												<?= $location['name']; ?>
												<?php } else { ?>
												<input type="checkbox" name="payroll_setting_presence_lock[]"
													value="<?= $location['location_id']; ?>" />
												<?= $location['name']; ?>
												<?php } ?>
											</label>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-default-hke"><span
										data-toggle="tooltip" title="<?= $help_default_hke; ?>">
										<?= $entry_default_hke; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_default_hke"
										value="<?= $payroll_setting_default_hke; ?>" class="form-control"
										id="input-default-hke" />
									<?php if ($error_default_hke) { ?>
									<div class="text-danger">
										<?= $error_default_hke; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-vacation-limit"><span
										data-toggle="tooltip" title="<?= $help_vacation_limit; ?>">
										<?= $entry_vacation_limit; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_vacation_limit"
										value="<?= $payroll_setting_vacation_limit; ?>" class="form-control"
										id="input-vacation-limit" />
									<?php if ($error_vacation_limit) { ?>
									<div class="text-danger">
										<?= $error_vacation_limit; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-schedule-lock"><span
										data-toggle="tooltip" title="<?= $help_schedule_lock; ?>">
										<?= $entry_schedule_lock; ?>
									</span></label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<?php if ($payroll_setting_schedule_lock) { ?>
										<input type="radio" name="payroll_setting_schedule_lock" value="1"
											checked="checked" />
										<?= $text_yes; ?>
										<?php } else { ?>
										<input type="radio" name="payroll_setting_schedule_lock" value="1" />
										<?= $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$payroll_setting_schedule_lock) { ?>
										<input type="radio" name="payroll_setting_schedule_lock" value="0"
											checked="checked" />
										<?= $text_no; ?>
										<?php } else { ?>
										<input type="radio" name="payroll_setting_schedule_lock" value="0" />
										<?= $text_no; ?>
										<?php } ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><span data-toggle="tooltip"
										title="<?= $help_presence_card; ?>">
										<?= $entry_presence_card; ?>
									</span></label>
								<div class="col-sm-10">
									<?php foreach ($presence_cards as $presence_card) { ?>
									<div>
										<label class="radio-inline">
											<input type="radio" name="payroll_setting_presence_card"
												value="<?= $presence_card['value']; ?>"
												<?=$payroll_setting_presence_card==$presence_card['value'] ? 'checked'
												: '' ; ?> />
											<?= $presence_card['text']; ?>
										</label>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-login-session">
									<?= $entry_login_session; ?>
								</label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_login_session"
										value="<?= $payroll_setting_login_session; ?>" class="form-control"
										id="input-login-session" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-login-date"><span
										data-toggle="tooltip" title="<?= $help_login_date; ?>">
										<?= $entry_login_date; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_login_date"
										value="<?= $payroll_setting_login_date; ?>" class="form-control"
										id="input-login-date" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-logout-date"><span
										data-toggle="tooltip" title="<?= $help_logout_date; ?>">
										<?= $entry_logout_date; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_logout_date"
										value="<?= $payroll_setting_logout_date; ?>" class="form-control"
										id="input-logout-date" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-login-start"><span
										data-toggle="tooltip" title="<?= $help_login_start; ?>">
										<?= $entry_login_start; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_login_start"
										value="<?= $payroll_setting_login_start; ?>" class="form-control"
										id="input-login-start" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-login-end"><span data-toggle="tooltip"
										title="<?= $help_login_end; ?>">
										<?= $entry_login_end; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_login_end"
										value="<?= $payroll_setting_login_end; ?>" class="form-control"
										id="input-login-end" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-logout-start"><span
										data-toggle="tooltip" title="<?= $help_logout_start; ?>">
										<?= $entry_logout_start; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_logout_start"
										value="<?= $payroll_setting_logout_start; ?>" class="form-control"
										id="input-logout-start" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><span data-toggle="tooltip"
										title="<?= $help_use_fingerprint; ?>">
										<?= $entry_use_fingerprint; ?>
									</span></label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<?php if ($payroll_setting_use_fingerprint) { ?>
										<input type="radio" name="payroll_setting_use_fingerprint" value="1"
											checked="checked" />
										<?= $text_yes; ?>
										<?php } else { ?>
										<input type="radio" name="payroll_setting_use_fingerprint" value="1" />
										<?= $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$payroll_setting_use_fingerprint) { ?>
										<input type="radio" name="payroll_setting_use_fingerprint" value="0"
											checked="checked" />
										<?= $text_no; ?>
										<?php } else { ?>
										<input type="radio" name="payroll_setting_use_fingerprint" value="0" />
										<?= $text_no; ?>
										<?php } ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label"><span data-toggle="tooltip"
										title="<?= $help_schedule_check; ?>">
										<?= $entry_schedule_check; ?>
									</span></label>
								<div class="col-sm-10">
									<label class="radio-inline">
										<?php if ($payroll_setting_schedule_check) { ?>
										<input type="radio" name="payroll_setting_schedule_check" value="1"
											checked="checked" />
										<?= $text_yes; ?>
										<?php } else { ?>
										<input type="radio" name="payroll_setting_schedule_check" value="1" />
										<?= $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$payroll_setting_schedule_check) { ?>
										<input type="radio" name="payroll_setting_schedule_check" value="0"
											checked="checked" />
										<?= $text_no; ?>
										<?php } else { ?>
										<input type="radio" name="payroll_setting_schedule_check" value="0" />
										<?= $text_no; ?>
										<?php } ?>
									</label>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-completed-after"><span
										data-toggle="tooltip" title="<?= $help_completed_after; ?>">
										<?= $entry_completed_after; ?>
									</span></label>
								<div class="col-sm-10">
									<input type="text" name="payroll_setting_completed_after"
										value="<?= $payroll_setting_completed_after; ?>" class="form-control"
										id="input-completed_after" />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-presence-status">
							<!-- Presence Status List: h, s, i, ... -->
							<?php foreach ($presence_items as $presence_item) { ?>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-status-<?= $presence_item; ?>">
									<?= $entry_status[$presence_item]; ?>
								</label>
								<div class="col-sm-10">
									<select name="payroll_setting_id_<?= $presence_item; ?>"
										id="input-status-<?= $presence_item; ?>" class="form-control">
										<?php foreach ($presence_statuses as $presence_status) { ?>
										<?php if ($presence_status['presence_status_id'] == $payroll_setting_id[$presence_item]) { ?>
										<option value="<?= $presence_status['presence_status_id']; ?>"
											selected="selected">
											<?= $presence_status['name']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $presence_status['presence_status_id']; ?>">
											<?= $presence_status['name']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<?php } ?>
							<div class="form-group required">
								<label class="col-sm-2 control-label"><span data-toggle="tooltip"
										title="<?= $help_presence_statuses; ?>">
										<?= $entry_presence_statuses; ?>
									</span></label>
								<div class="col-sm-10">
									<div class="well well-sm" style="height: 150px; overflow: auto;">
										<?php foreach ($presence_statuses as $presence_status) { ?>
										<div class="col-sm-4">
											<div class="checkbox">
												<label>
													<?php if (in_array($presence_status['presence_status_id'], $payroll_setting_presence_status_ids)) { ?>
													<input type="checkbox" name="payroll_setting_presence_status_ids[]"
														value="<?= $presence_status['presence_status_id']; ?>"
														checked="checked" />
													<?= $presence_status['name']; ?>
													<?php } else { ?>
													<input type="checkbox" name="payroll_setting_presence_status_ids[]"
														value="<?= $presence_status['presence_status_id']; ?>" />
													<?= $presence_status['name']; ?>
													<?php } ?>
												</label>
											</div>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-payroll-status">
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-pending-status"><span
										data-toggle="tooltip" title="<?= $help_pending_status; ?>">
										<?= $entry_pending_status; ?>
									</span></label>
								<div class="col-sm-10">
									<select name="payroll_setting_pending_status_id" id="input-pending-status"
										class="form-control">
										<?php foreach ($payroll_statuses as $payroll_status) { ?>
										<?php if ($payroll_status['payroll_status_id'] == $payroll_setting_pending_status_id) { ?>
										<option value="<?= $payroll_status['payroll_status_id']; ?>"
											selected="selected">
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
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-processing-status"><span
										data-toggle="tooltip" title="<?= $help_processing_status; ?>">
										<?= $entry_processing_status; ?>
									</span></label>
								<div class="col-sm-10">
									<select name="payroll_setting_processing_status_id" id="input-processing-status"
										class="form-control">
										<?php foreach ($payroll_statuses as $payroll_status) { ?>
										<?php if ($payroll_status['payroll_status_id'] == $payroll_setting_processing_status_id) { ?>
										<option value="<?= $payroll_status['payroll_status_id']; ?>"
											selected="selected">
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
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-submitted-status"><span
										data-toggle="tooltip" title="<?= $help_submitted_status; ?>">
										<?= $entry_submitted_status; ?>
									</span></label>
								<div class="col-sm-10">
									<select name="payroll_setting_submitted_status_id" id="input-submitted-status"
										class="form-control">
										<?php foreach ($payroll_statuses as $payroll_status) { ?>
										<?php if ($payroll_status['payroll_status_id'] == $payroll_setting_submitted_status_id) { ?>
										<option value="<?= $payroll_status['payroll_status_id']; ?>"
											selected="selected">
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
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-generated-status"><span
										data-toggle="tooltip" title="<?= $help_generated_status; ?>">
										<?= $entry_generated_status; ?>
									</span></label>
								<div class="col-sm-10">
									<select name="payroll_setting_generated_status_id" id="input-generated-status"
										class="form-control">
										<?php foreach ($payroll_statuses as $payroll_status) { ?>
										<?php if ($payroll_status['payroll_status_id'] == $payroll_setting_generated_status_id) { ?>
										<option value="<?= $payroll_status['payroll_status_id']; ?>"
											selected="selected">
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
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-approved-status"><span
										data-toggle="tooltip" title="<?= $help_approved_status; ?>">
										<?= $entry_approved_status; ?>
									</span></label>
								<div class="col-sm-10">
									<select name="payroll_setting_approved_status_id" id="input-approved-status"
										class="form-control">
										<?php foreach ($payroll_statuses as $payroll_status) { ?>
										<?php if ($payroll_status['payroll_status_id'] == $payroll_setting_approved_status_id) { ?>
										<option value="<?= $payroll_status['payroll_status_id']; ?>"
											selected="selected">
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
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-released-status"><span
										data-toggle="tooltip" title="<?= $help_released_status; ?>">
										<?= $entry_released_status; ?>
									</span></label>
								<div class="col-sm-10">
									<select name="payroll_setting_released_status_id" id="input-released-status"
										class="form-control">
										<?php foreach ($payroll_statuses as $payroll_status) { ?>
										<?php if ($payroll_status['payroll_status_id'] == $payroll_setting_released_status_id) { ?>
										<option value="<?= $payroll_status['payroll_status_id']; ?>"
											selected="selected">
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
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-completed-status"><span
										data-toggle="tooltip" title="<?= $help_completed_status; ?>">
										<?= $entry_completed_status; ?>
									</span></label>
								<div class="col-sm-10">
									<select name="payroll_setting_completed_status_id" id="input-completed-status"
										class="form-control">
										<?php foreach ($payroll_statuses as $payroll_status) { ?>
										<?php if ($payroll_status['payroll_status_id'] == $payroll_setting_completed_status_id) { ?>
										<option value="<?= $payroll_status['payroll_status_id']; ?>"
											selected="selected">
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
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<?= $footer; ?>