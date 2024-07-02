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
				<?= $text_addition; ?>
			</legend>
			<div class="col-sm-1"></div>
			<div class="table-responsive col-sm-11">
				<table class="table table-hover text-left">
					<tbody>
						<?php if ($payroll_detail['addition']) { ?>
						<?php foreach ($payroll_detail['addition'] as $component) { ?>
						<tr>
							<td width="50%">
								<?= $component['title']; ?>
							</td>
							<td class="text-right">
								<?= $component['text']; ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right">
								<h4><?= $payroll_detail['total']['addition']['title']; ?></h4>
							</td>
							<td class="text-right">
								<h4><?= $payroll_detail['total']['addition']['text']; ?></h4>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<?php } ?>
			<legend>
				<?= $text_deduction; ?>
			</legend>
			<div class="col-sm-1"></div>
			<div class="table-responsive col-sm-11">
				<table class="table table-hover text-left">
					<tbody>
						<?php if ($payroll_detail['deduction']) { ?>
						<?php foreach ($payroll_detail['deduction'] as $component) { ?>
						<tr>
							<td width="50%">
								<?= $component['title']; ?>
							</td>
							<td class="text-right">
								<?= $component['text']; ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td class="text-right">
								<h4><?= $payroll_detail['total']['deduction']['title']; ?></h4>
							</td>
							<td class="text-right">
								<h4><?= $payroll_detail['total']['deduction']['text']; ?></h4>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
			<?php } ?>
			<legend>
				<?= $text_grandtotal; ?>
			</legend>
			<div class="col-sm-1"></div>
			<div col-sm-11">
				<h3 class="text-center">
					<?= $grandtotal; ?>
				</h3>
			</div>
			<hr>
			<?php } else { ?>
			<p>
				<?php echo $error_no_result; ?>
			</p>
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