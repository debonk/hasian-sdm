<?php
class ControllerCommonPayrollInfo extends Controller {
	public function index() {
		$this->load->language('common/payroll_info');

		$this->load->model('payroll/payroll');

		$presence_period_id = $this->request->get['presence_period_id'];
		
		$total_payroll = $this->model_payroll_payroll->getTotalPayroll($presence_period_id);
		$net_salary = $total_payroll['total_earning'] - $total_payroll['total_deduction'];

		$data['component_codes'] = $this->model_payroll_payroll->getPayrollComponentCodes($presence_period_id);

		$total_component = $this->model_payroll_payroll->getPayrollComponentTotal($presence_period_id,0,'code');
		$data['net_salary'] = $this->currency->format($net_salary, $this->config->get('config_currency'));
		$data['grandtotal'] = $this->currency->format($net_salary + $total_component['grandtotal'], $this->config->get('config_currency'));
		$data['total_customer'] = $total_payroll['total_customer'];

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