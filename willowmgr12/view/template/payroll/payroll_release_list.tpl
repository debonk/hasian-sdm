<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<?php if ($released_status_check) { ?>
			<button type="button" id="button-payroll-complete" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning"><i class="fa fa-check"></i> <?php echo $button_payroll_complete; ?></button>
			<button type="button" class="btn btn-default" onclick="$('#form-payroll-release-list').attr('action', '<?php echo $export_cimb; ?>').submit()"><i class="fa fa-upload"></i> <?php echo $button_export_cimb; ?></button>
			<button type="button" id="button-send" data-toggle="tooltip" title="<?php echo $button_send; ?>" class="btn btn-default" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-payroll-release-list').submit() : false;"><i class="fa fa-envelope"></i></button>
		<?php } else { ?>
			<button type="button" class="btn btn-warning disabled"><i class="fa fa-check"></i> <?php echo $button_payroll_complete; ?></button>
			<button type="button" class="btn btn-default disabled"><i class="fa fa-upload"></i> <?php echo $button_export_cimb; ?></button>
			<button type="button" class="btn btn-default disabled"><i class="fa fa-envelope"></i></button>
		<?php } ?>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="row">
		<div class="col-sm-6" id="period-info"></div>
		<div class="col-sm-6" id="release-info"></div>
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
                <label class="control-label" for="input-payroll-method"><?php echo $entry_payroll_method; ?></label>
                <select name="filter_payroll_method_id" id="input-payroll-method" class="form-control">
                  <option value="*"><?php echo $text_all_payroll_method ?></option>
                  <?php foreach ($payroll_methods as $payroll_method) { ?>
                  <?php if ($payroll_method['payroll_method_id'] == $filter_payroll_method_id) { ?>
                  <option value="<?php echo $payroll_method['payroll_method_id']; ?>" selected="selected"><?php echo $payroll_method['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $payroll_method['payroll_method_id']; ?>"><?php echo $payroll_method['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-statement-sent"><?php echo $entry_statement_sent; ?></label>
                <select name="filter_statement_sent" id="input-statement-sent" class="form-control">
										<option value="*"><?php echo $text_all; ?></option>
										<?php if ($filter_statement_sent) { ?>
										<option value="1" selected="selected"><?php echo $text_yes; ?></option>
										<?php } else { ?>
										<option value="1"><?php echo $text_yes; ?></option>
										<?php } ?>
										<?php if (!$filter_statement_sent && !is_null($filter_statement_sent)) { ?>
										<option value="0" selected="selected"><?php echo $text_no; ?></option>
										<?php } else { ?>
										<option value="0"><?php echo $text_no; ?></option>
										<?php } ?>
									</select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form method="post" action="<?php echo $send; ?>" enctype="multipart/form-data" id="form-payroll-release-list">
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
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
				<td class="text-left"><?php echo $column_email; ?></td>
				<td class="text-left"><?php echo $column_acc_no; ?></td>
                <td class="text-left"><?php if ($sort == 'payroll_method') { ?>
                  <a href="<?php echo $sort_payroll_method; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_payroll_method; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_payroll_method; ?>"><?php echo $column_payroll_method; ?></a>
                <?php } ?></td>
				<td class="text-right"><?php echo $column_grandtotal; ?></td>
                <td class="text-center"><?php if ($sort == 'statement_sent') { ?>
                  <a href="<?php echo $sort_statement_sent; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_statement_sent; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_statement_sent; ?>"><?php echo $column_statement_sent; ?></a>
                <?php } ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($payroll_releases) { ?>
                <?php foreach ($payroll_releases as $payroll_release) { ?>
				  <tr>
                    <td class="text-center"><?php if (in_array($payroll_release['customer_id'], $selected)) { ?>
                      <input type="checkbox" name="selected[]" value="<?php echo $payroll_release['customer_id']; ?>" checked="checked" />
                      <?php } else { ?>
                      <input type="checkbox" name="selected[]" value="<?php echo $payroll_release['customer_id']; ?>" />
                      <?php } ?></td>
					<td class="text-left"><?php echo $payroll_release['nip']; ?></td>
					<td class="text-left"><?php echo $payroll_release['name']; ?></td>
					<td class="text-left"><?php echo $payroll_release['customer_group']; ?></td>
					<td class="text-left"><?php echo $payroll_release['email']; ?></td>
					<td class="text-left"><?php echo $payroll_release['acc_no']; ?></td>
					<td class="text-left"><?php echo $payroll_release['payroll_method']; ?></td>
					<td class="text-right"><?php echo $payroll_release['grandtotal']; ?></td>
					<td class="text-center"><?php echo $payroll_release['statement_sent'] ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' ?></td>
					<!-- <td class="text-left"><?php echo $payroll_release['statement_sent'] ? $text_yes : $text_no; ?></td> -->
				  </tr>
				<?php } ?>
              <?php } else { ?>
				<tr>
				  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
  <script type="text/javascript">
$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

$('#release-info').load('index.php?route=payroll/payroll_release/releaseinfo&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

$(document).keypress(function(e) {
        if(e.which == 13) {
			$("#button-filter").click();
        }
    });

$('#button-filter').on('click', function() {
	url = 'index.php?route=payroll/payroll_release/info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
	
	if (filter_customer_group_id != '*') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
	}	
	
	var filter_payroll_method_id = $('select[name=\'filter_payroll_method_id\']').val();
	
	if (filter_payroll_method_id != '*') {
		url += '&filter_payroll_method_id=' + encodeURIComponent(filter_payroll_method_id);
	}	
	
	var filter_statement_sent = $('select[name=\'filter_statement_sent\']').val();
	
	if (filter_statement_sent != '*') {
		url += '&filter_statement_sent=' + encodeURIComponent(filter_statement_sent); 
	}	
	
	location = url;
});
</script> 
  <script type="text/javascript">
$('#button-payroll-complete').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		$.ajax({
			url: 'index.php?route=payroll/payroll_release/completepayroll&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>',
			dataType: 'json',
			crossDomain: true,
			beforeSend: function() {
				$('#button-payroll-complete').button('loading');
			},
			complete: function() {
				$('#button-payroll-complete').button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				    $('#button-payroll-complete').replaceWith('<button type="button" class="btn btn-warning disabled"><i class="fa fa-check"></i> <?php echo $button_payroll_complete; ?></button>');
					$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
</script> 
  <script type="text/javascript">
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
</script> 
</div>
<?php echo $footer; ?> 
