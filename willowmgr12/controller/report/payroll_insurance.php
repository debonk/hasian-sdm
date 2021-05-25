<?php
class ControllerReportPayrollInsurance extends Controller {
	public function index() {
		$this->load->language('report/payroll_insurance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/presence_period');

		$presence_periods = $this->model_presence_presence_period->getPresencePeriods();

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = $presence_periods[0]['presence_period_id'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/payroll_insurance', 'token=' . $this->session->data['token'], true)
		);

		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending, processing, submitted, generated');
		if ($period_status) {
			$data['information'] = $this->language->get('info_simulation');
		} else {
			$data['information'] = null;
		}
		
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		$data['period_info'] = date($this->language->get('date_format_m_y'), strtotime($period_info['period']));

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$data['presence_periods'] = $presence_periods;

		$data['presence_period_id'] = $presence_period_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/payroll_insurance', $data));
	}

	public function report() {
		$this->load->language('report/payroll_insurance');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$this->load->model('report/payroll');

		$data['insurances'] = array();
		$insurances_value = array();
		$insurances_data = array();
		$insurances_total = array();
		
		$this->load->model('common/payroll');
		
		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'pending, processing, submitted, generated');

		$this->load->model('component/insurance');
		$insurance_titles = $this->model_component_insurance->getTitles();

		if ($period_status && $this->config->get('insurance_status')) {
			$filter_data = array(
				'presence_period_id'   		=> $presence_period_id,
				'filter_payroll_include' 	=> 1
			);

			$this->load->model('presence/presence');
		
			$customers = $this->model_presence_presence->getCustomers($filter_data);
		
			foreach ($customers as $key => $customer) {
				$result = $this->model_component_insurance->getQuote($presence_period_id, $customer['customer_id']);
				if (!empty($result['quote'])) {
					foreach ($result['quote'] as $quote) {
						$insurances_value[$customer['customer_id']][$quote['title']][$quote['type']] = $quote['value'];
					}
				} else {
					unset($customers[$key]);
				} 
			}
			$customer_count = count($insurances_value);
			
		} else {
			$filter_data = array(
				'code'		=> 'insurance'
			);

			$customer_count = $this->model_report_payroll->getComponentCustomersCount($presence_period_id, 'insurance');
			
			$customers = $this->model_report_payroll->getComponentCustomers($presence_period_id, $filter_data);

			$results = $this->model_report_payroll->getComponents($presence_period_id, 0, 'insurance');

			foreach ($results as $result) {
				$insurances_value[$result['customer_id']][$result['title']][$result['type']] = $result['value'];
			}
		}
		
		foreach ($insurance_titles as $title) {
			$insurances_total[$title][1] = 0;
			$insurances_total[$title][0] = 0;
		}
			
		foreach ($customers as $customer) {
			foreach ($insurance_titles as $title) {
				if (!empty($insurances_value[$customer['customer_id']][$title][1])) {
					$insurances_data[$title][1] = $this->currency->format($insurances_value[$customer['customer_id']][$title][1], $this->config->get('config_currency'));
					$insurances_data[$title][0] = $this->currency->format(-($insurances_value[$customer['customer_id']][$title][0] + $insurances_value[$customer['customer_id']][$title][1]), $this->config->get('config_currency'));

					$insurances_total[$title][1] += $insurances_value[$customer['customer_id']][$title][1];
					$insurances_total[$title][0] += -($insurances_value[$customer['customer_id']][$title][0] + $insurances_value[$customer['customer_id']][$title][1]);
				} else {
					// $insurances_data[$title][1] = $this->currency->format(0, $this->config->get('config_currency'));
					// $insurances_data[$title][0] = $this->currency->format(0, $this->config->get('config_currency'));
					$insurances_data[$title][1] = '-';
					$insurances_data[$title][0] = '-';
				}
			}
			$data['insurances'][] = array(
				'customer_id' 		=> $customer['customer_id'],
				'nip' 				=> $customer['nip'],
				'name'				=> $customer['name'],
				'customer_group'	=> $customer['customer_group'],
				'insurances_data'	=> $insurances_data
			);

		}

		foreach ($insurance_titles as $title) {
			$data['insurances_total'][$title][1] = $this->currency->format($insurances_total[$title][1], $this->config->get('config_currency'));
			$data['insurances_total'][$title][0] = $this->currency->format($insurances_total[$title][0], $this->config->get('config_currency'));
		}
		
		$data['titles'] = $insurance_titles;
		
		
		$data['text_no_results'] = $this->language->get('text_no_results');

		$language_list = array(
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_company',
			'column_customer',
			'text_total'
		);
		foreach ($language_list as $item) {
			$data[$item] = $this->language->get($item);
		}

		$data['token'] = $this->session->data['token'];

 		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

/* 		$pagination = new Pagination();
		$pagination->total = $customer_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/payroll_insurance/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();
 */
		$data['result_count'] = sprintf($this->language->get('text_result_count'), $customer_count);

		$data['customer_count'] = $customer_count;

		$this->response->setOutput($this->load->view('report/payroll_insurance_report', $data));
	}
}
