<?php
class ControllerReleaseFreeTransfer extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('release/free_transfer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/free_transfer');
		
		$this->getList();
		
	}

	public function add() {
		$this->load->language('release/free_transfer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/free_transfer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_release_free_transfer->addFreeTransfer($this->request->post);

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

			$this->response->redirect($this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('release/free_transfer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/free_transfer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_release_free_transfer->editFreeTransfer($this->request->get['free_transfer_id'], $this->request->post);

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

			$this->response->redirect($this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('release/free_transfer');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/free_transfer');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $free_transfer_id) {
				$this->model_release_free_transfer->deleteFreeTransfer($free_transfer_id);
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

			$this->response->redirect($this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . $url, true));
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
			'href' => $this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . $url, true)
		);

		$data['add'] = $this->url->link('release/free_transfer/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('release/free_transfer/delete', 'token=' . $this->session->data['token'] . $url, true);

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);

		$data['free_transfers'] = array();

		$free_transfer_count = $this->model_release_free_transfer->getFreeTransfersCount($filter_data);
		
		$results = $this->model_release_free_transfer->getFreeTransfers($filter_data);
// print_r($results);
		foreach ($results as $result) {
			$data['free_transfers'][] = array(
				'free_transfer_id' 	=> $result['free_transfer_id'],
				'description' 		=> $result['description'],
				'date_process' 		=> date($this->language->get('date_format_jMY'), strtotime($result['date_process'])),
				'fund_account' 		=> $result['acc_name'] . '</br> (' . $result['bank_name'] . ' - ' . $result['acc_no'] . ')',
				'count' 			=> $result['count'],
				'total' 			=> $this->currency->format($result['total'], $this->config->get('config_currency')),
				'date_modified' 	=> date($this->language->get('date_format_jMY'), strtotime($result['date_modified'])),
				'username'    		=> $result['username'],
				'edit'          	=> $this->url->link('release/free_transfer/edit', 'token=' . $this->session->data['token'] . '&free_transfer_id=' . $result['free_transfer_id'] . $url, true),
				'export'          	=> $this->url->link('release/free_transfer/exportcsv', 'token=' . $this->session->data['token'] . '&free_transfer_id=' . $result['free_transfer_id'] . $url, true),
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

		$data['sort_date_process'] = $this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . '&sort=ft.date_process' . $url, true);
		$data['sort_date_modified'] = $this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . '&sort=ft.date_modified' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $free_transfer_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($free_transfer_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($free_transfer_count - $this->config->get('config_limit_admin'))) ? $free_transfer_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $free_transfer_count, ceil($free_transfer_count / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('release/free_transfer_list', $data));
	}
	
	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['free_transfer_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'entry_description',
			'entry_date_process',
			'entry_fund_account',
			'entry_customer',
			'entry_note',
			'entry_amount',
			'button_save',
			'button_cancel',
			'button_free_transfer_add',
			'button_remove'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['token'] = $this->session->data['token'];

		$errors = array(
			'warning',
			'description',
			'fund_account',
			'date_process'
		);
		foreach ($errors as $error) {
			if (isset($this->error[$error])) {
				$data['error_' . $error] = $this->error[$error];
			} else {
				$data['error_' . $error] = '';
			}
		}

		if (isset($this->request->get['free_transfer_id'])) {
			$free_transfer_id = $this->request->get['free_transfer_id'];
		} else {
			$free_transfer_id = 0;
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
			'href' => $this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!$free_transfer_id) {
			$data['action'] = $this->url->link('release/free_transfer/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('release/free_transfer/edit', 'token=' . $this->session->data['token'] . '&free_transfer_id=' . $free_transfer_id . $url, true);
		}

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		$data['cancel'] = $this->url->link('release/free_transfer', 'token=' . $this->session->data['token'] . $url, true);

		if ($free_transfer_id) {
			$free_transfer_info = $this->model_release_free_transfer->getFreeTransfer($free_transfer_id);
		}
		
		//Text User Modify
		if (!empty($free_transfer_info)) {
			$username = $free_transfer_info['username'];
			$date_modified = date($this->language->get('date_format_jMY'), strtotime($free_transfer_info['date_modified']));
		} else {
			$username = $this->user->getUserName();
			$date_modified = date($this->language->get('date_format_jMY'));
		}
		$data['text_modified'] = sprintf($this->language->get('text_modified'), $username, $date_modified);
		
		if (isset($this->request->post['description'])) {
			$data['description'] = $this->request->post['description'];
		} elseif (!empty($free_transfer_info)) {
			$data['description'] = $free_transfer_info['description'];
		} else {
			$data['description'] = '';
		}
		
		if (isset($this->request->post['date_process'])) {
			$data['date_process'] = $this->request->post['date_process'];
		} elseif (!empty($free_transfer_info)) {
			$data['date_process'] = date($this->language->get('date_format_jMY'), strtotime($free_transfer_info['date_process']));
		} else {
			$data['date_process'] = '';
		}
		
		if (isset($this->request->post['fund_account_id'])) {
			$data['fund_account_id'] = $this->request->post['fund_account_id'];
		} elseif (!empty($free_transfer_info)) {
			$data['fund_account_id'] = $free_transfer_info['fund_account_id'];
		} else {
			$data['fund_account_id'] = 0;
		}
		
		$this->load->model('release/fund_account');

		$fund_accounts = $this->model_release_fund_account->getFundAccounts();
		foreach ($fund_accounts as $fund_account) {
			$data['fund_accounts'][] = array(
				'fund_account_id'	=> $fund_account['fund_account_id'],
				'fund_account_text'	=> $fund_account['acc_name'] . '; ' . $fund_account['bank_name'] . ' - ' .  $fund_account['acc_no']
			);
		}
		
		$data['customers'] = array();

		if (!$this->model_release_free_transfer->checkFreeTransferProcessed($free_transfer_id)) {
			$this->load->model('customer/customer');
			$customers = $this->model_customer_customer->getCustomers();
		} else {
			$customers = $this->model_release_free_transfer->getFreeTransferCustomers($free_transfer_id);
		}
		
		foreach ($customers as $customer) {
			$data['customers'][] = array(
				'customer_id' 		=> $customer['customer_id'],
				'customer_text' 	=> $customer['name'] . ' - ' . $customer['customer_group']
			);
		}
		
		if (isset($this->request->post['free_transfer_customer'])) {
			$data['free_transfer_customers'] = $this->request->post['free_transfer_customer'];
		} elseif ($free_transfer_id) {
			$data['free_transfer_customers'] = $this->model_release_free_transfer->getFreeTransferCustomers($free_transfer_id);
		} else {
			$data['free_transfer_customers'] = array();
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('release/free_transfer_form', $data));
	}

	protected function validateForm() {
		if (isset($this->request->get['free_transfer_id'])) {
			$free_transfer_id = $this->request->get['free_transfer_id'];
		} else {
			$free_transfer_id = 0;
		}
		
		if (!$this->user->hasPermission('modify', 'release/free_transfer')) {
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
		
		if (empty($this->request->post['free_transfer_customer'])) {
			$this->error['warning'] = $this->language->get('error_customer');
		}

		if ($free_transfer_id && $this->model_release_free_transfer->checkFreeTransferProcessed($free_transfer_id)) {
			$this->error['warning'] = $this->language->get('error_processed');
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}
	
	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'release/free_transfer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		foreach ($this->request->post['selected'] as $free_transfer_id) {
			if ($this->model_release_free_transfer->checkFreeTransferProcessed($free_transfer_id)) {
				$this->error['warning'] = $this->language->get('error_processed');
			}
		}

		return !$this->error;
	}

	public function exportCsv() {
		$this->load->language('release/free_transfer');

		$this->load->model('release/free_transfer');

		if (isset($this->request->get['free_transfer_id'])) {
			$free_transfer_id = $this->request->get['free_transfer_id'];
		} else {
			$free_transfer_id = 0;
		}

		$free_transfer_info = $this->model_release_free_transfer->getFreeTransfer($free_transfer_id);
		
		if (!empty($free_transfer_info) && $this->validateExport()) {
			$this->load->model('release/fund_account');
			$fund_account_info = $this->model_release_fund_account->getFundAccount($free_transfer_info['fund_account_id']);
			
			$currency_code = $this->config->get('config_currency');
			$date_process = date('Ymd', strtotime($free_transfer_info['date_process']));
			
			$result_count = $this->model_release_free_transfer->getFreeTransferCustomerCountByMethod($free_transfer_id, 'CIMB');
			$result_total = $this->model_release_free_transfer->getFreeTransferCustomerTotalByMethod($free_transfer_id, 'CIMB');
			
			$output = '';
			$output .= $fund_account_info['acc_no'] . ',' . $fund_account_info['acc_name'] . ',' . $currency_code . ',' . $result_total . ',' . $free_transfer_info['description'] . ',' . $result_count . ',' . $date_process . ',' . $fund_account_info['email']; 

			$output = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $output);
			$output = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $output);
			$output = str_replace('\\', '\\\\', $output);
			$output = str_replace('\'', '\\\'', $output);
			$output = str_replace('\\\n', '\n', $output);
			$output = str_replace('\\\r', '\r', $output);
			$output = str_replace('\\\t', '\t', $output);

			$results = $this->model_release_free_transfer->getFreeTransferCustomersByMethod($free_transfer_id, 'CIMB');

			foreach ($results as $result) {
				$value = '';
				$value .= $result['acc_no'] . ',' . $result['lastname'] . ',' . $currency_code . ',' . $result['amount'] . ',' . $free_transfer_info['description'] . ',' . $result['email'] . ',,';

				$value = str_replace(array("\x00", "\x0a", "\x0d", "\x1a"), array('\0', '\n', '\r', '\Z'), $value);
				$value = str_replace(array("\n", "\r", "\t"), array('\n', '\r', '\t'), $value);
				$value = str_replace('\\', '\\\\', $value);
				$value = str_replace('\'', '\\\'', $value);
				$value = str_replace('\\\n', '\n', $value);
				$value = str_replace('\\\r', '\r', $value);
				$value = str_replace('\\\t', '\t', $value);
				
				$output .= "\n" . $value;
			}
				
			$filename = $date_process . '_' . str_replace(' ', '_', $free_transfer_info['description']);
			
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
		if (!$this->user->hasPermission('modify', 'release/free_transfer')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}
