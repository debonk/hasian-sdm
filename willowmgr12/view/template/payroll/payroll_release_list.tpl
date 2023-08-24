<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <?php if ($released_status_check) { ?>
        <button type="button" id="button-payroll-complete" data-loading-text="<?= $text_loading; ?>"
          class="btn btn-warning"><i class="fa fa-check"></i>
          <?= $button_payroll_complete; ?>
        </button>
        <button type="button" class="btn btn-default"
          onclick="$('#form-payroll-release-list').attr('action', '<?= $export_cimb; ?>').submit()"><i
            class="fa fa-upload"></i>
          <?= $button_export_cimb; ?>
        </button>
        <button type="button" id="button-send" data-toggle="tooltip" title="<?= $button_send; ?>"
          class="btn btn-default"
          onclick="confirm('<?= $text_confirm; ?>') ? $('#form-payroll-release-list').submit() : false;"><i
            class="fa fa-envelope"></i></button>
        <?php } else { ?>
        <button type="button" class="btn btn-warning disabled"><i class="fa fa-check"></i>
          <?= $button_payroll_complete; ?>
        </button>
        <button type="button" class="btn btn-default disabled"><i class="fa fa-upload"></i>
          <?= $button_export_cimb; ?>
        </button>
        <button type="button" class="btn btn-default disabled"><i class="fa fa-envelope"></i></button>
        <?php } ?>
        <a href="<?= $back; ?>" data-toggle="tooltip" title="<?= $button_back; ?>"
          class="btn btn-default"><i class="fa fa-reply"></i></a>
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i>
      <?= $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
      <?= $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-sm-6" id="period-info"></div>
      <div class="col-sm-6" id="release-info"></div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i>
          <?= $text_list; ?>
        </h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name">
                  <?= $entry_name; ?>
                </label>
                <input type="text" name="filter_name" value="<?= $filter_name; ?>"
                  placeholder="<?= $entry_name; ?>" id="input-name" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-email">
                  <?= $entry_email; ?>
                </label>
                <input type="text" name="filter_email" value="<?= $filter_email; ?>"
                  placeholder="<?= $entry_email; ?>" id="input-email" class="form-control" />
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-customer-group">
                  <?= $entry_customer_group; ?>
                </label>
                <select name="filter_customer_group_id" id="input-customer-group" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <?php if ($customer_group['customer_group_id'] == $filter_customer_group_id) { ?>
                  <option value="<?= $customer_group['customer_group_id']; ?>" selected="selected">
                    <?= $customer_group['name']; ?>
                  </option>
                  <?php } else { ?>
                  <option value="<?= $customer_group['customer_group_id']; ?>">
                    <?= $customer_group['name']; ?>
                  </option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer-department">
                  <?= $entry_customer_department; ?>
                </label>
                <select name="filter_customer_department_id" id="input-customer-department" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php foreach ($customer_departments as $customer_department) { ?>
                  <?php if ($customer_department['customer_department_id'] == $filter_customer_department_id) { ?>
                  <option value="<?= $customer_department['customer_department_id']; ?>" selected="selected">
                    <?= $customer_department['name']; ?>
                  </option>
                  <?php } else { ?>
                  <option value="<?= $customer_department['customer_department_id']; ?>">
                    <?= $customer_department['name']; ?>
                  </option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-location">
                  <?= $entry_location; ?>
                </label>
                <select name="filter_location_id" id="input-location" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php foreach ($locations as $location) { ?>
                  <?php if ($location['location_id'] == $filter_location_id) { ?>
                  <option value="<?= $location['location_id']; ?>" selected="selected">
                    <?= $location['name']; ?>
                  </option>
                  <?php } else { ?>
                  <option value="<?= $location['location_id']; ?>">
                    <?= $location['name']; ?>
                  </option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-payroll-method">
                  <?= $entry_payroll_method; ?>
                </label>
                <select name="filter_payroll_method_id" id="input-payroll-method" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php foreach ($payroll_methods as $payroll_method) { ?>
                  <?php if ($payroll_method['payroll_method_id'] == $filter_payroll_method_id) { ?>
                  <option value="<?= $payroll_method['payroll_method_id']; ?>" selected="selected">
                    <?= $payroll_method['name']; ?>
                  </option>
                  <?php } else { ?>
                  <option value="<?= $payroll_method['payroll_method_id']; ?>">
                    <?= $payroll_method['name']; ?>
                  </option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-statement-sent">
                  <?= $entry_statement_sent; ?>
                </label>
                <select name="filter_statement_sent" id="input-statement-sent" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php if ($filter_statement_sent) { ?>
                  <option value="1" selected="selected">
                    <?= $text_yes; ?>
                  </option>
                  <?php } else { ?>
                  <option value="1">
                    <?= $text_yes; ?>
                  </option>
                  <?php } ?>
                  <?php if (!$filter_statement_sent && !is_null($filter_statement_sent)) { ?>
                  <option value="0" selected="selected">
                    <?= $text_no; ?>
                  </option>
                  <?php } else { ?>
                  <option value="0">
                    <?= $text_no; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
                <?= $button_filter; ?>
              </button>
            </div>
          </div>
        </div>
        <form method="post" action="<?= $send; ?>" enctype="multipart/form-data" id="form-payroll-release-list">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">
                    <?php if ($sort == 'nip') { ?>
                    <a href="<?= $sort_nip; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_nip; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_nip; ?>">
                      <?= $column_nip; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'name') { ?>
                    <a href="<?= $sort_name; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_name; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_name; ?>">
                      <?= $column_name; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'email') { ?>
                    <a href="<?= $sort_email; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_email; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_email; ?>">
                      <?= $column_email; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'customer_group') { ?>
                    <a href="<?= $sort_customer_group; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_customer_group; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_customer_group; ?>">
                      <?= $column_customer_group; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'customer_department') { ?>
                    <a href="<?= $sort_customer_department; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_customer_department; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_customer_department; ?>">
                      <?= $column_customer_department; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'location') { ?>
                    <a href="<?= $sort_location; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_location; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_location; ?>">
                      <?= $column_location; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'acc_no') { ?>
                    <a href="<?= $sort_acc_no; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_acc_no; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_acc_no; ?>">
                      <?= $column_acc_no; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'payroll_method') { ?>
                    <a href="<?= $sort_payroll_method; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_payroll_method; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_payroll_method; ?>">
                      <?= $column_payroll_method; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?php if ($sort == 'net_salary') { ?>
                    <a href="<?= $sort_net_salary; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_net_salary; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_net_salary; ?>">
                      <?= $column_net_salary; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-center">
                    <?php if ($sort == 'statement_sent') { ?>
                    <a href="<?= $sort_statement_sent; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_statement_sent; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_statement_sent; ?>">
                      <?= $column_statement_sent; ?>
                    </a>
                    <?php } ?>
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($payroll_releases) { ?>
                <?php foreach ($payroll_releases as $payroll_release) { ?>
                <tr>
                  <td class="text-center">
                    <?php if (in_array($payroll_release['customer_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $payroll_release['customer_id']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $payroll_release['customer_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['nip']; ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['name']; ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['email']; ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['customer_group']; ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['customer_department']; ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['location']; ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['acc_no']; ?>
                  </td>
                  <td class="text-left">
                    <?= $payroll_release['payroll_method']; ?>
                  </td>
                  <td class="text-right">
                    <?= $payroll_release['grandtotal']; ?>
                  </td>
                  <td class="text-center">
                    <?= $payroll_release['statement_sent'] ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9">
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
  <script type="text/javascript">
    $('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');

    $('#release-info').load('index.php?route=payroll/payroll_release/releaseinfo&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');

    $(document).keypress(function (e) {
      if (e.which == 13) {
        $("#button-filter").click();
      }
    });

    $('#button-filter').on('click', function () {
      url = 'index.php?route=payroll/payroll_release/info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>';

      let filter_name = $('input[name=\'filter_name\']').val();

      if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
      }

      let filter_email = $('input[name=\'filter_email\']').val();

      if (filter_email) {
        url += '&filter_email=' + encodeURIComponent(filter_email);
      }

      let filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();

      if (filter_customer_group_id != '*') {
        url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
      }

      let filter_customer_department_id = $('select[name=\'filter_customer_department_id\']').val();

      if (filter_customer_department_id != '*') {
        url += '&filter_customer_department_id=' + encodeURIComponent(filter_customer_department_id);
      }

      let filter_location_id = $('select[name=\'filter_location_id\']').val();

      if (filter_location_id != '*') {
        url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
      }

      let filter_payroll_method_id = $('select[name=\'filter_payroll_method_id\']').val();

      if (filter_payroll_method_id != '*') {
        url += '&filter_payroll_method_id=' + encodeURIComponent(filter_payroll_method_id);
      }

      let filter_statement_sent = $('select[name=\'filter_statement_sent\']').val();

      if (filter_statement_sent != '*') {
        url += '&filter_statement_sent=' + encodeURIComponent(filter_statement_sent);
      }

      location = url;
    });
  </script>
  <script type="text/javascript">
    $('#button-payroll-complete').on('click', function (e) {
      if (confirm('<?= $text_confirm; ?>')) {
        $.ajax({
          url: 'index.php?route=payroll/payroll_release/completepayroll&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>',
          dataType: 'json',
          crossDomain: true,
          beforeSend: function () {
            $('#button-payroll-complete').button('loading');
          },
          complete: function () {
            $('#button-payroll-complete').button('reset');
          },
          success: function (json) {
            $('.alert').remove();

            if (json['error']) {
              $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['success']) {
              $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

              $('#button-payroll-complete').replaceWith('<button type="button" class="btn btn-warning disabled"><i class="fa fa-check"></i> <?= $button_payroll_complete; ?></button>');
              $('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');
            }
          },
          error: function (xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    });
  </script>
  <script type="text/javascript">
    $('input[name=\'filter_name\']').autocomplete({
      'source': function (request, response) {
        $.ajax({
          url: 'index.php?route=presence/presence/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
          dataType: 'json',
          success: function (json) {
            response($.map(json, function (item) {
              return {
                label: item['name'],
                value: item['customer_id']
              }
            }));
          }
        });
      },
      'select': function (item) {
        $('input[name=\'filter_name\']').val(item['label']);
      }
    });
  </script>
</div>
<?= $footer; ?>