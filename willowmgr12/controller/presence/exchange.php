<?php
class ControllerPresenceExchange extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('presence/exchange');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/exchange');
		$this->load->model('common/payroll');
		
		$this->getList();
		
	}

	public function add() {
		$this->load->language('presence/exchange');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/exchange');
		$this->load->model('common/payroll');
			

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_exchange->addExchange($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
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

			$this->response->redirect($this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('presence/exchange');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/exchange');
		$this->load->model('common/payroll');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_exchange->editExchange($this->request->get['exchange_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
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

			$this->response->redirect($this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('presence/exchange');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/exchange');
		$this->load->model('common/payroll');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $exchange_id) {
				$this->model_presence_exchange->deleteExchange($exchange_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_date'])) {
				$url .= '&filter_date=' . $this->request->get['filter_date'];
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
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

			$this->response->redirect($this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_date'])) {
			$filter_date = $this->request->get['filter_date'];
		} else {
			$filter_date = '';
		}

		if (isset($this->request->get['filter_period_id'])) {
			$filter_period_id = $this->request->get['filter_period_id'];
		} else {
			$filter_period_id = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date';
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

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
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
			'href' => $this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('presence/exchange/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('presence/exchange/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'filter_name'				=> $filter_name,
			'filter_date'				=> $filter_date,
			'filter_period_id'			=> $filter_period_id,
			'sort'  					=> $sort,
			'order' 					=> $order,
			'start'         			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'         			=> $this->config->get('config_limit_admin')
		);

		$data['exchanges'] = array();

		$results = $this->model_presence_exchange->getExchanges($filter_data);

		foreach ($results as $result) {
			$data['exchanges'][] = array(
				'exchange_id' 		=> $result['exchange_id'],
				'date_from' 		=> date($this->language->get('date_format_jMY'), strtotime($result['date_from'])),
				'date_to' 			=> date($this->language->get('date_format_jMY'), strtotime($result['date_to'])),
				'name' 				=> $result['name'],
				'description' 		=> strlen($result['description']) > 30 ? substr($result['description'], 0, 28) . '..' : $result['description'],
				'username'    		=> $result['username'],
				// 'view'          	=> $this->url->link('presence/schedule/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . '&customer_id=' . $result['customer_id'], true),
				'edit'          	=> $this->url->link('presence/exchange/edit', 'token=' . $this->session->data['token'] . '&exchange_id=' . $result['exchange_id'] . $url, true),
			);
		}
		
		$exchanges_count = $this->model_presence_exchange->getExchangesCount($filter_data);
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_loading',
			'text_all_period',
			'entry_date',
			'entry_name',
			'entry_period',
			'column_date_from',
			'column_date_to',
			'column_name',
			'column_description',
			'column_username',
			'column_action',
			'button_filter',
			'button_add',
			'button_view',
			'button_edit',
			'button_delete'
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

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date_from'] = $this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . '&sort=date_from' . $url, true);
		$data['sort_date_to'] = $this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . '&sort=date_to' . $url, true);
		$data['sort_name'] = $this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $exchanges_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($exchanges_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($exchanges_count - $this->config->get('config_limit_admin'))) ? $exchanges_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $exchanges_count, ceil($exchanges_count / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_date'] = $filter_date;
		$data['filter_period_id'] = $filter_period_id;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('presence/presence_period');
		$data['periods'] = $this->model_presence_presence_period->getPresencePeriods();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/exchange_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['exchange_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select_customer',
			'text_select',
			'text_loading',
			'entry_name',
			'entry_date_from',
			'entry_date_to',
			'entry_schedule_type',
			'entry_description',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$data['customers'] = array();

		$this->load->model('presence/presence');
		$results = $this->model_presence_presence->getCustomers();
			
		foreach ($results as $result) {
			$data['customers'][] = array(
				'customer_id' 	=> $result['customer_id'],
				'text' 			=> $result['name'] . ' - ' . $result['customer_group']
			);
		}

		$errors = array(
			'warning',
			'date_from',
			'date_to',
			'schedule_type',
			'description'
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

		if (isset($this->request->get['filter_presence_status_id'])) {
			$url .= '&filter_presence_status_id=' . $this->request->get['filter_presence_status_id'];
		}

		if (isset($this->request->get['filter_date'])) {
			$url .= '&filter_date=' . $this->request->get['filter_date'];
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
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
			'href' => $this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['exchange_id'])) {
			$data['action'] = $this->url->link('presence/exchange/add', 'token=' . $this->session->data['token'] . $url, true);
			$data['disabled'] = '';
		} else {
			$data['action'] = $this->url->link('presence/exchange/edit', 'token=' . $this->session->data['token'] . '&exchange_id=' . $this->request->get['exchange_id'] . $url, true);
			$data['disabled'] = 'disabled';
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('presence/exchange', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['exchange_id'])) {
			$exchange_info = $this->model_presence_exchange->getExchange($this->request->get['exchange_id']);
		}

		//Text User Modify
		if (!empty($exchange_info)) {
			$username = $exchange_info['username'];
			$date_modified = date($this->language->get('date_format_jMY'), strtotime($exchange_info['date_modified']));
		} else {
			$username = $this->user->getUserName();
			$date_modified = date($this->language->get('date_format_jMY'));
		}
		$data['text_modified'] = sprintf($this->language->get('text_modified'), $username, $date_modified);
		
		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($exchange_info)) {
			$data['customer_id'] = $exchange_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}
		
		if (isset($this->request->post['date_from'])) {
			$data['date_from'] = $this->request->post['date_from'];
		} elseif (!empty($exchange_info)) {
			$data['date_from'] = date($this->language->get('date_format_jMY'), strtotime($exchange_info['date_from']));
		} else {
			$data['date_from'] = '';
		}
		
		if (isset($this->request->post['date_to'])) {
			$data['date_to'] = $this->request->post['date_to'];
		} elseif (!empty($exchange_info)) {
			$data['date_to'] = date($this->language->get('date_format_jMY'), strtotime($exchange_info['date_to']));
		} else {
			$data['date_to'] = '';
		}
		
		if (isset($this->request->post['schedule_type_id'])) {
			$data['schedule_type_id'] = $this->request->post['schedule_type_id'];
		} elseif (!empty($exchange_info)) {
			$data['schedule_type_id'] = $exchange_info['schedule_type_id'];
		} else {
			$data['schedule_type_id'] = 0;
		}
		
		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($exchange_info)) {
			$data['description'] = $exchange_info['description'];
		} else {
			$data['description'] = '';
		}
		
		$customer_info = $this->model_common_payroll->getCustomer($data['customer_id']);
		
		if ($customer_info && $customer_info['location_id'] && $customer_info['customer_group_id']) {
			$this->load->model('presence/schedule_type');
			
			$data['schedule_types'] = $this->model_presence_schedule_type->getScheduleTypesByLocationGroup($customer_info['location_id'], $customer_info['customer_group_id']);
		} else {
			$data['schedule_types'] = array();
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/exchange_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'presence/exchange')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['exchange_id']) && empty($this->request->post['customer_id'])) {
			$this->error['warning'] = $this->language->get('error_customer_id');
		}

		if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen(trim($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (empty($this->request->post['schedule_type_id'])) {
			$this->error['schedule_type'] = $this->language->get('error_schedule_type');
		}
		
		if (empty($this->request->post['date_from'])) {
			$this->error['date_from'] = $this->language->get('error_date_from');
		}

		if (empty($this->request->post['date_to'])) {
			$this->error['date_to'] = $this->language->get('error_date_to');
		}

		if (!$this->error) {
			$date_from = date('Y-m-d', strtotime($this->request->post['date_from']));
			$period_from_info = $this->model_common_payroll->getPeriodByDate($date_from);
			
			$date_to = date('Y-m-d', strtotime($this->request->post['date_to']));
			$period_to_info = $this->model_common_payroll->getPeriodByDate($date_to);

			if ($this->user->hasPermission('bypass', 'presence/exchange')) {
				if ($period_from_info && $this->model_common_payroll->checkPeriodStatus($period_from_info['presence_period_id'], 'approved, released, completed')) {//Check period status
					$this->error['date_from'] = $this->language->get('error_status');
				}
				
				if ($period_to_info && $this->model_common_payroll->checkPeriodStatus($period_to_info['presence_period_id'], 'approved, released, completed')) {//Check period status
					$this->error['date_to'] = $this->language->get('error_status');
				}
				
			} else {
				if ($period_from_info && $this->model_common_payroll->checkPeriodStatus($period_from_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {//Check period status
					$this->error['date_from'] = $this->language->get('error_status');
				}
				
				if ($period_to_info && $this->model_common_payroll->checkPeriodStatus($period_to_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {//Check period status
					$this->error['date_to'] = $this->language->get('error_status');
				}
				
				if (strtotime($this->request->post['date_from']) < strtotime('today')) {
					$this->error['date_from'] = $this->language->get('error_date_past');
				}
				
				if (strtotime($this->request->post['date_to']) < strtotime('today')) {
					$this->error['date_to'] = $this->language->get('error_date_past');
				}
				
				if (isset($this->request->get['exchange_id'])) {
					$exchange_info = $this->model_presence_exchange->getExchange($this->request->get['exchange_id']);
				
					$customer_id = $exchange_info['customer_id'];
					$date_from_check = $exchange_info['date_from'];
					$date_to_check = $exchange_info['date_to'];
				} else {
					$customer_id = $this->request->post['customer_id'];
					$date_from_check = '';
					$date_to_check = '';
				}
				
				$schedule_from_info = $this->model_common_payroll->getAppliedSchedule($customer_id, $date_from);
				$schedule_to_info = $this->model_common_payroll->getAppliedSchedule($customer_id, $date_to);
			
				if (empty($schedule_from_info) || empty($schedule_from_info['schedule_type_id'])) {//Check schedule hrs ada
					$this->error['date_from'] = $this->language->get('error_schedule_from');
				} elseif ($schedule_from_info['applied'] == 'overtime' || $schedule_from_info['applied'] == 'absence' || ($schedule_from_info['applied'] == 'exchange' && strtotime($date_from_check) != strtotime($date_from))) {//Check date used
					$this->error['date_from'] = $this->language->get('error_date_used');
				}
				
				if ($schedule_to_info) {
					if ($schedule_to_info['applied'] == 'overtime' || $schedule_to_info['applied'] == 'absence' || ($schedule_to_info['applied'] == 'exchange' && strtotime($date_to_check) != strtotime($date_to))) {//Check date used
						$this->error['date_to'] = $this->language->get('error_date_used');
					}
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'presence/exchange')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $exchange_id) {
			$exchange_info = $this->model_presence_exchange->getExchange($exchange_id);
			
			$period_from_info = $this->model_common_payroll->getPeriodByDate($exchange_info['date_from']);
			$period_to_info = $this->model_common_payroll->getPeriodByDate($exchange_info['date_to']);

			if ($this->user->hasPermission('bypass', 'presence/exchange')) {
				if ($period_from_info && $this->model_common_payroll->checkPeriodStatus($period_from_info['presence_period_id'], 'approved, released, completed')) {//Check period status
					$this->error['warning'] = $this->language->get('error_status');
					
					break;
				}
				
				if ($period_to_info && $this->model_common_payroll->checkPeriodStatus($period_to_info['presence_period_id'], 'approved, released, completed')) {//Check period status
					$this->error['warning'] = $this->language->get('error_status');
					
					break;
				}
			} else {
				if ($period_from_info && $this->model_common_payroll->checkPeriodStatus($period_from_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {//Check period status
					$this->error['warning'] = $this->language->get('error_status');
					
					break;
				}
				
				if ($period_to_info && $this->model_common_payroll->checkPeriodStatus($period_to_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {//Check period status
					$this->error['warning'] = $this->language->get('error_status');
					
					break;
				}
				
				if (strtotime($exchange_info['date_from']) < strtotime('today') || strtotime($exchange_info['date_to']) < strtotime('today')) {
					$this->error['warning'] = $this->language->get('error_exchange_status');
					
					break;
				}
			}
		}

		return !$this->error;
	}

	public function scheduleTypesByLocationGroup() {
		$json = array();

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->load->model('common/payroll');
		$customer_info = $this->model_common_payroll->getCustomer($customer_id);
		
		if ($customer_info && $customer_info['location_id']) {
			$this->load->model('presence/schedule_type');

			$json = $this->model_presence_schedule_type->getScheduleTypesByLocationGroup($customer_info['location_id'], $customer_info['customer_group_id']);
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}	

}
