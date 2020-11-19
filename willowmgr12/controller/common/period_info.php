<?php
class ControllerCommonPeriodInfo extends Controller {
	public function index() {
		$this->load->language('common/period_info');

		$this->load->model('common/payroll');

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = 0;
		}

		$data['text_period_info'] = $this->language->get('text_period_info');
		$data['text_period'] = $this->language->get('text_period');
		$data['text_date_start'] = $this->language->get('text_date_start');
		$data['text_date_end'] = $this->language->get('text_date_end');
		$data['text_payroll_status'] = $this->language->get('text_payroll_status');
		$data['text_no_results'] = $this->language->get('text_no_results');

		//Text Period
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if ($period_info) {
			$data['period'] = date($this->language->get('date_format_m_y'), strtotime($period_info['period']));
			$data['date_start'] = date($this->language->get('date_format_jMY'), strtotime($period_info['date_start']));
			$data['date_end'] = date($this->language->get('date_format_jMY'), strtotime($period_info['date_end']));
			$data['payroll_status'] = $period_info['payroll_status'];
		}
		$data['period_info_check'] = $period_info;
		
		$this->response->setOutput($this->load->view('common/period_info', $data));
	}
}