<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_back; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
		<div id="customer-info"></div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_title; ?></td>
                <td class="text-left"><?php echo $column_filename; ?></td>
                <td class="text-left"><?php echo $column_mask; ?></td>
                <td class="text-left"><?php echo $column_date_added; ?></td>
                <td class="text-left"><?php echo $column_username; ?></td>
                <td class="text-right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($documents) { ?>
              <?php foreach ($documents as $document_type_id => $document) { ?>
              <tr>
                <td class="text-left <?php echo $document['required']; ?>"><?php echo $document['title']; ?></td>
                <td class="text-left">
				<?php foreach ($document['filename'] as $filename) { ?>
                <?php echo $filename; ?>
				<?php if ($document['href_path']) { ?>
			    <a href="<?php echo $document['href_path'] . $filename; ?>" target="_blank" data-toggle="tooltip" title="<?php echo $button_view; ?>"> <i class="fa fa-external-link"></i></a><br />
                <?php } ?>
                <?php } ?>
				</td>
                 <td class="text-left">
				<?php foreach ($document['mask'] as $mask) { ?>
                <?php echo $mask . '<br>'; ?>
                <?php } ?>
				</td>
                <td class="text-left"><?php echo $document['date_added']; ?></td>
                <td class="text-left"><?php echo $document['username']; ?></td>
                <td class="text-right">
				  <?php if ($document['href_info']) { ?>
			      <a href="<?php echo $document['href_info']; ?>" type="button" target="_blank" data-toggle="tooltip" title="<?php echo $button_print; ?>" class="btn btn-info"><i class="fa fa-print"></i></a>
				  <?php } ?>
				  <?php if ($document['href_path']) { ?>
			      <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" id="button-delete<?php echo $document_type_id; ?>" value="<?php echo $document_type_id; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
				  <?php } else { ?>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_upload; ?>" id="button-upload<?php echo $document_type_id; ?>" value="<?php echo $document_type_id; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-upload"></i></button>
				  <?php } ?>
				</td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#customer-info').load('index.php?route=common/customer_info&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('button[id^=\'button-upload\']').on('click', function(e) {
	var node = this;
	
	$('#form-upload').remove();
	
	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="files[]" multiple /></form>');

	$('#form-upload input[name=\'files[]\']').trigger('click');
	
	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}
	
	timer = setInterval(function() {
		if ($('#form-upload input[name=\'files[]\']').val() != '') {
			clearInterval(timer);		
			
			$.ajax({
				url: 'index.php?route=customer/document/upload&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&document_type_id='+ $(node).val(),
				type: 'post',		
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,		
				beforeSend: function() {
					$(node).button('loading');
				},
				// complete: function() {
					// $(node).button('reset');
				// },	
				success: function(json) {
					$('.alert').remove();

					if (json['error']) {
						$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
						
						$(node).button('reset');
					}
								
					if (json['success']) {
						location.reload();
					}
				},			
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});

$('button[id^=\'button-delete\']').on('click', function(e) {
	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;
		
		$.ajax({
			url: 'index.php?route=customer/document/delete&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&document_type_id='+ $(node).val(),
			dataType: 'json',
			crossDomain: false,
			beforeSend: function() {
				$(node).button('loading');
			},
			// complete: function() {
				// $(node).button('reset');
			// },
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					
					$(node).button('reset');
				}

				if (json['success']) {
					location.reload();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
 //--></script>
</div>
<?php echo $footer; ?>