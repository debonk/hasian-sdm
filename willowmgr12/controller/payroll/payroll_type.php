<?php
class ControllerPayrollPayrollType extends Controller
{
	private $error = array();
	private $filter_items = array();

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

	public function index()
	{
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_payroll_payroll_type->addPayrollType($this->request->post);
			});


			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$this->model_payroll_payroll_type->editPayrollType($this->request->get['payroll_type_id'], $this->request->post);
			});


			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			$this->db->transaction(function () {
				foreach ($this->request->post['selected'] as $payroll_type_id) {
					$this->model_payroll_payroll_type->deletePayrollType($payroll_type_id);
				}
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true));
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
			'column_description',
			'column_name',
			'column_username',
			'column_date_modified',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
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

		$url = $this->urlFilter();

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('payroll/payroll_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('payroll/payroll_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$limit = $this->config->get('config_limit_admin');

		$data['payroll_types'] = array();

		$filter_data = array(
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_payroll_payroll_type->getPayrollTypes($filter_data);

		foreach ($results as $result) {
			$data['payroll_types'][] = array(
				'payroll_type_id' 	=> $result['payroll_type_id'],
				'name' 				=> $result['name'],
				'description' 		=> $result['description'],
				'username'    		=> $result['username'],
				'date_modified' 	=> date($this->language->get('date_format_jMY'), strtotime($result['date_modified'])),
				'edit'          	=> $this->url->link('payroll/payroll_type/edit', 'token=' . $this->session->data['token'] . '&payroll_type_id=' . $result['payroll_type_id'] . $url, true)
			);
		}

		$payroll_type_count = $this->model_payroll_payroll_type->getPayrollTypesCount($filter_data);


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

		$data['sort_name'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . '&sort=pt.name' . $url, true);
		$data['sort_description'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . '&sort=pt.description' . $url, true);
		$data['sort_username'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . '&sort=u.username' . $url, true);
		$data['sort_date_modified'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . '&sort=pt.date_modified' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $payroll_type_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_type_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($payroll_type_count - $limit)) ? $payroll_type_count : ((($page - 1) * $limit) + $limit), $payroll_type_count, ceil($payroll_type_count / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_type_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = !isset($this->request->get['payroll_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'text_note_deduction',
			'entry_action',
			'entry_name',
			'entry_type',
			'entry_description',
			'entry_variable',
			'entry_sort_order',
			'entry_title',
			'button_save',
			'button_cancel',
			'button_payroll_type_add',
			'button_remove'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$errors = array(
			'warning',
			'name',
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
			'href' => $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		$payroll_type_id = isset($this->request->get['payroll_type_id']) ? $this->request->get['payroll_type_id'] : 0;

		if (!$payroll_type_id) {
			$data['action'] = $this->url->link('payroll/payroll_type/add', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('payroll/payroll_type/edit', 'token=' . $this->session->data['token'] . '&payroll_type_id=' . $payroll_type_id, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true);

		if ($payroll_type_id) {
			$payroll_type_info = $this->model_payroll_payroll_type->getPayrollType($payroll_type_id);
		}

		$field_items = array(
			'name',
			'description'
		);
		foreach ($field_items as $field) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} elseif (!empty($payroll_type_info)) {
				$data[$field] = $payroll_type_info[$field];
			} else {
				$data[$field] = null;
			}
		}

		$data['payroll_type_components'] = [
			'addition'		=> [],
			'deduction'		=> []
		];

		if (isset($this->request->post['payroll_type_component'])) {
			$data['payroll_type_components'] = array_merge($data['payroll_type_components'], $this->request->post['payroll_type_component']);
		} elseif ($payroll_type_id) {
			$data['payroll_type_components'] = $this->model_payroll_payroll_type->getPayrollTypeComponents($payroll_type_id);
		}

		$data['main_components'] = $this->model_payroll_payroll_type->getMainComponentsDescription();

		$data['direction_title'] = [
			'addition'		=> $this->language->get('text_addition'),
			'deduction'		=> $this->language->get('text_deduction')
		];

		$component_row = [
			'addition'		=> count($data['payroll_type_components']['addition']),
			'deduction'		=> count($data['payroll_type_components']['deduction'])
		];

		// Text User Modify
		if (!empty($absence_info)) {
			$data['text_modified'] = sprintf($this->language->get('text_modified'), $payroll_type_info['username'], date($this->language->get('datetime_format_jMY'), strtotime($payroll_type_info['date_modified'])));
		} else {
			$data['text_modified'] = sprintf($this->language->get('text_created'), $this->user->getUserName(), date($this->language->get('datetime_format_jMY')));
		}

		$this->load->model('localisation/presence_status');
		$presence_statuses = $this->model_localisation_presence_status->getPresenceStatusesData();

		$note = array_merge(['hke'], $presence_statuses['primary'], $presence_statuses['additional'], $presence_statuses['secondary']);

		$note = array_map(function ($e) {
			return '{' . $e . '}';
		}, $note);

		$data['note'] = sprintf($this->language->get('text_note'), implode(', ', $note));

		$data['component_row'] = json_encode($component_row);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_type_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'payroll/payroll_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 5) || (utf8_strlen(trim($this->request->post['name'])) > 100)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (utf8_strlen(trim($this->request->post['description'])) > 255) {
			$this->error['description'] = $this->language->get('error_description');
		}

		$payroll_type_component = [
			'addition'	=> isset($this->request->post['payroll_type_component']['addition']) ? $this->request->post['payroll_type_component']['addition'] : [],
			'deduction'	=> isset($this->request->post['payroll_type_component']['deduction']) ? $this->request->post['payroll_type_component']['deduction'] : []
		];

		# Jumlah komponen masing2 maksimal 5.
		if (count($payroll_type_component['addition']) > 5) {
			$this->error['warning'] = $this->language->get('error_component_count');
		}

		if (in_array('pot_prop_all', array_column($payroll_type_component['deduction'], 'code'))) {
			$component_limit = 6;
		} else {
			$component_limit = 5;
		}

		if (count($payroll_type_component['deduction']) > $component_limit) {
			$this->error['warning'] = $this->language->get('error_component_count');
		}

		$post_data = array_merge($payroll_type_component['addition'], $payroll_type_component['deduction']);

		foreach ($post_data as $post) {
			if (!$post['title'] || !$post['code'] || !$post['variable']) {
				$this->error['warning'] = $this->language->get('error_component');

				break;
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'payroll/payroll_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/customer');

		foreach ($this->request->post['selected'] as $payroll_type_id) {
			$customer_count = $this->model_customer_customer->getTotalCustomersByPayrollTypeId($payroll_type_id);

			if ($customer_count) {
				$this->error['warning'] = sprintf($this->language->get('error_customer'), $customer_count);
			}
		}

		return !$this->error;
	}
}
