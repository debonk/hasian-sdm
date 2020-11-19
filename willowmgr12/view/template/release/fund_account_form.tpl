<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-fund-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-fund-account" class="form-horizontal">
		    <div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-bank-name"><?php echo $entry_bank_name; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bank_name; ?>" id="input-bank-name" class="form-control" ></input>
			    <?php if ($error_bank_name) { ?>
			      <div class="text-danger"><?php echo $error_bank_name; ?></div>
			    <?php  } ?>
			  </div>
		    </div>
		    <div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-acc-no"><?php echo $entry_acc_no; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="acc_no" value="<?php echo $acc_no; ?>" placeholder="<?php echo $entry_acc_no; ?>" id="input-acc-no" class="form-control" />
			    <?php if ($error_acc_no) { ?>
			      <div class="text-danger"><?php echo $error_acc_no; ?></div>
			    <?php  } ?>
			  </div>
		    </div>
		    <div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-acc-name"><?php echo $entry_acc_name; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="acc_name" value="<?php echo $acc_name; ?>" placeholder="<?php echo $entry_acc_name; ?>" id="input-acc-name" class="form-control" />
			    <?php if ($error_acc_name) { ?>
			      <div class="text-danger"><?php echo $error_acc_name; ?></div>
			    <?php  } ?>
			  </div>
		    </div>
		    <div class="form-group">
			  <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
			  <div class="col-sm-10">
			    <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
			    <?php if ($error_email) { ?>
			      <div class="text-danger"><?php echo $error_email; ?></div>
			    <?php  } ?>
			  </div>
		    </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>