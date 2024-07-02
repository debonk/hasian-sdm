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

			$this->session->data['success'] = $this->language->get('text_success_component');

			$this->response->redirect($this->url->link('extension/component', 'token=' . $this->session->data['token'], true));
		}

		$language_items = [
			'heading_title',
			'text_edit',
			'text_enabled',
			'text_disabled',
			'text_earning',
			'text_deduction',
			'text_wage_min',
			'text_wage_real',
			'text_wage_both',
			'entry_calculation_base',
			'entry_activation_health',
			'entry_activation_non_jht',
			'entry_activation_jht',
			'entry_activation_jp',
			'entry_min_wage',
			'entry_min_wage_old',
			'entry_date_start',
			'entry_status',
			'entry_sort_order',
			'help_activation',
			'button_save',
			'button_cancel',		
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

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

		$field_items = array(
			'insurance_activation_health',
			'insurance_activation_non_jht',
			'insurance_activation_jht',
			'insurance_activation_jp',
			'insurance_calculation_base',
			'insurance_min_wage',
			'insurance_min_wage_old',
			'insurance_date_start',
			'insurance_status',
			'insurance_sort_order'
		);
		foreach ($field_items as $field) {
			if (isset($this->request->post[$field])) {
				$data[$field] = $this->request->post[$field];
			} else {
				$data[$field] = $this->config->get($field);
			}
		}

		$data['insurance_date_start'] = date('M Y', $this->config->get('insurance_date_start'));

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
