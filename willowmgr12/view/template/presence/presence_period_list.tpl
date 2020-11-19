<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
		<?php if ($payroll_status_check) { ?>
		  <a href="<?php echo $edit; ?>" data-toggle="tooltip" title="<?php echo $button_edit_last; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
		  <button type="button" id="button-delete" data-toggle="tooltip" title="<?php echo $button_delete_last; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		<?php } else { ?>
		  <button type="button" data-toggle="tooltip" title="<?php echo $button_edit_last; ?>" class="btn btn-primary disabled"><i class="fa fa-pencil"></i></button>
		  <button type="button" data-toggle="tooltip" title="<?php echo $button_delete_last; ?>" class="btn btn-danger disabled"><i class="fa fa-trash-o"></i></button>
		<?php } ?>
	  </div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php echo $column_period; ?></td>
                  <td class="text-left"><?php echo $column_date_start; ?></td>
                  <td class="text-left"><?php echo $column_date_end; ?></td>
                  <td class="text-left"><?php echo $column_payroll_status; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($presence_periods) { ?>
                <?php foreach ($presence_periods as $presence_period) { ?>
                <tr>
                  <td class="text-left"><?php echo $presence_period['period']; ?></td>
                  <td class="text-left"><?php echo $presence_period['date_start']; ?></td>
                  <td class="text-left"><?php echo $presence_period['date_end']; ?></td>
                  <td class="text-left"><?php echo $presence_period['payroll_status']; ?></td>
                  <td class="text-right">
					<a href="<?php echo $presence_period['schedule']; ?>" data-toggle="tooltip" title="<?php echo $button_schedule; ?>" class="btn btn-info"><i class="fa fa-calendar"></i></a>
					<a href="<?php echo $presence_period['presence']; ?>" data-toggle="tooltip" title="<?php echo $button_presence; ?>" class="btn btn-info"><i class="fa fa-bar-chart-o"></i></a>
				  </td>
                 </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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
  <script type="text/javascript"><!--
$('#button-delete').on('click', function() {
	if (confirm('<?php echo $text_confirm; ?>')) {
		url = 'index.php?route=presence/presence_period/delete&token=<?php echo $token; ?>';
		
		location = url;
	}
});
//--></script> 
</div>
<?php echo $footer; ?>