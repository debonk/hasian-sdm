<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-absence" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-absence" class="form-horizontal">
		  <div class="well">
		    <div class="row">
		  	  <div class="col-sm-3"></div>
		  	  <div class="col-sm-6">
		  	    <div class="form-group">
		  		  <label class="control-label" for="input-customer"><?php echo $entry_name; ?></label>
		  		  <select name="customer_id" id="input-customer" class="form-control" <?php echo $disabled; ?>>
		  		    <option value="0"><?php echo $text_select ?></option>
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
		  <div class="row">
		  	<div id="customer-info"></div>
		  </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-presence-status"><?php echo $entry_presence_status; ?></label>
            <div class="col-sm-10">
			  <select name="presence_status_id" id="input-presence-status" class="form-control">
				<option value="0"><?php echo $text_select ?></option>
				<?php foreach ($presence_statuses as $presence_status) { ?>
				  <?php if (in_array($presence_status['presence_status_id'], $config_presence_status)) { ?>
				  <?php if ($presence_status['presence_status_id'] == $presence_status_id) { ?>
					<option value="<?php echo $presence_status['presence_status_id']; ?>" selected="selected"><?php echo $presence_status['name']; ?></option>
				  <?php } else { ?>
					<option value="<?php echo $presence_status['presence_status_id']; ?>"><?php echo $presence_status['name']; ?></option>
				  <?php } ?>
				  <?php } ?>
				<?php } ?>
			  </select>
              <?php if ($error_presence_status) { ?>
                <div class="text-danger"><?php echo $error_presence_status; ?></div>
              <?php } ?>
            </div>
          </div>
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
              <?php if ($error_ask_approval) { ?>
		  	  <button type="button" id="button-ask-approval" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning btn-xs"><i class="fa fa-check-square-o"></i> <?php echo $button_ask_approval; ?></button>
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
		  <?php if ($disabled) { ?>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-note"><?php echo $entry_note; ?></label>
            <div class="col-sm-10">
		  	  <div class="input-group">
                <input type="text" name="note" value="<?php echo $note; ?>" placeholder="<?php echo $entry_note; ?>" id="input-note" class="form-control" />
		  	    <span class="input-group-btn">
		  	      <button type="button" id="button-add-note" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning"><i class="fa fa-sticky-note-o"></i> <?php echo $button_add_note; ?></button>
		  	    </span>
		  	  </div>
            </div>
          </div>
		  <?php } ?>
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

$('#button-add-note').on('click', function() {
	$.ajax({
		url: 'index.php?route=presence/absence/note&token=<?php echo $token; ?>&absence_id=<?php echo $absence_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'note=' + $('input[name=\'note\']').val(),
		beforeSend: function() {
			$('#button-add-note').button('loading');
		},
		complete: function() {
			$('#button-add-note').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				location = 'index.php?route=presence/absence&token=<?php echo $token; ?>&url=<?php echo $url; ?>';
			}
		}
	});
});
//--></script> 
  <script type="text/javascript"><!--
$('#button-ask-approval').on('click', function() {
	var customer_id = encodeURIComponent($('select[name=\'customer_id\']').val());
	var presence_status_id = encodeURIComponent($('select[name=\'presence_status_id\']').val());
	var date = encodeURIComponent($('input[name=\'date\']').val());
	var description = encodeURIComponent($('input[name=\'description\']').val());

	$.ajax({
		url: 'index.php?route=presence/absence/askApproval&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'customer_id=' + customer_id + '&presence_status_id=' + presence_status_id + '&date=' + date + '&description=' + description,
		beforeSend: function() {
			$('#button-ask-approval').button('loading');
		},
		complete: function() {
			$('#button-ask-approval').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				location = 'index.php?route=presence/absence&token=<?php echo $token; ?>&url=<?php echo $url; ?>';
			}
		}
	});
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>
