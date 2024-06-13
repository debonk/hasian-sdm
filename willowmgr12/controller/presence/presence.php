<?php
class ControllerPresencePresence extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('presence/presence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/presence');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$this->load->model('presence/presence_period');
		$presence_period = $this->model_presence_presence_period->getPresencePeriod($presence_period_id);
		if ($presence_period) {
			$this->getList();
		} else {
			return new Action('error/not_found');
		}
	}

	public function edit()
	{
		$this->load->language('presence/presence');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->load->model('common/payroll');

		$payroll_period = $this->model_common_payroll->getPeriod($presence_period_id);
		$customer_info = $this->model_common_payroll->checkCustomer($customer_id);

		if (!$payroll_period || !$customer_info) {
			return new Action('error/not_found');
		}

		$this->load->model('presence/presence');
		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');
		// $this->load->model('overtime/overtime');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->db->transaction(function () {
				$presences_data = array();

				foreach ($this->request->post as $key => $value) {
					if (substr($key, 0, 6) == 'detail' && $value) {
						$presences_data[substr($key, 6)] = $value;
					}
				}

				$this->model_presence_presence->editPresences($this->request->get['presence_period_id'], $this->request->get['customer_id'], $presences_data);
			});


			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			$url .= '&presence_period_id=' . $presence_period_id;

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_location_id'])) {
				$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
			}

			if (isset($this->request->get['filter_contract_type_id'])) {
				$url .= '&filter_contract_type_id=' . $this->request->get['filter_contract_type_id'];
			}

			if (isset($this->request->get['filter_payroll_include'])) {
				$url .= '&filter_payroll_include=' . $this->request->get['filter_payroll_include'];
			}

			if (isset($this->request->get['filter_presence_code'])) {
				$url .= '&filter_presence_code=' . $this->request->get['filter_presence_code'];
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

			$this->response->redirect($this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $this->request->get['presence_period_id'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('presence/presence');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/presence');
		$this->load->model('presence/presence_period');

		if (isset($this->request->get['presence_period_id']) && isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_presence_presence->deletePresence($this->request->get['presence_period_id'], $customer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			$url .= '&presence_period_id=' . $this->request->get['presence_period_id'];

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_location_id'])) {
				$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
			}

			if (isset($this->request->get['filter_contract_type_id'])) {
				$url .= '&filter_contract_type_id=' . $this->request->get['filter_contract_type_id'];
			}

			if (isset($this->request->get['filter_payroll_include'])) {
				$url .= '&filter_payroll_include=' . $this->request->get['filter_payroll_include'];
			}

			if (isset($this->request->get['filter_presence_code'])) {
				$url .= '&filter_presence_code=' . $this->request->get['filter_presence_code'];
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

			$this->response->redirect($this->url->link('presence/presence', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_confirm',
			'text_submit_confirm',
			'text_all',
			'text_loading',
			'text_yes',
			'text_no',
			'text_no_results',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_location',
			'column_contract_type',
			'column_action',
			'column_presence_summary',
			'column_note',
			'entry_presence_period',
			'entry_name',
			'entry_customer_group',
			'entry_location',
			'entry_payroll_include',
			'entry_presence_status',
			'button_edit',
			'button_presence_submit',
			'button_back',
			'button_delete',
			'button_filter',
			'button_export',
			'button_schedule'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$presence_period = $this->model_presence_presence_period->getPresencePeriod($presence_period_id);
		$presence_period_id = $presence_period['presence_period_id'];

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

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = null;
		}

		if (isset($this->request->get['filter_contract_type_id'])) {
			$filter_contract_type_id = $this->request->get['filter_contract_type_id'];
		} else {
			$filter_contract_type_id = null;
		}

		if (isset($this->request->get['filter_payroll_include'])) {
			$filter_payroll_include = $this->request->get['filter_payroll_include'];
		} else {
			$filter_payroll_include = null;
		}

		if (isset($this->request->get['filter_presence_code'])) {
			$filter_presence_code = $this->request->get['filter_presence_code'];
		} else {
			$filter_presence_code = null;
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
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_contract_type_id'])) {
			$url .= '&filter_contract_type_id=' . $this->request->get['filter_contract_type_id'];
		}

		if (isset($this->request->get['filter_payroll_include'])) {
			$url .= '&filter_payroll_include=' . $this->request->get['filter_payroll_include'];
		}

		if (isset($this->request->get['filter_presence_code'])) {
			$url .= '&filter_presence_code=' . $this->request->get['filter_presence_code'];
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

		$data['url'] = $url;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true)
		);

		//Period Status Check
		$payroll_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending, processing');
		$submitted_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'processing, submitted, generated, approved, released');

		$data['information'] = null;
		if ($payroll_status_check) {
			$empty_presence_count = $this->model_presence_presence->getEmptyPresencesCount($presence_period_id);

			if ($empty_presence_count) {
				$data['information'] = sprintf($this->language->get('info_no_data'), $empty_presence_count);
			}
		}

		$data['delete'] = $this->url->link('presence/presence/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['back'] = $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'], true);
		$data['schedule'] = $this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true);

		$data['customers'] = array();

		$filter_data = array(
			'presence_period_id'   		=> $presence_period_id,
			'filter_name'	   	   		=> $filter_name,
			'filter_customer_group_id' 	=> $filter_customer_group_id,
			'filter_location_id' 		=> $filter_location_id,
			'filter_contract_type_id' 	=> $filter_contract_type_id,
			'filter_payroll_include' 	=> $filter_payroll_include,
			'filter_presence_code' 		=> $filter_presence_code,
			'sort'                 		=> $sort,
			'order'                		=> $order,
			'start'                		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                		=> $this->config->get('config_limit_admin')
		);

		if ($payroll_status_check) { //Untuk bisa mengedit data cust yg masih kosong
			$results = $this->model_presence_presence->getAllCustomerPresenceSummaries($presence_period_id, $filter_data);
			$customer_total = $this->model_presence_presence->getAllCustomerPresenceSummariesCount($presence_period_id, $filter_data);
		} else {
			$results = $this->model_presence_presence->getPresenceSummaries($presence_period_id, $filter_data);
			$customer_total = $this->model_presence_presence->getPresenceSummariesCount($presence_period_id, $filter_data);
		}

		$additional_items = $this->model_presence_presence->getPresenceAdditionalItem(array_column($results, 'additional'));

		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');

		//Get absence Note
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		// $data['presence_data_title'] = [];

		foreach ($results as $result) {
			$contract_type = $result['contract_type'] ? $result['contract_type'] : '-';

			$result['presence_data'] = $this->model_presence_presence->calculatePresenceSummaryData($result, $additional_items);

			$presence_data = [];

			# Get Note
			$range_date = array(
				'start'	=> $period_info['date_start'],
				'end'	=> $period_info['date_end']
			);

			$range_date['start'] = max($range_date['start'], $result['date_start']);

			if ($result['date_end']) {
				$range_date['end'] = min($range_date['end'], $result['date_end']);
			}

			$absences_info = $this->model_presence_absence->getAbsencesByCustomerDate($result['customer_id'], $range_date);

			$note = implode(', ', array_filter(array_column($absences_info, 'description')));
			# GetNote Block End

			$result['note'] = strlen($note) > 50 ? substr($note, 0, 48) . '..' : $note;
			$result['edit'] = $this->url->link('presence/presence/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true);

			$presence_data['hke'] = $result['presence_data']['total']['hke'];

			$result['presence_data'] = array_merge($presence_data, $result['presence_data']['primary'], $result['presence_data']['additional'], $result['presence_data']['secondary']);

			$data['customers'][] = $result;

			// $data['presence_data_title'] = array_merge($data['presence_data_title'], $result['presence_data']);
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
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_contract_type_id'])) {
			$url .= '&filter_contract_type_id=' . $this->request->get['filter_contract_type_id'];
		}

		if (isset($this->request->get['filter_payroll_include'])) {
			$url .= '&filter_payroll_include=' . $this->request->get['filter_payroll_include'];
		}

		if (isset($this->request->get['filter_presence_code'])) {
			$url .= '&filter_presence_code=' . $this->request->get['filter_presence_code'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_nip'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_contract_type'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&sort=contract_type' . $url, true);

		$sort_presences = ['h', 's', 'i', 'ns', 'ia', 'a', 'c', 't1', 't2', 't3'];

		foreach ($sort_presences as $code) {
			$data['sort_total'][$code] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&sort=total_' . $code . $url, true);
		}

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_contract_type_id'])) {
			$url .= '&filter_contract_type_id=' . $this->request->get['filter_contract_type_id'];
		}

		if (isset($this->request->get['filter_payroll_include'])) {
			$url .= '&filter_payroll_include=' . $this->request->get['filter_payroll_include'];
		}

		if (isset($this->request->get['filter_presence_code'])) {
			$url .= '&filter_presence_code=' . $this->request->get['filter_presence_code'];
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
		$pagination->url = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ($page * $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

		$data['presence_periods'] = $this->model_presence_presence_period->getPresencePeriods();

		$data['payroll_status_check'] = $payroll_status_check;
		$data['submitted_status_check'] = $submitted_status_check;
		$data['presence_period_id'] = $presence_period_id;
		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_location_id'] = $filter_location_id;
		$data['filter_contract_type_id'] = $filter_contract_type_id;
		$data['filter_payroll_include'] = $filter_payroll_include;
		$data['filter_presence_code'] = $filter_presence_code;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('localisation/presence_status');
		$data['presence_statuses'] = $this->model_localisation_presence_status->getPresenceStatuses();

		$this->load->model('customer/contract_type');
		$data['contract_types'] = $this->model_customer_contract_type->getContractTypes(['filter' => ['all' => true]]);

		if ($data['customers']) {
			$data['presence_data_title'] = array_keys($data['customers'][0]['presence_data']);
		} else {
			$presence_statuses_data = $this->model_localisation_presence_status->getPresenceStatusesData();

			$data['presence_data_title'] = ['hke'];

			$data['presence_data_title'] = array_merge($data['presence_data_title'], $presence_statuses_data['primary'], $presence_statuses_data['secondary']);
		}

		$data['presence_data_count'] = count($data['presence_data_title']);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/presence_list', $data));
	}

	protected function getForm()
	{
		$data['text_form'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_presence_detail',
			'text_presence_summary',
			'text_late_summary',
			'text_confirm',
			'text_off',
			'column_t',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$presence_period_id = $this->request->get['presence_period_id'];
		$customer_id = $this->request->get['customer_id'];

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
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_contract_type_id'])) {
			$url .= '&filter_contract_type_id=' . $this->request->get['filter_contract_type_id'];
		}

		if (isset($this->request->get['filter_payroll_include'])) {
			$url .= '&filter_payroll_include=' . $this->request->get['filter_payroll_include'];
		}

		if (isset($this->request->get['filter_presence_code'])) {
			$url .= '&filter_presence_code=' . $this->request->get['filter_presence_code'];
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
			'href' => $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['edit'] = $this->url->link('presence/presence/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . $url, true);
		$data['cancel'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['edit']
		);

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		$range_date = array(
			'start'	=> max($period_info['date_start'], $customer_info['date_start']),
			'end'	=> $customer_info['date_end'] ? min($period_info['date_end'], $customer_info['date_end']) : $period_info['date_end']
		);

		$this->load->model('presence/schedule');
		$this->load->model('overtime/overtime');
		$schedules_info = $this->model_presence_schedule->getFinalSchedules($presence_period_id, $customer_id, $range_date);

		$presences_info = $this->model_presence_presence->getFinalPresences($customer_id, $range_date);

		//Form Calendar
		$data['list_days'] = explode(' ', $this->language->get('text_days'));

		$date_diff = date_diff(date_create($period_info['date_start']), date_create($period_info['date_end']));
		$date_start = strtotime($period_info['date_start']);

		$week_day_start = date('w', $date_start);
		$days_in_month = $date_diff->format('%a');

		$data['total_week'] = ceil(($days_in_month + $week_day_start + 1) / 7);

		$data['calendar'] = array();

		/* array "blank" days until the first of the current week */

		$counter = -$week_day_start;

		for ($week = 0; $week < $data['total_week']; $week++) {
			for ($day = 0; $day < 7; $day++) {
				if ($counter >= 0 && $counter <= $days_in_month) {
					$date_text = date('Y-m-d', strtotime('+' . $counter . ' day', $date_start));

					if ($range_date['start'] > $date_text || $date_text > $range_date['end']) {
						$locked = 1;
					} else {
						$locked = 0;
					}

					$schedule_type	= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_in'] != '0000-00-00 00:00:00') ? ($schedules_info[$date_text]['schedule_type'] . ' (' . date('H:i', strtotime($schedules_info[$date_text]['time_in'])) . '-' . date('H:i', strtotime($schedules_info[$date_text]['time_out'])) . ')') : $data['text_off'];
					$time_login		= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_login'] != '0000-00-00 00:00:00') ? date('H:i:s', strtotime($schedules_info[$date_text]['time_login'])) : '...';
					$time_logout	= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_logout'] != '0000-00-00 00:00:00') ? date('H:i:s', strtotime($schedules_info[$date_text]['time_logout'])) : '...';
					$bg_class		= !empty($schedules_info[$date_text]['bg_class']) ? $schedules_info[$date_text]['bg_class'] : 'info';
					$note			= !empty($schedules_info[$date_text]['note']) ? $schedules_info[$date_text]['note'] : '';

					if (isset($this->request->post['detail' . $date_text])) {
						$presence_status_id = $this->request->post['detail' . $date_text];
						$presence_status = '-';
					} elseif (!empty($presences_info[$date_text])) {
						$presence_status_id = $presences_info[$date_text]['presence_status_id'];
						$presence_status = $presences_info[$date_text]['presence_status'];
						$locked = $presences_info[$date_text]['locked'];
					} else {
						$presence_status_id = 0;
						$presence_status = '-';
					}

					$data['calendar'][$week . $day] = array(
						'date'				=> $date_text,
						'text'				=> date('j M', strtotime('+ ' . $counter . ' day', $date_start)),
						'presence_status_id' => $presence_status_id,
						'presence_status'	=> $presence_status,
						'locked'			=> $locked,
						'schedule_type'		=> $schedule_type,
						'time_login'		=> $time_login,
						'time_logout'		=> $time_logout,
						'bg_class'			=> $bg_class,
						'note'				=> $note
					);
				}
				$counter++;
			}
		}
		//End Calendar

		//Ringkasan Absensi
		$presence_summary = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);

		if (!empty($presence_summary['total']['full_overtime'])) {
			$presence_summary['total']['hke'] .= ' (' . $presence_summary['total']['full_overtime'] . ' ' . $this->language->get('code_full_overtime') . ')';
		}

		$data['presence_summary']['hke'] = $presence_summary['total']['hke'];
		$data['presence_summary'] = array_merge($data['presence_summary'], $presence_summary['primary'], $presence_summary['additional']);

		$data['presence_summary_width'] = (100 / count($data['presence_summary'])) . '%';

		$data['late_summary']['t'] = $presence_summary['total']['t'];
		$data['late_summary'] = array_merge($data['late_summary'], $presence_summary['secondary']);

		$data['presence_period_id'] = $presence_period_id;
		$data['customer_id'] = $customer_id;
		$data['url'] = $url;

		$this->load->model('localisation/presence_status');
		$data['presence_statuses'] = $this->model_localisation_presence_status->getPresenceStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/presence_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'presence/presence')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			$period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);

			if ($this->user->hasPermission('bypass', 'presence/presence')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) { //Check period status
					$this->error['warning'] = $this->language->get('error_status');
				}
			} else {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) { //Check period status
					$this->error['warning'] = $this->language->get('error_status');
				}

				$customer_id = $this->request->get['customer_id'];
				$customer_info = $this->model_common_payroll->getCustomer($customer_id);

				if ($this->config->has('payroll_setting_presence_lock') && in_array($customer_info['location_id'], $this->config->get('payroll_setting_presence_lock'))) {
					$this->error['warning'] = $this->language->get('error_presence_lock');
				}
			}
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'presence/presence')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'processing')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		$this->load->model('payroll/payroll');

		foreach ($this->request->post['selected'] as $customer_id) {
			$payroll_total = $this->model_payroll_payroll->getPayroll($this->request->get['presence_period_id'], $customer_id);

			if ($payroll_total) {
				$this->error['warning'] = $this->language->get('error_data');
			}
		}

		return !$this->error;
	}

	public function autocomplete()
	{
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];

			$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

			$this->load->model('presence/presence');

			$filter_data = array(
				'presence_period_id'	=> $presence_period_id,
				'filter_name' 			=> $filter_name,
				'start'       			=> 0,
				'limit'       			=> 15
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

	public function submitPresence()
	{
		$this->load->language('presence/presence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'presence/presence')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['presence_period_id'])) {
				$presence_period_id = $this->request->get['presence_period_id'];
			} else {
				$presence_period_id = 0;
			}

			$this->load->model('common/payroll');
			$this->load->model('presence/presence');

			$presence_count = $this->model_presence_presence->getPresencesCount($presence_period_id);
			$payroll_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'processing');

			if (!$presence_count) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif (!$payroll_status_check) {
				$json['error'] = $this->language->get('error_status');
			} else {
				$this->load->model('presence/exchange');

				$this->model_common_payroll->setPeriodStatus($presence_period_id, 'submitted');

				$json['success'] = $this->language->get('text_submit_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function export()
	{
		$this->load->language('presence/presence');

		$this->load->model('presence/presence');
		$this->load->model('common/payroll');

		$language_items = array(
			'text_list',
			'text_no_results',
			'text_presence_summary',
			'column_no',
			'column_nip',
			'column_name',
			'column_lastname',
			'column_customer_group',
			'column_location',
			'column_t',
			'column_note',
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		$presence_period_id = $period_info['presence_period_id'];

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

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = null;
		}

		if (isset($this->request->get['filter_contract_type_id'])) {
			$filter_contract_type_id = $this->request->get['filter_contract_type_id'];
		} else {
			$filter_contract_type_id = null;
		}

		if (isset($this->request->get['filter_payroll_include'])) {
			$filter_payroll_include = $this->request->get['filter_payroll_include'];
		} else {
			$filter_payroll_include = null;
		}

		if (isset($this->request->get['filter_presence_code'])) {
			$filter_presence_code = $this->request->get['filter_presence_code'];
		} else {
			$filter_presence_code = null;
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

		//Period Status Check
		$submitted_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'processing, submitted, generated, approved, released');

		$filter_data = array(
			'presence_period_id'   		=> $presence_period_id,
			'filter_name'	   	   		=> $filter_name,
			'filter_customer_group_id' 	=> $filter_customer_group_id,
			'filter_location_id' 		=> $filter_location_id,
			'filter_contract_type_id' 	=> $filter_contract_type_id,
			'filter_payroll_include' 	=> $filter_payroll_include,
			'filter_presence_code' 		=> $filter_presence_code,
			'sort'                 		=> $sort,
			'order'                		=> $order
		);

		if ($submitted_status_check) {
			$results = $this->model_presence_presence->getPresenceSummaries($presence_period_id, $filter_data);
		} else {
			return new Action('error/not_found');
		}

		$period = date('M_Y', strtotime($period_info['period']));

		$output = $data['text_list'] . ' - ' . $period . '||';
		$output .= $data['text_presence_summary'] . '||||';

		$output .= $data['column_no'] . '|' . $data['column_nip'] . '|' . $data['column_name'] . '|' . $data['column_lastname'] . '|' . $data['column_customer_group'] . '|' . $data['column_location'] . '|';

		$no = 1;

		$this->load->model('presence/absence');

		$output_data = '';
		$presence_data_title = [];

		$additional_items = $this->model_presence_presence->getPresenceAdditionalItem(array_column($results, 'additional'));

		foreach ($results as $result) {
			$result['presence_data'] = $this->model_presence_presence->calculatePresenceSummaryData($result, $additional_items);

			$presence_data = [];

			//Get Note
			$range_date = array(
				'start'	=> $period_info['date_start'],
				'end'	=> $period_info['date_end']
			);

			$range_date['start'] = max($range_date['start'], $result['date_start']);

			if ($result['date_end']) {
				$range_date['end'] = min($range_date['end'], $result['date_end']);
			}

			$absences_info = $this->model_presence_absence->getAbsencesByCustomerDate($result['customer_id'], $range_date);

			if ($absences_info) {
				$note = implode(', ', array_filter(array_column($absences_info, 'description')));
			} else {
				$note = '-';
			}

			$presence_data['hke'] = $result['presence_data']['total']['hke'];

			$presence_data = array_merge($presence_data, $result['presence_data']['primary'], $result['presence_data']['additional'], $result['presence_data']['secondary']);

			$output_data .= $no . '|' . $result['nip'] . '|' . $result['firstname'] . '|' . $result['lastname'] . '|' . $result['customer_group'] . '|' . $result['location'] . '|';

			$output_data .= implode('|', $presence_data) . '|' . $note . '||';

			$presence_data_title = array_merge($presence_data_title, $presence_data);

			$no++;
		}

		$output .= utf8_strtoupper(implode('|', array_keys($presence_data_title))) . '|' . $data['column_note'] . '||';

		$output .= $output_data;

		$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
		$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
		$output = str_replace('\\', '\\\\',	$output);
		$output = str_replace('\'', '\\\'',	$output);
		$output = str_replace('\\\n', '\n',	$output);
		$output = str_replace('\\\r', '\r',	$output);
		$output = str_replace('\\\t', '\t',	$output);
		$output = str_replace('||', "\n",	$output);
		$output = str_replace('|', "\t",	$output);

		$data['token'] = $this->session->data['token'];

		$filename = date('Ym', strtotime($period_info['period'])) . ' - ' . $data['text_list'] . ' - ' . $period;
		$filename = str_replace(' ', '_',	$filename);

		$this->response->addheader('Pragma: public');
		$this->response->addheader('Expires: 0');
		$this->response->addheader('Content-Description: File Transfer');
		$this->response->addheader('Content-Type: application/octet-stream');
		$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.xls');
		$this->response->addheader('Content-Transfer-Encoding: binary');
		$this->response->setOutput($output);
		// echo $output;

	}

	public function presenceInfo()
	{
		$this->load->language('presence/presence');

		$this->load->model('common/payroll');
		$this->load->model('presence/presence');
		$this->load->model('presence/exchange');

		$presence_period_id = $this->request->get['presence_period_id'];

		$language_items = array(
			'text_sum_presence'
		);
		foreach ($language_items as $item) {
			$data[$item] = $this->language->get($item);
		}

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

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = null;
		}

		if (isset($this->request->get['filter_contract_type_id'])) {
			$filter_contract_type_id = $this->request->get['filter_contract_type_id'];
		} else {
			$filter_contract_type_id = null;
		}

		if (isset($this->request->get['filter_payroll_include'])) {
			$filter_payroll_include = $this->request->get['filter_payroll_include'];
		} else {
			$filter_payroll_include = null;
		}

		$data['presences_summary_total'] = array();

		$filter_data = array(
			'presence_period_id'   		=> $presence_period_id,
			'filter_name'	   	   		=> $filter_name,
			'filter_customer_group_id' 	=> $filter_customer_group_id,
			'filter_location_id' 		=> $filter_location_id,
			'filter_contract_type_id' 	=> $filter_contract_type_id,
			'filter_payroll_include'	=> $filter_payroll_include
		);

		$presence_summary_total = $this->model_presence_presence->getPresenceSummariesTotal($filter_data);

		$data['presence_summary_total'] = array_merge($presence_summary_total['primary'], $presence_summary_total['additional']);

		$data['presence_summary_total_width'] = (100 / count($data['presence_summary_total'])) . '%';

		$data['late_summary_total'] = $presence_summary_total['secondary'];

		$this->response->setOutput($this->load->view('presence/presence_info', $data));
	}

	public function overridePresence()
	{ //used by: payroll_form
		$this->load->language('presence/presence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'payroll/payroll')) {
			$json['error'] = $this->language->get('error_permission');
		} elseif (isset($this->request->get['presence_period_id'], $this->request->get['customer_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
			$customer_id = $this->request->get['customer_id'];

			$this->load->model('common/payroll');

			if ($this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed')) {
				$json['error'] = $this->language->get('error_status');
			} else {

				$this->load->model('presence/presence');

				$presence_data = [
					'presence_period_id'	=> $presence_period_id,
					'customer_id'			=> $customer_id
				];

				foreach ($this->request->post as $group => $items) {
					if ($group == 'additional') {
						$presence_data['additional'] = json_encode($items);
					} else {
						foreach ($items as $key => $value) {
							$presence_data['total_' . $key] = $value;
						}
					}
				}

				$presence_summary_data = $this->model_presence_presence->calculatePresenceSummaryData($presence_data);

				$presence_override = $this->model_presence_presence->editPresenceSummary($presence_period_id, $customer_id, $presence_summary_data);

				if ($presence_override) {
					$json['success'] = $this->language->get('text_success');
				} else {
					$json['error'] = $this->language->get('error_status');
				}
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
