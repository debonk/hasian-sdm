<?php
class ControllerReportPayroll extends Controller
{
	public function index()
	{
		$this->load->language('report/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');

		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		$url = '';
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/payroll', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['export'] = $this->url->link('report/payroll/export', 'token=' . $this->session->data['token'] . $url, true);

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		$data['period_info'] = date($this->language->get('date_format_m_y'), strtotime($period_info['period']));

		$language_items = array(
			'heading_title',
			'text_list',
			'button_filter',
			'button_export'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$data['presence_period_id'] = $presence_period_id;

		$this->load->model('presence/presence_period');
		$data['presence_periods'] = $this->model_presence_presence_period->getPresencePeriods();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/payroll', $data));
	}

	public function report()
	{
		$this->load->language('report/payroll');

		$this->load->model('report/payroll');
		$this->load->model('payroll/payroll');

		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

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

		$data['payrolls'] = array();

		$data['component_codes'] = $this->model_payroll_payroll->getPayrollComponentCodes($presence_period_id);

		foreach ($data['component_codes'] as $code) {
			$data['text_component'][$code] = $this->language->get('text_' . $code);
		}

		$this->load->model('common/payroll');

		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'approved, released, completed');

		if ($period_status) {
			$filter_data = array(
				'sort'      => $sort,
				'order'     => $order,
				'start'     => ($page - 1) * $this->config->get('config_limit_admin'),
				'limit'     => $this->config->get('config_limit_admin')
			);

			$results = $this->model_report_payroll->getPayrolls($presence_period_id, $filter_data);

			foreach ($results as $result) {
				$earning = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + $result['total_uang_makan'];
				$deduction = $result['pot_sakit'] + $result['pot_bolos'] + $result['pot_tunj_hadir'] + $result['pot_gaji_pokok'] + $result['pot_terlambat'];

				$net_salary = $earning - $deduction;

				//Payroll Component
				$component_data = array();

				$component_info = $this->model_payroll_payroll->getPayrollComponentTotal($presence_period_id, $result['customer_id'], 'code');
				foreach ($data['component_codes'] as $code) {
					$component_data[$code] = $this->currency->format($component_info[$code], $this->config->get('config_currency'));
				}

				$data['payrolls'][] = array(
					'nip' 				=> $result['nip'],
					'customer'			=> $result['customer'],
					'customer_group'	=> $result['customer_group'],
					'location'			=> $result['location'],
					'net_salary'    	=> $this->currency->format($net_salary, $this->config->get('config_currency')),
					'component_data'	=> $component_data,
					'grandtotal'    	=> $this->currency->format($net_salary + $component_info['grandtotal'], $this->config->get('config_currency')),
				);
			}
		}

		$result_count = $this->model_report_payroll->getPayrollsCount($presence_period_id);

		$language_list = array(
			'text_no_results',
			'column_nip',
			'column_customer',
			'column_customer_group',
			'column_location',
			'column_net_salary',
			'column_component',
			'column_grandtotal'
		);
		foreach ($language_list as $item) {
			$data[$item] = $this->language->get($item);
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

		$data['sort_customer'] = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . '&sort=customer' . $url, true);
		$data['sort_customer_group'] = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_location'] = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

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
		$pagination->url = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($result_count - $this->config->get('config_limit_admin'))) ? $result_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $result_count, ceil($result_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('report/payroll_report', $data));
	}

	public function export()
	{
		$this->load->language('report/payroll');

		$this->load->model('report/payroll');
		$this->load->model('payroll/payroll');

		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

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

		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated, approved, released, completed');

		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);
		
		if ($period_status && $this->user->hasPermission('modify', 'report/payroll')) {
			$language_items = array(
				'heading_title',
				'text_status',
				'column_no',
				'column_nip',
				'column_customer',
				'column_customer_group',
				'column_location',
				'column_net_salary',
				'column_component',
				'column_grandtotal'
			);
			foreach ($language_items as $language_item) {
				$data[$language_item] = $this->language->get($language_item);
			}

			$period = date('M_Y', strtotime($period_info['period']));

			$output = '';

			$component_codes = $this->model_payroll_payroll->getPayrollComponentCodes($presence_period_id);

			foreach ($component_codes as $code) {
				$text_component[$code] = $this->language->get('text_' . $code);
			}

			$output = '||' . $data['heading_title'] . ' - ' . $period . '|||||'. $data['text_status'] . '|' . $period_info['payroll_status'] . "\n";
			$output .= $data['column_no'] . '|' . $data['column_nip'] . '|' . $data['column_customer'] . '|' . $data['column_customer_group'] . '|' . $data['column_location'] . '|' . $data['column_net_salary'];
			foreach ($text_component as $title) {
				$output .= '|' . $title;
			}

			$output .= '|' . $data['column_grandtotal'];

			$output = str_replace('|', "\t", $output);

			$filter_data = array(
				'sort'      => $sort,
				'order'     => $order
			);

			$results = $this->model_report_payroll->getPayrolls($presence_period_id, $filter_data);

			$no = 1;

			foreach ($results as $result) {
				$earning = $result['gaji_pokok'] + $result['tunj_jabatan'] + $result['tunj_hadir'] + $result['tunj_pph'] + $result['total_uang_makan'];
				$deduction = $result['pot_sakit'] + $result['pot_bolos'] + $result['pot_tunj_hadir'] + $result['pot_gaji_pokok'] + $result['pot_terlambat'];

				$net_salary = $earning - $deduction;

				$value = '';
				$value .= $no . '|' . $result['nip'] . '|' . $result['customer'] . '|' . $result['customer_group'] . '|' . $result['location'] . '|' . $this->currency->format($net_salary, $this->config->get('config_currency')) . '|';

				//Payroll Component
				$component_info = $this->model_payroll_payroll->getPayrollComponentTotal($presence_period_id, $result['customer_id'], 'code');
				foreach ($component_codes as $code) {
					$value .= $this->currency->format($component_info[$code], $this->config->get('config_currency')) . '|';
				}
				$value .= $this->currency->format($net_salary + $component_info['grandtotal'], $this->config->get('config_currency')) . '|';

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\',	$value);
				$value = str_replace('\'', '\\\'',	$value);
				$value = str_replace('\\\n', '\n',	$value);
				$value = str_replace('\\\r', '\r',	$value);
				$value = str_replace('\\\t', '\t',	$value);
				$value = str_replace('|', "\t",	$value);
				$value = stripslashes($value);

				$output .= "\n" . $value;
				// $output .= "<br>" . $value;

				$no++;
			}

			$filename = date('Ym', strtotime($period_info['period'])) . '_Report_Payroll_' . $period;

			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.xls');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
			// echo $output;
		} else {

			$this->response->redirect($this->url->link('report/payroll', 'token=' . $this->session->data['token'] . '&presence_period_id=' . $presence_period_id, true));
		}
	}
}
