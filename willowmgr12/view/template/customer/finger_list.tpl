<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
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
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"><?php echo $text_active; ?></option>
                  <?php if ($filter_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_inactive; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_inactive; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                    <option value="0" selected="selected"><?php echo $text_all_status; ?></option>
                  <?php } else { ?>
                    <option value="0"><?php echo $text_all_status; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php if ($sort == 'nip') { ?>
                    <a href="<?php echo $sort_nip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_nip; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_nip; ?>"><?php echo $column_nip; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'customer_group') { ?>
                    <a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a>
                    <?php } ?></td>
				  <td class="text-left"><?php if ($sort == 'location') { ?>
				    <a href="<?php echo $sort_location; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_location; ?></a>
				    <?php } else { ?>
				    <a href="<?php echo $sort_location; ?>"><?php echo $column_location; ?></a>
				    <?php } ?></td>
					<td class="text-right"><?php echo $column_date_added; ?></td>
					<td class="text-right"><?php echo $column_username; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($customers) { ?>
                <?php foreach ($customers as $customer) { ?>
                <tr>
                  <td class="text-left"><?php echo $customer['nip']; ?></td>
                  <td class="text-left"><?php echo $customer['name']; ?></td>
                  <td class="text-left"><?php echo $customer['customer_group']; ?></td>
                  <td class="text-left"><?php echo $customer['location']; ?></td>
				  <td class="text-right" id="date_added<?php echo $customer['customer_id']; ?>"><?php echo $customer['date_added']; ?></td>
				  <td class="text-right" id="username<?php echo $customer['customer_id']; ?>"><?php echo $customer['username']; ?></td>
                  <td class="text-right">
				    <?php if ($customer['register']) { ?>
                    <button type="button" value="<?php echo $customer['customer_id']; ?>" id="button-register<?php echo $customer['customer_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_register; ?>" class="btn btn-primary"><i class="fa fa-barcode"></i></button>
					<?php } else { ?>
                    <button type="button" value="<?php echo $customer['customer_id']; ?>" id="button-delete<?php echo $customer['customer_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
                    <button type="button" value="<?php echo $customer['customer_id']; ?>" id="button-verification<?php echo $customer['customer_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_verification; ?>" class="btn btn-default"><i class="fa fa-sign-in"></i></button>
					<?php } ?>
				    <a href="<?php echo $customer['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info" target="_blank"><i class="fa fa-eye"></i></a>
				  </td>
                 </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$(document).keypress(function(e) {
        if(e.which == 13) {
			$("#button-filter").click();
        }
    });

$('#button-filter').on('click', function() {
	url = 'index.php?route=customer/finger&token=<?php echo $token; ?>';
	
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
	
	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}	
	
	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=presence/presence/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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
  <script type="text/javascript"><!--
$('button[id^=\'button-register\']').on('click', function(e) {
	var node = this;
	$(node).button('loading');
  $('.alert').remove();
  	
	url = 'index.php?route=customer/finger/register&token=<?php echo $token; ?>&customer_id=' + $(node).val();
	location = url;
	
	reg_status = 0;
	
	// try	{
		// timer_register.stop();
	// }
	// catch(err) {
		// console.log('Registration timer has been init');
	// }
	
	var limit = 12;
	var ct = 1;
	var timeout = 1500;
	
	timer_register = $.timer(timeout, function() {
		// console.log('Registration checking...');
		getRegisterStatus($(node).val());
		// console.log('Reg status = ' + reg_status);
		
		if (ct >= limit || reg_status == 1) {
			timer_register.stop();
			// console.log('Registration checking end');
			
			$(node).button('reset');
			
			if (ct >= limit && reg_status == 0) {
				alert('<?php echo $error_register; ?>');
			}
			
			if (reg_status == 1) {
                html = '<button type="button" value="' + $(node).val() + '" id="button-delete' + $(node).val() + '" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
                html += ' <button type="button" value="' + $(node).val() + '" id="button-verification' + $(node).val() + '" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_verification; ?>" class="btn btn-default"><i class="fa fa-sign-in"></i></button>';
				
				$(node).replaceWith(html);
				$('#date_added' + $(node).val()).html(date_added);
				$('#username' + $(node).val()).html(username);
				alert('<?php echo $text_success_register; ?>');
			}
		}
		ct++;
	});
});

function getRegisterStatus(customer_id) {
	$.ajax({
		url: 'index.php?route=customer/finger/getRegisterStatus&token=<?php echo $token; ?>&customer_id=' + customer_id,

		dataType: 'json',
		
		success: function(json) {
			if (json['reg_status']) {
				reg_status = 1;
				username = json['username'];
				date_added = json['date_added'];
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

//--></script> 
  <script type="text/javascript"><!--
$('td').on('click', 'button[id^=\'button-verification\']', function(e) {
	var node = this;
	$(node).button('loading');
  $('.alert').remove();
	
	url = 'index.php?route=customer/finger/verification&token=<?php echo $token; ?>&customer_id=' + $(node).val();
	location = url;
	
	setTimeout(function() {
		$(node).button('reset');
	}, 1500);
});

$('td').on('click', 'button[id^=\'button-delete\']', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;

		$.ajax({
			url: 'index.php?route=customer/finger/deleteFinger&token=<?php echo $token; ?>',
			dataType: 'json',
			type: 'post',
			data: 'customer_id=' + $(node).val(),
			crossDomain: false,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					alert(json['success']);

					location.reload();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script> 
</div>
<?php echo $footer; ?> 
