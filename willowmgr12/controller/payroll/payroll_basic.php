<?php
class ControllerPayrollPayrollBasic extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payroll/payroll_basic');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_basic');
		$this->load->model('presence/presence');
		
		$this->getList();
	}

	public function edit() {
		$this->load->language('payroll/payroll_basic');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_basic');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_payroll_payroll_basic->editPayrollBasic($this->request->get['customer_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('payroll/payroll_basic/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true));
		}

		$this->getForm();
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
			'href' => $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . $url, true)
		);

		$filter_data = array(
			'filter_payroll_include'=> 1,
			'filter_name'	   	   => $filter_name,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status' 	   => $filter_status,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$data['customers'] = array();

		$customer_total = $this->model_presence_presence->getTotalCustomers($filter_data);

		$results = $this->model_presence_presence->getCustomers($filter_data);

		foreach ($results as $result) {
			$payroll_basic = $this->model_payroll_payroll_basic->getPayrollBasic($result['customer_id']);
			
			if ($payroll_basic) {
				$data['customers'][] = array(
					'customer_id' 		=> $result['customer_id'],
					'nip' 				=> $result['nip'],
					'name' 				=> $result['name'],
					'customer_group' 	=> $result['customer_group'],
					'gaji_pokok'    	=> $this->currency->format($payroll_basic['gaji_pokok'], $this->config->get('config_currency')),
					'tunj_jabatan'  	=> $this->currency->format($payroll_basic['tunj_jabatan'], $this->config->get('config_currency')),
					'tunj_hadir'    	=> $this->currency->format($payroll_basic['tunj_hadir'], $this->config->get('config_currency')),
					'tunj_pph'    		=> $this->currency->format($payroll_basic['tunj_pph'], $this->config->get('config_currency')),
					'uang_makan'    	=> $this->currency->format($payroll_basic['uang_makan'], $this->config->get('config_currency')),
					'gaji_dasar'    	=> $this->currency->format($payroll_basic['gaji_pokok'] + $payroll_basic['tunj_jabatan'] + $payroll_basic['tunj_hadir'] + $payroll_basic['tunj_pph'] + ($payroll_basic['uang_makan'] * 25), $this->config->get('config_currency')),
					'date_added'        => date($this->language->get('date_format_jMY'), strtotime($payroll_basic['date_added'])),
					'edit'          	=> $this->url->link('payroll/payroll_basic/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				);
			} else {
				$data['customers'][] = array(
					'customer_id' 		=> $result['customer_id'],
					'nip' 				=> $result['nip'],
					'name' 				=> $result['name'],
					'customer_group' 	=> $result['customer_group'],
					'gaji_pokok' 		=> '',
					'tunj_jabatan'      => '',
					'tunj_hadir'        => '',
					'tunj_pph'         	=> '',
					'uang_makan'        => '',
					'gaji_dasar'        => '',
					'date_added'        => '',
					'edit'          	=> $this->url->link('payroll/payroll_basic/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				);
			}
		}
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_all_customer_group',
			'text_active',
			'text_inactive',
			'text_all_status',
			'entry_name',
			'entry_customer_group',
			'entry_status',
			'column_date',
			'column_inv_no',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_action',
			'column_gaji_pokok',
			'column_tunj_jabatan',
			'column_tunj_hadir',
			'column_tunj_pph',
			'column_uang_makan',
			'column_gaji_dasar',
			'column_date_added',
			'column_status',
			'button_filter',
			'button_add',
			'button_edit',
			'button_delete',
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

		$data['sort_nip'] = $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);

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
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

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

		$this->response->setOutput($this->load->view('payroll/payroll_basic_list', $data));
	}

	protected function getForm() {
		$data['text_edit'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_gaji_pokok',
			'entry_tunj_jabatan',
			'entry_tunj_hadir',
			'entry_tunj_pph',
			'entry_uang_makan',
			'entry_gaji_dasar',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

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

		if (isset($this->error['gaji_pokok'])) {
			$data['error_gaji_pokok'] = $this->error['gaji_pokok'];
		} else {
			$data['error_gaji_pokok'] = '';
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
			'href' => $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['customer_id'])) {
			$this->response->redirect($this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . $url, true));
		} else {
			$data['action'] = $this->url->link('payroll/payroll_basic/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_edit'],
			'href' => $data['action']
		);

		if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$payroll_basic_info = $this->model_payroll_payroll_basic->getPayrollBasic($this->request->get['customer_id']);
		}

		$payroll_basic_items = array(
			'gaji_pokok',
			'tunj_jabatan',
			'tunj_hadir',
			'tunj_pph',
			'uang_makan'
		);
		foreach ($payroll_basic_items as $payroll_basic_item) {
			if (isset($this->request->post[$payroll_basic_item])) {
				$data[$payroll_basic_item] = $this->request->post[$payroll_basic_item];
			} elseif (!empty($payroll_basic_info)) {
				$data[$payroll_basic_item] = number_format($payroll_basic_info[$payroll_basic_item]);
			} else {
				$data[$payroll_basic_item] = 0;
			}
		}

		if (!empty($payroll_basic_info)) {
			$gaji_dasar = $payroll_basic_info['gaji_pokok'] + $payroll_basic_info['tunj_jabatan'] + $payroll_basic_info['tunj_hadir'] + $payroll_basic_info['tunj_pph'] + (25 * $payroll_basic_info['uang_makan']);
			$data['gaji_dasar'] = number_format($gaji_dasar);
		} else {
			$data['gaji_dasar'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_basic_form', $data));
	}

	public function history() {
		$this->load->language('payroll/payroll_basic');

		$this->load->model('payroll/payroll_basic');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$results = $this->model_payroll_payroll_basic->getPayrollBasicHistories($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$gaji_dasar = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + (25 * $result['uang_makan']);
			$data['histories'][] = array(
				'gaji_pokok'    => $this->currency->format($result['gaji_pokok'], $this->config->get('config_currency')),
				'tunj_jabatan'  => $this->currency->format($result['tunj_jabatan'], $this->config->get('config_currency')),
				'tunj_hadir'    => $this->currency->format($result['tunj_hadir'], $this->config->get('config_currency')),
				'tunj_pph'    	=> $this->currency->format($result['tunj_pph'], $this->config->get('config_currency')),
				'uang_makan'    => $this->currency->format($result['uang_makan'], $this->config->get('config_currency')),
				'gaji_dasar' 	=> $this->currency->format($gaji_dasar, $this->config->get('config_currency')),
				'date_added' 	=> date($this->language->get('date_format_jMY'), strtotime($result['date_added'])),
				'username' 		=> $result['username']
			);
		}

		$history_total = $this->model_payroll_payroll_basic->getTotalPayrollBasicHistories($this->request->get['customer_id']);

		$language_items = array(
			'text_no_results',
			'column_date_added',
			'column_gaji_pokok',
			'column_tunj_jabatan',
			'column_tunj_hadir',
			'column_tunj_pph',
			'column_uang_makan',
			'column_gaji_dasar',
			'column_username'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('payroll/payroll_basic/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('payroll/payroll_basic_history', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'payroll/payroll_basic')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['gaji_pokok'] < 0) {
			$this->error['gaji_pokok'] = $this->language->get('error_gaji_pokok');
		}

		return !$this->error;
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
