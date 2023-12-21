<?= $header; ?>
<div class="container">
	<ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?= $breadcrumb['href']; ?>">
				<?= $breadcrumb['text']; ?>
			</a></li>
		<?php } ?>
	</ul>
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
			<div class="clearfix">
				<div class="pull-right">
					<?php if ($presence_tools) { ?>
					<span class="dropdown">
						<button type="button" id="button-tool" class="btn btn-primary dropdown-toggle"
							data-toggle="dropdown">
							<?= $button_tool; ?> <span class="caret"></span>
						</button>
						<ul class="dropdown-menu dropdown-menu-right">
							<?php foreach ($presence_tools as $presence_tool) { ?>
							<li>
								<a href="<?= $presence_tool['href']; ?>">
									<?= $presence_tool['title']; ?>
								</a>
							</li>
							<?php } ?>
						</ul>
					</span>
					<?php } ?>
					<?php if ($action == 'logout') { ?>
					<a href="<?= $href_login; ?>" type="button" id="button-login" class="btn btn-default">
						<?= $button_login; ?>
					</a>
					<a type="button" id="button-logout" class="btn btn-warning">
						<?= $button_logout; ?>
					</a>
					<?php } else { ?>
					<a type="button" id="button-login" class="btn btn-success">
						<?= $button_login; ?>
					</a>
					<a href="<?= $href_logout; ?>" type="button" id="button-logout" class="btn btn-default">
						<?= $button_logout; ?>
					</a>
					<?php } ?>
				</div>
				<h1>
					<?= $text_list; ?>
				</h1>
				<div class="row">
					<div class="col-md-4 col-sm-6 col-xs-12 pull-right">
						<select id="input-location" class="form-control">
							<option value="0">
								<?= $store_name ?>
							</option>
							<?php foreach ($locations as $location) { ?>
							<?php if ($location['location_id'] == $location_id) { ?>
							<option value="<?= $location['location_id']; ?>" selected="selected">
								<?= $location['name']; ?>
							</option>
							<?php } else { ?>
							<option value="<?= $location['location_id']; ?>">
								<?= $location['name']; ?>
							</option>
							<?php } ?>
							<?php } ?>
						</select>
					</div>
					<div class="col-md-5 col-sm-6 col-xs-12 pull-right">
						<div class="form-group">
							<div class="input-group">
								<input type="text" name="search" value="" placeholder="<?php echo $entry_search; ?>"
									id="input-search" class="form-control" />
								<span class="input-group-btn"><button class="btn btn-default" type="button"
										id="button-clear">
										<?php echo $button_clear; ?>
									</button></span>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="row" id="customer-list">
				<?php if ($error_session) { ?>
				<h3 class="text-center">
					<?= $error_session; ?>
				</h3>
				<?php } else { ?>
				<?php if ($customers) { ?>
				<?php foreach ($customers as $customer) { ?>
				<div class="<?= $presence_card == 'image' ? 'col-md-2 col-sm-3 col-xs-4' : 'col-md-4 col-sm-6 col-xs-12'; ?>"
					id="customer<?= $customer['customer_id']; ?>">
					<div class="tile tile-<?= $customer['log_class']; ?>">
						<?php if ($customer['image']) { ?>
						<div>
							<a href="#" id="scan_index<?= $customer['scan_active'][0]['index']; ?>"
								value="<?= $customer['scan_active'][0]['index']; ?>">
								<div class="tile-body" title="<?= $customer['scan_active'][0]['text']; ?>">
									<img src="<?= $customer['image']; ?>" alt="<?= $customer['name']; ?>"
										title="<?= $customer['scan_active'][0]['text']; ?>" class="img-thumbnail" />
									<h4 class="text-center">
										<?= $customer['name']; ?>
									</h4>
								</div>
							</a>
						</div>
						<?php } else { ?>
						<span class="tile-main">
							<a href="#" id="scan_index<?= $customer['scan_active'][0]['index']; ?>"
								value="<?= $customer['scan_active'][0]['index']; ?>">
								<div class="tile-body" title="<?= $customer['scan_active'][0]['text']; ?>">
									<h3>
										<?= $customer['name']; ?>
									</h3>
								</div>
							</a>
						</span>
						<span class="tile-alt">
							<a href="##" id="scan_index<?=$customer['scan_active'][1]['index']; ?>"
								value="<?= $customer['scan_active'][1]['index']; ?>">
								<div class="tile-body" title="<?= $customer['scan_active'][1]['text']; ?>">
									<h5><i class="fa fa-external-link"></i> Alt
									</h5>
								</div>
							</a>
						</span>
						<?php } ?>
					</div>
				</div>
				<?php } ?>
				<?php } else { ?>
				<h3 class="text-center">
					<?= $text_no_results; ?>
				</h3>
				<?php } ?>
				<?php } ?>
				<?= $content_bottom; ?>
			</div>
			<div id="fixed-alert"></div>
			<?= $column_right; ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('select[id=\'input-location\']').on('change', function () {
		url = 'index.php?route=presence/login&action=<?= $action; ?>';

		let location_id = $('select[id=\'input-location\']').val();

		url += '&location_id=' + location_id;

		location = url;
	});

	$('input[id=\'input-search\']').on('keyup', function () {
		let filter = $('input[id=\'input-search\']').val().toUpperCase();
		let list = $('#customer-list div[id^=\'customer\']');

		for (let i = 0; i < list.length; i++) {
			const customer_name = list[i].getElementsByClassName('tile-main')[0].innerText.toUpperCase();

			if (customer_name.indexOf(filter) > -1) {
				list[i].style.display = '';
				console.log(customer_name);
			} else {
				list[i].style.display = 'none';
			}
		}
	});

	$('button[id=\'button-clear\']').on('click', function () {
		$('input[id=\'input-search\']').val('');

		$('input[id=\'input-search\']').trigger('keyup');
	});
