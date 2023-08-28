<div class="table-responsive">
  <table class="table table-bordered">
	<thead>
	  <tr>
		<td class="text-left" rowspan="2"><?php echo $column_nip; ?></td>
		<td class="text-left" rowspan="2"><?php echo $column_name; ?></td>
		<td class="text-left" rowspan="2"><?php echo $column_customer_group; ?></td>
		<td class="text-left" rowspan="2"><?php echo $column_customer_department; ?></td>
		<td class="text-left" rowspan="2"><?php echo $column_location; ?></td>
		<?php foreach ($titles as $title) { ?>
		  <td class="text-center" colspan="2"><?php echo $title; ?></td>
		<?php } ?>
	  </tr>
	  <tr>
		<?php foreach ($titles as $title) { ?>
		  <td class="text-right"><?php echo $column_company; ?></td>
		  <td class="text-right"><?php echo $column_customer; ?></td>
		<?php } ?>
	  </tr>
	</thead>
	<tbody>
	  <?php if ($customer_count) { ?>
	  <?php foreach ($insurances as $insurance) { ?>
	  <tr>
		<td class="text-left"><?php echo $insurance['nip']; ?></td>
		<td class="text-left"><?php echo $insurance['name']; ?></td>
		<td class="text-left"><?php echo $insurance['customer_group']; ?></td>
		<td class="text-left"><?php echo $insurance['customer_department']; ?></td>
		<td class="text-left"><?php echo $insurance['location']; ?></td>
		<?php foreach ($titles as $title) { ?>
		  <td class="text-right"><?php echo $insurance['insurances_data'][$title][1]; ?></td>
		  <td class="text-right"><?php echo $insurance['insurances_data'][$title][0]; ?></td>
		<?php } ?>
	  </tr>
	  <?php } ?>
        <tr>
          <td class="text-right" colspan="3"><b><?php echo $text_total; ?></b></td>
		  <?php foreach ($titles as $title) { ?>
		    <td class="text-right text-bold"><?php echo $insurances_total[$title][1]; ?></td>
		    <td class="text-right text-bold"><?php echo $insurances_total[$title][0]; ?></td>
		  <?php } ?>
        </tr>
	  <?php } else { ?>
	  <tr>
		<td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
	  </tr>
	  <?php } ?>
	</tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6"></div>
  <div class="col-sm-6 text-right"><?php echo $result_count; ?></div>
</div>
