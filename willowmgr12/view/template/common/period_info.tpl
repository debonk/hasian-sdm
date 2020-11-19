<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-calendar"></i> <?php echo $text_period_info; ?></h3>
  </div>
  <table class="table">
    <?php if ($period_info_check) { ?>
	  <tr>
	    <td class="text-right" style="width: 50%;"><?php echo $text_period; ?></td>
	    <td class="text-left"><?php echo $period; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?php echo $text_date_start; ?></td>
	    <td class="text-left"><?php echo $date_start; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?php echo $text_date_end; ?></td>
	    <td class="text-left"><?php echo $date_end; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?php echo $text_payroll_status; ?></td>
        <td class="text-left"><button id="tile-period-status" class="btn btn-primary btn-lg" style="min-width: 50%;"><?php echo $payroll_status; ?></button></td>
	  </tr>
    <?php } else { ?>
    <tr>
	  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </table>
</div>
