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
	<div class="navbar-fixed-top">
		<div class="alert alert-success">
			<?= $success; ?> <button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
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
					<div class="col-sm-4"></div>
					<div class="col-sm-4">
						<?php if ($action != 'logout') { ?>
						<button type="button" data-toggle="tooltip" title="<?= $button_login; ?>" class="btn btn-primary btn-xl"
							id="login"><i class="fa fa-sign-in"></i>
							<?= $button_login; ?>
						</button>
						<?php } else { ?>
						<button type="button" data-toggle="tooltip" title="<?= $button_logout; ?>" class="btn btn-warning btn-xl"
							id="login"><i class="fa fa-sign-out"></i>
							<?= $button_logout; ?>
						</button>
						<?php }?>
					</div>

				</div>
			</div>
			<?= $content_bottom; ?>
		</div>
		<div class="navbar-fixed-top"></div>
		<?= $column_right; ?>
	</div>
</div>
<script type="text/javascript">
	let click_ct = 1;
	let alert_fade_out = setTimeout(function () { $('.alert').fadeOut(); }, 3000);
	let location_id;

	function success(position) {
		// navigator.geolocation.clearWatch(location_id);

		$.ajax({
			url: 'index.php?route=account/account/loginProcess',
			type: 'post',
			dataType: 'json',
			data: position,
			crossDomain: false,
			complete: function () {
				$('#login').button('reset');
			},
			success: function (json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['location']) {
					location.reload();
				} else if (json['error']) {
					$('.navbar-fixed-top').html('<div class="alert alert-danger">' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	function error(err) {
		$('.navbar-fixed-top').html('<div class="alert alert-danger"><?= $error_retrieve; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');

		console.error(`ERROR(${err.code}): ${err.message}`);

		// navigator.geolocation.clearWatch(location_id);
	}

	$('#login').on('click', function () {
		if (click_ct == 1) {
			click_ct++;

			$.ajax({
				url: 'index.php?route=account/account/validateLog',
				crossDomain: true,
				beforeSend: function () {
					clearTimeout(alert_fade_out);
					$('.alert').remove();
					$('#login').button('loading');
				},
				success: function (json) {
					if (json['error']) {
						$('#login').button('reset');

						$('.navbar-fixed-top').html('<div class="alert alert-danger">' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						alert_fade_out = setTimeout(function () { $('.alert').fadeOut(); }, 3000);
					}

					if (json['process_verification']) {
						processVerification();
					}

					click_ct = 1;
				},

				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});

	function processVerification() {
		if (navigator.geolocation) {
			const options = {
				enableHighAccuracy: true,
				maximumAge: 5000,
				timeout: Infinity
			};

			id = navigator.geolocation.watchPosition(success, error, options);
			// navigator.geolocation.getCurrentPosition(success, error, options);

		} else {
			$('.navbar-fixed-top').html('<div class="alert alert-danger"><?= $text_unsupport; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');

			alert_fade_out = setTimeout(function () { $('.alert').fadeOut(); }, 3000);
		}

		navigator.geolocation.clearWatch(location_id);
	}
</script>
<?= $footer; ?>