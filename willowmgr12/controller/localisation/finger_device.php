<?php
class ControllerLocalisationFingerDevice extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('localisation/finger_device');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/finger_device');

		$this->getList();
	}

	public function add() {
		$this->load->language('localisation/finger_device');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/finger_device');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_finger_device->addFingerDevice($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('localisation/finger_device');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/finger_device');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_localisation_finger_device->editFingerDevice($this->request->get['finger_device_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('localisation/finger_device');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('localisation/finger_device');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $finger_device_id) {
				$this->model_localisation_finger_device->deleteFingerDevice($finger_device_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

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
			'href' => $this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('localisation/finger_device/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('localisation/finger_device/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['finger_devices'] = array();

		$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$finger_device_count = $this->model_localisation_finger_device->getFingerDevicesCount();

		$results = $this->model_localisation_finger_device->getFingerDevices($filter_data);

		foreach ($results as $result) {
			$data['finger_devices'][] = array(
				'finger_device_id'	=> $result['finger_device_id'],
				'device_name'		=> $result['device_name'],
				'sn'				=> $result['sn'],
				'vc'				=> $result['vc'],
				'ac'				=> $result['ac'],
				'vkey'				=> substr($result['vkey'], 0, 5) . '...',
				'edit'				=> $this->url->link('localisation/finger_device/edit', 'token=' . $this->session->data['token'] . '&finger_device_id=' . $result['finger_device_id'] . $url, true)
			);
		}

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_device_name',
			'column_sn',
			'column_vc',
			'column_ac',
			'column_vkey',
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

		$pagination = new Pagination();
		$pagination->total = $finger_device_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($finger_device_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($finger_device_count - $this->config->get('config_limit_admin'))) ? $finger_device_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $finger_device_count, ceil($finger_device_count / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/finger_device_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['finger_device_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'entry_device_name',
			'entry_sn',
			'entry_vc',
			'entry_ac',
			'entry_vkey',
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

		if (isset($this->error['sn'])) {
			$data['error_sn'] = $this->error['sn'];
		} else {
			$data['error_sn'] = '';
		}

		$url = '';

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
			'href' => $this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['finger_device_id'])) {
			$data['action'] = $this->url->link('localisation/finger_device/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('localisation/finger_device/edit', 'token=' . $this->session->data['token'] . '&finger_device_id=' . $this->request->get['finger_device_id'] . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'] . $url, true);

		if (isset($this->request->get['finger_device_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$finger_device_info = $this->model_localisation_finger_device->getFingerDevice($this->request->get['finger_device_id']);
		}
	
		if (isset($this->request->post['device_name'])) {
			$data['device_name'] = $this->request->post['device_name'];
		} elseif (!empty($finger_device_info)) {
			$data['device_name'] = $finger_device_info['device_name'];
		} else {
			$data['device_name'] = '';
		}

		if (isset($this->request->post['sn'])) {
			$data['sn'] = $this->request->post['sn'];
		} elseif (!empty($finger_device_info)) {
			$data['sn'] = $finger_device_info['sn'];
		} else {
			$data['sn'] = '';
		}

		if (isset($this->request->post['vc'])) {
			$data['vc'] = $this->request->post['vc'];
		} elseif (!empty($finger_device_info)) {
			$data['vc'] = $finger_device_info['vc'];
		} else {
			$data['vc'] = '';
		}

		if (isset($this->request->post['ac'])) {
			$data['ac'] = $this->request->post['ac'];
		} elseif (!empty($finger_device_info)) {
			$data['ac'] = $finger_device_info['ac'];
		} else {
			$data['ac'] = '';
		}

		if (isset($this->request->post['vkey'])) {
			$data['vkey'] = $this->request->post['vkey'];
		} elseif (!empty($finger_device_info)) {
			$data['vkey'] = $finger_device_info['vkey'];
		} else {
			$data['vkey'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('localisation/finger_device_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'localisation/finger_device')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['device_name']) < 1) || (utf8_strlen(trim($this->request->post['device_name'])) > 50)) {
			$this->error['warning'] = $this->language->get('error_required');
		}

		if ((utf8_strlen($this->request->post['sn']) < 1) || (utf8_strlen(trim($this->request->post['sn'])) > 50)) {
			$this->error['warning'] = $this->language->get('error_required');
		}

		if ((utf8_strlen($this->request->post['vc']) < 1) || (utf8_strlen(trim($this->request->post['vc'])) > 50)) {
			$this->error['warning'] = $this->language->get('error_required');
		}

		if ((utf8_strlen($this->request->post['ac']) < 1) || (utf8_strlen(trim($this->request->post['ac'])) > 50)) {
			$this->error['warning'] = $this->language->get('error_required');
		}

		if ((utf8_strlen($this->request->post['vkey']) < 1) || (utf8_strlen(trim($this->request->post['vkey'])) > 50)) {
			$this->error['warning'] = $this->language->get('error_required');
		}
		
		$device_info = $this->model_localisation_finger_device->getFingerDeviceBySn($this->request->post['sn']);
		if (!isset($this->request->get['finger_device_id'])) {
			if ($device_info) {
				$this->error['sn'] = $this->language->get('error_sn_exist');
			}
		} else {
			if ($device_info && ($this->request->get['finger_device_id'] != $device_info['finger_device_id'])) {
				$this->error['sn'] = $this->language->get('error_sn_exist');
			}
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'localisation/finger_device')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
