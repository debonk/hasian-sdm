<?php
class ControllerPresenceSchedule extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');

		$this->getList();
	}

	public function edit() {
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');

		if (isset($this->request->get['presence_period_id'])) {
			$period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);
		}

		if (isset($this->request->get['customer_id'])) {
			$customer_info = $this->model_common_payroll->getCustomer($this->request->get['customer_id']);
		}

		if (!empty($period_info) && !empty($customer_info)) {
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_presence_schedule->editSchedule($this->request->get['presence_period_id'], $this->request->get['customer_id'], $this->request->post);

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

				if (isset($this->request->get['sort'])) {
					$url .= '&sort=' . $this->request->get['sort'];
				}

				if (isset($this->request->get['order'])) {
					$url .= '&order=' . $this->request->get['order'];
				}

				if (isset($this->request->get['page'])) {
					$url .= '&page=' . $this->request->get['page'];
				}

				if (isset($this->request->get['schedule_start'])) {
					$url .= '&schedule_start=' . $this->request->get['schedule_start'];
				}

				$this->response->redirect($this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $this->request->get['presence_period_id'] . $url, true));
			}

			$this->getForm();
		} else {
			return new Action('error/not_found');
		}
	}

	public function delete() {
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');
		
		if (isset($this->request->get['presence_period_id']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_presence_schedule->deleteSchedules($this->request->get['presence_period_id'], $customer_id);
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

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['schedule_start'])) {
				$url .= '&schedule_start=' . $this->request->get['schedule_start'];
			}

			$this->response->redirect($this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function recap() {
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');
		$this->load->model('presence/presence');
		
		if (isset($this->request->get['presence_period_id']) && $this->validateRecap()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_presence_schedule->recapPresenceSummary($this->request->get['presence_period_id'], $customer_id);
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

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['schedule_start'])) {
				$url .= '&schedule_start=' . $this->request->get['schedule_start'];
			}

			$this->response->redirect($this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	public function report() {
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');
		$this->load->model('presence/presence');

		$this->listReport();
	}

	protected function getList() {
		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}
		
		$this->load->model('presence/presence_period');
		$period_info = $this->model_presence_presence_period->getPresencePeriod($presence_period_id); //get current presence_period_id
		$presence_period_id = $period_info['presence_period_id'];

		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = '';
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = '';
		}

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = '';
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['schedule_start'])) {
			$url .= '&schedule_start=' . $this->request->get['schedule_start'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/schedule', 'token=' . $this->session->data['token'], true)
		);

		//Period Status Check
		$period_pending_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending');
		$period_processing_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'processing');

		$data['information'] = '';
		if ($period_pending_check || $period_processing_check) {
			$empty_schedule_count = $this->model_presence_schedule->getEmptySchedulesCount($presence_period_id);
			
			if ($empty_schedule_count) {
				$data['information'] = sprintf($this->language->get('info_no_data'), $empty_schedule_count);
			}
		}

		if ($period_pending_check) {
			$data['action'] = $this->url->link('presence/schedule/delete', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('presence/schedule/recap', 'token=' . $this->session->data['token'] . $url, true);
		}
		$data['back'] = $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'], true);

		$language_items = array(
			'heading_title',
			'text_list',
			'text_confirm',
			'text_loading',
			'text_all_customer_group',
			'text_all_location',
			'text_confirm_recap',
			'entry_name',
			'entry_customer_group',
			'entry_location',
			'entry_presence_period',
			'button_filter',
			'button_delete',
			'button_back',
			'button_apply_schedule',
			'button_recap'
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

		$data['presence_periods'] = $this->model_presence_presence_period->getPresencePeriods();

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['period_pending_check'] = $period_pending_check; //for button check
		$data['period_processing_check'] = $period_processing_check; //for button check
		
		$data['presence_period_id'] = $presence_period_id;
		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_location_id'] = $filter_location_id;

		$data['url'] = $url;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/schedule_list', $data));
	}

	protected function listReport() {
		$language_items = array(
			'text_no_results',
			'text_off',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_location',
			'column_action',
			'column_schedule_presence',
			'column_note',
			'button_edit',
			'button_prev',
			'button_next'
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
			$filter_name = '';
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = '';
		}

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = '';
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

		if (isset($this->request->get['schedule_start'])) {
			$schedule_start = $this->request->get['schedule_start'];
		} else {
			$today = date('Y-m-d', strtotime('today'));
			if ($period_info['date_start'] < $today && $period_info['date_end'] > $today) {
				$schedule_start = date('Y-m-d', strtotime('-2 day'));
			} else {
				$schedule_start = date('Y-m-d', strtotime($period_info['date_start']));
			}
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['schedule_start'])) {
			$url .= '&schedule_start=' . $this->request->get['schedule_start'];
		}

		$data['customers'] = array();

		$filter_data = array(
			'presence_period_id'   => $presence_period_id,
			'filter_name'	   	   => $filter_name,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_location_id'   => $filter_location_id,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);
		
		$range_date = array(
			'start'	=> $schedule_start,
			'end' 	=> date('Y-m-d', strtotime('+6 days', strtotime($schedule_start)))
		);

		$data['date_titles'] = $this->model_presence_schedule->getScheduleDateTitles($range_date);

		$period_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending, processing');
		
		if ($period_status_check) {
			$results = $this->model_presence_presence->getCustomers($filter_data);
			$customer_total = $this->model_presence_presence->getTotalCustomers($filter_data);
		} else {
			$results = $this->model_presence_schedule->getScheduleCustomers($presence_period_id, $filter_data);
			$customer_total = $this->model_presence_schedule->getScheduleCustomersCount($presence_period_id, $filter_data);
		}
		
		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');
		$this->load->model('overtime/overtime');
	
		foreach ($results as $result) {
			$schedules_data = array();

			$schedules = $this->model_presence_schedule->getFinalSchedules($result['customer_id'], $range_date);
			
			foreach ($schedules as $key => $schedule) {
				$schedules_data[$key] = array(
					'schedule_type_id' 	=> $schedule['schedule_type_id'],
					'schedule_type'		=> $schedule['schedule_type'] . ($schedule['time_in'] != '0000-00-00 00:00:00' ? '-' . date('H:i', strtotime($schedule['time_in'])) : $data['text_off']),
					'presence_status'	=> $schedule['presence_status'],
					'note'				=> $schedule['note'],
					'bg_class'			=> $schedule['bg_class']
				);
				
			}
	
			$data['customers'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'nip' 				=> $result['nip'],
				'name' 				=> $result['name'],
				'customer_group' 	=> $result['customer_group'],
				'location' 			=> $result['location'],
				'schedules_data' 	=> $schedules_data,
				'edit'          	=> $this->url->link('presence/schedule/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
			);
		}

		$data['token'] = $this->session->data['token'];

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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['schedule_start'])) {
			$url .= '&schedule_start=' . $this->request->get['schedule_start'];
		}

		$data['sort_nip'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['schedule_start'])) {
			$url .= '&schedule_start=' . $this->request->get['schedule_start'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$schedule_next = date('Y-m-d', min(strtotime('+7 day', strtotime($schedule_start)), strtotime('-6 day', strtotime($period_info['date_end']))));
		$schedule_prev = date('Y-m-d', max(strtotime('-7 day', strtotime($schedule_start)), strtotime('-2 day', strtotime($period_info['date_start']))));

		$data['schedule_next'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&schedule_start=' . $schedule_next . $url, true);
		$data['schedule_prev'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&schedule_start=' . $schedule_prev . $url, true);

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('presence/schedule_report', $data));
	}

	protected function getForm() {
		$data['text_form'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_schedule_detail',
			'text_legend',
			'text_confirm',
			'text_no_results',
			'text_presence_summary',
			'text_log',
			'text_off',
			'column_code',
			'column_time_start',
			'column_time_end',
			'column_hke',
			'column_t',
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
			'column_date',
			'column_schedule',
			'column_login',
			'column_logout',
			'column_presence',
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

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['schedule_start'])) {
			$url .= '&schedule_start=' . $this->request->get['schedule_start'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['edit'] = $this->url->link('presence/schedule/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . $url, true);
		$data['cancel'] = $this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['edit']
		);

		//Form Calendar
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$range_date = array(
			'start'	=> $period_info['date_start'],
			'end'	=> $period_info['date_end']
		);

		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');
		$this->load->model('overtime/overtime');
		
		$schedules_data = $this->model_presence_schedule->getFinalSchedules($customer_id, $range_date);

		$period_pending_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending');
		$period_processing_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'processing');
		// $period_pending_check = 0;
		// $period_processing_check = 0;
		
 		if ($period_pending_check || ($period_processing_check && empty($schedules_data))) {
			$locked_all = 0;
		} else {
			$locked_all = $this->config->get('payroll_setting_schedule_lock');
		}
			
		//Draw Calendar
		$data['list_days'] = explode(' ',$this->language->get('text_days'));

		$date_diff = date_diff(date_create($period_info['date_start']),date_create($period_info['date_end']));
		$date_start = strtotime($period_info['date_start']);
		
		$week_day_start = date('w',$date_start);

		$days_in_month = $date_diff->format('%a');
		
		$data['total_week'] = ceil(($days_in_month + $week_day_start + 1)/7);

		$data['calendar'] = array();
	
		// array "blank" days until the first of the current week //

		$counter = -$week_day_start;

		for($week = 0; $week < $data['total_week']; $week++) {
			for($day = 0; $day < 7; $day++) {
				if ($counter >= 0 && $counter <= $days_in_month) {
					$key_date = date('Y-m-d', strtotime('+' . $counter . ' day', $date_start));
					
					if (isset($this->request->post['schedule' . $key_date])) {
						$schedule_type_id = $this->request->post['schedule' . $key_date];
						$schedule_type_code = '-';
						$presence_status = '-';
						$time_login = '';
						$time_logout = '';
						$bg_class = '';
						
					} elseif (!empty($schedules_data[$key_date])) {
						$schedule_type_id = $schedules_data[$key_date]['schedule_type_id'];
						$schedule_type_code = $schedules_data[$key_date]['schedule_type'] . ($schedules_data[$key_date]['time_in'] != '0000-00-00 00:00:00' ? ' (' . date('H:i', strtotime($schedules_data[$key_date]['time_in'])) . '-' . date('H:i', strtotime($schedules_data[$key_date]['time_out'])) . ')' : $data['text_off']);
						$presence_status = $schedules_data[$key_date]['presence_status'];
						$time_login = ($schedules_data[$key_date]['time_login'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_data[$key_date]['time_login'])) : '...';
						$time_logout = ($schedules_data[$key_date]['time_logout'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_data[$key_date]['time_logout'])) : '...';
						$bg_class = !empty($schedules_data[$key_date]['bg_class']) ? $schedules_data[$key_date]['bg_class'] : 'info';
						$note = !empty($schedules_data[$key_date]['note']) ? $schedules_data[$key_date]['note'] : '';

					} else {
						$schedule_type_id = 0;
						$schedule_type_code = '-';
						$presence_status = '-';
						$time_login = '';
						$time_logout = '';
						$bg_class = '';
						$note = '';
					}
					
					if ($locked_all || strtotime($key_date) <= strtotime('today') || (isset($schedules_data[$key_date]) && $schedules_data[$key_date]['applied'] != 'schedule')) {
						$locked = 1;
					} else {
						$locked = 0;
					}

					$data['calendar'][$week . $day] = array(
						'date'				=> $key_date,
						'text'				=> date('j M', strtotime($key_date)),
						'schedule_type_id' 	=> $schedule_type_id,
						'schedule_type_code'=> $schedule_type_code,
						'presence_status'	=> $presence_status,
						'time_login'		=> $time_login,
						'time_logout'		=> $time_logout,
						'bg_class'			=> $bg_class,
						'note'				=> $note,
						'locked'			=> $locked
					);
				}
				$counter++;
			}
		}
		
		//Presence Summary
		$data['presence_summary'] = $this->model_presence_schedule->calculatePresenceSummary($presence_period_id, $customer_id, $schedules_data);

		//Legend
		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		$this->load->model('presence/schedule_type');
		// $data['schedule_types'] = $this->model_presence_schedule_type->getScheduleTypesByLocationGroup($customer_info['location_id'], $customer_info['customer_group_id']);
		$schedule_types = $this->model_presence_schedule_type->getScheduleTypesByLocationGroup($customer_info['location_id'], $customer_info['customer_group_id']);

		$data['schedule_types'] = array();
		
		foreach ($schedule_types as $schedule_type) {
			$time_start = date('H:i', strtotime($schedule_type['time_start']));
			$time_end = date('H:i', strtotime($schedule_type['time_end']));

			$data['schedule_types'][] = array(
				'schedule_type_id'	=> $schedule_type['schedule_type_id'],
				'code'				=> $schedule_type['code'],
				'time_start'		=> $time_start,
				'time_end'			=> $time_end,
				'text'				=> $schedule_type['code'] . ' (' . $time_start . '-' . $time_end . ')'
			);
		}
		//End Legend
		
		$data['presence_period_id'] = $presence_period_id;
		$data['customer_id'] = $customer_id;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/schedule_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'presence/schedule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'pending, processing')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'presence/schedule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'pending')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		$period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);
		if (strtotime($period_info['date_start']) <= strtotime('today')) {
			$this->error['warning'] = $this->language->get('error_date_start');
		}

		if (!isset($this->request->post['selected'])) {
			$this->error['warning'] = $this->language->get('error_not_selected');
		}

		return !$this->error;
	}

	protected function validateRecap() {
		if (!$this->user->hasPermission('modify', 'presence/schedule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'processing')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		if (!$this->model_presence_schedule->getScheduleCustomersCount($this->request->get['presence_period_id'])) {
			$this->error['warning'] = $this->language->get('error_not_found');
		}

		// Validasi jika periode belum berakhir
		// $period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);
		// if (strtotime('today') < strtotime($period_info['date_end'])) {
			// $this->error['warning'] = $this->language->get('error_date_end');
		// }

		if (!isset($this->request->post['selected'])) {
			$this->error['warning'] = $this->language->get('error_not_selected');
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

	public function applySchedule() {
		$this->load->language('presence/schedule');

		$json = array();

		if (!$this->user->hasPermission('modify', 'presence/schedule')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['presence_period_id'])) {
				$presence_period_id = $this->request->get['presence_period_id'];
			} else {
				$presence_period_id = 0;
			}

			$this->load->model('presence/schedule');
			$schedule_customer_count = $this->model_presence_schedule->getScheduleCustomersCount($presence_period_id);

			$this->load->model('common/payroll');
			$period_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending');

			if (!$schedule_customer_count) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif (!$period_status_check) {
				$json['error'] = $this->language->get('error_status');				
			} else {
				$this->model_common_payroll->setPeriodStatus($presence_period_id, 'processing');

				$json['success'] = $this->language->get('text_success_process');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
