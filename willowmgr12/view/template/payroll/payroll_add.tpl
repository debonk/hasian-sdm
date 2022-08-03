<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?= $back; ?>" data-toggle="tooltip" title="<?= $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?= $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
		<div class="col-sm-3"></div>
		<div class="col-sm-6" id="period-info"></div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?= $text_add; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-name"><?= $entry_name; ?></label>
                <select name="select_customer" id="input-name" class="form-control">
                  <?php foreach ($customers as $customer) { ?>
                    <option value="<?= $customer['customer_id']; ?>"><?= $customer['name_nip']; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-select" class="btn btn-primary pull-right"><i class="fa fa-check"></i> <?= $button_select; ?></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$(document).keypress(function(e) {
        if(e.which == 13) {
			$("#button-select").click();
        }
    });

$('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>&no_shortcut=1');

$('#button-select').on('click', function() {
	url = 'index.php?route=payroll/payroll/edit&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id . $url; ?>';
	
	var select_name = $('select[name=\'select_customer\']').val();
	
	if (select_name) {
		url += '&customer_id=' + encodeURIComponent(select_name);
	}
	
	location = url;
});
</script> 
</div>
<?= $footer; ?> 
