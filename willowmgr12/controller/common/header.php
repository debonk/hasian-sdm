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

		if ($this->registry->get('framework_load') == 'update') {
			$data['text_framework_update'] = $this->language->get('text_framework_update');
		}

		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_customer'] = $this->language->get('text_customer');
		$data['text_online'] = $this->language->get('text_online');
		$data['text_store'] = $this->language->get('text_store');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->user->getUserName());
		$data['text_logout'] = $this->language->get('text_logout');

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

			// Products
			// $this->load->model('catalog/product');

			// $product_total = $this->model_catalog_product->getTotalProducts(array('filter_quantity' => 0));

			// $data['product_total'] = $product_total;

			// $data['product'] = $this->url->link('catalog/product', 'token=' . $this->session->data['token'] . '&filter_quantity=0', true);

			// Reviews
			// $this->load->model('catalog/review');

			// $review_total = $this->model_catalog_review->getTotalReviews(array('filter_status' => false));

			// $data['review_total'] = $review_total;

			// $data['review'] = $this->url->link('catalog/review', 'token=' . $this->session->data['token'] . '&filter_status=0', true);

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
