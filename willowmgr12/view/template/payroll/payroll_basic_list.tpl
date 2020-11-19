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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
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
            <div class="col-sm-4">
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
					<td class="text-right"><?php echo $column_gaji_pokok; ?></td>
					<td class="text-right"><?php echo $column_tunj_jabatan; ?></td>
					<td class="text-right"><?php echo $column_tunj_hadir; ?></td>
					<td class="text-right"><?php echo $column_tunj_pph; ?></td>
					<td class="text-right"><?php echo $column_uang_makan; ?></td>
					<td class="text-right"><?php echo $column_gaji_dasar; ?></td>
					<td class="text-right"><?php echo $column_date_added; ?></td>
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
				  <td class="text-right"><?php echo $customer['gaji_pokok']; ?></td>
				  <td class="text-right"><?php echo $customer['tunj_jabatan']; ?></td>
				  <td class="text-right"><?php echo $customer['tunj_hadir']; ?></td>
				  <td class="text-right"><?php echo $customer['tunj_pph']; ?></td>
				  <td class="text-right"><?php echo $customer['uang_makan']; ?></td>
				  <td class="text-right text-warning"><?php echo $customer['gaji_dasar']; ?></td>
				  <td class="text-right"><?php echo $customer['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $customer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
	url = 'index.php?route=payroll/payroll_basic&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();
	
	if (filter_customer_group_id != '*') {
		url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
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
</div>
<?php echo $footer; ?> 
