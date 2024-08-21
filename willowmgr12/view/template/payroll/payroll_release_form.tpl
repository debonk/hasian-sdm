<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" id="button-save" form="form-release" data-toggle="tooltip" title="<?= $button_save; ?>"
          class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i
            class="fa fa-reply"></i></a>
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
    <div class="row">
      <div class="col-sm-3"></div>
      <div class="col-sm-6" id="period-info"></div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>
          <?= $text_edit; ?>
        </h3>
      </div>
      <div class="panel-body">
        <form method="post" action="<?= $edit; ?>" enctype="multipart/form-data" id="form-release"
          class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-fund-account">
              <?= $entry_fund_account; ?>
            </label>
            <div class="col-sm-10">
              <select name="fund_account_id" id="input-fund-account" class="form-control">
                <option value="0">
                  <?= $text_select ?>
                </option>
                <?php foreach ($fund_accounts as $fund_account) { ?>
                <?php if ($fund_account['fund_account_id'] == $fund_account_id) { ?>
                <option value="<?= $fund_account['fund_account_id']; ?>" selected="selected">
                  <?= $fund_account['fund_account_text']; ?>
                </option>
                <?php } else { ?>
                <option value="<?= $fund_account['fund_account_id']; ?>">
                  <?= $fund_account['fund_account_text']; ?>
                </option>
                <?php } ?>
                <?php } ?>
              </select>
              <?php if ($error_fund_account) { ?>
              <div class="text-danger">
                <?= $error_fund_account; ?>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-date-release">
              <?= $entry_date_release; ?>
            </label>
            <div class="col-sm-10">
              <div class="input-group date">
                <input type="text" name="date_release" value="<?= $date_release; ?>"
                  placeholder="<?= $entry_date_release; ?>" data-date-format="D MMM YYYY" id="input-date-release"
                  class="form-control" />
                <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span>
              </div>
              <?php if ($error_date_release) { ?>
              <div class="text-danger">
                <?= $error_date_release; ?>
              </div>
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');

    $('.date').datetimepicker({
      pickTime: false
    });
  </script>
</div>
<?= $footer; ?>