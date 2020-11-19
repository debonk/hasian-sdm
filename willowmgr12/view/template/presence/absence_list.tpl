<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-absence').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-presence-status"><?php echo $entry_presence_status; ?></label>
			    <select name="filter_presence_status_id" id="input-presence-status" class="form-control">
			  	<option value="*"><?php echo $text_all ?></option>
			  	<?php foreach ($presence_statuses as $presence_status) { ?>
				  <?php if (in_array($presence_status['presence_status_id'], $config_presence_status)) { ?>
			  	  <?php if ($presence_status['presence_status_id'] == $filter_presence_status_id) { ?>
			  		<option value="<?php echo $presence_status['presence_status_id']; ?>" selected="selected"><?php echo $presence_status['name']; ?></option>
			  	  <?php } else { ?>
			  		<option value="<?php echo $presence_status['presence_status_id']; ?>"><?php echo $presence_status['name']; ?></option>
			  	  <?php } ?>
			  	<?php } ?>
			  	<?php } ?>
			    </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-approved"><?php echo $entry_note; ?></label>
			    <select name="filter_note" id="input-note" class="form-control">
			  	<option value="*"><?php echo $text_all ?></option>
                  <?php if ($filter_note) { ?>
                    <option value="1" selected="selected"><?php echo $text_with_note; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_with_note; ?></option>
                  <?php } ?>
                  <?php if (!$filter_note && !is_null($filter_note)) { ?>
                    <option value="0" selected="selected"><?php echo $text_without_note; ?></option>
                  <?php } else { ?>
                    <option value="0"><?php echo $text_without_note; ?></option>
                  <?php } ?>
			    </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-approved"><?php echo $entry_approved; ?></label>
			    <select name="filter_approved" id="input-approved" class="form-control">
			  	<option value="*"><?php echo $text_all ?></option>
                  <?php if ($filter_approved) { ?>
                    <option value="1" selected="selected"><?php echo $text_approved; ?></option>
                  <?php } else { ?>
                    <option value="1"><?php echo $text_approved; ?></option>
                  <?php } ?>
                  <?php if (!$filter_approved && !is_null($filter_approved)) { ?>
                    <option value="0" selected="selected"><?php echo $text_not_approved; ?></option>
                  <?php } else { ?>
                    <option value="0"><?php echo $text_not_approved; ?></option>
                  <?php } ?>
			    </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-absence">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'date') { ?>
                    <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'presence_status') { ?>
                    <a href="<?php echo $sort_presence_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_presence_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_presence_status; ?>"><?php echo $column_presence_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_description; ?></td>
                  <td class="text-left"><?php echo $column_note; ?></td>
                  <td class="text-center"><?php echo $column_approved; ?></td>
                  <td class="text-left"><?php echo $column_username; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($absences) { ?>
                  <?php foreach ($absences as $absence) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($absence['absence_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $absence['absence_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $absence['absence_id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><?php echo $absence['date']; ?></td>
                      <td class="text-left"><?php echo $absence['name']; ?></td>
                      <td class="text-left"><?php echo $absence['presence_status']; ?></td>
                      <td class="text-left"><?php echo $absence['description']; ?></td>
                      <td class="text-left"><?php echo $absence['note']; ?></td>
                      <td class="text-center">
					    <?php if (!$absence['approved']) { ?>
						  <button type="button" id="button-approve<?php echo $absence['absence_id']; ?>" value="<?php echo $absence['absence_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-warning btn-xs"><i class="fa fa-check"></i> <?php echo $button_approve; ?></button>
						<?php } ?>
					  </td>
                      <td class="text-left"><?php echo $absence['username']; ?></td>
                      <td class="text-right">
                        <a href="<?php echo $absence['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
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
	url = 'index.php?route=presence/absence&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_presence_status_id = $('select[name=\'filter_presence_status_id\']').val();
	
	if (filter_presence_status_id != '*') {
		url += '&filter_presence_status_id=' + encodeURIComponent(filter_presence_status_id);
	}	
	
	var filter_period_id = $('select[name=\'filter_period_id\']').val();
	
	if (filter_period_id != '*') {
		url += '&filter_period_id=' + encodeURIComponent(filter_period_id);
	}	
	
	var filter_note = $('select[name=\'filter_note\']').val();
	
	if (filter_note != '*') {
		url += '&filter_note=' + encodeURIComponent(filter_note);
	}	
	
	var filter_approved = $('select[name=\'filter_approved\']').val();
	
	if (filter_approved != '*') {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}	
	
	var filter_date = $('input[name=\'filter_date\']').val();
	
	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
	}
	
	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('button[id^=\'button-approve\']').on('click', function(e) {
	// if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;
		
		$.ajax({
			url: 'index.php?route=presence/absence/approval&token=<?php echo $token; ?>&absence_id=' + $(node).val(),
			dataType: 'json',
			crossDomain: false,
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				// $('.alert').remove();

				if (json['error']) {
					alert(json['error']);
				}

				if (json['success']) {
					// alert(json['success']);

				    $(node).replaceWith('<i class="fa fa-check"></i>');
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	// }
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
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?> 
