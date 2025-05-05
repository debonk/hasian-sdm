<?php
class ControllerCustomerFinger extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('customer/finger');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/finger');
		$this->load->model('presence/presence');

		$this->document->addScript('view/javascript/finger/jquery.timer.js');

		$this->getList();
	}

	public function manage()
	{
		$this->load->language('customer/finger');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/finger');

		$this->document->addScript('view/javascript/finger/jquery.timer.js');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_finger->manageFinger($this->request->get['customer_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_group_id'])) {
				$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
			}

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

			$this->response->redirect($this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_all',
			'text_active',
			'text_inactive',
			'text_all_status',
			'text_left',
			'text_right',
			'text_loading',
			'text_index_old',
			'text_thumbs',
			'text_index',
			'text_middle',
			'text_ring',
			'text_pinkie',
			'entry_name',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'entry_status',
			'column_scan_active',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_customer_department',
			'column_location',
			'column_action',
			'button_filter',
			'button_view',
			'button_manage',
			'button_verification',
			// 'button_delete',
			'error_register'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$filter_customer_department_id = $this->request->get['filter_customer_department_id'];
		} else {
			$filter_customer_department_id = null;
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
			'href' => $this->url->link('customer/finger', 'token=' . $this->session->data['token'], true)
		);

		$filter_data = array(
			// 'filter_customer_department_id'	=> $this->user->getCustomerDepartmentId(),
			'filter_name'	   	   				=> $filter_name,
			'filter_customer_group_id'			=> $filter_customer_group_id,
			'filter_customer_department_id'		=> $filter_customer_department_id,
			'filter_location_id'   				=> $filter_location_id,
			'filter_status' 	   				=> $filter_status,
			'sort'                 				=> $sort,
			'order'                				=> $order,
			'start'                				=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                				=> $this->config->get('config_limit_admin')
		);

		$data['customers'] = array();

		$customer_count = $this->model_presence_presence->getTotalCustomers($filter_data);
		$results = $this->model_presence_presence->getCustomers($filter_data);

		$finger_indexes = $this->model_customer_finger->getFingerIndexes();

		$hands = [
			'1'	=> $data['text_left'],
			'2'	=> $data['text_right']
		];

		foreach ($results as $result) {
			$customer_add_info = $this->model_presence_presence->getCustomerAddData($result['customer_id']);

			$scan_active = [];

			if (!empty($customer_add_info['active_finger'])) {
				$active_fingers = json_decode($customer_add_info['active_finger'], true);
			} else {
				$finger_info = $this->model_customer_finger->getFingerByCustomerId($result['customer_id']);

				if ($finger_info) {
					$active_fingers = [
						'1'	=> $result['customer_id'] . 'x0'
					];
				} else {
					$active_fingers = [];
				}
			}

			foreach ($active_fingers as $key => $active_finger) {
				if ($active_finger) {
					$i = str_split(str_replace($result['customer_id'] . 'x', '', $active_finger));

					if (!isset($i[1])) {
						$scan_active[$key] = [
							'index'	=> $active_finger,
							'text'	=> $data['text_index_old']
						];
					} else {
						$scan_active[$key] = [
							'index'	=> $active_finger,
							'text'	=> $data['text_' . $finger_indexes[$i[1]]] . ' ' . $hands[$i[0]]
						];
					}
				}
			}

			$data['customers'][] = array(
				'customer_id' 			=> $result['customer_id'],
				'nip' 					=> $result['nip'],
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'scan_active' 			=> $scan_active,
				'view'          		=> $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true),
				'manage'          		=> $this->url->link('customer/finger/manage', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'register'        		=> 1
			);
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
		$data['sort_customer_department'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		$this->load->model('customer/customer_department');
		$data['customer_departments'] = $this->model_customer_customer_department->getCustomerDepartments();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_customer_department_id'] = $filter_customer_department_id;
		$data['filter_status'] = $filter_status;
		$data['filter_location_id'] = $filter_location_id;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/finger_list', $data));
	}

	protected function getForm()
	{
		$language_items = array(
			'heading_title',
			'text_edit',
			'text_loading',
			'text_none',
			'text_list_finger',
			'text_left',
			'text_right',
			'text_index_old',
			'text_thumbs',
			'text_index',
			'text_middle',
			'text_ring',
			'text_pinkie',
			'text_loading',
			'text_confirm',
			'text_no_results',
			'text_success_register',
			'entry_active_1',
			'entry_active_2',
			'column_left_hand',
			'column_right_hand',
			'column_finger_index',
			'column_date_added',
			'column_username',
			'column_action',
			'button_register',
			'button_save',
			'button_delete',
			'button_back',
			'button_verification',
			'error_register'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
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

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_group_id'])) {
			$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		if (isset($this->request->get['customer_id'])) {
			$data['action'] = $this->url->link('customer/finger/manage', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		} else {
			$this->response->redirect($this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url, true));
		}

		$data['back'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_edit'],
			'href' => $data['action']
		);

		$data['fingers'] = [];

		$finger_indexes = $this->model_customer_finger->getFingerIndexes();
		$fingers = $this->model_customer_finger->getFingersByCustomerId($customer_id);

		if (!empty($fingers[0])) {
			$data['fingers'][0] = [
				'index'			=> $customer_id . 'x0',
				'text'			=> '&nbsp;&nbsp;&nbsp;&nbsp; - ' . $data['text_index_old'],
				'username'		=> $fingers[0]['username'],
				'date_added'	=> date($this->language->get('datetime_format_jMY'), strtotime($fingers[0]['date_added'])),
				'register'		=> 0
			];
		}

		for ($i = 1; $i < 3; $i++) {
			foreach ($finger_indexes as $key => $finger_index) {
				$text_index = '&nbsp;&nbsp;&nbsp;&nbsp; - ' . $data['text_' . $finger_index];

				if (!empty($fingers[$i . $key])) {
					$data['fingers'][$i][$key] = [
						'index'			=> $customer_id . 'x' . $i . $key,
						'text'			=> $text_index,
						'username'		=> $fingers[$i . $key]['username'],
						'date_added'	=> date($this->language->get('datetime_format_jMY'), strtotime($fingers[$i . $key]['date_added'])),
						'registered'	=> true
					];
				} else {
					$data['fingers'][$i][$key] = [
						'index'			=> $customer_id . 'x' . $i . $key,
						'text'			=> $text_index,
						'username'		=> '',
						'date_added'	=> '',
						'registered'	=> false
					];
				}
			}
		}

		$hands = [
			'1'	=> $data['text_left'],
			'2'	=> $data['text_right']
		];

		$this->load->model('presence/presence');
		$customer_add_info = $this->model_presence_presence->getCustomerAddData($customer_id);

		if (!empty($customer_add_info['active_finger'])) {
			$data['active_fingers'] = json_decode($customer_add_info['active_finger'], true);
		} else {
			$finger_info = $this->model_customer_finger->getFingerByCustomerId($customer_id);

			if ($finger_info) {
				$data['active_fingers'] = [
					'1'	=> $customer_id . 'x0',
					'2'	=> 0
				];
			} else {
				$data['active_fingers'] = [
					'1'	=> 0,
					'2'	=> 0
				];
			}
		}

		$data['registered_fingers'] = [];

		foreach ($fingers as $key => $finger) {
			if (!$key) {
				$data['registered_fingers'][] = [
					'index'	=> $customer_id . 'x' . $finger['finger_index'],
					'text'	=> $data['text_index_old']
				];
			} else {
				$i = str_split($key);

				$data['registered_fingers'][] = [
					'index'	=> $customer_id . 'x' . $finger['finger_index'],
					'text'	=> $data['text_' . $finger_indexes[$i[1]]] . ' ' . $hands[$i[0]]
				];
			}
		}

		$data['customer_id'] = $customer_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/finger_form', $data));
	}

	public function register()
	{
		$this->load->language('customer/finger');

		$finger_index = isset($this->request->get['finger_index']) ? $this->request->get['finger_index'] : 0;

		if ($this->validateRegister()) {
			$register_path	= HTTP_SERVER . 'model/finger/register.php';

			// $url_register	= ($register_path . '?customer_id=' . $finger_index . '&user_id=' . $this->user->getId() . '&token=' . $this->session->data['token']);
			// $register = $url_register;
			$url_register	= base64_encode($register_path . '?customer_id=' . $finger_index . '&user_id=' . $this->user->getId() . '&token=' . $this->session->data['token']);
			$register = 'finspot:FingerspotReg;' . $url_register;

			$this->response->redirect($register);
		}

		$this->manage();
	}

	public function verification()
	{
		$this->load->language('customer/finger');

		$finger_index = isset($this->request->get['finger_index']) ? $this->request->get['finger_index'] : 0;

		if ($this->validateVerification()) {
			// if ($this->request->server['HTTPS']) {
			// 	$server = HTTPS_SERVER;
			// } else {
			// 	$server = HTTP_SERVER;
			// }

			$verification_path	= HTTP_SERVER . 'model/finger/verification.php';
			// $verification_path	= $server . 'model/finger/verification.php';

			$url_verification	= base64_encode($verification_path . '?customer_id=' . $finger_index);
			$verification = 'finspot:FingerspotVer;' . $url_verification;
			// $url_verification	= ($verification_path . '?customer_id=' . $finger_index);
			// $verification = $url_verification;

			$this->response->redirect($verification);
		}

		$this->index();
	}

	public function getRegisterStatus()
	{
		$this->load->language('customer/finger');

		$json = array();

		list($customer_id, $finger_index) = explode('x', $this->request->get['finger_index']);

		$this->load->model('customer/finger');
		$finger_info = $this->model_customer_finger->getFingerByCustomerId($customer_id, $finger_index);

		if ($finger_info) {
			$this->session->data['success'] = $this->language->get('text_success_register');

			$json['reg_status'] = 1;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// public function getVerificationStatus()
	// {
	// 	$this->load->language('customer/finger');

	// 	$json = array();

	// 	$this->load->model('customer/finger');
	// 	$finger_info = $this->model_customer_finger->getFingerByCustomerId($this->request->get['customer_id']);

	// 	if ($finger_info) {
	// 		$json['ver_status'] = 1;
	// 		$json['name'] = $finger_info['name'];
	// 		$json['date'] = date($this->language->get('date_format_jMY'));
	// 	}

	// 	$this->response->addHeader('Content-Type: application/json');
	// 	$this->response->setOutput(json_encode($json));
	// }

	protected function validateRegister()
	{
		list($customer_id, $finger_index) = explode('x', $this->request->get['finger_index']);

		if (!$this->user->hasPermission('modify', 'customer/finger')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ($this->user->getCustomerDepartmentId()) {
			$this->load->model('common/payroll');
			$customer_info = $this->model_common_payroll->getCustomer($customer_id);

			if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
				$this->error['warning'] = $this->language->get('error_customer_department');
			}
		}

		$this->load->model('customer/finger');
		$finger_count = $this->model_customer_finger->getFingersCount($customer_id, $finger_index);

		if ($finger_count) {
			$this->error['warning'] = $this->language->get('error_template_exist');
		}

		return !$this->error;
	}

	protected function validateVerification()
	{
		list($customer_id, $finger_index) = explode('x', $this->request->get['finger_index']);

		if (!$this->user->hasPermission('modify', 'customer/finger')) {
			$this->error['warning'] = $this->language->get('error_permission');
		} elseif ($this->user->getCustomerDepartmentId()) {
			$this->load->model('common/payroll');
			$customer_info = $this->model_common_payroll->getCustomer($customer_id);

			if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
				$this->error['warning'] = $this->language->get('error_customer_department');
			}
		}

		$this->load->model('customer/finger');
		$finger_count = $this->model_customer_finger->getFingersCount($customer_id, $finger_index);

		if (!$finger_count) {
			$this->error['warning'] = $this->language->get('error_not_found');
		}

		return !$this->error;
	}

	protected function validateForm()
	{
		$this->load->model('presence/presence');
		$customer_add_info = $this->model_presence_presence->getCustomerAddData($this->request->get['customer_id']);

		# Active_finger: Save pertama diijinkan, tp edit hanya oleh bypass
		if (!empty($customer_add_info['active_finger'])) {
			if (!$this->user->hasPermission('bypass', 'customer/finger')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
		} else {
			if (!$this->user->hasPermission('modify', 'customer/finger')) {
				$this->error['warning'] = $this->language->get('error_permission');
			}
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('bypass', 'customer/finger')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function deleteFinger()
	{
		$this->load->language('customer/finger');

		$json = array();

		if ($this->validateDelete()) {
			$this->load->model('customer/finger');

			list($customer_id, $finger_index) = explode('x', $this->request->post['finger_index']);

			$this->model_customer_finger->deleteFingerByCustomerId($customer_id, $finger_index);

			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->error['warning'];
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete()
	{
		$json = array();

		if (isset($this->request->get['filter_name'])) {
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			$this->load->model('customer/customer');

			$filter_data = array(
				'filter_name'	=> $filter_name,
				'filter_active'	=> '*',
				'start'       	=> 0,
				'limit'        	=> 15
			);

			$results = $this->model_customer_customer->getCustomers($filter_data);

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
}
