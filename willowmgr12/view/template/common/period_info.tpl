<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-calendar"></i> <?= $text_period_info; ?></h3>
  </div>
  <table class="table">
    <?php if ($period_info_check) { ?>
	  <tr>
	    <td class="text-right" style="width: 50%;"><?= $text_period; ?></td>
	    <td class="text-left"><?= $period; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?= $text_date_period; ?></td>
	    <td class="text-left"><?= $date_start . ' - ' . $date_end; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?= $text_payroll_status; ?></td>
      <td class="text-left"><button id="tile-period-status" class="btn btn-primary btn-lg" style="min-width: 50%;"><?= $payroll_status; ?></button></td>
	  </tr>
    <?php if ($shortcuts) { ?>
			<tr>
				<td colspan="2" class="text-center">
				<?php foreach ($shortcuts as $shortcut) { ?>
				<a href="<?= $shortcut['href']; ?>" class="btn btn-info" style="width: 24%;"><?= $shortcut['period']; ?></a>
				<?php } ?>
			</td>
			</tr>
	
			<?php } ?>

    <?php } else { ?>
    <tr>
	  <td class="text-center" colspan="6"><?= $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
