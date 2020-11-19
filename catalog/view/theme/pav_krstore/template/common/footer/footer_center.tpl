<div class="<?php echo str_replace('_','-',$blockid); ?> <?php echo $blockcls;?>" id="pavo-<?php echo str_replace('_','-',$blockid); ?>">
	<div class="container">
		<div class="row">
			<div class="box-newsletter text-center space-top-55 col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<?php
					echo $helper->renderModule('pavnewsletter');
				?>
				<?php
					if($content=$helper->getLangConfig('widget_social_network')){
						echo $content;
					}
				?>
			</div>
		</div>
	</div>
</div>