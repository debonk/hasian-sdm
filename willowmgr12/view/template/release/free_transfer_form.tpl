<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-free-transfer" data-toggle="tooltip" title="<?= $button_save; ?>"
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>
          <?= $text_form; ?>
        </h3>
        <h4 class="pull-right"><i class="fa fa-comment-o fa-flip-horizontal"></i>
          <?= $text_modified; ?>
        </h4>
      </div>
      <div class="panel-body">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-free-transfer"
          class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-description">
              <?= $entry_description; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="description" value="<?= $description; ?>"
                placeholder="<?= $entry_description; ?>" id="input-description" class="form-control" />
              <?php if ($error_description) { ?>
              <div class="text-danger">
                <?= $error_description; ?>
              </div>
              <?php } ?>
            </div>
          </div>
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
            <label class="col-sm-2 control-label" for="input-date-process">
              <?= $entry_date_process; ?>
            </label>
            <div class="col-sm-10">
              <div class="input-group date">
                <input type="text" name="date_process" value="<?= $date_process; ?>"
                  placeholder="<?= $entry_date_process; ?>" id="input-date-process" class="form-control"
                  data-date-format="D MMM YYYY" />
                <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span>
              </div>
              <?php if ($error_date_process) { ?>
              <div class="text-danger">
                <?= $error_date_process; ?>
              </div>
              <?php } ?>
            </div>
          </div>
          <table id="free-transfer-customer" class="table table-striped table-bordered table-hover text-left">
            <thead>
              <tr>
                <td>
                  <?= $column_customer; ?>
                </td>
                <td>
                  <?= $column_customer_group; ?>
                </td>
                <td>
                  <?= $column_location; ?>
                </td>
                <td>
                  <?= $column_payroll_method; ?>
                </td>
                <td>
                  <?= $column_note; ?>
                </td>
                <td class="text-right">
                  <?= $column_amount; ?>
                </td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($free_transfer_customers as $free_transfer_customer) { ?>
              <tr id="free-transfer-customer-row<?= $free_transfer_customer['customer_id']; ?>">
                <td>
                  <?= $free_transfer_customer['name']; ?>
                  <input type="hidden"
                    name="free_transfer_customer[<?= $free_transfer_customer['customer_id']; ?>][customer_id]"
                    value="<?= $free_transfer_customer['customer_id']; ?>" />
                </td>
                <td>
                  <?= $free_transfer_customer['customer_group']; ?>
                </td>
                <td>
                  <?= $free_transfer_customer['location']; ?>
                </td>
                <td>
                  <?= $free_transfer_customer['payroll_method']; ?>
                </td>
                <td><input type="text"
                    name="free_transfer_customer[<?= $free_transfer_customer['customer_id']; ?>][note]"
                    class="form-control" value="<?= $free_transfer_customer['note']; ?>"
                    placeholder="<?= $entry_note; ?>" /></td>
                <td class="text-right"><input type="text"
                    name="free_transfer_customer[<?= $free_transfer_customer['customer_id']; ?>][amount]"
                    class="form-control" value="<?= $free_transfer_customer['amount']; ?>"
                    placeholder="<?= $entry_amount; ?>" /></td>
                <td class="text-right"><button type="button"
                    onclick="$(`#free-transfer-customer-row<?= $free_transfer_customer['customer_id']; ?>`).remove();"
                    data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i
                      class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php } ?>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="4">
                  <div class="col-sm-4">
                    <input type="text" name="input_free_transfer_customer" value=""
                      placeholder="<?= $entry_input_customer; ?>" id="input-free-transfer-customer" class="form-control"
                      <?=$status_processed ? 'disabled' : '' ; ?> />
                  </div>
                </td>
              </tr>
            </tfoot>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('input[name=\'input_free_transfer_customer\']').autocomplete({
    'source': function (request, response) {
      $.ajax({
        url: 'index.php?route=release/free_transfer/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
        dataType: 'json',
        success: function (json) {
          response($.map(json, function (item) {
            return {
              label: item['name_set'],
              value: item['customer_id'],
              customer: item['name'],
              customer_group: item['customer_group'],
              location: item['location'],
              payroll_method: item['payroll_method']
            }
          }));
        }
      });
    },
    'select': function (item) {
      if (!$('#free-transfer-customer-row' + item['value']).length) {
        $('input[name=\'input_free_transfer_customer\']').val('');

        html = '<tr id="free-transfer-customer-row' + item['value'] + '">';
        html += '  <td>' + item['customer'];
        html += '  <input type="hidden" name="free_transfer_customer[' + item['value'] + '][customer_id]" value="' + item['value'] + '" /></td>';
        html += '  <td>' + item['customer_group'] + '</td>';
        html += '  <td>' + item['location'] + '</td>';
        html += '  <td>' + item['payroll_method'] + '</td>';
        html += '  <td><input type="text" name="free_transfer_customer[' + item['value'] + '][note]" class="form-control" value="" placeholder="<?= $entry_note; ?>" /></td>';
        html += '  <td class="text-right"><input type="text" name="free_transfer_customer[' + item['value'] + '][amount]" class="form-control" value="" placeholder="<?= $entry_amount; ?>" /></td>';
        html += '  <td class="text-right"><button type="button" onclick="$(\'#free-transfer-customer-row' + item['value'] + '\').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#free-transfer-customer tbody').append(html);

        $('free_transfer_customer[' + item['value'] + '][customer_id]').trigger();
      }
    }
  });

  $('.date').datetimepicker({
    pickTime: false
  });
</script>
<?= $footer; ?>