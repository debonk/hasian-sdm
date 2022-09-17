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
								<?= $text_uang_makan; ?>
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
					</tbody>
					<tfoot>
						<tr>
							<td>
								<h4>
									<?= $text_gaji_dasar; ?>
								</h4>
							</td>
							<td>
								<h4>
									<?= $gaji_dasar; ?>
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