<?php
class ControllerModuleAccount extends Controller {
	public function index() {
		$this->load->language('module/account');

		$language_items = [
			'heading_title',
			'text_logout',
			'text_forgotten',
			'text_account',
			'text_general',
			'text_password',
			'text_history',
			'text_schedule',
			'text_payroll_basic',
			'text_payroll',
			'text_vacation'
			// 'text_download',
			// 'text_newsletter'		
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		$data['logged'] = $this->customer->isLogged();

		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['forgotten'] = $this->url->link('account/forgotten', '', true);
		$data['account'] = $this->url->link('account/account', '', true);
		$data['general'] = $this->url->link('account/edit', '', true);
		$data['schedule'] = $this->url->link('account/schedule', '', true);
		$data['payroll_basic'] = $this->url->link('account/payroll_basic', '', true);
		$data['payroll'] = $this->url->link('account/payroll', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['vacation'] = $this->url->link('account/vacation', '', true);
		// $data['download'] = $this->url->link('account/download', '', true);
		// $data['newsletter'] = $this->url->link('account/newsletter', '', true);

		return $this->load->view('module/account', $data);
	}
}