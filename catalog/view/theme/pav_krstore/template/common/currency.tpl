<?php if (count($currencies) > 1) { ?>
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-currency">
		<div class="btn-group">
			<ul class="clearfix">
				<?php foreach ($currencies as $currency) { ?>
					<?php if ($currency['symbol_left']) { ?>
						<li><button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left']; ?> <?php echo $currency['title']; ?></button></li>
					<?php } 
					else { ?>
						<li><button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right']; ?> <?php echo $currency['title']; ?></button></li>
					<?php } ?>
				<?php } ?>
			</ul>
		</div>
		<input type="hidden" name="code" value="" />
		<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
	</form>
<?php } ?>