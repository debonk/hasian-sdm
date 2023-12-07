<?php
class ControllerPresenceAbsence extends Controller
{
	private $error = array();
	private $filter_items = array(
		'name',
		'customer_group_id',
		'customer_department_id',
		'location_id',
		'presence_status_id',
		'date',
		'period',
		'note',
		'approved'
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
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_absence->addAbsence($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_absence->editAbsence($this->request->get['absence_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('presence/absence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/absence');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $absence_id) {
				$this->model_presence_absence->deleteAbsence($absence_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$this->db->createView('v_absence');

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_loading',
			'text_all',
			'text_with_note',
			'text_without_note',
			'text_approved',
			'text_not_approved',
			'entry_name',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'entry_presence_status',
			'entry_date',
			'entry_period',
			'entry_note',
			'entry_approved',
			'column_date',
			'column_name',
			'column_customer_group',
			'column_customer_department',
			'column_location',
			'column_presence_status',
			'column_description',
			'column_note',
			'column_approved',
			'column_username',
			'column_action',
			'button_filter',
			'button_add',
			'button_view',
			'button_edit',
			'button_delete',
			'button_approve'
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
			'href' => $this->url->link('presence/absence', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('presence/absence/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('presence/absence/delete', 'token=' . $this->session->data['token'] . $url, true);

		$limit = $this->config->get('config_limit_admin');

		$data['absences'] = array();

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_presence_absence->getAbsences($filter_data);

		foreach ($results as $result) {
			$data['absences'][] = array(
				'absence_id' 			=> $result['absence_id'],
				'date' 					=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'presence_status' 		=> $result['presence_status'],
				'description' 			=> strlen($result['description']) > 30 ? substr($result['description'], 0, 28) . '..' : $result['description'],
				'note' 					=> strlen($result['note']) > 30 ? substr($result['note'], 0, 28) . '..' : $result['note'],
				'approved'    			=> $result['approved'],
				'username'    			=> $result['username'],
				'edit'          		=> $this->url->link('presence/absence/edit', 'token=' . $this->session->data['token'] . '&absence_id=' . $result['absence_id'] . $url, true),
			);
		}

		$absences_count = $this->model_presence_absence->getAbsencesCount($filter_data);

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

		$data['sort_date'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=date' . $url, true);
		$data['sort_name'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_customer_department'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_presence_status'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . '&sort=presence_status_id' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $absences_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($absences_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($absences_count - $limit)) ? $absences_count : ((($page - 1) * $limit) + $limit), $absences_count, ceil($absences_count / $limit));

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

		$this->load->model('localisation/presence_status');
		$data['presence_statuses'] = $this->model_localisation_presence_status->getPresenceStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/absence_list', $data));
	}

	protected function getForm()
	{
		$this->db->createView('v_absence');

		$data['text_form'] = !isset($this->request->get['absence_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_loading',
			'entry_name',
			'entry_date',
			'entry_presence_status',
			'entry_description',
			'entry_note',
			'button_save',
			'button_cancel',
			'button_add_note',
			'button_ask_approval'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$errors = array(
			'warning',
			'date',
			'ask_approval',
			'presence_status',
			'description'
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
			'href' => $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['absence_id'])) {
			$data['action'] = $this->url->link('presence/absence/add', 'token=' . $this->session->data['token'] . $url, true);
			$data['disabled'] = '';
			$data['absence_id'] = 0;
		} else {
			$data['action'] = $this->url->link('presence/absence/edit', 'token=' . $this->session->data['token'] . '&absence_id=' . $this->request->get['absence_id'] . $url, true);
			$data['disabled'] = 'disabled';
			$data['absence_id'] = $this->request->get['absence_id'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['absence_id'])) {
			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);
		}

		$field_items = array(
			'customer_id'			=> 0,
			'name'					=> '',
			'date'					=> '',
			'approved'				=> 0,
			'presence_status_id'	=> 0,
			'description'			=> '',
			'note'					=> ''
		);
		foreach ($field_items as $field => $value) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} elseif (!empty($absence_info)) {
				if ($field == 'date') {
					$data['date'] = date($this->language->get('date_format_jMY'), strtotime($absence_info['date']));
				} else {
					$data[$field] = $absence_info[$field];
				}
			} else {
				$data[$field] = $value;
			}
		}

		//Text User Modify
		if (!empty($absence_info)) {
			$data['text_modified'] = sprintf($this->language->get('text_modified'), $absence_info['username'], date($this->language->get('datetime_format_jMY'), strtotime($absence_info['date_modified'])));
		} else {
			$data['text_modified'] = sprintf($this->language->get('text_created'), $this->user->getUserName(), date($this->language->get('datetime_format_jMY')));
		}

		$data['config_presence_status'] = $this->config->get('payroll_setting_presence_status_ids');

		$this->load->model('localisation/presence_status');
		$data['presence_statuses'] = $this->model_localisation_presence_status->getPresenceStatuses();

		$data['url'] = $url;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/absence_form', $data));
	}

	protected function validateForm()
	{
		$this->load->model('common/payroll');

		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['absence_id']) && empty($this->request->post['customer_id'])) {
			$this->error['warning'] = $this->language->get('error_customer_id');
		}

		if (isset($this->request->get['absence_id'])) {
			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);

			$customer_id = $absence_info['customer_id'];
			$date = $absence_info['date'];
		} else {
			$customer_id = $this->request->post['customer_id'];
			$date = '';
		}

		if ((utf8_strlen($this->request->post['description']) < 3) || (utf8_strlen(trim($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (empty($this->request->post['presence_status_id'])) { //Check tidak ada presence status
			$this->error['presence_status'] = $this->language->get('error_presence_status');
		} elseif ($this->request->post['presence_status_id'] == $this->config->get('payroll_setting_id_c') && !$this->model_presence_absence->checkVacationLimit($customer_id, $this->request->post['date'])) { //Check batas cuti
			$this->error['presence_status'] = $this->language->get('error_vacation_limit');
		}

		if (empty($this->request->post['date'])) { //Check date kosong
			$this->error['date'] = $this->language->get('error_date_empty');
		}

		if (!$this->error) {
			$period_info = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime($this->request->post['date'])));

			if ($this->user->hasPermission('bypass', 'presence/absence')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
				}
			} else {
				if ($this->user->getCustomerDepartmentId()) {
					$customer_info = $this->model_common_payroll->getCustomer($customer_id);
		
					if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
						$this->error['warning'] = $this->language->get('error_customer_department');
					}
				}
		
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
				}

				$applied_schedule_info = $this->model_common_payroll->getAppliedSchedule($customer_id, $this->request->post['date']);

				$ask_approval = 0;

				if ($this->request->post['date']) {
					if (!$applied_schedule_info) { //Check schedule kosong
						$this->error['date'] = $this->language->get('error_schedule_empty');
					} elseif ($applied_schedule_info['applied'] == 'absence' && strtotime($date) != strtotime($this->request->post['date'])) { //Check date used
						$this->error['date'] = $this->language->get('error_date_used');
					} else {
						$this->load->model('localisation/presence_status');

						$presence_status_info = $this->model_localisation_presence_status->getPresenceStatus($this->request->post['presence_status_id']);
						$date_last_notif = date($this->language->get('date_format_jMY'), strtotime(-$presence_status_info['last_notif'] . ' days'));

						if (strtotime($this->request->post['date']) < strtotime($date_last_notif)) { //Check proteksi date
							$this->error['date'] = sprintf($this->language->get('error_date'), $presence_status_info['name'], $date_last_notif);

							if (!isset($this->request->get['absence_id'])) { //Check untuk minta persetujuan
								$ask_approval = 1;
							}
						}
					}
				}

				if (count($this->error) == 1 && $ask_approval) {
					$this->error['ask_approval'] = 1;
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
		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('common/payroll');

		foreach ($this->request->post['selected'] as $absence_id) {
			$absence_info = $this->model_presence_absence->getAbsence($absence_id);

			$period_info = $this->model_common_payroll->getPeriodByDate($absence_info['date']);

			if ($this->user->hasPermission('bypass', 'presence/absence')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');

					break;
				}
			} else {
				if ($this->user->getCustomerDepartmentId()) {
					$customer_info = $this->model_common_payroll->getCustomer($absence_info['customer_id']);

					if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
						$this->error['warning'] = $this->language->get('error_customer_department');

						break;
					}
				}

				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {
					$this->error['warning'] = $this->language->get('error_status');

					break;
				}

				$date_last_notif = date($this->language->get('date_format_jMY'), strtotime('-' . $absence_info['last_notif'] . ' days'));

				if (strtotime($absence_info['date']) < strtotime($date_last_notif)) {
					$this->error['warning'] = $this->language->get('error_absence_status');

					break;
				}
			}
		}

		return !$this->error;
	}

	public function note()
	{
		$this->load->language('presence/absence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('presence/absence');

			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);

			if (empty($absence_info)) {
				$json['error'] = $this->language->get('error_not_found');
			} else {
				$this->model_presence_absence->editNote($this->request->get['absence_id'], $this->request->post['note']);

				$this->session->data['success'] = $this->language->get('text_success_note');
				$json['success'] = 1;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function askApproval()
	{
		$this->load->language('presence/absence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'presence/absence')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('presence/absence');

			$absence_count = $this->model_presence_absence->getAbsencesCountByCustomerDate($this->request->post['customer_id'], $this->request->post['date']);
			
			if ($absence_count) {
				$json['error'] = $this->language->get('error_absence_exist');
			} else {
				$this->model_presence_absence->addUnapprovedAbsence($this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');
				$json['success'] = 1;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function approval()
	{
		$this->load->language('presence/absence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'common/absence_info')) { //Common_Absence_info user yg bisa approve
			$json['error'] = $this->language->get('error_permission');
		} else {
			$this->load->model('presence/absence');
			$absence_info = $this->model_presence_absence->getAbsence($this->request->get['absence_id']);

			$this->load->model('common/payroll');
			$period_info = $this->model_common_payroll->getPeriodByDate($absence_info['date']);
			$period_status_check = $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed');

			if (!$absence_info) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif ($period_status_check) { //Check period status
				$json['error'] = $this->language->get('error_status');
			} elseif ($absence_info['presence_status_id'] == $this->config->get('payroll_setting_id_c') && !$this->model_presence_absence->checkVacationLimit($absence_info['customer_id'], $absence_info['date'])) { //Check batas cuti
				$json['error'] = $this->language->get('error_vacation_limit');
			}
		}

		if (!$json) {
			$this->load->model('presence/presence');

			$presence_status_ia = $this->config->get('payroll_setting_id_ia');

			$presence_summary_info = $this->model_presence_presence->getPresenceSummary($period_info['presence_period_id'], $absence_info['customer_id']);

			$presence_summary_data = array();

			if ($absence_info['approved']) {
				$this->model_presence_absence->unapproveAbsence($this->request->get['absence_id']);

				//Edit presence dan presence summary
				if ($presence_summary_info) {
					$this->model_presence_presence->editPresence($absence_info['customer_id'], $absence_info['date'], $presence_status_ia);

					$presence_summary_data['total_ia'] = $presence_summary_info['total_ia'] + 1;

					if ($absence_info['presence_code']) {
						$presence_summary_data['total_' . $absence_info['presence_code']] = $presence_summary_info['total_' . $absence_info['presence_code']] - 1;
					}
				}
			} else {
				$this->model_presence_absence->approveAbsence($this->request->get['absence_id']);

				if ($presence_summary_info) {
					$this->model_presence_presence->editPresence($absence_info['customer_id'], $absence_info['date'], $absence_info['presence_status_id']);

					$presence_summary_data['total_ia'] = $presence_summary_info['total_ia'] - 1;

					if ($absence_info['presence_code']) {
						$presence_summary_data['total_' . $absence_info['presence_code']] = $presence_summary_info['total_' . $absence_info['presence_code']] + 1;
					}
				}
			}

			$this->model_presence_presence->editPresenceSummary($period_info['presence_period_id'], $absence_info['customer_id'], $presence_summary_data);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete()
	{
		$this->load->language('presence/absence');

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
