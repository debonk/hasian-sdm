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
			<h2>
				<?= $heading_title; ?>
			</h2>
			<?php if ($calendar) { ?>
			<legend>
				<?php echo $text_period; ?>
			</legend>
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<?php foreach ($list_days as $list_day) { ?>
							<td class="text-center table-evenly-7 bg-primary">
								<b><?php echo $list_day; ?></b>
							</td>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php for ($week = 0; $week < $total_week; $week++) { ?>
						<tr>
							<?php for ($day = 0; $day < 7; $day++) { ?>
							<?php if (isset($calendar[$week . $day])) { ?>
							<td class="text-right calendar-day">
								<h4>
									<?php echo $calendar[$week . $day]['text']; ?>
								</h4>
								<div class="text-center">
									<p><i class="fa fa-clock-o"></i>
										<?php echo $calendar[$week . $day]['schedule_type_code']; ?>
									</p>
									<p class="bg-<?php echo $calendar[$week . $day]['bg_class']; ?>">
										<i class="fa fa-sign-in"></i>
										<?php echo $calendar[$week . $day]['time_login'] . ' - ' . $calendar[$week . $day]['time_logout']; ?></br>
										<?php echo $calendar[$week . $day]['presence_status']; ?>
									</p>
								</div>
							</td>
							<?php } else { ?>
							<td class="text-center bg-light-dark"></td>
							<?php } ?>
							<?php } ?>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } else { ?>
			<p>
				<?= $text_no_results; ?>
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