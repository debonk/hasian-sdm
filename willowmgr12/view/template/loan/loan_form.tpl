<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-loan" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-loan" class="form-horizontal">
		  <div class="well">
		    <div class="row">
		  	  <div class="col-sm-3"></div>
		  	  <div class="col-sm-6">
		  	    <div class="form-group">
		  		  <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
		  		  <select name="customer_id" id="input-customer" class="form-control" <?php echo $disabled; ?>>
		  		    <option value="0"><?php echo $text_select_customer ?></option>
		  		    <?php foreach ($customers as $customer) { ?>
					  <?php if ($customer['customer_id'] == $customer_id) { ?>
						<option value="<?php echo $customer['customer_id']; ?>" selected="selected"><?php echo $customer['customer_text']; ?></option>
					  <?php } else { ?>
						<option value="<?php echo $customer['customer_id']; ?>"><?php echo $customer['customer_text']; ?></option>
		  		      <?php } ?>
		  		    <?php } ?>
		  		  </select>
		  	    </div>
		  	  </div>
		    </div>
		  </div>
		  <div class="row">
		  	<div class="col-md-8" id="customer-info"></div>
		  	<div class="col-md-4" id="loan-info"></div>
		    <div id="loan-history"></div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="" value="<?php echo $date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" id="input-date-added" class="form-control" disabled />
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-pinjaman-pokok"><?php echo $entry_pinjaman_pokok; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="pinjaman_pokok" value="<?php echo $pinjaman_pokok; ?>" placeholder="<?php echo $entry_pinjaman_pokok; ?>" id="input-pinjaman-pokok" class="form-control" <?php echo $disabled; ?>></input>
		  	  <?php if ($error_pinjaman_pokok) { ?>
		  	  <div class="text-danger"><?php echo $error_pinjaman_pokok; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-deskripsi"><?php echo $entry_deskripsi; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="deskripsi" value="<?php echo $deskripsi; ?>" placeholder="<?php echo $entry_deskripsi; ?>" id="input-deskripsi" class="form-control" <?php echo $disabled; ?> />
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-cicilan"><?php echo $entry_cicilan; ?></label>
		    <div class="col-sm-10">
		  	  <input type="text" name="cicilan" value="<?php echo $cicilan; ?>" placeholder="<?php echo $entry_cicilan; ?>" id="input-cicilan" class="form-control" />
		  	  <?php if ($error_cicilan) { ?>
		  	  <div class="text-danger"><?php echo $error_cicilan; ?></div>
		  	  <?php } ?>
		    </div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
		    <div class="col-sm-10">
		  	  <div class="input-group date">
		  	    <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" id="input-date-start" class="form-control" data-date-format="MMM YYYY" <?php echo $disabled; ?> />
		  	    <span class="input-group-btn">
		  	    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
		  	    </span>
		  	  </div>
		  	  <?php if ($error_date_start) { ?>
		  	    <div class="text-danger"><?php echo $error_date_start; ?></div>
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
$('#loan-info').load('index.php?route=loan/loan/history&token=<?php echo $token; ?>&customer_id=' + encodeURIComponent(select_customer));

$('#input-customer').change(function() {
	var select_customer = $('select[name=\'customer_id\']').val();

	$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=' + encodeURIComponent(select_customer));
	$('#loan-info').load('index.php?route=loan/loan/history&token=<?php echo $token; ?>&customer_id=' + encodeURIComponent(select_customer));
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	minViewMode: 'months',
	pickTime: false
});
//--></script>
</div>
<?php echo $footer; ?>
