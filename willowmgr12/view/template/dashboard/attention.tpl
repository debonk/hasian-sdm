	<?php if ($attentions) { ?>
	<?php foreach ($attentions as $attention) { ?>
	<div class="col-sm-12 alert <?= $attention['alert_class']; ?>"><i class="fa <?= $attention['icon']; ?>"></i>
		<?= $attention['text']; ?>
		<button type="button" class="close" data-dismiss="alert">&times;</button>
	</div>
	<?php } ?>
	<?php } ?>
