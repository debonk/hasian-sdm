<?php
class ControllerReportPayrollTax extends Controller {
	public function index() {
		$this->load->language('report/payroll_tax');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/presence_period');

		$presence_periods = $this->model_presence_presence_period->getPresencePeriods();

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = $presence_periods[0]['presence_period_id'];
		}
	
		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/payroll_tax', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['export'] = $this->url->link('report/payroll_tax/export', 'token=' . $this->session->data['token'] . $url, true);

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		$data['period_info'] = date($this->language->get('date_format_m_y'), strtotime($period_info['period']));

		$language_items = array(
			'heading_title',
			'text_list',
			'button_filter',
			'button_export'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$data['presence_periods'] = $presence_periods;
		$data['presence_period_id'] = $presence_period_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/payroll_tax', $data));
	}

	public function report() {
		$this->load->language('report/payroll_tax');

		$this->load->model('report/payroll');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}
		
 		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer';
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

		$data['taxes'] = array();
		$result_count = 0;
		
		$this->load->model('common/payroll');
		
		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed');

		if ($period_status) {
			$filter_data = array(
				'code'		=> 'insurance, overtime, incentive, dayoff, cutoff', //component yang ikut dalam perhitungan PPh21
				'sort'      => $sort,
				'order'     => $order,
				'start'     => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'     => $this->config->get('config_limit_admin')
				// 'grouped'	=> 1
			);
			
			$results = $this->model_report_payroll->getTaxes($presence_period_id, $filter_data);
			
			foreach ($results as $result) {
				$data['taxes'][] = array(
					// 'customer_id' 		=> $result['customer_id'],
					'customer'			=> $result['customer'],
					'gender' 			=> $result['gender_code'],
					'marriage_status'	=> $result['marriage_status'],
					'customer_group'	=> $result['customer_group'],
					'location'			=> $result['location'],
					'npwp'				=> $result['npwp'],
					'npwp_address'		=> strlen($result['npwp_address']) > 30 ? substr($result['npwp_address'], 0, 28) . '..' : $result['npwp_address'],
					'tax_value'			=> $this->currency->format($result['tax_value'], $this->config->get('config_currency'))
				);
			}
			
			$result_count = $this->model_report_payroll->getPayrollsCount($presence_period_id);
		}
	
		$language_list = array(
			'text_no_results',
			'column_customer',
			'column_gender',
			'column_marriage_status',
			'column_customer_group',
			'column_location',
			'column_npwp',
			'column_npwp_address',
			'column_tax_value'
		);
		foreach ($language_list as $item) {
			$data[$item] = $this->language->get($item);
		}

		$data['token'] = $this->session->data['token'];

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_customer'] = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_customer_group'] = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $result_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($result_count - $this->config->get('config_limit_admin'))) ? $result_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $result_count, ceil($result_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('report/payroll_tax_report', $data));
	}
	
	public function export() {
		$this->load->language('report/payroll_tax');

		$this->load->model('report/payroll');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}
		
 		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$this->load->model('common/payroll');
		
		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed');

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		
		if ($period_status && $this->user->hasPermission('modify', 'report/payroll_tax')) {
			$language_items = array(
				'heading_title',
				'column_no',
				'column_customer',
				'column_nik',
				'column_id_card_address',
				'column_gender',
				'column_marriage_status',
				'column_customer_group',
				'column_npwp',
				'column_npwp_address',
				'column_location',
				'column_net_salary',
				'column_company',
				'column_tax_value'
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			$period = date('M_Y', strtotime($period_info['period']));

			$output = '';

			$this->load->model('component/insurance');
			$titles = $this->model_component_insurance->getTitles();

			$output = $data['heading_title'] . ' - ' . $period . '|||||||||' . implode('||', $titles) . "\n";
			$output .= $data['column_no'] . '|' . $data['column_customer'] . '|' . $data['column_nik'] . '|' . $data['column_id_card_address'] . '|' . $data['column_gender'] . '|' . $data['column_marriage_status'] . '|' . $data['column_customer_group'] . '|' . $data['column_npwp'] . '|' . $data['column_npwp_address'] . '|' . $data['column_location'] . '|' . $data['column_net_salary'];
			foreach ($titles as $title) {
				$output .= '|' . $data['column_company'] . '|' . $data['column_customer'];
			}
			
			$output .= '|' . $data['column_tax_value'];
			
			$output = str_replace('|', "\t", $output);
			
			$filter_data = array(
				'code'		=> 'insurance, overtime, incentive, dayoff, cutoff', //component yang ikut dalam perhitungan PPh21
				'sort'      => $sort,
				'order'     => $order
			);

			$results = $this->model_report_payroll->getTaxes($presence_period_id, $filter_data);

			$no = 1;
			
			foreach ($results as $result) {
				$component_value = array();
				$component_data = array();
				
				$value = '';
				$value .= $no . '|' . $result['customer'] . '|\'' . $result['nik'] . '|' . $result['id_card_address'] . '|' . $result['gender_code'] . '|' . $result['marriage_status'] . '|' . $result['customer_group'] . '|' . $result['npwp'] . '|' . $result['npwp_address'] . '|' . $result['location'] . '|';
				
				$components_info = $this->model_report_payroll->getComponents($presence_period_id, $result['customer_id'], 'insurance');//Karena hanya insurance yg dirinci.
				
				foreach ($components_info as $component_info) {
					$component_value[$component_info['title']][$component_info['type']] = $component_info['value'];
				}

				$sub_value = '';
				$salary = $result['salary'];

				foreach ($titles as $title) {
					if (!empty($component_value[$title][1])) {
						$component_data[$title][1] = $component_value[$title][1];
					} else {
						$component_data[$title][1] = 0;
					}
					
					if (!empty($component_value[$title][0])) {
						$component_data[$title][0] = -($component_value[$title][1] + $component_value[$title][0]);
					} else {
						$component_data[$title][0] = 0;
					}
					
					$sub_value .= '|' . $component_data[$title][1] . '|' . $component_data[$title][0];

					$salary += $component_data[$title][0];
				}
				
				$value .= $salary . $sub_value . '|' . $result['tax_value'];
				
				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\',	$value);
				$value = str_replace('\'', '\\\'',	$value);
				$value = str_replace('\\\n', '\n',	$value);
				$value = str_replace('\\\r', '\r',	$value);
				$value = str_replace('\\\t', '\t',	$value);
				$value = str_replace('|', "\t",	$value);
				$value = stripslashes($value);
				
				$output .= "\n" . $value;
				// $output .= "<br>" . $value;
				
				$no++;
			}
			
			$filename = date('Ym', strtotime($period_info['period'])) . '_Payroll_Tax_' . $period;
			
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.xls');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
			// echo $output;
		} else {
		
			$this->response->redirect($this->url->link('report/payroll_tax', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true));
		}
	}	
}
