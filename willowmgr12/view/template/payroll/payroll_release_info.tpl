<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title"><i class="fa fa-share-alt"></i> <?php echo $text_release_info; ?></h3>
  </div>
  <table class="table">
    <?php if ($fund_account) { ?>
	  <tr>
	    <td class="text-right" style="width: 50%;"><?php echo $text_fund_acc_name; ?></td>
	    <td class="text-left"><?php echo $fund_acc_name; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?php echo $text_fund_acc_no; ?></td>
	    <td class="text-left"><?php echo $fund_acc_no; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?php echo $text_fund_email; ?></td>
	    <td class="text-left"><?php echo $fund_email; ?></td>
	  </tr>
	  <tr>
	    <td class="text-right"><?php echo $text_fund_date_release; ?></td>
	    <td class="text-left"><?php echo $fund_date_release; ?></td>
	  </tr>
	  <?php foreach ($method_releases as $method_release) { ?>
	  <tr>
	    <td class="text-right"><?php echo $method_release['method']; ?></td>
	    <td class="text-left"><?php echo $method_release['total']; ?></td>
	  </tr>
	  <?php } ?>
    <?php } else { ?>
    <tr>
	  <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
    </table>
</div>
