<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-bar-chart-o"></i>
			<?= $text_sum_presence; ?>
		</h3>
	</div>
	<div class="panel-body">
		<div class="table-responsive">
			<table class="table table-bordered text-center">
				<thead>
					<tr>
						<?php foreach (array_keys($presence_summary_total) as $code) { ?>
						<td <?='style="width:' . $presence_summary_total_width . ';"' ; ?>>
							<?= utf8_strtoupper($code); ?>
						</td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php foreach (array_values($presence_summary_total) as $value) { ?>
						<td>
							<?= $value; ?>
						</td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="table-responsive">
			<table class="table table-bordered text-center">
				<thead>
					<tr>
						<?php foreach (array_keys($late_summary_total) as $code) { ?>
						<td class="table-evenly-3">
							<?= utf8_strtoupper($code); ?>
						</td>
						<?php } ?>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php foreach (array_values($late_summary_total) as $value) { ?>
						<td>
							<?= $value; ?>
						</td>
						<?php } ?>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>