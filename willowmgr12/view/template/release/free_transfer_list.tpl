<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
	    <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-free-transfer').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-free-transfer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php echo $column_description; ?></td>
                  <td class="text-left"><?php if ($sort == 'ft.date_process') { ?>
                    <a href="<?php echo $sort_date_process; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_process; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_process; ?>"><?php echo $column_date_process; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_fund_account; ?></td>
                  <td class="text-right"><?php echo $column_count; ?></td>
                  <td class="text-right"><?php echo $column_total; ?></td>
                  <td class="text-left"><?php if ($sort == 'ft.date_modified') { ?>
                    <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_username; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($free_transfers) { ?>
                <?php foreach ($free_transfers as $free_transfer) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($free_transfer['free_transfer_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $free_transfer['free_transfer_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $free_transfer['free_transfer_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $free_transfer['description']; ?></td>
                  <td class="text-left"><?php echo $free_transfer['date_process']; ?></td>
                  <td class="text-left"><?php echo $free_transfer['fund_account']; ?></td>
                  <td class="text-right"><?php echo $free_transfer['count']; ?></td>
                  <td class="text-right"><?php echo $free_transfer['total']; ?></td>
                  <td class="text-left"><?php echo $free_transfer['date_modified']; ?></td>
                  <td class="text-left"><?php echo $free_transfer['username']; ?></td>
                  <td class="text-right nowrap">
				    <a href="<?php echo $free_transfer['export']; ?>" data-toggle="tooltip" title="<?php echo $button_export_csv; ?>" class="btn btn-info"><i class="fa fa-upload"></i></a>
				    <a href="<?php echo $free_transfer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
				  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>