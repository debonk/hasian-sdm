<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-payroll-type" data-toggle="tooltip" title="<?= $button_save; ?>"
					class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>"
					class="btn btn-default"><i class="fa fa-reply"></i></a>
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
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-payroll-type"
					class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-name">
							<?= $entry_name; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="name" value="<?= $name; ?>" placeholder="<?= $entry_name; ?>"
								id="input-name" class="form-control" />
							<?php if ($error_name) { ?>
							<div class="text-danger">
								<?= $error_name; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-description">
							<?= $entry_description; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="description" value="<?= $description; ?>" placeholder="<?= $entry_description; ?>"
								id="input-description" class="form-control" />
							<?php if ($error_description) { ?>
							<div class="text-danger">
								<?= $error_description; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<?php foreach ($payroll_type_components as $direction => $direction_item) { ?>
					<legend>
						<?= $direction_title[$direction]; ?>
					</legend>
					<table id="payroll-type-component-<?= $direction; ?>"
						class="table table-striped table-bordered table-hover text-left">
						<thead>
							<tr>
								<td>
									<?= $entry_title; ?>
								</td>
								<td style="width:35%">
									<?= $entry_type; ?>
									<select id="component-type-<?= $direction ?>"
										class="hidden">
										<option value="">
											<?= $text_select ?>
										</option>
										<?php foreach ($main_components[$direction] as $code => $description) { ?>
										<option value="<?= $code; ?>">
											<?= $description; ?>
										</option>
										<?php } ?>
									</select>
								</td>
								<td>
									<?= $entry_variable; ?>
								</td>
								<td>
									<?= $entry_sort_order; ?>
								</td>
								<td>
									<?= $entry_action; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($direction_item as $key => $payroll_type_component) { ?>
							<tr id="payroll-type-component-<?= $direction; ?>-row<?= $key; ?>">
								<td><input type="text"
										name="payroll_type_component[<?= $direction; ?>][<?= $key; ?>][title]"
										class="form-control" value="<?= $payroll_type_component['title']; ?>"
										placeholder="<?= $entry_title; ?>" /></td>
								<td><select name="payroll_type_component[<?= $direction; ?>][<?= $key; ?>][code]"
										class="form-control">
										<option value="">
											<?= $text_select ?>
										</option>
										<?php foreach ($main_components[$direction] as $code => $description) { ?>
										<option value="<?= $code; ?>" <?=$payroll_type_component['code']==$code
											? 'selected' : '' ; ?>>
											<?= $description; ?>
										</option>
										<?php } ?>
									</select>
								</td>
								<td><input type="text"
										name="payroll_type_component[<?= $direction; ?>][<?= $key; ?>][variable]"
										class="form-control" value="<?= $payroll_type_component['variable']; ?>"
										placeholder="<?= $entry_variable; ?>" /></td>
								<td><input type="text"
										name="payroll_type_component[<?= $direction; ?>][<?= $key; ?>][sort_order]"
										class="form-control" value="<?= $payroll_type_component['sort_order']; ?>"
										placeholder="<?= $entry_sort_order; ?>" /></td>
								<td class="text-right"><button type="button"
										onclick="$('#payroll-type-component-<?= $direction; ?>-row<?= $key; ?>').remove();"
										data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i
											class="fa fa-minus-circle"></i></button></td>
							</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="4" class="text-primary"><i>
										<?= $note . ($direction == 'deduction' ? $text_note_deduction : ''); ?>
									</i></td>
								<td class="text-right"><button type="button"
										onclick="addPayrollType('<?= $direction; ?>');" data-toggle="tooltip"
										title="<?= $button_payroll_type_add; ?>" class="btn btn-primary"><i
											class="fa fa-plus-circle"></i></button></td>
							</tr>
						</tfoot>
					</table>
					<?php } ?>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	let component_row = JSON.parse('<?= $component_row; ?>');

	function addPayrollType(direction) {
		let html = '';
		html += '<tr id="payroll-type-component-' + direction + '-row' + component_row[direction] + '">';
		html += '  <td><input type="text" name="payroll_type_component[' + direction + '][' + component_row[direction] + '][title]" placeholder="<?= $entry_title; ?>" class="form-control" /></td>';
		html += '  <td><select name="payroll_type_component[' + direction + '][' + component_row[direction] + '][code]" class="form-control">';
		html += $('#component-type-' + direction).html();
		html += '  </select></td>';
		html += '  <td><input type="text" name="payroll_type_component[' + direction + '][' + component_row[direction] + '][variable]" placeholder="<?= $entry_variable; ?>" class="form-control" /></td>';
		html += '  <td><input type="text" name="payroll_type_component[' + direction + '][' + component_row[direction] + '][sort_order]" placeholder="<?= $entry_sort_order; ?>" class="form-control" /></td>';
		html += '  <td class="text-right"><button type="button" onclick="$(\'#payroll-type-component-' + direction + '-row' + component_row[direction] + '\').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
		html += '</tr>';

		$('#payroll-type-component-' + direction + ' tbody').append(html);

		component_row[direction]++;
	}
</script>
<?= $footer; ?>