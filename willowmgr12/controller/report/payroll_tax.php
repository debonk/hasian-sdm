<?php
class ControllerReportPayrollTax extends Controller
{
	public function index()
	{
		$this->load->language('report/payroll_tax');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');
		$this->load->model('presence/presence_period');

		$language_items = array(
			'heading_title',
			'text_list',
			'entry_period',
			'button_filter',
			'button_export'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$presence_periods = $this->model_presence_presence_period->getPresencePeriods();

		if (isset($this->request->get['presence_period_id'])) {
			$presence_period_id = $this->request->get['presence_period_id'];
		} else {
			$presence_period_id = $presence_periods[0]['presence_period_id'];
		}

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/payroll_tax', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['export'] = $this->url->link('report/payroll_tax/export', 'token=' . $this->session->data['token'] . $url, true);

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		$data['period_info'] = date($this->language->get('date_format_m_y'), strtotime($period_info['period']));

		if (date('n', strtotime($period_info['period'])) == 12) {
			$data['heading_title'] .= ' ' . $this->language->get('text_final');
			$data['period_info'] .= ' (' . $this->language->get('text_full_year') . ')';
		}

		$data['token'] = $this->session->data['token'];

		$data['presence_periods'] = $presence_periods;
		$data['presence_period_id'] = $presence_period_id;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/payroll_tax', $data));
	}

	public function report()
	{
		$this->load->language('report/payroll_tax');

		$this->load->model('report/payroll');

		$language_list = array(
			'text_no_results',
			'column_customer',
			'column_npwp',
			'column_npwp_address',
			'column_customer_group',
			'column_location',
			'column_gender',
			'column_non_taxed_category',
			'column_ter_category',
			'column_basic_salary',
			'column_allowance',
			'column_deduction',
			'column_holiday_allowance',
			'column_gross_salary',
			'column_ter_tariff',
			'column_tax',
			'column_tax_final',
			'column_tax_net',
			'column_tax_paid',
			'column_functional_expense',
			'column_insurance_employment',
			'column_insurance_health',
			'column_thp',
		);
		foreach ($language_list as $item) {
			$data[$item] = $this->language->get($item);
		}

		$presence_period_id = $this->request->get['presence_period_id'] ?? 0;
		// if (isset($this->request->get['presence_period_id'])) {
		// 	$presence_period_id = $this->request->get['presence_period_id'];
		// } else {
		// 	$presence_period_id = 0;
		// }

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer';
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

		$data['taxes'] = array();
		$result_count = 0;

		$this->load->model('common/payroll');

		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed');

		$data['final'] = false;

		if ($period_status) {
			$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

			$filter_data = array(
				// 'code'		=> 'insurance, overtime, incentive, dayoff, cutoff', //component yang ikut dalam perhitungan PPh21
				'sort'      => $sort,
				'order'     => $order,
				'start'     => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'     => $this->config->get('config_limit_admin')
				// 'grouped'	=> 1
			);

			if (date('n', strtotime($period_info['period'])) != 12) {
				$results = $this->model_report_payroll->getTaxes($presence_period_id, $filter_data);
				// $data['final'] = false;
			} else {
				$results = $this->model_report_payroll->getFinalTaxes($presence_period_id, $filter_data);
				$data['final'] = true;
			}

			foreach ($results as $result) {
				if ($data['final']) {
					$thp = $result['gross_salary'] - $result['tax_final'];
				} else {
					$thp = $result['gross_salary'] - $result['tax'];
				}

				$data['taxes'][] = array(
					// 'customer_id' 		=> $result['customer_id'],
					'customer'				=> $result['customer'],
					// 'nik'					=> $result['nik'],
					// 'id_card_address'		=> $result['id_card_address'],
					'npwp'					=> $result['npwp'],
					'npwp_address'			=> strlen($result['npwp_address']) > 30 ? substr($result['npwp_address'], 0, 28) . '..' : $result['npwp_address'],
					'customer_group'		=> $result['customer_group'],
					'gender' 				=> $result['gender_code'],
					'non_taxed_category'	=> $result['non_taxed_category'],
					'ter_category'			=> $result['ter_category'],
					'basic_salary'			=> $this->currency->format($result['basic_salary'], $this->config->get('config_currency')),
					'allowance'				=> $this->currency->format($result['allowance'], $this->config->get('config_currency')),
					'deduction'				=> $this->currency->format($result['deduction'], $this->config->get('config_currency')),
					'insurance_employment'	=> $this->currency->format($result['insurance_employment'], $this->config->get('config_currency')),
					'insurance_health'		=> $this->currency->format($result['insurance_health'], $this->config->get('config_currency')),
					'holiday_allowance'		=> $this->currency->format($result['holiday_allowance'], $this->config->get('config_currency')),
					'gross_salary'			=> $this->currency->format($result['gross_salary'], $this->config->get('config_currency')),
					'ter_tariff'			=> $result['ter_tariff'] . '%',
					'tax_final'				=> $this->currency->format($result['tax_final'] ?? 0, $this->config->get('config_currency')),
					'tax'					=> $this->currency->format($result['tax'], $this->config->get('config_currency')),
					'tax_net'				=> $this->currency->format($result['tax_net'] ?? 0, $this->config->get('config_currency')),
					'functional_expense'	=> $this->currency->format($result['functional_expense'], $this->config->get('config_currency')),
					'thp'					=> $this->currency->format($thp, $this->config->get('config_currency')),
					'location'				=> $result['location'],
				);
			}

			$result_count = $this->model_report_payroll->getPayrollsCount($presence_period_id);
		}

		$data['token'] = $this->session->data['token'];

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_customer'] = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_customer_group'] = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $result_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/payroll_tax/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($result_count - $this->config->get('config_limit_admin'))) ? $result_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $result_count, ceil($result_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('report/payroll_tax_report', $data));
	}

	public function export()
	{
		$this->load->language('report/payroll_tax');

		$this->load->model('report/payroll');

		$language_list = array(
			'heading_title',
			// 'text_no_results',
			// 'column_no',
			// 'column_customer',
			// 'column_nik',
			// 'column_npwp',
			// 'column_npwp_address',
			// 'column_customer_group',
			// 'column_location',
			// 'column_gender',
			// 'column_non_taxed_category',
			// 'column_ter_category',
			// 'column_basic_salary',
			// 'column_allowance',
			// 'column_deduction',
			// 'column_holiday_allowance',
			// 'column_gross_salary',
			// 'column_ter_tariff',
			// 'column_tax',
			// 'column_tax_final',
			// 'column_tax_net',
			// 'column_tax_paid',
			// 'column_functional_expense',
			// 'column_insurance_employment',
			// 'column_insurance_health',
			// 'column_thp',
		);
		foreach ($language_list as $item) {
			$data[$item] = $this->language->get($item);
		}

		$presence_period_id = $this->request->get['presence_period_id'] ?? 0;

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'customer';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$this->load->model('common/payroll');

		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed');

		$error = '';

		switch (1) {
			case true:
				if (!$this->user->hasPermission('modify', 'report/payroll_tax')) {
					$error = $this->language->get('error_permission');

					break;
				}

				if (!$period_status) {
					$error = $this->language->get('error_status');

					break;
				}

				if (!is_file(DIR_FILE . 'Tax21 Template.xlsx')) {
					$error = $this->language->get('error_template');

					break;
				}

				break;

			default:
				break;
		}

		if ($error) {
			$this->session->data['warning'] = $error;

			$this->response->redirect($this->url->link('report/payroll_tax', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true));
		}

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		$taxes_data = [];
		$no = 1;

		$filter_data = array(
			'sort'      => $sort,
			'order'     => $order
		);

		if (date('n', strtotime($period_info['period'])) != 12) {
			$results = $this->model_report_payroll->getTaxes($presence_period_id, $filter_data);
			$final = false;

			foreach ($results as $result) {
				$taxes_data[] = array(
					'no' 					=> $no,
					'customer'				=> $result['customer'],
					'nik'					=> "'" . $result['nik'],
					'npwp'					=> $result['npwp'],
					'npwp_address'			=> $result['npwp_address'],
					'customer_group'		=> $result['customer_group'],
					'gender' 				=> $result['gender_code'],
					'non_taxed_category'	=> $result['non_taxed_category'],
					'ter_category'			=> $result['ter_category'],
					'basic_salary'			=> $result['basic_salary'],
					'allowance'				=> $result['allowance'],
					'deduction'				=> $result['deduction'],
					'insurance_employment'	=> $result['insurance_employment'],
					'insurance_health'		=> $result['insurance_health'],
					'holiday_allowance'		=> $result['holiday_allowance'],
					'gross_salary'			=> $result['gross_salary'],
					'ter_tariff'			=> $result['ter_tariff'] / 100,
					'tax'					=> $result['tax'],
					'functional_expense'	=> $result['functional_expense'],
					'thp'					=> $result['gross_salary'] - $result['tax'],
				);

				$no++;
			}
		} else {
			$results = $this->model_report_payroll->getFinalTaxes($presence_period_id, $filter_data);
			$final = true;
			$data['heading_title'] .= ' ' . $this->language->get('text_final') . ' (' . $this->language->get('text_full_year') . ')';

			foreach ($results as $result) {
				$taxes_data[] = array(
					'no' 					=> $no,
					'customer'				=> $result['customer'],
					'nik'					=> "'" . $result['nik'],
					'npwp'					=> $result['npwp'],
					'npwp_address'			=> $result['npwp_address'],
					'customer_group'		=> $result['customer_group'],
					'gender' 				=> $result['gender_code'],
					'non_taxed_category'	=> $result['non_taxed_category'],
					// 'ter_category'			=> $result['ter_category'],
					'basic_salary'			=> $result['basic_salary'],
					'allowance'				=> $result['allowance'],
					'deduction'				=> $result['deduction'],
					'insurance_employment'	=> $result['insurance_employment'],
					'insurance_health'		=> $result['insurance_health'],
					'holiday_allowance'		=> $result['holiday_allowance'],
					'gross_salary'			=> $result['gross_salary'],
					// 'ter_tariff'			=> $result['ter_tariff'] / 100,
					'tax_final'				=> $result['tax_final'] ?? 0,
					'tax'					=> $result['tax'],
					'tax_net'				=> $result['tax_net'] ?? 0,
					'functional_expense'	=> $result['functional_expense'],
					'thp'					=> $result['gross_salary'] - $result['tax_final'],
				);

				$no++;
			}
		}

		$store_name = htmlspecialchars_decode($this->config->get('config_name'));
		$period = \PhpOffice\PhpSpreadsheet\Shared\Date::stringToExcel($period_info['period']);

		$php_spreadsheet = new Spreadsheet('Xlsx');

		$spreadsheet = $php_spreadsheet->loadSpreadsheet(DIR_FILE . 'Tax21 Template.xlsx');

		$sheet = $spreadsheet->getActiveSheet();

		if (!$final) {
			$sheet->RemoveColumn('S', 3);
		} else {
			$sheet
				->RemoveColumn('Q', 2)
				->RemoveColumn('I');
		}

		$sheet->insertNewRowBefore(5, count($taxes_data) - 2);

		$sheet
			->setCellValue('A1', utf8_strtoupper($store_name))
			->setCellValue('A2', $data['heading_title'])
			->setCellValue('F2', $period)
			->fromArray(array_values($taxes_data), null, 'A4', true);

		# Force to download
		$new_file = DIR_DOWNLOAD . $data['heading_title'] . '_Export.xlsx';

		$writer = $php_spreadsheet->writer('Xlsx');
		$writer->setPreCalculateFormulas(false);

		$writer->save($new_file);

		$spreadsheet->disconnectWorksheets();
		unset($spreadsheet);

		if (!headers_sent()) {
			if (is_file($new_file)) {
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment; filename=' . basename($new_file));
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($new_file));

				if (ob_get_level()) {
					ob_end_clean();
				}

				readfile($new_file, 'rb');

				exit();
			} else {
				exit('Error: Could not find file ' . $new_file . '!');
			}
		} else {
			exit('Error: Headers already sent out!');
		}
	}
}
