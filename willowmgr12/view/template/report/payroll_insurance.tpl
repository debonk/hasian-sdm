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
    <div class="alert">
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart"></i>
          <?= $text_list; ?>
        </h3>
        <h4 class="pull-right"><i class="fa fa-calendar"></i> <span id="period-info"></span>
        </h4>
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
                <label class="control-label" for="input-presence-period">
                  <?= $entry_presence_period; ?>
                </label>
                <select name="presence_period_id" id="input-presence-period" class="form-control">
                  <?php foreach ($presence_periods as $presence_period) { ?>
                  <?php if ($presence_period['presence_period_id'] == $presence_period_id) { ?>
                  <option value="<?= $presence_period['presence_period_id']; ?>" selected="selected">
                    <?= date('M y',strtotime($presence_period['period'])); ?>
                  </option>
                  <?php } else { ?>
                  <option value="<?= $presence_period['presence_period_id']; ?>">
                    <?= date('M y',strtotime($presence_period['period'])); ?>
                  </option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
                <?= $button_filter; ?>
              </button>
            </div>
          </div>
        </div>
        <div id="insurance-report"></div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
    $('input[name=\'filter_name\']').autocomplete({
      'source': function (request, response) {
        $.ajax({
          url: 'index.php?route=customer/customer/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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

    $('#button-filter').on('click', function () {
      url = '';

      let presence_period_id = $('select[name=\'presence_period_id\']').val();

      if (presence_period_id) {
        url += '&presence_period_id=' + encodeURIComponent(presence_period_id);
      }

      let filter_name = $('input[name=\'filter_name\']').val();

      if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
      }

      let filter_customer_department_id = $('select[name=\'filter_customer_department_id\']').val();

      if (filter_customer_department_id != '*') {
        url += '&filter_customer_department_id=' + encodeURIComponent(filter_customer_department_id);
      }

      let filter_customer_group_id = $('select[name=\'filter_customer_group_id\']').val();

      if (filter_customer_group_id != '*') {
        url += '&filter_customer_group_id=' + encodeURIComponent(filter_customer_group_id);
      }

      let filter_location_id = $('select[name=\'filter_location_id\']').val();

      if (filter_location_id != '*') {
        url += '&filter_location_id=' + encodeURIComponent(filter_location_id);
      }

      $('#insurance-report').load('index.php?route=report/payroll_insurance/report&token=<?= $token; ?>' + url);

      if (history.replaceState) {
        history.replaceState({}, 'Data List', 'index.php?route=report/payroll_insurance&token=<?= $token; ?>' + url);
      }
    });

    $('#button-filter').trigger('click');

    $(document).keypress(function (e) {
      if (e.which == 13) {
        $("#button-filter").click();
      }
    });
  </script>
</div>
<?= $footer; ?>