<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-free-transfer" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3><h4 class="pull-right"><i class="fa fa-comment-o fa-flip-horizontal"></i> <?php echo $text_modified; ?></h4>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-free-transfer" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?php echo $description; ?>" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
              <?php if ($error_description) { ?>
                <div class="text-danger"><?php echo $error_description; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-fund-account"><?php echo $entry_fund_account; ?></label>
            <div class="col-sm-10">
			  <select name="fund_account_id" id="input-fund-account" class="form-control">
				<option value="0"><?php echo $text_select ?></option>
				<?php foreach ($fund_accounts as $fund_account) { ?>
				  <?php if ($fund_account['fund_account_id'] == $fund_account_id) { ?>
					<option value="<?php echo $fund_account['fund_account_id']; ?>" selected="selected"><?php echo $fund_account['fund_account_text']; ?></option>
				  <?php } else { ?>
					<option value="<?php echo $fund_account['fund_account_id']; ?>"><?php echo $fund_account['fund_account_text']; ?></option>
				  <?php } ?>
				<?php } ?>
			  </select>
              <?php if ($error_fund_account) { ?>
                <div class="text-danger"><?php echo $error_fund_account; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-date-process"><?php echo $entry_date_process; ?></label>
            <div class="col-sm-10">
			  <div class="input-group date">
                <input type="text" name="date_process" value="<?php echo $date_process; ?>" placeholder="<?php echo $entry_date_process; ?>" id="input-date-process" class="form-control" data-date-format="D MMM YYYY" />
		  	    <span class="input-group-btn">
		  	      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
		  	    </span>
              </div>
              <?php if ($error_date_process) { ?>
                <div class="text-danger"><?php echo $error_date_process; ?></div>
              <?php } ?>
            </div>
          </div>
          <table id="free-transfer-customer" class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $entry_customer; ?></td>
                <td class="text-left"><?php echo $entry_note; ?></td>
                <td class="text-right"><?php echo $entry_amount; ?></td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php $free_transfer_customer_row = 0; ?>
              <?php foreach ($free_transfer_customers as $free_transfer_customer) { ?>
              <tr id="free-transfer-customer-row<?php echo $free_transfer_customer_row; ?>">
                <td class="text-left"><select name="free_transfer_customer[<?php echo $free_transfer_customer_row; ?>][customer_id]" class="form-control">
                    <?php foreach ($customers as $customer) { ?>
                    <?php  if ($customer['customer_id'] == $free_transfer_customer['customer_id']) { ?>
                    <option value="<?php echo $customer['customer_id']; ?>" selected="selected"><?php echo $customer['customer_text']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['customer_text']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="text-left"><input type="text" name="free_transfer_customer[<?php echo $free_transfer_customer_row; ?>][note]" class="form-control" value="<?php echo $free_transfer_customer['note']; ?>" placeholder="<?php echo $entry_note; ?>" /></td>
                <td class="text-right"><input type="text" name="free_transfer_customer[<?php echo $free_transfer_customer_row; ?>][amount]" class="form-control" value="<?php echo $free_transfer_customer['amount']; ?>" placeholder="<?php echo $entry_amount; ?>" /></td>
                <td class="text-right"><button type="button" onclick="$('#free-transfer-customer-row<?php echo $free_transfer_customer_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $free_transfer_customer_row++; ?>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3"></td>
                <td class="text-right"><button type="button" onclick="addFreeTransfer();" data-toggle="tooltip" title="<?php echo $button_free_transfer_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var free_transfer_customer_row = <?php echo $free_transfer_customer_row; ?>;

function addFreeTransfer() {
	html  = '<tr id="free-transfer-customer-row' + free_transfer_customer_row + '">';
	html += '  <td class="text-left"><select name="free_transfer_customer[' + free_transfer_customer_row + '][customer_id]" class="form-control">';
	<?php foreach ($customers as $customer) { ?>
	html += '    <option value="<?php echo $customer['customer_id']; ?>"><?php echo addslashes($customer['customer_text']); ?></option>';
	<?php } ?>   
	html += '</select></td>';
	html += '  <td class="text-left"><input type="text" name="free_transfer_customer[' + free_transfer_customer_row + '][note]" class="form-control" value="" placeholder="<?php echo $entry_note; ?>" /></td>';
	html += '  <td class="text-right"><input type="text" name="free_transfer_customer[' + free_transfer_customer_row + '][amount]" class="form-control" value="" placeholder="<?php echo $entry_amount; ?>" /></td>';
	html += '  <td class="text-right"><button type="button" onclick="$(\'#free-transfer-customer-row' + free_transfer_customer_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#free-transfer-customer tbody').append(html);
	
	$('free_transfer_customer[' + free_transfer_customer_row + '][customer_id]').trigger();
			
	free_transfer_customer_row++;
}
//--></script>
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>
