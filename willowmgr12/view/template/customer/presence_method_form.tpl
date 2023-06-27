<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<a href="<?= $back; ?>" data-toggle="tooltip" title="<?= $button_back; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_edit; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div id="customer-info"></div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover text-left">
						<thead>
							<tr>
								<td>
									<?= $column_presence_method; ?>
								</td>
								<td class="text-center">
									<?= $column_status; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php if ($presence_methods) { ?>
							<?php foreach ($presence_methods as $presence_method_type_id => $presence_method_list) { ?>
							<?php foreach ($presence_method_list['presence_method_data'] as $presence_method) { ?>
							<tr>
								<td class="text-left <?= $presence_method_list['required']; ?>">
									<?= $presence_method_list['title']; ?>
								</td>
								<td>
									<?= $presence_method['mask']; ?>
								</td>
								<td>
									<?php if ($presence_method['href_view']) { ?>
									<a href="<?= $presence_method['href_view']; ?>" target="_blank" rel="noopener noreferrer" data-toggle="tooltip"
										title="<?= $button_view; ?>">
										<?= $presence_method['filename']; ?>
									</a>
									<?php } else { ?>
									<?= $presence_method['filename']; ?>
									<?php if ($presence_method['missing']) { ?>
									<cite class="text-danger">
										<?= $text_missing; ?>
									</cite>
									<?php } ?>
									<?php } ?>
								</td>
								<td>
									<?= $presence_method['date_added']; ?>
								</td>
								<td>
									<?= $presence_method['username']; ?>
								</td>
								<td class="text-right">
									<?php if ($presence_method_list['href_info']) { ?>
									<a href="<?= $presence_method['href_info']; ?>" type="button" target="_blank" rel="noopener noreferrer" data-toggle="tooltip"
										title="<?= $button_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a>
									<?php } ?>
									<?php if ($presence_method['filename'] != '-') { ?>
									<button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>"
										id="button-delete<?= $presence_method['presence_method_id']; ?>"
										value="<?= $presence_method['presence_method_id']; ?>" data-loading-text="<?= $text_loading; ?>"
										class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
									<?php } else { ?>
									<button type="button" data-toggle="tooltip" title="<?= $button_upload; ?>"
										id="button-upload<?= $presence_method_type_id; ?>" value="<?= $presence_method_type_id; ?>"
										data-loading-text="<?= $text_loading; ?>" class="btn btn-primary"><i
											class="fa fa-upload"></i></button>
									<?php } ?>
								</td>
							</tr>
							<?php } ?>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="6">
									<?= $text_no_results; ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('#customer-info').load('index.php?route=common/customer_info&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>');

		$('button[id^=\'button-upload\']').on('click', function (e) {
			var node = this;

			$('#form-upload').remove();

			$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="files[]" multiple /></form>');

			$('#form-upload input[name=\'files[]\']').trigger('click');

			if (typeof timer != 'undefined') {
				clearInterval(timer);
			}

			timer = setInterval(function () {
				if ($('#form-upload input[name=\'files[]\']').val() != '') {
					clearInterval(timer);

					$.ajax({
						url: 'index.php?route=customer/presence_method/upload&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>&presence_method_type_id=' + $(node).val(),
						type: 'post',
						dataType: 'json',
						data: new FormData($('#form-upload')[0]),
						cache: false,
						contentType: false,
						processData: false,
						beforeSend: function () {
							$(node).button('loading');
						},
						success: function (json) {
							$('.alert').remove();

							if (json['error']) {
								$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

								$(node).button('reset');
							}

							if (json['success']) {
								location.reload();
							}
						},
						error: function (xhr, ajaxOptions, thrownError) {
							alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
						}
					});
				}
			}, 500);
		});

		$('button[id^=\'button-delete\']').on('click', function (e) {
			if (confirm('<?= $text_confirm; ?>')) {
				var node = this;

				$.ajax({
					url: 'index.php?route=customer/presence_method/delete&token=<?= $token; ?>&customer_id=<?= $customer_id; ?>&presence_method_id=' + $(node).val(),
					dataType: 'json',
					crossDomain: false,
					beforeSend: function () {
						$(node).button('loading');
					},
					success: function (json) {
						$('.alert').remove();

						if (json['error']) {
							$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

							$(node).button('reset');
						}

						if (json['success']) {
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
</div>
<?= $footer; ?>