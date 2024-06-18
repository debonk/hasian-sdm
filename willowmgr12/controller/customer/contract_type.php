<?php
class ControllerCustomerContractType extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('customer/contract_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/contract_type');

		$this->getList();
	}

	public function add() {
		$this->load->language('customer/contract_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/contract_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_contract_type->addContractType($this->request->post);

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

			$this->response->redirect($this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('customer/contract_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/contract_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_customer_contract_type->editContractType($this->request->get['contract_type_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('customer/contract_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('customer/contract_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $contract_type_id) {
				// $this->model_customer_contract_type->deleteContractType($contract_type_id);
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

			$this->response->redirect($this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_disabled',
			'text_enabled',
			'text_indefinite',
			'column_name',
			'column_duration',
			'column_sort_order',
			'column_status',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
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
			'href' => $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'], true)
		);

		$data['add'] = $this->url->link('customer/contract_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('customer/contract_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['contract_types'] = array();

		$filter_data = array(
			'filter'	=> ['all' => true],
			'sort' 		=> $sort,
			'order'		=> $order,
			'start'		=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'		=> $this->config->get('config_limit_admin')
		);

		$contract_type_count = $this->model_customer_contract_type->getContractTypesCount();

		$results = $this->model_customer_contract_type->getContractTypes($filter_data);

		foreach ($results as $result) {
			$data['contract_types'][] = array(
				'contract_type_id'	=> $result['contract_type_id'],
				'name'      		=> $result['name'],
				'duration'  		=> $result['duration'] < 1 ? $data['text_indefinite'] : $result['duration'],
				'sort_order'		=> $result['sort_order'],
				'status'			=> $result['status'],
				'edit'      		=> $this->url->link('customer/contract_type/edit', 'token=' . $this->session->data['token'] . '&contract_type_id=' . $result['contract_type_id'] . $url, true)
			);
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

		$data['sort_name'] = $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_duration'] = $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . '&sort=duration' . $url, true);
		$data['sort_sort_order'] = $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);
		$data['sort_status'] = $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . '&sort=status' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $contract_type_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($contract_type_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($contract_type_count - $this->config->get('config_limit_admin'))) ? $contract_type_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $contract_type_count, ceil($contract_type_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/contract_type_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['contract_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_disabled',
			'text_enabled',
			'entry_name',
			'entry_description',
			'entry_duration',
			'entry_sort_order',
			'entry_status',
			'button_save',
			'button_cancel',
			'help_duration'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
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
			'href' => $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['contract_type_id'])) {
			$data['action'] = $this->url->link('customer/contract_type/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('customer/contract_type/edit', 'token=' . $this->session->data['token'] . '&contract_type_id=' . $this->request->get['contract_type_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('customer/contract_type', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['contract_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$contract_type_info = $this->model_customer_contract_type->getContractType($this->request->get['contract_type_id']);
		}

		$contract_type_items = array(
			'name',
			'description',
			'duration',
			'sort_order',
			'status',
		);
		foreach ($contract_type_items as $contract_type_item) {
			if (isset($this->request->post[$contract_type_item])) {
				$data[$contract_type_item] = $this->request->post[$contract_type_item];
			} elseif (!empty($contract_type_info)) {
				$data[$contract_type_item] = $contract_type_info[$contract_type_item];
			} else {
				$data[$contract_type_item] = '';
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('customer/contract_type_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'customer/contract_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'customer/contract_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('customer/contract');

		foreach ($this->request->post['selected'] as $contract_type_id) {
			if ($contract_type_id == 0) {
				$this->error['warning'] = $this->language->get('error_contract_resign');

				break;
			}

			$contract_count = $this->model_customer_contract->getContractCountByContractTypeId($contract_type_id);

			if ($contract_count) {
				$this->error['warning'] = sprintf($this->language->get('error_contract'), $contract_count);

				break;
			}
		}

		return !$this->error;
	}
}
