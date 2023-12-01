<div class="row">
  <div class="col-lg-7">
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-user"></i> <?php echo $text_customer_detail; ?></h3>
	  </div>
	  <table class="table">
		<tr>
		  <td class="text-center thumb" rowspan="4"><img src="<?php echo $thumb; ?>" alt="<?php echo $name; ?>" title="" /></td>
		  <td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_customer; ?>" class="btn btn-info btn-xs"><i class="fa fa-user fa-fw"></i></button></td>
		  <td><?php if ($customer) { ?>
			<a href="<?php echo $customer; ?>" target="_blank" rel="noopener noreferrer"><?php echo $name; ?></a>
			<?php } else { ?>
			<?php echo $name; ?>
			<?php } ?></td>
		</tr>
		<tr>
		  <td><button data-toggle="tooltip" title="<?php echo $text_customer_group . '/' . $text_customer_department; ?>" class="btn btn-info btn-xs"><i class="fa fa-group fa-fw"></i></button></td>
		  <td><?php echo $customer_group . '/' . $customer_department; ?></td>
		</tr>
		<tr>
		  <td><button data-toggle="tooltip" title="<?php echo $text_location; ?>" class="btn btn-info btn-xs"><i class="fa fa-building fa-fw"></i></button></td>
		  <td><?php echo $location; ?></td>
		</tr>
		<tr>
		  <td><button data-toggle="tooltip" title="<?php echo $text_employment_period; ?>" class="btn btn-info btn-xs"><i class="fa fa-calendar fa-fw"></i></button></td>
		  <td><?php echo $employment_period; ?> <span class="text-warning text-bold"><?php echo $contract_status; ?></span></td>
		</tr>
	  </table>
	</div>
  </div>
  <div class="col-lg-5">
	<div class="panel panel-default">
	  <div class="panel-heading">
		<h3 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $text_additional_info; ?></h3>
	  </div>
	  <table class="table">
		<tbody>
		  <tr>
			<td style="width: 1%;"><button data-toggle="tooltip" title="<?php echo $text_email; ?>" class="btn btn-info btn-xs"><i class="fa fa-envelope-o fa-fw"></i></button></td>
			<td><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></td>
		  </tr>
		  <tr>
			<td><button data-toggle="tooltip" title="<?php echo $text_telephone; ?>" class="btn btn-info btn-xs"><i class="fa fa-phone fa-fw"></i></button></td>
			<td><?php echo $telephone; ?></td>
		  </tr>
		  <?php if ($vacation) { ?>
		    <tr>
			  <td><button data-toggle="tooltip" title="<?php echo $text_vacation; ?>" class="btn btn-info btn-xs"><i class="fa fa-suitcase fa-fw"></i></button></td>
			  <td><?php echo $vacation; ?></td>
		    </tr>
		  <?php } ?>
		</tbody>
	  </table>
	</div>
  </div>
</div>
