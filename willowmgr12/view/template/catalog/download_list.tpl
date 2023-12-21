<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>"
          class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?= $button_delete; ?>" class="btn btn-danger"
          onclick="confirm('<?= $text_confirm; ?>') ? $('#form-download').submit() : false;"><i
            class="fa fa-trash-o"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-list"></i>
          <?= $text_list; ?>
        </h3>
      </div>
      <div class="panel-body">
        <form action="<?= $delete; ?>" method="post" enctype="multipart/form-data" id="form-download">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox"
                      onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left">
                    <?php if ($sort == 'dd.name') { ?>
                    <a href="<?= $sort_name; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_name; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_name; ?>">
                      <?= $column_name; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-right">
                    <?php if ($sort == 'd.date_added') { ?>
                    <a href="<?= $sort_date_added; ?>" class="<?= strtolower($order); ?>">
                      <?= $column_date_added; ?>
                    </a>
                    <?php } else { ?>
                    <a href="<?= $sort_date_added; ?>">
                      <?= $column_date_added; ?>
                    </a>
                    <?php } ?>
                  </td>
                  <td class="text-right">
                    <?= $column_action; ?>
                  </td>
                </tr>
              </thead>
              <tbody>
                <?php if ($downloads) { ?>
                <?php foreach ($downloads as $download) { ?>
                <tr>
                  <td class="text-center">
                    <?php if (in_array($download['download_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?= $download['download_id']; ?>"
                      checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?= $download['download_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-left">
                    <?= $download['name']; ?>
                  </td>
                  <td class="text-right">
                    <?= $download['date_added']; ?>
                  </td>
                  <td class="text-right">
                    <a href="<?= $download['href']; ?>" data-toggle="tooltip" title="<?= $button_download; ?>"
                      class="btn btn-warning"><i class="fa fa-cloud-download"></i></a>
                    <a href="<?= $download['edit']; ?>" data-toggle="tooltip" title="<?= $button_edit; ?>"
                      class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
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
        </form>
        <div class="row">
          <div class="col-sm-6 text-left">
            <?= $pagination; ?>
          </div>
          <div class="col-sm-6 text-right">
            <?= $results; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?>