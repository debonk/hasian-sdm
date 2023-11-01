<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-finger" data-toggle="tooltip" title="<?= $button_save; ?>"
					class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?= $back; ?>" data-toggle="tooltip" title="<?= $button_back; ?>" class="btn btn-default"><i
						class="fa fa-reply"></i></a>
			</div>
			<h1>
				<?= $heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?= $breadcrumb['href']; ?>">
						<?= $breadcrumb['text']; ?>
					</a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($error_warning) { ?>
		<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
			<?= $error_warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_edit; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div id="customer-info"></div>
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-finger"
					class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-active_1">
							<?= $entry_active_1; ?>
						</label>
						<div class="col-sm-8">
							<select name="active_finger[1]" id="input-active-1" class="form-control">
								<option value="0">
									<?= $text_none ?>
								</option>
								<?php foreach ($registered_fingers as $registered_finger) { ?>
								<option value="<?= $registered_finger['index']; ?>"
									<?=$registered_finger['index']==$active_fingers[1] ? 'selected' : '' ; ?>>
									<?= $registered_finger['text']; ?>
								</option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="input-active_2">
							<?= $entry_active_2; ?>
						</label>
						<div class="col-sm-8">
							<select name="active_finger[2]" id="input-active-2" class="form-control">
								<option value="0">
									<?= $text_none ?>
								</option>
								<?php foreach ($registered_fingers as $registered_finger) { ?>
								<option value="<?= $registered_finger['index']; ?>"
									<?=$registered_finger['index']==$active_fingers[2] ? 'selected' : '' ; ?>>
									<?= $registered_finger['text']; ?>
								</option>
								<?php } ?>
							</select>
						</div>
					</div>
				</form>
				<fieldset>
					<legend>
						<?= $text_list_finger; ?>
					</legend>
					<div class="table-responsive">
						<table class="table table-bordered table-hover text-left">
							<thead>
								<tr>
									<th>
										<?= $column_finger_index; ?>
									</th>
									<th>
										<?= $column_date_added; ?>
									</th>
									<th>
										<?= $column_username; ?>
									</th>
									<th class="text-right">
										<?= $column_action; ?>
									</th>
								</tr>
							</thead>
							<tbody>
								<?php if (isset($fingers[0])) { ?>
								<tr>
									<td>
										<?= $fingers[0]['text']; ?>
									</td>
									<td>
										<?= $fingers[0]['date_added']; ?>
									</td>
									<td>
										<?= $fingers[0]['username']; ?>
									</td>
									<td class="text-right">
										<button type="button" value="<?= $fingers[0]['index']; ?>"
											id="button-verification<?= $fingers[0]['index']; ?>"
											data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
											title="<?= $button_verification; ?>" class="btn btn-default"><i
												class="fa fa-sign-in"></i></button>
										<button type="button" value="<?= $fingers[0]['index']; ?>"
											id="button-delete<?= $fingers[0]['index']; ?>"
											data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
											title="<?= $button_delete; ?>" class="btn btn-danger"><i
												class="fa fa-trash-o"></i></button>
									</td>
								</tr>
								<?php } ?>
								<?php for ($i = 1; $i < 3; $i++) { ?>
								<tr>
									<th colspan="4">
										<?= $i == 1 ? $column_left_hand : $column_right_hand; ?>
									</th>
								</tr>
								<?php foreach ($fingers[$i] as $finger) { ?>
								<tr>
									<td>
										<?= $finger['text']; ?>
									</td>
									<td id="date_added<?= $finger['index']; ?>">
										<?= $finger['date_added']; ?>
									</td>
									<td id="username<?= $finger['index']; ?>">
										<?= $finger['username']; ?>
									</td>
									<td class="text-right">
										<?php if (!$finger['registered']) { ?>
										<button type="button" value="<?= $finger['index']; ?>"
											id="button-register<?= $finger['index']; ?>"
											data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
											title="<?= $button_register; ?>" class="btn btn-primary"><i
												class="fa fa-barcode"></i></button>
										<?php } else { ?>
										<button type="button" value="<?= $finger['index']; ?>"
											id="button-verification<?= $finger['index']; ?>"
											data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
											title="<?= $button_verification; ?>" class="btn btn-default"><i
												class="fa fa-sign-in"></i></button>
										<button type="button" value="<?= $finger['index']; ?>"
											id="button-delete<?= $finger['index']; ?>"
											data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip"
											title="<?= $button_delete; ?>" class="btn btn-danger"><i
												class="fa fa-trash-o"></i></button>
										<?php } ?>
									</td>
								</tr>
								<?php } ?>
								<?php } ?>
							</tbody>
						</table>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

	$('button[id^=\'button-register\']').on('click', function (e) {
		let node = this;
		$(node).button('loading');
		$('.alert').remove();

		url = 'index.php?route=customer/finger/register&token=<?= $token; ?>&finger_index=' + $(node).val();
		location = url;

		reg_status = 0;

		// try	{
		// timer_register.stop();
		// }
		// catch(err) {
		// console.log('Registration timer has been init');
		// }

		let limit = 12;
		let ct = 1;
		let timeout = 1500;

		timer_register = $.timer(timeout, function () {
			// console.log('Registration checking...');
			getRegisterStatus($(node).val());
			// console.log('Reg status = ' + reg_status);

			if (ct >= limit || reg_status == 1) {
				timer_register.stop();
				// console.log('Registration checking end');

				$(node).button('reset');

				if (ct >= limit && reg_status == 0) {
					alert('<?= $error_register; ?>');
				}

				if (reg_status == 1) {
					html = '<button type="button" value="' + $(node).val() + '" id="button-verification' + $(node).val() + '" data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip" title="<?= $button_verification; ?>" class="btn btn-default"><i class="fa fa-sign-in"></i></button>';
					html += ' <button type="button" value="' + $(node).val() + '" id="button-delete' + $(node).val() + '" data-loading-text="<?= $text_loading; ?>" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';

					$(node).replaceWith(html);
					$('#date_added' + $(node).val()).html(date_added);
					$('#username' + $(node).val()).html(username);
					alert('<?= $text_success_register; ?>');
				}
			}
			ct++;
		});
	});

	function getRegisterStatus(finger_index) {
		$.ajax({
			url: 'index.php?route=customer/finger/getRegisterStatus&token=<?= $token; ?>&finger_index=' + finger_index,
			dataType: 'json',
			success: function (json) {
				if (json['reg_status']) {
					reg_status = 1;
					username = json['username'];
					date_added = json['date_added'];
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	$('td').on('click', 'button[id^=\'button-verification\']', function (e) {
		let node = this;
		$(node).button('loading');
		$('.alert').remove();

		url = 'index.php?route=customer/finger/verification&token=<?= $token; ?>&finger_index=' + $(node).val();
		location = url;

		setTimeout(function () {
			$(node).button('reset');
		}, 1500);
	});

	$('td').on('click', 'button[id^=\'button-delete\']', function (e) {
		if (confirm('<?= $text_confirm; ?>')) {
			let node = this;

			$.ajax({
				url: 'index.php?route=customer/finger/deleteFinger&token=<?= $token; ?>',
				dataType: 'json',
				type: 'post',
				data: 'finger_index=' + $(node).val(),
				crossDomain: false,
				beforeSend: function () {
					$(node).button('loading');
				},
				complete: function () {
					$(node).button('reset');
				},
				success: function (json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['success']) {
						alert(json['success']);

						location.reload();
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	});
</script>
<?= $footer; ?>