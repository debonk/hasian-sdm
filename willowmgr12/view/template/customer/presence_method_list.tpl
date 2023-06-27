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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i>
					<?= $text_list; ?>
				</h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-name">
									<?= $entry_name; ?>
								</label>
								<input type="text" name="filter[name]" value="<?= $filter['name']; ?>" placeholder="<?= $entry_name; ?>"
									id="input-name" class="form-control" />
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-customer-group">
									<?= $entry_customer_group; ?>
								</label>
								<select name="filter[customer_group_id]" id="input-customer-group" class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<?php foreach ($customer_groups as $customer_group) { ?>
									<?php if ($customer_group['customer_group_id'] == $filter['customer_group_id']) { ?>
									<option value="<?= $customer_group['customer_group_id']; ?>" selected="selected">
										<?= $customer_group['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $customer_group['customer_group_id']; ?>">
										<?= $customer_group['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-location">
									<?= $entry_location; ?>
								</label>
								<select name="filter[location_id]" id="input-location" class="form-control">
									<option value="">
										<?= $text_all; ?>
									</option>
									<?php foreach ($locations as $location) { ?>
									<?php if ($location['location_id'] == $filter['location_id']) { ?>
									<option value="<?= $location['location_id']; ?>" selected="selected">
										<?= $location['name']; ?>
									</option>
									<?php } else { ?>
									<option value="<?= $location['location_id']; ?>">
										<?= $location['name']; ?>
									</option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="input-status">
									<?= $entry_status; ?>
								</label>
								<select name="filter[status]" id="input-status" class="form-control">
									<option value="">
										<?= $text_active; ?>
									</option>
									<?php if ($filter['status']) { ?>
									<option value="1" selected="selected">
										<?= $text_inactive; ?>
									</option>
									<?php } else { ?>
									<option value="1">
										<?= $text_inactive; ?>
									</option>
									<?php } ?>
									<?php if (!$filter['status'] && !is_null($filter['status'])) { ?>
									<option value="0" selected="selected">
										<?= $text_all; ?>
									</option>
									<?php } else { ?>
									<option value="0">
										<?= $text_all; ?>
									</option>
									<?php } ?>
								</select>
							</div>
							<button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i>
								<?= $button_filter; ?>
							</button>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-bordered table-hover text-left">
						<thead>
							<tr>
								<td>
									<?php if ($sort == 'nip') { ?>
									<a href="<?= $sort_nip; ?>" class="<?= strtolower($order); ?>">
										<?= $column_nip; ?>
									</a>
									<?php } else { ?>
									<a href="<?= $sort_nip; ?>">
										<?= $column_nip; ?>
									</a>
									<?php } ?>
								</td>
								<td>
									<?php if ($sort == 'name') { ?>
									<a href="<?= $sort_name; ?>" class="<?= strtolower($order); ?>">
										<?= $column_name; ?>
									</a>
									<?php } else { ?>
									<a href="<?= $sort_name; ?>">
										<?= $column_name; ?>
									</a>
									<?php } ?>
								</td>
								<td>
									<?php if ($sort == 'customer_group') { ?>
									<a href="<?= $sort_customer_group; ?>" class="<?= strtolower($order); ?>">
										<?= $column_customer_group; ?>
									</a>
									<?php } else { ?>
									<a href="<?= $sort_customer_group; ?>">
										<?= $column_customer_group; ?>
									</a>
									<?php } ?>
								</td>
								<td>
									<?php if ($sort == 'location') { ?>
									<a href="<?= $sort_location; ?>" class="<?= strtolower($order); ?>">
										<?= $column_location; ?>
									</a>
									<?php } else { ?>
									<a href="<?= $sort_location; ?>">
										<?= $column_location; ?>
									</a>
									<?php } ?>
								</td>
								<?php foreach ($presence_types as $presence_type) { ?>
								<td class="text-center">
									<?= $presence_type['text']; ?>
								</td>
								<?php } ?>
								<td class="text-right">
									<?= $column_action; ?>
								</td>
							</tr>
						</thead>
						<tbody>
							<?php if ($presence_methods) { ?>
							<?php foreach ($presence_methods as $presence_method) { ?>
							<tr>
								<td>
									<?= $presence_method['nip']; ?>
								</td>
								<td>
									<?= $presence_method['name']; ?>
								</td>
								<td>
									<?= $presence_method['customer_group']; ?>
								</td>
								<td>
									<?= $presence_method['location']; ?>
								</td>
								<?php foreach ($presence_types as $presence_type) { ?>
								<?php if ($presence_method['customer_method'][$presence_type['value']]) { ?>
								<td class="text-center text-success"><i class="fa fa-check"></td>
								<?php } else { ?>
								<td></td>
								<?php } ?>
								<?php } ?>
								<td class="text-right"><a href="<?= $presence_method['edit']; ?>" data-toggle="tooltip"
										title="<?= $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="text-center" colspan="11">
									<?= $text_no_results; ?>
								</td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
				<div class="row">
					<div class="col-sm-6 text-left">
						<?= $pagination; ?>
					</div>
					<div class="col-sm-6 text-right">
						<?= $results; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#button-filter').on('click', function () {
		url = 'index.php?route=customer/presence_method&token=<?= $token; ?>';

		let filter_items = [
			'name',
			'customer_group_id',
			'location_id',
			'status'
		];

		let filter = [];

		for (let i = 0; i < filter_items.length; i++) {
			filter[filter_items[i]] = $('.well [name=\'filter[' + filter_items[i] + ']\']').val();

			if (filter[filter_items[i]]) {
				url += '&filter_' + filter_items[i] + '=' + encodeURIComponent(filter[filter_items[i]]);
			}
		}

		location = url;
	});

	$('input[name=\'filter[name]\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=presence/presence/autocomplete&token=<?= $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {
						return {
							label: item['name'],
							value: item['customer_id']
						}
					}));
				}
			});
		},
		'select': function (item) {
			$('input[name=\'filter[name]\']').val(item['label']);
		}
	});

	$(document).keypress(function (e) {
		if (e.which == 13) {
			$("#button-filter").click();
		}
	});
</script>
<?= $footer; ?>