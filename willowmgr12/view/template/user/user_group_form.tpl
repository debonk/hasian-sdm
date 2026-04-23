<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user-group" data-toggle="tooltip" title="<?= $button_save; ?>"
          class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>
          <?= $text_form; ?>
        </h3>
      </div>
      <div class="panel-body">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-user-group"
          class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name">
              <?= $entry_name; ?>
            </label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?= $name; ?>" placeholder="<?= $entry_name; ?>" id="input-name"
                class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger">
                <?= $error_name; ?>
              </div>
              <?php  } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?= $entry_users; ?>
            </label>
            <div class="col-sm-10">
              <p class="form-control-static">
                <?= $users; ?>
              </p>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label">
              <?= $entry_permission; ?>
            </label>
            <div class="col-sm-10 col-lg-8">
              <div class="table-responsive">
                <table class="table text-center">
                  <thead>
                    <tr>
                      <td class="text-left" width="50%">
                        <?= $entry_module; ?>
                      </td>
                      <td width="12.5%">
                        <?= $entry_access; ?><br>
                        <input type="checkbox"
                          onclick="$('input[name*=\'permission[access]\']').prop('checked', this.checked);" />
                      </td>
                      <td width="12.5%">
                        <?= $entry_modify; ?><br>
                        <input type="checkbox"
                          onclick="$('input[name*=\'permission[modify]\']').prop('checked', this.checked);" />
                      </td>
                      <td width="12.5%">
                        <?= $entry_approve; ?><br>
                        <input type="checkbox"
                          onclick="$('input[name*=\'permission[approve]\']').prop('checked', this.checked);" />
                      </td>
                      <td width="12.5%">
                        <?= $entry_bypass; ?><br>
                        <input type="checkbox"
                          onclick="$('input[name*=\'permission[bypass]\']').prop('checked', this.checked);" />
                      </td>
                    </tr>
                  </thead>
                </table>
              </div>
              <div class="well well-lg" style="height: 600px; overflow: auto;" id="permissions">
                <div class="table-responsive">
                  <table class="table text-center">
                    <tbody>
                      <?php foreach ($permissions as $permission) { ?>
                      <tr>
                        <td class="text-left">
                          <?= $permission; ?>
                        </td>
                        <td>
                          <div class="checkbox">
                            <label>
                              <?php if (in_array($permission, $access)) { ?>
                              <input type="checkbox" name="permission[access][]" value="<?= $permission; ?>"
                                checked="checked" />
                              <?php } else { ?>
                              <input type="checkbox" name="permission[access][]" value="<?= $permission; ?>" />
                              <?php } ?>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox">
                            <label>
                              <?php if (in_array($permission, $modify)) { ?>
                              <input type="checkbox" name="permission[modify][]" value="<?= $permission; ?>"
                                checked="checked" />
                              <?php } else { ?>
                              <input type="checkbox" name="permission[modify][]" value="<?= $permission; ?>" />
                              <?php } ?>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox">
                            <label>
                              <?php if (in_array($permission, $approve)) { ?>
                              <input type="checkbox" name="permission[approve][]" value="<?= $permission; ?>"
                                checked="checked" />
                              <?php } else { ?>
                              <input type="checkbox" name="permission[approve][]" value="<?= $permission; ?>" />
                              <?php } ?>
                            </label>
                          </div>
                        </td>
                        <td>
                          <div class="checkbox">
                            <label>
                              <?php if (in_array($permission, $bypass)) { ?>
                              <input type="checkbox" name="permission[bypass][]" value="<?= $permission; ?>"
                                checked="checked" />
                              <?php } else { ?>
                              <input type="checkbox" name="permission[bypass][]" value="<?= $permission; ?>" />
                              <?php } ?>
                            </label>
                          </div>
                        </td>
                      </tr>
                      <?php } ?>
                      <tr>
                        <td width="50%"></td>
                        <td width="12.5%"></td>
                        <td width="12.5%"></td>
                        <td width="12.5%"></td>
                        <td width="12.5%"></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <!-- <div class="form-group">
            <label class="col-sm-2 control-label">
              <?= $entry_approve; ?>
            </label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($permissions as $permission) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($permission, $approve)) { ?>
                    <input type="checkbox" name="permission[approve][]" value="<?= $permission; ?>" checked="checked" />
                    <?= $permission; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="permission[approve][]" value="<?= $permission; ?>" />
                    <?= $permission; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);">
                <?= $text_select_all; ?>
              </a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);">
                <?= $text_unselect_all; ?>
              </a>
            </div>
          </div> -->
        </form>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?>