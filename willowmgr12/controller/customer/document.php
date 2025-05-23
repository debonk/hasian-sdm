<?php
class ControllerCustomerDocument extends Controller
{
	private $error = array();
	private $filter_items = array(
		'name',
		'customer_group_id',
		'customer_department_id',
		'location_id',
		'requirement',
		'active'
	);

	private function urlFilter()
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$url_filter .= '&filter_' . $filter_item . '=' . $this->request->get['filter_' . $filter_item];
			}
		}

		return $url_filter;
	}


	public function index()
	{
		$this->load->language('customer/document');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document');
		$this->load->model('presence/presence');

		$this->getList();
	}

	public function edit()
	{
		$this->load->language('customer/document');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/document');
		$this->load->model('customer/document_type');

		$this->getForm();
	}

	protected function getList()
	{
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_all',
			'text_active',
			'text_inactive',
			'text_all_status',
			'entry_name',
			'entry_customer_department',
			'entry_customer_group',
			'entry_location',
			'entry_requirement',
			'entry_status',
			'column_nip',
			'column_name',
			'column_customer_department',
			'column_customer_group',
			'column_location',
			'column_action',
			'button_filter',
			'button_edit'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		if (empty($filter['active'])) {
			$filter['active'] = 1;
		}

		// if (isset($this->request->get['filter_name'])) {
		// 	$filter_name = $this->request->get['filter_name'];
		// } else {
		// 	$filter_name = null;
		// }

		// if (isset($this->request->get['filter_customer_group_id'])) {
		// 	$filter_customer_group_id = $this->request->get['filter_customer_group_id'];
		// } else {
		// 	$filter_customer_group_id = null;
		// }

		// if (isset($this->request->get['filter_location_id'])) {
		// 	$filter_location_id = $this->request->get['filter_location_id'];
		// } else {
		// 	$filter_location_id = null;
		// }

		// if (isset($this->request->get['filter_status'])) {
		// 	$filter_status = $this->request->get['filter_status'];
		// } else {
		// 	$filter_status = null;
		// }

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

		$url = $this->urlFilter();

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
			'href' => $this->url->link('customer/document', 'token=' . $this->session->data['token'], true)
		);

		$data['documents'] = array();

		$limit = $this->config->get('config_limit_admin');

		$filter_data = array(
			'filter'  	=> $filter,
			'sort'  	=> $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $limit,
			'limit' 	=> $limit
		);

		$customer_count = $this->model_customer_document->getCustomerDocumentsCount($filter_data);

		$results = $this->model_customer_document->getCustomerDocuments($filter_data);

		$document_data = $this->model_customer_document->getDocuments();

		foreach ($results as $result) {
			$documents = isset($document_data[$result['customer_id']]) ? $document_data[$result['customer_id']] : [];

			$data['documents'][] = array(
				'customer_id' 			=> $result['customer_id'],
				'nip' 					=> $result['nip'],
				'name' 					=> $result['name'],
				'customer_group' 		=> $result['customer_group'],
				'customer_department' 	=> $result['customer_department'],
				'location' 				=> $result['location'],
				'documents' 			=> $documents,
				'edit'          		=> $this->url->link('customer/document/edit', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true),
			);
		}

		# Information
		$data['information'] = [];

		$filter_data = array(
			'filter'  	=> [
				'requirement'	=> '-1',
				'active'			=> '1'
			]
		);

		$requirement_count = $this->model_customer_document->getCustomerDocumentsCount($filter_data);

		if ($requirement_count) {
			$href = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&filter_requirement=-1&filter_active=1', true);
			$data['information'] = sprintf($this->language->get('text_information'), $requirement_count, $href);
		}

		$data['token'] = $this->session->data['token'];

		$url = $this->urlFilter();

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
		$data['sort_customer_department'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_customer_group'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = $this->urlFilter();

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

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->load->model('customer/customer_group');
		$data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups();

		$this->load->model('customer/customer_department');
		$data['customer_departments'] = $this->model_customer_customer_department->getCustomerDepartments();

		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('customer/document_type');
		$data['document_types'] = $this->model_customer_document_type->getActiveDocumentTypes();

		$data['requirements'] = [];
		$data['requirements']['1'] = $this->language->get('text_complete');
		$data['requirements']['-1'] = $this->language->get('text_incomplete');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/document_list', $data));
	}

	protected function getForm()
	{
		$data['text_edit'] = $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_loading',
			'text_confirm',
			'text_missing',
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

		$url = $this->urlFilter();

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
			$document_types = $this->model_customer_document_type->getActiveDocumentTypes();

			$results = $this->model_customer_document->getDocumentsByCustomer($this->request->get['customer_id']);

			foreach ($results as $result) {
				if (is_file(DIR_DOCUMENT . $result['filename'])) {
					$href_view = $this->url->link('customer/document/view', 'token=' . $this->session->data['token'] . '&document_id=' . $result['document_id'], true);
					$missing = false;
				} else {
					$href_view = '';
					$missing = true;
				}

				$documents_info[$result['document_type_id']][] = [
					'document_id'	=> $result['document_id'],
					'filename'		=> $result['filename'],
					'mask'			=> $result['mask'],
					'date_added'	=> $result['date_added'],
					'username'		=> $result['username'],
					'href_view'		=> $href_view,
					'missing'		=> $missing
				];
			}
		}

		foreach ($document_types as $document_type) {
			if (empty($documents_info[$document_type['document_type_id']])) {
				$documents_info[$document_type['document_type_id']][] = [
					'filename'		=> '-',
					'mask'			=> '-',
					'date_added'	=> '-',
					'username'		=> '-',
					'href_view'		=> '',
					'missing'		=> false
				];
			}

			if ($document_type['description']) {
				$href_info = $this->url->link('customer/document_type/info', 'token=' . $this->session->data['token'] . '&customer_id=' . $this->request->get['customer_id'] . '&document_type_id=' . $document_type['document_type_id'], true);
			} else {
				$href_info = '';
			}

			$data['documents'][$document_type['document_type_id']] = array(
				'title'				=> $document_type['title'],
				'required'			=> $document_type['required'] ? 'required' : '',
				'document_data'			=> $documents_info[$document_type['document_type_id']],
				'href_info'			=> $href_info
			);
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/document_form', $data));
	}

	public function view()
	{
		$this->load->model('customer/document');

		$data['token'] = $this->session->data['token'];

		$document_id = isset($this->request->get['document_id']) ? $this->request->get['document_id'] : 0;

		$documents_info = $this->model_customer_document->getDocument($document_id);

		if ($documents_info) {
			$data['title'] = $documents_info['mask'];

			if ($this->request->server['HTTPS']) {
				$server = HTTPS_CATALOG;
			} else {
				$server = HTTP_CATALOG;
			}

			$data['image'] = $server . 'document/' . $documents_info['filename'];

			$this->response->setOutput($this->load->view('customer/document_view', $data));
		} else {
			return new Action('error/not_found');
		}
	}

	public function upload()
	{
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
					// Allowed file extension types
					$allowed = array(
						'jpg',
						'jpeg',
						'gif',
						'png'
					);

					// $extension = strtolower(substr(strrchr($file['name'], '.'), 1));
					$extension = pathinfo($file['name'], PATHINFO_EXTENSION);

					if (!in_array($extension, $allowed)) {
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
				$mask = strtolower($customer_id . '_' . $document_type_id . '_' . $key . '_' . token(6) . '.' . $extension);
				$filename = $mask . '.' . token(10);

				$file['filename'] = $filename;

				$this->model_customer_document->getImage($file, 1000, 1000);

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

	public function delete()
	{
		$this->load->language('customer/document');

		$json = array();

		if (!$this->validateDelete()) {
			$json['error'] = $this->error['warning'];
		} else {
			$document_id = isset($this->request->get['document_id']) ? $this->request->get['document_id'] : 0;

			$this->load->model('customer/document');

			$document_info = $this->model_customer_document->getDocument($document_id);

			if (isset($document_info['filename']) && file_exists(DIR_DOCUMENT . $document_info['filename'])) {
				unlink(DIR_DOCUMENT . $document_info['filename']);
			}

			$this->model_customer_document->deleteDocument($document_id);

			$this->session->data['success'] = $this->language->get('text_success_delete');

			$json['success'] = 1;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	protected function validateDelete()
	{
		if (!$this->user->hasPermission('bypass', 'customer/document')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
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

		// $sort_order = array();

		// foreach ($json as $key => $value) {
		// 	$sort_order[$key] = $value['name'];
		// }

		// array_multisort($sort_order, SORT_ASC, $json);

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
