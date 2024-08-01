<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary"><i
            class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?= $button_copy; ?>" class="btn btn-default"
          onclick="$('#form-schedule-type').attr('action', '<?= $copy; ?>').submit()"><i
            class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
          onclick="confirm('<?= $text_confirm; ?>') ? $('#form-schedule-type').submit() : false;"><i
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
        <div class="well">
          <div class="flex-container">
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
          <div class="flex-container">
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-code">
                  <?= $entry_code; ?>
                </label>
                <input type="text" name="filter[code]" value="<?= $filter['code']; ?>" placeholder="<?= $entry_code; ?>"
                  id="input-code" class="form-control" />
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-code-id">
                  <?= $entry_code_id; ?>
                </label>
                <input type="text" name="filter[code_id]" value="<?= $filter['code_id']; ?>"
                  placeholder="<?= $entry_code_id; ?>" id="input-code-id" class="form-control" />
              </div>
            </div>
            <div class="flex-item">
              <div class="form-group">
                <label class="control-label" for="input-status">
                  <?= $entry_status; ?>
                </label>
                <select name="filter[status]" id="input-status" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php if ($filter['status']) { ?>
                  <option value="1" selected="selected">
                    <?= $text_enabled; ?>
                  </option>
                  <?php } else { ?>
                  <option value="1">
                    <?= $text_enabled; ?>
                  </option>
                  <?php } ?>
                  <?php if (!$filter['status'] && !is_null($filter['status'])) { ?>
                  <option value="0" selected="selected">
                    <?= $text_disabled; ?>
                  </option>
                  <?php } else { ?>
                  <option value="0">
                    <?= $text_disabled; ?>
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
        <form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-schedule-type">
          <div class="table-responsive">
            <table class="table table-bordered table-hover text-left">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td>
                    <a href="<?= $sort_name; ?>" <?=($sort=='name' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_name; ?>
                    </a>
                  </td>
                  <td class="text-center">
                    <a href="<?= $sort_code; ?>" <?=($sort=='code' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_code; ?>
                    </a>
                  </td>
                  <td class="text-center">
                    <a href="<?= $sort_code_id; ?>" <?=($sort=='code_id' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_code_id; ?>
                    </a>
                  </td>
                  <td>
                    <?= $column_location; ?>
                  </td>
                  <td>
                    <a href="<?= $sort_time_start; ?>" <?=($sort=='time_start' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_time_start; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_time_end; ?>" <?=($sort=='time_end' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_time_end; ?>
                    </a>
                  </td>
                  <td class="text-right">
                    <a href="<?= $sort_sort_order; ?>" <?=($sort=='sort_order' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_sort_order; ?>
                    </a>
                  </td>
                  <td>
                    <a href="<?= $sort_status; ?>" <?=($sort=='status' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                      <?= $column_status; ?>
                    </a>
                  </td>
                  <td>
                    <?= $column_current_use; ?>
                  </td>
                  <td class="text-right">
                    <?= $column_action; ?>
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($schedule_types) { ?>
                <?php foreach ($schedule_types as $schedule_type) { ?>
                <tr>
                  <td class="text-center">
                    <?php if (in_array($schedule_type['schedule_type_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $schedule_type['schedule_type_id']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $schedule_type['schedule_type_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td>
                    <?= $schedule_type['name']; ?>
                  </td>
                  <td class="text-center <?= 'schedule-bg-' . $schedule_type['bg_idx']; ?>">
                    <?= $schedule_type['code']; ?>
                  </td>
                  <td class="text-center">
                    <?= $schedule_type['code_id']; ?>
                  </td>
                  <td>
                    <?= $schedule_type['location']; ?>
                  </td>
                  <td>
                    <?= $schedule_type['time_start']; ?>
                  </td>
                  <td>
                    <?= $schedule_type['time_end']; ?>
                  </td>
                  <td class="text-right">
                    <?= $schedule_type['sort_order']; ?>
                  </td>
                  <td>
                    <?= $schedule_type['status']; ?>
                  </td>
                  <td>
                    <?= $schedule_type['current_use']; ?>
                  </td>
                  <td class="text-right"><a href="<?= $schedule_type['edit']; ?>" data-toggle="tooltip"
                      title="<?= $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
    url = 'index.php?route=presence/schedule_type&token=<?= $token; ?>';

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
</script>
<?= $footer; ?>