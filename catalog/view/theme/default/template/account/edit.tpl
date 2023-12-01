<?= $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?= $breadcrumb['href']; ?>">
        <?= $breadcrumb['text']; ?>
      </a></li>
    <?php } ?>
  </ul>
  <div class="row">
    <?= $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?= $class; ?>">
      <?= $content_top; ?>
      <h1>
        <?= $heading_title; ?>
      </h1>
      <form class="form-horizontal">
        <fieldset>
          <legend>
            <?= $text_basic_info; ?>
          </legend>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_firstname; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $firstname; ?>" placeholder="<?= $entry_firstname; ?>" class="form-control"
                disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_lastname; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $lastname; ?>" placeholder="<?= $entry_lastname; ?>" class="form-control"
                disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_email; ?>
            </label>
            <div class="col-sm-9">
              <input type="email" value="<?= $email; ?>" placeholder="<?= $entry_email; ?>" class="form-control"
                disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_telephone; ?>
            </label>
            <div class="col-sm-9">
              <input type="tel" value="<?= $telephone; ?>" placeholder="<?= $entry_telephone; ?>" class="form-control"
                disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_acc_no; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $acc_no; ?>" placeholder="<?= $entry_acc_no; ?>" class="form-control"
                disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_date_start; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $date_start; ?>" placeholder="<?= $entry_date_start; ?>"
                class="form-control" disabled />
            </div>
          </div>
          <?php foreach ($custom_fields as $custom_field) { ?>
          <?php if ($custom_field['location'] == 'account') { ?>
          <div class="form-group custom-field" data-sort="<?= $custom_field['sort_order']; ?>">
            <label class="col-sm-3 control-label">
              <?= $custom_field['name']; ?>
            </label>
            <div class="col-sm-9">
              <input type="text"
                value="<?= (isset($account_custom_field[$custom_field['custom_field_id']]) ? $account_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>"
                placeholder="<?= $custom_field['name']; ?>" class="form-control" />
            </div>
          </div>
          <?php } ?>
          <?php } ?>
        </fieldset>
        <fieldset>
          <legend>
            <?= $text_contract; ?>
          </legend>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_contract_type; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $contract_type; ?>" placeholder="<?= $entry_contract_type; ?>"
                class="form-control" disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_contract_status; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $contract_status; ?>" placeholder="<?= $entry_contract_status; ?>"
                class="form-control" disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_date_end; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $date_end; ?>" placeholder="<?= $entry_date_end; ?>" class="form-control"
                disabled />
            </div>
          </div>
        </fieldset>
        <fieldset>
          <legend>
            <?= $text_placement; ?>
          </legend>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_customer_group; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $customer_group; ?>" placeholder="<?= $entry_customer_group; ?>"
                class="form-control" disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_customer_department; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $customer_department; ?>" placeholder="<?= $entry_customer_department; ?>"
                class="form-control" disabled />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-3 control-label">
              <?= $entry_location; ?>
            </label>
            <div class="col-sm-9">
              <input type="text" value="<?= $location; ?>" placeholder="<?= $entry_location; ?>" class="form-control"
                disabled />
            </div>
          </div>

        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?= $back; ?>" class="btn btn-default">
              <?= $button_back; ?>
            </a></div>
        </div>
      </form>
      <?= $content_bottom; ?>
    </div>
    <?= $column_right; ?>
  </div>
</div>
<script type="text/javascript">
  // Sort the custom fields
  $('.form-group[data-sort]').detach().each(function () {
    if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('.form-group').length) {
      $('.form-group').eq($(this).attr('data-sort')).before(this);
    }

    if ($(this).attr('data-sort') > $('.form-group').length) {
      $('.form-group:last').after(this);
    }

    if ($(this).attr('data-sort') == $('.form-group').length) {
      $('.form-group:last').after(this);
    }

    if ($(this).attr('data-sort') < -$('.form-group').length) {
      $('.form-group:first').before(this);
    }
  });
</script>
<?= $footer; ?>