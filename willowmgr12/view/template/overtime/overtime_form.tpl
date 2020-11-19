<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-overtime" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3><h4 class="pull-right"><i class="fa fa-comment-o fa-flip-horizontal"></i> <?php echo $text_modified; ?></h4>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-overtime" class="form-horizontal">
		  <div class="well">
		    <div class="row">
		  	  <div class="col-sm-3"></div>
		  	  <div class="col-sm-6">
		  	    <div class="form-group">
		  		  <label class="control-label" for="input-customer"><?php echo $entry_name; ?></label>
		  		  <select name="customer_id" id="input-customer" class="form-control" <?php echo $disabled; ?>>
		  		    <option value="0"><?php echo $text_select_customer ?></option>
		  		    <?php foreach ($customers as $customer) { ?>
					  <?php if ($customer['customer_id'] == $customer_id) { ?>
						<option value="<?php echo $customer['customer_id']; ?>" selected="selected"><?php echo $customer['text']; ?></option>
					  <?php } else { ?>
						<option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['text']; ?></option>
		  		      <?php } ?>
		  		    <?php } ?>
		  		  </select>
		  	    </div>
		  	  </div>
		    </div>
		  </div>
		  <div id="customer-info"></div>
		  <div class="form-group required">
		    <label class="col-sm-2 control-label" for="input-date"><?php echo $entry_date; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group date">
		  	    <input type="text" name="date" value="<?php echo $date; ?>" placeholder="<?php echo $entry_date; ?>" id="input-date" class="form-control" data-date-format="D MMM YYYY" />
		  	    <span class="input-group-btn">
		  	      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
		  	    </span>
		  	  </div>
              <?php if ($error_date) { ?>
                <div class="text-danger"><?php echo $error_date; ?></div>
              <?php } ?>
		    </div>
		  </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-overtime-type"><?php echo $entry_overtime_type; ?></label>
            <div class="col-sm-10">
			  <select name="overtime_type_id" id="input-overtime-type" class="form-control">
				<option value="0"><?php echo $text_select ?></option>
				<?php foreach ($overtime_types as $overtime_type) { ?>
				  <?php if ($overtime_type['overtime_type_id'] == $overtime_type_id) { ?>
					<option value="<?php echo $overtime_type['overtime_type_id']; ?>" selected="selected"><?php echo $overtime_type['name']; ?></option>
				  <?php } else { ?>
					<option value="<?php echo $overtime_type['overtime_type_id']; ?>"><?php echo $overtime_type['name']; ?></option>
				  <?php } ?>
				<?php } ?>
			  </select>
              <?php if ($error_overtime_type) { ?>
                <div class="text-danger"><?php echo $error_overtime_type; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo $description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              <?php if ($error_description) { ?>
                <div class="text-danger"><?php echo $error_description; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-schedule-type"><?php echo $entry_schedule_type; ?></label>
            <div class="col-sm-10">
			  <select name="schedule_type_id" id="input-schedule-type" class="form-control">
				<option value="0"><?php echo $text_select ?></option>
				<?php foreach ($schedule_types as $schedule_type) { ?>
				  <?php if ($schedule_type['schedule_type_id'] == $schedule_type_id) { ?>
					<option value="<?php echo $schedule_type['schedule_type_id']; ?>" selected="selected"><?php echo $schedule_type['code']; ?></option>
				  <?php } else { ?>
					<option value="<?php echo $schedule_type['schedule_type_id']; ?>"><?php echo $schedule_type['code']; ?></option>
				  <?php } ?>
				<?php } ?>
			  </select>
              <?php if ($error_schedule_type) { ?>
                <div class="text-danger"><?php echo $error_schedule_type; ?></div>
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var select_customer = $('select[name=\'customer_id\']').val();

$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=' + encodeURIComponent(select_customer));

$('#input-customer').change(function() {
	var select_customer = $('select[name=\'customer_id\']').val();

	$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=' + encodeURIComponent(select_customer));
});
//--></script> 
  <script type="text/javascript"><!--
$('select[name=\'customer_id\']').on('change', function() {
	$.ajax({
		url: 'index.php?route=overtime/overtime/scheduleTypesByLocationGroup&token=<?php echo $token; ?>&customer_id=' + $('select[name=\'customer_id\']').val(),
		dataType: 'json',
		// beforeSend: function() {
			// $('select[name=\'customer_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		// },
		// complete: function() {
			// $('.fa-spin').remove();
		// },
		success: function(json) {
			html = '	<option value="0"><?php echo $text_select ?></option>';

			if (json && json != '') {
				for (i = 0; i < json.length; i++) {
					html += '<option value="' + json[i]['schedule_type_id'] + '">' + json[i]['code'] + '</option>';
				}
			}

			$('select[name=\'schedule_type_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

// $('select[name=\'customer_group_id\']').trigger('change');
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>
