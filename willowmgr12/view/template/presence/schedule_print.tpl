<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">

<head>
  <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <title>
    <?php echo $heading_title; ?>
  </title>
  <base href="<?php echo $base; ?>" />
  <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
  <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
  <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
  <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
  <link type="text/css" href="view/stylesheet/print.css" rel="stylesheet" media="all" />
</head>

<body>
  <div class="container">
    <?php foreach ($customers as $key => $customer_pages) { ?>
    <div class="page-content">
      <div class="flex-container">
        <div class="logo"><img src="<?php echo $store_logo; ?>" />
        </div>
        <div>
          <h1>
            <?php echo $heading_title; ?>
            <small>
              <?php echo ' (' . $location . ')' . ($key ? ' - ' . $key : ''); ?>
            </small>
          </h1>
        </div>
        <div></div>
        <div>
          <p class="text-right legend">
            <?php echo $text_division; ?><br />
            <?php echo $text_period; ?><br />
            <?php echo $text_user; ?>
          </p>
        </div>
      </div>
      <div class="container">
        <table class="table table-bordered schedule-print">
          <thead>
            <tr>
              <th>
                <?php echo $column_name; ?>
              </th>
              <th>
                <?php echo $column_customer_group; ?>
              </th>
              <?php foreach ($date_titles as $date_title) { ?>
              <th class="text-center">
                <?php echo $date_title['date_only']; ?>
              </th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php if ($customer_pages) { ?>
            <?php foreach ($customer_pages as $customer) { ?>
            <tr>
              <td class="nowrap">
                <?php echo $customer['name']; ?>
              </td>
              <td>
                <?php echo $customer['customer_group']; ?>
              </td>
              <?php foreach ($date_titles as $date_title) { ?>
              <?php if (isset($customer['schedules_data'][$date_title['date']])) { ?>
              <td
                class="text-center schedule-bg-<?php echo $customer['schedules_data'][$date_title['date']]['schedule_bg']; ?>">
                <?php echo $customer['schedules_data'][$date_title['date']]['schedule_type']; ?>
              </td>
              <?php } else { ?>
              <td class="text-center">
                <?php echo '-'; ?>
              </td>
              <?php } ?>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="35">
              <?php echo $text_no_results; ?>
            </td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
    <?php } ?>
    <?php if ($schedule_groups) { ?>
    <div class="page-content">
      <div class="flex-container">
        <div class="logo"><img src="<?php echo $store_logo; ?>" />
        </div>
        <div>
          <h1>
            <?php echo $text_summary; ?>
            <small>
              <?php echo ' (' . $location . ')'; ?>
            </small>
          </h1>
        </div>
        <div></div>
        <div>
          <p class="text-right legend">
            <?php echo $text_division; ?><br />
            <?php echo $text_period; ?><br />
            <?php echo $text_user; ?>
          </p>
        </div>
      </div>
      <div class="container">
        <table class="table table-bordered schedule-print">
          <?php foreach ($schedule_groups as $key => $schedule_group_data) { ?>
          <thead>
            <tr>
              <th colspan="35">
                <br />
                <h5>
                  <?php echo $text_schedule_summary . ($key ? ' - ' . $key : ''); ?>
                </h5>
              </th>
            </tr>
            <tr>
              <th>
                <?php echo $column_schedule_type; ?>
              </th>
              <?php foreach ($date_titles as $date_title) { ?>
              <th class="text-center">
                <?php echo $date_title['date_only']; ?>
              </th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($schedule_group_data as $schedule_group) { ?>
            <tr>
              <td class="nowrap">
                <?php echo $schedule_group['text']; ?>
              </td>
              <?php foreach ($date_titles as $date_title) { ?>
              <td class="text-center schedule-bg-<?php echo $schedule_group['bg']; ?>">
                <?php echo $schedule_group['group_data'][$date_title['date']]; ?>
              </td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
          <?php } ?>
          <?php if ($customer_groups) { ?>
          <thead>
            <tr>
              <th colspan="35">
                <br />
                <h5>
                  <?php echo $text_group_summary; ?>
                </h5>
              </th>
            </tr>
            <tr>
              <th>
                <?php echo $column_customer_group; ?>
              </th>
              <?php foreach ($date_titles as $date_title) { ?>
              <th class="text-center">
                <?php echo $date_title['date_only']; ?>
              </th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($customer_groups as $customer_group => $value) { ?>
            <tr>
              <td class="nowrap">
                <?php echo $customer_group; ?>
              </td>
              <?php foreach ($date_titles as $date_title) { ?>
              <td class="text-center schedule-bg-<?php echo fmod($value[$date_title['date']], 12); ?>">
                <?php echo $value[$date_title['date']]; ?>
              </td>
              <?php } ?>
            </tr>
            <?php } ?>
          </tbody>
          <?php } ?>
        </table>
      </div>
    </div>
    <?php } ?>
  </div>
</body>

</html>