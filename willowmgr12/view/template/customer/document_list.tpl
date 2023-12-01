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
		<?php if ($information) { ?>
      <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i>
        <?= $information; ?>
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
                <input type="text" name="filter[name]" value="<?= $filter['name']; ?>" placeholder="<?= $entry_name; ?>"
                  id="input-name" class="form-control" />
              </div>
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
            <div class="col-sm-4">
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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-requirement">
                  <?= $entry_requirement; ?>
                </label>
                <select name="filter[requirement]" id="input-requirement" class="form-control">
                  <option value="">
                    <?= $text_all ?>
                  </option>
                  <?php foreach ($requirements as $key => $value) { ?>
                  <option value="<?= $key; ?>" <?=$key==$filter['requirement'] ? 'selected' : '' ; ?>>
                    <?= $value; ?>
                  </option>
                  <?php } ?>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label" for="input-status">
                  <?= $entry_status; ?>
                </label>
                <select name="filter[active]" id="input-status" class="form-control">
                  <option value="*">
                    <?= $text_all; ?>
                  </option>
                  <?php if ($filter['active'] == 1) { ?>
                  <option value="1" selected="selected">
                    <?= $text_active; ?>
                  </option>
                  <?php } else { ?>
                  <option value="1">
                    <?= $text_active; ?>
                  </option>
                  <?php } ?>
                  <?php if ($filter['active'] == -1) { ?>
                  <option value="-1" selected="selected">
                    <?= $text_inactive; ?>
                  </option>
                  <?php } else { ?>
                  <option value="-1">
                    <?= $text_inactive; ?>
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
          <table class="table table-bordered table-hover text-left">
            <thead>
              <tr>
                <td>
                  <a href="<?= $sort_nip; ?>" <?=($sort=='nip' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                    <?= $column_nip; ?>
                  </a>
                </td>
                <td>
                  <a href="<?= $sort_name; ?>" <?=($sort=='name' ) ? 'class="' . strtolower($order) . '"' : '' ; ?> >
                    <?= $column_name; ?>
                  </a>
                </td>
                <td>
                  <a href="<?= $sort_customer_group; ?>" <?=($sort=='customer_group' ) ? 'class="' . strtolower($order)
                    . '"' : '' ; ?> >
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
                  <a href="<?= $sort_location; ?>" <?=($sort=='location' ) ? 'class="' . strtolower($order) . '"' : '' ;
                    ?> >
                    <?= $column_location; ?>
                  </a>
                </td>
                <?php foreach ($document_types as $document_type) { ?>
                <td class="text-left <?= $document_type['required'] ? 'required' : ''; ?>">
                  <?= $document_type['title']; ?>
                </td>
                <?php } ?>
                <td class="text-right">
                  <?= $column_action; ?>
                </td>
              </tr>
            </thead>
            <tbody>
              <?php if ($documents) { ?>
              <?php foreach ($documents as $document) { ?>
              <tr>
                <td class="text-left">
                  <?= $document['nip']; ?>
                </td>
                <td class="text-left">
                  <?= $document['name']; ?>
                </td>
                <td class="text-left">
                  <?= $document['customer_group']; ?>
                </td>
                <td class="text-left">
                  <?= $document['customer_department']; ?>
                </td>
                <td class="text-left">
                  <?= $document['location']; ?>
                </td>
                <?php foreach ($document_types as $document_type) { ?>
                <?php if (isset($document['documents'][$document_type['document_type_id']])) { ?>
                <td class="text-center text-success"><i class="fa fa-check"></td>
                <?php } else { ?>
                <td></td>
                <?php } ?>
                <?php } ?>
                <td class="text-right"><a href="<?= $document['edit']; ?>" data-toggle="tooltip"
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
  $('#button-filter').on('click', function () {
    url = 'index.php?route=customer/document&token=<?= $token; ?>';

    let filter_items = [
      'name',
      'customer_group_id',
      'customer_department_id',
      'location_id',
      'requirement',
      'active'
    ];

    let filter = [];

    for (let i = 0; i < filter_items.length; i++) {
      filter[filter_items[i]] = $('.well [name=\'filter[' + filter_items[i] + ']\']').val();

      if (filter[filter_items[i]]) {
        url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
      }
    }

    location = url;
  });

  $('input[name=\'filter[name]\']').autocomplete({
    'source': function (request, response) {
      $.ajax({
        url: 'index.php?route=customer/document/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
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

  $(document).keypress(function (e) {
    if (e.which == 13) {
      $("#button-filter").click();
    }
  });
</script>
<?= $footer; ?>