<?php
class ControllerLoanLoan extends Controller
{
	private $error = array();
	private $filter_items = array(
		'name',
		'customer_group_id',
		'customer_department_id',
		'location_id',
		'description',
		'status'
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
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_loan_loan->addLoan($this->request->post);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_loan_loan->editLoan($this->request->get['loan_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $loan_id) {
				$this->db->transaction(function () use ($loan_id) {
					$this->model_loan_loan->deleteLoan($loan_id);
				});
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_subtotal',
			'text_paid',
			'text_unpaid',
			'text_all',
			'text_confirm',
			'entry_name',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'entry_description',
			'entry_status',
			'column_date_added',
			'column_name',
			'column_customer_group',
			'column_customer_department',
			'column_location',
			'column_description',
			'column_amount',
			'column_installment',
			'column_balance',
			'column_action',
			'column_username',
			'button_filter',
			'button_add',
			'button_view',
			'button_edit',
			'button_delete',
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

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
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
			'href' => $this->url->link('loan/loan', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('loan/loan/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('loan/loan/delete', 'token=' . $this->session->data['token'] . $url, true);

		$limit = $this->config->get('config_limit_admin');

		$data['loans'] = array();

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_loan_loan->getLoans($filter_data);

		foreach ($results as $result) {
			$data['loans'][] = array(
				'loan_id' 				=> $result['loan_id'],
				'date_added' 			=> date($this->language->get('date_format_jMY'), strtotime($result['date_added'])),
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'description' 			=> $result['description'],
				'amount'    			=> $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'installment'    		=> $this->currency->format($result['installment'], $this->config->get('config_currency')),
				'balance'    			=> $this->currency->format($result['balance'], $this->config->get('config_currency')),
				'username' 				=> $result['username'],
				'view'          		=> $this->url->link('loan/loan/info', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'edit'          		=> $this->url->link('loan/loan/edit', 'token=' . $this->session->data['token'] . '&loan_id=' . $result['loan_id'] . $url, true),
			);
		}

		$loan_count = $this->model_loan_loan->getLoansCount($filter_data);

		$results_total = $this->model_loan_loan->getLoansTotal($filter_data);

		$loan_total = $this->model_loan_loan->getLoansTotal();

		$data['subtotal'] = $this->currency->format($results_total, $this->config->get('config_currency'));
		$data['grandtotal'] = sprintf($this->language->get('text_grandtotal'), $this->currency->format($loan_total, $this->config->get('config_currency')));

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

		$data['sort_date_added'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=date_added' . $url, true);
		$data['sort_name'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_customer_department'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_balance'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=balance' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $loan_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($loan_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($loan_count - $limit)) ? $loan_count : ((($page - 1) * $limit) + $limit), $loan_count, ceil($loan_count / $limit));

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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('loan/loan_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['loan_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_name',
			'entry_description',
			'entry_amount',
			'entry_date_added',
			'entry_installment',
			'entry_date_start',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$errors = array(
			'warning',
			'date_start',
			'description',
			'amount',
			'installment'
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
			'href' => $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['loan_id'])) {
			$data['action'] = $this->url->link('loan/loan/add', 'token=' . $this->session->data['token'] . $url, true);
			$data['disabled'] = '';
		} else {
			$data['action'] = $this->url->link('loan/loan/edit', 'token=' . $this->session->data['token'] . '&loan_id=' . $this->request->get['loan_id'] . $url, true);
			$data['disabled'] = 'disabled';
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['loan_id'])) {
			$loan_info = $this->model_loan_loan->getLoan($this->request->get['loan_id']);
		}

		$field_items = array(
			'customer_id',
			'name',
			'date_start',
			'description',
			'amount',
			'installment',
		);
		foreach ($field_items as $item) {
			if (isset($this->request->post[$item])) {
				$data[$item] = $this->request->post[$item];
			} elseif (!empty($loan_info)) {
				if ($item == 'date_start') {
					$data['date_start'] = date($this->language->get('date_format_m_y'), strtotime($loan_info['date_start']));
				} else {
					$data[$item] = $loan_info[$item];
				}
			} else {
				$data[$item] = '';
			}
		}

		if (!empty($loan_info)) {
			$data['date_added'] = date($this->language->get('date_format_jMY'), strtotime($loan_info['date_added']));
		} else {
			$data['date_added'] = date($this->language->get('date_format_jMY'));
		}

		//Text User Modify
		if (!empty($loan_info)) {
			$data['text_modified'] = sprintf($this->language->get('text_created'), $loan_info['username'], date($this->language->get('datetime_format_jMY'), strtotime($loan_info['date_added'])));
		} else {
			$data['text_modified'] = sprintf($this->language->get('text_created'), $this->user->getUserName(), date($this->language->get('datetime_format_jMY')));
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('loan/loan_form', $data));
	}

	public function info()
	{
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		$language_items = [
			'heading_title',
			'text_info',
			'text_loading',
			'text_select',
			'entry_loan',
			'entry_description',
			'entry_amount',
			'button_back',
			'button_transaction_add',
			'help_amount'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_info'),
			'href' => $this->url->link('loan/loan/info', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['back'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'customer_id'	   	   => $data['customer_id'],
			'order'                => 'ASC'
		);

		$data['loans'] = $this->model_loan_loan->getLoans($filter_data);

		if (isset($this->request->post['loan_id'])) {
			$data['loan_id'] = $this->request->post['loan_id'];
		} else {
			$data['loan_id'] = 0;
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['amount'])) {
			$data['amount'] = $this->request->post['amount'];
		} else {
			$data['amount'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('loan/loan_info', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'loan/loan')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['loan_id'])) {
			if (empty($this->request->post['customer_id'])) {
				$this->error['warning'] = $this->language->get('error_customer_id');
			}

			if ((utf8_strlen($this->request->post['description']) < 5) || (utf8_strlen(trim($this->request->post['description'])) > 255)) {
				$this->error['description'] = $this->language->get('error_description');
			}

			if ($this->request->post['amount'] <= 0) {
				$this->error['amount'] = $this->language->get('error_amount');
			}

			if (!$this->request->post['date_start'] || strtotime('20 ' . $this->request->post['date_start']) < strtotime('today')) {
				$this->error['date_start'] = $this->language->get('error_date_start');
			}
		}

		if ($this->request->post['installment'] < 0) {
			$this->error['installment'] = $this->language->get('error_installment');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('bypass', 'loan/loan')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $loan_id) {
			if ($this->model_loan_loan->getTransactionCountByLoanId($loan_id) > 1) {
				$this->error['warning'] = $this->language->get('error_paid_delete');
			}
		}

		return !$this->error;
	}

	public function transaction()
	{
		$this->load->language('loan/loan');

		$this->load->model('loan/loan');

		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_balance'] = $this->language->get('text_balance');

		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_loan_id'] = $this->language->get('column_loan_id');
		$data['column_description'] = $this->language->get('column_description');
		$data['column_amount'] = $this->language->get('column_amount');
		$data['column_username'] = $this->language->get('column_username');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['transactions'] = array();

		$results = $this->model_loan_loan->getTransactions($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['transactions'][] = array(
				'date_added'  => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'loan_id' 	  => '#' . $result['item'],
				'description' => $result['title'],
				'amount'      => $this->currency->format($result['value'], $this->config->get('config_currency')),
				'username'	  => $result['username']
			);
		}

		$data['balance'] = $this->currency->format($this->model_loan_loan->getTransactionTotal($this->request->get['customer_id']), $this->config->get('config_currency'));

		$transaction_total = $this->model_loan_loan->getTotalTransactions($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $transaction_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('loan/loan/transaction', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($transaction_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($transaction_total - 10)) ? $transaction_total : ((($page - 1) * 10) + 10), $transaction_total, ceil($transaction_total / 10));

		$this->response->setOutput($this->load->view('loan/loan_transaction', $data));
	}

	public function addTransaction()
	{
		$this->load->language('loan/loan');

		$json = array();

		if (!$this->user->hasPermission('modify', 'loan/loan')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (!$this->request->post['description'] || !$this->request->post['amount'] || !$this->request->post['loan_id']) {
			$json['error'] = $this->language->get('error_blank');
		} else {
			$this->load->model('loan/loan');

			$this->model_loan_loan->addTransaction($this->request->get['customer_id'], $this->request->post['description'], $this->request->post['amount'], $this->request->post['loan_id']);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function history()
	{
		$this->load->language('loan/loan');

		$this->load->model('loan/loan');

		$data['text_history'] = $this->language->get('text_history');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['button_edit'] = $this->language->get('button_edit');

		$data['loans'] = array();

		$filter = [];
		
		if (isset($this->request->get['filter_status'])) {
			$filter['status'] = $this->request->get['filter_status'];
		};

		$filter_data = array(
			'filter'		=> $filter,
			'customer_id'	=> $this->request->get['customer_id'],
			'order'			=> 'DESC',
			'start'        	=> 0,
			'limit'        	=> 5
		);

		$results = $this->model_loan_loan->getLoans($filter_data);

		foreach ($results as $result) {
			$data['loans'][] = array(
				'description'    => '#' . $result['loan_id'] . ': ' . $result['description'],
				'total'          => $this->currency->format($result['balance'], $this->config->get('config_currency')),
				'edit'           => $this->url->link('loan/loan/edit', 'token=' . $this->session->data['token'] . '&loan_id=' . $result['loan_id'], true)
			);
		}

		$this->response->setOutput($this->load->view('loan/loan_history', $data));
	}

	public function autocomplete()
	{
		$this->load->language('loan/loan');

		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];

			$this->load->model('presence/presence');

			$filter_status = isset($this->request->get['filter_status']) ? $this->request->get['filter_status'] : null;

			$filter_data = array(
				'filter_name'  	=> $filter_name,
				'filter_status'	=> $filter_status,
				'availability'  => true,
				'start'        	=> 0,
				'limit'        	=> 15
			);

			$results = $this->model_presence_presence->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'	=> $result['customer_id'],
					'name_set'		=> strip_tags(html_entity_decode(sprintf($this->language->get('text_name_set'), $result['name'], $result['customer_group'], $result['location']), ENT_QUOTES, 'UTF-8')),
					'name'			=> strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
