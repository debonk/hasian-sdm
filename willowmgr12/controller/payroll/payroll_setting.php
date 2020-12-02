<?php
class ControllerPayrollPayrollSetting extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payroll/payroll_setting');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_setting_setting->editSetting('payroll_setting', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			$this->response->redirect($this->url->link('payroll/payroll_setting', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	protected function getForm() {
		$language_items = array(
			'heading_title',
			'text_edit',
			'text_select',
			'text_yes',
			'text_no',
			'entry_pending_status',
			'entry_processing_status',
			'entry_submitted_status',
			'entry_generated_status',
			'entry_approved_status',
			'entry_released_status',
			'entry_completed_status',
			'entry_presence_lock',
			'entry_default_hke',
			'entry_vacation_limit',
			'entry_schedule_lock',
			'entry_logout_date',
			'entry_login_start',
			'entry_login_end',
			'entry_logout_start',
			'entry_use_fingerprint',
			'entry_schedule_check',
			'entry_presence_statuses',
			'button_save',
			'button_cancel',
			'help_pending_status',
			'help_processing_status',
			'help_submitted_status',
			'help_generated_status',
			'help_approved_status',
			'help_released_status',
			'help_completed_status',
			'help_presence_lock',
			'help_default_hke',
			'help_vacation_limit',
			'help_schedule_lock',
			'help_logout_date',
			'help_login_start',
			'help_login_end',
			'help_logout_start',
			'help_use_fingerprint',
			'help_schedule_check',
			'help_presence_statuses',
			'tab_general',
			'tab_presence_status',
			'tab_payroll_status'
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

		if (isset($this->error['default_hke'])) {
			$data['error_default_hke'] = $this->error['default_hke'];
		} else {
			$data['error_default_hke'] = '';
		}

		if (isset($this->error['vacation_limit'])) {
			$data['error_vacation_limit'] = $this->error['vacation_limit'];
		} else {
			$data['error_vacation_limit'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payroll/payroll_setting', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('payroll/payroll_setting', 'token=' . $this->session->data['token'], true);
		$data['cancel'] = $this->url->link('payroll/payroll_setting', 'token=' . $this->session->data['token'], true);

		$data['presence_items'] = array(
			'h',
			's',
			'i',
			'ns',
			'ia',
			'a',
			'c',
			't1',
			't2',
			't3'
		);
		foreach ($data['presence_items'] as $presence_item) {
			//Language item
			$data['entry_status'][$presence_item] = $this->language->get('entry_status_' . $presence_item);
			
			//Setting value
			if (isset($this->request->post['payroll_setting_id_' . $presence_item])) {
				$data['payroll_setting_id'][$presence_item] = $this->request->post['payroll_setting_id_' . $presence_item];
			} else {
				$data['payroll_setting_id'][$presence_item] = $this->config->get('payroll_setting_id_' . $presence_item);
			}
		};

		if (isset($this->request->post['payroll_setting_presence_lock'])) {
			$data['payroll_setting_presence_lock'] = $this->request->post['payroll_setting_presence_lock'];
		} elseif ($this->config->has('payroll_setting_presence_lock')) {
		   	$data['payroll_setting_presence_lock'] = $this->config->get('payroll_setting_presence_lock');
		} else {
			$data['payroll_setting_presence_lock'] = array();
		}

		if (isset($this->request->post['payroll_setting_presence_status_ids'])) {
			$data['payroll_setting_presence_status_ids'] = $this->request->post['payroll_setting_presence_status_ids'];
		} elseif ($this->config->has('payroll_setting_presence_status_ids')) {
		   	$data['payroll_setting_presence_status_ids'] = $this->config->get('payroll_setting_presence_status_ids');
		} else {
			$data['payroll_setting_presence_status_ids'] = array();
		}

		if (isset($this->request->post['payroll_setting_pending_status_id'])) {
			$data['payroll_setting_pending_status_id'] = $this->request->post['payroll_setting_pending_status_id'];
		} else {
			$data['payroll_setting_pending_status_id'] = $this->config->get('payroll_setting_pending_status_id');
		}

		if (isset($this->request->post['payroll_setting_processing_status_id'])) {
			$data['payroll_setting_processing_status_id'] = $this->request->post['payroll_setting_processing_status_id'];
		} else {
			$data['payroll_setting_processing_status_id'] = $this->config->get('payroll_setting_processing_status_id');
		}

		if (isset($this->request->post['payroll_setting_submitted_status_id'])) {
			$data['payroll_setting_submitted_status_id'] = $this->request->post['payroll_setting_submitted_status_id'];
		} else {
			$data['payroll_setting_submitted_status_id'] = $this->config->get('payroll_setting_submitted_status_id');
		}

		if (isset($this->request->post['payroll_setting_generated_status_id'])) {
			$data['payroll_setting_generated_status_id'] = $this->request->post['payroll_setting_generated_status_id'];
		} else {
			$data['payroll_setting_generated_status_id'] = $this->config->get('payroll_setting_generated_status_id');
		}

		if (isset($this->request->post['payroll_setting_approved_status_id'])) {
			$data['payroll_setting_approved_status_id'] = $this->request->post['payroll_setting_approved_status_id'];
		} else {
			$data['payroll_setting_approved_status_id'] = $this->config->get('payroll_setting_approved_status_id');
		}

		if (isset($this->request->post['payroll_setting_released_status_id'])) {
			$data['payroll_setting_released_status_id'] = $this->request->post['payroll_setting_released_status_id'];
		} else {
			$data['payroll_setting_released_status_id'] = $this->config->get('payroll_setting_released_status_id');
		}

		if (isset($this->request->post['payroll_setting_completed_status_id'])) {
			$data['payroll_setting_completed_status_id'] = $this->request->post['payroll_setting_completed_status_id'];
		} else {
			$data['payroll_setting_completed_status_id'] = $this->config->get('payroll_setting_completed_status_id');
		}

		if (isset($this->request->post['payroll_setting_default_hke'])) {
			$data['payroll_setting_default_hke'] = $this->request->post['payroll_setting_default_hke'];
		} elseif ($this->config->get('payroll_setting_default_hke')) {
			$data['payroll_setting_default_hke'] = $this->config->get('payroll_setting_default_hke');
		} else {
			$data['payroll_setting_default_hke'] = 25;
		}

		if (isset($this->request->post['payroll_setting_vacation_limit'])) {
			$data['payroll_setting_vacation_limit'] = $this->request->post['payroll_setting_vacation_limit'];
		} elseif ($this->config->get('payroll_setting_vacation_limit')) {
			$data['payroll_setting_vacation_limit'] = $this->config->get('payroll_setting_vacation_limit');
		} else {
			$data['payroll_setting_vacation_limit'] = 0;
		}

		if (isset($this->request->post['payroll_setting_schedule_lock'])) {
			$data['payroll_setting_schedule_lock'] = $this->request->post['payroll_setting_schedule_lock'];
		} elseif ($this->config->get('payroll_setting_schedule_lock')) {
			$data['payroll_setting_schedule_lock'] = $this->config->get('payroll_setting_schedule_lock');
		} else {
			$data['payroll_setting_schedule_lock'] = 0;
		}

		if (isset($this->request->post['payroll_setting_logout_date'])) {
			$data['payroll_setting_logout_date'] = $this->request->post['payroll_setting_logout_date'];
		} elseif ($this->config->get('payroll_setting_logout_date')) {
			$data['payroll_setting_logout_date'] = $this->config->get('payroll_setting_logout_date');
		} else {
			$data['payroll_setting_logout_date'] = 0;
		}

		if (isset($this->request->post['payroll_setting_login_start'])) {
			$data['payroll_setting_login_start'] = $this->request->post['payroll_setting_login_start'];
		} elseif ($this->config->get('payroll_setting_login_start')) {
			$data['payroll_setting_login_start'] = $this->config->get('payroll_setting_login_start');
		} else {
			$data['payroll_setting_login_start'] = 0;
		}

		if (isset($this->request->post['payroll_setting_login_end'])) {
			$data['payroll_setting_login_end'] = $this->request->post['payroll_setting_login_end'];
		} elseif ($this->config->get('payroll_setting_login_end')) {
			$data['payroll_setting_login_end'] = $this->config->get('payroll_setting_login_end');
		} else {
			$data['payroll_setting_login_end'] = 0;
		}

		if (isset($this->request->post['payroll_setting_logout_start'])) {
			$data['payroll_setting_logout_start'] = $this->request->post['payroll_setting_logout_start'];
		} elseif ($this->config->get('payroll_setting_logout_start')) {
			$data['payroll_setting_logout_start'] = $this->config->get('payroll_setting_logout_start');
		} else {
			$data['payroll_setting_logout_start'] = 0;
		}

		if (isset($this->request->post['payroll_setting_use_fingerprint'])) {
			$data['payroll_setting_use_fingerprint'] = $this->request->post['payroll_setting_use_fingerprint'];
		} else {
			$data['payroll_setting_use_fingerprint'] = $this->config->get('payroll_setting_use_fingerprint');
		}
		
		if (isset($this->request->post['payroll_setting_schedule_check'])) {
			$data['payroll_setting_schedule_check'] = $this->request->post['payroll_setting_schedule_check'];
		} else {
			$data['payroll_setting_schedule_check'] = $this->config->get('payroll_setting_schedule_check');
		}
		
		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();

		$this->load->model('localisation/payroll_status');
		$data['payroll_statuses'] = $this->model_localisation_payroll_status->getPayrollStatuses();

		$this->load->model('localisation/presence_status');
		$data['presence_statuses'] = $this->model_localisation_presence_status->getPresenceStatuses();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_setting_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'payroll/payroll_setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['payroll_setting_default_hke'] < 0) {
			$this->error['default_hke'] = $this->language->get('error_default_hke');
		}
		
		if ($this->request->post['payroll_setting_vacation_limit'] < 0) {
			$this->error['vacation_limit'] = $this->language->get('error_vacation_limit');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
}
