<?php
class ControllerLocalisationPayrollMethod extends Controller
{
	private $error = array();
	private $filter_items = array();

	private function urlFilter($excluded_item = null)
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$url_filter .= '&filter_' . $filter_item . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $filter_item], ENT_QUOTES, 'UTF-8'));
			}
		}

		if ($excluded_item != 'sort') {
			if (isset($this->request->get['sort'])) {
				$url_filter .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url_filter .= '&order=' . $this->request->get['order'];
			}
		}

		if (isset($this->request->get['page']) && $excluded_item != 'page') {
			$url_filter .= '&page=' . $this->request->get['page'];
		}

		return $url_filter;
	}

	public function index()
	{
		$this->load->language('localisation/payroll_method');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/payroll_method');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('localisation/payroll_method');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/payroll_method');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_localisation_payroll_method->addPayrollMethod($this->request->post);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('localisation/payroll_method');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/payroll_method');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_localisation_payroll_method->editPayrollMethod($this->request->get['payroll_method_id'], $this->request->post);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('localisation/payroll_method');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/payroll_method');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$this->db->transaction(function () {
				foreach ($this->request->post['selected'] as $payroll_method_id) {
					$this->model_localisation_payroll_method->deletePayrollMethod($payroll_method_id);
				}
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			// 'column_code',
			'column_name',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/payroll_method/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/payroll_method/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['payroll_methods'] = array();
		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_localisation_payroll_method->getPayrollMethods($filter_data);

		foreach ($results as $result) {
			$data['payroll_methods'][] = array(
				'payroll_method_id'	=> $result['payroll_method_id'],
				'name'				=> $result['name'],
				// 'code'				=> $result['code'],
				'edit'				=> $this->url->link('localisation/payroll_method/edit', 'token=' . $this->session->data['token'] . '&payroll_method_id=' . $result['payroll_method_id'] . $url, true)
			);
		}
		$payroll_method_count = $this->model_localisation_payroll_method->getTotalPayrollMethods();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = $this->urlFilter('sort');

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_name'] = $this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $payroll_method_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_method_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($payroll_method_count - $limit)) ? $payroll_method_count : ((($page - 1) * $limit) + $limit), $payroll_method_count, ceil($payroll_method_count / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/payroll_method_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['payroll_method_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_name',
			'entry_code',
			'entry_sort_order',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$errors = array(
			'warning',
			'name',
			'code'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . $url, true)
		);

		$payroll_method_id = isset($this->request->get['payroll_method_id']) ? $this->request->get['payroll_method_id'] : 0;

		if (!$payroll_method_id) {
			$data['action'] = $this->url->link('localisation/payroll_method/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/payroll_method/edit', 'token=' . $this->session->data['token'] . '&payroll_method_id=' . $this->request->get['payroll_method_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'] . $url, true);

		if ($payroll_method_id) {
			$payroll_method_info = $this->model_localisation_payroll_method->getPayrollMethod($payroll_method_id);
		}

		$this->load->model('localisation/language');
		$data['languages'] = $this->model_localisation_language->getLanguages();

		if (isset($this->request->post['payroll_method'])) {
			$data['payroll_method'] = $this->request->post['payroll_method'];
		} elseif (isset($this->request->get['payroll_method_id'])) {
			$data['payroll_method'] = $this->model_localisation_payroll_method->getPayrollMethodDescriptions($this->request->get['payroll_method_id']);
		} else {
			$data['payroll_method'] = array();
		}

		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($payroll_method_info)) {
			$data['code'] = $payroll_method_info['code'];
		} else {
			$data['code'] = null;
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/payroll_method_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'localisation/payroll_method')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['payroll_method'] as $language_id => $value) {
			if ((utf8_strlen($value['name']) < 3) || (utf8_strlen($value['name']) > 32)) {
				$this->error['name'][$language_id] = $this->language->get('error_name');
			}
		}

		if ((utf8_strlen($this->request->post['code']) < 3) || (utf8_strlen(trim($this->request->post['code'])) > 32)) {
			$this->error['code'] = $this->language->get('error_code');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'localisation/payroll_method')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/customer');

		foreach ($this->request->post['selected'] as $payroll_method_id) {
			$customer_total = $this->model_customer_customer->getTotalCustomersByPayrollMethodId($payroll_method_id);

			if ($customer_total) {
				$this->error['warning'] = sprintf($this->language->get('error_customer_total'), $customer_total);
			}
		}

		return !$this->error;
	}
}
