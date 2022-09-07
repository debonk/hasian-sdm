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
			'text_my_account',
			'text_my_presence',
			'text_my_newsletter',
			'text_edit',
			'text_password',
			'text_download',
			'text_newsletter',
			'text_unsupport',
			'entry_image',
			'button_login',
			'button_verify'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['login'] = $this->url->link('account/account/verify', '', true);
		// $data['address'] = $this->url->link('account/address', '', true);

		// $data['credit_cards'] = array();

		// $files = glob(DIR_APPLICATION . 'controller/credit_card/*.php');

		// foreach ($files as $file) {
		// $code = basename($file, '.php');

		// if ($this->config->get($code . '_status') && $this->config->get($code)) {
		// $this->load->language('credit_card/' . $code);

		// $data['credit_cards'][] = array(
		// 'name' => $this->language->get('heading_title'),
		// 'href' => $this->url->link('credit_card/' . $code, '', true)
		// );
		// }
		// }

		$data['download'] = $this->url->link('account/download', '', true);

		// if ($this->config->get('reward_status')) {
		// $data['reward'] = $this->url->link('account/reward', '', true);
		// } else {
		// $data['reward'] = '';
		// }		

		// $data['transaction'] = $this->url->link('account/transaction', '', true);
		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		// $data['recurring'] = $this->url->link('account/recurring', '', true);

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

		$this->response->setOutput($this->load->view('account/account', $data));
	}

	public function location()
	{
		$this->load->language('account/account');

		$json = [];

		if (!$this->customer->isLogged()) {
			$json['redirect'] = $this->url->link('account/login', '', true);
		}

		if (!$json) {
			$latitude = isset($this->request->post['latitude']) ? trim($this->request->post['latitude']) : null;
			$longitude = isset($this->request->post['longitude']) ? trim($this->request->post['longitude']) : null;

			if ($this->request->server['REQUEST_METHOD'] == 'POST' && $latitude && $longitude) {

				$json['location'] = 'Lat = ' . $latitude . ' & Long = ' . $longitude;

				$this->log($json['location']);
				// print_r($this->request->post);

			} else {
				$json['error'] = $this->language->get('error_retrieve');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function log($message)
	{
		$log = new Log('presence.log');
		$log->write($message);
	}

	public function verify()
	{
		// $file = $this->request->files['file'];
		$file['tmp_name'] = DIR_DOWNLOAD . 'wilbex3.jpg';

		$meta_data = exif_read_data($file['tmp_name'], 0, true);
		var_dump($meta_data);
	}

	public function country()
	{
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	// Bonk
	public function zone()
	{
		$json = array();

		$this->load->model('localisation/zone');

		$zone_info = $this->model_localisation_zone->getZone($this->request->get['zone_id']);

		if ($zone_info) {
			$this->load->model('localisation/city');

			$json = array(
				'zone_id'        	=> $zone_info['zone_id'],
				'name'              => $zone_info['name'],
				'code'        		=> $zone_info['code'],
				'city'              => $this->model_localisation_city->getCitiesByZoneId($this->request->get['zone_id']),
				'status'            => $zone_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
