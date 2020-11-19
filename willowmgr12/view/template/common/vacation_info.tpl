<legend><?php echo $text_list; ?></legend>
<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
  	  <tr>
  	    <td class="text-left"><?php echo $column_date; ?></td>
  	    <td class="text-left"><?php echo $column_description; ?></td>
  	  </tr>
    </thead>
    <tbody>
	  <?php if ($vacations) { ?>
      <?php foreach ($vacations as $vacation) { ?>
      <tr>
  		<td class="text-left"><?php echo $vacation['date']; ?></td>
  		<td class="text-left"><?php echo $vacation['description']; ?></td>
  	  </tr>
      <?php } ?>
	  <?php } else { ?>
	  <tr>
	    <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
	  </tr>
	  <?php } ?>
    </tbody>
  </table>
</div>
