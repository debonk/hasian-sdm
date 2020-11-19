<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <div class="row">
		<div class="col-md-8" id="customer-info"></div>
		<div class="col-md-4" id="history"></div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_info; ?></h3>
      </div>
      <div class="panel-body form-horizontal">
        <div id="transaction"></div>
        <br />
        <?php if ($customer_id) { ?>
		  <div class="form-group">
		    <label class="col-sm-2 control-label" for="input-loan"><?php echo $entry_loan; ?></label>
			<div class="col-sm-10">
		      <select name="loan_id" id="input-loan" class="form-control">
		  	    <option value="0"><?php echo $text_select ?></option>
		  	    <?php foreach ($loans as $loan) { ?>
				  <?php if ($loan['balance']) { ?>
				    <?php if ($loan['loan_id'] == $loan_id) { ?>
					  <option value="<?php echo $loan['loan_id']; ?>" selected="selected"><?php echo '#' . $loan['loan_id'] . ': ' . $loan['deskripsi']; ?></option>
				    <?php } else { ?>
					  <option value="<?php echo $loan['loan_id']; ?>"><?php echo '#' . $loan['loan_id'] . ': ' . $loan['deskripsi']; ?></option>
				    <?php } ?>
				  <?php } ?>
		  	    <?php } ?>
		      </select>
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-transaction-description"><?php echo $entry_description; ?></label>
			<div class="col-sm-10">
			  <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-transaction-description" class="form-control" />
			</div>
		  </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-amount"><span data-toggle="tooltip" title="<?php echo $help_amount; ?>"><?php echo $entry_amount; ?></span></label>
			<div class="col-sm-10">
			  <input type="text" name="amount" value="" placeholder="<?php echo $help_amount; ?>" id="input-amount" class="form-control" />
			</div>
		  </div>
		  <div class="text-right">
			<button type="button" id="button-transaction" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_transaction_add; ?></button>
		  </div>
        <?php } ?>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#transaction').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#transaction').load(this.href);
});

$('#transaction').load('index.php?route=loan/loan/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#history').load('index.php?route=loan/loan/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-transaction').on('click', function(e) {
  e.preventDefault();

  $.ajax({
		url: 'index.php?route=loan/loan/addtransaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'description=' + encodeURIComponent($('input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('input[name=\'amount\']').val()) + '&loan_id=' + encodeURIComponent($('select[name=\'loan_id\']').val()),
		beforeSend: function() {
			$('#button-transaction').button('loading');
		},
		complete: function() {
			$('#button-transaction').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#transaction').load('index.php?route=loan/loan/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

				$('#history').load('index.php?route=loan/loan/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

				$('select[name=\'loan_id\']').val(0);
				$('input[name=\'amount\']').val('');
				$('input[name=\'description\']').val('');
			}
		}
	});
});
//--></script>
</div>
<?php echo $footer; ?>