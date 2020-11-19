<?php  echo $header; ?> 
<!--Bonk: tp212 -->
<?php require( ThemeControlHelper::getLayoutPath( 'common/config-home.tpl' )  ); ?> 
<?php

	$fullclass = '-full';//isset($themeConfig['home_container_full'])&&$themeConfig['home_container_full']?"-full":""; 
?>

<div class="main-columns container<?php echo $fullclass; ?>">
  	<div class="row">
  		<?php if( $SPAN[0] ): ?>
			<aside id="sidebar-left" class="col-md-<?php echo $SPAN[0];?>">
				<?php echo $column_left; ?>
			</aside>	
		<?php endif; ?> 
	  
	   	<div id="sidebar-main" class="col-md-<?php echo $SPAN[1];?>">
			<div id="content"><?php echo $content_top; ?><?php echo $content_bottom; ?></div>
	   	</div> 
		<?php if( $SPAN[2] ): ?>
			<div id="sidebar-right" class="col-md-<?php echo $SPAN[2];?>">	
				<?php echo $column_right; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
<!--Bonk02-->
<script type="application/ld+json"><?php echo $mark_up_website;?></script>
<script type="application/ld+json"><?php echo $mark_up_organization;?></script>
<?php echo $footer; ?>