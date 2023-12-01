<?php
class ControllerCommonFooter extends Controller {
	public function index() {
		$this->load->language('common/footer');

		$data['scripts'] = $this->document->getScripts('footer');

		$language_items = array(
			'text_information',
			'text_service',
			'text_contact',
			'text_sitemap',
			'text_presence_log',
			'text_account',
			'text_schedule',
			'text_newsletter'
		);
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		
		// $data['text_blogs'] = $this->language->get('text_blogs');//Bonk
		// $data['link_instagram'] = $this->language->get('link_instagram');
		// $data['link_facebook'] = $this->language->get('link_facebook');
		// $data['link_twitter'] = $this->language->get('link_twitter');

		$this->load->model('catalog/information');

		$data['informations'] = array();

		foreach ($this->model_catalog_information->getInformations() as $result) {
			if ($result['bottom']) {
				$data['informations'][] = array(
					'title' => $result['title'],
					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])
				);
			}
		}

		$data['contact'] = $this->url->link('information/contact');
		$data['return'] = $this->url->link('account/return/add', '', true);
		$data['presence_log'] = $this->url->link('presence/login');
		// $data['sitemap'] = $this->url->link('information/sitemap');
		// $data['manufacturer'] = $this->url->link('product/manufacturer');
		// $data['voucher'] = $this->url->link('account/voucher', '', true);
		// $data['account'] = $this->url->link('account/account', '', true);
		$data['account'] = '#';
		$data['schedule'] = '#';
		$data['newsletter'] = '#';
		$data['sitemap'] = '#';
		// $data['wishlist'] = $this->url->link('account/wishlist', '', true);
		// $data['newsletter'] = $this->url->link('account/newsletter', '', true);
		// $data['career'] = $this->url->link('information/career');//Bonk

		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));

		// Whos Online
		if ($this->config->get('config_customer_online')) {
			$this->load->model('tool/online');

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

			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);
		}

		return $this->load->view('common/footer', $data);
	}
}
