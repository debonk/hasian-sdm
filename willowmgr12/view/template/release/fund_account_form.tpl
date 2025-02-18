<?= $header; ?><?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-fund-account" data-toggle="tooltip" title="<?= $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?= $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_form; ?></h3><h4 class="pull-right"><i class="fa fa-comment-o fa-flip-horizontal"></i> <?= $text_modified; ?></h4>
      </div>
      <div class="panel-body">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-fund-account" class="form-horizontal">
			<div class="form-group required">
				<label class="col-sm-3 control-label" for="input-payroll-method">
					<?= $entry_payroll_method; ?>
				</label>
				<div class="col-sm-9">
					<select name="payroll_method_id" id="input-payroll-method"
						class="form-control">
						<option value="">
							<?= $text_select; ?>
						</option>
						<?php foreach ($payroll_methods as $payroll_method) { ?>
						<option value="<?= $payroll_method['payroll_method_id']; ?>"
							<?= $payroll_method['payroll_method_id'] == $payroll_method_id ? 'selected' : ''; ?>>
							<?= $payroll_method['name']; ?>
						</option>
						<?php } ?>
					</select>
					<?php if ($error_payroll_method) { ?>
						<div class="text-danger">
							<?= $error_payroll_method; ?>
						</div>
					<?php } ?>
					</div>
			</div>
		    <div class="form-group required">
			  <label class="col-sm-3 control-label" for="input-acc-no"><?= $entry_acc_no; ?></label>
			  <div class="col-sm-9">
			    <input type="text" name="acc_no" value="<?= $acc_no; ?>" placeholder="<?= $entry_acc_no; ?>" id="input-acc-no" class="form-control" />
			    <?php if ($error_acc_no) { ?>
			      <div class="text-danger"><?= $error_acc_no; ?></div>
			    <?php  } ?>
			  </div>
		    </div>
		    <div class="form-group required">
			  <label class="col-sm-3 control-label" for="input-acc-name"><?= $entry_acc_name; ?></label>
			  <div class="col-sm-9">
			    <input type="text" name="acc_name" value="<?= $acc_name; ?>" placeholder="<?= $entry_acc_name; ?>" id="input-acc-name" class="form-control" />
			    <?php if ($error_acc_name) { ?>
			      <div class="text-danger"><?= $error_acc_name; ?></div>
			    <?php  } ?>
			  </div>
		    </div>
		    <div class="form-group">
			  <label class="col-sm-3 control-label" for="input-email"><?= $entry_email; ?></label>
			  <div class="col-sm-9">
			    <input type="text" name="email" value="<?= $email; ?>" placeholder="<?= $entry_email; ?>" id="input-email" class="form-control" />
			    <?php if ($error_email) { ?>
			      <div class="text-danger"><?= $error_email; ?></div>
			    <?php  } ?>
			  </div>
		    </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?>