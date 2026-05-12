<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary"><i
            class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
          onclick="confirm('<?= $text_confirm; ?>') ? $('#form-free-transfer').submit() : false;"><i
            class="fa fa-trash-o"></i></button>
      </div>
      <h1>
        <?= $heading_title; ?>
      </h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?= $breadcrumb['href']; ?>">
            <?= $breadcrumb['text']; ?>
          </a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
      <?= $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i>
      <?= $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>
          <?= $text_list; ?>
        </h3>
      </div>
      <div class="panel-body">
        <form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-free-transfer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td>
                    <?= $column_description; ?>
                  </td>
                  <td>
                    <?php if ($sort == 'ft.date_process') { ?>
                    <a href="<?= $sort_date_process; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_date_process; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_date_process; ?>">
                      <?= $column_date_process; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td>
                    <?= $column_fund_account; ?>
                  </td>
                  <td class="text-right">
                    <?= $column_count; ?>
                  </td>
                  <td class="text-right">
                    <?= $column_total; ?>
                  </td>
                  <td>
                    <?= $column_status; ?>
                  </td>
                  <td>
                    <?php if ($sort == 'ft.date_modified') { ?>
                    <a href="<?= $sort_date_modified; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_date_modified; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_date_modified; ?>">
                      <?= $column_date_modified; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td>
                    <?= $column_username; ?>
                  </td>
                  <td class="text-right">
                    <?= $column_action; ?>
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($free_transfers) { ?>
                <?php foreach ($free_transfers as $free_transfer) { ?>
                <tr>
                  <td class="text-center">
                    <?php if (in_array($free_transfer['free_transfer_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $free_transfer['free_transfer_id']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $free_transfer['free_transfer_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td>
                    <?= $free_transfer['description']; ?>
                  </td>
                  <td>
                    <?= $free_transfer['date_process']; ?>
                  </td>
                  <td>
                    <?= $free_transfer['fund_account']; ?>
                  </td>
                  <td class="text-right">
                    <?= $free_transfer['count']; ?>
                  </td>
                  <td class="text-right">
                    <?= $free_transfer['total']; ?>
                  </td>
                  <td><cite>
                      <?= $free_transfer['status']; ?>
                    </cite></td>
                  <td>
                    <?= $free_transfer['date_modified']; ?>
                  </td>
                  <td>
                    <?= $free_transfer['username']; ?>
                  </td>
                  <td class="text-right nowrap">
                    <a href="<?= $free_transfer['export']; ?>" data-toggle="tooltip"
                      title="<?= $free_transfer['button_export_csv']; ?>" class="btn btn-info"><i
                        class="fa fa-upload"></i></a>
                    <a href="<?= $free_transfer['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
                      class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="10">
                    <?= $text_no_results; ?>
                  </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left">
            <?= $pagination; ?>
          </div>
          <div class="col-sm-6 text-right">
            <?= $results; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?>