<legend><?php echo $text_payroll_calculation; ?></legend>
<div class="row">
  <div class="col-md-6">
	<table class="table table-bordered">
	  <thead>
		<tr>
		  <td class="text-left" colspan="2"><?php echo $column_earning; ?></td>
		</tr>
	  </thead>
	  <tbody>
		<?php if ($payroll_basic_check && $presence_summary_check) { ?>
		  <tr>
			<td class="text-left"><?php echo $text_gaji_pokok; ?></td>
			<td class="text-right"><?php echo $gaji_pokok; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_tunj_jabatan; ?></td>
			<td class="text-right"><?php echo $tunj_jabatan; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_tunj_hadir; ?></td>
			<td class="text-right"><?php echo $tunj_hadir; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_tunj_pph; ?></td>
			<td class="text-right"><?php echo $tunj_pph; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_total_uang_makan; ?></td>
			<td class="text-right"><?php echo $total_uang_makan; ?></td>
		  </tr>
		  <?php if ($earning_components) { ?>
			<?php foreach ($earning_components as $component) { ?>
			  <tr>
				<td class="text-left"><?php echo $component['title']; ?></td>
				<td class="text-right"><?php echo $component['value']; ?></td>
			  </tr>
			<?php } ?>
		  <?php } ?>
		  <tr>
			<td class="text-right"><?php echo $text_total_earning; ?></td>
			<td class="text-right"><?php echo $earning; ?></td>
		  </tr>
		<?php } else { ?>
		  <tr>
			<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
		  </tr>
		<?php } ?>
	  </tbody>
	</table>
  </div>
  <div class="col-md-6">
	<table class="table table-bordered">
	  <thead>
		<tr>
		  <td class="text-left" colspan="2"><?php echo $column_deduction; ?></td>
		</tr>
	  </thead>
	  <tbody>
		<?php if ($payroll_basic_check && $presence_summary_check) { ?>
		  <tr>
			<td class="text-left"><?php echo $text_pot_sakit; ?></td>
			<td class="text-right text-danger"><?php echo $pot_sakit; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_pot_bolos; ?></td>
			<td class="text-right text-danger"><?php echo $pot_bolos; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_pot_tunj_hadir; ?></td>
			<td class="text-right text-danger"><?php echo $pot_tunj_hadir; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_pot_gaji_pokok; ?></td>
			<td class="text-right text-danger"><?php echo $pot_gaji_pokok; ?></td>
		  </tr>
		  <tr>
			<td class="text-left"><?php echo $text_pot_terlambat; ?></td>
			<td class="text-right text-danger"><?php echo $pot_terlambat; ?></td>
		  </tr>
		  <?php if ($deduction_components) { ?>
			<?php foreach ($deduction_components as $component) { ?>
			  <tr>
				<td class="text-left"><?php echo $component['title']; ?></td>
				<td class="text-right text-danger"><?php echo $component['value']; ?></td>
			  </tr>
			<?php } ?>
		  <?php } ?>
		  <tr>
			<td class="text-right"><?php echo $text_total_deduction; ?></td>
			<td class="text-right text-danger"><?php echo $deduction; ?></td>
		  </tr>
		  <tr>
			<td class="text-right text-bold"><?php echo $text_grandtotal; ?></td>
			<td class="text-right text-bold"><?php echo $grandtotal; ?></td>
		  </tr>
		<?php } else { ?>
		  <tr>
			<td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
		  </tr>
		<?php } ?>
	  </tbody>
	</table>
  </div>
</div>
