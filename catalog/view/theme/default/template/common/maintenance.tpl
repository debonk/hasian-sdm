<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div class="">
	  <h1 class="text-center"><?php echo $message; ?></h1>
	</div>
    <?php if ($items) { ?>
    <?php foreach ($items as $item) { ?>
      <div class="col-sm-4">
	    <h3><a href="<?php echo $item['href']; ?>" rel="nofollow"><img class="img-responsive" src="<?php echo $item['image']; ?>"></a></h3>
	  </div>
    <?php } ?>
    <?php } ?>
  </div>
</div>
<?php echo $footer; ?>