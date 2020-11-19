<?php
class ControllerComponentInsurance extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('component/insurance');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->request->post['insurance_date_start'] = strtotime('25 ' . $this->request->post['insurance_date_start']);
			$this->model_setting_setting->editSetting('insurance', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/component', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['text_earning'] = $this->language->get('text_earning');
		$data['text_deduction'] = $this->language->get('text_deduction');

		$data['entry_min_wage'] = $this->language->get('entry_min_wage');
		$data['entry_min_wage_old'] = $this->language->get('entry_min_wage_old');
		$data['entry_date_start'] = $this->language->get('entry_date_start');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_component'),
			'href' => $this->url->link('extension/component', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('component/insurance', 'token=' . $this->session->data['token'], true)
		);

		$data['action'] = $this->url->link('component/insurance', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/component', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['insurance_min_wage'])) {
			$data['insurance_min_wage'] = $this->request->post['insurance_min_wage'];
		} else {
			$data['insurance_min_wage'] = $this->config->get('insurance_min_wage');
		}

		if (isset($this->request->post['insurance_min_wage_old'])) {
			$data['insurance_min_wage_old'] = $this->request->post['insurance_min_wage_old'];
		} else {
			$data['insurance_min_wage_old'] = $this->config->get('insurance_min_wage_old');
		}

		if (isset($this->request->post['insurance_date_start'])) {
			$data['insurance_date_start'] = date('M Y', $this->request->post['insurance_date_start']);
		} else {
			$data['insurance_date_start'] = date('M Y', $this->config->get('insurance_date_start'));
		}

		if (isset($this->request->post['insurance_status'])) {
			$data['insurance_status'] = $this->request->post['insurance_status'];
		} else {
			$data['insurance_status'] = $this->config->get('insurance_status');
		}

		if (isset($this->request->post['insurance_sort_order'])) {
			$data['insurance_sort_order'] = $this->request->post['insurance_sort_order'];
		} else {
			$data['insurance_sort_order'] = $this->config->get('insurance_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('component/insurance', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'component/insurance')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
