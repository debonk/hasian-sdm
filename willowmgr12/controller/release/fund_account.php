<?php
class ControllerReleaseFundAccount extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('release/fund_account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/fund_account');
		
		$this->getList();
	}

	public function add() {
		$this->load->language('release/fund_account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/fund_account');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_release_fund_account->addFundAccount($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('release/fund_account', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('release/fund_account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/fund_account');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_release_fund_account->editFundAccount($this->request->get['fund_account_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('release/fund_account', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->language('release/fund_account');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('release/fund_account');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $fund_account_id) {
				$this->model_release_fund_account->deleteFundAccount($fund_account_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('release/fund_account', 'token=' . $this->session->data['token'] . $url, true));
		}

		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

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
			'href' => $this->url->link('release/fund_account', 'token=' . $this->session->data['token'] . $url, true)
		);

		$filter_data = array(
			'start'                => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                => $this->config->get('config_limit_admin')
		);

		$data['add'] = $this->url->link('release/fund_account/add', 'token=' . $this->session->data['token'] . $url, true);
		$data['delete'] = $this->url->link('release/fund_account/delete', 'token=' . $this->session->data['token'] . $url, true);

		$data['fund_accounts'] = array();

		$fund_account_count = $this->model_release_fund_account->getFundAccountsCount($filter_data);

		$results = $this->model_release_fund_account->getFundAccounts($filter_data);

		foreach ($results as $result) {
			$data['fund_accounts'][] = array(
				'fund_account_id' 	=> $result['fund_account_id'],
				'bank_name' 		=> $result['bank_name'],
				'acc_no' 			=> $result['acc_no'],
				'acc_name' 			=> $result['acc_name'],
				'email' 			=> $result['email'],
				'date_modified'     => date($this->language->get('date_format_jMY'), strtotime($result['date_modified'])),
				'username' 			=> $result['username'],
				'edit'          	=> $this->url->link('release/fund_account/edit', 'token=' . $this->session->data['token'] . '&fund_account_id=' . $result['fund_account_id'] . $url, true),
			);
		}
		
		$language_items = array(
			'heading_title',
			'text_list',
			'text_confirm',
			'text_no_results',
			'column_bank_name',
			'column_acc_no',
			'column_acc_name',
			'column_email',
			'column_date_modified',
			'column_username',
			'column_action',
			'button_add',
			'button_edit',
			'button_delete'
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

		$pagination = new Pagination();
		$pagination->total = $fund_account_count;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('release/fund_account', 'token=' . $this->session->data['token'] . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($fund_account_count) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($fund_account_count - $this->config->get('config_limit_admin'))) ? $fund_account_count : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $fund_account_count, ceil($fund_account_count / $this->config->get('config_limit_admin')));

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('release/fund_account_list', $data));
	}

	protected function getForm() {
		$data['text_form'] = !isset($this->request->get['fund_account_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

		$language_items = array(
			'heading_title',
			'text_select',
			'entry_payroll_method',
			'entry_acc_no',
			'entry_acc_name',
			'entry_email',
			'button_save',
			'button_cancel'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$error_items = [
			'warning',
			'payroll_method',
			'acc_no',
			'acc_name',
			'email',
		];
		foreach ($error_items as $error_item) {
			$data['error_' . $error_item] = isset($this->error[$error_item]) ? $this->error[$error_item] : '';
		}

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['fund_account_id'])) {
			$fund_account_id = $this->request->get['fund_account_id'];
		} else {
			$fund_account_id = null;
		}

/* 		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
 */
		$url = '';

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
			'href' => $this->url->link('release/fund_account', 'token=' . $this->session->data['token'] . $url, true)
		);

		if (!isset($fund_account_id)) {
			$data['action'] = $this->url->link('release/fund_account/add', 'token=' . $this->session->data['token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('release/fund_account/edit', 'token=' . $this->session->data['token'] . '&fund_account_id=' . $fund_account_id . $url, true);
		}

		$data['cancel'] = $this->url->link('release/fund_account', 'token=' . $this->session->data['token'] . $url, true);

		$data['breadcrumbs'][] = array(
			'text' => $data['text_form'],
			'href' => $data['action']
		);

		if (isset($fund_account_id) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$fund_account_info = $this->model_release_fund_account->getFundAccount($fund_account_id);
		}

		$fund_account_items = array(
			'payroll_method_id',
			'acc_no',
			'acc_name',
			'email'
		);
		foreach ($fund_account_items as $fund_account_item) {
			if (isset($this->request->post[$fund_account_item])) {
				$data[$fund_account_item] = $this->request->post[$fund_account_item];
			} elseif (!empty($fund_account_info)) {
				$data[$fund_account_item] = $fund_account_info[$fund_account_item];
			} else {
				$data[$fund_account_item] = null;
			}
		}

		if (!empty($fund_account_info)) {
			$username = $fund_account_info['username'];
			$date_modified = date($this->language->get('date_format_jMY'), strtotime($fund_account_info['date_modified']));
		} else {
			$username = $this->user->getUserName();
			$date_modified = date($this->language->get('date_format_jMY'));
		}
		
		$data['text_modified'] = sprintf($this->language->get('text_modified'), $username, $date_modified);
	
		$this->load->model('localisation/payroll_method');
		$data['payroll_methods'] = $this->model_localisation_payroll_method->getPayrollMethods();

		$data['fund_account_id'] = $fund_account_id;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('release/fund_account_form', $data));
	}

	protected function validateForm() {
		if (!$this->user->hasPermission('modify', 'release/fund_account')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (empty($this->request->post['payroll_method_id'])) {
			$this->error['payroll_method'] = $this->language->get('error_payroll_method');
		}

		if ((utf8_strlen($this->request->post['acc_no']) < 1) || (utf8_strlen(trim($this->request->post['acc_no'])) > 32)) {
			$this->error['acc_no'] = $this->language->get('error_acc_no');
		}

		if ((utf8_strlen($this->request->post['acc_name']) < 1) || (utf8_strlen(trim($this->request->post['acc_name'])) > 64)) {
			$this->error['acc_name'] = $this->language->get('error_acc_name');
		}

		if ((!empty($this->request->post['email'])) && ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL))) {
			$this->error['email'] = $this->language->get('error_email');
		}

		// if (isset($this->request->get['fund_account_id']) && $this->model_release_fund_account->checkFundAccountHistory($this->request->get['fund_account_id'])) {
		// 	$this->error['warning'] = $this->language->get('error_history');
		// }

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}

	protected function validateDelete() {
		if (!$this->user->hasPermission('modify', 'release/fund_account')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (isset($this->request->post['selected'])) {
			foreach ($this->request->post['selected'] as $fund_account_id) {
				if ($this->model_release_fund_account->checkFundAccountHistory($fund_account_id)) {
					$this->error['warning'] = $this->language->get('error_history');
				}
			}
		}

		return !$this->error;
	}
}
