<div class="table-responsive">
  <table class="table table-bordered text-right">
    <thead>
      <tr>
        <td class="text-left"><?= $column_date_added; ?></td>
        <td><?= $column_gaji_pokok; ?></td>
        <td><?= $column_tunj_jabatan; ?></td>
        <td><?= $column_tunj_hadir; ?></td>
        <td><?= $column_uang_makan; ?></td>
        <td><?= $column_tunj_pph; ?></td>
        <td><?= $column_gaji_dasar; ?></td>
        <td class="text-left"><?= $column_status; ?></td>
        <td class="text-left"><?= $column_username; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($histories) { ?>
      <?php foreach ($histories as $history) { ?>
      <tr class="<?= $history['bg_class']; ?>">
        <td class="text-left"><?= $history['date_added']; ?></td>
        <td><?= $history['gaji_pokok']; ?></td>
        <td><?= $history['tunj_jabatan']; ?></td>
        <td><?= $history['tunj_hadir']; ?></td>
        <td><?= $history['uang_makan']; ?></td>
        <td><?= $history['tunj_pph']; ?></td>
        <td class="text-warning"><?= $history['gaji_dasar']; ?></td>
        <td class="text-left"><?= $history['status']; ?></td>
        <td class="text-left"><?= $history['username']; ?></td>
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
