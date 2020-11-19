<?php require( PAVO_THEME_DIR."/template/common/config_layout.tpl" );  ?>
<div class="container">
	<ul class="breadcrumb space-30">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
		<?php } ?>
	</ul>
</div>