<?php
class ControllerAccountVacation extends Controller
{
	public function index()
	{
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/vacation', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/vacation');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/absence');

		$language_items = [
			'heading_title',
			'text_vacation',
			'button_back'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

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
			'href'      => $this->url->link('account/vacation', '', true)
		);

		$data['vacations'] = [];

		$results = $this->model_account_absence->getVacations($this->customer->getId());

		foreach ($results as $result) {
			if ($result['note']) {
				$result['description'] .= '. ' . $result['note'];
			}
			
			$data['vacations'][] = array(
				'date'				=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'description'		=> $result['description']
			);
		}

		$active_time = date_diff(date_create($this->customer->getDateStart()), date_create());

		$vacation_limit = !$active_time->y ? 0 : $this->config->get('payroll_setting_vacation_limit');
		$vacation_count = !$vacation_limit ? 0 : $vacation_limit - $this->model_account_absence->getVacationsCount($result['customer_id']);

		$data['vacation_count']	= sprintf($this->language->get('text_vacation_count'), $vacation_count, $vacation_limit);
		
		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/vacation', $data));
	}
}
