<?php
class ControllerCustomerCustomer extends Controller
{
	private $error = array();

	public function index()
	{
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		$this->getList();
	}

	public function add()
	{
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$customer_id = $this->db->transaction(function () {
				return $this->model_customer_customer->addCustomer($this->request->post);
			});

			// Add to customer_history log
			$this->load->model('customer/history');
			$this->load->model('common/payroll');

			$history_data = array(
				'date' 					=> $this->request->post['date_start'],
				'customer_id' 			=> $customer_id,
				'name'        			=> $this->request->post['firstname'] . ' [' . $this->request->post['lastname'] . ']',
				'customer_group'		=> $this->model_common_payroll->getCustomerGroup($this->request->post['customer_group_id']),
				'customer_department'	=> $this->model_common_payroll->getCustomerDepartment($this->request->post['customer_department_id']),
				'location'				=> $this->model_common_payroll->getLocation($this->request->post['location_id'])
			);

			$this->model_customer_history->addHistory('register2', $history_data);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

			$this->response->redirect($this->url->link('customer/customer', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit()
	{
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->customerHistory();

			$this->db->transaction(function () {
				$this->model_customer_customer->editCustomer($this->request->get['customer_id'], $this->request->post);
			});

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

			$this->response->redirect($this->url->link('customer/customer', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete()
	{
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');
		$this->load->model('customer/history');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $customer_id) {
				// Add to customer_history log
				$customer_info = $this->model_customer_customer->getCustomer($customer_id);

				$history_data = array(
					'date' 			=> date($this->language->get('date_format_jMY')),
					'name'        	=> $customer_info['firstname'] . ' [' . $customer_info['lastname'] . ']'
				);

				$this->model_customer_history->addHistory('delete', $history_data);

				$this->model_customer_customer->deleteCustomer($customer_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

			$this->response->redirect($this->url->link('customer/customer', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function customerHistory()
	{
		$this->load->model('customer/history');
		$this->load->model('common/payroll');

		$history_data = array(
			'date' 					=> '',
			'customer_id' 			=> $this->request->get['customer_id'],
			'name'        			=> $this->request->post['firstname'] . ' [' . $this->request->post['lastname'] . ']'
		);

		// if (isset($this->request->post['date_end']) && $this->request->post['date_end']) {
		// $history_data['date'] = $this->request->post['date_end'];

		// $key = 'date_end';
		// } elseif (isset($this->request->post['date_start'])) {
		// $history_data['date'] = $this->request->post['date_start'];

		// $key = 'reactivate2';

		// $additional_data = [
		// 	'customer_group' 		=> $this->model_common_payroll->getCustomerGroup($this->request->post['customer_group_id']),
		// 	'customer_department' 	=> $this->model_common_payroll->getCustomerDepartment($this->request->post['customer_department_id']),
		// 	'location' 				=> $this->model_common_payroll->getLocation($this->request->post['location_id'])
		// ];

		// $history_data = array_merge($history_data, $additional_data);

		// } elseif (!isset($date)) {
		$history_data['date'] = date($this->language->get('date_format_jMY'));

		$customer_info = $this->model_common_payroll->getCustomer($this->request->get['customer_id']);

		if ($this->request->post['customer_department_id'] != $customer_info['customer_department_id'] || $this->request->post['customer_group_id'] != $customer_info['customer_group_id'] || $this->request->post['location_id'] != $customer_info['location_id']) {
			$key = 'mutation2';

			$additional_data = [
				'customer_group_from' 		=> $customer_info['customer_group'],
				'customer_department_from' 	=> $customer_info['customer_department'],
				'location_from' 			=> $customer_info['location'],
				'customer_group_to' 		=> $this->model_common_payroll->getCustomerGroup($this->request->post['customer_group_id']),
				'customer_department_to' 	=> $this->model_common_payroll->getCustomerDepartment($this->request->post['customer_department_id']),
				'location_to' 				=> $this->model_common_payroll->getLocation($this->request->post['location_id'])
			];

			$history_data = array_merge($history_data, $additional_data);
		} else {
			$key = 'edit';
		}
		// }

		$this->model_customer_history->addHistory($key, $history_data);
	}

	public function unlock()
	{
		$this->load->language('customer/customer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/customer');

		if (isset($this->request->get['email']) && $this->validateUnlock()) {
			$this->model_customer_customer->deleteLoginAttempts($this->request->get['email']);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['filter_name'])) {
				$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
			}

			if (isset($this->request->get['filter_customer_department_id'])) {
				$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

			$this->response->redirect($this->url->link('customer/customer', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList()
	{
		if (isset($this->request->get['filter_name'])) {
			$filter_name = $this->request->get['filter_name'];
		} else {
			$filter_name = null;
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$filter_customer_department_id = $this->request->get['filter_customer_department_id'];
		} else {
			$filter_customer_department_id = null;
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

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
			'href' => $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('customer/customer/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('customer/customer/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['customers'] = array();

		$filter_data = array(
			'filter_name'              		=> $filter_name,
			'filter_customer_department_id' => $filter_customer_department_id,
			'filter_customer_group_id' 		=> $filter_customer_group_id,
			'filter_location_id'       		=> $filter_location_id,
			'filter_status'            		=> $filter_status,
			'filter_date_start'        		=> $filter_date_start,
			'filter_active'            		=> $filter_active,
			'sort'                     		=> $sort,
			'order'                    		=> $order,
			'start'                    		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    		=> $this->config->get('config_limit_admin')
		);

		$customer_total = $this->model_customer_customer->getTotalCustomers($filter_data);

		$results = $this->model_customer_customer->getCustomers($filter_data);

		$this->load->model('tool/image');

		foreach ($results as $result) {
			$image = $this->model_tool_image->resize($result['image'], 60, 60);

			$login_info = $this->model_customer_customer->getTotalLoginAttempts($result['email']);

			if ($login_info && $login_info['total'] >= $this->config->get('config_login_attempts')) {
				$unlock = $this->url->link('customer/customer/unlock', 'token=' . $this->session->data['token'] . '&email=' . $result['email'] . $url, true);
			} else {
				$unlock = '';
			}

			$data['customers'][] = array(
				'customer_id'    		=> $result['customer_id'],
				'image'            		=> $image,
				'nip'            		=> $result['nip'],
				'name'           		=> $result['name'],
				'customer_department'	=> $result['customer_department'],
				'customer_group' 		=> $result['customer_group'],
				'location'       		=> $result['location'],
				'date_start'     		=> date($this->language->get('date_format_jMY'), strtotime($result['date_start'])),
				'date_end'     	 		=> empty($result['date_end']) ? '-' : date($this->language->get('date_format_jMY'), strtotime($result['date_end'])),
				'unlock'         		=> $unlock,
				'edit'           		=> $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
				'view'           		=> $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'], true)
			);
		}

		$language_items = array(
			'heading_title',
			'text_list',
			'text_all',
			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',
			'text_default',
			'text_no_results',
			'text_confirm',
			'text_active',
			'text_inactive',
			'entry_name',
			'entry_email',
			'entry_customer_department',
			'entry_customer_group',
			'entry_location',
			'entry_status',
			'entry_date_start',
			'entry_active',
			'column_image',
			'column_nip',
			'column_name',
			'column_customer_department',
			'column_customer_group',
			'column_location',
			'column_date_start',
			'column_date_end',
			'column_username',
			'column_action',
			'button_filter',
			'button_add',
			'button_edit',
			'button_delete',
			'button_login',
			'button_unlock',
			'button_view'
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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

		$data['sort_nip'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_department'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_customer_group'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);
		$data['sort_date_start'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=date_start' . $url, true);
		$data['sort_date_end'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&sort=date_end' . $url, true);

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
		$pagination->url = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_total - $this->config->get('config_limit_admin'))) ? $customer_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_total, ceil($customer_total / $this->config->get('config_limit_admin')));

		$data['filter_name'] = $filter_name;
		$data['filter_customer_department_id'] = $filter_customer_department_id;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_status'] = $filter_status;
		$data['filter_location_id'] = $filter_location_id;
		$data['filter_date_start'] = $filter_date_start;
		$data['filter_active'] = $filter_active;

		$this->load->model('customer/customer_department');
		$data['customer_departments'] = $this->model_customer_customer_department->getCustomerDepartments();

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('setting/store');
		$data['stores'] = $this->model_setting_store->getStores();

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_list', $data));
	}

	protected function getForm()
	{
		$this->db->createView('v_customer');
		// $this->model_customer_customer->createView();

		$data['text_form'] = !isset($this->request->get['customer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',
			'text_select',
			'text_none',
			'text_loading',
			'text_add_ban_ip',
			'text_remove_ban_ip',
			'entry_acc_no',
			'entry_address_1',
			'entry_address_2',
			'entry_children',
			'entry_city',
			'entry_confirm',
			'entry_country',
			'entry_customer_department',
			'entry_customer_group',
			'entry_date_birth',
			'entry_date_end',
			'entry_date_start',
			'entry_default',
			'entry_email',
			'entry_employment_insurance',
			'entry_employment_insurance_id',
			'entry_firstname',
			'entry_full_overtime',
			'entry_gender',
			'entry_health_insurance',
			'entry_health_insurance_id',
			'entry_id_card_address',
			'entry_image',
			'entry_insurance',
			'entry_lastname',
			'entry_life_insurance',
			'entry_location',
			'entry_marriage_status',
			'entry_nik',
			'entry_nip',
			'entry_npwp_address',
			'entry_npwp',
			'entry_password',
			'entry_payroll_include',
			'entry_payroll_method',
			'entry_pension_insurance',
			'entry_postcode',
			'entry_registered_wage',
			'entry_skip_trial_status',
			'entry_status',
			'entry_telephone',
			'entry_zone',
			'help_lastname',
			'help_skip_trial_status',
			'help_health_insurance',
			'help_employment_insurance',
			'help_npwp_address',
			'help_registered_wage',
			'button_save',
			'button_cancel',
			'button_address_add',
			'button_remove',
			'button_upload',
			// 'button_reactivate',
			'tab_general',
			'tab_additional',
			'tab_address'
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

		$errors = array(
			'warning',
			'nik',
			'firstname',
			'lastname',
			'customer_department',
			'customer_group',
			'date_start',
			// 'date_end',
			'email',
			'telephone',
			'npwp_address',
			'health_insurance_id',
			'employment_insurance_id',
			'password',
			'confirm'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		if (isset($this->error['address'])) {
			$data['error_address'] = $this->error['address'];
		} else {
			$data['error_address'] = array();
		}

		$url = '';

		if (isset($this->request->get['filter_name'])) {
			$url .= '&filter_name=' . urlencode(html_entity_decode($this->request->get['filter_name'], ENT_QUOTES, 'UTF-8'));
		}

		if (isset($this->request->get['filter_customer_department_id'])) {
			$url .= '&filter_customer_department_id=' . $this->request->get['filter_customer_department_id'];
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
			'href' => $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['customer_id'])) {
			$data['action'] = $this->url->link('customer/customer/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['customer_id'])) {
			$customer_info = $this->model_customer_customer->getCustomer($this->request->get['customer_id']);
		}

		$input_items = array(
			'image'						=> null,
			'customer_department_id'	=> null,
			'customer_group_id'			=> null,
			'location_id'				=> null,
			'gender_id'					=> null,
			'marriage_status_id'		=> 1,
			'payroll_method_id'			=> null,
			'nik'						=> null,
			'firstname'					=> null,
			'lastname'					=> null,
			'skip_trial_status'			=> true,
			'email'						=> null,
			'telephone'					=> null,
			'children'					=> null,
			'payroll_include'			=> true,
			'children'					=> null,
			'npwp'						=> null,
			'npwp_address'				=> null,
			'acc_no'					=> null,
			'full_overtime'				=> null,
			'health_insurance'			=> true,
			'life_insurance'			=> true,
			'employment_insurance'		=> false,
			'pension_insurance'			=> false,
			'registered_wage'			=> null,
			'health_insurance_id'		=> null,
			'employment_insurance_id'	=> null,
			'status'					=> true,
			'id_card_address_id'		=> null,
			'address_id'				=> null
		);
		foreach ($input_items as $input_item => $default_value) {
			if (isset($this->request->post[$input_item])) {
				$data[$input_item] = $this->request->post[$input_item];
			} elseif (!empty($customer_info)) {
				$data[$input_item] = $customer_info[$input_item];
			} else {
				$data[$input_item] = $default_value;
			}
		}

		if (isset($this->request->post['firstname'])) {
			$fields_data = [
				'skip_trial_status',
				'health_insurance',
				'employment_insurance',
				'pension_insurance',
				'life_insurance'
			];
			foreach ($fields_data as $field_data) {
				if (!isset($this->request->post[$field_data])) {
					$data[$field_data] = 0;
				}
			}
		}

		if (!empty($customer_info)) {
			$data['nip'] = $customer_info['nip'];
		} else {
			$data['nip'] = '-';
		}

		$this->load->model('tool/image');

		if (!empty($data['image']) && is_file(DIR_IMAGE . $data['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($data['image'], 200, 200);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 200, 200);
		}

		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($customer_info)) {
			$data['date_start'] = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start']));
		} else {
			$data['date_start'] = '';
		}

		if (isset($this->request->post['date_end'])) {
			$data['date_end'] = $this->request->post['date_end'];
		} elseif (!empty($customer_info) && $customer_info['date_end']) {
			$data['date_end'] = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_end']));
		} else {
			$data['date_end'] = '';
		}

		if (isset($this->request->post['date_birth'])) {
			$data['date_birth'] = $this->request->post['date_birth'];
		} elseif (!empty($customer_info) && $customer_info['date_birth']) {
			$data['date_birth'] = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_birth']));
		} else {
			$data['date_birth'] = null;
		}

		if (isset($this->request->post['address'])) {
			$data['addresses'] = $this->request->post['address'];
		} elseif (isset($this->request->get['customer_id'])) {
			$data['addresses'] = $this->model_customer_customer->getAddresses($this->request->get['customer_id']);
		} else {
			$data['addresses'] = array();
		}

		$this->load->model('customer/customer_department');
		$data['customer_departments'] = $this->model_customer_customer_department->getCustomerDepartments();

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('localisation/gender');
		$data['genders'] = $this->model_localisation_gender->getGenders();

		$this->load->model('localisation/marriage_status');
		$data['marriage_statuses'] = $this->model_localisation_marriage_status->getMarriageStatuses();

		$this->load->model('localisation/payroll_method');
		$data['payroll_methods'] = $this->model_localisation_payroll_method->getPayrollMethods();

		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();

		// Custom Fields
		$this->load->model('customer/custom_field');

		$data['custom_fields'] = array();

		$filter_data = array(
			'sort'  => 'cf.sort_order',
			'order' => 'ASC'
		);

		$custom_fields = $this->model_customer_custom_field->getCustomFields($filter_data);

		foreach ($custom_fields as $custom_field) {
			$data['custom_fields'][] = array(
				'custom_field_id'    => $custom_field['custom_field_id'],
				'custom_field_value' => $this->model_customer_custom_field->getCustomFieldValues($custom_field['custom_field_id']),
				'name'               => $custom_field['name'],
				'value'              => $custom_field['value'],
				'type'               => $custom_field['type'],
				'location'           => $custom_field['location'],
				'sort_order'         => $custom_field['sort_order']
			);
		}

		if (isset($this->request->post['custom_field'])) {
			$data['account_custom_field'] = $this->request->post['custom_field'];
		} elseif (!empty($customer_info)) {
			$data['account_custom_field'] = json_decode($customer_info['custom_field'], true);
		} else {
			$data['account_custom_field'] = array();
		}

		$generated_password = token(20);

		if (isset($this->request->post['password'])) {
			$data['password'] = $this->request->post['password'];
		} else {
			if (isset($this->request->get['customer_id'])) {
				$data['password'] = '';
			} else {
				$data['password'] = $generated_password;
			}
		}

		if (isset($this->request->post['confirm'])) {
			$data['confirm'] = $this->request->post['confirm'];
		} else {
			if (isset($this->request->get['customer_id'])) {
				$data['confirm'] = '';
			} else {
				$data['confirm'] = $generated_password;
			}
		}

		$data['date_start_locked'] = false;
		$data['date_end_locked'] = false;
		$data['skip_trial_status_locked'] = false;

		if (isset($this->request->get['customer_id'])) {
			if (isset($data['date_start']) && !empty(strtotime($data['date_start']))) {
				$data['date_start_locked'] = true;
				$data['skip_trial_status_locked'] = true;
			}

			if (!empty(strtotime($data['date_end']))) {
				$data['date_end_locked'] = true;
			}
		}

		$data['help_registered_wage_default'] = sprintf($this->language->get('help_registered_wage_default'), $this->language->get('text_' . $this->config->get('insurance_calculation_base')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/customer_form', $data));
	}

	protected function validateForm()
	{
		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['nik'] && ((utf8_strlen($this->request->post['nik']) != 16) || !preg_match('/^\d*$/', $this->request->post['nik']))) {
			$this->error['nik'] = $this->language->get('error_nik');
		}

		if ((utf8_strlen($this->request->post['firstname']) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen($this->request->post['lastname']) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 64)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if (empty($this->request->post['customer_department_id'])) {
			$this->error['customer_department'] = $this->language->get('error_customer_department');
		}

		if (empty($this->request->post['customer_group_id'])) {
			$this->error['customer_group'] = $this->language->get('error_customer_group');
		}

		if (!isset($this->request->get['customer_id'])) {
			if (!$this->request->post['date_start'] || empty(strtotime($this->request->post['date_start']))) {
				$this->error['date_start'] = $this->language->get('error_date_start');
			}
		} else {
			if (isset($this->request->post['date_start']) && empty(strtotime($this->request->post['date_start']))) {
				$this->error['date_start'] = $this->language->get('error_date_start');
			}
		}

		// if (!empty($this->request->post['date_end']) && empty(strtotime($this->request->post['date_end']))) {
		// 	$this->error['date_end'] = $this->language->get('error_date_end');
		// }

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ($this->request->post['npwp']) {
			if ((utf8_strlen($this->request->post['npwp_address']) < 3) || (utf8_strlen(trim($this->request->post['npwp_address'])) > 255)) {
				$this->error['npwp_address'] = $this->language->get('error_npwp_address');
			}
		}

		if ($this->request->post['health_insurance_id'] && ((utf8_strlen($this->request->post['health_insurance_id']) != 13) || !preg_match('/^\d*$/', $this->request->post['health_insurance_id']))) {
			$this->error['health_insurance_id'] = $this->language->get('error_health_insurance_id');
		}

		if ($this->request->post['employment_insurance_id'] && ((utf8_strlen($this->request->post['employment_insurance_id']) != 11) || !preg_match('/^\d*$/', $this->request->post['employment_insurance_id']))) {
			$this->error['employment_insurance_id'] = $this->language->get('error_employment_insurance_id');
		}

		$customer_info = $this->model_customer_customer->getCustomerByEmail($this->request->post['email']);

		if (!isset($this->request->get['customer_id'])) {
			if ($customer_info) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		} else {
			if ($customer_info && ($this->request->get['customer_id'] != $customer_info['customer_id'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		if ($this->request->post['nik']) {
			$customer_by_nik_info = $this->model_customer_customer->getCustomerByNik($this->request->post['nik']);

			if (!isset($this->request->get['customer_id'])) {
				if ($customer_by_nik_info) {
					$this->error['nik'] = $this->language->get('error_nik_exists');
				}
			} else {
				if ($customer_by_nik_info && ($this->request->get['customer_id'] != $customer_by_nik_info['customer_id'])) {
					$this->error['nik'] = $this->language->get('error_nik_exists');
				}
			}
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		// Custom field validation
		$this->load->model('customer/custom_field');

		$custom_fields = $this->model_customer_custom_field->getCustomFields(array('filter_customer_group_id' => $this->request->post['customer_group_id']));

		foreach ($custom_fields as $custom_field) {
			if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			} elseif (($custom_field['type'] == 'text' && !empty($custom_field['validation']) && $custom_field['location'] == 'account') && !filter_var($this->request->post['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field_validate'), $custom_field['name']);
			}
		}

		if ($this->request->post['password'] || (!isset($this->request->get['customer_id']))) {
			if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}

			if ($this->request->post['password'] != $this->request->post['confirm']) {
				$this->error['confirm'] = $this->language->get('error_confirm');
			}
		}

		if (isset($this->request->post['address'])) {
			foreach ($this->request->post['address'] as $key => $value) {
				if ((utf8_strlen($value['address_1']) < 3) || (utf8_strlen($value['address_1']) > 128)) {
					$this->error['address'][$key]['address_1'] = $this->language->get('error_address_1');
				}

				if ($value['country_id'] == '') {
					$this->error['address'][$key]['country'] = $this->language->get('error_country');
				}

				if (!isset($value['zone_id']) || $value['zone_id'] == '') {
					$this->error['address'][$key]['zone'] = $this->language->get('error_zone');
				}

				if (!isset($value['city']) || $value['city'] == '') {
					$this->error['address'][$key]['city'] = $this->language->get('error_city');
				}

				foreach ($custom_fields as $custom_field) {
					if (($custom_field['location'] == 'address') && $custom_field['required'] && empty($value['custom_field'][$custom_field['custom_field_id']])) {
						$this->error['address'][$key]['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
					} elseif (($custom_field['type'] == 'text' && !empty($custom_field['validation']) && $custom_field['location'] == 'address') && !filter_var($value['custom_field'][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
						$this->error['address'][$key]['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field_validate'), $custom_field['name']);
					}
				}
			}
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('bypass', 'customer/customer')) {
			$this->error['warning'] = $this->language->get('error_permission_delete');
		}

		return !$this->error;
	}

	protected function validateUnlock()
	{
		if (!$this->user->hasPermission('modify', 'customer/customer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}

	public function login()
	{
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		$this->load->model('customer/customer');

		$customer_info = $this->model_customer_customer->getCustomer($customer_id);

		if ($customer_info) {
			// Create token to login with
			$token = token(64);

			$this->model_customer_customer->editToken($customer_id, $token);

			if (isset($this->request->get['store_id'])) {
				$store_id = $this->request->get['store_id'];
			} else {
				$store_id = 0;
			}

			$this->load->model('setting/store');

			$store_info = $this->model_setting_store->getStore($store_id);

			if ($store_info) {
				$this->response->redirect($store_info['url'] . 'index.php?route=account/login&token=' . $token);
			} else {
				$this->response->redirect(HTTP_CATALOG . 'index.php?route=account/login&token=' . $token);
			}
		} else {
			$this->load->language('error/not_found');

			$this->document->setTitle($this->language->get('heading_title'));

			$data['heading_title'] = $this->language->get('heading_title');

			$data['text_not_found'] = $this->language->get('text_not_found');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], true)
			);

			$data['header'] = $this->load->controller('common/header');
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['footer'] = $this->load->controller('common/footer');

			$this->response->setOutput($this->load->view('error/not_found', $data));
		}
	}

	/* 	public function ip() {
		$this->load->language('customer/customer');

		$this->load->model('customer/customer');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_total'] = $this->language->get('column_total');
		$data['column_date_added'] = $this->language->get('column_date_added');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['ips'] = array();

		$results = $this->model_customer_customer->getIps($this->request->get['customer_id'], ($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['ips'][] = array(
				'ip'         => $result['ip'],
				'total'      => $this->model_customer_customer->getTotalCustomersByIp($result['ip']),
				'date_added' => date('d/m/y', strtotime($result['date_added'])),
				'filter_ip'  => $this->url->link('customer/customer', 'token=' . $this->session->data['token'] . '&filter_ip=' . $result['ip'], true)
			);
		}

		$ip_total = $this->model_customer_customer->getTotalIps($this->request->get['customer_id']);

		$pagination = new Pagination();
		$pagination->total = $ip_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('customer/customer/ip', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($ip_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($ip_total - 10)) ? $ip_total : ((($page - 1) * 10) + 10), $ip_total, ceil($ip_total / 10));

		$this->response->setOutput($this->load->view('customer/customer_ip', $data));
	}
 */
	public function autocomplete()
	{
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

			$this->load->model('customer/customer');

			$filter_data = array(
				'filter_name'	=> $filter_name,
				'filter_email'	=> $filter_email,
				'filter_active'	=> '*',
				'start'       	=> 0,
				'limit'        	=> 15
			);

			$results = $this->model_customer_customer->getCustomers($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'customer_id'       		=> $result['customer_id'],
					// 'customer_department_id'	=> $result['customer_department_id'],
					// 'customer_group_id' 		=> $result['customer_group_id'],
					'name'              		=> strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')),
					// 'customer_department'    	=> $result['customer_department'],
					// 'customer_group'    		=> $result['customer_group'],
					// 'nip'          				=> $result['nip'],
					// 'firstname'         		=> $result['firstname'],
					// 'lastname'          		=> $result['lastname'],
					'email'             		=> $result['email'],
					// 'telephone'         		=> $result['telephone'],
					// 'custom_field'      		=> json_decode($result['custom_field'], true),
					// 'address'           		=> $this->model_customer_customer->getAddresses($result['customer_id'])
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

	public function customfield()
	{
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

	public function address()
	{
		$json = array();

		if (!empty($this->request->get['address_id'])) {
			$this->load->model('customer/customer');

			$json = $this->model_customer_customer->getAddress($this->request->get['address_id']);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	// Bonk
	public function city()
	{
		$output = '<option value="">' . $this->language->get('text_select') . '</option>';

		$this->load->model('localisation/city');

		$results = $this->model_localisation_city->getCitiesByZoneId($this->request->get['zone_id']);

		foreach ($results as $result) {
			$output .= '<option value="' . $result['city'] . '"';

			if (isset($this->request->get['city']) && ($this->request->get['city'] == $result['city'])) {
				$output .= ' selected="selected"';
			}

			$output .= '>' . $result['name'] . '</option>';
		}

		if (!$results) {
			$output = '<option value="0">' . $this->language->get('text_none') . '</option>';
		} else {
			$output .= '<option value="0">' . $this->language->get('text_none') . '</option>';
		}

		$this->response->setOutput($output);
	}
}
