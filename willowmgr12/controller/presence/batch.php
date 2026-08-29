<?php
class ControllerPresenceBatch extends Controller
{
	private $error = array();

	private $filter_items = array(
		'name',
		'date',
		'period',
	);

	//============================================================
	// URL Filter Builder
	//============================================================

	private function urlFilter($excluded_item = null)
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
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

	//============================================================
	// Entry Points
	//============================================================

	public function index()
	{
		$this->load->language('presence/batch');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('presence/batch');
		$this->getList();
	}

	public function add()
	{
		$this->load->language('presence/batch');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('presence/batch');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_presence_batch->addBatch($this->request->post);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/batch', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('presence/batch');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('presence/batch');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_presence_batch->editBatch($this->request->get['batch_id'], $this->request->post);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/batch', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('presence/batch');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('presence/batch');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $batch_id) {
				$this->db->transaction(function () use ($batch_id) {
					$this->model_presence_batch->deleteBatch($batch_id);
				});
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/batch', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	//============================================================
	// List View
	//============================================================

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_all',
			'text_location',
			'text_customer_group',
			'text_customer_department',
			'entry_name',
			'entry_period',
			'entry_date',
			'column_name',
			'column_date',
			'column_description',
			'column_schedule_type',
			'column_presence_status',
			'column_rules',
			'column_username',
			'column_action',
			'button_filter',
			'button_unfilter',
			'button_add',
			'button_edit',
			'button_delete',
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$filter = array();

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		$sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'date';
		$order = isset($this->request->get['order']) ? $this->request->get['order'] : 'DESC';
		$page = isset($this->request->get['page']) ? $this->request->get['page'] : 1;

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/batch', 'token=' . $this->session->data['token'], true),
		);

		$data['add'] = $this->url->link('presence/batch/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('presence/batch/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['unfilter'] = $this->url->link('presence/batch/delete', 'token=' . $this->session->data['token'], true);

		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'filter'  => $filter,
			'sort'    => $sort,
			'order'   => $order,
			'start'   => ($page - 1) * $limit,
			'limit'   => $limit,
		);

		$results = $this->model_presence_batch->getBatches($filter_data);

		$data['batches'] = array();

		foreach ($results as $result) {
			$rules = $this->model_presence_batch->getBatchRulesWithMeta($result['batch_id']);

			$rules_summary = [];
			foreach ($rules as $rule => $ids) {
				if (!empty($ids)) {
					$rules_summary[] = $data['text_' . $rule] . ': ' . count($ids);
				}
			}

			if (!empty($rules_summary)) {
				$rules_summary = implode(' | ', $rules_summary);
			} else {
				$rules_summary = $data['text_all'];
			}

			$data['batches'][] = array(
				'batch_id'        => $result['batch_id'],
				'name'            => $result['name'],
				'date'            => date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'date_raw'        => $result['date'],
				'presence_status' => $result['presence_status'] ?: '—',
				'schedule_type'   => $result['schedule_type'] ?: '—',
				'description'     => !empty($result['description']) ? $result['description'] : '—',
				'username'        => $result['username'],
				'rules_summary'   => $rules_summary,
				'edit'            => $this->url->link('presence/batch/edit', 'token=' . $this->session->data['token'] . '&batch_id=' . $result['batch_id'] . $url, true),
			);
		}

		$batches_count = $this->model_presence_batch->getBatchesCount($filter_data);

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
		$url .= ($order == 'ASC') ? '&order=DESC' : '&order=ASC';

