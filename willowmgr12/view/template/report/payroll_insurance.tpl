<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($information) { ?>
    <div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $information; ?>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3><h4 class="pull-right"><i class="fa fa-calendar"></i> <?php echo $period_info; ?></h4>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3"></div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-presence-period"></label>
                <select name="presence_period_id" id="input-presence-period" class="form-control">
                  <?php foreach ($presence_periods as $presence_period) { ?>
                    <?php if ($presence_period['presence_period_id'] == $presence_period_id) { ?>
                      <option value="<?php echo $presence_period['presence_period_id']; ?>" selected="selected"><?php echo date('M y',strtotime($presence_period['period'])); ?></option>
                    <?php } else { ?>
                      <option value="<?php echo $presence_period['presence_period_id']; ?>"><?php echo date('M y',strtotime($presence_period['period'])); ?></option>
                    <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
            <div class="col-sm-3"></div>
          </div>
        </div>
		<div id="insurance-report"></div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#insurance-report').on('click', '.pagination a', function(e) {
	e.preventDefault();

	$('#insurance-report').load(this.href);
});

$('#insurance-report').load('index.php?route=report/payroll_insurance/report&token=<?php echo $token; ?>&presence_period_id=<?php echo $presence_period_id; ?>');
//--></script> 
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/payroll_insurance&token=<?php echo $token; ?>';
	
	var presence_period_id = $('select[name=\'presence_period_id\']').val();
	
	if (presence_period_id) {
		url += '&presence_period_id=' + encodeURIComponent(presence_period_id);
	}

	location = url;
});
//--></script> 
  <script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>