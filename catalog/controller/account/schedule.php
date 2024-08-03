<?php
class ControllerAccountSchedule extends Controller
{
	public function index()
	{
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/schedule', '', true);

			$this->response->redirect($this->url->link('account/login', '', true));
		}

		$this->load->language('account/schedule');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/schedule', '', true)
		);

		$this->load->model('account/payroll');

		$language_items = [
			'heading_title',
			'text_no_results',
			'button_back'
		];
		foreach ($language_items as $language_item) {
			$data[$language_item] = $this->language->get($language_item);
		}

		if (!isset($this->request->get['last_period'])) {
			$period_info = $this->model_account_payroll->getCurrentPeriod();
		} else {
			$period_info = $this->model_account_payroll->getPeriodByDate(date('Y-m-d', strtotime('last month')));
		}

		$data['calendar'] = [];

		if ($period_info && !$this->model_account_payroll->checkPeriodStatus($period_info['presence_period_id'], 'completed')) {
			$data['text_period'] = sprintf($this->language->get('text_period'), date($this->language->get('date_format_m_y'), strtotime($period_info['period'])));

			$range_date = array(
				'start'	=> $period_info['date_start'],
				'end'	=> $period_info['date_end']
			);

			$this->load->model('account/schedule');
			$this->load->model('account/customer');

			$schedules_data = $this->model_account_schedule->getFinalSchedules($period_info['presence_period_id'], $this->customer->getId(), $range_date);

			$data['list_days'] = explode(' ', $this->language->get('text_days'));

			$date_diff = date_diff(date_create($period_info['date_start']), date_create($period_info['date_end']));
			$date_start = strtotime($period_info['date_start']);

			$week_day_start = date('w', $date_start);

			$days_in_month = $date_diff->format('%a');

			$data['total_week'] = ceil(($days_in_month + $week_day_start + 1) / 7);

			$counter = -$week_day_start;

			for ($week = 0; $week < $data['total_week']; $week++) {
				for ($day = 0; $day < 7; $day++) {
					if ($counter >= 0 && $counter <= $days_in_month) {
						$key_date = date('Y-m-d', strtotime('+' . $counter . ' day', $date_start));

						$schedule_type_code = '-';
						$presence_status = '-';
						$time_login = '';
						$time_logout = '';

						if (!empty($schedules_data[$key_date])) {
							$schedule_type_id = $schedules_data[$key_date]['schedule_type_id'];
							$schedule_type_code = $schedules_data[$key_date]['schedule_type'] . ($schedules_data[$key_date]['time_in'] != '0000-00-00 00:00:00' ? '<br>(' . date('H:i', strtotime($schedules_data[$key_date]['time_in'])) . '-' . date('H:i', strtotime($schedules_data[$key_date]['time_out'])) . ')' : $this->language->get('text_off') . '<br><br>');
							$presence_status = $schedules_data[$key_date]['presence_status'];
							$time_login = ($schedules_data[$key_date]['time_login'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_data[$key_date]['time_login'])) : '...';
							$time_logout = ($schedules_data[$key_date]['time_logout'] != '0000-00-00 00:00:00') ? date('H:i', strtotime($schedules_data[$key_date]['time_logout'])) : '...';
							$bg_class = !empty($schedules_data[$key_date]['bg_class']) ? $schedules_data[$key_date]['bg_class'] : 'info';
						} else {
							$schedule_type_id = 0;
							$bg_class = 'warning';
						}

						$data['calendar'][$week . $day] = array(
							'date'					=> $key_date,
							'text'					=> date('j M', strtotime($key_date)),
							'schedule_type_id' 		=> $schedule_type_id,
							'schedule_type_code'	=> $schedule_type_code,
							'presence_status'		=> $presence_status,
							'time_login'			=> $time_login,
							'time_logout'			=> $time_logout,
							'bg_class'				=> $bg_class
						);
					}
					$counter++;
				}
			}
		}

		if (!isset($this->request->get['last_period'])) {
			$data['schedule'] = [
				'title'	=> $this->language->get('button_period_last'),
				'href'	=> $this->url->link('account/schedule', 'last_period=true', true)
			];
		} else {
			$data['schedule'] = [
				'title'	=> $this->language->get('button_period_current'),
				'href'	=> $this->url->link('account/schedule', '', true)
			];
		}

		$data['back'] = $this->url->link('account/account', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('account/schedule', $data));
	}
}
