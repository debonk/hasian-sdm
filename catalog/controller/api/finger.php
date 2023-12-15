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

		// var_dump($json);
		// die('--break');

		// if (!$json) {
		// 	foreach ($results as $result) {
		// 		// $name = $this->config->get('payroll_setting_presence_card') == 'lastname' ? $result['lastname'] : $result['firstname'];

		// 		if (is_null($result['active_finger'])) {
		// 			$json['fingers'][] = array(
		// 				'customer_id'	=> $result['customer_id'],
		// 				// 'finger_id'		=> $result['finger_id'],
		// 				'active_finger'	=> 0,
		// 				// 'name'       	=> $name,
		// 				'finger_data'	=> $result['finger_data']
		// 			);
		// 		} else {
		// 			$active_fingers = json_decode($result['active_finger'], 1);

		// 			if (in_array($result['customer_id'] . 'x' . $result['finger_index'], $active_fingers)) {
		// 				$json['fingers'][] = array(
		// 					'customer_id'	=> $result['customer_id'],
		// 					// 'finger_id'		=> $result['finger_id'],
		// 					'active_finger'	=> $result['finger_index'],
		// 					// 'name'       	=> $name,
		// 					'finger_data'	=> $result['finger_data']
		// 				);
		// 			}
		// 		}
		// 	}

		// 	$json['ready'] = $this->language->get('text_ready');
		// }

		// if (isset($this->request->server['HTTP_ORIGIN'])) {
		// 	$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
		// 	$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		// 	$this->response->addHeader('Access-Control-Max-Age: 1000');
		// 	$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		// }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function template()
	{
		$this->db->createView('v_customer_finger');

		$this->load->language('api/finger');

		$json = [];

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
		
		// $filter['location_id'] = 3;

		$this->load->model('presence/presence');

		$filter_data = array(
			'filter'	=> $filter
		);

		$results = $this->model_presence_presence->getFingers($filter_data);

		if (!$results) {
			$json['warning'] = $this->language->get('error_data');
		}

		if (!$json) {
			foreach ($results as $result) {
				// $name = $this->config->get('payroll_setting_presence_card') == 'lastname' ? $result['lastname'] : $result['firstname'];

				if (is_null($result['active_finger'])) {
					$json['fingers'][] = array(
						'customer_id'	=> $result['customer_id'],
						// 'finger_id'		=> $result['finger_id'],
						'active_finger'	=> 0,
						// 'name'       	=> $name,
						'finger_data'	=> $result['finger_data']
					);
				} else {
					$active_fingers = json_decode($result['active_finger'], 1);

					if (in_array($result['customer_id'] . 'x' . $result['finger_index'], $active_fingers)) {
						$json['fingers'][] = array(
							'customer_id'	=> $result['customer_id'],
							// 'finger_id'		=> $result['finger_id'],
							'active_finger'	=> $result['finger_index'],
							// 'name'       	=> $name,
							'finger_data'	=> $result['finger_data']
						);
					}
				}
			}

			$json['ready'] = $this->language->get('text_ready');
		}

		// if (isset($this->request->server['HTTP_ORIGIN'])) {
		// 	$this->response->addHeader('Access-Control-Allow-Origin: ' . $this->request->server['HTTP_ORIGIN']);
		// 	$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		// 	$this->response->addHeader('Access-Control-Max-Age: 1000');
		// 	$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
		// }

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function login()
	{
		$this->load->language('api/finger');

		$json = [];

		if (empty($this->request->post)) {
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
					$time_in = '';
					$time_out = '';
					// $time_in = '0000-00-00 00:00:00';
					// $time_out = '0000-00-00 00:00:00';
				}

				$name = $this->config->get('payroll_setting_presence_card') != 'lastname' ? $customer_info['firstname'] : $customer_info['lastname'];
				
				$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);
				
				if ($log_info) {
					if ($log_info['time_logout'] != '0000-00-00 00:00:00') { //Cek ternyata sudah logout
						// $json['result'] = $this->language->get('error_logout');
						$json['result'] = sprintf($this->language->get('error_logout'), $name, date('j M Y H:i:s', strtotime($log_info['time_logout'])));

						break;
					}

					if ($action == 'login' && $log_info['time_login'] != '0000-00-00 00:00:00') { //Cek sudah login
						// $json['result'] = $this->language->get('error_login');
						$json['result'] = sprintf($this->language->get('error_login'), $name, date('j M Y H:i:s', strtotime($log_info['time_login'])));

						break;
					}

					if ($action == 'logout' && $log_info['time_login'] == '0000-00-00 00:00:00') { //Cek belum login
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
}
