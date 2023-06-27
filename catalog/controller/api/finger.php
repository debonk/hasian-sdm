<?php
class ControllerApiFinger extends Controller
{
	public function index()
	{
		$this->load->language('api/finger');

		// Delete past finger in case there is an error
		unset($this->session->data['finger']);

		$json = [];


		// if (!isset($this->session->data['api_id'])) {
		// 	$json['error']['warning'] = $this->language->get('error_permission');
		// } else {
		// Add keys for missing post vars
		$filter_items = array(
			'location_id',
			'action'
		);

		$filter = [];

		foreach ($filter_items as $filter_item) {
			if (isset($this->request->post[$filter_item])) {
				$filter[$filter_item] = $this->request->post[$filter_item];
			} else {
				$filter[$filter_item] = null;
			}
		}

		// Finger
		$this->load->model('presence/finger');

		$filter_data = array(
			'filter'	=> $filter
		);

		$results = $this->model_presence_finger->getFingers($filter_data);

		if (!$results) {
			$json['error'] = $this->language->get('error_data');
		}

		// if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
		// 	$json['error']['firstname'] = $this->language->get('error_firstname');
		// }

		// if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
		// 	$json['error']['lastname'] = $this->language->get('error_lastname');
		// }

		// if ((utf8_strlen($this->request->post['email']) > 96) || (!filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL))) {
		// 	$json['error']['email'] = $this->language->get('error_email');
		// }

		// if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
		// 	$json['error']['telephone'] = $this->language->get('error_telephone');
		// }

		if (!$json) {
			foreach ($results as $result) {
				$json['fingers'] = array(
					'customer_id'	=> $result['customer_id'],
					'finger_data'	=> $result['finger_data'],
					'name'       	=> $result['customer']
				);

				$json['ready'] = $this->language->get('text_ready');
			}
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
