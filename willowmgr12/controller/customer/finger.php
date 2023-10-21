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

		// $url = '';

		// if (isset($this->request->get['filter_name'])) {
		// 	$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		// }

		// if (isset($this->request->get['filter_customer_group_id'])) {
		// 	$url .= '&filter_customer_group_id=' . $this->request->get['filter_customer_group_id'];
		// }

		// if (isset($this->request->get['filter_location_id'])) {
		// 	$url .= '&filter_location_id=' . $this->request->get['filter_location_id'];
		// }

		// if (isset($this->request->get['filter_status'])) {
		// 	$url .= '&filter_status=' . $this->request->get['filter_status'];
		// }

		// if (isset($this->request->get['sort'])) {
		// 	$url .= '&sort=' . $this->request->get['sort'];
		// }

		// if (isset($this->request->get['order'])) {
		// 	$url .= '&order=' . $this->request->get['order'];
		// }

		// if (isset($this->request->get['page'])) {
		// 	$url .= '&page=' . $this->request->get['page'];
		// }

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
			'filter_name'	   	   			=> $filter_name,
			'filter_customer_group_id'		=> $filter_customer_group_id,
			'filter_location_id'   			=> $filter_location_id,
			'filter_status' 	   			=> $filter_status,
			'sort'                 			=> $sort,
			'order'                			=> $order,
			'start'                			=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                			=> $this->config->get('config_limit_admin')
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
		$this->load->language('customer/finger');

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

		$this->index();
	}

	public function verification() {
		$this->load->language('customer/finger');

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		if ($this->validateVerification()) {
			// if ($this->request->server['HTTPS']) {
			// 	$server = HTTPS_SERVER;
			// } else {
			// 	$server = HTTP_SERVER;
			// }

			$verification_path	= HTTP_SERVER . 'model/finger/verification.php';
			// $verification_path	= $server . 'model/finger/verification.php';
										
			$url_verification	= base64_encode($verification_path . '?customer_id=' . $customer_id);
			$verification = 'finspot:FingerspotVer;' . $url_verification;
			// $url_verification	= ($verification_path . '?customer_id=' . $customer_id);
			// $verification = $url_verification;
			
			$this->response->redirect($verification);
		}

		$this->index();
	}

	public function getRegisterStatus() {
		$this->load->language('customer/finger');

		$json = array();
		
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
		} elseif ($this->user->getCustomerDepartmentId()) {
			$this->load->model('common/payroll');
			$customer_info = $this->model_common_payroll->getCustomer($this->request->get['customer_id']);

			if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
				$this->error['warning'] = $this->language->get('error_customer_department');
			}
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
		} elseif ($this->user->getCustomerDepartmentId()) {
			$this->load->model('common/payroll');
			$customer_info = $this->model_common_payroll->getCustomer($this->request->get['customer_id']);

			if ($this->user->getCustomerDepartmentId() != $customer_info['customer_department_id']) {
				$this->error['warning'] = $this->language->get('error_customer_department');
			}
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

			$this->model_customer_finger->deleteFingerByCustomerId($this->request->post['customer_id']);

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
