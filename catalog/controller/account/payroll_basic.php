<?php
class ControllerAccountPayrollBasic extends Controller
{
	public function index()
	{
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/payroll_basic', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/payroll_basic');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/payroll');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/payroll_basic', '', true)
		);

		$language_items = [
			'heading_title',
			'text_gaji_pokok',
			'text_tunj_jabatan',
			'text_tunj_hadir',
			'text_tunj_pph',
			'text_uang_makan',
			'text_gaji_dasar',
			'button_back'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$payroll_basic_info = $this->model_account_payroll->getPayrollBasicByCustomer($this->customer->getId());

		if ($payroll_basic_info) {
			$data['gaji_pokok'] = $this->currency->format($payroll_basic_info['gaji_pokok'], $this->session->data['currency']);
			$data['tunj_jabatan'] = $this->currency->format($payroll_basic_info['tunj_jabatan'], $this->session->data['currency']);
			$data['tunj_hadir'] = $this->currency->format($payroll_basic_info['tunj_hadir'], $this->session->data['currency']);
			$data['tunj_pph'] = $this->currency->format($payroll_basic_info['tunj_pph'], $this->session->data['currency']);
			$data['uang_makan'] = $this->currency->format($payroll_basic_info['uang_makan'], $this->session->data['currency']);

			$gaji_dasar = $payroll_basic_info['gaji_pokok'] + $payroll_basic_info['tunj_jabatan'] + $payroll_basic_info['tunj_hadir'] + $payroll_basic_info['tunj_pph'] + (25 * $payroll_basic_info['uang_makan']);
			$data['gaji_dasar'] = $this->currency->format($gaji_dasar, $this->session->data['currency']);
		} else {
			$data['gaji_pokok'] = '-';
			$data['tunj_jabatan'] = '-';
			$data['tunj_hadir'] = '-';
			$data['tunj_pph'] = '-';
			$data['uang_makan'] = '-';
			$data['gaji_dasar'] = '-';
		}

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/payroll_basic', $data));
	}
}
