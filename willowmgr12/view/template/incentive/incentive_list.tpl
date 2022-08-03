<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-incentive').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-description"><?php echo $entry_description; ?></label>
                <input type="text" name="filter_description" value="<?php echo $filter_description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*"><?php echo $text_unpaid; ?></option>
                  <?php if ($filter_status) { ?>
                    <option value="1" selected="selected"><?php echo $text_paid; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_paid; ?></option>
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
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-incentive">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
				  <td class="text-left"><?php echo $column_date; ?></td>
				  <td class="text-left"><?php echo $column_name; ?></td>
				  <td class="text-left"><?php echo $column_description; ?></td>
				  <td class="text-right"><?php echo $column_amount; ?></td>
                  <td class="text-center"><?php echo $column_payment; ?></td>
                  <td class="text-left"><?php echo $column_username; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($incentives) { ?>
                  <?php foreach ($incentives as $incentive) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($incentive['incentive_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $incentive['incentive_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $incentive['incentive_id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><?php echo $incentive['date']; ?></td>
                      <td class="text-left"><?php echo $incentive['name']; ?></td>
                      <td class="text-left"><?php echo $incentive['description']; ?></td>
				      <td class="text-right nowrap"><?php echo $incentive['amount']; ?></td>
					  <?php if ($incentive['payment']) { ?>
                        <td class="text-center"><?php echo $incentive['payment']; ?></td>
					  <?php } else { ?>
					    <td class="text-center text-danger"><i class="fa fa-question"></i></td>
					  <?php } ?>
                      <td class="text-left"><?php echo $incentive['username']; ?></td>
                      <td class="text-right">
					    <?php if ($incentive['payment']) { ?>
					      <a href="<?php echo $incentive['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info" target="_blank" rel="noopener noreferrer"><i class="fa fa-eye"></i></a>
						<?php } else { ?>
                          <a href="<?php echo $incentive['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
						<?php } ?>
					  </td>
                    </tr>
                  <?php } ?>
				  <td class="text-right text-bold" colspan="4"><?php echo $text_subtotal; ?></td>
				  <td class="text-right text-bold nowrap"><?php echo $subtotal; ?></td>
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
  <script type="text/javascript"><!--
$(document).keypress(function(e) {
        if(e.which == 13) {
			$("#button-filter").click();
        }
    });

$('#button-filter').on('click', function() {
	url = 'index.php?route=incentive/incentive&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_description = $('input[name=\'filter_description\']').val();
	
	if (filter_description) {
		url += '&filter_description=' + encodeURIComponent(filter_description);
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