		$data['sort_name'] = $this->url->link('presence/batch', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_date'] = $this->url->link('presence/batch', 'token=' . $this->session->data['token'] . '&sort=date' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $batches_count;
		$pagination->page  = $page;
		$pagination->limit = $limit;
		$pagination->url   = $this->url->link('presence/batch', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf(
			$this->language->get('text_pagination'),
			($batches_count) ? (($page - 1) * $limit) + 1 : 0,
			((($page - 1) * $limit) > ($batches_count - $limit)) ? $batches_count : ((($page - 1) * $limit) + $limit),
			$batches_count,
			ceil($batches_count / $limit)
		);

		$data['filter_items'] = json_encode($this->filter_items);
		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/batch_list', $data));
	}

	//============================================================
	// Form View
	//============================================================

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['batch_id'])
			? $this->language->get('text_add')
			: $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_basic_info',
			'text_select_all',
			'text_filter_rule',
			'text_no_shift',
			'entry_name',
			'entry_date',
			'entry_presence_status',
			'entry_schedule_type',
			'entry_description',
			'entry_location',
			'entry_customer_group',
			'entry_customer_department',
			'help_name',
			'help_rule',
			'help_schedule_type',
			'button_save',
			'button_cancel',
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$errors = array('warning', 'name', 'date');
		foreach ($errors as $error) {
			$data['error_' . $error] = isset($this->error[$error]) ? $this->error[$error] : '';
		}

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/batch', 'token=' . $this->session->data['token'] . $url, true),
		);

		if (!isset($this->request->get['batch_id'])) {
			$data['action'] = $this->url->link('presence/batch/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('presence/batch/edit', 'token=' . $this->session->data['token'] . '&batch_id=' . $this->request->get['batch_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action'],
		);

		$data['cancel'] = $this->url->link('presence/batch', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['batch_id'])) {
			$batch_info = $this->model_presence_batch->getBatch($this->request->get['batch_id']);
		}

		$rules = $this->model_presence_batch->getBatchRulesWithMeta($this->request->get['batch_id'] ?? 0);

		$field_items = array(
			'name'                => '',
			'date'                => '',
			'presence_status_id'  => 0,
			'schedule_type_id'    => 0,
			'description'         => '',
		);
		foreach ($field_items as $field => $value) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} elseif (!empty($batch_info)) {
				if ($field == 'date') {
					$data['date'] = date($this->language->get('date_format_jMY'), strtotime($batch_info['date']));
				} else {
					$data[$field] = $batch_info[$field];
				}
			} else {
				$data[$field] = $value;
			}
		}

		$data['filter'] = [];

		if (!empty($rules)) {
			foreach ($rules as $rule => $ids) {
				if (isset($this->request->post[$rule])) {
					$data['filter'][$rule] = $this->request->post['filter'][$rule];
				} else {
					$data['filter'][$rule] = $ids;
				}
			}
		}

		$this->load->model('localisation/presence_status');
		$all_statuses = $this->model_localisation_presence_status->getPresenceStatuses();
		$allowed_ids = $this->config->get('payroll_setting_presence_status_ids');
		$data['presence_statuses'] = array_filter($all_statuses, function ($item) use ($allowed_ids) {
			return in_array($item['presence_status_id'], (array)$allowed_ids);
		});

		$this->load->model('presence/schedule_type');
		$schedule_types = $this->model_presence_schedule_type->getScheduleTypes(['filter' => ['status' => 1]]);
		$data['schedule_types'] = [];
		foreach ($schedule_types as $st) {
			$data['schedule_types'][] = array(
				'schedule_type_id' => $st['schedule_type_id'],
				'text'             => $st['code'] . ' (' . date('H:i', strtotime($st['time_start'])) . '-' . date('H:i', strtotime($st['time_end'])) . ')',
			);
		}

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('customer/customer_department');
		$data['customer_departments'] = $this->model_customer_customer_department->getCustomerDepartments();

		$data['header']       = $this->load->controller('common/header');
		$data['column_left']  = $this->load->controller('common/column_left');
		$data['footer']       = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/batch_form', $data));
	}

	//============================================================
	// Validation
	//============================================================

	protected function validateForm()
	{
		do {
			if (!$this->user->hasPermission('modify', 'presence/batch')) {
				$this->error['warning'] = $this->language->get('error_permission');
				break;
			}

			if (empty($this->request->post['name']) || utf8_strlen($this->request->post['name']) < 2) {
				$this->error['name'] = $this->language->get('error_name');
			}

			if (empty($this->request->post['date'])) {
				$this->error['date'] = $this->language->get('error_date');
				break;
			}

			$this->load->model('common/payroll');
			$period_info = $this->model_common_payroll->getPeriodByDate(date('Y-m-d', strtotime($this->request->post['date'])));

			if ($this->user->hasPermission('bypass', 'presence/absence')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
					break;
				}
			} else {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) { //Check period status
					$this->error['date'] = $this->language->get('error_status');
					break;
				}
			}

			$date = date_create_from_format('d M Y', $this->request->post['date']);
			$batch_info = $this->model_presence_batch->getBatchEntryByDate(date_format($date, 'Y-m-d'));

			if ($batch_info && (!isset($this->request->get['batch_id']) || (isset($this->request->get['batch_id']) && $batch_info['batch_id'] != $this->request->get['batch_id']))) {
				$this->error['date'] = $this->language->get('error_batch_exist');
				break;
			}

			if ($this->error && !isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_warning');
				break;
			}

			# User dengan akses customer_department terbatas tidak memiliki akses
			if ($this->user->getCustomerDepartmentId()) {
				$this->error['warning'] = $this->language->get('error_permission');
				break;
			}
		} while (false);

		return !$this->error;
	}

	protected function validateDelete()
	{
		do {
			if (!$this->user->hasPermission('approve', 'presence/batch')) {
				$this->error['warning'] = $this->language->get('error_permission');
				break;
			}

			foreach ($this->request->post['selected'] as $batch_id) {
				$batch_info = $this->model_presence_batch->getBatch($batch_id);

				$this->load->model('common/payroll');
				$period_info = $this->model_common_payroll->getPeriodByDate($batch_info['date']);

				if ($this->user->hasPermission('bypass', 'presence/absence')) {
					if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) {
						$this->error['warning'] = $this->language->get('error_status');
						break;
					}
				} else {
					if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {
						$this->error['warning'] = $this->language->get('error_status');
						break;
					}
				}

				if ($this->user->getCustomerDepartmentId()) {
					$this->error['warning'] = $this->language->get('error_permission');
					break;
				}
			}
		} while (false);

		return !$this->error;
	}
}
