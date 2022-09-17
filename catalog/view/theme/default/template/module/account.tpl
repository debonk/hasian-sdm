<?php if ($logged) { ?>
<div class="list-group">
	<a href="<?= $account; ?>" class="list-group-item">
		<?= $text_account; ?>
	</a>
	<a href="<?= $general; ?>" class="list-group-item">
		<?= $text_general; ?>
	</a>
	<a href="<?= $schedule; ?>" class="list-group-item">
		<?= $text_schedule; ?>
	</a>
	<a href="<?= $payroll_basic; ?>" class="list-group-item">
		<?= $text_payroll_basic; ?>
	</a>
	<a href="<?= $payroll; ?>" class="list-group-item">
		<?= $text_payroll; ?>
	</a>
	<a href="<?= $password; ?>" class="list-group-item">
		<?= $text_password; ?>
	</a>
	<a href="<?= $logout; ?>" class="list-group-item">
		<?= $text_logout; ?>
	</a>
</div>
<?php } ?>