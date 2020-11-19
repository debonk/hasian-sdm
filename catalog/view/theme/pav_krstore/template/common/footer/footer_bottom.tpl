<div class="<?php echo str_replace('_','-',$blockid); ?> <?php echo $blockcls;?>" id="pavo-<?php echo str_replace('_','-',$blockid); ?>">
	<div class="container">
		<div class="row">
			<div class="col-left col-lg-3 col-md-3 col-sm-4 col-xs-12">
<!--				<div class="logo-footer space-15 space-top-15">
					<img src="catalog/view/theme/pav_krstore/image/logo.png" title="" alt="" />
				</div>
-->				<div class="space-15 space-top-15">
					<?php
						if($content=$helper->getLangConfig('widget_about_us')){
							echo $content;
						}
					?>
				</div>
				<div class="space-15 space-top-15">
					<?php
						if($content=$helper->getLangConfig('widget_business_hours')){
							echo $content;
						}
					?>
				</div>
			</div>
			<div class="col-right col-lg-9 col-md-9 col-sm-8 col-xs-12">
				<div class="row">
					<?php if ($informations) { ?>
						<div class="column col-lg-6 col-md-6 col-sm-6 col-xs-12 space-15 space-top-15">
							<div class="panel-heading">
								<h4 class="panel-title"><?php echo $text_information; ?></h4>
							</div>
							<ul class="list-unstyled">
								<?php foreach ($informations as $information) { ?>
									<li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
								<?php } ?>
								<li><a href="<?php echo $blogs; ?>"><?php echo $text_blogs; ?></a></li> <!-- Bonk: Blog Button -->
							</ul>
						</div>
					<?php } ?>
					<div class="column col-lg-6 col-md-6 col-sm-6 col-xs-12 space-15 space-top-15">
						<div class="panel-heading">
							<h4 class="panel-title"><?php echo $text_service; ?></h4>
						</div>
						<ul class="list-unstyled">
							<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
							<li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
						</ul>
					</div>
				</div>
				<div class="copyright col-lg-12 col-md-12 col-sm-12 col-xs-12 clearfix space-top-10">
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
							<p>
								<?php if( $helper->getConfig('enable_custom_copyright', 0) ) { ?>
									<?php echo html_entity_decode($helper->getConfig('copyright')); ?>
								<?php } 
								else { ?>
									<?php echo $powered; ?>. 
								<?php } ?>
							</p>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
						
						<!-- Bonk: Social Icon List -->
						    <div class="list-inline">
								<a href="<?php echo $link_instagram; ?>" target="_blank" rel="nofollow"><i class="user-social-icon-list instagram"><i class="fa fa-instagram"></i></i></a>
								<a href="<?php echo $link_facebook; ?>" target="_blank" rel="nofollow"><i class="user-social-icon-list facebook"><i class="fa fa-facebook"></i></i></a>
								<a href="<?php echo $link_twitter; ?>" target="_blank" rel="nofollow"><i class="user-social-icon-list twitter"><i class="fa fa-twitter"></i></i></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
