<div class="blog-item">
	<?php if( $cat_show_title ) { ?>
		<div class="image space-padding-tb-20">
			<?php if( $blog['thumb'] && $cat_show_image )  { ?>
				<a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><img src="<?php echo $blog['thumb'];?>" alt="<?php echo $blog['title'];?>" title="<?php echo $blog['title'];?>" class="img-responsive" /></a>
			<?php } ?>
			<div class="blog-meta">
				<ul class="list-inline">
					<li>
						<?php if( $blog_show_author ) { ?>
							<span class="author"><i class="fa fa-pencil-square-o"></i> <?php echo $blog['author'];?></span>
						<?php } ?>
					</li>
					<li>
						<?php if( $blog_show_category ) { ?>
							<span class="publishin">
								<i class="fa fa-paperclip"></i> <a class="color" href="<?php echo $blog['category_link'];?>" title="<?php echo $blog['category_title'];?>"><?php echo $blog['category_title'];?></a>
							</span>
						<?php } ?>
					</li>
					<li>
						<?php if( $blog_show_created ) { ?>
							<span class="created"><i class="fa fa-clock-o"></i> <?php echo $blog['created'];?></span>
						<?php } ?>
					</li>
					<li>
						<?php if( $blog_show_hits ) { ?>
							<span class="hits"><i class="fa fa-eye"></i> <?php echo $blog['hits'];?></span>
						<?php } ?>
					</li>
					<li>
						<?php if( $blog_show_comment_counter ) { ?>
							<span class="comment_count"><i class="fa fa-comment-o"></i> <?php echo $blog['comment_count'];?></span>
						<?php } ?>
					</li>
				</ul>
			</div>
		</div>
		<div class="blog-body">
		<div class="blog-header">
			<h4 class="blog-title"><a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a></h4>
		</div>
	<?php } ?>
		<?php if( $cat_show_description ) {?>
		<div class="description">
			<?php echo utf8_substr( $blog['description'],0, 180 );?>...
		</div>
		<?php } ?>
		<?php if( $cat_show_readmore ) { ?>
		<div class="space-top-15">
			<a href="<?php echo $blog['link'];?>" class="btn-v1 text-link text-uppercase font-size-12"><?php echo $objlang->get('text_readmore');?></a>
		</div>
		<?php } ?>
	</div>			
</div>
