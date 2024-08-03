<?= $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?= $breadcrumb['href']; ?>">
				<?= $breadcrumb['text']; ?>
			</a></li>
		<?php } ?>
	</ul>
	<?php if ($success) { ?>
	<div class="navbar-fixed-top">
		<div class="alert alert-success">
			<?= $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
	</div>
	<?php } ?>
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
			<!-- <div class="row"> -->
				<!-- <div class="col-sm-12"> -->
					<?php if ($warning) { ?>
					<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
						<?= $warning; ?>
					</div>
					<?php } ?>
				<!-- </div> -->
			<!-- </div> -->
			<?= $content_bottom; ?>
		</div>
		<div class="navbar-fixed-top"></div>
		<?= $column_right; ?>
	</div>
</div>
<?= $footer; ?>