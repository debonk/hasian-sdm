<?php
class ControllerCommonCustomerInfo extends Controller
{
	public function index()
	{
		$this->load->language('common/customer_info');

		$this->load->model('common/payroll');
		$this->load->model('customer/contract');

		$language_items = array(
			'text_customer_detail',
			'text_additional_info',
			'text_contract',
			'text_customer',
			'text_customer_department',
			'text_customer_group',
			'text_location',
			'text_employment_period',
			'text_email',
			'text_telephone',
			'text_vacation'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$customer_info = $this->model_common_payroll->getCustomer($customer_id);

		$this->load->model('tool/image');

		if ($customer_info) {
			if ($customer_info['image'] && is_file(DIR_IMAGE . $customer_info['image'])) {
				$data['thumb'] = $this->model_tool_image->resize($customer_info['image'], 140, 140);
			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', 140, 140);
			}

			$data['name'] = $customer_info['name'];
			$data['customer'] = $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id, true);

			$date_start = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start']));
			if ($customer_info['date_end']) {
				$date_end = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_end']));
			} else {
				$date_end = $this->language->get('text_now');
			}

			$contract_info = $this->model_customer_contract->getCustomerContract($customer_id);
			$this->load->language('customer/contract');
			$contract_status = $this->language->get('text_contract_' . $contract_info['contract_status']);

			$data['employment_period'] = $date_start . ' - ' . $date_end;
			// $data['contract_status'] = '(' . $contract_status . ')';
			$data['contract'] = $contract_info['contract_type'] . ' - ' . $contract_status;

			$data['customer_department'] = $customer_info['customer_department'];
			$data['customer_group'] = $customer_info['customer_group'];
			$data['location'] = $customer_info['location'];
			$data['email'] = $customer_info['email'];
			$data['telephone'] = $customer_info['telephone'];

			$vacation_limit = $this->config->get('payroll_setting_vacation_limit');
			$active_time = date_diff(date_create($customer_info['date_start']), date_create());

			$vacation_limit = !$active_time->y ? 0 : $this->config->get('payroll_setting_vacation_limit');

			$this->load->model('presence/absence');
			$vacation_count = $this->model_presence_absence->getVacationsCount($customer_id);

			$vacation_limit = max($vacation_limit, $vacation_count);
			$vacation_count = $vacation_limit - $vacation_count;

			$data['vacation'] = $vacation_limit ? sprintf($this->language->get('text_vacation_count'), $vacation_count, $vacation_limit) : '-';
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 140, 140);
			$data['nip'] = '-';
			$data['name'] = '-';
			$data['customer'] = '';
			$data['customer_department'] = '-';
			$data['customer_group'] = '-';
			$data['location'] = '-';
			$data['email'] = '-';
			$data['telephone'] = '-';
			$data['vacation'] = '-';
			$data['contract'] = '-';
			$data['employment_period'] = '-';
			$data['contract_status'] = '';
		}

		$this->response->setOutput($this->load->view('common/customer_info', $data));
	}
}
