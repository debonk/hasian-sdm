<?php
class ControllerCommonPayrollInfo extends Controller {
	private $filter_items = array(
		'name',
		'customer_group_id',
		'customer_department_id',
		'location_id'
	);

	public function index() {
		$this->load->language('common/payroll_info');

		$this->load->model('payroll/payroll');

		$presence_period_id = $this->request->get['presence_period_id'];

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		$filter_data = array(
			'filter'	=> $filter
		);

		$payroll_summary = $this->model_payroll_payroll->getPayrollSummary($presence_period_id, $filter_data);

		$data['component_codes'] = $this->model_payroll_payroll->getPayrollComponentCodes($presence_period_id);

		$total_component = $this->model_payroll_payroll->getPayrollComponentTotal($presence_period_id, 0, 'code', $filter_data);

		$data['net_salary'] = $this->currency->format($payroll_summary['total_net_salary'], $this->config->get('config_currency'));
		$data['grandtotal'] = $this->currency->format($payroll_summary['total_net_salary'] + $payroll_summary['total_component'], $this->config->get('config_currency'));
		$data['total_customer'] = $payroll_summary['total_customer'];

		foreach ($data['component_codes'] as $code) {
			$data['text_component'][$code] = $this->language->get('text_' . $code) . ' :';
			$data['component'][$code] = $this->currency->format($total_component[$code], $this->config->get('config_currency'));
		}
		
		$data['text_net_salary'] = $this->language->get('text_net_salary') . ' :';
		$data['text_grandtotal'] = $this->language->get('text_grandtotal') . ' :';
		$data['text_total_customer'] = $this->language->get('text_total_customer') . ' :';

		$data['text_payroll_info'] = $this->language->get('text_payroll_info');

		$this->response->setOutput($this->load->view('common/payroll_info', $data));
	}
}