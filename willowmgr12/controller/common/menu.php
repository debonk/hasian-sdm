<?php
class ControllerCommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');
	
		$language_items = array(
			'text_api',
			'text_backup',
			'text_catalog',
			'text_confirm',
			'text_contact',
			'text_country',
			'text_currency',
			'text_customer',
			'text_document',
			'text_finger',
			'text_customer_group',
			'text_customer_department',
			'text_document_type',
			'text_custom_field',
			'text_gender',
			'text_marriage_status',
			'text_loan',
			'text_cutoff',
			'text_incentive',
			'text_overtime',
			'text_overtime_type',
			'text_presence_period',
			'text_schedule',
			'text_exchange',
			'text_absence',
			'text_presence',
			'text_schedule_type',
			'text_payroll',
			'text_payroll_basic',
			'text_payroll_release',
			'text_free_transfer',
			'text_allowance',
			'text_fund_account',
			'text_payroll_setting',
			'text_download',
			'text_error_log',
			'text_extension',
			'text_geo_zone',
			'text_dashboard',
			'text_help',
			'text_information',
			'text_module',
			'text_installer',
			'text_language',
			'text_localisation',
			'text_location',
			'text_modification',
			'text_presence_status',
			'text_payroll_status',
			'text_payroll_method',
			'text_opencart',
			'text_report',
			'text_report_customer',
			'text_report_customer_loan',
			'text_report_customer_history',
			'text_report_payroll_insurance',
			'text_report_payroll_tax',
			'text_setting',
			'text_sysinfo',
			'text_system',
			'text_theme',
			'text_tools',
			'text_component',
			'text_upload',
			'text_tracking',
			'text_user',
			'text_user_group',
			'text_users',
			'text_zone',
			'text_city',
			'text_finger_device',
			'text_themecontrol',
			'text_pavmegamenu',
			'text_pavblog'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}
		
		$data['themecontrol'] = $this->url->link('module/themecontrol', 'token=' . $this->session->data['token'], 'true');
		$data['pavmegamenu'] = $this->url->link('module/pavmegamenu', 'token=' . $this->session->data['token'], 'true');
		$data['pavblog'] = $this->url->link('module/pavblog', 'token=' . $this->session->data['token'], 'true');
		// $data['pavnewsletter_link'] = $this->url->link('module/pavnewsletter', 'token=' . $this->session->data['token'], 'true');
		$data['home'] = $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true);
		$data['api'] = $this->url->link('user/api', 'token=' . $this->session->data['token'], true);
		$data['backup'] = $this->url->link('tool/backup', 'token=' . $this->session->data['token'], true);
		$data['country'] = $this->url->link('localisation/country', 'token=' . $this->session->data['token'], true);
		$data['contact'] = $this->url->link('marketing/contact', 'token=' . $this->session->data['token'], true);
		$data['currency'] = $this->url->link('localisation/currency', 'token=' . $this->session->data['token'], true);
		$data['customer'] = $this->url->link('customer/customer', 'token=' . $this->session->data['token'], true);
		$data['document'] = $this->url->link('customer/document', 'token=' . $this->session->data['token'], true);
		$data['finger'] = $this->url->link('customer/finger', 'token=' . $this->session->data['token'], true);
		$data['customer_group'] = $this->url->link('customer/customer_group', 'token=' . $this->session->data['token'], true);
		$data['customer_department'] = $this->url->link('customer/customer_department', 'token=' . $this->session->data['token'], true);
		$data['document_type'] = $this->url->link('customer/document_type', 'token=' . $this->session->data['token'], true);
		$data['custom_field'] = $this->url->link('customer/custom_field', 'token=' . $this->session->data['token'], true);
		$data['gender'] = $this->url->link('localisation/gender', 'token=' . $this->session->data['token'], true);
		$data['marriage_status'] = $this->url->link('localisation/marriage_status', 'token=' . $this->session->data['token'], true);
		$data['loan'] = $this->url->link('loan/loan', 'token=' . $this->session->data['token'], true);
		$data['cutoff'] = $this->url->link('cutoff/cutoff', 'token=' . $this->session->data['token'], true);
		$data['incentive'] = $this->url->link('incentive/incentive', 'token=' . $this->session->data['token'], true);
		$data['overtime'] = $this->url->link('overtime/overtime', 'token=' . $this->session->data['token'], true);
		$data['overtime_type'] = $this->url->link('overtime/overtime_type', 'token=' . $this->session->data['token'], true);
		$data['presence_period'] = $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'], true);
		$data['schedule'] = $this->url->link('presence/schedule', 'token=' . $this->session->data['token'], true);
		$data['exchange'] = $this->url->link('presence/exchange', 'token=' . $this->session->data['token'], true);
		$data['absence'] = $this->url->link('presence/absence', 'token=' . $this->session->data['token'], true);
		$data['presence'] = $this->url->link('presence/presence', 'token=' . $this->session->data['token'], true);
		$data['schedule_type'] = $this->url->link('presence/schedule_type', 'token=' . $this->session->data['token'], true);
		$data['payroll'] = $this->url->link('payroll/payroll', 'token=' . $this->session->data['token'], true);
		$data['payroll_basic'] = $this->url->link('payroll/payroll_basic', 'token=' . $this->session->data['token'], true);
		$data['payroll_release'] = $this->url->link('payroll/payroll_release', 'token=' . $this->session->data['token'], true);
		$data['free_transfer'] = $this->url->link('release/free_transfer', 'token=' . $this->session->data['token'], true);
		$data['allowance'] = $this->url->link('release/allowance', 'token=' . $this->session->data['token'], true);
		$data['fund_account'] = $this->url->link('release/fund_account', 'token=' . $this->session->data['token'], true);
		$data['payroll_setting'] = $this->url->link('payroll/payroll_setting', 'token=' . $this->session->data['token'], true);
		$data['download'] = $this->url->link('catalog/download', 'token=' . $this->session->data['token'], true);
		$data['error_log'] = $this->url->link('tool/error_log', 'token=' . $this->session->data['token'], true);
		$data['geo_zone'] = $this->url->link('localisation/geo_zone', 'token=' . $this->session->data['token'], true);
		$data['information'] = $this->url->link('catalog/information', 'token=' . $this->session->data['token'], true);
		$data['installer'] = $this->url->link('extension/installer', 'token=' . $this->session->data['token'], true);
		$data['language'] = $this->url->link('localisation/language', 'token=' . $this->session->data['token'], true);
		$data['location'] = $this->url->link('localisation/location', 'token=' . $this->session->data['token'], true);
		$data['modification'] = $this->url->link('extension/modification', 'token=' . $this->session->data['token'], true);
		$data['module'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);
		$data['presence_status'] = $this->url->link('localisation/presence_status', 'token=' . $this->session->data['token'], true);
		$data['payroll_status'] = $this->url->link('localisation/payroll_status', 'token=' . $this->session->data['token'], true);
		$data['payroll_method'] = $this->url->link('localisation/payroll_method', 'token=' . $this->session->data['token'], true);
		$data['report_customer'] = $this->url->link('report/customer', 'token=' . $this->session->data['token'], true);
		$data['report_customer_loan'] = $this->url->link('report/customer_loan', 'token=' . $this->session->data['token'], true);
		$data['report_customer_history'] = $this->url->link('report/customer_history', 'token=' . $this->session->data['token'], true);
		$data['report_customer_activity'] = $this->url->link('report/customer_activity', 'token=' . $this->session->data['token'], true);
		$data['report_customer_online'] = $this->url->link('report/customer_online', 'token=' . $this->session->data['token'], true);
		$data['report_payroll_insurance'] = $this->url->link('report/payroll_insurance', 'token=' . $this->session->data['token'], true);
		$data['report_payroll_tax'] = $this->url->link('report/payroll_tax', 'token=' . $this->session->data['token'], true);
		$data['report_marketing'] = $this->url->link('report/marketing', 'token=' . $this->session->data['token'], true);
		$data['setting'] = $this->url->link('setting/store', 'token=' . $this->session->data['token'], true);
		$data['sysinfo'] = $this->url->link('tool/sysinfo', 'token=' . $this->session->data['token'], true);
		$data['theme'] = $this->url->link('extension/theme', 'token=' . $this->session->data['token'], true);
		$data['component'] = $this->url->link('extension/component', 'token=' . $this->session->data['token'], true);
		$data['upload'] = $this->url->link('tool/upload', 'token=' . $this->session->data['token'], true);
		$data['user'] = $this->url->link('user/user', 'token=' . $this->session->data['token'], true);
		$data['user_group'] = $this->url->link('user/user_permission', 'token=' . $this->session->data['token'], true);
		$data['zone'] = $this->url->link('localisation/zone', 'token=' . $this->session->data['token'], true);
		$data['city'] = $this->url->link('localisation/city', 'token=' . $this->session->data['token'], true);//Bonk
		$data['finger_device'] = $this->url->link('localisation/finger_device', 'token=' . $this->session->data['token'], true);//Bonk

		return $this->load->view('common/menu', $data);
	}
}
