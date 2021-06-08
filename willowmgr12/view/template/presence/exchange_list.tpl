<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-exchange').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-period"><?php echo $entry_period; ?></label>
                <select name="filter_period_id" id="input-period" class="form-control">
			  	  <option value="*"><?php echo $text_all_period ?></option>
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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date"><?php echo $entry_date; ?></label>
		  	    <div class="input-group date">
		  	      <input type="text" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="<?php echo $entry_date; ?>" id="input-date" class="form-control" data-date-format="D MMM YYYY" />
		  	      <span class="input-group-btn">
		  	        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
		  	      </span>
		  	    </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-exchange">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'date_from') { ?>
                    <a href="<?php echo $sort_date_from; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_from; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_from; ?>"><?php echo $column_date_from; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'date_to') { ?>
                    <a href="<?php echo $sort_date_to; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_to; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_to; ?>"><?php echo $column_date_to; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_description; ?></td>
                  <td class="text-left"><?php echo $column_username; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($exchanges) { ?>
                  <?php foreach ($exchanges as $exchange) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($exchange['exchange_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $exchange['exchange_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $exchange['exchange_id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><?php echo $exchange['date_from']; ?></td>
                      <td class="text-left"><?php echo $exchange['date_to']; ?></td>
                      <td class="text-left"><?php echo $exchange['name']; ?></td>
                      <td class="text-left"><?php echo $exchange['description']; ?></td>
                      <td class="text-left"><?php echo $exchange['username']; ?></td>
                      <td class="text-right">
                        <a href="<?php echo $exchange['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
  <script type="text/javascript">
$(document).keypress(function(e) {
        if(e.which == 13) {
			$("#button-filter").click();
        }
    });

$('#button-filter').on('click', function() {
	url = 'index.php?route=presence/exchange&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_date = $('input[name=\'filter_date\']').val();
	
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	
	var filter_period_id = $('select[name=\'filter_period_id\']').val();
	
	if (filter_period_id != '*') {
		url += '&filter_period_id=' + encodeURIComponent(filter_period_id);
	}	
	
	location = url;
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
  <script type="text/javascript">
$('.date').datetimepicker({
	pickTime: false
});
</script>
</div>
<?php echo $footer; ?> 
