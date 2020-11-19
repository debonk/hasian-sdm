<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="address">
                    <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <li><a href="#tab-additional" data-toggle="tab"><?php echo $tab_additional; ?></a></li>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <li><a href="#tab-address<?php echo $address_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('#address a:first').tab('show'); $('#address a[href=\'#tab-address<?php echo $address_row; ?>\']').parent().remove(); $('#tab-address<?php echo $address_row; ?>').remove();"></i> <?php echo $tab_address . ' ' . $address_row; ?></a></li>
                    <?php $address_row++; ?>
                    <?php } ?>
                    <li id="address-add"><a onclick="addAddress();"><i class="fa fa-plus-circle"></i> <?php echo $button_address_add; ?></a></li>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
					  <div class="form-group">
						<label class="col-sm-3 control-label" for="input-image"><?php echo $entry_image; ?></label>
						<div class="col-sm-9"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
						  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
						</div>
					  </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label"><?php echo $entry_nip; ?></label>
                        <div class="col-sm-9">
                          <p class="form-control-static"><?php echo $nip; ?></p>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-nik"><?php echo $entry_nik; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="nik" value="<?php echo $nik; ?>" placeholder="<?php echo $entry_nik; ?>" id="input-nik" class="form-control" />
                          <?php if ($error_nik) { ?>
                          <div class="text-danger"><?php echo $error_nik; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                          <?php if ($error_firstname) { ?>
                          <div class="text-danger"><?php echo $error_firstname; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-lastname"><span data-toggle="tooltip" title="<?php echo $help_lastname; ?>"><?php echo $entry_lastname; ?></span></label>
                        <div class="col-sm-9">
                          <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                          <?php if ($error_lastname) { ?>
                          <div class="text-danger"><?php echo $error_lastname; ?></div>
                          <?php } ?>
                        </div>
                      </div>
					  <div class="form-group required">
						<label class="col-sm-3 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
						<!-- <div class="col-sm-9"> -->
						<fieldset id="fieldset-date-start" class="col-sm-9" <?php echo $date_start_locked ? 'disabled' : '' ?>>
						  <div class="input-group date">
							<input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="D MMM YYYY" id="input-date-start" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						  </div>
                          <?php if ($error_date_start) { ?>
                            <div class="text-danger"><?php echo $error_date_start; ?></div>
                          <?php } ?>
						</fieldset>
					  </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-skip-trial-status"><span data-toggle="tooltip" title="<?php echo $help_skip_trial_status; ?>"><?php echo $entry_skip_trial_status; ?></span></label>
                        <div class="col-sm-9">
                          <div class="checkbox">
                            <label>
                              <?php if ($skip_trial_status) { ?>
                              <input type="checkbox" name="skip_trial_status" value="1" checked="checked" id="input-skip-trial-status" <?php echo $skip_trial_status_locked ? 'disabled' : '' ?> />
                               <?php } else { ?>
                              <input type="checkbox" name="skip_trial_status" value="1" id="input-skip-trial-status" <?php echo $skip_trial_status_locked ? 'disabled' : '' ?> />
                               <?php } ?>
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                        <div class="col-sm-9">
                          <select name="customer_group_id" id="input-customer-group" class="form-control">
                            <?php foreach ($customer_groups as $customer_group) { ?>
                            <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-location"><?php echo $entry_location; ?></label>
                        <div class="col-sm-9">
                          <select name="location_id" id="input-location" class="form-control">
                            <?php foreach ($locations as $location) { ?>
                            <?php if ($location['location_id'] == $location_id) { ?>
                            <option value="<?php echo $location['location_id']; ?>" selected="selected"><?php echo $location['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $location['location_id']; ?>"><?php echo $location['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-full-overtime"><?php echo $entry_full_overtime; ?></label>
                        <div class="col-sm-9">
                          <select name="full_overtime" id="input-full-overtime" class="form-control">
                            <?php if ($full_overtime) { ?>
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
                        <label class="col-sm-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-9">
                          <select name="status" id="input-status" class="form-control">
                            <?php if ($status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
 					  <div class="form-group">
						<label class="col-sm-3 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
						<div class="col-sm-9">
						<fieldset id="fieldset-date-end" <?php echo $date_end_locked ? 'disabled' : '' ?>>
						  <div class="input-group date">
							<input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="D MMM YYYY" id="input-date-end" class="form-control" />
							<span class="input-group-btn">
							  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
							</span>
						  </div>
                          <?php if ($error_date_end) { ?>
                          <div class="text-danger"><?php echo $error_date_end; ?></div>
                          <?php } ?>
						</fieldset>
                        <?php if ($date_end) { ?>
						<br />
						<button id="button-reactivate" type="button" class="btn btn-warning pull-right"><i class="fa fa-refresh"></i> <?php echo $button_reactivate; ?></button>
                        <?php } ?>
					    </div>
					  </div>
                    </div>
                    <div class="tab-pane" id="tab-additional">
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-gender"><?php echo $entry_gender; ?></label>
                        <div class="col-sm-9">
                          <select name="gender_id" id="input-gender" class="form-control">
                            <?php foreach ($genders as $gender) { ?>
                            <?php if ($gender['gender_id'] == $gender_id) { ?>
                            <option value="<?php echo $gender['gender_id']; ?>" selected="selected"><?php echo $gender['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $gender['gender_id']; ?>"><?php echo $gender['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-marriage-status"><?php echo $entry_marriage_status; ?></label>
                        <div class="col-sm-9">
                          <select name="marriage_status_id" id="input-marriage-status" class="form-control">
                            <?php foreach ($marriage_statuses as $marriage_status) { ?>
                            <?php if ($marriage_status['marriage_status_id'] == $marriage_status_id) { ?>
                            <option value="<?php echo $marriage_status['marriage_status_id']; ?>" selected="selected"><?php echo $marriage_status['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $marriage_status['marriage_status_id']; ?>"><?php echo $marriage_status['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-npwp"><?php echo $entry_npwp; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="npwp" value="<?php echo $npwp; ?>" placeholder="<?php echo $entry_npwp; ?>" id="input-npwp" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-npwp-address"><span data-toggle="tooltip" title="<?php echo $help_npwp_address; ?>"><?php echo $entry_npwp_address; ?></span></label>
                        <div class="col-sm-9">
                          <input type="text" name="npwp_address" value="<?php echo $npwp_address; ?>" placeholder="<?php echo $entry_npwp_address; ?>" id="input-npwp-address" class="form-control" />
                          <?php if ($error_npwp_address) { ?>
                            <div class="text-danger"><?php echo $error_npwp_address; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                       <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-children"><?php echo $entry_children; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="children" value="<?php echo $children; ?>" placeholder="<?php echo $entry_children; ?>" id="input-children" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-payroll-include"><?php echo $entry_payroll_include; ?></label>
                        <div class="col-sm-9">
                          <select name="payroll_include" id="input-payroll-include" class="form-control">
                            <?php if ($payroll_include) { ?>
                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                            <option value="0"><?php echo $text_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-payroll-method"><?php echo $entry_payroll_method; ?></label>
                        <div class="col-sm-9">
                          <select name="payroll_method_id" id="input-payroll-method" class="form-control">
                            <?php foreach ($payroll_methods as $payroll_method) { ?>
                            <?php if ($payroll_method['payroll_method_id'] == $payroll_method_id) { ?>
                            <option value="<?php echo $payroll_method['payroll_method_id']; ?>" selected="selected"><?php echo $payroll_method['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $payroll_method['payroll_method_id']; ?>"><?php echo $payroll_method['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-acc-no"><?php echo $entry_acc_no; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="acc_no" value="<?php echo $acc_no; ?>" placeholder="<?php echo $entry_acc_no; ?>" id="input-acc-no" class="form-control" />
                         </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-health-insurance"><span data-toggle="tooltip" title="<?php echo $help_health_insurance; ?>"><?php echo $entry_health_insurance; ?></span></label>
                        <div class="col-sm-9">
                          <select name="health_insurance" id="input-health-insurance" class="form-control">
                            <?php if ($health_insurance) { ?>
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
                        <label class="col-sm-3 control-label" for="input-employment-insurance"><span data-toggle="tooltip" title="<?php echo $help_employment_insurance; ?>"><?php echo $entry_employment_insurance; ?></span></label>
                        <div class="col-sm-9">
                          <select name="employment_insurance" id="input-employment-insurance" class="form-control">
                            <?php if ($employment_insurance) { ?>
                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                            <option value="0"><?php echo $text_no; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_yes; ?></option>
                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                            <?php } ?>
                          </select>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-email"><?php echo $entry_email; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                          <?php if ($error_email) { ?>
                          <div class="text-danger"><?php echo $error_email; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                          <?php if ($error_telephone) { ?>
                          <div class="text-danger"><?php echo $error_telephone; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <?php foreach ($custom_fields as $custom_field) { ?>
                      <?php if ($custom_field['location'] == 'account') { ?>
                      <?php if ($custom_field['type'] == 'select') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <select name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'radio') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="radio">
                              <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $account_custom_field[$custom_field['custom_field_id']]) { ?>
                              <label>
                                <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="radio" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'checkbox') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="checkbox">
                              <?php if (isset($account_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $account_custom_field[$custom_field['custom_field_id']])) { ?>
                              <label>
                                <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="checkbox" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'text') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'textarea') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <textarea name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'file') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <button type="button" id="button-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                          <input type="hidden" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : ''); ?>" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'date') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div class="input-group date">
                            <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'time') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div class="input-group time">
                            <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'datetime') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
                        <label class="col-sm-3 control-label" for="input-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div class="input-group datetime">
                            <input type="text" name="custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_custom_field[$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_custom_field[$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php } ?>
                      <?php } ?>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-password"><?php echo $entry_password; ?></label>
                        <div class="col-sm-9">
                          <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
                          <?php if ($error_password) { ?>
                          <div class="text-danger"><?php echo $error_password; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                        <div class="col-sm-9">
                          <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm" class="form-control" />
                          <?php if ($error_confirm) { ?>
                          <div class="text-danger"><?php echo $error_confirm; ?></div>
                          <?php  } ?>
                        </div>
                      </div>
                    </div>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <div class="tab-pane" id="tab-address<?php echo $address_row; ?>">
                      <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-address-1<?php echo $address_row; ?>"><?php echo $entry_address_1; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['address_1'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['address_1']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-address-2<?php echo $address_row; ?>"><?php echo $entry_address_2; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2<?php echo $address_row; ?>" class="form-control" />
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label" for="input-postcode<?php echo $address_row; ?>"><?php echo $entry_postcode; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode<?php echo $address_row; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['postcode'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['postcode']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-country<?php echo $address_row; ?>"><?php echo $entry_country; ?></label>
                        <div class="col-sm-9">
                          <select name="address[<?php echo $address_row; ?>][country_id]" id="input-country<?php echo $address_row; ?>" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $address['zone_id']; ?>', '<?php echo $address['city']; ?>');" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($countries as $country) { ?>
                            <?php if ($country['country_id'] == $address['country_id']) { ?>
                            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                          <?php if (isset($error_address[$address_row]['country'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['country']; ?></div>
                          <?php } ?>
                        </div>
					  </div>
					  <!-- Bonk -->
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-zone<?php echo $address_row; ?>"><?php echo $entry_zone; ?></label>
                        <div class="col-sm-9">
                          <select name="address[<?php echo $address_row; ?>][zone_id]" id="input-zone<?php echo $address_row; ?>" onchange="zone(this, '<?php echo $address_row; ?>', '<?php echo $address['city']; ?>');" class="form-control">
                          </select>
                          <?php if (isset($error_address[$address_row]['zone'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['zone']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="form-group required">
                        <label class="col-sm-3 control-label" for="input-city<?php echo $address_row; ?>"><?php echo $entry_city; ?></label>
                        <div class="col-sm-9">
				<!-- Bonk -->
					<select name="address[<?php echo $address_row; ?>][city]" id="input-city<?php echo $address_row; ?>" class="form-control">
                          </select>
                          <?php if (isset($error_address[$address_row]['city'])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['city']; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php foreach ($custom_fields as $custom_field) { ?>
                      <?php if ($custom_field['location'] == 'address') { ?>
                      <?php if ($custom_field['type'] == 'select') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <select name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                            <option value=""><?php echo $text_select; ?></option>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <?php if (isset($address['custom_field'][$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address['custom_field'][$custom_field['custom_field_id']]) { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                            <?php } else { ?>
                            <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                            <?php } ?>
                            <?php } ?>
                          </select>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'radio') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="radio">
                              <?php if (isset($address['custom_field'][$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $address['custom_field'][$custom_field['custom_field_id']]) { ?>
                              <label>
                                <input type="radio" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="radio" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'checkbox') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div>
                            <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                            <div class="checkbox">
                              <?php if (isset($address['custom_field'][$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $address['custom_field'][$custom_field['custom_field_id']])) { ?>
                              <label>
                                <input type="checkbox" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } else { ?>
                              <label>
                                <input type="checkbox" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                              <?php } ?>
                            </div>
                            <?php } ?>
                          </div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'text') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'textarea') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <textarea name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'file') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <button type="button" id="button-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                          <input type="hidden" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : ''); ?>" />
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'date') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div class="input-group date">
                            <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'time') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div class="input-group time">
                            <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php if ($custom_field['type'] == 'datetime') { ?>
                      <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">
                        <label class="col-sm-3 control-label" for="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                        <div class="col-sm-9">
                          <div class="input-group datetime">
                            <input type="text" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($address['custom_field'][$custom_field['custom_field_id']]) ? $address['custom_field'][$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-address<?php echo $address_row; ?>-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                            <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                            </span></div>
                          <?php if (isset($error_address[$address_row]['custom_field'][$custom_field['custom_field_id']])) { ?>
                          <div class="text-danger"><?php echo $error_address[$address_row]['custom_field'][$custom_field['custom_field_id']]; ?></div>
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                      <?php } ?>
                      <?php } ?>
                      <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo $entry_default; ?></label>
                        <div class="col-sm-9">
                          <label class="radio">
                            <?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
                            <?php } ?>
                          </label>
                        </div>
                      </div>
                    </div>
                    <?php $address_row++; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <script type="text/javascript"><!--
$('select[name=\'customer_group_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=customer/customer/customfield&token=<?php echo $token; ?>&customer_group_id=' + this.value,
		dataType: 'json',
		success: function(json) {
			$('.custom-field').hide();
			$('.custom-field').removeClass('required');

			for (i = 0; i < json.length; i++) {
				custom_field = json[i];

				$('.custom-field' + custom_field['custom_field_id']).show();

				if (custom_field['required']) {
					$('.custom-field' + custom_field['custom_field_id']).addClass('required');
				}
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'customer_group_id\']').trigger('change');
//--></script>
  <script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;

function addAddress() {
	html  = '<div class="tab-pane" id="tab-address' + address_row + '">';
	html += '  <input type="hidden" name="address[' + address_row + '][address_id]" value="" />';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-3 control-label" for="input-address-1' + address_row + '"><?php echo $entry_address_1; ?></label>';
	html += '    <div class="col-sm-9"><input type="text" name="address[' + address_row + '][address_1]" value="" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-3 control-label" for="input-address-2' + address_row + '"><?php echo $entry_address_2; ?></label>';
	html += '    <div class="col-sm-9"><input type="text" name="address[' + address_row + '][address_2]" value="" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-3 control-label" for="input-postcode' + address_row + '"><?php echo $entry_postcode; ?></label>';
	html += '    <div class="col-sm-9"><input type="text" name="address[' + address_row + '][postcode]" value="" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode' + address_row + '" class="form-control" /></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-3 control-label" for="input-country' + address_row + '"><?php echo $entry_country; ?></label>';
	html += '    <div class="col-sm-9"><select name="address[' + address_row + '][country_id]" id="input-country' + address_row + '" onchange="country(this, \'' + address_row + '\', \'0\');" class="form-control">';
    html += '         <option value=""><?php echo $text_select; ?></option>';
    <?php foreach ($countries as $country) { ?>
    html += '         <option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
    <?php } ?>
    html += '      </select></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-3 control-label" for="input-zone' + address_row + '"><?php echo $entry_zone; ?></label>';
//	html += '    <div class="col-sm-9"><select name="address[' + address_row + '][zone_id]" id="input-zone' + address_row + '" class="form-control"><option value=""><?php echo $text_none; ?></option></select></div>';
	html += '    <div class="col-sm-9"><select name="address[' + address_row + '][zone_id]" id="input-zone' + address_row + '" onchange="zone(this, \'' + address_row + '\', \'0\');" class="form-control">';
//	html += '    <div class="col-sm-9"><select name="address[' + address_row + '][country_id]" id="input-country' + address_row + '" onchange="country(this, \'' + address_row + '\', \'0\');" class="form-control">';
	html += '    <option value=""><?php echo $text_select; ?></option>';
	html += '      </select></div>';
	html += '  </div>';

	html += '  <div class="form-group required">';
	html += '    <label class="col-sm-3 control-label" for="input-city' + address_row + '"><?php echo $entry_city; ?></label>';
//	html += '    <div class="col-sm-9"><input type="text" name="address[' + address_row + '][city]" value="" placeholder="<?php echo $entry_city; ?>" id="input-city' + address_row + '" class="form-control" /></div>';
	html += '    <div class="col-sm-9"><select name="address[' + address_row + '][city]" id="input-city' + address_row + '" class="form-control"><option value=""><?php echo $text_none; ?></option></select></div>';
	html += '  </div>';

	// Custom Fields
	<?php foreach ($custom_fields as $custom_field) { ?>
	<?php if ($custom_field['location'] == 'address') { ?>
	<?php if ($custom_field['type'] == 'select') { ?>

	html += '  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '  		<label class="col-sm-3 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '  		<div class="col-sm-9">';
	html += '  		  <select name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">';
	html += '  			<option value=""><?php echo $text_select; ?></option>';

	<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
	html += '  			<option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo addslashes($custom_field_value['name']); ?></option>';
	<?php } ?>

	html += '  		  </select>';
	html += '  		</div>';
	html += '  	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'radio') { ?>
	html += '  	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>">';
	html += '  		<label class="col-sm-3 control-label"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '  		<div class="col-sm-9">';
	html += '  		  <div>';

	<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
	html += '  			<div class="radio"><label><input type="radio" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" /> <?php echo addslashes($custom_field_value['name']); ?></label></div>';
	<?php } ?>

	html += '		  </div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'checkbox') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '		<label class="col-sm-3 control-label"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-9">';
	html += '		  <div>';

	<?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
	html += '			<div class="checkbox"><label><input type="checkbox" name="address[<?php echo $address_row; ?>][custom_field][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" /> <?php echo addslashes($custom_field_value['name']); ?></label></div>';
	<?php } ?>

	html += '		  </div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'text') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '		<label class="col-sm-3 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-9">';
	html += '		  <input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo addslashes($custom_field['value']); ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'textarea') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '		<label class="col-sm-3 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-9">';
	html += '		  <textarea name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo addslashes($custom_field['name']); ?>" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo addslashes($custom_field['value']); ?></textarea>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'file') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '		<label class="col-sm-3 control-label"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-9">';
	html += '		  <button type="button" id="button-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>';
	html += '		  <input type="hidden" name="address[' + address_row + '][<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" />';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'date') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '		<label class="col-sm-3 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-9">';
	html += '		  <div class="input-group date"><input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo addslashes($custom_field['value']); ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" data-date-format="YYYY-MM-DD" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'time') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '		<label class="col-sm-3 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-9">';
	html += '		  <div class="input-group time"><input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" data-date-format="HH:mm" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php if ($custom_field['type'] == 'datetime') { ?>
	html += '	  <div class="form-group custom-field custom-field<?php echo $custom_field['custom_field_id']; ?>" data-sort="<?php echo $custom_field['sort_order'] + 1; ?>">';
	html += '		<label class="col-sm-3 control-label" for="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo addslashes($custom_field['name']); ?></label>';
	html += '		<div class="col-sm-9">';
	html += '		  <div class="input-group datetime"><input type="text" name="address[' + address_row + '][custom_field][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo addslashes($custom_field['value']); ?>" placeholder="<?php echo addslashes($custom_field['name']); ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-address' + address_row + '-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div>';
	html += '		</div>';
	html += '	  </div>';
	<?php } ?>

	<?php } ?>
	<?php } ?>

	html += '  <div class="form-group">';
	html += '    <label class="col-sm-3 control-label"><?php echo $entry_default; ?></label>';
	html += '    <div class="col-sm-9"><label class="radio"><input type="radio" name="address[' + address_row + '][default]" value="1" /></label></div>';
	html += '  </div>';

    html += '</div>';

	// $('#tab-general .tab-content').append(html);
	$('.tab-content').append(html);

	$('select[name=\'customer_group_id\']').trigger('change');

	$('select[name=\'address[' + address_row + '][country_id]\']').trigger('change');

	$('#address-add').before('<li><a href="#tab-address' + address_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'#address a:first\').tab(\'show\'); $(\'a[href=\\\'#tab-address' + address_row + '\\\']\').parent().remove(); $(\'#tab-address' + address_row + '\').remove();"></i> <?php echo $tab_address; ?> ' + address_row + '</a></li>');

	$('#address a[href=\'#tab-address' + address_row + '\']').tab('show');

	$('.date').datetimepicker({
		pickTime: false
	});

	$('.datetime').datetimepicker({
		pickDate: true,
		pickTime: true
	});

	$('.time').datetimepicker({
		pickDate: false
	});

	$('#tab-address' + address_row + ' .form-group[data-sort]').detach().each(function() {
		if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-address' + address_row + ' .form-group').length) {
			$('#tab-address' + address_row + ' .form-group').eq($(this).attr('data-sort')).before(this);
		}

		if ($(this).attr('data-sort') > $('#tab-address' + address_row + ' .form-group').length) {
			$('#tab-address' + address_row + ' .form-group:last').after(this);
		}

		if ($(this).attr('data-sort') < -$('#tab-address' + address_row + ' .form-group').length) {
			$('#tab-address' + address_row + ' .form-group:first').before(this);
		}
	});

	address_row++;
}
//--></script>
  <script type="text/javascript"><!--
function country(element, index, zone_id, city) {
	$.ajax({
		url: 'index.php?route=localisation/country/country&token=<?php echo $token; ?>&country_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'address[' + index + '][country_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
/*			if (json['postcode_required'] == '1') {
				$('input[name=\'address[' + index + '][postcode]\']').parent().parent().addClass('required');
			} else {
				$('input[name=\'address[' + index + '][postcode]\']').parent().parent().removeClass('required');
			}
*/
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['zone'] && json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
					html += '<option value="' + json['zone'][i]['zone_id'] + '"';

					if (json['zone'][i]['zone_id'] == zone_id) {
						html += ' selected="selected"';
					}

					html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'address[' + index + '][zone_id]\']').html(html);
			$('select[name=\'address[' + index + '][city]\']').load('index.php?route=customer/customer/city&token=<?php echo $token; ?>&zone_id=' + zone_id + '&city=' + city); // Bonk
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('select[name$=\'[country_id]\']').trigger('change');
//--></script>
  <script type="text/javascript"><!--
  // Bonk
function zone(element, index, city) {
	$.ajax({
		url: 'index.php?route=localisation/zone/zone&token=<?php echo $token; ?>&zone_id=' + element.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'address[' + index + '][zone_id]\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
			html = '<option value=""><?php echo $text_select; ?></option>';

			if (json['city'] && json['city'] != '') {
				for (i = 0; i < json['city'].length; i++) {
					html += '<option value="' + json['city'][i]['city'] + '"';

					if (json['city'][i]['city'] == city) {
						html += ' selected="selected"';
					}

					html += '>' + json['city'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0"><?php echo $text_none; ?></option>';
			}

			$('select[name=\'address[' + index + '][city]\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$('select[name$=\'[zone_id]\']').trigger('change');
//--></script>
  <script type="text/javascript"><!--
$('#content').delegate('button[id^=\'button-custom-field\'], button[id^=\'button-address\']', 'click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload/upload&token=<?php echo $token; ?>',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input[type=\'hidden\']').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);
					}

					if (json['code']) {
						$(node).parent().find('input[type=\'hidden\']').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('.date').datetimepicker({
	pickTime: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	pickDate: false
});

// Sort the custom fields
<?php $address_row = 1; ?>
<?php foreach ($addresses as $address) { ?>
$('#tab-address<?php echo $address_row ?> .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-address<?php echo $address_row ?> .form-group').length) {
		$('#tab-address<?php echo $address_row ?> .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-address<?php echo $address_row ?> .form-group').length) {
		$('#tab-address<?php echo $address_row ?> .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-address<?php echo $address_row ?> .form-group').length) {
		$('#tab-address<?php echo $address_row ?> .form-group:first').before(this);
	}
});
<?php $address_row++; ?>
<?php } ?>


<?php foreach ($addresses as $address) { ?>
$('#tab-customer .form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#tab-customer .form-group').length) {
		$('#tab-customer .form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('#tab-customer .form-group').length) {
		$('#tab-customer .form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('#tab-customer .form-group').length) {
		$('#tab-customer .form-group:first').before(this);
	}
});
<?php } ?>
//--></script>
  <script type="text/javascript"><!--
$('#button-reactivate').on('click', function() {
	$('#input-skip-trial-status').removeAttr('disabled');
	$('#fieldset-date-start').removeAttr('disabled');
	$('#fieldset-date-start input').val('').focus();
	$('#fieldset-date-end').removeAttr('disabled');
	$('#fieldset-date-end input').val('');
	$('#button-reactivate').remove();
});
//--></script>
<?php echo $footer; ?>
