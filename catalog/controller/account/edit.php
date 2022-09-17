<?php
class ControllerAccountEdit extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/edit', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/edit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('account/edit', '', true)
		);

		$language_items = [
			'heading_title',
			'text_basic_info',
			'text_placement',
			'entry_firstname',
			'entry_lastname',
			'entry_email',
			'entry_telephone',
			'entry_acc_no',
			'entry_date_start',
			'entry_customer_group',
			'entry_customer_department',
			'entry_location',
			'button_back',
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

		$data['firstname'] = $customer_info['firstname'];
		$data['lastname'] = $customer_info['lastname'];
		$data['email'] = $customer_info['email'];
		$data['telephone'] = $customer_info['telephone'];
		$data['acc_no'] = $customer_info['acc_no'];
		$data['date_start'] = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start']));
		$data['account_custom_field'] = json_decode($customer_info['custom_field'], true);

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		$this->load->model('account/customer_group');
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_info['customer_group_id']);
		$data['customer_group'] = $customer_group_info['name'];

		$this->load->model('account/customer_department');
		$customer_department_info = $this->model_account_customer_department->getCustomerDepartment($customer_info['customer_department_id']);
		$data['customer_department'] = $customer_department_info['name'];

		$this->load->model('account/location');
		$location_info = $this->model_account_location->getLocation($customer_info['location_id']);
		$data['location'] = $location_info['name'];

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/edit', $data));
	}
}