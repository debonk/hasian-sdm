<?= $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?= $breadcrumb['href']; ?>">
				<?= $breadcrumb['text']; ?>
			</a></li>
		<?php } ?>
	</ul>
	<?php if ($success) { ?>
	<div class="alert alert-success"><i class="fa fa-check-circle"></i>
		<?= $success; ?>
	</div>
	<?php } ?>
	<div class="row">
		<?= $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
		<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
		<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
		<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?= $class; ?>">
			<?= $content_top; ?>
			<div class="row">
				<div class="col-sm-12">
					<div>
						<button type="button" data-toggle="tooltip" title="<?= $button_login; ?>" class="btn btn-primary"
							id="get-location"><i class="fa fa-map-marker"></i>
							<?= $button_login; ?>
						</button>
						<p id="location"></p>
						<textarea wrap="off" rows="15" readonly class="form-control"><?= $log; ?></textarea>
					</div>
	
				</div>
			</div>
			<?= $content_bottom; ?>
		</div>
		<?= $column_right; ?>
	</div>
</div>
<script type="text/javascript">
	let id;
	// let counter = 0;

	function success(position) {
		// let lat = position.coords.latitude;
		// let long = position.coords.longitude;
		
		// if (counter >= 2) {
		// 	navigator.geolocation.clearWatch(id);
		// } else {
		// 	counter++;
		// }

		navigator.geolocation.clearWatch(id);

		$.ajax({
			url: 'index.php?route=account/account/location',
			type: 'post',
			dataType: 'json',
			// data: 'latitude=' + lat + '&longitude=' + long,
			data: position,
			crossDomain: false,
			beforeSend: function () {
				$('#get-location').button('loading');
			},
			complete: function () {
				$('#get-location').button('reset');
			},
			success: function (json) {
				$('.alert').remove();
				
				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['location']) {
					location.reload();
				} else if (json['error']) {
					$('#content').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	function error(err) {
		console.error(`ERROR(${err.code}): ${err.message}`);

		// if (counter >= 2) {
		// 	navigator.geolocation.clearWatch(id);
		// } else {
		// 	counter++;
		// }

		navigator.geolocation.clearWatch(id);
	}

	$('#get-location').on('click', function () {
		if (navigator.geolocation) {
			const options = {
				enableHighAccuracy: true,
				maximumAge: 5000,
				timeout: Infinity
			};

			id = navigator.geolocation.watchPosition(success, error, options);
			// navigator.geolocation.getCurrentPosition(success, error, options);

		} else {
			$('#content').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?= $text_unsupport; ?></div>');
		}

	});

</script>
<?= $footer; ?>