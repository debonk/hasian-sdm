<?php  echo $header; ?>
<?php require( ThemeControlHelper::getLayoutPath( 'common/mass-header.tpl' )  ); ?>

<div class="main-columns container">
<div class="container-inside">
	<div class="row">
		<?php if( $SPAN[0] ): ?>
		<aside id="sidebar-left" class="col-md-<?php echo $SPAN[0];?>">
		  <?php echo $column_left; ?>
		</aside>	
	    <?php endif; ?> 
		<div class="col-md-<?php echo $SPAN[1];?>"><div id="content" class="bg-white page-pavblog">
		<?php echo $content_top; ?>
		<!-- Start Div Content -->
		<div class="clearfix">
			<h1 class="panel-title panel-v2 pull-left space-10 color-themes"><?php echo $heading_title; ?></h1>
			<div class="pav-header text-right pull-right space-10">
				<a href="<?php echo $category_rss;?>"><span class="fa fa-rss text-warning"></span></a>	
			</div>
		</div>
		<div class="pav-category">
				<?php if( !empty($children) ) { ?>
				<div class="pav-children clearfix">
					<h4><?php echo $objlang->get('text_children')."".":";?></h4>
					<div class="children-wrap">
						<?php 
						$cols = (int)$children_columns;
						foreach( $children as $key => $sub )  { $key = $key + 1;?>
							<div class="pavcol<?php echo $cols;?>">
								<div class="children-inner">
									<h5>
										<a href="<?php echo $sub['link']; ?>" title="<?php echo $sub['title']; ?>"><?php echo $sub['title']; ?> (<?php echo $sub['count_blogs']; ?>)</a> 
									</h5>
								</div>
							</div>
							<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
								<div class="clearfix"></div>
							<?php } ?>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<div class="pav-blogs">
					<?php
					$cols = $cat_columns_leading_blog;
					if( count($leading_blogs) ) { ?>
						<div class="leading-blogs clearfix">
							<?php foreach( $leading_blogs as $key => $blog ) { $key = $key + 1;?>
							<div class="pavcol<?php echo $cols;?>">
							<?php require( '_item.tpl' ); ?>
							</div>
							<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
								<div class="clearfix"></div>
							<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>

					<?php
						$cols = $cat_columns_secondary_blogs;
						if ( count($secondary_blogs) ) { ?>
						<div class="secondary clearfix">
							
							<?php foreach( $secondary_blogs as $key => $blog ) {  $key = $key+1; ?>
							<div class="pavcol<?php echo $cols;?>">
							<?php require( '_item.tpl' ); ?>
							</div>
							<?php if( ( $key%$cols==0 || $cols == count($leading_blogs)) ){ ?>
								<div class="clearfix"></div>
							<?php } ?>
							<?php } ?>
						</div>
					<?php } ?>
					<?php if( $total ) { ?>
					<div class="pav-pagination pagination space-top-30"><?php echo $pagination;?></div>
					<?php } ?>
				</div>
		</div>
		<!-- End Div Content -->
		<?php echo $content_bottom; ?></div>
	</div>
		<!-- End Div Row -->
		<?php echo $column_right; ?>
	</div>
</div>
</div><!-- End Div Container -->
<?php echo $footer; ?>