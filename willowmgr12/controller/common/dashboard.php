<?php
class ControllerCommonDashboard extends Controller
{
	public function index()
	{
		$this->load->language('common/dashboard');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		// Check install directory exists
		if (is_dir(dirname(DIR_APPLICATION) . '/install')) {
			$data['error_install'] = $this->language->get('error_install');
		} else {
			$data['error_install'] = '';
		}

		$data['token'] = $this->session->data['token'];

		$data['admin_maintenance'] = $this->config->get('config_admin_maintenance');

		if ($data['admin_maintenance']) {
			$data['text_maintenance'] = $this->language->get('text_maintenance');
			$data['login_session'] = false;
		} else {
			$data['text_maintenance'] = false;
			$data['login_session'] = $this->load->controller('dashboard/login_session');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['presence'] = $this->load->controller('dashboard/presence');
		$data['customer'] = $this->load->controller('dashboard/customer');
		$data['online'] = $this->load->controller('dashboard/online');
		$data['attention'] = $this->load->controller('dashboard/attention');
		$data['history'] = $this->load->controller('dashboard/history');
		$data['recent'] = $this->load->controller('dashboard/recent');
		$data['footer'] = $this->load->controller('common/footer');

		// Run currency update
		if ($this->config->get('config_currency_auto')) {
			$this->load->model('localisation/currency');

			$this->model_localisation_currency->refresh();
		}

		$this->response->setOutput($this->load->view('common/dashboard', $data));
	}
}
