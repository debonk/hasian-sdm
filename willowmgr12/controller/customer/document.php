<?php
class ControllerCustomerDocument extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/document');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document');
		$this->load->model('presence/presence');
		$this->load->model('customer/document_type');
		
		$this->getList();
	}

	public function edit() {
		$this->load->language('customer/document');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document');
		$this->load->model('customer/document_type');
		
		$this->getForm();
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
			$filter_location_id = null;
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
			'href' => $this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url, true)
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

		$data['documents'] = array();
		$documents_data = array();
		$documents = array();

		$customer_count = $this->model_presence_presence->getTotalCustomers($filter_data);

		$results = $this->model_presence_presence->getCustomers($filter_data);
		
		$document_types_info = $this->model_customer_document_type->getActiveDocumentTypes();
		
		$documents_info = $this->model_customer_document->getDocuments();
		
		foreach ($documents_info as $document_info) {
			$documents_data[$document_info['customer_id']][$document_info['document_type_id']] = 1;//Value bisa diganti dgn data yg diperlukan
		}
		
		foreach ($results as $result) {
			foreach ($document_types_info as $document_type) {
				if (isset($documents_data[$result['customer_id']][$document_type['document_type_id']])) {
					$documents[$document_type['document_type_id']] = $documents_data[$result['customer_id']][$document_type['document_type_id']];
				} else {
					$documents[$document_type['document_type_id']] = 0;
				}
			}
			
			$data['documents'][] = array(
				'customer_id' 		=> $result['customer_id'],
				'nip' 				=> $result['nip'],
				'name' 				=> $result['name'],
				'customer_group' 	=> $result['customer_group'],
				'location' 			=> $result['location'],
				'documents' 		=> $documents,
				'edit'          	=> $this->url->link('customer/document/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
			);
		}
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_all_customer_group',
			'text_all_location',
			'text_active',
			'text_inactive',
			'text_all_status',
			'entry_name',
			'entry_customer_group',
			'entry_location',
			'entry_status',
			'column_nip',
			'column_name',
			'column_customer_group',
			'column_location',
			'column_action',
			'button_filter',
			'button_edit'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

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

		$data['sort_nip'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&sort=nip' . $url, true);
		$data['sort_name'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

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
		$pagination->url = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($customer_count - $this->config->get('config_limit_admin'))) ? $customer_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $customer_count, ceil($customer_count / $this->config->get('config_limit_admin')));
		
		$data['document_types_info'] = $document_types_info;

		$data['filter_name'] = $filter_name;
		$data['filter_customer_group_id'] = $filter_customer_group_id;
		$data['filter_location_id'] = $filter_location_id;
		$data['filter_status'] = $filter_status;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/document_list', $data));
	}

	protected function getForm() {
		$data['text_edit'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_loading',
			'text_confirm',
			'text_no_results',
			'column_title',
			'column_filename',
			'column_mask',
			'column_date_added',
			'column_username',
			'column_action',
			'button_upload',
			'button_view',
			'button_delete',
			'button_back',
			'button_print'
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
			'href' => $this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (isset($this->request->get['customer_id'])) {
			$data['action'] = $this->url->link('customer/document/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		} else {
			$this->response->redirect($this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url, true));
		}

		$data['back'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_edit'],
			'href' => $data['action']
		);
		
		$data['documents'] = array();
		$documents_info = array();
		
		if (isset($this->request->get['customer_id'])) {
			$document_types_info = $this->model_customer_document_type->getActiveDocumentTypes();

			$results = $this->model_customer_document->getDocumentsByCustomer($this->request->get['customer_id']);
		
			foreach ($results as $result) {
				$documents_info[$result['document_type_id']][] = $result;
			}
		}
		
		if ($this->request->server['HTTPS']) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}

		foreach ($document_types_info as $document_type) {
			if (!empty($documents_info[$document_type['document_type_id']])) {
				$filename = array_column($documents_info[$document_type['document_type_id']], 'filename');
				$mask = array_column($documents_info[$document_type['document_type_id']], 'mask');
				$date_added = date($this->language->get('date_format_jMY'), strtotime($documents_info[$document_type['document_type_id']][0]['date_added']));
				$username = $documents_info[$document_type['document_type_id']][0]['username'];
				$href_path = $server . 'document/';
				
			} else {
				$filename = array('-');
				$mask = array('-');
				$date_added = '-';
				$username = '-';
				$href_path = '';
			}
			
			if ($document_type['description']) {
				$href_info = $this->url->link('customer/document_type/info', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&document_type_id=' . $document_type['document_type_id'], true);
			} else {
				$href_info = '';
			}
				
			$data['documents'][$document_type['document_type_id']] = array(
				'title'			=> $document_type['title'],
				'required'		=> $document_type['required'] ? 'required' : '',
				'filename'		=> $filename,
				'mask'			=> $mask,
				'date_added'	=> $date_added,
				'username'		=> $username,
				'href_path'		=> $href_path,
				'href_info'		=> $href_info
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/document_form', $data));
	}

	protected function getForm2() {
		$data['text_edit'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_loading',
			'text_confirm',
			'text_no_results',
			'column_title',
			'column_filename',
			'column_mask',
			'column_date_added',
			'column_username',
			'column_action',
			'button_upload',
			'button_view',
			'button_delete',
			'button_back'
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
			'href' => $this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (isset($this->request->get['customer_id'])) {
			$data['action'] = $this->url->link('customer/document/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . $url, true);
		} else {
			$this->response->redirect($this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url, true));
		}

		$data['back'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_edit'],
			'href' => $data['action']
		);
		
		$data['documents'] = array();
		$documents_info = array();
		
		if (isset($this->request->get['customer_id'])) {
			$document_types_info = $this->model_customer_document_type->getActiveDocumentTypes();

			$results = $this->model_customer_document->getDocumentsByCustomer($this->request->get['customer_id']);
		
			foreach ($results as $result) {
				$documents_info[$result['document_type_id']][] = $result;
			}
		}
		
		if ($this->request->server['HTTPS']) {
			$server = HTTPS_CATALOG;
		} else {
			$server = HTTP_CATALOG;
		}

		foreach ($document_types_info as $document_type) {
			if ($document_type['description']) {
				$href_info = $this->url->link('customer/document_type/info', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&document_type_id=' . $document_type['document_type_id'], true);
			}
			
			if (!empty($documents_info[$document_type['document_type_id']])) {
				$filename = array_column($documents_info[$document_type['document_type_id']], 'filename');
				$mask = array_column($documents_info[$document_type['document_type_id']], 'mask');
				
				$data['documents'][$document_type['document_type_id']] = array(
					'title'			=> $document_type['title'],
					'required'		=> $document_type['required'] ? 'required' : '',
					'filename'		=> $filename,
					'mask'			=> $mask,
					'date_added'	=> date($this->language->get('date_format_jMY'), strtotime($documents_info[$document_type['document_type_id']][0]['date_added'])),
					'username'		=> $documents_info[$document_type['document_type_id']][0]['username'],
					'href_path'		=> $server . 'document/'
				);
			} else {
				$data['documents'][$document_type['document_type_id']] = array(
					'title'			=> $document_type['title'],
					'required'		=> $document_type['required'] ? 'required' : '',
					'filename'		=> array('-'),
					'mask'			=> array('-'),
					'date_added'	=> '-',
					'username'		=> '-',
					'href_path'		=> ''
				);
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/document_form', $data));
	}

	public function upload() {
		$this->load->language('customer/document');

		$json = array();
		$files = array();
		
		// Check user has permission
		if (!$this->user->hasPermission('modify', 'customer/document')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (!$json) {
			foreach ($this->request->files['files'] as $key => $values) {
				foreach ($values as $sub_key => $value) {
					$files[$sub_key][$key] = $value;
				}
			}

			foreach ($files as $key => $file) {
				if (!empty($file['name']) && is_file($file['tmp_name'])) {
					// Sanitize the filename
					$filename = basename(html_entity_decode($file['name'], ENT_QUOTES, 'UTF-8'));
					$files[$key]['filename'] = $filename;

					// Validate the filename length
					if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 128)) {
						$json['error'] = $this->language->get('error_filename');
					}

					// Allowed file extension types
					$allowed = array(
						'jpg',
						'jpeg',
						'gif',
						'png'
					);
					
					if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
						$json['error'] = $this->language->get('error_filetype');
					}

					// Allowed file mime types
					$allowed = array(
						'image/jpeg',
						'image/pjpeg',
						'image/png',
						'image/x-png',
						'image/gif'
					);

					if (!in_array($file['type'], $allowed)) {
						$json['error'] = $this->language->get('error_filetype');
					}

					// Return any upload error
					if ($file['error'] != UPLOAD_ERR_OK) {
						$json['error'] = $this->language->get('error_upload_' . $file['error']);
					}
				} else {
					$json['error'] = $this->language->get('error_upload');
				}
			}
		}

		if (!$json) {
			$this->load->model('customer/document');
			
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

			foreach ($files as $key => $file) {
				$mask = strtolower($customer_id . '_' . $document_type_id . '_' . $key . '_' . $file['filename']);
				$filename = $mask . '.' . token(10);

				move_uploaded_file($file['tmp_name'], DIR_DOCUMENT . $filename);
				
				$post_data = array(
					'customer_id'		=> $customer_id,
					'document_type_id'	=> $document_type_id,
					'filename' 			=> $filename,
					'mask'	 			=> $mask
				);
				
				$this->model_customer_document->addDocument($post_data);
			}
			
			$this->session->data['success'] = $this->language->get('text_success_upload');
			
			$json['success'] = 1;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function delete() {
		$this->load->language('customer/document');

		$json = array();
		
		if (!$this->validateDelete()) {
			$json['error'] = $this->error['warning'];
			
		} else {
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

			$this->load->model('customer/document');
			
			$documents_info = $this->model_customer_document->getDocumentsByCustomer($customer_id, $document_type_id);
			
			foreach ($documents_info as $document_info) {
				if (isset($document_info['filename']) && file_exists(DIR_DOCUMENT . $document_info['filename'])) {
					unlink(DIR_DOCUMENT . $document_info['filename']);
				}
				
				$this->model_customer_document->deleteDocumentByCustomer($customer_id, $document_type_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success_delete');
			
			$json['success'] = 1;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('bypass', 'customer/document')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
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
}
