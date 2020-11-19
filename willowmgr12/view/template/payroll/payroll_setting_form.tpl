<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-save" form="form-payroll-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" id="form-payroll-setting" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-presence-status" data-toggle="tab"><?php echo $tab_presence_status; ?></a></li>
            <li><a href="#tab-payroll-status" data-toggle="tab"><?php echo $tab_payroll_status; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_presence_lock; ?>"><?php echo $entry_presence_lock; ?></span></label>
                <div class="col-sm-10">
                  <div class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($locations as $location) { ?>
                      <div class="checkbox">
                        <label>
                          <?php if (in_array($location['location_id'], $payroll_setting_presence_lock)) { ?>
                            <input type="checkbox" name="payroll_setting_presence_lock[]" value="<?php echo $location['location_id']; ?>" checked="checked" />
                            <?php echo $location['name']; ?>
                          <?php } else { ?>
                            <input type="checkbox" name="payroll_setting_presence_lock[]" value="<?php echo $location['location_id']; ?>" />
                            <?php echo $location['name']; ?>
                          <?php } ?>
                        </label>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-default-hke"><span data-toggle="tooltip" title="<?php echo $help_default_hke; ?>"><?php echo $entry_default_hke; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="payroll_setting_default_hke" value="<?php echo $payroll_setting_default_hke; ?>" class="form-control" id="input-default-hke" />
                  <?php if ($error_default_hke) { ?>
                    <div class="text-danger"><?php echo $error_default_hke; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-vacation-limit"><span data-toggle="tooltip" title="<?php echo $help_vacation_limit; ?>"><?php echo $entry_vacation_limit; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="payroll_setting_vacation_limit" value="<?php echo $payroll_setting_vacation_limit; ?>" class="form-control" id="input-vacation-limit" />
                  <?php if ($error_vacation_limit) { ?>
                    <div class="text-danger"><?php echo $error_vacation_limit; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-schedule-lock"><span data-toggle="tooltip" title="<?php echo $help_schedule_lock; ?>"><?php echo $entry_schedule_lock; ?></span></label>
                <div class="col-sm-10">
				  <select name="payroll_setting_schedule_lock" id="input-schedule-lock" class="form-control">
					<?php if ($payroll_setting_schedule_lock) { ?>
					<option value="1" selected="selected"><?php echo $text_yes; ?></option>
					<option value="0"><?php echo $text_no; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_yes; ?></option>
					<option value="0" selected="selected"><?php echo $text_no; ?></option>
					<?php } ?>
				  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-login-start"><span data-toggle="tooltip" title="<?php echo $help_login_start; ?>"><?php echo $entry_login_start; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="payroll_setting_login_start" value="<?php echo $payroll_setting_login_start; ?>" class="form-control" id="input-login-start" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-login-end"><span data-toggle="tooltip" title="<?php echo $help_login_end; ?>"><?php echo $entry_login_end; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="payroll_setting_login_end" value="<?php echo $payroll_setting_login_end; ?>" class="form-control" id="input-login-end" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-logout-start"><span data-toggle="tooltip" title="<?php echo $help_logout_start; ?>"><?php echo $entry_logout_start; ?></span></label>
                <div class="col-sm-10">
                  <input type="text" name="payroll_setting_logout_start" value="<?php echo $payroll_setting_logout_start; ?>" class="form-control" id="input-logout-start" />
                </div>
              </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-use-fingerprint"><span data-toggle="tooltip" title="<?php echo $help_use_fingerprint; ?>"><?php echo $entry_use_fingerprint; ?></span></label>
				<div class="col-sm-10">
				  <select name="payroll_setting_use_fingerprint" id="input-use-fingerprint" class="form-control">
					<?php if ($payroll_setting_use_fingerprint) { ?>
					<option value="1" selected="selected"><?php echo $text_yes; ?></option>
					<option value="0"><?php echo $text_no; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_yes; ?></option>
					<option value="0" selected="selected"><?php echo $text_no; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-schedule-check"><span data-toggle="tooltip" title="<?php echo $help_schedule_check; ?>"><?php echo $entry_schedule_check; ?></span></label>
				<div class="col-sm-10">
				  <select name="payroll_setting_schedule_check" id="input-schedule-check" class="form-control">
					<?php if ($payroll_setting_schedule_check) { ?>
					<option value="1" selected="selected"><?php echo $text_yes; ?></option>
					<option value="0"><?php echo $text_no; ?></option>
					<?php } else { ?>
					<option value="1"><?php echo $text_yes; ?></option>
					<option value="0" selected="selected"><?php echo $text_no; ?></option>
					<?php } ?>
				  </select>
				</div>
			  </div>
            </div>
            <div class="tab-pane" id="tab-presence-status">
              <!-- Presence Status List: h, s, i, ... -->
              <?php foreach ($presence_items as $presence_item) { ?>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-status-<?php echo $presence_item; ?>"><?php echo $entry_status[$presence_item]; ?></label>
                  <div class="col-sm-10">
                      <select name="payroll_setting_id_<?php echo $presence_item; ?>" id="input-status-<?php echo $presence_item; ?>" class="form-control">
                        <?php foreach ($presence_statuses as $presence_status) { ?>
                          <?php if ($presence_status['presence_status_id'] == $payroll_setting_id[$presence_item]) { ?>
                            <option value="<?php echo $presence_status['presence_status_id']; ?>" selected="selected"><?php echo $presence_status['name']; ?></option>
                          <?php } else { ?>
                            <option value="<?php echo $presence_status['presence_status_id']; ?>"><?php echo $presence_status['name']; ?></option>
                          <?php } ?>
                        <?php } ?>
                      </select>
                  </div>
                </div>
              <?php } ?>
            <div class="form-group required">
              <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_presence_statuses; ?>"><?php echo $entry_presence_statuses; ?></span></label>
              <div class="col-sm-10">
                <div class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($presence_statuses as $presence_status) { ?>
                  <div class="col-sm-4">
                    <div class="checkbox">
                      <label>
                        <?php if (in_array($presence_status['presence_status_id'], $payroll_setting_presence_status_ids)) { ?>
                          <input type="checkbox" name="payroll_setting_presence_status_ids[]" value="<?php echo $presence_status['presence_status_id']; ?>" checked="checked" />
                          <?php echo $presence_status['name']; ?>
                        <?php } else { ?>
                          <input type="checkbox" name="payroll_setting_presence_status_ids[]" value="<?php echo $presence_status['presence_status_id']; ?>" />
                          <?php echo $presence_status['name']; ?>
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
                <label class="col-sm-2 control-label" for="input-pending-status"><span data-toggle="tooltip" title="<?php echo $help_pending_status; ?>"><?php echo $entry_pending_status; ?></span></label>
                <div class="col-sm-10">
                  <select name="payroll_setting_pending_status_id" id="input-pending-status" class="form-control">
                    <?php foreach ($payroll_statuses as $payroll_status) { ?>
                      <?php if ($payroll_status['payroll_status_id'] == $payroll_setting_pending_status_id) { ?>
                        <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-processing-status"><span data-toggle="tooltip" title="<?php echo $help_processing_status; ?>"><?php echo $entry_processing_status; ?></span></label>
                <div class="col-sm-10">
                <select name="payroll_setting_processing_status_id" id="input-processing-status" class="form-control">
                  <?php foreach ($payroll_statuses as $payroll_status) { ?>
                  <?php if ($payroll_status['payroll_status_id'] == $payroll_setting_processing_status_id) { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-submitted-status"><span data-toggle="tooltip" title="<?php echo $help_submitted_status; ?>"><?php echo $entry_submitted_status; ?></span></label>
                <div class="col-sm-10">
                <select name="payroll_setting_submitted_status_id" id="input-submitted-status" class="form-control">
                  <?php foreach ($payroll_statuses as $payroll_status) { ?>
                  <?php if ($payroll_status['payroll_status_id'] == $payroll_setting_submitted_status_id) { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-generated-status"><span data-toggle="tooltip" title="<?php echo $help_generated_status; ?>"><?php echo $entry_generated_status; ?></span></label>
                <div class="col-sm-10">
                <select name="payroll_setting_generated_status_id" id="input-generated-status" class="form-control">
                  <?php foreach ($payroll_statuses as $payroll_status) { ?>
                  <?php if ($payroll_status['payroll_status_id'] == $payroll_setting_generated_status_id) { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-approved-status"><span data-toggle="tooltip" title="<?php echo $help_approved_status; ?>"><?php echo $entry_approved_status; ?></span></label>
                <div class="col-sm-10">
                <select name="payroll_setting_approved_status_id" id="input-approved-status" class="form-control">
                  <?php foreach ($payroll_statuses as $payroll_status) { ?>
                  <?php if ($payroll_status['payroll_status_id'] == $payroll_setting_approved_status_id) { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-released-status"><span data-toggle="tooltip" title="<?php echo $help_released_status; ?>"><?php echo $entry_released_status; ?></span></label>
                <div class="col-sm-10">
                <select name="payroll_setting_released_status_id" id="input-released-status" class="form-control">
                  <?php foreach ($payroll_statuses as $payroll_status) { ?>
                  <?php if ($payroll_status['payroll_status_id'] == $payroll_setting_released_status_id) { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-completed-status"><span data-toggle="tooltip" title="<?php echo $help_completed_status; ?>"><?php echo $entry_completed_status; ?></span></label>
                <div class="col-sm-10">
                <select name="payroll_setting_completed_status_id" id="input-completed-status" class="form-control">
                  <?php foreach ($payroll_statuses as $payroll_status) { ?>
                  <?php if ($payroll_status['payroll_status_id'] == $payroll_setting_completed_status_id) { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
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
<?php echo $footer; ?> 
