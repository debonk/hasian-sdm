<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td style="width: 1px;" class="text-center" rowspan="2"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'nip') { ?>
          <a href="<?php echo $sort_nip; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_nip; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_nip; ?>"><?php echo $column_nip; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'name') { ?>
          <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'customer_group') { ?>
          <a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'customer_department') { ?>
          <a href="<?php echo $sort_customer_department; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_department; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_customer_department; ?>"><?php echo $column_customer_department; ?></a>
          <?php } ?></td>
        <td class="text-left" rowspan="2"><?php if ($sort == 'location') { ?>
          <a href="<?php echo $sort_location; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_location; ?></a>
          <?php } else { ?>
          <a href="<?php echo $sort_location; ?>"><?php echo $column_location; ?></a>
          <?php } ?></td>
        <td class="text-center range-date" colspan="7">
          <a href="<?php echo $schedule_prev; ?>" data-toggle="tooltip" title="<?php echo $button_prev; ?>" class="btn-info btn-xs pull-left"><i class="fa fa-step-backward fa-fw"></i></a>
          <?php echo $column_schedule_presence; ?>
          <a href="<?php echo $schedule_next; ?>" data-toggle="tooltip" title="<?php echo $button_next; ?>" class="btn-info btn-xs pull-right"><i class="fa fa-step-forward fa-fw"></i></a>
        </td>
        <td class="text-right" rowspan="2"><?php echo $column_action; ?></td>
      </tr>
        <tr>
          <?php foreach ($date_titles as $date_title) { ?>
          <td class="schedule"><?php echo $date_title['day']; ?></br>
          <?php echo $date_title['text']; ?></td>
          <?php } ?>
        </tr>
    </thead>
    <tbody>
      <?php if ($customers) { ?>
      <?php foreach ($customers as $customer) { ?>
      <tr>
        <td class="text-center"><?php if (in_array($customer['customer_id'], $selected)) { ?>
          <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
          <?php } ?></td>
        <td class="text-left"><?php echo $customer['nip']; ?></td>
        <td class="text-left"><?php echo $customer['name']; ?></td>
        <td class="text-left"><?php echo $customer['customer_group']; ?></td>
        <td class="text-left"><?php echo $customer['customer_department']; ?></td>
        <td class="text-left"><?php echo $customer['location']; ?></td>
        <?php foreach ($date_titles as $date_title) { ?>
        <?php if (isset($customer['schedules_data'][$date_title['date']])) { ?>
        <td class="text-center bg-<?php echo $customer['schedules_data'][$date_title['date']]['bg_class']; ?>" data-toggle="tooltip" title="<?php echo $customer['schedules_data'][$date_title['date']]['note']; ?>">
		  <div><?php echo $customer['schedules_data'][$date_title['date']]['schedule_type']; ?></div>
		  <div><?php echo $customer['schedules_data'][$date_title['date']]['presence_status']; ?></div>
		</td>
        <?php } else { ?>
        <td class="text-center"><?php echo '-'; ?></td>
        <?php } ?>
        <?php } ?>
        <td class="text-right"><a href="<?php echo $customer['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a></td>
       </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="17"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>