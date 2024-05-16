<?php
class ControllerDashboardCustomer extends Controller {
	public function index() {
		$this->load->language('dashboard/customer');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		// Total Orders
		$this->load->model('presence/presence');

		$current_month = $this->model_presence_presence->getTotalCustomers();

		$this->load->model('common/payroll');

		$date_previous_month = date('Y-m-d', strtotime('-1 month'));
		$period_info = $this->model_common_payroll->getPeriodByDate($date_previous_month);
	
		$previous_month = $this->model_presence_presence->getTotalCustomers(array('presence_period_id' => $period_info['presence_period_id']));

		$difference = $current_month - $previous_month;

		if ($difference && $current_month) {
			$data['percentage'] = round(($difference / $current_month) * 100);
		} else {
			$data['percentage'] = 0;
		}

		$customer_total = $current_month;

		if ($customer_total > 1000000000000) {
			$data['total'] = round($customer_total / 1000000000000, 1) . 'T';
		} elseif ($customer_total > 1000000000) {
			$data['total'] = round($customer_total / 1000000000, 1) . 'B';
		} elseif ($customer_total > 1000000) {
			$data['total'] = round($customer_total / 1000000, 1) . 'M';
		} elseif ($customer_total > 1000) {
			$data['total'] = round($customer_total / 1000, 1) . 'K';
		} else {
			$data['total'] = $customer_total;
		}

		$data['customer'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true);

		return $this->load->view('dashboard/customer', $data);
	}
}