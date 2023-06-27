<?php
class ControllerCustomerPresenceMethod extends Controller
{
	private $error = array();
	private $filter_items = array(
		'name',
		'customer_group_id',
		'location_id',
		'status'
	);

	private function urlFilter()
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$url_filter .= '&filter_' . $filter_item . '=' . $this->request->get['filter_' . $filter_item];
			}
		}

		return $url_filter;
	}

	public function index()
	{
		$this->load->language('customer/presence_method');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/presence_method');
		$this->load->model('presence/presence');

		$this->getList();
	}

	public function edit()
	{
		$this->load->language('customer/presence_method');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/presence_method');
		$this->load->model('common/payroll');

		$this->getForm();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_all',
			// 'text_all_location',
			'text_active',
			'text_inactive',
			// 'text_all_status',
			'entry_name',
			'entry_customer_group',
			'entry_location',
			'entry_status',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_location',
			'column_action',
			'button_filter',
			'button_edit'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'], true)
		);

		$data['presence_methods'] = [];
		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		foreach ($filter as $key => $value) {
			$filter_data['filter_' . $key] = $value;
		}

		$presence_methods = [];

		$customer_count = $this->model_presence_presence->getTotalCustomers($filter_data);

		$results = $this->model_presence_presence->getCustomers($filter_data);

		$presence_types = $this->model_customer_presence_method->getPresenceTypes();

		$presence_methods = $this->model_customer_presence_method->getPresenceMethods();

		foreach ($presence_methods as $presence_method) {
			$customer_method_data[$presence_method['customer_id']][$presence_method['code']] = true;
		}

		foreach ($results as $result) {
			foreach ($presence_types as $presence_type) {
				if (isset($customer_method_data[$result['customer_id']][$presence_type])) {
					$customer_method[$presence_type] = $customer_method_data[$result['customer_id']][$presence_type];
				} else {
					$customer_method[$presence_type] = false;
				}
			}

			$data['presence_methods'][] = array(
				'customer_id' 			=> $result['customer_id'],
				'nip' 					=> $result['nip'],
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'location' 				=> $result['location'],
				'customer_method'		=> $customer_method,
				'edit'          		=> $this->url->link('customer/presence_method/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
			);
		}

		$url = $this->urlFilter();

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_nip'] = $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($customer_count - $limit)) ? $customer_count : ((($page - 1) * $limit) + $limit), $customer_count, ceil($customer_count / $limit));

		$data['token'] = $this->session->data['token'];

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['presence_types'] = [];
		foreach ($presence_types as $presence_type) {
			$data['presence_types'][] = [
				'value'	=> $presence_type,
				'text'	=> $this->language->get('text_' . $presence_type)
			];
		}

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/presence_method_list', $data));
	}

	protected function getForm()
	{
		$data['text_edit'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_edit',
			// 'text_loading',
			'text_confirm',
			// 'text_missing',
			'text_no_results',
			'column_presence_method',
			// 'column_filename',
			'column_status',
			// 'column_date_added',
			// 'column_username',
			// 'column_action',
			// 'button_upload',
			// 'button_view',
			// 'button_delete',
			'button_back',
			// 'button_print'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$customer_id = isset($this->request->get['customer_id']) ? $this->request->get['customer_id'] : 0;

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$url = $this->urlFilter();

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'], true)
		);

		$customer_info = $this->model_common_payroll->checkCustomer($customer_id);

		if (!$customer_info) {
			$this->response->redirect($this->url->link('customer/presence_method', 'token=' . $this->session->data['token'] . $url, true));
		} else {
			$data['action'] = $this->url->link('customer/presence_method/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . $url, true);
		}

		$data['back'] = $this->url->link('customer/presence_method', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_edit'],
			'href' => $data['action']
		);

		$data['presence_methods'] = [];
		$presence_methods = [];

		$presence_types = $this->model_customer_presence_method->getPresenceTypes();

		$presence_methods = $this->model_customer_presence_method->getPresenceMethodsByCustomer($customer_id);
		$customer_methods = array_column($presence_methods, 'code');

		foreach ($presence_types as $presence_type) {
			if (in_array($presence_type, $customer_methods)) {
				$data[$presence_type] = true;
			} else {
				$data[$presence_type] = false;
			}
		}

		$data['customer_id'] = $customer_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/presence_method_form', $data));
	}

	public function delete()
	{
		$this->load->language('customer/presence_method');

		$json = array();

		if (!$this->validateDelete()) {
			$json['error'] = $this->error['warning'];
		} else {
			$presence_method_id = isset($this->request->get['presence_method_id']) ? $this->request->get['presence_method_id'] : 0;

			$this->load->model('customer/presence_method');

			$presence_method = $this->model_customer_presence_method->getDocument($presence_method_id);

			if (isset($presence_method['filename']) && file_exists(DIR_DOCUMENT . $presence_method['filename'])) {
				unlink(DIR_DOCUMENT . $presence_method['filename']);
			}

			$this->model_customer_presence_method->deleteDocument($presence_method_id);

			$this->session->data['success'] = $this->language->get('text_success_delete');

			$json['success'] = 1;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('bypass', 'customer/presence_method')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
