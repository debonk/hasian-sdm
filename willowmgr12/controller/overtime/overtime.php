<?php
class ControllerOvertimeOvertime extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('overtime/overtime');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('overtime/overtime');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('overtime/overtime');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('overtime/overtime');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_overtime_overtime->addOvertime($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
			}

			if (isset($this->request->get['filter_overtime_type_id'])) {
				$url .= '&filter_overtime_type_id=' . $this->request->get['filter_overtime_type_id'];
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

			$this->response->redirect($this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('overtime/overtime');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('overtime/overtime');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_overtime_overtime->editOvertime($this->request->get['overtime_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
			}

			if (isset($this->request->get['filter_overtime_type_id'])) {
				$url .= '&filter_overtime_type_id=' . $this->request->get['filter_overtime_type_id'];
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

			$this->response->redirect($this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('overtime/overtime');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('overtime/overtime');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $overtime_id) {
				$this->model_overtime_overtime->deleteOvertime($overtime_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_period_id'])) {
				$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
			}

			if (isset($this->request->get['filter_overtime_type_id'])) {
				$url .= '&filter_overtime_type_id=' . $this->request->get['filter_overtime_type_id'];
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

			$this->response->redirect($this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_period_id'])) {
			$filter_period_id = $this->request->get['filter_period_id'];
		} else {
			$filter_period_id = '';
		}

		if (isset($this->request->get['filter_overtime_type_id'])) {
			$filter_overtime_type_id = $this->request->get['filter_overtime_type_id'];
		} else {
			$filter_overtime_type_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'o.date';
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

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_overtime_type_id'])) {
			$url .= '&filter_overtime_type_id=' . $this->request->get['filter_overtime_type_id'];
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
			'href' => $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('overtime/overtime/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('overtime/overtime/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'filter_name'				=> $filter_name,
			'filter_period_id'			=> $filter_period_id,
			'filter_overtime_type_id'	=> $filter_overtime_type_id,
			'filter_status' 			=> $filter_status,
			'sort'  					=> $sort,
			'order' 					=> $order,
			'start'         			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'         			=> $this->config->get('config_limit_admin')
		);

		$data['overtimes'] = array();

		$results = $this->model_overtime_overtime->getOvertimes($filter_data);

		$this->load->model('overtime/overtime_type');

		foreach ($results as $result) {
			if ($result['presence_period_id']) {
				$payment = date($this->language->get('date_format_m_y'), strtotime($result['period']));
			} else {
				$payment = '';
			}

			$payment_status = $this->model_overtime_overtime->getOvertimePaidStatus($result['overtime_id']);

			$data['overtimes'][] = array(
				'overtime_id' 		=> $result['overtime_id'],
				'date' 				=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'name' 				=> $result['name'],
				'overtime_type' 	=> $result['overtime_type'],
				'description' 		=> strlen($result['description']) > 50 ? substr($result['description'], 0, 48) . '..' : $result['description'],
				'wage' 				=> $this->currency->format($result['wage'], $this->config->get('config_currency')),
				'username'    		=> $result['username'],
				'payment'    		=> $payment,
				'payment_status'    => $payment_status,
				'view'          	=> $this->url->link('payroll/payroll/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'edit'          	=> $this->url->link('overtime/overtime/edit', 'token=' . $this->session->data['token'] . '&overtime_id=' . $result['overtime_id'] . $url, true),
			);
		}

		$overtime_count = $this->model_overtime_overtime->getOvertimesCount($filter_data);

		$overtime_total = $this->model_overtime_overtime->getOvertimesTotal($filter_data);

		$data['grandtotal'] = sprintf($this->language->get('text_grandtotal'), $this->currency->format($overtime_total, $this->config->get('config_currency')));

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_paid',
			'text_unpaid',
			'text_all',
			'text_confirm',
			'entry_period',
			'entry_name',
			'entry_overtime_type',
			'entry_status',
			'entry_description',
			'column_date',
			'column_name',
			'column_overtime_type',
			'column_description',
			'column_wage',
			'column_username',
			'column_payment',
			'column_action',
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

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_overtime_type_id'])) {
			$url .= '&filter_overtime_type_id=' . $this->request->get['filter_overtime_type_id'];
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

		$data['sort_date'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=o.date' . $url, true);
		$data['sort_name'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_presence_period'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=pcv.presence_period_id' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_overtime_type_id'])) {
			$url .= '&filter_overtime_type_id=' . $this->request->get['filter_overtime_type_id'];
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
		$pagination->total = $overtime_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($overtime_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($overtime_count - $this->config->get('config_limit_admin'))) ? $overtime_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $overtime_count, ceil($overtime_count / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_period_id'] = $filter_period_id;
		$data['filter_overtime_type_id'] = $filter_overtime_type_id;
		$data['filter_status'] = $filter_status;
		$data['sort'] = $sort;
		$data['order'] = $order;

		// $this->load->model('overtime/overtime_type');
		$data['overtime_types'] = $this->model_overtime_overtime_type->getOvertimeTypes();

		$this->load->model('presence/presence_period');
		$data['periods'] = $this->model_presence_presence_period->getPresencePeriods();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('overtime/overtime_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['overtime_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select_customer',
			'text_select',
			'entry_name',
			'entry_date',
			'entry_overtime_type',
			'entry_description',
			'entry_schedule_type',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$data['customers'] = array();

		$this->load->model('presence/presence');
		$results = $this->model_presence_presence->getCustomers(['filter_customer_department_id' => $this->user->getCustomerDepartmentId()]);

		foreach ($results as $result) {
			$data['customers'][] = array(
				'customer_id' 	=> $result['customer_id'],
				'text' 			=> $result['name'] . ' - ' . $result['customer_group'] . '/' . $result['customer_department'] . ' - ' . $result['location']
			);
		}

		$errors = array(
			'warning',
			'overtime_type',
			'date',
			'description',
			'schedule_type'
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

		if (isset($this->request->get['filter_period_id'])) {
			$url .= '&filter_period_id=' . $this->request->get['filter_period_id'];
		}

		if (isset($this->request->get['filter_overtime_type_id'])) {
			$url .= '&filter_overtime_type_id=' . $this->request->get['filter_overtime_type_id'];
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
			'href' => $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['overtime_id'])) {
			$data['action'] = $this->url->link('overtime/overtime/add', 'token=' . $this->session->data['token'] . $url, true);
			$data['disabled'] = '';
		} else {
			$data['action'] = $this->url->link('overtime/overtime/edit', 'token=' . $this->session->data['token'] . '&overtime_id=' . $this->request->get['overtime_id'] . $url, true);
			$data['disabled'] = 'disabled';
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['overtime_id'])) {
			$overtime_info = $this->model_overtime_overtime->getOvertime($this->request->get['overtime_id']);
		}

		//Text User Modify
		if (!empty($overtime_info)) {
			$username = $overtime_info['username'];
			$date_modified = date($this->language->get('datetime_format_jMY'), strtotime($overtime_info['date_modified']));
		} else {
			$username = $this->user->getUserName();
			$date_modified = date($this->language->get('datetime_format_jMY'));
		}
		$data['text_modified'] = sprintf($this->language->get('text_modified'), $username, $date_modified);

		if (isset($this->request->post['customer_id'])) {
			$data['customer_id'] = $this->request->post['customer_id'];
		} elseif (!empty($overtime_info)) {
			$data['customer_id'] = $overtime_info['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		if (isset($this->request->post['date'])) {
			$data['date'] = $this->request->post['date'];
		} elseif (!empty($overtime_info)) {
			$data['date'] = date($this->language->get('date_format_jMY'), strtotime($overtime_info['date']));
		} else {
			$data['date'] = '';
		}

		if (isset($this->request->post['overtime_type_id'])) {
			$data['overtime_type_id'] = $this->request->post['overtime_type_id'];
		} elseif (!empty($overtime_info)) {
			$data['overtime_type_id'] = $overtime_info['overtime_type_id'];
		} else {
			$data['overtime_type_id'] = 0;
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($overtime_info)) {
			$data['description'] = $overtime_info['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['schedule_type_id'])) {
			$data['schedule_type_id'] = $this->request->post['schedule_type_id'];
		} elseif (!empty($overtime_info)) {
			$data['schedule_type_id'] = $overtime_info['schedule_type_id'];
		} else {
			$data['schedule_type_id'] = 0;
		}

		$this->load->model('overtime/overtime_type');
		$data['overtime_types'] = $this->model_overtime_overtime_type->getOvertimeTypes();

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

		$this->response->setOutput($this->load->view('overtime/overtime_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'overtime/overtime')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['overtime_id']) && empty($this->request->post['customer_id'])) {
			$this->error['warning'] = $this->language->get('error_customer_id');
		}

		if (empty($this->request->post['overtime_type_id'])) {
			$this->error['overtime_type'] = $this->language->get('error_overtime_type');
		}

		if ((utf8_strlen($this->request->post['description']) < 5) || (utf8_strlen(trim($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (empty($this->request->post['schedule_type_id'])) {
			$this->error['schedule_type'] = $this->language->get('error_schedule_type');
		}

		if (empty($this->request->post['date'])) {
			$this->error['date'] = $this->language->get('error_date');
		}

		if (!$this->error) {
			$period_info = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime($this->request->post['date'])));

			if ($this->user->hasPermission('bypass', 'overtime/overtime')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
				}
			} else {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
				}

				if (isset($this->request->get['overtime_id'])) {
					$overtime_info = $this->model_overtime_overtime->getOvertime($this->request->get['overtime_id']);

					$customer_id = $overtime_info['customer_id'];
					$date_check = $overtime_info['date'];
				} else {
					$customer_id = $this->request->post['customer_id'];
					$date_check = '';
				}

				if ($this->user->getCustomerDepartmentId()) {
					$customer_info = $this->model_common_payroll->getCustomer($customer_id);

					if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
						$this->error['warning'] = $this->language->get('error_customer_department');
					}
				}

				$schedule_info = $this->model_common_payroll->getAppliedSchedule($customer_id, $this->request->post['date']);

				if ($schedule_info && ($schedule_info['applied'] == 'absence' || ($schedule_info['applied'] == 'overtime' && strtotime($date_check) != strtotime($this->request->post['date'])))) { //Check date used
					$this->error['date'] = $this->language->get('error_date_used');
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'overtime/overtime')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('common/payroll');

		foreach ($this->request->post['selected'] as $overtime_id) {
			$overtime_info = $this->model_overtime_overtime->getOvertime($overtime_id);

			$period_info = $this->model_common_payroll->getPeriodByDate($overtime_info['date']);

			if ($this->user->hasPermission('bypass', 'overtime/overtime')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');

					break;
				}
			} else {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');

					break;
				}

				if ($this->user->getCustomerDepartmentId()) {
					$customer_info = $this->model_common_payroll->getCustomer($overtime_info['customer_id']);

					if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
						$this->error['warning'] = $this->language->get('error_customer_department');

						break;
					}
				}
			}
		}

		return !$this->error;
	}

	public function scheduleTypesByLocationGroup()
	{
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

	public function approval()
	{
		$this->load->language('overtime/overtime');

		$json = array();

		if (!$this->user->hasPermission('modify', 'common/absence_info')) { //Common_Absence_info user yg bisa approve
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('overtime/overtime');
			$overtime_info = $this->model_overtime_overtime->getOvertime($this->request->get['overtime_id']);

			$this->load->model('common/payroll');
			$period_info = $this->model_common_payroll->getPeriodByDate($overtime_info['date']);
			$period_status_check = $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed');

			if (!$overtime_info) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($period_status_check) { //Check period status
				$json['error'] = $this->language->get('error_status');
			} else {
				if ($overtime_info['approved']) {
					$this->model_overtime_overtime->unapproveOvertime($this->request->get['overtime_id']);
				} else {
					$this->model_overtime_overtime->approveOvertime($this->request->get['overtime_id']);
				}

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
