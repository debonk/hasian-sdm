<?php
class ControllerCommonHeader extends Controller
{
	public function index()
	{
		$data['title'] = $this->document->getTitle();

		$this->load->language('common/header');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		if (is_file(DIR_IMAGE . 'favicon.ico')) {
			$this->document->addLink(HTTP_CATALOG . 'image/favicon.ico', 'icon');
		}

		if ($this->registry->get('framework_load') == 'update') {
			$data['text_framework_update'] = $this->language->get('text_framework_update');
		} else {
			$data['text_framework_update'] = '';
		}

		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$language_items = [
			'heading_title',
			'text_customer',
			'text_online',
			'text_store',
			'text_logout',
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());

		if (!isset($this->request->get['token']) || !isset($this->session->data['token']) || ($this->request->get['token'] != $this->session->data['token'])) {
			$data['logged'] = '';

			$data['home'] = $this->url->link('common/dashboard', '', true);
		} else {
			$data['logged'] = true;

			$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
			$data['logout'] = $this->url->link('common/logout', 'token=' . $this->session->data['token'], true);

			if ($this->config->get('config_admin_maintenance')) {
				$data['text_maintenance'] = $this->language->get('text_maintenance');
				$data['maintenance'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);
			}

			// Customers
			$this->load->model('report/customer');

			$data['online_total'] = $this->model_report_customer->getTotalCustomersOnline();

			$data['online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);

			$this->load->model('customer/customer');

			// $customer_total = $this->model_customer_customer->getTotalCustomers(array('filter_approved' => false));

			// $data['customer_total'] = $customer_total;
			// $data['customer_approval'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&filter_approved=0', true);

			$data['alerts'] = $data['online_total'];

			// Online Stores
			$data['stores'] = array();

			$data['stores'][] = array(
				'name' => $this->config->get('config_name'),
				'href' => HTTP_CATALOG
			);

			$this->load->model('setting/store');

			$results = $this->model_setting_store->getStores();

			foreach ($results as $result) {
				$data['stores'][] = array(
					'name' => $result['name'],
					'href' => $result['url']
				);
			}
		}

		return $this->load->view('common/header', $data);
	}
}
