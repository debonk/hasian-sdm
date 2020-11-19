<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-clock-o"></i> <?php echo $heading_title; ?></h3>
  </div>
  <ul class="list-group">
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <li class="list-group-item"><?php echo $history['comment']; ?><br />
      <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $history['date_added']; ?></small></li>
    <?php } ?>
    <?php } else { ?>
    <li class="list-group-item text-center"><?php echo $text_no_results; ?></li>
    <?php } ?>
  </ul>
</div>