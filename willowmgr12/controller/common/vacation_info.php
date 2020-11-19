<?php
class ControllerCommonVacationInfo extends Controller {
	public function index() {
		$this->load->language('common/vacation_info');

		$this->load->model('presence/absence');

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['year'])) {
			$year = $this->request->get['year'];
		} else {
			$year = date('Y');
		}

		$language_items = array(
			'text_list',
			'text_loading',
			'text_no_results',
			'column_date',
			'column_description'
		);
		foreach ($language_items as $item) {
			$data[$item] = $this->language->get($item);
		}
		
		$data['vacations'] = array();

		$results = $this->model_presence_absence->getVacations($customer_id, $year);
		
		foreach ($results as $result) {
			if ($result['note']) {
				$result['description'] .= '. ' . $result['note'];
			}
			
			$data['vacations'][] = array(
				'absence_id'		=> $result['absence_id'],
				'date'				=> date($this->language->get('date_format_jMY'), strtotime($result['date'])),
				'description'		=> $result['description']
			);
			
		}
		
		$this->response->setOutput($this->load->view('common/vacation_info', $data));
	}
}