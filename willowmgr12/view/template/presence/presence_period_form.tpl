<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-presence-period" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-presence-period" class="form-horizontal">
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="input-period"><?php echo $entry_period; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="period" value="<?php echo $period; ?>" placeholder="<?php echo $entry_period; ?>" id="input-period" class="form-control" readonly="readonly" />
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" id="input-date-start" class="form-control" readonly="readonly" />
			</div>
		  </div>
		  <div class="form-group required">
			<label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
			<div class="col-sm-10">
			  <div class="input-group date">
				<input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
				<span class="input-group-btn">
				<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
				</span>
			  </div>
			  <?php if ($error_date_end) { ?>
			    <div class="text-danger"><?php echo $error_date_end; ?></div>
			  <?php } ?>
			</div>
		  </div>
        </form>
      </div>
    </div>
  </div>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>