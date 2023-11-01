<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-name">
                  <?= $entry_name; ?>
                </label>
                <input type="text" name="filter_name" value="<?= $filter_name; ?>" placeholder="<?= $entry_name; ?>"
                  id="input-name" class="form-control" />
              </div>
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
            </div>
            <div class="col-sm-4">
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
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status">
                  <?= $entry_status; ?>
                </label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value="*">
                    <?= $text_active; ?>
                  </option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected">
                    <?= $text_inactive; ?>
                  </option>
                  <?php } else { ?>
                  <option value="1">
                    <?= $text_inactive; ?>
                  </option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected">
                    <?= $text_all_status; ?>
                  </option>
                  <?php } else { ?>
                  <option value="0">
                    <?= $text_all_status; ?>
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
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
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
                <td class="text-center">
                  <?= $column_scan_active; ?>
                </td>
                <td class="text-right">
                  <?= $column_action; ?>
                </td>
              </tr>
            </thead>
            <tbody>
              <?php if ($customers) { ?>
              <?php foreach ($customers as $customer) { ?>
              <tr>
                <td class="text-left">
                  <?= $customer['nip']; ?>
                </td>
                <td class="text-left">
                  <?= $customer['name']; ?>
                </td>
                <td class="text-left">
                  <?= $customer['customer_group']; ?>
                </td>
                <td class="text-left">
                  <?= $customer['customer_department']; ?>
                </td>
                <td class="text-left">
                  <?= $customer['location']; ?>
                </td>
                <td class="text-center">
                  <?php foreach ($customer['scan_active'] as $scan_active) { ?>
                  <button type="button" value="<?= $scan_active['index']; ?>"
                    id="button-verification<?= $scan_active['index']; ?>" data-loading-text="<?= $text_loading; ?>"
                    data-toggle="tooltip" title="<?= $button_verification; ?>" class="btn btn-default"><i
                      class="fa fa-barcode"></i>
                    <?= $scan_active['text']; ?>
                  </button>
                  <?php } ?>
                </td>
                <td class="text-right">
                  <a href="<?= $customer['manage']; ?>" data-toggle="tooltip" title="<?= $button_manage; ?>"
                    class="btn btn-success"><i class="fa fa-tasks"></i></a>
                  <a href="<?= $customer['view']; ?>" data-toggle="tooltip" title="<?= $button_view; ?>"
                    class="btn btn-info" target="_blank" rel="noopener noreferrer"><i class="fa fa-eye"></i></a>
                </td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="11">
                  <?= $text_no_results; ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
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
    $(document).keypress(function (e) {
      if (e.which == 13) {
        $("#button-filter").click();
      }
    });

    $('#button-filter').on('click', function () {
      url = 'index.php?route=customer/finger&token=<?= $token; ?>';

      let filter_name = $('input[name=\'filter_name\']').val();

      if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
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

      let filter_status = $('select[name=\'filter_status\']').val();

      if (filter_status != '*') {
        url += '&filter_status=' + encodeURIComponent(filter_status);
      }

      location = url;
    });
  </script>
  <script type="text/javascript">
    $('input[name=\'filter_name\']').autocomplete({
      'source': function (request, response) {
        $.ajax({
          url: 'index.php?route=customer/finger/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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
  <script type="text/javascript">
    $('td').on('click', 'button[id^=\'button-verification\']', function (e) {
      var node = this;
      $(node).button('loading');
      $('.alert').remove();

      url = 'index.php?route=customer/finger/verification&token=<?= $token; ?>&finger_index=' + $(node).val();
      location = url;

      setTimeout(function () {
        $(node).button('reset');
      }, 1500);
    });
  </script>
</div>
<?= $footer; ?>