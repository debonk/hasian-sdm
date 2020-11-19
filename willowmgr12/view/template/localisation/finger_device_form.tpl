<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-stock-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-stock-status" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_device_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="device_name" value="<?php echo $device_name; ?>" placeholder="<?php echo $entry_device_name; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_sn; ?></label>
            <div class="col-sm-10">
              <input type="text" name="sn" value="<?php echo $sn; ?>" placeholder="<?php echo $entry_sn; ?>" class="form-control" />
              <?php if ($error_sn) { ?>
                <div class="text-danger"><?php echo $error_sn; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_vc; ?></label>
            <div class="col-sm-10">
              <input type="text" name="vc" value="<?php echo $vc; ?>" placeholder="<?php echo $entry_vc; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_ac; ?></label>
            <div class="col-sm-10">
              <input type="text" name="ac" value="<?php echo $ac; ?>" placeholder="<?php echo $entry_ac; ?>" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_vkey; ?></label>
            <div class="col-sm-10">
              <input type="text" name="vkey" value="<?php echo $vkey; ?>" placeholder="<?php echo $entry_vkey; ?>" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>