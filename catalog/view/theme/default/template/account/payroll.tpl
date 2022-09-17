<?= $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?= $breadcrumb['href']; ?>">
				<?= $breadcrumb['text']; ?>
			</a></li>
		<?php } ?>
	</ul>
	<div class="row">
		<?= $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?= $class; ?>">
			<?= $content_top; ?>
			<h1>
				<?= $heading_title; ?>
			</h1>
			<?php if ($payroll_check) { ?>
			<h3>
				<?= $text_period; ?>
			</h3>
			<legend>
				<?= $text_earning; ?>
			</legend>
			<div class="col-sm-1"></div>
			<div class="table-responsive col-sm-11">
				<table class="table table-hover">
					<tbody>
						<tr>
							<td width="50%">
								<?= $text_gaji_pokok; ?>
							</td>
							<td>
								<?= $gaji_pokok; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_tunj_jabatan; ?>
							</td>
							<td>
								<?= $tunj_jabatan; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_tunj_hadir; ?>
							</td>
							<td>
								<?= $tunj_hadir; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_total_uang_makan; ?>
							</td>
							<td>
								<?= $uang_makan ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_tunj_pph; ?>
							</td>
							<td>
								<?= $tunj_pph; ?>
							</td>
						</tr>
						<?php if ($earning_components) { ?>
						<?php foreach ($earning_components as $component) { ?>
						<tr>
							<td>
								<?php echo $component['title']; ?>
							</td>
							<td>
								<?php echo $component['value']; ?>
							</td>
						</tr>
						<?php } ?>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right">
								<h4>
									<?= $text_total_earning; ?>
								</h4>
							</td>
							<td>
								<h4>
									<?= $earning; ?>
								</h4>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<legend>
				<?= $text_deduction; ?>
			</legend>
			<div class="col-sm-1"></div>
			<div class="table-responsive col-sm-11">
				<table class="table table-hover">
					<tbody>
						<tr>
							<td width="50%">
								<?= $text_pot_sakit; ?>
							</td>
							<td>
								<?= $pot_sakit; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_pot_bolos; ?>
							</td>
							<td>
								<?= $pot_bolos; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_pot_tunj_hadir; ?>
							</td>
							<td>
								<?= $pot_tunj_hadir; ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_pot_gaji_pokok; ?>
							</td>
							<td>
								<?= $pot_gaji_pokok ?>
							</td>
						</tr>
						<tr>
							<td>
								<?= $text_pot_terlambat; ?>
							</td>
							<td>
								<?= $pot_terlambat; ?>
							</td>
						</tr>
						<?php if ($deduction_components) { ?>
						<?php foreach ($deduction_components as $component) { ?>
						<tr>
							<td>
								<?php echo $component['title']; ?>
							</td>
							<td>
								<?php echo $component['value']; ?>
							</td>
						</tr>
						<?php } ?>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right">
								<h4>
									<?= $text_total_deduction; ?>
								</h4>
							</td>
							<td>
								<h4>
									<?= $deduction; ?>
								</h4>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<legend>
				<?= $text_grandtotal; ?>
			</legend>
			<div class="col-sm-1"></div>
			<div col-sm-11">
				<h3 class="text-center"><?= $grandtotal; ?></h3>
			</div>
			<hr>
			<?php } else { ?>
				<p><?php echo $text_no_results; ?></p>
			<?php } ?>
			<div class="buttons clearfix">
				<div class="pull-left"><a href="<?= $back; ?>" class="btn btn-default">
						<?= $button_back; ?>
					</a></div>
			</div>
			<?= $content_bottom; ?>
		</div>
		<?= $column_right; ?>
	</div>
</div>
<?= $footer; ?>