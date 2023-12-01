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
			<hr>
			<div class="col-sm-1"></div>
			<div class="table-responsive col-sm-11">
				<table class="table table-hover">
					<tbody>
						<?php foreach ($vacations as $vacation) { ?>
						<tr>
							<td>
								<?= $vacation['date']; ?>
							</td>
							<td>
								<?= $vacation['description']; ?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
					<tfoot>
						<tr>
							<td width="35%">
								<h4>
									<?= $text_vacation; ?>
								</h4>
							</td>
							<td>
								<h4>
									<?= $vacation_count; ?>
								</h4>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
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