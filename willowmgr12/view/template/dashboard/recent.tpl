<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <td class="text-right"><?php echo $column_nip; ?></td>
          <td><?php echo $column_name; ?></td>
          <td><?php echo $column_customer_group; ?></td>
          <td><?php echo $column_date_start; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($customers) { ?>
        <?php foreach ($customers as $customer) { ?>
        <tr>
          <td class="text-right"><?php echo $customer['nip']; ?></td>
          <td><?php echo $customer['name']; ?></td>
          <td><?php echo $customer['customer_group']; ?></td>
          <td><?php echo $customer['date_start']; ?></td>
          <td class="text-right"><a href="<?php echo $customer['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
