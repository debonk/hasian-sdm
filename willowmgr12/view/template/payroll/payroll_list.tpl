<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<?php if ($payroll_status_check) { ?>
			<button type="button" value="<?php echo $presence_period_id; ?>" id="button-payroll-approve" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning"><i class="fa fa-check-square-o"></i> <?php echo $button_payroll_approve; ?></button>
			<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary" id="button-add"><i class="fa fa-plus"></i></a>
			<button type="button" id="button-delete" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-payroll-list').submit() : false;"><i class="fa fa-trash-o"></i></button>
		<?php } else { ?>
			<button type="button" class="btn btn-warning" disabled><i class="fa fa-check-square-o"></i> <?php echo $button_payroll_approve; ?></button>
			<button type="button" class="btn btn-primary" disabled><i class="fa fa-plus"></i></button>
			<button type="button" class="btn btn-danger" disabled><i class="fa fa-trash-o"></i></button>
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
		<div class="col-sm-6" id="period-info"></div>
		<div class="col-sm-6" id="payroll-info"></div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
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
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
		<div class="panel panel-default">
		  <div class="panel-heading">
			<h4 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_subtotal; ?></h4>
		  </div>
		  <table class="table table-bordered">
			<tr>
			  <td class="text-left"><h4><?php echo $column_net_salary; ?></h4></td>
			  <?php foreach ($component_codes as $code) { ?>
				<td class="text-left"><h4><?php echo $text_component[$code]; ?></h4></td>
			  <?php } ?>
			  <td class="text-left"><h4><?php echo $column_grandtotal; ?></h4></td>
			</tr>
			<tr>
			  <td class="text-right"><h4><?php echo $component_total['net_salary']; ?></h4></td>
			  <?php foreach ($component_codes as $code) { ?>
				<td class="text-right"><h4><?php echo $component_total[$code]; ?></h4></td>
			  <?php } ?>
			  <td class="text-right"><h4><?php echo $component_total['grandtotal']; ?></h4></td>
			</tr>
		  </table>
		</div>
		<form method="post" action="<?php echo $delete; ?>" enctype="multipart/form-data" id="form-payroll-list">
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
					<td class="text-right"><?php echo $column_net_salary; ?></td>
					  <td class="text-center"><?php echo $column_component; ?></td>
					<td class="text-right"><?php echo $column_grandtotal; ?></td>
                  <td class="text-left"><?php echo $column_note; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($customers) { ?>
                <?php foreach ($customers as $customer) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($customer['customer_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $customer['nip']; ?></td>
                  <td class="text-left"><?php echo $customer['name']; ?></td>
                  <td class="text-left"><?php echo $customer['customer_group']; ?></td>
				  <td class="text-right nowrap"><?php echo $customer['net_salary']; ?></td>
				    <td class="text-left">
				  <?php foreach ($component_codes as $code) { ?>
				  <div class="col-sm-6 nowrap">
					<?php echo $text_component[$code] . ': ' . $customer['component_data'][$code]; ?>
					</div>
				  <?php } ?>
					</td>
				  <td class="text-right"><?php echo $customer['grandtotal']; ?></td>
				  <td class="text-left"><?php echo $customer['note']; ?></td>
                  <td class="text-right"><a href="<?php echo $customer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="14"><?php echo $text_no_results; ?></td>
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
$(document).keypress(function(e) {
        if(e.which == 13) {
			$("#button-filter").click();
        }
    });

$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

$('#payroll-info').load('index.php?route=common/payroll_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');

$('#button-filter').on('click', function() {
	url = 'index.php?route=payroll/payroll/info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
	
	if (filter_customer_group_id != '*') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
	}	
	
	location = url;
});
</script> 
  <script type="text/javascript">

$('#button-payroll-approve').on('click', function(e) {
	if (confirm('<?php echo $text_approve_confirm; ?>')) {
		$.ajax({
			url: 'index.php?route=payroll/payroll/approvePayroll&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>',
			dataType: 'json',
			crossDomain: false,
			beforeSend: function() {
				$('#button-payroll-approve').button('loading');
			},
			complete: function() {
				$('#button-payroll-approve').button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				    $('#button-payroll-approve').replaceWith('<button type="button" class="btn btn-warning" disabled><i class="fa fa-check-square-o"></i> <?php echo $button_payroll_approve; ?></button>');
				    $('#button-add').replaceWith('<button type="button" value="" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary disabled"><i class="fa fa-plus"></i></button>');
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
