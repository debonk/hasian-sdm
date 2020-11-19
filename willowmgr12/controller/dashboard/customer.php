<?php
class ControllerDashboardCustomer extends Controller {
	public function index() {
		$this->load->language('dashboard/customer');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		// Total Orders
		$this->load->model('presence/presence');

		$today = $this->model_presence_presence->getTotalCustomers(array('period_date_start' => date('Y-m-d')));

		$period_date_start = date('Y-m-') . '01';
		$yesterday = $this->model_presence_presence->getTotalCustomers(array('period_date_start' => date('Y-m-d', strtotime($period_date_start))));

		$difference = $today - $yesterday;

		if ($difference && $today) {
			$data['percentage'] = round(($difference / $today) * 100);
		} else {
			$data['percentage'] = 0;
		}

		$customer_total = $today;

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