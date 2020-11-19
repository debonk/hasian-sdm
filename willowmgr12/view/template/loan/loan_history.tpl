<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-credit-card"></i> <?php echo $text_history; ?></h3>
  </div>
  <table class="table">
    <?php if ($loans) { ?>
      <?php foreach ($loans as $loan) { ?>
        <tr>
		  <td style="width: 1%;"><a href="<?php echo $loan['edit']; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
		  <td class="text-left"><?php echo $loan['description']; ?></td>
		  <td class="text-right nowrap"><?php echo $loan['total']; ?></td>
		</tr>
	  <?php } ?>
	<?php } else { ?>
	<tr>
	  <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
	</tr>
	<?php } ?>
  </table>
</div>