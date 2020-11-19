<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-insurance" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-insurance" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-min-wage"><?php echo $entry_min_wage; ?></label>
            <div class="col-sm-10">
              <input type="text" name="insurance_min_wage" value="<?php echo $insurance_min_wage; ?>" placeholder="<?php echo $entry_min_wage; ?>" id="input-min-wage" class="form-control" />
            </div>
          </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group date">
		  	    <input type="text" name="insurance_date_start" value="<?php echo $insurance_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" id="input-date-start" class="form-control" data-date-format="MMM YYYY" />
		  	    <span class="input-group-btn">
		  	    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
		  	    </span>
		  	  </div>
		    </div>
		  </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-min-wage-old"><?php echo $entry_min_wage_old; ?></label>
            <div class="col-sm-10">
              <input type="text" name="insurance_min_wage_old" value="<?php echo $insurance_min_wage_old; ?>" placeholder="<?php echo $entry_min_wage_old; ?>" id="input-min-wage-old" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="insurance_status" id="input-status" class="form-control">
                <?php if ($insurance_status) { ?>
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
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="insurance_sort_order" value="<?php echo $insurance_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	minViewMode: 'months',
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>