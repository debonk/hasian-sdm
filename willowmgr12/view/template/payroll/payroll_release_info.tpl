<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-share-alt"></i>
			<?= $text_release_info; ?>
		</h3>
	</div>
	<table class="table">
		<?php if ($release_data) { ?>
		<tr>
			<td class="text-right" style="width: 50%;">
				<?= $text_fund_date_release; ?>
			</td>
			<td class="text-left">
				<?= $fund_date_release; ?>
			</td>
		</tr>
		<?php foreach ($method_releases as $method_release) { ?>
		<tr>
			<td class="text-right">
				<?= $method_release['method']; ?>
			</td>
			<td class="text-left">
				<?= $method_release['total']; ?>
			</td>
		</tr>
		<?php } ?>
		<?php if ($fund_accounts) { ?>
		<tr>
			<td class="text-center" colspan="2">
				<strong><?= $text_fund_account; ?></strong>
			</td>
		</tr>
		<?php foreach ($fund_accounts as $fund_account) { ?>
		<tr>
			<td class="text-right">
				<?= $fund_account['method']; ?>
			</td>
			<td class="text-left">
				<?= $fund_account['acc_no']; ?>
			</td>
		</tr>
		<?php } ?>
		<?php } ?>
		<?php } else { ?>
		<tr>
			<td class="text-center" colspan="2">
				<?= $text_no_results; ?>
			</td>
		</tr>
		<?php } ?>
	</table>
</div>