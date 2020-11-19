<?php
class ControllerDashboardHistory extends Controller {
	public function index() {
		$this->load->language('dashboard/history');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_no_results'] = $this->language->get('text_no_results');

		$data['token'] = $this->session->data['token'];

		$data['histories'] = array();

		$this->load->model('report/history');

		$results = $this->model_report_history->getHistories();

		foreach ($results as $result) {
			$comment = vsprintf($this->language->get('text_' . $result['key']), json_decode($result['data'], true));

			$find = array(
				'customer_id='
			);

			$replace = array(
				$this->url->link('customer/customer/edit', 'token=' . $this->session->data['token'] . '&customer_id=', true)
			);

			$data['histories'][] = array(
				'comment'    => str_replace($find, $replace, $comment),
				'date_added' => date($this->language->get('datetime_format'), strtotime($result['date_added']))
			);
		}

		return $this->load->view('dashboard/history', $data);
	}
}
