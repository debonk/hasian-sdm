<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1>
				<?= $heading_delete_older; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?= $breadcrumb['href']; ?>">
						<?= $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?= $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list-alt"></i>
					<?= $heading_delete_older; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<p>
						<?= $text_delete_older_intro; ?>
					</p>
					<p>
						<strong>
							<?= $text_delete_older_cutoff; ?>
						</strong>
					</p>
					<p>
						<strong>
							<?= $text_delete_older_count; ?>
						</strong>
					</p>
				</div>
				<?php if ($uploads) { ?>
				<div class="table-responsive">
					<table class="table table-striped table-hover text-left">
						<thead>
							<tr>
								<td class="text-left">
									<?= $column_name; ?>
								</td>
								<td class="text-left">
									<?= $column_filename; ?>
								</td>
								<td class="text-right">
									<?= $column_size; ?>
								</td>
								<td class="text-right">
									<?= $column_date_added; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($uploads as $upload) { ?>
							<tr>
								<td class="text-left">
									<?= $upload['name']; ?>
								</td>
								<td class="text-left">
									<?= $upload['filename']; ?>
								</td>
								<td class="text-right">
									<?= $upload['size']; ?>
								</td>
								<td class="text-right">
									<?= $upload['date_added']; ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<?php } else { ?>
				<div class="alert alert-info"><i class="fa fa-info-circle"></i>
					<?= $text_delete_older_empty; ?>
				</div>
				<?php } ?>
				<div class="row">
					<div class="col-sm-12 text-right">
						<?php if ($uploads) { ?>
						<form action="<?= $delete_older; ?>" method="post" id="form-upload-delete-older"
							style="display: inline-block;">
							<input type="hidden" name="confirm_delete_older" value="1" />
							<button type="submit" data-toggle="tooltip" title="<?= $button_delete_older; ?>"
								class="btn btn-danger"><i class="fas fa-trash-o"></i>
								<?= $button_delete_older; ?>
							</button>
						</form>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $footer; ?>