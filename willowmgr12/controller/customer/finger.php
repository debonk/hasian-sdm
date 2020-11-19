<?php
class ControllerCustomerFinger extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/finger');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/finger');
		$this->load->model('presence/presence');
		
		$this->document->addScript('view/javascript/finger/jquery.timer.js');
		
		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		} else {
			$filter_customer_group_id = null;
		}

		if (isset($this->request->get['filter_location_id'])) {
			$filter_location_id = $this->request->get['filter_location_id'];
		} else {
			$filter_location_id = '';
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
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

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url, true)
		);

		$filter_data = array(
			'filter_name'	   	   => $filter_name,
			'filter_customer_group_id' => $filter_customer_group_id,
			'filter_location_id'   => $filter_location_id,
			'filter_status' 	   => $filter_status,
			'sort'                 => $sort,
			'order'                => $order,
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$data['customers'] = array();

		$customer_count = $this->model_presence_presence->getTotalCustomers($filter_data);

		$results = $this->model_presence_presence->getCustomers($filter_data);

		foreach ($results as $result) {
			$finger_info = $this->model_customer_finger->getFingerByCustomerId($result['customer_id']);
			
			if (empty($finger_info)) {
				$username = '';
				$date_added = '';
				$register = 1;
			} else {
				$username = $finger_info['username'];
				$date_added = date($this->language->get('date_format_jMY'), strtotime($finger_info['date_added']));
				$register		= 0;				
			}

			$data['customers'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'nip' 				=> $result['nip'],
				'name' 				=> $result['name'],
				'customer_group' 	=> $result['customer_group'],
				'location' 			=> $result['location'],
				'date_added' 		=> $date_added,
				'username' 			=> $username,
				'view'          	=> $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true),
				'register'        	=> $register
			);
		}
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_all_customer_group',
			'text_all_location',
			'text_active',
			'text_inactive',
			'text_all_status',
			'text_loading',
			'text_success_register',
			'entry_name',
			'entry_customer_group',
			'entry_location',
			'entry_status',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_location',
			'column_date_added',
			'column_username',
			'column_action',
			'button_filter',
			'button_view',
			'button_register',
			'button_verification',
			'button_delete',
			'error_register'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_nip'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_location_id'])) {
			$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_count - $this->config->get('config_limit_admin'))) ? $customer_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_count, ceil($customer_count / $this->config->get('config_limit_admin')));

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_location_id'] = $filter_location_id;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/finger_list', $data));
	}

	public function register() {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		if ($this->validateRegister()) {
			$register_path	= HTTP_SERVER . 'model/finger/register.php';
			
			$url_register	= base64_encode($register_path . '?customer_id=' . $customer_id . '&user_id=' . $this->user->getId() . '&token=' . $this->session->data['token']);
			// $url_register	= ($register_path . '?customer_id=' . $customer_id . '&user_id=' . $this->user->getId() . '&token=' . $this->session->data['token']);
			// $register = $url_register;
			$register = 'finspot:FingerspotReg;' . $url_register;

			$this->response->redirect($register);
		}
	}

	public function verification() {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		if ($this->validateVerification()) {
			$verification_path	= HTTP_SERVER . 'model/finger/verification.php';
			
			$url_verification	= base64_encode($verification_path . '?customer_id=' . $customer_id);
			// $url_verification	= ($verification_path . '?customer_id=' . $customer_id);
			// $verification = $url_verification;
			$verification = 'finspot:FingerspotVer;' . $url_verification;

			$this->response->redirect($verification);
		}
	}

	public function getRegisterStatus() {
		$this->load->language('customer/finger');

		$json = array();
		
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		$this->load->model('customer/finger');
		$finger_info = $this->model_customer_finger->getFingerByCustomerId($this->request->get['customer_id']);
		
		if ($finger_info) {
			$json['reg_status'] = 1;
			$json['date_added'] = date($this->language->get('date_format_jMY'), strtotime($finger_info['date_added']));
			$json['username'] = $finger_info['username'];
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getVerificationStatus() {
		$this->load->language('customer/finger');

		$json = array();
		
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		$this->load->model('customer/finger');
		$finger_info = $this->model_customer_finger->getFingerByCustomerId($this->request->get['customer_id']);
		
		if ($finger_info) {
			$json['ver_status'] = 1;
			$json['name'] = $finger_info['name'];
			$json['date'] = date($this->language->get('date_format_jMY'));
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateRegister() {
		if (!$this->user->hasPermission('modify', 'customer/finger')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('customer/finger');
		$finger_count = $this->model_customer_finger->getFingersCount($this->request->get['customer_id']);
		
		if ($finger_count) {
			$this->error['warning'] = $this->language->get('error_template_exist');
		}

		return !$this->error;
	}

	protected function validateVerification() {
		if (!$this->user->hasPermission('modify', 'customer/finger')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		$this->load->model('customer/finger');
		$finger_count = $this->model_customer_finger->getFingersCount($this->request->get['customer_id']);
		
		if (!$finger_count) {
			$this->error['warning'] = $this->language->get('error_not_found');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('bypass', 'customer/finger')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		return !$this->error;
	}

	public function deleteFinger() {
		$this->load->language('customer/finger');

		$json = array();

		if ($this->validateDelete()) {
			$this->load->model('customer/finger');

			if (isset($this->request->post['customer_id'])) {
				$customer_id = $this->request->post['customer_id'];
			} else {
				$customer_id = 0;
			}

			$this->model_customer_finger->deleteFingerByCustomerId($customer_id);

			$json['success'] = $this->language->get('text_success');
			
		} else {
			$json['error'] = $this->error['warning'];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$this->load->model('presence/presence');

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => 5
			);

			$results = $this->model_presence_presence->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       => $result['customer_id'],
					'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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

/* 	protected function getForm() {
		$data['text_edit'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_gaji_pokok',
			'entry_tunj_jabatan',
			'entry_tunj_hadir',
			'entry_tunj_pph',
			'entry_uang_makan',
			'entry_gaji_dasar',
			'button_save',
			'button_cancel'
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

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->error['gaji_pokok'])) {
			$data['error_gaji_pokok'] = $this->error['gaji_pokok'];
		} else {
			$data['error_gaji_pokok'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
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
			'href' => $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['customer_id'])) {
			$this->response->redirect($this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url, true));
		} else {
			$data['action'] = $this->url->link('customer/finger/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_edit'],
			'href' => $data['action']
		);

		if (isset($this->request->get['customer_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$payroll_basic_info = $this->model_customer_finger->getPayrollBasic($this->request->get['customer_id']);
		}

		$payroll_basic_items = array(
			'gaji_pokok',
			'tunj_jabatan',
			'tunj_hadir',
			'tunj_pph',
			'uang_makan'
		);
		foreach ($payroll_basic_items as $payroll_basic_item) {
			if (isset($this->request->post[$payroll_basic_item])) {
				$data[$payroll_basic_item] = $this->request->post[$payroll_basic_item];
			} elseif (!empty($payroll_basic_info)) {
				$data[$payroll_basic_item] = number_format($payroll_basic_info[$payroll_basic_item]);
			} else {
				$data[$payroll_basic_item] = 0;
			}
		}

		if (!empty($payroll_basic_info)) {
			$gaji_dasar = $payroll_basic_info['gaji_pokok'] + $payroll_basic_info['tunj_jabatan'] + $payroll_basic_info['tunj_hadir'] + $payroll_basic_info['tunj_pph'] + (25 * $payroll_basic_info['uang_makan']);
			$data['gaji_dasar'] = number_format($gaji_dasar);
		} else {
			$data['gaji_dasar'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/finger_form', $data));
	}

	public function history() {
		$this->load->language('customer/finger');

		$this->load->model('customer/finger');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['histories'] = array();

		$results = $this->model_customer_finger->getPayrollBasicHistories($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$gaji_dasar = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + (25 * $result['uang_makan']);
			$data['histories'][] = array(
				'gaji_pokok'    => $this->currency->format($result['gaji_pokok'], $this->config->get('config_currency')),
				'tunj_jabatan'  => $this->currency->format($result['tunj_jabatan'], $this->config->get('config_currency')),
				'tunj_hadir'    => $this->currency->format($result['tunj_hadir'], $this->config->get('config_currency')),
				'tunj_pph'    	=> $this->currency->format($result['tunj_pph'], $this->config->get('config_currency')),
				'uang_makan'    => $this->currency->format($result['uang_makan'], $this->config->get('config_currency')),
				'gaji_dasar' 	=> $this->currency->format($gaji_dasar, $this->config->get('config_currency')),
				'date_added' 	=> date($this->language->get('date_format_jMY'), strtotime($result['date_added'])),
				'username' 		=> $result['username']
			);
		}

		$history_total = $this->model_customer_finger->getTotalPayrollBasicHistories($this->request->get['customer_id']);

		$language_items = array(
			'text_no_results',
			'column_date_added',
			'column_gaji_pokok',
			'column_tunj_jabatan',
			'column_tunj_hadir',
			'column_tunj_pph',
			'column_uang_makan',
			'column_gaji_dasar',
			'column_username'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$pagination = new Pagination();
		$pagination->total = $history_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('customer/finger/history', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($history_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($history_total - 10)) ? $history_total : ((($page - 1) * 10) + 10), $history_total, ceil($history_total / 10));

		$this->response->setOutput($this->load->view('customer/finger_history', $data));
	}
 */
}
