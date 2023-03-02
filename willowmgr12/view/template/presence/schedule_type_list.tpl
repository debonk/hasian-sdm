<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	    <a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?= $button_copy; ?>" class="btn btn-default" onclick="$('#form-schedule-type').attr('action', '<?= $copy; ?>').submit()"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?= $text_confirm; ?>') ? $('#form-schedule-type').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?= $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?= $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?= $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?= $filter_name; ?>" placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-code"><?= $entry_code; ?></label>
                <input type="text" name="filter_code" value="<?= $filter_code; ?>" placeholder="<?= $entry_code; ?>" id="input-code" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-customer-group"><?= $entry_customer_group; ?></label>
                <select name="filter_customer_group_id" id="input-customer-group" class="form-control">
                  <option value="*"><?= $text_all; ?></option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                  <option value="<?= $customer_group['customer_group_id']; ?>" selected="selected"><?= $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?= $customer_group['customer_group_id']; ?>"><?= $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-location"><?= $entry_location; ?></label>
                <select name="filter_location_id" id="input-location" class="form-control">
                  <option value="*"><?= $text_all; ?></option>
                  <?php foreach ($locations as $location) { ?>
                  <?php if ($location['location_id'] == $filter_location_id) { ?>
                  <option value="<?= $location['location_id']; ?>" selected="selected"><?= $location['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?= $location['location_id']; ?>"><?= $location['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?= $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"><?= $text_all; ?></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?= $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?= $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?= $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?= $text_disabled; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div>
                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?= $button_filter; ?></button>
              </div>
            </div>
          </div>
        </div>
        <form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-schedule-type">
          <div class="table-responsive">
            <table class="table table-bordered table-hover text-left">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td><?php if ($sort == 'name') { ?>
                    <a href="<?= $sort_name; ?>" class="<?= strtolower($order); ?>"><?= $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?= $sort_name; ?>"><?= $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-center"><?php if ($sort == 'code') { ?>
                    <a href="<?= $sort_code; ?>" class="<?= strtolower($order); ?>"><?= $column_code; ?></a>
                    <?php } else { ?>
                    <a href="<?= $sort_code; ?>"><?= $column_code; ?></a>
                    <?php } ?></td>
                  <td><?= $column_location; ?></td>
                  <td><?php if ($sort == 'time_start') { ?>
                    <a href="<?= $sort_time_start; ?>" class="<?= strtolower($order); ?>"><?= $column_time_start; ?></a>
                    <?php } else { ?>
                    <a href="<?= $sort_time_start; ?>"><?= $column_time_start; ?></a>
                    <?php } ?></td>
                  <td><?php if ($sort == 'time_end') { ?>
                    <a href="<?= $sort_time_end; ?>" class="<?= strtolower($order); ?>"><?= $column_time_end; ?></a>
                    <?php } else { ?>
                    <a href="<?= $sort_time_end; ?>"><?= $column_time_end; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'sort_order') { ?>
                    <a href="<?= $sort_sort_order; ?>" class="<?= strtolower($order); ?>"><?= $column_sort_order; ?></a>
                    <?php } else { ?>
                    <a href="<?= $sort_sort_order; ?>"><?= $column_sort_order; ?></a>
                    <?php } ?></td>
                  <td><?php if ($sort == 'status') { ?>
                    <a href="<?= $sort_status; ?>" class="<?= strtolower($order); ?>"><?= $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?= $sort_status; ?>"><?= $column_status; ?></a>
                    <?php } ?></td>
                 <td><?= $column_current_use; ?></td>
                 <td class="text-right"><?= $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($schedule_types) { ?>
                <?php foreach ($schedule_types as $schedule_type) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($schedule_type['schedule_type_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $schedule_type['schedule_type_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $schedule_type['schedule_type_id']; ?>" />
                    <?php } ?></td>
                  <td><?= $schedule_type['name']; ?></td>
                  <td class="text-center <?= 'schedule-bg-' . $schedule_type['bg_idx']; ?>"><?= $schedule_type['code']; ?></td>
                  <td><?= $schedule_type['location']; ?></td>
                  <td><?= $schedule_type['time_start']; ?></td>
                  <td><?= $schedule_type['time_end']; ?></td>
                  <td class="text-right"><?= $schedule_type['sort_order']; ?></td>
                  <td><?= $schedule_type['status']; ?></td>
                  <td><?= $schedule_type['current_use']; ?></td>
                  <td class="text-right"><a href="<?= $schedule_type['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?= $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?= $pagination; ?></div>
          <div class="col-sm-6 text-right"><?= $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$(document).keypress(function(e) {
	if(e.which == 13) {
		$("#button-filter").click();
	}
});

$('#button-filter').on('click', function() {
	url = 'index.php?route=presence/schedule_type&token=<?= $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_code = $('input[name=\'filter_code\']').val();
	
	if (filter_code) {
		url += '&filter_code=' + encodeURIComponent(filter_code);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
	
	if (filter_customer_group_id != '*') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
	}	
	
	var filter_location_id = $('select[name=\'filter_location_id\']').val();
	
	if (filter_location_id != '*') {
		url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
	}	
	
	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}	
	
	location = url;
});
</script>   
</div>
<?= $footer; ?>