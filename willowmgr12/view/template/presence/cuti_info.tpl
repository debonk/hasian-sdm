<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_period; ?></td>
        <td class="text-right"><?php echo $column_cuti; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($cutis) { ?>
        <?php foreach ($cutis as $cuti) { ?>
          <tr>
	        <td class="text-left"><?php echo $cuti['period']; ?></td>
	        <td class="text-right"><?php echo $cuti['cuti']; ?></td>
          </tr>
        <?php } ?>
        <tr>
	      <td class="text-right text-bold"><?php echo $text_total; ?></td>
	      <td class="text-right text-bold"><?php echo $total_cuti; ?></td>
        </tr>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>