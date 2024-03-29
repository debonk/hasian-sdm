<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>
        <?php echo $heading_title; ?>
      </h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
          </a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
      <?php echo $error_install; ?>
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
      <?php echo $login_session; ?>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-4 col-sm-6">
        <?php echo $presence; ?>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6">
        <?php echo $customer; ?>
      </div>
      <div class="col-lg-4 col-md-4 col-sm-6">
        <?php echo $online; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-12">
        <?php echo $attention; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-4 col-md-12 col-sm-12 col-sx-12">
        <?php echo $history; ?>
      </div>
      <div class="col-lg-8 col-md-12 col-sm-12 col-sx-12">
        <?php echo $recent; ?>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>