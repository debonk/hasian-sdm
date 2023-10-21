<?php
class ControllerIncentiveIncentive extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');
		
		$this->getList();
		
	}

	public function add() {
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_incentive_incentive->addIncentive($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . urlencode(html_entity_decode($this->request->get['filter_description'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_incentive_incentive->editIncentive($this->request->get['incentive_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . urlencode(html_entity_decode($this->request->get['filter_description'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $incentive_id) {
				$this->model_incentive_incentive->deleteIncentive($incentive_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_description'])) {
				$url .= '&filter_description=' . urlencode(html_entity_decode($this->request->get['filter_description'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_status'])) {
				$url .= '&filter_status=' . $this->request->get['filter_status'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_description'])) {
			$filter_description = $this->request->get['filter_description'];
		} else {
			$filter_description = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
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

		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . urlencode(html_entity_decode($this->request->get['filter_description'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('incentive/incentive/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('incentive/incentive/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'filter_name'			=> $filter_name,
			'filter_description' 	=> $filter_description,
			'filter_status' 		=> $filter_status,
			'start'         		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'         		=> $this->config->get('config_limit_admin')
		);

		$data['incentives'] = array();

		$incentive_count = $this->model_incentive_incentive->getIncentivesCount($filter_data);
		
		$results_total = $this->model_incentive_incentive->getIncentivesTotal($filter_data);
		$grandtotal = $this->model_incentive_incentive->getIncentivesTotal();

		$results = $this->model_incentive_incentive->getIncentives($filter_data);

		foreach ($results as $result) {
			if ($result['presence_period_id']) {
				$payment = date($this->language->get('date_format_m_y'), strtotime($result['period']));
			} else {
				$payment = 0;
			}
			
			$data['incentives'][] = array(
				'incentive_id' 		=> $result['incentive_id'],
				'date' 				=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'name' 				=> $result['name'] . ' - ' . $result['customer_group'],
				'description' 		=> $result['description'],
				'amount'    		=> $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'username'    		=> $result['username'],
				'payment'    		=> $payment,
				'view'          	=> $this->url->link('payroll/payroll/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'edit'          	=> $this->url->link('incentive/incentive/edit', 'token=' . $this->session->data['token'] . '&incentive_id=' . $result['incentive_id'] . $url, true),
			);
		}
		
		$data['subtotal'] = $this->currency->format($results_total, $this->config->get('config_currency'));
		$data['grandtotal'] = sprintf($this->language->get('text_grandtotal'), $this->currency->format($grandtotal, $this->config->get('config_currency')));
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_subtotal',
			'text_paid',
			'text_unpaid',
			'text_all_status',
			'text_confirm',
			'entry_name',
			'entry_description',
			'entry_status',
			'column_date',
			'column_name',
			'column_description',
			'column_amount',
			'column_action',
			'column_username',
			'column_payment',
			'button_filter',
			'button_add',
			'button_view',
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . urlencode(html_entity_decode($this->request->get['filter_description'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		$pagination = new Pagination();
		$pagination->total = $incentive_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($incentive_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($incentive_count - $this->config->get('config_limit_admin'))) ? $incentive_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $incentive_count, ceil($incentive_count / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_description'] = $filter_description;
		$data['filter_status'] = $filter_status;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('incentive/incentive_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['incentive_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'text_select_customer',
			'heading_title',
			'entry_name',
			'entry_customer_id',
			'entry_description',
			'entry_amount',
			'entry_date',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$error_items = array(
			'warning',
			'date',
			'description',
			'amount'
		);
		foreach ($error_items as $error_item) {
			if (isset($this->error[$error_item])) {
				$data['error_' . $error_item] = $this->error[$error_item];
			} else {
				$data['error_' . $error_item] = '';
			}
		}

		$data['token'] = $this->session->data['token'];

		$data['customers'] = array();

		$this->load->model('presence/presence');
		$this->load->model('common/payroll');

		$availability = (int)$this->config->get('config_customer_last');

		$period_info = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime('-' . $availability . ' months')));

		$filter_data = [];

		if ($period_info) {
			$filter_data['presence_period_id'] = $period_info['presence_period_id'];
		}

		$results = $this->model_presence_presence->getCustomers($filter_data);
		
		foreach ($results as $result) {
			$data['customers'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'name_department' 	=> $result['name'] . ' - ' . $result['customer_group']
			);
		}
		
		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_description'])) {
			$url .= '&filter_description=' . urlencode(html_entity_decode($this->request->get['filter_description'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['incentive_id'])) {
			$data['action'] = $this->url->link('incentive/incentive/add', 'token=' . $this->session->data['token'] . $url, true);
			$data['disabled'] = '';
		} else {
			$data['action'] = $this->url->link('incentive/incentive/edit', 'token=' . $this->session->data['token'] . '&incentive_id=' . $this->request->get['incentive_id'] . $url, true);
			$data['disabled'] = 'disabled';
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['incentive_id'])) {
			$incentive_info = $this->model_incentive_incentive->getIncentive($this->request->get['incentive_id']);
		}
		
		$incentive_items = array (
			'customer_id',
			'description',
			'amount'
		);
		foreach ($incentive_items as $item) {
			if (isset($this->request->post[$item])) {
				$data[$item] = $this->request->post[$item];
			} elseif (!empty($incentive_info)) {
				$data[$item] = $incentive_info[$item];
			} else {
				$data[$item] = '';
			}
		}

		if (isset($this->request->post['date'])) {
			$data['date'] = $this->request->post['date'];
		} elseif (!empty($incentive_info)) {
			$data['date'] = date($this->language->get('date_format_jMY'), strtotime($incentive_info['date']));
		} else {
			$data['date'] = '';
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('incentive/incentive_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'incentive/incentive')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['incentive_id']) && empty($this->request->post['customer_id'])) {
			$this->error['warning'] = $this->language->get('error_customer_id');
		}

		if (empty(($this->request->post['date']))) {
			$this->error['date'] = $this->language->get('error_date');
		}

		if ($this->request->post['amount'] <= 0) {
			$this->error['amount'] = $this->language->get('error_amount');
		}

		if ((utf8_strlen($this->request->post['description']) < 1) || (utf8_strlen(trim($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (!$this->error) {
			$this->load->model('common/payroll');
			
			$period_info = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime($this->request->post['date'])));
			
			if ($this->user->hasPermission('bypass', 'incentive/incentive')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) {//Check period status
					$this->error['date'] = $this->language->get('error_status');
				}
			} else {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'generated, approved, released, completed')) {//Check period status
					$this->error['date'] = $this->language->get('error_status');
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'incentive/incentive')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('common/payroll');
		
		foreach ($this->request->post['selected'] as $incentive_id) {
			$incentive_info = $this->model_incentive_incentive->getIncentive($incentive_id);

			$period_info = $this->model_common_payroll->getPeriodByDate($incentive_info['date']);
			
			if ($this->user->hasPermission('bypass', 'incentive/incentive')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');
					
					break;
				}
			} else {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'generated, approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');
					
					break;
				}
			}
		}

		return !$this->error;
	}
}
