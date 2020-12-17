<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');

		$data['menu_groups'] = [];

		$menu_groups = [
			'customer'					=> ['customer', 'document', 'finger'],
			'localisation_customer'		=> ['location', 'customer_department', 'customer_group', 'gender', 'marriage_status', 'payroll_method', 'custom_field', 'document_type', 'finger_device'],
			'component'					=> ['loan', 'cutoff', 'incentive', 'overtime'],
			'localisation_component'	=> ['overtime_type'],
			'presence'					=> ['presence_period', 'schedule', 'exchange', 'absence', 'presence'],
			'localisation_presence'		=> ['schedule_type', 'presence_status'],
			'payroll'					=> ['payroll_basic', 'payroll'],
			'release'					=> ['payroll_release', 'free_transfer', 'allowance'],
			'localisation_release'		=> ['fund_account'],
			'report_customer'			=> ['report_customer', 'customer_loan', 'customer_history'],
			'report_payroll'			=> ['payroll_insurance', 'payroll_tax'],
			'catalog'					=> ['information', 'download'],
			'extension'					=> ['installer', 'modification', 'theme', 'component', 'module'],
			'themecontrol'				=> ['themecontrol', 'pavmegamenu', 'pavblog'],
			'system'					=> ['setting', 'payroll_setting'],
			'localisation'				=> ['language', 'currency', 'payroll_status', 'country', 'zone', 'city', 'geozone'],
			'user'	 					=> ['user', 'user_permission', 'api'],
			'tool'	 					=> ['sysinfo', 'upload', 'backup', 'error_log']
		];
		
		$permissions = $this->user->getPermission();

		foreach ($permissions as $authority => $permission) {
			foreach ($permission as $value) {
				//Sementara, akibat key: customer menjadi dobel
				if ($value == 'report/customer') {
					$key ='report_customer';
				} else {
					$key = explode('/', $value)[1];
				}

				$permission_data[$key] = [
					'url'	=> $this->url->link($value, 'token=' . $this->session->data['token'], 'true'),
					'text'	=> $this->language->get('text_' . $key),
					'class'	=> $authority
				];
			}
		}
		

		foreach ($menu_groups as $menu_group => $menu_items) {
			foreach ($menu_items as $menu_item) {
				if (array_key_exists($menu_item, $permission_data)) {

					$data['menu_groups'][$menu_group][] = $permission_data[$menu_item];
				}
			}
		}
		$language_items = array(
			'text_catalog',
			'text_customer',
			'text_presence',
			'text_payroll',
			'text_payroll_release',
			'text_extension',
			'text_dashboard',
			'text_localisation',
			'text_report',
			'text_system',
			'text_tool',
			'text_component',
			'text_user',
			'text_themecontrol'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		return $this->load->view('common/menu', $data);
	}
}
