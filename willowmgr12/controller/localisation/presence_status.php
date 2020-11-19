<?php
class ControllerLocalisationPresenceStatus extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/presence_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/presence_status');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/presence_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/presence_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_presence_status->addPresenceStatus($this->request->post);

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

			$this->response->redirect($this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/presence_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/presence_status');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_presence_status->editPresenceStatus($this->request->get['presence_status_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/presence_status');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/presence_status');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $presence_status_id) {
				// $this->model_localisation_presence_status->deletePresenceStatus($presence_status_id);
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

			$this->response->redirect($this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . $url, true));
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
			'href' => $this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/presence_status/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/presence_status/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['presence_statuses'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$presence_status_count = $this->model_localisation_presence_status->getPresenceStatusesCount();

		$results = $this->model_localisation_presence_status->getPresenceStatuses($filter_data);

		foreach ($results as $result) {
			$data['presence_statuses'][] = array(
				'presence_status_id' => $result['presence_status_id'],
				'name'            => $result['name'],
				'code'            => $result['code'],
				'last_notif'      => $result['last_notif'] ? $result['last_notif'] : '',
				'edit'            => $this->url->link('localisation/presence_status/edit', 'token=' . $this->session->data['token'] . '&presence_status_id=' . $result['presence_status_id'] . $url, true)
			);
		}

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_name',
			'column_code',
			'column_last_notif',
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

		$data['sort_name'] = $this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $presence_status_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($presence_status_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($presence_status_count - $this->config->get('config_limit_admin'))) ? $presence_status_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $presence_status_count, ceil($presence_status_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/presence_status_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['presence_status_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_name',
			'entry_code',
			'entry_last_notif',
			'entry_sort_order',
			'help_last_notif',
			'button_save',
			'button_cancel'
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

		if (isset($this->error['code'])) {
			$data['error_code'] = $this->error['code'];
		} else {
			$data['error_code'] = '';
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
			'href' => $this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['presence_status_id'])) {
			$data['action'] = $this->url->link('localisation/presence_status/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/presence_status/edit', 'token=' . $this->session->data['token'] . '&presence_status_id=' . $this->request->get['presence_status_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['presence_status_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$presence_status_info = $this->model_localisation_presence_status->getPresenceStatus($this->request->get['presence_status_id']);
		}
	
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($presence_status_info)) {
			$data['name'] = $presence_status_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['code'])) {
			$data['code'] = $this->request->post['code'];
		} elseif (!empty($presence_status_info)) {
			$data['code'] = $presence_status_info['code'];
		} else {
			$data['code'] = '';
		}

		if (isset($this->request->post['last_notif'])) {
			$data['last_notif'] = $this->request->post['last_notif'];
		} elseif (!empty($presence_status_info)) {
			$data['last_notif'] = $presence_status_info['last_notif'];
		} else {
			$data['last_notif'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/presence_status_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/presence_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen(trim($this->request->post['name'])) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if ((utf8_strlen($this->request->post['code']) < 1) || (utf8_strlen(trim($this->request->post['code'])) > 8)) {
			$this->error['code'] = $this->language->get('error_code');
		}
		
		$this->load->model('localisation/presence_status');
			
		if (isset($this->request->get['presence_status_id'])) {
			$presence_status_info = $this->model_localisation_presence_status->getPresenceStatus($this->request->get['presence_status_id']);
			
			if (!empty($presence_status_info['code']) && $presence_status_info['code'] != $this->request->post['code']) {
				$this->error['code'] = $this->language->get('error_code_locked');
			}
		} elseif ($this->model_localisation_presence_status->getPresenceStatusesCountByCode($this->request->post['code'])) {
			$this->error['code'] = $this->language->get('error_code_used');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/presence_status')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$this->load->model('presence/presence');

		foreach ($this->request->post['selected'] as $presence_status_id) {
			$presence_count = $this->model_presence_presence->getPresencesCountByPresenceStatusId($presence_status_id);

			if ($presence_count) {
				$this->error['warning'] = sprintf($this->language->get('error_presence'), $presence_count);
			}
		}

		return !$this->error;
	}
}
