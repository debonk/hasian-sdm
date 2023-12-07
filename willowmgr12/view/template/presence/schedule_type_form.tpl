<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-schedule-type" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($information) { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?= $information; ?>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-schedule-type" class="form-horizontal">
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-name"><?= $entry_name; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="name" value="<?= $name; ?>" placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
		  	  <?php if ($error_name) { ?>
		  	  <div class="text-danger"><?= $error_name; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-code"><?= $entry_code; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="code" value="<?= $code; ?>" placeholder="<?= $entry_code; ?>" id="input-code" class="form-control" />
		  	  <?php if ($error_code) { ?>
		  	  <div class="text-danger"><?= $error_code; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-code-id"><?= $entry_code_id; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="code_id" value="<?= $code_id; ?>" placeholder="<?= $entry_code_id; ?>" id="input-code-id" class="form-control" />
		  	  <?php if ($error_code_id) { ?>
		  	  <div class="text-danger"><?= $error_code_id; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label"><?= $entry_location; ?></label>
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
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?= $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?= $text_unselect_all; ?></a>
		  	  <?php if ($error_locations) { ?>
		  	  <div class="text-danger"><?= $error_locations; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label"><?= $entry_customer_group; ?></label>
		    <div class="col-sm-10">
		  	  <div class="well well-sm" style="height: 150px; overflow: auto;">
		  	    <?php foreach ($customer_groups as $customer_group) { ?>
		  	    <div class="checkbox">
		  	  	<label>
		  	  	  <?php if (in_array($customer_group['customer_group_id'], $customer_group_ids)) { ?>
		  	  	    <input type="checkbox" name="customer_group_ids[]" value="<?= $customer_group['customer_group_id']; ?>" checked="checked" />
		  	  	    <?= $customer_group['name']; ?>
		  	  	  <?php } else { ?>
		  	  	    <input type="checkbox" name="customer_group_ids[]" value="<?= $customer_group['customer_group_id']; ?>" />
		  	  	    <?= $customer_group['name']; ?>
		  	  	  <?php } ?>
		  	  	</label>
		  	    </div>
		  	    <?php } ?>
		  	  </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?= $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?= $text_unselect_all; ?></a>
		  	  <?php if ($error_customer_groups) { ?>
		  	  <div class="text-danger"><?= $error_customer_groups; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-time-start"><?= $entry_time_start; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group time">
		  	    <input type="text" name="time_start" value="<?= $time_start; ?>" placeholder="<?= $entry_time_start; ?>" id="input-time-start" class="form-control" readonly />
		  	    <span class="input-group-btn">
		  	      <button type="button" class="btn btn-default"><i class="fa fa-clock-o"></i></button>
		  	    </span>
		  	  </div>
		  	  <?php if ($error_time_start) { ?>
		  	  <div class="text-danger"><?= $error_time_start; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-time-end"><?= $entry_time_end; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group time">
		  	    <input type="text" name="time_end" value="<?= $time_end; ?>" placeholder="<?= $entry_time_end; ?>" id="input-time-end" class="form-control" readonly />
		  	    <span class="input-group-btn">
		  	      <button type="button" class="btn btn-default"><i class="fa fa-clock-o"></i></button>
		  	    </span>
		  	  </div>
		  	  <?php if ($error_time_end) { ?>
		  	  <div class="text-danger"><?= $error_time_end; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-sort-order"><?= $entry_bg; ?></label>
		    <div class="col-sm-10">
			  <div class="btn-group" data-toggle="buttons">
				<?php for ($i = 1; $i <= 11; $i++) { ?>
				<?php if ($i == $bg_idx) { ?>
				<label class="btn color-pick schedule-bg-<?= $i; ?> active">
				  <input type="radio" name="bg_idx" value="<?= $i; ?>" checked>&nbsp;&nbsp;
				</label>
				<?php } else { ?>
				<label class="btn color-pick schedule-bg-<?= $i; ?>">
				  <input type="radio" name="bg_idx" value="<?= $i; ?>">&nbsp;&nbsp;
				</label>
				<?php } ?>
				<?php } ?>
			  </div>
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
				<?php if ($status) { ?>
				<option value="1" selected="selected"><?= $text_enabled; ?></option>
				<option value="0"><?= $text_disabled; ?></option>
				<?php } else { ?>
				<option value="1"><?= $text_enabled; ?></option>
				<option value="0" selected="selected"><?= $text_disabled; ?></option>
				<?php } ?>
			  </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$('.time').datetimepicker({
	pickDate: false,
	pickTime: true,
	format: 'HH:mm',
	minuteStepping: 5
});
  </script>
<?= $footer; ?>