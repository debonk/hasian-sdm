<?php
class ControllerAccountEdit extends Controller
{
	public function index()
	{
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
			'text_contract',
			'text_placement',
			'entry_firstname',
			'entry_lastname',
			'entry_email',
			'entry_telephone',
			'entry_acc_no',
			'entry_contract_status',
			'entry_contract_type',
			'entry_customer_group',
			'entry_customer_department',
			'entry_date_end',
			'entry_date_start',
			'entry_location',
			'button_back',
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());

		$field_items = array(
			'firstname',
			'lastname',
			'email',
			'telephone',
			'acc_no',
			'customer_group',
			'customer_department',
			'location',
		);
		foreach ($field_items as $field) {
			$data[$field] = $customer_info[$field];
		}

		$data['date_start'] = date($this->language->get('date_format_jMY'), strtotime($customer_info['date_start']));

		$contract_info = $this->model_account_customer->getCustomerContract($this->customer->getId());

		switch ($contract_info['contract_status']) {
			case 'expired':
				$contract_status = $contract_info['end_reason'];

				break;

			case 'end_soon':
				$contract_end_left = date_diff(date_create($contract_info['contract_end']), date_create(date('Y-m-d')))->days;

				$contract_status = sprintf($this->language->get('text_contract_end_left'), $contract_end_left);

				break;

			default:
				$contract_status = $this->language->get('text_contract_' . $contract_info['contract_status']);

				break;
		}

		if ($contract_info['contract_type']) {
			$contract_type = $contract_info['contract_type'];
		} else {
			$contract_type = '';
		}

		$data['contract_type'] = $contract_type;
		$data['contract_status'] = $contract_status;
		$data['date_end'] = !$customer_info['date_end'] ? '-' : date($this->language->get('date_format_jMY'), strtotime($customer_info['date_end']));

		// Custom Fields
		$data['account_custom_field'] = json_decode($customer_info['custom_field'], true);

		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

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
