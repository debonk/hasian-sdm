<?php
class ControllerDashboardAttention extends Controller
{
	public function index()
	{
		if (!$this->user->hasPermission('access', 'dashboard/attention')) {
			return;
		}

		$this->load->language('dashboard/attention');

		$this->db->createView('v_contract');

		$data['token'] = $this->session->data['token'];

		$data['attentions'] = [];

		$this->load->model('report/attention');

		# Customer
		$item_data = [
			'customer_department_id'	=> 0,
			'location_id'				=> 0,
			'image'						=> ''
		];

		$results = $this->model_report_attention->getCustomerUncompleteDataSummaries($item_data);

		foreach ($item_data as $item => $value) {
			if ($results[$item]) {
				if ($item == 'image') {
					$icon 			= 'fa-info-circle';
					$alert_class	= 'alert-info';
				} else {
					$icon 			= 'fa-exclamation-circle';
					$alert_class	= 'alert-danger';
				}

				$href = $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true);

				$data['attentions'][] = [
					'text'			=> sprintf($this->language->get('text_customer_summary'), $results[$item], $href, $this->language->get('text_customer_' . $item)),
					'icon'			=> $icon,
					'alert_class'	=> $alert_class
				];
			}
		}

		# Document
		$result = $this->model_report_attention->getCustomerUncompleteDocumentCount();

		if ($result) {
			$href = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&filter_requirement=-1&filter_active=1', true);

			$data['attentions'][] = [
				'text'			=> sprintf($this->language->get('text_uncomplete'), $result, $href),
				'icon'			=> 'fa-exclamation-circle',
				'alert_class'	=> 'alert-danger'
			];
		}

		# Contract Summary
		$results = $this->model_report_attention->getCustomerContractSummaries();

		foreach ($results as $result) {
			if (in_array($result['contract_status'], ['none', 'end_soon', 'end_today'])) {
				if ($result['contract_status'] == 'end_today') {
					$icon 			= 'fa-exclamation-circle';
					$alert_class	= 'alert-danger';
				} else {
					$icon 			= 'fa-exclamation-circle';
					$alert_class 	= 'alert-warning';
				}

				$href = $this->url->link('customer/contract', 'token=' . $this->session->data['token'] . '&filter_contract_status=' . $result['contract_status'], true);

				$data['attentions'][] = [
					'text'			=> sprintf($this->language->get('text_contract_summary'), $result['total'], $href, $this->language->get('text_contract_' . $result['contract_status'])),
					'icon'			=> $icon,
					'alert_class'	=> $alert_class
				];
			}
		}

		return $this->load->view('dashboard/attention', $data);
	}
}
