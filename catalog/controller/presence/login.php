<?php
class ControllerPresenceLogin extends Controller {
	public function index() {
		$this->load->language('presence/login');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$theme_dir = $this->config->get('theme_default_directory');
		$this->document->addStyle('catalog/view/theme/' . $theme_dir . '/stylesheet/login.css');

		$this->document->addScript('catalog/view/javascript/finger/jquery.timer.js');
		
		$this->load->model('presence/presence');
		
		$this->getList();
	}

	protected function getList() {
		if (isset($this->request->get['location_id'])) {
			$location_id = $this->request->get['location_id'];
		} else {
			$location_id = 0;
		}
		
		if (isset($this->request->get['action']) && $this->request->get['action'] == 'logout') {
			$action = 'logout';
			$data['text_list'] = $this->language->get('text_logout');
			
			$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_logout_date') . ' hours'));

		} else {
			$action = 'login';
			$data['text_list'] = $this->language->get('text_login');

			$login_start = $this->config->get('payroll_setting_login_start');
			$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));
		}
		
		$url = '';

		if (isset($this->request->get['location_id'])) {
			$url .= '&location_id=' . $this->request->get['location_id'];
		}
		
		if (isset($this->request->get['action'])) {
			$url .= '&action=' . $this->request->get['action'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('presence/login')
		);

		$data['customers'] = array();
		
		$filter_data = array(
			'filter_location_id'       => $location_id
		);

		$results = $this->model_presence_presence->getCustomers($filter_data);

		$this->load->model('tool/image');
		
		foreach ($results as $result) {
			if ($result['image'] && file_exists(DIR_IMAGE . $result['image'])) {
				$image = $this->model_tool_image->resize($result['image'], 140, 140);
				$text_image = '';
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 140, 140);
				$text_image = $result['name'];
			}
			
			$log_info = $this->model_presence_presence->getLog($result['customer_id'], $schedule_date);
			
			if ($log_info) {
				if ($log_info['time_logout'] != '0000-00-00 00:00:00') {
					$log_class = 'logout';
				} elseif ($log_info['time_login'] != '0000-00-00 00:00:00') {
					$log_class = 'login';
				} else {
					$log_class = 'active';
				}
			} else {
				$log_class = 'active';
			}
			
 			$data['customers'][] = array(
				'customer_id' 	=> $result['customer_id'],
				'name' 			=> $result['name'],
				'image' 		=> $image,
				'text_image' 	=> $text_image,
				'log_class'		=> $log_class
			);
		}
		
		$language_items = array(
			'heading_title',
			'text_select',
			'text_loading',
			'text_no_results',
			'entry_name',
			'button_login',
			'button_logout',
			'error_verification'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$url = '';

		if (isset($this->request->get['location_id'])) {
			$url .= '&location_id=' . $this->request->get['location_id'];
		}
		
		$data['href_login'] = $this->url->link('presence/login', 'action=login' . $url, true);
		$data['href_logout'] = $this->url->link('presence/login', 'action=logout' . $url, true);

		$data['use_fingerprint'] = $this->config->get('payroll_setting_use_fingerprint');
		
		$this->load->model('localisation/location');
		$data['locations'] = $this->model_localisation_location->getLocations();
		
		$data['location_id'] = $location_id;
		$data['action'] = $action;
		
		$data['store_name'] = $this->config->get('config_name');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('presence/login', $data));
	}

	public function verification() {
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['action']) && $this->request->get['action'] == 'logout') {
			$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_logout_date') . ' hours')); //penentuan tgl jadwal
			
			$action = 'logout';
		} else {
			$login_start = $this->config->get('payroll_setting_login_start');
			$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));

			$action = 'login';
		}

		// if ($this->request->server['HTTPS']) {
		// 	$server = HTTPS_SERVER;
		// } else {
		// 	$server = HTTP_SERVER;
		// }
		
		$verification_path	= HTTP_SERVER . 'catalog/model/finger/verification.php';
		// $verification_path	= $server . 'catalog/model/finger/verification.php';
	
		$url_verification	= base64_encode($verification_path . '?customer_id=' . $customer_id . '&schedule_date=' . $schedule_date . '&action=' . $action);
		$verification = 'finspot:FingerspotVer;' . $url_verification;
		// $url_verification = ($verification_path . '?customer_id=' . $customer_id . '&schedule_date=' . $schedule_date . '&action=' . $action);
		// $verification = $url_verification;
		
		$this->response->redirect($verification);
	}

	public function validateLog() {
		$this->load->language('presence/login');

		$json = array();

		if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->post['action']) && $this->request->post['action'] == 'logout') {
			$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_logout_date') . ' hours'));
			
			$action = 'logout';
		} else {
			$login_start = $this->config->get('payroll_setting_login_start');
			$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));

			$action = 'login';
		}

		$this->load->model('presence/presence');
		
		switch ($json) {
			case false:
				$customer_info = $this->model_presence_presence->getCustomer($customer_id);
				
				if (!$customer_info) {//Cek apakah karyawan msh aktif
					$json['error'] = $this->language->get('error_customer_not_found');
				
				break;
				}


				$use_fingerprint = $this->config->get('payroll_setting_use_fingerprint');
		
				$finger_count = $this->model_presence_presence->getFingersCount($customer_id);
				
				if ($use_fingerprint && !$finger_count) {//Cek apakah sudah rekam sidik jari
					$json['error'] = $this->language->get('error_finger_not_found');
					
				break;
				}
						
				$schedule_check = $this->config->get('payroll_setting_schedule_check');
					
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

				$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);

				if ($log_info) {
					if ($log_info['time_logout'] != '0000-00-00 00:00:00') {//Cek ternyata sudah logout
						$json['error'] = $this->language->get('error_logout');

					break;
					}
					
					if ($action == 'login' && $log_info['time_login'] != '0000-00-00 00:00:00') {//Cek sudah login
						$json['error'] = $this->language->get('error_login');

					break;
					}

					if ($action == 'logout' && $log_info['time_login'] == '0000-00-00 00:00:00') {//Cek belum login
						$json['error'] = $this->language->get('error_not_login');

					break;
					}
					
				} else {
					if ($action == 'logout') {//Cek belum login
						$json['error'] = $this->language->get('error_not_login');

					break;
					}
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
						$login_start = $this->config->get('payroll_setting_login_start');
						$date_login_start = date('Y-m-d H:i:s', strtotime('+' . $login_start . ' minutes'));
						
						$login_end = $this->config->get('payroll_setting_login_end');
						$date_login_end = date('Y-m-d H:i:s', strtotime('-' . $login_end . ' minutes'));
						
						if ($login_start && strtotime($date_login_start) < strtotime($time_in)) {//Cek login sebelum waktu start yang diizinkan
							$json['error'] = $this->language->get('error_login_start');
							
						break;
						} elseif ($login_end && strtotime($date_login_end) > strtotime($time_in)) {//Cek login setelah waktu akhir yang diizinkan
							$json['error'] = $this->language->get('error_login_end');

						break;
						}
					}
				}		

				$this->model_presence_presence->addScheduleTime($customer_id, $schedule_date, $action, $time_in, $time_out);

				if (!$use_fingerprint) {
					$this->model_presence_presence->addLog($customer_id, $schedule_date, $action);
				}
					
				$json['process_verification'] = 1;

			default:
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function getLogStatus() {
		$this->load->language('presence/login');

		$json = array();
		
		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}
		
		if (isset($this->request->get['action']) && $this->request->get['action'] == 'logout') {
			$schedule_date = date('Y-m-d', strtotime($this->config->get('payroll_setting_logout_date') . ' hours'));
			
			$action = 'logout';
		} else {
			$login_start = $this->config->get('payroll_setting_login_start');
			$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));

			$action = 'login';
		}
		
		$this->load->model('presence/presence');
		$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);
		
		if ($log_info) {
			if ($action == 'login' && $log_info['time_login'] != '0000-00-00 00:00:00') {
				$json['success'] = sprintf($this->language->get('text_success_login'), date('j M Y H:i:s', strtotime($log_info['time_login'])));
			} elseif ($action == 'logout' && $log_info['time_logout'] != '0000-00-00 00:00:00') {
				$json['success'] = sprintf($this->language->get('text_success_logout'), date('j M Y H:i:s', strtotime($log_info['time_logout'])));
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
