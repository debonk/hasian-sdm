<?php
class ControllerReportPayroll extends Controller
{
	private $filter_items = array(
		'period',
		'group'
	);

	private function urlFilter($excluded_item = null)
	{
		$url_filter = '';

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$url_filter .= '&filter_' . $filter_item . '=' . urlencode(html_entity_decode($this->request->get['filter_' . $filter_item], ENT_QUOTES, 'UTF-8'));
				// $url_filter .= '&filter_' . $filter_item . '=' . $this->request->get['filter_' . $filter_item];
			}
		}

		if ($excluded_item != 'sort') {
			if (isset($this->request->get['sort'])) {
				$url_filter .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url_filter .= '&order=' . $this->request->get['order'];
			}
		}

		if (isset($this->request->get['page']) && $excluded_item != 'page') {
			$url_filter .= '&page=' . $this->request->get['page'];
		}

		return $url_filter;
	}

	public function index()
	{
		$this->load->language('report/payroll');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('common/payroll');

		$language_items = array(
			'heading_title',
			'text_list',
			'text_none',
			'entry_period',
			'entry_group',
			'button_filter',
			'button_export'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		// if (isset($this->request->get['sort'])) {
		// 	$sort = $this->request->get['sort'];
		// } else {
		// 	$sort = 'name';
		// }

		// if (isset($this->request->get['order'])) {
		// 	$order = $this->request->get['order'];
		// } else {
		// 	$order = 'ASC';
		// }

		// if (isset($this->request->get['page'])) {
		// 	$page = $this->request->get['page'];
		// } else {
		// 	$page = 1;
		// }

		// $presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		// $period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if (isset($this->request->get['presence_period_id'])) {
			$period_info = $this->model_common_payroll->getPeriod($this->request->get['presence_period_id']);
		}

		if (!empty($period_info)) {
			$filter['period'] = date('M Y', strtotime($period_info['period']));
		} elseif (!empty($filter['period'])) {
			$date = date_create_from_format('d M Y', '01 ' . $filter['period']);

			$period_info = $this->model_common_payroll->getPeriodByDate(date_format($date, 'Y-m-d'));
		}

		if (empty($period_info)) {
			$period_info = $this->model_common_payroll->getPeriod();
			$filter['period'] = date('M Y', strtotime($period_info['period']));
		}

		$presence_period_id = $period_info['presence_period_id'];

		$url = $this->urlFilter();
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('report/payroll', 'token=' . $this->session->data['token'], true)
		);

		// $data['export'] = $this->url->link('report/payroll/export', 'token=' . $this->session->data['token'] . $url, true);

		$data['groups'] = [];

		$data['groups'][] = [
			'value'	=> 'customer_group',
			'text'	=> $this->language->get('text_customer_group'),
		];

		$data['groups'][] = [
			'value'	=> 'customer_department',
			'text'	=> $this->language->get('text_customer_department')
		];

		$data['groups'][] = [
			'value'	=> 'location',
			'text'	=> $this->language->get('text_location')
		];

		$data['report_route'] = !isset($filter['group']) ? 'report/payroll/report' : 'report/payroll/reportGroup';

		$data['presence_period_id'] = $presence_period_id;
		$data['token'] = $this->session->data['token'];
		$data['url'] = $url;

		$data['filter_items'] = json_encode($this->filter_items);
		$data['filter'] = $filter;
		// $data['sort'] = $sort;
		// $data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/payroll', $data));
	}

	public function report()
	{
		$this->load->language('report/payroll');

		$this->load->model('common/payroll');
		$this->load->model('report/payroll');

		$language_list = array(
			'text_no_results',
			'column_nip',
			'column_name',
			'column_customer_department',
			'column_customer_group',
			'column_location',
			'column_net_salary',
			'column_component',
			'column_grandtotal'
		);
		foreach ($language_list as $item) {
			$data[$item] = $this->language->get($item);
		}

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'name';
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

		$presence_period_id = $this->request->get['presence_period_id'];

		$url = $this->urlFilter();
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['payrolls'] = array();
		$result_count = 0;

		$limit = $this->config->get('config_limit_admin');

		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated, approved, released, completed');

		if ($period_status) {
			$filter_data = array(
				'filter'  	=> $filter,
				'sort'  	=> $sort,
				'order' 	=> $order,
				'start' 	=> ($page - 1) * $limit,
				'limit' 	=> $limit
			);

			# Payroll Components
			$data['component_codes'] = $this->model_report_payroll->getPayrollComponentCodes($presence_period_id);

			$component_total = array('net_salary' => 0, 'grandtotal' => 0);

			foreach ($data['component_codes'] as $code) {
				$data['text_component'][$code] = $this->language->get('text_' . $code);
				$component_total[$code] = 0;
			}

			$results = $this->model_report_payroll->getPayrolls($presence_period_id, $filter_data);

			foreach ($results as $result) {
				$component_total['net_salary'] += $result['net_salary'];

				//Payroll Component
				$component_data = array();

				$component_info = $this->model_report_payroll->getPayrollComponentTotal($presence_period_id, $result['customer_id'], 'code');

				foreach ($data['component_codes'] as $code) {
					$component_data[$code] = $this->currency->format($component_info[$code], $this->config->get('config_currency'));
					$component_total[$code] += $component_info[$code];
				}

				$component_total['grandtotal'] += $component_info['grandtotal'];

				$data['payrolls'][] = array(
					'nip' 					=> $result['nip'],
					'name' 					=> $result['name'],
					'customer_group' 		=> $result['customer_group'],
					'customer_department' 	=> $result['customer_department'],
					'location' 				=> $result['location'],
					'net_salary'    		=> $this->currency->format($result['net_salary'], $this->config->get('config_currency')),
					'component_data'		=> $component_data,
					'grandtotal'    		=> $this->currency->format($result['net_salary'] + $component_info['grandtotal'], $this->config->get('config_currency'))
				);
			}

			$result_count = $this->model_report_payroll->getPayrollsCount($presence_period_id);
		}

		$url = $this->urlFilter('sort');
		$url .= '&presence_period_id=' . $presence_period_id;

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_name'] = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . '&sort=name' . $url, true);
		$data['sort_customer_group'] = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . '&sort=customer_group' . $url, true);
		$data['sort_customer_department'] = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . '&sort=customer_department' . $url, true);
		$data['sort_location'] = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . '&sort=location' . $url, true);

		$url = $this->urlFilter('page');
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
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/payroll/report', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($result_count - $limit)) ? $result_count : ((($page - 1) * $limit) + $limit), $result_count, ceil($result_count / $limit));

		$data['token'] = $this->session->data['token'];

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('report/payroll_report', $data));
	}

	public function reportGroup()
	{
		$this->load->language('report/payroll');

		$this->load->model('common/payroll');
		$this->load->model('report/payroll');

		$language_list = array(
			'text_no_results',
			'column_customer_count',
			'column_customer_department',
			'column_customer_group',
			'column_location',
			'column_net_salary',
			'column_component',
			'column_grandtotal'
		);
		foreach ($language_list as $item) {
			$data[$item] = $this->language->get($item);
		}

		$filter = [];

		foreach ($this->filter_items as $filter_item) {
			if (isset($this->request->get['filter_' . $filter_item])) {
				$filter[$filter_item] = $this->request->get['filter_' . $filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'group_item';
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

		$presence_period_id = $this->request->get['presence_period_id'];

		$url = $this->urlFilter();
		$url .= '&presence_period_id=' . $presence_period_id;

		$data['payrolls'] = array();
		$result_count = 0;

		$limit = $this->config->get('config_limit_admin');

		$period_status = $this->model_common_payroll->checkPeriodStatus($presence_period_id, 'generated, approved, released, completed');

		$data['groups_title'] = [];

		$groups = array(
			'customer',
			'customer_group',
			'customer_department',
			'location'
		);

		$group_item = $filter['group'];

		foreach ($groups as $group) {
			if ($group != $group_item) {
				$data['groups_title'][$group] = '# ' . $this->language->get('text_' . $group);
			}
		}

		$data['column_group_item'] = $this->language->get('column_' . $group_item);

		if ($period_status) {
			$filter_data = array(
				'filter'  	=> $filter,
				'sort'  	=> $sort,
				'order' 	=> $order,
				'start' 	=> ($page - 1) * $limit,
				'limit' 	=> $limit
			);

			$results = $this->model_report_payroll->getPayrollsGrouped($presence_period_id, $filter_data);

			foreach ($results as $result) {
				$group_data = [];

				foreach (array_keys($data['groups_title']) as $group) {
					$group_data[$group] = $result[$group . '_count'];
				}

				$data['payrolls'][] = array(
					'item' 				=> $result[$group_item],
					'group_data'		=> $group_data,
					'net_salary'    	=> $this->currency->format($result['net_salary_total'], $this->config->get('config_currency')),
					'component'    		=> $this->currency->format($result['component_total'], $this->config->get('config_currency')),
					'grandtotal'    	=> $this->currency->format($result['net_salary_total'] + $result['component_total'], $this->config->get('config_currency'))
				);
			}

			$result_count = $this->model_report_payroll->getPayrollsGroupedCount($presence_period_id, $filter_data);
		}

		$url = $this->urlFilter('sort');
		$url .= '&presence_period_id=' . $presence_period_id;

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_group_item'] = $this->url->link('report/payroll/reportGroup', 'token=' . $this->session->data['token'] . '&sort=group_item' . $url, true);

		$data['sort_group_count'] = [];

		foreach (array_keys($data['groups_title']) as $group) {
			$data['sort_group_count'][$group] = $this->url->link('report/payroll/reportGroup', 'token=' . $this->session->data['token'] . '&sort=' . $group . '_count' . $url, true);
		}

		$url = $this->urlFilter('page');
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
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('report/payroll/reportGroup', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($result_count) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($result_count - $limit)) ? $result_count : ((($page - 1) * $limit) + $limit), $result_count, ceil($result_count / $limit));

		$data['token'] = $this->session->data['token'];

		$data['filter'] = $filter;
		$data['sort'] = $sort;
		$data['order'] = $order;

		$this->response->setOutput($this->load->view('report/payroll_report_group', $data));
	}

	/* 	public function export()
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

			$output = '||' . $data['heading_title'] . ' - ' . $period . '|||||' . $data['text_status'] . '|' . $period_info['payroll_status'] . "\n";
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
 */
}
