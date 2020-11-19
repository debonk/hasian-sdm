<?php
class ControllerCommonCustomerInfo extends Controller {
	public function index() {
		$this->load->language('common/customer_info');
		
		$this->load->model('common/payroll');
		
		$language_items = array(
			'text_customer_detail',
			'text_additional_info',
			'text_customer',
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

			$data['name'] = $customer_info['firstname'] . ' [' . $customer_info['lastname'] . ']';
			$data['customer'] = $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id, true);
			
			$date_start = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start']));
			if ($customer_info['date_end']) {
				$date_end = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_end']));
			} else {
				$date_end = $this->language->get('text_now');
			}
			$data['employment_period'] = $date_start . ' - ' . $date_end;

			$data['customer_group'] = $customer_info['customer_group'];
			$data['location'] = $customer_info['location'];
			$data['email'] = $customer_info['email'];
			$data['telephone'] = $customer_info['telephone'];
			
			$this->load->model('presence/absence');
			$vacation_count = $this->model_presence_absence->getVacationsCount($customer_id);
			$vacation_limit = $this->config->get('payroll_setting_vacation_limit');
			
			if ($vacation_limit) {
				$data['vacation'] = sprintf($this->language->get('text_vacation_count'), $vacation_count, $vacation_limit);
			} else {
				$data['vacation'] = '';
			}
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 140, 140);
			$data['nip'] = '-';
			$data['name'] = '-';
			$data['customer'] = '';
			$data['customer_group'] = '-';
			$data['location'] = '-';
			$data['email'] = '-';
			$data['telephone'] = '-';
			$data['vacation'] = '';
			$data['employment_period'] = '-';
		}

		$this->response->setOutput($this->load->view('common/customer_info', $data));
	}
}