</script>
<script type="text/javascript">
	var log_ct = 1;
	var click_ct = 1;
	let alert_fade_out;
	let home;

	function redirectHome() {
		home = setTimeout(function () { location = 'index.php?route=common/home&action=<?= $action; ?>'; }, 60000);
	}

	redirectHome();

	$('a[id^=\'scan_index\']').on('click', function (e) {
		e.preventDefault();

		if (click_ct == 1) {
			click_ct++;

			let node = this;
			let finger_index = $(node).attr('value');
			let action = '<?= $action; ?>';

			$.ajax({
				url: 'index.php?route=presence/login/validateLog',
				type: 'post',
				dataType: 'json',
				data: 'finger_index=' + finger_index + '&action=' + action,
				crossDomain: true,
				beforeSend: function () {
					clearTimeout(alert_fade_out);
					clearTimeout(home);
					$('.alert').remove();
				},

				success: function (json) {
					redirectHome();

					if (json['error']) {
						$('#fixed-alert').html('<div class="alert fixed-alert alert-danger">' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						click_ct = 1;

						alert_fade_out = setTimeout(function () { $('.alert').fadeOut(); }, 3000);
					}

					if (json['process_verification']) {
						processVerification(finger_index, action);
					}
				},

				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});

	function processVerification(finger_index, action) {
		let button = $('#button-' + action);
		let use_fingerprint = '<?= $use_fingerprint; ?>'

		if (use_fingerprint == 1) {
			url = 'index.php?route=presence/login/verification&finger_index=' + finger_index + '&action=' + action;
			location = url;
		}

		log_status = 0;

		// try	{
		// 	timer_register.stop();
		// }
		// catch(err) {
		// 	console.log('Verification timer has been init');
		// }

		var limit = 22;
		var ct = 1;
		var timeout = 500;

		timer_register = $.timer(timeout, function () {
			let customer_id = finger_index.split('x')[0];
			// console.log('Verification checking... ' + ct);

			getLogStatus(customer_id, action);
			// console.log('Log status = ' + log_status);

			if (ct >= limit || log_status == 1) {
				timer_register.stop();
				// console.log('Verification checking end');

				if (ct >= limit && log_status == 0) {
					if (action == 'login') {
						deleteScheduleTime(customer_id, action);
					}

					$('#fixed-alert').html('<div class="alert fixed-alert alert-danger"><?= $error_verification; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					click_ct = 1;
				}

				if (log_status == 1) {
					$('#customer' + customer_id + ' > div').removeClass().addClass('tile tile-' + action);

					$('#fixed-alert').html('<div class="alert fixed-alert alert-success">' + text_success + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					click_ct = 1;

					if (log_ct >= 5) {
						location.reload();
					}

					log_ct++;
				}

				alert_fade_out = setTimeout(function () { $('.alert').fadeOut(); }, 3000);
			}

			ct++;
		});
	};

	function getLogStatus(customer_id, action) {
		$.ajax({
			url: 'index.php?route=presence/login/getLogStatus&customer_id=' + customer_id + '&action=' + action,
			dataType: 'json',
			crossDomain: true,
			success: function (json) {
				if (json['success']) {
					log_status = 1;
					text_success = json['success'];
				}
			},

			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	function deleteScheduleTime(customer_id, action) {
		$.ajax({
			url: 'index.php?route=presence/login/deleteScheduleTime&customer_id=' + customer_id + '&action=' + action,
			dataType: 'json',
			crossDomain: true,

			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
</script>
<?= $footer; ?>