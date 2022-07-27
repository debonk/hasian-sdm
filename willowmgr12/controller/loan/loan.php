<?php
class ControllerLoanLoan extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');
		
		$this->getList();
	}

	public function add() {
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_loan_loan->addLoan($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_loan_loan->editLoan($this->request->get['loan_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $loan_id) {
				$this->model_loan_loan->deleteLoan($loan_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
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

			$this->response->redirect($this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
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

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'l.date_added';
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('loan/loan/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('loan/loan/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'filter_name'	   	   => $filter_name,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status' 	   => $filter_status,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$data['loans'] = array();

		$results = $this->model_loan_loan->getLoans($filter_data);

		foreach ($results as $result) {
			$data['loans'][] = array(
				'loan_id' 			=> $result['loan_id'],
				'date_added' 		=> date($this->language->get('date_format_jMY'), strtotime($result['date_added'])),
				'name' 				=> $result['name'],
				'customer_group' 	=> $result['customer_group'],
				'description' 		=> $result['deskripsi'],
				'pinjaman_pokok'    => $this->currency->format($result['pinjaman_pokok'], $this->config->get('config_currency')),
				'cicilan'    		=> $this->currency->format($result['cicilan'], $this->config->get('config_currency')),
				'balance'    		=> $this->currency->format($result['balance'], $this->config->get('config_currency')),
				'username' 			=> $result['username'],
				'view'          	=> $this->url->link('loan/loan/info', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'edit'          	=> $this->url->link('loan/loan/edit', 'token=' . $this->session->data['token'] . '&loan_id=' . $result['loan_id'] . $url, true),
			);
		}
		
		$loan_count = $this->model_loan_loan->getLoansCount($filter_data);
		
		$results_total = $this->model_loan_loan->getLoansTotal($filter_data);
		
		$loan_total = $this->model_loan_loan->getLoansTotal();

		$data['subtotal'] = $this->currency->format($results_total, $this->config->get('config_currency'));
		$data['grandtotal'] = sprintf($this->language->get('text_grandtotal'), $this->currency->format($loan_total, $this->config->get('config_currency')));
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_subtotal',
			'text_all_customer_group',
			'text_paid',
			'text_unpaid',
			'text_all_status',
			'entry_name',
			'entry_customer_group',
			'entry_status',
			'column_date_added',
			'column_name',
			'column_customer_group',
			'column_description',
			'column_pinjaman_pokok',
			'column_cicilan',
			'column_balance',
			'column_action',
			'column_username',
			'button_add',
			'button_edit',
			'button_view',
			'button_delete',
			'button_filter'
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date_added'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=l.date_added' . $url, true);
		$data['sort_name'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_balance'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=balance' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $loan_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($loan_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($loan_count - $this->config->get('config_limit_admin'))) ? $loan_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $loan_count, ceil($loan_count / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('loan/loan_list', $data));
	}

	protected function getListByCustomer() {
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

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
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

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('loan/loan/add', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'filter_name'	   	   => $filter_name,
			'filter_customer_group_id' => $filter_customer_group_id,
			// 'filter_status' 	   => $filter_status,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$data['customers'] = array();

		$results = $this->model_loan_loan->getLoanSummaries($filter_data);

		if (isset($filter_status) && !is_null($filter_status)) {
			if (!empty($filter_status)) {
				$balance_check = 0;
			}
		} else {
			$balance_check = 1;
		}

		$results_total = 0;
		foreach ($results as $result) {
			// if (isset($balance_check) && ($result['total'] <=> 0) != $balance_check) { //supported in PHP 7
			if (isset($balance_check) && (($result['total'] > 0) - ($result['total'] < 0)) != $balance_check) {
				$status = 0;
			} else {
				$status = 1;
			}
		
			if ($status) {
				$results_total += $result['total'];
				
				$data['customers'][] = array(
					'customer_id' 		=> $result['customer_id'],
					'nip' 				=> $result['nip'],
					'name' 				=> $result['name'],
					'customer_group' 	=> $result['customer_group'],
					'telephone' 		=> $result['telephone'],
					'balance'    		=> $this->currency->format($result['total'], $this->config->get('config_currency')),
					'view'          	=> $this->url->link('loan/loan/info', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				);
			}
		}
		
		$customer_count = count(array_column($data['customers'], 'customer_id'));
		$grandtotal = $this->model_loan_loan->getLoanSummariesTotal();
		
		$data['subtotal'] = $this->currency->format($results_total, $this->config->get('config_currency'));
		$data['grandtotal'] = sprintf($this->language->get('text_grandtotal'), $this->currency->format($grandtotal, $this->config->get('config_currency')));
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_subtotal',
			'text_all_customer_group',
			'text_paid',
			'text_unpaid',
			'text_all_status',
			'entry_name',
			'entry_customer_group',
			'entry_status',
			// 'column_nip',
			'column_name',
			'column_customer_group',
			// 'column_telephone',
			'column_balance',
			'column_action',
			'column_username',
			'button_add',
			'button_view',
			'button_filter'
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_nip'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('loan/loan', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_count - $this->config->get('config_limit_admin'))) ? $customer_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_count, ceil($customer_count / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

//		$this->load->model('setting/store');

//		$data['stores'] = $this->model_setting_store->getStores();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('loan/loan_customer_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['loan_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select_customer',
			// 'text_select',
			'entry_date_added',
			'entry_customer',
			'entry_pinjaman_pokok',
			'entry_deskripsi',
			'entry_cicilan',
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
			'pinjaman_pokok',
			'cicilan',
			'date_start'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

		$data['customers'] = array();

		$this->load->model('presence/presence');
		$results = $this->model_presence_presence->getCustomers();
			
		foreach ($results as $result) {
			$data['customers'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'customer_text' 	=> $result['name'] . ' - ' . $result['customer_group']
			);
		}
		
		if (isset($this->request->get['loan_id'])) {
			$loan_info = $this->model_loan_loan->getLoan($this->request->get['loan_id']);
		}

		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($loan_info)) {
			$data['customer_id'] = $loan_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (isset($this->request->post['pinjaman_pokok'])) {
			$data['pinjaman_pokok'] = $this->request->post['pinjaman_pokok'];
		} elseif (!empty($loan_info)) {
			$data['pinjaman_pokok'] = $loan_info['pinjaman_pokok'];
		} else {
			$data['pinjaman_pokok'] = '';
		}

		if (isset($this->request->post['deskripsi'])) {
			$data['deskripsi'] = $this->request->post['deskripsi'];
		} elseif (!empty($loan_info)) {
			$data['deskripsi'] = $loan_info['deskripsi'];
		} else {
			$data['deskripsi'] = '';
		}

		if (isset($this->request->post['cicilan'])) {
			$data['cicilan'] = $this->request->post['cicilan'];
		} elseif (!empty($loan_info)) {
			$data['cicilan'] = $loan_info['cicilan'];
		} else {
			$data['cicilan'] = '';
		}

		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($loan_info)) {
			$data['date_start'] = date($this->language->get('date_format_m_y'), strtotime($loan_info['date_start']));
		} else {
			$data['date_start'] = '';
		}

		if (!empty($loan_info)) {
			$data['date_added'] = date($this->language->get('date_format_jMY'), strtotime($loan_info['date_added']));
		} else {
			$data['date_added'] = date($this->language->get('date_format_jMY'));
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('loan/loan_form', $data));
	}

	public function info() {
		$this->load->language('loan/loan');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('loan/loan');
		
		$data['text_info'] = $this->language->get('text_info');
		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_select'] = $this->language->get('text_select');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_loan'] = $this->language->get('entry_loan');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_amount'] = $this->language->get('entry_amount');

		$data['button_back'] = $this->language->get('button_back');
		$data['button_transaction_add'] = $this->language->get('button_transaction_add');
		
		$data['help_amount'] = $this->language->get('help_amount');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

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

		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = '';
		}

		if (isset($this->error['amount'])) {
			$data['error_amount'] = $this->error['amount'];
		} else {
			$data['error_amount'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'loan/loan')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['loan_id'])) {
			if ($this->request->post['customer_id'] == 0) {
				$this->error['warning'] = $this->language->get('error_customer_id');
			}

			if ($this->request->post['pinjaman_pokok'] <= 0) {
				$this->error['pinjaman_pokok'] = $this->language->get('error_pinjaman_pokok');
			}
			
			if (!$this->request->post['date_start'] || strtotime('20 ' . $this->request->post['date_start']) < strtotime('today')) {
				$this->error['date_start'] = $this->language->get('error_date_start');
			}
		}

		if ($this->request->post['cicilan'] < 0) {
			$this->error['cicilan'] = $this->language->get('error_cicilan');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
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

	public function transaction() {
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

	public function addTransaction() {
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

	public function history() {
		$this->load->language('loan/loan');
		
		$this->load->model('loan/loan');
		
		$data['text_history'] = $this->language->get('text_history');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['button_edit'] = $this->language->get('button_edit');

		$data['loans'] = array();
		
		$filter_data = array(
			'customer_id'	=> $this->request->get['customer_id'],
			'filter_status'	=> 0,
			'start'        	=> 0,
			'limit'        	=> 5
		);

		$results = $this->model_loan_loan->getLoans($filter_data);
		
		foreach ($results as $result) {
			$data['loans'][] = array(
				'description'    => '#' . $result['loan_id'] . ': ' . $result['deskripsi'],
				'total'          => $this->currency->format($result['balance'], $this->config->get('config_currency')),
				'edit'           => $this->url->link('loan/loan/edit', 'token=' . $this->session->data['token'] . '&loan_id=' . $result['loan_id'], true)
			);
		}

		$this->response->setOutput($this->load->view('loan/loan_history', $data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$this->load->model('presence/presence');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_presence_presence->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
