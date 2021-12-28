<?php
class ControllerDashboardLoginSession extends Controller {
	public function index() {
		if (!$this->user->hasPermission('access', 'dashboard/login_session')) {
			return;
		}

		$this->load->language('dashboard/login_session');

		$data['text_loading'] = $this->language->get('text_loading');
		$data['text_login_page'] = $this->language->get('text_login_page');

		$data['token'] = $this->session->data['token'];

		$data['locations'] = [];

		$this->load->model('localisation/location');
		$locations = $this->model_localisation_location->getLocations();

		foreach ($locations as $location) {
			$data['locations'][] = [
				'location_id' 	=> $location['location_id'],
				'name' 			=> $location['name']
			];
		}
				
		return $this->load->view('dashboard/login_session', $data);
	}

	public function loginPage() {
		$this->load->language('dashboard/login_session');

		$json = array();

		if (!$this->user->hasPermission('modify', 'dashboard/login_session')) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if (isset($this->request->post['location_id'])) {
				$location_id = $this->request->post['location_id'];
			} else {
				$location_id = 0;
			}

			$this->load->model('localisation/location');
			$session_token = $this->model_localisation_location->generateLocationToken($location_id);

			$this->session->data['login_session'] = $session_token;

			$session_time = $this->config->get('payroll_setting_login_session') * 3600;
			header('Set-Cookie: login_session=' . $session_token . '; Max-Age=' . $session_time . '; path=/; Domain=' . $this->request->server['HTTP_HOST'] . '; SameSite=Lax');

			$this->user->logout();

			unset($this->session->data['token']);

			if ($this->request->server['HTTPS']) {
				$server = HTTPS_CATALOG;
			} else {
				$server = HTTP_CATALOG;
			}
	
			$json['redirect'] = $server . 'index.php?route=presence/login&action=login&location_id=' . $location_id;
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

}