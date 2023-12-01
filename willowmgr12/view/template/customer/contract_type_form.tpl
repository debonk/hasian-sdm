<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-contract-type" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-contract-type" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?= $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?= $name; ?>" placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
                <div class="text-danger"><?= $error_name; ?></div>
              <?php } ?>
             </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-description"><?= $entry_description; ?></label>
            <div class="col-sm-10">
              <textarea name="description" rows="5" placeholder="<?= $entry_description; ?>" id="input-description" class="form-control" ><?= $description; ?></textarea>
			</div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-duration"><span data-toggle="tooltip" title="<?php echo $help_duration; ?>"><?= $entry_duration; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="duration" value="<?= $duration; ?>" placeholder="<?= $entry_duration; ?>" id="input-duration" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?= $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sort_order" value="<?= $sort_order; ?>" placeholder="<?= $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?= $entry_status; ?></label>
            <div class="col-sm-10">
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