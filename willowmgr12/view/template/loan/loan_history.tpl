<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-credit-card"></i> <?= $text_history; ?></h3>
  </div>
  <table class="table">
    <?php if ($loans) { ?>
      <?php foreach ($loans as $loan) { ?>
        <tr>
		  <td style="width: 1%;"><a href="<?= $loan['edit']; ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip" title="<?= $button_edit; ?>" class="btn btn-info btn-xs"><i class="fa fa-pencil fa-fw"></i></a></td>
		  <td class="text-left"><?= $loan['description']; ?></td>
		  <td class="text-right nowrap"><?= $loan['total']; ?></td>
		</tr>
	  <?php } ?>
	<?php } else { ?>
	<tr>
	  <td class="text-center" colspan="3"><?= $text_no_results; ?></td>
	</tr>
	<?php } ?>
  </table>
</div>