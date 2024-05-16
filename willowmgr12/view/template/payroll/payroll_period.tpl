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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_period_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-period"><?php echo $entry_period; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_period" value="<?php echo $filter_period; ?>" placeholder="<?php echo $entry_period; ?>" data-date-format="MMM YY" id="input-period" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-payroll-status"><?php echo $entry_payroll_status; ?></label>
                <select name="filter_payroll_status" id="input-payroll-status" class="form-control">
                  <option value="*"></option>
                  <?php foreach ($payroll_statuses as $payroll_status) { ?>
                  <?php if ($payroll_status['payroll_status_id'] == $filter_payroll_status) { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>" selected="selected"><?php echo $payroll_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_status['payroll_status_id']; ?>"><?php echo $payroll_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" action="" enctype="multipart/form-data" id="form-payroll">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_period; ?></td>
                  <td class="text-left"><?php echo $column_date_start; ?></td>
                  <td class="text-left"><?php echo $column_date_end; ?></td>
                  <td class="text-left"><?php echo $column_payroll_status; ?></td>
                  <td class="text-right"><?php echo $column_all_net_salary; ?></td>
                  <td class="text-right"><?php echo $column_all_grandtotal; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($payroll_periods) { ?>
                <?php foreach ($payroll_periods as $payroll_period) { ?>
                <tr>
                  <td class="text-left"><?php echo $payroll_period['period']; ?></td>
                  <td class="text-left"><?php echo $payroll_period['date_start']; ?></td>
                  <td class="text-left"><?php echo $payroll_period['date_end']; ?></td>
                  <td class="text-left"><?php echo $payroll_period['payroll_status']; ?></td>
                  <td class="text-right"><?php echo $payroll_period['net_salary']; ?></td>
                  <td class="text-right"><?php echo $payroll_period['grandtotal']; ?></td>
                  <td class="text-right">
                  <?php if ($payroll_period['generate_check']) { ?>
				    <button type="button" value="<?php echo $payroll_period['presence_period_id']; ?>" id="button-generate<?php echo $payroll_period['presence_period_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-toggle="tooltip" title="<?php echo $button_generate; ?>" class="btn btn-warning"><i class="fa fa-refresh"></i></button>
                  <?php } else { ?>
				    <button type="button" disabled="disabled" class="btn btn-warning"><i class="fa fa-refresh"></i></button>
                  <?php } ?>
                  <?php if ($payroll_period['view_check']) { ?>
					<a href="<?php echo $payroll_period['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info" id="button-view<?php echo $payroll_period['presence_period_id']; ?>"><i class="fa fa-eye"></i></a>
                  <?php } else { ?>
					<a class="btn btn-info" disabled="disabled" id="button-view<?php echo $payroll_period['presence_period_id']; ?>"><i class="fa fa-eye"></i></a>
                  <?php } ?>
				  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
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
	url = 'index.php?route=payroll/payroll&token=<?php echo $token; ?>';

	var filter_payroll_status = $('select[name=\'filter_payroll_status\']').val();

	if (filter_payroll_status != '*') {
		url += '&filter_payroll_status=' + encodeURIComponent(filter_payroll_status);
	}

	var filter_period = $('input[name=\'filter_period\']').val();

	if (filter_period) {
		url += '&filter_period=' + encodeURIComponent(filter_period);
	}

	location = url;
});

$('button[id^=\'button-generate\']').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;
		
		$.ajax({
			url: 'index.php?route=payroll/payroll/generate&token=<?php echo $token; ?>&presence_period_id=' + $(node).val(),
//			dataType: 'json',
//			crossDomain: true,
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
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				    $(node).replaceWith('<button type="button" disabled="disabled" id="button-generate' + $(node).val() + '" class="btn btn-warning"><i class="fa fa-refresh"></i></button>');
				    $('#button-view' + $(node).val()).replaceWith('<a href="index.php?route=payroll/payroll/info&token=<?php echo $token; ?>&presence_period_id=' + $(node).val() + '" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info" id="button-view' + $(node).val() + '"><i class="fa fa-eye"></i></a>');
				
//					location.reload();
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
$('.date').datetimepicker({
	minViewMode: 'months',
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 
