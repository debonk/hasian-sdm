<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="row">
		<div class="col-md-4" id="period-info"></div>
		<div class="col-md-8" id="customer-info"></div>
    </div>
	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
		<legend><?php echo $text_payroll_basic; ?></legend>
		  <div class="table-responsive">
		    <table class="table table-bordered">
		  	<thead>
		  	  <tr>
		  		<td class="text-left"><?php echo $column_basic_date_added; ?></td>
		  		<td class="text-right"><?php echo $column_gaji_pokok; ?></td>
		  		<td class="text-right"><?php echo $column_tunj_jabatan; ?></td>
		  		<td class="text-right"><?php echo $column_tunj_hadir; ?></td>
		  		<td class="text-right"><?php echo $column_tunj_pph; ?></td>
		  		<td class="text-right"><?php echo $column_uang_makan; ?></td>
		  		<td class="text-right"><?php echo $column_gaji_dasar; ?></td>
		  		<td class="text-right"><?php echo $column_action; ?></td>
		  	  </tr>
		  	</thead>
		  	<tbody>
		  	  <?php if ($payroll_basic_check) { ?>
		  	  <tr>
		  		<td class="text-left"><?php echo $basic_date_added; ?></td>
		  		<td class="text-right"><?php echo $gaji_pokok; ?></td>
		  		<td class="text-right"><?php echo $tunj_jabatan; ?></td>
		  		<td class="text-right"><?php echo $tunj_hadir; ?></td>
		  		<td class="text-right"><?php echo $tunj_pph ?></td>
		  		<td class="text-right"><?php echo $uang_makan; ?></td>
		  		<td class="text-right text-warning"><?php echo $gaji_dasar; ?></td>
			    <?php if ($payroll_status_check) { ?>
				  <td class="text-right"><a href="<?php echo $payroll_basic_edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer"><i class="fa fa-pencil"></i></a></td>
			    <?php } else { ?>
				  <td class="text-right"><a href="<?php echo $payroll_basic_edit; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info" target="_blank" rel="noopener noreferrer"><i class="fa fa-eye"></i></a></td>
			    <?php } ?>
		  	  </tr>
		  	  <?php } else { ?>
		  	  <tr>
		  		<td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                <td class="text-right"><a href="<?php echo $payroll_basic_edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer"><i class="fa fa-pencil"></i></a></td>
		  	  </tr>
		  	  <?php } ?>
		  	</tbody>
		    </table>
		  </div>
		<legend><?php echo $text_presence_summary; ?></legend>
		  <div class="table-responsive">
		    <table class="table table-bordered">
		  	  <thead>
		  	    <tr>
			  	  <td class="text-center table-evenly-13"><?php echo $column_hke; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_h; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_s; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_i; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_ns; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_ia; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_a; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_c; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_t1; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_t2; ?></td>
			  	  <td class="text-center table-evenly-13"><?php echo $column_t3; ?></td>
		  	  	  <td class="text-right"><?php echo $column_action; ?></td>
		  	    </tr>
		  	  </thead>
		  	  <tbody>
		  	    <?php if ($presence_summary_check) { ?>
		  	      <tr>
                    <td id="hke" class="text-center text-bold"><?php echo $hke; ?></td>
					<?php foreach ($presence_items as $presence_item) { ?>
                      <td id="<?php echo 'total-' . $presence_item; ?>" class="text-center"><?php echo $total_item[$presence_item]; ?></td>
					<?php } ?>
					<td class="text-right">
					  <?php if ($payroll_status_check) { ?>
				        <button type="button" id="button-presence" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-warning"><i class="fa fa-pencil"></i></button>
					  <?php } else { ?>
				        <button type="button" class="btn btn-warning disabled"><i class="fa fa-pencil"></i></button>
					  <?php } ?>
					  <a href="<?php echo $presence_summary_edit; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer"><i class="fa fa-eye"></i></a>
					</td>
		  	      </tr>
		  	      <tr>
		  	    	<td id="absence-info" colspan="12"></td>
		  	      </tr>
		  	    <?php } else { ?>
		  	      <tr>
		  	  	  <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
                  <td class="text-right"><a href="<?php echo $presence_summary_edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer"><i class="fa fa-pencil"></i></a></td>
		  	      </tr>
		  	    <?php } ?>
		  	  </tbody>
		    </table>
		  </div>
		<!-- Payroll detail info (widget) -->
        <div id="payroll-detail-info"></div>
        <div>
  	      <?php if ($payroll_basic_check && $presence_summary_check) { ?>
			  <?php if ($payroll_status_check) { ?>
				<div class="text-right">
				  <button type="button" id="button-payroll" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning"><i class="fa fa-save"></i> <?php echo $button_payroll_update; ?></button>
				</div>
			  <?php } else { ?>
				<div class="text-right">
				  <button type="button" class="btn btn-warning disabled"><i class="fa fa-save"></i> <?php echo $button_payroll_update; ?></button>
				</div>
			  <?php } ?>
  	      <?php } ?>
        </div>
        <div id="payroll-old"></div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#payroll-detail-info').load('index.php?route=payroll/payroll/payrolldetailinfo&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>&customer_id=<?php echo $customer_id; ?>');

