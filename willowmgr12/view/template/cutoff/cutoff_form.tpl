<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cutoff" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cutoff" class="form-horizontal">
		  <div class="well">
		    <div class="row">
		  	  <div class="col-sm-3"></div>
		  	  <div class="col-sm-6">
		  	    <div class="form-group">
		  		  <label class="control-label" for="input-customer"><?php echo $entry_name; ?></label>
		  		  <select name="customer_id" id="input-customer" class="form-control" <?php echo $disabled; ?>>
		  		    <option value="0"><?php echo $text_select_customer ?></option>
		  		    <?php foreach ($customers as $customer) { ?>
					  <?php if ($customer['customer_id'] == $customer_id) { ?>
						<option value="<?php echo $customer['customer_id']; ?>" selected="selected"><?php echo $customer['name_department']; ?></option>
					  <?php } else { ?>
						<option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['name_department']; ?></option>
		  		      <?php } ?>
		  		    <?php } ?>
		  		  </select>
		  	    </div>
		  	  </div>
		    </div>
		  </div>
		  <div class="row">
		  	<div id="customer-info"></div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-date"><?php echo $entry_date; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group date">
		  	    <input type="text" name="date" value="<?php echo $date; ?>" placeholder="<?php echo $entry_date; ?>" id="input-date" class="form-control" data-date-format="D MMM YYYY" />
		  	    <span class="input-group-btn">
		  	      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
		  	    </span>
		  	  </div>
		  	  <?php if ($error_date) { ?>
		  	  <div class="text-danger"><?php echo $error_date; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-inv-no"><?php echo $entry_inv_no; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="inv_no" value="<?php echo $inv_no; ?>" placeholder="<?php echo $entry_inv_no; ?>" id="input-inv-no" class="form-control"></input>
		  	  <?php if ($error_inv_no) { ?>
		  	  <div class="text-danger"><?php echo $error_inv_no; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-principle"><?php echo $entry_principle; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="principle" value="<?php echo $principle; ?>" placeholder="<?php echo $entry_principle; ?>" id="input-principle" class="form-control" />
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-business-name"><?php echo $entry_business_name; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="business_name" value="<?php echo $business_name; ?>" placeholder="<?php echo $entry_business_name; ?>" id="input-business-name" class="form-control" />
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-amount"><?php echo $entry_amount; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="amount" value="<?php echo $amount; ?>" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
		  	  <?php if ($error_amount) { ?>
		  	  <div class="text-danger"><?php echo $error_amount; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
var select_customer = $('select[name=\'customer_id\']').val();

$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=' + encodeURIComponent(select_customer));

$('#input-customer').change(function() {
	var select_customer = $('select[name=\'customer_id\']').val();

	$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=' + encodeURIComponent(select_customer));
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>
