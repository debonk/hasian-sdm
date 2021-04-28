<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list-alt"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
	  <div id="customer-info"></div>
        <form action="" method="" enctype="multipart/form-data" id="form-customer" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <?php if ($customer_id) { ?>
            <li><a href="#tab-document" data-toggle="tab"><?php echo $tab_document; ?></a></li>
            <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
            <li><a href="#tab-payroll-basic" data-toggle="tab"><?php echo $tab_payroll_basic; ?></a></li>
            <li><a href="#tab-vacation" data-toggle="tab"><?php echo $tab_vacation; ?></a></li>
            <li><a href="#tab-loan" data-toggle="tab"><?php echo $tab_loan; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="row">
                <div class="col-sm-2">
                  <ul class="nav nav-pills nav-stacked" id="address">
                    <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <li><a href="#tab-address<?php echo $address_row; ?>" data-toggle="tab"><?php echo $tab_address . ' ' . $address_row; ?></a></li>
                    <?php $address_row++; ?>
                    <?php } ?>
                  </ul>
                </div>
                <div class="col-sm-10">
                  <div class="tab-content">
                    <div class="tab-pane active" id="tab-customer">
                      <?php foreach ($generals as $general) { ?>
                        <div class="form-group">
                          <label class="col-sm-3 control-label"><?php echo $general['label']; ?></label>
                          <div class="col-sm-9">
                            <input type="text" name="" value="<?php echo $general['value']; ?>" class="form-control" readonly="readonly" />
                          </div>
                        </div>
                      <?php } ?>
                    </div>
                    <?php $address_row = 1; ?>
                    <?php foreach ($addresses as $address) { ?>
                    <div class="tab-pane" id="tab-address<?php echo $address_row; ?>">
                      <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
                    <?php foreach ($address_items as $item) { ?>
                      <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo $label[$item]; ?></label>
                        <div class="col-sm-9">
                          <input type="text" name="" value="<?php echo $address[$item]; ?>" class="form-control" readonly="readonly" />
                        </div>
                      </div>
                    <?php } ?>
                      <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo $entry_id_card_address; ?></label>
                        <div class="col-sm-9">
                          <label class="radio">
                            <?php if (($address['address_id'] == $id_card_address_id) || !$addresses) { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][id_card_address]" value="<?php echo $address_row; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][id_card_address]" value="<?php echo $address_row; ?>" disabled />
                            <?php } ?>
                          </label>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo $entry_default; ?></label>
                        <div class="col-sm-9">
                          <label class="radio">
                            <?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" />
                            <?php } else { ?>
                            <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" disabled />
                            <?php } ?>
                          </label>
                        </div>
                      </div>
                    </div>
                    <?php $address_row++; ?>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <?php if ($customer_id) { ?>
              <div class="tab-pane" id="tab-document">
			    <div class="col-md-3"></div>
			    <div class="col-md-6">
			      <table class="table">
			        <?php if ($documents) { ?>
			        <?php foreach ($documents as $document) { ?>
			        <tr>
				      <td>
								<?php if ($document['href']) { ?>
									<a href="<?php echo $document['href']; ?>" target="_blank">
										<?php echo $document['mask']; ?>
									</a>
									<?php } else { ?>
									<?php echo $document['mask']; ?>
									<cite class="text-danger">
										<?php echo $text_missing; ?>
									</cite>
									<?php } ?>



				      </td>
			        </tr>
			        <?php } ?>
			        <?php } else { ?>
			        <tr>
			        <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
			        </tr>
			        <?php } ?>
			      </table>
			    </div>
              </div>
              <div class="tab-pane" id="tab-history">
                <div id="history-info"></div>
              </div>
              <div class="tab-pane" id="tab-payroll-basic">
                <div id="payroll-basic-info"></div>
              </div>
              <div class="tab-pane" id="tab-vacation">
			    <div class="well">
			      <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                      <div class="form-group">
                        <label class="control-label" for="input-year"><?php echo $entry_year; ?></label>
                        <div class="input-group year">
                          <input type="text" name="year" value="<?php echo $year; ?>" placeholder="<?php echo $entry_year; ?>" data-date-format="YYYY" id="input-year" class="form-control" />
                          <span class="input-group-btn">
                            <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                          </span>
						</div>
                      </div>
                      <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
			        </div>
			      </div>
			    </div>
                <div id="vacation-info"></div>
              </div>
              <div class="tab-pane" id="tab-loan">
                <div id="loan-info"></div>
                <div id="transaction-info"></div>
              </div>
            <?php } ?>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <script type="text/javascript">
$('#history-info').load('index.php?route=report/customer_history/report&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#history-info').on('click', '.pagination a', function(e) {
	e.preventDefault();

	$('#history-info').load(this.href);
});

$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#vacation-info').load('index.php?route=common/vacation_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-filter').on('click', function(e) {
	var year = $('input[name=\'year\']').val();

	$('#vacation-info').load('index.php?route=common/vacation_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&year=' + year);
});

$('#payroll-basic-info').load('index.php?route=payroll/payroll_basic/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#payroll-basic-info').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#payroll-basic-info').load(this.href);
});

$('#loan-info').load('index.php?route=loan/loan/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#transaction-info').delegate('.pagination a', 'click', function(e) {
	e.preventDefault();

	$('#transaction-info').load(this.href);
});

$('#transaction-info').load('index.php?route=loan/loan/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('.year').datetimepicker({
	minViewMode: 'years',
	pickTime: false
});
</script>
<?php echo $footer; ?>
