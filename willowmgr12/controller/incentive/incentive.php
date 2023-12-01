<?php
class ControllerIncentiveIncentive extends Controller
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
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_incentive_incentive->addIncentive($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_incentive_incentive->editIncentive($this->request->get['incentive_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('incentive/incentive');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('incentive/incentive');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $incentive_id) {
				$this->model_incentive_incentive->deleteIncentive($incentive_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = $this->urlFilter();

			$this->response->redirect($this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$this->db->createView('v_customer');
		$this->db->createView('v_incentive');

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_subtotal',
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
			'column_date',
			'column_name',
			'column_customer_group',
			'column_customer_department',
			'column_location',
			'column_description',
			'column_amount',
			'column_action',
			'column_username',
			'column_period',
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
			'href' => $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('incentive/incentive/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('incentive/incentive/delete', 'token=' . $this->session->data['token'] . $url, true);

		$limit = $this->config->get('config_limit_admin');

		$data['incentives'] = array();

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$results = $this->model_incentive_incentive->getIncentives($filter_data);

		foreach ($results as $result) {
			if ($result['presence_period_id']) {
				$period = date($this->language->get('date_format_m_y'), strtotime($result['period']));
			} else {
				$period = 0;
			}

			$data['incentives'][] = array(
				'incentive_id' 			=> $result['incentive_id'],
				'date' 					=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'description' 			=> $result['description'],
				'amount'    			=> $this->currency->format($result['amount'], $this->config->get('config_currency')),
				'username'    			=> $result['username'],
				'period'    			=> $period,
				'view'          		=> $this->url->link('payroll/payroll/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'edit'          		=> $this->url->link('incentive/incentive/edit', 'token=' . $this->session->data['token'] . '&incentive_id=' . $result['incentive_id'] . $url, true),
			);
		}

		$incentive_count = $this->model_incentive_incentive->getIncentivesCount($filter_data);

		$results_total = $this->model_incentive_incentive->getIncentivesTotal($filter_data);
		$grandtotal = $this->model_incentive_incentive->getIncentivesTotal();

		$data['subtotal'] = $this->currency->format($results_total, $this->config->get('config_currency'));
		$data['grandtotal'] = sprintf($this->language->get('text_grandtotal'), $this->currency->format($grandtotal, $this->config->get('config_currency')));

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

		$data['sort_date'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . '&sort=date' . $url, true);
		$data['sort_name'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_customer_department'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_period'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . '&sort=period' . $url, true);

		$url = $this->urlFilter('page');

		$pagination = new Pagination();
		$pagination->total = $incentive_count;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($incentive_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($incentive_count - $limit)) ? $incentive_count : ((($page - 1) * $limit) + $limit), $incentive_count, ceil($incentive_count / $limit));

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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('incentive/incentive_list', $data));
	}

	protected function getForm()
	{
		$this->db->createView('v_customer');
		$this->db->createView('v_incentive');

		$data['text_form'] = !isset($this->request->get['incentive_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_name',
			'entry_description',
			'entry_amount',
			'entry_date',
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
			'amount'
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
			'href' => $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['incentive_id'])) {
			$data['action'] = $this->url->link('incentive/incentive/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('incentive/incentive/edit', 'token=' . $this->session->data['token'] . '&incentive_id=' . $this->request->get['incentive_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['incentive_id'])) {
			$incentive_info = $this->model_incentive_incentive->getIncentive($this->request->get['incentive_id']);
		}

		$field_items = array(
			'customer_id',
			'name',
			'date',
			'description',
			'amount'
		);
		foreach ($field_items as $item) {
			if (isset($this->request->post[$item])) {
				$data[$item] = $this->request->post[$item];
			} elseif (!empty($incentive_info)) {
				if ($item == 'date') {
					$data['date'] = date($this->language->get('date_format_jMY'), strtotime($incentive_info['date']));
				} else {
					$data[$item] = $incentive_info[$item];
				}
			} else {
				$data[$item] = '';
			}
		}

		//Text User Modify
		if (!empty($incentive_info)) {
			$data['text_modified'] = sprintf($this->language->get('text_modified'), $incentive_info['username'], date($this->language->get('datetime_format_jMY'), strtotime($incentive_info['date_modified'])));
		} else {
			$data['text_modified'] = sprintf($this->language->get('text_created'), $this->user->getUserName(), date($this->language->get('datetime_format_jMY')));
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('incentive/incentive_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'incentive/incentive')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!isset($this->request->get['incentive_id']) && empty($this->request->post['customer_id'])) {
			$this->error['warning'] = $this->language->get('error_customer_id');
		}

		if (empty($this->request->post['date'])) {
			$this->error['date'] = $this->language->get('error_date');
		}

		if ($this->request->post['amount'] <= 0) {
			$this->error['amount'] = $this->language->get('error_amount');
		}

		if ((utf8_strlen($this->request->post['description']) < 5) || (utf8_strlen(trim($this->request->post['description'])) > 255)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (!$this->error) {
			$this->load->model('common/payroll');

			if (isset($this->request->get['incentive_id'])) {
				$incentive_info = $this->model_incentive_incentive->getIncentive($this->request->get['incentive_id']);

				if ($incentive_info['presence_period_id']) {
					if ($this->user->hasPermission('bypass', 'incentive/incentive')) {
						if ($this->model_common_payroll->checkPeriodStatus($incentive_info['presence_period_id'], 'approved, released, completed')) { //Check period status
							$this->error['date'] = $this->language->get('error_status');
						}
					} else {
						if ($this->model_common_payroll->checkPeriodStatus($incentive_info['presence_period_id'], 'generated, approved, released, completed')) { //Check period status
							$this->error['date'] = $this->language->get('error_status_bypass');
						}
					}
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
		if (!$this->user->hasPermission('modify', 'incentive/incentive')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('common/payroll');

		foreach ($this->request->post['selected'] as $incentive_id) {
			$incentive_info = $this->model_incentive_incentive->getIncentive($incentive_id);

			if ($incentive_info['presence_period_id']) {
				if ($this->user->hasPermission('bypass', 'incentive/incentive')) {
					if ($this->model_common_payroll->checkPeriodStatus($incentive_info['presence_period_id'], 'approved, released, completed')) {
						$this->error['warning'] = $this->language->get('error_status');

						break;
					}
				} else {
					if ($this->model_common_payroll->checkPeriodStatus($incentive_info['presence_period_id'], 'generated, approved, released, completed')) {
						$this->error['warning'] = $this->language->get('error_status_bypass');

						break;
					}
				}
			}
		}

		return !$this->error;
	}

	public function autocomplete()
	{
		$this->load->language('incentive/incentive');

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
