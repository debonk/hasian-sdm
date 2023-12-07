<?php
class ControllerCustomerContract extends Controller
{
	private $error = array();
	private $filter_items = array(
		'name',
		'customer_group_id',
		'customer_department_id',
		'location_id',
		'contract_status',
		'active'
	);

	private function urlFilter($excluded_item = null)
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$url_filter .= '&filter_' . $filter_item . '=' . $this->request->get['filter_' . $filter_item];
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
		$this->load->language('customer/contract');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/contract');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('customer/contract');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/contract');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_customer_contract->AddContract($this->request->get['customer_id'], $this->request->post);

				# Add to customer_history log
				$this->load->model('customer/history');
				
				$history_data = array(
					'date'				=> $this->request->post['contract_start'],
					'customer_id' 		=> $this->request->get['customer_id'],
					'name'        		=> '',
					'contract_end' 		=> isset($this->request->post['contract_end']) ? $this->request->post['contract_end'] : '...'
				);
				
				$this->model_customer_history->addHistory('contract', $history_data);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('customer/contract/add', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true));
		}

		$this->getForm();
	}

	public function resign()
	{
		$this->load->language('customer/contract');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/contract');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateResign()) {
			$this->db->transaction(function () {
				$this->model_customer_contract->endContract($this->request->get['customer_id'], $this->request->post);

				# Add to customer_history log
				$this->load->model('customer/history');

				$history_data = array(
					'date' 			=> $this->request->post['date_end'],
					'customer_id' 	=> $this->request->get['customer_id'],
					'name'        	=> ''
				);
				
				$this->model_customer_history->addHistory('date_end', $history_data);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('customer/contract/add', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true));
		}

		$this->getForm();
	}

	protected function getList()
	{
		$this->db->createView('v_contract');

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_all',
			'text_active',
			'text_inactive',
			'entry_name',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'entry_contract_status',
			'entry_status',
			'column_date',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_customer_department',
			'column_location',
			'column_action',
			'column_duration',
			'column_contract_type',
			'column_contract_start',
			'column_contract_end',
			'column_contract_status',
			'button_filter',
			'button_delete',
			'button_add',
			'button_view'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		if (empty($filter['active'])) {
			$filter['active'] = 1;
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
			'href' => $this->url->link('customer/contract', 'token=' . $this->session->data['token'], true)
		);

		$data['customers'] = array();

		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$customer_count = $this->model_customer_contract->getCustomerContractsCount($filter_data);

		$results = $this->model_customer_contract->getCustomerContracts($filter_data);

		foreach ($results as $result) {
			$bg_class = '';

			switch ($result['contract_status']) {
				case 'none':
					$contract_status = $this->language->get('text_contract_none');
					$bg_class = 'bg-warning';

					break;

				case 'inactive':
					$contract_status = $this->language->get('text_contract_inactive');
					$bg_class = 'bg-light-dark';

					break;

				case 'expired':
					$contract_status = $result['end_reason'];
					$bg_class = 'bg-light-dark';

					break;

				case 'end_today':
					$contract_status = $this->language->get('text_contract_end_today');
					$bg_class = 'bg-danger';

					break;

				case 'end_soon':
					$contract_end_left = date_diff(date_create($result['contract_end']), date_create(date('Y-m-d')))->days;

					$contract_status = sprintf($this->language->get('text_contract_end_left'), $contract_end_left);
					$bg_class = 'bg-warning';

					break;

				default:
					$contract_status = $this->language->get('text_contract_' . $result['contract_status']);

					break;
			}

			if ($result['contract_type']) {
				$contract_type = $result['contract_type'] . ' (' . ($result['duration'] ? sprintf($this->language->get('text_month'), ($result['duration'])) : $this->language->get('text_contract_permanent')) . ')';
			} else {
				$contract_type = '';
			}

			$data['customers'][] = array(
				'customer_id' 			=> $result['customer_id'],
				'nip' 					=> $result['nip'],
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'contract_type'    		=> $contract_type,
				'contract_start'   		=> $result['contract_start'],
				'contract_end'   		=> $result['contract_end'],
				'contract_status'  		=> $contract_status,
				'bg_class'	    		=> $bg_class,
				'add'          			=> $this->url->link('customer/contract/add', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'view'          		=> $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true),
			);
		}

		# Information
		$data['informations'] = [];

		$contract_summaries = $this->model_customer_contract->getCustomerContractSummaries();

		foreach ($contract_summaries as $contract_summary) {
			if (in_array($contract_summary['contract_status'], ['none', 'end_soon', 'end_today'])) {
				$href = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&filter_contract_status=' . $contract_summary['contract_status'], true);
				$data['informations'][] = sprintf($this->language->get('text_information'), $contract_summary['total'], $href, $this->language->get('text_contract_' . $contract_summary['contract_status']));
			}
		}

		$data['token'] = $this->session->data['token'];

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

		$url = $this->urlFilter('sort');

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_nip'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_customer_department'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_duration'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=duration' . $url, true);
		$data['sort_contract_type'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=contract_type' . $url, true);
		$data['sort_contract_start'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=contract_start' . $url, true);
		$data['sort_contract_end'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=contract_end' . $url, true);
		$data['sort_contract_status'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&sort=contract_status' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $customer_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($customer_count - $limit)) ? $customer_count : ((($page - 1) * $limit) + $limit), $customer_count, ceil($customer_count / $limit));

		$data['filter_items'] = json_encode($this->filter_items);
		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('customer/customer_department');
		$data['customer_departments'] = $this->model_customer_customer_department->getCustomerDepartments();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['contract_statuses'] = $this->model_customer_contract->getContractStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/contract_list', $data));
	}

	protected function getForm()
	{
		$this->db->createView('v_contract');

		$language_items = array(
			'heading_title',
			'text_add',
			'text_select',
			'text_confirm',
			'text_apply',
			'text_resign',
			'text_history',
			'entry_contract_type',
			'entry_contract_start',
			'entry_contract_end',
			'entry_description',
			'entry_date_end',
			'entry_end_reason',
			'button_delete',
			'button_save',
			'button_resign',
			'button_cancel',
			'help_resign'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$customer_id = isset($this->request->get['customer_id']) ? $this->request->get['customer_id'] : 0;

		$error_items = [
			'warning',
			'contract_type',
			'contract_start',
			'contract_end',
			'date_end',
			'end_reason'
		];
		foreach ($error_items as $error_item) {
			$data['error_' . $error_item] = isset($this->error[$error_item]) ? $this->error[$error_item] : '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!$customer_id) {
			$this->response->redirect($this->url->link('customer/contract', 'token=' . $this->session->data['token'] . $url, true));
		} else {
			$data['action'] = $this->url->link('customer/contract/add', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . $url, true);
			$data['resign'] = $this->url->link('customer/contract/resign', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . $url, true);
		}

		$data['cancel'] = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_add'],
			'href' => $data['action']
		);

		if ($customer_id && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$contract_info = $this->model_customer_contract->getCustomerContract($customer_id);
		}

		$contract_items = array(
			'contract_type_id',
			'contract_end',
			'description',
			'date_end',
			'end_reason'
		);
		foreach ($contract_items as $contract_item) {
			if (isset($this->request->post[$contract_item])) {
				$data[$contract_item] = $this->request->post[$contract_item];
			} else {
				$data[$contract_item] = '';
			}
		}

		if (isset($this->request->post['contract_start'])) {
			$data['contract_start'] = $this->request->post['contract_start'];
		} elseif (!empty($contract_info)) {
			if (!$contract_info['contract_id']) {
				$data['contract_start'] = date($this->language->get('date_format_jMY'), strtotime($contract_info['date_start']));
			} else {
				$data['contract_start'] = $contract_info['contract_end'] ? date($this->language->get('date_format_jMY'), strtotime('+1 day', strtotime($contract_info['contract_end']))) : '';
			}
		} else {
			$data['contract_start'] = '';
		}

		$data['contract_types'] = [];

		$this->load->model('customer/contract_type');
		$contract_types = $this->model_customer_contract_type->getContractTypes();

		foreach ($contract_types as $contract_type) {
			$data['contract_types'][] = [
				'index'	=> $contract_type['contract_type_id'],
				'name'	=> $contract_type['name'] . ' (' . ($contract_type['duration'] ? sprintf($this->language->get('text_month'), ($contract_type['duration'])) : $this->language->get('text_contract_permanent')) . ')'
			];
		}

		$data['customer_id'] = $customer_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/contract_form', $data));
	}

	public function history()
	{
		$this->load->language('customer/contract');

		$this->load->model('customer/contract');

		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$data['histories'] = array();

		$results = $this->model_customer_contract->getContractHistories($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			if ($result['contract_end']) {
				$duration = sprintf($this->language->get('text_month'), $result['duration']);
			} else {
				$duration = $this->language->get('text_contract_permanent');
			}

			if (!$result['contract_type_id']) {
				$result['contract_type'] = $this->language->get('text_resign');

				$duration = '';
			}

			$data['histories'][] = array(
				'contract_type_id' 	=> $result['contract_type_id'],
				'contract_type' 	=> $result['contract_type'] . ($duration ? ' (' . $duration . ')' : ''),
				'contract_start' 	=> $result['contract_start'] ? date($this->language->get('date_format_jMY'), strtotime($result['contract_start'])) : '-',
				'contract_end' 		=> $result['contract_end'] ? date($this->language->get('date_format_jMY'), strtotime($result['contract_end'])) : '-',
				'description' 		=> $result['description'],
				'date_added' 		=> date($this->language->get('date_format_jMY'), strtotime($result['date_added'])),
				'username' 			=> $result['username']
			);
		}

		$history_total = $this->model_customer_contract->getTotalContractHistories($this->request->get['customer_id']);

		$language_items = array(
			'text_no_results',
			'column_contract_type',
			'column_contract_start',
			'column_contract_end',
			'column_description',
			'column_date_added',
			'column_username'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('customer/contract/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('customer/contract_history', $data));
	}

	public function delete()
	{
		$this->load->language('customer/contract');

		$json = array();

		if (!$this->validateDelete()) {
			$json['error'] = $this->error['warning'];
		} else {
			$this->load->model('customer/contract');

			$this->db->transaction(function () {
				$this->model_customer_contract->deleteContract($this->request->post['customer_id']);

				# Add to customer_history log
				$this->load->model('customer/history');

				$history_data = array(
					'date' 			=> date($this->language->get('date_format_jMY')),
					'customer_id' 	=> $this->request->post['customer_id'],
					'name'        	=> ''
				);
				
				$this->model_customer_history->addHistory('contract_delete', $history_data);
			});

			$this->session->data['success'] = $this->language->get('text_success_delete');
			$json['success'] = 1;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'customer/contract')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['contract_type_id'])) {
			$this->error['contract_type'] = $this->language->get('error_contract_type');
		}

		if (empty($this->request->post['contract_start'])) {
			$this->error['contract_start'] = $this->language->get('error_contract_start');
		} else {
			$this->load->model('customer/contract');
			$contract_info = $this->model_customer_contract->getCustomerContract($this->request->get['customer_id']);

			if ($contract_info && ($contract_info['contract_end'] >= date('Y-m-d', strtotime($this->request->post['contract_start'])) || $contract_info['date_start'] > date('Y-m-d', strtotime($this->request->post['contract_start'])))) {
				$this->error['contract_start'] = $this->language->get('error_contract_start');
			}
		}

		$this->load->model('customer/contract_type');
		$contract_type_info = $this->model_customer_contract_type->getContractType($this->request->post['contract_type_id']);

		if (!$contract_type_info || ($contract_type_info && $contract_type_info['duration'] > 0)) {
			if (empty($this->request->post['contract_end']) || strtotime($this->request->post['contract_start']) > strtotime($this->request->post['contract_end'])) {
				$this->error['contract_end'] = $this->language->get('error_contract_end');
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateResign()
	{
		if (!$this->user->hasPermission('modify', 'customer/contract')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['date_end'])) {
			$this->error['date_end'] = $this->language->get('error_date_end');
		}

		if (empty($this->request->post['end_reason'])) {
			$this->error['end_reason'] = $this->language->get('error_end_reason');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('bypass', 'customer/contract')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function autocomplete()
	{
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$this->load->model('customer/customer');

			$filter_data = array(
				'filter_name'	=> $filter_name,
				'filter_active'	=> '*',
				'start'       	=> 0,
				'limit'        	=> 15
			);

			$results = $this->model_customer_customer->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function contractType()
	{
		$json = [];

		$contract_type_id = !empty($this->request->get['contract_type_id']) ? $this->request->get['contract_type_id'] : 0;
		$contract_start = !empty($this->request->post['contract_start']) ? $this->request->post['contract_start'] : '';

		$this->load->model('customer/contract_type');

		$contract_type_info = $this->model_customer_contract_type->getContractType($contract_type_id);

		$json['locked'] = false;

		if (!$contract_start || !$contract_type_info || !$contract_type_info['duration']) {
			$json['contract_end'] = '';
		} else {
			$json['contract_end'] = date($this->language->get('date_format_jMY'), strtotime('+' . $contract_type_info['duration'] . ' months -1 day', strtotime($contract_start)));
		}

		if ($contract_type_info && !$contract_type_info['duration']) {
			$json['locked'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
