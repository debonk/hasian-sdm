<?php
class ControllerCommonMenu extends Controller
{
	public function index()
	{
		$this->load->language('common/menu');

		$data['text_dashboard'] = $this->language->get('text_dashboard');
		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);

		$data['menu_groups'] = [];
		$data['child_groups'] = [];
		$data['menu_title'] = [];

		$fa_icon_class = ['fa-user', 'fa-th-large', 'fa-calendar', 'fa-money', 'fa-share-alt', 'fa-bar-chart-o', 'fa-info', 'fa-puzzle-piece', 'fa-rocket', 'fa-cog', '', ''];

		$menu_groups = [
			'customer'		=> [
				'customer/customer',
				'customer/document',
				'customer/finger',
				'option'		=> ['localisation/location', 'customer/customer_department', 'customer/customer_group', 'localisation/gender', 'localisation/marriage_status', 'localisation/payroll_method', 'customer/custom_field', 'customer/document_type', 'localisation/finger_device'],
			],
			'component'		=> [
				'loan/loan',
				'cutoff/cutoff',
				'incentive/incentive',
				'overtime/overtime',
				'option'		=> ['overtime/overtime_type'],
			],
			'presence'		=> [
				'presence/presence_period',
				'presence/schedule',
				'presence/exchange',
				'presence/absence',
				'presence/presence',
				'option'		=> ['presence/schedule_type', 'localisation/presence_status']
			],
			'payroll'		=> ['payroll/payroll_basic', 'payroll/payroll'],
			'release'		=> [
				'payroll/payroll_release',
				'release/free_transfer',
				'release/allowance',
				'option'		=> ['release/fund_account'],
			],
			'report'		=> [
				'customer'		=> ['report/customer', 'report/customer_loan', 'report/customer_history'],
				'payroll'		=> ['report/payroll', 'report/payroll_insurance', 'report/payroll_tax'],
			],
			'information'	=> ['module/information', 'catalog/download'],
			'extension'		=> ['extension/installer', 'extension/modification', 'extension/theme', 'extension/component', 'extension/module'],
			'themecontrol'	=> ['module/themecontrol', 'module/pavmegamenu', 'module/pavblog'],
			'system'		=> [
				'setting/store',
				'payroll/payroll_setting',
				'localisation'	=> ['localisation/language', 'localisation/currency', 'localisation/payroll_status', 'localisation/country', 'localisation/zone', 'localisation/city', 'localisation/geozone'],
				'user'			=> ['user/user', 'user/user_permission', 'user/api'],
				'tools'			=> ['tool/sysinfo', 'tool/upload', 'tool/backup', 'tool/error_log']
			]
		];

		$permissions = $this->user->getPermission();

		foreach ($permissions as $authority => $permission) {
			foreach ($permission as $value) {
				$permission_data[$value] = [
					'url'	=> $this->url->link($value, 'token=' . $this->session->data['token'], 'true'),
					'text'	=> $this->language->get('text_' . explode('/', $value)[1]),
					'class'	=> $authority
				];
			}
		}

		$menu_titles = array_keys($menu_groups);
		foreach ($menu_titles as $idx => $title) {
			$data['menu_titles'][$title] = [
				'text'	=> $this->language->get('text_' . $title),
				'icon'	=> $fa_icon_class[$idx]
			];
		}

		foreach ($menu_groups as $menu_group => $menu_items) {
			foreach ($menu_items as $child_group => $menu_item) {
				if (is_array($menu_item)) {
					foreach ($menu_item as $child_item) {
						if (array_key_exists($child_item, $permission_data)) {
							$data['child_groups'][$menu_group][$child_group][] = $permission_data[$child_item];
						}
					}

					if (!empty($data['child_groups'][$menu_group][$child_group])) {
						$data['menu_groups'][$menu_group][$child_group] = [
							'text'	=> $this->language->get('text_' . $child_group)
						];
					}
				} else {
					if (array_key_exists($menu_item, $permission_data)) {
						$data['menu_groups'][$menu_group][$menu_item] = $permission_data[$menu_item];
					}
				}
			}
		}

		return $this->load->view('common/menu', $data);
	}
}
