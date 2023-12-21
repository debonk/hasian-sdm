<?php echo $header; ?>
	  <!--Remove after display modul ok -->
	  <div>
	  <a href="<?php echo $main_url; ?>" ><img src="<?php echo $main_image; ?>" alt="" title="<?php echo $info; ?>" class="full-width img-responsive"/></a>
	  </div>
	  <!--End -->
<div class="container">
  <div class="row">
    <?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
	  <?php echo $content_top; ?>
	  <?php echo $content_bottom; ?>
	</div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>