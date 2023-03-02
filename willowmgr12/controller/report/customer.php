<?php
class ControllerReportCustomer extends Controller {
	// private $error = array();

	public function index() {
		$this->load->language('report/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/customer');

		$this->getList();
	}

	public function view() {
		$this->load->language('report/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('report/customer');

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$customer_info = $this->model_report_customer->getCustomer($customer_id);
		if ($customer_info) {
			$this->getForm();
		} else {
			return new Action('error/not_found');
		}
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_email'])) {
			$filter_email = $this->request->get['filter_email'];
		} else {
			$filter_email = null;
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = null;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = null;
		}

		if (isset($this->request->get['filter_active'])) {
			$filter_active = $this->request->get['filter_active'];
		} else {
			$filter_active = null;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_active'])) {
			$url .= '&filter_active=' . $this->request->get['filter_active'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/customer', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['customers'] = array();

		$filter_data = array(
			'filter_name'              => $filter_name,
			'filter_email'             => $filter_email,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_status'            => $filter_status,
			'filter_location_id'       => $filter_location_id,
			'filter_date_start'        => $filter_date_start,
			'filter_active'            => $filter_active,
			'sort'                     => $sort,
			'order'                    => $order,
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);

		$customer_total = $this->model_report_customer->getTotalCustomers($filter_data);

		$results = $this->model_report_customer->getCustomers($filter_data);
		
		$this->load->model('presence/absence');
		
		foreach ($results as $result) {
			$vacation_count = $this->model_presence_absence->getVacationsCount($result['customer_id']);
			
			$data['customers'][] = array(
				'customer_id'    => $result['customer_id'],
				'nip'            => $result['nip'],
				'name'           => $result['name'],
				'customer_group' => $result['customer_group'],
				'location'       => $result['location'],
				'date_start'     => date($this->language->get('date_format_jMY'), strtotime($result['date_start'])),
				'email'          => $result['email'],
				'vacation_count' => $vacation_count,
				'view'           => $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_nip'] = $this->language->get('column_nip');
		$data['column_name'] = $this->language->get('column_name');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_customer_group'] = $this->language->get('column_customer_group');
		$data['column_location'] = $this->language->get('column_location');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_vacation_count'] = $this->language->get('column_vacation_count');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_location'] = $this->language->get('entry_location');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_active'] = $this->language->get('entry_active');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_active'])) {
			$url .= '&filter_active=' . $this->request->get['filter_active'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_nip'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . '&sort=c.nip' . $url, true);
		$data['sort_name'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_email'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . '&sort=c.email' . $url, true);
		$data['sort_date_start'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . '&sort=c.date_start' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_active'])) {
			$url .= '&filter_active=' . $this->request->get['filter_active'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_email'] = $filter_email;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_location_id'] = $filter_location_id;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_active'] = $filter_active;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/customer_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = $this->language->get('text_view');

		$language_items = array(
			'heading_title',
			'text_enabled',
			'text_disabled',
			'text_missing',
			'text_yes',
			'text_no',
			'text_no_results',
			'entry_default',
			'entry_id_card_address',
			'entry_year',//vacation info
			'button_cancel',
			'button_filter',
			'tab_general',
			'tab_address',
			'tab_document',
			'tab_history',
			'tab_payroll_basic',
			'tab_vacation',
			'tab_loan'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$data['customer_id'] = $this->request->get['customer_id'];
		} else {
			$data['customer_id'] = 0;
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_email'])) {
			$url .= '&filter_email=' . urlencode(html_entity_decode($this->request->get['filter_email'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}
		
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_active'])) {
			$url .= '&filter_active=' . $this->request->get['filter_active'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/customer', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['action'] = $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);

		$data['cancel'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);
		
		$customer_info = $this->model_report_customer->getCustomer($this->request->get['customer_id']);

		$data['generals'] = array();
		$data['addresses'] = array();

		$general_items = array(
			'nip',
			'nik',
			'date_start',
			'full_overtime',
			'status',
			'date_end',
			'gender',
			'date_birth',
			'marriage_status',
			'children',
			'npwp',
			'npwp_address',
			'payroll_method',
			'acc_no',
			'health_insurance',
			'health_insurance_id',
			'employment_insurance',
			'employment_insurance_id'
		);
		
		$value['nip'] = $customer_info['nip'];
		$value['nik'] = $customer_info['nik'];
		$value['date_start'] = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start']));
		$value['date_birth'] = $customer_info['date_birth'] ? date($this->language->get('date_format_jMY'), strtotime($customer_info['date_birth'])) : '-';
		$value['full_overtime'] = $customer_info['full_overtime'] ? $this->language->get('text_yes') : $this->language->get('text_no');
		$value['status'] = $customer_info['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled');
		$value['date_end'] = $customer_info['date_end'] ? date($this->language->get('date_format_jMY'), strtotime($customer_info['date_end'])) : '-';
		$value['npwp'] = $customer_info['npwp'];
		$value['npwp_address'] = $customer_info['npwp_address'];
		$value['children'] = $customer_info['children'];
		$value['acc_no'] = $customer_info['acc_no'];
		$value['health_insurance'] = $customer_info['health_insurance'] ? $this->language->get('text_yes') : $this->language->get('text_no');
		$value['health_insurance_id'] = $customer_info['health_insurance_id'];
		$value['employment_insurance'] = $customer_info['employment_insurance'] ? $this->language->get('text_yes') : $this->language->get('text_no');
		$value['employment_insurance_id'] = $customer_info['employment_insurance_id'];
		
		$this->load->model('localisation/gender');
		$gender = $this->model_localisation_gender->getGender($customer_info['gender_id']);
		$value['gender'] = $gender['name'];
		
		$this->load->model('localisation/marriage_status');
		$marriage_status = $this->model_localisation_marriage_status->getMarriageStatus($customer_info['marriage_status_id']);
		$value['marriage_status'] = $marriage_status['name'];
		
		$this->load->model('localisation/payroll_method');
		$payroll_method = $this->model_localisation_payroll_method->getPayrollMethod($customer_info['payroll_method_id']);
		$value['payroll_method'] = $payroll_method['name'];
		
		$data['address_id'] = $customer_info['address_id'];
		$data['id_card_address_id'] = $customer_info['id_card_address_id'];

		foreach ($general_items as $item) {
			$data['generals'][] = array(
				'label'		=> $this->language->get('entry_' . $item),
				'value'		=> $value[$item]
			);
		}
		
		$data['address_items'] = array(
			'address_1',
			'address_2',
			'postcode',
			'country',
			'zone',
			'city_name'
		);
		
		foreach ($data['address_items'] as $item) {
			$data['label'][$item] = $this->language->get('entry_' . $item);
		}
		
		$data['addresses'] = $this->model_report_customer->getAddresses($this->request->get['customer_id']);
		
		//Documents
		$this->load->model('customer/document');

		$data['documents'] = array();
		
		$results = $this->model_customer_document->getDocumentsByCustomer($this->request->get['customer_id']);

		foreach ($results as $result) {
			if (is_file(DIR_DOCUMENT . $result['filename'])) {
				$href = $this->url->link('customer/document/view', 'token=' . $this->session->data['token'] . '&document_id=' . $result['document_id'], true);
				// $missing = false;
			} else {
				$href = '';
				// $missing = true;
			}

			$data['documents'][] = array(
				'mask' => $result['mask'],
				'href'  => $href
			);
		}
		
		//vacation_info
		$data['year'] = date('Y');
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/customer_form', $data));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name']) || isset($this->request->get['filter_email'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['filter_email'])) {
				$filter_email = $this->request->get['filter_email'];
			} else {
				$filter_email = '';
			}

			$this->load->model('report/customer');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_email' => $filter_email,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_report_customer->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'customer_group_id' => $result['customer_group_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					'customer_group'    => $result['customer_group'],
					'nip'          		=> $result['nip'],
					'firstname'         => $result['firstname'],
					'lastname'          => $result['lastname'],
					'email'             => $result['email'],
					'telephone'         => $result['telephone'],
					'custom_field'      => json_decode($result['custom_field'], true),
					'address'           => $this->model_report_customer->getAddresses($result['customer_id'])
				);
			}
		}

		$sort_order = array();

		foreach ($json as $key => $value) {
			$sort_order[$key] = $value['name'];
		}

		array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

/* 	public function customfield() {
		$json = array();

		$this->load->model('customer/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id'])) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_customer_custom_field->getCustomFields(array('filter_customer_group_id' => $customer_group_id));

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => empty($custom_field['required']) || $custom_field['required'] == 0 ? false : true
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
 */
/* 	public function address() {
		$json = array();

		if (!empty($this->request->get['address_id'])) {
			$this->load->model('report/customer');

			$json = $this->model_report_customer->getAddress($this->request->get['address_id']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
 */
}
