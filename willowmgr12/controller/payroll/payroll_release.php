<?php
class ControllerPayrollPayrollRelease extends Controller
{
	private $error = array();
	private $filter_items = array(
		'name',
		'customer_group_id',
		'customer_department_id',
		'location_id',
		'email',
		'payroll_method_id',
		'statement_sent',
		'status_released'
	);

	private function urlFilter($excluded_item = null)
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				// $url_filter .= '&filter_' . $filter_item . '=' . $this->request->get['filter_' . $filter_item];
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
		$this->load->language('payroll/payroll_release');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		$this->getPeriod();
	}

	public function edit()
	{
		$this->load->language('payroll/payroll_release');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$payroll_period = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!$payroll_period || !$payroll_period['total_payroll']) {
			return new Action('error/not_found');
		} else {
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->load->model('presence/presence');

				$this->model_common_payroll->setPeriodStatus($presence_period_id, 'released', $this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';
				$url .= '&presence_period_id=' . $presence_period_id;

				$this->response->redirect($this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . $url, true));
			}

			$this->getForm();
		}
	}

	public function info()
	{
		$this->load->language('payroll/payroll_release');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		$this->getList();
	}

	protected function getPeriod()
	{
		$language_items = array(
			'heading_title',
			'text_period_list',
			'text_no_results',
			'text_confirm',
			'text_loading',
			'column_period',
			'column_payroll_status',
			'column_fund_acc_name',
			'column_fund_acc_no',
			'column_sum_grandtotal',
			'column_date_released',
			'column_action',
			'entry_payroll_status',
			'entry_period',
			'button_filter',
			'button_release',
			'button_uncomplete',
			'button_view'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['filter_payroll_status'])) {
			$filter_payroll_status = $this->request->get['filter_payroll_status'];
		} else {
			$filter_payroll_status = null;
		}

		if (isset($this->request->get['filter_period'])) {
			$filter_period = $this->request->get['filter_period'];
		} else {
			$filter_period = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_payroll_status'])) {
			$url .= '&filter_payroll_status=' . $this->request->get['filter_payroll_status'];
		}

		if (isset($this->request->get['filter_period'])) {
			$url .= '&filter_period=' . $this->request->get['filter_period'];
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
			'href' => $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'], true)
		);

		$filter_data = array(
			'filter_payroll_status'	=> $filter_payroll_status,
			'filter_period'			=> $filter_period,
			'start'           		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           		=> $this->config->get('config_limit_admin')
		);

		$data['payroll_periods'] = array();

		$payroll_period_total = $this->model_payroll_payroll_release->getTotalPayrollPeriods($filter_data);

		$results = $this->model_payroll_payroll_release->getPayrollPeriods($filter_data);

		foreach ($results as $result) {
			# Period Status Check
			$release_status_check = $this->model_common_payroll->checkPeriodStatus($result['presence_period_id'], 'approved, released');
			$view_status_check = $this->model_common_payroll->checkPeriodStatus($result['presence_period_id'], 'released, completed');
			$complete_status_check = $this->model_common_payroll->checkPeriodStatus($result['presence_period_id'], 'completed');

			if ($result['date_release']) {
				$date_release = date($this->language->get('date_format_jMY'), strtotime($result['date_release']));
			} else {
				$date_release = '';
			}

			$data['payroll_periods'][] = array(
				'presence_period_id' => $result['presence_period_id'],
				'period'        	=> date($this->language->get('date_format_m_y'), strtotime($result['period'])),
				'payroll_status' 	=> $result['payroll_status'],
				'fund_acc_name' 	=> $result['acc_name'],
				'fund_acc_no' 		=> $result['bank_name'] . ' - ' . $result['acc_no'],
				'total_payroll' 	=> $this->currency->format($result['total_payroll'], $this->config->get('config_currency')),
				'date_release' 		=> $date_release,
				'release_check' 	=> $release_status_check,
				'release'          	=> $this->url->link('payroll/payroll_release/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . $url, true),
				'view_check'		=> $view_status_check,
				'view'          	=> $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'], true),
				'complete_check'	=> $complete_status_check
			);
		}

		$url = '';

		if (isset($this->request->get['filter_payroll_status'])) {
			$url .= '&filter_payroll_status=' . $this->request->get['filter_payroll_status'];
		}

		if (isset($this->request->get['filter_period'])) {
			$url .= '&filter_period=' . $this->request->get['filter_period'];
		}

		$pagination = new Pagination();
		$pagination->total = $payroll_period_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_period_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($payroll_period_total - $this->config->get('config_limit_admin'))) ? $payroll_period_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $payroll_period_total, ceil($payroll_period_total / $this->config->get('config_limit_admin')));

		$data['information'] = '';

		$max_unarchive = $this->config->get('payroll_setting_max_unarchive');

		$released_period_total = $this->model_payroll_payroll_release->getTotalPayrollPeriods(['filter_payroll_status'	=> $this->config->get('payroll_setting_released_status_id')]);
		
		if ($released_period_total > $max_unarchive) {
			$data['information'] = sprintf($this->language->get('error_complete'), $max_unarchive);
		}

		$data['filter_payroll_status'] = $filter_payroll_status;
		$data['filter_period'] = $filter_period;

		$this->load->model('localisation/payroll_status');
		$data['payroll_statuses'] = $this->model_localisation_payroll_status->getPayrollStatuses();

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_release_period', $data));
	}

	protected function getList()
	{
		$language_items = [
			'heading_title',
			'text_list',
			'text_confirm',
			'text_confirm_release',
			'text_confirm_send_all',
			'text_no_results',
			'text_all',
			'text_yes',
			'text_no',
			'text_loading',
			'text_period_list',
			'text_release_late',
			'text_release_present',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_customer_department',
			'column_location',
			'column_email',
			'column_acc_no',
			'column_payroll_method',
			'column_net_salary',
			'column_date_released',
			'column_statement_sent',
			'entry_name',
			'entry_email',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'entry_payroll_method',
			'entry_release_status',
			'entry_statement_sent',
			'button_back',
			'button_filter',
			'button_payroll_complete',
			'button_export',
			'button_action'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		if ($filter['status_released'] == 'pending') {
			$filter['all_period'] = 'true';
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
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_period_list'],
			'href' => $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_list'],
			'href' => $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true)
		);

		$data['send'] = $this->url->link('payroll/payroll_release/send', 'token=' . $this->session->data['token'] . $url, true);
		$data['back'] = $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'], true);

		$limit = $this->config->get('config_limit_admin');

		$data['payroll_releases'] = [];

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order'		=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_payroll_payroll_release->getReleases($presence_period_id, $filter_data);

		foreach ($results as $result) {
			$grandtotal = $result['net_salary'] + $result['component'];

			if ($result['presence_period_id'] < $presence_period_id) {
				$label = 'late';
				$label_period = '<cite> - ' . date($this->language->get('date_format_m_y'), strtotime($result['period'])) . '</cite>';
			} else {
				$label = 'present';
				$label_period = '';
			}

			if ($result['date_released']) {
				$date_released = date($this->language->get('date_format_jMY'), strtotime($result['date_released']));
			} elseif (!$result['status_released']) {
				$date_released = '-';
			} else {
				$date_released = $this->language->get('text_' . $result['status_released']);
			}

			$data['payroll_releases'][$label][] = array(
				'customer_id' 			=> $result['customer_id'],
				'customer_code'			=> $result['presence_period_id'] . '-' . $result['customer_id'],
				'nip' 					=> $result['nip'],
				'name' 					=> $result['name'] . $label_period,
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'email' 				=> $result['email'],
				'payroll_method'		=> $result['payroll_method'],
				'acc_no' 				=> $result['acc_no'],
				'grandtotal'    		=> $this->currency->format($grandtotal, $this->config->get('config_currency')),
				'date_released' 		=> $date_released,
				'text_class' 			=> $result['status_released'] == 'cancelled' ? 'text-danger' : '',
				'statement_sent' 		=> $result['statement_sent']
			);
		}

		$payroll_release_count = $this->model_payroll_payroll_release->getReleasesCount($presence_period_id, $filter_data);

		// Status Check 
		$data['released_status_check'] = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'released');

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

		$url .= '&presence_period_id=' . $presence_period_id;

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		$data['sort_nip'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_email'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=email' . $url, true);
		$data['sort_customer_group'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_customer_department'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_acc_no'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=acc_no' . $url, true);
		$data['sort_payroll_method'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=payroll_method' . $url, true);
		$data['sort_net_salary'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=net_salary' . $url, true);
		$data['sort_date_released'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=date_released' . $url, true);
		$data['sort_statement_sent'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=statement_sent' . $url, true);

		$url = $this->urlFilter('page');

		$url .= '&presence_period_id=' . $presence_period_id;

		$pagination = new Pagination();
		$pagination->total = $payroll_release_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_release_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($payroll_release_count - $limit)) ? $payroll_release_count : ((($page - 1) * $limit) + $limit), $payroll_release_count, ceil($payroll_release_count / $limit));

		$data['presence_period_id'] = $presence_period_id;
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

		$this->load->model('localisation/payroll_method');
		$data['payroll_methods'] = $this->model_localisation_payroll_method->getPayrollMethods();

		$data['release_statuses'] = [];

		$release_statuses = [
			'unreleased',
			'released',
			'pending',
			'cancelled'
		];

		foreach ($release_statuses as $release_status) {
			$data['release_statuses'][] = [
				'code'	=> $release_status,
				'text'	=> $this->language->get('text_' . $release_status)
			];
		}

		$url = $this->urlFilter();
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['actions'] = [];

		$actions = [
			'pending',
			'cancelled',
			'send'
		];

		if ($this->user->hasPermission('bypass', 'payroll/payroll_release')) {
			$actions[] = 'unreleased';
		}

		foreach ($actions as $action) {
			$data['actions'][] = [
				'href'	=> $this->url->link('payroll/payroll_release/action', 'token=' . $this->session->data['token'] . '&action=' . $action . $url, true),
				'text'	=> $this->language->get('button_' . $action)
			];
		}

		$data['exports'] = [];

		$data['exports'][] = [
			'href'	=> $this->url->link('payroll/payroll_release/export', 'token=' . $this->session->data['token'] . '&payroll_method_id=0' . $url, true),
			'text'	=> $this->language->get('button_draft')
		];

		foreach ($data['payroll_methods'] as $payroll_method) {
			$data['exports'][] = [
				'href'	=> $this->url->link('payroll/payroll_release/export', 'token=' . $this->session->data['token'] . '&payroll_method_id=' . $payroll_method['payroll_method_id'] . $url, true),
				'text'	=> $payroll_method['name']
			];
		}

		$data['url'] = $url;
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_release_list', $data));
	}

	protected function getForm()
	{
		$language_items = array(
			'heading_title',
			'text_edit',
			'text_select',
			'entry_fund_account',
			'entry_date_release',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$errors = array(
			'warning',
			'fund_account',
			'date_release'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_payroll_status'])) {
			$url .= '&filter_payroll_status=' . $this->request->get['filter_payroll_status'];
		}

		if (isset($this->request->get['filter_period'])) {
			$url .= '&filter_period=' . $this->request->get['filter_period'];
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
			'href' => $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'], true)
		);

		$data['edit'] = $this->url->link('payroll/payroll_release/edit', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_edit'],
			'href' => $data['edit']
		);

		$payroll_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if (isset($this->request->post['fund_account_id'])) {
			$data['fund_account_id'] = $this->request->post['fund_account_id'];
		} elseif (!empty($payroll_info)) {
			$data['fund_account_id'] = $payroll_info['fund_account_id'];
		} else {
			$data['fund_account_id'] = 0;
		}

		if (isset($this->request->post['date_release'])) {
			$data['date_release'] = $this->request->post['date_release'];
		} elseif (!empty($payroll_info) && $payroll_info['date_release']) {
			$data['date_release'] = date($this->language->get('date_format_jMY'), strtotime($payroll_info['date_release']));
		} else {
			$data['date_release'] = '';
		}

		$this->load->model('release/fund_account');

		$fund_accounts = $this->model_release_fund_account->getFundAccounts();
		foreach ($fund_accounts as $fund_account) {
			$data['fund_accounts'][] = array(
				'fund_account_id'	=> $fund_account['fund_account_id'],
				'fund_account_text'	=> $fund_account['acc_name'] . '; ' . $fund_account['bank_name'] . ' - ' .  $fund_account['acc_no']
			);
		}

		$data['presence_period_id'] = $presence_period_id;

		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_release_form', $data));
	}

	public function send()
	{
		$this->load->language('payroll/payroll_release');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		if (isset($this->request->get['presence_period_id']) && isset($this->request->post['selected']) && $this->validateAction()) {
			$mail_error = [];

			foreach ($this->request->post['selected'] as $selected) {
				$code = explode('-', $selected);

				$presence_period_id = $code[0];
				$customer_id = $code[1];

				$release_info = $this->model_payroll_payroll_release->getRelease($presence_period_id, $customer_id);

				if (!is_null($release_info['date_released'])) {
					$error_status = $this->model_payroll_payroll_release->sendStatement($presence_period_id, $customer_id);
				} else {
					$error_status = sprintf($this->language->get('error_mail_not_released'), $release_info['name']);
				}

				if ($error_status) {
					$mail_error[] = $error_status;
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_send');

			if ($mail_error) {
				$this->session->data['success'] .= '<br>' . $this->language->get('error_mail_sending_status');
				$this->session->data['success'] .= '<br>' . implode('<br>', $mail_error);
			}

			$url = $this->urlFilter();
			$url .= '&presence_period_id=' . $presence_period_id;

			$this->response->redirect($this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function releaseInfo()
	{
		$this->load->language('payroll/payroll_release');

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		$language_items = array(
			'text_release_info',
			'text_fund_acc_no',
			'text_fund_acc_name',
			'text_fund_email',
			'text_fund_date_release',
			'text_no_results'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$presence_period_id = $this->request->get['presence_period_id'];

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		if ($filter['status_released'] == 'pending') {
			$filter['all_period'] = 'true';
		}

		$filter_data = [];
		$filter_data['filter'] = $filter;

		// Text Period
		$payroll_period = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!empty($payroll_period) && $payroll_period['fund_account_id']) {
			$this->load->model('release/fund_account');
			$fund_account_info = $this->model_release_fund_account->getFundAccount($payroll_period['fund_account_id']);

			$data['fund_acc_name'] = $fund_account_info['acc_name'];
			$data['fund_acc_no'] = $fund_account_info['bank_name'] . ' - ' . $fund_account_info['acc_no'];
			$data['fund_email'] = $fund_account_info['email'];
			$data['fund_date_release'] = date($this->language->get('date_format_jMY'), strtotime($payroll_period['date_release']));

			$method_releases = $this->model_payroll_payroll_release->getMethodsSummary($presence_period_id, $filter_data);

			$data['method_releases'] = array();
			foreach ($method_releases as $method_release) {
				$data['method_releases'][] = array(
					'method' 	=> sprintf($this->language->get('text_method'), $method_release['payroll_method'], $method_release['count']),
					'total' 	=> $this->currency->format($method_release['total'], $this->config->get('config_currency'))
				);
			}
		}
		$data['fund_account'] = $payroll_period['fund_account_id'];

		$this->response->setOutput($this->load->view('payroll/payroll_release_info', $data));
	}

	public function completePayroll()
	{
		$this->load->language('payroll/payroll_release');

		$json = array();

		if (!$this->user->hasPermission('modify', 'payroll/payroll_release')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

			$this->load->model('payroll/payroll_release');
			$this->load->model('common/payroll');

			$payroll_release_count = $this->model_payroll_payroll_release->getReleasesCount($presence_period_id);
			$released_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'released');

			if (!$payroll_release_count) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif (!$released_status_check) {
				$json['error'] = $this->language->get('error_status');
			}
		}

		if (!$json) {
			$completed_after = $this->config->get('payroll_setting_completed_after');
			$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

			if (strtotime('+ ' . $completed_after . 'months', strtotime($period_info['date_release'])) > strtotime('today')) {
				$json['error'] = sprintf($this->language->get('error_completed_after'), $completed_after);
			}
		}

		if (!$json) {
			if (!defined('DB_ARCH_DATABASE')) {
				$json['error'] = $this->language->get('error_db_archive');
			}
		}

		if (!$json) {
			$this->db->transaction(function () use ($presence_period_id) {
				$this->model_payroll_payroll_release->archivePeriodData($presence_period_id);

				$this->model_common_payroll->setPeriodStatus($presence_period_id, 'completed');
			});

			$json['success'] = $this->language->get('text_complete_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function uncompletePayroll()
	{
		$this->load->language('payroll/payroll_release');

		$json = array();

		if (!$this->user->hasPermission('bypass', 'payroll/payroll_release')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

			$this->load->model('common/payroll');

			$complete_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'completed');

			if (!$complete_status_check) {
				$json['error'] = $this->language->get('error_status');
			}
		}

		if (!$json) {
			if (!defined('DB_ARCH_DATABASE')) {
				$json['error'] = $this->language->get('error_db_archive');
			}
		}

		if (!$json) {
			$this->load->model('payroll/payroll_release');

			$this->db->transaction(function () use ($presence_period_id) {
				$this->model_payroll_payroll_release->unarchivePeriodData($presence_period_id);

				$this->model_common_payroll->setPeriodStatus($presence_period_id, 'released');
			});

			$json['success'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function action()
	{
		$this->load->language('payroll/payroll_release');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		if (isset($this->request->get['presence_period_id']) && isset($this->request->get['action']) && $this->validateAction()) {
			$error_action = [];

			if ($this->request->get['action'] == 'draft') {
				$this->exportDraft();
			} else {
				if (isset($this->request->post['selected'])) {
					foreach ($this->request->post['selected'] as $selected) {
						$code = explode('-', $selected);

						$presence_period_id = $code[0];
						$customer_id = $code[1];

						$release_info = $this->model_payroll_payroll_release->getRelease($presence_period_id, $customer_id);

						switch ($this->request->get['action']) {
							case 'pending':
							case 'cancelled':
								if ($release_info['status_released'] == 'released' || !empty($release_info['date_released'])) {
									$error_action[] = sprintf($this->language->get('error_status_released'), $release_info['name']);

									break;
								}

								$this->model_payroll_payroll_release->editPayrollReleaseStatus($presence_period_id, $customer_id, $this->request->get['action']);

								break;

							case 'unreleased':
								$this->model_payroll_payroll_release->editPayrollReleaseStatus($presence_period_id, $customer_id, $this->request->get['action']);

								break;

							case 'send':
								$this->send();

								break;

							default:
								break;
						}
					}
				} else {
					$error_action[] = $this->language->get('error_not_found');
				}

				$this->session->data['success'] = $this->language->get('text_success');

				if ($error_action) {
					$this->session->data['success'] .= '<br>' . implode('<br>', $error_action);
				}

				$url = $this->urlFilter();
				$url .= '&presence_period_id=' . $this->request->get['presence_period_id'];

				$this->response->redirect($this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . $url, true));
			}
		} else {
			$this->getList();
		}
	}

	public function export()
	{
		$this->load->language('payroll/payroll_release');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		if (isset($this->request->get['presence_period_id']) && isset($this->request->get['payroll_method_id']) && $this->validateAction()) {
			if (isset($this->request->get['payroll_method_id']) && $this->request->get['payroll_method_id'] == 0) {
				$code = 'draft';
			} else {
				$this->load->model('localisation/payroll_method');

				$payroll_method_info = $this->model_localisation_payroll_method->getPayrollMethod($this->request->get['payroll_method_id']);

				$code = isset($payroll_method_info['code']) ? $payroll_method_info['code'] : '';
			}

			$this->db->transaction(function () use ($code) {
				switch ($code) {
					case 'draft':
						$this->exportDraft();

						break;

					case 'cimb':
						$this->exportCimb();

						break;

					case 'mandiri':
						$this->exportMandiri();

						break;

					default:
						$this->exportOther();

						break;
				}
			});
		} else {
			$this->getList();
		}
	}

	public function exportDraft()
	{
		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!empty($period_info)) {
			foreach ($this->filter_items as $filter_item) {
				if (isset($this->request->get['filter_' . $filter_item])) {
					$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
				} else {
					$filter[$filter_item] = null;
				}
			}

			$period = date('M_Y', strtotime($period_info['period']));

			$output = '';
			$sub_output = '';

			$filter_selection = [];
			$grand_results = [];

			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $selected) {
					$code = explode('-', $selected);

					$filter_selection[$code[0]][] = $code[1];
				}
			} else {
				$filter_selection[$presence_period_id] = [];

				$filter['status_released'] = 'unreleased';
			}

			foreach ($filter_selection as $presence_period_id => $filter_customers) {
				$filter_customers = implode(',', $filter_customers);

				$filter_data = array(
					'filter'  	=> $filter,
				);

				$filter_data['filter']['customer_ids'] = $filter_customers;

				$results = $this->model_payroll_payroll_release->getReleases($presence_period_id, $filter_data);

				$grand_results = array_merge($grand_results, $results);
			}

			foreach ($grand_results as $result) {
				if ((!is_null($result['status_released']) && $result['status_released'] != 'pending') || $result['date_released']) {
					continue;
				}

				$grandtotal = $result['net_salary'] + $result['component'];

				$value = '';
				$value .= $result['lastname'] . ',' . $result['customer_group'] . ',' . $result['customer_department'] . ',' . $result['location'] . ',' . $result['payroll_method'] . ',' . $result['acc_no'] . ',' . $grandtotal;

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\',	$value);
				$value = str_replace('\'', '\\\'',	$value);
				$value = str_replace('\\\n', '\n',	$value);
				$value = str_replace('\\\r', '\r',	$value);
				$value = str_replace('\\\t', '\t',	$value);

				$sub_output .= "\n" . $value;
			}

			$output .= $this->language->get('column_name') . ',' . $this->language->get('column_customer_group') . ',' . $this->language->get('column_customer_department') . ',' . $this->language->get('column_location') . ',' . $this->language->get('column_payroll_method') . ',' . $this->language->get('column_acc_no') . ',' . $this->language->get('column_net_salary');

			$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
			$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
			$output = str_replace('\\', '\\\\',	$output);
			$output = str_replace('\'', '\\\'',	$output);
			$output = str_replace('\\\n', '\n',	$output);
			$output = str_replace('\\\r', '\r',	$output);
			$output = str_replace('\\\t', '\t',	$output);

			$output .= $sub_output;

			$filename = date('Ym', strtotime($period_info['period'])) . '_Payroll_' . $period . '_DRAFT';

			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
			// echo '<pre>' . print_r($output, 1); 
		} else {

			$this->info();
		}
	}

	protected function exportCimb()
	{
		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!empty($period_info)) {
			foreach ($this->filter_items as $filter_item) {
				if (isset($this->request->get['filter_' . $filter_item])) {
					$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
				} else {
					$filter[$filter_item] = null;
				}
			}

			$period = date('M_Y', strtotime($period_info['period']));

			$currency_code = $this->config->get('config_currency');
			$date_release = date('Ymd', strtotime($period_info['date_release']));

			$output = '';
			$sub_output = '';
			$sum_grandtotal = 0;
			$customer_total = 0;

			$filter_selection = [];
			$grand_results = [];

			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $selected) {
					$code = explode('-', $selected);

					$filter_selection[$code[0]][] = $code[1];
				}
			} else {
				$filter_selection[$presence_period_id] = [];

				$filter['status_released'] = 'unreleased';
			}

			foreach ($filter_selection as $presence_period_id => $filter_customers) {
				$filter_customers = implode(',', $filter_customers);

				$filter_data = array(
					'filter'  	=> $filter,
				);

				$filter_data['filter']['customer_ids'] = $filter_customers;

				$results = $this->model_payroll_payroll_release->getReleases($presence_period_id, $filter_data);

				$grand_results = array_merge($grand_results, $results);
			}

			foreach ($grand_results as $result) {
				if ((!is_null($result['status_released']) && $result['status_released'] != 'pending') || $result['date_released']) {
					continue;
				}

				if ($result['payroll_method_id'] != $this->request->get['payroll_method_id']) {
					continue;
				}

				$release_data = [
					'date_released'				=> $date_release,
					'release_payroll_method_id'	=> $result['payroll_method_id'],
					'release_acc_no'			=> $result['acc_no']
				];
				$this->model_payroll_payroll_release->editPayrollReleaseStatus($result['presence_period_id'], $result['customer_id'], 'released', $release_data);

				$grandtotal = $result['net_salary'] + $result['component'];

				if ($grandtotal <= 0) {
					continue;
				}

				$customer_period = date('M_Y', strtotime($result['period']));

				$sum_grandtotal += $grandtotal;
				$customer_total++;

				$value = '';
				$value .= $result['acc_no'] . ',' . $result['lastname'] . ',' . $currency_code . ',' . $grandtotal . ',Payroll_' . $customer_period . ',' . $result['email'] . ',,';

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\',	$value);
				$value = str_replace('\'', '\\\'',	$value);
				$value = str_replace('\\\n', '\n',	$value);
				$value = str_replace('\\\r', '\r',	$value);
				$value = str_replace('\\\t', '\t',	$value);

				$sub_output .= "\n" . $value;
			}

			$this->load->model('release/fund_account');
			$fund_account_info = $this->model_release_fund_account->getFundAccount($period_info['fund_account_id']);

			$output .= $fund_account_info['acc_no'] . ',' . $fund_account_info['acc_name'] . ',' . $currency_code . ',' . $sum_grandtotal . ',Payroll_' . $period . ',' . $customer_total . ',' . $date_release . ',' . $fund_account_info['email'];

			$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
			$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
			$output = str_replace('\\', '\\\\',	$output);
			$output = str_replace('\'', '\\\'',	$output);
			$output = str_replace('\\\n', '\n',	$output);
			$output = str_replace('\\\r', '\r',	$output);
			$output = str_replace('\\\t', '\t',	$output);

			$output .= $sub_output;

			$payroll_method_info = $this->model_localisation_payroll_method->getPayrollMethod($this->request->get['payroll_method_id']);
			$filename = date('Ym', strtotime($period_info['period'])) . '_Payroll_' . $period . '_' . $payroll_method_info['name'];
			// echo '<pre>' . print_r($output, 1); 

			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
		} else {

			$this->info();
		}
	}

	public function exportMandiri()
	{
		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!empty($period_info)) {
			foreach ($this->filter_items as $filter_item) {
				if (isset($this->request->get['filter_' . $filter_item])) {
					$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
				} else {
					$filter[$filter_item] = null;
				}
			}

			$period = date('M_Y', strtotime($period_info['period']));

			$currency_code = $this->config->get('config_currency');
			$date_release = date('Ymd', strtotime($period_info['date_release']));

			$output = '';
			$sub_output = '';
			$sum_grandtotal = 0;
			$customer_total = 0;

			$filter_selection = [];
			$grand_results = [];

			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $selected) {
					$code = explode('-', $selected);

					$filter_selection[$code[0]][] = $code[1];
				}
			} else {
				$filter_selection[$presence_period_id] = [];

				$filter['status_released'] = 'unreleased';
			}

			foreach ($filter_selection as $presence_period_id => $filter_customers) {
				$filter_customers = implode(',', $filter_customers);

				$filter_data = array(
					'filter'  	=> $filter,
				);

				$filter_data['filter']['customer_ids'] = $filter_customers;

				$results = $this->model_payroll_payroll_release->getReleases($presence_period_id, $filter_data);

				$grand_results = array_merge($grand_results, $results);
			}

			foreach ($grand_results as $result) {
				if ((!is_null($result['status_released']) && $result['status_released'] != 'pending') || $result['date_released']) {
					continue;
				}

				if ($result['payroll_method_id'] != $this->request->get['payroll_method_id']) {
					continue;
				}

				$release_data = [
					'date_released'				=> $date_release,
					'release_payroll_method_id'	=> $result['payroll_method_id'],
					'release_acc_no'			=> $result['acc_no']
				];
				$this->model_payroll_payroll_release->editPayrollReleaseStatus($result['presence_period_id'], $result['customer_id'], 'released', $release_data);

				$grandtotal = $result['net_salary'] + $result['component'];

				if ($grandtotal <= 0) {
					continue;
				}

				$customer_period = date('M_Y', strtotime($result['period']));

				$sum_grandtotal += $grandtotal;
				$customer_total++;

				$value = '';
				$value .= $result['acc_no'] . ',' . $result['lastname'] . ',,,,' . $currency_code . ',' . $grandtotal . ',' . 'Payroll_' . $customer_period . ',,IBU,,,,,,,Y,' . $result['email'] . ',,,,,,,,,,,,,,,,,,,,,OUR,1,E,,,';

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\',	$value);
				$value = str_replace('\'', '\\\'',	$value);
				$value = str_replace('\\\n', '\n',	$value);
				$value = str_replace('\\\r', '\r',	$value);
				$value = str_replace('\\\t', '\t',	$value);

				$sub_output .= "\n" . $value;
			}

			$this->load->model('release/fund_account');
			$fund_account_info = $this->model_release_fund_account->getFundAccount($period_info['fund_account_id']);

			$output .= 'P' . ',' . $date_release . ',' . $fund_account_info['acc_no'] . ',' . $customer_total . ',' . $sum_grandtotal;

			$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
			$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
			$output = str_replace('\\', '\\\\',	$output);
			$output = str_replace('\'', '\\\'',	$output);
			$output = str_replace('\\\n', '\n',	$output);
			$output = str_replace('\\\r', '\r',	$output);
			$output = str_replace('\\\t', '\t',	$output);

			$output .= $sub_output;

			$payroll_method_info = $this->model_localisation_payroll_method->getPayrollMethod($this->request->get['payroll_method_id']);
			$filename = date('Ym', strtotime($period_info['period'])) . '_Payroll_' . $period . '_' . $payroll_method_info['name'];
			// echo '<pre>' . print_r($output, 1); 
			// die(' ---breakpoint--- ');

			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
		} else {

			$this->info();
		}
	}

	public function exportOther()
	{
		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!empty($period_info)) {
			foreach ($this->filter_items as $filter_item) {
				if (isset($this->request->get['filter_' . $filter_item])) {
					$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
				} else {
					$filter[$filter_item] = null;
				}
			}

			$period = date('M_Y', strtotime($period_info['period']));

			$date_release = date('Ymd', strtotime($period_info['date_release']));

			$output = '';
			$sub_output = '';

			$filter_selection = [];
			$grand_results = [];

			if (isset($this->request->post['selected'])) {
				foreach ($this->request->post['selected'] as $selected) {
					$code = explode('-', $selected);

					$filter_selection[$code[0]][] = $code[1];
				}
			} else {
				$filter_selection[$presence_period_id] = [];

				$filter['status_released'] = 'unreleased';
			}

			foreach ($filter_selection as $presence_period_id => $filter_customers) {
				$filter_customers = implode(',', $filter_customers);

				$filter_data = array(
					'filter'  	=> $filter,
				);

				$filter_data['filter']['customer_ids'] = $filter_customers;

				$results = $this->model_payroll_payroll_release->getReleases($presence_period_id, $filter_data);

				$grand_results = array_merge($grand_results, $results);
			}

			foreach ($grand_results as $result) {
				if ((!is_null($result['status_released']) && $result['status_released'] != 'pending') || $result['date_released']) {
					continue;
				}

				if ($result['payroll_method_id'] != $this->request->get['payroll_method_id']) {
					continue;
				}

				$release_data = [
					'date_released'				=> $date_release,
					'release_payroll_method_id'	=> $result['payroll_method_id'],
					'release_acc_no'			=> $result['acc_no']
				];
				$this->model_payroll_payroll_release->editPayrollReleaseStatus($result['presence_period_id'], $result['customer_id'], 'released', $release_data);

				$grandtotal = $result['net_salary'] + $result['component'];

				if ($grandtotal <= 0) {
					continue;
				}

				$grandtotal = $result['net_salary'] + $result['component'];

				$value = '';
				$value .= $result['lastname'] . ',' . $result['customer_group'] . ',' . $result['customer_department'] . ',' . $result['location'] . ',' . $result['payroll_method'] . ',' . $result['acc_no'] . ',' . $grandtotal;

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\',	$value);
				$value = str_replace('\'', '\\\'',	$value);
				$value = str_replace('\\\n', '\n',	$value);
				$value = str_replace('\\\r', '\r',	$value);
				$value = str_replace('\\\t', '\t',	$value);

				$sub_output .= "\n" . $value;
			}

			$output .= $this->language->get('column_name') . ',' . $this->language->get('column_customer_group') . ',' . $this->language->get('column_customer_department') . ',' . $this->language->get('column_location') . ',' . $this->language->get('column_payroll_method') . ',' . $this->language->get('column_acc_no') . ',' . $this->language->get('column_net_salary');

			$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
			$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
			$output = str_replace('\\', '\\\\',	$output);
			$output = str_replace('\'', '\\\'',	$output);
			$output = str_replace('\\\n', '\n',	$output);
			$output = str_replace('\\\r', '\r',	$output);
			$output = str_replace('\\\t', '\t',	$output);

			$output .= $sub_output;

			$payroll_method_info = $this->model_localisation_payroll_method->getPayrollMethod($this->request->get['payroll_method_id']);
			if ($payroll_method_info['code'] != 'none') {
				$output .= "\n\n" . $this->language->get('text_no_template');
			}

			$filename = date('Ym', strtotime($period_info['period'])) . '_Payroll_' . $period . '_' . $payroll_method_info['name'];

			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
			// echo '<pre>' . print_r($output, 1); 
		} else {

			$this->info();
		}
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'payroll/payroll_release')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'approved, released')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		if (empty($this->request->post['fund_account_id'])) {
			$this->error['fund_account'] = $this->language->get('error_fund_account');
		}

		if (empty($this->request->post['date_release']) || strtotime($this->request->post['date_release']) < strtotime('today')) {
			$this->error['date_release'] = $this->language->get('error_date_release');
		}

		$max_unarchive = $this->config->get('payroll_setting_max_unarchive');

		$released_period_total = $this->model_payroll_payroll_release->getTotalPayrollPeriods(['filter_payroll_status'	=> $this->config->get('payroll_setting_released_status_id')]);
		
		if ($released_period_total > $max_unarchive) {
			$this->error['warning'] = sprintf($this->language->get('error_complete'), $max_unarchive);
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateAction()
	{
		if (!$this->user->hasPermission('modify', 'payroll/payroll_release')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$released_status_check = $this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'released');
		$payroll_release_count = $this->model_payroll_payroll_release->getReleasesCount($this->request->get['presence_period_id']);
		$fund_account_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id'])['fund_account_id'];

		if (!$released_status_check) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		if (!$fund_account_info) {
			$this->error['warning'] = $this->language->get('error_fund_account');
		}

		if (!$payroll_release_count) {
			$this->error['warning'] = $this->language->get('error_not_found');
		}

		if (isset($this->request->get['action']) && $this->request->get['action'] == 'unreleased') {
			if (!$this->user->hasPermission('bypass', 'payroll/payroll_release')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
		}

		return !$this->error;
	}
}
