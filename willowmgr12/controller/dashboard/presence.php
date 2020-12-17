<?php
class ControllerDashboardPresence extends Controller {
	public function index() {
		$this->load->language('dashboard/presence');

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_view'] = $this->language->get('text_view');

		$data['token'] = $this->session->data['token'];

		$this->load->model('report/presence');

		$period_y_m = date('Y') . '-' . (date('n') - 1);
		$last_month = $this->model_report_presence->getPercentPresenceByPeriod($period_y_m);

		if ($last_month['period']) {
			$last_month_presence = $last_month['sum_presence'] * 100 / ($last_month['sum_presence'] + $last_month['sum_absence']);
		} else {
			$last_month_presence = 0;
		}

		$period_y_m = date('Y') . '-' . (date('n') - 2);
		$last_2_month =$this->model_report_presence->getPercentPresenceByPeriod($period_y_m);

		if ($last_2_month['period'] && ($last_2_month['sum_presence'] + $last_2_month['sum_absence'] > 0)) {
			$last_2_month_presence = $last_2_month['sum_presence'] * 100/ ($last_2_month['sum_presence'] + $last_2_month['sum_absence']);
		} else {
			$last_2_month_presence = 0;
		}

		$difference = $last_month_presence - $last_2_month_presence;

		if ($difference && $last_month_presence) {
			$data['percentage'] = round(($difference / $last_month_presence) * 100);
		} else {
			$data['percentage'] = 0;
		}

		if ($last_month_presence) {
			$data['total'] = round($last_month_presence, 2) . '%';
		} else {
			$data['total'] = '0%';
		}

		$data['presence'] = $this->url->link('presence/presence_period', 'token=' . $this->session->data['token'], true);

		return $this->load->view('dashboard/presence', $data);
	}
}