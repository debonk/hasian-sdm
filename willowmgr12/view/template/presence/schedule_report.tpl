<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td style="width: 1px;" class="text-center" rowspan="2"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'nip') { ?>
          <a href="<?= $sort_nip; ?>" class="<?= strtolower($order); ?>"><?= $column_nip; ?></a>
          <?php } else { ?>
          <a href="<?= $sort_nip; ?>"><?= $column_nip; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'name') { ?>
          <a href="<?= $sort_name; ?>" class="<?= strtolower($order); ?>"><?= $column_name; ?></a>
          <?php } else { ?>
          <a href="<?= $sort_name; ?>"><?= $column_name; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'customer_group') { ?>
          <a href="<?= $sort_customer_group; ?>" class="<?= strtolower($order); ?>"><?= $column_customer_group; ?></a>
          <?php } else { ?>
          <a href="<?= $sort_customer_group; ?>"><?= $column_customer_group; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'customer_department') { ?>
          <a href="<?= $sort_customer_department; ?>" class="<?= strtolower($order); ?>"><?= $column_customer_department; ?></a>
          <?php } else { ?>
          <a href="<?= $sort_customer_department; ?>"><?= $column_customer_department; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'location') { ?>
          <a href="<?= $sort_location; ?>" class="<?= strtolower($order); ?>"><?= $column_location; ?></a>
          <?php } else { ?>
          <a href="<?= $sort_location; ?>"><?= $column_location; ?></a>
          <?php } ?></td>
        <td class="text-center range-date" colspan="7">
          <a href="<?= $schedule_prev; ?>" data-toggle="tooltip" title="<?= $button_prev; ?>" class="btn-info btn-xs pull-left"><i class="fa fa-step-backward fa-fw"></i></a>
          <?= $column_schedule_presence; ?>
          <a href="<?= $schedule_next; ?>" data-toggle="tooltip" title="<?= $button_next; ?>" class="btn-info btn-xs pull-right"><i class="fa fa-step-forward fa-fw"></i></a>
        </td>
        <td class="text-right" rowspan="2"><?= $column_action; ?></td>
      </tr>
        <tr>
          <?php foreach ($date_titles as $date_title) { ?>
          <td class="schedule"><?= $date_title['day']; ?></br>
          <?= $date_title['text']; ?></td>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
      <?php if ($customers) { ?>
      <?php foreach ($customers as $customer) { ?>
      <tr>
        <td class="text-center"><?php if (in_array($customer['customer_id'], $selected)) { ?>
          <input type="checkbox" name="selected[]" value="<?= $customer['customer_id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="selected[]" value="<?= $customer['customer_id']; ?>" />
          <?php } ?></td>
        <td class="text-left"><?= $customer['nip']; ?></td>
        <td class="text-left"><?= $customer['name']; ?></td>
        <td class="text-left"><?= $customer['customer_group']; ?></td>
        <td class="text-left"><?= $customer['customer_department']; ?></td>
        <td class="text-left"><?= $customer['location']; ?></td>
        <?php foreach ($date_titles as $date_title) { ?>
        <?php if (isset($customer['schedules_data'][$date_title['date']])) { ?>
        <td class="text-center bg-<?= $customer['schedules_data'][$date_title['date']]['bg_class']; ?>" data-toggle="tooltip" title="<?= $customer['schedules_data'][$date_title['date']]['note']; ?>">
		  <div><?= $customer['schedules_data'][$date_title['date']]['schedule_type']; ?></div>
		  <div><?= $customer['schedules_data'][$date_title['date']]['presence_status']; ?></div>
		</td>
        <?php } else { ?>
        <td class="text-center"><?= '-'; ?></td>
        <?php } ?>
        <?php } ?>
        <td class="text-right"><a href="<?= $customer['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a></td>
       </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="17"><?= $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?= $pagination; ?></div>
  <div class="col-sm-6 text-right"><?= $results; ?></div>
</div>