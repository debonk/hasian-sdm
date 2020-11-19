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
		<div class="col-lg-<?php echo $SPAN[1];?> col-md-<?php echo $SPAN[1];?> col-sm-12 col-xs-12"><div id="content" class="bg-white page-pavblog">

		<?php echo $content_top; ?>
		<div class="clearfix">
			<h1 class="panel-title panel-v2 pull-left space-10"><?php echo $heading_title; ?></h1>
			<!-- Start Div Content -->
			<div class="pav-header text-right pull-right space-10">
				<a href="<?php echo $latest_rss;?>"><span class="fa fa-rss text-warning"></span></a>
			</div>
		</div>
		<div class="pav-filter-blogs">
			<div class="pav-blogs">
				<?php $cols = $cat_columns_leading_blogs;

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
					$cols = !empty($cols)?$cols:1;
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
					
				<div class="pav-pagination pagination space-top-30"><?php echo $pagination;?></div>
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