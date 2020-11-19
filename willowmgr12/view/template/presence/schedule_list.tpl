<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<?php if ($period_pending_check) { ?>
		  <button type="button" id="button-apply-schedule" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-info"><i class="fa fa-clipboard"></i> <?php echo $button_apply_schedule; ?></button>
          <button type="button" id="button-delete" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-schedule').submit() : false;"><i class="fa fa-trash-o"></i></button>
        <?php } elseif ($period_processing_check) { ?>
          <button type="button" id="button-recap-presence" data-toggle="tooltip" title="<?php echo $button_recap; ?>" class="btn btn-warning" onclick="confirm('<?php echo $text_confirm_recap; ?>') ? $('#form-schedule').submit() : false;"><i class="fa fa-share-square-o"></i> <?php echo $button_recap; ?></button>
          <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>
        <?php } else { ?>
		  <button type="button" class="btn btn-warning disabled"><i class="fa fa-share-square-o"></i> <?php echo $button_recap; ?></button>
          <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>
        <?php } ?>
		<a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	  </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($information) { ?>
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $information; ?>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
		<div class="col-sm-3"></div>
		<div class="col-sm-6" id="period-info"></div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-presence-period"><?php echo $entry_presence_period; ?></label>
                <select name="presence_period_id" id="input-presence-period" class="form-control">
                  <?php foreach ($presence_periods as $presence_period) { ?>
                    <?php if ($presence_period['presence_period_id'] == $presence_period_id) { ?>
                      <option value="<?php echo $presence_period['presence_period_id']; ?>" selected="selected"><?php echo date('M y',strtotime($presence_period['period'])); ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $presence_period['presence_period_id']; ?>"><?php echo date('M y',strtotime($presence_period['period'])); ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                <select name="filter_customer_group_id" id="input-customer-group" class="form-control">
                  <option value="*"><?php echo $text_all_customer_group ?></option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-location"><?php echo $entry_location; ?></label>
                <select name="filter_location_id" id="input-location" class="form-control">
                  <option value="*"><?php echo $text_all_location ?></option>
                  <?php foreach ($locations as $location) { ?>
                  <?php if ($location['location_id'] == $filter_location_id) { ?>
                  <option value="<?php echo $location['location_id']; ?>" selected="selected"><?php echo $location['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $location['location_id']; ?>"><?php echo $location['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" action="<?php echo $action; ?>" enctype="multipart/form-data" id="form-schedule">
		<div id="schedule-report"></div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$(document).keypress(function(e) {
        if(e.which == 13) {
			$("#button-filter").click();
        }
    });

$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

$('#schedule-report').load('index.php?route=presence/schedule/report&token=<?php echo $token; ?>' + '<?php echo $url; ?>');

$('#schedule-report').on('click', '.pagination a, thead a', function(e) {
	e.preventDefault();

	$('#schedule-report').load(this.href);
});

$('#schedule-report').on('click', 'tbody a', function() {
	location = this.href;
});

$('#button-filter').on('click', function() {
	url = 'index.php?route=presence/schedule&token=<?php echo $token; ?>';
	
	var presence_period_id = $('select[name=\'presence_period_id\']').val();
	
	if (presence_period_id) {
		url += '&presence_period_id=' + encodeURIComponent(presence_period_id);
	}
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
	
	if (filter_customer_group_id != '*') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
	}	
	
	var filter_location_id = $('select[name=\'filter_location_id\']').val();
	
	if (filter_location_id != '*') {
		url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
	}	
	
	location = url;
});

$('#button-apply-schedule').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		$.ajax({
			url: 'index.php?route=presence/schedule/applySchedule&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>',
			dataType: 'json',
			crossDomain: false,
			beforeSend: function() {
				$('#button-apply-schedule').button('loading');
			},
			complete: function() {
				$('#button-apply-schedule').button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				    $('#button-apply-schedule').replaceWith('<button type="button" class="btn btn-info disabled"><i class="fa fa-clipboard"></i> <?php echo $button_apply_schedule; ?></button>');
				    $('#button-delete').replaceWith('<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>');
					$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=presence/schedule/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}	
});
//--></script> 
</div>
<?php echo $footer; ?> 
