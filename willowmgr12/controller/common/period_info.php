<?php
class ControllerCommonPeriodInfo extends Controller
{
	public function index()
	{
		$this->load->language('common/period_info');

		$this->load->model('common/payroll');

		$presence_period_id = isset($this->request->get['presence_period_id']) ? $this->request->get['presence_period_id'] : 0;

		$no_shortcut = isset($this->request->get['no_shortcut']) ? $this->request->get['no_shortcut'] : '';

		$language_items = [
			'text_period_info',
			'text_period',
			'text_date_period',
			'text_payroll_status',
			'text_no_results'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		//Text Period
		$period_info = $this->model_common_payroll->getPeriod($presence_period_id);

		if ($period_info) {
			$data['period'] = date($this->language->get('date_format_m_y'), strtotime($period_info['period']));
			$data['date_start'] = date($this->language->get('date_format_jMY'), strtotime($period_info['date_start']));
			$data['date_end'] = date($this->language->get('date_format_jMY'), strtotime($period_info['date_end']));
			$data['payroll_status'] = $period_info['payroll_status'];
		}

		$data['period_info_check'] = $period_info;

		$data['shortcuts'] = [];

		if (!$no_shortcut) {
			$referrer = parse_url($this->request->server['HTTP_REFERER']);
			parse_str(htmlspecialchars_decode($referrer['query']), $params);

			unset($params['presence_period_id']);

			$url = '';

			foreach ($params as $key => $param) {
				$url .= '&' . $key . '=' . $param;
			}

			$url = htmlentities(str_replace('&route=', '', $url));

			$periods = [];

			$period_data = $this->model_common_payroll->getLatestPeriod();

			$periods[$period_data['presence_period_id']] = $period_data;

			$period_data = $this->model_common_payroll->getPeriod();

			$periods[$period_data['presence_period_id']] = $period_data;

			$periods_count = count($periods);

			$i = 1;
			while ($periods_count < 4 && $i < $period_info['presence_period_id']) {
				$period_data = $this->model_common_payroll->getPeriod($period_info['presence_period_id'] - $i);

				if ($period_data && !isset($periods[$period_data['presence_period_id']])) {
					$periods[$period_data['presence_period_id']] = $period_data;

					$periods_count++;
				}

				$i++;
			}

			ksort($periods);

			foreach ($periods as $period) {
				$data['shortcuts'][] = [
					'period'	=> date($this->language->get('date_format_m_y'), strtotime($period['period'])),
					'href'		=> $this->url->link($url, 'presence_period_id=' . $period['presence_period_id'], true)
				];
			}
		}

		$this->response->setOutput($this->load->view('common/period_info', $data));
	}
}
