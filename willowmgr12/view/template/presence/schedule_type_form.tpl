<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-schedule-type" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($information) { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $information; ?>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-schedule-type" class="form-horizontal">
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
		  	  <?php if ($error_name) { ?>
		  	  <div class="text-danger"><?php echo $error_name; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-code"><?php echo $entry_code; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
		  	  <?php if ($error_code) { ?>
		  	  <div class="text-danger"><?php echo $error_code; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label"><?php echo $entry_location; ?></label>
		    <div class="col-sm-10">
		  	  <div class="well well-sm" style="height: 150px; overflow: auto;">
		  	    <?php foreach ($locations as $location) { ?>
		  	    <div class="checkbox">
		  	  	<label>
		  	  	  <?php if (in_array($location['location_id'], $location_ids)) { ?>
		  	  	    <input type="checkbox" name="location_ids[]" value="<?php echo $location['location_id']; ?>" checked="checked" />
		  	  	    <?php echo $location['name']; ?>
		  	  	  <?php } else { ?>
		  	  	    <input type="checkbox" name="location_ids[]" value="<?php echo $location['location_id']; ?>" />
		  	  	    <?php echo $location['name']; ?>
		  	  	  <?php } ?>
		  	  	</label>
		  	    </div>
		  	    <?php } ?>
		  	  </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
		  	  <?php if ($error_locations) { ?>
		  	  <div class="text-danger"><?php echo $error_locations; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
		    <div class="col-sm-10">
		  	  <div class="well well-sm" style="height: 150px; overflow: auto;">
		  	    <?php foreach ($customer_groups as $customer_group) { ?>
		  	    <div class="checkbox">
		  	  	<label>
		  	  	  <?php if (in_array($customer_group['customer_group_id'], $customer_group_ids)) { ?>
		  	  	    <input type="checkbox" name="customer_group_ids[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
		  	  	    <?php echo $customer_group['name']; ?>
		  	  	  <?php } else { ?>
		  	  	    <input type="checkbox" name="customer_group_ids[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
		  	  	    <?php echo $customer_group['name']; ?>
		  	  	  <?php } ?>
		  	  	</label>
		  	    </div>
		  	    <?php } ?>
		  	  </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
		  	  <?php if ($error_customer_groups) { ?>
		  	  <div class="text-danger"><?php echo $error_customer_groups; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-time-start"><?php echo $entry_time_start; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group time">
		  	    <input type="text" name="time_start" value="<?php echo $time_start; ?>" placeholder="<?php echo $entry_time_start; ?>" id="input-time-start" class="form-control" readonly />
		  	    <span class="input-group-btn">
		  	      <button type="button" class="btn btn-default"><i class="fa fa-clock-o"></i></button>
		  	    </span>
		  	  </div>
		  	  <?php if ($error_time_start) { ?>
		  	  <div class="text-danger"><?php echo $error_time_start; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-time-end"><?php echo $entry_time_end; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group time">
		  	    <input type="text" name="time_end" value="<?php echo $time_end; ?>" placeholder="<?php echo $entry_time_end; ?>" id="input-time-end" class="form-control" readonly />
		  	    <span class="input-group-btn">
		  	      <button type="button" class="btn btn-default"><i class="fa fa-clock-o"></i></button>
		  	    </span>
		  	  </div>
		  	  <?php if ($error_time_end) { ?>
		  	  <div class="text-danger"><?php echo $error_time_end; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
		    </div>
		  </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
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
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('.time').datetimepicker({
	pickDate: false,
	pickTime: true,
	format: 'HH:mm',
	minuteStepping: 15
});
//--></script>
</div>
<?php echo $footer; ?>