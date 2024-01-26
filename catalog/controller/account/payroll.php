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
			'text_earning',
			'text_deduction',
			'text_gaji_pokok',
			'text_tunj_jabatan',
			'text_tunj_hadir',
			'text_tunj_pph',
			'text_pot_tunj_hadir',
			'text_pot_gaji_pokok',
			'text_total_deduction',
			'text_total_earning',
			'text_grandtotal',
			'error_no_result',
			'button_back'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['payroll_check'] = false;

		// $period_info = $this->model_account_payroll->getPeriodByDate(date('Y-m-d', strtotime('-1 month')));
		$period_info = $this->model_account_payroll->getPeriodByDate(date('Y-m-d', strtotime('25 Oct 2023')));

		if ($period_info) {
			$payroll_info = $this->model_account_payroll->getPayrollDetail($period_info['presence_period_id'], $this->customer->getId());
		}

		if (!empty($payroll_info) && $payroll_info['status_released'] == 'released') {
			$data['payroll_check'] = true;

			$data['text_period'] = sprintf($this->language->get('text_period'), date($this->language->get('date_format_m_y'), strtotime($period_info['period'])));

			$data['gaji_pokok']     		= $this->currency->format($payroll_info['gaji_pokok'], $this->config->get('config_currency'));
			$data['tunj_jabatan']   		= $this->currency->format($payroll_info['tunj_jabatan'], $this->config->get('config_currency'));
			$data['tunj_hadir']     		= $this->currency->format($payroll_info['tunj_hadir'], $this->config->get('config_currency'));
			$data['tunj_pph']       		= $this->currency->format($payroll_info['tunj_pph'], $this->config->get('config_currency'));
			$data['uang_makan']     		= $this->currency->format($payroll_info['uang_makan'], $this->config->get('config_currency'));
			$data['total_uang_makan']     	= $this->currency->format($payroll_info['total_uang_makan'], $this->config->get('config_currency'));

			$data['pot_sakit']     			= $this->currency->format($payroll_info['pot_sakit'], $this->config->get('config_currency'));
			$data['pot_bolos']     			= $this->currency->format($payroll_info['pot_bolos'], $this->config->get('config_currency'));
			$data['pot_tunj_hadir'] 		= $this->currency->format($payroll_info['pot_tunj_hadir'], $this->config->get('config_currency'));
			$data['pot_gaji_pokok'] 		= $this->currency->format($payroll_info['pot_gaji_pokok'], $this->config->get('config_currency'));
			$data['pot_terlambat']  		= $this->currency->format($payroll_info['pot_terlambat'], $this->config->get('config_currency'));

			$data['text_total_uang_makan'] 	= sprintf($this->language->get('text_total_uang_makan'), $payroll_info['hke'], $data['uang_makan']);
			$data['text_pot_sakit'] 		= sprintf($this->language->get('text_pot_sakit'), $payroll_info['total_sakit'], $data['uang_makan']);
			$data['text_pot_bolos'] 		= sprintf($this->language->get('text_pot_bolos'), $payroll_info['total_bolos'], $data['uang_makan']);
			$data['text_pot_terlambat'] 	= sprintf($this->language->get('text_pot_terlambat'), $payroll_info['total_t'], $data['uang_makan']);

			$earning = $payroll_info['gaji_dasar'];
			$deduction = $payroll_info['total_potongan'];

			// Payroll Components
			$data['earning_components'] = [];
			$data['deduction_components'] = [];
			$result_component = [];

			$always_view['overtime'] = 1; //Masukkan ke setting. Tetap di view walaupun nilainya 0.

			$components = $this->model_account_payroll->getPayrollComponents($period_info['presence_period_id'], $this->customer->getId());

			foreach ($components as $component) {
				if (isset($always_view[$component['code']]) && $always_view[$component['code']]) {
					if ($component['type']) {
						$earning += $component['value'];

						$data['earning_components'][] = array(
							'title'	=> $component['title'],
							'value' => $this->currency->format($component['value'], $this->config->get('config_currency'))
						);
					} else {
						$deduction -= $component['value'];

						$data['deduction_components'][] = array(
							'title'	=> $component['title'],
							'value' => $this->currency->format(-$component['value'], $this->config->get('config_currency'))
						);
					}
				} else {
					if (!isset($result_component[$component['title']])) {
						$result_component[$component['title']] = 0;
					}

					$result_component[$component['title']] += $component['value'];
				}
			}

			if ($result_component) {
				foreach ($result_component as $key => $value) {
					if ($value < 0) {
						$deduction -= $value;

						$data['deduction_components'][] = array(
							'title'	=> $key,
							'value' => $this->currency->format(-$value, $this->config->get('config_currency'))
						);
					} elseif ($value > 0) {
						$earning += $value;

						$data['earning_components'][] = array(
							'title'	=> $key,
							'value' => $this->currency->format($value, $this->config->get('config_currency'))
						);
					}
				}
			}

			$data['earning']     	= $this->currency->format($earning, $this->config->get('config_currency'));
			$data['deduction']     	= $this->currency->format($deduction, $this->config->get('config_currency'));
			$data['grandtotal']  	= $this->currency->format($earning - $deduction, $this->config->get('config_currency'));
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
