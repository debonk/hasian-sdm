<?= $header; ?>
<?= $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
      <?= $error_install; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-sm-12">
        <?php if ($admin_maintenance) { ?>
        <button type="button" value="" class="btn btn-danger button-login-session">
          <?= $text_maintenance; ?>
        </button>
        <?php } ?>
      </div>
    </div>
    <div class="row">
      <?= $login_session; ?>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-6">
        <?= $presence; ?>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6">
        <?= $customer; ?>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6">
        <?= $online; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <?= $attention; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12 col-sx-12">
        <?= $history; ?>
      </div>
      <div class="col-lg-8 col-md-12 col-sm-12 col-sx-12">
        <?= $recent; ?>
      </div>
    </div>
  </div>
</div>
<?= $footer; ?>