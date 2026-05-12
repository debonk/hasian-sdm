<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary"><i
            class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
          onclick="confirm('<?= $text_confirm; ?>') ? $('#form-allowance').submit() : false;"><i
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
        <form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-allowance">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td>
                    <?php if ($sort == 'a.allowance_period') { ?>
                    <a href="<?= $sort_allowance_period; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_allowance_period; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_allowance_period; ?>">
                      <?= $column_allowance_period; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td>
                    <?php if ($sort == 'a.date_process') { ?>
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
                    <?php if ($sort == 'a.date_modified') { ?>
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
                <?php if ($allowances) { ?>
                <?php foreach ($allowances as $allowance) { ?>
                <tr>
                  <td class="text-center">
                    <?php if (in_array($allowance['allowance_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $allowance['allowance_id']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $allowance['allowance_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td>
                    <?= $allowance['allowance_period']; ?>
                  </td>
                  <td>
                    <?= $allowance['date_process']; ?>
                  </td>
                  <td>
                    <?= $allowance['fund_account']; ?>
                  </td>
                  <td class="text-right">
                    <?= $allowance['count']; ?>
                  </td>
                  <td class="text-right">
                    <?= $allowance['total']; ?>
                  </td>
                  <td><cite>
                      <?= $allowance['status']; ?>
                    </cite></td>
                  <td>
                    <?= $allowance['date_modified']; ?>
                  </td>
                  <td>
                    <?= $allowance['username']; ?>
                  </td>
                  <td class="text-right">
                    <a href="<?= $allowance['export']; ?>" data-toggle="tooltip"
                      title="<?= $allowance['button_export_csv']; ?>" class="btn btn-info"><i
                        class="fa fa-upload"></i></a>
                    <a href="<?= $allowance['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
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