<?php
class ControllerAccountAccount extends Controller
{
	public function index()
	{
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/account', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/account');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$language_items = [
			'heading_title',
			'text_unsupport',
			'error_retrieve',
			'button_login',
			'button_logout',
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$this->load->model('presence/presence');

		$login_start = $this->config->get('payroll_setting_login_start');

		$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));
		$log_info = $this->model_presence_presence->getLog($this->customer->getId(), $schedule_date);

		if ($log_info && $log_info['time_login'] != '0000-00-00 00:00:00') {
			$data['action'] = 'logout';
		} else {
			$data['action'] = 'login';
		}
				
		// Temporary to cek log
		$data['log'] = '';

		$file = DIR_LOGS . 'presence.log';

		if (file_exists($file)) {
			$data['log'] = file_get_contents($file, FILE_USE_INCLUDE_PATH, null);
		}

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		// Change this template when login by phone is active
		// $this->response->setOutput($this->load->view('account/account', $data));
		$this->response->setOutput($this->load->view('account/account_wo_login', $data));
	}

	public function validateLog()
	{
		$this->load->language('account/account');

		$json = [];

		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/login', '', true);
		}
		
		$customer_id = $this->customer->getId();
		
		$this->load->model('presence/presence');

		$login_start = $this->config->get('payroll_setting_login_start');

		$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));
		$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);

		if ($log_info && $log_info['time_login'] != '0000-00-00 00:00:00') {
			$action = 'logout';
		} else {
			$action = 'login';
		}
		
		switch ($json) {
			case false:
				if ($log_info) {
					if ($log_info['time_logout'] != '0000-00-00 00:00:00') { //Cek ternyata sudah logout
						$json['error'] = $this->language->get('error_logout');

						break;
					}

					if ($action == 'login' && $log_info['time_login'] != '0000-00-00 00:00:00') { //Cek sudah login
						$json['error'] = $this->language->get('error_login');

						break;
					}

					if ($action == 'logout' && $log_info['time_login'] == '0000-00-00 00:00:00') { //Cek belum login
						$json['error'] = $this->language->get('error_not_login');

						break;
					}
				} else {
					if ($action == 'logout') { //Cek belum login
						$json['error'] = $this->language->get('error_not_login');

						break;
					}
				}

				$schedule_check = $this->config->get('payroll_setting_schedule_check'); # Cek validasi jadwal
		
				if ($schedule_check) {
					$schedule_info = $this->model_presence_presence->getAppliedSchedule($customer_id, $schedule_date);
	
					if (!$schedule_info || !$schedule_info['schedule_type_id']) { //Cek ga ada jadwal
						$json['error'] = $this->language->get('error_absence');

						break;
					}

					$time_in = $schedule_date . ' ' . $schedule_info['time_in'];
					$time_out = $schedule_date . ' ' . $schedule_info['time_out'];

					if ($time_in >= $time_out) {
						$time_out = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($time_out)));
					}
				} else {
					$time_in = '0000-00-00 00:00:00';
					$time_out = '0000-00-00 00:00:00';
				}

				if ($schedule_check) {
					if ($action == 'logout') {
						$logout_start = $this->config->get('payroll_setting_logout_start');
						$datetime_logout = date('Y-m-d H:i:s', strtotime('+' . $logout_start . ' minutes'));

						if ($logout_start && strtotime($datetime_logout) < strtotime($time_out)) { //Cek login sebelum waktu start yang diizinkan
							$json['error'] = $this->language->get('error_logout_start');

							break;
						}
					} else {
						$date_login_start = date('Y-m-d H:i:s', strtotime('+' . $login_start . ' minutes'));

						$login_end = $this->config->get('payroll_setting_login_end');
						$date_login_end = date('Y-m-d H:i:s', strtotime('-' . $login_end . ' minutes'));

						if ($login_start && strtotime($date_login_start) < strtotime($time_in)) { //Cek login sebelum waktu start yang diizinkan
							$json['error'] = $this->language->get('error_login_start');

							break;
						} elseif ($login_end && strtotime($date_login_end) > strtotime($time_in)) { //Cek login setelah waktu akhir yang diizinkan
							$json['error'] = $this->language->get('error_login_end');

							break;
						}
					}
				}
		
				$this->model_presence_presence->addScheduleTime($customer_id, $schedule_date, $action, $time_in, $time_out);

				$json['process_verification'] = true;
				
				break;

			default:
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function loginProcess()
	{
		$this->load->language('account/account');

		$json = [];

		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/login', '', true);
		}

		if (!$json && $this->request->server['REQUEST_METHOD'] == 'POST' && $this->request->post) {
			$latitude = trim($this->request->post['coords']['latitude']);
			$longitude = trim($this->request->post['coords']['longitude']);
			$accuracy = trim($this->request->post['coords']['accuracy']);
			$datetime = date('Y-m-d H:i:s', (int)($this->request->post['timestamp'] / 1000));

			$config_latitude = -7.2801801;
			$config_longitude = 112.7755675;
			$config_tolerance = 0.00008;

			if (abs($latitude - $config_latitude) > $config_tolerance || abs($longitude - $config_longitude) > $config_tolerance) {
				$json['error'] = $this->language->get('error_login');
				
				$status = 'Too Far!';
			} else {
				#login block
				$customer_id = $this->customer->getId();
		
				$this->load->model('presence/presence');
		
				$login_start = $this->config->get('payroll_setting_login_start');
		
				$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));
				$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);
		
				if ($log_info && $log_info['time_login'] != '0000-00-00 00:00:00') {
					$action = 'logout';
				} else {
					$action = 'login';
				}
				
				$this->model_presence_presence->addLog($customer_id, $schedule_date, $action);

				$this->session->data['success'] = $this->language->get('text_success_' . $action);

				$status = 'Login Success!';
			}

			# Add long-lat location history block here 

			$json['location'] = 'Name: ' . $this->customer->getFirstname() . ' - Lat: ' . $latitude . ' & Long: ' . $longitude . ' & Acc: ' . $accuracy . ' & Time: ' . $datetime . ' (' . $status . ')';
			$json['pos'] = 'Pos: @' . $latitude . ',' . $longitude . ',21z?hl=en';

			$this->log($json['location']);
			$this->log($json['pos']);

		} else {
			$json['error'] = $this->language->get('error_retrieve');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function log($message)
	{
		$log = new Log('presence.log');
		$log->write($message);
	}
}
