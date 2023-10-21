<?php
class ControllerReportUserOnline extends Controller {
	public function index() {
		$this->load->language('report/user_online');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_ip'])) {
			$filter_ip = $this->request->get['filter_ip'];
		} else {
			$filter_ip = null;
		}

		if (isset($this->request->get['filter_user'])) {
			$filter_user = $this->request->get['filter_user'];
		} else {
			$filter_user = null;
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

		if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . urlencode($this->request->get['filter_user']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true),
			'text' => $this->language->get('text_home')
		);

		$data['breadcrumbs'][] = array(
			'href' => $this->url->link('report/user_online', 'token=' . $this->session->data['token'] . $url, true),
			'text' => $this->language->get('heading_title')
		);

		$this->load->model('report/user');
		$this->load->model('user/user');

		$data['users'] = array();

		$filter_data = array(
			'filter_ip'       => $filter_ip,
			'filter_user' => $filter_user,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$user_total = $this->model_report_user->getTotalUsersOnline($filter_data);

		$results = $this->model_report_user->getUsersOnline($filter_data);

		foreach ($results as $result) {
			$user_info = $this->model_user_user->getUser($result['user_id']);

			if ($user_info) {
				$user = $user_info['firstname'] . ' ' . $user_info['lastname'];
			} else {
				$user = $this->language->get('text_guest');
			}

			$data['users'][] = array(
				'user_id'   	=> $result['user_id'],
				'ip'        	=> $result['ip'],
				'user'      	=> $user,
				'url'       	=> $result['url'],
				'referer'   	=> $result['referer'],
				'date_added'	=> date($this->language->get('datetime_format'), strtotime($result['date_added'])),
				'edit'      	=> $this->url->link('user/user/edit', 'token=' . $this->session->data['token'] . '&user_id=' . $result['user_id'], true)
			);
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_ip'] = $this->language->get('column_ip');
		$data['column_user'] = $this->language->get('column_user');
		$data['column_url'] = $this->language->get('column_url');
		$data['column_referer'] = $this->language->get('column_referer');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_action'] = $this->language->get('column_action');

		$data['entry_ip'] = $this->language->get('entry_ip');
		$data['entry_user'] = $this->language->get('entry_user');

		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_filter'] = $this->language->get('button_filter');

		$data['token'] = $this->session->data['token'];

		$url = '';

		if (isset($this->request->get['filter_user'])) {
			$url .= '&filter_user=' . urlencode($this->request->get['filter_user']);
		}

		if (isset($this->request->get['filter_ip'])) {
			$url .= '&filter_ip=' . $this->request->get['filter_ip'];
		}

		$pagination = new Pagination();
		$pagination->total = $user_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('report/user_online', 'token=' . $this->session->data['token'] . $url . '&page={page}', true);

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($user_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($user_total - $this->config->get('config_limit_admin'))) ? $user_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $user_total, ceil($user_total / $this->config->get('config_limit_admin')));

		$data['filter_user'] = $filter_user;
		$data['filter_ip'] = $filter_ip;

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('report/user_online', $data));
	}
}
