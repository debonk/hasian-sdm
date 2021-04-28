<?php if ($schedule_changes) { ?>
<legend><?php echo $text_list; ?></legend>
<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
  	  <tr>
  	    <td class="text-left"><?php echo $column_date; ?></td>
  	    <td class="text-left"><?php echo $column_presence_status; ?></td>
  	    <td class="text-left"><?php echo $column_description; ?></td>
  	    <td class="text-left"><?php echo $column_note; ?></td>
  	    <td class="text-left"><?php echo $column_username; ?></td>
  	    <td class="text-center"><?php echo $column_status; ?></td>
  	  </tr>
    </thead>
    <tbody>
      <?php foreach ($schedule_changes as $key => $schedule_change) { ?>
      <tr>
  		<td class="text-left"><?php echo $schedule_change['date']; ?></td>
  		<td class="text-left"><?php echo $schedule_change['presence_status']; ?></td>
  		<td class="text-left"><?php echo $schedule_change['description']; ?></td>
  		<td class="text-left"><?php echo $schedule_change['note']; ?></td>
  		<td class="text-left"><?php echo $schedule_change['username']; ?></td>
  		<td class="text-center">
		  <?php if ($schedule_change['action_url']) { ?>
		  <?php if ($schedule_change['approved']) { ?>
		    <button type="button" id="button-action<?php echo $key; ?>" value="<?php echo $schedule_change['action_url']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-success btn-xs"><i class="fa fa-check"></i></button>
		  <?php } else { ?>
		    <button type="button" id="button-action<?php echo $key; ?>" value="<?php echo $schedule_change['action_url']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button>
		  <?php } ?>
		  <?php } ?>
		</td>
  	  </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<?php } ?>
