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
        <button type="button" class="btn btn-danger"
          onclick="confirm('<?= $text_confirm_release; ?>') ? $('#form-payroll-release-list').attr('action', '<?= $export_cimb; ?>').submit() : false;"><i
            class="fa fa-upload"></i>
          <?= $button_export_cimb; ?>
        </button>
        <?php } else { ?>
        <button type="button" class="btn btn-warning disabled"><i class="fa fa-check"></i>
          <?= $button_payroll_complete; ?>
        </button>
        <button type="button" class="btn btn-danger disabled"><i class="fa fa-upload"></i>
          <?= $button_export_cimb; ?>
        </button>
        <?php } ?>
        <span class="dropdown">
          <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><i
              class="fa fa-tasks"></i>
            <?= $button_action; ?>
          </button>
          <ul class="dropdown-menu pull-right" id="menu-action">
            <?php foreach ($actions as $action) { ?>
            <?php $href = $action['href'] ?>
            <li><a href="#"
                onclick="confirm('<?= $text_confirm; ?>') ? $('#form-payroll-release-list').attr('action', '<?= $href; ?>').submit() : false;">
                <?= $action['text']; ?>
              </a></li>
            <?php } ?>
          </ul>
        </span>
        <a href="<?= $back; ?>" data-toggle="tooltip" title="<?= $button_back; ?>" class="btn btn-default"><i
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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i>
      <?= $success; ?>
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
          <div class="row flex-container">
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-name">
                  <?= $entry_name; ?>
                </label>
                <input type="text" name="filter[name]" value="<?= $filter['name']; ?>" placeholder="<?= $entry_name; ?>"
                  id="input-name" class="form-control" />
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-customer-group">
                  <?= $entry_customer_group; ?>
                </label>
                <select name="filter[customer_group_id]" id="input-customer-group" class="form-control">
                  <option value="">
                    <?= $text_all; ?>
                  </option>
                  <?php foreach ($customer_groups as $customer_group) { ?>
                  <option value="<?= $customer_group['customer_group_id']; ?>"
                    <?=$customer_group['customer_group_id']==$filter['customer_group_id'] ? 'selected' : '' ; ?>>
                    <?= $customer_group['name']; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-customer-department">
                  <?= $entry_customer_department; ?>
                </label>
                <select name="filter[customer_department_id]" id="input-customer-department" class="form-control">
                  <option value="">
                    <?= $text_all; ?>
                  </option>
                  <?php foreach ($customer_departments as $customer_department) { ?>
                  <option value="<?= $customer_department['customer_department_id']; ?>"
                    <?=$customer_department['customer_department_id']==$filter['customer_department_id'] ? 'selected'
                    : '' ; ?>>
                    <?= $customer_department['name']; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-location">
                  <?= $entry_location; ?>
                </label>
                <select name="filter[location_id]" id="input-location" class="form-control">
                  <option value="">
                    <?= $text_all ?>
                  </option>
                  <?php foreach ($locations as $location) { ?>
                  <option value="<?= $location['location_id']; ?>" <?=$location['location_id']==$filter['location_id']
                    ? 'selected' : '' ; ?>>
                    <?= $location['name']; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row flex-container">
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-email">
                  <?= $entry_email; ?>
                </label>
                <input type="text" name="filter[email]" value="<?= $filter['email']; ?>"
                  placeholder="<?= $entry_email; ?>" id="input-email" class="form-control" />
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-payroll-method">
                  <?= $entry_payroll_method; ?>
                </label>
                <select name="filter[payroll_method_id]" id="input-payroll-method" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php foreach ($payroll_methods as $payroll_method) { ?>
                  <option value="<?= $payroll_method['payroll_method_id']; ?>"
                    <?=($payroll_method['payroll_method_id']==$filter['payroll_method_id']) ? 'selected' : '' ; ?>>
                    <?= $payroll_method['name']; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-release-status">
                  <?= $entry_release_status; ?>
                </label>
                <select name="filter[status_released]" id="input-release-status" class="form-control">
                  <option value="*">
                    <?= $text_all ?>
                  </option>
                  <?php foreach ($release_statuses as $release_status) { ?>
                  <option value="<?= $release_status['code']; ?>"
                    <?=($release_status['code']==$filter['status_released']) ? 'selected' : '' ; ?>>
                    <?= $release_status['text']; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-statement-sent">
                  <?= $entry_statement_sent; ?>
                </label>
                <select name="filter[statement_sent]" id="input-statement-sent" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <option value="1" <?=($filter['statement_sent']) ? 'selected' : '' ; ?>>
                    <?= $text_yes; ?>
                  </option>
                  <option value="0" <?=(!$filter['statement_sent'] && !is_null($filter['statement_sent'])) ? 'selected'
                    : '' ; ?>>
                    <?= $text_no; ?>
                  </option>
                </select>
              </div>
            </div>
            <div>
              <div class="form-group">
                <label>
                  <?= '&nbsp;'; ?>
                </label>
                <div>
                  <button type="button" id="button-filter" class="btn btn-primary pull-right"><i
                      class="fa fa-search"></i>
                    <?= $button_filter; ?>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <form method="post" action="<?= $send; ?>" enctype="multipart/form-data" id="form-payroll-release-list">
          <div class="table-responsive">
            <table class="table table-bordered table-hover text-left">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td>
                    <a href="<?= $sort_nip; ?>" <?=($sort=='nip' ) ? 'class="' . strtolower($order) . '"' : '' ; ?>>
                      <?= $column_nip; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_name; ?>" <?=($sort=='name' ) ? 'class="' . strtolower($order) . '"' : '' ; ?>>
                      <?= $column_name; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_email; ?>" <?=($sort=='email' ) ? 'class="' . strtolower($order) . '"' : '' ; ?>>
                      <?= $column_email; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_customer_group; ?>" <?=($sort=='customer_group' ) ? 'class="' .
                      strtolower($order) . '"' : '' ; ?>>
                      <?= $column_customer_group; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_customer_department; ?>" <?=($sort=='customer_department' ) ? 'class="' .
                      strtolower($order) . '"' : '' ; ?>>
                      <?= $column_customer_department; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_location; ?>" <?=($sort=='location' ) ? 'class="' . strtolower($order) . '"' : ''
                      ; ?>>
                      <?= $column_location; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_payroll_method; ?>" <?=($sort=='payroll_method' ) ? 'class="' .
                      strtolower($order) . '"' : '' ; ?>>
                      <?= $column_payroll_method; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_acc_no; ?>" <?=($sort=='acc_no' ) ? 'class="' . strtolower($order) . '"' : '' ;
                      ?>>
                      <?= $column_acc_no; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_net_salary; ?>" <?=($sort=='net_salary' ) ? 'class="' . strtolower($order) . '"'
                      : '' ; ?>>
                      <?= $column_net_salary; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_date_released; ?>" <?=($sort=='date_released' ) ? 'class="' . strtolower($order)
                      . '"' : '' ; ?>>
                      <?= $column_date_released; ?>
                    </a>
                  </td>
                  <td class="text-center">
                    <a href="<?= $sort_statement_sent; ?>" <?=($sort=='statement_sent' ) ? 'class="' .
                      strtolower($order) . '"' : '' ; ?>>
                      <?= $column_statement_sent; ?>
                    </a>
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($payroll_releases) { ?>
                <?php if (isset($payroll_releases['late'])) { ?>
                <tr>
                  <th colspan="12">
                    <?= $text_release_late; ?>
                  </th>
                </tr>
                <?php foreach ($payroll_releases['late'] as $payroll_release) { ?>
                <tr >
                  <td class="text-center">
                    <?php if (in_array($payroll_release['customer_code'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $payroll_release['customer_code']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $payroll_release['customer_code']; ?>" />
                    <?php } ?>
                  </td>
                  <td>
                    <?= $payroll_release['nip']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['name']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['email']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['customer_group']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['customer_department']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['location']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['payroll_method']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['acc_no']; ?>
                  </td>
                  <td class="text-right">
                    <?= $payroll_release['grandtotal']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['date_released']; ?>
                  </td>
                  <td class="text-center">
                    <?= $payroll_release['statement_sent'] ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' ?>
                  </td>
                </tr>
                <?php } ?>
                <?php if (isset($payroll_releases['present'])) { ?>
                <tr>
                  <th colspan="12">
                    <?= $text_release_present; ?>
                  </th>
                </tr>
                <?php } ?>
                <?php } ?>
                <?php if (isset($payroll_releases['present'])) { ?>
                <?php foreach ($payroll_releases['present'] as $payroll_release) { ?>
                <tr class="<?= $payroll_release['text_class']; ?>">
                  <td class="text-center">
                    <?php if (in_array($payroll_release['customer_code'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $payroll_release['customer_code']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $payroll_release['customer_code']; ?>" />
                    <?php } ?>
                  </td>
                  <td>
                    <?= $payroll_release['nip']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['name']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['email']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['customer_group']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['customer_department']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['location']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['payroll_method']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['acc_no']; ?>
                  </td>
                  <td class="text-right">
                    <?= $payroll_release['grandtotal']; ?>
                  </td>
                  <td>
                    <?= $payroll_release['date_released']; ?>
                  </td>
                  <td class="text-center">
                    <?= $payroll_release['statement_sent'] ? '<i class="fa fa-check-square-o"></i>' : '<i class="fa fa-square-o"></i>' ?>
                  </td>
                </tr>
                <?php } ?>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="12">
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
<script type="text/javascript">
  $('#period-info').load('index.php?route=common/period_info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>');

  $('#release-info').load('index.php?route=payroll/payroll_release/releaseinfo&token=<?= $token . $url; ?>');

  $(document).keypress(function (e) {
    if (e.which == 13) {
      $("#button-filter").click();
    }
  });

  $('#button-filter').on('click', function () {
    url = 'index.php?route=payroll/payroll_release/info&token=<?= $token; ?>&presence_period_id=<?= $presence_period_id; ?>';

    let filter = [];

    let filter_items = JSON.parse('<?= $filter_items; ?>');

    for (let i = 0; i < filter_items.length; i++) {
      filter[filter_items[i]] = $('.well [name=\'filter[' + filter_items[i] + ']\']').val();

      if (filter[filter_items[i]] && filter[filter_items[i]] != '*') {
        url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
      }
    }

    location = url;
  });

  $('#menu-action a').on('click', function (e) {
    e.preventDefault();
  });

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

  $('input[name=\'filter[name]\']').autocomplete({
    'source': function (request, response) {
      $.ajax({
        url: 'index.php?route=presence/presence/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request) + '&presence_period_id=<?= $presence_period_id; ?>',
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
      $('input[name=\'filter[name]\']').val(item['label']);
    }
  });
</script>
<?= $footer; ?>