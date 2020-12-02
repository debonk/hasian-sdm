<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
	  <div class="pull-right">
	    <?php if ($action == 'logout') { ?>
	    <a href="<?php echo $href_login; ?>" type="button" id="button-login" class="btn btn-default"><?php echo $button_login; ?></a>
	    <a type="button" id="button-logout" class="btn btn-warning"><?php echo $button_logout; ?></a>
		<?php } else { ?>
	    <a type="button" id="button-login" class="btn btn-success"><?php echo $button_login; ?></a>
	    <a href="<?php echo $href_logout; ?>" type="button" id="button-logout" class="btn btn-default"><?php echo $button_logout; ?></a>
		<?php } ?>
	  </div>
	  <div class="col-sm-4 pull-right">
	  <select id="input-location" class="form-control" >
	    <option value="*"><?php echo $store_name ?></option>
	    <?php foreach ($locations as $location) { ?>
		<?php if ($location['location_id'] == $location_id) { ?>
	    <option value="<?php echo $location['location_id']; ?>" selected="selected"><?php echo $location['name']; ?></option>
	    <?php } else { ?>
	    <option value="<?php echo $location['location_id']; ?>"><?php echo $location['name']; ?></option>
	    <?php } ?>
	    <?php } ?>
	  </select>
	  </div>
      <h1><?php echo $text_list; ?></h1>
	  <div class="row">
		<?php if ($customers) { ?>
		<?php foreach ($customers as $customer) { ?>
		<div class="col-md-2 col-sm-3 col-xs-4">
		  <div class="tile tile-<?php echo $customer['log_class']; ?>">
		    <a href="#" id="customer<?php echo $customer['customer_id']; ?>" value="<?php echo $customer['customer_id']; ?>">
		      <div class="tile-body">
		        <?php if ($customer['image']) { ?>
		        <img src="<?php echo $customer['image']; ?>" alt="<?php echo $customer['name']; ?>" title="<?php echo $customer['name']; ?>" class="img-thumbnail" />
				<h4 class="text-center"><?php echo $customer['text_image']; ?></h4>
		        <?php } else { ?>
		        <?php echo $customer['name']; ?>
		        <?php } ?>
		      </div>
		    </a>
		  </div>
		</div>
		<?php } ?>
		<?php } else { ?>
		<h3 class="text-center"><?php echo $text_no_results; ?></h3>
		<?php } ?>
        <?php echo $content_bottom; ?>
	  </div>
	  <div id="fixed-alert"></div>
      <?php echo $column_right; ?>
	</div>
  </div>
  <script type="text/javascript"><!--
$('select[id=\'input-location\']').on('change', function() {
	url = 'index.php?route=presence/login&action=<?php echo $action; ?>';

	var location_id = $('select[id=\'input-location\']').val();
	
	if (location_id != '*') {
		url += '&location_id=' + location_id;
	}
	
	location = url;
});
//--></script> 
  <script type="text/javascript">
var log_ct = 1;
var click_ct = 1;
var alert_fade_out;


$('a[id^=\'customer\']').on('click', function(e) {
	e.preventDefault();
	
	if (click_ct == 1) {
		click_ct++;
		
		var node = this;
		var customer_id = $(node).attr('value');
		var action = '<?php echo $action; ?>';

		$.ajax({
			url: 'index.php?route=presence/login/validateLog',
			type: 'post',
			dataType: 'json',
			data: 'customer_id=' + customer_id + '&action=' + action,
			crossDomain: true,
			beforeSend: function() {
				clearTimeout(alert_fade_out);
				$('.alert').remove();
			},

			success: function(json) {
				if (json['error']) {
					$('#fixed-alert').html('<div class="alert fixed-alert alert-danger">' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					
					click_ct = 1;
  
					alert_fade_out = setTimeout(function(){$('.alert').fadeOut();}, 3000);
				}

				if (json['process_verification']) {
					processVerification(customer_id, action);
				}
			},
			
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});

function processVerification(customer_id, action) {
	var button = $('#button-' + action);

	<?php if ($use_fingerprint) { ?>
	url = 'index.php?route=presence/login/verification&customer_id=' + customer_id + '&action=' + action;
	location = url;
	<?php } ?>

	log_status = 0;
	
	// try	{
	// 	timer_register.stop();
	// }
	// catch(err) {
	// 	console.log('Verification timer has been init');
	// }
	
	var limit = 22;
	var ct = 1;
	var timeout = 500;
	
	timer_register = $.timer(timeout, function() {
		// console.log('Verification checking... ' + ct);
		
		getLogStatus(customer_id, action);
		// console.log('Log status = ' + log_status);
		
		if (ct >= limit || log_status == 1) {
			timer_register.stop();
			// console.log('Verification checking end');
			
			if (ct >= limit && log_status == 0) {
				$('#fixed-alert').html('<div class="alert fixed-alert alert-danger"><?php echo $error_verification; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				click_ct = 1;
			}
			
			if (log_status == 1) {
				$('#customer' + customer_id).parent().removeClass().addClass('tile tile-' + action);

				$('#fixed-alert').html('<div class="alert fixed-alert alert-success">' + text_success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				click_ct = 1;
				
				if (log_ct >= 5) {
					location.reload();				
				}
				log_ct++;
			}
  
			alert_fade_out = setTimeout(function(){$('.alert').fadeOut();}, 3000);
		}
		
		ct++;
	});
};

function getLogStatus(customer_id, action) {
	$.ajax({
		url: 'index.php?route=presence/login/getLogStatus&customer_id=' + customer_id + '&action=' + action,
		dataType: 'json',
		crossDomain: true,
		success: function(json) {
			if (json['success']) {
				log_status = 1;
				text_success = json['success'];
			}
		},
		
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
</script> 
</div>
<?php echo $footer; ?>
