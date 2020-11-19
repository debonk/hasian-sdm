<?php
class ControllerDashboardChart extends Controller {
	public function index() {
		$this->load->language('dashboard/chart');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_earning'] = $this->language->get('text_earning');
		$data['text_deduction'] = $this->language->get('text_deduction');
		$data['text_grandtotal'] = $this->language->get('text_grandtotal');
//		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		return $this->load->view('dashboard/chart', $data);
	}

	public function chart() {
		$this->load->language('dashboard/chart');

		$json = array();

		$this->load->model('report/payroll');
		$this->load->model('report/sale');
		$this->load->model('report/customer');

		$json['payroll'] = array();
		$json['customer'] = array();
		$json['xaxis'] = array();

		$json['payroll']['label'] = $this->language->get('text_payroll');
		$json['customer']['label'] = $this->language->get('text_customer');
		$json['payroll']['data'] = array();
		$json['customer']['data'] = array();

		if (isset($this->request->get['range'])) {
			$range = $this->request->get['range'];
		} else {
			$range = 'grandtotal';
		}

		switch ($range) {
			default:
			case 'earning':
				$results = $this->model_report_payroll->getTotalPayrollsByYear('earning');

				foreach ($results as $key => $value) {
					$json['payroll']['data'][] = array($key, $value['total']);
				}

 				$results = $this->model_report_customer->getTotalCustomersByYear();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}
 
				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				break;
			case 'deduction':
				$results = $this->model_report_payroll->getTotalPayrollsByYear('deduction');

				foreach ($results as $key => $value) {
					$json['payroll']['data'][] = array($key, $value['total']);
				}

 				$results = $this->model_report_customer->getTotalCustomersByYear();

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}
 
				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				break;
			case 'grandtotal':
				$results = $this->model_report_payroll->getTotalPayrollsByYear('grandtotal');

				foreach ($results as $key => $value) {
					$json['payroll']['data'][] = array($key, $value['total']);
				}

				$results = $this->model_report_payroll->getTotalPayrollsByYear('deduction');

				foreach ($results as $key => $value) {
					$json['customer']['data'][] = array($key, $value['total']);
				}
 
				for ($i = 1; $i <= 12; $i++) {
					$json['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
				}
				break;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}