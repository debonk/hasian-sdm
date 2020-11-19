<?php
class ControllerDashboardPayroll extends Controller {
	public function index() {
		$this->load->language('dashboard/payroll');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		$this->load->model('report/payroll');

		$period_y_m = date('Y') . '-' . (date('n') - 1);
		$last_month = $this->model_report_payroll->getTotalPayrollByPeriod($period_y_m);
		$last_month_payroll = $last_month['sum_earning'] - $last_month['sum_deduction'];

		$period_y_m = date('Y') . '-' . (date('n') - 2);
		$last_2_month = $this->model_report_payroll->getTotalPayrollByPeriod($period_y_m);
		$last_2_month_payroll = $last_2_month['sum_earning'] - $last_2_month['sum_deduction'];
		
		$difference = $last_month_payroll - $last_2_month_payroll;

		if ($difference && (int)$last_month_payroll) {
			$data['percentage'] = round(($difference / $last_month_payroll) * 100);
		} else {
			$data['percentage'] = 0;
		}

		if ($last_month_payroll > 1000000000000) {
			$data['total'] = round($last_month_payroll / 1000000000000, 3) . 'T';
		} elseif ($last_month_payroll > 1000000000) {
			$data['total'] = round($last_month_payroll / 1000000000, 3) . 'B';
		} elseif ($last_month_payroll > 1000000) {
			$data['total'] = round($last_month_payroll / 1000000, 3) . 'M';
		} elseif ($last_month_payroll > 1000) {
			$data['total'] = round($last_month_payroll / 1000, 3) . 'K';
		} else {
			$data['total'] = round($last_month_payroll);
		}

		$data['payroll'] = $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'], true);

		return $this->load->view('dashboard/payroll', $data);
	}
}
