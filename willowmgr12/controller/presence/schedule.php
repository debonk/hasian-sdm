<?php
class ControllerPresenceSchedule extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');

		if ($this->registry->has('framework_load')) {
			$this->getList();
		} else {
			return new Action('error/not_found');
		}
	}

	public function edit()
	{
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');

		if (isset($this->request->get['presence_period_id'])) {
			$period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);
		}

		if (isset($this->request->get['customer_id'])) {
			$customer_info = $this->model_common_payroll->checkCustomer($this->request->get['customer_id']);
		}

		if (!empty($period_info) && !empty($customer_info)) {
			if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
				$this->model_presence_schedule->editSchedule($this->request->get['presence_period_id'], $this->request->get['customer_id'], $this->request->post);

				$this->session->data['success'] = $this->language->get('text_success');

				$url = '';

				if (isset($this->request->get['filter_name'])) {
					$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
				}

				if (isset($this->request->get['filter_customer_group_id'])) {
					$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
				}

				if (isset($this->request->get['filter_customer_department_id'])) {
					$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

	public function delete()
	{
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

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

	public function recap()
	{
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');
		$this->load->model('presence/presence');

		if (isset($this->request->get['presence_period_id']) && $this->validateRecap()) {
			if (!$this->request->post['selected']) {
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

				if (isset($this->request->get['filter_customer_department_id'])) {
					$filter_customer_department_id = $this->request->get['filter_customer_department_id'];
				} else {
					$filter_customer_department_id = '';
				}

				if (isset($this->request->get['filter_location_id'])) {
					$filter_location_id = $this->request->get['filter_location_id'];
				} else {
					$filter_location_id = '';
				}

				$filter_data = array(
					'presence_period_id'   			=> $this->request->get['presence_period_id'],
					'filter_name'	   	   			=> $filter_name,
					'filter_customer_group_id'		=> $filter_customer_group_id,
					'filter_customer_department_id'	=> $filter_customer_department_id,
					'filter_location_id'   			=> $filter_location_id,
				);

				$customers = $this->model_presence_presence->getCustomers($filter_data);
				$customer_ids = array_column($customers, 'customer_id');
			} else {
				$customer_ids = $this->request->post['selected'];
			}

			$this->db->transaction(function () use ($customer_ids) {
				$this->model_presence_schedule->recapPresenceSummary($this->request->get['presence_period_id'], $customer_ids);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			$url .= '&presence_period_id=' . $this->request->get['presence_period_id'];

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

	protected function getList()
	{
		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id); //get current presence_period_id
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$filter_customer_department_id = $this->request->get['filter_customer_department_id'];
		} else {
			$filter_customer_department_id = '';
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
			'href' => $this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true)
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

		$data['import'] = $this->url->link('presence/schedule/import', 'token=' . $this->session->data['token'] . $url, true);
		$data['print'] = $this->url->link('presence/schedule/print', 'token=' . $this->session->data['token'] . $url, true);
		$data['back'] = $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'], true);
		$data['presence'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . $url, true);

		$language_items = array(
			'heading_title',
			'text_list',
			'text_confirm',
			'text_loading',
			'text_all_customer_group',
			'text_all_customer_department',
			'text_all_location',
			'text_confirm_recap',
			'entry_name',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'entry_presence_period',
			'button_filter',
			'button_print',
			'button_delete',
			'button_back',
			'button_import',
			'button_apply_schedule',
			'button_recap',
			'button_presence'
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

		$this->load->model('presence/presence_period');
		$data['presence_periods'] = $this->model_presence_presence_period->getPresencePeriods();

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('customer/customer_department');
		$data['customer_departments'] = $this->model_customer_customer_department->getCustomerDepartments();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['period_pending_check'] = $period_pending_check; //for button check
		$data['period_processing_check'] = $period_processing_check; //for button check

		$data['presence_period_id'] = $presence_period_id;
		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_customer_department_id'] = $filter_customer_department_id;
		$data['filter_location_id'] = $filter_location_id;

		$data['url'] = $url;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/schedule_list', $data));
	}

	public function print()
	{
		$this->load->language('presence/schedule');

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');

		$language_items = array(
			'heading_title',
			'text_no_results',
			'text_summary',
			'text_schedule_summary',
			'text_group_summary',
			'column_name',
			'column_schedule_type',
			'column_customer_group'
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

		// $period_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending, approved, released, completed');

		// if ($period_status_check || !$this->user->hasPermission('modify', 'presence/schedule')) {
		// return new Action('error/not_found');
		// }

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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$filter_customer_department_id = $this->request->get['filter_customer_department_id'];
		} else {
			$filter_customer_department_id = '';
		}

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = '';
		}

		$sort = 'customer_group DESC, name';

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');
		$data['store_logo'] = HTTP_CATALOG . 'image/' . $this->config->get('config_logo');

		$data['customers'] = array();
		$schedule_groups = array(); // Rekap berdasarkan schedule_types
		$data['schedule_groups'] = array(); // Rekap berdasarkan schedule_types
		$data['customer_groups'] = array(); // Memisahkan customer_groups yang jumlahnya >= 15

		$filter_customer_department_id = $this->user->getCustomerDepartmentId();

		$filter_data = array(
			'filter_customer_department_id'	=> $filter_customer_department_id,
			'filter_name'	   	   			=> $filter_name,
			'filter_customer_group_id' 		=> $filter_customer_group_id,
			'filter_customer_department_id' => $filter_customer_department_id,
			'filter_location_id'   			=> $filter_location_id,
			'sort'                 			=> $sort,
			'order'                			=> $order
		);

		$data['location'] = !empty($filter_location_id) ? $this->model_common_payroll->getLocation($filter_location_id) : $this->language->get('text_all_location');
		$data['text_division'] = sprintf($this->language->get('text_department'), !empty($filter_customer_department_id) ? $this->model_common_payroll->getCustomerDepartment($filter_customer_department_id) : $this->language->get('text_all_customer_department'));
		$data['text_period'] = sprintf($this->language->get('text_period'), date($this->language->get('date_format_jMY'), strtotime($period_info['date_start'])), date($this->language->get('date_format_jMY'), strtotime($period_info['date_end'])));
		$data['text_user'] = sprintf($this->language->get('text_user'), $this->user->getUserName());

		$range_date = array(
			'start'	=> $period_info['date_start'],
			'end' 	=> $period_info['date_end']
		);

		$data['date_titles'] = $this->model_presence_schedule->getScheduleDateTitles($range_date);
		$date_keys = array_column($data['date_titles'], 'date');

		$results = $this->model_presence_schedule->getScheduleCustomers($presence_period_id, $filter_data);

		// Rekap berdasarkan customer_groups
		$customer_groups = array_unique(array_column($results, 'customer_group'));

		foreach ($customer_groups as $customer_group) {
			$data['customer_groups'][$customer_group] = array_fill_keys($date_keys, 0);
		}

		// Memisahkan customer_groups yang jumlahnya >= 15
		$customer_group_pages = array_count_values(array_column($results, 'customer_group'));

		foreach ($customer_group_pages as $key => $value) {
			if ($value < 15) {
				$customer_group_pages[$key] = 0;
			} else {
				$customer_group_pages[$key] = $key;
			}
		}

		$this->load->model('presence/absence');
		$this->load->model('presence/exchange');
		$this->load->model('overtime/overtime');

		foreach ($results as $result) {
			$schedules_data = array();

			$schedules = $this->model_presence_schedule->getFinalSchedules($presence_period_id, $result['customer_id'], $range_date);

			foreach ($schedules as $key => $schedule) {
				$schedules_data[$key] = array(
					'schedule_type_id' 	=> $schedule['schedule_type_id'],
					'schedule_type'		=> !empty($schedule['schedule_type']) ? $schedule['schedule_type'] : '-',
					'schedule_bg'		=> $schedule['schedule_bg'],
					'bg_class'			=> $schedule['bg_class']
				);

				// Rekap berdasarkan schedule_types 
				if (!isset($schedule_groups[$customer_group_pages[$result['customer_group']]][$schedule['schedule_type_id']][$key])) {
					$schedule_groups[$customer_group_pages[$result['customer_group']]][$schedule['schedule_type_id']] = array_fill_keys($date_keys, 0);
				}

				// Hitung hanya data dalam range_date 
				if (in_array($key, $date_keys)) {
					$schedule_groups[$customer_group_pages[$result['customer_group']]][$schedule['schedule_type_id']][$key]++;

					// Rekap berdasarkan customer_groups
					if ($schedule['schedule_type_id']) {
						$data['customer_groups'][$result['customer_group']][$key]++;
					}
				}
			}

			$data['customers'][$customer_group_pages[$result['customer_group']]][] = array(
				'customer_id' 		=> $result['customer_id'],
				'name' 				=> $result['firstname'],
				'customer_group' 	=> $result['customer_group'],
				'schedules_data' 	=> $schedules_data
			);
		}

		$this->load->model('presence/schedule_type');

		foreach ($schedule_groups as $customer_group_pages => $customer_group_data) {
			ksort($customer_group_data);

			foreach ($customer_group_data as $schedule_type_id => $schedule_group_data) {
				if ($schedule_type_id) {
					$schedule_type_info = $this->model_presence_schedule_type->getScheduleType($schedule_type_id);

					$bg = $schedule_type_info['bg_idx'];
					$text = sprintf($this->language->get('text_schedule_type'), $schedule_type_info['code'], date($this->language->get('time_format'), strtotime($schedule_type_info['time_start'])), date($this->language->get('time_format'), strtotime($schedule_type_info['time_end'])));
				} else {
					$text = $this->language->get('text_off');
					$bg = 0;
				}

				$data['schedule_groups'][$customer_group_pages][] = [
					'text'		=> $text,
					'bg'		=> $bg,
					'group_data' => $schedule_group_data
				];
			}
		}
		$this->response->setOutput($this->load->view('presence/schedule_print', $data));
	}

	public function report()
	{
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/schedule');
		$this->load->model('presence/presence');

		$language_items = array(
			'text_no_results',
			'text_off',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_customer_department',
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$filter_customer_department_id = $this->request->get['filter_customer_department_id'];
		} else {
			$filter_customer_department_id = '';
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
			if ($period_info['date_end'] < date('Y-m-d')) {
				$schedule_start = date('Y-m-d', strtotime($period_info['date_start']));
			} else {
				$schedule_start = max($period_info['date_start'], date('Y-m-d', strtotime('-2 days')));

				$schedule_start = min($schedule_start, date('Y-m-d', strtotime('-6 days', strtotime($period_info['date_end']))));
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
			'presence_period_id'   			=> $presence_period_id,
			'filter_name'	   	   			=> $filter_name,
			'filter_customer_group_id'		=> $filter_customer_group_id,
			'filter_customer_department_id'	=> $filter_customer_department_id,
			'filter_location_id'   			=> $filter_location_id,
			'sort'                 			=> $sort,
			'order'                			=> $order,
			'start'                			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                			=> $this->config->get('config_limit_admin')
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
		$this->load->model('presence/presence');

		foreach ($results as $result) {
			$schedules_data = array();

			$schedules = $this->model_presence_schedule->getFinalSchedules($presence_period_id, $result['customer_id'], $range_date);

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
				'customer_id' 			=> $result['customer_id'],
				'nip' 					=> $result['nip'],
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'schedules_data' 		=> $schedules_data,
				'edit'          		=> $this->url->link('presence/schedule/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
		$data['sort_customer_department'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('presence/schedule/report', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

	public function import()
	{
		$this->load->language('presence/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$presence_period_id = isset($this->request->get['presence_period_id']) ? (int)$this->request->get['presence_period_id'] : 0;

		$data['text_form'] = $this->language->get('text_import');

		$language_items = [
			'heading_title',
			'text_browse',
			'text_confirm',
			'entry_file',
			'entry_template',
			'button_download',
			'button_export',
			'button_import',
			'button_cancel',
			'button_clear'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_import'),
			'href' => $this->url->link('presence/schedule/import', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['download'] = $this->url->link('presence/schedule/download', 'token=' . $this->session->data['token'] . $url, true);
		$data['import_data'] = $this->url->link('presence/schedule/importData', 'token=' . $this->session->data['token'] . $url, true);
		$data['clear'] = $this->url->link('presence/schedule/clear', 'token=' . $this->session->data['token'] . $url, true);
		$data['cancel'] = $this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . $url, true);

		$data['log'] = '';

		$file = DIR_LOGS . 'schedule.log';

		if (file_exists($file)) {
			$size = filesize($file);

			if ($size >= 5242880) {
				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				$i = 0;

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$data['error_warning'] = sprintf($this->language->get('error_warning'), basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
			} else {
				$data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/schedule_import', $data));
	}

	public function download()
	{
		$this->load->language('presence/schedule');

		$presence_period_id = isset($this->request->get['presence_period_id']) ? (int)$this->request->get['presence_period_id'] : 0;

		if ($this->user->getCustomerDepartmentId()) {
			$filter_customer_department_id = $this->user->getCustomerDepartmentId();
		} else {
			$filter_customer_department_id = isset($this->request->get['filter_customer_department_id']) ? (int)$this->request->get['filter_customer_department_id'] : 0;
		}

		$filter = [
			'name'						=> isset($this->request->get['filter_name']) ? $this->request->get['filter_name'] : '',
			'customer_group_id'			=> isset($this->request->get['filter_customer_group_id']) ? (int)$this->request->get['filter_customer_group_id'] : 0,
			'customer_department_id'	=> $filter_customer_department_id,
			'location_id'				=> isset($this->request->get['filter_location_id']) ? (int)$this->request->get['filter_location_id'] : 0,
			'status'					=> 1
		];

		$title_color = 'FF76933c';
		$table_head_format = [
			'fill' => [
				'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
				'startColor' => [
					'argb' => 'FF9bbb59',
				],
			],
			'font' => [
				'color' => [
					'argb' => 'FFFFFFFF',
				],
			],
		];

		$conditional_colors = ['ff808080', 'ffcc9900', 'ff99cc00', 'ff009933', 'ff009999', 'ff3333ff', 'ffcc00ff', 'ff00cc66', 'ff0099cc', 'ff6600ff', 'ffcc6699'];

		switch ($this->error) {
			case false:
				if (!$this->user->hasPermission('modify', 'presence/schedule')) {
					$this->error = $this->language->get('error_permission');

					break;
				}

				$this->load->model('common/payroll');

				$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

				if (!$period_info) {
					$this->error = $this->language->get('error_period');

					break;
				}

				if (strtolower($period_info['payroll_status']) != 'pending') {
					$this->error = $this->language->get('error_status');

					break;
				}

				if ($this->user->getCustomerDepartmentId() && $filter['customer_department_id'] && $this->user->getCustomerDepartmentId() != $filter['customer_department_id']) {
					$this->error = $this->language->get('error_customer_department');

					break;
				}

				$php_spreadsheet = new Spreadsheet('Xlsx');

				$spreadsheet = $php_spreadsheet->loadSpreadsheet(DIR_FILE . 'Schedule Form Template.xlsx');

				# Sheet: Setting
				# Set Cell Format because PhpSpreadsheet Bug losing color data from template
				$spreadsheet->getSheetByName('Setting')->getStyle('A1')->getFont()->getColor()->setARGB($title_color);
				$spreadsheet->getSheetByName('Setting')->getStyle('F1')->getFont()->getColor()->setARGB($title_color);

				$setting = [
					'store'				=> $this->config->get('config_name'),
					'date_start'		=> $period_info['date_start'],
					'date_end'			=> $period_info['date_end'],
					'user_id'			=> $this->user->getId(),
					'division'			=> $this->user->getCustomerDepartmentId(),
					'security_token'	=> '4gBjUpFX2t'
				];

				if ($this->user->getCustomerDepartmentId()) {
					$this->load->model('customer/customer_department');

					$division_info = $this->model_customer_customer_department->getCustomerDepartment($this->user->getCustomerDepartmentId());

					$division = $division_info['name'];
				} else {
					$division = $this->language->get('text_all_division');
				}

				$setting_data = [
					md5(implode('', $setting)),
					null,
					htmlspecialchars_decode($this->config->get('config_name')),
					\PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($period_info['period']),
					\PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($period_info['date_start']),
					\PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($period_info['date_end']),
					null,
					$this->user->getUsername(),
					$division
				];

				$setting_data = array_chunk($setting_data, 1);

				$spreadsheet->getActiveSheet()->fromArray($setting_data, NULL, 'B2');

				# Sheet: Tipe Jadwal
				# Set Cell Format because PhpSpreadsheet Bug losing color data from template
				$spreadsheet->getSheetByName('Tipe Jadwal')->getStyle('A1')->getFont()->getColor()->setARGB($title_color);
				$spreadsheet->getActiveSheet()->getStyle('A2:D2')->applyFromArray($table_head_format);

				$this->load->model('presence/presence');
				// $this->load->model('customer/customer');
				$this->load->model('presence/schedule_type');

				$filter_data = [
					'presence_period_id'   			=> $presence_period_id,
					'filter'	   	   				=> $filter,
					'filter_name'	   	   			=> $filter['name'],
					'filter_customer_group_id'		=> $filter['customer_group_id'],
					'filter_customer_department_id'	=> $filter['customer_department_id'],
					'filter_location_id'   			=> $filter['location_id'],
					'filter_status'   				=> 1
				];

				$customer_count = $this->model_presence_presence->getTotalCustomers($filter_data);
				$customers = $this->model_presence_presence->getCustomers($filter_data);

				// $customer_count = $this->model_customer_customer->getTotalCustomers($filter_data);
				// $customers = $this->model_customer_customer->getCustomers($filter_data);

				$customer_groups = array_unique(array_column($customers, 'customer_group_id'));

				$schedule_type_data = [];

				unset($filter_data['filter']['name']);

				foreach ($customer_groups as $customer_group) {
					$filter_data['filter']['customer_group_id'] = $customer_group;

					$schedule_types = $this->model_presence_schedule_type->getScheduleTypes($filter_data);

					foreach ($schedule_types as $schedule_type) {
						if (!isset($schedule_type_data[$schedule_type['schedule_type_id']])) {
							$schedule_type_data[$schedule_type['schedule_type_id']] = [
								$schedule_type['code_id'],
								date('H:i', strtotime($schedule_type['time_start'])),
								date('H:i', strtotime($schedule_type['time_end'])),
								$schedule_type['schedule_type_id']
							];
						}
					}
				}

				$schedule_type_data = array_values($schedule_type_data);

				$schedule_type_count = count($schedule_type_data);

				$spreadsheet->getActiveSheet()->insertNewRowBefore(4, $schedule_type_count);

				$spreadsheet->getActiveSheet()->fromArray($schedule_type_data, NULL, 'A4');

				# Sheet: Jadwal
				# Set Cell Format because PhpSpreadsheet Bug losing color data from template
				$spreadsheet->getSheetByName('Jadwal')->getStyle('A1')->getFont()->getColor()->setARGB($title_color);
				$spreadsheet->getSheetByName('Jadwal')->getStyle('B7')->getFont()->getColor()->setARGB($title_color);
				$spreadsheet->getActiveSheet()->getStyle('A2:AK3')->applyFromArray($table_head_format);
				$spreadsheet->getActiveSheet()->getStyle('C8:AK8')->applyFromArray($table_head_format);

				$summary_data = [];

				if ($schedule_type_count > 1) {
					$spreadsheet->getActiveSheet()->insertNewRowBefore(11, $schedule_type_count - 1);
				}

				for ($i = 0; $i <= $schedule_type_count; $i++) {
					$spreadsheet->getActiveSheet()->getStyle('C' . ($i + 9) . ':AK' . ($i + 9))->getFill()
						->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
						->getStartColor()->setARGB($conditional_colors[fmod($i, 11)]);
				}

				$ref = [];

				for ($i = 7; $i <= 37; $i++) {
					$column = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);

					$ref[$i] = '=COUNTIF(' . $column . '4:' . $column . '5,';
				}

				foreach ($schedule_type_data as $key => $schedule_type) {
					$summary_data[$key] = [
						$schedule_type[1],
						$schedule_type[2],
						$schedule_type[0],
						$schedule_type[3]
					];

					for ($i = 7; $i <= 37; $i++) {
						$summary_data[$key][] = $ref[$i] . 'E' . ($key + 10) . ')';
					}
				}

				$spreadsheet->getSheetByName('Jadwal')->fromArray($summary_data, NULL, 'C10');

				$header_data = [
					'NO',
					'NAMA',
					'JABATAN',
					'DIVISI',
					'LOKASI',
					'ID'
				];

				$diff = date_diff(date_create($period_info['date_start']), date_create($period_info['date_end']));
				$day_count = $diff->days;

				// $day_count = 28; // For testing

				$day = \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($period_info['date_start']);

				$header_date_data = [];

				for ($i = 0; $i <= $day_count; $i++) {
					$header_date_data[] = $day + $i;
				}

				$customer_data = [];

				foreach ($customers as $key => $customer) {
					$customer_data[] = [
						$key + 1,
						trim($customer['name']),
						$customer['customer_group'],
						$customer['customer_department'],
						$customer['location'],
						$customer['customer_id'],
					];
				}

				if ($day_count < 30) {
					$cell = $spreadsheet->getActiveSheet()->getCellByColumnAndRow($day_count + 8, 3);

					$spreadsheet->getActiveSheet()->removeColumn($cell->getColumn(), 30 - $day_count);
				}

				if ($customer_count > 1) {
					$spreadsheet->getActiveSheet()->insertNewRowBefore(5, $customer_count - 1);
				}

				# Conditional Formatting.
				$schedule_range = 'G4:AK' . ($customer_count + 3);

				$conditional_styles = $spreadsheet->getActiveSheet()->getStyle($schedule_range)->getConditionalStyles();

				for ($i = 0; $i <= $schedule_type_count; $i++) {
					unset($conditional);

					$conditional = new \PhpOffice\PhpSpreadsheet\Style\Conditional();

					$conditional->setConditionType(\PhpOffice\PhpSpreadsheet\Style\Conditional::CONDITION_CELLIS)
						->setOperatorType(\PhpOffice\PhpSpreadsheet\Style\Conditional::OPERATOR_EQUAL)
						->addCondition('$E$' . ($customer_count + 8 + $i))
						->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
						->getEndColor()->setARGB($conditional_colors[fmod($i, 11)]);

					$conditional_styles[] = $conditional;
				}

				$spreadsheet->getActiveSheet()->getStyle($schedule_range)->setConditionalStyles($conditional_styles);

				$spreadsheet->getSheetByName('Jadwal')
					->fromArray(array_merge($header_data, $header_date_data), NULL, 'A3')
					->fromArray($customer_data, NULL, 'A4');

				$spreadsheet->setActiveSheetIndexByName('Setting');

				# Force to download
				$new_file = DIR_DOWNLOAD . 'Schedule Form Final.xlsx';

				$writer = $php_spreadsheet->writer('Xlsx');
				$writer->setPreCalculateFormulas(false);

				$writer->save($new_file);

				$spreadsheet->disconnectWorksheets();
				unset($spreadsheet);

				if (!headers_sent()) {
					if (is_file($new_file)) {
						header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						header('Content-Disposition: attachment; filename=' . basename($new_file));
						header('Expires: 0');
						header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
						header('Pragma: public');
						header('Content-Length: ' . filesize($new_file));

						if (ob_get_level()) {
							ob_end_clean();
						}

						readfile($new_file, 'rb');

						exit();
					} else {
						exit('Error: Could not find file ' . $new_file . '!');
					}
				} else {
					exit('Error: Headers already sent out!');
				}

				break;

			default:
				break;
		}

		if ($this->error) {
			$this->session->data['error'] = $this->error;

			$url = '';
			$url .= '&presence_period_id=' . $presence_period_id;

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

			$this->response->redirect($this->url->link('presence/schedule/import', 'token=' . $this->session->data['token'] . $url, true));
		}
	}

	public function importData()
	{
		$this->load->language('presence/schedule');

		$presence_period_id = isset($this->request->get['presence_period_id']) ? (int)$this->request->get['presence_period_id'] : 0;

		$schedule_data = [];

		switch ($this->error) {
			case false:
				if (($this->request->server['REQUEST_METHOD'] != 'POST') || !$this->user->hasPermission('modify', 'presence/schedule')) {
					$this->error = $this->language->get('error_permission');

					break;
				}

				$this->load->model('common/payroll');

				$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

				if (!$period_info) {
					$this->error = $this->language->get('error_period');

					break;
				}

				if (strtolower($period_info['payroll_status']) != 'pending') {
					$this->error = $this->language->get('error_status');

					break;
				}

				$file = $this->request->files['file'];

				if (empty($file['name']) || !is_uploaded_file($file['tmp_name'])) {
					$this->error = $this->language->get('error_empty');

					break;
				}

				// $extension = strtolower(substr(strrchr($file['name'], '.'), 1));
				$extension = pathinfo($file['name'], PATHINFO_EXTENSION);

				if ($extension != 'xlsx') {
					$this->error = $this->language->get('error_filetype');

					break;
				}

				if ($file['type'] != 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
					$this->error = $this->language->get('error_filetype');

					break;
				}

				// Return any upload error
				if ($file['error'] != UPLOAD_ERR_OK) {
					$this->error = sprintf($this->language->get('error_upload'), $file['error']);

					break;
				}

				$spreadsheet_option = [
					'data_only'		=> false,
					'sheet_names'	=> ['Setting', 'Tipe Jadwal', 'Jadwal']
				];

				$php_spreadsheet = new Spreadsheet('Xlsx', $spreadsheet_option);
				$spreadsheet = $php_spreadsheet->loadSpreadsheet($file['tmp_name']);

				# Save uploaded file Start
				$uploaded_file = $file['name'] . '.' . token(32);

				move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $uploaded_file);

				# Hide the uploaded file name so people can not link to it directly.
				$this->load->model('tool/upload');

				$this->model_tool_upload->addUpload($file['name'], $uploaded_file);
				# Save uploaded file End

				if ($spreadsheet->getSheetNames() != $spreadsheet_option['sheet_names']) {
					$this->error = $this->language->get('error_sheet');

					break;
				}

				$setting = [
					'store'				=> $this->config->get('config_name'),
					'date_start'		=> $period_info['date_start'],
					'date_end'			=> $period_info['date_end'],
					'user_id'			=> $this->user->getId(),
					'division'			=> $this->user->getCustomerDepartmentId(),
					'security_token'	=> '4gBjUpFX2t'
				];

				$setting_data = $spreadsheet->getSheetByName('Setting')->getCell('B2')->getValue();

				if (md5(implode('', $setting)) != $setting_data) {
					$this->error = $this->language->get('error_setting');

					break;
				}

				$schedule_type = [];

				$schedule_type_data = $spreadsheet->getSheetByName('Tipe Jadwal')
					->toArray(null, false, false, false);

				if (array_slice($schedule_type_data[1], 0, 4) !== ['KODE', 'JAM MASUK', 'JAM KELUAR', 'ID']) {
					$this->error = $this->language->get('error_schedule_type');

					break;
				}

				$schedule_type_data = array_slice($schedule_type_data, 2);

				foreach ($schedule_type_data as $value) {
					if (!$value[0]) {
						break;
					}

					if (!isset($schedule_type[$value[0]])) {
						$schedule_type[$value[0]] = (int)$value[3];
					} else {
						$this->error = $this->language->get('error_schedule_type');

						break 2;
					}
				}

				$schedule_data = $spreadsheet->getSheetByName('Jadwal')
					->toArray(null, false, false, false);

				$header_data = [
					'NO',
					'NAMA',
					'JABATAN',
					'DIVISI',
					'LOKASI',
					'ID'
				];

				$diff = date_diff(date_create($period_info['date_start']), date_create($period_info['date_end']));
				$day_count = $diff->days;

				$day = \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($period_info['date_start']);

				$header_date_data = [];

				for ($i = 0; $i <= $day_count; $i++) {
					$header_date_data[] = $day + $i;
				}

				$header_data = array_merge($header_data, $header_date_data);

				$header_count = count($header_data);

				if ($header_data != array_slice($schedule_data[2], 0, $header_count)) {
					$this->error = $this->language->get('error_schedule');

					break;
				}

				$schedule_data = array_slice($schedule_data, 3);

				$import_success_count = 0;
				$import_count = 0;

				$user_division_id = $this->user->getCustomerDepartmentId();

				$this->log($this->language->get('text_importing'));

				$this->load->model('presence/schedule');

				foreach ($schedule_data as $schedule) {
					if (!$schedule[0]) {
						break;
					}

					$import_count++;

					$schedule = array_combine($header_data, array_slice($schedule, 0, $header_count));

					$customer_info = $this->model_common_payroll->getCustomer($schedule['ID']);

					if (trim($customer_info['firstname']) . ' [' . trim($customer_info['lastname']) . ']' != trim($schedule['NAMA'])) {
						$this->log(sprintf($this->language->get('error_customer'), $schedule['NAMA']));

						continue;
					}

					if ($user_division_id && $user_division_id != $customer_info['customer_department_id']) {
						$this->log(sprintf($this->language->get('error_customer_division'), $schedule['NAMA']));

						continue;
					}

					$range_date = array(
						'start'	=> $period_info['date_start'],
						'end'	=> $period_info['date_end']
					);

					$schedules_info = $this->model_presence_schedule->getSchedules($schedule['ID'], $range_date);

					if ($schedules_info) {
						$this->log(sprintf($this->language->get('error_schedule_exist'), $schedule['NAMA']));

						continue;
					}

					$post_data = [];

					foreach (array_slice($schedule, 6, $header_count - 6, true) as $date => $value) {
						if (!isset($schedule_type[$value])) {
							$this->log(sprintf($this->language->get('error_schedule_type_none'), $schedule['NAMA']));

							continue 2;
						}

						$schedule_date = date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($date));

						if ($schedule_date >= date('Y-m-d')) {
							$post_data['schedule' . $schedule_date] = $schedule_type[$value];
						}
					}

					$this->model_presence_schedule->editSchedule($presence_period_id, $schedule['ID'], $post_data);

					$import_success_count++;

					$this->log(sprintf($this->language->get('text_schedule_imported'), $schedule['NAMA']));
				}

				$text_success = sprintf($this->language->get('text_success_import'), $import_success_count, $import_count - $import_success_count);

				$this->session->data['success'] = $text_success;

				$this->log($text_success);

				break;

			default:
				break;
		}

		if ($this->error) {
			$this->session->data['error'] = $this->error;
		}

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		$this->response->redirect($this->url->link('presence/schedule/import', 'token=' . $this->session->data['token'] . $url, true));
	}

	protected function getForm()
	{
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
			'text_inactive',
			'column_code',
			'column_duration',
			'column_time_start',
			'column_time_end',
			'column_t',
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		if (($customer_info['date_start'] > $period_info['date_end']) || ($customer_info['date_end'] && ($customer_info['date_end'] < $period_info['date_start']))) {
			$data['inactive'] = true;
		} else {
			$data['inactive'] = false;

			$this->load->model('presence/absence');
			$this->load->model('presence/exchange');
			$this->load->model('overtime/overtime');

			$schedules_data = $this->model_presence_schedule->getFinalSchedules($presence_period_id, $customer_id, $range_date);

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
			$data['list_days'] = explode(' ', $this->language->get('text_days'));

			$date_diff = date_diff(date_create($period_info['date_start']), date_create($period_info['date_end']));
			$date_start = strtotime($period_info['date_start']);

			$week_day_start = date('w', $date_start);

			$days_in_month = $date_diff->format('%a');

			$data['total_week'] = ceil(($days_in_month + $week_day_start + 1) / 7);

			$data['calendar'] = array();

			// array "blank" days until the first of the current week //

			$counter = -$week_day_start;

			for ($week = 0; $week < $data['total_week']; $week++) {
				for ($day = 0; $day < 7; $day++) {
					if ($counter >= 0 && $counter <= $days_in_month) {
						$key_date = date('Y-m-d', strtotime('+' . $counter . ' day', $date_start));

						$schedule_type_code = '-';
						$presence_status = '-';
						$time_login = '';
						$time_logout = '';
						$duration = '...';

						if (isset($this->request->post['schedule' . $key_date])) {
							$schedule_type_id = $this->request->post['schedule' . $key_date];
							$bg_class = '';
							$note = '';
						} elseif (!empty($schedules_data[$key_date])) {
							$schedule_type_id = $schedules_data[$key_date]['schedule_type_id'];
							$schedule_type_code = $schedules_data[$key_date]['schedule_type'] . ($schedules_data[$key_date]['time_in'] != '0000-00-00 00:00:00' ? ' (' . date('H:i', strtotime($schedules_data[$key_date]['time_in'])) . '-' . date('H:i', strtotime($schedules_data[$key_date]['time_out'])) . ')' : $data['text_off']);
							$presence_status = $schedules_data[$key_date]['presence_status'];
							$time_login = ($schedules_data[$key_date]['time_login'] != '0000-00-00 00:00:00') ? date('H:i:s', strtotime($schedules_data[$key_date]['time_login'])) : '...';
							$time_logout = ($schedules_data[$key_date]['time_logout'] != '0000-00-00 00:00:00') ? date('H:i:s', strtotime($schedules_data[$key_date]['time_logout'])) : '...';
							$bg_class = !empty($schedules_data[$key_date]['bg_class']) ? $schedules_data[$key_date]['bg_class'] : 'info';
							$note = !empty($schedules_data[$key_date]['note']) ? $schedules_data[$key_date]['note'] : '';

							if ($schedules_data[$key_date]['time_login'] != '0000-00-00 00:00:00' && $schedules_data[$key_date]['time_logout'] != '0000-00-00 00:00:00') {
								$diff = date_diff(date_create($schedules_data[$key_date]['time_login']), date_create($schedules_data[$key_date]['time_logout']));
								$duration = $diff->format($this->language->get('info_duration'));
							}
						} else {
							$schedule_type_id = 0;
							$bg_class = 'warning';
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
							'schedule_type_code' => $schedule_type_code,
							'presence_status'	=> $presence_status,
							'time_login'		=> $time_login,
							'time_logout'		=> $time_logout,
							'duration'			=> $duration,
							'bg_class'			=> $bg_class,
							'note'				=> $note,
							'locked'			=> $locked
						);
					}

					$counter++;
				}
			}

			# Presence Summary
			$presence_summary = $this->model_presence_schedule->calculatePresenceSummary($presence_period_id, $customer_id, $schedules_data);

			if (!isset($presence_summary['additional'])) {
				$presence_summary['additional'] = [];
			}

			$data['presence_summary']['hke'] = $presence_summary['total']['hke'];
			$data['presence_summary'] = array_merge($data['presence_summary'], $presence_summary['primary'], $presence_summary['additional']);

			$data['presence_summary_width'] = (100 / count($data['presence_summary'])) . '%';

			$data['late_summary']['t'] = $presence_summary['total']['t'];
			$data['late_summary'] = array_merge($data['late_summary'], $presence_summary['secondary']);

			//Legend
			$this->load->model('presence/schedule_type');
			$data['schedule_types'] = $this->model_presence_schedule_type->getScheduleTypesByLocationGroup($customer_info['location_id'], $customer_info['customer_group_id']);
			//End Legend
		}

		$data['presence_period_id'] = $presence_period_id;
		$data['customer_id'] = $customer_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/schedule_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'presence/schedule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ($this->user->getCustomerDepartmentId()) {
			$customer_info = $this->model_common_payroll->getCustomer($this->request->get['customer_id']);

			if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
				$this->error['warning'] = $this->language->get('error_customer_department');
			}
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'pending, processing')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('modify', 'presence/schedule')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ($this->user->getCustomerDepartmentId()) {
			$customer_info = $this->model_common_payroll->getCustomer($this->request->get['customer_id']);

			if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
				$this->error['warning'] = $this->language->get('error_customer_department');
			}
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

	protected function validateRecap()
	{
		if (!$this->user->hasPermission('modify', 'presence/schedule') || $this->user->getCustomerDepartmentId()) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'processing')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		if (!$this->model_presence_schedule->getScheduleCustomersCount($this->request->get['presence_period_id'])) {
			$this->error['warning'] = $this->language->get('error_not_found');
		}

		# Validasi jika periode belum berakhir
		// $period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);
		// if (strtotime('today') < strtotime($period_info['date_end'])) {
		// $this->error['warning'] = $this->language->get('error_date_end');
		// }

		// if (!isset($this->request->post['selected'])) {
		// 	$this->error['warning'] = $this->language->get('error_not_selected');
		// }

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
				'filter_name'			=> $filter_name,
				'start'      			=> 0,
				'limit'      			=> 15
			);

			$results = $this->model_presence_presence->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'   => $result['customer_id'],
					'name'			=> strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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

	public function applySchedule()
	{
		$this->load->language('presence/schedule');

		$json = array();

		if (!$this->user->hasPermission('modify', 'presence/schedule') || $this->user->getCustomerDepartmentId()) {
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

	public function log($message)
	{
		$log = new Log('schedule.log');
		$log->write($message);
	}

	public function clear()
	{
		$this->load->language('presence/schedule');

		if (!$this->user->hasPermission('modify', 'presence/schedule')) {
			$this->session->data['error'] = $this->language->get('error_permission');
		} else {
			$file = DIR_LOGS . 'schedule.log';

			$handle = fopen($file, 'w+');

			fclose($handle);

			$this->session->data['success'] = $this->language->get('text_success');
		}

		$url = '';
		$url .= '&presence_period_id=' . $this->request->get['presence_period_id'];

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		$this->response->redirect($this->url->link('presence/schedule/import', 'token=' . $this->session->data['token'] . $url, true));
	}
}
