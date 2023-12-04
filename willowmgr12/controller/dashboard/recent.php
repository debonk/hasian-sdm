<?php
class ControllerDashboardRecent extends Controller {
	public function index() {
		if (!$this->user->hasPermission('access', 'dashboard/recent')) {
			return;
		}

		$this->db->createView('v_customer');

		$this->load->language('dashboard/recent');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_nip'] = $this->language->get('column_nip');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_customer_group'] = $this->language->get('column_customer_group');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_view'] = $this->language->get('button_view');

		$data['token'] = $this->session->data['token'];

		// Last 5 Orders
		$data['customers'] = array();

		$filter_data = array(
			'sort'  => 'date_start',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 5
		);

		$this->load->model('report/customer');
		$results = $this->model_report_customer->getCustomers($filter_data);

		foreach ($results as $result) {
			$data['customers'][] = array(
				'nip'   		=> $result['nip'],
				'name'   		=> $result['name'],
				'customer_group'=> $result['customer_group'],
				'date_start' 	=> date($this->language->get('date_format_jMY'), strtotime($result['date_start'])),
				'view'       	=> $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true),
			);
		}

		return $this->load->view('dashboard/recent', $data);
	}
}