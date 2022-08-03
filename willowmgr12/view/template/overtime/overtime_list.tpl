<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-overtime').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3><h4 class="pull-right"><i class="fa fa-line-chart"></i> <?php echo $grandtotal; ?></h4>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-period"><?php echo $entry_period; ?></label>
                <select name="filter_period_id" id="input-period" class="form-control">
			  	  <option value="*"><?php echo $text_all ?></option>
                  <?php foreach ($periods as $period) { ?>
                    <?php if ($period['presence_period_id'] == $filter_period_id) { ?>
                      <option value="<?php echo $period['presence_period_id']; ?>" selected="selected"><?php echo date('M y',strtotime($period['period'])); ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $period['presence_period_id']; ?>"><?php echo date('M y',strtotime($period['period'])); ?></option>
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
                <label class="control-label" for="input-overtime-type"><?php echo $entry_overtime_type; ?></label>
			    <select name="filter_overtime_type_id" id="input-overtime-type" class="form-control">
			  	<option value="*"><?php echo $text_all ?></option>
			  	<?php foreach ($overtime_types as $overtime_type) { ?>
			  	  <?php if ($overtime_type['overtime_type_id'] == $filter_overtime_type_id) { ?>
			  		<option value="<?php echo $overtime_type['overtime_type_id']; ?>" selected="selected"><?php echo $overtime_type['name']; ?></option>
			  	  <?php } else { ?>
			  		<option value="<?php echo $overtime_type['overtime_type_id']; ?>"><?php echo $overtime_type['name']; ?></option>
			  	  <?php } ?>
			  	<?php } ?>
			    </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"><?php echo $text_all; ?></option>
                  <?php if ($filter_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_paid; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_paid; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                    <option value="0" selected="selected"><?php echo $text_unpaid; ?></option>
                  <?php } else { ?>
                    <option value="0"><?php echo $text_unpaid; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-overtime">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'o.date') { ?>
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
				  <td class="text-left"><?php echo $column_overtime_type; ?></td>
				  <td class="text-left"><?php echo $column_description; ?></td>
                  <td class="text-left"><?php echo $column_wage; ?></td>
                  <td class="text-center"><?php if ($sort == 'pcv.presence_period_id') { ?>
                    <a href="<?php echo $sort_presence_period; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_payment; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_presence_period; ?>"><?php echo $column_payment; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_username; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($overtimes) { ?>
                  <?php foreach ($overtimes as $overtime) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($overtime['overtime_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $overtime['overtime_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $overtime['overtime_id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><?php echo $overtime['date']; ?></td>
                      <td class="text-left"><?php echo $overtime['name']; ?></td>
                      <td class="text-left"><?php echo $overtime['overtime_type']; ?></td>
                      <td class="text-left"><?php echo $overtime['description']; ?></td>
				      <td class="text-right nowrap"><?php echo $overtime['wage']; ?></td>
					  <?php if ($overtime['payment']) { ?>
                        <td class="text-center">
						  <?php echo $overtime['payment']; ?>
						    <?php if ($overtime['payment_status']) { ?>
							  <i class="fa fa-check text-info"></i>
						    <?php } else { ?>
							  <i class="fa fa-question text-warning"></i>
						    <?php } ?>
						</td>
					  <?php } else { ?>
					    <td class="text-center text-warning"><i class="fa fa-question"></i></td>
					  <?php } ?>
                      <td class="text-left"><?php echo $overtime['username']; ?></td>
                      <td class="text-right nowrap">
					    <?php if ($overtime['payment']) { ?>
					      <a href="<?php echo $overtime['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info" target="_blank" rel="noopener noreferrer"><i class="fa fa-eye"></i></a>
						<?php } ?>
                        <a href="<?php echo $overtime['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
					  </td>
                    </tr>
                  <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?route=overtime/overtime&token=<?php echo $token; ?>';
	
	var filter_period_id = $('select[name=\'filter_period_id\']').val();
	
	if (filter_period_id != '*') {
		url += '&filter_period_id=' + encodeURIComponent(filter_period_id);
	}	
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_overtime_type_id = $('select[name=\'filter_overtime_type_id\']').val();
	
	if (filter_overtime_type_id != '*') {
		url += '&filter_overtime_type_id=' + encodeURIComponent(filter_overtime_type_id);
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
