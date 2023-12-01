<?= $header; ?>
<?= $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" id="button-save" form="form-setting" data-toggle="tooltip" title="<?= $button_save; ?>"
					disabled="disabled" class="btn btn-primary"><i class="fa fa-save"></i></button>
				<a href="<?= $cancel; ?>" data-toggle="tooltip" title="<?= $button_cancel; ?>" class="btn btn-default"><i
						class="fa fa-reply"></i></a>
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
		<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i>
			<?= $success; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i>
					<?= $text_edit; ?>
				</h3>
			</div>
			<div class="panel-body">
				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-setting"
					class="form-horizontal">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab-general" data-toggle="tab">
								<?= $tab_general; ?>
							</a></li>
						<li><a href="#tab-store" data-toggle="tab">
								<?= $tab_store; ?>
							</a></li>
						<li><a href="#tab-local" data-toggle="tab">
								<?= $tab_local; ?>
							</a></li>
						<li><a href="#tab-option" data-toggle="tab">
								<?= $tab_option; ?>
							</a></li>
						<li><a href="#tab-image" data-toggle="tab">
								<?= $tab_image; ?>
							</a></li>
						<li><a href="#tab-ftp" data-toggle="tab">
								<?= $tab_ftp; ?>
							</a></li>
						<li><a href="#tab-mail" data-toggle="tab">
								<?= $tab_mail; ?>
							</a></li>
						<li><a href="#tab-server" data-toggle="tab">
								<?= $tab_server; ?>
							</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab-general">
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-meta-title">
									<?= $entry_meta_title; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_meta_title" value="<?= $config_meta_title; ?>"
										placeholder="<?= $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
									<?php if ($error_meta_title) { ?>
									<div class="text-danger">
										<?= $error_meta_title; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-meta-description">
									<?= $entry_meta_description; ?>
								</label>
								<div class="col-sm-9">
									<textarea name="config_meta_description" rows="5" placeholder="<?= $entry_meta_description; ?>"
										id="input-meta-description" class="form-control"><?= $config_meta_description; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-meta-keyword">
									<?= $entry_meta_keyword; ?>
								</label>
								<div class="col-sm-9">
									<textarea name="config_meta_keyword" rows="5" placeholder="<?= $entry_meta_keyword; ?>"
										id="input-meta-keyword" class="form-control"><?= $config_meta_keyword; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-theme">
									<?= $entry_theme; ?>
								</label>
								<div class="col-sm-9">
									<select name="config_theme" id="input-theme" class="form-control">
										<?php foreach ($themes as $theme) { ?>
										<?php if ($theme['value'] == $config_theme) { ?>
										<option value="<?= $theme['value']; ?>" selected="selected">
											<?= $theme['text']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $theme['value']; ?>">
											<?= $theme['text']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
									<br />
									<img src="" alt="" id="theme" class="img-thumbnail" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-layout">
									<?= $entry_layout; ?>
								</label>
								<div class="col-sm-9">
									<select name="config_layout_id" id="input-layout" class="form-control">
										<?php foreach ($layouts as $layout) { ?>
										<?php if ($layout['layout_id'] == $config_layout_id) { ?>
										<option value="<?= $layout['layout_id']; ?>" selected="selected">
											<?= $layout['name']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $layout['layout_id']; ?>">
											<?= $layout['name']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-store">
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-name">
									<?= $entry_name; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_name" value="<?= $config_name; ?>" placeholder="<?= $entry_name; ?>"
										id="input-name" class="form-control" />
									<?php if ($error_name) { ?>
									<div class="text-danger">
										<?= $error_name; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-owner">
									<?= $entry_owner; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_owner" value="<?= $config_owner; ?>"
										placeholder="<?= $entry_owner; ?>" id="input-owner" class="form-control" />
									<?php if ($error_owner) { ?>
									<div class="text-danger">
										<?= $error_owner; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-address">
									<?= $entry_address; ?>
								</label>
								<div class="col-sm-9">
									<textarea name="config_address" placeholder="<?= $entry_address; ?>" rows="5" id="input-address"
										class="form-control"><?= $config_address; ?></textarea>
									<?php if ($error_address) { ?>
									<div class="text-danger">
										<?= $error_address; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-geocode"><span data-toggle="tooltip"
										data-container="#tab-general" title="<?= $help_geocode; ?>">
										<?= $entry_geocode; ?>
									</span></label>
								<div class="col-sm-9">
									<input type="text" name="config_geocode" value="<?= $config_geocode; ?>"
										placeholder="<?= $entry_geocode; ?>" id="input-geocode" class="form-control" />
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-email">
									<?= $entry_email; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_email" value="<?= $config_email; ?>"
										placeholder="<?= $entry_email; ?>" id="input-email" class="form-control" />
									<?php if ($error_email) { ?>
									<div class="text-danger">
										<?= $error_email; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group required">
								<label class="col-sm-3 control-label" for="input-telephone">
									<?= $entry_telephone; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_telephone" value="<?= $config_telephone; ?>"
										placeholder="<?= $entry_telephone; ?>" id="input-telephone" class="form-control" />
									<?php if ($error_telephone) { ?>
									<div class="text-danger">
										<?= $error_telephone; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-fax">
									<?= $entry_fax; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_fax" value="<?= $config_fax; ?>" placeholder="<?= $entry_fax; ?>"
										id="input-fax" class="form-control" />
								</div>
							</div>
							<!-- Bonk: Additional Contact -->
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-wa">
									<?= $entry_wa; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_wa" value="<?= $config_wa; ?>" placeholder="<?= $entry_wa; ?>"
										id="input-wa" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-bbm">
									<?= $entry_bbm; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_bbm" value="<?= $config_bbm; ?>" placeholder="<?= $entry_bbm; ?>"
										id="input-bbm" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-line">
									<?= $entry_line; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_line" value="<?= $config_line; ?>" placeholder="<?= $entry_line; ?>"
										id="input-line" class="form-control" />
								</div>
							</div>

							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-image">
									<?= $entry_image; ?>
								</label>
								<div class="col-sm-9"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img
											src="<?= $thumb; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" /></a>
									<input type="hidden" name="config_image" value="<?= $config_image; ?>" id="input-image" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-open"><span data-toggle="tooltip"
										data-container="#tab-general" title="<?= $help_open; ?>">
										<?= $entry_open; ?>
									</span></label>
								<div class="col-sm-9">
									<textarea name="config_open" rows="5" placeholder="<?= $entry_open; ?>" id="input-open"
										class="form-control"><?= $config_open; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-comment"><span data-toggle="tooltip"
										data-container="#tab-general" title="<?= $help_comment; ?>">
										<?= $entry_comment; ?>
									</span></label>
								<div class="col-sm-9">
									<textarea name="config_comment" rows="5" placeholder="<?= $entry_comment; ?>" id="input-comment"
										class="form-control"><?= $config_comment; ?></textarea>
								</div>
							</div>
							<?php if ($locations) { ?>
							<div class="form-group">
								<label class="col-sm-3 control-label"><span data-toggle="tooltip" data-container="#tab-general"
										title="<?= $help_location; ?>">
										<?= $entry_location; ?>
									</span></label>
								<div class="col-sm-9">
									<?php foreach ($locations as $location) { ?>
									<div class="checkbox">
										<label>
											<?php if (in_array($location['location_id'], $config_location)) { ?>
											<input type="checkbox" name="config_location[]" value="<?= $location['location_id']; ?>"
												checked="checked" />
											<?= $location['name']; ?>
											<?php } else { ?>
											<input type="checkbox" name="config_location[]" value="<?= $location['location_id']; ?>" />
											<?= $location['name']; ?>
											<?php } ?>
										</label>
									</div>
									<?php } ?>
								</div>
							</div>
							<?php } ?>
						</div>
						<div class="tab-pane" id="tab-local">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-country">
									<?= $entry_country; ?>
								</label>
								<div class="col-sm-9">
									<select name="config_country_id" id="input-country" class="form-control">
										<?php foreach ($countries as $country) { ?>
										<?php if ($country['country_id'] == $config_country_id) { ?>
										<option value="<?= $country['country_id']; ?>" selected="selected">
											<?= $country['name']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $country['country_id']; ?>">
											<?= $country['name']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-zone">
									<?= $entry_zone; ?>
								</label>
								<div class="col-sm-9">
									<select name="config_zone_id" id="input-zone" class="form-control">
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-language">
									<?= $entry_language; ?>
								</label>
								<div class="col-sm-9">
									<select name="config_language" id="input-language" class="form-control">
										<?php foreach ($languages as $language) { ?>
										<?php if ($language['code'] == $config_language) { ?>
										<option value="<?= $language['code']; ?>" selected="selected">
											<?= $language['name']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $language['code']; ?>">
											<?= $language['name']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-admin-language">
									<?= $entry_admin_language; ?>
								</label>
								<div class="col-sm-9">
									<select name="config_admin_language" id="input-admin-language" class="form-control">
										<?php foreach ($languages as $language) { ?>
										<?php if ($language['code'] == $config_admin_language) { ?>
										<option value="<?= $language['code']; ?>" selected="selected">
											<?= $language['name']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $language['code']; ?>">
											<?= $language['name']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-currency"><span data-toggle="tooltip"
										title="<?= $help_currency; ?>">
										<?= $entry_currency; ?>
									</span></label>
								<div class="col-sm-9">
									<select name="config_currency" id="input-currency" class="form-control">
										<?php foreach ($currencies as $currency) { ?>
										<?php if ($currency['code'] == $config_currency) { ?>
										<option value="<?= $currency['code']; ?>" selected="selected">
											<?= $currency['title']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $currency['code']; ?>">
											<?= $currency['title']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_currency_auto; ?>">
										<?= $entry_currency_auto; ?>
									</span></label>
								<div class="col-sm-9">
									<label class="radio-inline">
										<?php if ($config_currency_auto) { ?>
										<input type="radio" name="config_currency_auto" value="1" checked="checked" />
										<?= $text_yes; ?>
										<?php } else { ?>
										<input type="radio" name="config_currency_auto" value="1" />
										<?= $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$config_currency_auto) { ?>
										<input type="radio" name="config_currency_auto" value="0" checked="checked" />
										<?= $text_no; ?>
										<?php } else { ?>
										<input type="radio" name="config_currency_auto" value="0" />
										<?= $text_no; ?>
										<?php } ?>
									</label>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-option">
							<fieldset>
								<legend>
									<?= $text_customer; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-nip-prefix"><span data-toggle="tooltip"
											title="<?= $help_nip_prefix; ?>">
											<?= $entry_nip_prefix; ?>
										</span></label>
									<div class="col-sm-9">
										<input type="text" name="config_nip_prefix" value="<?= $config_nip_prefix; ?>"
											placeholder="<?= $entry_nip_prefix; ?>" id="input-nip-prefix" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-customer-last"><span data-toggle="tooltip"
											title="<?= $help_customer_last; ?>">
											<?= $entry_customer_last; ?>
										</span></label>
									<div class="col-sm-9">
										<input type="text" name="config_customer_last" value="<?= $config_customer_last; ?>"
											placeholder="<?= $entry_customer_last; ?>" id="input-customer-last" class="form-control" />
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_contract; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-contract-end-notif"><span data-toggle="tooltip"
											title="<?= $help_contract_end_notif; ?>">
											<?= $entry_contract_end_notif; ?>
										</span></label>
									<div class="col-sm-9">
										<input type="text" name="config_contract_end_notif" value="<?= $config_contract_end_notif; ?>"
											placeholder="<?= $entry_contract_end_notif; ?>" id="input-contract-end-notif" class="form-control" />
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_allowance; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_components; ?>">
											<?= $entry_components; ?>
										</span>
									</label>
									<div class="col-sm-9">
										<div class="well well-sm" style="height: 150px; overflow: auto;">
											<?php foreach ($components as $component) { ?>
											<div class="checkbox">
												<label>
													<?php if (in_array($component['value'], $config_components)) { ?>
													<input type="checkbox" name="config_components[]" value="<?= $component['value']; ?>"
														checked="checked" />
													<?= $component['text']; ?>
													<?php } else { ?>
													<input type="checkbox" name="config_components[]" value="<?= $component['value']; ?>" />
													<?= $component['text']; ?>
													<?php } ?>
												</label>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_item; ?>
								</legend>
								<div class="form-group required">
									<label class="col-sm-3 control-label" for="input-admin-limit"><span data-toggle="tooltip"
											title="<?= $help_limit_admin; ?>">
											<?= $entry_limit_admin; ?>
										</span></label>
									<div class="col-sm-9">
										<input type="text" name="config_limit_admin" value="<?= $config_limit_admin; ?>"
											placeholder="<?= $entry_limit_admin; ?>" id="input-admin-limit" class="form-control" />
										<?php if ($error_limit_admin) { ?>
										<div class="text-danger">
											<?= $error_limit_admin; ?>
										</div>
										<?php } ?>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_account; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip"
											title="<?= $help_customer_online; ?>">
											<?= $entry_customer_online; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_customer_online) { ?>
											<input type="radio" name="config_customer_online" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_customer_online" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_customer_online) { ?>
											<input type="radio" name="config_customer_online" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_customer_online" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-login-attempts"><span data-toggle="tooltip"
											title="<?= $help_login_attempts; ?>">
											<?= $entry_login_attempts; ?>
										</span></label>
									<div class="col-sm-9">
										<input type="text" name="config_login_attempts" value="<?= $config_login_attempts; ?>"
											placeholder="<?= $entry_login_attempts; ?>" id="input-login-attempts" class="form-control" />
										<?php if ($error_login_attempts) { ?>
										<div class="text-danger">
											<?= $error_login_attempts; ?>
										</div>
										<?php } ?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-account"><span data-toggle="tooltip"
											title="<?= $help_account; ?>">
											<?= $entry_account; ?>
										</span></label>
									<div class="col-sm-9">
										<select name="config_account_id" id="input-account" class="form-control">
											<option value="0">
												<?= $text_none; ?>
											</option>
											<?php foreach ($informations as $information) { ?>
											<?php if ($information['information_id'] == $config_account_id) { ?>
											<option value="<?= $information['information_id']; ?>" selected="selected">
												<?= $information['title']; ?>
											</option>
											<?php } else { ?>
											<option value="<?= $information['information_id']; ?>">
												<?= $information['title']; ?>
											</option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_account_mail; ?>">
											<?= $entry_account_mail; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_account_mail) { ?>
											<input type="radio" name="config_account_mail" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_account_mail" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_account_mail) { ?>
											<input type="radio" name="config_account_mail" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_account_mail" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-api"><span data-toggle="tooltip"
											title="<?= $help_api; ?>">
											<?= $entry_api; ?>
										</span></label>
									<div class="col-sm-9">
										<select name="config_api_id" id="input-api" class="form-control">
											<option value="0">
												<?= $text_none; ?>
											</option>
											<?php foreach ($apis as $api) { ?>
											<?php if ($api['api_id'] == $config_api_id) { ?>
											<option value="<?= $api['api_id']; ?>" selected="selected">
												<?= $api['name']; ?>
											</option>
											<?php } else { ?>
											<option value="<?= $api['api_id']; ?>">
												<?= $api['name']; ?>
											</option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_captcha; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_captcha; ?>">
											<?= $entry_captcha; ?>
										</span></label>
									<div class="col-sm-9">
										<select name="config_captcha" id="input-return" class="form-control">
											<option value="">
												<?= $text_none; ?>
											</option>
											<?php foreach ($captchas as $captcha) { ?>
											<?php if ($captcha['value'] == $config_captcha) { ?>
											<option value="<?= $captcha['value']; ?>" selected="selected">
												<?= $captcha['text']; ?>
											</option>
											<?php } else { ?>
											<option value="<?= $captcha['value']; ?>">
												<?= $captcha['text']; ?>
											</option>
											<?php } ?>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">
										<?= $entry_captcha_page; ?>
									</label>
									<div class="col-sm-9">
										<div class="well well-sm" style="height: 150px; overflow: auto;">
											<?php foreach ($captcha_pages as $captcha_page) { ?>
											<div class="checkbox">
												<label>
													<?php if (in_array($captcha_page['value'], $config_captcha_page)) { ?>
													<input type="checkbox" name="config_captcha_page[]" value="<?= $captcha_page['value']; ?>"
														checked="checked" />
													<?= $captcha_page['text']; ?>
													<?php } else { ?>
													<input type="checkbox" name="config_captcha_page[]" value="<?= $captcha_page['value']; ?>" />
													<?= $captcha_page['text']; ?>
													<?php } ?>
												</label>
											</div>
											<?php } ?>
										</div>
									</div>
								</div>
							</fieldset>
						</div>
						<div class="tab-pane" id="tab-image">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-logo">
									<?= $entry_logo; ?>
								</label>
								<div class="col-sm-9"><a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img
											src="<?= $logo; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" /></a>
									<input type="hidden" name="config_logo" value="<?= $config_logo; ?>" id="input-logo" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-icon"><span data-toggle="tooltip"
										title="<?= $help_icon; ?>">
										<?= $entry_icon; ?>
									</span></label>
								<div class="col-sm-9"><a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img
											src="<?= $icon; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" /></a>
									<input type="hidden" name="config_icon" value="<?= $config_icon; ?>" id="input-icon" />
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-ftp">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-ftp-host">
									<?= $entry_ftp_hostname; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_ftp_hostname" value="<?= $config_ftp_hostname; ?>"
										placeholder="<?= $entry_ftp_hostname; ?>" id="input-ftp-host" class="form-control" />
									<?php if ($error_ftp_hostname) { ?>
									<div class="text-danger">
										<?= $error_ftp_hostname; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-ftp-port">
									<?= $entry_ftp_port; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_ftp_port" value="<?= $config_ftp_port; ?>"
										placeholder="<?= $entry_ftp_port; ?>" id="input-ftp-port" class="form-control" />
									<?php if ($error_ftp_port) { ?>
									<div class="text-danger">
										<?= $error_ftp_port; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-ftp-username">
									<?= $entry_ftp_username; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_ftp_username" value="<?= $config_ftp_username; ?>"
										placeholder="<?= $entry_ftp_username; ?>" id="input-ftp-username" class="form-control" />
									<?php if ($error_ftp_username) { ?>
									<div class="text-danger">
										<?= $error_ftp_username; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-ftp-password">
									<?= $entry_ftp_password; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_ftp_password" value="<?= $config_ftp_password; ?>"
										placeholder="<?= $entry_ftp_password; ?>" id="input-ftp-password" class="form-control" />
									<?php if ($error_ftp_password) { ?>
									<div class="text-danger">
										<?= $error_ftp_password; ?>
									</div>
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-ftp-root"><span data-toggle="tooltip" data-html="true"
										title="<?= htmlspecialchars($help_ftp_root); ?>">
										<?= $entry_ftp_root; ?>
									</span></label>
								<div class="col-sm-9">
									<input type="text" name="config_ftp_root" value="<?= $config_ftp_root; ?>"
										placeholder="<?= $entry_ftp_root; ?>" id="input-ftp-root" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label">
									<?= $entry_ftp_status; ?>
								</label>
								<div class="col-sm-9">
									<label class="radio-inline">
										<?php if ($config_ftp_status) { ?>
										<input type="radio" name="config_ftp_status" value="1" checked="checked" />
										<?= $text_yes; ?>
										<?php } else { ?>
										<input type="radio" name="config_ftp_status" value="1" />
										<?= $text_yes; ?>
										<?php } ?>
									</label>
									<label class="radio-inline">
										<?php if (!$config_ftp_status) { ?>
										<input type="radio" name="config_ftp_status" value="0" checked="checked" />
										<?= $text_no; ?>
										<?php } else { ?>
										<input type="radio" name="config_ftp_status" value="0" />
										<?= $text_no; ?>
										<?php } ?>
									</label>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-mail">
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-mail-protocol"><span data-toggle="tooltip"
										title="<?= $help_mail_protocol; ?>">
										<?= $entry_mail_protocol; ?>
									</span></label>
								<div class="col-sm-9">
									<select name="config_mail_protocol" id="input-mail-protocol" class="form-control">
										<?php foreach ($mail_protocols as $mail_protocol) { ?>
										<?php if ($mail_protocol['protocol'] == $config_mail_protocol) { ?>
										<option value="<?= $mail_protocol['protocol']; ?>" selected="selected">
											<?= $mail_protocol['text']; ?>
										</option>
										<?php } else { ?>
										<option value="<?= $mail_protocol['protocol']; ?>">
											<?= $mail_protocol['text']; ?>
										</option>
										<?php } ?>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-mail-parameter"><span data-toggle="tooltip"
										title="<?= $help_mail_parameter; ?>">
										<?= $entry_mail_parameter; ?>
									</span></label>
								<div class="col-sm-9">
									<input type="text" name="config_mail_parameter" value="<?= $config_mail_parameter; ?>"
										placeholder="<?= $entry_mail_parameter; ?>" id="input-mail-parameter" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-mail-smtp-hostname"><span data-toggle="tooltip"
										title="<?= $help_mail_smtp_hostname; ?>">
										<?= $entry_mail_smtp_hostname; ?>
									</span></label>
								<div class="col-sm-9">
									<input type="text" name="config_mail_smtp_hostname" value="<?= $config_mail_smtp_hostname; ?>"
										placeholder="<?= $entry_mail_smtp_hostname; ?>" id="input-mail-smtp-hostname"
										class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-mail-smtp-username">
									<?= $entry_mail_smtp_username; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_mail_smtp_username" value="<?= $config_mail_smtp_username; ?>"
										placeholder="<?= $entry_mail_smtp_username; ?>" id="input-mail-smtp-username"
										class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-mail-smtp-password"><span data-toggle="tooltip"
										title="<?= $help_mail_smtp_password; ?>">
										<?= $entry_mail_smtp_password; ?>
									</span></label>
								<div class="col-sm-9">
									<input type="text" name="config_mail_smtp_password" value="<?= $config_mail_smtp_password; ?>"
										placeholder="<?= $entry_mail_smtp_password; ?>" id="input-mail-smtp-password"
										class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-mail-smtp-port">
									<?= $entry_mail_smtp_port; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_mail_smtp_port" value="<?= $config_mail_smtp_port; ?>"
										placeholder="<?= $entry_mail_smtp_port; ?>" id="input-mail-smtp-port" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-mail-smtp-timeout">
									<?= $entry_mail_smtp_timeout; ?>
								</label>
								<div class="col-sm-9">
									<input type="text" name="config_mail_smtp_timeout" value="<?= $config_mail_smtp_timeout; ?>"
										placeholder="<?= $entry_mail_smtp_timeout; ?>" id="input-mail-smtp-timeout" class="form-control" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-alert-email"><span data-toggle="tooltip"
										title="<?= $help_mail_alert; ?>">
										<?= $entry_mail_alert; ?>
									</span></label>
								<div class="col-sm-9">
									<textarea name="config_mail_alert" rows="5" placeholder="<?= $entry_mail_alert; ?>"
										id="input-alert-email" class="form-control"><?= $config_mail_alert; ?></textarea>
								</div>
							</div>
						</div>
						<div class="tab-pane" id="tab-server">
							<fieldset>
								<legend>
									<?= $text_general; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_admin_maintenance; ?>">
											<?= $entry_admin_maintenance; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_admin_maintenance) { ?>
											<input type="radio" name="config_admin_maintenance" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_admin_maintenance" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_admin_maintenance) { ?>
											<input type="radio" name="config_admin_maintenance" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_admin_maintenance" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_maintenance; ?>">
											<?= $entry_maintenance; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_maintenance) { ?>
											<input type="radio" name="config_maintenance" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_maintenance" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_maintenance) { ?>
											<input type="radio" name="config_maintenance" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_maintenance" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_seo_url; ?>">
											<?= $entry_seo_url; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_seo_url) { ?>
											<input type="radio" name="config_seo_url" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_seo_url" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_seo_url) { ?>
											<input type="radio" name="config_seo_url" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_seo_url" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-robots"><span data-toggle="tooltip"
											title="<?= $help_robots; ?>">
											<?= $entry_robots; ?>
										</span></label>
									<div class="col-sm-9">
										<textarea name="config_robots" rows="5" placeholder="<?= $entry_robots; ?>" id="input-robots"
											class="form-control"><?= $config_robots; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-compression"><span data-toggle="tooltip"
											title="<?= $help_compression; ?>">
											<?= $entry_compression; ?>
										</span></label>
									<div class="col-sm-9">
										<input type="text" name="config_compression" value="<?= $config_compression; ?>"
											placeholder="<?= $entry_compression; ?>" id="input-compression" class="form-control" />
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_security; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_secure; ?>">
											<?= $entry_secure; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_secure) { ?>
											<input type="radio" name="config_secure" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_secure" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_secure) { ?>
											<input type="radio" name="config_secure" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_secure" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_password; ?>">
											<?= $entry_password; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_password) { ?>
											<input type="radio" name="config_password" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_password" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_password) { ?>
											<input type="radio" name="config_password" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_password" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"><span data-toggle="tooltip" title="<?= $help_shared; ?>">
											<?= $entry_shared; ?>
										</span></label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_shared) { ?>
											<input type="radio" name="config_shared" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_shared" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_shared) { ?>
											<input type="radio" name="config_shared" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_shared" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-encryption"><span data-toggle="tooltip"
											title="<?= $help_encryption; ?>">
											<?= $entry_encryption; ?>
										</span></label>
									<div class="col-sm-9">
										<textarea name="config_encryption" rows="5" placeholder="<?= $entry_encryption; ?>"
											id="input-encryption" class="form-control"><?= $config_encryption; ?></textarea>
										<?php if ($error_encryption) { ?>
										<div class="text-danger">
											<?= $error_encryption; ?>
										</div>
										<?php } ?>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_upload; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-file-max-size"><span data-toggle="tooltip"
											title="<?= $help_file_max_size; ?>">
											<?= $entry_file_max_size; ?>
										</span></label>
									<div class="col-sm-9">
										<input type="text" name="config_file_max_size" value="<?= $config_file_max_size; ?>"
											placeholder="<?= $entry_file_max_size; ?>" id="input-file-max-size" class="form-control" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-file-ext-allowed"><span data-toggle="tooltip"
											title="<?= $help_file_ext_allowed; ?>">
											<?= $entry_file_ext_allowed; ?>
										</span></label>
									<div class="col-sm-9">
										<textarea name="config_file_ext_allowed" rows="5" placeholder="<?= $entry_file_ext_allowed; ?>"
											id="input-file-ext-allowed" class="form-control"><?= $config_file_ext_allowed; ?></textarea>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label" for="input-file-mime-allowed"><span data-toggle="tooltip"
											title="<?= $help_file_mime_allowed; ?>">
											<?= $entry_file_mime_allowed; ?>
										</span></label>
									<div class="col-sm-9">
										<textarea name="config_file_mime_allowed" rows="5" placeholder="<?= $entry_file_mime_allowed; ?>"
											id="input-file-mime-allowed" class="form-control"><?= $config_file_mime_allowed; ?></textarea>
									</div>
								</div>
							</fieldset>
							<fieldset>
								<legend>
									<?= $text_error; ?>
								</legend>
								<div class="form-group">
									<label class="col-sm-3 control-label">
										<?= $entry_error_display; ?>
									</label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_error_display) { ?>
											<input type="radio" name="config_error_display" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_error_display" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_error_display) { ?>
											<input type="radio" name="config_error_display" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_error_display" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-3 control-label">
										<?= $entry_error_log; ?>
									</label>
									<div class="col-sm-9">
										<label class="radio-inline">
											<?php if ($config_error_log) { ?>
											<input type="radio" name="config_error_log" value="1" checked="checked" />
											<?= $text_yes; ?>
											<?php } else { ?>
											<input type="radio" name="config_error_log" value="1" />
											<?= $text_yes; ?>
											<?php } ?>
										</label>
										<label class="radio-inline">
											<?php if (!$config_error_log) { ?>
											<input type="radio" name="config_error_log" value="0" checked="checked" />
											<?= $text_no; ?>
											<?php } else { ?>
											<input type="radio" name="config_error_log" value="0" />
											<?= $text_no; ?>
											<?php } ?>
										</label>
									</div>
								</div>
								<div class="form-group required">
									<label class="col-sm-3 control-label" for="input-error-filename">
										<?= $entry_error_filename; ?>
									</label>
									<div class="col-sm-9">
										<input type="text" name="config_error_filename" value="<?= $config_error_filename; ?>"
											placeholder="<?= $entry_error_filename; ?>" id="input-error-filename" class="form-control" />
										<?php if ($error_error_filename) { ?>
										<div class="text-danger">
											<?= $error_error_filename; ?>
										</div>
										<?php } ?>
									</div>
								</div>
							</fieldset>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		$('select[name=\'config_theme\']').on('change', function () {
			$.ajax({
				url: 'index.php?route=setting/setting/theme&token=<?= $token; ?>&theme=' + this.value,
				dataType: 'html',
				beforeSend: function () {
					$('select[name=\'config_theme\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
				},
				complete: function () {
					$('.fa-spin').remove();
				},
				success: function (html) {
					$('#theme').attr('src', html);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('select[name=\'config_theme\']').trigger('change');
	</script>
	<script type="text/javascript">
		$('select[name=\'config_country_id\']').on('change', function () {
			$.ajax({
				url: 'index.php?route=localisation/country/country&token=<?= $token; ?>&country_id=' + this.value,
				dataType: 'json',
				beforeSend: function () {
					$('select[name=\'config_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
				},
				complete: function () {
					$('.fa-spin').remove();
				},
				success: function (json) {
					html = '<option value=""><?= $text_select; ?></option>';

					if (json['zone'] && json['zone'] != '') {
						for (i = 0; i < json['zone'].length; i++) {
							html += '<option value="' + json['zone'][i]['zone_id'] + '"';

							if (json['zone'][i]['zone_id'] == '<?= $config_zone_id; ?>') {
								html += ' selected="selected"';
							}

							html += '>' + json['zone'][i]['name'] + '</option>';
						}
					} else {
						html += '<option value="0" selected="selected"><?= $text_none; ?></option>';
					}

					$('select[name=\'config_zone_id\']').html(html);

					$('#button-save').prop('disabled', false);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('select[name=\'config_country_id\']').trigger('change');
	</script>
</div>
<?= $footer; ?>