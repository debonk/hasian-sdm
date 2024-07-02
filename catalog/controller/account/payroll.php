<?php
class ControllerAccountPayroll extends Controller
{
	public function index()
	{
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/payroll', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/payroll', '', true)
		);

		$this->load->model('account/payroll');

		$language_items = [
			'heading_title',
			'text_addition',
			'text_deduction',
			'text_gaji_pokok',
			'text_tunj_jabatan',
			'text_tunj_hadir',
			'text_tunj_pph',
			'text_pot_tunj_hadir',
			'text_pot_gaji_pokok',
			'text_total_deduction',
			'text_total_addition',
			'text_grandtotal',
			'error_no_result',
			'button_back'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['payroll_check'] = false;

		$period_info = $this->model_account_payroll->getPeriodByDate(date('Y-m-d', strtotime('-1 month')));

		if ($period_info) {
			$payroll_info = $this->model_account_payroll->getPayrollDetail($period_info['presence_period_id'], $this->customer->getId());
		}

		$data['payroll_detail'] = [
			'addition'			=> [],
			'deduction'			=> [],
			'total'				=> [
				'addition'	=> [],
				'deduction'	=> []
			]
		];

		if (!empty($payroll_info)) {
			$data['payroll_detail']['addition'] = array_merge($payroll_info['main_component']['addition'], $payroll_info['sub_component']['addition']);
			$data['payroll_detail']['deduction'] = array_merge($payroll_info['main_component']['deduction'], $payroll_info['sub_component']['deduction']);

			foreach (array_keys($data['payroll_detail']['total']) as $key) {
				$data['payroll_detail']['total'][$key] = [
					'title'	=> $this->language->get('text_total_' . $key),
					'value'	=> $payroll_info['main_component']['total'][$key]['value'] + $payroll_info['sub_component']['total'][$key]['value'],
					'text'	=> $this->currency->format($payroll_info['main_component']['total'][$key]['value'] + $payroll_info['sub_component']['total'][$key]['value'], $this->config->get('config_currency'))
				];
			}
			
			$data['grandtotal'] = $this->currency->format($data['payroll_detail']['total']['addition']['value'] - $data['payroll_detail']['total']['deduction']['value'], $this->config->get('config_currency'));

			$data['text_period'] = sprintf($this->language->get('text_period'), date($this->language->get('date_format_m_y'), strtotime($period_info['period'])));

			$data['payroll_check'] = true;
		}

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/payroll', $data));
	}
}
