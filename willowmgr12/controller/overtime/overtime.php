<?php
class ControllerOvertimeOvertime extends Controller
{
	private $error = array();

	private $filter_items = array(
		'name',
		'customer_group_id',
		'customer_department_id',
		'location_id',
		'description',
		'status',
		'period',
		'overtime_type_id'
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
		$this->load->language('overtime/overtime');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('overtime/overtime');
		// $this->load->model('common/payroll');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('overtime/overtime');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('overtime/overtime');
		// $this->load->model('common/payroll');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_overtime_overtime->addOvertime($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

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

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('overtime/overtime');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('overtime/overtime');
		// $this->load->model('common/payroll');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $overtime_id) {
				$this->model_overtime_overtime->deleteOvertime($overtime_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$this->db->createView('v_overtime');

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_paid',
			'text_unpaid',
			'text_all',
			'text_confirm',
			'entry_name',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'entry_period',
			'entry_description',
			'entry_status',
			'entry_overtime_type',
			'column_date',
			'column_name',
			'column_customer_group',
			'column_customer_department',
			'column_location',
			'column_description',
			'column_overtime_type',
			'column_wage',
			'column_username',
			'column_period',
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

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('overtime/overtime/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('overtime/overtime/delete', 'token=' . $this->session->data['token'] . $url, true);

		$limit = $this->config->get('config_limit_admin');

		$data['overtimes'] = array();

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_overtime_overtime->getOvertimes($filter_data);

		foreach ($results as $result) {
			if ($result['presence_period_id']) {
				$period = date($this->language->get('date_format_m_y'), strtotime($result['period']));
			} else {
				$period = '';
			}

			// $payment_status = $this->model_overtime_overtime->getOvertimePaidStatus($result['overtime_id']);

			$data['overtimes'][] = array(
				'overtime_id' 			=> $result['overtime_id'],
				'date' 					=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'description' 			=> $result['description'],
				'overtime_type' 		=> $result['overtime_type'],
				'wage' 					=> $this->currency->format($result['wage'], $this->config->get('config_currency')),
				'username'    			=> $result['username'],
				'period'    			=> $period,
				// 'payment_status' 	   => $payment_status,
				'view'          		=> $this->url->link('payroll/payroll/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'edit'          		=> $this->url->link('overtime/overtime/edit', 'token=' . $this->session->data['token'] . '&overtime_id=' . $result['overtime_id'] . $url, true),
			);
		}

		$overtime_count = $this->model_overtime_overtime->getOvertimesCount($filter_data);

		$overtime_total = $this->model_overtime_overtime->getOvertimesTotal($filter_data);

		$data['grandtotal'] = sprintf($this->language->get('text_grandtotal'), $this->currency->format($overtime_total, $this->config->get('config_currency')));

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

		$data['sort_date'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=date' . $url, true);
		$data['sort_name'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_customer_department'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_period'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=period' . $url, true);
		$data['sort_overtime_type'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . '&sort=overtime_type' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $overtime_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($overtime_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($overtime_count - $limit)) ? $overtime_count : ((($page - 1) * $limit) + $limit), $overtime_count, ceil($overtime_count / $limit));

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

		$this->load->model('overtime/overtime_type');
		$data['overtime_types'] = $this->model_overtime_overtime_type->getOvertimeTypes();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('overtime/overtime_list', $data));
	}

	protected function getForm()
	{
		$this->db->createView('v_overtime');

		$data['text_form'] = !isset($this->request->get['overtime_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'entry_name',
			'entry_description',
			'entry_date',
			'entry_overtime_type',
			'entry_schedule_type',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$errors = array(
			'warning',
			'date',
			'description',
			'overtime_type',
			'schedule_type'
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

		$field_items = array(
			'customer_id',
			'name',
			'date',
			'description',
			'overtime_type_id',
			'schedule_type_id'
		);
		foreach ($field_items as $item) {
			if (isset($this->request->post[$item])) {
				$data[$item] = $this->request->post[$item];
			} elseif (!empty($overtime_info)) {
				if ($item == 'date') {
					$data['date'] = date($this->language->get('date_format_jMY'), strtotime($overtime_info['date']));
				} else {
					$data[$item] = $overtime_info[$item];
				}
			} else {
				$data[$item] = '';
			}
		}

		//Text User Modify
		if (!empty($overtime_info)) {
			$data['text_modified'] = sprintf($this->language->get('text_modified'), $overtime_info['username'], date($this->language->get('datetime_format_jMY'), strtotime($overtime_info['date_modified'])));
		} else {
			$data['text_modified'] = sprintf($this->language->get('text_created'), $this->user->getUserName(), date($this->language->get('datetime_format_jMY')));
		}

		$this->load->model('overtime/overtime_type');
		$data['overtime_types'] = $this->model_overtime_overtime_type->getOvertimeTypes();


		if (isset($overtime_info) && $overtime_info['location_id'] && $overtime_info['customer_group_id']) {
			$this->load->model('presence/schedule_type');

			$data['schedule_types'] = $this->model_presence_schedule_type->getScheduleTypesByLocationGroup($overtime_info['location_id'], $overtime_info['customer_group_id']);
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

		if (empty($this->request->post['date'])) {
			$this->error['date'] = $this->language->get('error_date');
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

		if (!$this->error) {
			$this->load->model('common/payroll');

			if (isset($this->request->get['overtime_id'])) {
				$overtime_info = $this->model_overtime_overtime->getOvertime($this->request->get['overtime_id']);

				if ($overtime_info['presence_period_id']) {
					if ($this->user->hasPermission('bypass', 'overtime/overtime')) {
						if ($this->model_common_payroll->checkPeriodStatus($overtime_info['presence_period_id'], 'approved, released, completed')) { //Check period status
							$this->error['date'] = $this->language->get('error_status');
						}
					} else {
						if ($this->model_common_payroll->checkPeriodStatus($overtime_info['presence_period_id'], 'generated, approved, released, completed')) { //Check period status
							$this->error['date'] = $this->language->get('error_status_bypass');
						}
					}
				}
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

			if ($overtime_info['presence_period_id']) {
				if ($this->user->hasPermission('bypass', 'overtime/overtime')) {
					if ($this->model_common_payroll->checkPeriodStatus($overtime_info['presence_period_id'], 'approved, released, completed')) {
						$this->error['warning'] = $this->language->get('error_status');

						break;
					}
				} else {
					if ($this->model_common_payroll->checkPeriodStatus($overtime_info['presence_period_id'], 'generated, approved, released, completed')) {
						$this->error['warning'] = $this->language->get('error_status_bypass');

						break;
					}
				}

				if ($this->user->getCustomerDepartmentId() && $this->user->getCustomerDepartmentId() != $overtime_info['customer_department_id']) {
					$this->error['warning'] = $this->language->get('error_customer_department');

					break;
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

	public function autocomplete()
	{
		$this->load->language('overtime/overtime');

		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];

			$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

			$this->load->model('presence/presence');

			$filter_data = array(
				'presence_period_id'	=> $presence_period_id,
				'filter_name'			=> $filter_name,
				'start'      			=> 0,
				'limit'      			=> 15
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
