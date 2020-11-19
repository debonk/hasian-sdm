<?php echo $text_greeting;?>
<?php if ($user_point_status) { ?>
	<div class="user-point">
		<table>
			<?php if ($reward_status) { ?>
				<tr><td class="text-left"><a href="<?php echo $reward; ?>"><?php echo $text_reward;?></a></td><td class="text-right"><?php echo $poin_reward;?></td></tr>
			<?php } ?>
			<?php if ($balance_status) { ?>
				<tr><td class="text-left"><a href="<?php echo $balance; ?>"><?php echo $text_balance;?></a></td><td class="text-right"><?php echo $poin_balance;?></td></tr>
			<?php } ?>
			<?php if ($cashback_status) { ?>
				<tr><td class="text-left"><span data-toggle="tooltip" title="<?php echo $help_cashback; ?>"><?php echo $text_cashback;?></span></td><td class="text-right price"><?php echo $poin_cashback;?></td></tr>
			<?php } ?>
		</table>
	</div>
<?php } ?>
