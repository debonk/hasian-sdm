<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-payroll-basic" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-payroll-basic" class="form-horizontal">
		    <div id="customer-info"></div>
			<br />
		    <div id="history"></div>
		    <br />
		    <div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_gaji_dasar; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="gaji_dasar" value="<?php echo $gaji_dasar; ?>" placeholder="<?php echo $entry_gaji_dasar; ?>" class="form-control" readonly="readonly"></input>
			  </div>
		    </div>
		    <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-gaji-pokok"><?php echo $entry_gaji_pokok; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="gaji_pokok" value="<?php echo $gaji_pokok; ?>" placeholder="<?php echo $entry_gaji_pokok; ?>" id="input-gaji-pokok" class="form-control" ></input>
			    <?php if ($error_gaji_pokok) { ?>
			    <div class="text-danger"><?php echo $error_gaji_pokok; ?></div>
			    <?php } ?>
			  </div>
		    </div>
		    <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-tunj-jabatan"><?php echo $entry_tunj_jabatan; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="tunj_jabatan" value="<?php echo $tunj_jabatan; ?>" placeholder="<?php echo $entry_tunj_jabatan; ?>" id="input-tunj-jabatan" class="form-control" />
			  </div>
		    </div>
		    <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-tunj-hadir"><?php echo $entry_tunj_hadir; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="tunj_hadir" value="<?php echo $tunj_hadir; ?>" placeholder="<?php echo $entry_tunj_hadir; ?>" id="input-tunj-hadir" class="form-control" />
			  </div>
		    </div>
		    <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-uang-makan"><?php echo $entry_uang_makan; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="uang_makan" value="<?php echo $uang_makan; ?>" placeholder="<?php echo $entry_uang_makan; ?>" id="input-uang-makan" class="form-control" />
			  </div>
		    </div>
		    <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-tunj-pph"><?php echo $entry_tunj_pph; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="tunj_pph" value="<?php echo $tunj_pph; ?>" placeholder="<?php echo $entry_tunj_pph; ?>" id="input-tunj-pph" class="form-control" />
			  </div>
		    </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#history').on('click', '.pagination a', function(e) {
	e.preventDefault();

	$('#history').load(this.href);
});

$('#history').load('index.php?route=payroll/payroll_basic/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('input[id^=\'input\']').focus(function() {
	var node = this;
	
	if ($(node).val()) {
		var value = parseInt($(node).val().replace(/,/g, ""));
	}
	
	$(node).val(value);
});

$('input[id^=\'input\']').blur(function() {
	var node = this;

	if ($(node).val()) {
		var value = parseInt($(node).val()).toLocaleString('en-GB');
	}
	
	$(node).val(value);
});

$('input[id^=\'input\']').keyup(function() {
	var node = this;
	var gaji_pokok = parseInt($('input[name=\'gaji_pokok\']').val().replace(/(?!-)[^0-9.]/g, ""));
	var tunj_jabatan = parseInt($('input[name=\'tunj_jabatan\']').val().replace(/(?!-)[^0-9.]/g, ""));
	var tunj_hadir = parseInt($('input[name=\'tunj_hadir\']').val().replace(/(?!-)[^0-9.]/g, ""));
	var tunj_pph = parseInt($('input[name=\'tunj_pph\']').val().replace(/(?!-)[^0-9.]/g, ""));
	var total_uang_makan = parseInt($('input[name=\'uang_makan\']').val().replace(/(?!-)[^0-9.]/g, "")) * 25;
	var gaji_dasar = gaji_pokok + tunj_jabatan + tunj_hadir + tunj_pph + total_uang_makan;

	$('input[name=\'gaji_dasar\']').val(gaji_dasar.toLocaleString('en-GB'));
});
 //--></script>
</div>
<?php echo $footer; ?>