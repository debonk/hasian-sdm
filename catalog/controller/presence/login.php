<?php
class ControllerPresenceLogin extends Controller {
	private $error = array();

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
		} else {
			$action = 'login';
			$data['text_list'] = $this->language->get('text_login');
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
		
		$login_start = $this->config->get('payroll_setting_login_start');
		$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));
		
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
				if ($log_info['time_logout'] == '0000-00-00 00:00:00') {
					$log_class = 'login';
				} else {
					$log_class = 'logout';
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
			$schedule_date = date('Y-m-d', strtotime('-13 hours'));//penentuan tgl jadwal
			
			$action = 'logout';
		} else {
			$login_start = $this->config->get('payroll_setting_login_start');
			$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));

			$action = 'login';
		}

		$verification_path	= HTTP_SERVER . 'catalog/model/finger/verification.php';
		
		$url_verification	= base64_encode($verification_path . '?customer_id=' . $customer_id . '&schedule_date=' . $schedule_date . '&action=' . $action);
		// $url_verification = ($verification_path . '?customer_id=' . $customer_id . '&schedule_date=' . $schedule_date . '&action=' . $action);
		// $verification = $url_verification;
		$verification = 'finspot:FingerspotVer;' . $url_verification;

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
			$schedule_date = date('Y-m-d', strtotime('-13 hours'));
			
			$action = 'logout';
		} else {
			$login_start = $this->config->get('payroll_setting_login_start');
			$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));

			$action = 'login';
		}

		$this->load->model('presence/presence');
		
		$check = 0;
		$counter = 1;

		while($check < 1) {
			switch ($counter) {
				case '1':
					$customer_info = $this->model_presence_presence->getCustomer($customer_id);
					
					if (!$customer_info) {//Cek apakah karyawan msh aktif
						$check = 1;
						$json['error'] = $this->language->get('error_customer_not_found');
					}

					break;
					
				case '2':
					$use_fingerprint = $this->config->get('payroll_setting_use_fingerprint');
		
					$finger_count = $this->model_presence_presence->getFingersCount($customer_id);
					
					if($use_fingerprint && !$finger_count) {//Cek apakah sudah rekam sidik jari
						$check = 1;
						$json['error'] = $this->language->get('error_finger_not_found');
					}

					break;
					
				case '3':
					$schedule_check = $this->config->get('payroll_setting_schedule_check');
					
					if ($schedule_check) {
						$schedule_info = $this->model_presence_presence->getAppliedSchedule($customer_id, $schedule_date);
						
						if (!$schedule_info || !$schedule_info['schedule_type_id']) {//Cek ga ada jadwal
							$check = 1;
							$json['error'] = $this->language->get('error_absence');
						}

						break;
					}
					
				case '4':
					$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);
					
					if ($log_info) {
						if ($log_info['time_logout'] != '0000-00-00 00:00:00') {//Cek ternyata sudah logout
							$check = 1;
							$json['error'] = $this->language->get('error_logout');
							
						} elseif ($action == 'login') {//Cek sudah login
							$check = 1;
							$json['error'] = $this->language->get('error_login');
							
						} elseif ($schedule_check) {
							$logout_start = $this->config->get('payroll_setting_logout_start');
							$datetime_logout = date('Y-m-d H:i:s', strtotime('+' . $logout_start . ' minutes'));
							
							if ($logout_start && strtotime($datetime_logout) < strtotime($schedule_date . ' ' . $schedule_info['time_out'])) {//Cek login sebelum waktu start yang diizinkan
								$check = 1;
								$json['error'] = $this->language->get('error_logout_start');
							}
						}
						
					} else {
						if ($action == 'logout') {
							$check = 1;
							$json['error'] = $this->language->get('error_not_login');
						
						} elseif ($schedule_check) {
							$login_start = $this->config->get('payroll_setting_login_start');
							$date_login_start = date('Y-m-d H:i:s', strtotime('+' . $login_start . ' minutes'));
							
							$login_end = $this->config->get('payroll_setting_login_end');
							$date_login_end = date('Y-m-d H:i:s', strtotime('-' . $login_end . ' minutes'));
							
							if ($login_start && strtotime($date_login_start) < strtotime($schedule_date . ' ' . $schedule_info['time_in'])) {//Cek login sebelum waktu start yang diizinkan
								$check = 1;
								$json['error'] = $this->language->get('error_login_start');
								
							} elseif ($login_end && strtotime($date_login_end) > strtotime($schedule_date . ' ' . $schedule_info['time_in'])) {//Cek login setelah waktu akhir yang diizinkan
								$check = 1;
								$json['error'] = $this->language->get('error_login_end');
							}
						}
					}

					break;
					
				default:
					if (!$this->config->get('payroll_setting_use_fingerprint')) {
						$this->load->model('presence/presence');
						
						$this->model_presence_presence->addLog($customer_id, $schedule_date, $action);
					}
					
					$check = 1;
					$json['process_verification'] = 1;
			}
			
			$counter++;
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
			$schedule_date = date('Y-m-d', strtotime('-13 hours'));
			
			$action = 'logout';
		} else {
			$login_start = $this->config->get('payroll_setting_login_start');
			$schedule_date = date('Y-m-d', strtotime('+' . $login_start . ' minutes'));

			$action = 'login';
		}
		
		$this->load->model('presence/presence');
		$log_info = $this->model_presence_presence->getLog($customer_id, $schedule_date);
		
		if ($log_info) {
			if ($action == 'login' && $log_info['time_logout'] == '0000-00-00 00:00:00') {
				$json['success'] = sprintf($this->language->get('text_success_login'), date('j M Y H:i:s', strtotime($log_info['time_login'])));
			} elseif ($action == 'logout' && $log_info['time_logout'] != '0000-00-00 00:00:00') {
				$json['success'] = sprintf($this->language->get('text_success_logout'), date('j M Y H:i:s', strtotime($log_info['time_logout'])));
			}
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