$('#payroll-old').load('index.php?route=payroll/payroll/getpayroll&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>&customer_id=<?php echo $customer_id; ?>');

$('#absence-info').load('index.php?route=common/absence_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&presence_period_id=<?php echo $presence_period_id; ?>');
</script>
  <script type="text/javascript">
$(document).on('click', '#button-presence', function() {
	<?php foreach ($presence_items as $presence_item) { ?>
		$('td[id=\'total-<?php echo $presence_item ?>\']').html('<input class="text-center form-control" type="text" name="total_<?php echo $presence_item ?>" value="<?php echo $total_item[$presence_item]; ?>" onkeyup="calchke()"  />');
	<?php } ?>

	$('#button-presence').replaceWith('<button id="button-presence-override" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_override; ?>" class="btn btn-warning"><i class="fa fa-check"></i></button>');
});

function calchke() {
	var hke = 0;
	var counter = 0;

	<?php foreach ($presence_items as $presence_item) { ?>
		if (counter < 6) {
			hke += Number($('input[name=\'total_<?php echo $presence_item ?>\']').val());
		}
		counter++;
	<?php } ?>

	$('#hke').html(hke);
}

$(document).on('click', '#button-presence-override', function() {
	var data = 'presence_period_id=<?php echo $presence_period_id; ?>&customer_id=<?php echo $customer_id; ?>';
	<?php foreach ($presence_items as $presence_item) { ?>
		data +='&total_<?php echo $presence_item ?>=' + encodeURIComponent($('input[name=\'total_<?php echo $presence_item ?>\']').val());
	<?php } ?>

	$.ajax({
		url: 'index.php?route=presence/presence/overridepresence&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: data,
		beforeSend: function() {
			$('#button-presence-override').button('loading');
		},
		complete: function() {
			$('#button-presence-override').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
			}

			if (json['success']) {
				<?php foreach ($presence_items as $presence_item) { ?>
					$('td[id=\'total-<?php echo $presence_item ?>\']').html(encodeURIComponent($('input[name=\'total_<?php echo $presence_item ?>\']').val()));
				<?php } ?>
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#button-presence-override').replaceWith('<button type="button" id="button-presence" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-warning"><i class="fa fa-pencil"></i></button>');

				$('#payroll-detail-info').load('index.php?route=payroll/payroll/payrolldetailinfo&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>&customer_id=<?php echo $customer_id; ?>');
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	
});
</script>
  <script type="text/javascript">
$('#absence-info').on('click', 'button[id^=\'button-action\']', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;
		
		$.ajax({
			url: 'index.php?route=' + $(node).val() + '&token=<?php echo $token; ?>',
			dataType: 'json',
			crossDomain: false,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				if (json['error']) {
					alert(json['error']);
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

$('#button-payroll').on('click', function(e) {
  e.preventDefault();

  $.ajax({
		url: 'index.php?route=payroll/payroll/addpayroll&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'presence_period_id=<?php echo $presence_period_id; ?>&customer_id=<?php echo $customer_id; ?>',
		beforeSend: function() {
			$('#button-payroll').button('loading');
		},
		complete: function() {
			$('#button-payroll').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#payroll-old').load('index.php?route=payroll/payroll/getpayroll&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>&customer_id=<?php echo $customer_id; ?>');
			}
		}
	});
});
</script>
</div>
<?php echo $footer; ?>