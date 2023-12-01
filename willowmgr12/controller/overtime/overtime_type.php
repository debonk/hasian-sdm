<?php
class ControllerOvertimeOvertimeType extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('overtime/overtime_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('overtime/overtime_type');

		$this->getList();
	}

	public function add() {
		$this->load->language('overtime/overtime_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('overtime/overtime_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_overtime_overtime_type->addOvertimeType($this->request->post);

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

			$this->response->redirect($this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('overtime/overtime_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('overtime/overtime_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_overtime_overtime_type->editOvertimeType($this->request->get['overtime_type_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('overtime/overtime_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('overtime/overtime_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $overtime_type_id) {
				$this->model_overtime_overtime_type->deleteOvertimeType($overtime_type_id);
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

			$this->response->redirect($this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
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
			'href' => $this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('overtime/overtime_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('overtime/overtime_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['overtime_types'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$overtime_type_count = $this->model_overtime_overtime_type->getOvertimeTypesCount();

		$results = $this->model_overtime_overtime_type->getOvertimeTypes($filter_data);

		foreach ($results as $result) {
			$data['overtime_types'][] = array(
				'overtime_type_id'  => $result['overtime_type_id'],
				'name'              => $result['name'],
				'sort_order'        => $result['sort_order'],
				'edit'              => $this->url->link('overtime/overtime_type/edit', 'token=' . $this->session->data['token'] . '&overtime_type_id=' . $result['overtime_type_id'] . $url, true)
			);
		}

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_name',
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

		$data['sort_name'] = $this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_sort_order'] = $this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . '&sort=sort_order' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $overtime_type_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($overtime_type_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($overtime_type_count - $this->config->get('config_limit_admin'))) ? $overtime_type_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $overtime_type_count, ceil($overtime_type_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('overtime/overtime_type_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['overtime_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_name',
			'entry_description',
			'entry_wage',
			'entry_duration',
			'entry_sort_order',
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
			'href' => $this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['overtime_type_id'])) {
			$data['action'] = $this->url->link('overtime/overtime_type/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('overtime/overtime_type/edit', 'token=' . $this->session->data['token'] . '&overtime_type_id=' . $this->request->get['overtime_type_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['overtime_type_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$overtime_type_info = $this->model_overtime_overtime_type->getOvertimeType($this->request->get['overtime_type_id']);
		}

		$overtime_type_items = array(
			'name',
			'description',
			'wage',
			'duration',
			'sort_order'
		);
		foreach ($overtime_type_items as $overtime_type_item) {
			if (isset($this->request->post[$overtime_type_item])) {
				$data[$overtime_type_item] = $this->request->post[$overtime_type_item];
			} elseif (!empty($overtime_type_info)) {
				$data[$overtime_type_item] = $overtime_type_info[$overtime_type_item];
			} else {
				$data[$overtime_type_item] = '';
			}
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('overtime/overtime_type_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'overtime/overtime_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'overtime/overtime_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('overtime/overtime');

		foreach ($this->request->post['selected'] as $overtime_type_id) {
			$overtime_count = $this->model_overtime_overtime->getOvertimeCountByOvertimeTypeId($overtime_type_id);

			if ($overtime_count) {
				$this->error['warning'] = sprintf($this->language->get('error_overtime'), $overtime_count);
			}
		}

		return !$this->error;
	}
}
