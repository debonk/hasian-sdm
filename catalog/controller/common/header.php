<?php 
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('extension/extension');

		$data['analytics'] = array();

		$analytics = $this->model_extension_extension->getExtensions('analytics');

		foreach ($analytics as $analytic) {
			if ($this->config->get($analytic['code'] . '_status')) {
				$data['analytics'][] = $this->load->controller('analytics/' . $analytic['code'], $this->config->get($analytic['code'] . '_status'));
			}
		}

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		//cek lagi semua script, styles, links yang dibutuhkan pd header
		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$language_items = [
			'text_account',
			'text_general',
			'text_payroll_basic',
			'text_payroll',
			'text_password',
			'text_schedule',
			'text_login',
			'text_logout',
			'text_category',
			'text_vacation'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if ($this->customer->isLogged()) {
			$data['text_menu'] = sprintf($this->language->get('text_logged'), $this->customer->getFirstName());
		} else {
			$data['text_menu'] = $this->language->get('text_account');
		}
		
		$data['logged'] = $this->customer->isLogged();

		$data['home'] = $this->url->link('common/home');
		$data['account'] = $this->url->link('account/account', '', true);
		$data['edit'] = $this->url->link('account/edit', '', true);
		$data['payroll_basic'] = $this->url->link('account/payroll_basic', '', true);
		$data['payroll'] = $this->url->link('account/payroll', '', true);
		$data['password'] = $this->url->link('account/password', '', true);
		$data['login'] = $this->url->link('account/login', '', true);
		$data['schedule'] = $this->url->link('account/schedule', '', true);
		$data['logout'] = $this->url->link('account/logout', '', true);
		$data['vacation'] = $this->url->link('account/vacation', '', true);

		// Menu
		$this->load->model('catalog/category');

		// $this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$children_data[] = array(
						'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['information_id'])) {
				$class = '-' . $this->request->get['information_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}

		// if (file_exists(DIR_TEMPLATE . $this->config->get('theme_default_directory') . '/template/common/'.$headerlayout.'.tpl')) {
			// $header = $headerlayout;
		// } else { 
			$header = "header";
		// }

		return $this->load->view('common/'.$header, $data);
	}
}
