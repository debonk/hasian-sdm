<div class="table-responsive">
  <table class="table table-bordered text-left">
    <thead>
      <tr>
        <td><?= $column_contract_type; ?></td>
        <td><?= $column_contract_start; ?></td>
        <td><?= $column_contract_end; ?></td>
        <td><?= $column_description; ?></td>
        <td><?= $column_date_added; ?></td>
        <td><?= $column_username; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($histories) { ?>
      <?php foreach ($histories as $history) { ?>
      <tr class="<?= !$history['contract_type_id'] ? 'text-warning' : ''; ?>">
        <td><?= $history['contract_type']; ?></td>
        <td><?= $history['contract_start']; ?></td>
        <td><?= $history['contract_end']; ?></td>
        <td><?= $history['description']; ?></td>
        <td><?= $history['date_added']; ?></td>
        <td><?= $history['username']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="8"><?= $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6"><?= $pagination; ?></div>
  <div class="col-sm-6 text-right"><?= $results; ?></div>
</div>
