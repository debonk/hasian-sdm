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
			'button_login',
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$data['newsletter'] = $this->url->link('account/newsletter', '', true);

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

		if (!$json && $this->request->server['REQUEST_METHOD'] == 'POST' && $this->request->post) {
			$latitude = trim($this->request->post['coords']['latitude']);
			$longitude = trim($this->request->post['coords']['longitude']);
			$accuracy = trim($this->request->post['coords']['accuracy']);
			$datetime = date('Y-m-d H:i:s', (int)($this->request->post['timestamp'] / 1000));

			$config_latitude = -7.2802238;
			$config_longitude = 112.7755689;
			$config_tolerance = 0.00008;

			if ($latitude - $config_latitude > $config_tolerance || $longitude - $config_longitude > $config_tolerance) {
				// $json['error'] = $this->language->get('error_login');
				$status = 'Too Far!';
			} else {
				#login block
				$status = 'Login Success!';
			}
// 
			$json['location'] = 'Lat: ' . $latitude . ' & Long: ' . $longitude . ' & Acc: ' . $accuracy . ' & Time: ' . $datetime . ' (' . $status . ')';
			$json['pos'] = 'Pos: @' . $latitude . ',' . $longitude . ',21z?hl=en';

			$this->log($json['location']);
			$this->log($json['pos']);
			// print_r($this->request->post);

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

	// public function verify()
	// {
	// 	// $file = $this->request->files['file'];
	// 	$file['tmp_name'] = DIR_DOWNLOAD . 'wilbex3.jpg';

	// 	$meta_data = exif_read_data($file['tmp_name'], 0, true);
	// 	var_dump($meta_data);
	// }
}
