<?php
class ControllerPresencePresencePeriod extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('presence/presence_period');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/presence_period');

		$this->getList();
	}

	public function add() {
		$this->load->language('presence/presence_period');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/presence_period');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_presence_period->addPresencePeriod($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('presence/presence_period', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('presence/presence_period');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/presence_period');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_presence_presence_period->editPresencePeriod($this->request->get['presence_period_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('presence/presence_period', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('presence/presence_period');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('presence/presence_period');
		$last_period_id = $this->model_presence_presence_period->getLatestPeriod()['presence_period_id'];

		if ($last_period_id && $this->validateDelete()) {
			$this->model_presence_presence_period->deletePresencePeriod($last_period_id);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('presence/presence_period', 'token=' . $this->session->data['token'] . $url, true));
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
			'href' => $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'] . $url, true)
		);

		$last_period_id = $this->model_presence_presence_period->getLatestPeriod()['presence_period_id'];
		
		$this->load->model('common/payroll');
		$data['payroll_status_check'] = $this->model_common_payroll->checkPeriodStatus($last_period_id, 'pending');

		$data['add'] = $this->url->link('presence/presence_period/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['edit'] = $this->url->link('presence/presence_period/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $last_period_id . $url, true);

		$data['presence_periods'] = array();

		$filter_data = array(
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$presence_period_total = $this->model_presence_presence_period->getTotalPresencePeriods();

		$results = $this->model_presence_presence_period->getPresencePeriods($filter_data);

		foreach ($results as $result) {
			$data['presence_periods'][] = array(
				'presence_period_id' => $result['presence_period_id'],
				'period'        => date($this->language->get('date_format_m_y'), strtotime($result['period'])),
				'date_start'    => date($this->language->get('date_format_short'), strtotime($result['date_start'])),
				'date_end'    	=> date($this->language->get('date_format_short'), strtotime($result['date_end'])),
				'payroll_status'=> $result['payroll_status'],
				'schedule'      => $this->url->link('presence/schedule', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . $url, true),
				'presence'      => $this->url->link('presence/presence', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $result['presence_period_id'] . $url, true),
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_period'] = $this->language->get('column_period');
		$data['column_date_start'] = $this->language->get('column_date_start');
		$data['column_date_end'] = $this->language->get('column_date_end');
		$data['column_payroll_status'] = $this->language->get('column_payroll_status');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit_last'] = $this->language->get('button_edit_last');
		$data['button_schedule'] = $this->language->get('button_schedule');
		$data['button_presence'] = $this->language->get('button_presence');
		$data['button_delete_last'] = $this->language->get('button_delete_last');
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

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$pagination = new Pagination();
		$pagination->total = $presence_period_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($presence_period_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($presence_period_total - $this->config->get('config_limit_admin'))) ? $presence_period_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $presence_period_total, ceil($presence_period_total / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/presence_period_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['presence_period_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['entry_period'] = $this->language->get('entry_period');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_date_end'] = $this->language->get('entry_date_end');

		if (isset($this->request->get['presence_period_id'])) {
			$data['presence_period_id'] = $this->request->get['presence_period_id'];
		} else {
			$data['presence_period_id'] = 0;
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['date_end'])) {
			$data['error_date_end'] = $this->error['date_end'];
		} else {
			$data['error_date_end'] = '';
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
			'href' => $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($this->request->get['presence_period_id'])) {
			$data['action'] = $this->url->link('presence/presence_period/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('presence/presence_period/edit', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $this->request->get['presence_period_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'] . $url, true);


		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			if (isset($this->request->get['presence_period_id'])) {
				$presence_period_info = $this->model_presence_presence_period->getPresencePeriod($this->request->get['presence_period_id']);
			} else {
				$presence_period_last = $this->model_presence_presence_period->getLatestPeriod();
			}
		}

		if (isset($this->request->post['period'])) {
//			$data['period'] = date_create_from_format("d M y","01 " . $this->request->post['period']);
			$data['period'] = $this->request->post['period'];
		} if (!empty($presence_period_info)) {
			$data['period'] = date($this->language->get('date_format_m_y'), strtotime($presence_period_info['period']));
//			$data['period'] = $presence_period_info['period'];
		} else {
			$data['period'] = '';
		}

		if (isset($this->request->post['date_start'])) {
			$data['date_start'] = $this->request->post['date_start'];
		} elseif (!empty($presence_period_info)) {
			$data['date_start'] = $presence_period_info['date_start'];
		} else {
//			$data['date_start'] = $presence_period_last['date_end'];
			$data['date_start'] = date('Y-m-d', strtotime("+1 day",strtotime($presence_period_last['date_end'])));
		}

		if (isset($this->request->post['date_end'])) {
			$data['date_end'] = $this->request->post['date_end'];
		} elseif (!empty($presence_period_info)) {
			$data['date_end'] = $presence_period_info['date_end'];
		} else {
			$data['date_end'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('presence/presence_period_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'presence/presence_period')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['date_end'] <= $this->request->post['date_start']) {
			$this->error['date_end'] = $this->language->get('error_date_end');
		}

		if (isset($this->request->get['presence_period_id'])) {
			$this->load->model('common/payroll');

			if (!$this->model_common_payroll->checkPeriodStatus($this->request->get['presence_period_id'], 'pending, processing')) {
				$this->error['warning'] = $this->language->get('error_status');
			}
			
			$this->load->model('presence/schedule');
			$schedules_count = $this->model_presence_schedule->getSchedulesCount($this->request->get['presence_period_id']);

			if ($schedules_count) {
				$this->error['warning'] = ($this->language->get('error_data'));
			}

		}
		
		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'presence/presence_period')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		$last_period_id = $this->model_presence_presence_period->getLatestPeriod()['presence_period_id'];
		
		$this->load->model('common/payroll');
		if (!$this->model_common_payroll->checkPeriodStatus($last_period_id, 'pending')) {
			$this->error['warning'] = $this->language->get('error_status');
		}
		
		$this->load->model('presence/schedule');
		$schedules_count = $this->model_presence_schedule->getSchedulesCount($last_period_id);

		if ($schedules_count) {
			$this->error['warning'] = ($this->language->get('error_data'));
		}

		return !$this->error;
	}
}
