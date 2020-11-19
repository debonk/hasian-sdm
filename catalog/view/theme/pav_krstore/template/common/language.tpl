<?php if (count($languages) > 1) { ?>
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-language">
			<div class="btn-group dropdown">
				<ul class="clearfix">
                    <?php foreach ($languages as $language) { ?>
                        <li><button class="language-select btn-link btn-block" type="button" name="<?php echo $language['code']; ?>"><img src="catalog/language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></button></li>
                    <?php } ?>
				</ul>
			</div>
			<input type="hidden" name="code" value="" />
			<input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		</form>
<?php } ?>
