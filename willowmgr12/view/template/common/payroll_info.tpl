<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-money"></i> <?php echo $text_payroll_info; ?></h3>
  </div>
  <table class="table">
	<tr>
	  <td class="text-right" style="width: 50%;"><?php echo $text_net_salary; ?></td>
	  <td class="text-left"><?php echo $net_salary; ?></td>
	</tr>
    <?php foreach ($component_codes as $code) { ?>
	  <tr>
	    <td class="text-right"><?php echo $text_component[$code]; ?></td>
	    <td class="text-left"><?php echo $component[$code]; ?></td>
	  </tr>
	<?php } ?>
	<tr>
	  <td class="text-right"><?php echo $text_grandtotal; ?></td>
	  <td class="text-left"><?php echo $grandtotal; ?></td>
	</tr>
	<tr>
	  <td class="text-right"><?php echo $text_total_customer; ?></td>
	  <td class="text-left"><?php echo $total_customer; ?></td>
	</tr>
  </table>
</div>
