<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary"><i
            class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
          onclick="confirm('<?= $text_confirm; ?>') ? $('#form-incentive').submit() : false;"><i
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
        <h4 class="pull-right"><i class="fa fa-line-chart"></i>
          <?= $grandtotal; ?>
        </h4>
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
                <label class="control-label" for="input-period">
                  <?= $entry_period; ?>
                </label>
                <div class="input-group month">
                  <input type="text" name="filter[period]" value="<?= $filter['period']; ?>"
                    placeholder="<?= $entry_period; ?>" id="input-period" class="form-control"
                    data-date-format="MMM YYYY" />
                  <span class="input-group-btn">
                    <button type="button" class="btn btn-default"><i class="fa fa-calendar-o"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-description">
                  <?= $entry_description; ?>
                </label>
                <input type="text" name="filter[description]" value="<?= $filter['description']; ?>"
                  placeholder="<?= $entry_description; ?>" id="input-description" class="form-control" />
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-status">
                  <?= $entry_status; ?>
                </label>
                <select name="filter[status]" id="input-status" class="form-control">
                  <option value="*">
                    <?= $text_unpaid; ?>
                  </option>
                  <?php if ($filter['status']) { ?>
                  <option value="1" selected="selected">
                    <?= $text_paid; ?>
                  </option>
                  <?php } else { ?>
                  <option value="1">
                    <?= $text_paid; ?>
                  </option>
                  <?php } ?>
                  <?php if (!$filter['status'] && !is_null($filter['status'])) { ?>
                  <option value="0" selected="selected">
                    <?= $text_all; ?>
                  </option>
                  <?php } else { ?>
                  <option value="0">
                    <?= $text_all; ?>
                  </option>
                  <?php } ?>
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
        <form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-incentive">
          <div class="table-responsive">
            <table class="table table-bordered table-hover text-left">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td>
                    <a href="<?= $sort_date; ?>" <?=($sort=='date' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_date; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_name; ?>" <?=($sort=='name' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_name; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_customer_group; ?>" <?=($sort=='customer_group' ) ? 'class="' .
                      strtolower($order) . '"' : '' ; ?> >
                      <?= $column_customer_group; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_customer_department; ?>" <?=($sort=='customer_department' ) ? 'class="' .
                      strtolower($order) . '"' : '' ; ?> >
                      <?= $column_customer_department; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_location; ?>" <?=($sort=='location' ) ? 'class="' . strtolower($order) . '"' : ''
                      ; ?> >
                      <?= $column_location; ?>
                    </a>
                  </td>
                  <td>
                    <?= $column_description; ?>
                  </td>
                  <td class="text-right">
                    <?= $column_amount; ?>
                  </td>
                  <td class="text-center">
                    <a href="<?= $sort_period; ?>" <?=($sort=='period' ) ? 'class="' . strtolower($order) . '"' : ''
                      ; ?> >
                      <?= $column_period; ?>
                    </a>
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
                <?php if ($incentives) { ?>
                <?php foreach ($incentives as $incentive) { ?>
                <tr>
                  <td class="text-center">
                    <?php if (in_array($incentive['incentive_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $incentive['incentive_id']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $incentive['incentive_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td>
                    <?= $incentive['date']; ?>
                  </td>
                  <td>
                    <?= $incentive['name']; ?>
                  </td>
                  <td>
                    <?= $incentive['customer_group']; ?>
                  </td>
                  <td>
                    <?= $incentive['customer_department']; ?>
                  </td>
                  <td>
                    <?= $incentive['location']; ?>
                  </td>
                  <td>
                    <?= $incentive['description']; ?>
                  </td>
                  <td class="text-right nowrap">
                    <?= $incentive['amount']; ?>
                  </td>
                  <?php if ($incentive['period']) { ?>
                  <td class="text-center">
                    <?= $incentive['period']; ?>
                  </td>
                  <?php } else { ?>
                  <td class="text-center text-danger"><i class="fa fa-question"></i></td>
                  <?php } ?>
                  <td>
                    <?= $incentive['username']; ?>
                  </td>
                  <td class="text-right">
                    <?php if ($incentive['period']) { ?>
                    <a href="<?= $incentive['view']; ?>" data-toggle="tooltip" title="<?= $button_view; ?>"
                      class="btn btn-info" target="_blank" rel="noopener noreferrer"><i class="fa fa-eye"></i></a>
                    <?php } else { ?>
                    <a href="<?= $incentive['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
                      class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?>
                <td class="text-right text-bold" colspan="7">
                  <?= $text_subtotal; ?>
                </td>
                <td class="text-right text-bold nowrap">
                  <?= $subtotal; ?>
                </td>
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
  $(document).keypress(function (e) {
    if (e.which == 13) {
      $("#button-filter").click();
    }
  });

  $('#button-filter').on('click', function () {
    url = 'index.php?route=incentive/incentive&token=<?= $token; ?>';

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

  $('input[name=\'filter[name]\']').autocomplete({
    'source': function (request, response) {
      $.ajax({
        url: 'index.php?route=incentive/incentive/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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

  $('.month').datetimepicker({
    minViewMode: 'months',
    pickTime: false
  });
</script>
<?= $footer; ?>