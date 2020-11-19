<div class="table-responsive">
  <table class="table table-bordered">
	<thead>
	  <tr>
	  <td class="text-left"><?php if ($sort == 'customer') { ?>
		<a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
		<?php } else { ?>
		<a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
		<?php } ?></td>
		<td class="text-left"><?php echo $column_gender; ?></td>
		<td class="text-left"><?php echo $column_marriage_status; ?></td>
	  <td class="text-left"><?php if ($sort == 'customer_group') { ?>
		<a href="<?php echo $sort_customer_group; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer_group; ?></a>
		<?php } else { ?>
		<a href="<?php echo $sort_customer_group; ?>"><?php echo $column_customer_group; ?></a>
		<?php } ?></td>
	  <td class="text-left"><?php echo $column_npwp; ?></td>
	  <td class="text-left"><?php echo $column_npwp_address; ?></td>
	  <td class="text-left"><?php if ($sort == 'location') { ?>
		<a href="<?php echo $sort_location; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_location; ?></a>
		<?php } else { ?>
		<a href="<?php echo $sort_location; ?>"><?php echo $column_location; ?></a>
		<?php } ?></td>
	  <td class="text-right"><?php echo $column_tax_value; ?></td>
	  </tr>
	</thead>
	<tbody>
	  <?php if ($taxes) { ?>
	  <?php foreach ($taxes as $tax) { ?>
	  <tr>
		<td class="text-left"><?php echo $tax['customer']; ?></td>
		<td class="text-left"><?php echo $tax['gender']; ?></td>
		<td class="text-left"><?php echo $tax['marriage_status']; ?></td>
		<td class="text-left"><?php echo $tax['customer_group']; ?></td>
		<td class="text-left"><?php echo $tax['npwp']; ?></td>
		<td class="text-left"><?php echo $tax['npwp_address']; ?></td>
		<td class="text-left"><?php echo $tax['location']; ?></td>
		<td class="text-right"><?php echo $tax['tax_value']; ?></td>
	  </tr>
	  <?php } ?>
	  <?php } else { ?>
	  <tr>
		<td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
	  </tr>
	  <?php } ?>
	</tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
