<?php
class ControllerReleaseAllowance extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('release/allowance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/allowance');
		
		$this->getList();
		
	}

	public function add() {
		$this->load->language('release/allowance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/allowance');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$allowance_id = $this->model_release_allowance->addAllowance($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			// $this->response->redirect($this->url->link('release/allowance', 'token=' . $this->session->data['token'] . $url, true));
			$this->response->redirect($this->url->link('release/allowance/edit', 'token=' . $this->session->data['token'] . '&allowance_id=' . $allowance_id . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('release/allowance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/allowance');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_release_allowance->editAllowance($this->request->get['allowance_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('release/allowance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('release/allowance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/allowance');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $allowance_id) {
				$this->model_release_allowance->deleteAllowance($allowance_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('release/allowance', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'a.allowance_period';
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

		$url = '';

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
			'href' => $this->url->link('release/allowance', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('release/allowance/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('release/allowance/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$data['allowances'] = array();

		$allowance_count = $this->model_release_allowance->getAllowancesCount($filter_data);
		
		$results = $this->model_release_allowance->getAllowances($filter_data);

		foreach ($results as $result) {
			$data['allowances'][] = array(
				'allowance_id' 		=> $result['allowance_id'],
				'allowance_period' 	=> $this->language->get('heading_title') . ' - ' . date($this->language->get('date_format_jMY'), strtotime($result['allowance_period'])),
				'date_process' 		=> date($this->language->get('date_format_jMY'), strtotime($result['date_process'])),
				'fund_account' 		=> $result['acc_name'] . '</br> (' . $result['bank_name'] . ' - ' . $result['acc_no'] . ')',
				'count' 			=> $result['count'],
				'total' 			=> $this->currency->format($result['total'], $this->config->get('config_currency')),
				'date_modified' 	=> date($this->language->get('date_format_jMY'), strtotime($result['date_modified'])),
				'username'    		=> $result['username'],
				'edit'          	=> $this->url->link('release/allowance/edit', 'token=' . $this->session->data['token'] . '&allowance_id=' . $result['allowance_id'], true),
				'export'        	=> $this->url->link('release/allowance/exportcsv', 'token=' . $this->session->data['token'] . '&allowance_id=' . $result['allowance_id'] . $url, true)
			);
		}
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_allowance_period',
			'column_date_process',
			'column_fund_account',
			'column_count',
			'column_total',
			'column_date_modified',
			'column_username',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete',
			'button_export_csv'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_allowance_period'] = $this->url->link('release/allowance', 'token=' . $this->session->data['token'] . '&sort=a.allowance_period' . $url, true);
		$data['sort_date_process'] = $this->url->link('release/allowance', 'token=' . $this->session->data['token'] . '&sort=a.date_process' . $url, true);
		$data['sort_date_modified'] = $this->url->link('release/allowance', 'token=' . $this->session->data['token'] . '&sort=a.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $allowance_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('release/allowance', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($allowance_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($allowance_count - $this->config->get('config_limit_admin'))) ? $allowance_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $allowance_count, ceil($allowance_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('release/allowance_list', $data));
	}
	
	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['allowance_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_confirm',
			'text_loading',
			'text_calculate',
			'entry_allowance_period',
			'entry_date_process',
			'entry_fund_account',
			'column_customer',
			'column_email',
			'column_method',
			'column_portion',
			'column_amount',
			'column_action',
			'button_add',
			'button_save',
			'button_cancel',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$errors = array(
			'warning',
			'description',
			'allowance_period',
			'fund_account',
			'date_process'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		$url = '';

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
			'href' => $this->url->link('release/allowance', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['allowance_id'])) {
			$data['action'] = $this->url->link('release/allowance/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('release/allowance/edit', 'token=' . $this->session->data['token'] . '&allowance_id=' . $this->request->get['allowance_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('release/allowance', 'token=' . $this->session->data['token'], true);

		$data['allowance_customers'] = array();
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$limit = $this->config->get('config_limit_admin');

		$allowance_customers_count = 0;
		
		if (isset($this->request->get['allowance_id'])) {
			$allowance_info = $this->model_release_allowance->getAllowance($this->request->get['allowance_id']);

			$filter_data = array(
				'start'   => ($page - 1) * $limit,
				'limit'   => $limit
			);

			$allowance_customers = $this->model_release_allowance->getAllowanceCustomers($this->request->get['allowance_id'], $filter_data);
			$allowance_customers_count = $this->model_release_allowance->getAllowanceCustomersCount($this->request->get['allowance_id']);
		}
		
		if (isset($this->request->post['allowance_period'])) {
			$data['allowance_period'] = $this->request->post['allowance_period'];
		} elseif (!empty($allowance_info)) {
			$data['allowance_period'] = date($this->language->get('date_format_jMY'), strtotime($allowance_info['allowance_period']));
		} else {
			$data['allowance_period'] = date($this->language->get('date_format_jMY'), strtotime('today'));
		}
		
		if (isset($this->request->post['fund_account_id'])) {
			$data['fund_account_id'] = $this->request->post['fund_account_id'];
		} elseif (!empty($allowance_info)) {
			$data['fund_account_id'] = $allowance_info['fund_account_id'];
		} else {
			$data['fund_account_id'] = 0;
		}
		
		if (isset($this->request->post['date_process'])) {
			$data['date_process'] = $this->request->post['date_process'];
		} elseif (!empty($allowance_info)) {
			$data['date_process'] = date($this->language->get('date_format_jMY'), strtotime($allowance_info['date_process']));
		} else {
			$data['date_process'] = date($this->language->get('date_format_jMY'), strtotime('today'));
		}
		
		// $components = [
		// 	'gaji_pokok',
		// 	'tunj_jabatan',
		// 	'tunj_hadir',
		// 	'tunj_pph',
		// 	'uang_makan'
		// ];

		// $data['components'] = [];

		// foreach ($components as $component) {
		// 	$data['components'][] = [
		// 		'value'	=> $component,
		// 		'text'	=> $component == 'uang_makan' ? sprintf($this->language->get('text_' . $component), $this->config->get('payroll_setting_default_hke')) : $this->language->get('text_' . $component)
		// 	];
		// };

		// if (isset($this->request->post['allowance_components'])) {
		// 	$data['allowance_components'] = $this->request->post['allowance_components'];
		// } elseif (!empty($allowance_info)) {
		//    	$data['allowance_components'] = $allowance_info('allowance_components');
		// } else {
		// 	$data['allowance_components'] = ['gaji_pokok', 'tunj_jabatan', 'tunj_hadir', 'tunj_pph'];
		// }

		if (!empty($allowance_customers)) {
			$date_allowance = date_create($allowance_info['allowance_period']);
			
			foreach ($allowance_customers as $allowance_customer) {
				$date_start = date_create($allowance_customer['date_start']);
				
				$diff = date_diff($date_start, $date_allowance);
				
				if ($diff->format('%y')) {
					$portion = $this->language->get('text_full');
				} elseif ($diff->format('%m') > 2) {
					$portion = $diff->format('%m') . '/12';
				} else {
					$portion = '-';
				}
				
				$data['allowance_customers'][] = array(
					'customer_id' 		=> $allowance_customer['customer_id'],
					'customer_text' 	=> $allowance_customer['name'] . ' - ' . $allowance_customer['customer_group'],
					'email' 			=> $allowance_customer['email'],
					'method' 			=> $allowance_customer['payroll_method'] . ($allowance_customer['acc_no'] ? ' - ' . $allowance_customer['acc_no'] : ''),
					'portion' 			=> $portion,
					'amount_value' 		=> $allowance_customer['amount'],
					'amount' 			=> $this->currency->format($allowance_customer['amount'], $this->config->get('config_currency'))
				);
			}
		}
		
		$this->load->model('release/fund_account');
		$fund_accounts = $this->model_release_fund_account->getFundAccounts();
		
		$data['fund_accounts'] = array();
		foreach ($fund_accounts as $fund_account) {
			$data['fund_accounts'][] = array(
				'fund_account_id'	=> $fund_account['fund_account_id'],
				'fund_account_text'	=> $fund_account['acc_name'] . '; ' . $fund_account['bank_name'] . ' - ' .  $fund_account['acc_no']
			);
		}
		
		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['allowance_id'])) {
			$allowance_id = $this->request->get['allowance_id'];
		} else {
			$allowance_id = 0;
		}

		//Text User Modify
		if (!empty($allowance_info)) {
			$username = $allowance_info['username'];
			$date_modified = date($this->language->get('date_format_jMY'), strtotime($allowance_info['date_modified']));
		} else {
			$username = $this->user->getUserName();
			$date_modified = date($this->language->get('date_format_jMY'));
		}
		$data['text_modified'] = sprintf($this->language->get('text_modified'), $username, $date_modified);
		
		$url = '';
		$url .= '&allowance_id=' . $allowance_id;

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $allowance_customers_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('release/allowance/edit', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($allowance_customers_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($allowance_customers_count - $limit)) ? $allowance_customers_count : ((($page - 1) * $limit) + $limit), $allowance_customers_count, ceil($allowance_customers_count / $limit));

		$data['allowance_id'] = $allowance_id;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('release/allowance_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'release/allowance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['allowance_id']) && empty(strtotime($this->request->post['allowance_period']))) {
			$this->error['allowance_period'] = $this->language->get('error_allowance_period');
		}

		$this->load->model('common/payroll');

		if (!$this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime($this->db->escape($this->request->post['allowance_period']))))) {
			$this->error['allowance_period'] = $this->language->get('error_presence_period');
		}
		
		if (empty($this->request->post['fund_account_id'])) {
			$this->error['fund_account'] = $this->language->get('error_fund_account');
		}
		
		if (empty($this->request->post['date_process']) || strtotime($this->request->post['date_process']) < strtotime('today')) {
			$this->error['date_process'] = $this->language->get('error_date_process');
		}

		if (isset($this->request->get['allowance_id']) && $this->model_release_allowance->checkAllowanceProcessed($this->request->get['allowance_id'])) {
			$this->error['warning'] = $this->language->get('error_processed');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'release/allowance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $allowance_id) {
			if ($this->model_release_allowance->checkAllowanceProcessed($allowance_id)) {
				$this->error['warning'] = $this->language->get('error_processed');
			}
		}

		return !$this->error;
	}

	public function editAllowanceCustomer() {
		$this->load->language('release/allowance');

		$json = array();

		if (!$this->user->hasPermission('modify', 'release/allowance')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('release/allowance');

			if (isset($this->request->get['allowance_id'])) {
				$allowance_id = $this->request->get['allowance_id'];
			} else {
				$allowance_id = 0;
			}

			$allowance_info = $this->model_release_allowance->getAllowance($allowance_id);

			if (!$allowance_info) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($this->model_release_allowance->checkAllowanceProcessed($allowance_id)) {
				$json['error'] = $this->language->get('error_processed');
			}
		}

		if (!$json) {
			$this->load->model('common/payroll');

			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = 0;
			}

			$customer_info = $this->model_common_payroll->checkCustomer($customer_id);

			if (!$customer_info) {
				$json['error'] = $this->language->get('error_customer_not_found');
			}
		}

		if (!$json) {
			$amount = $this->request->post['amount'];
			
			$this->model_release_allowance->editAllowanceCustomer($allowance_id, $customer_id, $amount);

			$json['amount_value'] = $amount;
			$json['amount'] = $this->currency->format($amount, $this->config->get('config_currency'));
			$json['success'] = $this->language->get('text_success');
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function deleteAllowanceCustomer() {
		$this->load->language('release/allowance');

		$json = array();

		if (!$this->user->hasPermission('modify', 'release/allowance')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('release/allowance');

			if (isset($this->request->get['allowance_id'])) {
				$allowance_id = $this->request->get['allowance_id'];
			} else {
				$allowance_id = 0;
			}

			$allowance_info = $this->model_release_allowance->getAllowance($allowance_id);

			if (!$allowance_info) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($this->model_release_allowance->checkAllowanceProcessed($allowance_id)) {
				$json['error'] = $this->language->get('error_processed');
			}
		}

		if (!$json) {
			if (isset($this->request->get['customer_id'])) {
				$customer_id = $this->request->get['customer_id'];
			} else {
				$customer_id = 0;
			}

			$this->model_release_allowance->deleteAllowanceCustomer($allowance_id, $customer_id);

			$json['success'] = $this->language->get('text_success');
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function exportCsv() {
		$this->load->language('release/allowance');

		$this->load->model('release/allowance');

		if (isset($this->request->get['allowance_id'])) {
			$allowance_id = $this->request->get['allowance_id'];
		} else {
			$allowance_id = 0;
		}

		$allowance_info = $this->model_release_allowance->getAllowance($allowance_id);
		
		if (!empty($allowance_info) && $this->validateExport()) {
			$this->load->model('release/fund_account');
			$fund_account_info = $this->model_release_fund_account->getFundAccount($allowance_info['fund_account_id']);
			
			$currency_code = $this->config->get('config_currency');
			$date_process = date('Ymd', strtotime($allowance_info['date_process']));
			
			$description = $this->language->get('heading_title') . ' - ' . date($this->language->get('date_format_m_y'), strtotime($allowance_info['allowance_period']));
			
			$result_count = $this->model_release_allowance->getAllowanceCustomerCountByMethod($allowance_id, 'CIMB');
			$result_total = $this->model_release_allowance->getAllowanceCustomerTotalByMethod($allowance_id, 'CIMB');
			
			$output = '';
			$output .= $fund_account_info['acc_no'] . ',' . $fund_account_info['acc_name'] . ',' . $currency_code . ',' . $result_total . ',' . $description . ',' . $result_count . ',' . $date_process . ',' . $fund_account_info['email']; 

			$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
			$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
			$output = str_replace('\\', '\\\\', $output);
			$output = str_replace('\'', '\\\'', $output);
			$output = str_replace('\\\n', '\n', $output);
			$output = str_replace('\\\r', '\r', $output);
			$output = str_replace('\\\t', '\t', $output);

			$results = $this->model_release_allowance->getAllowanceCustomersByMethod($allowance_id, 'CIMB');

			foreach ($results as $result) {
				$value = '';
				$value .= $result['acc_no'] . ',' . $result['lastname'] . ',' . $currency_code . ',' . $result['amount'] . ',' . $description . ',' . $result['email'] . ',,';

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\', $value);
				$value = str_replace('\'', '\\\'', $value);
				$value = str_replace('\\\n', '\n', $value);
				$value = str_replace('\\\r', '\r', $value);
				$value = str_replace('\\\t', '\t', $value);
				
				$output .= "\n" . $value;
			}
				
			$filename = str_replace(' ', '_', $description . '_' . $allowance_info['date_process']);
			
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
			// echo $output;
		} else {
		
			$this->index();
		}
	}

	protected function validateExport() {
		if (!$this->user->hasPermission('modify', 'release/allowance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
