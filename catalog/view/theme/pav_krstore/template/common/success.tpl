<?php echo $header; ?>
<div class="container">
	<ul class="breadcrumb space-30">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
		<div class="panel-heading space-25">
			<h1 class="panel-title panel-v1"><?php echo $heading_title; ?></h1>
		</div>
      <?php echo $text_message; ?>
      <div class="buttons space-top-20 space-30 clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary btn-v1"><?php echo $button_continue; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>