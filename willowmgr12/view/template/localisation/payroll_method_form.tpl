<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-stock-status" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-stock-status" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?= $entry_name; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"> <span class="input-group-addon"><img src="language/<?= $language['code']; ?>/<?= $language['code']; ?>.png" title="<?= $language['name']; ?>" /></span>
                <input type="text" name="payroll_method[<?= $language['language_id']; ?>][name]" value="<?= isset($payroll_method[$language['language_id']]) ? $payroll_method[$language['language_id']]['name'] : ''; ?>" placeholder="<?= $entry_name; ?>" class="form-control" />
              </div>
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <div class="text-danger"><?= $error_name[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-code">
							<?= $entry_code; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="code" value="<?= $code; ?>" placeholder="<?= $entry_code; ?>"
								id="input-code" class="form-control" />
							<?php if ($error_code) { ?>
							<div class="text-danger">
								<?= $error_code; ?>
							</div>
							<?php } ?>
						</div>
					</div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?>