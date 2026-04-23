<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><?= $entry_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="username" value="<?= $username; ?>" placeholder="<?= $entry_username; ?>" id="input-username" class="form-control" />
              <?php if ($error_username) { ?>
              <div class="text-danger"><?= $error_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-user-group"><?= $entry_user_group; ?></label>
            <div class="col-sm-10">
              <select name="user_group_id" id="input-user-group" class="form-control">
                <?php foreach ($user_groups as $user_group) { ?>
                <?php if ($user_group['user_group_id'] == $user_group_id) { ?>
                <option value="<?= $user_group['user_group_id']; ?>" selected="selected"><?= $user_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?= $user_group['user_group_id']; ?>"><?= $user_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname"><?= $entry_firstname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="<?= $firstname; ?>" placeholder="<?= $entry_firstname; ?>" id="input-firstname" class="form-control" />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?= $error_firstname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-lastname"><?= $entry_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?= $lastname; ?>" placeholder="<?= $entry_lastname; ?>" id="input-lastname" class="form-control" />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?= $error_lastname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-email"><?= $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?= $email; ?>" placeholder="<?= $entry_email; ?>" id="input-email" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-image"><?= $entry_image; ?></label>
            <div class="col-sm-10"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?= $thumb; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" /></a>
              <input type="hidden" name="image" value="<?= $image; ?>" id="input-image" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?= $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?= $password; ?>" placeholder="<?= $entry_password; ?>" id="input-password" class="form-control" autocomplete="off" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?= $error_password; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?= $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?= $confirm; ?>" placeholder="<?= $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?= $error_confirm; ?></div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-coverage"><?= $entry_coverage; ?></label>
            <div class="col-sm-10">
              <select name="full_coverage" id="input-coverage" class="form-control">
                <?php foreach ($coverages as $value => $text) { ?>
                <?php if ($value == $full_coverage) { ?>
                <option value="<?= $value; ?>" selected="selected"><?= $text; ?></option>
                <?php } else { ?>
                <option value="<?= $value; ?>"><?= $text; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?= $entry_customer_department; ?>
            </label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($customer_departments as $customer_department) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($customer_department['customer_department_id'], $customer_department_ids)) { ?>
                    <input type="checkbox" name="customer_department_ids[]" value="<?= $customer_department['customer_department_id']; ?>" checked="checked" />
                    <?= $customer_department['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="customer_department_ids[]" value="<?= $customer_department['customer_department_id']; ?>" />
                    <?= $customer_department['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">
                <?= $text_select_all; ?>
              </a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">
                <?= $text_unselect_all; ?>
              </a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?= $entry_location; ?>
            </label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($locations as $location) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($location['location_id'], $location_ids)) { ?>
                    <input type="checkbox" name="location_ids[]" value="<?= $location['location_id']; ?>" checked="checked" />
                    <?= $location['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="location_ids[]" value="<?= $location['location_id']; ?>" />
                    <?= $location['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">
                <?= $text_select_all; ?>
              </a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">
                <?= $text_unselect_all; ?>
              </a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-customer-department"><?= $entry_customer_department; ?></label>
            <div class="col-sm-10">
              <select name="customer_department_id" id="input-customer-department" class="form-control">
                <option value="0"><?= $text_all; ?></option>
                <?php foreach ($customer_departments as $customer_department) { ?>
                <?php if ($customer_department['customer_department_id'] == $customer_department_id) { ?>
                <option value="<?= $customer_department['customer_department_id']; ?>" selected="selected"><?= $customer_department['name']; ?></option>
                <?php } else { ?>
                <option value="<?= $customer_department['customer_department_id']; ?>"><?= $customer_department['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?= $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="0"><?= $text_disabled; ?></option>
                <option value="1" selected="selected"><?= $text_enabled; ?></option>
                <?php } else { ?>
                <option value="0" selected="selected"><?= $text_disabled; ?></option>
                <option value="1"><?= $text_enabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?> 