<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['text_footer'] = $this->language->get('text_footer');

		if ($this->user->isLogged() && isset($this->request->get['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['text_version'] = sprintf($this->language->get('text_version'), VERSION);
		} else {
			$data['text_version'] = '';
		}
		
		// Whos Online
		$this->load->model('tool/activity');

		if (isset($this->request->server['REMOTE_ADDR'])) {
			$ip = $this->request->server['REMOTE_ADDR'];
		} else {
			$ip = '';
		}

		if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {
			$url = 'http://' . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
		} else {
			$url = '';
		}

		if (isset($this->request->server['HTTP_REFERER'])) {
			$referer = $this->request->server['HTTP_REFERER'];
		} else {
			$referer = '';
		}

		$this->model_tool_activity->addOnline($ip, $this->user->getId(), $url, $referer);

		return $this->load->view('common/footer', $data);
	}
}
