<?php
class ControllerCommonMaintenance extends Controller {
	public function index() {
		$this->load->language('common/maintenance');

		$this->document->setTitle($this->language->get('heading_title'));

		if ($this->request->server['SERVER_PROTOCOL'] == 'HTTP/1.1') {
			$this->response->addHeader('HTTP/1.1 503 Service Unavailable');
		} else {
			$this->response->addHeader('HTTP/1.0 503 Service Unavailable');
		}

		$this->response->addHeader('Retry-After: 3600');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_maintenance'),
			'href' => $this->url->link('common/maintenance')
		);

		$item_lists = array(
			'blank',
			// 'willowbabyshop',
			// 'willow-hr-management',
			// 'willow-accounting',
			// 'willow-login'
		);

		$data['items'] = array();

		foreach ($item_lists as $item_list) {
			$data['items'][] = array(
				'image' => $this->language->get('image_' . $item_list),
				'href' 	=> $this->language->get('href_' . $item_list)
			);
		}

		$data['message'] = $this->language->get('text_message');

		$data['header'] = $this->load->controller('common/header');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('common/maintenance', $data));
	}
}
