<?php
class ControllerPresencePresence extends Controller {
	private $error = array();

	public function index() {
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

	public function edit() {
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
			$presence_data = array();
			
			foreach ($this->request->post as $key => $value) {
				if (substr($key,0,6) == 'detail' && $value) {
					$presence_data[substr($key,6)] = $value;
				}
			}

			$this->model_presence_presence->editPresences($this->request->get['presence_period_id'], $this->request->get['customer_id'], $presence_data);

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

	public function delete() {
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

	protected function getList() {
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
			'href' => $this->url->link('presence/presence', 'token=' . $this->session->data['token'], true)
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
			'filter_payroll_include' 	=> $filter_payroll_include,
			'filter_presence_code' 		=> $filter_presence_code,
			'sort'                 		=> $sort,
			'order'                		=> $order,
			'start'                		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                		=> $this->config->get('config_limit_admin')
		);

		if ($payroll_status_check) {//Untuk bisa mengedit data cust yg masih kosong
			$results = $this->model_presence_presence->getAllCustomerPresenceSummaries($presence_period_id, $filter_data);
			$customer_total = $this->model_presence_presence->getAllCustomerPresenceSummariesCount($presence_period_id, $filter_data);
		} else {
			$results = $this->model_presence_presence->getPresenceSummaries($presence_period_id, $filter_data);
			$customer_total = $this->model_presence_presence->getPresenceSummariesCount($presence_period_id, $filter_data);
		}
		
		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');
	
		//Get absence Note
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		foreach ($results as $result) {
			//Get Note
			$range_date = array(
				'start'	=> $period_info['date_start'],
				'end'	=> $period_info['date_end']
			);
			
			$range_date['start'] = max($range_date['start'], $result['date_start']);
			
			if ($result['date_end']) {
				$range_date['end'] = min($range_date['end'], $result['date_end']);
			}
			//End GetNote Block
	
			$absences_info = $this->model_presence_absence->getAbsencesByCustomerDate($result['customer_id'], $range_date);
			
			$note = implode(', ', array_filter(array_column($absences_info, 'description')));
				
			if ($payroll_status_check) {
				$presence_summary = $this->model_presence_presence->getPresenceSummary($presence_period_id, $result['customer_id']);
				
				if ($presence_summary) {
					$data['customers'][] = array(
						'customer_id' 		=> $result['customer_id'],
						'nip' 				=> $result['nip'],
						'name' 				=> $result['name'],
						'customer_group' 	=> $result['customer_group'],
						'location' 			=> $result['location'],
						'hke' 				=> $presence_summary['total_h'] + $presence_summary['total_s'] + $presence_summary['total_i'] + $presence_summary['total_ns'] + $presence_summary['total_ia'] + $presence_summary['total_a'],
						'total_h' 			=> $presence_summary['total_h'],
						'total_s'         	=> $presence_summary['total_s'],
						'total_i'         	=> $presence_summary['total_i'],
						'total_ns'         	=> $presence_summary['total_ns'],
						'total_ia'         	=> $presence_summary['total_ia'],
						'total_a'         	=> $presence_summary['total_a'],
						'total_c'         	=> $presence_summary['total_c'],
						'total_t1'         	=> $presence_summary['total_t1'],
						'total_t2'         	=> $presence_summary['total_t2'],
						'total_t3'         	=> $presence_summary['total_t3'],
						'note' 				=> strlen($note) > 50 ? substr($note, 0, 48) . '..' : $note,
						'edit'          	=> $this->url->link('presence/presence/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
					);
					
				} else {
					$data['customers'][] = array(
						'customer_id' 		=> $result['customer_id'],
						'nip' 				=> $result['nip'],
						'name' 				=> $result['name'],
						'customer_group' 	=> $result['customer_group'],
						'location' 			=> $result['location'],
						'hke' 				=> '',
						'total_h' 			=> '',
						'total_s'         	=> '',
						'total_i'         	=> '',
						'total_ns'         	=> '',
						'total_ia'         	=> '',
						'total_a'         	=> '',
						'total_c'         	=> '',
						'total_t1'         	=> '',
						'total_t2'         	=> '',
						'total_t3'         	=> '',
						'note' 				=> '',
						'edit'          	=> $this->url->link('presence/presence/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
					);
				}
			
			} else {
				$data['customers'][] = array(
					'customer_id' 		=> $result['customer_id'],
					'nip' 				=> $result['nip'],
					'name' 				=> $result['name'],
					'customer_group' 	=> $result['customer_group'],
					'location' 			=> $result['location'],
					'hke' 				=> $result['total_h'] + $result['total_s'] + $result['total_i'] + $result['total_ns'] + $result['total_ia'] + $result['total_a'],
					'total_h' 			=> $result['total_h'],
					'total_s'         	=> $result['total_s'],
					'total_i'         	=> $result['total_i'],
					'total_ns'         	=> $result['total_ns'],
					'total_ia'         	=> $result['total_ia'],
					'total_a'         	=> $result['total_a'],
					'total_c'         	=> $result['total_c'],
					'total_t1'         	=> $result['total_t1'],
					'total_t2'         	=> $result['total_t2'],
					'total_t3'         	=> $result['total_t3'],
					'note' 				=> strlen($note) > 30 ? substr($note, 0, 28) . '..' : $note,
					'edit'          	=> $this->url->link('presence/presence/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				);
			}
		}

		$language_items = array(
			'heading_title',
			'text_list',
			'text_confirm',
			'text_submit_confirm',
			'text_no_results',
			'text_all',
			'text_loading',
			'text_yes',
			'text_no',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_location',
			'column_action',
			'column_presence_summary',
			'column_hke',
			'column_h',
			'column_s',
			'column_i',
			'column_ns',
			'column_ia',
			'column_a',
			'column_c',
			'column_t1',
			'column_t2',
			'column_t3',
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

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/presence_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_presence_detail',
			'text_presence_summary',
			'text_late_summary',
			'text_total_t',
			'text_hke',
			'text_confirm',
			'text_no_results',
			'text_off',
			'column_hke',
			'column_h',
			'column_s',
			'column_i',
			'column_ns',
			'column_ia',
			'column_a',
			'column_c',
			'column_t1',
			'column_t2',
			'column_t3',
			'entry_h',
			'entry_s',
			'entry_i',
			'entry_ns',
			'entry_ia',
			'entry_a',
			'entry_c',
			'entry_t1',
			'entry_t2',
			'entry_t3',
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
		$schedules_info = $this->model_presence_schedule->getFinalSchedules($customer_id, $range_date);
		
		$presences_info = $this->model_presence_presence->getFinalPresences($customer_id, $range_date);
		
		//Form Calendar
		$data['list_days'] = explode(' ',$this->language->get('text_days'));

		$date_diff = date_diff(date_create($period_info['date_start']),date_create($period_info['date_end']));
		$date_start = strtotime($period_info['date_start']);
		
		$week_day_start = date('w',$date_start);
		$days_in_month = $date_diff->format('%a');
		
		$data['total_week'] = ceil(($days_in_month + $week_day_start + 1)/7);

		$data['calendar'] = array();
	
		/* array "blank" days until the first of the current week */

		$counter = -$week_day_start;

		for($week = 0; $week < $data['total_week']; $week++) {
			for($day = 0; $day < 7; $day++) {
				if ($counter >= 0 && $counter <= $days_in_month) {
					$date_text = date('Y-m-d', strtotime('+' . $counter . ' day', $date_start));
					
					if ($range_date['start'] > $date_text || $date_text > $range_date['end']) {
						$locked = 1;
					} else {
						$locked = 0;
					}

					$schedule_type	= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_in'] != '0000-00-00 00:00:00') ? ($schedules_info[$date_text]['schedule_type'] . ' (' . date('H:i', strtotime($schedules_info[$date_text]['time_in'])) . '-' . date('H:i', strtotime($schedules_info[$date_text]['time_out'])) . ')') : $data['text_off'];
					$time_login		= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_login'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_info[$date_text]['time_login'])) : '...';
					$time_logout	= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_logout'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_info[$date_text]['time_logout'])) : '...';
					$bg_class		= !empty($schedules_info[$date_text]['bg_class']) ? $schedules_info[$date_text]['bg_class'] : 'info';
					$note			= !empty($schedules_info[$date_text]['note']) ? $schedules_info[$date_text]['note'] : '';

					if (isset($this->request->post['detail' . $date_text])) {
						$presence_status_id = $this->request->post['detail' . $date_text];
						$presence_status = '-';
					} elseif (!empty($presences_info[$date_text])) {
						$presence_status_id = $presences_info[$date_text]['presence_status_id'];
						$presence_status = $presences_info[$date_text]['presence_status'];
						$locked = $presences_info[$date_text]['locked'];
						// $schedule_type	= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_in'] != '0000-00-00 00:00:00') ? ($schedules_info[$date_text]['schedule_type'] . ' (' . date('H:i', strtotime($schedules_info[$date_text]['time_in'])) . '-' . date('H:i', strtotime($schedules_info[$date_text]['time_out'])) . ')') : $data['text_off'];
						// $time_login		= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_login'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_info[$date_text]['time_login'])) : '...';
						// $time_logout	= (isset($schedules_info[$date_text]) && $schedules_info[$date_text]['time_logout'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_info[$date_text]['time_logout'])) : '...';
						// $bg_class		= !empty($schedules_info[$date_text]['bg_class']) ? $schedules_info[$date_text]['bg_class'] : 'info';
						// $note			= !empty($schedules_info[$date_text]['note']) ? $schedules_info[$date_text]['note'] : '';
					} else {
						$presence_status_id = 0;
						$presence_status = '-';
						// $schedule_type	= '-';
						// $time_login		= '';
						// $time_logout	= '';
						// $bg_class		= 'warning';
						// $note			= '';
					}

					$data['calendar'][$week . $day] = array(
						'date'				=> $date_text,
						'text'				=> date('j M', strtotime('+ ' . $counter . ' day', $date_start)),
						'presence_status_id'=> $presence_status_id,
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
		$data['presence_summary'] = array();
		
		$presence_summary_data = $this->model_presence_presence->getPresenceSummary($presence_period_id, $customer_id);
		
		foreach ($presence_summary_data as $key => $total) {
			$data['presence_summary'][$key] = $total;
		}
		
		if (!empty($data['presence_summary']['full_overtimes_count'])) {
			$data['presence_summary']['hke'] .= ' (' . $data['presence_summary']['full_overtimes_count'] . ' ' . $this->language->get('code_full_overtime') . ')';
		}

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

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'presence/presence')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			$period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);
			
			if ($this->user->hasPermission('bypass', 'presence/presence')) {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'approved, released, completed')) {//Check period status
					$this->error['warning'] = $this->language->get('error_status');
				}
			} else {
				if ($period_info && $this->model_common_payroll->checkPeriodStatus($period_info['presence_period_id'], 'submitted, generated, approved, released, completed')) {//Check period status
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

	protected function validateDelete() {
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

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$this->load->model('presence/presence');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 5
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

	public function submitPresence() {
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

	public function export() {
		$this->load->language('presence/presence');

		$this->load->model('presence/presence');
		$this->load->model('common/payroll');

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
		
		$language_items = array(
			'text_list',
			'text_no_results',
			'text_presence_summary',
			'column_no',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_location',
			'column_hke',
			'column_h',
			'column_s',
			'column_i',
			'column_ns',
			'column_ia',
			'column_a',
			'column_c',
			'column_t1',
			'column_t2',
			'column_t3',
			'column_total_t',
			'column_note',
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$period = date('M_Y', strtotime($period_info['period']));

		$output = $data['text_list'] . ' - ' . $period . '||';
		$output .= $data['text_presence_summary'] . '||||';

		$output .= $data['column_no'] . '|' . $data['column_nip'] . '|' . $data['column_name'] . '|' . $data['column_customer_group'] . '|' . $data['column_location'] . '|' . $data['column_hke'] . '|' . $data['column_h'] . '|' . $data['column_s'] . '|' . $data['column_i'] . '|' . $data['column_ns'] . '|' . $data['column_ia'] . '|' . $data['column_a'] . '|' . $data['column_c'] . '|' . $data['column_total_t'] . '|' . $data['column_t1'] . '|' . $data['column_t2'] . '|' . $data['column_t3'] . '|' . $data['column_note'] . '||';
		
		$no = 1;
		
		//Get absence Note
		$range_date = array(
			'start'	=> $period_info['date_start'],
			'end'	=> $period_info['date_end']
		);
		//End GetNote Block
		
		$this->load->model('presence/absence');
	
		foreach ($results as $result) {
			//Get Note
			$range_date['start'] = max($range_date['start'], $result['date_start']);
			
			if ($result['date_end']) {
				$range_date['end'] = min($range_date['end'], $result['date_end']);
			}
	
			$absences_info = $this->model_presence_absence->getAbsencesByCustomerDate($result['customer_id'], $range_date);
			
			if($absences_info) {
				$note = implode(', ', array_filter(array_column($absences_info, 'description')));
			} else {
				$note = '-';
			}
				
			$hke = $result['total_h'] + $result['total_s'] + $result['total_i'] + $result['total_ns'] + $result['total_ia'] + $result['total_a'];
			$total_t = $result['total_t1'] + 3 * $result['total_t2'] + 5 * $result['total_t3'];
			
			$output .= $no . '|' . $result['nip'] . '|' . $result['name'] . '|' . $result['customer_group'] . '|' . $result['location'] . '|' . $hke . '|' . $result['total_h'] . '|' . $result['total_s'] . '|' . $result['total_i'] . '|' . $result['total_ns'] . '|' . $result['total_ia'] . '|' . $result['total_a'] . '|' . $result['total_c'] . '|' . $total_t . '|' . $result['total_t1'] . '|' . $result['total_t2'] . '|' . $result['total_t3'] . '|' . $note . '||';

			$no++;
		}

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

	public function presenceInfo() {
		$this->load->language('presence/presence');

		$this->load->model('common/payroll');
		$this->load->model('presence/presence');
		$this->load->model('presence/exchange');

		$presence_period_id = $this->request->get['presence_period_id'];
		
		$language_items = array(
			'text_sum_presence',
			'text_no_results',
			'column_h',
			'column_s',
			'column_i',
			'column_ns',
			'column_ia',
			'column_a',
			'column_c',
			'column_t1',
			'column_t2',
			'column_t3'
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

		if (isset($this->request->get['filter_payroll_include'])) {
			$filter_payroll_include = $this->request->get['filter_payroll_include'];
		} else {
			$filter_payroll_include = null;
		}

		$data['presences_summary_total'] = array();
		
		// $period_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending, processing');

		$filter_data = array(
			'presence_period_id'   		=> $presence_period_id,
			'filter_name'	   	   		=> $filter_name,
			'filter_customer_group_id' 	=> $filter_customer_group_id,
			'filter_location_id' 		=> $filter_location_id,
			'filter_payroll_include'	=> $filter_payroll_include
		);

		// if ($period_status_check) {
			// $period_info = $this->model_common_payroll->getPeriod($presence_period_id);

			// $range_date = array(
				// 'start'	=> $period_info['date_start'],
				// 'end'	=> $period_info['date_end']
			// );
			
			// $data['presences_summary'] = array();
			
			// $results = $this->model_presence_presence->getCustomers($filter_data);
		
			// $this->load->model('presence/absence');
			
			// foreach ($results as $result) {
				// $presence_summary = $this->model_presence_presence->getPresenceSummaryResult($presence_period_id, $result['customer_id'], $range_date);

				// $data['presences_summary'][] = array(
					// 'total_h' 			=> $presence_summary['total_h'],
					// 'total_s'         	=> $presence_summary['total_s'],
					// 'total_i'         	=> $presence_summary['total_i'],
					// 'total_ns'         	=> $presence_summary['total_ns'],
					// 'total_ia'         	=> $presence_summary['total_ia'],
					// 'total_a'         	=> $presence_summary['total_a'],
					// 'total_c'         	=> $presence_summary['total_c'],
					// 'total_t1'         	=> $presence_summary['total_t1'],
					// 'total_t2'         	=> $presence_summary['total_t2'],
					// 'total_t3'         	=> $presence_summary['total_t3']
				// );
			// }
			
			// $presence_statuses = array(
				// 'h',
				// 's',
				// 'i',
				// 'ns',
				// 'ia',
				// 'a',
				// 'c',
				// 't1',
				// 't2',
				// 't3'
			// );
			// foreach ($presence_statuses as $presence_status) {
				// $data['presences_summary_total']['sum_' . $presence_status] = array_sum(array_column($data['presences_summary'], 'total_' . $presence_status));
			// }
			
		// } else {
			$presence_summary_total = $this->model_presence_presence->getPresenceSummariesTotal($filter_data);

			if (array_sum($presence_summary_total)) {
				foreach ($presence_summary_total as $key => $value) {
					$data['presences_summary_total'][$key] = $value;
				}
			}
		// }
		
		$this->response->setOutput($this->load->view('presence/presence_info', $data));
	}

	public function overridePresence() { //used by: payroll_form
		$this->load->language('presence/presence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'payroll/payroll')) {
			$json['error'] = $this->language->get('error_permission');
			
		} elseif (isset($this->request->post['presence_period_id'], $this->request->post['customer_id'])) {
			$presence_period_id = $this->request->post['presence_period_id'];
			$customer_id = $this->request->post['customer_id']; 

			$presence_items = array(
				'total_h',
				'total_s',
				'total_i',
				'total_ns',
				'total_ia',
				'total_a',
				'total_c',
				'total_t1',
				'total_t2',
				'total_t3'
			);

			$presence_data = array();
			
			foreach ($presence_items as $presence_item) {
				$presence_data[$presence_item] = $this->request->post[$presence_item];
			}

			$this->load->model('presence/presence');

			$presence_override = $this->model_presence_presence->editPresenceSummary($presence_period_id, $customer_id, $presence_data);

			if ($presence_override) {
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_action');
				// $this->session->data['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
