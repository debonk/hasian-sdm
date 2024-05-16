<?php
class ControllerPayrollPayrollType extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');
		
		$this->getList();
		
	}

	public function add() {
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_payroll_payroll_type->addPayrollType($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_payroll_payroll_type->editPayrollType($this->request->get['payroll_type_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('payroll/payroll_type');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('payroll/payroll_type');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $payroll_type_id) {
				$this->model_payroll_payroll_type->deletePayrollType($payroll_type_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'ft.date_process';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
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
			'href' => $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('payroll/payroll_type/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('payroll/payroll_type/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$data['payroll_types'] = array();

		$payroll_type_count = $this->model_payroll_payroll_type->getPayrollTypesCount($filter_data);
		
		$results = $this->model_payroll_payroll_type->getPayrollTypes($filter_data);

		foreach ($results as $result) {
			$data['payroll_types'][] = array(
				'payroll_type_id' 	=> $result['payroll_type_id'],
				'description' 		=> $result['description'],
				'date_process' 		=> date($this->language->get('date_format_jMY'), strtotime($result['date_process'])),
				'fund_account' 		=> $result['acc_name'] . '</br> (' . $result['bank_name'] . ' - ' . $result['acc_no'] . ')',
				'count' 			=> $result['count'],
				'total' 			=> $this->currency->format($result['total'], $this->config->get('config_currency')),
				'date_modified' 	=> date($this->language->get('date_format_jMY'), strtotime($result['date_modified'])),
				'username'    		=> $result['username'],
				'edit'          	=> $this->url->link('payroll/payroll_type/edit', 'token=' . $this->session->data['token'] . '&payroll_type_id=' . $result['payroll_type_id'] . $url, true),
				'export'          	=> $this->url->link('payroll/payroll_type/exportcsv', 'token=' . $this->session->data['token'] . '&payroll_type_id=' . $result['payroll_type_id'] . $url, true),
			);
		}
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_no_results',
			'text_confirm',
			'column_description',
			'column_date_process',
			'column_fund_account',
			'column_count',
			'column_total',
			'column_date_modified',
			'column_username',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete',
			'button_export_csv'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

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

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_date_process'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . '&sort=ft.date_process' . $url, true);
		$data['sort_date_modified'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . '&sort=ft.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $payroll_type_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($payroll_type_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($payroll_type_count - $this->config->get('config_limit_admin'))) ? $payroll_type_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $payroll_type_count, ceil($payroll_type_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_type_list', $data));
	}
	
	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['payroll_type_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			// 'text_select',
			'entry_action',
			'entry_name',
			'entry_calculation',
			'entry_description',
			'entry_formula',
			'entry_sort_order',
			'entry_title',
			'button_save',
			'button_cancel',
			'button_payroll_type_add',
			'button_remove'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$errors = array(
			'warning',
			'name',
			// 'title',
			// 'formula'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		if (isset($this->request->get['payroll_type_id'])) {
			$payroll_type_id = $this->request->get['payroll_type_id'];
		} else {
			$payroll_type_id = 0;
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'], true)
		);

		if (!$payroll_type_id) {
			$data['action'] = $this->url->link('payroll/payroll_type/add', 'token=' . $this->session->data['token'], true);
		} else {
			$data['action'] = $this->url->link('payroll/payroll_type/edit', 'token=' . $this->session->data['token'] . '&payroll_type_id=' . $payroll_type_id, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('payroll/payroll_type', 'token=' . $this->session->data['token'], true);

		if ($payroll_type_id) {
			$payroll_type_info = $this->model_payroll_payroll_type->getPayrollType($payroll_type_id);
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($payroll_type_info)) {
			$data['name'] = $payroll_type_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($payroll_type_info)) {
			$data['description'] = $payroll_type_info['description'];
		} else {
			$data['description'] = '';
		}
		
		if (isset($this->request->post['payroll_type_component'])) {
			$data['payroll_type_components'] = $this->request->post['payroll_type_component'];
		} elseif ($payroll_type_id) {
			$data['payroll_type_components'] = $this->model_payroll_payroll_type->getPayrollTypeComponents($payroll_type_id);
		} else {
			$data['payroll_type_components'] = [
				'addition'		=> [],
				'deduction'		=> []
			];
		}
		// var_dump($data['payroll_type_components']);

		$data['direction_title'] = [
			'addition'		=> $this->language->get('text_addition'),
			'deduction'		=> $this->language->get('text_deduction')
		];

		$component_row = [
			'addition'		=> count($data['payroll_type_components']['addition']),
			'deduction'		=> count($data['payroll_type_components']['deduction'])
		];

		$data['component_row'] = json_encode($component_row);


		// var_dump($data['payroll_type_components']);

		// $data['addition_row'] = count($data['payroll_type_components']['addition']);
		// $data['deduction_row'] = count($data['payroll_type_components']['deduction']);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payroll/payroll_type_form', $data));
	}

	protected function validateForm() {
		if (isset($this->request->get['payroll_type_id'])) {
			$payroll_type_id = $this->request->get['payroll_type_id'];
		} else {
			$payroll_type_id = 0;
		}
		
		if (!$this->user->hasPermission('modify', 'payroll/payroll_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['description']) < 1) || (utf8_strlen(trim($this->request->post['description'])) > 128)) {
			$this->error['description'] = $this->language->get('error_description');
		}

		if (empty($this->request->post['date_process']) || strtotime($this->request->post['date_process']) < strtotime('today')) {
			$this->error['date_process'] = $this->language->get('error_date_process');
		}

		if (empty($this->request->post['fund_account_id'])) {
			$this->error['fund_account'] = $this->language->get('error_fund_account');
		}
		
		if (empty($this->request->post['payroll_type_component'])) {
			$this->error['warning'] = $this->language->get('error_component');
		}

		if ($payroll_type_id && $this->model_payroll_payroll_type->checkPayrollTypeProcessed($payroll_type_id)) {
			$this->error['warning'] = $this->language->get('error_processed');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'payroll/payroll_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $payroll_type_id) {
			if ($this->model_payroll_payroll_type->checkPayrollTypeProcessed($payroll_type_id)) {
				$this->error['warning'] = $this->language->get('error_processed');
			}
		}

		return !$this->error;
	}

	public function exportCsv() {
		$this->load->language('payroll/payroll_type');

		$this->load->model('payroll/payroll_type');

		if (isset($this->request->get['payroll_type_id'])) {
			$payroll_type_id = $this->request->get['payroll_type_id'];
		} else {
			$payroll_type_id = 0;
		}

		$payroll_type_info = $this->model_payroll_payroll_type->getPayrollType($payroll_type_id);
		
		if (!empty($payroll_type_info) && $this->validateExport()) {
			$this->load->model('release/fund_account');
			$fund_account_info = $this->model_release_fund_account->getFundAccount($payroll_type_info['fund_account_id']);
			
			$currency_code = $this->config->get('config_currency');
			$date_process = date('Ymd', strtotime($payroll_type_info['date_process']));
			
			$result_count = $this->model_payroll_payroll_type->getPayrollTypeComponentCountByMethod($payroll_type_id, 'CIMB');
			$result_total = $this->model_payroll_payroll_type->getPayrollTypeComponentTotalByMethod($payroll_type_id, 'CIMB');
			
			$output = '';
			$output .= $fund_account_info['acc_no'] . ',' . $fund_account_info['acc_name'] . ',' . $currency_code . ',' . $result_total . ',' . $payroll_type_info['description'] . ',' . $result_count . ',' . $date_process . ',' . $fund_account_info['email']; 

			$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
			$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
			$output = str_replace('\\', '\\\\', $output);
			$output = str_replace('\'', '\\\'', $output);
			$output = str_replace('\\\n', '\n', $output);
			$output = str_replace('\\\r', '\r', $output);
			$output = str_replace('\\\t', '\t', $output);

			$results = $this->model_payroll_payroll_type->getPayrollTypeComponentsByMethod($payroll_type_id, 'CIMB');

			foreach ($results as $result) {
				$value = '';
				$value .= $result['acc_no'] . ',' . $result['lastname'] . ',' . $currency_code . ',' . $result['amount'] . ',' . $payroll_type_info['description'] . ',' . $result['email'] . ',,';

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\', $value);
				$value = str_replace('\'', '\\\'', $value);
				$value = str_replace('\\\n', '\n', $value);
				$value = str_replace('\\\r', '\r', $value);
				$value = str_replace('\\\t', '\t', $value);
				
				$output .= "\n" . $value;
			}
				
			$filename = $date_process . '_' . str_replace(' ', '_', $payroll_type_info['description']);
			
			$this->response->addheader('Pragma: public');
			$this->response->addheader('Expires: 0');
			$this->response->addheader('Content-Description: File Transfer');
			$this->response->addheader('Content-Type: application/octet-stream');
			$this->response->addheader('Content-Disposition: attachment; filename=' . $filename . '.csv');
			$this->response->addheader('Content-Transfer-Encoding: binary');
			$this->response->setOutput($output);
			// echo $output;
		} else {
		
			$this->index();
		}
	}

	protected function validateExport() {
		if (!$this->user->hasPermission('modify', 'payroll/payroll_type')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
