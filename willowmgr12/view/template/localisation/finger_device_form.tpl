<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-finger-device" data-toggle="tooltip" title="<?= $button_save; ?>"
          class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>
          <?= $text_form; ?>
        </h3>
      </div>
      <div class="panel-body">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-finger-device"
          class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-3 control-label">
              <?= $entry_device_name; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" name="device_name" value="<?= $device_name; ?>"
                placeholder="<?= $entry_device_name; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-3 control-label">
              <?= $entry_sn; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" name="sn" value="<?= $sn; ?>" placeholder="<?= $entry_sn; ?>"
                class="form-control" />
              <?php if ($error_sn) { ?>
              <div class="text-danger">
                <?= $error_sn; ?>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-3 control-label">
              <?= $entry_vc; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" name="vc" value="<?= $vc; ?>" placeholder="<?= $entry_vc; ?>"
                class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-3 control-label">
              <?= $entry_ac; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" name="ac" value="<?= $ac; ?>" placeholder="<?= $entry_ac; ?>"
                class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-3 control-label">
              <?= $entry_vkey; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" name="vkey" value="<?= $vkey; ?>" placeholder="<?= $entry_vkey; ?>"
                class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label" for="input-location">
              <?= $entry_location; ?>
            </label>
            <div class="col-sm-9">
              <select name="location_id" id="input-location" class="form-control">
                <option value="">
                  <?= $text_select; ?>
                </option>
                <?php foreach ($locations as $location) { ?>
                <?php if ($location['location_id'] == $location_id) { ?>
                <option value="<?= $location['location_id']; ?>" selected="selected">
                  <?= $location['name']; ?>
                </option>
                <?php } else { ?>
                <option value="<?= $location['location_id']; ?>">
                  <?= $location['name']; ?>
                </option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
              <label class="col-sm-3 control-label" for="input-status"><?= $entry_status; ?></label>
              <div class="col-sm-9">
                <select name="status" id="input-status" class="form-control">
                  <option value="0" <?= !$status ? 'selected' : ''; ?>><?= $text_disabled; ?></option>
                  <option value="1" <?= $status ? 'selected' : ''; ?>><?= $text_enabled; ?></option>
                </select>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?>