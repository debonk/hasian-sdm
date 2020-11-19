<?php if( count($testimonials) ) { ?>
	<?php $id = rand(1,10)+rand();?>
	<div class="panel-heading space-15">
		<h4 class="panel-title panel-v1"><?php echo "TESTIMONIALS";?></h4>
	</div>
	<div id="pavtestimonial<?php echo $id;?>" class="carousel slide pavtestimonial">
		<?php if( count($testimonials) > 1 ){ ?>
			<div class="carousel-style carousel-controls-v3">	
				<a class="carousel-control left" href="#pavtestimonial<?php echo $id;?>" data-slide="prev"><i class="fa fa-angle-left ltr"></i><i class="fa fa-angle-right rtl"></i></a>
				<a class="carousel-control right" href="#pavtestimonial<?php echo $id;?>" data-slide="next"><i class="fa fa-angle-left rtl"></i><i class="fa fa-angle-right ltr"></i></a>
			</div>
		<?php } ?>
		<div class="carousel-inner">
			 <?php foreach ($testimonials as $i => $testimonial) {  ?>
				<div class="item text-center <?php if($i==0) {?>active<?php } ?>">
	 				<div class="testimonial-item">
						<?php if(  $testimonial['description'] ) { ?>
							<div class="testimonial description">
								<div><?php echo $testimonial['description']; ?></div>
							</div>
						<?php } ?>
						<div class="t-avatar space-top-20">
							<img  alt="<?php echo strip_tags($testimonial['profile']); ?>" src="<?php echo $testimonial['thumb']; ?>" class="img-circle"/>
						</div>
						<?php if(  $testimonial['profile'] ) { ?>
							<div class="profile"><?php echo $testimonial['profile']; ?></div>
						<?php } ?>
						<?php if( $testimonial['video_link']) { ?>
							<a class="colorbox-t" href="<?php echo $testimonial['video_link'];?>"><?php echo $this->language->get('text_watch_video_testimonial');?></a>
						<?php } ?>
					</div>
				</div>
			<?php } ?>
		</div>
    </div>
	<?php if( count($testimonials) > 1 ){ ?>
		<script type="text/javascript">
			$('#pavtestimonial<?php echo $id;?>').carousel({interval:<?php echo ( $auto_play_mode?$interval:'false') ;?>,auto:<?php echo $auto_play;?>,pause:'hover'});
		</script>
	<?php } ?>
	<script type="text/javascript">
		$(document).ready(function() {
		  $('.colorbox-t').colorbox({iframe:true, innerWidth:640, innerHeight:390});
		});
	</script> 
<?php } ?>
