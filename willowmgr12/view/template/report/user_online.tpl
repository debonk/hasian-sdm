<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list-alt"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-ip"><?php echo $entry_ip; ?></label>
                <input type="text" name="filter_ip" value="<?php echo $filter_ip; ?>" id="input-ip" placeholder="<?php echo $entry_ip; ?>" i class="form-control" />
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label class="control-label" for="input-user"><?php echo $entry_user; ?></label>
                <input type="text" name="filter_user" value="<?php echo $filter_user; ?>" placeholder="<?php echo $entry_user; ?>" id="input-user" class="form-control" />
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-striped table-hover text-left">
            <thead>
              <tr>
                <td><?php echo $column_ip; ?></td>
                <td><?php echo $column_user; ?></td>
                <td><?php echo $column_url; ?></td>
                <td><?php echo $column_referer; ?></td>
                <td><?php echo $column_date_added; ?></td>
                <td class="text-right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($users) { ?>
              <?php foreach ($users as $user) { ?>
              <tr>
                <td><a href="http://whatismyipaddress.com/ip/<?php echo $user['ip']; ?>" target="_blank" rel="noopener noreferrer"><?php echo $user['ip']; ?></a></td>
                <td><?php echo $user['user']; ?></td>
                <td><a href="<?php echo $user['url']; ?>" target="_blank" rel="noopener noreferrer"><?php echo implode('<br/>', str_split($user['url'], 65)); ?></a></td>
                <td><?php if ($user['referer']) { ?>
                  <a href="<?php echo $user['referer']; ?>" target="_blank" rel="noopener noreferrer"><?php echo implode('<br/>', str_split($user['referer'], 65)); ?></a>
                  <?php } ?></td>
                <td><?php echo $user['date_added']; ?></td>
                <td class="text-right"><?php if ($user['user_id']) { ?>
                  <a href="<?php echo $user['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pen"></i></a>
                  <?php } else { ?>
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary" disabled="disabled"><i class="fa fa-pen"></i></button>
                  <?php } ?></td>
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
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/user_online&token=<?php echo $token; ?>';

	let filter_user = $('input[name=\'filter_user\']').val();

	if (filter_user) {
		url += '&filter_user=' + encodeURIComponent(filter_user);
	}

	let filter_ip = $('input[name=\'filter_ip\']').val();

	if (filter_ip) {
		url += '&filter_ip=' + encodeURIComponent(filter_ip);
	}

	location = url;
});

$(document).keypress(function (e) {
	if (e.which == 13) {
			$("#button-filter").click();
		}
	});
</script></div>
<?php echo $footer; ?>
