<?php
class ControllerApiFinger extends Controller
{
	public function index()
	{
		$this->load->language('api/finger');

		$json = [];

		$this->load->model('localisation/finger_device');

		$json['finger_devices'] = [];

		$finger_devices = $this->model_localisation_finger_device->getFingerDevices();

		foreach ($finger_devices as $finger_device) {
			$json['finger_devices'][] = [
				'name'	=> $finger_device['device_name'] . ' [' . $finger_device['sn'] . ']',
				'sn'	=> $finger_device['sn'],
				'vc'	=> $finger_device['vc'],
				'ac'	=> $finger_device['ac']
			];
		}

		$this->load->model('localisation/location');

		$json['locations'] = $this->model_localisation_location->getLocations();

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function template()
	{
		$this->load->language('api/finger');

		$json = [];
		$results = [];
		$token = '';

		switch ($json) {
			case false:
				$sn = isset($this->request->get['sn']) ? $this->request->get['sn'] : '';

				$this->load->model('localisation/finger_device');
				$device_info = $this->model_localisation_finger_device->getFingerDeviceBySn($sn);

				if (empty($device_info)) {
					$json['warning'] = $this->language->get('error_device');

					break;
				}

				$token = token(12);

				$this->model_localisation_finger_device->editToken($device_info['finger_device_id'], md5($device_info['vc'] . $token));

				$filter_items = array(
					'location_id'
				);

				$filter = [];

				foreach ($filter_items as $filter_item) {
					if (isset($this->request->get[$filter_item])) {
						$filter[$filter_item] = $this->request->get[$filter_item];
					} else {
						$filter[$filter_item] = null;
					}
				}

				$this->load->model('presence/presence');

				$filter_data = array(
					'filter'	=> $filter
				);

				$results = $this->model_presence_presence->getFingers($filter_data);

				if (!$results) {
					$json['warning'] = $this->language->get('error_data');

					break;
				}

				break;

			default:

				break;
		}

		if (!$json) {
			foreach ($results as $result) {
				if (is_null($result['active_finger'])) {
					$json['fingers'][] = array(
						'customer_id'	=> $result['customer_id'],
						'active_finger'	=> 0,
						'finger_data'	=> $result['finger_data']
					);
				} else {
					$active_fingers = json_decode($result['active_finger'], 1);

					if (in_array($result['customer_id'] . 'x' . $result['finger_index'], $active_fingers)) {
						$json['fingers'][] = array(
							'customer_id'	=> $result['customer_id'],
							'active_finger'	=> $result['finger_index'],
							'finger_data'	=> $result['finger_data']
						);
					}
				}
			}

			$json['token'] = $token;
			$json['ready'] = $this->language->get('text_ready');
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function login()
	{
		$this->load->language('api/finger');

		$json = [];

		if (empty($this->request->post) || !isset($this->request->post['sn'])) {
			$json['result'] = $this->language->get('error_data');
		} else {
			if (isset($this->request->post['action']) && $this->request->post['action'] == 'logout') {
				$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_logout_date') . ' hours'));

				$action = 'logout';
			} else {
				$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_login_date') . ' hours'));

				$action = 'login';
			}
		}

		$this->load->model('presence/presence');

		switch ($json) {
			case false:
				$this->load->model('localisation/finger_device');
				$device_info = $this->model_localisation_finger_device->getFingerDeviceBySn($this->request->post['sn']);

				if (!$device_info) {
					$json['result'] = $this->language->get('error_device');

					break;
				}

				if ($this->request->post['token_hash'] != $device_info['token']) {
					$json['result'] = $this->language->get('error_session');

					break;
				}

				$customer_id = isset($this->request->post['customer_id']) ? $this->request->post['customer_id'] : 0;
				$customer_info = $this->model_presence_presence->getCustomer($customer_id);

				if (!$customer_info) { //Cek apakah karyawan msh aktif
					$json['result'] = $this->language->get('error_customer_not_found');

					break;
				}

				$schedule_check = $this->config->get('payroll_setting_schedule_check');

				if ($schedule_check) {
					$schedule_info = $this->model_presence_presence->getAppliedSchedule($customer_id, $schedule_date);

					if (!$schedule_info || !$schedule_info['schedule_type_id']) { //Cek ga ada jadwal
						$json['result'] = $this->language->get('error_absence');

						break;
					}

					$time_in = $schedule_date . ' ' . $schedule_info['time_in'];
					$time_out = $schedule_date . ' ' . $schedule_info['time_out'];

					if ($time_in >= $time_out) {
						$time_out = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($time_out)));
					}
				} else {
					$time_in = null;
					$time_out = null;
				}

				$name = $this->config->get('payroll_setting_presence_card') != 'lastname' ? $customer_info['firstname'] : $customer_info['lastname'];

				$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);

				if ($log_info) {
					if ($log_info['time_logout'] != null) { //Cek ternyata sudah logout
						// $json['result'] = $this->language->get('error_logout');
						$json['result'] = sprintf($this->language->get('error_logout'), $name, date('j M Y H:i:s', strtotime($log_info['time_logout'])));

						break;
					}

					if ($action == 'login' && $log_info['time_login'] != null) { //Cek sudah login
						// $json['result'] = $this->language->get('error_login');
						$json['result'] = sprintf($this->language->get('error_login'), $name, date('j M Y H:i:s', strtotime($log_info['time_login'])));

						break;
					}

					if ($action == 'logout' && $log_info['time_login'] == null) { //Cek belum login
						// $json['result'] = $this->language->get('error_not_login');
						$json['result'] = sprintf($this->language->get('error_not_login'), $name);

						break;
					}
				} else {
					if ($action == 'logout') { //Cek belum login
						// $json['result'] = $this->language->get('error_not_login');
						$json['result'] = sprintf($this->language->get('error_not_login'), $name);

						break;
					}
				}

				if ($schedule_check) {
					if ($action == 'logout') {
						$logout_start = $this->config->get('payroll_setting_logout_start');
						$datetime_logout = date('Y-m-d H:i:s', strtotime('+' . $logout_start . ' minutes'));

						if ($logout_start && strtotime($datetime_logout) < strtotime($time_out)) { //Cek login sebelum waktu start yang diizinkan
							// $json['result'] = $this->language->get('error_logout_start');
							$json['result'] = sprintf($this->language->get('error_logout_start'), $name);

							break;
						}
					} else {
						$login_start = $this->config->get('payroll_setting_login_start');
						$date_login_start = date('Y-m-d H:i:s', strtotime('+' . $login_start . ' minutes'));

						$login_end = $this->config->get('payroll_setting_login_end');
						$date_login_end = date('Y-m-d H:i:s', strtotime('-' . $login_end . ' minutes'));

						if ($login_start && strtotime($date_login_start) < strtotime($time_in)) { #Cek login sebelum waktu start yang diizinkan. Value 0 untuk menonaktifkan.
							// $json['result'] = $this->language->get('error_login_start');
							$json['result'] = sprintf($this->language->get('error_login_start'), $name);

							break;
						} elseif ($login_end && strtotime($date_login_end) > strtotime($time_in)) { #Cek login setelah waktu akhir yang diizinkan. Value 0 untuk menonaktifkan.
							// $json['result'] = $this->language->get('error_login_end');
							$json['result'] = sprintf($this->language->get('error_login_end'), $name);

							break;
						}
					}
				}

				$this->model_presence_presence->addScheduleTime($customer_id, $schedule_date, $action, $time_in, $time_out);
				$this->model_presence_presence->addLog($customer_id, $schedule_date, $action);

				$json['result'] = sprintf($this->language->get('text_success_login'), $name, $this->language->get('text_' . $action), date('j M Y H:i:s'));

				break;

			default:
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function enroll()
	{
		$this->load->language('api/finger');

		$json = '';

		$input = file_get_contents('php://input');
		// $json = json_decode($input, true);
		$input2 = '{"SN":"{7C259A01-7CC2-954F-A686-58C8737CE77E}","Idx":"10x24x1","Fmd":"<?xml version=\"1.0\" encoding=\"UTF-8\"?><Fid><Bytes>Rk1SACAyMAABuAAz\/v8AAAH0AiYBFAEUAQAAAFZEgQcATExfQMUAbVJfQTEA0j9ZQNEA80hZgMIBO15WgPIBQUNUgJcBPAVSgO0Ag09SQYAA3JZNgE4BQT9NgI4BAWBLQP8Acp5JgMwAPKdJgI0BUoNJQWcBqIdJgV0Bq4FJgU0Bsn5IgNIBmz9GgScB2IFGgTEB7R5GQEEA\/y1FgLEBG19DgHEBqJVDQDAAsxJCQX0A0ZJCgLsBIlxBgTEB2oFBgD0BEzZAQEsBOS5AQXgBm4FAQG0BIyE\/gJMBx54\/gCsA8Bc\/QJcBdZA\/gFYBt4E\/QUEB2oA\/gK4BuJ0+gRoB0Xs+QUgBznk9gMcANaw8gI4BJ3g8QYcA5ZQ8gXgBeoI8QF4Bkn48QWEBlyg8QXUBzoE8gYQBL4k7gDUA7Y87gD0A9Yg7QEsBeIE7QLYB5UQ7gNEB2kQ6gVUAYD86QLEB4EQ6QYUAz5k5gEkBUC05QXABLTY5QR4BmYY5gE4Bsik5gUYA+D04gWwB1SU4QWYAaJg4QGcBKiA4gRgB7Xw4gX8BRTM3QDgBTTM3QD0BiCU3gEkBDiI3AAA=<\/Bytes><Format>1769473<\/Format><Version>1.0.0<\/Version><\/Fid>"}';
		$input_data = json_decode($input, true);

		switch ($json) {
			case false:
				if ($input_data === null) {
					// http_response_code(400); // Bad Request
					$json = $this->language->get('error_data');
					break;
				}

				if (empty($input_data['SN']) || empty($input_data['Idx']) || empty($input_data['Fmd'])) {
					$json = $this->language->get('error_data');
					break;
				}

				list($customer_id, $finger_index, $user_id) = explode('x', $input_data['Idx']);
				$vkey = str_replace(['-', '{', '}'], '', $input_data['SN']);
				$fmd = htmlspecialchars($input_data['Fmd']);

				// $this->load->model('localisation/finger_device');
				// $device_info = $this->model_localisation_finger_device->getFingerDeviceByVkey($vkey);

				// if (!$device_info) {
				// 	$json = $this->language->get('error_device');

				// 	break;
				// }

				// if ($this->request->post['token_hash'] != $device_info['token']) {
				// 	$json = $this->language->get('error_session');

				// 	break;
				// }

				$this->load->model('presence/presence');
				$customer_info = $this->model_presence_presence->getCustomer($customer_id);

				if (!$customer_info) { //Cek apakah karyawan msh aktif
					$json = $this->language->get('error_customer_not_found');

					break;
				}

				$finger_info = $this->model_presence_presence->getFingerByCustomerId($customer_id, $finger_index);

				if ($finger_info) {
					$json = $this->language->get('error_finger_exist');

					break;
				}

				$finger_data = [
					'finger_index'	=> $finger_index,
					'finger_data'	=> $fmd,
					'user_id'		=> $user_id,
				];

				$this->model_presence_presence->addFinger($customer_id, $finger_data);

				$json = $this->language->get('text_success_enroll');

				break;

			default:
				break;
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function identify()
	{
		$this->load->language('api/finger');

		$json = '';

		$input = file_get_contents('php://input');
		// $input = '{"LogAction":"login","CustomerId":2,"FingerIndex":21,"LocationId":0}';
		$input_data = json_decode($input, true);

		switch ($json) {
			case false:
				if ($input_data === null) {
					// http_response_code(400); // Bad Request
					$json = $this->language->get('error_data');
					break;
				}

				$action = $input_data['LogAction'];
				$customer_id = $input_data['CustomerId'];
				// $finger_index = $input_data['FingerIndex'];
				$location_id = $input_data['LocationId'];

				if (empty($action) || empty($customer_id)) {
					$json = $this->language->get('error_data');
					break;
				}

				$this->load->model('presence/presence');
				$customer_info = $this->model_presence_presence->getCustomer($customer_id);

				if (!$customer_info) { //Cek apakah karyawan msh aktif
					$json = $this->language->get('error_customer_not_found');

					break;
				}
				
				$name = $this->config->get('payroll_setting_presence_card') != 'lastname' ? $customer_info['firstname'] : $customer_info['lastname'];

				# Input block untuk validasi karyawan hanya boleh input sidik jari di device finger yang sesuai dengan lokasi kerjanya
				$customer_info['permitted_locations'] = []; //Untuk testing, anggap all location.
				// $customer_info['permitted_locations'] = json_decode($customer_info['permitted_locations'], 1);

				if ($customer_info['permitted_locations'] && !in_array($location_id, $customer_info['permitted_locations'])) {
					$json = sprintf($this->language->get('error_location_mismatch'), $name);

					break;
				}

				if ($action == 'logout') {
					$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_logout_date') . ' hours'));

					$action = 'logout';
				} else {
					$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_login_date') . ' hours'));

					$action = 'login';
				}

				$schedule_check = $this->config->get('payroll_setting_schedule_check');

				if ($schedule_check) {
					$schedule_info = $this->model_presence_presence->getAppliedSchedule($customer_id, $schedule_date);

					if (!$schedule_info || !$schedule_info['schedule_type_id']) { //Cek ga ada jadwal
						$json = sprintf($this->language->get('error_absence'), $name);

						break;
					}

					$time_in = $schedule_date . ' ' . $schedule_info['time_in'];
					$time_out = $schedule_date . ' ' . $schedule_info['time_out'];

					if ($time_in >= $time_out) {
						$time_out = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($time_out)));
					}
				} else {
					$time_in = null;
					$time_out = null;
				}

				$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);

				if ($log_info) {
					if ($log_info['time_logout'] != null) { //Cek ternyata sudah logout
						$json = sprintf($this->language->get('error_logout'), $name, date('j M Y H:i:s', strtotime($log_info['time_logout'])));

						break;
					}

					if ($action == 'login' && $log_info['time_login'] != null) { //Cek sudah login
						$json = sprintf($this->language->get('error_login'), $name, date('j M Y H:i:s', strtotime($log_info['time_login'])));

						break;
					}

					if ($action == 'logout' && $log_info['time_login'] == null) { //Cek belum login
						$json = sprintf($this->language->get('error_not_login'), $name);

						break;
					}
				} else {
					if ($action == 'logout') { //Cek belum login
						$json = sprintf($this->language->get('error_not_login'), $name);

						break;
					}
				}

				if ($schedule_check) {
					if ($action == 'logout') {
						$logout_start = $this->config->get('payroll_setting_logout_start');
						$datetime_logout = date('Y-m-d H:i:s', strtotime('+' . $logout_start . ' minutes'));

						if ($logout_start && strtotime($datetime_logout) < strtotime($time_out)) { //Cek login sebelum waktu start yang diizinkan
							$json = sprintf($this->language->get('error_logout_start'), $name);

							break;
						}
					} else {
						$login_start = $this->config->get('payroll_setting_login_start');
						$date_login_start = date('Y-m-d H:i:s', strtotime('+' . $login_start . ' minutes'));

						$login_end = $this->config->get('payroll_setting_login_end');
						$date_login_end = date('Y-m-d H:i:s', strtotime('-' . $login_end . ' minutes'));

						if ($login_start && strtotime($date_login_start) < strtotime($time_in)) { #Cek login sebelum waktu start yang diizinkan. Value 0 untuk menonaktifkan.
							$json = sprintf($this->language->get('error_login_start'), $name);

							break;
						} elseif ($login_end && strtotime($date_login_end) > strtotime($time_in)) { #Cek login setelah waktu akhir yang diizinkan. Value 0 untuk menonaktifkan.
							// $json = sprintf($this->language->get('error_login_end'), $name);

							// break;
						}
					}
				}

				$this->model_presence_presence->addScheduleTime($customer_id, $schedule_date, $action, $time_in, $time_out);
				$this->model_presence_presence->addLog($customer_id, $schedule_date, $action, $location_id);

				$json = sprintf($this->language->get('text_success_login'), $name, $this->language->get('text_' . $action), date('j M Y H:i:s'));

				break;

			default:
				break;
		}

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function fingers()
	{
		$this->load->language('api/finger');

		$json = [];
		$status = '';

		switch ($json) {
			case false:
				if (!isset($this->request->get['token'])) {
					$status = $this->language->get('error_token');
					break;
				}

				if (!isset($this->request->get['location_id'])) {
					$status = $this->language->get('error_location');
					break;
				}

				$this->load->model('localisation/finger_device');
				$device_info = $this->model_localisation_finger_device->getFingerDeviceByToken($this->request->get['token']);

				if (!$device_info) {
					$status = $this->language->get('error_device');

					break;
				}

				if ($device_info['location_id'] && $device_info['location_id'] != $this->request->get['location_id']) {
					$status = $this->language->get('error_location_mismatch');

					break;
				}

				$filter = [
					'location_id'  	=> $this->request->get['location_id'],
					'legacy'		=> 0
				];

				$this->load->model('presence/presence');
				$fingers = $this->model_presence_presence->getFingers(['filter' => $filter]);

				foreach ($fingers as $finger) {
					$json['Data'][] = [
						'CustomerId'	=> (int)$finger['customer_id'],
						// 'Name'			=> $finger['lastname'],
						'FingerIndex'	=> (int)$finger['finger_index'],
						'Fmd'			=> htmlspecialchars_decode($finger['finger_data'])
					];
				};

				break;

			default:
				break;
		}

		$json['Status'] = $status;

		if (isset($this->request->server['HTTP_ORIGIN'])) {
			$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
			$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
			$this->response->addHeader('Access-Control-Max-Age: 1000');
			$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
