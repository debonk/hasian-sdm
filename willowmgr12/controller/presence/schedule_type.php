<?php
class ControllerPresenceScheduleType extends Controller
{
	private $error = array();
	private $filter_items = array(
		'name',
		'customer_group_id',
		// 'customer_department_id',
		'location_id',
		'status',
		'code_id',
		'code'
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
		$this->load->language('presence/schedule_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/schedule_type');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('presence/schedule_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/schedule_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_schedule_type->addScheduleType($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('presence/schedule_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/schedule_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_schedule_type->editScheduleType($this->request->get['schedule_type_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function copy()
	{
		$this->load->language('presence/schedule_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/schedule_type');

		if (isset($this->request->post['selected']) && $this->validateCopy()) {
			foreach ($this->request->post['selected'] as $schedule_type_id) {
				$this->model_presence_schedule_type->copyScheduleType($schedule_type_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function delete()
	{
		$this->load->language('presence/schedule_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/schedule_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $schedule_type_id) {
				$this->model_presence_schedule_type->deleteScheduleType($schedule_type_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_enabled',
			'text_disabled',
			'text_all',
			'text_yes',
			'text_no',
			'entry_name',
			'entry_customer_group',
			'entry_location',
			'entry_status',
			'entry_code',
			'entry_code_id',
			'column_name',
			'column_code',
			'column_code_id',
			'column_location',
			'column_time_start',
			'column_time_end',
			'column_sort_order',
			'column_status',
			'column_current_use',
			'column_action',
			'button_add',
			'button_copy',
			'button_filter',
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
			$order = 'ASC';
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
			'href' => $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('presence/schedule_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['copy'] = $this->url->link('presence/schedule_type/copy', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('presence/schedule_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$limit = $this->config->get('config_limit_admin');

		$data['schedule_types'] = array();

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_presence_schedule_type->getScheduleTypes($filter_data);

		$locations_data = array();

		$this->load->model('localisation/location');
		$locations = $this->model_localisation_location->getLocations();

		foreach ($locations as $location) {
			$locations_data[$location['location_id']] = $location['name'];
		}

		$this->load->model('presence/schedule');
		$this->load->model('common/payroll');
		$period_info = $this->model_common_payroll->getPeriod(); //get current presence_period_id

		$range_date = array(
			'start'	=> $period_info['date_start'],
			'end'	=> $period_info['date_end']
		);

		foreach ($results as $result) {
			if ($result['location_ids']) {
				$result_locations = array_intersect_key($locations_data, array_flip(json_decode($result['location_ids'])));
			} else {
				$result_locations = array();
			}

			if ($period_info) {
				$period_schedule_type_count = $this->model_presence_schedule->getFinalSchedulesCountByScheduleTypeId($result['schedule_type_id'], $range_date);

				// $period_schedule_type_count = $this->model_presence_schedule->getSchedulesCountByScheduleTypeId($result['schedule_type_id'], $period_info['presence_period_id']);
			}

			$data['schedule_types'][] = array(
				'schedule_type_id' => $result['schedule_type_id'],
				'name'            => $result['name'],
				'code_id'         => $result['code_id'],
				'code'            => $result['code'],
				'location'        => implode('<br />', $result_locations),
				'time_start'      => date($this->language->get('time_format'), strtotime($result['time_start'])),
				'time_end'        => date($this->language->get('time_format'), strtotime($result['time_end'])),
				'bg_idx'      	  => $result['bg_idx'],
				'sort_order'      => $result['sort_order'],
				'status'      	  => $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'current_use'     => $period_schedule_type_count ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'edit'            => $this->url->link('presence/schedule_type/edit', 'token=' . $this->session->data['token'] . '&schedule_type_id=' . $result['schedule_type_id'] . $url, true)
			);
		}

		$schedule_type_count = $this->model_presence_schedule_type->getScheduleTypesCount($filter_data);

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

		$data['sort_name'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_code'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . '&sort=code' . $url, true);
		$data['sort_code_id'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . '&sort=code_id' . $url, true);
		$data['sort_time_start'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . '&sort=time_start' . $url, true);
		$data['sort_time_end'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . '&sort=time_end' . $url, true);
		$data['sort_sort_order'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);
		$data['sort_status'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $schedule_type_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($schedule_type_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($schedule_type_count - $limit)) ? $schedule_type_count : ((($page - 1) * $limit) + $limit), $schedule_type_count, ceil($schedule_type_count / $limit));

		$data['token'] = $this->session->data['token'];

		$data['filter_items'] = json_encode($this->filter_items);
		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/schedule_type_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['schedule_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_select_all',
			'text_enabled',
			'text_disabled',
			'text_unselect_all',
			'entry_name',
			'entry_code',
			'entry_code_id',
			'entry_location',
			'entry_customer_group',
			'entry_time_start',
			'entry_time_end',
			'entry_bg',
			'entry_sort_order',
			'entry_status',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$errors = array(
			'warning',
			'name',
			'code',
			'code_id',
			'locations',
			'customer_groups',
			'time_start',
			'time_end'
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
			'href' => $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['schedule_type_id'])) {
			$data['action'] = $this->url->link('presence/schedule_type/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('presence/schedule_type/edit', 'token=' . $this->session->data['token'] . '&schedule_type_id=' . $this->request->get['schedule_type_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'] . $url, true);

		$data['information'] = '';

		if (isset($this->request->get['schedule_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$schedule_type_info = $this->model_presence_schedule_type->getScheduleType($this->request->get['schedule_type_id']);

			$this->load->model('presence/schedule');

			$schedule_count = $this->model_presence_schedule->getSchedulesCountByScheduleTypeId($this->request->get['schedule_type_id']);

			if ($schedule_count) {
				$data['information'] = $this->language->get('info_schedule');
			}
		}

		$schedule_type_items = array(
			'name',
			'code',
			'code_id',
			'bg_idx',
			'sort_order',
			'status'
		);
		foreach ($schedule_type_items as $item) {
			if (isset($this->request->post[$item])) {
				$data[$item] = $this->request->post[$item];
			} elseif (!empty($schedule_type_info)) {
				$data[$item] = $schedule_type_info[$item];
			} else {
				$data[$item] = '';
			}
		}

		if (isset($this->request->post['time_start'])) {
			$data['time_start'] = $this->request->post['time_start'];
		} elseif (!empty($schedule_type_info)) {
			$data['time_start'] = date($this->language->get('time_format'), strtotime($schedule_type_info['time_start']));
		} else {
			$data['time_start'] = '';
		}

		if (isset($this->request->post['time_end'])) {
			$data['time_end'] = $this->request->post['time_end'];
		} elseif (!empty($schedule_type_info)) {
			$data['time_end'] = date($this->language->get('time_format'), strtotime($schedule_type_info['time_end']));
		} else {
			$data['time_end'] = '';
		}

		if (isset($this->request->post['location_ids'])) {
			$data['location_ids'] = $this->request->post['location_ids'];
		} elseif (!empty($schedule_type_info['location_ids'])) {
			$data['location_ids'] = json_decode($schedule_type_info['location_ids'], true);
		} else {
			$data['location_ids'] = array();
		}

		if (isset($this->request->post['customer_group_ids'])) {
			$data['customer_group_ids'] = $this->request->post['customer_group_ids'];
		} elseif (!empty($schedule_type_info['customer_group_ids'])) {
			$data['customer_group_ids'] = json_decode($schedule_type_info['customer_group_ids'], true);
		} else {
			$data['customer_group_ids'] = array();
		}

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();
		
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/schedule_type_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'presence/schedule_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen(trim($this->request->post['name'])) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['code']) < 1) || (utf8_strlen(trim($this->request->post['code'])) > 6)) {
			$this->error['code'] = $this->language->get('error_code');
		}

		if ((utf8_strlen($this->request->post['code_id']) < 1) || (utf8_strlen(trim($this->request->post['code_id'])) > 6)) {
			$this->error['code_id'] = $this->language->get('error_code_id');
		} else {
			$schedule_type_info = $this->model_presence_schedule_type->getScheduleTypeByCodeId($this->request->post['code_id']);

			if (!isset($this->request->get['schedule_type_id'])) {
				if ($schedule_type_info) {
					$this->error['code_id'] = $this->language->get('error_exists');
				}
			} else {
				if ($schedule_type_info && ($this->request->get['schedule_type_id'] != $schedule_type_info['schedule_type_id'])) {
					$this->error['code_id'] = $this->language->get('error_exists');
				}
			}
		}

		if (!isset($this->request->post['location_ids'])) {
			$this->error['locations'] = $this->language->get('error_locations');
		}

		if (!isset($this->request->post['customer_group_ids'])) {
			$this->error['customer_groups'] = $this->language->get('error_customer_groups');
		}

		if (empty($this->request->post['time_start'])) {
			$this->error['time_start'] = $this->language->get('error_time_start');
		}

		if (empty($this->request->post['time_end'])) {
			$this->error['time_end'] = $this->language->get('error_time_end');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateCopy()
	{
		if (!$this->user->hasPermission('modify', 'presence/schedule_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'presence/schedule_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} else {
			$this->load->model('presence/schedule');

			foreach ($this->request->post['selected'] as $schedule_type_id) {
				$schedules_count = $this->model_presence_schedule->getSchedulesCountByScheduleTypeId($schedule_type_id);

				if ($schedules_count) {
					$this->error['warning'] = $this->language->get('error_schedules');

					break;
				}
			}
		}

		return !$this->error;
	}
}
