<?php
class ControllerCustomerDocumentType extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/document_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document_type');

		$this->getList();
	}

	public function add() {
		$this->load->language('customer/document_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_document_type->addDocumentType($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('customer/document_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_document_type->editDocumentType($this->request->get['document_type_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('customer/document_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $document_type_id) {
				$this->model_customer_document_type->deleteDocumentType($document_type_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'sort_order';
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
			'href' => $this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('customer/document_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('customer/document_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['document_types'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$document_count = $this->model_customer_document_type->getDocumentTypesCount();

		$results = $this->model_customer_document_type->getDocumentTypes($filter_data);

		foreach ($results as $result) {
			$data['document_types'][] = array(
				'document_type_id' 	=> $result['document_type_id'],
				'title'         => $result['title'],
				'sort_order'    => $result['sort_order'],
				'required'    	=> $result['required'] ? $this->language->get('text_yes') : $this->language->get('text_no'),
				'status'    	=> $result['status'] ? $this->language->get('text_enabled') : $this->language->get('text_disabled'),
				'edit'          => $this->url->link('customer/document_type/edit', 'token=' . $this->session->data['token'] . '&document_type_id=' . $result['document_type_id'] . $url, true)
			);
		}

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_title',
			'column_required',
			'column_status',
			'column_sort_order',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_title'] = $this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . '&sort=title' . $url, true);
		$data['sort_sort_order'] = $this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $document_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($document_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($document_count - $this->config->get('config_limit_admin'))) ? $document_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $document_count, ceil($document_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/document_type_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['document_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_enabled',
			'text_disabled',
			'text_yes',
			'text_no',
			'help_description',
			'entry_title',
			'entry_description',
			'entry_required',
			'entry_status',
			'entry_sort_order',
			'button_save',
			'button_cancel'
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

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}

		$url = '';

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
			'href' => $this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['document_type_id'])) {
			$data['action'] = $this->url->link('customer/document_type/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('customer/document_type/edit', 'token=' . $this->session->data['token'] . '&document_type_id=' . $this->request->get['document_type_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('customer/document_type', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['document_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$document_type_info = $this->model_customer_document_type->getDocumentType($this->request->get['document_type_id']);
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($document_type_info)) {
			$data['title'] = $document_type_info['title'];
		} else {
			$data['title'] = '';
		}

		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($document_type_info)) {
			$data['description'] = $document_type_info['description'];
		} else {
			$data['description'] = '';
		}

		if (isset($this->request->post['required'])) {
			$data['required'] = $this->request->post['required'];
		} elseif (!empty($document_type_info)) {
			$data['required'] = $document_type_info['required'];
		} else {
			$data['required'] = true;
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($document_type_info)) {
			$data['status'] = $document_type_info['status'];
		} else {
			$data['status'] = true;
		}

		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($document_type_info)) {
			$data['sort_order'] = $document_type_info['sort_order'];
		} else {
			$data['sort_order'] = 0;
		}

		// $this->load->model('design/layout');

		// $data['layouts'] = $this->model_design_layout->getLayouts();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/document_type_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customer/document_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['title']) < 2) || (utf8_strlen($this->request->post['title']) > 64)) {
			$this->error['title'] = $this->language->get('error_title');
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'customer/document_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function info() {
		$this->load->language('customer/document_type');

		$data['title'] = $this->language->get('heading_title');

		$this->load->model('customer/document_type');
		$this->load->model('common/payroll');

		if ($this->request->server['HTTPS']) {
			$data['base'] = HTTPS_SERVER;
		} else {
			$data['base'] = HTTP_SERVER;
		}

		$data['direction'] = $this->language->get('direction');
		$data['lang'] = $this->language->get('code');

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['document_type_id'])) {
			$document_type_id = $this->request->get['document_type_id'];
		} else {
			$document_type_id = 0;
		}

		$customer_info = $this->model_common_payroll->getCustomer($customer_id);
		$document_type_info = $this->model_customer_document_type->getDocumentType($document_type_id);

		if ($customer_info && $document_type_info) {
			$data['title'] = $document_type_info['title'];
			
			$store_name = $this->config->get('config_name');
			$store_address = $this->config->get('config_address');
			$store_email = $this->config->get('config_email');
			$store_telephone = $this->config->get('config_telephone');

			
			if ($customer_info['address_id']) {
				$this->load->model('customer/customer');
				$address_info = $this->model_customer_customer->getAddress($customer_info['address_id']);
			}
			
			$find = array(
				'{store_name}',
				'{store_address}',
				'{store_email}',
				'{store_telephone}',
				'{name}',
				'{fullname}',
				'{address_1}',
				'{address_2}',
				'{postcode}',
				'{district}',
				'{city}',
				'{province}',
				'{department}',
				'{location}',
				'{date_start}',
				
			);

			$replace = array(
				'store_name' 		=> $this->config->get('config_name'),
				'store_address' 	=> $this->config->get('config_address'),
				'store_email' 		=> $this->config->get('config_email'),
				'store_telephone'	=> $this->config->get('config_telephone'),
				'name' 				=> $customer_info['firstname'],
				'fullname'  		=> $customer_info['lastname'],
				'address_1' 		=> $address_info['address_1'],
				'address_2' 		=> $address_info['address_2'],
				'postcode'  		=> $address_info['postcode'],
				'district' 			=> $address_info['city_name'],
				'city'      		=> $address_info['zone'],
				'province'  		=> $address_info['country'],
				'department'  		=> $customer_info['customer_group'],
				'location'  		=> $customer_info['location'],
				'date_start'  		=> date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start'])),
			);

			$description = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $document_type_info['description']))));
			$data['description'] = html_entity_decode($description, ENT_QUOTES, 'UTF-8');

			$this->response->setOutput($this->load->view('customer/document_type_info', $data));
		}
	}
}
