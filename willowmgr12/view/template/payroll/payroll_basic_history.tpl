<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-right"><?php echo $column_gaji_pokok; ?></td>
        <td class="text-right"><?php echo $column_tunj_jabatan; ?></td>
        <td class="text-right"><?php echo $column_tunj_hadir; ?></td>
        <td class="text-right"><?php echo $column_tunj_pph; ?></td>
        <td class="text-right"><?php echo $column_uang_makan; ?></td>
        <td class="text-right"><?php echo $column_gaji_dasar; ?></td>
        <td class="text-left"><?php echo $column_username; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($histories) { ?>
      <?php foreach ($histories as $history) { ?>
      <tr>
        <td class="text-left"><?php echo $history['date_added']; ?></td>
        <td class="text-right"><?php echo $history['gaji_pokok']; ?></td>
        <td class="text-right"><?php echo $history['tunj_jabatan']; ?></td>
        <td class="text-right"><?php echo $history['tunj_hadir']; ?></td>
        <td class="text-right"><?php echo $history['tunj_pph']; ?></td>
        <td class="text-right"><?php echo $history['uang_makan']; ?></td>
        <td class="text-right text-warning"><?php echo $history['gaji_dasar']; ?></td>
        <td class="text-left"><?php echo $history['username']; ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
