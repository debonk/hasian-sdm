<?php
	$objlang = $this->registry->get('language');
	$config = $this->registry->get('config');
?>
<?php if( !empty($blogs) ) { ?>
<div id="blog-carousel" class="widget-blogs owl-carousel-play latest-posts latest-posts-v1 panel-default <?php echo $addition_cls?> <?php if ( isset($stylecls)&&$stylecls) { ?>box-<?php echo $stylecls; ?><?php } ?>">
	<?php if( $show_title ) { ?>
		<div class="panel-heading bg-panel text-center space-10">
			<h4 class="panel-title panel-v1"><?php echo $heading_title?></h4>
		</div>
	<?php } ?>
	<?php if( count($blogs) > $itemsperpage ) { ?>
			<div class="carousel-style carousel-controls-v2">
				<a class="carousel-control left" href="#product_list"   data-slide="prev"><i class="fa fa-angle-left ltr"></i><i class="fa fa-angle-right rtl"></i></a>
				<a class="carousel-control right" href="#product_list"  data-slide="next"><i class="fa fa-angle-left rtl"></i><i class="fa fa-angle-right ltr"></i></a>
			</div>
	<?php } ?>
	<div class="carousel-inner owl-carousel" data-show="1" data-pagination="false" data-navigation="true">
		<?php  $pages = array_chunk( $blogs, $itemsperpage); $span = 12/$cols; ?>
			<?php foreach ($pages as  $k => $tblogs ) {   ?>
				<div class="item <?php if($k==0) {?>active<?php } ?>">
					<?php foreach( $tblogs as $i => $blog ) {  $i=$i+1;?>
						<?php if( $i%$cols == 1 ) { ?>
							<div class="row products-row">
						<?php } ?>
							<div class="space-blog space-top-15 col-lg-12 col-md-12 col-sm-12">
					  			<div class="blog-item clearfix">
									<div class="latest-posts-meta">
										<span class="created">
											<i class="fa fa-calendar-o"></i>
											<span class="day"><?php echo date("d",strtotime($blog['created']));?></span>
											<span class="month"><?php echo date("M",strtotime($blog['created']));?></span>
										</span>
										<span class="comment_count"><?php echo "<i class='fa fa-comments-o'></i> "."".$blog['comment_count'];?></span>
									</div>
									<div class="latest-posts-body">
										<h4 class="latest-posts-title">
											<a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a>
										</h4>
									</div>
								</div>
							</div>
					  	<?php if( $i%$cols == 0 || $i==count($tblogs) ) { ?>
						</div>
						<?php } ?>
					<?php } ?>
				</div>
			<?php } ?>
	</div>
</div>
<?php } ?>