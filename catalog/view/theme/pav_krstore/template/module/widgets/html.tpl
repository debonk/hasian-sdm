<div class="widget-html text-center box <?php echo $addition_cls ?>  <?php if( isset($stylecls)&&$stylecls ) { ?>box-<?php echo $stylecls;?><?php } ?>">
	<div class="body-wg-html">
		<?php if( $show_title ) { ?>
		<div class="panel-heading bg-panel">
			<h4 class="panel-title panel-v1"><?php echo $heading_title?></h4>
		</div>
		<?php } ?>
		 <?php echo htmlspecialchars_decode( $html ); ?>
	</div>
</div>
