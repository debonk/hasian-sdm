<?php
class ControllerPayrollPayrollRelease extends Controller
{
	private $error = array();

	// private function initDbArchive() {
	// 	$db_archive = new DB(DB_DRIVER, DB_HOSTNAME, DB_ARCH_USERNAME, DB_ARCH_PASSWORD, DB_ARCH_DATABASE, DB_PORT);
	// 	$this->registry->set('db_archive', $db_archive);
	// }

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

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$payroll_period = $this->model_common_payroll->getPeriod($presence_period_id);
		if ($payroll_period && $payroll_period['fund_account_id']) {
			$this->getList();
		} else {
			return new Action('error/not_found');
		}
	}

	protected function getPeriod()
	{
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
			'href' => $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'] . $url, true)
		);

		$filter_data = array(
			'filter_payroll_status' => $filter_payroll_status,
			'filter_period' 	   => $filter_period,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$data['payroll_periods'] = array();

		$payroll_period_total = $this->model_payroll_payroll_release->getTotalPayrollPeriods($filter_data);

		$results = $this->model_payroll_payroll_release->getPayrollPeriods($filter_data);

		$data['information'] = '';

		foreach ($results as $result) {
			//Period Status Check
			$release_status_check = $this->model_common_payroll->checkPeriodStatus($result['presence_period_id'], 'approved, released');
			$view_status_check = $this->model_common_payroll->checkPeriodStatus($result['presence_period_id'], 'released, completed');
			$complete_status_check = $this->model_common_payroll->checkPeriodStatus($result['presence_period_id'], 'completed');

			$release_count = $this->model_payroll_payroll_release->getReleasesCount($result['presence_period_id']);

			if ($result['date_release']) {
				$date_release = date($this->language->get('date_format_jMY'), strtotime($result['date_release']));

				if (!$data['information']) {
					$completed_after = $this->config->get('payroll_setting_completed_after');

					if (strtotime('+ ' . ($completed_after + 3) . 'months', strtotime($result['date_release'])) < strtotime('today')) {
						$data['information'] = sprintf($this->language->get('text_information'), $completed_after);
					}
				}
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
				'release_check' 	=> $release_status_check && $release_count,
				'release'          	=> $this->url->link('payroll/payroll_release/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . $url, true),
				'view_check'		=> $view_status_check,
				'view'          	=> $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'], true),
				'complete_check'	=> $complete_status_check
			);
		}

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
			'column_date_release',
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

		$data['token'] = $this->session->data['token'];

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

		$pagination = new Pagination();
		$pagination->total = $payroll_period_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_period_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($payroll_period_total - $this->config->get('config_limit_admin'))) ? $payroll_period_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $payroll_period_total, ceil($payroll_period_total / $this->config->get('config_limit_admin')));

		$data['filter_payroll_status'] = $filter_payroll_status;
		$data['filter_period'] = $filter_period;

		$this->load->model('localisation/payroll_status');
		$data['payroll_statuses'] = $this->model_localisation_payroll_status->getPayrollStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_release_period', $data));
	}

	protected function getList()
	{
		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}

		if (isset($this->request->get['filter_payroll_method_id'])) {
			$filter_payroll_method_id = $this->request->get['filter_payroll_method_id'];
		} else {
			$filter_payroll_method_id = null;
		}

		if (isset($this->request->get['filter_statement_sent'])) {
			$filter_statement_sent = $this->request->get['filter_statement_sent'];
		} else {
			$filter_statement_sent = null;
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

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_payroll_method_id'])) {
			$url .= '&filter_payroll_method_id=' . $this->request->get['filter_payroll_method_id'];
		}

		if (isset($this->request->get['filter_statement_sent'])) {
			$url .= '&filter_statement_sent=' . $this->request->get['filter_statement_sent'];
		}

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
			'href' => $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['export_cimb'] = $this->url->link('payroll/payroll_release/exportCimb', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true);
		$data['send'] = $this->url->link('payroll/payroll_release/send', 'token=' . $this->session->data['token'] . $url, true);
		$data['back'] = $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'], true);

		$data['payroll_releases'] = array();

		$filter_data = array(
			'filter_name'	   	   		=> $filter_name,
			'filter_customer_group_id'	=> $filter_customer_group_id,
			'filter_payroll_method_id'	=> $filter_payroll_method_id,
			'filter_statement_sent'		=> $filter_statement_sent,
			'sort'                 		=> $sort,
			'order'                		=> $order,
			'start'                		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                		=> $this->config->get('config_limit_admin')
		);

		$payroll_release_count = $this->model_payroll_payroll_release->getReleasesCount($presence_period_id, $filter_data);

		$results = $this->model_payroll_payroll_release->getReleases($presence_period_id, $filter_data);

		foreach ($results as $result) {
			$grandtotal = $result['net_salary'] + $result['component'];

			$data['payroll_releases'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'nip' 				=> $result['nip'],
				'name' 				=> $result['name'],
				'customer_group' 	=> $result['customer_group'],
				'email' 			=> $result['email'],
				'payroll_method'	=> $result['payroll_method'],
				'acc_no' 			=> $result['acc_no'],
				'grandtotal'    	=> $this->currency->format($grandtotal, $this->config->get('config_currency')),
				'statement_sent' 	=> $result['statement_sent']
			);
		}

		//Status Check 
		$data['released_status_check'] = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'released');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_confirm_send_all'] = $this->language->get('text_confirm_send_all');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_all'] = $this->language->get('text_all');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');

		$data['text_all_customer_group'] = $this->language->get('text_all_customer_group');
		$data['text_all_payroll_method'] = $this->language->get('text_all_payroll_method');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_nip'] = $this->language->get('column_nip');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_customer_group'] = $this->language->get('column_customer_group');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_acc_no'] = $this->language->get('column_acc_no');
		$data['column_payroll_method'] = $this->language->get('column_payroll_method');
		$data['column_grandtotal'] = $this->language->get('column_grandtotal');
		$data['column_statement_sent'] = $this->language->get('column_statement_sent');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_payroll_method'] = $this->language->get('entry_payroll_method');
		$data['entry_statement_sent'] = $this->language->get('entry_statement_sent');

		$data['button_back'] = $this->language->get('button_back');
		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_payroll_complete'] = $this->language->get('button_payroll_complete');
		$data['button_export_cimb'] = $this->language->get('button_export_cimb');
		$data['button_send_all'] = $this->language->get('button_send_all');
		$data['button_send'] = $this->language->get('button_send');

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

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_payroll_method_id'])) {
			$url .= '&filter_payroll_method_id=' . $this->request->get['filter_payroll_method_id'];
		}

		if (isset($this->request->get['filter_statement_sent'])) {
			$url .= '&filter_statement_sent=' . $this->request->get['filter_statement_sent'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_nip'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_payroll_method'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=payroll_method' . $url, true);
		$data['sort_statement_sent'] = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . '&sort=statement_sent' . $url, true);

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_payroll_method_id'])) {
			$url .= '&filter_payroll_method_id=' . $this->request->get['filter_payroll_method_id'];
		}

		if (isset($this->request->get['filter_statement_sent'])) {
			$url .= '&filter_statement_sent=' . $this->request->get['filter_statement_sent'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $payroll_release_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_release_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($payroll_release_count - $this->config->get('config_limit_admin'))) ? $payroll_release_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $payroll_release_count, ceil($payroll_release_count / $this->config->get('config_limit_admin')));

		$data['presence_period_id'] = $presence_period_id;
		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_payroll_method_id'] = $filter_payroll_method_id;
		$data['filter_statement_sent'] = $filter_statement_sent;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/payroll_method');
		$data['payroll_methods'] = $this->model_localisation_payroll_method->getPayrollMethods();

		//		$this->load->model('setting/store');

		//		$data['stores'] = $this->model_setting_store->getStores();

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

		if (isset($this->request->get['presence_period_id']) && isset($this->request->post['selected']) && $this->validateExport()) {
			$mail_error = [];

			foreach ($this->request->post['selected'] as $customer_id) {
				$error_status = $this->model_payroll_payroll_release->sendStatement($this->request->get['presence_period_id'], $customer_id);

				if ($error_status) {
					$mail_error[] = $error_status;
				}
			}

			$this->session->data['success'] = $this->language->get('text_success_send');

			if ($mail_error) {
				$this->session->data['success'] .= '<br>' . $this->language->get('error_mail_sending_status');
				$this->session->data['success'] .= '<br>' . implode('<br>', $mail_error);
			}

			$url = '';

			if (isset($this->request->get['presence_period_id'])) {
				$url .= '&presence_period_id=' . $this->request->get['presence_period_id'];
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('payroll/payroll_release/info', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function releaseInfo()
	{
		$this->load->language('payroll/payroll_release');

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');

		$presence_period_id = $this->request->get['presence_period_id'];

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

		//Text Period
		$payroll_period = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!empty($payroll_period) && $payroll_period['fund_account_id']) {
			$this->load->model('release/fund_account');
			$fund_account_info = $this->model_release_fund_account->getFundAccount($payroll_period['fund_account_id']);

			$data['fund_acc_name'] = $fund_account_info['acc_name'];
			$data['fund_acc_no'] = $fund_account_info['bank_name'] . ' - ' . $fund_account_info['acc_no'];
			$data['fund_email'] = $fund_account_info['email'];
			$data['fund_date_release'] = date($this->language->get('date_format_jMY'), strtotime($payroll_period['date_release']));

			$method_releases = $this->model_payroll_payroll_release->getMethodReleases($presence_period_id);

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
			$this->model_payroll_payroll_release->archivePeriodData($presence_period_id);

			$this->model_common_payroll->setPeriodStatus($presence_period_id, 'completed');

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
			$this->model_payroll_payroll_release->unarchivePeriodData($presence_period_id);

			$this->model_common_payroll->setPeriodStatus($presence_period_id, 'released');

			$json['success'] = true;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function exportCimb()
	{
		$this->load->language('payroll/payroll_release');

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll_release');
		$this->load->model('payroll/payroll');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if (!empty($period_info) && $this->validateExport()) {
			$period = date('M_Y', strtotime($period_info['period']));

			$currency_code = $this->config->get('config_currency');
			$date_release = date('Ymd', strtotime($period_info['date_release']));

			$output = '';
			$sub_output = '';
			$sum_grandtotal = 0;

			if (isset($this->request->post['selected'])) {
				$filter_customer = implode(',', $this->request->post['selected']);
			} else {
				$filter_customer = '';
			}

			$filter_data = array(
				'method'      		=> 'CIMB',
				'filter_customer'	=> $filter_customer
			);

			$customer_total = $this->model_payroll_payroll_release->getPayrollsCount($presence_period_id, $filter_data);
			$results = $this->model_payroll_payroll_release->getPayrolls($presence_period_id, $filter_data);

			foreach ($results as $result) {
				$earning = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + $result['total_uang_makan'];
				$deduction = $result['pot_sakit'] + $result['pot_bolos'] + $result['pot_tunj_hadir'] + $result['pot_gaji_pokok'] + $result['pot_terlambat'];
				$grandtotal = $earning - $deduction;

				//Payroll Component
				$component_info = $this->model_payroll_payroll->getPayrollComponentTotal($presence_period_id, $result['customer_id']);

				$grandtotal += $component_info['grandtotal'];
				$sum_grandtotal += $grandtotal;

				$value = '';
				$value .= $result['acc_no'] . ',' . $result['lastname'] . ',' . $currency_code . ',' . $grandtotal . ',Payroll_' . $period . ',' . $result['email'] . ',,';

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

			$filename = date('Ym', strtotime($period_info['period'])) . '_Payroll_' . $period;

			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
			// echo $output;
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

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateExport()
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

		return !$this->error;
	}
}
