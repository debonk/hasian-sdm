<?php
class ControllerReportCustomerLoan extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('report/customer_loan');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = '';
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = '';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

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
			'href' => $this->url->link('report/customer_loan', 'token=' . $this->session->data['token'] . $url, true)
		);

		$this->load->model('report/customer');

		$data['customers'] = array();

		$filter_data = array(
			'filter_date_start'	=> $filter_date_start,
			'filter_date_end'	=> $filter_date_end,
			'start'             => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'             => $this->config->get('config_limit_admin')
		);

		$results = $this->model_report_customer->getLoansTotal($filter_data);

		foreach ($results as $result) {
			if ($result['total']) {
				$data['customers'][] = array(
					'name'       	 => $result['name'],
					'customer_group' => $result['customer_group'],
					'email'          => $result['email'],
					'telephone'      => $result['telephone'],
					'total'          => $this->currency->format($result['total'], $this->config->get('config_currency')),
					'view'           => $this->url->link('report/customer/view', 'token=' . $this->session->data['token'] . '&customer_id=' . $result['customer_id'] . $url, true)
				);
			}
		}

		$customer_loan_count = count($data['customers']);
		
		$data['grandtotal'] = $this->currency->format(array_sum(array_column($results, 'total')), $this->config->get('config_currency'));

		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'text_total',
			'entry_date_start',
			'entry_date_end',
			'column_name',
			'column_customer_group',
			'column_email',
			'column_telephone',
			'column_total',
			'column_action',
			'button_filter',
			'button_view'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}

		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}

		$pagination = new Pagination();
		$pagination->total = $customer_loan_count;
		$pagination->page = $page;
		$pagination->limit = $filter_data['limit'];
		$pagination->url = $this->url->link('report/customer_loan', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($customer_loan_count) ? (($page - 1) * $filter_data['limit']) + 1 : 0, ((($page - 1) * $filter_data['limit']) > ($customer_loan_count - $filter_data['limit'])) ? $customer_loan_count : ((($page - 1) * $filter_data['limit']) + $filter_data['limit']), $customer_loan_count, ceil($customer_loan_count / $filter_data['limit']));

		$data['filter_date_start'] = $filter_date_start;
		$data['filter_date_end'] = $filter_date_end;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/customer_loan', $data));
	}
}
