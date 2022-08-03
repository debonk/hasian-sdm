<?php
class ControllerPayrollPayroll extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payroll/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll');

		$this->getPeriod();
	}

	public function info() {
		$this->load->language('payroll/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$payroll_period = $this->model_common_payroll->getPeriod($presence_period_id);
		if ($payroll_period && $this->registry->has('framework_load')) {
			$this->getList();
		} else {
			return new Action('error/not_found');
		}
	}

	public function edit() {
		$this->load->language('payroll/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll');

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

		$payroll_period = $this->model_common_payroll->getPeriod($presence_period_id);
		$customer_info = $this->model_common_payroll->checkCustomer($customer_id);
		
		if ($payroll_period && $customer_info) {
			$this->getForm();
		} else {
			return new Action('error/not_found');
		}
	}

	public function add() {
		$this->load->language('payroll/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll');
		$this->load->model('presence/presence');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$url = '';

		if (isset($this->request->get['presence_period_id'])) {
			$url .= '&presence_period_id=' . $this->request->get['presence_period_id'];
		}

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
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
			'href' => $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_add'),
			'href' => $this->url->link('payroll/payroll/add', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['back'] = $this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . $url, true);
		
		$data['customers'] = array();

		$results = $this->model_payroll_payroll->getBlankPayrollCustomers($presence_period_id);

		foreach ($results as $result) {
			$data['customers'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'name_nip' 			=> $result['name'] . ' - NIP: ' . $result['nip']
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_add'] = $this->language->get('text_add');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_select'] = $this->language->get('button_select');

		$data['token'] = $this->session->data['token'];
		
		$data['presence_period_id'] = $presence_period_id;
		$data['url'] = $url;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_add', $data));
	}

	public function delete() {
		$this->load->language('payroll/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll');

		if (isset($this->request->get['presence_period_id']) && isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				$this->model_payroll_payroll->deletePayroll($this->request->get['presence_period_id'], $customer_id);
				$this->model_payroll_payroll->deletePayrollComponent($this->request->get['presence_period_id'], $customer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['presence_period_id'])) {
				$url .= '&presence_period_id=' . $this->request->get['presence_period_id'];
			}

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
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

			// Check: Jika data payroll kosong, status kembali ke 'submitted'
			$payroll_total = $this->model_payroll_payroll->getTotalPayrolls($this->request->get['presence_period_id']);
			if (!$payroll_total) {
				$this->model_common_payroll->setPeriodStatus($this->request->get['presence_period_id'], 'submitted');
				$this->response->redirect($this->url->link('payroll/payroll', 'token=' . $this->session->data['token'] . $url, true));
			} else {
				$this->response->redirect($this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . $url, true));
			}
		}

		$this->getList();
	}

	protected function getPeriod() {
		if (isset($this->request->get['filter_payroll_status'])) {
			$filter_payroll_status = $this->request->get['filter_payroll_status'];
		} else {
			$filter_payroll_status = null;
		}

		if (isset($this->request->get['filter_period'])) {
			$filter_period = $this->request->get['filter_period'];
		} else {
			$filter_period = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_payroll_status'])) {
			$url .= '&filter_payroll_status=' . $this->request->get['filter_payroll_status'];
		}

		if (isset($this->request->get['filter_period'])) {
			$url .= '&filter_period=' . $this->request->get['filter_period'];
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
			'href' => $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'], true)
		);

		$filter_data = array(
			'filter_payroll_status'=> $filter_payroll_status,
			'filter_period' 	   => $filter_period,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$data['payroll_periods'] = array();

		$payroll_period_total = $this->model_payroll_payroll->getTotalPayrollPeriods($filter_data);

		$results = $this->model_payroll_payroll->getPayrollPeriods($filter_data);

		foreach ($results as $result) {
			$total_payroll = $this->model_payroll_payroll->getTotalPayroll($result['presence_period_id']);
			$net_salary = $total_payroll['total_earning'] - $total_payroll['total_deduction'];

			//Payroll Component
			$component_info = $this->model_payroll_payroll->getPayrollComponentTotal($result['presence_period_id'],0,'type');
			
			$component_grandtotal = $component_info['grandtotal'];
			
			//Period Status Check
			$payroll_status_check = $this->model_common_payroll->checkPeriodStatus($result['presence_period_id'], 'submitted');
			$payroll_total = $this->model_payroll_payroll->getTotalPayrolls($result['presence_period_id']);
			
			$data['payroll_periods'][] = array(
				'presence_period_id'=> $result['presence_period_id'],
				'period'        	=> date($this->language->get('date_format_m_y'), strtotime($result['period'])),
				'payroll_status' 	=> $result['payroll_status'],
				'date_start'    	=> date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'    		=> date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'net_salary' 		=> $this->currency->format($net_salary, $this->config->get('config_currency')),
				'grandtotal' 		=> $this->currency->format($net_salary + $component_grandtotal, $this->config->get('config_currency')),
				'generate_check' 	=> $payroll_status_check && !$payroll_total,
				'view_check'		=> $payroll_total,
				'view'          	=> $this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'], true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_period_list'] = $this->language->get('text_period_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['column_period'] = $this->language->get('column_period');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_payroll_status'] = $this->language->get('column_payroll_status');
		$data['column_all_net_salary'] = $this->language->get('column_all_net_salary');
		$data['column_all_grandtotal'] = $this->language->get('column_all_grandtotal');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_payroll_status'] = $this->language->get('entry_payroll_status');
		$data['entry_period'] = $this->language->get('entry_period');

		$data['button_filter'] = $this->language->get('button_filter');
		$data['button_generate'] = $this->language->get('button_generate');
		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_payroll_status'])) {
			$url .= '&filter_payroll_status=' . $this->request->get['filter_payroll_status'];
		}

		if (isset($this->request->get['filter_period'])) {
			$url .= '&filter_period=' . $this->request->get['filter_period'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$pagination = new Pagination();
		$pagination->total = $payroll_period_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_period_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($payroll_period_total - $this->config->get('config_limit_admin'))) ? $payroll_period_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $payroll_period_total, ceil($payroll_period_total / $this->config->get('config_limit_admin')));

		$data['filter_payroll_status'] = $filter_payroll_status;
		$data['filter_period'] = $filter_period;

		$this->load->model('localisation/payroll_status');
		$data['payroll_statuses'] = $this->model_localisation_payroll_status->getPayrollStatuses();

//		$data['store_url'] = $this->request->server['HTTPS'] ? HTTPS_CATALOG : HTTP_CATALOG;

		// API login
/*		$this->load->model('user/api');

		$api_info = $this->model_user_api->getApi($this->config->get('config_api_id'));

		if ($api_info) {
			$data['api_id'] = $api_info['api_id'];
			$data['api_key'] = $api_info['key'];
			$data['api_ip'] = $this->request->server['REMOTE_ADDR'];
		} else {
			$data['api_id'] = '';
			$data['api_key'] = '';
			$data['api_ip'] = '';
		}
*/
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_period', $data));
	}

	public function generate() {
		$this->load->language('payroll/payroll');

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll');
		$this->load->model('presence/presence');

		$json = array();

		if (!$this->user->hasPermission('modify', 'payroll/payroll')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['presence_period_id'])) {
				$presence_period_id = $this->request->get['presence_period_id'];
			} else {
				$presence_period_id = 0;
			}

			$payroll_total = $this->model_payroll_payroll->getTotalPayrolls($presence_period_id);
			$payroll_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'submitted');

			if ($payroll_total) {
				$json['error'] = $this->language->get('error_data');
			} elseif (!$payroll_status_check) {
				$json['error'] = $this->language->get('error_status');
			} else {
				$payroll_data = array();
				$component_data = array();

				$filter_data = array(
					'filter_payroll_include'=> 1,
					'presence_period_id'	=> $presence_period_id
				);
				
				$results = $this->model_presence_presence->getCustomers($filter_data);
				
				$result_total = $this->model_presence_presence->getTotalCustomers($filter_data);
				
				$count = 0;
				
				foreach ($results as $result) {
					$payroll_calculation = $this->model_payroll_payroll->getPayrollDetail($presence_period_id, $result['customer_id']);
			
					if ($payroll_calculation['payroll_basic_check'] && $payroll_calculation['presence_summary_check']) {
						$payroll_data = array(
							'presence_period_id'=> $payroll_calculation['presence_period_id'],
							'customer_id' 		=> $payroll_calculation['customer_id'],
							'gaji_pokok'    	=> $payroll_calculation['gaji_pokok'],
							'tunj_jabatan'  	=> $payroll_calculation['tunj_jabatan'],
							'tunj_hadir'     	=> $payroll_calculation['tunj_hadir'],
							'tunj_pph'    		=> $payroll_calculation['tunj_pph'],
							'uang_makan'    	=> $payroll_calculation['uang_makan'],
							'total_uang_makan'	=> $payroll_calculation['total_uang_makan'],
							'pot_sakit'			=> $payroll_calculation['pot_sakit'],
							'pot_bolos'			=> $payroll_calculation['pot_bolos'],
							'pot_tunj_hadir'	=> $payroll_calculation['pot_tunj_hadir'],
							'pot_gaji_pokok'	=> $payroll_calculation['pot_gaji_pokok'],
							'pot_terlambat'		=> $payroll_calculation['pot_terlambat']
						);
						
						$this->model_payroll_payroll->addPayroll($presence_period_id, $result['customer_id'], $payroll_data);
						$count++;
						
						// Payroll Components
						$components = $this->model_payroll_payroll->calculatePayrollComponent($presence_period_id, $result['customer_id']);

						foreach ($components as $component) {
							foreach ($component['quote'] as $quote) {
								$component_data = array(
									'code' 				=> $component['code'],
									'item' 				=> $quote['item'],
									'title' 			=> $quote['title'],
									'value' 			=> $quote['value'],
									'type' 				=> $quote['type'],
									'sort_order' 		=> $component['sort_order']
								);
								
								if ($component_data) {
									$this->model_payroll_payroll->addPayrollComponent($presence_period_id, $result['customer_id'], $component_data);
								}
							}
						}
					}
				}

				if ($count) {
					$this->model_common_payroll->setPeriodStatus($presence_period_id, 'generated');

					$json['success'] = sprintf($this->language->get('text_success_generated'), $count, $result_total);
				} else {
					$json['error'] = $this->language->get('error_not_found');
				}
			}
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function getList() {
/* 		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}
 */
		$presence_period_id = $this->request->get['presence_period_id'];
		
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
			'href' => $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('payroll/payroll/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('payroll/payroll/delete', 'token=' . $this->session->data['token'] . $url, true);
		$data['back'] = $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'], true);

		$data['payroll_status_check'] = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated');
		
		$data['information'] = null;
		if ($data['payroll_status_check']) {
			$blank_data = $this->model_payroll_payroll->getBlankPayrollCustomers($presence_period_id);
			$blank_data_total = count($blank_data);

			if ($blank_data_total) {
				$data['information'] = sprintf($this->language->get('info_no_data'), $blank_data_total);
			}
		}
		
		$data['customers'] = array();

		$filter_data = array(
			'filter_name'	   	   => $filter_name,
			'filter_customer_group_id' => $filter_customer_group_id,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		//Payroll Components
		$data['component_codes'] = $this->model_payroll_payroll->getPayrollComponentCodes($presence_period_id);
	
		$component_total = array('net_salary' => 0, 'grandtotal' => 0);
		
		foreach ($data['component_codes'] as $code) {
			$data['text_component'][$code] = $this->language->get('text_' . $code);
			$component_total[$code] = 0;
		}
		
		$customer_total = $this->model_payroll_payroll->getTotalPayrolls($presence_period_id, $filter_data);

		$results = $this->model_payroll_payroll->getPayrolls($presence_period_id, $filter_data);
		
		//Get absence Note
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$this->load->model('presence/absence');
		//End GetNote Block
		
		foreach ($results as $result) {
			$earning = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + $result['total_uang_makan'];
			$deduction = $result['pot_sakit'] + $result['pot_bolos'] + $result['pot_tunj_hadir'] + $result['pot_gaji_pokok'] + $result['pot_terlambat'];
			
			$net_salary = $earning - $deduction;
			$component_total['net_salary'] += $net_salary;

			//Payroll Component
			$component_data = array();
			
			$component_info = $this->model_payroll_payroll->getPayrollComponentTotal($presence_period_id, $result['customer_id'], 'code');

			foreach ($data['component_codes'] as $code) {
				$component_data[$code] = $this->currency->format($component_info[$code], $this->config->get('config_currency'));
				$component_total[$code] += $component_info[$code];
			}

			$component_total['grandtotal'] += $component_info['grandtotal'];
			
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
			
			$note = implode(', ', array_filter(array_column($absences_info, 'note')));
			
			$data['customers'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'nip' 				=> $result['nip'],
				'name' 				=> $result['name'],
				'customer_group' 	=> $result['customer_group'],
				'net_salary'    	=> $this->currency->format($net_salary, $this->config->get('config_currency')),
				'component_data'	=> $component_data,
				'grandtotal'    	=> $this->currency->format($net_salary + $component_info['grandtotal'], $this->config->get('config_currency')),
				'note' 				=> strlen($note) > 30 ? substr($note, 0, 28) . '..' : $note,
				'edit'          	=> $this->url->link('payroll/payroll/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true)
			);
		}
		
		foreach ($data['component_codes'] as $code) {
			$data['component_total'][$code] = $this->currency->format($component_total[$code], $this->config->get('config_currency'));
		}
		$data['component_total']['net_salary'] = $this->currency->format($component_total['net_salary'], $this->config->get('config_currency'));
		$data['component_total']['grandtotal'] = $this->currency->format($component_total['net_salary'] + $component_total['grandtotal'], $this->config->get('config_currency'));
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_confirm',
			'text_no_results',
			'text_all_customer_group',
			'text_approve_confirm',
			'text_loading',
			'text_subtotal',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_action',
			'column_net_salary',
			'column_component',
			'column_grandtotal',
			'column_note',
			'entry_name',
			'entry_customer_group',
			'button_add',
			'button_edit',
			'button_delete',
			'button_back',
			'button_filter',
			'button_payroll_approve'
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

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_nip'] = $this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
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
		$pagination->url = $this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

		$data['presence_period_id'] = $presence_period_id;
		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

//		$this->load->model('setting/store');

//		$data['stores'] = $this->model_setting_store->getStores();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		
		$language_items = array(
			'heading_title',
			'text_loading',
			'text_no_results',
			'text_confirm',
			'text_presence_summary',
			'text_payroll_basic',
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
			'column_basic_date_added',
			'column_gaji_pokok',
			'column_tunj_jabatan',
			'column_tunj_hadir',
			'column_tunj_pph',
			'column_uang_makan',
			'column_gaji_dasar',
			'column_action',
			'column_hke',
			'button_payroll_update',
			'button_save',
			'button_back',
			'button_edit',
			'button_view',
			'button_override'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

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
			'href' => $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $this->url->link('payroll/payroll/edit', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['back'] = $this->url->link('payroll/payroll/info', 'token=' . $this->session->data['token'] . $url, true);

		//Start Rincian Gaji
		$payroll_calculation = $this->model_payroll_payroll->getPayrollDetail($presence_period_id, $customer_id);
		
 		$presence_items = array(
			'h',
			's',
			'i',
			'ns',
			'ia',
			'a',
			'c',
			't1',
			't2',
			't3'
		);
		foreach ($presence_items as $presence_item) {
			$data['total_item'][$presence_item] = $payroll_calculation[$presence_item];
		}
		
		$data['hke'] = $payroll_calculation['hke'];

		$data['gaji_pokok']     		= $this->currency->format($payroll_calculation['gaji_pokok'], $this->config->get('config_currency'));
		$data['tunj_jabatan']   		= $this->currency->format($payroll_calculation['tunj_jabatan'], $this->config->get('config_currency'));
		$data['tunj_hadir']     		= $this->currency->format($payroll_calculation['tunj_hadir'], $this->config->get('config_currency'));
		$data['tunj_pph']       		= $this->currency->format($payroll_calculation['tunj_pph'], $this->config->get('config_currency'));
		$data['uang_makan']     		= $this->currency->format($payroll_calculation['uang_makan'], $this->config->get('config_currency'));
		
		$gaji_dasar = $payroll_calculation['gaji_pokok'] + $payroll_calculation['tunj_jabatan'] + $payroll_calculation['tunj_hadir'] + $payroll_calculation['tunj_pph'] + $payroll_calculation['uang_makan'] * 25;
		$data['gaji_dasar']     		= $this->currency->format($gaji_dasar, $this->config->get('config_currency'));
		$data['basic_date_added']		= date($this->language->get('date_format_jMY'), strtotime($payroll_calculation['date_added']));
		$data['payroll_basic_edit'] 	= $this->url->link('payroll/payroll_basic/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id, true);

		if ($payroll_calculation['full_overtimes_count']) {
			$data['hke'] .= ' (' . $payroll_calculation['full_overtimes_count'] . ' ' . $this->language->get('code_full_overtime') . ')';
		}

		$data['presence_summary_edit'] 	= $this->url->link('presence/presence/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . '&presence_period_id=' . $presence_period_id, true);

		$data['payroll_basic_check']    = $payroll_calculation['payroll_basic_check'];
		$data['presence_summary_check'] = $payroll_calculation['presence_summary_check'];

		$data['payroll_status_check'] = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated');
		
		$data['presence_period_id'] = $presence_period_id;
		$data['customer_id'] = $customer_id;
		
		$data['presence_items'] = $presence_items;
		
		$data['token'] = $this->session->data['token'];

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_form', $data));
	}
	
	public function payrollDetailInfo() {
		$this->load->language('payroll/payroll');

		$this->load->model('common/payroll');
		$this->load->model('payroll/payroll');

		$language_items = array(
			'text_payroll_calculation',
			'text_loading',
			'text_no_results',
			'text_gaji_pokok',
			'text_tunj_jabatan',
			'text_tunj_hadir',
			'text_tunj_pph',
			'text_pot_tunj_hadir',
			'text_pot_gaji_pokok',
			'text_total_earning',
			'text_total_deduction',
			'text_grandtotal',
			'column_earning',
			'column_deduction',
			'entry_overtime_type',
			'entry_description',
			'entry_schedule_type',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		$presence_period_id = $this->request->get['presence_period_id'];
		$customer_id = $this->request->get['customer_id'];
		
		$payroll_calculation = $this->model_payroll_payroll->getPayrollDetail($presence_period_id, $customer_id);
			
		$total_sakit			= $payroll_calculation['total_sakit'];
		$total_bolos   			= $payroll_calculation['total_bolos'];
		$total_t   				= $payroll_calculation['total_t'];
		
		$data['gaji_pokok']     		= $this->currency->format($payroll_calculation['gaji_pokok'], $this->config->get('config_currency'));
		$data['tunj_jabatan']   		= $this->currency->format($payroll_calculation['tunj_jabatan'], $this->config->get('config_currency'));
		$data['tunj_hadir']     		= $this->currency->format($payroll_calculation['tunj_hadir'], $this->config->get('config_currency'));
		$data['tunj_pph']       		= $this->currency->format($payroll_calculation['tunj_pph'], $this->config->get('config_currency'));
		$data['uang_makan']     		= $this->currency->format($payroll_calculation['uang_makan'], $this->config->get('config_currency'));
		$data['total_uang_makan']     	= $this->currency->format($payroll_calculation['total_uang_makan'], $this->config->get('config_currency'));

		$data['pot_sakit']     			= $this->currency->format($payroll_calculation['pot_sakit'], $this->config->get('config_currency'));
		$data['pot_bolos']     			= $this->currency->format($payroll_calculation['pot_bolos'], $this->config->get('config_currency'));
		$data['pot_tunj_hadir'] 		= $this->currency->format($payroll_calculation['pot_tunj_hadir'], $this->config->get('config_currency'));
		$data['pot_gaji_pokok'] 		= $this->currency->format($payroll_calculation['pot_gaji_pokok'], $this->config->get('config_currency'));
		$data['pot_terlambat']  		= $this->currency->format($payroll_calculation['pot_terlambat'], $this->config->get('config_currency'));

		$data['payroll_basic_check']    = $payroll_calculation['payroll_basic_check'];
		$data['presence_summary_check'] = $payroll_calculation['presence_summary_check'];
		
		if ($payroll_calculation['full_overtimes_count']) {
			$text_hke = '(' . $payroll_calculation['hke'] . ' - ' . $payroll_calculation['full_overtimes_count'] . ' (' . $this->language->get('code_full_overtime') . '))';
		} else {
			$text_hke = $payroll_calculation['hke'];
		}

		$data['text_total_uang_makan'] 	= sprintf($this->language->get('text_total_uang_makan'), $text_hke, $data['uang_makan']);
		$data['text_pot_sakit'] 		= sprintf($this->language->get('text_pot_sakit'), $total_sakit, $data['uang_makan']);
		$data['text_pot_bolos'] 		= sprintf($this->language->get('text_pot_bolos'), $total_bolos, $data['uang_makan']);
		$data['text_pot_terlambat'] 	= sprintf($this->language->get('text_pot_terlambat'), $total_t, $data['uang_makan']);

		$earning = $payroll_calculation['gaji_dasar'];
		$deduction = $payroll_calculation['total_potongan'];

		// Payroll Components
		$data['earning_components'] = array();
		$data['deduction_components'] = array();
		$result_component = array();
		
		$always_view['overtime'] = 1; //Masukkan ke setting. Tetap di view walaupun nilainya 0.
			
		if ($this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated')) {
			$components = $this->model_payroll_payroll->calculatePayrollComponent($presence_period_id, $customer_id);
			
			foreach ($components as $component) {
				if (isset($always_view[$component['code']]) && $always_view[$component['code']]) {
					foreach ($component['quote'] as $quote) {
						if ($quote['type']) {
							$earning += $quote['value'];
							
							$data['earning_components'][] = array(
								'title'	=> $quote['title'],
								'value' => $this->currency->format($quote['value'], $this->config->get('config_currency'))
							);
						} else {
							$deduction -= $quote['value'];
							
							$data['deduction_components'][] = array(
								'title'	=> $quote['title'],
								'value' => $this->currency->format(-$quote['value'], $this->config->get('config_currency'))
							);
						}
					}
					
				} else {
					foreach ($component['quote'] as $quote) {
						if (!isset($result_component[$quote['title']])) {
							$result_component[$quote['title']] = 0;
						}
						
						$result_component[$quote['title']] += $quote['value'];
					}
				}
			}
			
			if ($result_component) {
				foreach ($result_component as $key => $value) {
					if ($value < 0) {
						$deduction -= $value;
						
						$data['deduction_components'][] = array(
							'title'	=> $key,
							'value' => $this->currency->format(-$value, $this->config->get('config_currency'))
						);
						
					} elseif ($value > 0) {
						$earning += $value;
						
						$data['earning_components'][] = array(
							'title'	=> $key,
							'value' => $this->currency->format($value, $this->config->get('config_currency'))
						);
					}
				}
			}
			
		} else {
			$components = $this->model_payroll_payroll->getPayrollComponents($presence_period_id, $customer_id);
			
			foreach ($components as $component) {
				if (isset($always_view[$component['code']]) && $always_view[$component['code']]) {
					if ($component['type']) {
						$earning += $component['value'];
						
						$data['earning_components'][] = array(
							'title'	=> $component['title'],
							'value' => $this->currency->format($component['value'], $this->config->get('config_currency'))
						);
					} else {
						$deduction -= $component['value'];
						
						$data['deduction_components'][] = array(
							'title'	=> $component['title'],
							'value' => $this->currency->format(-$component['value'], $this->config->get('config_currency'))
						);
					}
						
				} else {
					if (!isset($result_component[$component['title']])) {
						$result_component[$component['title']] = 0;
					}
					
					$result_component[$component['title']] += $component['value'];
				}
			}
				
			if ($result_component) {
				foreach ($result_component as $key => $value) {
					if ($value < 0) {
						$deduction -= $value;
						
						$data['deduction_components'][] = array(
							'title'	=> $key,
							'value' => $this->currency->format(-$value, $this->config->get('config_currency'))
						);
					} elseif ($value > 0) {
						$earning += $value;
						
						$data['earning_components'][] = array(
							'title'	=> $key,
							'value' => $this->currency->format($value, $this->config->get('config_currency'))
						);
					}
				}
			}
		}
		
		$data['earning']     	= $this->currency->format($earning, $this->config->get('config_currency'));
		$data['deduction']     	= $this->currency->format($deduction, $this->config->get('config_currency'));
		$data['grandtotal']  	= $this->currency->format($earning - $deduction, $this->config->get('config_currency'));
		
		$this->response->setOutput($this->load->view('payroll/payroll_detail_info', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'payroll/payroll')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->post['presence_period_id'], 'generated')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		$customer_info = $this->model_common_payroll->getCustomer($this->request->post['customer_id']);
		$period_info = $this->model_common_payroll->getPeriod($this->request->post['presence_period_id']);
		
		if (empty($customer_info)) {
			$this->error['warning'] = $this->language->get('error_not_found');
		} elseif (!$customer_info['status'] || !$customer_info['payroll_include']) {
			$this->error['warning'] = $this->language->get('error_payroll_include');
		} elseif ($customer_info['date_start'] >= $period_info['date_end'] || ($customer_info['date_end'] && $customer_info['date_end'] <= $period_info['date_start'])) {
			$this->error['warning'] = $this->language->get('error_inactive');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'payroll/payroll')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'generated')) {
			$this->error['warning'] = $this->language->get('error_status');
		}

		return !$this->error;
	}
	
	public function getPayroll() {
		$this->load->language('payroll/payroll');

		$this->load->model('payroll/payroll');

		$data['text_payroll_old'] = $this->language->get('text_payroll_old');
		
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_net_salary'] = $this->language->get('column_net_salary');
		$data['column_grandtotal'] = $this->language->get('column_grandtotal');

		if (isset($this->request->get['customer_id']) && isset($this->request->get['presence_period_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$payroll_info = $this->model_payroll_payroll->getPayroll($this->request->get['presence_period_id'], $this->request->get['customer_id']);
		}

		if (!empty($payroll_info)) {
			$earning = $payroll_info['gaji_pokok'] + $payroll_info['tunj_jabatan'] + $payroll_info['tunj_hadir'] + $payroll_info['tunj_pph'] + $payroll_info['total_uang_makan'];
			$deduction = $payroll_info['pot_sakit'] + $payroll_info['pot_bolos'] + $payroll_info['pot_tunj_hadir'] + $payroll_info['pot_gaji_pokok'] + $payroll_info['pot_terlambat'];
			
			$net_salary = $earning - $deduction;
			
			//Payroll Component
			$data['component_codes'] = $this->model_payroll_payroll->getPayrollComponentCodes($this->request->get['presence_period_id'], $this->request->get['customer_id']);
	
			foreach ($data['component_codes'] as $code) {
				$data['text_component'][$code] = $this->language->get('text_' . $code);
			}
			
			$components_info = $this->model_payroll_payroll->getPayrollComponentTotal($this->request->get['presence_period_id'], $this->request->get['customer_id'], 'code');

			foreach ($data['component_codes'] as $code) {
				$data['component_data'][$code] = $this->currency->format($components_info[$code], $this->config->get('config_currency'));
			}

			$grandtotal = $net_salary + $components_info['grandtotal'];
			
			$data['net_salary'] = $this->currency->format($net_salary, $this->config->get('config_currency'));
			$data['grandtotal'] = $this->currency->format($grandtotal, $this->config->get('config_currency'));
			$data['payroll_date_added']	= date($this->language->get('date_format_jMY'), strtotime($payroll_info['date_added']));
		} else {
			$data['grandtotal'] = 0;
		}

		$this->response->setOutput($this->load->view('payroll/payroll_summary', $data));
	}

	public function addPayroll() {
		$this->load->language('payroll/payroll');

		$this->load->model('common/payroll');
		
		$json = array();

		if (!$this->validateForm()) {
			$json['error'] = $this->error['warning'];
		} else {
			$this->load->model('payroll/payroll');
			
			$this->model_payroll_payroll->deletePayroll($this->request->post['presence_period_id'], $this->request->post['customer_id']);
			$this->model_payroll_payroll->deletePayrollComponent($this->request->post['presence_period_id'], $this->request->post['customer_id']);

			$payroll_calculation = $this->model_payroll_payroll->getPayrollDetail($this->request->post['presence_period_id'], $this->request->post['customer_id']);

			if ($payroll_calculation['payroll_basic_check'] && $payroll_calculation['presence_summary_check']) {
				$payroll_data = array(
					'presence_period_id'=> $payroll_calculation['presence_period_id'],
					'customer_id' 		=> $payroll_calculation['customer_id'],
					'gaji_pokok'    	=> $payroll_calculation['gaji_pokok'],
					'tunj_jabatan'  	=> $payroll_calculation['tunj_jabatan'],
					'tunj_hadir'     	=> $payroll_calculation['tunj_hadir'],
					'tunj_pph'    		=> $payroll_calculation['tunj_pph'],
					'uang_makan'    	=> $payroll_calculation['uang_makan'],
					'total_uang_makan'	=> $payroll_calculation['total_uang_makan'],
					'pot_sakit'			=> $payroll_calculation['pot_sakit'],
					'pot_bolos'			=> $payroll_calculation['pot_bolos'],
					'pot_tunj_hadir'	=> $payroll_calculation['pot_tunj_hadir'],
					'pot_gaji_pokok'	=> $payroll_calculation['pot_gaji_pokok'],
					'pot_terlambat'		=> $payroll_calculation['pot_terlambat']
				);
				
				$this->model_payroll_payroll->addPayroll($this->request->post['presence_period_id'], $this->request->post['customer_id'], $payroll_data);
				
				// Payroll Components
				$components = $this->model_payroll_payroll->calculatePayrollComponent($this->request->post['presence_period_id'], $this->request->post['customer_id']);

				foreach ($components as $component) {
					foreach ($component['quote'] as $quote) {
						$component_data = array(
							'code' 			=> $component['code'],
							'item' 			=> $quote['item'],
							'title' 		=> $quote['title'],
							'value' 		=> $quote['value'],
							'type' 			=> $quote['type'],
							'sort_order' 	=> $component['sort_order']
						);
						
						if ($component_data) {
							$this->model_payroll_payroll->addPayrollComponent($this->request->post['presence_period_id'], $this->request->post['customer_id'], $component_data);
						}
					}
				}
				$json['success'] = $this->language->get('text_success');
			} else {
				$json['error'] = $this->language->get('error_not_found');
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function approvePayroll() {
		$this->load->language('payroll/payroll');

		$json = array();

		if (!$this->user->hasPermission('modify', 'payroll/payroll')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->get['presence_period_id'])) {
				$presence_period_id = $this->request->get['presence_period_id'];
			} else {
				$presence_period_id = 0;
			}

			$this->load->model('payroll/payroll');
			$this->load->model('common/payroll');

			$payroll_total = $this->model_payroll_payroll->getTotalPayrolls($presence_period_id);
			$payroll_status_check = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated');

			if (!$payroll_total) {
				$json['error'] = $this->language->get('error_not_found');
			} elseif (!$payroll_status_check) {
				$json['error'] = $this->language->get('error_status');				
			} else {
				$total_payroll = $this->model_payroll_payroll->getTotalPayroll($presence_period_id);

				$grandtotal = $total_payroll['total_earning'] - $total_payroll['total_deduction'];
				
				//Payroll Component
				$total_component = $this->model_payroll_payroll->getPayrollComponentTotal($this->request->get['presence_period_id']);
				
				$grandtotal += $total_component['grandtotal'];
				
				$period_data = array(
					'total_payroll' 	=> $grandtotal
				);
				
				$this->load->model('common/payroll');
				$this->model_common_payroll->setPeriodStatus($presence_period_id, 'approved', $period_data);

				$json['success'] = $this->language->get('text_approve_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	// public function payrollInfo() {
	// 	$this->load->language('payroll/payroll');

	// 	$this->load->model('payroll/payroll');

	// 	$presence_period_id = $this->request->get['presence_period_id'];
		
	// 	$total_payroll = $this->model_payroll_payroll->getTotalPayroll($presence_period_id);
	// 	$net_salary = $total_payroll['total_earning'] - $total_payroll['total_deduction'];

	// 	$data['component_codes'] = $this->model_payroll_payroll->getPayrollComponentCodes($presence_period_id);

	// 	$total_component = $this->model_payroll_payroll->getPayrollComponentTotal($presence_period_id,0,'code');
	// 	$data['net_salary'] = $this->currency->format($net_salary, $this->config->get('config_currency'));
	// 	$data['grandtotal'] = $this->currency->format($net_salary + $total_component['grandtotal'], $this->config->get('config_currency'));
	// 	$data['total_customer'] = $total_payroll['total_customer'];

	// 	foreach ($data['component_codes'] as $code) {
	// 		$data['text_component'][$code] = $this->language->get('text_' . $code) . ' :';
	// 		$data['component'][$code] = $this->currency->format($total_component[$code], $this->config->get('config_currency'));
	// 	}
		
	// 	$data['text_net_salary'] = $this->language->get('column_net_salary') . ' :';
	// 	$data['text_grandtotal'] = $this->language->get('text_grandtotal') . ' :';
	// 	$data['text_total_customer'] = $this->language->get('text_total_customer') . ' :';

	// 	$data['text_payroll_info'] = $this->language->get('text_payroll_info');

	// 	$this->response->setOutput($this->load->view('payroll/payroll_info', $data));
	// }
}
