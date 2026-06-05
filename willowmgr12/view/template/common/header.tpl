<!DOCTYPE html>
<html dir="<?= $direction; ?>" lang="<?= $lang; ?>" translate="no">

<head>
  <meta charset="UTF-8" />
  <title>
    <?= $title; ?>
  </title>
  <base href="<?= $base; ?>" />
  <?php if ($description) { ?>
  <meta name="description" content="<?= $description; ?>" />
  <?php } ?>
  <?php if ($keywords) { ?>
  <meta name="keywords" content="<?= $keywords; ?>" />
  <?php } ?>
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
  <link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
  <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet"
    media="screen" />
  <link type="text/css" href="<?= 'view/stylesheet/stylesheet.css?v=' . VERSION; ?>" rel="stylesheet" media="screen" />
  <?php foreach ($styles as $style) { ?>
  <link type="text/css" href="<?= $style['href']; ?>" rel="<?= $style['rel']; ?>" media="<?= $style['media']; ?>" />
  <?php } ?>
  <?php foreach ($links as $link) { ?>
  <link href="<?= $link['href']; ?>" rel="<?= $link['rel']; ?>" />
  <?php } ?>
  <script src="view/javascript/common.js" type="text/javascript"></script>
  <?php foreach ($scripts as $script) { ?>
  <script type="text/javascript" src="<?= $script; ?>"></script>
  <?php } ?>
  <script type="text/javascript" src="view/javascript/number-format.js"></script>
</head>

<body>
  <div id="container">
    <header id="header" class="navbar navbar-static-top">
      <div class="navbar-header">
        <?php if ($logged) { ?>
        <a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
        <?php } ?>
        <a href="<?= $home; ?>" class="navbar-brand"><img src="view/image/logo.png" alt="<?= $heading_title; ?>"
            title="<?= $heading_title; ?>" /></a>
      </div>
      <?php if ($logged) { ?>
      <ul class="nav pull-right">
        <?php if ($text_framework_update) { ?>
        <li><a>
            <?= $text_framework_update; ?>
          </a></li>
        <?php } ?>
        <?php if (isset($maintenance)) { ?>
        <li><a href="<?= $maintenance; ?>" class="bg-danger text-bold"><i class="fa fa-exclamation-triangle"></i>
            <?= $text_maintenance; ?>
          </a></li>
        <?php } ?>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><span
              class="label label-danger pull-left">
              <?= $alerts; ?>
            </span> <i class="fa fa-bell fa-lg"></i></a>
          <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
            <li class="dropdown-header">
              <?= $text_customer; ?>
            </li>
            <li><a href="<?= $online; ?>"><span class="label label-success pull-right">
                  <?= $online_total; ?>
                </span>
                <?= $text_online; ?>
              </a></li>
            <li class="divider"></li>
          </ul>
        </li>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home fa-lg"></i></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <li class="dropdown-header">
              <?= $text_store; ?>
            </li>
            <?php foreach ($stores as $store) { ?>
            <li><a href="<?= $store['href']; ?>" target="_blank" rel="noopener noreferrer">
                <?= $store['name']; ?>
              </a></li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="<?= $logout; ?>"><span class="hidden-xs hidden-sm hidden-md">
              <?= $text_logout; ?>
            </span> <i class="fa fa-sign-out fa-lg"></i></a></li>
      </ul>
      <?php } ?>
    </header>