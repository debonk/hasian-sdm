<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?= $column_date_added; ?></td>
        <td class="text-center"><?= $column_loan_id; ?></td>
        <td class="text-left"><?= $column_description; ?></td>
        <td class="text-right"><?= $column_amount; ?></td>
        <td class="text-left"><?= $column_username; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions as $transaction) { ?>
      <tr>
        <td class="text-left"><?= $transaction['date_added']; ?></td>
        <td class="text-center"><?= $transaction['loan_id']; ?></td>
        <td class="text-left"><?= $transaction['description']; ?></td>
        <td class="text-right"><?= $transaction['amount']; ?></td>
        <td class="text-left"><?= $transaction['username']; ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td class="text-right" colspan="3"><b><?= $text_balance; ?></b></td>
        <td class="text-right text-bold"><?= $balance; ?></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="5"><?= $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?= $pagination; ?></div>
  <div class="col-sm-6 text-right"><?= $results; ?></div>
</div>
