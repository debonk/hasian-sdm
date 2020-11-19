<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-save" form="form-release" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="row">
		<div class="col-sm-3"></div>
		<div class="col-sm-6" id="period-info"></div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form method="post" action="<?php echo $edit; ?>" enctype="multipart/form-data" id="form-release" class="form-horizontal">
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
			<label class="col-sm-2 control-label" for="input-date-release"><?php echo $entry_date_release; ?></label>
			<div class="col-sm-10">
			  <div class="input-group date">
				<input type="text" name="date_release" value="<?php echo $date_release; ?>" placeholder="<?php echo $entry_date_release; ?>" data-date-format="D MMM YYYY" id="input-date-release" class="form-control" />
				<span class="input-group-btn">
				<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
				</span>
			  </div>
			  <?php if ($error_date_release) { ?>
				<div class="text-danger"><?php echo $error_date_release; ?></div>
			  <?php } ?>
			</div>
		  </div>
		</form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#period-info').load('index.php?route=common/period_info&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 
