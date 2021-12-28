<?php if ($locations) { ?>
	<h3 class="panel-heading panel-title"><i class="fa fa-th"></i> <?= $text_login_page; ?></h3>
<?php foreach ($locations as $location) { ?>
<div class="col-md-4 col-sm-6">
	<button type="button" value="<?= $location['location_id']; ?>" id="button-login-session<?= $location['location_id']; ?>" data-loading-text="<?= $text_loading; ?>" class="btn btn-warning button-login-session">
		<?= $location['name']; ?>
	</button>
</div>
<?php } ?>
<?php } ?>


<script type="text/javascript">
	$('button[id^=\'button-login-session\']').on('click', function () {
		$.ajax({
			url: 'index.php?route=dashboard/login_session/loginPage&token=<?php echo $token; ?>',
			type: 'post',
      data: 'location_id=' + this.value,
			dataType: 'json',
			crossDomain: false,
			beforeSend: function() {
				$('#button-login-session').button('loading');
			},
			complete: function() {
				$('#button-login-session').button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['redirect']) {
					location = json['redirect'];
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
</script>